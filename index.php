<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin

    require_once("../../config.php");
    require_once("lib.php");

    $id        = required_param('id', PARAM_INT); 
    
    if (!$course = $DB->get_record("course", array( "id" => $id))) {
        error("Course ID is incorrect");
    }
    
    require_course_login($course);
    
    add_to_log($course->id, "Reader", "view all", "index.php?id=$course->id", "");
    
    $PAGE->set_url('/mod/reader/index.php', array('id' => $id));
    
    $title = $course->shortname;
    $PAGE->set_title($title);
    $PAGE->set_heading($course->fullname);
    
    if (! $displays = get_all_instances_in_course("reader", $course)) {
        notice("There are no displays", new moodle_url("/course/view.php", array("id" => $course->id)));
        die;
    }
    
    echo $OUTPUT->header();
    
    echo html_writer::empty_tag('br');
    
    echo $OUTPUT->box_start('generalbox');

    foreach ($displays as $display) {
        echo html_writer::link(new moodle_url('/mod/reader/view.php', array('id'=>$display->coursemodule)), $display->name);
        echo html_writer::empty_tag('br');
    }

    echo $OUTPUT->box_end();

    echo html_writer::empty_tag('br');

    echo $OUTPUT->footer();

