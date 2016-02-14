<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin

define("READER_GRADEHIGHEST", "1");
define("READER_GRADEAVERAGE", "2");
define("READER_ATTEMPTFIRST", "3");
define("READER_ATTEMPTLAST",  "4");
define('READER_REVIEW_OPEN',       0x3c00fc0);
define('READER_REVIEW_CLOSED',    0x3c03f000);
define('READER_REVIEW_SCORES',          2*0x1041);
define('READER_STATE_DURING', 'during'); 
define('READER_REVIEW_IMMEDIATELY', 0x3c003f);
define('READER_REVIEW_FEEDBACK',        4*0x1041);
define('READER_REVIEW_GENERALFEEDBACK',32*0x1041);

//------------------------ Images LInks -----------------------//
$reader_images                   = array();
$reader_images['open']           = new moodle_url('/mod/reader/img/open.gif');
$reader_images['closed']         = new moodle_url('/mod/reader/img/closed.gif');
$reader_images['pw']             = new moodle_url('/mod/reader/img/pw.png');
$reader_images['zoomloader']     = new moodle_url('/mod/reader/img/zoomloader.gif');

//------------------------ Set default values -----------------//

$cheated_message = "We are sorry to say that the MoodleReader program has discovered that you have probably cheated when you took the above quiz.  'Cheating' means that you either helped another person to take the quiz or that you received help from someone else to take the quiz.  Both people have been marked 'cheated'.
        
Sometimes the computer makes mistakes.  If you honestly did not receive help and did not help someone else, then please inform your teacher and your points will be restored.

--The MoodleReader Module Manager";

$not_cheated_message = "We are happy to inform you that your points for the above quiz have been restored. We apologize for the mistake!
        
--The MoodleReader Module Manager";

$readercfg = get_config('reader');

if (!isset($readercfg->reader_quiztimeout))
  set_config('reader_quiztimeout', '15', 'reader');
if (!isset($readercfg->reader_pointreport))
  set_config('reader_pointreport', '0', 'reader');
if (!isset($readercfg->reader_percentforreading))
  set_config('reader_percentforreading', '60', 'reader');
if (!isset($readercfg->reader_questionmark))
  set_config('reader_questionmark', '0', 'reader');
if (!isset($readercfg->reader_quiznextlevel))
  set_config('reader_quiznextlevel', '6', 'reader');
if (!isset($readercfg->reader_quizpreviouslevel))
  set_config('reader_quizpreviouslevel', '3', 'reader');
if (!isset($readercfg->reader_quizonnextlevel))
  set_config('reader_quizonnextlevel', '1', 'reader');
if (!isset($readercfg->reader_bookcovers))
  set_config('reader_bookcovers', '1', 'reader');
if (!isset($readercfg->reader_attemptsofday))
  set_config('reader_attemptsofday', '0', 'reader');
if (!isset($readercfg->reader_usecourse))
  set_config('reader_usecourse', '0', 'reader');
if (!isset($readercfg->reader_iptimelimit))
  set_config('reader_iptimelimit', '0', 'reader');
if (!isset($readercfg->reader_levelcheck))
  set_config('reader_levelcheck', '1', 'reader');
if (!isset($readercfg->reader_reportwordspoints))
  set_config('reader_reportwordspoints', '0', 'reader');
if (!isset($readercfg->reader_wordsprogressbar))
  set_config('reader_wordsprogressbar', '1', 'reader');
if (!isset($readercfg->reader_checkbox))
  set_config('reader_checkbox', '0', 'reader');
if (!isset($readercfg->reader_sendmessagesaboutcheating))
  set_config('reader_sendmessagesaboutcheating', '1', 'reader');
if (!isset($readercfg->reader_editingteacherrole))
  set_config('reader_editingteacherrole', '1', 'reader');
if (!isset($readercfg->reader_update))
  set_config('reader_update', '1', 'reader');
if (!isset($readercfg->reader_last_update))
  set_config('reader_last_update', '1', 'reader');
if (!isset($readercfg->reader_update_interval))
  set_config('reader_update_interval', '604800', 'reader');
if (!isset($readercfg->reader_cheated_message))
  set_config('reader_cheated_message', $cheated_message, 'reader');
if (!isset($readercfg->reader_not_cheated_message))
  set_config('reader_not_cheated_message', $not_cheated_message, 'reader');

if (!isset($readercfg->reader_serverlink))
  set_config('reader_serverlink', 'http://moodlereader.net/quizbank', 'reader');
  
if (!isset($readercfg->reader_serverlogin))
  set_config('reader_serverlogin', '', 'reader');
  
if (!isset($readercfg->reader_serverpassword))
  set_config('reader_serverpassword', '', 'reader');

$readercfg = get_config('reader');

//-------------------------------------------------------------//


function reader_add_instance($reader) {
    global $CFG, $USER, $DB;

    $reader->timemodified = time();
    $reader->id           = $DB->insert_record("reader", $reader);

    if (isset($reader->promotionstop)) {
        $allstudents = $DB->get_records("reader_levels", array("readerid" => $reader->id));
        foreach ($allstudents as $allstudents_) {
            $DB->set_field("reader_levels",  "promotionstop",  $reader->promotionstop, array( "id" => $allstudents_->id));
        }
    }
    
    return $reader->id;
}


function reader_update_instance($reader, $id) {
    global $CFG, $DB;
   
    $reader->timemodified = time();
    $reader->id           = $reader->instance;
    
    //No promotion after level
    if (isset($reader->promotionstop)) {
        $allstudents = $DB->get_records("reader_levels", array("readerid" => $reader->id));
        foreach ($allstudents as $allstudents_) {
            $DB->set_field("reader_levels",  "promotionstop",  $reader->promotionstop, array( "id" => $allstudents_->id));
        }
    }

    return $DB->update_record("reader", $reader);
}


function reader_submit_instance($reader, $id) {
    global $CFG;
    return true;
}


function reader_delete_instance($id) {
    global $CFG,$DB;

    if (! $reader = $DB->get_record("reader", array( "id" => "$id"))) {
        return false;
    }

    $result = true;

    # Delete any dependent records here #
    if (! $DB->delete_records("reader", array("id" => "$reader->id"))) {
        $result = false;
    }

    return $result;
}


function reader_user_outline($course, $user, $mod, $reader) {
    return $return;
}


function reader_user_complete($course, $user, $mod, $reader) {
    return true;
}


function reader_print_recent_activity($course, $isteacher, $timestart) {
    global $CFG;
    return false;
}


function reader_cron () {
    global $CFG,$DB;
    
    $textmessages = $DB->get_records ("reader_messages");
    
    foreach ($textmessages as $textmessage) {
        $before = $textmessage->timebefore - time();
        
        if ($before <= 0) {
            $DB->delete_records("reader_messages", array("id" => $textmessage->id));
        }
    }
    
    //Check questions list
    $publishersquizzes = $DB->get_records ("reader_publisher");
    
    foreach ($publishersquizzes as $publishersquizze) {
        $quizdata      = $DB->get_record("quiz", array( "id" => $publishersquizze->quizid));
        $questions     = explode(",", $quizdata->questions);
        $answersgrade  = $DB->get_records ("reader_question_instances", array("quiz" => $publishersquizze->id)); 
        $doublecheck   = array();
        foreach ($answersgrade as $answersgrade_) {
            if (!in_array($answersgrade_->question, $questions)) { 
                $DB->delete_records("reader_question_instances", array("quiz" => $publishersquizze->id, "question" => $answersgrade_->question));
                $editedquizzes[$publishersquizze->id] = $publishersquizze->quizid;
            } 
            if (!in_array($answersgrade_->question, $doublecheck)) {
                $doublecheck[] = $answersgrade_->question;
            } else {
                add_to_log(1, "reader", "Cron", "", "Double entries found!! reader_question_instances; quiz: {$publishersquizze->id}; question: {$answersgrade_->question}");
            }
        }
    }
    
    //----------Slashes fix-----------//
    $publishersquizzes = $DB->get_records ("reader_publisher");
    
    foreach ($publishersquizzes as $publishersquizze) {
        if (strstr($publishersquizze->name, "\'")) {
            $DB->set_field("reader_publisher",  "name", stripslashes($publishersquizze->name), array( "id" => $publishersquizze->id));
            echo '..reader title updating: '.$publishersquizze->name.'
';
        }
    }

///Delete problem quizzes

    $DB->delete_records("reader_attempts", array("sumgrades"=>0, "bookrating"=>0, "readtime"=>NULL, "ip"=>NULL, "flow"=>NULL));

    return true;
}


function reader_grades($readerid) {
    return NULL;
}


function reader_get_participants($readerid) {
    return false;
}


function reader_get_user_attempt_unfinished($readerid, $userid) {
    $attempts = reader_get_user_attempts($readerid, $userid, 'unfinished', true);
    if ($attempts) {
        return array_shift($attempts);
    } else {
        return false;
    }
}


