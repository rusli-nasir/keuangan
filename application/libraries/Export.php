<?php 
    //in a library file export.php
    if (!defined('BASEPATH')) exit('No direct script access allowed');

    /*
    * Excel library for Code Igniter applications
    * Based on: Derek Allard, Dark Horse Consulting, www.darkhorse.to, April 2006
    * Tweaked by: Moving.Paper June 2013
    */
    class Export{
        
        function to_excel($array, $filename, $date, $total, $status=null) {
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename='.$filename.'.xls');

            if (empty($status)) {
                $status = 'Uang Masuk';
            }

            //Filter all keys, they'll be table headers
            $h = array();
            foreach($array->result_array() as $row){
                foreach($row as $key=>$val){
                    if(!in_array($key, $h)){
                     $h[] = $key;   
                    }
                    }
                    }
                    //echo the entire table headers
                    echo '<h3><b>'.$status.' '.$date.' (Rp.'.number_format($total, 0 , '' , '.' ).')</b></h3>';
                    echo '<table border="2"><tr>';
                    foreach($h as $key) {
                        $key = ucwords($key);
                        echo '<th>'.$key.'</th>';
                    }
                    echo '</tr>';
                    
                    foreach($array->result_array() as $row){
                         echo '<tr>';
                        foreach($row as $val)
                           $this->writeRow($val);   
                    }
                    echo '</tr>';
                    echo '</table>';
                    
                
            }
        function writeRow($val) {
                    $cek=strlen($val);
                    if ($cek=='22') {
                        echo '<td>"'.utf8_decode($val).'</td>';    
                    } else {
                        echo '<td>'.utf8_decode($val).'</td>';              
                    }
                    
        }

    }
 ?>