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
 * TODO describe file index
 *
 * @package    local_blocksi
 * @copyright  2024 Gonzalo Cañada
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$courseid = required_param('id', PARAM_INT); // Course id.

$PAGE->set_pagelayout('incourse');

$url = new moodle_url('/local/blocksi/index.php', ['id' => $courseid]);
$PAGE->set_url($url);

// SECURITY
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
require_login($course);
$context = context_course::instance($course->id);
require_capability('local/blocksi:view', $context);

$PAGE->set_title($course->shortname .': '. get_string('menuitem', 'local_blocksi'));
$PAGE->add_body_class('limitedwidth');
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();

$groupsraw = groups_get_all_groups($courseid, withmembers: true);
$groups = [];
if (!empty($groupsraw)) {
	foreach ($groupsraw as $group) {
			$students = groups_get_members_by_role($group->id, $group->courseid, extrawheretest: "r.shortname = 'student'");
			
			if (!empty($students)) {
				$students = array_values($students);
				$students = $students[0]->users;
			}

			$studentcount = empty($students) ? 0 : count($students);

			if ($studentcount > 0) {
				$groups[] = [
					'id' => $group->id,
					'name' => $group->name,
					'studentcount' => empty($students) ? 0 : count($students),
					'url' => new moodle_url('/local/blocksi/export.php', [
						'id' => $courseid,
						'group' => $group->id,
					]),
				];
			}			
	}
}

echo $OUTPUT->render_from_template('local_blocksi/list', [
	'groups' => $groups,
	'hasgroups' => !empty($groups),
]);

echo $OUTPUT->footer();