function reader_get_stlevel_data($reader) {
    global $USER, $CFG,$DB;
    
    $counter               = array();
    $counter['countlevel'] = 0;
    $counter['prevlevel']  = 0;
    $counter['nextlevel']  = 0;

    if (!$studentlevel = $DB->get_record("reader_levels", array( "userid" => $USER->id,  "readerid" => $reader->id))) {
        $createlevel                    = new stdClass;
        $createlevel->userid            = $USER->id;
        $createlevel->startlevel        = 0;
        $createlevel->currentlevel      = 0;
        $createlevel->readerid          = $reader->id;
        $createlevel->promotionstop     = $reader->promotionstop;
        $createlevel->time              = time();
        $DB->insert_record('reader_levels', $createlevel);
        $studentlevel = $DB->get_record("reader_levels", array( "userid" => $USER->id,  "readerid" => $reader->id));
    }
    
    $attemptsofbook = $DB->get_records_sql("SELECT ra.*,rp.difficulty,rp.id as rpid FROM {reader_attempts} ra INNER JOIN {reader_publisher} rp ON rp.id = ra.quizid WHERE ra.userid= ?  and ra.reader= ?  and ra.timefinish> ?  ORDER BY ra.timemodified", array($USER->id, $reader->id, $reader->ignordate));
    
    foreach ($attemptsofbook as $attemptsofbook_) {
        if (reader_get_reader_difficulty($reader, $attemptsofbook_->rpid) == $studentlevel->currentlevel) {
            if (strtolower($attemptsofbook_->passed) == "true") {
                $counter['countlevel'] += 1;
            }
        }
        
        if (($studentlevel->time < $attemptsofbook_->timefinish) && (reader_get_reader_difficulty($reader, $attemptsofbook_->rpid) == ($studentlevel->currentlevel + 1))) {
            $counter['nextlevel'] += 1;
        }
        
        if ($studentlevel->currentlevel >= $studentlevel->startlevel) {
            if (($studentlevel->time < $attemptsofbook_->timefinish) && (reader_get_reader_difficulty($reader, $attemptsofbook_->rpid) == ($studentlevel->currentlevel - 1))) {
                $counter['prevlevel'] += 1;
            }
        } else {
            $counter['prevlevel'] = -1;
        }
    }

    //---------Add Promotion Stop----//
    if ($studentlevel->promotionstop > 0 && $studentlevel->promotionstop == $studentlevel->currentlevel) {
        $DB->set_field("reader_levels",  "nopromote",  1, array( "readerid" => $reader->id,  "userid" => $USER->id));
        $studentlevel->nopromote = 1;
    }
    
    //---------Add NoPromote-----//
    if ($studentlevel->nopromote == 1) {
        $counter['countlevel'] = 1;
    }

    //---------Check for next level------------//
    if ($counter['countlevel'] >= $reader->nextlevel) {
        $studentlevel->currentlevel += 1;
        $DB->set_field("reader_levels",  "currentlevel",  $studentlevel->currentlevel, array( "readerid" => $reader->id,  "userid" => $USER->id));
        $DB->set_field("reader_levels", "time", time(), array("readerid" => $reader->id, "userid" => $USER->id));
        $counter['countlevel']       = 0;
        $counter['prevlevel']        = 0;
        $counter['nextlevel']        = 0;
        echo html_writer::script('alert("Congratulations!!  You have been promoted to Level '.$studentlevel->currentlevel.'!");');
    }
    //-----------------------------------------//
    
    //---------RETURN-------------//
    $leveldata                      = array();
    $leveldata['studentlevel']      = $studentlevel->currentlevel;
    $leveldata['onthislevel']       = $reader->nextlevel - $counter['countlevel'];
    
    if ($counter['prevlevel'] != -1) {
        $leveldata['onprevlevel']   = $reader->quizpreviouslevel - $counter['prevlevel'];
    } else {
        $leveldata['onprevlevel']   = -1;
    }
    
    $leveldata['onnextlevel']       = $reader->quiznextlevel - $counter['nextlevel'];
    //----------------------------//
    
    return $leveldata;
}


function reader_get_user_attempts($readerid, $userid, $status = 'finished', $includepreviews = false) {
    global $DB;
    
    $status_condition = array(
        'all' => '',
        'finished' => ' AND timefinish > 0',
        'unfinished' => ' AND timefinish = 0'
    );
    $previewclause = '';
    if (!$includepreviews) {
        $previewclause = ' AND preview = 0';
    }
    if ($attempts = $DB->get_records_select('reader_attempts',
            "reader = ? AND userid = ? " . $previewclause . $status_condition[$status], array($readerid, $userid),
            'attempt ASC')) {
        return $attempts;
    } else {
        return array();
    }
}


function reader_create_attempt($reader, $attemptnumber, $idofquiz) {
    global $USER, $CFG,$DB;
   
    $quizdata = $DB->get_record("reader_publisher", array( "id" => $idofquiz));

    if (!$attemptnumber > 1 or !$reader->attemptonlast or !$attempt = $DB->get_record('reader_attempts', array( 'reader' => $reader->id,  'userid' => $USER->id,  'attempt' => $attemptnumber-1))) {
        $attempt->reader   = $reader->id;
        $attempt->userid   = $USER->id;
        $attempt->preview  = 0;
        
        if (empty($quizdata->quizid)) {
            $allquestions  = $DB->get_records ("question", array("category" => $idofquiz));
            $rand_keys     = $allquestions;
            $questionlist  = "";

            foreach ($rand_keys as $rand_key) {
                if (!empty($rand_key->id)) {
                    $questionlist .= $rand_key->id.',';
                }
            }
        } else {
            $allquestions = $DB->get_record("quiz", array( "id" => $quizdata->quizid));
            $questionlist = $allquestions->questions;
        }
        
        $reader->questions = $questionlist;
        
        $attempt->layout   = reader_repaginate($reader->questions, $reader->questionsperpage);
        $attempt->quizid   = $idofquiz;
    }

    $timenow               = time();
    $attempt->attempt      = $attemptnumber;
    $attempt->sumgrades    = 0.0;
    $attempt->timestart    = $timenow;
    $attempt->timefinish   = 0;
    $attempt->timemodified = $timenow;
    
    //---------???????? ??????? ? ?? ?????? ??? reader_question_instances ---//
    $questionsid = explode (",", $attempt->layout);
    foreach ($questionsid as $questionsid_) {
        if ($questionsid_ && $questionsid_ != 0) {
            $questiondata = $DB->get_record("question", array( "id" => $questionsid_));
            
            $questiongrade           = new stdClass;
            $questiongrade->quiz     = $idofquiz;
            $questiongrade->question = $questionsid_;
            
            if (empty($quizdata->quizid)) {
                $questiongrade->grade = $questiondata->defaultgrade; 
            } else {
                if ($questiongradeofquiz = $DB->get_record("quiz_question_instances", array( "quiz" => $quizdata->quizid,  "question" => $questionsid_))) {
                    $questiongrade->grade = $questiongradeofquiz->grade;
                } else {
                    $questiongrade->grade = 1;
                }
            }
            
            if (!$DB->get_record("reader_question_instances", array( "quiz" => $idofquiz,  "question" => $questionsid_))) {
                $questiongrade->grade = round($questiongrade->grade);
                $DB->insert_record ("reader_question_instances", $questiongrade);
            }
        }
    }
    //-----------------------------------------------------------------------//

    return $attempt;
}


function reader_delete_attempt($attempt, $reader) {
    global $DB;
    
    if (is_numeric($attempt)) {
        if (!$attempt = $DB->get_record('reader_attempts', array( 'id' => $attempt))) {
            return;
        }
    }

    if ($attempt->reader != $reader->id) {
        debugging("Trying to delete attempt $attempt->id which belongs to reader $attempt->reader " .
                "but was passed reader $reader->id.");
        return;
    }

    $DB->delete_records('reader_attempts', array('id' => $attempt->id));
    delete_attempt($attempt->uniqueid);

    // Search reader_attempts for other instances by this user.
    // If none, then delete record for this reader, this user from reader_grades
    // else recalculate best grade

    $userid = $attempt->userid;
    if (!record_exists('reader_attempts', 'userid', $userid, 'reader', $reader->id)) {
        $DB->delete_records('reader_grades', array('userid' => $userid,'reader' => $reader->id));
    } else {
        reader_save_best_grade($reader, $userid);
    }

    reader_update_grades($reader, $userid);
}


function reader_save_best_grade($reader, $userid = null) {
    global $USER,$DB;

    if (empty($userid)) {
        $userid = $USER->id;
    }
    // Get all the attempts made by the user
    if (!$attempts = reader_get_user_attempts($reader->id, $userid)) {
        notify('Could not find any user attempts');
        return false;
    }
    // Calculate the best grade
    $bestgrade = reader_calculate_best_grade($reader, $attempts);
    $bestgrade = reader_rescale_grade($bestgrade, $reader);
    // Save the best grade in the database
    if ($grade = $DB->get_record('reader_grades', array( 'reader' => $reader->id,  'userid' => $userid))) {
        $grade->grade        = $bestgrade;
        $grade->timemodified = time();
        if (!$DB->update_record('reader_grades', $grade)) {
            notify('Could not update best grade');
            return false;
        }
    } else {
        $grade->reader       = $reader->id;
        $grade->userid       = $userid;
        $grade->grade        = $bestgrade;
        $grade->timemodified = time();
        if (!$DB->insert_record('reader_grades', $grade)) {
            notify('Could not insert new best grade');
            return false;
        }
    }

    reader_update_grades($reader, $userid);
    return true;
}


function reader_update_grades($reader=null, $userid=0, $nullifnone=true) {
    global $CFG,$DB;
    
    if (!function_exists('grade_update')) { 
        require_once($CFG->libdir.'/gradelib.php');
    }
    if ($reader != null) {
        if ($grades = reader_get_user_grades($reader, $userid)) {
            reader_grade_item_update($reader, $grades);
        } else if ($userid and $nullifnone) {
            $grade = new stdClass();
            $grade->userid   = $userid;
            $grade->rawgrade = NULL;
            reader_grade_item_update($reader, $grade);
        }
    } else {
        $sql = "SELECT a.*, cm.idnumber as cmidnumber, a.course as courseid
                  FROM {reader} a, {course_modules} cm, {modules} m
                 WHERE m.name='reader' AND m.id=cm.module AND cm.instance=a.id";

        if ($rs = $DB->get_recordset_sql($sql)) {
          foreach ($rs as $reader) {
              if ($reader->grade != 0) {
                  reader_update_grades($reader, 0, false);
              } else {
                  reader_grade_item_update($reader);
              }
          }
          $rs->close();
        }
    }
}


