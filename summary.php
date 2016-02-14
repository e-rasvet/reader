<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin
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
 * This page prints a summary of a quiz attempt before it is submitted.
 *
 * @package    mod
 * @subpackage quiz
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


    require_once(dirname(__FILE__) . '/../../config.php');
    require_once($CFG->dirroot . '/mod/reader/lib.php');
    require_once($CFG->dirroot . '/mod/reader/accessrules.php');
    require_once($CFG->dirroot . '/mod/reader/attemptlib.php');

    $attemptid = required_param('attempt', PARAM_INT); // The attempt to summarise.

    $PAGE->set_url('/mod/reader/summary.php', array('attempt' => $attemptid));

    $attemptobj = reader_attempt::create($attemptid);

    // Check login.
    require_login($attemptobj->get_course(), false, $attemptobj->get_cm());

    // If this is not our own attempt, display an error.
    if ($attemptobj->get_userid() != $USER->id) {
        print_error('notyourattempt', 'quiz', $attemptobj->view_url());
    }

    // If the attempt is already closed, redirect them to the review page.
    if ($attemptobj->is_finished()) {
        redirect($attemptobj->review_url());
    }

    // Check access.
    $accessmanager = $attemptobj->get_access_manager(time());
    $messages = $accessmanager->prevent_access();
    $output = $PAGE->get_renderer('mod_quiz');

    $accessmanager->do_password_check($attemptobj->is_preview_user());

    $displayoptions = $attemptobj->get_display_options(false);

    // Print the page header
    if (empty($attemptobj->get_quiz()->showblocks)) {
        $PAGE->blocks->show_only_fake_blocks();
    }

    $title = get_string ("likebook", "reader");
    if ($accessmanager->securewindow_required($attemptobj->is_preview_user())) {
        $accessmanager->setup_secure_page($attemptobj->get_course()->shortname . ': ' .
                format_string($attemptobj->get_quiz_name()), '');
    } else if ($accessmanager->safebrowser_required($attemptobj->is_preview_user())) {
        $PAGE->set_title($attemptobj->get_course()->shortname . ': ' .
                format_string($attemptobj->get_quiz_name()));
        $PAGE->set_heading($attemptobj->get_course()->fullname);
        $PAGE->set_cacheable(false);
        echo $OUTPUT->header();
    } else {
        $PAGE->navbar->add($title);
        $PAGE->set_title(format_string($attemptobj->get_reader_name()));
        $PAGE->set_heading($attemptobj->get_course()->fullname);
        echo $OUTPUT->header();
    }

    // Print heading.
    echo $OUTPUT->heading(format_string($attemptobj->get_reader_name()));
    echo $OUTPUT->heading($title, 3);

    echo $OUTPUT->box_start('generalbox');

    echo get_string ("likebook", "reader");

    $o  = '';

    $o .= html_writer::empty_tag('br');
    $o .= html_writer::empty_tag('br');
    $o .= html_writer::start_tag('form', array('action'=>new moodle_url('/mod/reader/processattempt.php'), 'method'=>'post'));

    $o .= html_writer::empty_tag('input', array('type'=>'radio', 'name'=>'likebook', 'value'=>3, 'id'=>'like-3'));
    $o .= html_writer::tag('label', get_string('itwasgreat', 'reader'), array('for'=>'like-3', 'style'=>'cursor:pointer;'));
    $o .= html_writer::empty_tag('br');

    $o .= html_writer::empty_tag('input', array('type'=>'radio', 'name'=>'likebook', 'value'=>2, 'id'=>'like-2'));
    $o .= html_writer::tag('label', get_string('itwasokay', 'reader'), array('for'=>'like-2', 'style'=>'cursor:pointer;'));
    $o .= html_writer::empty_tag('br');

    $o .= html_writer::empty_tag('input', array('type'=>'radio', 'name'=>'likebook', 'value'=>1, 'id'=>'like-1'));
    $o .= html_writer::tag('label', get_string('itwasso', 'reader'), array('for'=>'like-1', 'style'=>'cursor:pointer;'));
    $o .= html_writer::empty_tag('br');

    $o .= html_writer::empty_tag('input', array('type'=>'radio', 'name'=>'likebook', 'value'=>0, 'id'=>'like-0'));
    $o .= html_writer::tag('label', get_string('ididntlikeitatall', 'reader'), array('for'=>'like-0', 'style'=>'cursor:pointer;'));
    $o .= html_writer::empty_tag('br');

    $o .= html_writer::empty_tag('input', array('name'=>'attempt', 'value'=>$attemptid, 'type'=>'hidden'));
    $o .= html_writer::empty_tag('input', array('name'=>'finishattempt', 'value'=>1, 'type'=>'hidden'));
    $o .= html_writer::empty_tag('input', array('name'=>'timeup', 'value'=>0, 'type'=>'hidden'));
    $o .= html_writer::empty_tag('input', array('name'=>'slots', 'value'=>'', 'type'=>'hidden'));
    $o .= html_writer::empty_tag('input', array('name'=>'sesskey', 'value'=>sesskey(), 'type'=>'hidden'));

    $o .= html_writer::start_tag('center');
    $o .= html_writer::empty_tag('input', array('value'=>'Ok', 'type'=>'submit'));
    $o .= html_writer::end_tag('center');
    $o .= html_writer::end_tag('form');

    echo $o;

    echo $OUTPUT->box_end();

    // Finish the page
    $accessmanager->show_attempt_timer_if_needed($attemptobj->get_attempt(), time());
    echo $OUTPUT->footer();
