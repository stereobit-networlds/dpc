<?
class CPanelX{
	var $host = "";
	var $path = "";
	var $port = 2082;
	var $cpaneluser = "";
  	var $cpanelpass = "";
  	var $authstr = "";
	var $pass = "";
	var $output = false;
	
	function login($user,$password,$host)
	{
		$this->host = $host;
		$this->cpaneluser = $user;
		$this->cpanelpass = $password;
		$this->authstr = "$this->cpaneluser:$this->cpanelpass";
		$this->pass = base64_encode($this->authstr);
	}

	function api_output()	
	{
	$this->output = true;
	}
		
	function open_sock($method,$formdata)
	{
		foreach($formdata AS $key => $val){
     			$string .= urlencode($key) . "=" . urlencode($val) . "&";
    			}
		
		$string = substr($string, 0, -1);
	    $fp = fsockopen($this->host, $this->port, $errno, $errstr, $timeout = 30);
  
 		if(!$fp){ $ret = "$errstr ($errno)\n"; }
		else{			
				fputs($fp, "$methos $this->path HTTP/1.1\r\n");
    			fputs($fp, "Host: $this->host\r\n");
    			fputs($fp, "Authorization: Basic $this->pass \r\n");
    			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
    			fputs($fp, "Content-length: ".strlen($string)."\r\n");
    			fputs($fp, "Connection: close\r\n\r\n");
    			fputs($fp, $string . "\r\n\r\n");	
			if($this->output)
				{
				while(!feof($fp)) {
     				$ret .= fgets($fp, 4096);
    					}    
			    fclose($fp);
				}
			}  	

			return ($ret); 
	}


	function create_email_account($email,$password,$quota)
	{
		$this->path = "/frontend/x2/mail/doaddpop.html";
		$formdata = array ( "email" => $email, "domain" => $this->host, "password" => $password, "quota" => $quota);
		$this->open_sock('POST',$formdata);
    }

				
	 function delete_email_account($email)
		{
		 $this->path = "/frontend/x2/mail/realdelpop.html";
		 $formdata = array ( "email" => $email, "domain" => $this->host);
		 $this->open_sock('GET',$formdata);
		}  	

	 function add_subdomain($subdomain,$domain='')
	 {
	   $this->path = "/frontend/x/subdomain/doadddomain.html";
	   $formdata = array ( "domain" => $subdomain , "rootdomain" => $domain.$this->host);
	   $this->open_sock('POST',$formdata);
	}
	
	function delete_subdomain($subdomain)
	{
	 $this->path = "/frontend/x/subdomain/dodeldomain.html";
	 $formdata = array ( "domain" => $subdomain);
	 $this->open_sock('POST',$formdata);
	}
	
	function show_awstats($domain='') {
	 $this->path = "/frontend/x2/mail/realdelpop.html";
	 $formdata = array ( "email" => $email, "domain" => $this->host);
	 $ret = $this->open_sock('GET',$formdata);
	 
	 return ($ret);
	}

}

##########################  CPANEL TOOLS ############################
// Author:  David (advancedstyle.com)
// Date: 11/18/06
// Description:  This class gives you a wide range of tools for use with Cpanel.
//               These tools can be used to execute commands through Cpanel.
//               If you have any suggested functions to add, find bugs or have questions
//               you can email me at dave@advancedstyle.com
//               Emails written in a demanding tone will be ignored.
//               SEE EXAMPLE BELOW THE FUNCTION LIST
//
// Function Summary: Here is a categorized list of the functions which can be used
//
       ## EMAIL ##
//     emailCreate($email,$password,$quota='5')    ||    Create Address Email
//     emailDelete($email)                         ||    Delete Email Address
//     emailQuota($email,$quota)                   ||    Change Email Quota
//     emailAging($email,$numdays)                 ||    Change Email Aging for an Account
//     emailChangePass($email,$password)           ||    Change Email Password
//     emailForwardDomain($domain,$email)          ||    Setup Email fowarding for entire domain
//     emailDeleteFowardDomain($domain)            ||    Delete Email fowarding for entire domain
//     emailSetDefault($domain,$email)             ||    Set Default Email Address for domain
//     emailForward($email,$foward_to_email)       ||    Setup specific email fowarding
//     emailFowardDelete($email,$foward_to_email   ||    Delete specific email fowarding
//     emailAutoReponder($email,$from,$subject,$message,$html='0',$charset = 'us-ascii')    ||    Setup auto responder
//     emailDeleteAutoReponder($email)             ||    Delete Auto responder

       ## DOMAINS ##
