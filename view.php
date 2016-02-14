<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin

    require_once("../../config.php");
    require_once("lib.php");
    require_once($CFG->dirroot.'/course/moodleform_mod.php');
    require_once($CFG->dirroot . '/question/editlib.php');
    
    $id        = required_param('id', PARAM_INT); 
    $a         = optional_param('a', NULL, PARAM_CLEAN); 

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
    
    add_to_log($course->id, "reader", "view personal page", "view.php?id=$id", "$cm->instance");
    
    
    $contextmodule = get_context_instance(CONTEXT_MODULE, $cm->id);


/*
* check admin by user view
*/
    if (isset($_SESSION['SESSION']->reader_page) || isset($_SESSION['SESSION']->reader_lasttime)){
        if ($_SESSION['SESSION']->reader_page == 'view' || $_SESSION['SESSION']->reader_lasttime < (time() - 300)) {
          unset ($_SESSION['SESSION']->reader_page);
          unset ($_SESSION['SESSION']->reader_lasttime);
          unset ($_SESSION['SESSION']->reader_lastuser);
          unset ($_SESSION['SESSION']->reader_lastuserfrom);
        }
    }
    
    
/*
* Small patch delete later
*/
    if ($reader->shuffleanswers == 0) {
        $DB->set_field("reader",  "shuffleanswers",  1, array( "id" => $reader->id));
        $reader->shuffleanswers = 1;
    }


/*
* Initialize $PAGE, compute blocks
*/
    $PAGE->set_url('/mod/reader/view.php', array('id' => $cm->id));
    
    $title = $course->shortname . ': ' . format_string($reader->name);
    $PAGE->set_title($title);
    $PAGE->set_heading($course->fullname);
    $PAGE->requires->js('/mod/reader/js/jquery-1.4.2.min.js', true);
    $PAGE->requires->js('/mod/reader/js/view.js');
    
    $PAGE->requires->css('/mod/reader/css/main.css');
                  
    echo $OUTPUT->header();
                  

