<?php
$__DPCSEC['RCSCHPASS_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCSCHPASS_DPC")) && (seclevel('RCSCHPASS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCSCHPASS_DPC",true);

$__DPC['RCSCHPASS_DPC'] = 'rcschpass';
 
$__EVENTS['RCSCHPASS_DPC'][0]='cpschpass';
$__EVENTS['RCSCHPASS_DPC'][1]='cpsdopass';

$__ACTIONS['RCSCHPASS_DPC'][0]='cpschpass';
$__ACTIONS['RCSCHPASS_DPC'][1]='cpsdopass';

$__DPCATTR['RCSCHPASS_DPC']['cpschpass'] = 'cpschpass,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCSCHPASS_DPC'][0]='RCSCHPASS_DPC;Change Login;Αλλαγή κωδικού';
$__LOCALE['RCSCHPASS_DPC'][1]='_RCSMESSAGEPASS;Credentials changed!;Τα στοιχεία άλλαξαν';

class rcschpass {

	var $title,$path,$dbpath,$prpath;
	var $verify, $verify_and_changed;
		
	function rcschpass() {
	
	    $this->title = localize('RCSCHPASS_DPC',getlocal());		

		$this->path = paramload('SHELL','urlpath') . $this->subpath;   		
		//echo $this->path;
		$this->dbpath = paramload('SHELL','dbgpath');
		
		$this->prpath = paramload('SHELL','prpath');	
		
		$this->verify = false;	
	    $this->verify_and_changed = false;		
	}
	
    function event($sAction) {    	  
	   
	   //if a remote user is in do not allow /cp actions
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('REMOTELOGIN')!='') die("Not allowed!");//	
	   /////////////////////////////////////////////////////////////		  		  			      
  
	   switch ($sAction) {				
		case "cpsdopass"   :$this->verify = $this->verify_login();
		                    //echo $this->verify,'-';
		                    $this->change_login();
		                    break;
							
		case "cpschpass"   :
		default            :
		                   
       } 
    }
  
    function action($action) {
	
	   switch ($sAction) {				
		case "cpsdopass"   :
							
		case "cpschpass"   :
		default            :$out = $this->show_form();
		                   
       } 
	 
	  return ($out);
    } 
	
	function show_form() {
	
	   if (GetSessionParam('REMOTELOGIN')) 
	     $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	   else  
         $out = setNavigator(seturl("t=cp","Control Panel"),$this->title); 	 
		
		if ($this->verify_and_changed===true) {
		
		  $toprint = localize('_RCSMESSAGEPASS',getlocal());	
	      $swin = new window($this->title,$toprint);
	      $out .= $swin->render("center::50%::0::group_dir_body::left::0::0::");	
	      unset ($swin);		  
		  return ($out);
		}
	 
        $filename = seturl("t=cpsdopass",0,1);
	  	  
        $toprint  = "<FORM action=". $filename . " method=post class=\"thin\">";
	    $toprint .= "<STRONG>Old Username:</STRONG>"; 
        $toprint .= "<input type=\"text\" name=\"cpuser\" value=\"\" size=\"32\" maxlength=\"128\"><br>";  		
        $toprint .= "<STRONG>Old Password:</STRONG>"; 		
	    $toprint .= "<input type=\"password\" name=\"cppass\" value=\"\" size=\"32\" maxlength=\"128\"><br>";
		$toprint .= "<br><br>";
	    $toprint .= "<STRONG>New Username:</STRONG>"; 
        $toprint .= "<input type=\"text\" name=\"cpnuser\" value=\"\" size=\"32\" maxlength=\"128\"><br>";  		
        $toprint .= "<STRONG>New Password:</STRONG>"; 		
	    $toprint .= "<input type=\"password\" name=\"cpnpass\" value=\"\" size=\"32\" maxlength=\"128\"><br>";		
		$toprint .= "<br><br>";		
	    $toprint .= "<input type=\"submit\" name=\"Submit\" value=\"Ok\">"; 
        $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"cpsdopass\">";
		
		//enable AUTH
		//$toprint .= "<input type=\"hidden\" name=\"AUTHENTICATE\" value=\"Login\">";
				
        $toprint .= "</FORM>";	   
		
	    $swin = new window($this->title,$toprint);
	    $out .= $swin->render("center::50%::0::group_dir_body::left::0::0::");	
	    unset ($swin);

        return ($out);
    } 	
	
    function verify_login() {
      $db = GetGlobal('db'); 	
  
      if (($user=GetParam('cpuser')) && ($pwd=GetParam('cppass'))) {
	
	    //get running application info  //NOT USED (ONE CLIENT)
	    $is_instance_app = paramload('ID','isinstance');
	    $appname = paramload('ID','instancename');
	   
	    $sSQL .= "select user,pwd,appname from dpcmodules where appname=".$db->qstr($appname);//where user='$user' and pwd='$pwd'";
        $result = $db->Execute($sSQL,2);	
	    //echo $sSQL;	
	    //print_r($result2);  
	  
	    //if username & password exists
	    if (($result->fields[0]==$user) && ($result->fields[1]==$pwd)) 
		  return true;
	  }	
	  return false;
    }
	
	function change_login() {
      $db = GetGlobal('db'); 	
	
	  if ($this->verify===false) return;
	
      if (($user=GetParam('cpuser')) && ($pwd=GetParam('cppass')) &&
          ($new_user=GetParam('cpnuser')) && ($new_pwd=GetParam('cpnpass'))) {
		  
	      $sSQL .= "update dpcmodules set user='$new_user',pwd='$new_pwd' where user='$user' and pwd='$pwd'";
          $result = $db->Execute($sSQL,2);	
	      //echo $sSQL;		  
		  
	      $this->verify_and_changed = true;
	  }	  	
	} 	
};
}	
?>