<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin
    
    require_once("../../config.php");
    require_once("lib.php");
    
    $id        = required_param('id', PARAM_INT); 
    $uid       = required_param('uid', PARAM_INT); 

    if (!$cm = get_coursemodule_from_id('reader', $id)) {
        print_error('invalidcoursemodule');
    }
    if (!$course = $DB->get_record('course', array('id' => $cm->course))) {
        print_error("coursemisconf");
    }
    if (!$reader = $DB->get_record('reader', array('id' => $cm->instance))) {
        print_error('invalidcoursemodule');
    }

    require_login($course, true, $cm);

    add_to_log($course->id, "reader", "show incorrect quizzes", "showincorrectquizzes.php?id=$id", "$cm->instance");
    
    $bookincorrectinprevterm = '';
    
    if ($oldattempts = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE userid= ? and timefinish <= ? ORDER BY timefinish", array($uid,$reader->ignordate))) {
        foreach ($oldattempts as $oldattempt) {
            if (strtolower($oldattempt->passed) != 'true') {
                $bookdata = $DB->get_record("reader_publisher", array( "id" => $oldattempt->quizid));
                $bookincorrectinprevterm .= "{$bookdata->name}<br />";
            }
        }
    }
    
    if ($bookincorrectinprevterm) {
        echo $bookincorrectinprevterm;
    } else {
        print_string("noincorrectquizzes", "reader");
    }
    
