<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin

    require_once("../../config.php");
    require_once("lib.php");
    
    
    $rm = array();
    
    if ($handle = opendir($CFG->dataroot)) {
        make_upload_directory('reader/images');

        /* This is the correct way to loop over the directory. */
        while (false !== ($entry = readdir($handle))) {
            if (is_number($entry)){
                if(is_dir($CFG->dataroot."/".$entry."/images")){
                  if ($handle2 = opendir($CFG->dataroot."/".$entry."/images")) {
                    while (false !== ($entry2 = readdir($handle2))) {
                      if (is_file($CFG->dataroot."/".$entry."/images/".$entry2)){
                        echo "$entry2<br />";
                        copy($CFG->dataroot."/".$entry."/images/".$entry2, $CFG->dataroot.'/reader/images/'.$entry2);
                      }
                    }
                  }
                  
                  rrmdir($CFG->dataroot."/".$entry."/images");
                  
                  $rm[] = $CFG->dataroot."/".$entry;
                }
            }
        }

        closedir($handle);
    }
    
    sleep(2);
    
    foreach($rm as $rm_){
      @rmdir($rm_);
    }
    
    echo '<br />All images was moved to new location. Please delete this file. reader/updater.php<br /><br />';
    
    
    function rrmdir($dir) {
      if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
          if ($object != "." && $object != "..") {
            if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
          }
        }
        reset($objects);
        rmdir($dir);
      }
    }