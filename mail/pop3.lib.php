<?php
/*
  Class pop3.class.inc
  Author: Jointy <bestmischmaker@web.de>
  create: 07. May 2003
  last cahnge: 11. May 2003
  
  Version: 0.84 (beta)

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

Public Functions

  
  // Constructor
  
  In Constructor you those log (true or false) and give log filename
  - it's only POP Session log...
  - error messages come out in browser !!!
  
  // Functions
  You have this function to those.

//////////
  connect($server, $port, $timeout, $sock_timeout)


      // Vars:
      - $server ( Server IP or DNS )
      - $port ( Server port default is "110" )
      - $timeout ( Connection timeout for connect to server )
      - $sock_timeout ( Socket timeout for all actions   (10 sec 500 msec) = (10,500))


      If all right you get true, when not you get false and on $this->error = msg !!!
  

//////////
  login($user,$pass,$apop)
  // Vars:
      - $apop  ( 1 = true and 0 = false)  (default = 0)

//////////
  get_office_status()
  - you get an array build like this
  - if an error Connection will closed.
  
  Array
(
    [count_mails] => 3
    [octets] => 2496
    [1] => Array
        (
            [size] => 832
            [uid] => 617999468
        )

    [2] => Array
        (
            [size] => 3253781
            [uid] => 617999616
        )

    [3] => Array
        (
            [size] => 2864
            [uid] => 617999782
        )


)


/////////
  get_mail($msg_number)
  - return an array where every line is in his own key...like this
  Array(
     [0] => "line1"
     [1] => "line2"
     ....
     )
     
////////////////////////////////////////////////////
//               IMPORTANT                        //
// When you want to get all mails on POP3 Server  //
// sometimes you must set more execution time,    //
// because 30 sec are to low.                     //
// but this is dependently by your count of mails //
// and size !!!                                   //
// Good Luck :)                                   //
////////////////////////////////////////////////////

/////////
  delete_mail($msg_number)


  - mark an email as delete
  - if you "QUIT" connection, the emails will delete.
  - if failed command, connection will not closed !!
  
/////////
  save2file($message,$filename)
  - $message must be a numeric array with one "CRLF".
  Array(
     [0] => "line1"
     [1] => "line2"
     ....
     )

   $filename
   - i would take as filename   base64_encode(uid).".txt"
   - and to check if you have download this mail  base64_encode(uid).".txt" == $filename
   
   // forwards filename you can set a directory string
   win32  .//mails// or c://ownfiles//etc...
   linux  ./mails/   or /dev/hda1/ownfiles/etc...

/////////
  save2mysql($message,$unique_id,$read="0",$table,$mysql_socket)
  - give table must exists !!!
  - check befor you run save2mysql() if mail exists...



/////////
  noob()
  - send the "NOOB" command to server
  - if failed command, connection will not closed !!
/////////
  get_top($msg_number,$lines)
  
  - with get_top() you can get header only if you make get_top($msg_number,"0")
  - and if you set $lines != 0  then get you the count of lines...are more lines given as there you get the hole mail !!!
  
/////////
  reset()
  - all as delete marked mails ... will marked as undelete !!!
  - if failed command, connection will not closed !!
  
/////////
  uidl($msg_number) (default = "0")
  - when you leave $msg_number blank, you get the hole uid list
  - when take message number, you get the uid for this one mail.
  - when error you get FALSE and connection will not closed !!!
  

////////////////////////////////////////////////////////////////////////////////
  
Private Functions

/////////
  _putline($string)
  - put a command to server socket !!
  - give the string with not "CRLF" and the end

/////////
  _getnextstring()
  - optional: $buffer_size (default = "512")
  - get the next String from server socket !!_
  
/////////
  _logging($string)
  - logging string to give log_file (constructor)
  - give the string with not "CRLF" and the end
  
/////////
  _checkstate($string)
  - check the connection state to pop3 server !!!
  - $string = function name !!!
  
/////////
  _parse_banner($server_text)
  - $server_text = first response after connect !!!
  - return the server banner for APOP Login command !!
  
/////////
  _cleanup()
  - unset some vars
  - close log_file
  - close server socket
  
/////////
  _stats()
  - get maildrop stats
  - return when alright an array
  ["count_mails"]
  ["octets"]
  
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
  ChangeLog:
  
  // 08 May 2003
  - Version 0.52 (beta) public coming out !!!
  - add logging
  
  // 09 May 2003
  - Version 0.61 out !!!
  - add get_top() function (public)
  
  -------------------------
  ! POP3 Class get Public !
  -------------------------
  
  // 10 May 2003
  - add reset() function (public)
  - add _checkstate($string) function (private)
  - add _stats() function (private)
  - add uidl($msg_number) function (public)
  
  // 11 May 2003
  - add save2mysql function (public)
  - fixed some errors !!!
  
  // 14 May 2003
  - fixed a heavy bug with APOP Server (private func _parse_banner($server_text))
  (so sometimes the APOP Authorization goes failed, although the password was correct !!)

  
*/