function reader_calculate_best_grade($reader, $attempts) {
    switch ($reader->grademethod) {
        case READER_ATTEMPTFIRST:
            foreach ($attempts as $attempt) {
                return $attempt->sumgrades;
            }
            break;

        case READER_ATTEMPTLAST:
            foreach ($attempts as $attempt) {
                $final = $attempt->sumgrades;
            }
            return $final;

        case READER_GRADEAVERAGE:
            $sum = 0;
            $count = 0;
            foreach ($attempts as $attempt) {
                $sum += $attempt->sumgrades;
                $count++;
            }
            return (float)$sum/$count;

        default:
        case READER_GRADEHIGHEST:
            $max = 0;
            foreach ($attempts as $attempt) {
                if ($attempt->sumgrades > $max) {
                    $max = $attempt->sumgrades;
                }
            }
            return $max;
    }
}


function reader_rescale_grade($rawgrade, $reader) {
    if ($reader->sumgrades) {
        return round($rawgrade*$reader->grade/$reader->sumgrades, $reader->decimalpoints);
    } else {
        return 0;
    }
}


function reader_get_user_grades($reader, $userid=0) {
    global $CFG,$DB;

    $user = $userid ? "AND u.id = $userid" : "";

    $sql = "SELECT u.id, u.id AS userid, g.grade AS rawgrade, g.timemodified AS dategraded, MAX(a.timefinish) AS datesubmitted
            FROM {$CFG->prefix}user u, {$CFG->prefix}reader_grades g, {$CFG->prefix}reader_attempts a
            WHERE u.id = g.userid AND g.reader = {$reader->id} AND a.reader = g.reader AND u.id = a.userid
                  $user
            GROUP BY u.id, g.grade, g.timemodified";

    return $DB->get_records_sql($sql);
}


function reader_grade_item_update($reader, $grades=NULL) {
    global $CFG;

    if (!function_exists('grade_update')) { 
        require_once($CFG->libdir.'/gradelib.php');
    }

    if (array_key_exists('cmidnumber', $reader)) { 
        $params = array('itemname'=>$reader->name, 'idnumber'=>$reader->cmidnumber);
    } else {
        $params = array('itemname'=>$reader->name);
    }

    if ($reader->grade > 0) {
        $params['gradetype'] = GRADE_TYPE_VALUE;
        $params['grademax']  = $reader->grade;
        $params['grademin']  = 0;

    } else {
        $params['gradetype'] = GRADE_TYPE_NONE;
    }

    if (!($reader->review & READER_REVIEW_SCORES & READER_REVIEW_CLOSED)
    and !($reader->review & READER_REVIEW_SCORES & READER_REVIEW_OPEN)) {
        $params['hidden'] = 1;

    } else if ( ($reader->review & READER_REVIEW_SCORES & READER_REVIEW_CLOSED)
           and !($reader->review & READER_REVIEW_SCORES & READER_REVIEW_OPEN)) {
        if ($reader->timeclose) {
            $params['hidden'] = $reader->timeclose;
        } else {
            $params['hidden'] = 1;
        }

    } else {
        $params['hidden'] = 0;
    }

    return grade_update('mod/quiz', $reader->course, 'mod', 'reader', $reader->id, 0, $grades, $params);
}


function reader_repaginate($layout, $perpage, $shuffle=false) {
    $layout    = preg_replace('/,+/',',', $layout);
    $layout    = str_replace(',0', '', $layout); // remove existing page breaks
    $questions = explode(',', $layout);
    if ($shuffle) {
        srand((float)microtime() * 1000000); // for php < 4.2
        shuffle($questions);
    }
    $i = 1;
    $layout = '';
    foreach ($questions as $question) {
        if ($perpage and $i > $perpage) {
            $layout .= '0,';
            $i = 1;
        }
        $layout .= $question.',';
        $i++;
    }
    return $layout.'0';
}


function reader_questions_on_page($layout, $page) {
    $pages = explode(',0', $layout);
    return trim($pages[$page], ',');
}


function reader_questions_in_reader($layout) {
    return str_replace(',0', '', $layout);
}


function reader_number_of_pages($layout) {
    return substr_count($layout, ',0');
}


