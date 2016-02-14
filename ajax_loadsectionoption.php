<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin

    require_once("../../config.php");
 

    $id = required_param('id', PARAM_INT);
    
    if (!$course = $DB->get_record("course", array( "id" => $id))) {
        error("Course ID is incorrect");
    }
    
    require_course_login($course);
    
    $currentcoursesections = $DB->get_records("course_sections", array("course" => $id));
    
    $t = 0;
    
    foreach ($currentcoursesections as $currentcoursesections_) {
        if ($t <= $course->numsections) {
            $sectionname = strip_tags(trim(str_replace(array("\r", "\n"), "", $currentcoursesections_->summary)));
            
            if (empty($sectionname)) $sectionname = 'Section '.$currentcoursesections_->section;
            if (strlen($sectionname) > 25) $sectionname = substr($sectionname, 0, 25)."...";
            
            echo html_writer::tag('option', $sectionname, array('value'=>$currentcoursesections_->section));
        }
        $t++;
    }