class POP3 {
    // Socket Vars
    var $socket = FALSE;
    var $socket_status = "";
    var $socket_timeout = "10,500";

    var $error = "No Errors";
    var $state = "DISCONNECTED";
    var $apop_banner = "";
    
    var $log = FALSE;
    var $log_file;
    var $log_fp;
    
    var $file_fp;


    // Constructor
    function POP3($log,$log_file){
        $this->log = $log;
        $this->log_file = $log_file;

    }
    

    /*
      Function _cleanup()
      Access: Private
    */
    function _cleanup(){
        $this->state = "DISCONNECTED";
        if(is_array($this->socket_status)) unset($this->socket_status);
        if($this->socket) fclose($this->socket);

        $close_log = "Connection Closed. \r\n";
        $close_log .= "/------------------------------------------------------------------- \r\n";
        $close_log .= "/--- Log File: ".$this->log_file." \r\n";
        $close_log .= "/--- Log Close: ".date('l, d M Y @ H:i:s')." \r\n";
        $close_log .= "/------------------------------------------------------------------- \r\n";

        if($this->log) $this->_logging($close_log);

        if($this->log_fp) fclose($this->log_fp);
        unset($close_log);
    }
    
    /*
      Function _logging($string)
      Access: Private
    */
    function _logging($string){
        if($this->log){
        if(!$this->log_fp){
            $this->log_fp = fopen($this->log_file,"a+");
            if(!$this->log_fp){
                $this->error = "POP3 _logging() - Error: Can't open log file in write mode ($this->log_file) !!!";
                $this->_cleanup();
                return FALSE;
            }
            $open_log = "/------------------------------------------------------------------- \r\n";
            $open_log .= "/--- Log File: ".$this->log_file." \r\n";
            $open_log .= "/--- Log Open: ".date('l, d M Y @ H:i:s')." \r\n";
            $open_log .= "/------------------------------------------------------------------- \r\n";
                         
            if(!fwrite($this->log_fp,$open_log,strlen($open_log))){
                $this->error = "POP3 _logging() - Error: Can't write string to file !!!";
                $this->_cleanup();
                return FALSE;
            }
            unset($open_log);
        }
        if(substr($string,0,1) != "-" && substr($string,0,1) != "+" && substr($string,-4) != "\r\n" && substr($string,-2) != "\n"){
            $string = $string."\r\n";
        }
        $date = date("H:i:s");
        $string = $date." -- ".$string;
        if(!fwrite($this->log_fp, $string, strlen($string))){
            $this->error = "POP3 _logging() - Error: Can't write string to file !!!";
            $this->_cleanup();
            return FALSE;
        }
        
        }
        return TRUE;
    }

    
    /*
      Function connect($server, $port, $timeout, $sock_timeout)
      Access: Public
      
      // Vars:
      - $server ( Server IP or DNS )
      - $port ( Server port default is "110" )
      - $timeout ( Connection timeout for connect to server )
      - $sock_timeout ( Socket timeout for all actions   (10 sec 500 msec) = (10,500))
      

      If all right you get true, when not you get false and on $this->error = msg !!!
    */
    function connect($server, $port="110", $timeout, $sock_timeout){


        if($this->socket){
            $this->error = "Connection also avalible !!!";
            return FALSE;
        }

        if(!trim($server)){
            $this->error = "Please give a server address.";
            return FALSE;
        }

        if($port < "1" && $port < "65535" || !trim($port)){
            $this->error = "Port not set or out of range (1 - 65535)";
            return FALSE;
        }

        if($timeout < 0 && $timeout > 25 || !trim($timeout)){
            $this->error = "Connection Timeout not set or out of range (0 - 25)";
            return FALSE;
        }
        if($sock_timeout < 0 && $sock_timeout > 25 || !trim($sock_timeout)){
            $this->error = "Socket Timeout not set or out of range (0 - 25)";
            return FALSE;
        }
        // Check State
        if(!$this->_checkstate("connect")) return FALSE;
        
        $this->socket = fsockopen($server, $port, $errno, $errstr, $timeout);
        if(!$this->socket){
            $this->error = "Can't connect to Server. Error: $errno -- $errstr ";
            return FALSE;
        }

        if(!$this->_logging("Connecting to \"$server:$port\" !!!")) return FALSE;
        // Set Socket Timeout
        // It is valid for all other functions !!

        $sock_timeout = explode(",",$sock_timeout);
        socket_set_timeout($this->socket,$sock_timeout[0],$sock_timeout[1]);
        socket_set_blocking($this->socket,true);

        $response = $this->_getnextstring();

        if(!$this->_logging($response)){
            $this->_cleanup();
            return FALSE;
        }

        if(substr($response,0,1) != "+"){
            $this->_cleanup();
            $this->error = "POP3 connect() - Error: ".$response;
            return FALSE;
        }


        $this->apop_banner = $this->_parse_banner($response);

        $this->state = "AUTHORIZATION";
        if(!$this->_logging("STATUS: AUTHORIZATION")) return FALSE;

        return TRUE;

    }
    