function reader_first_questionnumber($readerlayout, $pagelayout) {
    global $CFG,$DB;

    $start = strpos($readerlayout, ','.$pagelayout.',')-2;
    if ($start > 0) {
        $prevlist = substr($readerlayout, 0, $start);
        return $DB->get_field_sql("SELECT sum(length)+1 FROM {question}
         WHERE id IN (?)",array($prevlist));
    } else {
        return 1;
    }
}


function reader_get_renderoptions($reviewoptions, $state) {
    $options                           = new stdClass;
    $options->readonly                 = question_state_is_closed($state);
    $options->feedback                 = question_state_is_graded($state) && ($reviewoptions & READER_REVIEW_FEEDBACK & READER_REVIEW_IMMEDIATELY);
    $options->validation               = QUESTION_EVENTVALIDATE === $state->event;
    $options->correct_responses        = $options->readonly && ($reviewoptions & READER_REVIEW_ANSWERS & READER_REVIEW_IMMEDIATELY);
    $options->generalfeedback          = question_state_is_graded($state) && ($reviewoptions & READER_REVIEW_GENERALFEEDBACK & READER_REVIEW_IMMEDIATELY);
    $options->overallfeedback          = false;
    $options->responses                = true;
    $options->scores                   = true;
    $options->readerstate              = READER_STATE_DURING;

    return $options;
}


function reader_questions_in_quiz($layout) {
    return str_replace(',0', '', $layout);
}


function reader_scale_used ($readerid,$scaleid) {
    $return = false;
   
    return $return;
}


function reader_make_table_headers ($titlesarray, $orderby, $sort, $link) {
    global $USER, $CFG;

    if ($orderby == "ASC") {
        $columndir    = "DESC";
        $columndirimg = "down";
    } else {
        $columndir    = "ASC";
        $columndirimg = "up";
    }

    foreach ($titlesarray as $titlesarraykey => $titlesarrayvalue) {
        if ($sort != $titlesarrayvalue) {
            $columnicon = "";
        } else {
            $iconlink   = new moodle_url("/theme/image.php", array("theme" => $CFG->theme, "image" => "t/{$columndirimg}", "rev" => $CFG->themerev));
            $columnicon = " <img src=\"{$iconlink}\" alt=\"\" />";
        }
        if (!empty($titlesarrayvalue)) {
            $table->head[] = "<a href=\"".$link."&sort=$titlesarrayvalue&orderby=$columndir\">$titlesarraykey</a>$columnicon";
        } else {
            $table->head[] = "$titlesarraykey";
        } 
    }
    
    return $table->head;
}


function reader_sort_table_data ($data, $titlesarray, $orderby, $sort) {
    global $USER, $CFG;

    $j = 0;
    $finaldata = array();
    
    if ($sort) {
        foreach ($titlesarray as $titlesarray_) {
            if ($titlesarray_ == $sort) {
                $orderkey = $j;
            }
            $j++;
        }
    }
    
    if (!isset($orderkey)) {
        $orderkey = 0;
    }

    $i = 0;
    $datavalue = array();
    $newarray  = array();

    foreach ((array)$data as $datakey => $datavalue) {
        if (!is_array($datavalue[$orderkey])) {
            $key = $datavalue[$orderkey];
        } else {
            $key = $datavalue[$orderkey][1];
        }

        for ($j=0; $j < count($datavalue); $j++) {
            if (!is_array($datavalue[$j])) {
                $newarray[(string)$key][$i][$j] = $datavalue[$j];
            } else {
                $newarray[(string)$key][$i][$j] = $datavalue[$j][0];
            }
        }
        
        $i ++;
    }
    
    if (!isset($newarray)) $newarray = array();
    
    if (empty($orderby) || $orderby == "ASC") {
      ksort ($newarray); 
    } else {
      krsort ($newarray);
    }
    
    reset($newarray);
    
    foreach ((array)$newarray as $newarray_) {
        foreach ($newarray_ as $newarray__) {
            $newarraynew = array ();
            foreach ($newarray__ as $newarray___) {
                $newarraynew[] = $newarray___;
            }
            $finaldata[] = $newarraynew;
        }
    }
    
    return $finaldata;
}


function reader_question_preview_button($quiz, $question) {
    global $CFG, $COURSE;
    
    if (!question_has_capability_on($question, 'use', $question->category)){
        return '';
    }
    
    $strpreview = get_string('previewquestion', 'quiz');
    $quizorcourseid = $quiz->id?('&amp;quizid=' . $quiz->id):('&amp;courseid=' .$COURSE->id);
    
    $iconlink = new moodle_url("/theme/image.php", array("theme" => $CFG->theme, "image" => "preview", "rev" => $CFG->themerev));
    
    return link_to_popup_window('/question/preview.php?id=' . $question->id . $quizorcourseid, 'questionpreview',
            "<img src=\"{$iconlink}\" class=\"iconsmall\" alt=\"$strpreview\" />",
            0, 0, $strpreview, QUESTION_PREVIEW_POPUP_OPTIONS, true);
} 


function reader_get_student_attempts($userid, $reader, $allreaders = false, $booklist = false) {
    global $CFG, $COURSE, $bookpersentmaxgrade,$DB, $USER;
    
    $returndata = array();
    $totable    = array();
    $readersql  = "";
    if ($booklist) $reader->ignordate = 0;
    if (!$allreaders) $readersql = " and ra.reader= :readerid ";
    
    $studentattempts_p = $DB->get_records_sql("SELECT ra.timefinish,ra.userid,ra.attempt,ra.persent,ra.id,ra.quizid,ra.sumgrades,ra.passed,ra.checkbox,ra.preview,rp.name,rp.publisher,rp.level,rp.length,rp.image,rp.difficulty,rp.words,rp.sametitle,rp.id as rpid FROM {reader_attempts} ra LEFT JOIN {reader_publisher} rp ON rp.id = ra.quizid WHERE ra.preview != 1 and ra.userid= :userid and ra.timefinish > :readerignordate {$readersql} ORDER BY ra.timefinish", array("readerid"=>$reader->id, "userid"=>$userid, "readerignordate"=>$reader->ignordate));

    $studentattempts_n = $DB->get_records_sql("SELECT ra.timefinish,ra.userid,ra.attempt,ra.persent,ra.id,ra.quizid,ra.sumgrades,ra.passed,ra.checkbox,ra.preview,rp.name,rp.publisher,rp.level,rp.length,rp.image,rp.difficulty,rp.words,rp.sametitle,rp.id as rpid FROM {reader_attempts} ra LEFT JOIN {reader_noquiz}    rp ON rp.id = ra.quizid WHERE ra.preview = 1 and ra.userid= :userid and ra.timefinish > :readerignordate {$readersql} ORDER BY ra.timefinish", array("readerid"=>$reader->id, "userid"=>$userid, "readerignordate"=>$reader->ignordate));


    if (is_array($studentattempts_n ) && is_array($studentattempts_p)) {
        $studentattempts = array_merge($studentattempts_p,$studentattempts_n);
    } elseif ($studentattempts_n) {
        $studentattempts = $studentattempts_n;
    } else { 
        $studentattempts = $studentattempts_p;
    }
    
    if (!$studentlevel = $DB->get_record("reader_levels", array( "userid" => $USER->id,  "readerid" => $reader->id))) {
        $createlevel = new stdClass;
        $createlevel->userid = $USER->id;
        $createlevel->startlevel = 0;
        $createlevel->currentlevel = 0;
        $createlevel->readerid = $reader->id;
        $createlevel->promotionstop = $reader->promotionstop;
        $createlevel->time = time();
        $DB->insert_record('reader_levels', $createlevel);
        $studentlevel = $DB->get_record("reader_levels", array( "userid" => $USER->id,  "readerid" => $reader->id));
    }
    
    if (is_array($studentattempts)) {
        $totable['correct']       = 0;
        $totable['incorrect']     = 0;
        $totable['totalpoints']   = 0;
        $totable['countattempts'] = 0;
        
        foreach ($studentattempts as $studentattempt) {
            $totable['countattempts']++;
            if ($studentattempt->passed == "true" || $studentattempt->passed == "TRUE") {
                $statustext = "Passed";
                $status     = "correct";
                $totable['points'] = reader_get_reader_length($reader, $studentattempt->rpid);
                $totable['correct']++;
            } else {
                if($studentattempt->passed == "cheated") {
                    $statustext = "<font color='red'>".get_string('cheated','reader')."</font>";
                } else {
                    $statustext = "Not Passed";
                }
                $status = "incorrect";
                $totable['points'] = 0;
                $totable['incorrect']++;
            }
            
            $totable['totalpoints'] += round($totable['points'], 2);
            
            if (!isset($bookpersentmaxgrade[$studentattempt->quizid])) {
                $totalgrade = 0;
                $answersgrade = $DB->get_records ("reader_question_instances", array("quiz" => $studentattempt->quizid)); 
                foreach ($answersgrade as $answersgrade_) {
                    $totalgrade += $answersgrade_->grade;
                }
                $totable['bookpersent']  = $studentattempt->persent."%";
                $totable['bookmaxgrade'] = $totalgrade * reader_get_reader_length($reader, $studentattempt->rpid);
                $bookpersentmaxgrade[$studentattempt->quizid] = array($totable['bookpersent'], $totable['bookmaxgrade']);
            } else {
                list($totable['bookpersent'], $totable['bookmaxgrade']) = $bookpersentmaxgrade[$studentattempt->quizid];
            }
            
            if ($studentattempt->preview == 1) $statustext = "Credit";
            
            $returndata[$studentattempt->id] = array("id" => $studentattempt->id, 
                                                     "quizid" => $studentattempt->quizid, 
                                                     "timefinish" => $studentattempt->timefinish, 
                                                     "booktitle" => $studentattempt->name, 
                                                     "image" => $studentattempt->image, 
                                                     "words" => $studentattempt->words, 
                                                     "booklength" => reader_get_reader_length($reader, $studentattempt->rpid), 
                                                     "booklevel" => $studentattempt->level, 
                                                     "bookdiff" => reader_get_reader_difficulty($reader, $studentattempt->rpid), 
                                                     "persent" => $studentattempt->persent, 
                                                     "passed" => $studentattempt->passed, 
                                                     "checkbox" => $studentattempt->checkbox, 
                                                     "sametitle" => $studentattempt->sametitle, 
                                                     "userlevel" => $studentlevel->currentlevel, 
                                                     "status" => $status, 
                                                     "statustext" => $statustext, 
                                                     "bookpoints" => $totable['points'], 
                                                     "bookpersent" => $totable['bookpersent'], 
                                                     "bookmaxgrade" => $totable['bookmaxgrade'], 
                                                     "totalpoints" => $totable['totalpoints'], 
                                                     "startlevel" => $studentlevel->startlevel,
                                                     "currentlevel" => $studentlevel->currentlevel);
        }
        
        $totable['startlevel']   = $studentlevel->startlevel;
        $totable['currentlevel'] = $studentlevel->currentlevel;
        
        return array($returndata, $totable);
    } else {
        return false;
    }
}


function reader_print_group_select_box($courseid, $link) {
    global $CFG, $COURSE, $grid;
        
    $o  = "";
        
    if ($groups = groups_get_all_groups ($courseid)) {
        $o .= html_writer::start_tag('div', array('class'=>'fr'));
        $o .= html_writer::start_tag('form', array('action'=>'', 'method'=>'post', 'id'=>'id_reader_select_group_form'));
        $o .= html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'link', 'value'=>$link, 'id'=>'id_reader_select_group_url'));
        $o .= html_writer::start_tag('select', array('name'=>'group_select', 'id'=>'id_reader_select_group_box'));
        $o .= html_writer::tag('option', get_string('allgroups', 'reader'), array('value'=>-1));
        foreach ($groups as $groupkey => $groupvalue) {
            if ($groupkey == $grid)
                $o .= html_writer::tag('option', $groupvalue->name, array('value'=>$groupkey, 'selected'=>'selected'));
            else
                $o .= html_writer::tag('option', $groupvalue->name, array('value'=>$groupkey));
        }
        $o .= html_writer::end_tag('select');
        $o .= html_writer::end_tag('form');
        $o .= html_writer::end_tag('div');
        $o .= html_writer::tag('div', '', array('class'=>'clear'));
    }
    
    echo $o;
}


function reader_print_group_select_box_sub($courseid) {
    global $CFG, $COURSE, $grid;
        
    $o  = "";
        
    if ($groups = groups_get_all_groups ($courseid)) {
        $o .= html_writer::start_tag('div', array('class'=>'fr'));
        $o .= html_writer::start_tag('select', array('name'=>'grid'));
        $o .= html_writer::tag('option', get_string('allgroups', 'reader'), array('value'=>-1));
        foreach ($groups as $groupkey => $groupvalue) {
            if ($groupkey == $grid)
                $o .= html_writer::tag('option', $groupvalue->name, array('value'=>$groupkey, 'selected'=>'selected'));
            else
                $o .= html_writer::tag('option', $groupvalue->name, array('value'=>$groupkey));
        }
        $o .= html_writer::end_tag('select');
        $o .= html_writer::end_tag('div');
        //$o .= html_writer::tag('div', '', array('class'=>'clear'));
    }
    
    echo $o;
}


function reader_get_pages($table, $page, $perpage) {
    global $CFG, $COURSE;
    
    $viewtable  = array();
    $totalcount = count ($table);
    $startrec   = $page * $perpage;
    $finishrec  = $startrec + $perpage;
    
    foreach ((array)$table as $key => $value) {
        if ($key >= $startrec && $key < $finishrec) {
            $viewtable[] = $value;
        }
    }
    
    return array($totalcount, $viewtable, $startrec, $finishrec, $page);
}


function reader_user_link_t($userdata) {
    global $CFG, $COURSE;

    if (!isset($userdata->userid)) 
        $userdata->userid = $userdata->id;
    
    $link = new moodle_url("/user/view.php", array("id" => $userdata->userid, "course" => $COURSE->id));

    return array(html_writer::link($link, $userdata->username), $userdata->username);
}


function reader_fullname_link_viewasstudent($userdata, $link) {
    global $CFG, $COURSE, $id,$act;

    if (!isset($userdata->userid)) 
        $userdata->userid = $userdata->id;
        
    $linkurl = new moodle_url("/mod/reader/admin.php?".$link, array('id'=>$id, 'act'=>$act, 'viewasstudent'=>$userdata->id));

    return array(html_writer::link($linkurl, $userdata->firstname.' '.$userdata->lastname), $userdata->firstname.' '.$userdata->lastname);
}


function reader_fullname_link_t($userdata) {
    global $CFG, $COURSE;
    
    if (!$userdata->userid) $userdata->userid = $userdata->id;
    
    $link = new moodle_url("/user/view.php", array("id" => $userdata->userid, "course" => $COURSE->id));
    
    return array(html_writer::link($link, $userdata->firstname.' '.$userdata->lastname), $userdata->firstname.' '.$userdata->lastname);
}


