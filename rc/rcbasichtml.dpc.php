<?php

$__DPCSEC['RCBASICHTML_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCBASICHTML_DPC")) && (seclevel('RCBASICHTML_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCBASICHTML_DPC",true);

$__DPC['RCBASICHTML_DPC'] = 'RCBASICHTML';

//check bad request....
$requri = $_SERVER["REQUEST_URI"];
//echo "<br>" . $requri ."<br>";
$p = explode("/",$requri);
$badpage = $p[count($p)-1];
//echo $badpage,'>';
if (isset($badpage)) //means bad request has occured
  $phpname = $badpage;
else //get the unknown command reading PHP_SELF
  $phpname = pathinfo($_SERVER['PHP_SELF'],PATHINFO_BASENAME);

$name = explode('.',$phpname);
//echo $name[0];

$__EVENTS['RCBASICHTML_DPC'][0]= $name[0];//'noname';
//$__EVENTS['RCBASICHTML_DPC'][1]= 'noname2';

$__ACTIONS['RCBASICHTML_DPC'][0]= $name[0];//'noname';
//$__ACTIONS['RCBASICHTML_DPC'][1]= 'noname2';

class rcbasichtml {

   function rcbasichtml() {
   }
   
   function event($event=null) {
   }
   
   function action($action=null) {
   }
};
}
?>