    /*
      Function _login($user = "", $pass = "")
      Access: Public
    */
    
    function login($user, $pass, $apop){
        if(!$this->socket){
            $this->error = "POP3 login() - Error: No connection avalible.";
            $this->_cleanup();
            return FALSE;
        }

        if($this->_checkstate("login")){
        if($apop == "0"){

            $response = "";
            $cmd = "USER $user";
            if(!$this->_logging($cmd)) return FALSE;
            if(!$this->_putline($cmd)) return FALSE;

            $response = $this->_getnextstring();

            if(!$this->_logging($response)) return FALSE;

            if(substr($response,0,1) == "-" ){
                $this->error = "POP3 login() - Error: ".$response;
                $this->_cleanup();
                return FALSE;
            }
            
            $response = "";
            $cmd = "PASS $pass";
            $cmd_1 = "PASS ".md5($pass);
            if(!$this->_logging($cmd_1)) return FALSE;
            if(!$this->_putline($cmd)) return FALSE;
            $response = $this->_getnextstring();
            if(!$this->_logging($response)) return FALSE;
            if(substr($response,0,1) == "-" ){
                $this->error = "POP3 login() - Error: ".$response;
                $this->_cleanup();
                return FALSE;
            }
            $this->state = "TRANSACTION";
            if(!$this->_logging("STATUS: TRANSACTION")) return FALSE;
            return TRUE;

        }elseif($apop == "1"){
            // APOP Section

            // Check is Server Banner for APOP Command given !!!
            if(empty($this->apop_banner)){
                $this->error = "POP3 login() (APOP) - Error: No Server Banner -- aborted and close connection";
                $this->_cleanup();
                return FALSE;
            }

            $response = "";

            // Send APOP Command !!!

            $cmd = "APOP $user ".md5($this->apop_banner.$pass);

            if(!$this->_logging($cmd)) return FALSE;
            if(!$this->_putline($cmd)) return FALSE;
            $response = $this->_getnextstring();

            if(!$this->_logging($response)) return FALSE;
            // Check the response !!!
            if(substr($response,0,1) != "+" ){
                $this->error = "POP3 login() (APOP) - Error: ".$response;
                $this->_cleanup();
                return FALSE;
            }
            $this->state = "TRANSACTION";
            if(!$this->_logging("STATUS: TRANSACTION")) return FALSE;
            return TRUE;

        }else{
            $this->error = "POP3 login() - Error: Please set apop var !!! (1 [true] or 0 [false]).";
            $this->_cleanup();
            return FALSE;
        }

        }
        
        return FALSE;
    }
    /*
      Function get_top($msg_number,$lines)
      Access: Public
    */
    function get_top($msg_number,$lines){
        if(!$this->socket){
            $this->error = "POP3 get_top() - Error: No connection avalible.";
            return $output["error"] = $this->error;
        }
        
        if(!$this->_checkstate("get_top")) return $output["error"] = $this->error;
        
        $response = "";
        $cmd = "TOP $msg_number $lines";
        if(!$this->_logging($cmd)) return $output["error"] = $this->error;
        if(!$this->_putline($cmd)) return $output["error"] = $this->error;
        
        $response = $this->_getnextstring();

        if(!$this->_logging($response)) return $output["error"] = $this->error;

        if(substr($response,0,3) != "+OK"){
            $this->error = "POP3 get_top() - Error: ".$response;
            return $output["error"] = $this->error;
        }
        // Get Header
        $i = "0";
        $response = "<HEADER> \r\n";
        while(!eregi("^\.\r\n",$response)){
            if(substr($response,0,4) == "\r\n") break;
            $output[$i] = $response;
            $i++;
            $response = $this->_getnextstring();
        }
        $output[$i++] = "</HEADER> \r\n";
        // Get $lines
        if($lines == "0"){
            if(!$this->_logging("Complete.")) return $output["error"] = $this->error;
            return $output;
        }
        
        $response = "<MESSAGE> \r\n";
        for($g = 0;$g < $lines; $g++){
            if(eregi("^\.\r\n",$response)) break;
            $output[$i] = $response;
            $i++;
            $response = $this->_getnextstring();
        }
        $output[$i] = "</MESSAGE> \r\n";
        if(!$this->_logging("Complete.")) return $output["error"] = $this->error;
        return $output;
}


