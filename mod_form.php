<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->dirroot . '/course/moodleform_mod.php');

class mod_reader_mod_form extends moodleform_mod {
    function definition() {
        global $COURSE, $CFG, $form, $DB, $PAGE;
        
        $readercfg = get_config('reader');
        
        $mform    =& $this->_form;
        
        $context = get_context_instance(CONTEXT_COURSE, $COURSE->id);
        if (has_capability('mod/reader:cancreateinstance', $context) || $readercfg->reader_editingteacherrole == 0) {
            
        } else {
            notify(get_string('nothavepermissioncreateinstance', 'reader'));
            die();
        }
        

//-------------------------------------------------------------------------------
    /// Adding the "general" fieldset, where all the common settings are showed
        $mform->addElement('header', 'general', get_string('general', 'form'));
    /// Adding the standard "name" field
        $mform->addElement('text', 'name', get_string('name'), array('size'=>'64'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
    /// Adding the optional "intro" and "introformat" pair of fields
        $this->add_intro_editor(false, get_string('summary'));
        
//-------------------------------------------------------------------------------

        $mform->addElement('header', 'timinghdr', get_string('timing', 'form'));
        $mform->addElement('date_time_selector', 'timeopen', get_string('quizopen', 'quiz'), array('optional'=>true));

        $mform->addElement('date_time_selector', 'timeclose', get_string('quizclose', 'quiz'), array('optional'=>true));


        $timelimitgrp=array();
        $timelimitgrp[] = &$mform->createElement('text', 'timelimit');
        $timelimitgrp[] = &$mform->createElement('checkbox', 'timelimitenable', '', get_string('enable'));
        $mform->addGroup($timelimitgrp, 'timelimitgrp', get_string('timelimitmin', 'quiz'), array(' '), false);
        $mform->setType('timelimit', PARAM_TEXT);
        $timelimitgrprules = array();
        $timelimitgrprules['timelimit'][] = array(null, 'numeric', null, 'client');
        $mform->addGroupRule('timelimitgrp', $timelimitgrprules);
        $mform->disabledIf('timelimitgrp', 'timelimitenable');
        
        if (isset($readercfg->reader_fix_timelimit))
            $mform->setAdvanced('timelimitgrp', $readercfg->reader_fix_timelimit);

        if (isset($readercfg->reader_timelimit))
            $mform->setDefault('timelimit', $readercfg->reader_timelimit);
//-------------------------------------------------------------------------------
        
//-------------------------------------------------------------------------------
        $mform->addElement('header', 'general', get_string('setings', 'reader'));
        $mform->addElement('select', 'pointreport', get_string('s_fullpointreport', 'reader'), array('0'=>'No', '1'=>'Yes'));
        $mform->addElement('select', 'individualbooks', get_string('s_individualbooks', 'reader'), array('0'=>'No', '1'=>'Yes'));
        $mform->addElement('select', 'levelcheck', get_string('s_levelrestrictionfeature', 'reader'), array('0'=>'No', '1'=>'Yes'));
        $mform->addElement('select', 'reportwordspoints', get_string('s_reportwordspoints', 'reader'), array('0'=>'Show Word Count only', '1'=>'Show Points only', '2'=>'Show both Points and Word Count'));
        $mform->addElement('select', 'wordsprogressbar', get_string('s_wordsprogressbar', 'reader'), array('0'=>'Hide', '1'=>'Show'));
        $percentcorrectforbookform=array();
        $percentcorrectforbookform[] =& $mform->createElement('text', 'percentforreading', get_string('s_percentcorrectforbook', 'reader'), array('size'=>'10'));
        $percentcorrectforbookform[] =& $mform->createElement('static', 'description', '', '%');
        $mform->addGroup($percentcorrectforbookform, 'percentcorrectforbookformgroup', get_string('s_percentcorrectforbook', 'reader'), ' ', false);
        $mform->addElement('select', 'questionmark', get_string('s_questionmark', 'reader'), array('0'=>'No', '1'=>'yes'));
        $mform->addElement('select', 'bookcovers', get_string('s_bookcovers', 'reader'), array('0'=>'No', '1'=>'yes'));
        $mform->addElement('select', 'checkbox', get_string('s_checkbox', 'reader'), array('0'=>'No', '1'=>'yes'));
        $mform->addElement('select', 'attemptsofday', get_string('s_quizfordays', 'reader'), array('0'=>'Off', '1'=>'1', '2'=>'2', '3'=>'3'));
        $mform->addElement('text', 'goal', get_string('s_totalpointsgoal', 'reader'), array('size'=>'10'));
        $mform->addElement('text', 'nextlevel', get_string('s_nextlevel', 'reader'), array('size'=>'10'));
        $mform->addElement('text', 'quizpreviouslevel', get_string('s_selectquizes', 'reader'), array('size'=>'10'));
        $mform->addElement('text', 'quiznextlevel', get_string('s_quiznextlevel', 'reader'), array('size'=>'10'));
        $mform->addElement('text', 'promotionstop', get_string('s_nopromotion', 'reader'), array('size'=>'10'));
        $ignorform=array();
        $ignorform[] =& $mform->createElement('date_selector', 'ignordate', '', array('startyear'=>2002, 'stopyear'=>date("Y", time()), 'applydst'=>true));
        $ignorform[] =& $mform->createElement('static', 'description', '', get_string('s_ignor_2', 'reader'));
        $mform->addGroup($ignorform, 'ignor', get_string('s_ignor_1', 'reader'), ' ', false);
        
        $courses = get_courses();
        foreach ($courses as $courses_) {
            if ($courses_->id != 1) {
                $cousesarray[$courses_->id] = $courses_->fullname;
            }
        }
        
        $mform->addElement('select', 'usecourse', get_string('s_selectcourse', 'reader'), $cousesarray);
        
        
//-------------------------------------------------------------------------------
        $mform->addElement('header', 'security', get_string('security', 'form'));
        
        $securitymeasuresarray=array();
        $securitymeasuresarray[] = &MoodleQuickForm::createElement('radio', 'secmeass', '', get_string('s_off', 'reader'), 0);
        $securitymeasuresarray[] = &MoodleQuickForm::createElement('radio', 'secmeass', '', get_string('s_anywhere', 'reader'), 1);
        $securitymeasuresarray[] = &MoodleQuickForm::createElement('radio', 'secmeass', '', get_string('s_computers', 'reader'), 2);
        $mform->addGroup($securitymeasuresarray, 'securitymeasuresarray', get_string('s_securitymeasures', 'reader'), array(' '), false);

        $mform->addElement('selectyesno', 'popup', get_string("popup", "quiz"));

        if (isset($readercfg->reader_fix_popup))
            $mform->setAdvanced('popup', $readercfg->reader_fix_popup);
        
        if (isset($readercfg->reader_popup))
            $mform->setDefault('popup', $readercfg->reader_popup);

        $mform->addElement('passwordunmask', 'password', get_string("requirepassword", "quiz"));
        $mform->setType('password', PARAM_TEXT);


        if (isset($readercfg->reader_fix_password))
            $mform->setAdvanced('password', $readercfg->reader_fix_password);
        
        if (isset($readercfg->reader_password))
            $mform->setDefault('password', $readercfg->reader_password);

        $mform->addElement('text', 'subnet', get_string("requiresubnet", "quiz"));
        $mform->setType('subnet', PARAM_TEXT);

        if (isset($readercfg->reader_fix_subnet))
            $mform->setAdvanced('subnet', $readercfg->reader_fix_subnet);
        
        if (isset($readercfg->reader_subnet))
            $mform->setDefault('subnet', $readercfg->reader_subnet);
        
        $mform->addElement('select', 'individualstrictip', get_string('s_individualstrictip', 'reader'), array('0'=>'No', '1'=>'yes'));
        
        $mform->addElement('select', 'sendmessagesaboutcheating', get_string('s_sendmessagesaboutcheating', 'reader'), array('0'=>'No', '1'=>'yes'));
        
        $mform->addElement('textarea', 'cheated_message', stripslashes(get_string('s_cheated_message', 'reader')), 'rows="5" cols="50"');
        $mform->addElement('textarea', 'not_cheated_message', get_string('s_not_cheated_message', 'reader'), 'rows="5" cols="50"');

//-------------------------------------------------------------------------------
        
        
        $mform->setType('subnet', PARAM_TEXT);
        
        //-----
        $mform->setDefault('timelimit', $readercfg->reader_quiztimeout);
        $mform->setDefault('percentforreading', $readercfg->reader_percentforreading);
        $mform->setDefault('nextlevel', $readercfg->reader_quiznextlevel);
        $mform->addRule('nextlevel', null, 'required', null, 'client');
        $mform->setDefault('quizpreviouslevel', $readercfg->reader_quizpreviouslevel);
        $mform->addRule('quizpreviouslevel', null, 'required', null, 'client');
        $mform->setDefault('quiznextlevel', $readercfg->reader_quizonnextlevel);
        $mform->addRule('quiznextlevel', null, 'required', null, 'client');

        $mform->setDefault('pointreport', $readercfg->reader_pointreport);
        $mform->setDefault('questionmark', $readercfg->reader_questionmark);
        $mform->setDefault('bookcovers', $readercfg->reader_bookcovers);
        $mform->setDefault('attemptsofday', $readercfg->reader_attemptsofday);
        
        if (isset($readercfg->reader_goal))
            $mform->setDefault('goal', $readercfg->reader_goal);
        
        if (isset($readercfg->reader_secmeass))
            $mform->setDefault('secmeass', $readercfg->reader_secmeass);
        
        $mform->setDefault('levelcheck', $readercfg->reader_levelcheck);
        
        $mform->setDefault('reportwordspoints', $readercfg->reader_reportwordspoints);
        $mform->setDefault('wordsprogressbar', $readercfg->reader_wordsprogressbar);
        $mform->setDefault('sendmessagesaboutcheating', $readercfg->reader_sendmessagesaboutcheating);
        $mform->setDefault('cheated_message', $readercfg->reader_cheated_message);
        $mform->setDefault('not_cheated_message', $readercfg->reader_not_cheated_message);

        if ($readercfg->reader_usecourse == 0) {
            $mform->setDefault('usecourse', $COURSE->id);
        } else {
            $mform->setDefault('usecourse', $readercfg->reader_usecourse);
        }
        
        $mform->setDefault('timelimitenable', 1);
        
//-------------------------------------------------------------------------------
        // add standard elements, common to all modules
        $this->standard_coursemodule_elements();
//-------------------------------------------------------------------------------
        // add standard buttons, common to all modules
        $this->add_action_buttons();
    }
}


