<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin


    require_once(dirname(__FILE__) . '/../../config.php');
    require_once($CFG->dirroot . '/mod/reader/attemptlib.php');
    require_once($CFG->dirroot . '/mod/reader/lib.php');
    require_once($CFG->dirroot . '/mod/reader/accessrules.php');
    require_once($CFG->dirroot . '/question/engine/lib.php');


    // Get submitted parameters.
    $id        = optional_param('id', 0, PARAM_INT); 
    $attemptid = required_param('attempt', PARAM_INT);
    $page      = optional_param('page', 0, PARAM_INT);


    $attemptobj = reader_attempt::create($attemptid);

    $PAGE->set_url($attemptobj->attempt_url(0, $page));

    // Check login.
    require_login($attemptobj->get_course(), false, $attemptobj->get_cm());

    // Check that this attempt belongs to this user.
    if ($attemptobj->get_userid() != $USER->id) {
        if ($attemptobj->has_capability('mod/reader:viewreports')) {
            redirect($attemptobj->review_url(0, $page));
        } else {
            throw new moodle_reader_exception($attemptobj->get_readerobj(), 'notyourattempt');
        }
    }

    navigation_node::override_active_url($attemptobj->start_attempt_url());

    // If the attempt is already closed, send them to the review page.
    if ($attemptobj->is_finished()) {
        redirect($attemptobj->review_url(0, $page));
    }

    // Check the access rules.
    $output        = $PAGE->get_renderer('mod_quiz');
    $accessmanager = $attemptobj->get_access_manager(time());
    $messages      = $accessmanager->prevent_access();

    $pagetext      = $page + 1;
    $logtext       = "readerID {$attemptobj->readerobj->reader->id}; reader quiz {$attemptobj->readerobj->book->id}; page: {$pagetext}";

    add_to_log($attemptobj->readerobj->course->id, "reader", "view attempt: ".addslashes($attemptobj->readerobj->book->name), "view.php?id=$id", $logtext);

    // Get the list of questions needed by this page.
    $slots = $attemptobj->get_slots($page);

    // Check.
    if (empty($slots)) {
        throw new moodle_reader_exception($attemptobj->get_readerobj(), 'noquestionsfound');
    }

    // Initialise the JavaScript.
    $headtags = $attemptobj->get_html_head_contributions($page);
    $PAGE->requires->js_init_call('M.mod_quiz.init_attempt_form', null, false, reader_get_js_module());
    $PAGE->requires->css('/mod/reader/css/timer.css');

    // Arrange for the navigation to be displayed.
    $headtags = $attemptobj->get_html_head_contributions($page);
    $PAGE->set_heading($attemptobj->get_course()->fullname);
    $PAGE->set_title(format_string($attemptobj->get_reader_name()));
    $PAGE->requires->js('/mod/reader/js/timer.js');

    if ($attemptobj->is_last_page($page)) {
        $nextpage = -1;
    } else {
        $nextpage = $page + 1;
    }

    $accessmanager->show_attempt_timer_if_needed($attemptobj->get_attempt(), time());

    if ($attemptobj->readerobj->reader->timelimit > 0) {
        $totaltimertime = $attemptobj->readerobj->reader->timelimit * 60 - (time() - $attemptobj->attempt->timestart);
        if ($totaltimertime < 0) $totaltimertime = 0;
        
        $PAGE->requires->data_for_js('totaltime', $totaltimertime);
    }

    $_SESSION['SESSION']->reader_lastattemptpage = $_SERVER['QUERY_STRING'];

    echo $output->attempt_page($attemptobj, $page, $accessmanager, $messages, $slots, $id, $nextpage);

    if ($attemptobj->readerobj->reader->timelimit > 0) {
        echo html_writer::tag('div', '', array('id'=>'fixededit'));
    }

