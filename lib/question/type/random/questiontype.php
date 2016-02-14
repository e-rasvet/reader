<?php  // $Id: questiontype.php,v 1.17.2.5 2008/12/10 00:54:33 tjhunt Exp $

/////////////////
/// TRUEFALSE ///
/////////////////

/// QUESTION TYPE CLASS //////////////////
/**
 * @package questionbank
 * @subpackage questiontypes
 */
class back_random_qtype {
/// RESTORE FUNCTIONS /////////////////

    /*
     * Restores the data in the question
     *
     * This is used in question/restorelib.php
     */
    function restore($old_question_id,$new_question_id,$info,$restore) {
        global $DB;
        
        $status = true;

        return $status;
    }
}