function reader_select_perpage($id, $act, $sort, $orderby, $grid) {
    global $CFG, $COURSE, $_SESSION, $book;
    
    $pages = array(50, 100, 200, 500);
    
    if (!empty($book))
        $link = new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act, 'sort'=>$sort, 'orderby'=>$orderby, 'book'=>$book, 'grid'=>$grid));
    else
        $link = new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act, 'sort'=>$sort, 'orderby'=>$orderby, 'grid'=>$grid));
    
    $o  = '';
    $o .= html_writer::start_tag('div', array('class'=>'fr'));
    $o .= html_writer::start_tag('form', array('action'=>'', 'method'=>'get', 'id'=>'id_reader_choose_perpage_form', 'class'=>'popupform'));
    $o .= html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'link', 'value'=>$link, 'id'=>'id_reader_choose_perpage_url'));
    $o .= 'Perpage ';
    $o .= html_writer::start_tag('select', array('id'=>'id_reader_choose_perpage_box', 'name'=>'perpage'));
    
    foreach ($pages as $page) {
        if ($_SESSION['SESSION']->reader_perpage == $page)
            $o .= html_writer::tag('option', $page, array('value'=>$page, 'selected'=>'selected'));
        else
            $o .= html_writer::tag('option', $page, array('value'=>$page));
    }
    
    $o .= html_writer::end_tag('select');
    $o .= html_writer::end_tag('form');
    $o .= html_writer::end_tag('div');
    $o .= html_writer::tag('div', '', array('class'=>'clear'));

    echo $o;
}


function reader_select_perpage_sub($id, $act, $sort, $orderby, $grid) {
    global $CFG, $COURSE, $_SESSION;
    
    $pages = array(50, 100, 200, 500);
    
    $o  = '';
    $o .= html_writer::start_tag('div', array('class'=>'fr'));
    $o .= 'Perpage ';
    $o .= html_writer::start_tag('select', array('name'=>'perpage'));
    
    foreach ($pages as $page) {
        if ($_SESSION['SESSION']->reader_perpage == $page)
            $o .= html_writer::tag('option', $page, array('value'=>$page, 'selected'=>'selected'));
        else
            $o .= html_writer::tag('option', $page, array('value'=>$page));
    }
    
    $o .= html_writer::end_tag('select');
    
    $o .= html_writer::end_tag('div');
    $o .= html_writer::tag('div', '', array('class'=>'clear'));

    echo $o;
}


function reader_print_search_form ($id, $act) {
    global $CFG, $COURSE, $_SESSION, $searchtext, $book, $OUTPUT;
    
    $searchtext = str_replace('\"', '"', $searchtext);
    
    if ($book) {
        $link = new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act, 'book'=>$book));
    } else {
        $link = new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>$act));
    }
    
    $o  = '';
    $o .= html_writer::start_tag('table', array('style'=>'width:100%'));
    $o .= html_writer::start_tag('tr');
    $o .= html_writer::start_tag('td', array('align'=>'right'));
    $o .= html_writer::start_tag('form', array('action'=>$link, 'method'=>'post', 'id'=>'mform1'));
    $o .= html_writer::empty_tag('input', array('type'=>'text', 'name'=>'searchtext', 'value'=>$searchtext, 'style'=>'width:120px;'));
    $o .= html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'submit', 'value'=>get_string('search', 'reader')));
    $o .= html_writer::end_tag('form');

    $options            = array();
    $options["act"]     = $act;
    $options["id"]      = $id;
    if (!empty($searchtext)) {
        $o .= $OUTPUT->render(new single_button(new moodle_url('/mod/reader/admin.php', $options), get_string("showall", "reader"))); 
    }
    $o .= html_writer::end_tag('td');
    $o .= html_writer::end_tag('tr');
    $o .= html_writer::end_tag('table');
    
    echo $o;
}


function reader_print_search_form_sub ($id, $act) {
    global $CFG, $COURSE, $_SESSION, $searchtext, $book, $OUTPUT;
    
    $searchtext = str_replace('\"', '"', $searchtext);
    
    $o  = '';
    $o .= html_writer::start_tag('table', array('style'=>'width:100%'));
    $o .= html_writer::start_tag('tr');
    $o .= html_writer::start_tag('td', array('align'=>'right'));
    $o .= html_writer::start_tag('div', array('class'=>'fr'));
    $o .= get_string('search', 'reader');
    $o .= html_writer::end_tag('div');
    $o .= html_writer::empty_tag('input', array('type'=>'text', 'name'=>'searchtext', 'value'=>$searchtext, 'style'=>'width:120px;'));

    $options            = array();
    $options["act"]     = $act;
    $options["id"]      = $id;
    if (!empty($searchtext)) {
        $o .= $OUTPUT->render(new single_button(new moodle_url('/mod/reader/admin.php', $options), get_string("showall", "reader"))); 
    }
    $o .= html_writer::end_tag('td');
    $o .= html_writer::end_tag('tr');
    $o .= html_writer::end_tag('table');
    
    echo $o;
}


function reader_select_term(){
    global $id, $act, $sort, $grid, $orderby, $ct;
    
    $link   = new moodle_url("/mod/reader/admin.php", array("id" => $id, "act" => $act, "a" => "admin", "sort" => $sort, "grid" => $grid, "orderby" => $orderby));
        
    $o  = "";
    $o .= html_writer::start_tag('div', array('class'=>'tal-r'));
    $o .= html_writer::start_tag('form', array('action'=>'', 'method'=>'post', 'id'=>'id_reader_select_term_form'));
    $o .= html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'link', 'value'=>$link, 'id'=>'id_reader_select_term_url'));
    $o .= html_writer::start_tag('select', array('id'=>'id_reader_select_term_box', 'name'=>'perpage'));
    $o .= html_writer::tag('option', 'All terms', array('value'=>-1));
    
    if (!empty($ct))
        $o .= html_writer::tag('option', 'Current term', array('value'=>1, 'selected'=>'selected'));
    else
        $o .= html_writer::tag('option', 'Current term', array('value'=>1));
        
    $o .= html_writer::end_tag('select');
    $o .= html_writer::end_tag('form');
    $o .= html_writer::end_tag('div');
        
    echo $o;
}


function reader_select_term_sub(){
    global $id, $act, $sort, $grid, $orderby, $ct;
    
    $link   = new moodle_url("/mod/reader/admin.php", array("id" => $id, "act" => $act, "a" => "admin", "sort" => $sort, "grid" => $grid, "orderby" => $orderby));
        
    $o  = "";
    $o .= html_writer::start_tag('div', array('class'=>'fr'));
    $o .= html_writer::start_tag('select', array('name'=>'ct'));
    $o .= html_writer::tag('option', 'All terms', array('value'=>-1));
    
    if (!empty($ct))
        $o .= html_writer::tag('option', 'Current term', array('value'=>1, 'selected'=>'selected'));
    else
        $o .= html_writer::tag('option', 'Current term', array('value'=>1));
        
    $o .= html_writer::end_tag('select');
    $o .= html_writer::end_tag('div');
        
    echo $o;
}


function reader_excel_download_btn(){
    global $options, $OUTPUT;
    
    $o  = "";
    $o .= html_writer::start_tag('div', array('class'=>'tal-r'));
    $o .= $OUTPUT->single_button(new moodle_url("/mod/reader/admin.php",$options), get_string("downloadexcel", "reader"), 'post', $options);
    $o .= html_writer::end_tag('div');
        
    echo $o;
}


function reader_check_search_text ($searchtext, $coursestudent, $book = false) {
    global $CFG, $COURSE, $_SESSION;
    
    $book_ = array();

    if ($searchtext) {
        if (strstr($searchtext, '"')) {
            $searchtext = str_replace('\"', '"', $searchtext);
            $searchtext = explode('"', $searchtext);
        } else {
            $searchtext = explode(" ", $searchtext);
        }
        foreach ($searchtext as $searchtext_) {
            if ($searchtext_) {
                $searchtext_ = strtolower($searchtext_);
                if ($coursestudent) {
                    $coursestudent->username  = strtolower($coursestudent->username);
                    $coursestudent->firstname = strtolower($coursestudent->firstname);
                    $coursestudent->lastname  = strtolower($coursestudent->lastname);
                    
                    
                    if (strstr($coursestudent->username, $searchtext_) || strstr($coursestudent->firstname." ".$coursestudent->lastname, $searchtext_)) {
                        return true;
                    }
                }
              
                if ($book) {
                    if (is_array($book)) {
                        $book_['booktitle'] = strtolower($book['booktitle']);
                        $book_['level']     = strtolower($book['level']);
                        $book_['publisher'] = strtolower($book['publisher']);
                    } else {
                        $book_['booktitle'] = strtolower($book->name);
                        $book_['level'] = strtolower($book->level);
                        $book_['publisher'] = strtolower($book->publisher);
                    }

                    if (strstr($book_['booktitle'], $searchtext_) || strstr($book_['level'], $searchtext_) || strstr($book_['publisher'], $searchtext_)) {
                        return true;
                    }
                }
            }
        }
    } else {
        return true;
    }
}


