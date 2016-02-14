<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin

    require_once("../../config.php");
    require_once("lib.php");

    require_once($CFG->dirroot.'/course/moodleform_mod.php');
    require_once($CFG->dirroot.'/lib/excellib.class.php');
    require_once($CFG->libdir.'/tablelib.php');
    require_once($CFG->dirroot . '/question/editlib.php');
     
    $id                      = required_param('id', PARAM_INT); 
    $a                       = optional_param('a', NULL, PARAM_CLEAN); 
    $act                     = optional_param('act', NULL, PARAM_CLEAN); 
    $quizesid                = optional_param('quizesid', NULL, PARAM_CLEAN); 
    $publisher               = optional_param('publisher', NULL, PARAM_CLEAN); 
    $publisherex             = optional_param('publisherex', NULL, PARAM_CLEAN); 
    $difficulty              = optional_param('difficulty', NULL, PARAM_CLEAN); 
    $todifficulty            = optional_param('todifficulty', NULL, PARAM_CLEAN); 
    $difficultyex            = optional_param('difficultyex', NULL, PARAM_CLEAN); 
    $level                   = optional_param('level', NULL, PARAM_CLEAN); 
    $tolevel                 = optional_param('tolevel', NULL, PARAM_CLEAN); 
    $topublisher             = optional_param('topublisher', NULL, PARAM_CLEAN); 
    $levelex                 = optional_param('levelex', NULL, PARAM_CLEAN); 
    $length                  = optional_param('length', NULL, PARAM_CLEAN); 
    $tolength                = optional_param('tolength', NULL, PARAM_CLEAN); 
    $grid                    = optional_param('grid', NULL, PARAM_INT); 
    $excel                   = optional_param('excel', NULL, PARAM_CLEAN); 
    $del                     = optional_param('del', NULL, PARAM_CLEAN); 
    $attemptid               = optional_param('attemptid', NULL, PARAM_CLEAN); 
    $restoreattemptid        = optional_param('restoreattemptid', NULL, PARAM_CLEAN); 
    $upassword               = optional_param('upassword', NULL, PARAM_CLEAN); 
    $groupid                 = optional_param('groupid', 0, PARAM_INT); 
    $activehours             = optional_param('activehours', NULL, PARAM_CLEAN); 
    $text                    = optional_param('text', NULL, PARAM_CLEAN); 
    $bookid                  = optional_param('bookid', NULL, PARAM_CLEAN); 
    $deletebook              = optional_param('deletebook', 0, PARAM_INT); 
    $deleteallattempts       = optional_param('deleteallattempts', 0, PARAM_INT); 
    $editmessage             = optional_param('editmessage', 0, PARAM_INT); 
    $deletemessage           = optional_param('deletemessage', 0, PARAM_INT); 
    $hidebooks               = optional_param('hidebooks', 0, PARAM_ALPHA); 
    $unhidebooks             = optional_param('unhidebooks', 0, PARAM_ALPHA); 
    $sort                    = optional_param('sort', 'username', PARAM_CLEAN); 
    $orderby                 = optional_param('orderby', 'ASC', PARAM_CLEAN); 
    $dir                     = optional_param('dir', 'ASC', PARAM_ALPHA);
    $slevel                  = optional_param('slevel', 0, PARAM_ALPHA);
    $page                    = optional_param('page', 0, PARAM_INT);
    $perpage                 = optional_param('perpage', 0, PARAM_INT);
    $userid                  = optional_param('userid', 0, PARAM_INT);
    $changelevel             = optional_param('changelevel', NULL, PARAM_CLEAN);
    $searchtext              = optional_param('searchtext', NULL, PARAM_CLEAN);
    $needip                  = optional_param('needip', NULL, PARAM_CLEAN);
    $setip                   = optional_param('setip', NULL, PARAM_CLEAN);
    $nopromote               = optional_param('nopromote', NULL, PARAM_CLEAN);
    $promotionstop           = optional_param('promotionstop', NULL, PARAM_CLEAN);
    $private                 = optional_param('private', 0, PARAM_INT);
    $ajax                    = optional_param('ajax', NULL, PARAM_CLEAN);
    $changeallstartlevel     = optional_param('changeallstartlevel', -1, PARAM_INT);
    $changeallcurrentlevel   = optional_param('changeallcurrentlevel', -1, PARAM_INT);
    $changeallcurrentgoal    = optional_param('changeallcurrentgoal', NULL, PARAM_CLEAN);
    $changeallpromo          = optional_param('changeallpromo', NULL, PARAM_CLEAN);
    $changeallstoppromo      = optional_param('changeallstoppromo', -1, PARAM_INT);
    $userimagename           = optional_param('userimagename', NULL, PARAM_CLEAN);
    $award                   = optional_param('award', NULL, PARAM_CLEAN);
    $student                 = optional_param('student', NULL, PARAM_CLEAN);
    $useonlythiscourse       = optional_param('useonlythiscourse', NULL, PARAM_CLEAN);
    $ipmask                  = optional_param('ipmask', 3, PARAM_CLEAN);
    $fromtime                = optional_param('fromtime', 86400, PARAM_CLEAN);
    $maxtime                 = optional_param('maxtime', 1800, PARAM_CLEAN);
    $cheated                 = optional_param('cheated', NULL, PARAM_CLEAN);
    $uncheated               = optional_param('uncheated', NULL, PARAM_CLEAN);
    $findcheated             = optional_param('findcheated', NULL, PARAM_CLEAN);
    $separategroups          = optional_param('separategroups', NULL, PARAM_CLEAN);
    $levelall                = optional_param('levelall', NULL, PARAM_CLEAN);
    $levelc                  = optional_param('levelc', NULL, PARAM_CLEAN);
    $wordsorpoints           = optional_param('wordsorpoints', NULL, PARAM_CLEAN);
    $setgoal                 = optional_param('setgoal', NULL, PARAM_CLEAN);
    $wordscount              = optional_param('wordscount', NULL, PARAM_CLEAN);
    $viewasstudent           = optional_param('viewasstudent', NULL, PARAM_CLEAN);
    $booksratingbest         = optional_param('booksratingbest', NULL, PARAM_CLEAN);
    $booksratinglevel        = optional_param('booksratinglevel', NULL, PARAM_CLEAN);
    $booksratingterm         = optional_param('booksratingterm', NULL, PARAM_CLEAN);
    $booksratingwithratings  = optional_param('booksratingwithratings', NULL, PARAM_CLEAN);
    $booksratingshow         = optional_param('booksratingshow', NULL, PARAM_CLEAN);
    $quiz                    = optional_param_array('quiz', NULL, PARAM_CLEAN);
    $sametitlekey            = optional_param('sametitlekey', NULL, PARAM_CLEAN);
    $sametitleid             = optional_param('sametitleid', NULL, PARAM_CLEAN);
    $wordstitlekey           = optional_param('wordstitlekey', NULL, PARAM_CLEAN);
    $wordstitleid            = optional_param('wordstitleid', NULL, PARAM_CLEAN);
    $leveltitlekey           = optional_param('leveltitlekey', NULL, PARAM_CLEAN);
    $leveltitleid            = optional_param('leveltitleid', NULL, PARAM_CLEAN);
    $publishertitlekey       = optional_param('publishertitlekey', NULL, PARAM_CLEAN);
    $publishertitleid        = optional_param('publishertitleid', NULL, PARAM_CLEAN);
    $checkattempt            = optional_param('checkattempt', NULL, PARAM_CLEAN);
    $checkattemptvalue       = optional_param('checkattemptvalue', 0, PARAM_INT); 
    $book                    = optional_param('book', 0, PARAM_INT); 
    $noquizuserid            = optional_param('noquizuserid', NULL, PARAM_CLEAN); 
    $withoutdayfilter        = optional_param('withoutdayfilter', NULL, PARAM_CLEAN); 
    $numberofsections        = optional_param('numberofsections', NULL, PARAM_CLEAN); 
    $ct                      = optional_param('ct', NULL, PARAM_CLEAN); 
    $adjustscoresupbooks     = optional_param('adjustscoresupbooks', NULL, PARAM_CLEAN); 
    $adjustscoresaddpoints   = optional_param('adjustscoresaddpoints', NULL, PARAM_CLEAN); 
    $adjustscoresupall       = optional_param('adjustscoresupall', NULL, PARAM_CLEAN); 
    $adjustscorespand        = optional_param('adjustscorespand', NULL, PARAM_CLEAN); 
    $adjustscorespby         = optional_param('adjustscorespby', NULL, PARAM_CLEAN); 
    $sctionoption            = optional_param('sctionoption', NULL, PARAM_CLEAN); 
    $studentuserid           = optional_param('studentuserid', 0, PARAM_INT); 
    $studentusername         = optional_param('studentusername', NULL, PARAM_CLEAN); 
    $bookquiznumber          = optional_param('bookquiznumber', 0, PARAM_INT); 
    $importstudentrecorddata = optional_param('importstudentrecorddata', 0, PARAM_INT); 
    $noqstudents             = optional_param_array('noqstudents', 0, PARAM_INT); 
    
    $readercfg = get_config('reader');


/*
* From student view to teacher
*/
    if (isset($USER->realuser)) {
      $USER = get_complete_user_data('id', $USER->realuser);
    }


/*
* SET COURSE TERM FILTER
*/
    if ($ct > 0) {
        $_SESSION['SESSION']->reader_values_ct = $ct;
    } else if ($ct == -1) {
        $ct = 0;
        unset($_SESSION['SESSION']->reader_values_ct);
    }
    
    if (isset($_SESSION['SESSION']->reader_values_ct)) {
        $ct = $_SESSION['SESSION']->reader_values_ct;
    }


/*
* SET GROIP SORT
*/
    if ($grid > 0) {
        $_SESSION['SESSION']->reader_values_grid = $grid;
    } else if ($grid == -1) {
        $grid = 0;
        unset($_SESSION['SESSION']->reader_values_grid);
    }
    
    if (isset($_SESSION['SESSION']->reader_values_grid)) {
        $grid = $_SESSION['SESSION']->reader_values_grid;
    }


/*
* SET PERPAGE SORT
*/
    if (!empty($searchtext)) {
        $perpage = 1000;
    } else {
        if ($perpage > 0) {
            $_SESSION['SESSION']->reader_perpage = $perpage;
        }
        
        if (isset($_SESSION['SESSION']->reader_perpage)) {
            $perpage = $_SESSION['SESSION']->reader_perpage;
        }
    }
    
    if ($perpage < 50)
        $perpage = 50;
    
    $_SESSION['SESSION']->reader_perpage = $perpage;
    
    
    $row = 3; // Start row for Excel file
    

    if (!$cm = get_coursemodule_from_id('reader', $id)) {
        print_error('invalidcoursemodule');
    }
    if (!$course = $DB->get_record('course', array('id'=>$cm->course))) {
        print_error("coursemisconf");
    }
    if (!$reader = $DB->get_record('reader', array('id'=>$cm->instance))) {
        print_error('invalidcoursemodule');
    }

    require_login($course, true, $cm);

    add_to_log($course->id, "reader", "admin area", "admin.php?id=$id", "$cm->instance");


/*
* FullReports ignor date fix
*/
    if ($act == "fullreports" && $ct == 0) 
        $reader->ignordate = 0;


    $alinkpadding = new moodle_url("/mod/reader/admin.php", array("a"=>"admin", "id"=>$id, "act"=>$act, "grid"=>$grid, "sort"=>$sort, "orderby"=>$orderby));

    $context = get_context_instance(CONTEXT_COURSE, $course->id);
    $contextmodule = get_context_instance(CONTEXT_MODULE, $cm->id);


/*
* Add quizzes
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $quizesid) {
        if (empty($publisher) && ($publisherex == '0')) {
            print_error ("Please choose publisher", "admin.php?a=admin&id=".$id."&act=addquiz");
        } else if (!isset($difficulty) && $difficulty != 0 && $difficultyex != 0 && !$difficultyex) {
            print_error ("Please choose Reading Level", "admin.php?a=admin&id=".$id."&act=addquiz");
        } else if (!isset($level) && ($levelex == '0')) {
            print_error ("Please choose level", "admin.php?a=admin&id=".$id."&act=addquiz");
        }
        
        if ($_FILES['userimage']) {
            if (is_uploaded_file($_FILES['userimage']['tmp_name'])) {
                $ext = substr($_FILES['userimage']['name'], 1 + strrpos($_FILES['userimage']['name'], "."));
                if (in_array(strtolower($ext), array("jpg", "jpeg", "gif"))) {
                    if (! make_upload_directory('reader/images')) {
                        //return false;
                    }
                    if (file_exists ($CFG->dataroot."/reader/images/".$_FILES['userimage']['name'])) {
                        list ($imgname, $imgtype) = explode (".", $_FILES['userimage']['name']);
                        $newimagename  = $imgname.rand (9999,9999999);
                        $newimagename .= ".".$ext;
                        $newimagename  = strtolower($newimagename);
                    } else {
                        $newimagename  = $_FILES['userimage']['name'];
                    }
                    @move_uploaded_file($_FILES['userimage']['tmp_name'], $CFG->dataroot."/reader/images/".$newimagename);
                }
            }
        }
        
        if ($userimagename) {
            $newimagename = $userimagename;
        }
        
        foreach ($quizesid as $quizesid_) {
            $newquiz = new stdClass;
            if (!$publisher) {
                $newquiz->publisher = $publisherex;
            } else {
                $newquiz->publisher = $publisher;
            }
            
            if (!isset($difficulty) && $difficulty != 0) {
                $newquiz->difficulty = $difficultyex;
            } else {
                $newquiz->difficulty = $difficulty;
            }
            
            if (!isset($level)) {
                $newquiz->level     = $levelex;
            } else {
                $newquiz->level     = $level;
            }
            
            if ($length) {
                $newquiz->length    = $length;
            }
            
            if ($newimagename) {
                $newquiz->image     = $newimagename;
            }
            
            if ($wordscount) {
                $newquiz->words     = $wordscount;
            }
            
            $quizdata               = $DB->get_record("quiz", array( "id"=>$quizesid_));
            
            $newquiz->name          = $quizdata->name;
            
            $newquiz->quizid        = $quizesid_;
            $newquiz->private       = $private;
            
            $DB->insert_record ("reader_publisher", $newquiz);
        }
        
        $message_forteacher         = reader_red_notice(get_string("quizzesadded", "reader"), true);
        
        add_to_log($course->id, "reader", "AA-Quizzes Added", "admin.php?id=$id", "$cm->instance");
    }
    
    
/*
* Delete attempts
*/
    if (has_capability('mod/reader:deletereaderattempts', $contextmodule) && $act == "viewattempts" && $attemptid) {
        $attemptdata = $DB->get_record("reader_attempts", array( "id"=>$attemptid));
        unset($attemptdata->id);
        $DB->insert_record ("reader_deleted_attempts", $attemptdata);
        $DB->delete_records ("reader_attempts", array("id"=>$attemptid));
        add_to_log($course->id, "reader", "AA-reader_deleted_attempts", "admin.php?id=$id", "$cm->instance");
    }
    
    
/*
* Restore attempts
*/
    if (has_capability('mod/reader:deletereaderattempts', $contextmodule) && $act == "viewattempts" && $bookquiznumber) {
        if (empty($studentuserid)) {
            $data = $DB->get_record("user", array("username"=>$studentusername));
            $studentuserid = $data->id;
        }
        
        if (!empty($studentuserid)) {
            $attemptdata = $DB->get_record("reader_deleted_attempts", array( "userid"=>$studentuserid, "quizid"=>$bookquiznumber));
            unset($attemptdata->id);
            $DB->insert_record ("reader_attempts", $attemptdata);
            $DB->delete_records ("reader_deleted_attempts", array( "userid"=>$studentuserid, "quizid"=>$bookquiznumber));
            add_to_log($course->id, "reader", "AA-reader_restore_attempts", "admin.php?id=$id", "$cm->instance");
        }
        
        if (is_file($CFG->dirroot."/blocks/readerview/cron.php")) {
            file_get_contents(new moodle_url("/blocks/readerview/cron.php"));
        }
    }
    

/*
* Add new Message
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $text && $activehours) {
        $message                   = new stdClass;
        $message->users            = '';
        foreach ($groupid as $groupkey=>$groupvalue) {
            $message->users       .= $groupvalue.',';
        }
        
        $message->users            = substr($message->users,0,-1);
        $message->instance         = $cm->instance;
        $message->teacherid        = $USER->id;
        $message->text             = $text;
        $message->timebefore       = time() + ($activehours * 60 * 60);
        $message->timemodified     = time();
        
        if ($editmessage) {
            $message->id           = $editmessage;
            $DB->update_record("reader_messages", $message);
        } else {
            $DB->insert_record("reader_messages", $message);
        }
        
        add_to_log($course->id, "reader", "AA-Message Added", "admin.php?id=$id", "$cm->instance");
    }
    

/*
* Delete Message
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $deletemessage) {
        $DB->delete_records("reader_messages", array("id"=>$deletemessage));
        
        add_to_log($course->id, "reader", "AA-Message Deleted", "admin.php?id=$id", "$cm->instance");
    }
    
    
/*
* Check attempt
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $checkattempt && $ajax == 'true') {
        $DB->set_field("reader_attempts",  "checkbox",  $checkattemptvalue, array( "id"=>$checkattempt));
        die();
    }
    

/*
* Hide UnHide Books
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $bookid) {
        if ($hidebooks) {
            foreach ($bookid as $bookidkey=>$bookidvalue) {
                $DB->set_field("reader_publisher",  "hidden",  1, array( "id"=>$bookidkey));
            }
        }
        if ($unhidebooks) {
            foreach ($bookid as $bookidkey=>$bookidvalue) {
                $DB->set_field("reader_publisher",  "hidden",  0, array( "id"=>$bookidkey));
            }
        }
        
        if (is_file($CFG->dirroot."/blocks/readerview/cron.php")) {
            file_get_contents(new moodle_url("/blocks/readerview/cron.php"));
        }
        
        add_to_log($course->id, "reader", "AA-Books status changed", "admin.php?id=$id", "$cm->instance");
    }


/*
* Delete Attempts
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $deletebook && $deleteallattempts) {
        $DB->delete_records("reader_attempts", array("quizid"=>$deletebook, "reader"=>$reader->id));
        
        add_to_log($course->id, "reader", "AA-Attempts Deleted", "admin.php?id=$id", "$cm->instance");
    }


/*
* Delete Books
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $deletebook) {
        if ($DB->count_records("reader_attempts", array("quizid"=>$deletebook, "reader"=>$reader->id)) == 0) {
            $DB->delete_records("reader_publisher", array("id"=>$deletebook));
        } else {
            $needdeleteattemptsfirst = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE quizid= ?  and reader= ?  ORDER BY timefinish", array($deletebook,$reader->id));
        }
        add_to_log($course->id, "reader", "AA-Book Deleted", "admin.php?id=$id", "$cm->instance");
    }


/*
* Set sametitlekey to books
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $ajax == "true" && isset($sametitlekey)) {
        $DB->set_field("reader_publisher",  'sametitle',  $sametitlekey, array( "id"=>$sametitleid));
        echo $sametitlekey;
        die();
    }



/*
* View as student
*/
    if (has_capability('mod/reader:viewstudentreaderscreens', $contextmodule) && $viewasstudent > 1) {
        $systemcontext = get_context_instance(CONTEXT_SYSTEM);
        $coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);

        if (has_capability('moodle/user:loginas', $systemcontext)) {
            if (has_capability('moodle/site:doanything', $systemcontext, $viewasstudent, false)) {
                print_error('nologinas');
            }
            $context = $systemcontext;
        } else {
            require_login($course);
            require_capability('moodle/user:loginas', $coursecontext);
            if (!has_capability('moodle/course:view', $coursecontext, $viewasstudent, false)) {
                print_error ('This user is not in this course!');
            }
            if (has_capability('moodle/site:doanything', $coursecontext, $viewasstudent, false)) {
                print_error('nologinas');
            }
            $context = $coursecontext;
        }
      /// Login as this user and return to course home page.

      if (function_exists('session_loginas')) {
        session_loginas($viewasstudent, $context);
      } else {
        $oldfullname = fullname($USER, true);
        $olduserid   = $USER->id;

      /// Create the new USER object with all details and reload needed capabilitites
        $USER = get_complete_user_data('id', $viewasstudent);
        $USER->realuser = $olduserid;
        $USER->loginascontext = $context;
        check_enrolment_plugins($USER);
        load_all_capabilities();   // reload capabilities

        if (isset($SESSION->currentgroup)) {    // Remember current cache setting for later
            $SESSION->oldcurrentgroup = $SESSION->currentgroup;
            unset($SESSION->currentgroup);
        }

        $newfullname = fullname($USER, true);
      }
      add_to_log($course->id, "course", "loginas", "../user/view.php?id=$course->id&amp;user=$userid", "$oldfullname -> $newfullname");
      header("Location: view.php?a=quizes&id=".$id);
    }


/*
* If need to set individual strict users acsess
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $setip) {
        if ($DB->get_record("reader_strict_users_list", array("userid"=>$userid, "readerid"=>$reader->id))) {
            $DB->set_field("reader_strict_users_list",  "needtocheckip",  $needip, array( "userid"=>$userid,  "readerid"=>$reader->id));
        } else {
            $data                = new stdClass;
            $data->userid        = $userid;
            $data->readerid      = $reader->id;
            $data->needtocheckip = $needip;
            
            $DB->insert_record("reader_strict_users_list", $data);
        }
        add_to_log($course->id, "reader", substr("AA-Student check ip Changed ({$userid} {$needip})",0,39), "admin.php?id=$id", "$cm->instance");
        if ($ajax == "true") {
            echo reader_selectip_form ($userid, $reader);
            die();
        }
    }


/*
* Mass Change Levels
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $changeallstartlevel >= 0) {
        $coursestudents = get_enrolled_users($context, NULL, $grid);
        foreach ($coursestudents as $coursestudent) {
            if ($DB->get_record("reader_levels", array("userid"=>$coursestudent->id, "readerid"=>$reader->id))) {
                $DB->set_field("reader_levels",  "startlevel",  $changeallstartlevel, array( "userid"=>$coursestudent->id,  "readerid"=>$reader->id));
            }
            else
            {
                $data = new stdClass;
                $data->startlevel = $changeallstartlevel;
                $data->currentlevel = $changeallstartlevel;
                $data->userid = $coursestudent->id;
                $data->readerid = $reader->id;
                $data->time = time();
                
                $DB->insert_record("reader_levels", $data);
            }
            add_to_log($course->id, "reader", substr("AA-changeallstartlevel userid: {$coursestudent->id}, startlevel={$changeallstartlevel}",0,39), "admin.php?id=$id", "$cm->instance");
        }
    }


/*
* Mass Change Levels
*/
/*
    if (has_capability('mod/reader:manage', $contextmodule) &&  $changeallcurrentlevel >= 0) {
        $coursestudents = get_enrolled_users($context, NULL, $grid);
        foreach ($coursestudents as $coursestudent) {
            if ($DB->get_record("reader_levels", array("userid"=>$coursestudent->id, "readerid"=>$reader->id))) {
                $DB->set_field("reader_levels",  "currentlevel",  $changeallcurrentlevel, array( "userid"=>$coursestudent->id,  "readerid"=>$reader->id));
            } else {
                $data = new stdClass;
                $data->startlevel = $changeallcurrentlevel;
                $data->currentlevel = $changeallcurrentlevel;
                $data->userid = $coursestudent->id;
                $data->readerid = $reader->id;
                $data->time = time();
                
                $DB->insert_record("reader_levels", $data);
            }
            add_to_log($course->id, "reader", substr("AA-changeallcurrentlevel userid: {$coursestudent->id}, currentlevel={$changeallcurrentlevel}",0,39), "admin.php?id=$id", "$cm->instance");
        }
    }
*/

