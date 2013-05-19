<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoggingHelper
 *
 * @author WebMobileCloudGuru
 */
class LoggingHelper {
    function logToFile($filename, $msg) {
        // open file 
        $fd = fopen($filename, "a"); 
        // append date/time to message 
        $str = "[" . date("Y/m/d h:i:s", mktime()) . "] " . $msg; 
        // write string 
        fwrite($fd, $str . "\n");
        // close file 
        fclose($fd);
    }       
}

?>
