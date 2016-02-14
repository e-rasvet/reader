<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin

    require_once('../../config.php');
    require_once('lib.php');
    require_once($CFG->libdir.'/tablelib.php');
    require_once($CFG->libdir.'/xmlize.php');
    require_once($CFG->dirroot.'/course/moodleform_mod.php');
    
    
    $id                = required_param('id', PARAM_INT); 
    $quiz              = optional_param_array('quiz', NULL, PARAM_INT); 
    $installall        = optional_param_array('installall', NULL, PARAM_INT); 
    $password          = optional_param('password', NULL, PARAM_CLEAN); 
    $second            = optional_param('second', NULL, PARAM_CLEAN); 
    $step              = optional_param('step', NULL, PARAM_CLEAN); 
    
    if (!$cm = get_coursemodule_from_id('reader', $id)) {
        print_error('invalidcoursemodule');
    }
    if (!$course = $DB->get_record('course', array('id' => $cm->course))) {
        print_error("coursemisconf");
    }
    if (!$reader = $DB->get_record('reader', array('id' => $cm->instance))) {
        print_error('invalidcoursemodule');
    }
    if (!is_dir($CFG->dirroot.'/question/type/ordering')){
      print_error('Ordering question type is missign. Please install it the first.');
    }

    require_login($course, true, $cm);

    add_to_log($course->id, "reader", "Download Quizzes", "dlquizzesnoq.php?id=$id", "$cm->instance");
    
    $readercfg = get_config('reader');
    
