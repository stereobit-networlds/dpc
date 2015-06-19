<?php

$__DPCSEC['RCSMSGUI_DPC']='1;1;1;1;1;1;1;1;2';

if ( (!defined("RCSMSGUI_DPC")) && (seclevel('RCSMSGUI_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCSMSGUI_DPC",true);

$__DPC['RCSMSGUI_DPC'] = 'rcsmsgui';

//$d = GetGlobal('controller')->require_dpc('sms/smsgui.dpc.php');
//require_once($d); 

$e = GetGlobal('controller')->require_dpc('sms/easysms.lib.php');
require_once($e); 

$__EVENTS['RCSMSGUI_DPC'][0]='cpsmsgui';
$__EVENTS['RCSMSGUI_DPC'][1]='cpsmssend';

$__ACTIONS['RCSMSGUI_DPC'][0]='cpsmsgui';
$__ACTIONS['RCSMSGUI_DPC'][1]='cpsmssend';

$__LOCALE['RCSMSGUI_DPC'][0]='RCSMSGUI_DPC;Send SMS;Αποστολή SMS';
$__LOCALE['RCSMSGUI_DPC'][1]='_INCLSUBS;Subscribers;Συνδρομητές';
$__LOCALE['RCSMSGUI_DPC'][2]='_INCLALL;All;Όλοι';
$__LOCALE['RCSMSGUI_DPC'][3]='_FROM;From;Από';
$__LOCALE['RCSMSGUI_DPC'][4]='_TO;To;Σε';
$__LOCALE['RCSMSGUI_DPC'][5]='_SUBJECT;Subject;Θέμα';
$__LOCALE['RCSMSGUI_DPC'][6]='_SUCCESS;Success;Επιτυχώς';
$__LOCALE['RCSMSGUI_DPC'][7]='_FAILURE;Failure;Ανεπιτυχώς';
$__LOCALE['RCSMSGUI_DPC'][8]='_NOSMSPOOL;No SMS pool;Δεν υπάρχουν SMS. Παρακαλώ επικοινωνήστε με τον διαχειριστή.';
$__LOCALE['RCSMSGUI_DPC'][9]='_SENDTO;Send to;Αποστολή πρός';
$__LOCALE['RCSMSGUI_DPC'][10]='_SMSSEND;SMS Send;Απεσταλμένα SMS';
$__LOCALE['RCSMSGUI_DPC'][11]='_SMSSUM;SMS total;SMS που στάλθηκαν';
$__LOCALE['RCSMSGUI_DPC'][12]='_SMSREMAIN;SMS remain;Υπόλοιπο';
$__LOCALE['RCSMSGUI_DPC'][13]='_SMSSUBJECT;Subject;Θέμα';
$__LOCALE['RCSMSGUI_DPC'][14]='_TESTTEXT;Test;Δοκιμή';
$__LOCALE['RCSMSGUI_DPC'][15]='_receiver;Receiver;Παραλήπτης';
$__LOCALE['RCSMSGUI_DPC'][16]='_status;Status;Κατάσταση';
$__LOCALE['RCSMSGUI_DPC'][17]='_subject;Subject;Θέμα';
$__LOCALE['RCSMSGUI_DPC'][18]='_body;Text;Κειμένο';

class rcsmsgui /* extends smsgui */{
   
    var $prpath;
    var $user, $pwd;
    var $message;
    var $title;
	var $sender;
	var $bulk_sms_counter;
	var $sendok;
	 
	function rcsmsgui() {
	
	  $this->prpath = paramload('SHELL','prpath');	
	  $this->title = localize('RCSMSGUI_DPC',getlocal());
	  $this->sender = paramload('ID','instancename');
	  
	  $this->smstable = paramload('RCSMSGUI','smstable');
	  $this->smsfield = paramload('RCSMSGUI','smsfield');	  	  
	  
      $char_set  = arrayload('SHELL','char_set');	  
      $charset  = paramload('SHELL','charset');	  		
	  if (($charset=='utf-8') || ($charset=='utf8'))
	    $this->encoding = 'utf-8';
	  else  
	    $this->encoding = $char_set[getlocal()]; 
			  
	  $this->user = paramload('RCSMSGUI','user');
	  $this->pwd = paramload('RCSMSGUI','pwd');	  
	  
	  if ($this->interface = paramload('RCSMSGUI','interface')) {
	    switch ($this->interface) {
	      case 'clickatel' : $this->{$this->interface} = new clickatel(); break;
	      case 'smsgui'    : $this->{$this->interface} = new smsgui(); break;		
		  //default          : $this->{$this->interface} = new smsgui();  //easy sms...no interface
	    }	  
	  }
	  else
	    $this->interface = null; //easysms
	  
	  //smsgui::smsgui();
	  
	  $this->message = null;
	  $this->sendok = false;
	}

    function event($evn=null) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//
	   /////////////////////////////////////////////////////////////	
	
	  switch ($evn) {
	    case 'cpsmssend':  $this->sendok = $this->send(GetParam('sms_text'));
		                   break; 
		default         :  //smsgui::event($evn);
		                   if ($this->interface)
		                     $this->{$this->interface}->event($evn);
	  }	
	}	

	function action($act=null) {
	
        if (!GetReq('editmode'))
		  $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);
		  
		switch ($act) {  	
	      case 'cpsmssend':  $out .= $this->message;
		                     $out .= $this->bulk_sms_info(1,'&nbsp;');
		                     $out .= $this->form();
							 $out .= $this->viewSms();
		                     break; 
		  default         : 	    
	                         //$ret = $this->sendsms("Test message",null);
		                     //$ret = $this->{$this->interface}->sendsms("Test message",null);
							 /*
		                     $w = new window("SMS",$this->message.'...'.$ret);
		                     $out .= $w->render();
		                     unset($w);
		                     */ 
							 $out .= $this->bulk_sms_info(0,'&nbsp;');
		                     $out .= $this->form();
							 $out .= $this->viewSms();
		}	
	    return ($out);
	}
	
	function form($cmd=null) {
       $from =  GetParam('from')?GetParam('from'):$this->sender; 
	   $to   =  GetParam('to')?GetParam('to'):'0000000000';
	   $subject = GetParam('subject')?GetParam('subject'):localize('_SUBJECT',getlocal());
	   $message = GetParam('sms_text')?GetParam('sms_text'):localize('_TESTTEXT',getlocal());	   
       $mycmd = $cmd?$cmd:'cpsmssend';
	   
       $filename = seturl("t=$mycmd&editmode=1");	   	   
   
	   //params form
       $out  .= "<FORM action=". "$filename" . " method=post class=\"thin\">";
       $out .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	       
		  		 	
       $out .= "<B>" . localize('_FROM',getlocal()) . "</B>" . "<input type=\"text\" name=\"from\" value=\"".$from."\" maxlenght=\"64\" size=25 READONLY>"; 			  
       $out .= "<B>" . localize('_TO',getlocal()) . "</B>" . "<input type=\"text\" name=\"to\" value=\"".$to."\" maxlenght=\"64\" size=25>"; 	
       $out .= "<B>" . localize('_SUBJECT',getlocal()) . "</B>" . "<input type=\"text\" name=\"subject\" value=\"".$subject."\" maxlenght=\"64\" size=25>";
	   
	   $options = "<B>" . localize('_INCLSUBS',getlocal()) . "</B>&nbsp;<input type=\"checkbox\" name=\"includesubs\">";		
       $options .= "<B>" . localize('_INCLALL',getlocal()) . "</B>&nbsp;<input type=\"checkbox\" name=\"includeall\" ><br>";	   
	     	   
	   $out .= "<br>" . $options;
	      			  
       $out .= "<DIV class=\"monospace\"><TEXTAREA style=\"width:100%\" NAME=\"sms_text\" ROWS=10 cols=60 wrap=\"virtual\">";
	   $out .=  $message;		 
       $out .= "</TEXTAREA></DIV>";			   
		  	   		  	   
	  
       $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"$mycmd\">&nbsp;";			
			
       $out .= "<input type=\"submit\" name=\"Submit\" value=\"Send\">";		
       $out .= "</FONT></FORM>"; 	  	
		
       //$winout = setError($sFormErr . $this->message);
	   //$winout = $this->message;
	   		
	   $wina = new window($this->title,$out);
	   $winout .= $wina->render();
	   unset ($wina);
		
	   return ($winout);  		   		
	}	
	
	function send($smsmessage,$to=null) {
	   $include_subs = GetParam('includesubs');
	   $include_all = GetParam('includeall');	  
	   $subscribers = $include_subs?$include_subs:$include_all;//one or another	
	   $smspool = $this->sms_pool();
	   $st[0] = null;
	   $st[1] = null;	   
	   
	   if ($this->sms_exist()==false) {
	     $this->message = localize('_NOSMSPOOL',getlocal());
		 return false;
	   }
	     
	   if (($smsmessage = GetParam('sms_text')) && ($sms_no = GetParam('to'))) {
	   
         if ($this->interface) {
	       $this->message .= $this->{$this->interface}->sendsms($smsmessage,$sms_no);
		   $st[0] = null;
		   $st[1] = null;		   		   
		 }  
		 else {
		   $sms_state = $this->easysms_sendsms($smsmessage,$sms_no);
		   if ($sms_state) {
		     $st = explode('|',$sms_state);  
			 if ($st[0]==1)
			   $this->message = "<br>".localize('_SENDTO',getlocal()).":" .$sms_no.":". localize('_SUCCESS',getlocal());
			 else
			   $this->message = "<br>".localize('_SENDTO',getlocal()).":" .$sms_no.":". localize('_FAILURE',getlocal());			   
		   }	 
		   else {
		     $st[0] = null;
		     $st[1] = null;		   	 
		   }	 
		 }  
		   
	     //save 2 db
	     $this->savesms($smsmessage,$sms_no,GetParam('subject'),$st[0],$st[1]);	  	

	   
         if ($subscribers) {	 
	   
           $subs = $this->getsubsms($include_all);
		   //echo $subs;
		 
		   if (stristr($subs,';')) {
		 
		     $subs_array = explode(';',$subs);
		   
		     foreach ($subs_array as $i=>$sub_sms_no) {
		       
			   if ($this->sms_exist()==true) {
			   
                 if ($this->interface) {			   
 	               $this->message .= $this->{$this->interface}->sendsms($smsmessage,$sub_sms_no);
				   $st[0] = null;
				   $st[1] = null;
				 }  
		         else {
		           $sms_state = $this->easysms_sendsms($smsmessage,$sms_no); 
				   if ($sms_state) {
		   		     $st = explode('|',$sms_state);  
			         if ($st[0]==1)
			           $this->message .= "<br>".localize('_SENDTO',getlocal()).":" .$sub_sms_no.":". localize('_SUCCESS',getlocal());
			         else
			           $this->message .= "<br>".localize('_SENDTO',getlocal()).":" .$sub_sms_no.":". localize('_FAILURE',getlocal());					 				   
				   }	 
				   else {
		             $st[0] = null;
		             $st[1] = null;				   	 
				   }	 
				 }  
				   
	             //save 2 db
	             $this->savesms($smsmessage,$sub_sms_no,GetParam('subject'),$st[0],$st[1]);

			   }//sms exist
			   else 
			     $this->message .= "<br>".localize('_SENDTO',getlocal()).":" .$sub_sms_no.":". localize('_NOSMSPOOL',getlocal());	 
		     }
		   }	     	 	   
	     } //if subs
	   }//if sms
	   
	   return true;		 

	}
	
	function savesms($smsmessage,$to=null,$subject=null,$sms_state=null,$sms_code=null) {
       $db = GetGlobal('db');
	   $datetime = date('Y-m-d h:s:m');
	   $active = 1;	   
	   
	   $sSQL = "insert into smsqueue (timein,active,sender,receiver,subject,body,status,origin) ";
		   
	   $sSQL .=  "values (" .
				 $db->qstr($datetime) . "," . $active . "," .
		 	     $db->qstr(strtolower($this->sender)) . "," . $db->qstr(strtolower($to)) . "," .
			     $db->qstr($subject) . "," . 
				 $db->qstr($smsmessage) . "," .
				 $db->qstr($sms_state) . "," .				 
				 $db->qstr($sms_code);
				 
	   $sSQL .= ")"; 
	   
	   //echo $sSQL,'<br>';			
	   $result = $db->Execute($sSQL,1);	   				 	   	
	}
	
	function getsubsms($all_users=null) {
       $db = GetGlobal('db'); 
	   $table = $this->smstable?$this->smstable:'customers';
	   $field = $this->smsfield?$this->smsfield:'voice2';	   		
	   
	   if ($all_users==null) {//select only subscriber
	     $sSQL .="SELECT ". $table.".".$field." FROM " . $table . ",users WHERE " . $table.".".$field . " is not null AND";	   
	     $sSQL .= " $table.code2=users.code2 AND users.lname='SUBSCRIBER' and users.subscribe=1";
	   }
	   else
	     $sSQL .="SELECT ". $field." FROM " . $table . " WHERE " . $field . " is not null";	   
	   	 	 
	   //echo $sSQL;	
	   
       $result = $db->Execute($sSQL,2);
	   //print_r($result);
	   
	   $this->bulk_sms_counter = $db->Affected_Rows();	
	   //$this->message .= "<br>Bulk SMS send to:" . $this->bulk_sms_counter . " receipients";
	   
	   if (count($result)>0) {		   
	     foreach ($result as $n=>$rec) {	     
		   $ret[] = $rec[$field];
		 }
	   }
	   //print_r($ret); echo 'a';	 
	   if (!empty($ret)) {  
	     $out = implode(';',$ret);
       }
	   //echo '>',$out;
	   return $out;	
	}
	
	function bulk_sms_info($issend=null,$newline='&nbsp;') {
	  if ($this->sendok)
        $send = $issend?1:0;
	  else
	    $send = 0;	
	  $sms_counter = $this->bulk_sms_counter?$this->bulk_sms_counter+$send:$send;
	  $sms_send_total = $this->total_sms();
	  $sms_pool = $this->sms_pool();
	  $sms_remain = intval($sms_pool) - intval($sms_send_total);
	
	  $ret = $newline . localize('_SMSSEND',getlocal()) . ":" . $sms_counter;
	  $ret .= $newline . localize('_SMSSUM',getlocal()) . ":" . $sms_send_total;
	  $ret .= $newline . localize('_SMSREMAIN',getlocal()) . ":" . $sms_remain;	  	

	  return ($ret);  
	}
	
	function total_sms() {
       $db = GetGlobal('db'); 
	   $ret = 0;
	   
       $sSQL .= "SELECT count('id') from smsqueue WHERE status=1"; //where active=1....
 
       $result = $db->Execute($sSQL,2);
	   //print_r($result);
	   if (count($result)>0) {		   
	     foreach ($result as $n=>$rec) {	     
		   $ret = $rec[0];
		 }
	   }
	   	   		   	
	   return $ret;
	}
	
	function sms_pool() {
	
	   $smspool = file_get_contents($this->prpath.'/smspool.txt');
	   //echo $smspool;
	   return (intval($smspool));   
	}
	
	function sms_exist() {
	  $sms_send_total = $this->total_sms();
	  $sms_pool = $this->sms_pool();
	  $sms_remain = intval($sms_pool) - intval($sms_send_total);
	  
	  if ($sms_remain>0)
	    return true;
      else
	    return false;			
	}	
	
	
	//EASY SMS ..must include lib
	function easysms_sendsms($smsmessage,$to=null) {
	
	  $mySMS = new EasySMS('balexiou', '92527');
	  //get the account balance
	  echo 'BALANCE:' . $mySMS->getbalance();
	  //get the delivery reports
	  echo 'REPORT:' . $mySMS->getDeliveryReports();
	  //send sms
	  $ret = $mySMS->sendSMS($to,		            //the target mobile number
						   $smsmessage,			//the message
						   true,  	 		    //request delivery report
						   false,				//not as flash sms
						   $this->sender		//set the originator
					       );
						   
	  echo 'SMS:' . $ret;					   
	  
	  return $ret;
	}	

	
	//SMS GUI
	
	function smsgui_sendsms($smsmessage,$to=null) {
	
	  if ($to)
	    $sendto = $to;
	  else 
	    $sendto = $this->tolist;	
		
      // your user ID on smsgui.com
      $userid = rawurlencode($this->user);
      // md5 is used to hide the clear-text password when transmitted over the Internet
      $md5password = md5($this->pwd);
      // comma-separated list of group names, and/or mobile numbers in international format
      $to = rawurlencode( $sendto );
      // message to send
      $message = rawurlencode($smsmessage);
      // if you want to send a message Unicode-encoded in UCS2, you first need to convert
      // the message from UTF-8 (assuming you have UTF-8 encoding in the HTML page, or whatever source)
      //$message = bin2hex( iconv("UTF-8","UTF-16BE","YOUR_MESSAGE") ); // UTF-16BE = UCS-2
      $lang = 'ucs2';
      // the smsgui.com URL you are trying to send the parameters to (no need to edit)
      $url = 'http://www.smsgui.com/send.php';
      $fullUrl = "$url?userid=$userid&md5password=$md5password&to=$to&message=$message";//&lang=$lang";
	  //echo $fullUrl;
      // try to send the parameters to smsgui.com
      // if the connection is successful, the result is received in the $contents array
      $contents = @file( $fullUrl );
      if( !$contents ) {
	    //echo "<br>Error accessing URL: $fullUrl";
		//if ($echoing) 
		$this->message = "Error: sending SMS!";
	  }	
      else {
        $code = $contents[0][0]; // read just the very first character from the result
		//if ($echoing) {
          switch( $code ){
			case '0': $this->message =  "Error: " . $contents[0]; break;
			case '1': $this->message = "Warning: " . $contents[0]; break;
			case '2': $this->message = "OK: " . $contents[0]; break;
			default : $this->message = "$code - Read failed, or unrecognized code!";
          }
		//}
      }
	  
	  return ($code);
	}
		
	//CLICKATELL	
/*
	user=balexiou
    password=<Clickatell Account Password>
    api_id=3239691
    to=<Mobile Number(s)> (comma separated)
    text=<SMS Message>	 
	*/
	function clickatell_sendsms($smsmessage,$to=null,$echoing=0) {
	
	  if ($to)
	    $sendto = substr_replace($to,'+',0,0); //add + at the beggining of the to
	  else 
	    $sendto = $this->tolist;//+ included	
		
      // your user ID on clickatell
      $userid = $this->user;
      $password = $this->pwd;
	  $api = $this->httpapi;
      // comma-separated list of group names, and/or mobile numbers in international format
      $to = $sendto;
      // message to send
      $message = rawurlencode($smsmessage);
      // if you want to send a message Unicode-encoded in UCS2, you first need to convert
      // the message from UTF-8 (assuming you have UTF-8 encoding in the HTML page, or whatever source)
      //$message = bin2hex( iconv("UTF-8","UTF-16BE","YOUR_MESSAGE") ); // UTF-16BE = UCS-2
      $lang = 'ucs2';
 
      $url = 'http://api.clickatell.com/http/sendmsg';//?user=balexiou&password=PASSWORD&api_id=3239691&to=306936550848&text=Message';
      $fullUrl = "$url?user=$userid&password=password&api=$api&to=$to&text=$message";//&lang=$lang";
	  //echo $fullUrl;
      // try to send the parameters to smsgui.com
      // if the connection is successful, the result is received in the $contents array
      $this->contents = @file( $fullUrl );
      if( !$contents ) {
	    //echo "<br>Error accessing URL: $fullUrl";
		if ($echoing) echo "Error: sending SMS!";
	  }	
      else {
        $code = $contents[0][0]; // read just the very first character from the result
		if ($echoing) {
          switch( $code ){
			case '0': echo "Error: " . $this->contents[0]; break;
			case '1': echo "Warning: " . $this->contents[0]; break;
			case '2': echo "OK: " . $this->contents[0]; break;
			default : echo "$code - Read failed, or unrecognized code!";
          }
		}
      }
	  
	  return ($code . $this->contents[0]);
	}
	
	
	function viewSms() {
       $db = GetGlobal('db');
	   $a = GetReq('a');
       $UserName = GetGlobal('UserName');	   
	   
	   $apo = GetParam('apo'); //echo $apo;
	   $eos = GetParam('eos');	//echo $eos;   

       $myaction = seturl("t=cpsmsgui");	   
	   
       $out .= "<form method=\"POST\" action=\"";
       $out .= "$myaction";
       $out .= "\" name=\"Transview\">";		   

	 
	   $out .= 	$this->getSmsList();	 
		 
			 
       $out .= "<input type=\"hidden\" name=\"FormName\" value=\"Smsview\">";
       $out .= "</FORM>";			 		   
			 	 					
	   return ($out);	
	
	}	
	
	function getSmsList() {
       $db = GetGlobal('db');
       $UserName = GetGlobal('UserName');	
	   //$name = $UserName?decode($UserName):null;		   
       //echo GetReq('col');
	   	   
	     $sSQL = "select id,receiver,status,subject,body,origin,active from smsqueue ";
		 if ($col = GetReq('col'))
		   $sSQL .= "order by " . $col;
		 else
		   $sSQL .= "order by id"; 
		   
		 if (GetReq('sort')<0)
		   $sSQL .= ' DESC';
		   
		 //echo $sSQL;		 
				 
				 
	     $res = $db->Execute($sSQL,2);
	     //print_r ($res);
		 $i=0;
	     if (!empty($res)) { 
	       foreach ($res as $n=>$rec) {
		    $i+=1;
				
			
            $transtbl[] = $i . ";" . 
                         $rec[0] . ";" . $rec[1] . ";" . $rec[2] . ";" . $rec[3] . ";" .
						 $rec[4] . ";" . $rec[5] . ";" . $rec[6] . ";" . $rec[7];						 					 	   
		   }
		   
           //browse
		   //print_r($transtbl); 
		   $ppager = GetReq('pl')?GetReq('pl'):50;
           $browser = new browse($transtbl,null,$this->getpage($transtbl,$this->searchtext));
	       $out .= $browser->render("transview",$ppager,$this,1,0,0,0);
	       unset ($browser);	
		      
	     }
		 else {
           //empty message
	       $w = new window(null,localize('_EMPTY',getlocal()));
	       $out .= $w->render("center::40%::0::group_win_body::left::0::0::");//" ::100%::0::group_form_headtitle::center;100%;::");
	       unset($w);

		 }		 	
	   
	   return ($out);
	}
		
	function getpage($array,$id){
	
	   if (count($array)>0) {
         //while(list ($num, $data) = each ($array)) {
         foreach ($array as $num => $data) {
		    $msplit = explode(";",$data);
			if ($msplit[1]==$id) return floor(($num+1) / $this->pagenum)+1;
		 }	  
		 
		 return 1;
	   }	 
	}
		
    function browse($packdata,$view) {
	
	   $data = explode("||",$packdata); //print_r($data);
	
       $out = $this->viewusrs($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9]);

	   return ($out);
	}	
	
    function viewusrs($i,$id,$receiver,$status,$subject,$body,$active,$timein,$timeout) {
	   $p = GetReq('p');
	   $a = GetReq('a');
	   
	   $del_link = seturl("t=cpsmsgui&rec=$id&editmode=1" , $i);
	   $name_link = seturl("t=cpsmsgui&rec=$id&editmode=1" , $receiver);								  	   
	   $status_link = seturl("t=cpsmsgui&rec=$id&editmode=1" , $status);	
	   $activ_link = seturl("t=cpsmsgui&rec=$id&editmode=1" , $subject);	   			  
	   	   
	   $data[] = $i;   
	   $attr[] = "left;10%";	   
	   
	   $data[] = $receiver;   
	   $attr[] = "left;15%";   
	   
	   if ($status==1)
	     $st = localize('_SUCCESS',getlocal());
	   elseif ($status=-1)
	     $st = localize('_FAILURE',getlocal());
	   else
	     $st = '&nbsp;';	 	 
	   $data[] = $st;   
	   $attr[] = "left;15%";	      
	   
	   $data[] = $subject?$subject:'&nbsp;';
	   $attr[] = "left;25%";	
	   
	   $data[] = $body;   
	   $attr[] = "left;35%";		   
	   
	   
	   $myarticle = new window('',$data,$attr);
       $line = $myarticle->render("center::100%::0::group_dir_body::left::0::0::");
	   unset ($data);
	   unset ($attr);
	   
       if ($this->details) {//disable cancel and delete form buttons due to form elements in details????
	     $mydata = $line . '<br/>' . $this->details($id);
	     $cartwin = new window2($id . '/' . $status,$mydata,null,1,null,'HIDE',null,1);
	     $out = $cartwin->render();//"center::100%::0::group_article_body::left::0::0::"
	     unset ($cartwin);		   
	   }	
	   else {   
		 $out .= $line . '<hr>';
	   }	   
	   

	   return ($out);
	}		
	
	function headtitle() {
	   $p = GetReq('p');
	   $t = GetReq('t')?GetReq('t'):'cpsmsgui';
	   $sort = GetReq('sort')>0?-1:1; 
	   
	   if (GetReq('editmode'))
	     $edmode = '&editmode=1';
	   else
	     $edmode = null; 
	
       $data[] = seturl("t=$t&a=&g=1&p=$p&sort=$sort&col=id".$edmode ,  "A/A" );
	   $attr[] = "left;10%";							  
	   $data[] = seturl("t=$t&a=&g=2&p=$p&sort=$sort&col=receiver".$edmode , localize('_receiver',getlocal()) );
	   $attr[] = "left;15%";
	   $data[] = seturl("t=$t&a=&g=3&p=$p&sort=$sort&col=status".$edmode , localize('_status',getlocal()) );
	   $attr[] = "left;15%";
	   $data[] = seturl("t=$t&a=&g=4&p=$p&sort=$sort&col=subject".$edmode , localize('_subject',getlocal()) );
	   $attr[] = "left;25%";
	   $data[] = seturl("t=$t&a=&g=5&p=$p&sort=$sort&col=body".$edmode , localize('_body',getlocal()) );
	   $attr[] = "left;35%";	   

  	   $mytitle = new window('',$data,$attr);
	   $out = $mytitle->render(" ::100%::0::group_form_headtitle::center;100%;::");
	   unset ($data);
	   unset ($attr);	
	   
	   return ($out);
	}				

};
}
?>