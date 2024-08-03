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
 * TODO describe file export
 *
 * @package    local_blocksi
 * @copyright  2024 Gonzalo Cañada
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/group/lib.php');

$courseid = required_param('id', PARAM_INT); // Course id.
$groupid = required_param('group', PARAM_INT); // Group id.

$url = new moodle_url('/local/blocksi/export.php', [
	'id' => $courseid,
	'group' => $groupid,
]);
$PAGE->set_url($url);

// SECURITY
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
$group = groups_get_group($groupid, '*', MUST_EXIST);
require_login($course);
$context = context_course::instance($course->id);
$PAGE->set_context($context);
require_capability('local/blocksi:view', $context);

$students = groups_get_members_by_role($group->id, $course->id, 'u.email', extrawheretest: "r.shortname = 'student'");
if (!empty($students)) {
	$students = array_values($students);
	$students = $students[0]->users;
} else {
	$students = [];
}

$emails = array_column($students, 'email');
$firstEmail = array_shift($emails);

\core\dataformat::download_data(
	$group->name,
	'csv',
	[$firstEmail],
	new ArrayIterator($emails),
);