    /*
      Function get_mail
      Access: Public
    */
    function get_mail($msg_number){
        if(!$this->socket){
            $this->error = "POP3 get_mail() - Error: No connection avalible.";
            $this->_cleanup();
            return $output["error"] = $this->error;
        }

        if(!$this->_checkstate("get_mail")) return $output["error"] = $this->error;

        $response = "";
        $cmd = "RETR $msg_number";
        if(!$this->_logging($cmd)) return $output["error"] = $this->error;
        if(!$this->_putline($cmd)) return $output["error"] = $this->error;

        $response = $this->_getnextstring();

        if(!$this->_logging($response)) return $output["error"] = $this->error;

        if(substr($response,0,3) != "+OK"){
            $this->error = "POP3 get_mail() - Error: ".$response;
            $this->_cleanup();
            return $output["error"] = $this->error;
        }

        // Get MAIL !!!
        $i = "0";
        $response = "<HEADER> \r\n";
        while(!eregi("^\.\r\n",$response)){
            if(substr($response,0,4) == "\r\n") break;
            $output[$i] = $response;
            $i++;
            $response = $this->_getnextstring();
        }
        $output[$i++] = "</HEADER> \r\n";

        $response = "<MESSAGE> \r\n";
        
        while(!eregi("^\.\r\n",$response)){
            $output[$i] = $response;
            $i++;
            $response = $this->_getnextstring();
        }

        $output[$i] = "</MESSAGE> \r\n";

        if(!$this->_logging("Complete.")) return $output["error"] = $this->error;
        
        return $output;
    }