//     domainAddParked($domain)                    ||    Add a parked domain
//     domainDeleteParked($domain)                 ||    Delete a parked domain
//     domainCreatedAddon($domain,$directory='',$password='')    ||    Add an addon domain
//     domainDeleteAddon($domain)                  ||    Delete an addon domain
//     domainAddonRedirect($domain,$url)           ||    Setup redirect for addon domain
//     domainAddonDeleteRedirect($domain)          ||    Delete redirect for addon domain
//     domainCreatedSub($subprefix,$domain)        ||    Add subdomain
//     domainDeleteSub($subprefix,$domain)         ||    Delete subdomain
//     domainSubRedirect($subprefix,$domain,$url)  ||    Setup redirect for subdomain
//     domainSubDeleteRedirect($subprefix,$domain) ||    Delete redirect for subdomain

       ## DATABASE ##
//     databaseCreate($database)                   ||    Create mySQL Database
//     databaseDelete($database)                   ||    Delete Database
//     databaseAddUsers($user,$password)           ||    Create Database User
//     databaseDeleteUsers($user)                  ||    Delete Database User
//     databaseAssignUser($user,$database)         ||    Assign user to database

       ## HTACCESS ##
//     htaccessProtectDirectory($dir_path,$auth_message = 'Admin Area')    ||    Set a directory as password protected
//     htaccessUnProtectDirectory($dir_path)       ||    Unprotect a directory
//     htaccessAddUser($dir_path,$user,$password)  ||    Add user to directory
//     htaccessChangePass($dir_path,$user,$password) ||  Change user password
//     htaccessDeleteUser($dir_path,$user)         ||    Delete user
//     htaccessBanIP($ip)                          ||    Ban IP address
//     htaccessUnBanIP($ip)                        ||    Unban IP address

       ## FILES ##
//     fileCHMOD($full_path,$mod='777')            ||    CMHOD a file or folder


############## EXAMPLE USAGE ##################
//      include('cPanelTools.php');
//      $cpanel = new cPanelTools('username','password','example.com');
//      // create email address
//      $errors = $cpanel->emailCreate('dave@example.com','mypass');
//      if(empty($errors)){
//          echo 'Email Address was Created!';
//      }else{
//          echo 'There was an error<br>';
//          print_r($errors);
//      }


class cPanelTools
{
	var $cpanel_user;
	var $cpanel_pw;
	var $cpanel_url;
	var $cpanel_port;
	var $cpanel_theme;
	
	function cPanelTools($user, $pw, $domain, $port = '2082', $theme = 'x'){
		$this->cpanel_user = $user;
		$this->cpanel_pw = $pw;
		$this->cpanel_url = $domain;
		$this->cpanel_port = $port;
		$this->cpanel_theme = $theme;
	
	}
	
	// This is the main execute function which is used for each function to send the request to cpanel and get response
	function cPanelExecute($url_string,$error_array=array()){
		
		$url = 'http://' . $this->cpanel_user .':' . $this->cpanel_pw . '@' . $this->cpanel_url . ':' . $this->cpanel_port . '/frontend/' . $this->cpanel_theme . $url_string;
		//echo $url,'>';
		$errors = array();
		
		// Check if the URL responds, if not return error saying so
		if($handle = fopen($url, "rb")){
			$contents = '';
			// Read Cpanel Contents
			while (!feof($handle)) {
			  $contents .= fread($handle, 8192);
			}
			fclose($handle);
			// Start Error Handling
			if(is_array($error_array) && !empty($error_array)){
				foreach($error_array as $v){
					if(is_array($v)){
						foreach($v as $key => $val){
							if($key == 'name'){
								$error_msg = $val;
							}elseif($key == 'words'){
								$numwords = count($val);
								$error_count = 0;
								foreach($val as $word){
									if(stristr($contents,$word)){
										$error_count++;
									}
								}
								if($error_count == 	$numwords){
									$errors[] = $error_msg;
								}
							}
						}
					}					
				}
			}
			// End Error Handling
		}else{
			$errors[] = 'Server Timed Out';
		}
		
		return $errors;
	}
	
