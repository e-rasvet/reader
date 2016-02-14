<?php

/**
 * Structure step to restore one reader activity
 */
class restore_reader_activity_structure_step extends restore_activity_structure_step {
 
    protected function define_structure() {
 
        $paths = array();

        $paths[] = new restore_path_element('reader', '/activity/reader');
 
        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }
 
    protected function process_reader($data) {
        global $DB;
  
        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        $data->timeopen = $this->apply_date_offset($data->timeopen);
        $data->timeclose = $this->apply_date_offset($data->timeclose);
        $data->introformat = $this->apply_date_offset($data->introformat);
        $data->timemodified = $this->apply_date_offset($data->timemodified);
        $data->teacher = $this->get_mappingid('user', $data->teacher);
 
        // insert the reader record
        $newitemid = $DB->insert_record('reader', $data);
        // immediately after inserting "activity" record, call this
        $this->apply_activity_instance($newitemid);
    }

    
    protected function after_execute() {
        // Add reader related files, no need to match by itemname (just internally handled context)
        $this->add_related_files('mod_reader', 'intro', null);
    }
}