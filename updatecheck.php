<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin

    require_once("../../config.php");
    require_once("lib.php");

    $id                = required_param('id', PARAM_INT); 
    $a                 = optional_param('a', NULL, PARAM_CLEAN); 
    $quiz              = optional_param('quiz', NULL, PARAM_CLEAN); 
    $newquizzes        = optional_param('newquizzes', NULL, PARAM_CLEAN); 
    $updatedquizzes    = optional_param('updatedquizzes', NULL, PARAM_CLEAN); 
    $quiz              = optional_param('updatedquizzes', NULL, PARAM_CLEAN); 
    $newquizzesto      = optional_param('newquizzesto', NULL, PARAM_CLEAN); 
    $json              = optional_param('json', NULL, PARAM_CLEAN); 
    $checker           = optional_param('checker', 0, PARAM_INT); 
    
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
    
    $readercfg = get_config('reader');
    
    $context = get_context_instance(CONTEXT_COURSE, $course->id);
    $contextmodule = get_context_instance(CONTEXT_MODULE, $cm->id);
    if (!has_capability('mod/reader:manage', $contextmodule)) { 
        error("You should be Admin");
    }

    add_to_log($course->id, "reader", "Download Quizzes Process", "dlquizzes.php?id=$id", "$cm->instance");
    
    $PAGE->set_url('/mod/reader/updatecheck.php', array('id' => $cm->id));
    
    $title = $course->shortname . ': ' . format_string($reader->name);
    $PAGE->set_title($title);
    $PAGE->set_heading($course->fullname);
    $PAGE->requires->js('/mod/reader/js/hide.js', true);
    $PAGE->requires->js('/mod/reader/js/jquery-1.4.2.min.js', true);
    $PAGE->requires->js('/mod/reader/js/dlquizzes.js');
    
    $PAGE->requires->css('/mod/reader/css/main.css');
    
    echo $OUTPUT->header();
    
    require_once ('tabs_dl.php');
                      
    echo $OUTPUT->box_start('generalbox');
      
    if ($checker == 1) {
        echo html_writer::start_tag('center');
        print_string("lastupdatedtime", "reader", date("d M Y", $readercfg->reader_last_update));
        echo html_writer::empty_tag('br');
        echo html_writer::link(new moodle_url('/mod/reader/updatecheck.php', array('id'=>$id)), 'YES');
        echo ' / ';
        echo html_writer::link(new moodle_url('/mod/reader/admin.php', array('id'=>$id, 'a'=>'admin')), 'NO');
        echo html_writer::end_tag('center');

        echo $OUTPUT->box_end();
        
        echo $OUTPUT->footer();
        die();
    }

