<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin

    require_once("../../config.php");
    require_once("lib.php");

    $id        = required_param('id', PARAM_INT); 
    $publisher = optional_param('publisher', NULL, PARAM_CLEAN);
    
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

    add_to_log($course->id, "reader", "Ajax get list of books", "view.php?id=$id", "$cm->instance");
    
    $booksform = array();
    
    if ($publisher) {
        $books = $DB->get_records_sql("SELECT * FROM {reader_noquiz} WHERE publisher= ? ORDER BY name", array($publisher));
        foreach ($books as $books_) {
            $booksform[$books_->id] = "{$books_->name} ({$books_->level})";
        }
    }

    
    if ($publisher != "Select Publisher") {
        if (count($booksform) > 0) {
            echo html_writer::start_tag('select', array('size'=>10, 'name'=>'book', 'id'=>'id_book', 'style'=>'width: 500px;', 'multiple'=>'multiple'));
            foreach ($booksform as $booksformkey => $booksformvalue) {
                echo html_writer::tag('option', $booksformvalue, array('value'=>$booksformkey));
            }
            echo html_writer::end_tag('select');
        } else {
            print_string('nobooksinlist', 'reader');
        }
    } else {
        print_string('pleaseselectpublisher', 'reader');
    }

    
