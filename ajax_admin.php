<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin

    require_once("../../config.php");
 
    $id                     = required_param('id', PARAM_INT); 
    $a                      = optional_param('a', NULL, PARAM_CLEAN); 
    $act                    = optional_param('act', NULL, PARAM_CLEAN); 
    $f                      = optional_param('f', NULL, PARAM_TEXT); 
    $promotionstop          = optional_param('promotionstop', NULL, PARAM_INT); 
    $setgoal                = optional_param('setgoal', NULL, PARAM_INT); 
    $nopromote              = optional_param('nopromote', NULL, PARAM_INT); 
    $userid                 = optional_param('userid', NULL, PARAM_INT); 
    $startlevel             = optional_param('startlevel', NULL, PARAM_INT); 
    $currentlevel           = optional_param('currentlevel', NULL, PARAM_INT); 
    $length                 = optional_param('length', NULL, PARAM_CLEAN); 
    $bookid                 = optional_param('bookid', NULL, PARAM_INT); 
    $difficulty             = optional_param('difficulty', NULL, PARAM_INT); 
    $words                  = optional_param('words', NULL, PARAM_INT); 
    $publishertitle         = optional_param('publishertitle', NULL, PARAM_TEXT); 
    $publisher              = optional_param('publisher', NULL, PARAM_TEXT); 
    $level                  = optional_param('level', NULL, PARAM_TEXT); 
    $masspublisherfrom      = optional_param('masspublisherfrom', NULL, PARAM_TEXT); 
    $masspublisherto        = optional_param('masspublisherto', NULL, PARAM_TEXT); 
    $masslevelto            = optional_param('masslevelto', NULL, PARAM_TEXT); 
    $masslevelfrom          = optional_param('masslevelfrom', NULL, PARAM_TEXT); 
    $masslengthfrom         = optional_param('masslengthfrom', NULL, PARAM_TEXT); 
    $masslengthto           = optional_param('masslengthto', NULL, PARAM_TEXT); 
    $massdifficultyto       = optional_param('massdifficultyto', NULL, PARAM_TEXT); 
    $massdifficultyfrom     = optional_param('massdifficultyfrom', NULL, PARAM_TEXT); 
    
    
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


    add_to_log($course->id, "reader", "admin ajax call", "ajax_admin.php?id=$id", "$cm->instance");
    
    $contextmodule = get_context_instance(CONTEXT_MODULE, $cm->id);
    
/* 
* PROMOTION STOP
*/

    if (has_capability('mod/reader:manage', $contextmodule) && $f == 'promotionstop') {
        if ($studentlevel = $DB->get_record("reader_levels", array("userid" => $userid, "readerid" => $reader->id))) {
            $DB->set_field("reader_levels",  'promotionstop',  $promotionstop, array( "userid" => $userid,  "readerid" => $reader->id));
        }
        
        add_to_log($course->id, "reader", substr("AA-Student Promotion Stop Changed ({$userid} set to {$promotionstop})",0,39), "admin.php?id=$id", "$cm->instance");
        
        $studentlevel->promotionstop = $promotionstop;

        //echo reader_promotion_stop_box ($userid, $studentlevel);
        echo $promotionstop;
        die();
    }
    
/* 
* SET GOAL
*/
    
    if (has_capability('mod/reader:manage', $contextmodule) && $f == 'setgoal') {
        if ($data = $DB->get_record("reader_levels", array("userid" => $userid, "readerid" => $reader->id))) {
            $DB->set_field("reader_levels",  "goal",  $setgoal, array( "id" => $data->id));
        } else {
            $data                 = new stdClass;
            $data->userid         = $userid;
            $data->startlevel     = 0;
            $data->currentlevel   = 0;
            $data->readerid       = $reader->id;
            $data->goal           = $setgoal;
            $data->time           = time();
            
            $DB->insert_record("reader_levels", $data);
        }
        
        add_to_log($course->id, "reader", "AA-Change Student Goal ({$setgoal})", "admin.php?id=$id", "$cm->instance");
        
        $data->goal = $setgoal;
        
        //echo reader_goal_box ($userid, $data, $reader);
        echo $setgoal;
        die();
    }
    
    
