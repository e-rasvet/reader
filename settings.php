<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin

    defined('MOODLE_INTERNAL') || die;

    require_once($CFG->dirroot . '/mod/reader/lib.php');

    $pagetitle = get_string('modulename', 'reader');

    $readercfg = get_config('reader');

    $readersettings = new admin_settingpage('modsettingreader', $pagetitle, 'moodle/site:config');


    // Time limit
    $readersettings->add(new admin_setting_configtext_with_advanced('reader/reader_quiztimeout',
            'Quiz time out', '',
            array('value' => $readercfg->reader_quiztimeout, 'fix' => false), PARAM_INT));

    // Number of attempts
    $options    = array();
    $options[0] = "Disallow";
    $options[1] = "Allow";
    $readersettings->add(new admin_setting_configselect_with_advanced('reader/reader_editingteacherrole',
            'Use "Editing Teacher" role', '',
            array('value' => $readercfg->reader_editingteacherrole, 'fix' => false), $options));

    // Number of attempts
    $options    = array();
    $options[0] = "No";
    $options[1] = "Yes";
    $readersettings->add(new admin_setting_configselect_with_advanced('reader/reader_pointreport',
            'Full point report', '',
            array('value' => $readercfg->reader_pointreport, 'fix' => false), $options));

    // Number of attempts
    $options    = array();
    $options[0] = "No";
    $options[1] = "Yes";
    $readersettings->add(new admin_setting_configselect_with_advanced('reader/reader_levelcheck',
            'Use the quiz taking level restriction feature', '',
            array('value' => $readercfg->reader_levelcheck, 'fix' => false), $options));
            
            
    // Number of attempts
    $options    = array();
    $options[0] = "No";
    $options[1] = "Yes";
    $readersettings->add(new admin_setting_configselect_with_advanced('reader/reader_checkbox',
            'Use checkboxes in student report', '',
            array('value' => $readercfg->reader_checkbox, 'fix' => false), $options));
            
            
    // Time limit
    $readersettings->add(new admin_setting_configtext_with_advanced('reader/reader_percentforreading',
            'Percent correct for making the book as read. (%)', '',
            array('value' => $readercfg->reader_percentforreading, 'fix' => false), PARAM_INT));
            
            
            
    // Number of attempts
    $options    = array();
    $options[0] = "No";
    $options[1] = "Yes";
    $readersettings->add(new admin_setting_configselect_with_advanced('reader/reader_questionmark',
            'Show question mark', '',
            array('value' => $readercfg->reader_questionmark, 'fix' => false), $options));
            
    // Number of attempts
    $options    = array();
    $options[0] = "No";
    $options[1] = "Yes";
    $readersettings->add(new admin_setting_configselect_with_advanced('reader/reader_bookcovers',
            'Show book covers', '',
            array('value' => $readercfg->reader_bookcovers, 'fix' => false), $options));
            
    // Number of attempts
    $options    = array();
    $options[0] = "Show Word Count only";
    $options[1] = "Show Points only";
    $options[2] = "Show both Points and Word Count";
    $readersettings->add(new admin_setting_configselect_with_advanced('reader/reader_reportwordspoints',
            'Report', '',
            array('value' => $readercfg->reader_reportwordspoints, 'fix' => false), $options));
            
    // Number of attempts
    $options    = array();
    $options[0] = "Hide";
    $options[1] = "Show";
    $readersettings->add(new admin_setting_configselect_with_advanced('reader/reader_wordsprogressbar',
            'Word Count Progress Bar', '',
            array('value' => $readercfg->reader_wordsprogressbar, 'fix' => false), $options));
            
            
    // Number of attempts
    $options    = array();
    $options[0] = "Off";
    $options[1] = "1";
    $options[2] = "2";
    $options[3] = "3";
    $readersettings->add(new admin_setting_configselect_with_advanced('reader/reader_attemptsofday',
            'Quiz for days', '',
            array('value' => $readercfg->reader_attemptsofday, 'fix' => false), $options));
            
            
    // Time limit
    $readersettings->add(new admin_setting_configtext_with_advanced('reader/reader_quiznextlevel',
            'Give next level, when student answered more than correct quizzes.', '',
            array('value' => $readercfg->reader_quiznextlevel, 'fix' => false), PARAM_INT));
            
    // Time limit
    $readersettings->add(new admin_setting_configtext_with_advanced('reader/reader_quizpreviouslevel',
            'Allow to select quizzes of previous level.', '',
            array('value' => $readercfg->reader_quizpreviouslevel, 'fix' => false), PARAM_INT));
            
    // Time limit
    $readersettings->add(new admin_setting_configtext_with_advanced('reader/reader_quizonnextlevel',
            'Allow to select quizzes of next level.', '',
            array('value' => $readercfg->reader_quizonnextlevel, 'fix' => false), PARAM_INT));
            
            
            
            
            
    // Number of attempts
    $options    = array();
    $options[0] = "Current course";
    $courses    = get_courses();

    foreach ($courses as $course) {
        $options[$course->id] = $course->fullname;
    }

    $readersettings->add(new admin_setting_configselect_with_advanced('reader/reader_usecourse',
            'Quiz location', '',
            array('value' => $readercfg->reader_usecourse, 'fix' => false), $options));
            
            
    // Number of attempts
    $options    = array();
    $options[0] = "No";
    $options[1] = "Yes";
    $readersettings->add(new admin_setting_configselect_with_advanced('reader/reader_update',
            'Check server for quiz updates?', '',
            array('value' => $readercfg->reader_update, 'fix' => false), $options));
            
    // Number of attempts
    $options    = array();
    $options[0] = "No";
    $options[1] = "Yes";
    $readersettings->add(new admin_setting_configselect_with_advanced('reader/reader_sendmessagesaboutcheating',
            'Send messages about cheating?', '',
            array('value' => $readercfg->reader_sendmessagesaboutcheating, 'fix' => false), $options));
            
            
    $readersettings->add(new admin_setting_configtextarea('reader/reader_cheated_message',
            '"Cheated" notice', '',
            array('value' => stripslashes($readercfg->reader_cheated_message), 'fix' => false)));
            
    $readersettings->add(new admin_setting_configtextarea('reader/reader_not_cheated_message',
            'Points restored notice', '',
            array('value' => stripslashes($readercfg->reader_not_cheated_message), 'fix' => false)));
            
    // Time limit
    $readersettings->add(new admin_setting_configtext_with_advanced('reader/reader_serverlink',
            'Link to Reader server (with quizzes).', '',
            array('value' => $readercfg->reader_serverlink, 'fix' => false), PARAM_TEXT));
            
    // Time limit
    $readersettings->add(new admin_setting_configtext_with_advanced('reader/reader_serverlogin',
            'Reader server login.', '',
            array('value' => $readercfg->reader_serverlogin, 'fix' => false), PARAM_TEXT));
            
    // Time limit
    $readersettings->add(new admin_setting_configtext_with_advanced('reader/reader_serverpassword',
            'Reader server password.', '',
            array('value' => $readercfg->reader_serverpassword, 'fix' => false), PARAM_TEXT));
            

    $ADMIN->add('modsettings', $readersettings);


    $settings = NULL; // we do not want standard settings link