function reader_check_search_text_quiz ($searchtext, $book) {
    global $CFG, $COURSE, $_SESSION;

    $book_ = array();

    if (!empty($searchtext)) {
        if (strstr($searchtext, '"')) {
            $searchtext = str_replace('\"', '"', $searchtext);
            $searchtext = explode('"', $searchtext);
        } else {
            $searchtext = explode(" ", $searchtext);
        }
        foreach ($searchtext as $searchtext_) {
            if ($searchtext_) {
                $searchtext_ = strtolower($searchtext_);
                
                if ($book) {
                    if (is_array($book)) {
                        $book_['booktitle'] = strtolower($book['booktitle']);
                        $book_['level']     = strtolower($book['level']);
                        $book_['publisher'] = strtolower($book['publisher']);
                    } else {
                        $book_['booktitle'] = strtolower($book->name);
                        $book_['level'] = strtolower($book->level);
                        $book_['publisher'] = strtolower($book->publisher);
                    }

                    if (strstr($book_['booktitle'], $searchtext_) || strstr($book_['level'], $searchtext_) || strstr($book_['publisher'], $searchtext_)) {
                        return true;
                    }
                }
            }
        }
    } else {
        return true;
    }
}


function reader_selectlevel_box ($userid, $leveldata, $level) {
    global $id;

    $link   = 'id='.$id.'&userid='.$userid.'&f='.$level;
    $levels = array(0,1,2,3,4,5,6,7,8,9,10,12,13,14);
    
    $o  = '';
    $o .= html_writer::start_tag('div', array('class'=>'changelevels'));
    $o .= html_writer::start_tag('select', array('class'=>'class_reader_changelevels_box', 'name'=>'changelevel'));
    
    foreach ($levels as $levels_) {
        if ($levels_ == $leveldata->$level) {
            $o .= html_writer::tag('option', $levels_, array('value'=>$link.'&'.$level.'='.$levels_, 'selected'=>'selected'));
        } else {
            $o .= html_writer::tag('option', $levels_, array('value'=>$link.'&'.$level.'='.$levels_));
        }
    }
    
    $o .= html_writer::end_tag('select');
    $o .= html_writer::end_tag('div');
    
    return $o;
}


function reader_promotion_stop_box ($userid, $data) {
    global $id;

    $link   = 'id='.$id.'&userid='.$userid;
    $levels = array(0,1,2,3,4,5,6,7,8,9,10,12,99);
    
    $o  = '';
    $o .= html_writer::start_tag('div', array('class'=>'changepromote'));
    $o .= html_writer::start_tag('select', array('class'=>'class_reader_promotion_stop_box', 'name'=>'promote'));
    
    foreach ($levels as $levels_) {
        if ($levels_ == $data->promotionstop) {
            $o .= html_writer::tag('option', $levels_, array('value'=>$link.'&promotionstop='.$levels_, 'selected'=>'selected'));
        } else {
            $o .= html_writer::tag('option', $levels_, array('value'=>$link.'&promotionstop='.$levels_));
        }
    }
    
    $o .= html_writer::end_tag('select');
    $o .= html_writer::end_tag('div');
    
    return $o;
}


function reader_goal_box ($userid, $dataoflevel, $reader) {
    global $DB, $id;
    
    if (!empty($dataoflevel->goal)) {
        $goal = $dataoflevel->goal;
    }

    if (empty($goal)) {
        $data = $DB->get_records("reader_goal", array("readerid" => $reader->id));
        foreach ($data as $data_) {
            if (!empty($data_->groupid)) {
                if (!groups_is_member($data_->groupid, $userid)) {
                    $noneed = true;
                }
            }
            if (!empty($data_->level)) {
                if ($dataoflevel->currentlevel != $data_->level) {
                    $noneed = true;
                }
            }
            if (!$noneed) $goal = $data_->goal;
        }
    }
    if (empty($goal) && !empty($reader->goal)) {
        $goal = $reader->goal;
    }
    
    $levels2 = array();

    if (empty($reader->wordsorpoints) || $reader->wordsorpoints == "words") {
        $levels = array(0,5000,6000,7000,8000,9000,10000,12500,15000,20000,25000,30000,35000,40000,45000,50000,60000,70000,80000,90000,100000,125000,150000,175000,200000,250000,300000,350000,400000,450000,500000);
        if (!in_array($goal, $levels) && !empty($goal)) {
            for ($i=0; $i<count($levels); $i++) {
                if ($goal < $levels[$i+1] && $goal > $levels[$i]) {
                    $levels2[] = $goal;
                    $levels2[] = $levels[$i];
                } else {
                    $levels2[] = $levels[$i];
                }
            }
            $levels = $levels2;
        }
    } else {
        $levels = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15);
    }

    $link   = 'id='.$id.'&userid='.$userid;
    
    $o  = '';
    $o .= html_writer::start_tag('div', array('class'=>'changegoal'));
    $o .= html_writer::start_tag('select', array('class'=>'class_reader_change_goal_box', 'name'=>'goal'));
    
    foreach ($levels as $levels_) {
        if ($levels_ == $dataoflevel->goal || $levels_ == $goal) {
            $o .= html_writer::tag('option', $levels_, array('value'=>$link.'&setgoal='.$levels_, 'selected'=>'selected'));
        } else {
            $o .= html_writer::tag('option', $levels_, array('value'=>$link.'&setgoal='.$levels_));
        }
    }
    
    $o .= html_writer::end_tag('select');
    $o .= html_writer::end_tag('div');
    
    
    return $o;
}


function reader_yes_no_box ($userid, $data) {
    global $id;
    
    $levels    = array();
    $levels[0] = "Promo";
    $levels[1] = "NoPromo";
    
    
    $link   = 'id='.$id.'&userid='.$userid;
    
    $o  = '';
    $o .= html_writer::start_tag('div', array('class'=>'nopromotebox'));
    $o .= html_writer::start_tag('select', array('class'=>'class_reader_nopromote_box', 'name'=>'goal'));
    
    foreach ($levels as $key => $levels_) {
        if ($key == $data->nopromote) {
            $o .= html_writer::tag('option', $levels_, array('value'=>$link.'&nopromote='.$key, 'selected'=>'selected'));
        } else {
            $o .= html_writer::tag('option', $levels_, array('value'=>$link.'&nopromote='.$key));
        }
    }
    
    $o .= html_writer::end_tag('select');
    $o .= html_writer::end_tag('div');

    return $o;
}


function reader_selectip_form ($userid, $reader) {
    global $CFG, $COURSE, $_SESSION, $id, $act, $grid, $sort, $orderby, $page,$DB;
    
    $levels = array(0=>get_string('no', 'reader'),1=>get_string('yes', 'reader'));
    
    $data = $DB->get_record("reader_strict_users_list", array( "readerid" => $reader->id,  "userid" => $userid));
    
    $patch = $userid."_ip_".$reader->id;
    
    $string = '<div id="selectip'.$patch.'">';
    $string .= '<select id="choose_ips'.$patch.'" name="ips'.$patch.'" onchange="request(\'admin.php?ajax=true&\' + this.options[this.selectedIndex].value,\'selectip'.$patch.'\'); return false;">';
    
    foreach ($levels as $key => $value) {
        $string .= '<option value="admin.php?a=admin&id='.$id.'&act='.$act.'&setip=1&userid='.$userid.'&needip='.$key.'" ';
        if ($key == $data->needtocheckip) {
            $string .= ' selected="selected" ';
        }
        $string .= '>'.$value.'</option>';
    }
    
    $string .= '</select></div>';
    
    return $string;
}


function reader_select_difficulty_box ($difficulty, $bookid) {
    global $id;
    
    $levels = array(0,1,2,3,4,5,6,7,8,9,10,12,13,14);
    
    $link   = 'id='.$id.'&bookid='.$bookid;
    
    $o  = '';
    $o .= html_writer::start_tag('div', array('class'=>'change_difficulty'));
    $o .= html_writer::start_tag('select', array('class'=>'class_reader_change_difficulty_box', 'name'=>'difficulty'));
    
    foreach ($levels as $levels_) {
        if ($levels_ == $difficulty) {
            $o .= html_writer::tag('option', $levels_, array('value'=>$link.'&difficulty='.$levels_, 'selected'=>'selected'));
        } else {
            $o .= html_writer::tag('option', $levels_, array('value'=>$link.'&difficulty='.$levels_));
        }
    }
    
    $o .= html_writer::end_tag('select');
    $o .= html_writer::end_tag('div');
    
    return $o;
}


function reader_select_length_box ($length, $bookid) {
    global $id;

    $levels = array(0.50,0.60,0.70,0.80,0.90,1.00,1.10,1.20,1.30,1.40,1.50,1.60,1.70,1.80,1.90,2.00,3.00,4.00,5.00,6.00,7.00,8.00,9.00,10.00,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100,110,120,130,140,150,160,170,175,180,190,200,225,250,275,300,350,400);
    
    $link   = 'id='.$id.'&bookid='.$bookid;
    
    $o  = '';
    $o .= html_writer::start_tag('div', array('class'=>'change_length'));
    $o .= html_writer::start_tag('select', array('class'=>'class_reader_change_length_box', 'name'=>'length'));
    
    foreach ($levels as $levels_) {
        if ($levels_ == $length) {
            $o .= html_writer::tag('option', $levels_, array('value'=>$link.'&length='.$levels_, 'selected'=>'selected'));
        } else {
            $o .= html_writer::tag('option', $levels_, array('value'=>$link.'&length='.$levels_));
        }
    }
    
    $o .= html_writer::end_tag('select');
    $o .= html_writer::end_tag('div');
    
    return $o;
}


function reader_change_publishertitle_text($bookid, $publisher){
    global $id;
    
    $link   = 'id='.$id.'&bookid='.$bookid;
    
    $o  = '';
    $o .= html_writer::start_tag('div');
    $o .= html_writer::empty_tag('input', array('type'=>'text', 'class'=>'class_reader_change_publishertitle_text', 'name'=>'publishertitle', 'value'=>$publisher, 'data-url'=>$link));
    $o .= html_writer::end_tag('div');
    
    return $o;
}


