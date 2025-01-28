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
 * Basic unit tests for dataformat_markdown.
 *
 * @package    dataformat_markdown
 * @copyright  2017 Lafayette College ITS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace dataformat_markdown;

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot.'/lib/classes/dataformat.php');

/**
 * Basic unit tests for dataformat_markdown.
 *
 * @package    dataformat_markdown
 * @copyright  2017 Lafayette College ITS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class export_test extends \basic_testcase {
    public function test_export() {
        $fields = array('fruit', 'color');
        $records = array(
            array('Apple', 'red'),
            array('Banana', 'yellow'),
            array('Orange', 'orange')
        );
        $downloadrecords = new \ArrayObject($records);
        $iterator = $downloadrecords->getIterator();

        // Verify export.
        $expected = "fruit  | color \n------ | ------\nApple  | red   \nBanana | yellow\nOrange | orange\n";
        $format = new writer();
        ob_start();
        $c = 0;
        $format->write_header($fields);
        foreach ($iterator as $row) {
            $format->write_record($row, $c++);
        }
        $format->write_footer($fields);
        $output = ob_get_clean();
        $this->assertEquals($expected, $output);
    }
}