/*
* Check time [open/close]
*/
    $timenow = time();
    if (!empty($reader->timeopen) && $reader->timeopen > $timenow) {
        error (get_string('quizopens', 'quiz').': '.userdate($reader->timeopen));
    }
    if (!empty($reader->timeclose) && $reader->timeclose < $timenow) {
        error (get_string('quizcloses', 'quiz').': '.userdate($reader->timeclose));
    }
    
    $alreadyansweredbooksid = array();
    
    
    if (has_capability('mod/reader:manage', $contextmodule)) {
        require_once ('tabs.php');
    } else {
    /// Check subnet access
        if ($reader->subnet && !address_in_subnet(getremoteaddr(), $reader->subnet)) {
            error(get_string("subneterror", "quiz"), "view.php?id=".$id);
        }
    }
    
    $leveldata = reader_get_stlevel_data($reader);
    
    echo $OUTPUT->box_start('generalbox');

    $table = new html_table();

    if ($reader->pointreport == 1) {
        if ($reader->reportwordspoints != 1) {  //1 - only points
            $table->head = array ("Date", "Book Title", "Level", "Words", "Percent Correct", "Total Points");
            $table->align = array ("left", "left", "left", "center", "center", "center");
        } else {
            $table->head = array ("Date", "Book Title", "Level", "Percent Correct", "Total Points");
            $table->align = array ("left", "left", "left", "center", "center");
        }
    } else {
        if ($reader->reportwordspoints == 2) {  //points and words
            $table->head = array ("Date", "Book Title", "Level", "Status", "Words", "Points This Book", "Total Points");
            $table->align = array ("left", "left", "left", "center", "center", "center", "center");
        } else if ($reader->reportwordspoints == 1) {  //points only
            $table->head = array ("Date", "Book Title", "Level", "Status", "Points This Book", "Total Points");
            $table->align = array ("left", "left", "left", "center", "center", "center");
        } else if ($reader->reportwordspoints == 0) {  //words only
            $table->head = array ("Date", "Book Title", "Level", "Status", "Words", "Total words");
            $table->align = array ("left", "left", "left", "center", "center", "center");
        }
    }
    
    $table->width         = "800";
    
    $totalpoints          = 0;
    $correctpoints        = 0;
    $totalwords           = 0;
    $totalwordscount      = 0;
    $totalwordsall        = 0;
    $totalwordscountall   = 0;
    
    $bookcovers           = "";
    $bookcoversinprevterm = "";
    $bookcoversinthisterm = "";
        
    if ($oldattempts = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE userid= ? and timefinish <= ? ORDER BY timefinish", array($USER->id, $reader->ignordate))) {
        foreach ($oldattempts as $oldattempt) {
            //-----------------Book old covers------------------//
            if ($reader->bookcovers == 1 && strtolower($oldattempt->passed) == 'true') {
                $bookdata = $DB->get_record("reader_publisher", array( "id" => $oldattempt->quizid));
                $imagelink = new moodle_url('/mod/reader/images.php/reader/images/'.$bookdata->image);
                $bookcoversinprevterm .= html_writer::empty_tag('img', array('src'=>$imagelink, 'border'=>0, 'alt'=>$bookdata->name, 'height'=>150, 'width'=>100));
            }
            //----------------------------------------------//
        }
    }

        
    if (list($attemptdata, $summaryattemptdata) = reader_get_student_attempts($USER->id, $reader)) {
        foreach ($attemptdata as $attemptdata_) {
            $lastattemptdate = $attemptdata_['timefinish']; // fixing postgress problem
                
            $alreadyansweredbooksid[] = $attemptdata_['quizid'];
                
            //-----------------Book covers------------------//
            if ($reader->bookcovers == 1 && $attemptdata_['status'] == 'correct') {
                $imagelink = new moodle_url('/mod/reader/images.php/reader/images/'.$attemptdata_['image']);
                $bookcoversinthisterm .= html_writer::empty_tag('img', array('src'=>$imagelink, 'border'=>0, 'alt'=>$attemptdata_['booktitle'], 'height'=>150, 'width'=>100));
            }
            //----------------------------------------------//
                
            if ($attemptdata_['statustext'] == "Passed" || $attemptdata_['statustext'] == "Credit"){
                $totalwords += $attemptdata_['words'];
                $totalwordscount++;
                $showwords = $attemptdata_['words'];
            } else {
                $showwords = "";
            }
                
            if ($reader->pointreport == 1) {
                if ($reader->reportwordspoints != 1) {
                    $table->data[] = array (date ("d M Y", $attemptdata_['timefinish']), 
                                            $attemptdata_['booktitle'], 
                                            $attemptdata_['booklevel']."[RL ".$attemptdata_['bookdiff']."]", 
                                            $showwords,
                                            $attemptdata_['bookpersent'], 
                                            $attemptdata_['totalpoints']);
                } else {  //without words
                    $table->data[] = array (date ("d M Y", $attemptdata_['timefinish']), 
                                            $attemptdata_['booktitle'], 
                                            $attemptdata_['booklevel']."[RL ".$attemptdata_['bookdiff']."]",
                                            $attemptdata_['bookpersent'], 
                                            $attemptdata_['totalpoints']);
                }
            } else {
                if ($reader->reportwordspoints == 2) {  //points and words
                    $table->data[] = array (date ("d M Y", $attemptdata_['timefinish']), 
                                            $attemptdata_['booktitle'], 
                                            $attemptdata_['booklevel']."[RL ".$attemptdata_['bookdiff']."]", 
                                            $attemptdata_['statustext'], 
                                            $showwords,
                                            $attemptdata_['bookpoints'], 
                                            $attemptdata_['totalpoints']);
                } else if ($reader->reportwordspoints == 1) {  //points only
                    $table->data[] = array (date ("d M Y", $attemptdata_['timefinish']), 
                                            $attemptdata_['booktitle'], 
                                            $attemptdata_['booklevel']."[RL ".$attemptdata_['bookdiff']."]", 
                                            $attemptdata_['statustext'], 
                                            $attemptdata_['bookpoints'], 
                                            $attemptdata_['totalpoints']);
                } else if ($reader->reportwordspoints == 0) {  //words only
                    $table->data[] = array (date ("d M Y", $attemptdata_['timefinish']), 
                                            $attemptdata_['booktitle'], 
                                            $attemptdata_['booklevel']."[RL ".$attemptdata_['bookdiff']."]", 
                                            $attemptdata_['statustext'], 
                                            $showwords,
                                            $totalwords);
                }
            }
        }
    }

    //--------SELECT ALL words------//
    $studentattempts_p = $DB->get_records_sql("SELECT ra.timefinish,ra.userid,ra.attempt,ra.persent,ra.id,ra.quizid,ra.sumgrades,ra.passed,rp.name,rp.publisher,rp.level,rp.length,rp.image,rp.difficulty,rp.words,rp.id as rpid FROM {reader_attempts} ra LEFT JOIN {reader_publisher} rp ON rp.id = ra.quizid WHERE ra.preview != 1 and ra.userid= ?", array($USER->id));

    $studentattempts_n = $DB->get_records_sql("SELECT ra.timefinish,ra.userid,ra.attempt,ra.persent,ra.id,ra.quizid,ra.sumgrades,ra.passed,rp.name,rp.publisher,rp.level,rp.length,rp.image,rp.difficulty,rp.words,rp.id as rpid FROM {reader_attempts} ra LEFT JOIN {reader_noquiz} rp ON rp.id = ra.quizid WHERE ra.preview = 1 and ra.userid= ?", array($USER->id));

    if (is_array($studentattempts_n ) && is_array($studentattempts_p)) {
        $studentattempts = array_merge($studentattempts_p,$studentattempts_n);
    } elseif ($studentattempts_n) {
        $studentattempts = $studentattempts_n;
    } else { 
        $studentattempts = $studentattempts_p;
    }
    
    foreach ($studentattempts as $studentattempt) {
        if (strtolower($studentattempt->passed) == "true"){
            $totalwordsall      += $studentattempt->words;
            $totalwordscountall++;
        }
    }
        
    if (!empty($bookcoversinprevterm)) {
        echo html_writer::empty_tag('br');
        echo html_writer::tag('h2', get_string("booksreadinpreviousterms", "reader"));
        echo html_writer::empty_tag('br');
        echo $bookcoversinprevterm;
        echo html_writer::empty_tag('br');
        echo $OUTPUT->action_link(new moodle_url("/mod/reader/showincorrectquizzes.php", array("id" => $id, "uid" => $USER->id)), get_string("incorrectbooksreadinpreviousterms", "reader"));
        echo html_writer::empty_tag('br');
        echo html_writer::empty_tag('hr');
    }
    
    if (!empty($bookcoversinthisterm)) {
        echo html_writer::empty_tag('br');
        echo html_writer::tag('h2', get_string("booksreadthisterm", "reader"));
        echo html_writer::empty_tag('br');
        echo $bookcoversinthisterm;
        echo html_writer::empty_tag('br');
        echo html_writer::empty_tag('br');
    }
    
    echo html_writer::empty_tag('br');
    echo html_writer::start_tag('table', array('width'=>'100%'));
    echo html_writer::start_tag('tr');
    echo html_writer::start_tag('td');
    echo html_writer::start_tag('h2');
    echo html_writer::tag('span', get_string("readingreportfor", "reader").": {$USER->firstname} {$USER->lastname}", array('class'=>'v-orange'));
    echo html_writer::end_tag('h2');
    
    if (isset($USER->realuser)) {
        echo html_writer::end_tag('td');
        echo html_writer::start_tag('td', array('width'=>'50%', 'align'=>'right'));
        echo html_writer::start_tag('small');
        echo html_writer::start_tag('span', array('class'=>'tal-r'));
        echo html_writer::link(new moodle_url('/mod/reader/admin.php', array('a'=>'admin', 'id'=>$id, 'act'=>'reports')), get_string("returntostudentlist", "reader"));
        echo html_writer::end_tag('span');
        echo html_writer::end_tag('small');
    }
    
    echo html_writer::end_tag('td');
    echo html_writer::end_tag('tr');
    echo html_writer::end_tag('table');

    if (!empty($table->data)) {
        echo html_writer::tag('center', html_writer::table($table));
    } else {
        print_string("nodata", "reader");
    }
        
    /*
    Under table fields
    */
        
    if ($reader->wordsprogressbar) {
        echo html_writer::start_tag('table', array('width'=>800, 'cellpadding'=>5, 'cellspacing'=>1, 'class'=>'generaltable boxaligncenter'));
        echo html_writer::start_tag('tr');
        echo html_writer::tag('th', get_string("totalwords", "reader").': '.$totalwords, array('width'=>500, 'class'=>'v-total-words'));
        echo html_writer::tag('th', get_string("totalwordsall", "reader").": ".$totalwordsall, array('class'=>'v-total-words-all'));
        echo html_writer::end_tag('tr');
        echo html_writer::end_tag('table');
        
        echo html_writer::start_tag('table', array('width'=>820, 'cellpadding'=>0, 'cellspacing'=>0, 'class'=>'generaltable boxaligncenter'));
        echo html_writer::start_tag('tr');
        echo html_writer::start_tag('td', array('align'=>'center'));

        if ($progressimage = reader_get_goal_progress($totalwords, $reader)) {
            echo html_writer::start_tag('table', array('width'=>820));
            echo html_writer::start_tag('tr');
            echo html_writer::start_tag('td', array('align'=>'center'));
            echo html_writer::tag('div', $progressimage, array('class'=>'v-progress-image'));
            echo html_writer::end_tag('td');
            echo html_writer::end_tag('tr');
            echo html_writer::start_tag('tr');
            echo html_writer::start_tag('td');
            echo html_writer::tag('b', '&nbsp;'.get_string("in1000sofwords", "reader"));
            echo html_writer::end_tag('td');
            echo html_writer::end_tag('tr');
            echo html_writer::end_tag('table');
        }
        echo html_writer::end_tag('td');
        echo html_writer::end_tag('tr');
        echo html_writer::end_tag('table');
    }
    
    echo html_writer::start_tag('div', array('style'=>'float:left;'));
    
    echo html_writer::tag('h3', get_string("yourcurrentlevel", "reader").": {$leveldata['studentlevel']}");
    
    $promoteinfo = $DB->get_record("reader_levels", array( "userid" => $USER->id,  "readerid" => $reader->id));
    if ($promoteinfo->nopromote == 1) {
        if ($promoteinfo->promotionstop == $leveldata['studentlevel']) {
            print_string("pleaseaskyourinstructor", "reader");
        } else {
            print_string("yourteacherhasstopped", "reader");
        }
        
        //-------Promotion stop On this level----------//
        print_string("youcantakeasmanyquizzesasyouwant", "reader", $leveldata['studentlevel']);
        //-------Promotion stop On prev level----------//
        
        if ($leveldata['onprevlevel'] <= 0) {
            $quizcount = 'no';
        } else {
            $quizcount = $leveldata['onprevlevel'];
        }
        
        if ($leveldata['onprevlevel'] == 1) { 
            $quiztext = "quiz"; 
        } else { 
            $quiztext = "quizzes"; 
        }
        
        print_string("youmayalsotake", "reader", $quizcount);
        
        echo "{$quiztext} ".get_string("atlevel", "reader")." ".($leveldata['studentlevel'] - 1)." ";
    } else if ($reader->levelcheck == 1){
        //-------Promotion On his level----------//
        if ($leveldata['onthislevel'] == 1) {
            print_string("youmusttakequiz", "reader", $leveldata['onthislevel']);
        } else {
            print_string("youmusttakequizzes", "reader", $leveldata['onthislevel']);
        }
        print_string("atlevelbeforebeingpromoted", "reader", $leveldata['studentlevel']);
       
        //-------Promotion On prev level----------//
        if ($leveldata['onprevlevel'] <= 0) {
            $quizcount = 'no';
        } else {
            $quizcount = $leveldata['onprevlevel'];
        }
        
        if ($leveldata['onprevlevel'] == 1) { 
            $quiztext = "quiz"; 
        } else { 
            $quiztext = "quizzes"; 
        }
        
        if (($leveldata['studentlevel'] - 1) >= 0) {
            //-------Promotion On next level----------//
            if ($leveldata['onprevlevel'] > 0 && $leveldata['onnextlevel'] <= 0) {
                $quiznextlevelso = 'but';
            } else {
                $quiznextlevelso = 'and';
            }
            print_string("youmayalsotake", "reader", $quizcount);
//This line moved down to here
            echo "{$quiztext} ".get_string("atlevel", "reader")." ".($leveldata['studentlevel'] - 1)." ";
        } else {
            print_string("youcantake", "reader");
        }
        
        if ($leveldata['onnextlevel'] <= 0) {
            $quizcount = 'no';
        } else {
            $quizcount = $leveldata['onnextlevel'];
        }
        
        if (!isset($quiznextlevelso)) $quiznextlevelso = "";
        
        if ($leveldata['onnextlevel'] == 1) { 
            $quiztext = " quiz "; 
        } else { 
            $quiztext = " quizzes "; 
        }
        
        echo $quiznextlevelso.get_string("andnextmore", "reader", $quizcount).$quiztext.get_string("atlevel", "reader"). " " . ($leveldata['studentlevel'] + 1 .".");
    } else if ($reader->levelcheck == 0) {
        print_string("butyoumaytakequizzes", "reader");
    }
    
    //-----------------Check time----------------------------------//
    if ($reader->attemptsofday > 0) {
        $cleartime = $lastattemptdate + ($reader->attemptsofday * 24 * 60 * 60);
        $cleartime = reader_forcedtimedelay_check ($cleartime, $reader, $leveldata['studentlevel'], $lastattemptdate);
        $time = time();
        if ($time > $cleartime) {
            $showform = true;
            echo html_writer::tag('span', '&nbsp;&nbsp;'.get_string("youcantakeaquiznow", "reader").'&nbsp;&nbsp;', array('class'=>'v-take-quiz-bg'));
        } else {
            $showform = false;
            $approvetime = $cleartime - time();
            echo html_writer::tag('span', '&nbsp;&nbsp;'.get_string("youcantakeaquizafter", "reader").' '.reader_nicetime2($approvetime).'&nbsp;&nbsp;', array('class'=>'v-take-cant-quiz-bg'));
        }
    } else {
        $showform = true;
    }
    //--------------------------------------------------------------//
    
    echo html_writer::tag('h3', get_string("messagefromyourteacher", "reader"));
    
    $messages = $DB->get_records_sql("SELECT * FROM {reader_messages} WHERE instance = ?", array($cm->instance));
    
    if (count($messages) > 0 && !empty($messages)) {
        $usergroupsarray = array(0);
        $studentgroups = groups_get_user_groups($course->id, $USER->id);
        foreach ($studentgroups as $studentgroup) {
            foreach ($studentgroup as $studentgroup_) {
                $usergroupsarray[] = $studentgroup_;
            }
        }

        foreach ($messages as $message) {
            $forgroupsarray = explode (',', $message->users);
            $showmessage = false;
            $bgcolor  = '';
            
            foreach ($forgroupsarray as $forgroupsarray_) {
                if (in_array($forgroupsarray_, $usergroupsarray)) {
                    $showmessage = true;
                }
            }
            
            if ($message->timemodified > (time() - ( 48 * 60 * 60))) {
                $bgcolor = 'bgcolor="#CCFFCC"';
            }
            
            if ($showmessage) {
                echo html_writer::start_tag('table', array('width'=>'100%'));
                echo html_writer::start_tag('tr');
                echo html_writer::start_tag('td', array('align'=>'right'));
                echo html_writer::start_tag('table', array('cellspacing'=>0, 'cellpadding'=>0, 'class'=>'forumpost blogpost blog" '.$bgcolor, 'width'=>'90%'));
                echo html_writer::start_tag('tr');
                echo html_writer::start_tag('td', array('align'=>'left'));
                echo html_writer::start_tag('div', array('class'=>'v-message'));
                echo format_text($message->text);
                echo html_writer::start_tag('div', array('class'=>'tal-r'));
                echo html_writer::start_tag('small');
                
                $teacherdata = $DB->get_record("user", array( "id" => $message->teacherid));
                $link = new moodle_url("/user/view.php", array("id" => $message->teacherid, "course" => $course->id));
                
                echo html_writer::link($link, fullname($teacherdata, true));
                echo html_writer::end_tag('small');
                echo html_writer::end_tag('div');
                echo html_writer::end_tag('div');
                echo html_writer::end_tag('td');
                echo html_writer::end_tag('tr');
                echo html_writer::end_tag('table');
                echo html_writer::end_tag('td');
                echo html_writer::end_tag('tr');
                echo html_writer::end_tag('table');
            }
        }
    }
    
    echo html_writer::tag('h3', get_string("selectthebookthatyouwant", "reader").":");
    
    //------GET LIST--------------//
    $publisherform          = array("id=".$id."&publisher=Select_Publisher" => get_string("selectpublisher", "reader"));
    $publisherform2         = array("id=".$id."&publisher=Select_Publisher" => get_string("selectpublisher", "reader"));
    $seriesform             = array(get_string("selectseries", "reader"));
    $levelsform             = array(get_string("selectlevel", "reader"));
    $booksform              = array();
    //-------------Get list start----------------//
    
    $alreadyansweredbooksid = array();
    $leveldata              = reader_get_stlevel_data($reader);
    $promoteinfo            = $DB->get_record("reader_levels", array( "userid" => $USER->id,  "readerid" => $reader->id));
    $allowdifficultysql     = "";
    
    if ($leveldata['onthislevel'] > 0) $allowdifficultysql .= $leveldata['studentlevel'].",";
    if ($leveldata['onprevlevel'] > 0 && ($leveldata['studentlevel'] - 1) >= 0) $allowdifficultysql .= ($leveldata['studentlevel'] - 1).",";
    if ($leveldata['onnextlevel'] > 0) $allowdifficultysql .= ($leveldata['studentlevel'] + 1).",";
    $allowdifficultysql = substr($allowdifficultysql, 0, -1);
    if ((isset($_SESSION['SESSION']->reader_teacherview) && $_SESSION['SESSION']->reader_teacherview == "teacherview") || $reader->levelcheck == 0) $allowdifficultysql = '0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15';
    
    if (list($attemptdata, $summaryattemptdata) = reader_get_student_attempts($USER->id, $reader, true, true)) {
        foreach ($attemptdata as $attemptdata_) {
            reader_set_attempt_result ($attemptdata_['id'], $reader);  //insert result
            $alreadyansweredbooksid[] = $attemptdata_['quizid'];
            if (!empty($attemptdata_['sametitle'])) $alreadyansweredbookssametitle[] = $attemptdata_['sametitle'];
        }
    }
    
    $publishers = $DB->get_records ("reader_publisher", NULL, "publisher");
    foreach ($publishers as $publisher_) {
        $publisherform["id=".$id."&publisher=".$publisher_->publisher] = $publisher_->publisher;
    }
    
    
    foreach ($publisherform as $key => $value) {
        $needtousepublisher = false;
        if ($allowdifficultysql != "") {
            if ($reader->individualbooks == 1) {
                $books = $DB->get_records_sql("SELECT * FROM {reader_publisher} rp INNER JOIN {reader_individual_books} ib ON ib.bookid = rp.id WHERE ib.readerid = ? and rp.publisher= ? and rp.hidden='0' and rp.private IN(0, ? ) and ib.difficulty IN( ".$allowdifficultysql." ) ORDER BY rp.name", array($reader->id, $value, $reader->id));
            } else {
                $books = $DB->get_records_sql("SELECT * FROM {reader_publisher} WHERE publisher= ? and hidden='0' and private IN(0, ? ) and difficulty IN( ".$allowdifficultysql." ) ORDER BY name", array($value, $reader->id));
            }
            
            foreach ($books as $books_) {
                if (!empty($books_->quizid)) {
                    if ($reader->individualbooks == 1) {
                        if (!in_array($books_->bookid, $alreadyansweredbooksid)) $needtousepublisher = true;
                    } else {
                        if (!in_array($books_->id, $alreadyansweredbooksid)) $needtousepublisher = true;
                    }
                    
                    if ($showform) {
                        if (!empty($books_->sametitle) && is_array($alreadyansweredbookssametitle)) {
                            if ($reader->individualbooks == 1) {
                                if (!in_array($books_->sametitle, $alreadyansweredbookssametitle)) { $needtousepublisher = true; break; }
                            } else {
                                if (!in_array($books_->sametitle, $alreadyansweredbookssametitle)) { $needtousepublisher = true; break; }
                            }
                        } else {
                            if ($reader->individualbooks == 1) {
                                $needtousepublisher = true;
                                break;
                            } else {
                                $needtousepublisher = true;
                                break;
                            }
                        }
                    }
                }
            }
        }
        
        if ($needtousepublisher) {
            $publisherform2["id=".$id."&publisher=".$value] = $value;
        }
    }
    
    unset($publisherform);
    $publisherform = $publisherform2;
    //-------------Get list finish----------------//
    
    if ($notcomplitequiz = $DB->get_record("reader_attempts", array( "reader" => $cm->instance,  "userid" => $USER->id,  "timefinish" => 0))) {
        $showform = false;
        
        $timelimit = 60 * $reader->timelimit;
        
        if ($timelimit < (time() - $notcomplitequiz->timestart)) {
            $showform = true;
            
            $DB->set_field("reader_attempts", "timemodified", time(), array("id" => $notcomplitequiz->id));
            $DB->set_field("reader_attempts", "timefinish", time(), array("id" => $notcomplitequiz->id));
            $DB->set_field("reader_attempts",  "passed",  "false", array( "id" => $notcomplitequiz->id));
            $DB->set_field("reader_attempts",  "persent",  "0", array( "id" => $notcomplitequiz->id));
            $DB->set_field("reader_attempts",  "sumgrades",  "0", array( "id" => $notcomplitequiz->id));
            $DB->set_field("reader_attempts",  "bookrating",  0, array( "id" => $notcomplitequiz->id));
        } else {
            $bookdata = $DB->get_record("reader_publisher", array( "id" => $notcomplitequiz->quizid));
        
            print_string("pleasecompletequiz", "reader", $bookdata->name);
            
            echo html_writer::link(new moodle_url('/mod/reader/attempt.php?'.$_SESSION['SESSION']->reader_lastattemptpage), get_string("complete", "reader"));
        }
    }
    
    
    if (isset($_SESSION['SESSION']->reader_changetostudentview)) {
        if ($showform == false && $_SESSION['SESSION']->reader_changetostudentview > 0) { 
            echo html_writer::empty_tag('br');
            echo get_string("thisblockunavailable", "reader");
            echo html_writer::empty_tag('br');
            $showform == true;
        }
    }
    
    if ($showform && has_capability('mod/reader:attemptreaders', $contextmodule)) {
        $link = new moodle_url("/mod/reader/startattempt.php", array("id" => $id));
        
        echo html_writer::start_tag('form', array('action'=>$link, 'method'=>'post', 'id'=>'mform1'));
        echo html_writer::start_tag('center');
        echo html_writer::start_tag('table', array('width'=>'600px'));
        echo html_writer::start_tag('tr');
        echo html_writer::start_tag('td', array('width'=>'200px'));
        echo get_string("publisherseries", "reader");
        echo html_writer::end_tag('td');
        echo html_writer::start_tag('td', array('width'=>'10px'));
        echo html_writer::end_tag('td');
        echo html_writer::start_tag('td', array('width'=>'200px'));
        echo html_writer::end_tag('td');
        echo html_writer::end_tag('tr');
        echo html_writer::start_tag('tr');
        echo html_writer::start_tag('td', array('valign'=>'top'));
        echo html_writer::start_tag('select', array('name'=>'publisher', 'id'=>'id_publisher'));
        foreach ($publisherform as $publisherformkey => $publisherformvalue) {
            echo html_writer::tag('option', $publisherformvalue, array('value'=>$publisherformkey));
        }
        echo html_writer::end_tag('select');
        echo html_writer::end_tag('td');
        echo html_writer::start_tag('td', array('valign'=>'top'));
        echo html_writer::end_tag('td');
        echo html_writer::start_tag('td', array('valign'=>'top'));
        echo html_writer::start_tag('div', array('id'=>'selectthebook'));
        echo html_writer::end_tag('div');
        echo html_writer::end_tag('td');
        echo html_writer::end_tag('tr');
        echo html_writer::start_tag('tr');
        echo html_writer::start_tag('td', array('colspan'=>'3', 'align'=>'center'));

        $link = new moodle_url("/course/view.php", array("id" => $course->id));

        echo html_writer::end_tag('td');
        echo html_writer::end_tag('tr');
        echo html_writer::start_tag('td', array('colspan'=>'3', 'align'=>'center'));
        echo html_writer::empty_tag('input', array('type'=>'button', 'value'=>'Take quiz', 'onclick'=>'if (validateForm(this.form)) {this.form.submit() };', 'id'=>'id_reader_view_take_quiz_btn', 'style'=>'display:none;'));
        echo html_writer::empty_tag('input', array('type'=>'button', 'value'=>get_string("returntocoursepage", "reader"), 'onclick'=>'location.href=\''.$link.'\''));
        echo html_writer::end_tag('td');
        echo html_writer::end_tag('tr');
        echo html_writer::end_tag('table');
        echo html_writer::end_tag('center');
        echo html_writer::end_tag('form');
    } else if (!$DB->get_record("reader_attempts", array( "reader" => $cm->instance,  "userid" => $USER->id,  "timefinish" => 0))) {
        print_string("pleasewait", "reader");
    }
    
    //--------From student view to teacher-------//
    if (isset($_SESSION['SESSION']->reader_changetostudentview) && $_SESSION['SESSION']->reader_changetostudentview > 0) {
        $_SESSION['SESSION']->reader_lastuser    = $USER->id;
        $_SESSION['SESSION']->reader_page        = 'view';
        $_SESSION['SESSION']->reader_lasttime    = time();
        $_SESSION['SESSION']->reader_lastuserfrom = $_SESSION['SESSION']->reader_changetostudentview;
        
        if ($USER = $DB->get_record("user", array( "id" => $_SESSION['SESSION']->reader_changetostudentview))) {
            unset($_SESSION['SESSION']->reader_changetostudentview);
            $_SESSION['SESSION']->reader_teacherview = "teacherview";
        }
    }
    //-------------------------------------------//
    
    echo html_writer::end_tag('div');
    
    if ($reader->levelcheck == 1) {
        echo html_writer::start_tag('div', array('style'=>'float:right;margin: 0 0 0 50px;'));
        
        $o  = "";

        $lmax           = $reader->quizpreviouslevel;
        $max            = $reader->nextlevel;
        $hmax           = $reader->quiznextlevel;
        $lqnow          = $reader->quizpreviouslevel - $leveldata['onprevlevel'];
        $qnow           = $reader->nextlevel - $leveldata['onthislevel'];
        $hqnow          = $reader->quiznextlevel - $leveldata['onnextlevel'];

        $spacer = html_writer::empty_tag('img', array('src'=>new moodle_url('/mod/reader/img/progress/spacer.jpg'), 'border'=>0, 'alt'=>'space', 'height'=>26, 'width'=>28, 'style'=>'margin:0 4px 0 0'));
        $done   = html_writer::empty_tag('img', array('src'=>new moodle_url('/mod/reader/img/progress/done.jpg'), 'border'=>0, 'alt'=>'done', 'height'=>26, 'width'=>28, 'style'=>'margin:0 4px 0 0'));
        $yet    = html_writer::empty_tag('img', array('src'=>new moodle_url('/mod/reader/img/progress/notyet.jpg'), 'border'=>0, 'alt'=>'notyet', 'height'=>26, 'width'=>28, 'style'=>'margin:0 4px 0 0'));
        $lm1    = html_writer::empty_tag('img', array('src'=>new moodle_url('/mod/reader/img/progress/lm1.jpg'), 'border'=>0, 'alt'=>'lm1', 'height'=>16, 'width'=>28, 'style'=>'margin:0 4px 0 0'));
        $lnow   = html_writer::empty_tag('img', array('src'=>new moodle_url('/mod/reader/img/progress/l.jpg'), 'border'=>0, 'alt'=>'l', 'height'=>16, 'width'=>28, 'style'=>'margin:0 4px 0 0'));
        $lp1    = html_writer::empty_tag('img', array('src'=>new moodle_url('/mod/reader/img/progress/lp1.jpg'), 'border'=>0, 'alt'=>'lp1', 'height'=>16, 'width'=>28, 'style'=>'margin:0 4px 0 0'));
        
        $o .= html_writer::start_tag('div');
        $o .= html_writer::tag('h3', get_string("quizzespassedtable", "reader", $leveldata['studentlevel']));
        $o .= html_writer::end_tag('div');
        
        for ($i = $max; $i > 0; $i -= 1) {
            if ($i > $lqnow && $i <= $lmax) { 
                $o = $o . $yet;
            } else if ($i > $lqnow ) {
                $o = $o . $spacer;
            } else {
                $o = $o . $done;
            }
                    
            if ($i > $qnow && $i <= $max) { 
                $o = $o . $yet;
            } else if ($i > $qnow ) {
                $o = $o . $spacer;
            } else {
                $o = $o . $done;
            }
                    
            if ($i > $hqnow && $i <= $hmax) { 
                $o = $o . $yet . html_writer::empty_tag('br');
            } else if ($i > $hqnow ) {
                $o = $o . $spacer . html_writer::empty_tag('br');
            } else {
                $o = $o . $done . html_writer::empty_tag('br');
            }
        }
            $o = $o . $lm1 . $lnow . $lp1 . html_writer::empty_tag('br');
        
        echo $o;
        
        echo html_writer::end_tag('div');
    }
    echo html_writer::tag('div', '', array('style'=>'clear:both;'));
    
    echo $OUTPUT->box_end();
    
    echo html_writer::tag('center', html_writer::empty_tag('img', array('src'=>new moodle_url('/mod/reader/img/credit.jpg'),'height'=>'40px')));

    echo $OUTPUT->footer();
    
    