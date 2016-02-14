<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin

    require_once("../../config.php");
    require_once("lib.php");

    $b                     = optional_param('b', 0, PARAM_INT); 


    if (!empty($b)) {
        if ($data = $DB->get_records("reader_attempts", array("quizid" => $b))) {
            if ($datapub = $DB->get_record("reader_publisher", array("id" => $b))) {
                $quizid = $datapub->quizid;
            }
            
            while(list($key,$value)=each($data)){
                reader_put_to_quiz_attempt($value->id);
            }
        }
    }

    if (!empty($quizid)) {
        if ($cm = get_coursemodule_from_instance("quiz", $quizid)) {
            $site_url = new moodle_url('/mod/quiz/report.php', array("id" => $cm->id, "mode" => "responses"));
            echo html_writer::script("top.location.href='{$site_url}'");
        }
    } else {
        echo html_writer::tag('h1', 'No attempts found');
    }
  