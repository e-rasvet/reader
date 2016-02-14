<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin

    require_once("../../config.php");
    require_once($CFG->dirroot."/mod/reader/lib.php");
    require_once($CFG->dirroot."/mod/reader/lib/pclzip/pclzip.lib.php");
    require_once($CFG->dirroot."/mod/reader/lib/backup/restorelib.php");
    require_once($CFG->dirroot."/mod/reader/lib/backup/backuplib.php");
    require_once($CFG->dirroot."/mod/reader/lib/backup/lib.php");
    require_once($CFG->dirroot."/mod/reader/lib/question/restorelib.php");
    
    $id                = required_param('id', PARAM_INT); 
    $a                 = optional_param('a', NULL, PARAM_CLEAN); 
    $quiz              = optional_param_array('quiz', NULL, PARAM_INT); 
    $password          = optional_param('password', NULL, PARAM_CLEAN); 
    $sectionchoosing   = optional_param('sectionchoosing', 1, PARAM_INT); 
    $section           = optional_param('section', 1, PARAM_INT); 
    $courseid          = optional_param('courseid', 0, PARAM_INT); 
    
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

    
    $context = get_context_instance(CONTEXT_COURSE, $course->id);
    $contextmodule = get_context_instance(CONTEXT_MODULE, $cm->id);
    if (!has_capability('mod/reader:manage', $contextmodule)) { 
        error("You should be \"Edit\" Teacher");
    }
    
    $readercfg = get_config('reader');

    add_to_log($course->id, "reader", "Download Quizzes Process", "dlquizzes_proccess-noq.php?id=$id", "$cm->instance");
    
// Initialize $PAGE, compute blocks
    $PAGE->set_url('/mod/reader/dlquizzes_proccess-noq.php', array('id' => $cm->id));
    
    $title = $course->shortname . ': ' . format_string($reader->name);
    $PAGE->set_title($title);
    $PAGE->set_heading($course->fullname);
    $PAGE->requires->js('/mod/reader/js/jquery-1.4.2.min.js', true);
    
                      
    echo $OUTPUT->header();
    echo $OUTPUT->box_start('generalbox');
    
    $xmlquizzesfile = reader_file($readercfg->reader_serverlink."/index-noq.php?a=quizzes&login={$readercfg->reader_serverlogin}&password={$readercfg->reader_serverpassword}", array('password'=>$password, 'quiz'=>$quiz, 'upload'=>'true'));
    
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

    foreach ($quizzestoadd as $quizzestoadd_) {
        if ($reader->usecourse) { 
            $tocourseid = $reader->usecourse; 
        } else { 
            $tocourseid = $course->id; 
        }
        
        make_upload_directory('reader/images');
        $image = file_get_contents($readercfg->reader_serverlink.'/getfilenoq.php?imageid='.$quizzestoadd_['id']);
        $fp = @fopen($CFG->dataroot.'/reader/images/'.$quizzestoadd_['image'], "w+");
        @fwrite($fp, $image);
        @fclose($fp);
        
        $readerpublisher                    = new object;
        $readerpublisher->publisher         = $quizzestoadd_['publisher'];
        $readerpublisher->level             = $quizzestoadd_['level'];
        $readerpublisher->difficulty        = $quizzestoadd_['difficulty'];
        $readerpublisher->name              = addslashes($quizzestoadd_['title']);
        $readerpublisher->words             = addslashes($quizzestoadd_['words']); // quizwords
        $readerpublisher->genre             = addslashes($quizzestoadd_['genre']); // quizgenre
        $readerpublisher->fiction           = addslashes($quizzestoadd_['fiction']); // quizfiction
        $readerpublisher->maxtime           = addslashes($quizzestoadd_['maxtime']); // quizmaxtime
        $readerpublisher->sametitle         = addslashes($quizzestoadd_['sametitle']); // sametitle
        $readerpublisher->image             = $quizzestoadd_['image'];
        $readerpublisher->length            = $quizzestoadd_['length'];
        $readerpublisher->hidden            = 0;
        
        if (empty($readerpublisher->genre)) unset($readerpublisher->genre);
        if (empty($readerpublisher->fiction)) unset($readerpublisher->fiction);
        if (empty($readerpublisher->maxtime)) unset($readerpublisher->maxtime);
        
        
        if ($data = $DB->get_record("reader_noquiz", array("name"=>addslashes($quizzestoadd_['title']), "publisher"=>$quizzestoadd_['publisher'], "level"=>$quizzestoadd_['level']))) {
            $add                   = new stdClass();
            $add->genre            = $quizzestoadd_['genre'];
            $add->fiction          = $quizzestoadd_['fiction'];
            $add->difficulty       = $quizzestoadd_['difficulty'];
            $add->words            = $quizzestoadd_['words'];
            $add->length           = $quizzestoadd_['length'];
            $add->id               = $data->id;
                    
            $DB->update_record ('reader_noquiz', $add);
                    
            $newquizid             = $data->id;
        } else {
            $newquizid = $DB->insert_record ('reader_noquiz', $readerpublisher);
        }
    }

    
    echo html_writer::start_tag('center');
    echo $OUTPUT->single_button(new moodle_url('/mod/reader/dlquizzesnoq.php', array("id" => $id)), "Return to Quiz Selection Screen");
    echo $OUTPUT->single_button(new moodle_url('/mod/reader/admin.php', array("id" => $id, "a"=>"admin")), "Continue");
    echo html_writer::end_tag('center');
    
    echo $OUTPUT->box_end();
    echo $OUTPUT->footer();
    