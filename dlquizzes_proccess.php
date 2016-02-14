<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin

    require_once("../../config.php");
    require_once($CFG->dirroot."/mod/reader/lib.php");
    require_once($CFG->dirroot."/mod/reader/lib/pclzip/pclzip.lib.php");
    require_once($CFG->dirroot."/mod/reader/lib/backup/restorelib.php");
    require_once($CFG->dirroot."/mod/reader/lib/backup/backuplib.php");
    require_once($CFG->dirroot."/mod/reader/lib/backup/lib.php");
    require_once($CFG->dirroot."/mod/reader/lib/question/restorelib.php");
    
    $id                = required_param('id', PARAM_INT); 
    $quiz              = optional_param_array('quiz', NULL, PARAM_INT); 
    $password          = optional_param('password', NULL, PARAM_CLEAN); 
    $sectionchoosing   = optional_param('sectionchoosing', 1, PARAM_INT); 
    $section           = optional_param('section', 1, PARAM_INT); 
    $courseid          = optional_param('courseid', 0, PARAM_INT); 
    $quizid            = optional_param('quizid', 0, PARAM_INT); 
    $end               = optional_param('end', 0, PARAM_INT); 
    
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
    
    
    /**/
    require_once($CFG->dirroot.'/mod/reader/lib/question/type/multianswer/questiontype.php');
    require_once($CFG->dirroot.'/mod/reader/lib/question/type/multichoice/questiontype.php');
    require_once($CFG->dirroot.'/mod/reader/lib/question/type/ordering/questiontype.php');
    require_once($CFG->dirroot.'/mod/reader/lib/question/type/truefalse/questiontype.php');
    require_once($CFG->dirroot.'/mod/reader/lib/question/type/random/questiontype.php');
    require_once($CFG->dirroot.'/mod/reader/lib/question/type/match/questiontype.php');
    require_once($CFG->dirroot.'/mod/reader/lib/question/type/description/questiontype.php');
    
    $QTYPES = array();
    $QTYPES['multianswer'] = new back_multianswer_qtype();
    $QTYPES['multichoice'] = new back_multichoice_qtype();
    $QTYPES['ordering']    = new back_ordering_qtype();
    $QTYPES['truefalse']   = new back_truefalse_qtype();
    $QTYPES['random']      = new back_random_qtype();
    $QTYPES['match']       = new back_match_qtype();
    $QTYPES['description'] = new back_description_qtype();
    /**/
    
    $context = get_context_instance(CONTEXT_COURSE, $course->id);
    $contextmodule = get_context_instance(CONTEXT_MODULE, $cm->id);
    if (!has_capability('mod/reader:manage', $contextmodule)) { 
        error("You should be \"Edit\" Teacher");
    }
    
    $readercfg = get_config('reader');

    add_to_log($course->id, "reader", "Download Quizzes Process", "dlquizzes.php?id=$id", "$cm->instance");
    