function reader_change_level_text($bookid, $level){
    global $id;
    
    $link   = 'id='.$id.'&bookid='.$bookid;
    
    $o  = '';
    $o .= html_writer::start_tag('div');
    $o .= html_writer::empty_tag('input', array('type'=>'text', 'class'=>'class_reader_change_level_text', 'name'=>'level', 'value'=>$level, 'data-url'=>$link));
    $o .= html_writer::end_tag('div');
    
    return $o;
}


function reader_change_words_text($bookid, $words){
    global $id;
    
    $link   = 'id='.$id.'&bookid='.$bookid;
    
    $o  = '';
    $o .= html_writer::start_tag('div');
    $o .= html_writer::empty_tag('input', array('type'=>'text', 'class'=>'class_reader_change_words_text', 'name'=>'words', 'value'=>$words, 'data-url'=>$link));
    $o .= html_writer::end_tag('div');
    
    return $o;
}


function reader_set_attempt_result ($attemptid, $reader) {
    global $CFG, $COURSE, $USER,$DB;
    
    $attemptdata = $DB->get_record("reader_attempts", array( "id" => $attemptid));
    
    if (!$attemptdata->persent) {
        $bookdata = $DB->get_record("reader_publisher", array( "id" => $attemptdata->quizid));
        $totalgrade = 0;
        $answersgrade = $DB->get_records ("reader_question_instances", array("quiz" => $attemptdata->quizid)); // Count Grades (TotalGrade)
        foreach ($answersgrade as $answersgrade_) {
            $totalgrade += $answersgrade_->grade;
        }

        $persent = round(($attemptdata->sumgrades/$totalgrade) * 100, 0);

        if ($persent >= $reader->percentforreading) {
            $passed = "true";
            $passedlog = "Passed";
        } else {
            $passed = "false";
            $passedlog = "Failed";
        }
        
        if (!$DB->get_record("log", array( "userid" => $USER->id,  "course" => $COURSE->id,  "info" => "readerID {$reader->id}; reader quiz {$bookdata->id}; {$persent}/{$passedlog}"))) {
            add_to_log($COURSE->id, "reader", "view attempt: ".addslashes($bookdata->name), "view.php?id={$attemptid}", "readerID {$reader->id}; reader quiz {$bookdata->id}; {$persent}/{$passedlog}");
        }
        
        $DB->set_field("reader_attempts",  "persent",  $persent, array( "id" => $attemptid));
        $DB->set_field("reader_attempts",  "passed",  $passed, array( "id" => $attemptid));
    }
}


function reader_makexml($xmlarray) {
    $xml = "";
    foreach ($xmlarray as $xmlarray_) {
        $xml .= $xmlarray_;
    }
    return $xml;
}


