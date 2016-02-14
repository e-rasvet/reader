<?php  //$Id: upgrade.php,v 1.2 2007/08/08 22:36:54 stronk7 Exp $

// This file keeps track of upgrades to
// the videoboard module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installtion to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the functions defined in lib/ddllib.php

function xmldb_reader_upgrade($oldversion=0) {

    global $CFG, $THEME, $DB;

    if ($oldversion < 2012010702) {
        //$DB->execute("ALTER TABLE {reader} ADD `introformat` INT( 10 ) NULL DEFAULT '0' AFTER `intro`");
                          
        // Define table quiz_report to be created
        $table = new xmldb_table('reader');

        // Adding fields to table quiz_report
        $table->add_field('introformat', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED,
                XMLDB_NULL, XMLDB_SEQUENCE, null);
                          
        upgrade_mod_savepoint(true, 2012010702, 'reader');
    }

    $result = true;

    return $result;
}