// Initialize $PAGE, compute blocks
    $PAGE->set_url('/mod/reader/dlquizzes_proccess.php', array('id' => $cm->id));
    
    $title = $course->shortname . ': ' . format_string($reader->name);
    $PAGE->set_title($title);
    $PAGE->set_heading($course->fullname);
    $PAGE->requires->js('/mod/reader/js/jquery-1.4.2.min.js', true);
    
    if (empty($quizid)) {
        echo $OUTPUT->header();
        echo $OUTPUT->box_start('generalbox');
        
        $o = "";
        $o .= 'var quizzes = new Array();';

        $lastkey = 0;
        while(list($key,$value)=each($quiz)) {
            $o .= ' quizzes['.$key.'] = ['.$value.'];';
            $lastkey = $key;
        }
  
        $o .= 'lastkey = '.$lastkey.';';
        $o .= '$(document).ready(function() {loadquiz(0);});function loadquiz(key) {if (key == window.lastkey) var end = 1; else var end = 0;$.post(\''.$CFG->wwwroot.'/mod/reader/dlquizzes_proccess.php?id='.$id.'&quizid=\'+window.quizzes[key]+\'&courseid='.$courseid.'&sectionchoosing='.$sectionchoosing.'&section='.$section.'&end=\'+end, function(data) {$("#installationlog").append(data);if (key != window.lastkey) loadquiz(key + 1); });}';
        
        echo html_writer::script($o);
        echo html_writer::tag('div', 'Installation in process...', array('id'=>'installationlog'));
    } else {
        $OUTPUT->header();
      
        $xmlquizzesfile = reader_file($readercfg->reader_serverlink."/?a=quizzes&login={$readercfg->reader_serverlogin}&password={$readercfg->reader_serverpassword}", array('password'=>$password, 'quiz'=>array($quizid), 'upload'=>'true'));
      
        $listofquizzes = xmlize($xmlquizzesfile);
        foreach ($listofquizzes['myxml']['#']['item'] as $number => $listofquizze) {
            foreach ($listofquizze['@'] as $key => $value) {
                $quizzestoadd[$number][$key] = $value;
                $sections[$listofquizze['@']['publisher']." - ".$listofquizze['@']['level']] = $listofquizze['@']['publisher']." - ".$listofquizze['@']['level'];
                if (empty($currentsection)) {
                    $currentsection = $listofquizze['@']['publisher']." - ".$listofquizze['@']['level'];
                }
            }
        }
            
        if ($courseid == 0) {
            $data                 = array();
            $data['fullname']     = 'All Quizzes';
            $data['shortname']    = 'Reader';
            $data['summary']      = 'All Quizzes';
            $data['format']       = "topics";
            $data['numsections']  = count($sections);
            $data['startdate']    = time();
            $data['timecreated']  = time();
            $data['enrollable']   = 1;
            $data['password']     = 'readeradmin';
            $data['visible']      = 1;
            $data['guest']        = 0;
            
            $category = $DB->get_record("course_categories", array("visible" => "1"));
            
            $data['category']     = $category->id;
            
            $coursedata           = create_course($data);
            $courseid             = $coursedata->id;
            
            print_string('process_courseadded', 'reader');
            
            $sectionnum           = 1;
        } else if ($sectionchoosing == 1) {
            $excoursedata = $DB->get_record('course', array('id' => $courseid));
            $DB->set_field('course', 'numsections', ($excoursedata->numsections + count($sections)), array('id' => $courseid));
            
            $sectionnum = $excoursedata->numsections + 1;
        }
        
        $_SESSION['SESSION']->reader_downloadprocesscourseid = $courseid;
        
        $module = $DB->get_record("modules", array("name" => "quiz"));
        
        //Add quizzes
        foreach ($quizzestoadd as $quizzestoadd_) {
            if ($sectionchoosing == 1) {
                if ($currentsection != $quizzestoadd_['publisher']." - ".$quizzestoadd_['level']) {
                    $sectionnum++;
                    $currentsection = $quizzestoadd_['publisher']." - ".$quizzestoadd_['level'];
                }
            } else if ($sectionchoosing == 2) {
                //--------------------Read sections list-------------------//
                $currentcoursesections = $DB->get_records("course_sections", array("course" => $courseid));
                //---------------------------------------------------------//
                $needtoaddsection = false;
                $sectionnum = 0;
                foreach ($currentcoursesections as $currentcoursesections_) {
                    if ($currentcoursesections_->summary == $quizzestoadd_['publisher']." - ".$quizzestoadd_['level']) {
                        $sectionnum = $currentcoursesections_->section;
                    }
                }
                if ($sectionnum == 0) {
                    $needtoaddsection = true;
                    $excoursedata = $DB->get_record('course', array('id' => $courseid));
                    $sectionnum = $excoursedata->numsections + 1;
                    $currentsection = $quizzestoadd_['publisher']." - ".$quizzestoadd_['level'];
                    $DB->set_field('course',  'numsections',  $sectionnum, array( 'id' => $courseid));
                }
            } else if ($sectionchoosing == 3) {
                $sectionnum = $section;
            }
            
            $maxquizgrade = 0;
        
            $quizdata                        = new stdClass;
            $quizdata->name                  = $quizzestoadd_['title'];
            $quizdata->intro                 = " ";
            $quizdata->visible               = 1;
            $quizdata->introformat           = 1;
            $quizdata->course                = $courseid;
            $quizdata->section               = $sectionnum;
            $quizdata->add                   = 'quiz';
            $quizdata->modulename            = 'quiz';
            $quizdata->timemodified          = time();
            $quizdata->quizpassword          = '';
            $quizdata->feedbackboundarycount = 0;
            $quizdata->feedbacktext          = array(0=>array('text'=>'','format'=>0),1=>array('text'=>'','format'=>0),2=>array('text'=>'','format'=>0),3=>array('text'=>'','format'=>0),4=>array('text'=>'','format'=>0));
            $quizdata->feedbackboundaries    = array(0 => 0, -1 => 11);
            $quizdata->sumgrades             = 10;
            $quizdata->grade                 = 100;
            $quizdata->timeopen              = 0;
            $quizdata->timeclose             = 0;
            $quizdata->timelimit             = 0;
            $quizdata->delay1                = 0;
            $quizdata->delay2                = 0;
            $quizdata->questionsperpage      = 0;
            $quizdata->shufflequestions      = 0;
            $quizdata->shuffleanswers        = 1;
            $quizdata->attempts              = 0;
            $quizdata->attemptonlast         = 1;
            $quizdata->adaptive              = 1;
            $quizdata->grademethod           = 1;
            $quizdata->penaltyscheme         = 1;
            $quizdata->decimalpoints         = 2;
            $quizdata->popup                 = 0;
            $quizdata->subnet                = '';
            $quizdata->cmidnumber            = '';
            $quizdata->groupmode             = 0;
            $quizdata->module                = $module->id;
            $quizdata->modulename            = "quiz";
            $quizdata->MAX_FILE_SIZE         = 10485760;
            $quizdata->update                = 0;
            $quizdata->preferredbehaviour    = 'deferredfeedback';
            $quizdata->return                = 0;
            
            
            if ($oldquizinstancearr = $DB->get_records_sql("SELECT * FROM {quiz} WHERE course= ?  and name= ?", array($courseid, $quizzestoadd_['title']))) {
              while(list($key,$oldquizinstanceid)=each($oldquizinstancearr)) {
                  if ($oldquizinsection = $DB->get_record_sql("SELECT * FROM {course_modules} WHERE course= ? and module= ? and instance= ? and visible=1 ", array($courseid,$module->id,$oldquizinstanceid->id))) {
                      set_coursemodule_visible($oldquizinsection->id, 0);
                  }
              }
            }

            
            $quizdata->questions              = '0,';
            $quizdata->id                     = $DB->insert_record("quiz", $quizdata);
            $quizdata->instance               = $quizdata->id;
        
            if (!$quizdata->coursemodule = add_course_module($quizdata) ) {
                error("Could not add a new course module");
            }
            if (!$sectionid = add_mod_to_section($quizdata) ) {
                error("Could not add the new course module to that section");
            }
            if (!$DB->set_field("course_modules",  "section",  $sectionid, array( "id" => $quizdata->coursemodule))) {
                error("Could not update the course module with the correct section");
            }
            if (!isset($quizdata->visible)) {   // We get the section's visible field status
                $quizdata->visible = $DB->get_field("course_sections","visible","id",$sectionid);
            }
            set_coursemodule_visible($quizdata->coursemodule, $quizdata->visible);
            
            // Trigger mod_updated event with information about this module.
            $eventdata             = new stdClass();
            $eventdata->modulename = $quizdata->modulename;
            $eventdata->name       = $quizdata->name;
            $eventdata->cmid       = $quizdata->coursemodule;
            $eventdata->courseid   = $courseid;
            $eventdata->userid     = $USER->id;
            events_trigger('mod_updated', $eventdata);
            
            rebuild_course_cache($courseid);
            
            $quizbook[$quizzestoadd_['id']] = $quizdata->instance;
            
            //Set section name
            if ($sectionchoosing == 1) 
                $DB->set_field("course_sections",  "summary",  $currentsection, array( "course" => $courseid,  "section" => $sectionnum));
            
            if ($needtoaddsection) 
                $DB->set_field("course_sections",  "summary",  $currentsection, array( "course" => $courseid,  "section" => $sectionnum));
        }
        
        
        $courseqdata = $DB->get_record("course", array("id" => $courseid));
        
        $i = 0;
        
        $time = time();

        foreach ($quizzestoadd as $quizzestoadd_) {
            //Import questions
            $i++;
            
            $restoreq                            = new stdClass;
            $restoreq->backup_unique_code        = $time;
            $restoreq->backup_name               = 'moodle.zip'; 
            $modules = $DB->get_records('modules');
            foreach ($modules as $module) {
                $restoreq->mods[$module->name]->restore  = 0; 
                $restoreq->mods[$module->name]->userinfo = 0; 
            }
            $restoreq->restoreto                 = 1;
            $restoreq->metacourse                = 0;
            $restoreq->users                     = 0;
            $restoreq->groups                    = 0;
            $restoreq->logs                      = 0;
            $restoreq->user_files                = 0;
            $restoreq->course_files              = 0;
            $restoreq->site_files                = 0;
            $restoreq->messages                  = 0;
            $restoreq->blogs                     = 0;
            $restoreq->restore_gradebook_history = 0;
            $restoreq->course_id                 = $courseid;
            $restoreq->course_shortname          = $courseqdata->shortname;
            $restoreq->restore_restorecatto      = 0;
            $restoreq->deleting                  = '';
            $restoreq->original_wwwroot          = $CFG->wwwroot;
            $restoreq->backup_version            = 2008030300;
            
            $dirq = "/temp/backup/".$restoreq->backup_unique_code;
            
            make_upload_directory($dirq);
            
            $xmlcontentf = "";
            
            $xmlcontent = reader_curlfile($readercfg->reader_serverlink.'/getfile.php?getid='.$quizzestoadd_['id'].'&pass='.$password[$quizzestoadd_['publisher']][$quizzestoadd_['level']]);
            
            foreach ($xmlcontent as $xmlcontent_) {
                $xmlcontentf .= $xmlcontent_;
            }
            
            
            $xmlcontentfnew = explode("<QUESTION_CATEGORY>", $xmlcontentf);  //Crope Default questions
            $xmlcontentfnewtext = "";
            $c = 0;
            foreach ($xmlcontentfnew as $xmlcontentfnew_) {
                if (!strstr($xmlcontentfnew_, "<NAME>Default for Test101</NAME>")) {
                    if ($c > 0) {
                        $xmlcontentfnewtext .= "<QUESTION_CATEGORY>".$xmlcontentfnew_;
                    } else {
                        $xmlcontentfnewtext .= $xmlcontentfnew_;
                    }
                    $c++;
                }
            }
            
            $xml_file = $CFG->dataroot.$dirq.'/moodle.xml';
            $restoreq->file = $CFG->dataroot.$dirq.'/moodle.zip'; 

            $fp = fopen($xml_file, "w+");
            fwrite($fp, $xmlcontentfnewtext);
            
            if (!$statusbackup = backup_zip ($restoreq)) {
                print_error('Can\'t create backup zip file.');
            }
            
            $filelist = list_directories_and_files ($CFG->dataroot.$dirq); 
            
            
            $xmlarray = xmlize($xmlcontentfnewtext);
            
            //---------------Check images ----------------//
            $checkimagesarr = $xmlarray['MOODLE_BACKUP']['#']['COURSE']['0']['#']['QUESTION_CATEGORIES']['0']['#']['QUESTION_CATEGORY'];
            while (list($key, $value) = each($checkimagesarr)) {
                if (@is_array($value['#']['QUESTIONS']['0']['#']['QUESTION'])) {
                    $dataquestarr = $value['#']['QUESTIONS']['0']['#']['QUESTION'];
                    while (list($key2, $value2) = each($dataquestarr)) {
                        if ($value2['#']['IMAGE']['0']['#']) {
                            //Upload Image-------//
                            $imglinks = explode("/",$value2['#']['IMAGE']['0']['#']);
                            end($imglinks);
                            unset($imglinks[key($imglinks)]);
                            $imglinks = implode("/", $imglinks);
                            make_upload_directory('reader/images');
                            $image = file_get_contents($readercfg->reader_serverlink.'/getfile_quiz_image.php?imagelink='.urlencode($value2['#']['IMAGE']['0']['#']));
                            $fp = @fopen($CFG->dataroot.'/reader/images/'.$value2['#']['IMAGE']['0']['#'], "w+");
                            @fwrite($fp, $image);
                            @fclose($fp);
                            //-------------------//
                        }
                    }
                }
            }
            //--------------------------------------------//
            unset($oldquizid);

            for ($i=0;$i<=100;$i++) {
                if (empty($oldquizid)) {
                    if (isset($xmlarray['MOODLE_BACKUP']['#']['COURSE']['0']['#']['QUESTION_CATEGORIES']['0']['#']['QUESTION_CATEGORY'][$i]['#']['CONTEXT']['0']['#']['INSTANCE']['0']['#']))
                        $oldquizid = $xmlarray['MOODLE_BACKUP']['#']['COURSE']['0']['#']['QUESTION_CATEGORIES']['0']['#']['QUESTION_CATEGORY'][$i]['#']['CONTEXT']['0']['#']['INSTANCE']['0']['#'];
                }
            }
            
            if (!empty($oldquizid)) {
                $module = $DB->get_record('modules', array('name' => 'quiz'));
                $newid  = $DB->get_record('course_modules', array('course' => $courseid, 'module' => $module->id, 'instance' => $quizbook[$quizzestoadd_['id']]));
                
                $reader_backup_ids              = new stdClass;
                $reader_backup_ids->backup_code = $restoreq->backup_unique_code;
                $reader_backup_ids->table_name  = 'course_modules';
                $reader_backup_ids->old_id      = $oldquizid;
                $reader_backup_ids->new_id      = $newid->id;
                $reader_backup_ids->info        = 's:0:"";';
                
                $DB->insert_record('reader_backup_ids', $reader_backup_ids);
                
                $restoreq->mods['quiz']->restore  = 1;
                $restoreq->mods['quiz']->userinfo = 0;
                $restoreq->mods['quiz']->granular = 1;
                $restoreq->mods['quiz']->instances[$reader_backup_ids->old_id]->restore  = 1;
                $restoreq->mods['quiz']->instances[$reader_backup_ids->old_id]->userinfo = 0;
                $restoreq->mods['quiz']->instances[$reader_backup_ids->old_id]->restored_as_course_module = $reader_backup_ids->new_id;

                $restoreq->course_startdateoffset = -1900800;
                $restoreq->restore_restorecatto   = 3;
                $restoreq->rolesmapping           = array(3=>3,4=>4);
            
                print_string('process_addquestion', 'reader', $quizzestoadd_['title']);
            
                echo $OUTPUT->box_start('generalbox');
                restore_create_questions($restoreq, $xml_file);
                echo $OUTPUT->box_end();

                //Add questions to quiz
                $quizzesneeds = explode(',', $xmlarray['MOODLE_BACKUP']['#']['COURSE']['0']['#']['MODULES']['0']['#']['MOD']['0']['#']['QUESTIONS']['0']['#']);
                
                $questiondata = array();
                
                foreach ($quizzesneeds as $quizzesneed) {
                    if ($quizzesneed != 0) {
                        $questionnewid = backup_getid($restoreq->backup_unique_code, 'question', $quizzesneed);
                        if (!empty($questionnewid->new_id)) {
                            $questiondata[$quizzesneed] = $questionnewid->new_id;
                        }
                    }
                }
        
                $questiongradedata = array();
                
                foreach ($xmlarray['MOODLE_BACKUP']['#']['COURSE']['0']['#']['MODULES']['0']['#']['MOD']['0']['#']['QUESTION_INSTANCES']['0']['#']['QUESTION_INSTANCE'] as $findgrade) {
                    $questiongradedata[$findgrade['#']['QUESTION']['0']['#']] = $findgrade['#']['GRADE']['0']['#'];
                }
        
                $DB->set_field('quiz', 'questions', implode(',', $questiondata).',0', array('id' => $quizbook[$quizzestoadd_['id']]));
                
                foreach ($questiondata as $key => $value) {
                    $grade            = new stdClass;
                    $grade->quiz      = $quizbook[$quizzestoadd_['id']];
                    $grade->question  = $value;
                    $grade->grade     = $questiongradedata[$key];
                    $maxquizgrade     += $grade->grade;
                    
                    $DB->insert_record ('quiz_question_instances', $grade);
                }
                
                $DB->set_field('quiz', 'sumgrades', $maxquizgrade, array('id' => $quizbook[$quizzestoadd_['id']]));
                //------------------------------------//
                
                reader_removedirrec($CFG->dataroot.$dirq);
                
                //Upload Image-------//
                if (!empty($quizzestoadd_['image'])) {
                    make_upload_directory('reader/images');
                    $image = file_get_contents($readercfg->reader_serverlink.'/getfile.php?imageid='.$quizzestoadd_['id']);
                    $fp = @fopen($CFG->dataroot.'/reader/images/'.$quizzestoadd_['image'], "w+");
                    @fwrite($fp, $image);
                    @fclose($fp);
                }
                //-------------------//
                
                $readerpublisher                    = new stdClass;
                $readerpublisher->publisher         = $quizzestoadd_['publisher'];
                $readerpublisher->level             = $quizzestoadd_['level'];
                $readerpublisher->difficulty        = $quizzestoadd_['difficulty'];
                $readerpublisher->name              = $quizzestoadd_['title']; 
                $readerpublisher->words             = $quizzestoadd_['words']; 
                $readerpublisher->genre             = $quizzestoadd_['genre']; 
                $readerpublisher->fiction           = $quizzestoadd_['fiction']; 
                $readerpublisher->maxtime           = $quizzestoadd_['maxtime']; 
                $readerpublisher->sametitle         = $quizzestoadd_['sametitle']; 
                $readerpublisher->quizid            = $quizbook[$quizzestoadd_['id']];
                $readerpublisher->image             = $quizzestoadd_['image'];
                $readerpublisher->length            = $quizzestoadd_['length'];
                $readerpublisher->hidden            = 0;
                $readerpublisher->time              = time();
                
                if (empty($readerpublisher->genre)) unset($readerpublisher->genre);
                if (empty($readerpublisher->fiction)) unset($readerpublisher->fiction);
                if (empty($readerpublisher->maxtime)) unset($readerpublisher->maxtime);
                
                if ($data = $DB->get_record("reader_publisher", array("name"=>addslashes($quizzestoadd_['title']), "publisher"=>$quizzestoadd_['publisher'], "level"=>$quizzestoadd_['level']))) {
                    $add                   = new stdClass();
                    $add->genre            = $quizzestoadd_['genre'];
                    $add->fiction          = $quizzestoadd_['fiction'];
                    $add->difficulty       = $quizzestoadd_['difficulty'];
                    $add->words            = $quizzestoadd_['words'];
                    $add->quizid           = $quizbook[$quizzestoadd_['id']];
                    $add->length           = $quizzestoadd_['length'];
                    $add->time             = time();
                    $add->id               = $data->id;
                    
                    $DB->update_record ('reader_publisher', $add);
                    
                    $newquizid             = $data->id;
                } else {
                    $newquizid = $DB->insert_record ('reader_publisher', $readerpublisher);
                } 
            } else {
                echo html_writer::tag('div', 'Quiz have problems, sorry');
            }
            
        }
        
        $DB->set_field("reader", "usecourse",  $courseid, array( "id" => $reader->id));  // Course should be same
        
        if ($readercfg->reader_last_update == 1) 
            $DB->set_field("config_plugins", "value", time(), array("name" => "reader_last_update"));
        
        if (!empty($end)) {
            echo html_writer::start_tag('center');
            echo $OUTPUT->single_button(new moodle_url('/mod/reader/dlquizzes.php', array("id" => $id)), "Return to Quiz Selection Screen");
            echo $OUTPUT->single_button(new moodle_url('/mod/reader/admin.php', array("id" => $id, "a"=>"admin")), "Continue");
            echo html_writer::end_tag('center');
        }
    }
    
    if (is_file($CFG->dirroot."/blocks/readerview/cron.php")) {
        file_get_contents($CFG->wwwroot."/blocks/readerview/cron.php");
    }
    
    if (empty($quizid)) {
        echo $OUTPUT->box_end();
        echo $OUTPUT->footer();
    }
    