/*
* Mass Change Promo
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $changeallpromo) {
        $coursestudents = get_enrolled_users($context, NULL, $grid);
        foreach ($coursestudents as $coursestudent) {
            if ($DB->get_record("reader_levels", array("userid"=>$coursestudent->id, "readerid"=>$reader->id))) {
                if (strtolower($changeallpromo) == "promo") {
                    $nopromote = 0;
                } else {
                    $nopromote = 1;
                } 
                $DB->set_field("reader_levels",  'nopromote',  $nopromote, array( "userid"=>$coursestudent->id,  "readerid"=>$reader->id));
            }
            add_to_log($course->id, "reader", substr("AA-Student Promotion Stop Changed ({$coursestudent->id} set to {$promotionstop})",0,39), "admin.php?id=$id", "$cm->instance");
        }
    }


/*
* Mass Change Promo
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $changeallstoppromo >= 0 && $grid) {
        $coursestudents = get_enrolled_users($context, NULL, $grid);
        foreach ($coursestudents as $coursestudent) {
            if ($DB->get_record("reader_levels", array("userid"=>$coursestudent->id, "readerid"=>$reader->id))) {
                $DB->set_field("reader_levels",  'promotionstop',  $changeallstoppromo, array( "userid"=>$coursestudent->id,  "readerid"=>$reader->id));
            }
            add_to_log($course->id, "reader", substr("AA-Student NoPromote Changed ({$coursestudent->id} set to {$changeallstoppromo})",0,39), "admin.php?id=$id", "$cm->instance");
        }
    }


/*
* Mass Change Goal
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $act == "studentslevels" && !empty($grid) && isset($changeallcurrentgoal)) {
        $coursestudents = get_enrolled_users($context, NULL, $grid);
        foreach ($coursestudents as $coursestudent) {
            if ($data = $DB->get_record("reader_levels", array("userid"=>$coursestudent->id, "readerid"=>$reader->id))) {
                $DB->set_field("reader_levels",  "goal",  $changeallcurrentgoal, array( "id"=>$data->id));
            } else {
                $data = new stdClass;
                $data->userid = $coursestudent->id;
                $data->startlevel = 0;
                $data->currentlevel = 0;
                $data->readerid = $reader->id;
                $data->goal = $changeallcurrentgoal;
                $data->time = time();
                
                $DB->insert_record("reader_levels", $data);
            }
            add_to_log($course->id, "reader", substr("AA-goal userid: {$coursestudent->id}, goal={$changeallcurrentgoal}",0,39), "admin.php?id=$id", "$cm->instance");
        }
    }


/*
* Award Extra Points
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $act == "assignpointsbookshavenoquizzes" && $book) {
        $useridold = $USER->id;
        if ($bookdata = $DB->get_record("reader_noquiz", array("id"=>$book))) {
            foreach ($noqstudents as $student_) {
                if(!$attemptnumber = (int)$DB->get_field_sql('SELECT MAX(attempt)+1 FROM ' .
                        "{reader_attempts} WHERE reader = ? AND " .
                        "userid = ? AND timefinish > 0", array($reader->id, $student_))) {
                    $attemptnumber = 1;
                }
                
                $USER->id = $student_;
                
                $attempt = reader_create_attempt($reader, $attemptnumber, $bookdata->id);
                $attempt->ip = getremoteaddr();
                
                $lastattemptid     = $DB->get_field_sql('SELECT uniqueid FROM {reader_attempts} ORDER BY uniqueid DESC');
                $attempt->uniqueid = $lastattemptid + 1;
                
                // Save the attempt
                if (!$attempt->id = $DB->insert_record('reader_attempts', $attempt)) {
                    print_error ('Could not create new attempt');
                }
                
                $totalgrade = 0;
                $answersgrade = $DB->get_records ("reader_question_instances", array("quiz"=>$bookdata->id)); // Count Grades (TotalGrade)
                
                foreach ($answersgrade as $answersgrade_) {
                    $totalgrade += $answersgrade_->grade;
                }
                
                $attemptnew               = new stdClass;
                $attemptnew->id           = $attempt->id;
                $attemptnew->sumgrades    = $totalgrade;
                $attemptnew->persent      = 100;
                $attemptnew->passed       = "true";
                
                $attemptnew->timefinish   = time() - $reader->attemptsofday * 3600 * 24;
                $attemptnew->timecreated  = time() - $reader->attemptsofday * 3600 * 24;
                $attemptnew->timemodified = time() - $reader->attemptsofday * 3600 * 24;
                
                $DB->update_record("reader_attempts", $attemptnew);
                
                add_to_log($course->id, "reader", "AWP (userid: {$student_}; set: {$award})", "admin.php?id=$id", "$cm->instance");
                
                $message = get_string("done", "reader");
            }
        }
        $USER->id = $useridold;
    }


/*
* cheated
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $cheated) {
        list($cheated1, $cheated2) = explode("_", $cheated);
        $DB->set_field("reader_attempts",  "passed",  "cheated", array( "id"=>$cheated1));
        $DB->set_field("reader_attempts",  "passed",  "cheated", array( "id"=>$cheated2));
        add_to_log($course->id, "reader", "AA-cheated", "admin.php?id=$id", "$cm->instance");
        
        $userid1 = $DB->get_record("reader_attempts", array("id"=>$cheated1));
        $userid2 = $DB->get_record("reader_attempts", array("id"=>$cheated2));
        
        $book = $DB->get_record("reader_publisher", array("id"=>$userid1->quizid));
        
        $data            = new stdClass;
        $data->byuserid  = $USER->id;
        $data->userid1   = $userid1->userid;
        $data->userid2   = $userid2->userid;
        $data->attempt1  = $cheated1;
        $data->attempt2  = $cheated2;
        $data->courseid  = $course->id;
        $data->readerid  = $reader->id;
        $data->quizid    = $userid1->quizid;
        $data->status    = "cheated";
        $data->date      = time();

        $DB->insert_record("reader_cheated_log", $data);
        
        if ($reader->sendmessagesaboutcheating == 1) {
            $user1 = $DB->get_record("user", array("id"=>$userid1->userid));
            $user2 = $DB->get_record("user", array("id"=>$userid2->userid));
            email_to_user($user1,get_admin(),"Notice about cheating: ".$book->name,$reader->cheated_message);
            email_to_user($user2,get_admin(),"Notice about cheating: ".$book->name,$reader->cheated_message);
        }
    }


/*
* uncheated
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $uncheated) {
        list($cheated1, $cheated2) = explode("_", $uncheated);
        $DB->set_field("reader_attempts",  "passed",  "true", array( "id"=>$cheated1));
        $DB->set_field("reader_attempts",  "passed",  "true", array( "id"=>$cheated2));
        add_to_log($course->id, "reader", "AA-set passed (uncheated)", "admin.php?id=$id", "$cm->instance");
        
        $userid1 = $DB->get_record("reader_attempts", array("id"=>$cheated1));
        $userid2 = $DB->get_record("reader_attempts", array("id"=>$cheated2));
        
        $data            = new stdClass;
        $data->byuserid  = $USER->id;
        $data->userid1   = $userid1->userid;
        $data->userid2   = $userid2->userid;
        $data->attempt1  = $cheated1;
        $data->attempt2  = $cheated2;
        $data->courseid  = $course->id;
        $data->readerid  = $reader->id;
        $data->quizid    = $userid1->quizid;
        $data->status    = "passed";
        $data->date      = time();
        
        $DB->insert_record("reader_cheated_log", $data);
        
        if ($reader->sendmessagesaboutcheating == 1) {
            $user1 = $DB->get_record("user", array("id"=>$userid1->userid));
            $user2 = $DB->get_record("user", array("id"=>$userid2->userid));
            email_to_user($user1,get_admin(),"Points restored notice",$reader->not_cheated_message);
            email_to_user($user2,get_admin(),"Points restored notice",$reader->not_cheated_message);
        }
    }


/*
* Set goal
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $act == "setgoal") {
        if ($wordsorpoints) {
            $DB->set_field("reader",  "wordsorpoints",  $wordsorpoints, array( "id"=>$reader->id));
        }
        if (!$levelall) {
            foreach ((array)$levelc as $key=>$value) {
                if ($value) {
                    $data              = new stdClass;
                    
                    if ($separategroups) 
                        $data->groupid = $separategroups;
                        
                    $data->readerid    = $reader->id;
                    $data->level       = $key;
                    $data->goal        = $value;
                    $data->changedate  = time();
                    $dataid = $DB->insert_record("reader_goal", $data);
                    
                    add_to_log($course->id, "reader", "AA-wordsorpoints goal=$value", "admin.php?id=$id", "$cm->instance");
                }
            }
        } else {
            $DB->delete_records("reader_goal", array("readerid"=>$reader->id));
            if ($separategroups) {
                $data              = new stdClass;
                $data->groupid     = $separategroups;
                $data->readerid    = $reader->id;
                $data->level       = 0;
                $data->goal        = $levelall;
                $data->changedate  = time();
                $DB->insert_record("reader_goal", $data);
            } else {
                $DB->set_field("reader", "goal", $levelall);
            }
            add_to_log($course->id, "reader", "AA-wordsorpoints goal=$levelall", "admin.php?id=$id", "$cm->instance");
        }
    }
    
    
/*
* Set individual quizzes
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $act == "setindividualbooks" && is_array($quiz)) {
        $DB->delete_records ("reader_individual_books", array("readerid"=>$reader->id));
        
        foreach ($quiz as $quiz_) {
            $oldbookdata        = $DB->get_record("reader_publisher", array("id"=>$quiz_));
            $data               = new stdClass;
            $data->readerid     = $reader->id;
            $data->bookid       = $quiz_;
            $data->difficulty   = $oldbookdata->difficulty;
            $data->length       = $oldbookdata->length;

            $DB->insert_record("reader_individual_books", $data);
        }
    }
    
    
/*
* Set forced time delay
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $act == "forcedtimedelay" && is_array($levelc)) {
        $DB->delete_records ("reader_forcedtimedelay", array("readerid"=>$reader->id, "groupid"=>$separategroups));
        foreach ($levelc as $key=>$value) {
            if ($value != 0) {
                $data             = new stdClass;
                $data->readerid   = $reader->id;
                $data->groupid    = $separategroups;
                $data->level      = $key;
                $data->delay      = $value;
                $data->changedate = time();
                $DB->insert_record("reader_forcedtimedelay", $data);
            }
        }
    }
    

/*
* Set forced time delay
*/
    if (has_capability('mod/reader:manage', $contextmodule) && $book && is_array($noquizuserid)) {
        foreach ($noquizuserid as $key=>$value) {
            if ($value != 0) {
                $lastattemptid      = $DB->get_field_sql('SELECT uniqueid FROM {reader_attempts} ORDER BY uniqueid DESC');
                $data               = new stdClass;
                $data->uniqueid     = $lastattemptid + 1;
                $data->reader       = $reader->id;
                $data->userid       = $value;
                $data->attempt      = 1;
                $data->sumgrades    = 1;
                $data->passed       = "true";
                $data->persent      = 100;
                $data->timestart    = time();
                $data->timefinish   = time();
                $data->timemodified = time();
                $data->layout       = "0,";
                $data->preview      = 1;
                $data->quizid       = $book;
                $data->bookrating   = 1;
                $data->ip           = $_SERVER['REMOTE_ADDR'];
                
                $DB->insert_record("reader_attempts", $data);
            }
        }
            
        $noquizreport = get_string('done', 'reader');
            
        unset($book);
    }
    
    
/*
* Set forced time delay
*/
    if ((has_capability('mod/reader:downloadquizzesfromthereaderquizdatabase', $contextmodule)) && $numberofsections && $act == "changenumberofsectionsinquiz") {
        if ($reader->usecourse) 
            $DB->set_field("course",  "numsections",  $numberofsections, array( "id"=>$reader->usecourse));
    }
    
    
/*
* Adjust scores
*/
    if ($act == "adjustscores" && !empty($adjustscoresaddpoints) && !empty($adjustscoresupbooks)) {
        foreach ($adjustscoresupbooks as $key=>$value) {
            $data     = $DB->get_record("reader_attempts", array("id"=>$value));
            $newpoint = $data->persent + $adjustscoresaddpoints;
            
            if ($newpoint >= $reader->percentforreading) 
                $passed = 'true'; 
            else 
                $passed = 'false';
            
            $attempt          = new stdClass;
            $attempt->passed  = $passed;
            $attempt->persent = $newpoint;
            $attempt->id      = $value;
            $DB->update_record('reader_attempts', $attempt);
        }
        
        $adjustscorestext = get_string('done', 'reader');
    }
    
    
/*
* Adjust scores
*/
    if ($act == "adjustscores" && !empty($adjustscoresupall) && !empty($adjustscorespand) && !empty($adjustscorespby)) {
        $dataarr = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE persent < ? AND persent > ? AND quizid = ?", array($adjustscorespand, $adjustscoresupall, $book));
      
        foreach ($dataarr as $ida) {
            $data = $DB->get_record("reader_attempts", array("id"=>$ida->id));
            $newpoint = $data->persent + $adjustscorespby;
            
            if ($newpoint >= $reader->percentforreading) 
                $passed = 'true'; 
            else 
                $passed = 'false';

            $attempt          = new stdClass;
            $attempt->passed  = $passed;
            $attempt->persent = $newpoint;
            $attempt->id      = $ida->id;
            $DB->update_record('reader_attempts', $attempt);
        }
        
        $adjustscorestext = get_string('done', 'reader');
    }
    
    
/*
* EXCEL
*/
    if ($excel) {
        $workbook = new MoodleExcelWorkbook("-");
        $myxls =& $workbook->add_worksheet('report');
        
        $format =& $workbook->add_format();
        $format->set_bold(0);
        $formatbc =& $workbook->add_format();
        $formatbc->set_bold(1);
        
        if (!empty($grid)) {
            $grname = groups_get_group_name($grid);
        } else {
            $grname = "all";
        }
        
        $exceldata['time'] = date("d.M.Y");
        $exceldata['course_shotname'] = str_replace(" ", "-", $course->shortname);
        $exceldata['groupname'] = str_replace(" ", "-", $grname);
        
        if ($act != "exportstudentrecords") {
            $workbook->send('report_'.$exceldata['time'].'_'.$exceldata['course_shotname'].'_'.$exceldata['groupname'].'.xls');
        } else {
            header("Content-Type: text/plain; filename=\"{$COURSE->shortname}_attempts.txt\"");
            $workbook->send($COURSE->shortname.'_attempts.txt');
        }
        add_to_log($course->id, "reader", "AA-excel", "admin.php?id=$id", "$cm->instance");
    }
    
    
/*
* Initialize $PAGE, compute blocks
*/
    $PAGE->set_url('/mod/reader/admin.php', array('id'=>$cm->id));
    
    $title = $course->shortname . ': ' . format_string($reader->name);
    $PAGE->set_title($title);
    $PAGE->set_heading($course->fullname);
    

    if (!$excel) {
        $PAGE->requires->js('/mod/reader/js/jquery-1.4.2.min.js', true);
        $PAGE->requires->js('/mod/reader/js/admin.js');
        $PAGE->requires->js('/mod/reader/js/hide.js');
        
        $PAGE->requires->css('/mod/reader/css/main.css');
        
        echo $OUTPUT->header();
    }
    
    $alreadyansweredbooksid = array();
    
    if (has_capability('mod/reader:manage', $contextmodule)) {
        if (!$excel) {
            require_once ('tabs.php');
        }
    } else {
        die();
    }
    
    if (!$excel) {
        echo $OUTPUT->box_start('generalbox');
    }
    
    if (isset($message_forteacher)) {
        echo $message_forteacher;
    }
    
    if (!$excel) {
        echo html_writer::tag('h3', get_string("menu", "reader").':');
        
        if (has_capability('mod/reader:readerviewreports', $contextmodule)) 
                         $menu['studentmanagement']["?a=admin&id={$id}&act=reports"]          = get_string("reportquiztoreader", "reader");
        if (has_capability('mod/reader:readerviewreports', $contextmodule)) 
                         $menu['studentmanagement']["?a=admin&id={$id}&act=fullreports"]      = get_string("fullreportquiztoreader", "reader");
        if (has_capability('mod/reader:readerviewreports', $contextmodule)) 
                         $menu['studentmanagement']["?a=admin&id={$id}&act=reportbyclass"]    = get_string("summaryreportbyclassgroup", "reader");
        if (has_capability('mod/reader:viewanddeleteattempts', $contextmodule)) 
                         $menu['studentmanagement']["?a=admin&id={$id}&act=viewattempts"]     = get_string("viewattempts", "reader");
        if (has_capability('mod/reader:changestudentslevelsandpromote', $contextmodule)) 
                         $menu['studentmanagement']["?a=admin&id={$id}&act=studentslevels"]   = get_string("studentslevels", "reader");
        if (has_capability('mod/reader:sendmessage', $contextmodule)) 
                         $menu['studentmanagement']["?a=admin&id={$id}&act=sendmessage"]      = get_string("sendmessage", "reader");
        if (has_capability('mod/reader:awardextrapoints', $contextmodule)) 
                         $menu['studentmanagement']["?a=admin&id={$id}&act=awardextrapoints"] = get_string("awardextrapoints", "reader");
        if (has_capability('mod/reader:changestudentslevelsandpromote', $contextmodule)) 
                         $menu['studentmanagement']["?a=admin&id={$id}&act=assignpointsbookshavenoquizzes"] = get_string("assignpointsbookshavenoquizzes", "reader");
        if (has_capability('mod/reader:manage', $contextmodule)) 
                         $menu['studentmanagement']["?a=admin&id={$id}&act=adjustscores"]     = get_string("adjustscores", "reader");
                         
        if (has_capability('mod/reader:addcoursequizzestoreaderquizzes', $contextmodule)) 
                         $menu['quizmanagement']["?a=admin&id={$id}&act=addquiz"]             = get_string("addquiztoreader", "reader");
        if (has_capability('mod/reader:downloadquizzesfromthereaderquizdatabase', $contextmodule)) 
                         $menu['quizmanagement']["dlquizzes.php?id={$id}"]                    = get_string("uploadquiztoreader", "reader");
        if (has_capability('mod/reader:downloadquizzesfromthereaderquizdatabase', $contextmodule)) 
                         $menu['quizmanagement']["dlquizzesnoq.php?id={$id}"]                 = get_string("uploaddatanoquizzes", "reader");
        if (has_capability('mod/reader:manage', $contextmodule)) 
                         $menu['quizmanagement']["updatecheck.php?id={$id}&checker=1"]        = get_string("quizmanagement", "reader");
        //if (has_capability('mod/reader:setgoal', $contextmodule)) 
        //                 $menu['quizmanagement']["?a=admin&id={$id}&act=setgoal"]             = get_string("setgoal", "reader");
        if (has_capability('mod/reader:forcedtimedelay', $contextmodule)) 
                         $menu['quizmanagement']["?a=admin&id={$id}&act=forcedtimedelay"]     = get_string("forcedtimedelay", "reader");
        if (has_capability('mod/reader:deletequizzes', $contextmodule)) 
                         $menu['quizmanagement']["?a=admin&id={$id}&act=editquiz"]            = get_string("editquiztoreader", "reader");
        if (has_capability('mod/reader:readerviewreports', $contextmodule)) 
                         $menu['quizmanagement']["?a=admin&id={$id}&act=summarybookreports"]  = get_string("summaryreportbybooktitle", "reader");
        if (has_capability('mod/reader:readerviewreports', $contextmodule)) 
                         $menu['quizmanagement']["?a=admin&id={$id}&act=fullbookreports"]     = get_string("fullreportbybooktitle", "reader");
        if (has_capability('mod/reader:checklogsforsuspiciousactivity', $contextmodule)) 
                         $menu['quizmanagement']["?a=admin&id={$id}&act=checksuspiciousactivity"] = get_string("checksuspiciousactivity", "reader");
        if (has_capability('mod/reader:readerviewreports', $contextmodule)) 
                         $menu['quizmanagement']["?a=admin&id={$id}&act=viewlogsuspiciousactivity"] = get_string("viewlogsuspiciousactivity", "reader");
        if (has_capability('mod/reader:selectquizzestomakeavailabletostudents', $contextmodule)) 
                         $menu['quizmanagement']["?a=admin&id={$id}&act=setindividualbooks"]  = get_string("setindividualbooks", "reader");
        if (has_capability('mod/reader:changereadinglevelorlengthfactor', $contextmodule)) 
                         $menu['booklevelmanagement']["?a=admin&id={$id}&act=changereaderlevel"]   = get_string("changereaderlevel", "reader");
        if (has_capability('mod/reader:downloadquizzesfromthereaderquizdatabase', $contextmodule)) 
                         $menu['booklevelmanagement']["?a=admin&id={$id}&act=changenumberofsectionsinquiz"]   = get_string("changenumberofsectionsinquiz", "reader");
        //if (has_capability('mod/reader:createcoversetsbypublisherlevel', $contextmodule)) 
                         //$menu['booklevelmanagement']["?a=admin&id={$id}&act=makepix_t"]           = get_string("createcoversets_t", "reader");
        //if (has_capability('mod/reader:createcoversetsbypublisherlevel', $contextmodule)) 
                         //$menu['booklevelmanagement']["?a=admin&id={$id}&act=makepix_l"]           = get_string("createcoversets_l", "reader");
        if (has_capability('mod/reader:readerviewreports', $contextmodule)) 
                         $menu['booklevelmanagement']["?a=admin&id={$id}&act=bookratingslevel"]    = get_string("bookratingslevel", "reader");
        if (has_capability('mod/reader:userdbmanagement', $contextmodule)) 
                         $menu['booklevelmanagement']["?a=admin&id={$id}&act=exportstudentrecords&excel=1"]    = get_string("exportstudentrecords", "reader");
        if (has_capability('mod/reader:userdbmanagement', $contextmodule)) 
                         $menu['booklevelmanagement']["?a=admin&id={$id}&act=importstudentrecord"]    = get_string("importstudentrecord", "reader");
        
        
    
        foreach ($menu as $key1=>$value1) {
            echo html_writer::tag('b', get_string($key1, "reader"));
            echo html_writer::start_tag('ul');
            foreach ($value1 as $key=>$value) {
                echo html_writer::start_tag('li');
                if ("?a=admin&id={$id}&act={$act}" != $key) {
                    echo html_writer::link($key, $value);
                } else {
                    echo $value;
                }
                echo html_writer::end_tag('li');
            }
            echo html_writer::end_tag('ul');
        }
        
        echo html_writer::empty_tag('br');
        echo html_writer::empty_tag('hr');
        
        if ($readercfg->reader_update == 1) {
            if (time() - $readercfg->reader_last_update > $readercfg->reader_update_interval) {
              echo $OUTPUT->box_start('generalbox');
              $days = round((time() - $readercfg->reader_last_update) / (24 * 3600));
              print_string("needtocheckupdates", "reader", $days);
              $alinkno   = new moodle_url("/mod/reader/admin.php", array("id"=>$id, "a"=>"admin"));
              $alinkyes  = new moodle_url("/mod/reader/updatecheck.php", array("id"=>$id));
              echo html_writer::link($alinkyes, get_string('yes', 'reader'));
              echo ' / ';
              echo html_writer::link($alinkno, get_string('no', 'reader'));
              echo $OUTPUT->box_end();
            }
        }
    }
    
    $options               = array();
    $options["a"]          = $a;
    $options["id"]         = $id;
    $options["act"]        = $act;
    $options["sort"]       = $sort;
    $options['orderby']    = $orderby;
    $options["grid"]       = $grid;
    $options["ct"]         = $ct;
    $options["searchtext"] = $searchtext;
    $options["excel"]      = 1;
    
    
