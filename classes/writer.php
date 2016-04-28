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
 * @copyright  Charles Fulton (fultonc@lafayette.edu)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace dataformat_markdown;

defined('MOODLE_INTERNAL') || die();

/**
 * Markdown data format writer
 *
 * @package    dataformat_markdown
 * @copyright  2016 Charles Fulton (fultonc@lafayette.edu)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class writer extends \core\dataformat\base {

    /** @var $mimetype */
    public $mimetype = "text/markdown";

    /** @var $extension */
    public $extension = ".md";

    /**
     * Write the start of the format
     *
     * @param array $columns
     */
    public function write_header($columns) {
        $this->write_record($columns, -1);
        $this->write_record(array_fill(0, count($columns), '---'), -1);
    }

    /**
     * Write a single record
     *
     * @param array $record
     * @param int $rownum
     */
    public function write_record($record, $rownum) {
        echo '|' . $this->sanitize_record(implode('|', $record)) . '|' . "\n";
    }

    /**
     * Remove line breaks from a record
     *
     * @param string $record
     * @return string
     */
    private function sanitize_record($record) {
        return preg_replace('~[[:cntrl:]]~', '', $record);
    }
}