    /*
       Function _check_state()
       Access: Private
       
    */
    
    function _checkstate($string){
        // Check for delete_mail func
        if($string == "delete_mail" || $string == "get_office_status" || $string == "get_mail" || $string == "get_top" || $string == "noob" || $string == "reset" || $string == "uidl" || $string == "stats"){
            $state = "TRANSACTION";
            if($this->state != $state){
                $this->error = "POP3 $string() - Error: state must be in \"$state\" mode !!! Your state: \"$this->state\" !!!";
                return FALSE;
            }
            return TRUE;
        }

        // Check for connect func
        if($string == "connect"){
            $state = "DISCONNECTED";
            $state_1 = "UPDATE";
            if($this->state == $state or $this->state == $state_1){
                return TRUE;
            }
            $this->error= "POP3 $string() - Error: state must be in \"$state\" or \"$state_1\" mode !!! Your state: \"$this->state\" !!!";
            return FALSE;

        }

        // Check for login func
        if($string == "login"){
            $state = "AUTHORIZATION";
            if($this->state != $state){
                $this->error = "POP3 $string() - Error: state must be in \"$state\" mode !!! Your state: \"$this->state\" !!!";
                return FALSE;
            }
            return TRUE;
        }
        


        $this->error = "POP3 _checkstate() - Error: Not allowed string given !!!";
        return FALSE;
    }

    /*
      Function delete_mail($msg_number)
      Access: Public
      
      
    */

    function delete_mail($msg_number){
         if(!$this->socket){
            $this->error = "POP3 delete_mail() - Error: No connection avalible.";
            return FALSE;
        }
        if(!$this->_checkstate("delete_mail")) return FALSE;

        // Delete Mail
        $response = "";
        $cmd = "DELE $msg_number";
        if(!$this->_logging($cmd)) return FALSE;
        if(!$this->_putline($cmd)) return FALSE;
        $response = $this->_getnextstring();
        if(!$this->_logging($response)) return FALSE;
        if(substr($response,0,1) != "+"){
           $this->error = "POP3 delete_mail() - Error: ".$response;
           return FALSE;
        }

        return TRUE;
    }
        

