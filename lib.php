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
 * Callback implementations for Blocksi
 *
 * @package    local_blocksi
 * @copyright  2024 Gonzalo Cañada
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 function local_blocksi_extend_navigation_course(navigation_node $parentnode, stdClass $course,	context_course $context)
 {
	if (!has_capability('local/blocksi:view', $context)) {
		return;
	}
	
	$parentnode->add(
		get_string('menuitem', 'local_blocksi'),
		new moodle_url('/local/blocksi/index.php', ['id' => $course->id]),
		navigation_node::TYPE_CUSTOM
	);
 }