/*
* Add NEW quizzes
*/
    if ($act == "addquiz" && has_capability('mod/reader:addcoursequizzestoreaderquizzes', $contextmodule)) {
        $quizesarray = array();
        if (!$quizesid) {
            if ($quizdata  = get_all_instances_in_course("quiz", $DB->get_record("course", array("id"=>$reader->usecourse)), NULL, true)) {
                $existdata['publisher'][0]  = get_string("selectalreadyexist", "reader");
                $existdata['difficulty'][0] = get_string("selectalreadyexist", "reader");
                $existdata['level'][0]      = get_string("selectalreadyexist", "reader");
            
                if ($publishers = $DB->get_records ("reader_publisher")) {
                    foreach ($publishers as $publishers_) {
                        $existdata['publisher'][$publishers_->publisher] = $publishers_->publisher;
                        $existdata['difficulty'][$publishers_->difficulty] = $publishers_->difficulty;
                        $existdata['level'][$publishers_->level] = $publishers_->level;
                        $existdata['quizid'][$publishers_->quizid] = $publishers_->quizid;
                    }
                }
                
                foreach ($quizdata as $quizdata_) {
                    if (!in_array ($quizdata_->id, $existdata['quizid'])) {
                        $quizesarray[$quizdata_->id] = $quizdata_->name;
                    }
                }
                
                $o  = "";
    
                if (count($quizesarray) > 0) {
                    $o .= $OUTPUT->box_start('generalbox');
                    
                    $o .= html_writer::tag('h2', get_string("selectquizzes", "reader"));
                    $o .= html_writer::empty_tag('br');
                    
                    $alink   = new moodle_url("/mod/reader/admin.php", array("id"=>$id, "a"=>"admin"));
                    
                    $o .= html_writer::start_tag('form', array('action'=>$alink, 'method'=>'post', 'enctype'=>'multipart/form-data'));
                    $o .= html_writer::start_tag('table', array('style'=>'width:100%'));
                    $o .= html_writer::start_tag('tr');
                    $o .= html_writer::tag('td', get_string("publisher", "reader"), array('style'=>'width:120px'));
                    $o .= html_writer::start_tag('td', array('style'=>'width:120px'));
                    $o .= html_writer::empty_tag('input', array('type'=>'text', 'name'=>'publisher', 'value'=>''));
                    $o .= html_writer::end_tag('td');
                    $o .= html_writer::start_tag('td', array('style'=>'width:160px'));
                    if ($existdata['publisher']) {
                        $o .= html_writer::start_tag('select', array('name'=>'publisherex'));
                        foreach ($existdata['publisher'] as $key=>$value) {
                            $o .= html_writer::tag('option', $value, array('value'=>$key));
                        }
                        $o .= html_writer::end_tag('select');
                    }
                    $o .= html_writer::end_tag('td');
                    $o .= html_writer::start_tag('td', array('rowspan'=>5));
                    $o .= html_writer::start_tag('select', array('size'=>10, 'multiple'=>'multiple', 'name'=>'quizesid[]'));
                    foreach ($quizesarray as $key=>$value) {
                        $o .= html_writer::tag('option', $value, array('value'=>$key));
                    }
                    $o .= html_writer::end_tag('select');
                    $o .= html_writer::end_tag('td');
                    $o .= html_writer::end_tag('tr');
                    $o .= html_writer::start_tag('tr');
                    $o .= html_writer::tag('td', get_string("level", "reader"));
                    $o .= html_writer::start_tag('td');
                    $o .= html_writer::empty_tag('input', array('type'=>'text', 'name'=>'level', 'value'=>''));
                    $o .= html_writer::end_tag('td');
                    $o .= html_writer::start_tag('td');
                    if ($existdata['level']) {
                        $o .= html_writer::start_tag('select', array('name'=>'levelex'));
                        foreach ($existdata['level'] as $key=>$value) {
                            $o .= html_writer::tag('option', $value, array('value'=>$key));
                        }
                        $o .= html_writer::end_tag('select');
                    }
                    $o .= html_writer::end_tag('td');
                    $o .= html_writer::end_tag('tr');
                    $o .= html_writer::start_tag('tr');
                    $o .= html_writer::tag('td', get_string("readinglevel", "reader"));
                    $o .= html_writer::start_tag('td');
                    $o .= html_writer::empty_tag('input', array('type'=>'text', 'name'=>'difficulty', 'value'=>''));
                    $o .= html_writer::end_tag('td');
                    $o .= html_writer::start_tag('td');
                    if ($existdata['difficulty']) {
                        $o .= html_writer::start_tag('select', array('name'=>'difficultyex'));
                        foreach ($existdata['difficulty'] as $key=>$value) {
                            $o .= html_writer::tag('option', $value, array('value'=>$key));
                        }
                        $o .= html_writer::end_tag('select');
                    }
                    $o .= html_writer::end_tag('td');
                    $o .= html_writer::end_tag('tr');
                    
                    $o .= html_writer::start_tag('tr');
                    $o .= html_writer::tag('td', get_string("lengthex11", "reader"));
                    $o .= html_writer::start_tag('td');
                    $o .= html_writer::empty_tag('input', array('type'=>'text', 'name'=>'difficulty', 'value'=>''));
                    $o .= html_writer::end_tag('td');
                    $o .= html_writer::tag('td', '');
                    $o .= html_writer::end_tag('tr');

                    $o .= html_writer::start_tag('tr');
                    $o .= html_writer::tag('td', get_string("image", "reader"));
                    $o .= html_writer::start_tag('td', array('colspan'=>2));
                    $o .= html_writer::empty_tag('input', array('type'=>'file', 'name'=>'userimage'));
                    $o .= html_writer::end_tag('td');
                    $o .= html_writer::end_tag('tr');
                    
                    $o .= html_writer::start_tag('tr');
                    $o .= html_writer::tag('td', get_string("ifimagealreadyexists", "reader"));
                    $o .= html_writer::start_tag('td', array('colspan'=>2));
                    $o .= html_writer::empty_tag('input', array('type'=>'text', 'name'=>'userimagename', 'value'=>''));
                    $o .= html_writer::end_tag('td');
                    $o .= html_writer::end_tag('tr');
                    
                    $o .= html_writer::start_tag('tr');
                    $o .= html_writer::tag('td', get_string("wordscount", "reader"));
                    $o .= html_writer::start_tag('td', array('colspan'=>2));
                    $o .= html_writer::empty_tag('input', array('type'=>'text', 'name'=>'wordscount', 'value'=>''));
                    $o .= html_writer::end_tag('td');
                    $o .= html_writer::end_tag('tr');

                    $o .= html_writer::start_tag('tr');
                    $o .= html_writer::tag('td', get_string("private", "reader"));
                    $o .= html_writer::start_tag('td', array('colspan'=>2));
                    $o .= html_writer::start_tag('select', array('name'=>'private'));
                    $o .= html_writer::tag('option', get_string('no', 'reader'), array('value'=>0));
                    $o .= html_writer::tag('option', get_string('yes', 'reader'), array('value'=>$reader->id));
                    $o .= html_writer::end_tag('select');
                    $o .= html_writer::end_tag('td');
                    $o .= html_writer::end_tag('tr');
                    
                    $o .= html_writer::start_tag('tr', array('align'=>'center'));
                    $o .= html_writer::start_tag('td', array('colspan'=>4, 'height'=>'60px'));
                    $o .= html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'submit', 'value'=>get_string('add', 'reader')));
                    $o .= html_writer::end_tag('td');
                    $o .= html_writer::end_tag('tr');
                    $o .= html_writer::end_tag('table');
                    $o .= html_writer::end_tag('form');
                    
                    echo $o;
                    
                    echo $OUTPUT->box_end();
                    //---------------------------------------//
                } else {
                    reader_red_notice (get_string('noquizzesfound', 'reader'));
                }
            }
        }


/*
* Edit Quiz
*/
    } else if ($act == "editquiz" && has_capability('mod/reader:deletequizzes', $contextmodule)) {
        if ($sort == "username") {
            $sort = "title";
        }
        $table = new html_table();
        
        $titlesarray = array (''=>'', get_string('title', 'reader')=>'title', get_string('publisher', 'reader')=>'publisher', get_string('level', 'reader')=>'level', get_string('readinglevel', 'reader')=>'rlevel', get_string('length', 'reader')=>'length', get_string('timesquiztaken', 'reader')=>'qtaken', get_string('averagepoints', 'reader')=>'apoints', get_string('options', 'reader')=>'');
        
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act='.$act);
        $table->align = array ("center", "left", "left", "center", "center", "center", "center", "center", "center");
        $table->width = "100%";

        $books = $DB->get_records ("reader_publisher");
        
        foreach ($books as $book) {
            $totalgrade = 0;
            $totalpointsaverage = 0;
            $correctpoints = 0;
        
            $answersgrade = $DB->get_records ("reader_question_instances", array("quiz"=>$book->id)); // ??????? ???????? ???? ?????? ?????
            foreach ($answersgrade as $answersgrade_) {
                $totalgrade += $answersgrade_->grade;
            }
            
            $i = 0;
            
            $attemptsofbook = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE quizid= ?  and reader= ? ", array($book->id, $reader->id));
            foreach ($attemptsofbook as $attemptsofbook_) {
                $i++;
                $totalpoints = round(($attemptsofbook_->sumgrades / $totalgrade) * 100, 2);
                $totalpointsaverage += $totalpoints;
                
                if ($totalpoints >= $reader->percentforreading) {
                    $correctpoints += 1;
                }
            }
            
            if ($i != 0){
                $averagepoint = round($totalpointsaverage / $i);
            } else {
                $averagepoint = 0;
            }
            
            $timesoftaken = $i;
            
            if ($book->hidden == 1) {
                $book->name = '<font color="#666666">'.$book->name.' - '.get_string('hidden', 'reader').'</font>';
            }
            
            $alink   = new moodle_url("/mod/reader/admin.php", array("id"=>$id, "act"=>$act, "deletebook"=>$book->id, "a"=>"admin"));
            
            $table->data[] = array (html_writer::empty_tag('input', array('type'=>'checkbox', 'name'=>'bookid['.$book->id.']')),
                                    $book->name,
                                    $book->publisher,
                                    $book->level,
                                    reader_get_reader_difficulty($reader, $book->id),
                                    reader_get_reader_length($reader, $book->id),
                                    $timesoftaken,
                                    $averagepoint.'%',
                                    reader_confirm_link($alink, get_string('delete', 'reader'), get_string('deletethisbook', 'reader')));
        }
        
        $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
        
        $o  = "";
        
        if (!isset($needdeleteattemptsfirst)) {
            $alink   = new moodle_url("/mod/reader/admin.php", array("id"=>$id, "act"=>$act, "a"=>"admin"));
            $o .= html_writer::start_tag('form', array('action'=>$alink, 'method'=>'post'));
        
            if ($table) {
                $o .= html_writer::table($table);
            }
            
            $o .= html_writer::empty_tag('input', array('type'=>'button', 'value'=>get_string('selectall', 'reader'), 'onclick'=>'checkall();'));
            $o .= html_writer::empty_tag('input', array('type'=>'button', 'value'=>get_string('deselectall', 'reader'), 'onclick'=>'uncheckall();'));
            $o .= html_writer::empty_tag('br');
            $o .= html_writer::empty_tag('br');
            $o .= html_writer::empty_tag('input', array('type'=>'submit', 'value'=>get_string('hidebooks', 'reader'), 'name'=>'hidebooks'));
            $o .= html_writer::empty_tag('input', array('type'=>'submit', 'value'=>get_string('unhidebooks', 'reader'), 'name'=>'unhidebooks'));
            $o .= html_writer::end_tag('form');
        } else {
            unset($table);
            $options["excel"] = 0;
            
            $table->head = array(get_string('id', 'reader'), get_string('date', 'reader'), get_string('user', 'reader'), get_string('sumgrades', 'reader'), get_string('status', 'reader'));
            $table->align = array ("center", "left", "left", "center", "center");
            $table->width = "80%";
            
            foreach ($needdeleteattemptsfirst as $attemptdata) {
                if ($attemptdata->timefinish >= $reader->ignordate) {
                    $status = "active";
                } else {
                    $status = "inactive";
                } 
            
                $userdata = $DB->get_record("user", array("id"=>$attemptdata->userid));
                $table->data[] = array ($attemptdata->id,
                                        date("d M Y", $attemptdata->timefinish),
                                        fullname($userdata),
                                        round($attemptdata->sumgrades, 2),
                                        $status
                );
            }
            
            reader_red_notice(get_string("needdeletethisattemptstoo", "reader"));
            
            if ($table) {
                $o .= html_writer::table($table);
            }
            
            $alink = new moodle_url("/mod/reader/admin.php", array("id"=>$id, "act"=>$act, "a"=>"admin", "deletebook"=>$deletebook));
            
            $o .= html_writer::start_tag('form', array('action'=>$alink, 'method'=>'post'));
            $o .= html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'deleteallattempts', 'value'=>1));
            $o .= html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'Delete'));
            $o .= html_writer::end_tag('form');
            $o .= $OUTPUT->single_button(new moodle_url("/mod/reader/admin.php",$options), get_string("cancel"), 'post', $options);
        }
        
        echo $o;


/*
* View Reports
*/
    } else if ($act == "reports" && has_capability('mod/reader:readerviewreports', $contextmodule)) {
        $table = new html_table();
        
        $titlesarray = array ('Image'=>'', 'Username'=>'username', 'Fullname<br />Click to view screen'=>'fullname', 'Start level'=>'startlevel', 'Current level'=>'currentlevel', 'Taken Quizzes'=>'tquizzes', 'Passed<br /> Quizzes'=>'cquizzes', 'Failed<br /> Quizzes'=>'iquizzes', 'Total Points'=>'totalpoints', 'Total words<br /> this term'=>'totalwordsthisterm', 'Total words<br /> all terms'=>'totalwordsallterms');
        
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act=reports&grid='.$grid.'&searchtext='.$searchtext.'&page='.$page);
        $table->align = array ("center", "left", "left", "center", "center", "center", "center", "center", "center", "center", "center");
        $table->width = "100%";
        
        if ($excel) {
            $myxls->write_string(0, 0, 'Summary Report by Student',$formatbc);
            $myxls->write_string(1, 0, 'Date: '.$exceldata['time'].'; Course name: '.$exceldata['course_shotname'].'; Group: '.$exceldata['groupname']);
            $myxls->set_row(0, 30);
            $myxls->set_column(0,1,20);
            $myxls->set_column(2,10,15);
            
            $myxls->write_string(2, 0, 'Username',$formatbc);
            $myxls->write_string(2, 1, 'Fullname',$formatbc);
            $myxls->write_string(2, 2, 'Groups',$formatbc);
            $myxls->write_string(2, 3, 'Start level',$formatbc);
            $myxls->write_string(2, 4, 'Current level',$formatbc);
            $myxls->write_string(2, 5, 'Taken Quizzes',$formatbc);
            $myxls->write_string(2, 6, 'Passed Quizzes',$formatbc);
            $myxls->write_string(2, 7, 'Failed Quizzes',$formatbc);
            $myxls->write_string(2, 8, 'Total Points',$formatbc);
            $myxls->write_string(2, 9, 'Total words this term',$formatbc);
            $myxls->write_string(2, 10, 'Total words all terms',$formatbc);
        }
        
        if (!$grid) {
            $grid = NULL;
        }
        
        $cachename = md5('admin-reports-'.$reader->id."-".$grid."-".$ct."-".$searchtext);
        
        if (!empty($_GET['sort']) || $excel) {
            if (!$table->data = reader_get_cache_obj($cachename)) {

                $coursestudents = get_enrolled_users($context, NULL, $grid);
                $groupsdata     = array();

                foreach ($coursestudents as $coursestudent) {
                    if (reader_check_search_text($searchtext, $coursestudent)) {
                        $picture = $OUTPUT->user_picture($coursestudent,array($course->id, true, 0, true));
                        
                        if ($excel) {
                            //---------------------Groups for Excel------------//
                            if ($usergroups = groups_get_all_groups($course->id, $coursestudent->id)){
                                foreach ($usergroups as $group){
                                    $groupsdata[$coursestudent->username] .= $group->name.', ';
                                }
                                $groupsdata[$coursestudent->username] = substr($groupsdata[$coursestudent->username], 0, -2);
                            }
                            //---------------------------------------------------//
                        }
                        
                        
                        $data = array('totalwordsthisterm'=>0, 'totalwordsallterms'=>0);
                        
                        if ($attempts = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE userid= ?  and reader= ?  and timefinish > ?", array($coursestudent->id, $reader->id, $reader->ignordate))) {
                            foreach ($attempts as $attempt) {
                                if (strtolower($attempt->passed) == "true") {
                                    if ($attempt->preview == 0) {
                                        $bookdata = $DB->get_record("reader_publisher", array("id"=>$attempt->quizid));
                                    } else {
                                        $bookdata = $DB->get_record("reader_noquiz", array("id"=>$attempt->quizid));
                                    }
                                    if (!isset($data['totalwordsthisterm'])) $data['totalwordsthisterm'] = 0;
                                    $data['totalwordsthisterm'] += $bookdata->words;
                                }
                            }
                        }
                        
                        if ($attempts = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE userid= ? ", array($coursestudent->id))) {
                            foreach ($attempts as $attempt) {
                                if (strtolower($attempt->passed) == "true") {
                                    if ($attempt->preview == 0) {
                                        $bookdata = $DB->get_record("reader_publisher", array("id"=>$attempt->quizid));
                                    } else {
                                        $bookdata = $DB->get_record("reader_noquiz", array("id"=>$attempt->quizid));
                                    }
                                    if (!isset($data['totalwordsallterms'])) $data['totalwordsallterms'] = 0;
                                    $data['totalwordsallterms'] += $bookdata->words;
                                }
                            }
                        }
                        
                        if ($attemptdata = reader_get_student_attempts($coursestudent->id, $reader)) {
                            if (has_capability('mod/reader:viewstudentreaderscreens', $contextmodule)) {
                                $link = reader_fullname_link_viewasstudent($coursestudent, "grid={$grid}&searchtext={$searchtext}&page={$page}&sort={$sort}&orderby={$orderby}");
                            } else {
                                $link = reader_fullname_link_t($coursestudent);
                            }
                            
                            $table->data[] = array ($picture,
                                                    reader_user_link_t($coursestudent),
                                                    $link,
                                                    $attemptdata[1]['startlevel'],
                                                    $attemptdata[1]['currentlevel'],
                                                    $attemptdata[1]['countattempts'],
                                                    $attemptdata[1]['correct'], 
                                                    $attemptdata[1]['incorrect'], 
                                                    $attemptdata[1]['totalpoints'],
                                                    $data['totalwordsthisterm'],
                                                    $data['totalwordsallterms']
                                                    );
                        } else {
                            if (has_capability('mod/reader:viewstudentreaderscreens', $contextmodule)) {
                                $link = reader_fullname_link_viewasstudent($coursestudent);
                            } else {
                                $link = reader_fullname_link_t($coursestudent);
                            }
                            
                            $table->data[] = array ($picture,
                                                    reader_user_link_t($coursestudent),
                                                    $link,
                                                    $attemptdata[1]['startlevel'],
                                                    $attemptdata[1]['currentlevel'],
                                                    0,0,0,0,0,0);
                        }
                    }
                }
            
                reader_save_cache_obj($cachename, $table->data);
            }
            
            $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);

            if ($excel) {
                foreach ($table->data as $tabledataarray) {
                    $myxls->write_string($row, 0, strip_tags($tabledataarray[1]));
                    $myxls->write_string($row, 1, strip_tags($tabledataarray[2]));
                    
                    if (isset($groupsdata[strip_tags($tabledataarray[1])]))
                        $myxls->write_string($row, 2, (string) $groupsdata[strip_tags($tabledataarray[1])]);
                    
                    $myxls->write_number($row, 3, (int) $tabledataarray[3]);
                    $myxls->write_number($row, 4, (int) $tabledataarray[4]);
                    $myxls->write_number($row, 5, (int) $tabledataarray[5]);
                    $myxls->write_number($row, 6, (int) $tabledataarray[6]);
                    $myxls->write_number($row, 7, (int) $tabledataarray[7]);
                    $myxls->write_string($row, 8, $tabledataarray[8]);
                    $myxls->write_string($row, 9, (int) $tabledataarray[9]);
                    $myxls->write_string($row, 10, (int) $tabledataarray[10]);
                    $row++;
                }
            }

            if ($excel) {
                $workbook->close();
                die();
            }
            
            reader_excel_download_btn();
        
        } //END is sort
        
        if (!empty($book))
            $link = new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act, 'sort'=>$sort, 'orderby'=>$orderby, 'book'=>$book, 'grid'=>$grid));
        else
            $link = new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act, 'sort'=>$sort, 'orderby'=>$orderby, 'grid'=>$grid));
        
        echo html_writer::start_tag('form', array('action'=>$link, 'method'=>'post', 'id'=>'id_reader_reports_form', 'class'=>'popupform'));
        
        reader_print_search_form_sub ($id, $act);
        
        reader_print_group_select_box_sub($course->id);
        
        reader_select_perpage_sub($id, $act, $sort, $orderby, $grid);
        
        echo html_writer::start_tag('div', array('class'=>'fr'));
        echo html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'submit', 'value'=>get_string('go', 'reader'), 'style'=>'margin-right: 10px;'));
        echo html_writer::end_tag('div');
        echo html_writer::tag('div', '', array('class'=>'clear'));
        
        echo html_writer::end_tag('form');
        
        echo html_writer::tag('div', '', array('class'=>'clear'));
        
        
        if (!empty($_GET['sort']) || $excel) {
            list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);
            
            echo $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 
            
            if ($table) {
                echo html_writer::table($table);
            }
            
            echo $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 
        }
        