    /*
      Function get_office_status
      Access: Public
      
      Output an array
      
      Array
     (
        [count_mails] => 3
        [octets] => 2496
        [1] => Array
              (
                  [size] => 832
                  [uid] => 617999468
              )

        [2] => Array
              (
                  [size] => 882
                  [uid] => 617999616
              )

        [3] => Array
              (
                  [size] => 1726
                  [uid] => 617999782
              )

        [error] => No Errors
     )

    */
    function get_office_status(){

        if(!$this->socket){
            $this->error = "POP3 get_office_status() - Error: No connection avalible.";
            $this->_cleanup();
            return $output["error"] = $this->error;
        }
        
        if(!$this->_checkstate("get_office_status")){
            $this->_cleanup();
            return $output["error"] = $this->error;
        }
        
        // Put the "STAT" Command !!!
        $response = "";
        $cmd = "STAT";
        if(!$this->_logging($cmd)) return $output["error"] = $this->error;
        if(!$this->_putline($cmd)) return $output["error"] = $this->error;

        $response = $this->_getnextstring();

        if(!$this->_logging($response)) return $output["error"] = $this->error;

        if(substr($response,0,3) != "+OK"){
            $this->error = "POP3 get_office_status() - Error: ".$response;
            if(!$this->_logging($this->error)) return $output["error"] = $this->error;
            $this->_cleanup();
            return $output["error"] = $this->error;
        }
        // Remove "\r\n" !!!
        $response = trim($response);
        
        $array = explode(" ",$response);
        $output["count_mails"] = $array[1];
        $output["octets"] = $array[2];
        
        unset($array);
        $response = "";
        
        if($output["count_mails"] != "0"){

            // List Command
            $cmd = "LIST";
            if(!$this->_logging($cmd)) return $output["error"] = $this->error;
            if(!$this->_putline($cmd)) return $output["error"] = $this->error;
            $response ="";
            $response = $this->_getnextstring();

            if(!$this->_logging($response)) return $output["error"] = $this->error;

            if(substr($response,0,3) != "+OK"){
                $this->error = "POP3 get_office_status() - Error: ".$response;
                $this->_cleanup();
                return $output["error"] = $this->error;
            }
            // Get Message Number and Size !!!
            $response = "";
            for($i=0;$i<$output["count_mails"];$i++){
                $nr=$i+1;
                $response = trim($this->_getnextstring());
                if(!$this->_logging($response)) return $output["error"] = $this->error;
                $array = explode(" ",$response);
                $output[$nr]["size"] = $array[1];
                $response = "";
                unset($array);
                unset($nr);
            }
            // $response = $this->_getnextstring();
            // echo "<b>".$response."</b>";

            // Check is server send "."
            if(trim($this->_getnextstring()) != "."){
                $this->error = $output["error"] = "POP3 get_office_status() - Error: Server does not send "." at the end !!!";
                $this->_cleanup();
                return $output["error"] = $this->error;
            }
            if(!$this->_logging(".")) return $output["error"] = $this->error;

            // UIDL Command
            $cmd = "UIDL";
            if(!$this->_logging($cmd)) return $output["error"] = $this->error;
            if(!$this->_putline($cmd)) return $output["error"] = $this->error;
            $response = "";
            $response = $this->_getnextstring();
            if(!$this->_logging($response)) return $output["error"] = $this->error;
            if(substr($response,0,3) != "+OK"){
                $this->error = "POP3 get_office_status() - Error: ".$response;
                $this->_cleanup();
                return $output["error"] = $this->error;
            }
            // Get UID's
            $response = "";
            for($i=0;$i<$output["count_mails"];$i++){
                $nr=$i+1;
                $response = trim($this->_getnextstring());
                if(!$this->_logging($response)) return $output["error"] = $this->error;
                $array = explode(" ", $response);
                $output[$nr]["uid"] = $array[1];
                $response = "";
                unset($array);
                unset($nr);
            }

            // Check is server send "."
            if(trim($this->_getnextstring()) != "."){
                $this->error = "POP3 get_office_status() - Error: Server does not send "." at the end !!!";
                $this->_cleanup();
                return $output["error"] = $this->error;
            }
            if(!$this->_logging(".")) return $output["error"] = $this->error;
        }

        return $output;
        
    }

    /*
      Function save2file($message,$filename)
      Access: Public
      
      return written bytes or "false"
    */
    function save2file($message,$filename){
        $this->file_fp = fopen($filename,"w+");
        if(!$this->file_fp){
            $this->error = "POP3 save2file() - Error: Can't open file in write mode. (".$filename.")";
            if(!$this->_logging($this->error)) return FALSE;
            $this->_cleanup();
            return FALSE;
        }
        if(!$this->_logging("LOG FILE: File ".$filename." created.")){
            $this->_cleanup();
            return FALSE;
        }
        $count_bytes = "0";
        
        for($i=0;$i<count($message);$i++){
            $line = $message[$i];
            $str_len = strlen($line);
            $count_bytes = $count_bytes + $str_len;
            if(!fputs($this->file_fp,$line,$str_len)){
                $this->error = "POP3 save2file() - Error: Can't write string to file (".$filename.") !!!";
                if(!$this->_logging($this->error)) return FALSE;
                $this->_cleanup();
                return FALSE;
            }
            unset($line);
        }
        if(!$this->_logging("LOG FILE: File ".$filename." (".$count_bytes." Bytes) written.")){
            $this->_cleanup();
            return FALSE;
        }
        
        return $count_bytes;
    }
            




