<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin

    $currenttab = $a;
    if (!isset($currenttab)) {
        $currenttab = 'quizes';
    }

    if (!isset($idh)) {
        $idh = $id;
    }

    if (!isset($cm)) {
        if (! $cm = $DB->get_record("course_modules", array( "id" => $idh))) {
            error("Course Module ID was incorrect");
        }
    }

    $context = get_context_instance(CONTEXT_MODULE, $cm->id);

    if (!isset($contexts)){
        $contexts = new question_edit_contexts($context);
    }

    $tabs      = array();
    $row       = array();
    $inactive  = array();
    $activated = array();

    $row[]  = new tabobject('quizes', new moodle_url("/mod/reader/view.php", array('a'=>'quizes', 'id'=>$idh)), "Quizzes");
    $row[]  = new tabobject('admin', new moodle_url("/mod/reader/admin.php", array('a'=>'admin', 'id'=>$idh)), "Admin Area");
    
    if (has_capability('mod/reader:manage', $contextmodule)) {
        $row[]  = new tabobject('readersettings', new moodle_url("/course/mod.php", array('update'=>$cm->id, 'return'=>'true', 'sesskey'=>sesskey())), "Main Settings");
    }

    $tabs[] = $row;

    if ($currenttab == 'admin' and isset($mode)) {
        $inactive[] = 'admin';
        $activated[] = 'admin';

        // Standard reports we want to show first.
        $reportlist = array ('overview', 'regrade', 'grading', 'analysis');
        // Reports that are restricted by capability.
        $reportrestrictions = array(
            'regrade' => 'mod/quiz:grade',
            'grading' => 'mod/quiz:grade'
        );

        $allreports = get_list_of_plugins("mod/quiz/report");
        foreach ($allreports as $report) {
            if (!in_array($report, $reportlist)) {
                $reportlist[] = $report;
            }
        }

        $row  = array();
        $currenttab = '';
        foreach ($reportlist as $report) {
            if (!isset($reportrestrictions[$report]) || has_capability($reportrestrictions[$report], $context)) {
                $row[] = new tabobject($report, new moodle_url("/mod/reader/report.php", array("idh" => $idh, "q" => $q, "mode" => $report, "b" => $b)),
                                        get_string($report, 'quiz_'.$report));
                if ($report == $mode) {
                    $currenttab = $report;
                }
            }
        }
        $tabs[] = $row;
    }

    unset ($tabs[1][0]);

    print_tabs($tabs, $currenttab, $inactive, $activated);