/*
* View Full Student Reports
*/
    } else if ($act == "fullreports" && has_capability('mod/reader:readerviewreports', $contextmodule)) {
        $table = new html_table();
        if ($reader->wordsorpoints == "words") {
            if ($reader->checkbox == 1) {
                $titlesarray = array ('Image'=>'', 'Username'=>'username', 'Fullname<br />Click to view screen'=>'fullname', 'Check'=>'', 'Date'=>'date', 'S-Level'=>'slevel', 'B-Level'=>'blevel', get_string('title', 'reader')=>'title', 'Score'=>'', 'P/F/C'=>'', 'Words'=>'', 'Total Words'=>'');
                $table->align = array ("center", "left", "left", "center", "center", "center", "center", "left", "center", "center", "center", "center");
            } else {
                $titlesarray = array ('Image'=>'', 'Username'=>'username', 'Fullname<br />Click to view screen'=>'fullname', 'Date'=>'date', 'S-Level'=>'slevel', 'B-Level'=>'blevel', get_string('title', 'reader')=>'title', 'Score'=>'', 'P/F/C'=>'', 'Words'=>'', 'Total Words'=>'');
                $table->align = array ("center", "left", "left", "center", "center", "center", "left", "center", "center",  "center");
            }
        } else {
            if ($reader->checkbox == 1) {
                $titlesarray = array ('Image'=>'', 'Username'=>'username', 'Fullname<br />Click to view screen'=>'fullname', 'Check'=>'', 'Date'=>'date', 'S-Level'=>'slevel', 'B-Level'=>'blevel', get_string('title', 'reader')=>'title', 'Score'=>'', 'P/F/C'=>'', 'Points'=>'', get_string('length', 'reader')=>'', 'Total Points'=>'');
                $table->align = array ("center", "left", "left", "center", "center", "center", "center", "left", "center", "center", "center", "center", "center");
            } else {
                $titlesarray = array ('Image'=>'', 'Username'=>'username', 'Fullname<br />Click to view screen'=>'fullname', 'Date'=>'date', 'S-Level'=>'slevel', 'B-Level'=>'blevel', get_string('title', 'reader')=>'title', 'Score'=>'', 'P/F/C'=>'', 'Points'=>'', get_string('length', 'reader')=>'', 'Total Points'=>'');
                $table->align = array ("center", "left", "left", "center", "center", "center", "left", "center", "center", "center", "center", "center");
            }
        }
        
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act=fullreports&grid='.$grid.'&searchtext='.$searchtext.'&page='.$page.'&ct='.$ct);
        $table->width = "100%";
        
        if ($excel) {
            $myxls->write_string(0, 0, 'Full Report by Student',$formatbc);
            $myxls->write_string(1, 0, 'Date: '.$exceldata['time'].'; Course name: '.$exceldata['course_shotname'].'; Group: '.$exceldata['groupname']);
            $myxls->set_row(0, 30);
            $myxls->set_column(0,1,20);
            $myxls->set_column(2,10,15);
            
            $myxls->write_string(2, 0, 'Username',$formatbc);
            $myxls->write_string(2, 1, 'Fullname',$formatbc);
            $myxls->write_string(2, 2, 'Groups',$formatbc);
            if ($reader->wordsorpoints == "words") {
                if ($reader->checkbox == 1) {
                    $myxls->write_string(2, 3, 'Check',$formatbc);
                    $myxls->write_string(2, 4, 'Date',$formatbc);
                    $myxls->write_string(2, 5, 'S-Level',$formatbc);
                    $myxls->write_string(2, 6, 'B-Level',$formatbc);
                    $myxls->write_string(2, 7, get_string('title', 'reader'),$formatbc);
                    $myxls->write_string(2, 8, 'Score',$formatbc);
                    $myxls->write_string(2, 9, 'P/F/C',$formatbc);
                    $myxls->write_string(2, 10, 'Words',$formatbc);
                    $myxls->write_string(2, 11, 'Total Words',$formatbc);
                } else {
                    $myxls->write_string(2, 3, 'Date',$formatbc);
                    $myxls->write_string(2, 4, 'S-Level',$formatbc);
                    $myxls->write_string(2, 5, 'B-Level',$formatbc);
                    $myxls->write_string(2, 6, get_string('title', 'reader'),$formatbc);
                    $myxls->write_string(2, 7, 'Score',$formatbc);
                    $myxls->write_string(2, 8, 'P/F/C',$formatbc);
                    $myxls->write_string(2, 9, 'Words',$formatbc);
                    $myxls->write_string(2, 10, 'Total Words',$formatbc);
                }
            } else {
                if ($reader->checkbox == 1) {
                    $myxls->write_string(2, 3, 'Check',$formatbc);
                    $myxls->write_string(2, 4, 'Date',$formatbc);
                    $myxls->write_string(2, 5, 'S-Level',$formatbc);
                    $myxls->write_string(2, 6, 'B-Level',$formatbc);
                    $myxls->write_string(2, 7, get_string('title', 'reader'),$formatbc);
                    $myxls->write_string(2, 8, 'Score',$formatbc);
                    $myxls->write_string(2, 9, 'P/F/C',$formatbc);
                    $myxls->write_string(2, 10, 'Points',$formatbc);
                    $myxls->write_string(2, 11, get_string('length', 'reader'),$formatbc);
                    $myxls->write_string(2, 12, 'Total Points',$formatbc);
                } else {
                    $myxls->write_string(2, 3, 'Date',$formatbc);
                    $myxls->write_string(2, 4, 'S-Level',$formatbc);
                    $myxls->write_string(2, 5, 'B-Level',$formatbc);
                    $myxls->write_string(2, 6, get_string('title', 'reader'),$formatbc);
                    $myxls->write_string(2, 7, 'Score',$formatbc);
                    $myxls->write_string(2, 8, 'P/F/C',$formatbc);
                    $myxls->write_string(2, 9, 'Points',$formatbc);
                    $myxls->write_string(2, 10, get_string('length', 'reader'),$formatbc);
                    $myxls->write_string(2, 11, 'Total Points',$formatbc);
                }
            }
        }
        
        if (!$grid) {
            $grid = NULL;
        }
        
        $cachename = md5('admin-fullreports-'.$reader->id."-".$grid."-".$ct."-".$searchtext);
        
        if (!empty($_GET['sort']) || $excel) {
            if (!$table->data = reader_get_cache_obj($cachename)) {
            
                if ($sort != 'username' && $sort != 'firstname') {
                    $coursestudents = get_enrolled_users($context, NULL, $grid);
                } else {
                    $coursestudents = get_enrolled_users($context, NULL, $grid);
                }
                
                $groupsdata = array();

                foreach ($coursestudents as $coursestudent) {
                    $picture = $OUTPUT->user_picture($coursestudent,array($course->id, true, 0, true));
                    $totable['first'] = true;
                    
                    if ($excel) {
                        //---------------------Groups for Excel------------//
                        if ($usergroups = groups_get_all_groups($course->id, $coursestudent->id)){
                            foreach ($usergroups as $group){
                                $groupsdata[$coursestudent->username] .= $group->name.', ';
                            }
                            $groupsdata[$coursestudent->username] = substr($groupsdata[$coursestudent->username], 0, -2);
                        }
                        //---------------------------------------------------//
                    }
                    
                    //----------------Count quizzes------------//
                    if (list($attemptdata, $summaryattemptdata) = reader_get_student_attempts($coursestudent->id, $reader)) {
                        $totalwords = 0;
                        foreach ($attemptdata as $attemptdata_) {
                            if (($attemptdata_['timefinish']>=$reader->ignordate && $ct == 1) || empty($ct)) {
                                if ($reader->wordsorpoints == "words") {
                                    if (reader_check_search_text($searchtext, $coursestudent, $attemptdata_)) {
                                        $showwords = 0;
                                        switch (strtolower($attemptdata_['passed'])) { 
                                            case "true": 
                                                $passedstatus = "P";
                                                $totalwords +=  $attemptdata_['words'];
                                                $showwords   =  $attemptdata_['words'];
                                                break; 
                                            case "false": 
                                                $passedstatus = "F"; 
                                                break; 
                                            case "cheated": 
                                                $passedstatus = "C";
                                                break; 
                                        }

                                        if (!$excel && $sort == 'date') {
                                            $attemptbooktime = array(date("d-M-Y", $attemptdata_['timefinish']), $attemptdata_['timefinish']);
                                        } else if (!$excel) {
                                            $attemptbooktime = date("d-M-Y", $attemptdata_['timefinish']);
                                        } else {
                                            $attemptbooktime = date("Y/m/d", $attemptdata_['timefinish']);
                                        }
                                        
                                        if (($totable['first'] || $sort == 'slevel' || $sort == 'blevel' || $sort == 'title' || $sort == 'date') || $excel) {
                                            list($linkusername, $username) = reader_user_link_t($coursestudent);
                                            if ($excel) $linkusername = $username;
                                            list($linkfullname, $username) = reader_fullname_link_t($coursestudent);

                                            if (has_capability('mod/reader:viewstudentreaderscreens', $contextmodule)) {
                                                list($linkfullname) = reader_fullname_link_viewasstudent($coursestudent, "grid={$grid}&searchtext={$searchtext}&page={$page}&sort={$sort}&orderby={$orderby}");
                                            }
                                            
                                            if ($reader->checkbox == 1) {
                                                $table->data[] = array ($picture,
                                                                    $linkusername,
                                                                    $linkfullname,
                                                                    reader_ra_checkbox($attemptdata_),
                                                                    $attemptbooktime,
                                                                    $attemptdata_['userlevel'],
                                                                    $attemptdata_['bookdiff'],
                                                                    $attemptdata_['booktitle'],
                                                                    $attemptdata_['persent']."%",
                                                                    $passedstatus,
                                                                    $attemptdata_['words'], 
                                                                    $totalwords);

                                            } else {
                                                $table->data[] = array ($picture,
                                                                    $linkusername,
                                                                    $linkfullname,
                                                                    $attemptbooktime,
                                                                    $attemptdata_['userlevel'],
                                                                    $attemptdata_['bookdiff'],
                                                                    $attemptdata_['booktitle'],
                                                                    $attemptdata_['persent']."%",
                                                                    $passedstatus,
                                                                    $attemptdata_['words'], 
                                                                    $totalwords);
                                            }
                                            $totable['first'] = false;
                                        } else {
                                            if ($reader->checkbox == 1) {
                                                $table->data[] = array ('','','',
                                                                   reader_ra_checkbox($attemptdata_),
                                                                   $attemptbooktime,
                                                                   $attemptdata_['userlevel'],
                                                                   $attemptdata_['bookdiff'],
                                                                   $attemptdata_['booktitle'],
                                                                   $attemptdata_['persent']."%",
                                                                   $passedstatus,
                                                                   $attemptdata_['words'], 
                                                                   $totalwords);
                                            } else {
                                                $table->data[] = array ('','','',
                                                                   $attemptbooktime,
                                                                   $attemptdata_['userlevel'],
                                                                   $attemptdata_['bookdiff'],
                                                                   $attemptdata_['booktitle'],
                                                                   $attemptdata_['persent']."%",
                                                                   $passedstatus,
                                                                   //$attemptdata_['bookpoints'],
                                                                   $attemptdata_['words'], 
                                                                   $totalwords);
                                            }
                                        }
                                    }
                                } else {
                                    if (reader_check_search_text($searchtext, $coursestudent, $attemptdata_)) {
                                        switch (strtolower($attemptdata_['passed'])) { 
                                            case "true": 
                                                $passedstatus = "P";
                                                break; 
                                            case "false": 
                                                $passedstatus = "F"; 
                                                break; 
                                            case "cheated": 
                                                $passedstatus = "C";
                                                break; 
                                        } 
                                        if (!$excel && $sort == 'date'){
                                            $attemptbooktime = array(date("d-M-Y", $attemptdata_['timefinish']), $attemptdata_['timefinish']);
                                        } else if (!$excel) {
                                            $attemptbooktime = date("d-M-Y", $attemptdata_['timefinish']);
                                        } else {
                                            $attemptbooktime = date("Y/m/d", $attemptdata_['timefinish']);
                                        }
                                        
                                        if (($totable['first'] || $sort == 'slevel' || $sort == 'blevel' || $sort == 'title' || $sort == 'date') || $excel) {
                                            list($linkusername, $username) = reader_user_link_t($coursestudent);
                                            if ($excel) $linkusername = $username;
                                            list($linkfullname, $username) = reader_fullname_link_t($coursestudent);
                                            if (has_capability('mod/reader:viewstudentreaderscreens', $contextmodule)) {
                                                list($linkfullname) = reader_fullname_link_viewasstudent($coursestudent, "grid={$grid}&searchtext={$searchtext}&page={$page}&sort={$sort}&orderby={$orderby}");
                                            }
                                            if ($reader->checkbox == 1) {
                                                $table->data[] = array ($picture,
                                                                    $linkusername,
                                                                    $linkfullname,
                                                                    reader_ra_checkbox($attemptdata_),
                                                                    $attemptbooktime,
                                                                    $attemptdata_['userlevel'],
                                                                    $attemptdata_['bookdiff'],
                                                                    $attemptdata_['booktitle'],
                                                                    $attemptdata_['persent']."%",
                                                                    $passedstatus,
                                                                    $attemptdata_['bookpoints'],
                                                                    $attemptdata_['booklength'], 
                                                                    $attemptdata_['totalpoints']);
                                            } else {
                                                $table->data[] = array ($picture,
                                                                    $linkusername,
                                                                    $linkfullname,
                                                                    $attemptbooktime,
                                                                    $attemptdata_['userlevel'],
                                                                    $attemptdata_['bookdiff'],
                                                                    $attemptdata_['booktitle'],
                                                                    $attemptdata_['persent']."%",
                                                                    $passedstatus,
                                                                    $attemptdata_['bookpoints'],
                                                                    $attemptdata_['booklength'], 
                                                                    $attemptdata_['totalpoints']);
                                            }
                                            $totable['first'] = false;
                                        } else {
                                            if ($reader->checkbox == 1) {
                                                $table->data[] = array ('','','',
                                                                   reader_ra_checkbox($attemptdata_),
                                                                   $attemptbooktime,
                                                                   $attemptdata_['userlevel'],
                                                                   $attemptdata_['bookdiff'],
                                                                   $attemptdata_['booktitle'],
                                                                   $attemptdata_['persent']."%",
                                                                   $passedstatus,
                                                                   $attemptdata_['bookpoints'],
                                                                   $attemptdata_['booklength'], 
                                                                   $attemptdata_['totalpoints']);
                                            } else {
                                                $table->data[] = array ('','','',
                                                                   $attemptbooktime,
                                                                   $attemptdata_['userlevel'],
                                                                   $attemptdata_['bookdiff'],
                                                                   $attemptdata_['booktitle'],
                                                                   $attemptdata_['persent']."%",
                                                                   $passedstatus,
                                                                   $attemptdata_['bookpoints'],
                                                                   $attemptdata_['booklength'], 
                                                                   $attemptdata_['totalpoints']);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                
                reader_save_cache_obj($cachename, $table->data);
            }

            if ($sort == 'slevel' || $sort == 'blevel' || $sort == 'title' || $sort == 'date') {
                $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
            }
            
        } //END sort
        
        if ($excel) {
            $formatDate =& $workbook->add_format();
            $formatDate->set_num_format(get_string('log_excel_date_format'));
            foreach ($table->data as $tabledataarray) {
                $myxls->write_string($row, 0, $tabledataarray[1]);
                $myxls->write_string($row, 1, strip_tags($tabledataarray[2]));
                
                if(isset($groupsdata[$tabledataarray[1]]))
                    $myxls->write_string($row, 2, (string) $groupsdata[$tabledataarray[1]]);
                
                if ($reader->checkbox == 1) {
                    $myxls->write_string($row, 3, $tabledataarray[3]);
                    $myxls->write_string($row, 4, strip_tags($tabledataarray[4]), $formatDate);
                    $myxls->write_string($row, 5, $tabledataarray[5]);
                    $myxls->write_string($row, 6, $tabledataarray[6]);
                    $myxls->write_string($row, 7, $tabledataarray[7]);
                    $myxls->write_string($row, 8, $tabledataarray[8]);
                    $myxls->write_string($row, 9, $tabledataarray[9]);
                    $myxls->write_string($row, 10, $tabledataarray[10]);
                    $myxls->write_string($row, 11, $tabledataarray[11]);
                    $myxls->write_string($row, 12, $tabledataarray[12]);
                } else {
                    $myxls->write_string($row, 3, strip_tags($tabledataarray[3]), $formatDate);
                    $myxls->write_string($row, 4, $tabledataarray[4]);
                    $myxls->write_string($row, 5, $tabledataarray[5]);
                    $myxls->write_string($row, 6, $tabledataarray[6]);
                    $myxls->write_string($row, 7, $tabledataarray[7]);
                    $myxls->write_string($row, 8, $tabledataarray[8]);
                    $myxls->write_string($row, 9, $tabledataarray[9]);
                    $myxls->write_string($row, 10, $tabledataarray[10]);
                    $myxls->write_string($row, 11, $tabledataarray[11]);
                }
                $row++;
            }
        }
        
        if ($excel) {
            $workbook->close();
            die();
        }
        
        reader_excel_download_btn();
        
        if (!empty($book))
            $link = new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act, 'sort'=>$sort, 'orderby'=>$orderby, 'book'=>$book, 'grid'=>$grid));
        else
            $link = new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act, 'sort'=>$sort, 'orderby'=>$orderby, 'grid'=>$grid));
        
        echo html_writer::start_tag('form', array('action'=>$link, 'method'=>'post', 'id'=>'id_reader_reports_form', 'class'=>'popupform'));
        
        reader_print_search_form_sub ($id, $act);
        
        reader_print_group_select_box_sub($course->id);
        
        reader_select_term_sub();
        
        reader_select_perpage_sub($id, $act, $sort, $orderby, $grid);
        
        echo html_writer::start_tag('div', array('class'=>'fr'));
        echo html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'submit', 'value'=>get_string('go', 'reader'), 'style'=>'margin-right: 10px;'));
        echo html_writer::end_tag('div');
        echo html_writer::tag('div', '', array('class'=>'clear'));
        
        echo html_writer::end_tag('form');
        
        echo html_writer::tag('div', '', array('class'=>'clear'));
        
        
        if (!empty($_GET['sort']) || $excel) {

            list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);

            $alinkpadding     = new moodle_url("/mod/reader/admin.php", array("a"=>"admin", "id"=>$id, "act"=>$act, "grid"=>$grid, "sort"=>$sort, "orderby"=>$orderby, "ct"=>$ct));

            echo $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 
            
            if ($table) {
                echo html_writer::table($table);
            }
            
            echo $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 
        
        }


/*
* View Summary Books Reports
*/
    } else if ($act == "summarybookreports" && has_capability('mod/reader:readerviewreports', $contextmodule)) {
        if ($sort == "username") {
            $sort = "title";
        }
        $table = new html_table();
        $titlesarray = array (get_string('title', 'reader')=>'title', get_string('publisher', 'reader')=>'publisher', get_string('level', 'reader')=>'level', get_string('readinglevel', 'reader')=>'rlevel', get_string('length', 'reader')=>'length', 'Times Quiz Taken'=>'qtaken', 'Average Points'=>'apoints', 'Passed'=>'passed', 'Failed'=>'failed', 'Pass Rate'=>'prate');
        
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act=summarybookreports&grid='.$grid.'&searchtext='.$searchtext.'&page='.$page);
        $table->align = array ("left", "left", "center", "center", "center", "center", "center", "center", "center", "center");
        $table->width = "100%";
        
        if ($excel) {
            $myxls->write_string(0, 0, 'Summary Report by Book Title',$formatbc);
            $myxls->write_string(1, 0, 'Date: '.$exceldata['time'].'; Course name: '.$exceldata['course_shotname'].'; Group: '.$exceldata['groupname']);
            $myxls->set_row(0, 30);
            $myxls->set_column(0,1,20);
            $myxls->set_column(2,10,15);
            
            $myxls->write_string(2, 0, get_string('title', 'reader'),$formatbc);
            $myxls->write_string(2, 1, get_string('publisher', 'reader'),$formatbc);
            $myxls->write_string(2, 2, get_string('level', 'reader'),$formatbc);
            $myxls->write_string(2, 3, get_string('readinglevel', 'reader'),$formatbc);
            $myxls->write_string(2, 4, get_string('length', 'reader'),$formatbc);
            $myxls->write_string(2, 5, 'Times Quiz Taken',$formatbc);
            $myxls->write_string(2, 6, 'Average Points',$formatbc);
            $myxls->write_string(2, 7, 'Passed',$formatbc);
            $myxls->write_string(2, 8, 'Failed',$formatbc);
            $myxls->write_string(2, 8, 'Pass Rate',$formatbc);
        }
        
        $books = $DB->get_records_sql ("SELECT * FROM {reader_publisher} WHERE hidden=? and private IN(0,?)", array(0, $reader->id));
        
        while(list($bookkey, $book) = each($books)) {
            if (reader_check_search_text($searchtext, '', $book)) {
                $totalgrade = 0;
                $totalpointsaverage = 0;
                $correctpoints = 0;

                $i = 0;
                
                $attemptsofbook = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE quizid= ? ", array($book->id));
                while(list($attemptsofbookkey, $attemptsofbook_) = each($attemptsofbook)) {
                    $i++;
                    $totalpointsaverage += $attemptsofbook_->persent;
                    
                    if (strtolower($attemptsofbook_->passed) == "true") {
                        $correctpoints += 1;
                    }
                }
                if ($i != 0) {
                    $averagepoint = round($totalpointsaverage / $i);
                    $prate = round(($correctpoints/$i) * 100);
                } else {
                    $averagepoint = 0;
                    $prate        = 0;
                }
                
                $timesoftaken = $i;

                $table->data[] = array (array('<a href="report.php?b='.$book->id.'">'.$book->name.'</a>', $book->name),
                                        $book->publisher,
                                        $book->level,
                                        reader_get_reader_difficulty($reader, $book->id),
                                        reader_get_reader_length($reader, $book->id),
                                        $timesoftaken,
                                        $averagepoint.'%',
                                        $correctpoints,
                                        ($timesoftaken - $correctpoints),
                                        $prate.'%');
            }
        }

        $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);

        if ($excel) {
            foreach ($table->data as $tabledataarray) {
                $myxls->write_string($row, 0, strip_tags($tabledataarray[0]));
                $myxls->write_string($row, 1, $tabledataarray[1]);
                $myxls->write_string($row, 2, $tabledataarray[2]);
                $myxls->write_string($row, 3, $tabledataarray[3]);
                $myxls->write_string($row, 4, $tabledataarray[4]);
                $myxls->write_string($row, 5, $tabledataarray[5]);
                $myxls->write_string($row, 6, $tabledataarray[6]);
                $myxls->write_string($row, 7, $tabledataarray[7]);
                $myxls->write_string($row, 8, $tabledataarray[8]);
                $myxls->write_string($row, 9, $tabledataarray[9]);
                $row++;
            }
        }
        
        if ($excel) {
            $workbook->close();
            die();
        }
        
        reader_excel_download_btn();
        
        reader_print_search_form ($id, $act);

        reader_select_perpage($id, $act, $sort, $orderby, $grid);

        list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);

        echo $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 
        
        if ($table) {
            echo html_writer::table($table);
        }
        
        echo $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 


/*
* View Full Books Reports
*/
    } else if ($act == "fullbookreports" && has_capability('mod/reader:readerviewreports', $contextmodule)) {
        if ($sort == "username") {
            $sort = "title";
        }
        $table = new html_table();
        
        $titlesarray = array (get_string('title', 'reader')=>'title', get_string('publisher', 'reader')=>'publisher', get_string('level', 'reader')=>'level', get_string('readinglevel', 'reader')=>'rlevel', 'Student Name'=>'sname', 'Student ID'=>'studentid', 'Passed/Failed'=>'');
        
        if ($excel) {
            $myxls->write_string(0, 0, 'Full Report by Book Title',$formatbc);
            $myxls->write_string(1, 0, 'Date: '.$exceldata['time'].'; Course name: '.$exceldata['course_shotname'].'; Group: '.$exceldata['groupname']);
            $myxls->set_row(0, 30);
            $myxls->set_column(0,1,20);
            $myxls->set_column(2,10,15);
            
            $myxls->write_string(2, 0, get_string('title', 'reader'),$formatbc);
            $myxls->write_string(2, 1, get_string('publisher', 'reader'),$formatbc);
            $myxls->write_string(2, 2, get_string('level', 'reader'),$formatbc);
            $myxls->write_string(2, 3, get_string('readinglevel', 'reader'),$formatbc);
            $myxls->write_string(2, 4, 'Student Name',$formatbc);
            $myxls->write_string(2, 5, 'Student ID',$formatbc);
            $myxls->write_string(2, 6, 'Passed/Failed',$formatbc);
        }
        
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act=fullbookreports&grid='.$grid.'&searchtext='.$searchtext.'&page='.$page);
        $table->align = array ("left", "left", "center", "center", "left", "left", "center");
        $table->width = "100%";
        
        $books = $DB->get_records ("reader_publisher");

        $usegroupidsql = "";
        $allids        = "";
        
        if ($grid) {
            $groupstudents = groups_get_members($grid);
            foreach ($groupstudents as $groupstudents_) {
                $allids .= $groupstudents_->id.",";
            }
            $allids = substr($allids, 0, -1);
            $usegroupidsql = "and ra.userid IN ({$allids})";
        }
        
        while(list($bookkey, $book) = each($books)) {
            if (reader_check_search_text($searchtext, '', $book)) {
                $totalgrade = 0;
                
                $attemptsofbook = $DB->get_records_sql("SELECT *,u.username,u.firstname,u.lastname FROM {reader_attempts} ra INNER JOIN {user} u ON u.id = ra.userid WHERE ra.quizid= ?  and ra.reader= ?  {$usegroupidsql}", array($book->id, $reader->id));
                
                while(list($attemptsofbookkey, $attemptsofbook_) = each($attemptsofbook)) {
                    $table->data[] = array (array('<a href="report.php?idh='.$id.'&q='.$book->quizid.'&mode=analysis&b='.$book->id.'">'.$book->name.'</a>', $book->name),
                                                $book->publisher,
                                                $book->level,
                                                reader_get_reader_difficulty($reader, $book->id),
                                                reader_fullname_link_t($attemptsofbook_),
                                                reader_user_link_t($attemptsofbook_),
                                                $attemptsofbook_->passed);
                }
            }
        }

        $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
        
        if ($excel) {
            foreach ($table->data as $tabledataarray) {
                $myxls->write_string($row, 0, strip_tags($tabledataarray[0]));
                $myxls->write_string($row, 1, $tabledataarray[1]);
                $myxls->write_string($row, 2, $tabledataarray[2]);
                $myxls->write_string($row, 3, $tabledataarray[3]);
                $myxls->write_string($row, 4, strip_tags($tabledataarray[4]));
                $myxls->write_string($row, 5, strip_tags($tabledataarray[5]));
                $myxls->write_string($row, 6, $tabledataarray[6]);
                $row++;
            }
        }
        
        if ($excel) {
            $workbook->close();
            die();
        }
        
        reader_excel_download_btn();
        
        reader_print_search_form ($id, $act);

        reader_print_group_select_box($course->id, new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act, 'sort'=>$sort, 'orderby'=>$orderby)));
        
        reader_select_perpage($id, $act, $sort, $orderby, $grid);
        
        list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);
        
        echo $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 
        
        if ($table) {
            echo html_writer::table($table);
        }
        
        echo $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 


/*
* View Attempts
*/
    } else if ($act == "viewattempts" && has_capability('mod/reader:viewanddeleteattempts', $contextmodule)) {
        $table = new html_table();
        
        if (!$searchtext && !$grid) {
            reader_red_notice(get_string("pleasespecifyyourclassgroup", "reader"));
        } else {
            if (has_capability('mod/reader:deletereaderattempts', $contextmodule)) {
                $titlesarray = array ('Username'=>'username', 'Fullname'=>'fullname', 'Book Name'=>'bname', 'AttemptID'=>'attemptid', 'Score'=>'score', 'P/F/C'=>'', 'Finishtime'=>'timefinish', 'Option'=>'');
            } else {
                $titlesarray = array ('Username'=>'username', 'Fullname'=>'fullname', 'Book Name'=>'bname', 'AttemptID'=>'attemptid', 'Score'=>'score', 'P/F/C'=>'', 'Finishtime'=>'timefinish');
            }
            
            $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act=viewattempts&page='.$page.'&grid='.$grid.'&searchtext='.$searchtext);
            $table->align = array ("left", "left", "left", "center", "center", "center", "center", "center");
            $table->width = "100%";
            
            if ($excel) {
                $myxls->write_string(0, 0, 'View and Delete Attempts',$formatbc);
                $myxls->set_row(0, 30);
                $myxls->set_column(0,1,20);
                $myxls->set_column(2,10,15);
                
                $myxls->write_string(2, 0, 'Username',$formatbc);
                $myxls->write_string(2, 1, 'Fullname',$formatbc);
                $myxls->write_string(2, 2, 'Book Name',$formatbc);
                $myxls->write_string(2, 3, 'AttemptID',$formatbc);
                $myxls->write_string(2, 4, 'Score',$formatbc);
                $myxls->write_string(2, 5, 'Finishtime',$formatbc);
                $myxls->write_string(2, 6, 'P/F/C',$formatbc);
            }

            if (!$searchtext && $grid) {
                $groupstudents = groups_get_members($grid);
                $allids = "";
                foreach ($groupstudents as $groupstudents_) {
                    $allids .= $groupstudents_->id.",";
                }
                $allids = substr($allids, 0, -1);
                
                if ($CFG->dbtype == "mysql") {
                    $attemptsdata = $DB->get_records_sql("SELECT ra.timefinish,ra.userid,ra.attempt,ra.persent,ra.id,ra.passed,rp.name,rp.publisher,rp.level,u.username,u.firstname,u.lastname FROM {reader_attempts} ra LEFT JOIN ({reader_publisher} rp, {user} u) ON (rp.id = ra.quizid AND u.id = ra.userid) WHERE ra.userid IN ({$allids})");
                } else {
                    $attemptsdata = $DB->get_records_sql("SELECT ra.timefinish,ra.userid,ra.attempt,ra.persent,ra.id,ra.quizid,ra.sumgrades,ra.passed,rp.name,rp.publisher,rp.level,rp.length,rp.image,rp.difficulty,rp.words FROM {reader_attempts} ra LEFT JOIN {reader_publisher} rp ON rp.id = ra.quizid WHERE ra.userid IN ({$allids}) ORDER BY ra.timefinish");
                     foreach ($attemptsdata as $key=>$value) {
                         $userdata = $DB->get_record("user", array("id"=>$value->userid));
                         $attemptsdata[$key]->username  = $userdata->username;
                         $attemptsdata[$key]->firstname = $userdata->firstname;
                         $attemptsdata[$key]->lastname  = $userdata->lastname;
                     }
                 }
            } else if ($searchtext) {
                if (strstr($searchtext, '"')) {
                    $searchtextforsql = str_replace('\"', '"', $searchtext);
                    $searchtextforsql = explode('"', $searchtextforsql);
                } else {
                    $searchtextforsql = explode(" ", $searchtext);
                }
                if ($CFG->dbtype == "mysql") {
                    foreach ($searchtextforsql as $searchtext_) {
                        if ($searchtext_ && strlen($searchtext_) > 3) {
                            $searchsql .= " u.username LIKE '%{$searchtext_}%' OR u.firstname LIKE '%{$searchtext_}%' OR u.lastname LIKE '%{$searchtext_}%' OR rp.name LIKE '%{$searchtext_}%' OR rp.level LIKE '%{$searchtext_}%' OR rp.publisher LIKE '%{$searchtext_}%' OR";
                        }
                    }
                    $searchsql = substr($searchsql, 0, -2);
                    $attemptsdata = $DB->get_records_sql("SELECT ra.timefinish,ra.userid,ra.attempt,ra.persent,ra.id,ra.passed,rp.name,rp.publisher,rp.level,u.username,u.firstname,u.lastname FROM {reader_attempts} ra LEFT JOIN ({reader_publisher} rp, {user} u) ON (rp.id = ra.quizid AND u.id = ra.userid) WHERE {$searchsql}");
                } else {
                    $attemptsdata = $DB->get_records_sql("SELECT ra.timefinish,ra.userid,ra.attempt,ra.persent,ra.id,ra.passed,rp.name,rp.publisher,rp.level FROM {reader_attempts} ra LEFT JOIN {reader_publisher} rp ON rp.id = ra.quizid");

                    foreach ($attemptsdata as $key=>$value) {
                        $userdata = $DB->get_record("user", array("id"=>$value->userid));
                        $needmark = false;
                        foreach ($searchtextforsql as $searchtext_) {
                            if (strstr(strtolower($userdata->username),strtolower($searchtext_)) || strstr(strtolower($userdata->firstname),strtolower($searchtext_)) || strstr(strtolower($userdata->lastname),strtolower($searchtext_))) {
                              $needmark = true;
                            }
                        }
                        if ($needmark) {
                            $attemptsdata[$key]->username  = $userdata->username;
                            $attemptsdata[$key]->firstname = $userdata->firstname;
                            $attemptsdata[$key]->lastname  = $userdata->lastname;
                        } else {
                            unset($attemptsdata[$key]);
                        }
                    }
                }
            }
            
            foreach ($attemptsdata as $attemptdata) {
                if (!$excel) {
                    $attemptbooktime = array(date("d-M-Y", $attemptdata->timefinish), $attemptdata->timefinish);
                } else {
                    $attemptbooktime = date("Y/m/d", $attemptdata->timefinish);
                }
                
                switch (strtolower($attemptdata->passed)) {
                    case "true": 
                        $passedstatus = "P";
                        break; 
                    case "false": 
                        $passedstatus = "F"; 
                        break; 
                    case "cheated": 
                        $passedstatus = "C";
                        break; 
                } 
                
                $alink = new moodle_url("/mod/reader/admin.php", array("a"=>"admin", "id"=>$id, "act"=>$act, "page"=>$page, "sort"=>$sort, "attemptid"=>$attemptdata->id, "orderby"=>$orderby));
                
                if (has_capability('mod/reader:deletereaderattempts', $contextmodule)) {
                    $table->data[] = array (reader_user_link_t($attemptdata),
                                            reader_fullname_link_t($attemptdata), 
                                            $attemptdata->name, 
                                            $attemptdata->attempt, 
                                            $attemptdata->persent."%", 
                                            $passedstatus,
                                            $attemptbooktime, 
                                            '<a href="'.$alink.'" onclick="alert(\'`'.$attemptdata->name.'` quiz  for `'.$attemptdata->username.' ('.$attemptdata->firstname.' '.$attemptdata->lastname.')` has been deleted\');">Delete</a>');
                } else {
                    $table->data[] = array (reader_user_link_t($attemptdata),
                                            reader_fullname_link_t($attemptdata), 
                                            $attemptdata->name, 
                                            $attemptdata->attempt, 
                                            $attemptdata->persent."%", 
                                            $passedstatus,
                                            $attemptbooktime);
                }
            }

            $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
            
            if ($excel) {
                foreach ($table->data as $tabledataarray) {
                    $myxls->write_string($row, 0, strip_tags($tabledataarray[0]));
                    $myxls->write_string($row, 1, strip_tags($tabledataarray[1]));
                    $myxls->write_string($row, 2, $tabledataarray[2]);
                    $myxls->write_string($row, 3, $tabledataarray[3]);
                    $myxls->write_string($row, 4, $tabledataarray[4]);
                    $myxls->write_string($row, 5, $tabledataarray[5]);
                    $myxls->write_string($row, 6, $tabledataarray[6]);
                    $row++;
                }
            }
            
            if ($excel) {
                $workbook->close();
                die();
            }
            
            reader_excel_download_btn();
        }
        
        reader_print_search_form ($id, $act);
        
        reader_print_group_select_box($course->id, new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act, 'sort'=>$sort, 'orderby'=>$orderby)));
        
        reader_select_perpage($id, $act, $sort, $orderby, $grid);
        
        list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);
        
        echo $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 
        
        if ($table) {
            echo html_writer::table($table);
        }
        
        echo $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 
        
        if (has_capability('mod/reader:deletequizzes', $contextmodule)) {
            $link = new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act, 'sort'=>$sort, 'orderby'=>$orderby, 'grid'=>$grid));
            
            $o  = "";
            $o .= html_writer::start_tag('form', array('action'=>$link, 'method'=>'post'));
            $o .= html_writer::start_tag('div');
            $o .= html_writer::start_tag('div', array('style'=>'margin:20px 0;font-size:16px;'));
            $o .= get_string("restoredeletedattempt", "reader");
            $o .= html_writer::end_tag('div');
            $o .= html_writer::tag('div', get_string("studentuserid", "reader"), array('style'=>'float:left;width:200px;'));
            $o .= html_writer::start_tag('div', array('style'=>'float:left;width:200px;'));
            $o .= html_writer::empty_tag('input', array('type'=>'text', 'name'=>'studentuserid', 'value'=>'', 'style'=>'width:120px;'));
            $o .= html_writer::end_tag('div');
            $o .= html_writer::tag('div', '', array('style'=>'clear:both;'));
            $o .= html_writer::tag('div', 'or');
            $o .= html_writer::tag('div', get_string("studentusername", "reader"), array('style'=>'float:left;width:200px;'));
            $o .= html_writer::start_tag('div', array('style'=>'float:left;width:200px;'));
            $o .= html_writer::empty_tag('input', array('type'=>'text', 'name'=>'studentusername', 'value'=>'', 'style'=>'width:120px;'));
            $o .= html_writer::end_tag('div');
            $o .= html_writer::tag('div', '', array('style'=>'clear:both;'));
            $o .= html_writer::tag('div', get_string("bookquiznumber", "reader"), array('style'=>'float:left;width:200px;'));
            $o .= html_writer::start_tag('div', array('style'=>'float:left;width:200px;'));
            $o .= html_writer::empty_tag('input', array('type'=>'text', 'name'=>'bookquiznumber', 'value'=>'', 'style'=>'width:120px;'));
            $o .= html_writer::end_tag('div');
            $o .= html_writer::tag('div', '', array('style'=>'clear:both;'));
            $o .= html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'submit', 'value'=>'Restore'));
            $o .= html_writer::end_tag('div');
            $o .= html_writer::end_tag('form');
            
            echo $o;
        }
        

