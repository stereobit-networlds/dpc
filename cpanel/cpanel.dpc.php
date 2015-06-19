<?
//You can use this class only with a cPanel hosting account

/*
include "cpanel.php";

$cpanel = new cpanel();

//login with your hosting account
$cpanel->login('user','password','mydomain.com');

//if you don't want to see the cPanel output commment the next line
$cpanel->api_output();

//Exemples:
//don't run this script as is , comment all examples but one

//Ex 1:
//create new email address : newuser@mydomain.com with password: newpassword and a quota of 10Mb
$cpanel->create_email_account('newuser','newpassword','10');

//Ex 2:
//delete email account
$cpanel->delete_email_account('newuser');

//Ex 3:
//add subdomain one.mydomain.com
$cpanel->add_subdomain('one');

//Ex 4:
//add subdomain two.one.mydomain.com
$cpanel->add_subdomain('two','one.');

//Ex 5:
//delete subdomain two.one.mydomain.com
$cpanel->delete_subdomain('two.one')

//Ex 6:
//delete subdomain one.mydomain.com
$cpanel->delete_subdomain('one')
*/

$__DPCSEC['CPANEL_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("CPANEL_DPC")) && (seclevel('CPANEL_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("CPANEL_DPC",true);

$__DPC['CPANEL_DPC'] = 'cpanel';

$d = GetGlobal('controller')->require_dpc('cpanel/cpanel.lib.php');
require_once($d); 

$__EVENTS['CPANEL_DPC'][0]='cpcpanel';
$__EVENTS['CPANEL_DPC'][1]='cpanelx';
$__EVENTS['CPANEL_DPC'][2]='cpanelz';

$__ACTIONS['CPANEL_DPC'][0]='cpcpanel';
$__ACTIONS['CPANEL_DPC'][1]='cpanelx';
$__ACTIONS['CPANEL_DPC'][2]='cpanelz';

$__LOCALE['CPANEL_DPC'][0]='CPANEL_DPC;Cpanel;Cpanel';

class cpanel {

    var $cpanelx;

	function cpanel() { 
	
	  $usr = paramload('CPANEL','user');
	  $pwd = paramload('CPANEL','password');
	  $domain = paramload('CPANEL','domain');
	
	
      $this->cpanelx = new cpanelx();		
      //login with your hosting account
      $this->cpanelx->login($usr,$pwd,$domain);	 
    }
	
   function event($event=null) {
      
	  switch ($event) {
	    case 'cpabclangs' : break;
		default           : 
	  }
   }
   
   function action($action=null) {  
      
	  switch ($action) {
	    case 'cpabclangs' : break;
		default           : $ret = $this->show_stats();
	  }
	  
	  return ($ret);
   }	
	
	function show_stats() {
	
      //if you don't want to see the cPanel output commment the next line
      /*$this->cpanelx->api_output();	  
	  
	  $ret = $this->cpanelx->show_awstats();*/
	  
	  $ret = $this->cpanel_tools->show_awstats();
	  
	  return ($ret);
	}
};
}
?>