	########### EMAIL TOOLS ##############
	// Create Email Address
	function emailCreate($email,$password,$quota='5'){
		list($email_prefix,$domain) = explode('@',$email);
		$url = '/mail/doaddpop.html?email=' . $email_prefix . '&domain=' . $domain . '&password=' . $password.'&quota='.$quota;
		$errors_test = array(array('name'=> 'Email Address Already Exists',
								  'words' => array('sorry','already','exists')
								  )
							);
		return $this->cPanelExecute($url,$errors_test);
	}
	
	// Remove Email Address
	function emailDelete($email){
		list($email_prefix,$domain) = explode('@',$email);
		$url = '/mail/realdelpop.html?email=' . $email_prefix . '&domain=' . $domain;
		return $this->cPanelExecute($url);
	}
	
	// Set Email Aging
	function emailAging($email,$numdays){
		$url = '/mail/doaging.html?user=' . $email . '&numdays=' . $numdays;
		return $this->cPanelExecute($url);
		
	}
	
	// Set Email Quota
	function emailQuota($email,$quota){
		list($email_prefix,$domain) = explode('@',$email);
		$url = '/mail/doeditquota.html?email=' . $email_prefix . '&domain=' . $domain.'&quota='.$quota;
		return $this->cPanelExecute($url);
	}
	
	// Change Email Password
	function emailChangePass($email,$password){
		list($email_prefix,$domain) = explode('@',$email);
		$url = '/mail/dopasswdpop.html?email=' . $email_prefix . '&domain=' . $domain.'&password='.$password;
		return $this->cPanelExecute($url);
	}
	
	// Domain Email Forwarding
	function emailForwardDomain($domain,$email){
		$url = '/mail/doadddfwd.html?domain=' . $domain . '&forward=' . $email;
		return $this->cPanelExecute($url);
	}
	
	// Delete Domain Email Fowarding
	function emailDeleteFowardDomain($domain){
		$url = '/mail/dodeldfwd.html?domain=' . $domain;
		return $this->cPanelExecute($url);
	}
	
	// Set Default Domain Email Address
	function emailSetDefault($domain,$email){
		$url = '/mail/dosetdef.html?domain=' . $domain . '&forward=' . $email;
		return $this->cPanelExecute($url);
	}
	
	// Forward Email Address
	function emailForward($email,$foward_to_email){
		list($email_prefix,$domain) = explode('@',$email);
		$url = '/mail/doaddfwd.html?email=' . $email_prefix . '&domain=' . $domain.'&forward='.$foward_to_email;
		return $this->cPanelExecute($url);
	}
	
	// Delete From for Email Address
	function emailFowardDelete($email,$foward_to_email){
		$url = '/mail/dodelfwd.html?domain=' . $email . '=' . $foward_to_email;
		return $this->cPanelExecute($url);
	}
	
	// Add Email autoresponder 
	function emailAutoReponder($email,$from,$subject,$message,$html='0',$charset = 'us-ascii'){
		list($email_prefix,$domain) = explode('@',$email);
		$url = '/mail/doaddars.html?email=' . $email_prefix . '&domain=' . $domain.'&from='.$from.'&subject='.$subject.'&charset='.$charset.'&body='.$message.($html == 1 ? '&html=1' : '');
		return $this->cPanelExecute($url);
	}
	
	// Delete Email Autoresponder
	function emailDeleteAutoReponder($email){
		$url = '/mail/dodelautores.html?email=' . $email;
		return $this->cPanelExecute($url);
	}
	########### DOMAIN TOOLS ##############
	// Add Parked Domain
	function domainAddParked($domain){
		$url = '/park/doaddparked.html?domain=' . $domain;
		$errors_test = array(array('name'=> 'You cannot park your main domain',
								  'words' => array('cannot','park','main','domain')
								  ),
							 array('name'=> 'Invalid Domain',
								  'words' => array('not conform','name rules')
								  )
							);
		return $this->cPanelExecute($url,$errors_test);
	}
	