/*
* View Students Levels
*/
    } else if ($act == "studentslevels" && has_capability('mod/reader:changestudentslevelsandpromote', $contextmodule)) {
    
        $table = new html_table();
    
        $titlesarray = array ('Image'=>'', 'Username'=>'username', 'Fullname<br />Click to view screen'=>'fullname', 'Start level'=>'startlevel', 'Current level'=>'currentlevel', 'NoPromote'=>'nopromote', 'Stop Promo At'=>'promotionstops', 'Goal'=>'goal');
        
        //----------If need to set individual strict users acsess-----//
        if ($reader->individualstrictip == 1) {
            $titlesarray['Restrict IP'] = '';
        }
        
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act='.$act.'&page='.$page.'&grid='.$grid.'&searchtext='.$searchtext);
        $table->align = array ("center", "left", "left", "center", "center", "center", "center", "center", "center");
        $table->width = "100%";
        
        if (!$grid) {
            $grid = NULL;
        }
        
        $coursestudents = get_enrolled_users($context, NULL, $grid);
        
        foreach ($coursestudents as $coursestudent) {
            if (reader_check_search_text($searchtext, $coursestudent)) {
                if (!$studentlevel = $DB->get_record("reader_levels", array( "userid"=>$coursestudent->id,  "readerid"=>$reader->id))) {
                    $createlevel = new stdClass;
                    $createlevel->userid = $coursestudent->id;
                    $createlevel->startlevel = 0;
                    $createlevel->currentlevel = 0;
                    $createlevel->readerid = $reader->id;
                    $createlevel->promotionstop = $reader->promotionstop;
                    $createlevel->time = time();
                    $DB->insert_record('reader_levels', $createlevel);
                    $studentlevel = $DB->get_record("reader_levels", array( "userid"=>$coursestudent->id,  "readerid"=>$reader->id));
                }

                $picture = $OUTPUT->user_picture($coursestudent,array($course->id, true, 0, true));
                
                if ($studentlevel->startlevel >= 0) {
                    $startlevelt = reader_selectlevel_box ($coursestudent->id, $studentlevel, 'startlevel');
                } else {
                    $startlevelt = $studentlevel->startlevel;
                }
                
                if ($studentlevel->currentlevel >= 0) {
                    $currentlevelt = reader_selectlevel_box ($coursestudent->id, $studentlevel, 'currentlevel');
                } else {
                    $currentlevelt = $studentlevel->currentlevel;
                }
                
                $nopromote     = reader_yes_no_box ($coursestudent->id, $studentlevel);
                $promotionstop = reader_promotion_stop_box ($coursestudent->id, $studentlevel, 'promotionstop');
                $goalbox       = reader_goal_box ($coursestudent->id, $studentlevel, $reader);
                
                //----------If need to set individual strict users acsess-----//
                list($linkusername, $username) = reader_user_link_t($coursestudent);
                list($linkfullname, $username) = reader_fullname_link_t($coursestudent);
                
                if (has_capability('mod/reader:viewstudentreaderscreens', $contextmodule)) {
                    $linkfullname = reader_fullname_link_viewasstudent($coursestudent, "grid={$grid}&searchtext={$searchtext}&page={$page}&sort={$sort}&orderby={$orderby}");
                }
                
                if ($reader->individualstrictip == 1) {
                    $table->data[] = array ($picture,
                                            $linkusername,
                                            $linkfullname,
                                            array($startlevelt, $studentlevel->startlevel),
                                            array($currentlevelt, $studentlevel->currentlevel),
                                            array($nopromote, $studentlevel->nopromote),
                                            array($promotionstop, $studentlevel->promotionstop),
                                            array($goalbox, $studentlevel->goal),
                                            reader_selectip_form ($coursestudent->id, $reader));
                } else {
                    $table->data[] = array ($picture,
                                            $linkusername,
                                            $linkfullname,
                                            array($startlevelt, $studentlevel->startlevel),
                                            array($currentlevelt, $studentlevel->currentlevel),
                                            array($nopromote, $studentlevel->nopromote),
                                            array($promotionstop, $studentlevel->promotionstop),
                                            array($goalbox, $studentlevel->goal));
                }
            }
        }
        
        $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
        
        
        if ($grid) {
            $levels = array(0,1,2,3,4,5,6,7,8,9,10);
            
            $link = new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act, 'sort'=>$sort, 'orderby'=>$orderby, 'grid'=>$grid));
            
            $o  = "";
            $o .= html_writer::start_tag('form', array('action'=>$link, 'method'=>'post'));
            $o .= html_writer::start_tag('div');
            $o .= get_string("changestartlevel", "reader");
            $o .= html_writer::start_tag('select', array('name'=>'changeallstartlevel'));
            
            foreach ($levels as $value) {
                $o .= html_writer::tag('option', $value, array('value'=>$value));
            }
            
            $o .= html_writer::end_tag('select');
            $o .= html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'submit', 'value'=>'Change'));
            $o .= html_writer::end_tag('div');
            $o .= html_writer::end_tag('form');
            
            $levels = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14);
            $o .= html_writer::start_tag('form', array('action'=>$link, 'method'=>'post'));
            $o .= html_writer::start_tag('div');
            $o .= get_string("changecurrentlevel", "reader");
            $o .= html_writer::start_tag('select', array('name'=>'changeallcurrentlevel'));
            
            foreach ($levels as $value) {
                $o .= html_writer::tag('option', $value, array('value'=>$value));
            }
            
            $o .= html_writer::end_tag('select');
            $o .= html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'submit', 'value'=>'Change'));
            $o .= html_writer::end_tag('div');
            $o .= html_writer::end_tag('form');
            
            //Points goal for all students
            $levels = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15);
            $o .= html_writer::start_tag('form', array('action'=>$link, 'method'=>'post'));
            $o .= html_writer::start_tag('div');
            $o .= get_string("setuniformgoalinpoints", "reader");
            $o .= html_writer::start_tag('select', array('name'=>'changeallcurrentgoal'));
            
            foreach ($levels as $value) {
                $o .= html_writer::tag('option', $value, array('value'=>$value));
            }
            
            $o .= html_writer::end_tag('select');
            $o .= html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'submit', 'value'=>'Change'));
            $o .= html_writer::end_tag('div');
            $o .= html_writer::end_tag('form');
            
            //Words goal for all students
            $levels = array(0,5000,6000,7000,8000,9000,10000,12500,15000,20000,25000,30000,35000,40000,45000,50000,55000,60000,65000,70000,75000,80000,85000,90000,95000,100000,125000,150000,175000,200000,250000,300000,350000,400000,450000,500000);
            if (!in_array($reader->goal, $levels) && !empty($reader->goal)) {
                for ($i=0; $i<count($levels); $i++) {
                    if ($reader->goal < $levels[$i+1] && $reader->goal > $levels[$i]) {
                        $levels2[] = $reader->goal;
                        $levels2[] = $levels[$i];
                    } else {
                        $levels2[] = $levels[$i];
                    }
                }
                $levels = $levels2;
            }
            
            $o .= html_writer::start_tag('form', array('action'=>$link, 'method'=>'post'));
            $o .= html_writer::start_tag('div');
            $o .= get_string("setuniformgoalinwords", "reader");
            $o .= html_writer::start_tag('select', array('name'=>'changeallcurrentgoal'));
            
            foreach ($levels as $value) {
                $o .= html_writer::tag('option', $value, array('value'=>$value));
            }
            
            $o .= html_writer::end_tag('select');
            $o .= html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'submit', 'value'=>'Change'));
            $o .= html_writer::end_tag('div');
            $o .= html_writer::end_tag('form');
            
            $levels = array("Promo", "NoPromo");
            $o .= html_writer::start_tag('form', array('action'=>$link, 'method'=>'post'));
            $o .= html_writer::start_tag('div');
            $o .= get_string("changeallto", "reader");
            $o .= html_writer::start_tag('select', array('name'=>'changeallpromo'));
            
            foreach ($levels as $value) {
                $o .= html_writer::tag('option', $value, array('value'=>$value));
            }
            
            $o .= html_writer::end_tag('select');
            $o .= html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'submit', 'value'=>'Change'));
            $o .= html_writer::end_tag('div');
            $o .= html_writer::end_tag('form');
            
            //Stop Promo for all students
            $levels = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15);
            $o .= html_writer::start_tag('form', array('action'=>$link, 'method'=>'post'));
            $o .= html_writer::start_tag('div');
            $o .= get_string("changeallstoppromoto", "reader");
            $o .= html_writer::start_tag('select', array('name'=>'changeallstoppromo'));
            
            foreach ($levels as $value) {
                $o .= html_writer::tag('option', $value, array('value'=>$value));
            }
            
            $o .= html_writer::end_tag('select');
            $o .= html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'submit', 'value'=>'Change'));
            $o .= html_writer::end_tag('div');
            $o .= html_writer::end_tag('form');
            
            echo $o;
        }
        
        
        reader_print_search_form ($id, $act);
        
        reader_print_group_select_box($course->id, new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act, 'sort'=>$sort, 'orderby'=>$orderby)));
        
        reader_select_perpage($id, $act, $sort, $orderby, $grid);
        
        list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);
        
        echo $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 
        
        if ($table) {
            echo html_writer::table($table);
        }
        
        echo $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 


/*
* Change Reading Level or Length Factor
*/
    } else if ($act == "changereaderlevel" && has_capability('mod/reader:changereadinglevelorlengthfactor', $contextmodule)) {
        if ($reader->individualbooks == 0) 
            reader_red_notice(get_string('difficultiandlevelsforallbooks', 'reader'));
    
        $table = new html_table();
        
        if ($reader->individualbooks == 1) {
          $titlesarray = array (get_string('title', 'reader')=>'title', get_string('publisher', 'reader')=>'publisher', get_string('level', 'reader')=>'level', get_string('readinglevel', 'reader')=>'readinglevel', get_string('length', 'reader')=>'length');
        } else {
          $titlesarray = array (get_string('title', 'reader')=>'title', get_string('publisher', 'reader')=>'publisher', get_string('level', 'reader')=>'level', 'Words'=>'words', get_string('readinglevel', 'reader')=>'readinglevel', get_string('length', 'reader')=>'length');
        }
        
        //----------If need to set individual strict users acsess-----//
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act='.$act.'&grid='.$grid.'&publisher='.$publisher.'&page='.$page.'&searchtext='.$searchtext);
        
        if ($reader->individualbooks == 1) {
            $table->align = array ("left", "left", "left", "center", "center");
        } else {
            $table->align = array ("left", "left", "left", "center", "center", "center", "center");
        }
        
        $table->width = "100%";
        
        if ($publisher && $level) {
            $perpage = 1000;
        }
        
        if ($reader->individualbooks == 0) {
            $books = $DB->get_records("reader_publisher", array('hidden'=>0));
        } else {
            $books = $DB->get_records_sql("SELECT * FROM {reader_individual_books} ib INNER JOIN {reader_publisher} rp ON rp.id = ib.bookid AND ib.readerid= ? ", array($reader->id));
        }
        
        $totalgrade = 0;
        $totalpointsaverage = 0;
        $correctpoints = 0;
        
        foreach ($books as $book) {
            if (reader_check_search_text_quiz($searchtext, $book)) {
                if ((empty($publisher) || $publisher == $book->publisher) && (empty($level) || $level == $book->level)) {
                    if (has_capability('mod/reader:manage', $contextmodule)) {
                        $wordstitle = reader_change_words_text($book->id, $book->words);
                    } else {
                        $wordstitle = $book->words;
                    }
                    
                    if (has_capability('mod/reader:manage', $contextmodule)) {
                        $leveltitle = reader_change_level_text($book->id, $book->level);
                    } else {
                        $leveltitle = $book->level;
                    }
                    
                    if (has_capability('mod/reader:manage', $contextmodule)) {
                        $publishertitle = reader_change_publishertitle_text($book->id, $book->publisher);
                    } else {
                        $publishertitle = $book->publisher;
                    }

                    if ($reader->individualbooks == 1) {
                        $table->data[] = array ($book->name,
                                                $publishertitle,
                                                $leveltitle,
                                                trim(reader_select_difficulty_box (reader_get_reader_difficulty($reader, $book->id), $book->id)),
                                                trim(reader_select_length_box (reader_get_reader_length($reader, $book->id), $book->id)));
                    } else {
                        $table->data[] = array ($book->name,
                                                $publishertitle,
                                                $leveltitle,
                                                $wordstitle,
                                                trim(reader_select_difficulty_box (reader_get_reader_difficulty($reader, $book->id), $book->id)),
                                                trim(reader_select_length_box (reader_get_reader_length($reader, $book->id), $book->id)));
                    }
                }
            }
        }
        
        if ($sort == "username") { 
            $sort = "title"; 
        }
        
        $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
        
        //---------------Select publisher and level form--------------//
        
        $publisherform = array();

        $publishers = $DB->get_records ("reader_publisher", NULL, 'publisher');
        $link = new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act, 'sort'=>$sort, 'orderby'=>$orderby));
        
        $o  = "";
        $o .= html_writer::tag('div', get_string("massrename", "reader"), array('class'=>'changereaderlevel-masschanges'));
        $o .= html_writer::start_tag('div', array('class'=>'fl'));
        $o .= html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'link', 'value'=>$link, 'id'=>'id_reader_changereaderlevel_publisherselect_url'));
        $o .= html_writer::start_tag('select', array('id'=>'id_reader_changereaderlevel_publisherselect', 'name'=>'publisher'));
        $o .= html_writer::tag('option', get_string("selectpublisher", "reader"), array('value'=>''));
        
        $added = array();
        foreach ($publishers as $publisher_) {
            if (!in_array($publisher_->publisher, $added)) {
                $added[] = $publisher_->publisher;
                if ($publisher == $publisher_->publisher) {
                    $o .= html_writer::tag('option', $publisher_->publisher, array('value'=>$publisher_->publisher, 'selected'=>'selected'));
                } else {
                    $o .= html_writer::tag('option', $publisher_->publisher, array('value'=>$publisher_->publisher));
                }
            }
        }
            
        $o .= html_writer::end_tag('select');
        $o .= html_writer::end_tag('div');
        
        
        if ($publisher) {
            $link = new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act, 'sort'=>$sort, 'orderby'=>$orderby, 'publisher'=>$publisher));
            
            $levels = $DB->get_records ("reader_publisher", array('publisher'=>$publisher), 'level');
            $o .= html_writer::start_tag('div', array('class'=>'fl'));
            $o .= html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'link', 'value'=>$link, 'id'=>'id_reader_changereaderlevel_levelselect_url'));
            $o .= html_writer::start_tag('select', array('id'=>'id_reader_changereaderlevel_levelselect', 'name'=>'level'));
            $o .= html_writer::tag('option', 'Select Level', array('value'=>''));
                
            $added       = array();
            $level_      = array();
            $difficulty_ = array();
            $length_     = array();
            foreach ($levels as $levels_) {
                if (!$level || $levels_->level == $level) {
                    $level_[$levels_->level] = $levels_->id;
                    $difficulty_[$levels_->difficulty] = $levels_->id;
                    $length_[$levels_->length] = $levels_->id;
                }
                if (!in_array($levels_->level, $added)) {
                    $added[] = $levels_->level;
                    if ($level == $levels_->level) {
                        $o .= html_writer::tag('option', $levels_->level, array('value'=>$levels_->level, 'selected'=>'selected'));
                    } else {
                        $o .= html_writer::tag('option', $levels_->level, array('value'=>$levels_->level));
                    }
                }
            }
                    
            $o .= html_writer::end_tag('select');
            $o .= html_writer::end_tag('div');
        }
        
        $o .= html_writer::tag('div', '', array('class'=>'clear'));
            
        if ($publisher) {
            $o .= html_writer::empty_tag('input', array('type'=>'hidden', 'value'=>$id, 'id'=>'id_reader_id'));
            $o .= html_writer::empty_tag('input', array('type'=>'hidden', 'value'=>$publisher, 'id'=>'id_reader_changereaderlevel_publisher_id'));
            $o .= html_writer::empty_tag('input', array('type'=>'hidden', 'value'=>$level, 'id'=>'id_reader_changereaderlevel_level_id'));
            
            if ($reader->individualbooks == 1) {
                unset($difficulty_,$length_);
                $data = $DB->get_records_sql("SELECT ib.difficulty as ibdifficulty,ib.length as iblength FROM {reader_publisher} rp INNER JOIN {reader_individual_books} ib ON ib.bookid = rp.id WHERE ib.readerid= ?  and rp.publisher = ? ", array($reader->id, $publisher));
                foreach ($data as $data_) {
                    $difficulty_[$data_->ibdifficulty] = $data_->bookid;
                    $length_[$data_->iblength] = $data_->bookid;
                }
            }
            
            $o .= html_writer::start_tag('div');
            $o .= get_string("changepublisherfrom", "reader");
            $o .= html_writer::start_tag('select', array('name'=>'publisher', 'id'=>'id_reader_changereaderlevel_masspublisher_from'));
            $pubto = array();

            foreach ($publishers as $key=>$value) {
                if (!in_array($value->publisher, $pubto)) {
                    $pubto[] = $value->publisher;
                    if ($publisher == $value->publisher) {
                        $o .= html_writer::tag('option', $value->publisher, array('value'=>$value->publisher));
                    } else {
                        $o .= html_writer::tag('option', $value->publisher, array('value'=>$value->publisher, 'selected'=>'selected'));
                    }
                }
            }
            
            $o .= html_writer::end_tag('select');
            $o .= get_string("to", "reader");
            $o .= html_writer::empty_tag('input', array('type'=>'text', 'value'=>'', 'id'=>'id_reader_changereaderlevel_masspublisher_to'));
            $o .= html_writer::empty_tag('input', array('type'=>'button', 'value'=>'Change', 'id'=>'id_reader_changereaderlevel_masspublisher_click'));
            $o .= html_writer::end_tag('div');
            
            
            $o .= html_writer::start_tag('div');
            $o .= get_string("changelevelfrom", "reader");
            $o .= html_writer::start_tag('select', array('name'=>'publisher', 'id'=>'id_reader_changereaderlevel_masslevel_from'));
            $pubto = array();

            foreach ($level_ as $key=>$value) {
                if (!in_array($key, $pubto)) {
                    $pubto[] = $key;
                    if ($level == $key) {
                        $o .= html_writer::tag('option', $key, array('value'=>$key));
                    } else {
                        $o .= html_writer::tag('option', $key, array('value'=>$key, 'selected'=>'selected'));
                    }
                }
            }
            
            $o .= html_writer::end_tag('select');
            $o .= get_string("to", "reader");
            $o .= html_writer::empty_tag('input', array('type'=>'text', 'value'=>'', 'id'=>'id_reader_changereaderlevel_masslevel_to'));
            $o .= html_writer::empty_tag('input', array('type'=>'button', 'value'=>'Change', 'id'=>'id_reader_changereaderlevel_masslevel_click'));
            $o .= html_writer::end_tag('div');
            
            
            $lengtharray = array(0.50,0.60,0.70,0.80,0.90,1.00,1.10,1.20,1.30,1.40,1.50,1.60,1.70,1.80,1.90,2.00,3.00,4.00,5.00,6.00,7.00,8.00,9.00,10.00,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100,110,120,130,140,150,160,170,175,180,190,200,225,250,275,300,350,400);
            $o .= html_writer::start_tag('div');
            $o .= get_string("changelengthfrom", "reader");
            $o .= html_writer::start_tag('select', array('name'=>'publisher', 'id'=>'id_reader_changereaderlevel_masslength_from'));
            $pubto = array();
            ksort($length_); 
            reset($length_); 
            foreach ($length_ as $key=>$value) {
                if (!in_array($key, $pubto)) {
                    $pubto[] = $key;
                    if ($length == $key) {
                        $o .= html_writer::tag('option', $key, array('value'=>$key));
                    } else {
                        $o .= html_writer::tag('option', $key, array('value'=>$key, 'selected'=>'selected'));
                    }
                }
            }
            
            $o .= html_writer::end_tag('select');
            $o .= get_string("to", "reader");
            $o .= html_writer::start_tag('select', array('name'=>'length', 'id'=>'id_reader_changereaderlevel_masslength_to'));
            foreach ($lengtharray as $value) {
                $o .= html_writer::tag('option', $value, array('value'=>$value));
            }
            
            $o .= html_writer::end_tag('select');
            $o .= html_writer::empty_tag('input', array('type'=>'button', 'value'=>'Change', 'id'=>'id_reader_changereaderlevel_masslength_click'));
            $o .= html_writer::end_tag('div');
            
            
            $difficultyarray = array(0,1,2,3,4,5,6,7,8,9,10,11,12);
            $o .= html_writer::start_tag('div');
            $o .= get_string("changedifficultyfrom", "reader");
            $o .= html_writer::start_tag('select', array('name'=>'difficulty', 'id'=>'id_reader_changereaderlevel_massdifficulty_from'));
            $pubto = array();
            ksort($difficulty_); 
            reset($difficulty_); 
            foreach ($difficulty_ as $key=>$value) {
                if (!in_array($key, $pubto)) {
                    $pubto[] = $key;
                    if ($difficulty == $key) {
                        $o .= html_writer::tag('option', $key, array('value'=>$key));
                    } else {
                        $o .= html_writer::tag('option', $key, array('value'=>$key, 'selected'=>'selected'));
                    }
                }
            }
            
            $o .= html_writer::end_tag('select');
            $o .= get_string("to", "reader");
            $o .= html_writer::start_tag('select', array('name'=>'length', 'id'=>'id_reader_changereaderlevel_massdifficulty_to'));
            foreach ($difficultyarray as $value) {
                $o .= html_writer::tag('option', $value, array('value'=>$value));
            }
            
            $o .= html_writer::end_tag('select');
            $o .= html_writer::empty_tag('input', array('type'=>'button', 'value'=>'Change', 'id'=>'id_reader_changereaderlevel_massdifficulty_click'));
            $o .= html_writer::end_tag('div');
        }
        
        echo $o;

        list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);
        
        $alinkpadding     = new moodle_url("/mod/reader/admin.php", array("a"=>"admin", "id"=>$id, "act"=>$act, "grid"=>$grid, "sort"=>$sort, "orderby"=>$orderby, "publisher"=>$publisher));
        
        echo $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 
        
        if ($table) {
            echo html_writer::table($table);
        }
        
        echo $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 


