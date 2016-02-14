<?php  // $Id: questiontype.php,v 1.11.2.6 2008/12/10 06:22:04 tjhunt Exp $

class back_description_qtype {
    function restore($old_question_id,$new_question_id,$info,$restore) {
        // The default question type has nothing to restore
        return true;
    }

    function restore_map($old_question_id,$new_question_id,$info,$restore) {
        // There is nothing to decode
        return true;
    }

}