// Initialize $PAGE, compute blocks
    $PAGE->set_url('/mod/reader/dlquizzesnoq.php', array('id' => $cm->id));
    
    $title = $course->shortname . ': ' . format_string($reader->name);
    $PAGE->set_title($title);
    $PAGE->set_heading($course->fullname);
    $PAGE->requires->js('/mod/reader/js/hide.js', true);
    $PAGE->requires->js('/mod/reader/js/jquery-1.4.2.min.js', true);
    $PAGE->requires->js('/mod/reader/js/dlquizzes.js');
    
    $PAGE->requires->css('/mod/reader/css/main.css');
    
    echo $OUTPUT->header();
    
    if (!function_exists('file')) { 
       error("FILE function unavailable. "); 
    }

    $listofpublishers = reader_curlfile("{$readercfg->reader_serverlink}/index-noq.php?a=publishers&login={$readercfg->reader_serverlogin}&password={$readercfg->reader_serverpassword}");
    
    $listofpublishers = xmlize(reader_makexml($listofpublishers));
    
    foreach ($listofpublishers['myxml']['#']['item'] as $listofpublisher) {
        $quizzes[$listofpublisher['@']['publisher']][$listofpublisher['@']['level']][$listofpublisher['@']['id']] = $listofpublisher['#'];
        $needpassword[$listofpublisher['@']['publisher']][$listofpublisher['@']['level']] = $listofpublisher['@']['needpass'];
    }
    
    $allquestionscount = 0;
    $printerrormessage = false;
    
    foreach ($quizzes as $key =>$value) {
        foreach ($value as $key2 => $value2) {
            foreach ($value2 as $key3 => $value3) {
                $allquestionscount++;
                $checkboxdatapublishersreal[$key][] = $key3;
                $checkboxdatalevelsreal[$key][$key2][] = $key3;
                
                $checkboxdatapublishers[$key][] = $allquestionscount;
                $checkboxdatalevels[$key][$key2][] = $allquestionscount;
                $quizzescountid[$key3] = $allquestionscount;
                
                /*  FOR LOGIN AND PASS CHECKING  */
                if (strstr($value3, "You should be student")) {
                    $printerrormessage = true;
                }
            }
        }
    }
    
    require_once ('tabs_dl.php');
    
    $context = get_context_instance(CONTEXT_COURSE, $course->id);
    $contextmodule = get_context_instance(CONTEXT_MODULE, $cm->id);
    if (!has_capability('mod/reader:manage', $contextmodule)) { 
        error("You should be \"Edit\" Teacher");
    }
    
    if ($installall) {
        foreach ((array)$installall as $installall_) {
            $installalldata = explode (",", $installall_);
            foreach ($installalldata as $installalldata_) {
                if (!empty($installalldata_)) {
                    $quiz[] = $installalldata_;
                }
            }
        }
        
        $quiz = array_unique($quiz); 
    }
    
    echo $OUTPUT->box_start('generalbox');
    
    /*  FOR LOGIN AND PASS CHECKING  */
    if ($printerrormessage) {
        echo "{$readercfg->reader_serverlink}/index-noq.php?a=publishers&login={$readercfg->reader_serverlogin}&password={$readercfg->reader_serverpassword} <br />";
        error(" In order to download quizzes, you need to be registered on the  \"Moodle Reader Users\" course on MoodleReader.org ( <a href=\"http://moodlereader.org/moodle/course/view.php?id=15\">http://moodlereader.org/moodle/course/view.php?id=15</a> ),  Please contact the system administrator ( admin@moodlereader.org ) to register yourself, providing information on your school, your position, the grade level of your students and the approximate number of students who will be using the system.");
        echo $OUTPUT->box_end();
        die();
    }
    
    

    echo $OUTPUT->box_start('generalbox');
    
    $o  = "";
    $o .= html_writer::start_tag('form', array('action'=>new moodle_url('/mod/reader/dlquizzes_proccess-noq.php', array('id'=>$id)), 'method'=>'post', 'id'=>'mform1'));
    $o .= html_writer::start_tag('div', array('class'=>'w-600'));
    $o .= html_writer::link('#', 'Show All', array('onclick'=>'myexpandall();return false;'));
    $o .= ' / ';
    $o .= html_writer::link('#', 'Hide All', array('onclick'=>'mycollapseall();return false;'));
    $o .= html_writer::empty_tag('br');

    $cp = 0;

    if ($quizzes) {
        foreach ($quizzes as $publiher => $datas) {
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

            foreach ($datas as $level => $quizzesdata) {
                $cp++;
                
                $o .= html_writer::start_tag('div', array('class'=>'dl-page-box1'));
                $o .= html_writer::start_tag('a', array('href'=>'#', 'onclick'=>'mytoggle(\'comments_'.$cp.'\');return false'));
                $o .= html_writer::start_tag('span', array('id'=>'comments_'.$cp.'indicator'));
                $o .= html_writer::empty_tag('img', array('src'=>$reader_images['open'], 'alt'=>'Opened folder'));
                $o .= html_writer::end_tag('span');
                $o .= html_writer::end_tag('a');

                if ($needpassword[$publiher][$level] == "true") {
                    $o .= html_writer::empty_tag('img', array('src'=>$reader_images['pw'], 'width'=>'23', 'height'=>'15', 'alt'=>'Need password'));
                }
                
                $o .= html_writer::tag('b', $level, array('class'=>'dl-title'));
                
                $o .= html_writer::start_tag('span', array('id'=>'comments_'.$cp));
                $o .= html_writer::empty_tag('input', array('type'=>'checkbox', 'name'=>'installall['.$cp.']', 'onclick'=>'setChecked(this,'.$checkboxdatalevels[$publiher][$level][0].','.end($checkboxdatalevels[$publiher][$level]).')', 'value'=>''));
                $o .= html_writer::tag('span', 'Install All', array('id'=>'seltext_'.$cp, 'class'=>'ml-10'));
                $o .= html_writer::tag('div', '', array('class'=>'mt-10'));

                foreach ($quizzesdata as $quizid => $quiztitle) {
                    $o .= html_writer::start_tag('div', array('class'=>'pl-20'));
                    $o .= html_writer::empty_tag('input', array('type'=>'checkbox', 'name'=>'quiz[]', 'id'=>'quiz_'.$quizzescountid[$quizid], 'value'=>$quizid));
                    $o .= html_writer::tag('span', $quiztitle, array('class'=>'ml-10'));
                    $o .= html_writer::end_tag('div');
                }
                $o .= html_writer::end_tag('span');
                $o .= html_writer::end_tag('div');
            }
            $o .= html_writer::end_tag('span');
        }
        
        $o .= html_writer::start_tag('div', array('class'=>'dl-page-install'));
        $o .= html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'downloadquizzes', 'value'=>'Install Quizzes'));
        $o .= html_writer::end_tag('div');
    }
    
    $o .= html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'step', 'value'=>'1'));
    $o .= html_writer::end_tag('div');
    $o .= html_writer::end_tag('form');
    
    echo $o;
    
    echo $OUTPUT->box_end();
    
    
    echo $OUTPUT->box_end();
    
    if (isset($cp))
        echo html_writer::script('var vh_numspans = '.$cp.';mycollapseall(vh_numspans);');
    
    echo $OUTPUT->footer();