/*
* Send Message
*/
    } else if ($act == "sendmessage" && has_capability('mod/reader:sendmessage', $contextmodule)) {
        class mod_reader_message_form extends moodleform {
            function definition() {
                global $CFG, $course, $editmessage;
                $mform      =& $this->_form;
            
                $groups     = groups_get_all_groups ($course->id);
                $grouparray = array ("0"=>"All Course students");
            
                foreach ($groups as $group) {
                    $grouparray[$group->id] = $group->name;
                }
                
                $timearray = array("168"=>"1 Week", "240"=>"10 Days", "336"=>"2 Weeks", "504"=>"3 Weeks", "1000000"=>"Indefinite");
            
                $mform->addElement('select', 'groupid', 'Group', $grouparray, 'size="5" multiple');
                $mform->addElement('select', 'activehours', 'Active Time (Hours)', $timearray);
                $mform->addElement('textarea', 'text', 'Text', 'wrap="virtual" rows="10" cols="70"');
                
                if ($editmessage) {
                    $message = $DB->get_record("reader_messages", array( "id"=>$editmessage));
                    $mform->setDefault('text', $message->text);
                    $mform->addElement('hidden', 'editmessage', $editmessage);
                }
                $this->add_action_buttons(false, $submitlabel="Send");
            }
        }
        $mform = new mod_reader_message_form("admin.php?a=admin&id={$id}&act=sendmessage");
        $mform->display();
        
        echo "Current Messages:";
        
        $textmessages = $DB->get_records_sql("SELECT * FROM {reader_messages} where teacherid = ? and instance = ? ORDER BY timemodified DESC", array($USER->id, $cm->instance));
        
        foreach ($textmessages as $textmessage) {
            $before = $textmessage->timebefore - time();
            
            $forgroupsarray = explode (',', $textmessage->users);
            
            $forgroup = "";
            $bgcolor  = '';
            
            foreach ($forgroupsarray as $forgroupsarray_) {
                if ($forgroupsarray_ == 0) {
                    $forgroup .= 'All, ';
                } else {
                    $forgroup .= groups_get_group_name($forgroupsarray_).', ';
                }
            }
            
            $forgroup = substr($forgroup, 0, -2);
            
            $bgcolor  = "";
            
            if ($textmessage->timemodified > (time() - ( 48 * 60 * 60))) {
                $bgcolor = '#CCFFCC';
            }
            
            $alinkedit     = new moodle_url("/mod/reader/admin.php", array("a"=>"admin", "id"=>$id, "act"=>$act, "editmessage"=>$textmessage->id));
            $alinkdelete   = new moodle_url("/mod/reader/admin.php", array("a"=>"admin", "id"=>$id, "act"=>$act, "deletemessage"=>$textmessage->id));
            
            $o  = "";
            $o .= html_writer::start_tag('table', array('width'=>'100%'));
            $o .= html_writer::start_tag('tr');
            $o .= html_writer::start_tag('td', array('align'=>'right'));
            $o .= html_writer::start_tag('table', array('cellspacing'=>0, 'cellpadding'=>0, 'class'=>'forumpost blogpost blog', 'bgcolor'=>$bgcolor, 'width'=>'90%'));
            $o .= html_writer::start_tag('tr');
            $o .= html_writer::start_tag('td', array('align'=>'left'));
            $o .= html_writer::start_tag('div', array('style'=>'margin-left: 10px;margin-right: 10px;'));
            $o .= format_text($textmessage->text);
            $o .= html_writer::start_tag('div', array('class'=>'tal-r'));
            $o .= html_writer::start_tag('small');
            $o .= round($before/(60 * 60 * 24), 2).' Days; ';
            $o .= 'Added: '.date("d M Y H:i", $textmessage->timemodified)."; ";
            $o .= 'Group: '. $forgroup.'; ';
            $o .= html_writer::link($alinkedit, 'Edit');
            $o .= ' / ';
            $o .= html_writer::link($alinkdelete, 'Delete');
            $o .= html_writer::end_tag('small');
            $o .= html_writer::end_tag('div');
            $o .= html_writer::end_tag('div');
            $o .= html_writer::end_tag('td');
            $o .= html_writer::end_tag('tr');
            $o .= html_writer::end_tag('table');
            $o .= html_writer::end_tag('td');
            $o .= html_writer::end_tag('tr');
            $o .= html_writer::end_tag('table');

            echo $o;
        }


/*
* Pix by Publisher
*/
/*
    } else if ($act == "makepix_t" && has_capability('mod/reader:createcoversetsbypublisherlevel', $contextmodule)) {
        $allbooks = $DB->get_records_sql("SELECT * FROM {reader_publisher}  where hidden = 0 ORDER BY publisher ASC, level ASC");
        
        $prehtml = "<img width='110'  height='160'  border='0' src='";
        $posthtml = '/> ';
        $nowpub = "";
        $nowlevel = "";
        $cellcount = -1;
        echo("<table border='0'");
        foreach ($allbooks as $thisbook) {
            if (($thisbook->publisher != $nowpub) || ($thisbook->level != $nowlevel) ){
                //close the row unless the row was closed because the last row was full
                if ($cellcount > 0) { 
                    echo ("</td></tr>");
                }
                $nowpub = $thisbook->publisher;
                $nowlevel = $thisbook->level ;
                echo("<tr ><td colspan='6' align='left'><font size=+2>$nowpub &nbsp; $nowlevel</font></td> </tr>");
                //let's make the cpu rest for 1 sec between sets so that the server isn't overloaded with requests
                echo("<tr valign='top' align='center'>");
                sleep(1);
                $cellcount = 0;
            }
            $thistitle = $thisbook->name;
            if ($cellcount > 5) {
                $cellcount = 1;
                
                $alink = new moodle_url("/file.php/{$reader->usecourse}/images/{$thisbook->image}");
                
                echo("</td></tr> <tr valign='top' align='center'><td $prehtml" . $alink . "'$posthtml" . "  <br />  $thistitle  </td>");
            } else {
                $cellcount++;
                echo("<td>" . "$prehtml" . "$thisbook->image". "'$posthtml". "<br />  $thistitle </td>");
            }
       
        }
        echo("</tr></table>");
*/


/*
* Pix by level
*/
/*
    } else if ($act == "makepix_l" && has_capability('mod/reader:createcoversetsbypublisherlevel', $contextmodule)) {
        $allbooks     = $DB->get_records_sql("SELECT * FROM {reader_publisher}  where hidden = 0 ORDER BY difficulty, publisher, level, name");
        $prehtml      = "<img width='110'  height='160'  border='0' src='";
        $posthtml     = '/> ';
        $nowpub       = "";
        $nowlevel     = "";
        $nowdiff      = "999";
        $cellcount    = -1;
        echo("<table border='0'");
        foreach ($allbooks as $thisbook) {
            
            if (($thisbook->publisher != $nowpub) || ($thisbook->level != $nowlevel) ){
                if($nowdiff != $thisbook->difficulty) {
                    echo("<tr><td bgcolor='#999999' colspan ='6'><font size='5'>Reading Level: " . "$thisbook->difficulty" . "</font></td></tr>");
                    $nowdiff = $thisbook->difficulty;
                }
                //close the row unless the row was closed because the last row was full
                if ($cellcount > 0) { 
                    echo ("</td></tr>");
                }
                $nowpub = $thisbook->publisher;
                $nowlevel = $thisbook->level ;
                echo("<tr ><td colspan='6' align='left'><font size=+2>$nowpub &nbsp; $nowlevel</font></td> </tr>");
                //let's make the cpu rest for 1 sec between sets so that the server isn't overloaded with requests
                echo("<tr valign='top' align='center'>");
                sleep(1);
                $cellcount = 0;
            }
            $thistitle = $thisbook->name;
            
            $alink = new moodle_url("/file.php/{$reader->usecourse}/images/{$thisbook->image}");
            
            if ($cellcount > 5) {
                $cellcount = 1;
                echo("</td></tr> <tr valign='top' align='center'><td $prehtml" . $alink . "'$posthtml" . "  <br />  $thistitle  </td>");
            } else {
                $cellcount++;
                echo("<td>" . "$prehtml" . $alink . "'$posthtml". "<br />  $thistitle </td>");
            }
       
        }
        echo("</tr></table>");
*/


/*
* Award Extra Points
*/
    } else if ($act == "awardextrapoints" && has_capability('mod/reader:awardextrapoints', $contextmodule)) {
        if (!$bookdata = $DB->get_record("reader_publisher", array("name"=>"One point"))) {
            reader_red_notice(get_string('downloadpointsquizzesbefore', 'reader'));
        } else {
            $table = new html_table();
            
            reader_print_group_select_box($course->id, new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act, 'sort'=>$sort, 'orderby'=>$orderby)));
            
            if ($grid) {
                if ($award && $student) {
                    reader_red_notice(get_string('done', 'reader'));
                } else {
                    $o  = "";
                    $o .= html_writer::start_tag('form', array('action'=>'', 'method'=>'post'));
                    $o .= html_writer::start_tag('table', array('width'=>'100%'));
                    $o .= html_writer::start_tag('tr');
                    $o .= html_writer::start_tag('td', array('align'=>'right'));
                    $o .= html_writer::empty_tag('input', array('type'=>'button', 'value'=>get_string('selectall', 'reader'), 'onclick'=>'checkall();'));
                    $o .= html_writer::empty_tag('input', array('type'=>'button', 'value'=>get_string('deselectall', 'reader'), 'onclick'=>'uncheckall();'));
                    $o .= html_writer::end_tag('td');
                    $o .= html_writer::end_tag('tr');
                    $o .= html_writer::end_tag('table');
 
                    $titlesarray = array ('Image'=>'', 'Username'=>'username', 'Fullname'=>'fullname', 'Select Students'=>'');
                
                    $table->head = reader_make_table_headers($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act=awardextrapoints&grid='.$grid);
                    $table->align = array ("center", "left", "left", "center");
                    $table->width = "100%";
                    
                    $coursestudents = get_enrolled_users($context, NULL, $grid);
                    
                    foreach ($coursestudents as $coursestudent) {
                        $picture = $OUTPUT->user_picture($coursestudent,array($course->id, true, 0, true));
                        $table->data[] = array ($picture,
                                                reader_user_link_t($coursestudent),
                                                reader_fullname_link_t($coursestudent),
                                                html_writer::empty_tag('input', array('type'=>'checkbox', 'name'=>'student[]', 'value'=>$coursestudent->id)));
                    }
                    
                    $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);

                    if ($table) {
                        $o .= html_writer::table($table);
                    }

                    $awardpoints = array("0.5 pt/500 Words"=>"0.5 points","1 pt/1000 Words"=>"One point", "2 pts/2000 Words"=>"Two points", "3 pts/4000 Words"=>"Three points", "4 Pts/8000 Words"=>"Four points", "5 Pts/16000 Words"=>"Five points");
                    
                    $o .= html_writer::start_tag('center');
                    $o .= html_writer::start_tag('select', array('id'=>'Award_point', 'name'=>'award'));
                    foreach ($awardpoints as $key=>$value) { 
                        $o .= html_writer::tag('option', $key, array('value'=>$value));
                    }
                    $o .= html_writer::end_tag('select');
                    $o .= html_writer::empty_tag('input', array('type'=>'submit', 'value'=>'GO!'));
                    $o .= html_writer::end_tag('center');
                    $o .= html_writer::end_tag('form');
                    
                    echo $o;
                }
            } else {
                reader_red_notice(get_string("pleasespecifyyourclassgroup", "reader"));
            }
        }


/*
* Check logs for suspicious activity
*/
    } else if ($act == "checksuspiciousactivity" && has_capability('mod/reader:checklogsforsuspiciousactivity', $contextmodule)) {
        $table = new html_table();
        
        $alink = new moodle_url("/mod/reader/admin.php", array("a"=>"admin", "id"=>$id, "act"=>$act));
        
        $o  = "";
        $o  = html_writer::tag('div', '', array('class'=>'mt-10'));
        $o .= html_writer::start_tag('form', array('action'=>$alink, 'method'=>'post'));
        $o .= html_writer::start_tag('div');
        $o .= html_writer::empty_tag('input', array('type'=>'checkbox', 'name'=>'useonlythiscourse', 'value'=>get_string('yes', 'reader'), 'id'=>'id_useonlythiscourse', 'checked'=>'checked'));
        $o .= html_writer::tag('label', get_string("checkonlythiscourse", "reader"), array('for'=>'id_useonlythiscourse', 'class'=>'ml-10'));
        $o .= html_writer::end_tag('div');
        $o .= html_writer::start_tag('div');
        $o .= html_writer::empty_tag('input', array('type'=>'checkbox', 'name'=>'withoutdayfilter', 'value'=>get_string('yes', 'reader'), 'id'=>'id_withoutdayfilter'));
        $o .= html_writer::tag('label', get_string("withoutdayfilter", "reader"), array('for'=>'id_withoutdayfilter', 'class'=>'ml-10'));
        $o .= html_writer::end_tag('div');
        
        $o .= html_writer::start_tag('div');
        $o .= html_writer::start_tag('select', array('id'=>'ip_mask', 'name'=>'ipmask'));
        
        $ipmaskselect = array("2"=>"xxx.xxx.", "3"=>"xxx.xxx.xxx.");
        
        foreach ($ipmaskselect as $key=>$value) {
            if ($key == $ipmask)
                $o .= html_writer::tag('option', $value, array('value'=>$key, 'selected'=>'selected'));
            else
                $o .= html_writer::tag('option', $value, array('value'=>$key));
        }
        $o .= html_writer::end_tag('select');
        
        $o .= html_writer::tag('label', get_string("selectipmask", "reader"), array('for'=>'ip_mask', 'class'=>'ml-10'));
        $o .= html_writer::end_tag('div');
        
        $o .= html_writer::start_tag('div');
        $o .= html_writer::start_tag('select', array('id'=>'from_time', 'name'=>'fromtime'));
        
        $fromtimeselect = array("86400"=>"Day", "604800"=>"Week", "2419200"=>"Month", "5270400"=>"2 Months", "7862400"=>"3 Months");
        foreach ($fromtimeselect as $key=>$value) {
            if ($key == $fromtime)
                $o .= html_writer::tag('option', $value, array('value'=>$key, 'selected'=>'selected'));
            else
                $o .= html_writer::tag('option', $value, array('value'=>$key));
        }
        $o .= html_writer::end_tag('select');
        
        $o .= html_writer::tag('label', get_string("fromthistime", "reader"), array('for'=>'from_time', 'class'=>'ml-10'));
        $o .= html_writer::end_tag('div');
        
        $o .= html_writer::start_tag('div');
        $o .= html_writer::start_tag('select', array('id'=>'max_time', 'name'=>'maxtime'));
        
        $fromtimeselect = array("900"=>"15 Minutes", "1800"=>"30 Minutes", "2700"=>"45 Minutes", "3600"=>"Hour", "10800"=>"3 Hours", "21600"=>"6 Hours", "43200"=>"12 Hours", "86400"=>"Day");
        foreach ($fromtimeselect as $key=>$value) {
            if ($key == $maxtime)
                $o .= html_writer::tag('option', $value, array('value'=>$key, 'selected'=>'selected'));
            else
                $o .= html_writer::tag('option', $value, array('value'=>$key));
        }
        $o .= html_writer::end_tag('select');
        
        $o .= html_writer::tag('label', get_string("maxtimebetweenquizzes", "reader"), array('for'=>'max_time', 'class'=>'ml-10'));
        $o .= html_writer::end_tag('div');
        
        $o .= html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'findcheated', 'value'=>'Go'));
        $o .= html_writer::end_tag('form');
        
        echo $o;

        if ($findcheated) {
            $allips      = array();
            $comparearr  = array();
            $compare     = array();
            
            $order='l.time DESC';
            
            if ($useonlythiscourse) $usecoursesql = "course = '{$course->id}' and";
            if ($fromtime) $fromtimesql = "time > '".(time() - $fromtime)."' and";
            
            $select = " {$usecoursesql} {$fromtimesql} module = 'reader' and info LIKE 'readerID%; reader quiz%; %/%' ";
            $countsql = (strlen($select) > 0) ? ' WHERE '. $select : '';
            $totalcount = $DB->count_records_sql("SELECT COUNT(*) FROM {log} l {$countsql}");
            $logtext = $DB->get_records_sql("SELECT * FROM {log} l $countsql") ;
            
            foreach ($logtext as $logtext_) {
                if (preg_match("!reader quiz (.*?); !si",$logtext_->info,$quizid)) $quizid=$quizid[1]; 
                if ($quizid) {
                    $allips[$quizid][$logtext_->id] = $logtext_->ip;
                }
            }
            
    
            foreach ((array)$allips as $quize=>$val) {
                $checkerarray = $val;
                foreach ($val as $resultid=>$resultip) {
                    unset($checkerarray[$resultid]);
                    list($ip1,$ip2,$ip3,$ip4) = explode(".",$resultip);
                    if ($ipmask == 2) {
                        $ipmaskcheck = "$ip1.$ip2";
                    } else {
                        $ipmaskcheck = "$ip1.$ip2.$ip3";
                    }
                    while (list($rid, $rip) = each($checkerarray)) {
                        if (address_in_subnet($rip, $ipmaskcheck)) {
                            $comparearr[$quize][$resultid] = $resultip;
                            $comparearr[$quize][$rid]      = $resultip;
                        }
                    }
                    reset ($checkerarray);
                }
            }
            
            
            foreach ($comparearr as $key=>$value) {
                if (count($value) <= 1) unset($comparearr[$key]);
            }
            
            foreach ($comparearr as $key=>$value) {
                $f = 0;
                $countofarray = count($value);
                foreach ($value as $key1=>$value1) {
                    if ($f > 0) {
                        $compare[$key][$fkey]['ip2']       = $value1;
                        $compare[$key][$fkey]['id2']       = $key1;
                        
                        if ($f < $countofarray - 1) {
                            $compare[$key][$key1]['ip']    = $value1;
                            $fkey = $key1;
                        }
                    } else {
                        $compare[$key][$key1]['ip']        = $value1;
                        $fkey = $key1;
                    }
                    $f++;
                }
            }

            $titlesarray = array ('Book'=>'book', 'Username 1'=>'username1', 'Username 2'=>'username2', 'IP 1'=>'', 'IP 2'=>'', 'Time 1'=>'', 'Time 2'=>'', 'Time period'=>'', 'Log text'=>'');
            
            $table->head  = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act='.$act);
            $table->align = array ("left", "left", "left", "center", "center", "center", "center", "center", "left");
            $table->width = "100%";
            
            
            foreach ($compare as $bookid=>$result) {
                foreach ($result as $key=>$data) {
                  if ($logtext[$key]->userid != $logtext[$data['id2']]->userid) {
                      $diff = $logtext[$key]->time - $logtext[$data['id2']]->time;
                      if ($diff < 0) $diff = (int)substr($diff, 1);
                      if ($maxtime > $diff || $withoutdayfilter == "yes") {
                          $bookdata  = $DB->get_record("reader_publisher", array("id"=>$bookid));
                          $user1dta  = $DB->get_record("user", array("id"=>$logtext[$key]->userid));
                          $user2data = $DB->get_record("user", array("id"=>$logtext[$data['id2']]->userid));
                          if ($diff < 3600) {
                              $diffstring = round($diff/60)." minutes";
                          } else {
                              $diffstring = round($diff/3600)." hours";
                          }
                          
                          $raid1 = (int)str_replace("view.php?id=", "", $logtext[$key]->url);
                          $raid2 = (int)str_replace("view.php?id=", "", $logtext[$data['id2']]->url);
                          
                          $readerattempt[1] = $DB->get_record("reader_attempts", array("id"=>$raid1));
                          $readerattempt[2] = $DB->get_record("reader_attempts", array("id"=>$raid2));
                          
                            if ($readerattempt[1]->id && $readerattempt[2]->id) {
                                $cheatedstring_script = '$(document).ready(function() {
                                $("#cheated-link-'.$readerattempt[1]->id.'_'.$readerattempt[2]->id.'").click(function() {
                                  if(confirm(\'Cheated ?\')) {
                                    $.post("admin.php", { a: "'.$a.'", id: "'.$id.'", act: "'.$act.'", useonlythiscourse: "'.$useonlythiscourse.'",ipmask: "'.$ipmask.'", fromtime: "'.$fromtime.'", maxtime: "'.$maxtime.'", cheated: "'.$readerattempt[1]->id.'_'.$readerattempt[2]->id.'" } );
                                    $("#cheated-div-'.$readerattempt[1]->id.'_'.$readerattempt[2]->id.'").html("done");
                                    return false;
                                  } else { return false; }
                                }
                                );
                                });';
                                
                                $cheatedstring  = "";
                                $cheatedstring .= html_writer::script($cheatedstring_script);
                                $cheatedstring .= html_writer::start_tag('div', array('id'=>'cheated-div-'.$readerattempt[1]->id.'_'.$readerattempt[2]->id));
                                $cheatedstring .= html_writer::link('#', 'cheated', array('id'=>'cheated-link-'.$readerattempt[1]->id.'_'.$readerattempt[2]->id));
                                $cheatedstring .= html_writer::end_tag('div');
                                
                                if ($readerattempt[1]->passed != "cheated") {
                                    if (strstr(strtolower($logtext[$key]->info), "passed")) {
                                        $logstatus[1] = 'passed';
                                    } else {
                                        $logstatus[1] = 'failed';
                                    }
                                } else {
                                    $logstatus[1] = html_writer::tag('font', 'cheated', array('color'=>'red'));
                                    
                                    $cheatedstring_script = '$(document).ready(function() {
                                $("#cheated-link-'.$readerattempt[1]->id.'_'.$readerattempt[2]->id.'").click(function() {
                                  if(confirm(\'Set passed ?\')) {
                                    $.post("admin.php", { a: "'.$a.'", id: "'.$id.'", act: "'.$act.'", useonlythiscourse: "'.$useonlythiscourse.'",ipmask: "'.$ipmask.'", fromtime: "'.$fromtime.'", maxtime: "'.$maxtime.'", uncheated: "'.$readerattempt[1]->id.'_'.$readerattempt[2]->id.'" } );
                                    $("#cheated-div-'.$readerattempt[1]->id.'_'.$readerattempt[2]->id.'").html("done");
                                    return false;
                                  } else { return false; }
                                }
                                );
                                });';
                                    
                                    $cheatedstring  = "";
                                    $cheatedstring .= html_writer::script($cheatedstring_script);
                                    $cheatedstring .= html_writer::start_tag('div', array('id'=>'cheated-div-'.$readerattempt[1]->id.'_'.$readerattempt[2]->id));
                                    $cheatedstring .= html_writer::link('#', 'Set passed', array('id'=>'cheated-link-'.$readerattempt[1]->id.'_'.$readerattempt[2]->id));
                                    $cheatedstring .= html_writer::end_tag('div');
                                }
                                
                                if ($readerattempt[1]->passed != "cheated") {
                                    if (strstr(strtolower($logtext[$data['id2']]->info), "passed")) {
                                        $logstatus[2] = 'passed';
                                    } else {
                                        $logstatus[2] = 'failed';
                                    }
                                } else {
                                    $logstatus[2] = html_writer::tag('font', 'cheated', array('color'=>'red'));
                                }
                                
                                if (!has_capability('mod/reader:checklogsforsuspiciousactivity', $contextmodule)) $cheatedstring = '';
                                
                                $usergroups  = reader_groups_get_user_groups($user1dta->id);
                                $groupsuser1 = groups_get_group_name($usergroups[0][0]);
                                
                                $usergroups  = reader_groups_get_user_groups($user2data->id);
                                $groupsuser2 = groups_get_group_name($usergroups[0][0]);
                                
                                $alink  = new moodle_url("/user/view.php", array("id"=>$logtext[$key]->userid, "course"=>$course->id));
                                $alink2 = new moodle_url("/user/view.php", array("id"=>$logtext[$data['id2']]->userid, "course"=>$course->id));
                                $alink3 = new moodle_url("/iplookup/index.php", array("id"=>$data['ip'], "user"=>$logtext[$key]->userid));
                                $alink4 = new moodle_url("/iplookup/index.php", array("id"=>$data['ip2'], "user"=>$logtext[$data['id2']]->userid));
                                
                                $table->data[] = array ($bookdata->name."<br />".$cheatedstring,
                                                                        "<a href=\"{$alink}\">{$user1dta->username} ({$user1dta->firstname} {$user1dta->lastname}; group: {$groupsuser1})</a><br />".$logstatus[1],
                                "<a href=\"{$alink2}\">{$user2data->username} ({$user2data->firstname} {$user2data->lastname}; group: {$groupsuser2})</a><br />".$logstatus[2],
                                link_to_popup_window($alink3, $data['ip'], 440, 700, null, null, true),
                                link_to_popup_window($alink4, $data['ip2'], 440, 700, null, null, true), 
                                date("D d F H:i", $logtext[$key]->time),
                                date("D d F H:i", $logtext[$data['id2']]->time),
                                $diffstring,
                                $logtext[$key]->info."<br />".$logtext[$data['id2']]->info);
                            }
                        }
                    }
                }
            }
            
            $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
            
            if ($table) {
                echo html_writer::table($table);
            }
        }
        
        