/* 
* SET NO PROMOTE
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $f == 'nopromote') {
        if ($studentlevel = $DB->get_record("reader_levels", array("userid" => $userid, "readerid" => $reader->id))) {
            $DB->set_field("reader_levels",  'nopromote',  $nopromote, array( "userid" => $userid,  "readerid" => $reader->id));
        }
        
        add_to_log($course->id, "reader", substr("AA-Student NoPromote Changed ({$userid} set to {$nopromote})",0,39), "admin.php?id=$id", "$cm->instance");
        
        $studentlevel->nopromote = $nopromote;
        
        if ($nopromote == 0)
            echo "Promo";
        else
            echo "NoPromo";
        //echo reader_yes_no_box ($userid, $studentlevel);
        die();
    }
    
/* 
* SET START LEVEL
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $f == 'startlevel') {
        if ($studentlevel = $DB->get_record("reader_levels", array("userid" => $userid, "readerid" => $reader->id))) {
            $DB->set_field("reader_levels", "startlevel",  $startlevel, array( "userid" => $userid,  "readerid" => $reader->id));
            $DB->set_field("reader_levels", 'time', time(), array("userid" => $userid, "readerid" => $reader->id));
        } else {
            $data                = new stdClass;
            $data->userid        = $userid;
            $data->startlevel    = $startlevel;
            $data->currentlevel  = $startlevel;
            $data->readerid      = $reader->id;
            $data->time          = time();
            
            $DB->insert_record("reader_levels", $data);
        }
        
        add_to_log($course->id, "reader", substr("AA-Student Level Changed ({$userid} {$studentlevel->startlevel} to {$startlevel})", 0, 39), "admin.php?id=$id", "$cm->instance");
        
        $studentlevel->startlevel = $startlevel;
        //echo reader_selectlevel_box ($userid, $studentlevel, $slevel);
        echo $startlevel;

        die();
    }
    
/* 
* SET CURRENT LEVEL
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $f == 'currentlevel') {
        if ($studentlevel = $DB->get_record("reader_levels", array("userid" => $userid, "readerid" => $reader->id))) {
            $DB->set_field("reader_levels", "currentlevel",  $currentlevel, array( "userid" => $userid,  "readerid" => $reader->id));
            $DB->set_field("reader_levels", 'time', time(), array("userid" => $userid, "readerid" => $reader->id));
        } else {
            $data                = new stdClass;
            $data->userid        = $userid;
            $data->startlevel    = $currentlevel;
            $data->currentlevel  = $currentlevel;
            $data->readerid      = $reader->id;
            $data->time          = time();
            
            $DB->insert_record("reader_levels", $data);
        }
        
        add_to_log($course->id, "reader", substr("AA-Student Level Changed ({$userid} {$studentlevel->currentlevel} to {$currentlevel})", 0, 39), "admin.php?id=$id", "$cm->instance");
        
        $studentlevel->currentlevel = $currentlevel;
        //echo reader_selectlevel_box ($userid, $studentlevel, $slevel);
        echo $currentlevel;

        die();
    }
    
    
/* 
* CHANGE BOOK LENGTH
*/

    if (has_capability('mod/reader:manage', $contextmodule) && $f == "changelength") {
        if ($reader->individualbooks == 0) {
            if ($bookdata = $DB->get_record("reader_publisher", array("id" => $bookid))) {
                $DB->set_field("reader_publisher",  "length",  $length, array( "id" => $bookid));
            }
            add_to_log($course->id, "reader", substr("AA-Change length ({$bookid} {$bookdata->length} to {$length})",0,39), "admin.php?id=$id", "$cm->instance");
        } else {
            if ($bookdata = $DB->get_record("reader_individual_books", array("readerid" => $reader->id, "bookid" => $bookid))) {
                $DB->set_field("reader_individual_books",  "length",  $length, array( "readerid" => $reader->id,  "bookid" => $bookid));
            }
            add_to_log($course->id, "reader", substr("AA-Change length individual ({$bookid} {$bookdata->length} to {$length})",0,39), "admin.php?id=$id", "$cm->instance");
        }

        //echo reader_select_length_box ($length, $book->id);
        echo $length;
        
        die();
    }

