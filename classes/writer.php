<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Markdown data format writer
 *
 * @package    dataformat_markdown
 * @copyright  2016 Lafayette College ITS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace dataformat_markdown;

/**
 * Markdown data format writer
 *
 * @package    dataformat_markdown
 * @copyright  2016 Lafayette College ITS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class writer extends \core\dataformat\base {

    /** @var FLUSH_LENGTH Pre-process this many records. */
    const FLUSH_LENGTH = 100;

    /** @var $mimetype The mimetype for the outputted file. */
    public $mimetype = "text/markdown";

    /** @var $extension The file extension for the outputted file. */
    public $extension = ".md";

    /** @var $columns Column headings for the data. */
    private $columns = [];

    /** @var $columnlength Stores the maximum found record length of each column. */
    private $columnlength = [];

    /** @var $flushed Whether the the preprocessed records have been flushed. */
    private $flushed = 0;

    /** @var $records Stores the records until they're flushed. */
    private $records = [];

    /**
     * Write the start of the format. The records will be flushed after column length is calculated.
     *
     * @param array $columns
     */
    public function write_header($columns) {
        $this->columns = $columns;
        foreach ($columns as $key => $column) {
            $this->columnlength[$key] = \core_text::strlen($column);
        }
    }

    /**
     * Write a single record. The records will be flushed after column length is calculated.
     *
     * @param array $record
     * @param int $rownum
     */
    public function write_record($record, $rownum) {
        if (!$this->flushed && $rownum == self::FLUSH_LENGTH) {
            $this->flush();
        }
        if (!$this->flushed && $rownum < self::FLUSH_LENGTH) {
            // Calculate column widths.
            foreach ($record as $key => $value) {
                $length = \core_text::strlen($value);
                if ($length > $this->columnlength[$key]) {
                    $this->columnlength[$key] = $length;
                }
            }
            $this->records[] = $record;
        } else {
            $this->print_record($record);
        }
    }

    /**
     * If the number of records was fewer than FLUSH_LENGTH, flush.
     *
     * @param array $columns (unused)
     */
    public function write_footer($columns) {
        if (!$this->flushed) {
            $this->flush();
        }
    }

    /**
     * Write the column and pre-processed records.
     */
    private function flush() {
        $this->print_record($this->columns);
        $separators = [];
        foreach ($this->columnlength as $key => $length) {
            $separators[$key] = str_pad('', $length, '-');
        }
        $this->print_record($separators);
        foreach ($this->records as $record) {
            $this->print_record($record);
        }
        $this->flushed = 1;
    }

    /**
     * Actually output a record.
     *
     * @param array $record
     */
    private function print_record($record) {
        $values = [];
        foreach ($this->columnlength as $key => $length) {
            $values[] = str_pad($this->sanitize_record($record[$key]), $length);
        }
        echo implode(' | ', $values) . "\n";
    }

    /**
     * Remove line breaks from a record.
     *
     * @param string $record
     * @return string
     */
    private function sanitize_record($record) {
        return preg_replace('~[[:cntrl:]]~', '', $record);
    }
}