/*
* Summary Report by Class Group
*/
   } else if ($act == "reportbyclass" && has_capability('mod/reader:readerviewreports', $contextmodule)) { 
        $groups = groups_get_all_groups ($course->id);
        
        $table = new html_table();
        
        $titlesarray = array ('Group name'=>'groupname', 'Students with<br /> no quizzes'=>'noquizzes', 'Students with<br /> quizzes'=>'quizzes', 'Percent with<br /> quizzes'=>'quizzes', 'Average Taken<br /> Quizzes'=>'takenquizzes', 'Average Passed<br /> Quizzes'=>'passedquizzes', 'Average Failed<br /> Quizzes'=>'failedquizzes', 'Average total<br /> points'=>'totalpoints', 'Average words<br /> this term'=>'averagewordsthisterm', 'Average words<br /> all terms'=>'averagewordsallterms');
        
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act='.$act.'&grid='.$grid.'&searchtext='.$searchtext.'&page='.$page.'&fromtime='.$fromtime);
        $table->align = array ("left", "center", "center", "center", "center", "center", "center", "center", "center");
        $table->width = "100%";
        
        
        if ($excel) {
            $myxls->write_string(0, 0, 'Summary Report by Class Group',$formatbc);
            $myxls->write_string(1, 0, 'Date: '.$exceldata['time'].'; Course name: '.$exceldata['course_shotname']);
            $myxls->set_row(0, 30);
            $myxls->set_column(0,1,20);
            $myxls->set_column(2,10,15);
            
            $myxls->write_string(2, 0, 'Group name',$formatbc);
            $myxls->write_string(2, 1, 'Students with no quizzes',$formatbc);
            $myxls->write_string(2, 2, 'Students with quizzes',$formatbc);
            $myxls->write_string(2, 3, 'Percent with Quizzes',$formatbc);
            $myxls->write_string(2, 4, 'Average Taken Quizzes',$formatbc);
            $myxls->write_string(2, 5, 'Average Passed Quizzes',$formatbc);
            $myxls->write_string(2, 6, 'Average Failed Quizzes',$formatbc);
            $myxls->write_string(2, 7, 'Average total points',$formatbc);
            $myxls->write_string(2, 8, 'Average words this term',$formatbc);
            $myxls->write_string(2, 9, 'Average words all terms',$formatbc);
        }
        
        foreach ($groups as $group) {
            unset($data);
            $data['averagetaken']         = 0;
            $data['averagepassed']        = 0;
            $data['averagepoints']        = 0;
            $data['averagefailed']        = 0;
            $data['withquizzes']          = 0;
            $data['withoutquizzes']       = 0;
            $data['averagewordsthisterm'] = 0;
            $data['averagewordsallterms'] = 0;
            
            $coursestudents = get_enrolled_users($context, NULL, $group->id);
            foreach ($coursestudents as $coursestudent) {
                if ($attempts = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE userid= ?  and reader= ?  and timestart > ?", array($coursestudent->id, $reader->id, $reader->ignordate))) {
                    $data['averagetaken'] += count($attempts);
                    foreach ($attempts as $attempt) {
                        if (strtolower($attempt->passed) == "true") {
                            $data['averagepassed']++;
                            $bookdata = $DB->get_record("reader_publisher", array("id"=>$attempt->quizid));
                            $data['averagepoints'] += reader_get_reader_length($reader, $bookdata->id);
                            $data['averagewordsthisterm'] += $bookdata->words;
                        } else {
                            $data['averagefailed']++; 
                        }
                    }
                    $data['withquizzes'] ++;
                } else {
                    $data['withoutquizzes'] ++;
                }
                
                if ($attempts = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE userid= ? ", array($coursestudent->id))) {
                    foreach ($attempts as $attempt) {
                        if (strtolower($attempt->passed) == "true") {
                            $bookdata = $DB->get_record("reader_publisher", array("id"=>$attempt->quizid));
                            $data['averagewordsallterms'] += $bookdata->words;
                        }
                    }
                }
            }
            $table->data[] = array ($group->name,
                                    $data['withoutquizzes'],
                                    $data['withquizzes'],
                                    round(($data['withquizzes']/($data['withquizzes']+$data['withoutquizzes']))*100 ,1) ."%",  
                                    round($data['averagetaken']/count($coursestudents),1),
                                    round($data['averagepassed']/count($coursestudents),1),
                                    round($data['averagefailed']/count($coursestudents),1),
                                    round($data['averagepoints']/count($coursestudents),1),
                                    round($data['averagewordsthisterm']/count($coursestudents)),
                                    round($data['averagewordsallterms']/count($coursestudents))
                                    );
        }
        
        $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
        
        if ($excel) {
            foreach ($table->data as $tabledataarray) {
                $myxls->write_string($row, 0, $tabledataarray[0]);
                $myxls->write_number($row, 1, (int) $tabledataarray[1]);
                $myxls->write_number($row, 2, (int) $tabledataarray[2]);
                $myxls->write_number($row, 3, (int) $tabledataarray[3]);
                $myxls->write_number($row, 4, (int) $tabledataarray[4]);
                $myxls->write_number($row, 5, (int) $tabledataarray[5]);
                $myxls->write_string($row, 6, (int) $tabledataarray[6]);
                $myxls->write_string($row, 7, (int) $tabledataarray[7]);
                $myxls->write_string($row, 8, (int) $tabledataarray[8]);
                $myxls->write_string($row, 9, (int) $tabledataarray[9]);
                $row++;
            }
        }

        if ($excel) {
            $workbook->close();
            die();
        }
                
        reader_excel_download_btn();
       
        reader_select_perpage($id, $act, $sort, $orderby, $grid);
        
        list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);
        
        $alinkpadding     = new moodle_url("/mod/reader/admin.php", array("a"=>"admin", "id"=>$id, "act"=>$act, "grid"=>$grid, "sort"=>$sort, "orderby"=>$orderby, "fromtime"=>$fromtime));
        
        echo $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 
        
        if ($table) {
            echo html_writer::table($table);
        }
        
        echo $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 


/*
* Summary Report by Class Group
*/
    } else if ($act == "setgoal" && has_capability('mod/reader:setgoal', $contextmodule)) {
        class reader_setgoal_form extends moodleform {
            function definition() {
                global $COURSE, $CFG, $reader, $course, $DB;
                
                $mform    =& $this->_form;
                $mform->addElement('header', 'setgoal', get_string('setgoal', 'reader')); 
                $mform->addElement('select', 'wordsorpoints', get_string('wordsorpoints', 'reader'), array("points"=>get_string('points', 'reader'), "words"=>get_string('words', 'reader')));
                $groups = array("0"=>get_string('allparticipants', 'reader'));
                if ($usergroups = groups_get_all_groups($course->id)){
                    foreach ($usergroups as $group){
                        $groups[$group->id] = $group->name;
                    }
                    $mform->addElement('select', 'separategroups', get_string('separategroups', 'reader'), $groups);
                }
                $mform->addElement('text', 'levelall', get_string('all', 'reader'), array('size'=>'10'));
                for($i=1; $i<=10; $i++) {
                    $mform->addElement('text', 'levelc['.$i.']', $i, array('size'=>'10'));
                }
                
                if ($data = $DB->get_records("reader_goal", array("readerid"=>$reader->id))) {
                    foreach ($data as $data_) {
                        if (empty($data_->level)){
                            $mform->setDefault('levelall', $data_->goal);
                        } else {
                            $mform->setDefault('levelc['.$data_->level.']', $data_->goal);
                        }
                        if ($data_->groupid) $mform->setDefault('separategroups', $data_->groupid);
                        if ($data_->goal < 100) {
                            $mform->setDefault('wordsorpoints', 'points');
                        } else {
                            $mform->setDefault('wordsorpoints', 'words');
                        }
                    }
                } else if ($reader->goal) {
                    $mform->setDefault('levelall', $reader->goal);
                    if ($reader->goal < 100) {
                        $mform->setDefault('wordsorpoints', 'points');
                    } else {
                        $mform->setDefault('wordsorpoints', 'words');
                    }
                }
                $this->add_action_buttons(false, $submitlabel="Save");
            }
        }
        
        $link = new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act));
        $mform = new reader_setgoal_form($link);
        $mform->display();


/*
* Set forced time delay
*/
    } else if ($act == "forcedtimedelay" && has_capability('mod/reader:forcedtimedelay', $contextmodule)) {
        class reader_forcedtimedelay_form extends moodleform {
            function definition() {
                global $COURSE, $CFG, $reader, $course,$DB, $id, $separategroups;
                
                if (empty($separategroups)) $separategroups = 0;
                
                if ($default = $DB->get_record("reader_forcedtimedelay", array( "readerid"=>$reader->id,  'level'=>99, 'groupid'=>$separategroups))) {
                    if ($default->delay) {
                        $defdelaytime = $default->delay;
                    }
                } else {
                    $defdelaytime = $reader->attemptsofday * 24;
                }
                
                $dtimes = array(0=>'Default', 1=>'Without delay', 14400=>4, 28800=>8, 43200=>12, 57600=>16, 86400=>24, 129600=>36, 172800=>48, 259200=>72, 345600=>96, 432000=>120);
                
                $mform    =& $this->_form;
                $mform->addElement('header', 'forcedtimedelay', get_string("forcedtimedelay", "reader")." (hours)"); 
                $groups = array("0"=>get_string('allparticipants', 'reader'));
                if ($usergroups = groups_get_all_groups($course->id)){
                    foreach ($usergroups as $group){
                        $groups[$group->id] = $group->name;
                    }
                    $mform->addElement('select', 'separategroups', get_string('separategroups', 'reader'), $groups);
                }
                $mform->addElement('select', 'levelc[99]', get_string('all', 'reader'), $dtimes);
                for($i=1; $i<=10; $i++) {
                    $mform->addElement('select', 'levelc['.$i.']', $i, $dtimes);
                }
                
                /* SET default */
                
                $mform->setDefault('levelc[99]', $defdelaytime);
                $mform->setDefault('separategroups', $separategroups);
                
                $data = $DB->get_records ("reader_forcedtimedelay", array("readerid"=>$reader->id, "groupid"=>$separategroups));
                foreach ($data as $data_) {
                    if ($data_->level == 99) 
                        $mform->setDefault('levelall', $data_->delay);
                    else
                        $mform->setDefault('levelc['.$data_->level.']', $data_->delay);
                }
                
                $this->add_action_buttons(false, $submitlabel="Save");
            }
        }
        
        $link = new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act));
        
        $mform = new reader_forcedtimedelay_form($link);
        $mform->display();


/*
* Display student book ratings for each book level
*/
    } else if ($act == "bookratingslevel" && has_capability('mod/reader:readerviewreports', $contextmodule)) {
        $table = new html_table();

        $alink     = new moodle_url("/mod/reader/admin.php", array("a"=>"admin", "id"=>$id, "act"=>$act));
        
        $o  = "";
        $o .= html_writer::tag('div', '', array('class'=>'mt-10'));
        $o .= html_writer::start_tag('form', array('action'=>$alink, 'method'=>'post'));
        $o .= get_string("best", "reader");
        $o .= html_writer::start_tag('select', array('id'=>'booksratingbest', 'name'=>'booksratingbest'));
        $fromselect = array("5"=>"5", "10"=>"10", "25"=>"25", "50"=>"50", "0"=>"All");
        foreach ($fromselect as $key=>$value) {
            if ($key == $booksratingbest)
                $o .= html_writer::tag('option', $value, array('value'=>$key, 'selected'=>'selected'));
            else
                $o .= html_writer::tag('option', $value, array('value'=>$key));
        }
        $o .= html_writer::end_tag('select');
        $o .= html_writer::empty_tag('br');
        
        $o .= get_string("showlevel", "reader");
        $o .= html_writer::start_tag('select', array('id'=>'booksratinglevel', 'name'=>'booksratinglevel'));
        $o .= html_writer::empty_tag('br');
        $fromselect = array("0"=>"0", "1"=>"1", "2"=>"2", "3"=>"3", "4"=>"4", "5"=>"5", "6"=>"6", "7"=>"7", "8"=>"8", "9"=>"9", "10"=>"10", "11"=>"11", "12"=>"12", "13"=>"13", "14"=>"14", "15"=>"15", "99"=>"All");
        foreach ($fromselect as $key=>$value) {
            if ($key == $booksratinglevel)
                $o .= html_writer::tag('option', $value, array('value'=>$key, 'selected'=>'selected'));
            else
                $o .= html_writer::tag('option', $value, array('value'=>$key));
        }
        $o .= html_writer::end_tag('select');
        $o .= html_writer::empty_tag('br');

        $o .= get_string("term", "reader");
        $o .= html_writer::start_tag('select', array('id'=>'booksratingterm', 'name'=>'booksratingterm'));
        $fromselect = array("0"=>"All terms", $reader->ignordate=>"Current");
        foreach ($fromselect as $key=>$value) {
            if ($key == $booksratingterm)
                $o .= html_writer::tag('option', $value, array('value'=>$key, 'selected'=>'selected'));
            else
                $o .= html_writer::tag('option', $value, array('value'=>$key));
        }
        $o .= html_writer::end_tag('select');
        $o .= html_writer::empty_tag('br');
        
        $o .= get_string("onlybookswithmorethan", "reader");
        $o .= html_writer::start_tag('select', array('id'=>'booksratingwithratings', 'name'=>'booksratingwithratings'));
        $fromselect = array("0"=>"0", "5"=>"5", "10"=>"10", "25"=>"25", "50"=>"50");
        foreach ($fromselect as $key=>$value) {
            if ($key == $booksratingwithratings)
                $o .= html_writer::tag('option', $value, array('value'=>$key, 'selected'=>'selected'));
            else
                $o .= html_writer::tag('option', $value, array('value'=>$key));
        }
        $o .= html_writer::end_tag('select');
        $o .= get_string("ratings", "reader") . ' : ';
        $o .= html_writer::empty_tag('br');
        $o .= html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'booksratingshow', 'value'=>'Go'));
        $o .= html_writer::empty_tag('br');
        $o .= html_writer::end_tag('form');
        $o .= html_writer::tag('div', '', array('class'=>'mt-10'));
        
        echo $o;

        if ($booksratingshow) {
            $data = array();
        
            if ($booksratinglevel == 99) {
                $findallbooks = $DB->get_records("reader_publisher");
            } else {
                $findallbooks = $DB->get_records_sql("SELECT * FROM {reader_publisher} WHERE difficulty = ?", array($booksratinglevel));
            }
            
            foreach ($findallbooks as $findallbook) {
                $findallattempts = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE timefinish >= ? and quizid = ?", array($booksratingterm, $findallbook->id));
                $ratingsummary = "";
                $contof = 0;
                foreach ($findallattempts as $findallattempt) {
                    $ratingsummary += $findallattempt->bookrating;
                    $contof++;
                }
                $findallbook->ratingsummary = $ratingsummary;
                $findallbook->ratingcount   = $contof;
                if ($contof > 0) {
                    $findallbook->ratingaverage = round((($ratingsummary/$contof)*3.3)+0.1,1);
                } else {
                    $findallbook->ratingaverage = 0;
                }
                    
                if ($contof >= $booksratingwithratings) $data[] = $findallbook;
            }
            
            if (count($data) <= 0) {
                reader_red_notice(get_string('nobookfound', 'reader'));
            } else {
                $data = reader_order_object($data, "ratingaverage");
                
                $titlesarray = array ('Book Title'=>'booktitle', get_string('publisher', 'reader')=>'publisher', 'R. Level'=>'level', 'Avg Rating'=>'avrating', 'No. of Ratings'=>'nrating');
                
                $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act='.$act.'&booksratingbest='.$booksratingbest.'&booksratinglevel='.$booksratinglevel.'&booksratingterm='.$booksratingterm.'&booksratingwithratings='.$booksratingwithratings."&booksratingshow=Go");
                $table->align = array ("left", "left", "center", "center", "center");
                $table->width = "100%";
                
                if ($booksratingbest) {
                    foreach ($data as $data_) {
                        $datares[$data_->id][0] = $data_->id;
                        $datares[$data_->id][1] = $data_->name;
                        $datares[$data_->id][2] = $data_->publisher;
                        $datares[$data_->id][3] = $data_->ratingaverage;
                        $datares[$data_->id][4] = $data_->ratingcount;
                    }
                    $i=0;
                    unset($data);
                    foreach ($datares as $datares_) {
                        $i++;
                        if ($i<=$booksratingbest) {
                            $data[$datares_[0]]->id = $datares_[0];
                            $data[$datares_[0]]->name = $datares_[1];
                            $data[$datares_[0]]->publisher = $datares_[2];
                            $data[$datares_[0]]->ratingaverage = $datares_[3];
                            $data[$datares_[0]]->ratingcount = $datares_[4];
                        }
                    }
                }
                
                foreach ($data as $data_) {
                    $table->data[] = array ($data_->name,
                                        $data_->publisher,
                                        reader_get_reader_difficulty($reader, $data_->id),
                                        $data_->ratingaverage,
                                        $data_->ratingcount);
                }
                $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
                
                if ($table) {
                    echo html_writer::table($table);
                }
            }
        }


/*
* Individual books listing
*/
    } else if ($act == "setindividualbooks" && has_capability('mod/reader:selectquizzestomakeavailabletostudents', $contextmodule)) {
        if ($reader->individualbooks == 0) 
            reader_red_notice(get_string('coursespecificquizselection', 'reader'));
        
        $quizzes = array();
        
        $data = $DB->get_records_sql ("SELECT * FROM {reader_publisher} where hidden=? order by publisher, level, name", array(0));
        foreach ($data as $data_) {
            $quizzes[$data_->publisher][$data_->level][$data_->id] = $data_->name;
        }
        
        $allquestionscount = 0;
        foreach ((array)$quizzes as $key =>$value) {
            foreach ((array)$value as $key2=>$value2) {
                foreach ((array)$value2 as $key3=>$value3) {
                    $allquestionscount++;
                    
                    $checkboxdatapublishers[$key][] = $allquestionscount;
                    $checkboxdatalevels[$key][$key2][] = $allquestionscount;
                    $quizzescountid[$key3] = $allquestionscount;
                }
            }
        }
        
        $currentbooksarray = array();
        
        $currentbooks = $DB->get_records_sql ("SELECT * FROM {reader_individual_books} WHERE readerid = ?", array($reader->id)); 
        if (is_array($currentbooks)) {
            foreach ($currentbooks as $currentbook) {
                $currentbooksarray[$currentbook->bookid] = $currentbook->readerid;
            }
        }
        
        echo $OUTPUT->box_start('generalbox');
        
        $alink  = new moodle_url("/mod/reader/admin.php", array("id"=>$id, "act"=>"setindividualbooks"));
        
        $o  = "";
        $o .= html_writer::start_tag('div');
        $o .= html_writer::start_tag('form', array('action'=>$alink, 'method'=>'post', 'id'=>'mform1'));
        $o .= html_writer::start_tag('div', array('class'=>'w-600'));
        $o .= html_writer::link('#', 'Show All', array('onclick'=>'myexpandall();return false;'));
        $o .= ' / ';
        $o .= html_writer::link('#', 'Hide All', array('onclick'=>'mycollapseall();return false;'));
        $o .= html_writer::empty_tag('br');
        $o .= html_writer::end_tag('div');
        
        $cp               = 0;
        $submitonclick    = array();
        $submitonclicktop = array();

        if (is_array($quizzes)) {
            foreach ($quizzes as $publiher=>$datas) {
                $cp++;
                $o .= html_writer::empty_tag('br');
                $o .= html_writer::start_tag('a', array('href'=>'#','onclick'=>'mytoggle(\'comments_'.$cp.'\');return false'));
                $o .= html_writer::start_tag('span', array('id'=>'comments_'.$cp.'indicator'));
                $o .= html_writer::empty_tag('img', array('src'=>$reader_images['open'], 'alt'=>'Opened folder'));
                $o .= html_writer::end_tag('span');
                $o .= html_writer::end_tag('a');
                $o .= html_writer::tag('b', $publiher, array('class'=>'dl-title'));
                $o .= html_writer::start_tag('span', array('id'=>'comments_'.$cp));
                $o .= html_writer::empty_tag('input', array('type'=>'checkbox', 'name'=>'installall['.$cp.']', 'onclick'=>'setChecked(this,'.$checkboxdatapublishers[$publiher][0].','.end($checkboxdatapublishers[$publiher]).')', 'value'=>''));
                $o .= html_writer::tag('span', 'Install All', array('id'=>'seltext_'.$cp, 'class'=>'ml-10'));
                
                $topsubmitonclick = $cp;
                foreach ($datas as $level=>$quizzesdata) {
                    $cp++;
                    
                    $o .= html_writer::start_tag('div', array('class'=>'dl-page-box1'));
                    $o .= html_writer::start_tag('a', array('href'=>'#', 'onclick'=>'mytoggle(\'comments_'.$cp.'\');return false'));
                    $o .= html_writer::start_tag('span', array('id'=>'comments_'.$cp.'indicator'));
                    $o .= html_writer::empty_tag('img', array('src'=>$reader_images['open'], 'alt'=>'Opened folder'));
                    $o .= html_writer::end_tag('span');
                    $o .= html_writer::end_tag('a');
                    
                    $o .= html_writer::tag('b', $level, array('class'=>'dl-title'));
                    
                    $o .= html_writer::start_tag('span', array('id'=>'comments_'.$cp));
                    $o .= html_writer::empty_tag('input', array('type'=>'checkbox', 'name'=>'installall['.$cp.']', 'onclick'=>'setChecked(this,'.$checkboxdatalevels[$publiher][$level][0].','.end($checkboxdatalevels[$publiher][$level]).')', 'value'=>''));
                    $o .= html_writer::tag('span', 'Install All', array('id'=>'seltext_'.$cp, 'class'=>'ml-10'));
                    $o .= html_writer::tag('div', '', array('class'=>'mt-10'));

                    foreach ($quizzesdata as $quizid=>$quiztitle) {
                        $o .= html_writer::start_tag('div', array('class'=>'pl-20'));
                        
                        if (isset($currentbooksarray[$quizid])) {
                            $submitonclick[$cp]                  = 1; 
                            $submitonclicktop[$topsubmitonclick] = 1; 
                            $o .= html_writer::empty_tag('input', array('type'=>'checkbox', 'name'=>'quiz[]', 'id'=>'quiz_'.$quizzescountid[$quizid], 'value'=>$quizid, 'checked'=>'checked'));
                        } else {
                            $o .= html_writer::empty_tag('input', array('type'=>'checkbox', 'name'=>'quiz[]', 'id'=>'quiz_'.$quizzescountid[$quizid], 'value'=>$quizid));
                        }
                        
                        $o .= html_writer::tag('span', $quiztitle, array('class'=>'ml-10'));
                        $o .= html_writer::end_tag('div');
                    }
                    $o .= html_writer::end_tag('span');
                    $o .= html_writer::end_tag('div');
                }
                $o .= html_writer::end_tag('span');
            }
            
            $o .= html_writer::start_tag('div', array('class'=>'dl-page-install'));
            $o .= html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'showquizzes', 'value'=>'Show Students Selected Quizzes'));
            $o .= html_writer::end_tag('div');
        }
        $o .= html_writer::end_tag('div');
        $o .= html_writer::end_tag('form');
        
        echo $o;
        
        if (isset($cp))
            echo html_writer::script('var vh_numspans = '.$cp.';$(document).ready(function(){mycollapseall(vh_numspans)});');
        
        $s  = "";
        
        foreach ((array) $submitonclicktop as $key=>$value) {
            $s .= 'myexpand1("comments_'.$key.'");';
        }
        
        foreach ((array) $submitonclick as $key=>$value) {
            $s .= 'myexpand1("comments_'.$key.'");';
        }
        
        if (!empty($s))
            echo html_writer::script('$(document).ready(function(){'.$s.'});');
        
        echo $OUTPUT->box_end();