/* 
* CHANGE DIFFICULTY LENGTH
*/

    if (has_capability('mod/reader:manage', $contextmodule) && $f == "changedifficulty") {
        if ($reader->individualbooks == 0) {
            if ($bookdata = $DB->get_record("reader_publisher", array("id" => $bookid))) {
                $DB->set_field("reader_publisher",  "difficulty",  $difficulty, array( "id" => $bookid));
            }
            
            add_to_log($course->id, "reader", substr("AA-Change difficulty ({$bookid} {$bookdata->difficulty} to {$difficulty})",0,39), "admin.php?id=$id", "$cm->instance");
        } else {
            if ($bookdata = $DB->get_record("reader_individual_books", array("readerid" => $reader->id, "bookid" => $bookid))) {
                $DB->set_field("reader_individual_books",  "difficulty",  $difficulty, array( "readerid" => $reader->id,  "bookid" => $bookid));
            }

            add_to_log($course->id, "reader", substr("AA-Change difficulty individual ({$bookid} {$bookdata->difficulty} to {$difficulty})",0,39), "admin.php?id=$id", "$cm->instance");
        }

        //echo reader_select_difficulty_box (reader_get_reader_difficulty($reader, $bookid), $book->id);
        echo $difficulty;
        
        die();
    }


/* 
* CHANGE BOOK TITLE
*/

    if (has_capability('mod/reader:manage', $contextmodule) && $f == "publishertitletext") {
        $DB->set_field("reader_publisher",  'publisher', urldecode($publishertitle), array( "id" => $bookid));
        echo $publishertitle;
        
        die();
    }
    
/* 
* CHANGE BOOK LEVEL
*/

    if (has_capability('mod/reader:manage', $contextmodule) && $f == "leveltext") {
        $DB->set_field("reader_publisher",  'level', urldecode($level), array( "id" => $bookid));
        echo $level;
        
        die();
    }
    
/* 
* CHANGE BOOK WORDS
*/

    if (has_capability('mod/reader:manage', $contextmodule) && $f == "wordstext") {
        $DB->set_field("reader_publisher",  'words', urldecode($words), array( "id" => $bookid));
        echo $words;
        
        die();
    }
    
    
/* 
* CHANGE MASS PUBLISHER
*/

    if (has_capability('mod/reader:manage', $contextmodule) && $f == "changereaderlevel_masspublisher") {
        $DB->set_field("reader_publisher",  'publisher', urldecode($masspublisherto), array( "publisher" => urldecode($masspublisherfrom)));
        
        add_to_log($course->id, "reader", substr("AA-Mass changes publisher (".urldecode($masspublisherfrom)." to ".urldecode($masspublisherto).")", 0, 39), "admin.php?id=$id", "$cm->instance");
        
        echo 'Done ';
        
        echo html_writer::link(new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'act'=>'changereaderlevel', 'id'=>$id)), 'Reload page');
        
        die();
    }
    
/* 
* CHANGE MASS LEVEL
*/

    if (has_capability('mod/reader:manage', $contextmodule) && $f == "changereaderlevel_masslevel") {
        $DB->set_field("reader_publisher", 'level', urldecode($masslevelto), array( "level" => urldecode($masslevelfrom), "publisher" => urldecode($publisher)));
        
        add_to_log($course->id, "reader", substr("AA-Mass changes level (".urldecode($masspublisherfrom)." to ".urldecode($masspublisherto).")", 0, 39), "admin.php?id=$id", "$cm->instance");
        
        echo 'Done ';
        
        echo html_writer::link(new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'act'=>'changereaderlevel', 'id'=>$id)), 'Reload page');
        
        die();
    }
    
    
