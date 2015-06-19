<?php
/*
  Sample File not more !!!
  Author: Jointy <bestmischmaker@web.de>
*/

require("pop3.class.inc");

$server = "pop.web.de";
$port = "110";

$conn_timeout = "20";  // Connection Timeout
$pop3_timeout = "10,500"; // Socket Timeout

$username = "username";
$password = "password";
$apop = "0";

$log = TRUE;
$log_file = "pop3.class.log";

$pop3 = new POP3($log,$log_file);

if($pop3->connect($server,$port,$conn_timeout,$pop3_timeout)){
    if($pop3->login($username,$password,$apop)){
        // Send "NOOB" command !!!
        // Server alive ??
        if(!$pop3->noob()){
            echo $this->error;

        }
        // Get office status array with mail info's
        $msg_array = $pop3->get_office_status();
        if($msg_array["error"]){
            echo $msg_array["error"];
            return;
        }
        // Get mail
        $msgnum = "1";
        $delete = FALSE;
        $savetofile = TRUE;
        $message = $pop3->get_mail($msgnum);
        // print_r($message);
        if($message["error"]){
            echo $message["error"];
            return;
        }
        // Delete mail
        if($delete){
            if(!$pop3->delete_mail($msgnum)){
                echo $this->error;
                return;
            }
        }
        // Save mail to given filename !!!
        if($savetofile){
            $filename = ".//mails//".base64_encode($msg_array[$msgnum]["uid"]).".txt";
            if(!is_file($filename)){
                $filesize = $pop3->save2file($message,$filename);
                if(!$filesize){
                    echo $pop3->error;
                    return;
                }
                echo "message save to ".$filename." (".$filesize." Bytes written)";
            }else{
                echo "message \"$filename\" already saved !!";
            }

        }
        
        // Close Connection
        if(!$pop3->close()){
            echo $this->error;
            return;
        }
        
    }else{
        echo $this->error;
        return;
    }
}else{
    echo $this->error;
    return;
}


/*
///////////////////////////////////
if(!$pop3->connect($server, $port, $conn_timeout, $pop3_timeout)){
    echo $pop3->error;
    return;
}

if(!$pop3->login($username,$password,$apop)){
    echo $pop3->error;
    return;
}

// Server alive ??
if(!$pop3->send_noob()){
    echo $pop3->error;
    return;
}

$msg_array = $pop3->get_office_status();
if($msg_array["error"]){
    echo $msg_array
print_r($msg_array);
////////////////////////////////////////////////////
//               IMPORTANT                        //
// When you want to get all mails on POP3 Server  //
// sometimes you must set more execution time,    //
// because 30 sec are to low.                     //
// but this is dependently by your count of mails //
// and size !!!                                   //
// Good Luck :)                                   //
////////////////////////////////////////////////////

$msgnum = $msg_array["count_mails"];

// Get one message give by the msgnum
for($i = 1; $i <= $msgnum; $i++){


    $filename = ".//mails//".base64_encode($msg_array[$i]["uid"]).".txt";
    if(!is_file($filename)){
       $message = $pop3->get_mail($i);
       if(!$message["error"]){
           $filesize = $pop3->save2file($message,$filename);
           if(!$filesize){
              echo $this->error;
              return;
           }else{
              echo "message save to ".$filename."(".$filesize." Bytes written)";
          }
       }else{
           echo $message["error"];
       }
   }else{
       echo "message \"$filename\" already download !!";
   }
   unset($message);
   unset($filesize);


if($delete == "Y"){
    if(!$pop3->delete_mail($msgnum)){
        echo $pop3->error;
        return;
    }else{
        echo "message delete";
    }
}
}


if(!$pop3->close()){
    echo $pop3->error;
    return;
}
*/

?>