    /*
      Function noob()
      Access: Public
    */

    function noob(){
        if(!$this->socket){
            $this->error = "POP3 noob() - Error: No connection avalible.";
            if(!$this->_logging($this->error)) return FALSE;
            // $this->_cleanup();
            return FALSE;
        }
        if(!$this->_checkstate("noob")) return FALSE;
        
        $cmd = "NOOB";

        if(!$this->_logging($cmd)) return FALSE;
        if(!$this->_putline($cmd)) return FALSE;

        $response = "";
        $response = $this->_getnextstring();
        if(!$this->_logging($response)) return FALSE;
        if(substr($response,0,1) != "+"){
            $this->error = "POP3 noob() - Error: ".$response;
            return FALSE;
        }

        return TRUE;
    }

    /*
      Function reset()
      Access: Public
    */
    function reset(){
        if(!$this->socket){
            $this->error = "POP3 reset() - Error: No connection avalible.";
            if(!$this->_logging($this->error)) return FALSE;

            return FALSE;
        }

        if(!$this->_checkstate("reset")) return FALSE;

        $cmd = "RSET";
        
        if(!$this->_logging($cmd)) return FALSE;
        if(!$this->_putline($cmd)) return FALSE;
        $response = "";
        $response = $this->_getnextstring();
        if(!$this->_logging($response)) return FALSE;
        if(substr($response,0,1) != "+"){
            $this->error = "POP3 reset() - Error: ".$response;
            return FALSE;
        }
        return TRUE;
    }
    /*
      Function stats
      Access: Private
      Get only count of mails and size of maildrop !!!
    */

    function _stats(){
        if(!$this->socket){
            $this->error = "POP3 _stats() - Error: No connection avalible.";
            return FALSE;
        }



        if(!$this->_checkstate("stats")) return FALSE;
        $cmd = "STAT";
        if(!$this->_logging($cmd)) return FALSE;
        if(!$this->_putline($cmd)) return FALSE;

        $response = $this->_getnextstring();
        if(substr($response,0,1) != "+"){
            $this->error = "POP3 _stats() - Error: ".$response;
            return FALSE;
        }
        $response = trim($response);

        $array = explode(" ",$response);
        
        $output["count_mails"] = $array[1];
        $output["octets"] = $array[2];
        

        return $output;
    }



    /*
      Function uidl($msg_number = "0")
      Access: Public
    */
    function uidl($msg_number = "0"){
        if(!$this->socket){
            $this->error = "POP3 uidl() - Error: No connection avalible.";
            return FALSE;
        }

        if(!$this->_checkstate("uidl")) return FALSE;
        
        
        
        if($msg_number == "0"){
            $cmd = "UIDL";
            
            // Get count of mails
            $mails = $this->_stats();
            if(!$mails) return FALSE;

            if(!$this->_logging($cmd)) return FALSE;
            if(!$this->_putline($cmd)) return FALSE;

            $response = "";
            $response = $this->_getnextstring();
            if(!$this->_logging($response)) return FALSE;
            if(substr($response,0,1) != "+"){
               $this->error = "POP3 uidl() - Error: ".$response;
               return FALSE;
            }
            $response = "";
            for($i = 1; $i <= $mails["count_mails"];$i++){
                $response = $this->_getnextstring();
                if(!$this->_logging($response)) return FALSE;
                $response = trim($response);
                $array = explode(" ",$response);
                $output[$i] = $array[1];
            }
            return $output;
        }else{
            $cmd = "UIDL $msg_number";
            
            if(!$this->_logging($cmd)) return FALSE;
            if(!$this->_putline($cmd)) return FALSE;

            $response = "";
            $response = $this->_getnextstring();
            if(!$this->_logging($response)) return FALSE;
            if(substr($response,0,1) != "+"){
               $this->error = "POP3 uidl() - Error: ".$response;
               return FALSE;
            }
            
            $response = trim($response);
            
            $array = explode(" ",$response);
            
            $output[$array[1]] = $array[2];


            return $output;
        }

    }

