<?php  // $Id:,v 2.0 2012/05/20 16:10:00 Igor Nikulin

    require_once("../../config.php");

    list($a, $req)  =  explode('images.php', $_SERVER['REQUEST_URI']);

    $img = $CFG->dataroot.$req; 

    $fp = fopen($img, "r");
    if ($fp) {
        header("Cache-Control: private, max-age=10800, pre-check=10800");
        header("Pragma: private");
        header("Expires: " . date(DATE_RFC822,strtotime("30 day")));

        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == filemtime($img))) {
            header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($img)).' GMT', 
            true, 304);
            exit;
        }
        
        header("Content-type: image/jpeg");
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($img)) . ' GMT');
        
        fpassthru($fp);
        fclose($fp);
    } else {
        header("Content-type: application/octet-stream");
        echo "File no found";
    }

