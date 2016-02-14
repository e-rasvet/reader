<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin

    $tabs      = array();
    $row       = array();
    $inactive  = array();
    $activated = array();

    $row[] = new tabobject('quizes', new moodle_url("/mod/reader/view.php", array("a" => "quizes", "id" => $id)), "Quizzes");
    $row[] = new tabobject('admin', new moodle_url("/mod/reader/admin.php", array("a" => "admin", "id" => $id)), "Admin Area");
    $row[] = new tabobject('download', "", "Download Quizzes");

    $tabs[] = $row;

    print_tabs($tabs, 'download', $inactive, $activated);

