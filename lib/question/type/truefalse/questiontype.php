<?php  // $Id: questiontype.php,v 1.17.2.5 2008/12/10 00:54:33 tjhunt Exp $

/////////////////
/// TRUEFALSE ///
/////////////////

/// QUESTION TYPE CLASS //////////////////
/**
 * @package questionbank
 * @subpackage questiontypes
 */
class back_truefalse_qtype {
/// RESTORE FUNCTIONS /////////////////

    /*
     * Restores the data in the question
     *
     * This is used in question/restorelib.php
     */
    function restore($old_question_id,$new_question_id,$info,$restore) {
        global $DB;
        $status = true;

        //Get the truefalse array
        if (array_key_exists('TRUEFALSE', $info['#'])) {
            $truefalses = $info['#']['TRUEFALSE'];
        } else {
            $truefalses = array();
        }

        //Iterate over truefalse
        for($i = 0; $i < sizeof($truefalses); $i++) {
            $tru_info = $truefalses[$i];

            //Now, build the question_truefalse record structure
            $truefalse = new stdClass;
            $truefalse->question = $new_question_id;
            $truefalse->trueanswer = stripslashes(backup_todb($tru_info['#']['TRUEANSWER']['0']['#']));
            $truefalse->falseanswer = stripslashes(backup_todb($tru_info['#']['FALSEANSWER']['0']['#']));

            ////We have to recode the trueanswer field
            $answer = backup_getid($restore->backup_unique_code,"question_answers",$truefalse->trueanswer);
            if ($answer) {
                $truefalse->trueanswer = $answer->new_id;
            }

            ////We have to recode the falseanswer field
            $answer = backup_getid($restore->backup_unique_code,"question_answers",$truefalse->falseanswer);
            if ($answer) {
                $truefalse->falseanswer = $answer->new_id;
            }

            //The structure is equal to the db, so insert the question_truefalse
            $newid = $DB->insert_record ("question_truefalse", $truefalse);

            //Do some output
            if (($i+1) % 50 == 0) {
                if (!defined('RESTORE_SILENTLY')) {
                    echo ".";
                    if (($i+1) % 1000 == 0) {
                        echo "<br />";
                    }
                }
                backup_flush(300);
            }

            if (!$newid) {
                $status = false;
            }
        }

        return $status;
    }
}