/** Find all readers **/
    $readersarr    = $DB->get_records("reader");
    $r             = array();
    $datareaders   = array();
    $jdata         = array();
    while (list($key,$reader) = each($readersarr)) {
        $datareaders[$reader->id]['ignordate']       = $reader->ignordate;
        $usersarr                                    = $DB->get_records_sql("SELECT DISTINCT userid FROM {reader_attempts} WHERE reader= ? and timestart >= ?", array($reader->id, $reader->ignordate));
        $datareaders[$reader->id]['totalusers']      = count($usersarr);
        $attemptsarr                                 = $DB->get_records_sql("SELECT id FROM {reader_attempts} WHERE reader= ? and timestart >= ?", array($reader->id, $reader->ignordate));
        $datareaders[$reader->id]['attemptsaver']    = round(count($attemptsarr) / count($usersarr), 1);
        $datareaders[$reader->id]['course']          = $reader->course;
        $course                                      = $DB->get_record('course', array('id' => $reader->course));
        $datareaders[$reader->id]['short_name']      = $course->shortname;
        $r[$reader->id]['course']                    = $reader->course;
        $r[$reader->id]['short_name']                = $course->shortname;
    }
    /**=============**/
    
    $publishers = $DB->get_records_sql("SELECT * FROM {reader_publisher} WHERE hidden != 1");
    
    while (list($key,$book) = each($publishers)) {
        if ($book->time < 10) $book->time = $readercfg->reader_last_update;

        $attempts = $DB->get_records_sql("SELECT id,passed,bookrating,reader FROM {reader_attempts} WHERE quizid = ?", array($book->id));
        unset($rate,$c);
        $c    = array();
        $data = array();
        $rate = array();
        if (is_array($attempts)) {
            while(list($key2,$attempt) = each($attempts)) {
                @$c[$attempt->reader]++;
                if ($attempt->passed == 'TRUE' || $attempt->passed == 'true') {
                    @$data[$attempt->reader][$book->image]['true']++;
                } else if ($attempt->passed == 'credit') {
                    @$data[$attempt->reader][$book->image]['credit']++;
                } else {
                    @$data[$attempt->reader][$book->image]['false']++;
                }
                @$rate[$attempt->reader] = $attempt->bookrating + $rate[$attempt->reader];
            }
        } else {
            $data[0][$book->image]['true']       = 0;
            $data[0][$book->image]['false']      = 0;
            $data[0][$book->image]['credit']     = 0;
            $data[0][$book->image]['rate']       = 0;
            $data[0][$book->image]['course']     = 1;
            $data[0][$book->image]['time']       = $book->time;
            $data[0][$book->image]['short_name'] = 'NOTUSED';
        }
        
        reset($readersarr);
        while (list($key,$reader) = each($readersarr)) {
            if (isset($data[$reader->id][$book->image]['true']) || isset($data[$reader->id][$book->image]['credit']) || isset($data[$reader->id][$book->image]['false'])) {
                $data[$reader->id][$book->image]['rate']        = round($rate[$reader->id] / $c[$reader->id],1);
                $data[$reader->id][$book->image]['course']      = $r[$reader->id]['course'];
                $data[$reader->id][$book->image]['time']        = $book->time;
                $data[$reader->id][$book->image]['short_name']  = $r[$reader->id]['short_name'];
            }
        }
    }

    $jdata['userlogin']  = $readercfg->reader_serverlogin;
    $jdata['lastupdate'] = $readercfg->reader_last_update;
    $jdata['books']      = $data;
    $jdata['readers']    = $datareaders;

    $json = json_encode($jdata);

    $postdata = http_build_query(
        array(
            'json' => $json
        )
    );

    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );

    $context  = stream_context_create($opts);

    $result = file_get_contents($readercfg->reader_serverlink."/update_quizzes.php", false, $context);

    $needqudate = json_decode(stripslashes($result));

    unset($data);

    if (is_object($needqudate)) {
        echo $OUTPUT->box_start('generalbox');
        
        $o  = "";
        $o .= html_writer::start_tag('form', array('action'=>new moodle_url('/mod/reader/dlquizzes.php', array('id'=>$id)), 'method'=>'post', 'id'=>'mform1'));
        $o .= html_writer::start_tag('div', array('class'=>'w-600'));
        $o .= html_writer::link('#', 'Show All', array('onclick' => 'myexpandall();return false;'));
        $o .= ' / ';
        $o .= html_writer::link('#', 'Hide All', array('onclick' => 'mycollapseall();return false;'));
        $o .= html_writer::empty_tag('br');

        //vivod
        $cp = 0;
        $allquestionscount = 0;
        $newcheckboxes = "";
        $updatedcheckboxes = "";
        
        while(list($key, $value) = each($needqudate)) {
            while(list($key2, $value2) = each($value)) {
                while(list($key3, $value3) = each($value2)) {
                    $allquestionscount++;
                    $checkboxdatapublishersreal[$key][] = $key3;
                    $checkboxdatalevelsreal[$key][$key2][] = $key3;
                    
                    $checkboxdatapublishers[$key][] = $allquestionscount;
                    $checkboxdatalevels[$key][$key2][] = $allquestionscount;
                    $quizzescountid[$key3] = $allquestionscount;
                    
                    if (strstr($value3, "UPDATE::")) 
                        $updatedcheckboxes .= $allquestionscount . ",";
                 
                    if (strstr($value3, "NEW::"))
                        $newcheckboxes .= $allquestionscount . ",";
                }
            }
        }
        
        $updatedcheckboxes = substr($updatedcheckboxes,0,-1);
        $newcheckboxes     = substr($newcheckboxes,0,-1);
        
        $o .= html_writer::start_tag('div');
        
        if (!empty($newcheckboxes))
            $o .= html_writer::empty_tag('input', array('type'=>'button', 'name'=>'selectnew', 'value'=>'Select all new', 'onclick'=>'myexpandall();setcheckedbyid(\''.$newcheckboxes.'\');return false;'));
        
        if (!empty($updatedcheckboxes))
            $o .= html_writer::empty_tag('input', array('type'=>'button', 'name'=>'selectupdated', 'value'=>'Select all updated', 'onclick'=>'myexpandall();setcheckedbyid(\''.$updatedcheckboxes.'\');return false;'));
        
        $o .= html_writer::empty_tag('input', array('type'=>'button', 'name'=>'selectupdated', 'value'=>'Clear all selections', 'onclick'=>'uncheckall();return false;'));
        $o .= html_writer::end_tag('div');
        
        reset($needqudate);
        if (is_object($needqudate)) {
            foreach ($needqudate as $publiher => $datas) {
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
                    
                    $o .= html_writer::tag('b', $level, array('class'=>'dl-title'));
                    
                    $o .= html_writer::start_tag('span', array('id'=>'comments_'.$cp));
                    $o .= html_writer::empty_tag('input', array('type'=>'checkbox', 'name'=>'installall['.$cp.']', 'onclick'=>'setChecked(this,'.$checkboxdatalevels[$publiher][$level][0].','.end($checkboxdatalevels[$publiher][$level]).')', 'value'=>''));
                    $o .= html_writer::tag('span', 'Install All', array('id'=>'seltext_'.$cp, 'class'=>'ml-10'));
                    $o .= html_writer::tag('div', '', array('class'=>'mt-10'));

                    foreach ($quizzesdata as $quizid => $quiztitle) {
                        if (strstr($quiztitle, "NEW::")) {$quiztitle = substr($quiztitle, 5); $mark = 'New';}
                        if (strstr($quiztitle, "UPDATE::")) {$quiztitle = substr($quiztitle, 8); $mark = 'Updated';}
                    
                        $o .= html_writer::start_tag('div', array('class'=>'pl-20'));
                        $o .= html_writer::tag('span', $mark, array('class'=>'dl-mark'));
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
        
        $o .= html_writer::end_tag('div');
        $o .= html_writer::end_tag('form');
        
        echo $o;
        
        echo $OUTPUT->box_end();
    } else {
        echo $OUTPUT->box_start('generalbox');
        
        print_string("therehavebeennonewquizzesorupdates", "reader");
        
        echo $OUTPUT->box_end();
    }

    echo $OUTPUT->box_end();
    
    if (isset($cp))
        echo html_writer::script('var vh_numspans = '.$cp.';mycollapseall(vh_numspans);');

    echo $OUTPUT->footer();

    $DB->set_field("config_plugins", "value", time(), array("name" => "reader_last_update"));
    
    
    