	// Remove Parked Domain
	function domainDeleteParked($domain){
		$url = '/park/dodelparked.html?domain=' . $domain;
		$errors_test = array(array('name'=> 'You cannot unpark your main domain',
								  'words' => array('cannot','park','main','domain')
								  ),
							 array('name'=> 'Invalid Domain',
								  'words' => array('not conform','name rules')
								  )
							);
		return $this->cPanelExecute($url,$errors_test);
	}
	
	// Create addon domain
	function domainCreatedAddon($domain,$directory='',$password=''){
		if($directory == ''){
			$directory = str_replace(strrchr($domain,'.'),'',$domain);
		}
		if($password == ''){
			$password = str_replace(strrchr($domain,'.'),'',$domain);
		}
		$url = '/addon/doadddomain.html?domain=' . $domain.'&user='.$directory.'&pass='.$password;
		$errors_test = array(array('name'=> 'Domain already exists',
								  'words' => array('error','already','configured')
								  ),
							 array('name'=> 'Invalid Domain',
								  'words' => array('not conform','name rules')
								  )
							);
		return $this->cPanelExecute($url,$errors_test);
	}
	
	// Delete Addon Domain
	function domainDeleteAddon($domain){
		$url = '/addon/dodeldomain.html?domain=' . $domain;
		$errors_test = array(array('name'=> 'Domain does not exist',
								  'words' => array('sorry','control','domain')
								  ),
							 array('name'=> 'Invalid Domain',
								  'words' => array('not conform','name rules')
								  )
							);
		return $this->cPanelExecute($url,$errors_test);
	}
	
	// Setup redirect for addon domain
	function domainAddonRedirect($domain,$url){
		$url = '/addon/saveredirect.html?domain=' . $domain .'&url='.$url;
		$errors_test = array(array('name'=> 'Domain does not exist',
								  'words' => array('sorry','control','domain')
								  ),
							 array('name'=> 'Invalid Domain',
								  'words' => array('not conform','name rules')
								  )
							);
		return $this->cPanelExecute($url,$errors_test);
	}
	
	// Delete addon domain redirection
	function domainAddonDeleteRedirect($domain){
		$url = '/addon/donoredirect.html?domain=' . $domain;
		$errors_test = array(array('name'=> 'Domain does not exist',
								  'words' => array('sorry','control','domain')
								  ),
							 array('name'=> 'Invalid Domain',
								  'words' => array('not conform','name rules')
								  )
							);
		return $this->cPanelExecute($url,$errors_test);
	}
	
	
	// Create Subdomain
	function domainCreatedSub($subprefix,$domain){
		$url = '/subdomain/doadddomain.html?domain=' . $subprefix.'&rootdomain='.$domain;
		$errors_test = array(array('name'=> 'Subomain already exists',
								  'words' => array('error','already','configured')
								  ),
							 array('name'=> 'Subomain Domain',
								  'words' => array('not conform','name rules')
								  )
							);
		return $this->cPanelExecute($url,$errors_test);
	}
	
	// Delete Subdomain
	function domainDeleteSub($subprefix,$domain){
		$url = '/subdomain/dodeldomain.html?domain=' . $subprefix.'_'.$domain;
		$errors_test = array(array('name'=> 'Subdomain does not exist',
								  'words' => array('sorry','control','domain')
								  ),
							 array('name'=> 'Invalid Subdomain',
								  'words' => array('not conform','name rules')
								  )
							);
		return $this->cPanelExecute($url,$errors_test);
	}
	
	// Setup redirect for subdomain
	function domainSubRedirect($subprefix,$domain,$url){
		$url = '/subdomain/saveredirect.html?domain=' . $subprefix.'_'.$domain .'&url='.$url;
		$errors_test = array(array('name'=> 'Subdomain does not exist',
								  'words' => array('sorry','control','domain')
								  ),
							 array('name'=> 'Invalid Subdomain',
								  'words' => array('not conform','name rules')
								  )
							);
		return $this->cPanelExecute($url,$errors_test);
	}
	
	// Delete subdomain redirection
	function domainSubDeleteRedirect($subprefix,$domain){
		$url = '/subdomain/donoredirect.html?domain=' . $subprefix.'_'.$domain;
		$errors_test = array(array('name'=> 'Subdomain does not exist',
								  'words' => array('sorry','control','domain')
								  ),
							 array('name'=> 'Invalid Subdomain',
								  'words' => array('not conform','name rules')
								  )
							);
		return $this->cPanelExecute($url,$errors_test);
	}
	