    /*
      Function close()
      Access: Public
      
      Close POP3 Connection
    */

    function close(){

        $response = "";
        $cmd = "QUIT";
        if(!$this->_logging($cmd)) return FALSE;
        if(!$this->_putline($cmd)) return FALSE;

        if($this->state == "AUTHORIZATION"){
            $this->state = "DISCONNECTED";
        }elseif($this->state == "TRANSACTION"){
            $this->state = "UPDATE";
        }
        
        $response = $this->_getnextstring();
        
        if(!$this->_logging($response)) return FALSE;
        if(substr($response,0,1) != "+"){
            $this->error = "POP3 close() - Error: ".$response;
            return FALSE;
        }
        $this->socket = FALSE;

        $this->_cleanup();

        return TRUE;
    }




    /*
      Function _getnextstring()
      Access: Private
    */

    function _getnextstring($buffer_size="512"){
        $buffer = "";
        $buffer = fgets($this->socket,$buffer_size);
        $this->socket_status = socket_get_status($this->socket);
        if($this->socket_status["timed_out"]){
            $this->_cleanup();
            return "POP3 _getnextstring() - Socket_Timeout_reached.";
        }
        unset($this->socket_status);
        return $buffer;
    }

    /*
      Function _putline()
      Access: Private
    */
    function _putline($string){
        $line = "";
        $line = $string."\r\n";
        if(!fwrite($this->socket,$line,strlen($line))){
            $this->error = "POP3 _putline() - Error while send \" $string \". -- Connection closed.";
            $this->_cleanup();
            return FALSE;
        }
        return TRUE;
    }

    /*
      Function _parse_banner( $server_text )
      Access: Private
    */
    function _parse_banner ( $server_text ){
		$outside = true;
		$banner = "";
		$length = strlen($server_text);
		for($count = 0; $count < $length; $count++)
		{
			$digit = substr($server_text,$count,1);
			if($digit != "")
			{
				if( (!$outside) and ($digit != '<') and ($digit != '>') )
				{
					$banner .= $digit;
				}
				if ($digit == '<')
				{
					$outside = false;
				}
				if($digit == '>')
				{
					$outside = true;
				}
			}
		}
		$banner = trim($banner);
		return "<$banner>";
	}
 
    /*
      Function save2mysql($message,$unique_id,$read,$table,$mysql_socket)
      Access: Public
      
      
    */
    function save2mysql($message,$unique_id,$read="0",$table,$mysql_socket){

        $count_lines = count($message);

        $count_bytes = "0";
        if(!$this->_logging("LOG MySQL: Write mail (uid '".$unique_id."') to table ('".$table."') !!")) return FALSE;

        for($i = 0;$i < $count_lines;$i++){
            $linetext = $message[$i];
            $count_bytes = $count_bytes + strlen($linetext);
            $query = 'INSERT INTO `'.$table.'` ( `id` , `unique-id` , `savedate` , `linenumber` , `linetext` , `read` ) VALUES ( \'\', \''.$unique_id.'\', NOW(), \''.$i.'\', \''.$linetext.'\', \''.$read.'\' )';
            $result = mysql_query($query,$mysql_socket) or die(mysql_error());

        }

        if(!$this->_logging("LOG MySQL: Write mail (uid '".$unique_id."') complete. ( ".$count_bytes." Bytes written)")) return FALSE;

        return $count_bytes;
        

    }


}

?>