function reader_file($url, $post = false) {
    $postdata = "";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if ($post) {
        curl_setopt($ch, CURLOPT_POST, 1);
        
        foreach ($post as $key => $value) {
            if (!is_array($value)) {
                $postdata .= $key.'='.$value.'&';
            } else {
                foreach ($value as $key2 => $value2) {
                    if (!is_array($value2)) {
                        $postdata .= $key.'['.$key2.']='.$value2.'&';
                    } else {
                        foreach ($value2 as $key3 => $value3) {
                            $postdata .= $key.'['.$key2.']['.$key3.']='.$value3.'&';
                        }
                    }
                }
            }
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    }
    $result = curl_exec($ch);
    curl_close($ch);
    
    return $result;
}


function reader_removedirrec($dir) {
    if ($objs = glob($dir."/*")) {
        foreach($objs as $obj) {
            is_dir($obj) ? reader_removedirrec($obj) : @unlink($obj);
        }
    }
    @rmdir($dir);
}


function reader_curlfile($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
   
    if (!empty($result)) {
        return explode("\n", $result);
    } else {
        return false;
    }
}


function reader_debug_speed_check($name) {
    global $CFG, $USER, $debugandspeedforadminreport, $dbstimebegin, $dbstimelast;
    
    if(!$dbstimebegin) {
        list($msec,$sec)=explode(chr(32),microtime());
        $dbstimebegin = $sec+$msec;
    } else {
        list($msec,$sec)=explode(chr(32),microtime());
        $dbstimenow = $sec+$msec;
        $debugandspeedforadminreport .= $name . " " . round($dbstimenow - $dbstimebegin,4) . "sec ";
        if ($dbstimelast) {
            $debugandspeedforadminreport .= " (".round($dbstimenow - $dbstimelast,4).") <br />";
        } else {
            $debugandspeedforadminreport .= "<br />";
        }
        $dbstimelast = $dbstimenow;
    }
}


function reader_order_object ($array, $key) {
    $tmp = array();
    foreach($array as $akey => $array2) {
        $tmp[$akey] = $array2->$key;
    }
    sort($tmp, SORT_NUMERIC);
    $tmp2 = array();
    $tmp_size = count($tmp);
    foreach($tmp as $key => $value) {
        $tmp2[$key] = $array[$key];
    }
    return $tmp2;
}


function reader_get_goal_progress($progress, $reader) {
    global $CFG, $USER,$DB;
    
    if (!$progress) $progress = 0;
    
    if (!$dataofuserlevels = $DB->get_record("reader_levels", array( "userid" => $USER->id,  "readerid" => $reader->id))) {
        $createlevel = new stdClass;
        $createlevel->userid = $USER->id;
        $createlevel->startlevel = 0;
        $createlevel->currentlevel = 0;
        $createlevel->readerid = $reader->id;
        $createlevel->promotionstop = $reader->promotionstop;
        $createlevel->time = time();
        $DB->insert_record('reader_levels', $createlevel);
        $dataofuserlevels = $DB->get_record("reader_levels", array( "userid" => $USER->id,  "readerid" => $reader->id));
    }
    if (!empty($dataofuserlevels->goal)) {
        $goal = $dataofuserlevels->goal;
    }
    if (empty($goal)) {
        $data = $DB->get_records("reader_goal", array("readerid" => $reader->id));
        foreach ($data as $data_) {
            if (!empty($data_->groupid)) {
                if (!groups_is_member($data_->groupid, $USER->id)) {
                    $noneed = true;
                }
            }
            if (!empty($data_->level)) {
                if ($dataofuserlevels->currentlevel != $data_->level) {
                    $noneed = true;
                }
            }
            if (!$noneed) $goal = $data_->goal;
        }
    }
    
    if (empty($goal) || !empty($reader->goal)) {
        $goal = $reader->goal;
    }
    
    $goalchecker = $goal;
    if ($progress > $goal) $goalchecker = $progress;
    if ($goalchecker <= 50000) {
        $img = 5;
        $bgcolor = "#00FFFF";
    } else if ($goalchecker <= 100000) {
        $img = 10;
        $bgcolor = "#FF00FF";
    } else if ($goalchecker <= 500000) {
        $img = 50;
        $bgcolor = "#FFFF00";
    } else {
        $img = 100;
        $bgcolor = "#0000FF";
    }
    if ($goal > 1000000) $goal = 1000000;
    if ($progress > 1000000) $progress = 1000000;
    $currentpositiongoal = $goal / ($img * 10000);
    $currentpositiongoalpix = round($currentpositiongoal * 800);
    if ($currentpositiongoalpix > 800) $currentpositiongoalpix = 800;
    
    $currentposition = $progress / ($img * 10000);
    $currentpositionpix = round($currentposition * 800);
    if ($currentpositionpix > 800) $currentpositionpix = 800;
    $currentpositionpix += 8;
    
    $colorscalelink     = new moodle_url("/mod/reader/img/colorscale800px{$img}.png");
    $colorscalegslink   = new moodle_url("/mod/reader/img/colorscale800px{$img}gs.png");
    $colorscalenowlink  = new moodle_url("/mod/reader/img/now.png");
    $colorscalegoallink = new moodle_url("/mod/reader/img/goal.png");
    
    $returntext = '<style  type="text/css" >
<!--
#ScoreBoxDiv
{
position:absolute;
left:5px; top:34px; 
width:824px; 
height:63px; 
background-color: '.$bgcolor.' ;
z-index:5;
}
img.color
{
position:absolute;
top:40px;
left:10px;
z-index:20;
clip: rect(0px '.$currentpositionpix.'px 100px 0px);
}
img.mark
{
position:absolute;
top:47px;
left:'.($currentpositionpix+10).'px;
z-index:20;
}
img.grey 
{
position:absolute;
top:40px;
left:10px;
z-index:15;
}
img.goal
{
position:absolute;
top:26px;
left:'.$currentpositiongoalpix.'px;
z-index:40;
}
-->

</style>
<div id="ScoreBoxDiv" class="ScoreBoxDiv"> &nbsp;&nbsp;&nbsp;&nbsp;</div>
<img class="color" src="'.$colorscalelink.'">
<img class="grey" src="'.$colorscalegslink.'">
<img class="mark" src="'.$colorscalenowlink.'">
';
    
    if (!empty($goal)) {
        $returntext .= '<img class="goal" src="'.$colorscalegoallink.'">';
    }
    
    return $returntext;
}


function reader_get_reader_difficulty($reader, $bookid) {
    global $DB;
    
    if ($reader->individualbooks == 1) {
        if (!$data = $DB->get_record("reader_individual_books", array( "readerid" => $reader->id,  "bookid" => $bookid))) {
            $data = $DB->get_record("reader_publisher", array( "id" => $bookid));
            return $data->difficulty;
        } else {
            return $data->difficulty;
        }
    } else {
        $data = $DB->get_record("reader_publisher", array( "id" => $bookid));
        return $data->difficulty;
    }
}


function reader_get_reader_length($reader, $bookid) {
    global $DB;
    
    if ($reader->individualbooks == 1) {
        $data = $DB->get_record("reader_individual_books", array( "readerid" => $reader->id,  "bookid" => $bookid));
        if (!empty($data->length)) {
            return $data->length;
        } else {
            $data = $DB->get_record("reader_publisher", array( "id" => $bookid));
            return $data->length;
        }
    } else {
        $data = $DB->get_record("reader_publisher", array( "id" => $bookid));
        return $data->length;
    }
}


function reader_ra_checkbox ($data) {
    global $act, $id, $CFG, $USER, $excel;
    
    $checked = '';
    
    if ($excel) {
        if ($data['checkbox'] == 1) {
            return get_string('yes', 'reader');
        } else {
            return get_string('no', 'reader');
        }
    }
    
    if ($data['checkbox'] == 1) $checked = 'checked';
    
    return '<input type="checkbox" name="checkattempt" value="1" '.$checked.' onclick="if(this.checked) { request(\'admin.php?ajax=true&id='.$id.'&act='.$act.'&checkattempt='.$data['id'].'&checkattemptvalue=1\',\'atcheck_'.$data['id'].'\'); } else { request(\'admin.php?ajax=true&id='.$id.'&act='.$act.'&checkattempt='.$data['id'].'&checkattemptvalue=0\',\'atcheck_'.$data['id'].'\'); }" ><div id="atcheck_'.$data['id'].'"></div>';
}


function reader_groups_get_user_groups($userid=0) {
    global $CFG, $USER, $DB;

    if (empty($userid)) {
        $userid = $USER->id;
    }

    if (!$rs = $DB->get_recordset_sql("SELECT g.id, gg.groupingid
                                    FROM {groups} g
                                         JOIN {groups_members} gm        ON gm.groupid = g.id
                                         LEFT JOIN {groupings_groups} gg ON gg.groupid = g.id
                                   WHERE gm.userid = ?",array($userid))) {
        return array('0' => array());
    }

    $result    = array();
    $allgroups = array();
    
    foreach ($rs as $group) {
        $allgroups[$group->id] = $group->id;
        if (is_null($group->groupingid)) {
            continue;
        }
        if (!array_key_exists($group->groupingid, $result)) {
            $result[$group->groupingid] = array();
        }
        $result[$group->groupingid][$group->id] = $group->id;
    }
    $rs->close();

    $result['0'] = array_keys($allgroups); // all groups

    return $result;
} 


function reader_nicetime($unix_date){
    if(empty($unix_date)) {
        return "No date provided";
    }
   
    $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths         = array("60","60","24","7","4.35","12","10");

    $now             = time();

    if($now > $unix_date) {   
        $difference     = $now - $unix_date;
        $tense         = "";
       
    } else {
        $difference     = $unix_date - $now;
        $tense         = "";
    }
   
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
   
    $difference = round($difference);
   
    if($difference != 1) {
        $periods[$j].= "s";
    }
    
    $textr = "$difference $periods[$j] {$tense} ";
    
    if ($j == 3) {
        $unix_date = $unix_date - $difference * 24 * 60 * 60;
        if($now > $unix_date) {   
            $difference     = $now - $unix_date;
            $tense         = "";
           
        } else {
            $difference     = $unix_date - $now;
            $tense         = "";
        }
       
        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }
       
        $difference = round($difference);
       
        if($difference != 1) {
            $periods[$j].= "s";
        }
        
        $textr .= " $difference $periods[$j] {$tense}";
    }
   
    return $textr;
}


function reader_nicetime2($session_time){
    $time_difference = $session_time ;

    $seconds = $time_difference ;
    $minutes = round($time_difference / 60 );
    $hours = round($time_difference / 3600 );
    $days = round($time_difference / 86400 );
    $weeks = round($time_difference / 604800 );
    $months = round($time_difference / 2419200 );
    $years = round($time_difference / 29030400 );
    // Seconds
    if($seconds <= 60) { 
        $text .= "$seconds seconds "; 
    }
    //Minutes
    else if($minutes <=60) {
        if($minutes==1) {
            $text .= "one minute ";
        } else {
            $text .= "$minutes minutes ";
        }
    }
    //Hours
    else if($hours <=24) {
        if($hours==1) {
            $text .= "one hour ";
        } else {
            $text .= "$hours hours ";
        }
    }
    //Days
    else if($days <= 7) {
        if($days==1) {
            $text .= "one day ";
        } else {
            $text .= "$days days ";
        }
    }
    //Weeks
    else if($weeks <= 4) {
        if($weeks==1) {
            $text .= "one week ";
        } else {
            $text .= "$weeks weeks ";
        }
    }
    //Months
    else if($months <=12) {
        if($months==1) {
            $text .= "one month ";
        } else {
            $text .= "$months months ";
        }
    }
    //Years
    else {
        if($years==1) {
            $text .= "one year ago";
        } else {
            $text .= "$years years ";
        }
    }

    return $text;
}


function reader_forcedtimedelay_check ($cleartime, $reader, $studentlevel, $lasttime) {
    global $USER, $course,$DB;
    
    $data = $DB->get_record("reader_forcedtimedelay", array( "readerid" => $reader->id,  "level" => 99,  "groupid" => 0));

    $data = $DB->get_record("reader_forcedtimedelay", array( "readerid" => $reader->id,  "level" => $studentlevel,  "groupid" => 0));

    if ($usergroups = groups_get_all_groups($course->id, $USER->id)){
        foreach ($usergroups as $group){
            $data = $DB->get_record("reader_forcedtimedelay", array( "readerid" => $reader->id,  "level" => $studentlevel,  "groupid" => $group->id));
        }
    }
    
    if (isset($data->delay)) {
        return $data->delay + $lasttime;
    } else {
        return $cleartime;
    }
}


function reader_put_to_quiz_attempt($attemptid) {
    global $DB;
    
    if ($data = $DB->get_record("reader_attempts", array("id" => $attemptid))) {
        if ($datapub = $DB->get_record("reader_publisher", array("id" => $data->quizid))) {
            $add                         = array();
            $add['uniqueid']             = $data->uniqueid;
            $add['quiz']                 = $datapub->quizid;
            $add['userid']               = $data->userid;
            $add['attempt']              = $data->attempt;
            $add['sumgrades']            = $data->sumgrades;
            $add['timestart']            = $data->timestart;
            $add['timefinish']           = $data->timefinish;
            $add['timemodified']         = $data->timemodified;
            $add['layout']               = $data->layout;
            $add['preview']              = 0;
            $add['needsupgradetonewqe']  = 0;
            
            $DB->delete_records("quiz_attempts", array("uniqueid" => $data->uniqueid));
            
            $id = $DB->insert_record("quiz_attempts", $add);
        } else {
            return false;
        }
    } else {
        return false;
    }
}


function reader_red_notice($text, $return = false) {
    $o  = "";

    $o .= html_writer::start_tag('center');
    $o .= html_writer::start_tag('h2');
    $o .= html_writer::tag('font', $text, array('color'=>'red'));
    $o .= html_writer::end_tag('h2');
    $o .= html_writer::end_tag('center');
    
    if ($return)
        return $o;
    else
        echo $o;
}


function reader_confirm_link($url, $text, $ctext){
    global $OUTPUT;
    
    return html_writer::link($url, html_writer::empty_tag('img', array('src'=>$OUTPUT->pix_url('t/delete'), 'alt'=>$text, 'title'=>$text, 'class'=>'smallicon', 'onclick'=>'if(confirm(\''.$ctext.'\')) return true; else return false;')));
}


function reader_getfile($itemid){
    global $DB, $CFG;
    
    if ($file = $DB->get_record_sql("SELECT * FROM {files} WHERE `itemid`=? AND `filesize` != 0", array($itemid))){
        $contenthash = $file->contenthash;
        $l1          = $contenthash[0].$contenthash[1];
        $l2          = $contenthash[2].$contenthash[3];
        $filepatch   = $CFG->dataroot."/filedir/$l1/$l2/$contenthash";
        
        $file->fullpatch = $filepatch;
        
        return $file;
    } else
        return false;
}


function reader_get_cache($prefix, $t = 3600){
    global $CFG;
    
    $file = $CFG->dataroot.'/cache/reader/'.$prefix;
    
    if(is_file($file) && filemtime($file) > (time() - $t)) {
      return file_get_contents($file);
    } else 
      return false;
      
}


function reader_save_cache($prefix, $o){
    global $CFG;
    
    $dir  = $CFG->dataroot.'/cache/reader';
    
    if (!is_dir($CFG->dataroot.'/cache')) 
      mkdir($dir, 0777);
    
    if (!is_dir($dir)) 
      mkdir($dir, 0777);
    
    $file = $dir.'/'.$prefix;
    
    file_put_contents($file, $o);
    
    return true;
}


function reader_get_cache_obj($prefix, $t = 3600){
    global $CFG;
    
    $file = $CFG->dataroot.'/cache/reader/'.$prefix;
    
    if(is_file($file) && filemtime($file) > (time() - $t)) {
      return unserialize(file_get_contents($file));
    } else 
      return false;
      
}


function reader_save_cache_obj($prefix, $o){
    global $CFG;
    
    $dir  = $CFG->dataroot.'/cache/reader';
    
    if (!is_dir($CFG->dataroot.'/cache')) 
      mkdir($dir, 0777);
    
    if (!is_dir($dir)) 
      mkdir($dir, 0777);
    
    $file = $dir.'/'.$prefix;
    
    file_put_contents($file, serialize($o));
    
    return true;
}