	########### DATABASE TOOLS ##############
	// Add new Database
	function databaseCreate($database){
		$url = '/sql/adddb.html?db=' . $database;
		return $this->cPanelExecute($url);
	}
	
	// Delete Database
	function databaseDelete($database){
		$url = '/sql/deldb.html?db=' . $database;
		return $this->cPanelExecute($url);
	}
	
	// Add User
	function databaseAddUsers($user,$password){
		$url = '/sql/adduser.html?user=' . $user.'&pass='.$password;
		return $this->cPanelExecute($url);
	}
	
	// Delete User
	function databaseDeleteUsers($user){
		$url = '/sql/deluser.html?user=' . $user;
		return $this->cPanelExecute($url);
	}
	
	// Assign User to Database
	function databaseAssignUser($user,$database){
		$url = '/sql/addusertodb.html?user=' . $user.'&db='.$database;
		return $this->cPanelExecute($url);
	}
	
	########### HTACCESS TOOLS ##############
	// Password Protect Directory
	function htaccessProtectDirectory($dir_path,$auth_message = 'Admin Area'){
		$url = '/htaccess/changepro.html?dir=' . rtrim($dir_path,'/').'&resname='.$auth_message.'&action=Save&protected=1';
		return $this->cPanelExecute($url);
	}
	
	// Password UnProtect Directory
	function htaccessUnProtectDirectory($dir_path){
		$url = '/htaccess/changepro.html?dir=' . rtrim($dir_path,'/').'&action=Save';
		return $this->cPanelExecute($url);
	}
	
	// Add User
	function htaccessAddUser($dir_path,$user,$password){
		$url = '/htaccess/newuser.html?dir=' . rtrim($dir_path,'/').'&user='.$user.'&pass='.$password.'&action=Add/modify authorized user';
		return $this->cPanelExecute($url);
	}
	
	// Change user password
	function htaccessChangePass($dir_path,$user,$password){
		$url = '/htaccess/newuser.html?dir=' . rtrim($dir_path,'/').'&user='.$user.'&pass='.$password.'&action=Change Password';
		return $this->cPanelExecute($url);
	}
	
	// Delete User
	function htaccessDeleteUser($dir_path,$user){
		$url = '/htaccess/deluser.html?dir=' . rtrim($dir_path,'/').'&user='.$user.'&action=Delete User';
		return $this->cPanelExecute($url);
	}
	
	// Ban IP Address
	function htaccessBanIP($ip){
		$url = '/denyip/add.html?ip=' . $ip;
		return $this->cPanelExecute($url);
	}
	
	// UnBan IP Address
	function htaccessUnBanIP($ip){
		$url = '/denyip/del.html?ip=' . $ip;
		return $this->cPanelExecute($url);
	}
	
	########### FILE TOOLS ##############
	// CHMOD File  (Added this function as a lot of hosting providers restrict you from CHMODing directly through php
	function fileCHMOD($full_path,$mod='777'){
		$full_path = rtrim($full_path,'/');
		$file = str_replace('/','',strrchr($full_path,'/'));
		$dir_path = str_replace($file,'',$full_path);
		
		$mod_string = '/files/changeperm.html?file=' . $file.'&dir='.$dir_path;
		
		$mod = sprintf("%03d",$mod);
		 
		$bits = explode('',$mod);
		
		$trim_bits = array();
		foreach($bits as $v){
			if($v != ''){
				$trim_bits[] = $v;
			}
		}
		foreach($trim_bits as $key => $val){
			switch($key){
				case 0:
					$first_char = 'u';
				break;
				case 1:
					$first_char = 'g';
				break;
				case 2:
					$first_char = 'w';
				break;
			}
			if($val >= 4){
				$mod_string .= '&'.$first_char.'r=4';
				if($val >= 6){
					$mod_string .= '&'.$first_char.'w=2';
					if($val >= 7){
						$mod_string .= '&'.$first_char.'x=1';
					}
				}
			}
		}  // End foreach
		return $this->cPanelExecute($mod_string);
	}
	
	//added by me
	########### STATS TOOLS ##############	
	function show_awstats(){
		$url = '/stats/awstats.html';
		return $this->cPanelExecute($url);
	}		
}
?>