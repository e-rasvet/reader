<?php

/**
 * Define the complete reader structure for backup, with file and id annotations
 */     
class backup_reader_activity_structure_step extends backup_activity_structure_step {
 
    protected function define_structure() {
 
        // To know if we are including userinfo
        $userinfo = $this->get_setting_value('userinfo');
 
        // Define each element separated
        $reader = new backup_nested_element('reader', array('id'), array(
            'course', 'name', 'intro', 'introformat', 'timeopen', 'timeclose', 'optionflags', 'penaltyscheme', 'attempts', 'attemptonlast',
            'grademethod', 'decimalpoints', 'review', 'questionsperpage', 'shufflequestions', 'shuffleanswers', 'questions',
            'sumgrades', 'grade', 'usecourse', 'timecreated', 'timemodified', 'timelimit', 'password',
            'subnet', 'popup', 'individualstrictip', 'delay1', 'delay2', 'percentforreading', 'nextlevel',
            'quizpreviouslevel', 'quiznextlevel', 'pointreport', 'questionmark', 'bookcovers', 'attemptsofday', 'ignordate',
            'goal', 'wordsorpoints', 'secmeass', 'promotionstop', 'levelcheck', 'reportwordspoints', 'wordsprogressbar',
            'individualbooks', 'sendmessagesaboutcheating', 'cheated_message', 'not_cheated_message', 'checkbox'));
        
        // Build the tree
        
        // Define sources
        $reader->set_source_table('reader', array('id' => backup::VAR_ACTIVITYID, 'course' => backup::VAR_COURSEID));
 
        // Define id annotations

        // Return the root element (reader), wrapped into standard activity structure
        
        return $this->prepare_activity_structure($reader);
    }
}