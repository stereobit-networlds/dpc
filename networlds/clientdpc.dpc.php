<?php
	
$__DPCSEC['CLIENTDPC_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("CLIENTDPC_DPC")) && (seclevel('CLIENTDPC_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("CLIENTDPC_DPC",true);

$__DPC['CLIENTDPC_DPC'] = 'clientdpc';

$d = GetGlobal('controller')->require_dpc('database/database.dpc.php');
require_once($d); 

$e = GetGlobal('controller')->require_dpc('log/rclog.dpc.php');
require_once($e);	
 
$__EVENTS['CLIENTDPC_DPC'][0]='clientdpc';

$__ACTIONS['CLIENTDPC_DPC'][0]='clientdpc';

$__DPCATTR['CLIENTDPC_DPC']['clientdpc'] = 'clientdpc,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['CLIENTDPC_DPC'][0]='CLIENTDPC_DPC;Client DPC;Client DPC';

class clientdpc {	
 
    var $path;
	var $instance_name,$is_instance,$spy;
	var $suddendeath;
	var $renew_link, $refresh;

    function clientdpc() {
  
      $this->path = paramload('SHELL','prpath');
	  $this->centraldbpath = paramload('SHELL','dbgpath');
	  $this->spy  = paramload('ID','dpcspy');
	  $this->is_instance = paramload('ID','instance');	  
	  $this->instance_name = paramload('ID','instancename');
	  $this->suddendeath = paramload('ID','suddendeath');
	  
	  $this->refresh = 1000;
	  $this->renew_link = 'http://www.networlds.org/renew.php?app='.$this->instance_name;
	  
	  $this->logger = new rclog();
	  
	  $this->suddendeath = $this->getclienttimezone();//to check expiration..override suddendath if exst in db else return this->suddendeath
	  
	  //EXPIRED ALERT (INBETWEEN SUDDEN DEATH)
      if ((iniload('JAVASCRIPT')) && (GetSessionParam('EXPIRED'))) {

	       $code = $this->expired();
	  
		   $js = new jscript;   
		   $js->setloadparams("expired()");		   
           $js->load_js($code,"",1);
		   unset ($js);
	  }		  
    }
  
    function event($sAction) {
	
    }
  
    function action($action) {
	 
	 //return ($out);
    } 
	
	function expired() {
		$out = "function expired() { 
alert('Expired Application!');
}\r\n";
        return ($out);
	}
	
	function is_client_dpc($dpc) {
	
	  if ($this->spy) {
	  
	    if ($cdpc = GetSessionParam('clientdpc'))  {
		  return (@in_array($dpc,$cdpc));
		}
		else {
		  return (@in_array($dpc,$this->getclientdpc()));
		}
	  }
	  else
	    return true;//default true
	} 
	
	function getclientdpc() {
	  $db = & GetGlobal('controller')->calldpc_method('database.switch_db use +1+1');	
      //$db = GetGlobal('db');	
	
	  $appname = $this->instance_name;  	    	
	  
	  $sSQL = "select * from dpcmodules where ";
	  $sSQL .= "appname='$appname'";
	  //echo $sSQL . '<br>';			
	  $result = $db->Execute($sSQL,2);
	  //print_r($result);
	  
	  $gracedays = $result->fields['gracedays'];	  
	  $extratime = $gracedays?$gracedays:$this->suddendath;
	  
	  $today = date('Y-m-d');		  
	  $expiration = $result->fields['expire'];
	  //echo $expiration,'.',$today;
	  $expres = $this->date_diff($today,$expiration);	  
	  //echo '>',$expres;
	  //echo '-',$this->suddendeath;	  
	  
	  if ($expres>0) {
	    //return array of dpc
	    foreach ($result->fields as $id=>$dpc) {
	      if (is_string($id) && ($dpc)) {
		    $p = explode("_",$id);
		    if (($p[0]) && ($p[1]))
		      $dpcarr[] = $p[0].".".$p[1];
		  }  
	    }
	    //print_r($dpcarr);
	    SetSessionParam('clientdpc',$dpcarr);//save it - cache it
	  }
	  else {
	    $sSQL2 = "select expire,gracedays,timezone from applications where appname='$appname'";
	    //echo $sSQL . '<br>';			
	    $result2 = $db->Execute($sSQL2,2);
		
	    $gracedays = $result->fields['gracedays'];	  
	    $extratime = $gracedays?$gracedays:$this->suddendath;			  
	    //echo '|',$expres;
	    //echo '|',$this->suddendeath;		  
		
		//message at first load .. after session array returned and session param saved
		echo "Expired:",$expres,':',$extratime;//$this->suddendeath;
		
	    //if (($this->suddendeath) && ($this->suddendeath>=abs($expres))) {
        if (($extratime) && ($extratime>=abs($expres))) {		
		
		  //althougt expired if has favorite period return array of dpc
	      foreach ($result->fields as $id=>$dpc) {
	        if (is_string($id) && ($dpc)) {
		      $p = explode("_",$id);
		      if (($p[0]) && ($p[1]))
		        $dpcarr[] = $p[0].".".$p[1];
		    }  
	      }
	      //print_r($dpcarr);
	      SetSessionParam('clientdpc',$dpcarr);//save it - cache it		
		
		  SetSessionParam('EXPIRED',1);  
		}
		else {
		  $this->renew_js($this->renew_link,$this->refresh);		
	      die('Expired application!('.$expres.')');
		}  
      }	  
	  
	  unset($db);	  
	  GetGlobal('controller')->calldpc_method('database.switch_db use '.$appname);	
	  //test
      /*$mydb = GetGlobal('db');	  
	  
	  $sSQL = "select itmname from products where ";
	  $sSQL .= "code2=4527";
	  echo $sSQL . '<br>';			
	  $result = $mydb->Execute($sSQL,2);
	  echo $result->firlds[0],'>';
	  //print_r($result);  	*/  
	  
	  return ($dpcarr);
	}
	
	//get app timezone
	function getclienttimezone() {
	  $db = & GetGlobal('controller')->calldpc_method('database.switch_db use +1+1'); 
      //$db = GetGlobal('db');
	  	
	  $appname = $this->instance_name;  	    	
	  
	  $sSQL = "select expire,gracedays,timezone from applications where appname='$appname'";
	  //echo $sSQL . '<br>';			
	 
	  $result = $db->Execute($sSQL,2);	 
	  //print_r($result);
	  
	  $gracedays = $result->fields['gracedays'];	  
	  $extratime = $gracedays?$gracedays:$this->suddendath;	   
	  
	  $today = date('Y-m-d');		
	  //echo '>',$result->fields['expire'];  
	  $expiration = $result->fields['expire'];
	  //echo $expiration,'.',$today;
	  $expres = $this->date_diff($today,$expiration);	  
	  //echo '>',$expres;
	  //echo '-',$this->suddendeath;
	  
	  if ($expres>0) {
	    //date_default_timezone_set('Europe/Athens');
		date_default_timezone_set($result->fields['timezone']);
	    //echo '>',date_default_timezone_get(); 
		
		//return gracedays to override suddendeath
		return ($gracedays);
	  }
	  else {
	    if (($extratime) && ($extratime>=abs($expres))) {
		
		  SetSessionParam('EXPIRED',1);  
		}
		else {
  		  //$this->renew_js($this->renew_link,$this->refresh);		
		  
	      //die('<html><script>'.$this->javascript().'</script><body></body></html>');
		  //die('error, please come later!');
		  die("Expired application!($expres)");
		}  
      }
	  
	  unset($db);
	  GetGlobal('controller')->calldpc_method('database.switch_db use '.$appname);	  		
	  
	  //return expdate in days
	  return ($expres);
	}	
	
	//date format 2010-01-01
function date_diff($start_date, $end_date, $returntype="d") {
   if ($returntype == "s")
       $calc = 1;
   if ($returntype == "m")
       $calc = 60;
   if ($returntype == "h")
       $calc = (60*60);
   if ($returntype == "d")
       $calc = (60*60*24);   
	   
   //echo $start_date,'-',$end_date;	   
       
   $_d1 = explode(" ", $start_date);
   $_d11 = explode('-',$_d1[0]); //print_r($_d11);
   $_d12 = explode(':',$_d1[1]); //print_r($_d12);  
   $d1 = $_d11[2]; //echo '<br>',$d1;
   $m1 = $_d11[1];//echo '<br>',$m1;
   $y1 = $_d11[0];//echo '<br>',$y1;
   $h1 = $_d12[0]?$_d12[0]:0;
   $n1 = $_d12[1]?$_d12[1]:0;
   $s1 = $_d12[2]?$_d12[2]:0; 
   //echo $h1,':',$n1,':',$s1,'<br>'; 
   
   $_d2 = explode(" ", $end_date);
   $_d21 = explode('-',$_d2[0]);
   $_d22 = explode(':',$_d2[1]);   
   $d2 = $_d21[2];//echo '<br>',$d2;
   $m2 = $_d21[1];//echo '<br>',$m2;
   $y2 = $_d21[0];//echo '<br>',$y2;
   $h2 = $_d22[0]?$_d22[0]:0;
   $n2 = $_d22[1]?$_d22[1]:0;
   $s2 = $_d22[2]?$_d22[2]:0;
   //echo $h2,':',$n2,':',$s2,'<br>'; 
   
  
   if (($y1 < 1970 || $y1 > 2037) || ($y2 < 1970 || $y2 > 2037)) {
       return 0;
   } 
   else {
       $start_date_stamp    = mktime($h1,$n1,$s1,$m1,$d1,$y1); 
//echo $start_date_stamp,'<br>';
       $end_date_stamp    = mktime($h2,$n2,$s2,$m2,$d2,$y2);
//echo $end_date_stamp,'<br>';
	   
	   
       $difference = round(($end_date_stamp-$start_date_stamp)/$calc);
	   //echo $difference,"LLLLLLLL<br>";
		  
	   return $difference;  
   }
}	
	
    function javascript($page=null,$timeout=null) {
      $db = GetGlobal('db');	
   
      $mytimeout=$timeout?$timeout:10; 
   
   /*  $ret = "
function neu()
{	
	top.frames.location.href = \"$page\"
}
window.setTimeout(\"neu()\",$mytimeout);
";*/

//Header("content-type: application/x-javascript"); //NOT USED

//$app = $_GET['c']; //get application as param key //NOT USED
//test params (include in app)
$domain = paramload('SHELL','url');//'www.stereobit.net';

$serverIP = $_SERVER['REMOTE_ADDR'];
$message = "Expired Application. Are you the owner? Renew...";
$renewurl = "http://www.networlds.org/renew.php";
$expiredurl = "http://www.networlds.org";
$gracedays = $this->suddendeath;//12;
$interval = 0;//25000;
$testmode=0; //test mode
$silence=0;//send only mails

//if ($app) { //NOT USED

$ret .= "
var myurl = window.location.href;
";

$ret .= "document.write(\"Your URL is: <b>\"+myurl+\"</b>\");";
$ret .= "document.write(\"Your IP address is: <b>" . $serverIP . "</b>\");";

$ret .= '
function gup( name )
{
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location.href );
  if( results == null )
    return "";
  else
    return results[1];
}

';

$ret .= "
function myalert() {

if (confirm('$message'))
window.location = '$renewurl?c=$app';
else ";

if ($gracedays) 
  $ret .= " 
alert('$gracedays grace days left!');";
else
  $ret .= " 
window.location = '$expiredurl';";

$ret .= "
}

";

$ret.= "document.write(\"Your IP address is: <b>" . $serverIP . "</b>\");";
//echo "document.write(\"Your URL is: <b>\"+window.location.href+\"</b>\");";

$ret .= "
if (document.referrer) {
document.write(\"<B>Thanks for visiting us from \");
document.write(document.referrer+\"</B>\");
}
";

$ret .= "
window.onload=function(){

if (myurl.indexOf('" . $domain . "') != -1) {";

if ($interval)
  $ret .= " 
setInterval(\"myalert()\", ".$interval.");";
else
  $ret .= " 
myalert();";

$ret .= "
}
}
";

/*}//if app //NOT USED
else
   $ret = "";//  null;//or view a normal php page
*/	 
	  return ($ret);
    }		
	 
    function renew_js($page,$timeout=null) {
   
      if (iniload('JAVASCRIPT')) {

	       $code = $this->javascript($page,$timeout);
	   
		   $js = new jscript;
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }   
    } 	 	
	
	function __destruct() {
	
	  $this->logger->writelog();
	}
	
}
};

?>