/* 
* CHANGE MASS LENGTH
*/

    if (has_capability('mod/reader:manage', $contextmodule) && $f == "changereaderlevel_masslength") {
        if (!empty($level)) {
            $DB->set_field("reader_publisher", 'length', urldecode($masslengthto), array( "length" => urldecode($masslengthfrom), "publisher" => urldecode($publisher), "level" => urldecode($level)));
        } else {
            $DB->set_field("reader_publisher", 'length', urldecode($masslengthto), array( "length" => urldecode($masslengthfrom), "publisher" => urldecode($publisher)));
        }
        
        if (!empty($level)) {
            $data = $DB->get_records("reader_publisher", array("publisher" => urldecode($publisher), "level" => urldecode($level)));
        } else {
            $data = $DB->get_records("reader_publisher", array("publisher" => urldecode($publisher)));
        }
        
        $lengthstring = "";
        
        foreach ($data as $key => $value) {
            $lengthstring .= $value->id.",";
        }
        $lengthstring = substr($lengthstring, 0, -1);
            
        $DB->execute("UPDATE {reader_individual_books} SET length = ? WHERE length = ? and readerid = ? and bookid IN (?)", array(urldecode($masslengthto), urldecode($masslengthfrom), $reader->id, $lengthstring)); 
        
        add_to_log($course->id, "reader", substr("AA-Mass changes length (".urldecode($masslengthfrom)." to ".urldecode($masslengthto).")", 0, 39), "admin.php?id=$id", "$cm->instance");
        
        echo 'Done ';
        
        echo html_writer::link(new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'act'=>'changereaderlevel', 'id'=>$id)), 'Reload page');
        
        die();
    }
    
    
/* 
* CHANGE MASS DIFFICULTY
*/

    if (has_capability('mod/reader:manage', $contextmodule) && $f == "changereaderlevel_massdifficulty") {
        if ($reader->individualbooks == 0) {
            if (!empty($level)) {
                $DB->set_field("reader_publisher", 'difficulty', urldecode($massdifficultyto), array( "difficulty" => urldecode($massdifficultyfrom), "publisher" => urldecode($publisher), "level" => urldecode($level)));
            } else {
                $DB->set_field("reader_publisher", 'difficulty', urldecode($massdifficultyto), array( "difficulty" => urldecode($massdifficultyfrom), "publisher" => urldecode($publisher)));
            }
        } else {
            if (!empty($level)) {
                $data = $DB->get_records("reader_publisher", array("publisher" => urldecode($publisher), "level" => urldecode($level)));
            } else {
                $data = $DB->get_records("reader_publisher", array("publisher" => urldecode($publisher)));
            }
            
            $difficultystring = "";
            
            foreach ($data as $key => $value) {
                $difficultystring .= $value->id.",";
            }
            $difficultystring = substr($difficultystring, 0, -1);
                
            $DB->execute("UPDATE {reader_individual_books} SET difficulty = ? WHERE difficulty = ? and readerid = ? and bookid IN (?)", array(urldecode($massdifficultyto), urldecode($massdifficultyfrom), $reader->id, $difficultystring)); 
        }
        
        add_to_log($course->id, "reader", substr("AA-Mass changes difficulty (".urldecode($massdifficultyfrom)." to ".urldecode($massdifficultyto).")", 0, 39), "admin.php?id=$id", "$cm->instance");
        
        echo 'Done ';
        
        echo html_writer::link(new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'act'=>'changereaderlevel', 'id'=>$id)), 'Reload page');
        
        die();
    }
    
    
    