/*
* View log of suspicious activity
*/
    } else if ($act == "viewlogsuspiciousactivity" && has_capability('mod/reader:readerviewreports', $contextmodule)) {
        $table        = new html_table();
        
        $titlesarray  = array ('Image'=>'', 'By Username'=>'byusername', 'Student 1'=>'student1', 'Student 2'=>'student2', 'Quiz'=>'quiz', 'Status'=>'status', 'Date'=>'date');
        
        $table->head  = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act='.$act.'&grid='.$grid.'&page='.$page);
        $table->align = array ("center", "left", "left", "left", "left", "center", "center");
        $table->width = "100%";
        
        if ($excel) {
            $myxls->write_string(0, 0, 'View log of suspicious activity',$formatbc);
            $myxls->write_string(1, 0, 'Date: '.$exceldata['time'].'; Course name: '.$exceldata['course_shotname'].'; ');
            $myxls->set_row(0, 30);
            $myxls->set_column(0,1,20);
            $myxls->set_column(2,10,15);
            
            $myxls->write_string(2, 0, 'By Username',$formatbc);
            $myxls->write_string(2, 1, 'Student 1',$formatbc);
            $myxls->write_string(2, 2, 'Student 2',$formatbc);
            $myxls->write_string(2, 3, 'Quiz',$formatbc);
            $myxls->write_string(2, 4, 'Status',$formatbc);
            $myxls->write_string(2, 5, 'Date',$formatbc);
        }
        
        if (!$grid) {
            $grid = NULL;
        }
        
        $cheatedlogs = $DB->get_records("reader_cheated_log", array("readerid"=>$reader->id));
        
        foreach ($cheatedlogs as $cheatedlog) {
            $cheatedstring = '';
            
            $alink     = new moodle_url("/mod/reader/admin.php", array("a"=>"admin", "id"=>$id, "act"=>$act, "grid"=>$grid, "page"=>$page, "sort"=>$sort, "orderby"=>$orderby, "uncheated"=>$cheatedlog->attempt1.'_'.$cheatedlog->attempt2));
            
            if ($cheatedlog->status == "cheated") 
                $cheatedstring = html_writer::tag('a', 'Set passed', array('href'=>$alink, 'onclick'=>'if(confirm(\'Set passed ?\')) return true; else return false;'));
            
            $byuser  = $DB->get_record("user", array("id"=>$cheatedlog->byuserid));
            $user1   = $DB->get_record("user", array("id"=>$cheatedlog->userid1));
            $user2   = $DB->get_record("user", array("id"=>$cheatedlog->userid2));
            $quiz    = $DB->get_record("reader_publisher", array("id"=>$cheatedlog->quizid));
            
            $picture = $OUTPUT->user_picture($byuser,array($course->id, true, 0, true));
            $table->data[] = array ($picture,
                                    reader_fullname_link_t($byuser),
                                    reader_fullname_link_t($user1),
                                    reader_fullname_link_t($user2),
                                    $quiz->name,
                                    $cheatedlog->status.$cheatedstring,
                                    date("d M Y", $cheatedlog->date)
                                    );
        }
        
        $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);

        if ($excel) {
            foreach ($table->data as $tabledataarray) {
                $myxls->write_string($row, 0, (string) $tabledataarray[1]);
                $myxls->write_string($row, 1, (string) $tabledataarray[2]);
                $myxls->write_string($row, 2, (string) $tabledataarray[3]);
                $myxls->write_number($row, 3, (string) $tabledataarray[4]);
                $myxls->write_number($row, 4, (string) $tabledataarray[5]);
                $myxls->write_number($row, 5, (string) $tabledataarray[6]);
                $row++;
            }
        }

        if ($excel) {
            $workbook->close();
            die();
        }
        
        reader_excel_download_btn();
        
        reader_select_perpage($id, $act, $sort, $orderby, $grid);
        list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);

        echo $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 
        
        if ($table) {
            echo html_writer::table($table);
        }
        
        echo $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 


/*
* Export student records
*/
    } else if ($act == "exportstudentrecords" && has_capability('mod/reader:userdbmanagement', $contextmodule)) {
        $data = $DB->get_records("reader_attempts", array("reader"=>$reader->id));
        
        foreach($data as $data_) {
            if (!$userdata[$data_->userid]) $userdata[$data_->userid] = $DB->get_record("user", array("id"=>$data_->userid));
            if (!$levelsdata[$data_->userid]) $levelsdata[$data_->userid] = $DB->get_record("reader_levels", array("userid"=>$data_->userid));
            if (!$bookdata[$data_->quizid]) $bookdata[$data_->quizid] = $DB->get_record("reader_publisher", array("id"=>$data_->quizid));
            header("Content-Type: text/plain; filename=\"{$COURSE->shortname}_attempts.txt\"");
            echo "{$userdata[$data_->userid]->username},{$data_->uniqueid},{$data_->attempt},{$data_->sumgrades},{$data_->persent},{$data_->bookrating},{$data_->ip},{$bookdata[$data_->quizid]->image},{$data_->timefinish},{$data_->passed},{$data_->persent},{$levelsdata[$data_->userid]->currentlevel}\n";
        }
        die();
        
        
/* 
* Import student record
*/
    } else if ($act == "importstudentrecord" && has_capability('mod/reader:userdbmanagement', $contextmodule)) {
        if (!empty($importstudentrecorddata)) {
            if ($file = reader_getfile($importstudentrecorddata)){
                $filename   = $file->fullpatch;
                
                $zd         = fopen ($filename, "r");
                $contents   = fread ($zd, filesize($filename));
                fclose ($zd);
                
                $contents   = preg_replace('/\r\n|\r/', "\n", $contents); 

                $contents   = explode("\n", $contents);
                
                if ($contents) 
                    reader_red_notice('File was uploaded');
                
                $lastattemptid = $DB->get_field_sql('SELECT uniqueid FROM {reader_attempts} ORDER BY uniqueid DESC');
                
                foreach ($contents as $content_) {
                    $lastattemptid++;
                    list($data['username'],$data['uniqueid'],$data['attempt'],$data['sumgrades'],$data['persent'],$data['bookrating'],$data['ip'],$data['image'],$data['timefinish'],$data['passed'],$data['persent'],$data['currentlevel']) = explode(",", $content_);
                    if (!$userdata[$data['username']]) $userdata[$data['username']] = $DB->get_record("user", array("username"=>$data['username']));
                    if (!$bookdata[$data['image']]) $bookdata[$data['image']] = $DB->get_record("reader_publisher", array("image"=>$data['image']));
                    
                    echo "User name: {$data['username']} (id:{$userdata[$data['username']]->id}) Book id: {$bookdata[$data['image']]->id} <br />\n";
                    
                    $newdata                 = new stdClass;
                    $newdata->uniqueid       = $lastattemptid;
                    $newdata->reader         = $reader->id;
                    $newdata->userid         = $userdata[$data['username']]->id;
                    $newdata->attempt        = $data['attempt'];
                    $newdata->sumgrades      = $data['sumgrades'];
                    $newdata->persent        = $data['persent'];
                    $newdata->passed         = $data['passed'];
                    $newdata->checkbox       = 0;
                    $newdata->timestart      = $data['timefinish'];
                    $newdata->timefinish     = $data['timefinish'];
                    $newdata->timemodified   = $data['timefinish'];
                    $newdata->layout         = 0;
                    $newdata->preview        = 0;
                    $newdata->quizid         = $bookdata[$data['image']]->id;
                    $newdata->bookrating     = $data['bookrating'];
                    $newdata->ip             = $data['ip'];
                    
                    if (!$DB->get_record("reader_attempts", array("userid"=>$newdata->userid, "quizid"=>$newdata->quizid)) && !empty($newdata->userid) && !empty($newdata->quizid)) {
                        
                        if ($idat = $DB->insert_record("reader_attempts", $newdata)) {
                            echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Attempt added ({$idat})\n";
                            echo html_writer::empty_tag('br');
                        } else {
                            echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Can't add attempt \n";
                            echo html_writer::empty_tag('br');
                            echo html_writer::tag('pre', $newdata);
                        }
                    } else if (empty($newdata->userid)) {
                        echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; User not found\n";
                        echo html_writer::empty_tag('br');
                    } else if (empty($newdata->quizid)) {
                        echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Book not found\n";
                        echo html_writer::empty_tag('br');
                    } else {
                        echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Attempt already exist\n";
                        echo html_writer::empty_tag('br');
                    }
                }
                echo get_string('done', 'reader');
            }
        } else {
            class mod_reader_importstudentrecord_form extends moodleform {
                function definition() {
                    global $CFG, $course,$DB;
                    
                    $filepickeroptions = array();
                    $filepickeroptions['maxbytes']  = get_max_upload_file_size($CFG->maxbytes);
            
                    $mform    =& $this->_form;
                    $mform->addElement('filepicker', 'importstudentrecorddata', 'File', null, $filepickeroptions);
                    
                    $this->add_action_buttons(false, $submitlabel="Upload");
                }
            }
            
            $link = new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act));
            
            $mform = new mod_reader_importstudentrecord_form($link);
            $mform->display();
        }
        
        
/*
* Change number of sections
*/
    } else if ($act == "changenumberofsectionsinquiz" && has_capability('mod/reader:manage', $contextmodule)) {
        if ($numberofsections) 
            reader_red_notice(get_string('done', 'reader'));

        class mod_reader_changenumberofsectionsinquiz_form extends moodleform {
            function definition() {
                global $CFG, $course,$DB;
            
                $mform    =& $this->_form;
              
                $mform->addElement('header', 'setgoal', get_string('changenumberofsectionsinquiz', 'reader')); 
                $mform->addElement('text', 'numberofsections', '', array('size'=>'10'));
                  
                $this->add_action_buttons(false, $submitlabel="Save");
            }
        }
        
        $link = new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act));
        
        $mform = new mod_reader_changenumberofsectionsinquiz_form($link);
        $mform->display();
        

/*
* Book no quizzes record
*/
    } else if ($act == "assignpointsbookshavenoquizzes" && has_capability('mod/reader:changestudentslevelsandpromote', $contextmodule)) {
        if(@isset($message))
            reader_red_notice($message);
    
        $table = new html_table();
        
        $titlesarray = array (html_writer::empty_tag('input', array('type'=>'button', 'value'=>get_string('selectall', 'reader'), 'onclick'=>'checkall();'))=>'', 'Image'=>'', 'Username'=>'username', 'Fullname<br />Click to view screen'=>'fullname', 'Current level'=>'currentlevel', 'Total words<br /> this term'=>'totalwordsthisterm', 'Total words<br /> all terms'=>'totalwordsallterms');
        
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act='.$act.'&book='.$book.'&grid='.$grid.'&searchtext='.$searchtext.'&page='.$page);
        $table->align = array ("center", "center", "left", "left", "center", "center", "center");
        $table->width = "100%";
        
        if (!$grid) {
            $grid = NULL;
        }
        
        $coursestudents = get_enrolled_users($context, NULL, $grid);
        
        foreach ($coursestudents as $coursestudent) {
            if (reader_check_search_text($searchtext, $coursestudent)) {
                $picture = $OUTPUT->user_picture($coursestudent,array($course->id, true, 0, true));
                
                unset($data);
                $data['totalwordsthisterm'] = 0;
                $data['totalwordsallterms'] = 0;
                
                if ($attempts = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE userid= ?  and reader= ?  and timefinish > ? ", array($coursestudent->id, $reader->id, $reader->ignordate))) {
                    foreach ($attempts as $attempt) {
                        if (strtolower($attempt->passed) == "true") {
                            $bookdata = $DB->get_record("reader_publisher", array("id"=>$attempt->quizid));
                            $data['totalwordsthisterm'] += $bookdata->words;
                        }
                    }
                }
                
                if ($attempts = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE userid= ? ", array($coursestudent->id))) {
                    foreach ($attempts as $attempt) {
                        if (strtolower($attempt->passed) == "true") {
                            $bookdata = $DB->get_record("reader_publisher", array("id"=>$attempt->quizid));
                            $data['totalwordsallterms'] += $bookdata->words;
                        }
                    }
                }
                
                if ($attemptdata = reader_get_student_attempts($coursestudent->id, $reader)) {
                    if (has_capability('mod/reader:viewstudentreaderscreens', $contextmodule)) {
                        $link = reader_fullname_link_viewasstudent($coursestudent, "grid={$grid}&book={$book}&searchtext={$searchtext}&page={$page}&sort={$sort}&orderby={$orderby}");
                    } else {
                        $link = reader_fullname_link_t($coursestudent);
                    }
                    
                    $table->data[] = array (html_writer::empty_tag('input', array('type'=>'checkbox', 'name'=>'noqstudents[]', 'value'=>$coursestudent->id)),
                                            $picture,
                                            reader_user_link_t($coursestudent),
                                            $link,
                                            $attemptdata[1]['currentlevel'],
                                            $data['totalwordsthisterm'],
                                            $data['totalwordsallterms']
                                            );
                } else {
                    if (has_capability('mod/reader:viewstudentreaderscreens', $contextmodule)) {
                        $link = reader_fullname_link_viewasstudent($coursestudent);
                    } else {
                        $link = reader_fullname_link_t($coursestudent);
                    }
                    
                    $table->data[] = array (html_writer::empty_tag('input', array('type'=>'checkbox', 'name'=>'noqstudents[]', 'value'=>$coursestudent->id)),
                                            $picture,
                                            reader_user_link_t($coursestudent),
                                            $link,
                                            $attemptdata[1]['currentlevel'],
                                            0,0);
                }
            }
        }
        
        $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
        
        reader_print_search_form ($id, $act);
        
        reader_print_group_select_box($course->id, new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act, 'sort'=>$sort, 'orderby'=>$orderby, 'book'=>$book)));
        
        $alink  = new moodle_url("/mod/reader/admin.php", array("id"=>$id, "act"=>$act, 'a'=>'admin', 'book'=>$book));
        
        if (isset($noquizreport)) 
            reader_red_notice($noquizreport);
            
        $publisherform          = array("id=".$id."&publisher=Select_Publisher"=>get_string("selectpublisher", "reader"));
        $publishers = $DB->get_records ("reader_noquiz", NULL, "publisher");
        foreach ($publishers as $publisher_) {
            $publisherform["id=".$id."&publisher=".$publisher_->publisher] = $publisher_->publisher;
        }
        
        $o  = "";
        
        $o .= html_writer::start_tag('form', array('action'=>$alink, 'method'=>'post', 'id'=>'mform1'));
        $o .= html_writer::start_tag('center');
        $o .= html_writer::start_tag('table', array('width'=>'600px'));
        $o .= html_writer::start_tag('tr');
        $o .= html_writer::start_tag('td', array('width'=>'200px'));
        $o .= get_string("publisherseries", "reader");
        $o .= html_writer::end_tag('td');
        $o .= html_writer::start_tag('td', array('width'=>'10px'));
        $o .= html_writer::end_tag('td');
        $o .= html_writer::start_tag('td', array('width'=>'200px'));
        $o .= html_writer::end_tag('td');
        $o .= html_writer::end_tag('tr');
        $o .= html_writer::start_tag('tr');
        $o .= html_writer::start_tag('td', array('valign'=>'top'));
        $o .= html_writer::start_tag('select', array('name'=>'publisher', 'id'=>'id_publisher_noquizzes'));
        foreach ($publisherform as $publisherformkey=>$publisherformvalue) {
            $o .= html_writer::tag('option', $publisherformvalue, array('value'=>$publisherformkey));
        }
        $o .= html_writer::end_tag('select');
        $o .= html_writer::end_tag('td');
        $o .= html_writer::start_tag('td', array('valign'=>'top'));
        $o .= html_writer::end_tag('td');
        $o .= html_writer::start_tag('td', array('valign'=>'top'));
        $o .= html_writer::start_tag('div', array('id'=>'selectthebook'));
        $o .= html_writer::end_tag('div');
        $o .= html_writer::end_tag('td');
        $o .= html_writer::end_tag('tr');
        $o .= html_writer::start_tag('tr');
        $o .= html_writer::start_tag('td', array('colspan'=>'3', 'align'=>'center'));
        $o .= html_writer::empty_tag('input', array('type'=>'submit', 'value'=>'Add quiz to selected users', 'id'=>'id_reader_assignpointsbookshavenoquizzes_submit_btn', 'style'=>'display:none'));
        $o .= html_writer::end_tag('td');
        $o .= html_writer::end_tag('tr');
        $o .= html_writer::end_tag('table');
        $o .= html_writer::end_tag('center');
        
        //--------------------------------------------//
        
        reader_select_perpage($id, $act, $sort, $orderby, $grid);
        
        list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);
        
        $alinkpadding     = new moodle_url("/mod/reader/admin.php", array("a"=>"admin", "id"=>$id, "act"=>$act, "grid"=>$grid, "sort"=>$sort, "orderby"=>$orderby, "book"=>$book));

        $o .= $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 
        
        if ($table) {
            $o .= html_writer::table($table);
        }
        
        $o .= html_writer::end_tag('form');
        
        $o .= $OUTPUT->render(new paging_bar($totalcount, $page, $perpage, $alinkpadding)); 
        
        echo $o;


/*
* Adjust scores
*/
    } else if ($act == "adjustscores" && has_capability('mod/reader:readerviewreports', $contextmodule)) {
        $table = new html_table();
        
        if ($sort == "username") {
            $sort = "title";
        }
        $titlesarray = array (''=>'', 'Full Name'=>'username', get_string('title', 'reader')=>'title', get_string('publisher', 'reader')=>'publisher', get_string('level', 'reader')=>'level', get_string('readinglevel', 'reader')=>'rlevel', get_string('readinglevel', 'reader')=>'rlevel', 'Score'=>'score', 'P/F/C'=>'', 'Finishtime'=>'finishtime', 'Option'=>'');
        
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act='.$act.'&searchtext='.$searchtext);
        $table->align = array ("left", "left", "left", "left", "center", "center", "center", "center", "center", "center", "center");
        $table->width = "100%";
        
        if ($book >= 1) 
            $bookdata = $DB->get_record("reader_publisher", array( "id"=>$book));
            
        $attemptsofbook = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE quizid= ?  and reader= ? ", array($book, $reader->id));
        
        while(list($attemptsofbookkey, $attemptsofbook_) = each($attemptsofbook)) {
            if (strtolower($attemptsofbook_->passed) == 'true') {
                $pfcmark = 'P';
            } else if (strtolower($attemptsofbook_->passed) == 'false') {
                $pfcmark = 'F';
            } else { 
                $pfcmark = 'C'; 
            }
            
            $userdata = $DB->get_record("user", array("id"=>$attemptsofbook_->userid));
            
            $table->data[] = array (html_writer::empty_tag('input', array('type'=>'checkbox', 'name'=>'adjustscoresupbooks[]', 'value'=>$attemptsofbook_->id)),
                                    fullname($userdata),
                                    array(html_writer::link(new moodle_url('/mod/reader/report.php', array('idh'=>$id, 'q'=>$bookdata->quizid, 'mode'=>'analysis', 'b'=>$bookdata->id)), $bookdata->name), $bookdata->name),
                                    $bookdata->publisher,
                                    $bookdata->level,
                                    reader_get_reader_difficulty($reader, $bookdata->id),
                                    //$bookdata->difficulty,
                                    round($attemptsofbook_->persent)."%",
                                    $pfcmark,
                                    date("d-M-Y", $attemptsofbook_->timemodified),
                                    'deleted');
        }

        $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
        
        $publisherform          = array("id=".$id."&publisher=Select_Publisher"=>get_string("selectpublisher", "reader"));
        $publishers             = $DB->get_records ("reader_publisher", NULL, "publisher");
        foreach ($publishers as $publisher_) {
            $publisherform["id=".$id."&publisher=".$publisher_->publisher] = $publisher_->publisher;
        }

        $alink  = new moodle_url("/mod/reader/admin.php", array("id"=>$id, "act"=>$act, 'a'=>'admin'));
        
        if (isset($adjustscorestext)) {
            reader_red_notice($adjustscorestext);
        }
        
        $o  = "";
        $o .= html_writer::start_tag('table', array('style'=>'width:100%'));
        $o .= html_writer::start_tag('tr');
        $o .= html_writer::start_tag('td', array('align'=>'right'));
        $o .= html_writer::start_tag('form', array('action'=>$alink, 'method'=>'post', 'id'=>'mform1'));
        $o .= html_writer::start_tag('center');
        $o .= html_writer::start_tag('table', array('width'=>'600px'));
        $o .= html_writer::start_tag('tr');
        $o .= html_writer::tag('td', get_string("publisherseries", "reader"), array('width'=>'200px'));
        $o .= html_writer::start_tag('td', array('width'=>'10px'));
        $o .= html_writer::end_tag('td');
        $o .= html_writer::start_tag('td', array('width'=>'200px'));
        $o .= html_writer::end_tag('td');
        $o .= html_writer::end_tag('tr');
        $o .= html_writer::start_tag('td', array('valign'=>'top'));
        $o .= html_writer::start_tag('select', array('name'=>'publisher', 'id'=>'id_reader_adjustscores_publisher'));

        foreach ($publisherform as $publisherformkey=>$publisherformvalue) {
            if ($publisherformvalue == $publisher)
                $o .= html_writer::tag('option', $publisherformvalue, array('value'=>$publisherformkey, 'selected'=>'selected'));
            else
                $o .= html_writer::tag('option', $publisherformvalue, array('value'=>$publisherformkey));
        }
        
        $o .= html_writer::end_tag('select');
        $o .= html_writer::end_tag('td');
        $o .= html_writer::start_tag('td', array('valign'=>'top'));
        $o .= html_writer::end_tag('td');
        $o .= html_writer::start_tag('td', array('valign'=>'top'));
        $o .= html_writer::tag('div', '', array('id'=>'selectthebook'));
        $o .= html_writer::end_tag('td');
        $o .= html_writer::end_tag('tr');
        $o .= html_writer::start_tag('tr');
        $o .= html_writer::start_tag('td', array('colspan'=>3, 'align'=>'center'));
        $o .= html_writer::end_tag('td');
        $o .= html_writer::end_tag('tr');
        $o .= html_writer::start_tag('tr');
        $o .= html_writer::start_tag('td', array('colspan'=>3, 'align'=>'center'));
        $o .= html_writer::empty_tag('input', array('type'=>'submit', 'value'=>'Select quiz'));
        $o .= html_writer::end_tag('td');
        $o .= html_writer::end_tag('tr');
        $o .= html_writer::end_tag('table');
        $o .= html_writer::end_tag('form');
        $o .= html_writer::end_tag('center');
        $o .= html_writer::end_tag('td');
        $o .= html_writer::end_tag('tr');
        $o .= html_writer::end_tag('table');
        
        $alink  = new moodle_url("/mod/reader/admin.php", array("id"=>$id, "act"=>$act, "book"=>$book, 'a'=>'admin'));
        
        $o .= html_writer::start_tag('form', array('action'=>$alink, 'method'=>'post'));
        $o .= html_writer::start_tag('div', array('style'=>'20px 0;'));
        $o .= html_writer::start_tag('table');
        $o .= html_writer::start_tag('tr');
        $o .= html_writer::tag('td', 'Update selected adding', array('width'=>'180px;'));
        $o .= html_writer::start_tag('td', array('width'=>'60px;'));
        $o .= html_writer::empty_tag('input', array('type'=>'text', 'name'=>'adjustscoresaddpoints', 'value'=>'', 'style'=>'width:60px;'));
        $o .= html_writer::end_tag('td');
        $o .= html_writer::tag('td', 'points', array('width'=>'70px;'));
        $o .= html_writer::start_tag('td');
        $o .= html_writer::empty_tag('input', array('type'=>'submit', 'value'=>get_string('add', 'reader')));
        $o .= html_writer::end_tag('td');
        $o .= html_writer::end_tag('tr');
        $o .= html_writer::end_tag('table');
        $o .= html_writer::start_tag('table');
        $o .= html_writer::start_tag('tr');
        $o .= html_writer::tag('td', 'Update all > ', array('width'=>'100px;'));
        $o .= html_writer::start_tag('td', array('width'=>'60px;'));
        $o .= html_writer::empty_tag('input', array('type'=>'text', 'name'=>'adjustscoresupall', 'value'=>'', 'style'=>'width:50px;'));
        $o .= html_writer::end_tag('td');
        $o .= html_writer::tag('td', 'points and < ', array('width'=>'90px;'));
        $o .= html_writer::start_tag('td', array('width'=>'60px;'));
        $o .= html_writer::empty_tag('input', array('type'=>'text', 'name'=>'adjustscorespand', 'value'=>'', 'style'=>'width:50px;'));
        $o .= html_writer::end_tag('td');
        $o .= html_writer::tag('td', 'points by ', array('width'=>'90px;'));
        $o .= html_writer::start_tag('td', array('width'=>'60px;'));
        $o .= html_writer::empty_tag('input', array('type'=>'text', 'name'=>'adjustscorespby', 'value'=>'', 'style'=>'width:50px;'));
        $o .= html_writer::end_tag('td');
        $o .= html_writer::tag('td', 'points', array('width'=>'70px;'));
        $o .= html_writer::start_tag('td');
        $o .= html_writer::empty_tag('input', array('type'=>'submit', 'value'=>get_string('add', 'reader')));
        $o .= html_writer::end_tag('td');
        $o .= html_writer::end_tag('tr');
        $o .= html_writer::end_tag('table');
        $o .= html_writer::end_tag('div');

        if ($table) {
            $o .= html_writer::table($table);
        }
        
        $o .= html_writer::end_tag('form');
        
        echo $o;
    }
    
    echo $OUTPUT->box_end();
    
    echo $OUTPUT->footer();
    

