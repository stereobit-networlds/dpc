<?php
$__DPCSEC['RCUWIZARD_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("RCUWIZARD_DPC")) && (seclevel('RCUWIZARD_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCUWIZARD_DPC",true);

$__DPC['RCUWIZARD_DPC'] = 'rcuwizard';

$x = GetGlobal('controller')->require_dpc('libs/cpanelx3.lib.php');
require_once($x);

$a = GetGlobal('controller')->require_dpc('phpdac/rcwizard.dpc.php');
require_once($a);

//$d = GetGlobal('controller')->require_dpc('rc/rcdownload.dpc.php');
//require_once($d); 
//$d = GetGlobal('controller')->require_dpc('tcp/httpdownload.lib.php');
//require_once($d);
//$d = GetGlobal('controller')->require_dpc('filesystem/downloadfile.lib.php');
//require_once($d); 

GetGlobal('controller')->get_parent('RCWIZARD_DPC','RCUWIZARD_DPC');

$__EVENTS['RCUWIZARD_DPC'][20]='cpupgrade';
$__EVENTS['RCUWIZARD_DPC'][21]='cpwizcancel';
$__EVENTS['RCUWIZARD_DPC'][22]='cpupgcancel';
$__EVENTS['RCUWIZARD_DPC'][23]='cpwupdate';

$__ACTIONS['RCUWIZARD_DPC'][20]='cpupgrade';
$__ACTIONS['RCUWIZARD_DPC'][21]='cpwizcancel';
$__ACTIONS['RCUWIZARD_DPC'][22]='cpupgcancel';
$__ACTIONS['RCUWIZARD_DPC'][23]='cpwupdate';

$__LOCALE['RCUWIZARD_DPC'][0]='RCUWIZARD_DPC;Upgrade Wizard;Upgrade Wizard';

define('DS', DIRECTORY_SEPARATOR); 

class rcuwizard extends rcwizard {
  
    var $upgrade_root_path;
	var $log, $wf, $error;
	var $instaled, $installit, $reinstall_question;

	function __construct() {

	    parent::__construct();
		
		$this->upgrade_root_path = $this->prpath . '../../cp/upgrade-app/';		
        $this_log = null;	
		$this->error = false;
        $this->instaled = false;	
		$this->reinstall_question = false;
		
		//upgrade wizard...
		//$this->wf = $_SESSION['wf'] ? $_SESSION['wf'] : (GetReq('wf') ? GetReq('wf') : null);
		if ($addon = (GetParam('wf'))) {
		    //print_r($_POST);
			if ($this->instalit = $this->call_upgrade_ini($addon)) {//copy from upgrade root dir
				$this->wf = $addon;
				SetSessionParam('wf',$this->wf);//save to session
			}	
			else
                $this->instalit = false;					
		}	
		else {
		    $this->wf = $_SESSION['wf'];
		    $this->instalit = $_SESSION['wf'] ? $_SESSION['wf'] : false; //true			
		}	
		//echo $this->instalit,'>',$_SESSION['wf'];		
		$this->wizardfile = $this->wf ? "cpwizard-".$this->wf.".ini" : 'cpwizard.ini';
		
		//re-read params
        $this->environment = $_SESSION['env'] ? $_SESSION['env'] : $this->read_env_file(true);//session or read..; 				
        $this->wdata = $_SESSION['wdata'] ? $_SESSION['wdata'] : $this->read_wizard_file(true);//session or read..; 		
	    $this->wstep = $_SESSION['wstep'] ? $_SESSION['wstep'] : 0;
		
	}
	
	function event($event=null) {
	
	    //$this->pre_check_installation();
	
	    switch ($event) {

          case 'cpwizcancel' : //just ask for cancel
		                       $this->log = 'Cancel installation.'; 
                               break;			
		  case 'cpupgcancel' :  if ($this->instalit) { 
									//just exit if procedure canceled
									$this->log = '<br>clear installation details!';
									$this->wdata = $_SESSION['wdata'] = null; 
									$this->wstep = $_SESSION['wstep'] = null;
									$this->wf = $this->instalit = $_SESSION['wf'] = null;
									
									$this->log = '<br>delete installation files!';
									if (is_readable($this->prpath . $this->wizardfile))
										@unlink($this->prpath . $this->wizardfile);
								}	   
							   //echo 'return to dashboard!'; //<<<<<<
							   header('location:cp.php?editmode=1'); //return to dashboard...
		                     break; 	  

		  case 'cpwizexit' : //if app installed reredirect to app
		                     //if ($this->instalit) {
							    //session_destroy();//kill session ..into redirect
								
								//just exit if procedure completed
								$this->log = '<br>clear installation details!';
								$this->wdata = $_SESSION['wdata'] = null; 
								$this->wstep = $_SESSION['wstep'] = null;
								$this->wf = $this->instalit = $_SESSION['wf'] = null;								
								
		                        $this->redirect_to_app();		
							 /*}
							 else {
							    //just exit if procedure canceled
								//echo 'return to dashboard!'; //<<<<<<
								header('location:cp.php?editmode=1'); //return to dashboard...
							 }*/	
		                     break; 
							 
		  case 'cpwizsave' : if ($this->instalit) { // && ($this->instaled = $this->install_siwapp())) { //<<<<<<<
		                        //echo 'save';
								
								//save wiz file ..
								$ok = $this->write_wizard_file(false);//true //..to enable/disable wiz next time..
								//save env file updated with addon
			                    $ok2 = $this->write_env_file($this->wf, 1, true);
								
								$this->instaled = $ok ? ($ok2 ? true : false) : false;
							 }
                             break;		  

		  case 'cpwiznext' : if ($this->instalit) $this->inc_wizard_step(); break;
		  case 'cpwizprev' : if ($this->instalit) $this->dec_wizard_step(); break;
		  case 'cpwizskip' : if ($this->instalit) $this->inc_wizard_step(); break;
		  
		  case 'cpwupdate' :
          case 'cpupgrade' : 
		  default          : 
		                     
        }			
    }
	
	function action($action=null) {	
	    //echo $this->wstep;	
	
	    switch ($action) {	

		  case 'cpupgcancel' : //never here redirect...					
		  case 'cpwizcancel' : //just exit if procedure canceled
		                       //if ($this->instalit) 
								  $out = $this->cancel_step($this->log);
                               break;			
		
		  case 'cpwizsave' :   if ($this->instalit) {
		                          if ($this->instaled)
									$out = $this->completed_step($this->log);
								  else	  
									$out = $this->error_step($this->log);
							   }		
		                       break; 
		  case 'cpwiznext' :
		  case 'cpwizprev' :
		  case 'cpwizskip' :
		  											   
		  case 'cpwizexit' : //just exit if procedure canceled or completed....
		                     //break;
		  case 'cpwupdate' :					 	
          case 'cpupgrade' :
          default          : if (!$this->instalit) { //pre-install checks
                               		  
		                        if ($this->log) {//pre-install warning-error
								    //echo 'log:'.$this->log;
								    if (GetParam('Submit')==='No') //question reinstall answered No (if yes never here...=wizard_step)
										$out = $this->final_step(false); //wizard last step canceled procedure
									else //pre-install checks	
										$out = $this->error_step($this->log);
								}	
							    else {	//setup link	
								    //if addon not defined lets choose ....
									//$msg = '<br>upgrade..' . seturl('t=cpwizard&wf=siwapp', 'Siwapp');
                                    $addon = GetParam('addon') ? GetParam('addon') : null;//'siwapp';	
                                    //echo $addon; 	
                                    if ($addon) {									
										$out = $this->welcome_step($msg, $addon);
									}	
									else {
                                        //$out = $this->error_step('Undefined add-on. Repeat the procedure.');									
										//addon screen
										$cb = base64_encode($_ENV["HTTP_HOST"]);//str_replace('www.','',$_ENV["HTTP_HOST"]));
										header('location:http://stereobit.com/netpanel.php?t=modapp&cb='.$cb);
									}	
								}	
	                         }
							 else //execute wizard step scenario
		                       $out = $this->wizard_step();
		 						  
        }
		
        return ($out);

    }
	
	/*protected function pre_check_installation() {
	}*/
	
	//override
	protected function wizard_step() {
		
		if (!empty($this->wdata)) {
		  //echo 'current step:'.$this->wstep;
		  $step_index = 0; //0 is welcome screen
		  foreach ($this->wdata as $stepname=>$stepaction) {
			//echo $stepname,':',$stepaction,'>';
  
			if (($stepaction) && ($step_index==$this->wstep)) {//if step val exist
			    //echo $stepname,':',$stepaction,'>';
				$ret = $this->render_step($stepname, $stepaction);
				if ($ret) 
				    return ($ret); //else continue....other steps...	
			}
			else
			    $step_index += 1;
		  }
		}
		
		//finilizing wizard exist screen...
		//echo $this->wstep,'>';
		if ($this->wstep) //has do the wiz cycle...
		   $ret = $this->final_step(true); //no final step....
		else   
		   $ret = $this->completed_step();
		   
		return ($ret);
	}
	
	//ovwerride render wizard step
	protected function render_step($name=null, $value=null) {
		if (!$name) return ('no named step!');
		//echo $name;
		switch ($name) {
		
			case 'dnload5' :
			case 'dnload4' :
			case 'dnload3' :
			case 'dnload2' :
			case 'dnload1' :
		    case 'dnload'  :  $msg = $this->install_addon_step($name,$value);
                              $ret = $this->command_step($msg);	
			                  break;		

			case 'goturl5' :
			case 'goturl4' :
			case 'goturl3' :
			case 'goturl2' :
			case 'goturl1' :
		    case 'goturl'  : $msg = $this->install_addon_step($name,$value);
                             $ret = $this->command_step($msg);	
			                 break;
		
			case 'copyrc5' :
			case 'copyrc4' :
			case 'copyrc3' :
			case 'copyrc2' :
			case 'copyrc1' :
		    case 'copyrc'  :  $msg = $this->install_addon_step($name,$value);
                              $ret = $this->command_step($msg);	
			                  break;			
			
			case 'cpanel5' :
			case 'cpanel4' :
			case 'cpanel3' :
			case 'cpanel2' :
			case 'cpanel1' :
		    case 'cpanel'  :  							  
			                  $msg = $this->install_addon_step($name,$value);
                              $ret = $this->command_step($msg);	
                              break;	
							 
		    case 'phpdac5' :  								 
		    case 'phpdac4' :  								 
		    case 'phpdac3' :  								 
		    case 'phpdac2' :  								 
		    case 'phpdac1' :  							  
		    case 'phpdac'  :  //remote dpc calls
			                  //$msg = $this->install_addon_step($name,$value);
							  //if (defined('RCCONFIG_DPC')) echo 'rxxx'; else echo 'no';
							  if (stristr($value,',')) { //has args 
								$p = explode(',',$value);
								$dpccmd = array_shift($p);//first part
								//$test .= implode(',',$p) . $dpccmd.'>';
								foreach ($p as $pi=>$pn) {
								  if (substr($pn,0,1)=='@')  //is wizarg arg
		                             $arguments[] = $this->get_wizard_arg(substr($pn,1));
								  else
								     $arguments[] = $pn; //as is
								}
								$args = implode('+',$arguments);
								$linker = (stristr($dpccmd,' use ')) ? '+':' use ';
								$istrue = GetGlobal('controller')->calldpc_method($dpccmd.$linker.$args);
							  }
							  else 
			                    $istrue = GetGlobal('controller')->calldpc_method($value);
							  //$msg .= implode(',',array_keys($this->wdata));
                              //$test .= $istrue;							  
                              $msg .= $istrue ? 'setting is done':'ERROR:'.$dpccmd.$linker.$args.':'.$test;							
							
                              $ret = $this->command_step($msg, true);			
			                  break;			
							
			case 'execmd5' :
		    case 'execmd4' :							  
		    case 'execmd3' :
		    case 'execmd2' :							  
		    case 'execmd1' :							  
		    case 'execmd'  :  //local func call
			                  $params = explode(',',$value);
			                  if (method_exists($this, $params[0])) {
							     $function = $params[0];
                                 if (substr($params[1],0,1)=='@') //is wizarg arg
 							        $argument = $this->get_wizard_arg(substr($params[1],1)); 							  
							     //else as exist/entered
								 
			                     $msg = $this->$function($argument);
								 $ret = $this->command_step($msg, true);
							  }	 
							  else
                                 $ret = $this->command_step('method not exist ('.$function.')'); //..continue steps 							  
			                  break;
							  
		
		    case 'wizard':	 //always step 0
							if ($value) //welcome screen
								$ret = $this->welcome_step('Pre install check are valid!'); //<<has been shown ???????????	  
							else //wizard is off (completed)
								$ret = $this->completed_step();	
							break; 							  
						   
			default      :  //if (($value) && ($value!='disabled')) //..problem bypass counters
								$ret = $this->default_step($name, $value);
							//else
                              //  $ret = false;	 //..continue steps 							
		}
		
		return ($ret);
	}
	
	//override	
	protected function default_step($name=null, $value=null) {

	    $ret  = 'Press '.seturl('t=cpwizcancel','here').' to cancel';		
		
	    $ret .= parent::default_step($name, $value);
		return ($ret);
	}		
	
	protected function command_step($msg=false, $nocancel=false) {
		$cmd = 'cpwiznext'; 
		$message = $msg ? $msg : 'command has no ret value.';

	    //cmd commands can't cancel..?
		if (!$nocancel)
	        $cancel = 'Press '.seturl('t=cpwizcancel','here').' to cancel';	
		
		$ret .= <<<EOF
							<form name="wizdefstep" method="post" class="sign-up-form" action="">
								<input type="hidden" name="FormAction" value="$cmd" /> 
                                    $message 
									<br/><br/>
									$cancel
									<div class="msg grid_4 alpha omega aligncenterparent"></div>      
									<div class="grid_4 alpha omega aligncenterparent">
									<br/>
									<input type="submit" class="call-out grid_2 push_2 alpha omega" alt="Save"	title="Next" name="Submit" value="Next">
									</input>
								    </div>
							</form>
<script type="text/javascript">
	$(document).ready(function() {
		$("#stepfield").focus();
	});
</script>							
EOF;
		
 	    return ($ret);
	}	
	
	//override
	protected function welcome_step($msg=null,$addon=false) {	
	    $message = $msg;
	    $addon = $addon ? $addon : GetParam('wf');
		
	    $ret = $message; //'Welcome step:' . $msg;
		
		if (!$addon) {
		    session_destroy();//kill session 
		    return ($ret . "Addon not defined! ($addon)");
		}	
		
		$cmd = $this->instalit ? 'cpwiznext' : 'cpupgrade';
		
		$ret .= '<br><br>Install:<strong>'. str_replace('_','&nbsp;',$addon) . '</strong>';
		$ret .= '<br>Press '.seturl('t=cpwizcancel','here').' to cancel this installation';
		
		$ret .= '<form name="wizwelcomestep" method="post" class="sign-up-form" action="">
								<input type="hidden" name="FormAction" value="'.$cmd.'" />     
								<input type="hidden" name="wf" value="'.$addon.'" />  
									<div class="grid_4 alpha omega aligncenterparent">
										<br/>
										<input type="submit" class="call-out grid_2 push_2 alpha omega" alt="start"
											title="Start"
											name="Submit" value="Start">
										</input>
								    </div>
							</form>';	
							
		return ($ret);	
	}	
	
	//override..
	protected function completed_step($msg=null) { 	
	
	    $message = $msg;//'Completed step:' . $msg;

		//cpwizexit also do the redirection.......
		$goto = $this->redirect_to_app(true); //get redirect link....   
		$onclick = $this->instaled ? "onClick=\"$goto\"" : null;
	    $completed = '<br>Installation completed.'; //. $goto;		
		
		$ret .= '<form name="wizcompletestep" method="post" class="sign-up-form" action="">
		                        '.$message.' 
								<br/><br/>
								'.$completed.'		
								<input type="hidden" name="FormAction" value="cpwizexit" />     
									<div class="grid_4 alpha omega aligncenterparent">
										<br/>
										<input type="submit" class="call-out grid_2 push_2 alpha omega" alt="exit"
											title="exit"
											name="Submit" value="Exit" '.$onclick.'>
										</input>
								    </div>
							</form>';			
		
		return ($ret);	
	}	
	
	//override
	protected function final_step($finished=false, $goto=null) {
	
	    if ($finished) {//installed ok..gor save step...	
			$formaction = 'cpwizsave' ? '<input type="hidden" name="FormAction" value="cpwizsave" />' : null;
			$action = null;
		}	
		else {//cancel procedure return...
			$formaction = null;
            $action = $goto ? $goto : 'cp.php?editmode=1';
		}	

		/*
	    $ret = 'Final step:<br>';
		if (!empty($this->wdata))
		    $ret .= implode('<br>',$this->wdata);
        */ 
		//form... when submit cpwizsave... 
		$ret .= '<form name="wizlaststep" method="post" class="sign-up-form" action="'.$action.'">
								'.$formaction.'     
									<div class="grid_4 alpha omega aligncenterparent">
										<br/>
										<input type="submit" class="call-out grid_2 push_2 alpha omega" alt="finish"
											title="Finish"
											name="Submit" value="Finish">
										</input>
								    </div>
							</form>';			
		
		return ($ret);
	}
	
	protected function error_step($msg=null) { 	
	    $addon = GetParam('wf');
	    $message = $msg;//'Error step:' . $msg;
		
		$this->error = true; //toggge error var
		
		if (!$addon) {
		    session_destroy();//kill session 		
		    return ($message . "<br>Addon ($addon) not defined!");
		}	
		
		if ($this->reinstall_question) {
		
		    $cancel = 'Press '.seturl('t=cpwizcancel','here').' to cancel';		
		
		    $ret .= '<form name="wizreinstallquestion" method="post" class="sign-up-form" action="">
								<input type="hidden" name="FormAction" value="cpupgrade" /> 
                                <input type="hidden" name="wf" value="'.$addon.'" />
		                        '.$message.' 
								<br/><br/>
								'.$cancel.'									
									<div class="grid_4 alpha omega aligncenterparent">
										<br/>
										<input type="submit" class="call-out grid_1 push_1 alpha omega" alt="no"
											title="no"
											name="Submit" value="No">
										</input>
										&nbsp;
										<input type="submit" class="call-out grid_1 push_1 alpha omega" alt="yes"
											title="yes"
											name="Submit" value="Yes">
										</input>										
								    </div>
							</form>';		
		}
		else {
		    $ret .= '<form name="wizerror" method="post" class="sign-up-form" action="">
								<input type="hidden" name="FormAction" value="cpupgrade" /> 
                                <input type="hidden" name="wf" value="'.$addon.'" />								
									<div class="grid_4 alpha omega aligncenterparent">
										<br/>
										<input type="submit" class="call-out grid_2 push_2 alpha omega" alt="retry"
											title="retry"
											name="Submit" value="Retry">
										</input>
								    </div>
							</form>';
        }							
		
		return ($ret);	
	}	

	protected function cancel_step($msg=null) { 	
	
	    $message = $msg;	
	    $continue = 'Press '.seturl('t=cpupgrade','here').' to continue';	
			
		$ret .= '<form name="wizcancel" method="post" class="sign-up-form" action="">
		                        '.$message.' 
								<br/><br/>
								'.$continue.'
								<input type="hidden" name="FormAction" value="cpupgcancel" />     
									<div class="grid_4 alpha omega aligncenterparent">
										<br/>
										<input type="submit" class="call-out grid_2 push_2 alpha omega" alt="cancel"
											title="cancel"
											name="Submit" value="Cancel">
										</input>
								    </div>
							</form>';			
		
		return ($ret);	
	}	

	protected function redirect_to_app($retonclick=false, $kill_session=false) {
	
	    switch ($this->wf) {
	    
		    case 'siwapp' : session_destroy();//kill session anyway 
			
			                $go = $this->url .'/siwapp/installer.php'; 
			                $onclick = "location.href='$go'";
							if ($retonclick) 
								$ret = "top.location.href='" . $go . "'";
			                break;
							
			case 'google_analytics' :
			                $kill_session=false; //override to not kill the session 
			
                            $go = 'cp.php?editmode=1'; //return to cp
			                $onclick = "location.href='$go'";
							if ($retonclick) //not.. top.location
								$ret = "location.href='" . $go . "'";
								
                            break;	

			case 'add_recaptcha'  :				 
			case 'upload_logo'    :				  
			case 'add_products'   :
			case 'add_categories' :
			                $kill_session=false; //override to not kill the session 
			
                            $go = 'cp.php?editmode=1'; //return to cp
			                $onclick = "location.href='$go'";
							if ($retonclick) //not.. top.location
								$ret = "location.href='" . $go . "'";
								
                            break;								
							
		    default       : //goto uprgade screen...
			                $go = 'cpupgrade.php'; 
			                $onclick = "location.href='$go'";
							if ($retonclick) 
								$ret = "top.location.href='" . $go . "'";							
		}	
		
		if ($kill_session) 
			session_destroy();//kill session 		
		
		if ($ret)
		    return ($ret);
		
		//redirect
		header('location:'.$go);
		//die();
	}
	
	protected function call_upgrade_ini($addon) {
	   // $addon = GetParam('wf') ? : $this->wf;
	    if (!$addon) return false;
	    $inifile = $this->upgrade_root_path . "/cpwizard-".$addon.".ini";
		$target_inifile = $this->prpath . "/cpwizard-".$addon.".ini";
		$installed_inifile = str_replace('.ini','._ni',$target_inifile);
		$reinstall_answered_yes = (GetParam('Submit')=='Yes') ? true : false;

		if (is_readable($target_inifile)) {//already copied
		    //..answer has submited..
			//echo 'z';
		    return true;
		}
		
		if (is_readable($inifile)) {
		   
		   if ($this->environtment[strtoupper($addon)]==false) {
		   
		      if ((!is_readable($installed_inifile)) || ($reinstall_answered_yes)) {
			  
				$ret = @copy($inifile, $target_inifile);
				return $ret ? $addon : false;
			  }
			  else {
			    $this->log = 'This addon seems to be installed. Re-install?';
				$this->reinstall_question = true;
			  }	
		   }
		   else
		      $this->log = 'Already installed!';
		}
		else
		   $this->log = 'Unknown installation!';
		   
		return false;
	}
	
	//override
	public function get_step_title($title=null, $pretext=null, $posttext=null) {
        $mypath = $this->upgrade_root_path;		
	
        if ($this->error) 	
		    return ('Installation');
	
	    $default_ret = $title ? $title : 'Welcome';		
		$step_index = $this->wstep ? $this->wstep : '0';
		$step_file = str_replace('.ini','.text'.$step_index, $this->wizardfile);
		
		//fetch step title =first line
		$ret = (is_readable($mypath . $step_file)) 
		     ? array_shift(@file($mypath . $step_file)) 
			 : $default_ret;		
		
		return ($pretext . $ret . $posttext);	
    }	
	
	//override
	public function get_step_text($textpath=null) {
	    $mypath = $textpath ? $textpath : $this->upgrade_root_path;	
	
        if ($this->error) {	
		    $console = $this->log;
	        $ret = "<h5>Installation details</h5>
				<br/>
				<p>
				$console
				</p><br /> <br />";		
	    }
	    elseif (GetReq('t')!='cpwizcancel')  
            $ret = parent::get_step_text($mypath);
		else	
	        $ret = "<h5>Cancel installation?</h5>
				<br/>
				<p>
				You choose to cancel the installation. <br /> <br />
				You can continue by pressing the continue link 
				or press <strong>cancel</strong> to stop this installation.<br /> <br />
				</p><br /> <br />";
				
		return ($ret);
	}	
	
	//override
	protected function write_env_file($module,$mvalue=1,$reload_env_session=false) {
	    if (!$module) return false;
        $myenvfile = $this->prpath . 'cp.ini';
        $newmodule = strtoupper($module);
		$append_string = $newmodule.'='.$mvalue;
   
        //backup cp file
		@copy($myenvfile, str_replace('.ini','._ni',$myenvfile));
		
		//check for existing string
		$initext = @file($myenvfile);
		$savetext = null;
		$existed = false;
		foreach ($initext as $i=>$line) {
		    if (trim($line)) {
		       $p = explode('=',$line);
			   
			   if ($p[0]==$newmodule) {
			      
                    switch ($p[1]) {//value
					   case '1' :  
					   default  : $savetext .= $newmodule . "=$mvalue\r\n"; 
                    } 
                    $existed = true; 					
			   }
			   else
			       $savetext .= $line . "\r\n"; 
			}  
		}
		
		
		if (!$existed)
		   $savetext .= $newmodule . "=$mvalue\r\n";  
		   
		//overwrite file   
		$ret = @file_put_contents($myenvfile, $savetext);	
		
		
		if ($reload_env_session) //reload environment in session	
            $this->environment = $this->read_env_file(true);
			
        return ($ret);			
    }	
	
	
	//step by step install actions based on ini scenario
	protected function install_addon_step($name=null, $cmd=null) {	
	    if ((!$name)||(!$cmd)) return false;
		
        //$str = 'In My Cart : 11 items';
        //$mygroupcmd = filter_var($name, FILTER_SANITIZE_NUMBER_INT);		
		$mygroupcmd = substr($name,0,6);//extract numbers
		
		//cmd filetrs
		if (stristr($cmd,'DBNAME@')) {
		  $dbname = paramload('DATABASE','dbname');
		  $cmd = str_replace('DBNAME@',$dbname,$cmd);
		}
		if (stristr($cmd,'DBUSER@')) { 
		  $dbuser = paramload('DATABASE','dbuser');
		  $cmd = str_replace('DBUSER@',$dbuser,$cmd);
		}
		/*if (substr($cmd,0,1)=='@') { //is wizarg arg
		  $argument = $this->get_wizard_arg(substr($cmd,1));
		}*/		
		//if...
		
		if (stristr($cmd,',')) 
			$params = explode(',',$cmd); 
        else
		    $params = array(0=>$cmd); 
			
		//test	
		//print_r($params);
        //echo $mygroupcmd,'>';
		/*$cp = null;
	    $this->cpanel_login($cp);
		if (is_object($cp)) 
			$ret = $mygroupcmd . '['.implode('>',$params).']';
		else
		    $ret = 'Invlid cpanel login';
        return ($ret);	*/	
		
		switch ($mygroupcmd) {
		
		    case 'phpdac' : if ($cmd)   
			                    $msg = GetGlobal('controller')->calldpc_method($cmd);

							echo $name.':'.$cmd;	
			                break;
		
		    case 'dnload' : //download file
			                $file = $this->urlpath .'/'. $params[0];
							$title = $params[1] ? $params[1] : 'press here';
							//$link = $title ? "<a href='$url' target='_blank'>$title</a>" : $url;	

							//$downloadfile = new DOWNLOADFILE($file);
							//if ($downloadfile->df_download()) //no header allowed here
							
							$ip = paramload('SHELL','ip');//$_SERVER['HTTP_HOST'];
							$pr = paramload('SHELL','protocol');		   			 
							$download_link = $pr . $ip . "/" . $params[0];
							if ((is_readable($file)) && ($download_link)) {
							    $link = $title ? "<a href='$download_link' target='_blank'>$title</a>" : $download_link;	
								$ret = $link; //$this->get_step_title();
							}	
							else
							    $ret = " file ($params[0]) not exist!";
			                break;		
		
		    case 'goturl' : //wait for user url goto and act
			                $url = $params[0];
							$title = $params[1] ? $params[1] : 'press here';
							$link = $title ? "<a href='$url' target='_blank'>$title</a>" : $url;	
							$ret = $this->get_step_title() . $link;
			                break;
		  
            case 'cpanel' : $cp = null;
			                $this->cpanel_login($cp);
							
			                if (is_object($cp)) {
							  //$mydbname = paramload('DATABASE','dbname') . 'siwapp';
							
			                  if ($params[0]=='v1') {
							    //for($i=3;$i<count($params);$i++) {
								foreach ($params as $i=>$v) {
								   if (($i>=3) && ($v))
                                      $queryArgs[] = $v;					
								}   
			                    $exec = $cp->_cpapi1_exec($params[1],$params[2], $queryArgs);
								$ret = implode('<br>',$params);//$exec ? '' : '';
							  }	
                              else {//v2	
							    $queryArgs = array($params[2]=>$params[3],
								                   $params[4]=>$params[5],
												   $params[6]=>$params[7],
												   $params[8]=>$params[9],
								                   );
			                    $exec = $cp->_cpapi2_exec($params[0],$params[1], $queryArgs);
			                    $reason = $exec['reason'];
			                    $result = $exec['result'];
                                if (!$result) 
				                   $ret = $reason;							
								else
								   $ret = $ret = implode('<br>',$params);
                              }
							}
							else
							   $ret = 'Invalid cpanel login';
                            break;  	

            case 'copyrc' : 
					        $source_path = $this->upgrade_root_path . $params[0];	
		                    $target_path = $this->urlpath . '/' . $params[1];	
			                $cf = $this->copy_r($source_path, $target_path, false);
                            $ret = 'Directory created ' . $params[1];//$target_path;							
                            break;			
			               
			
			default : //do nothing....
			          $ret = null; 
		}

        return ($ret);		
	}
	
	protected function cpanel_login(&$cp) {
		$cpanel_user = 'stereobi';
		$cpanel_pass = 'Yi>~O,h/';	
        //$this->db_prefix = GetParam('dbprefix') ? GetParam('dbprefix') : 'stereobi_';		
		
        $cp = new cpanelx3($cpanel_user , $cpanel_pass);
	}
	
	//RECURSIVE COPY...
    function copy_r($path, $dest, $verbose=false)  {
        if( is_dir($path) )  {
		
            @mkdir( $dest );
            $objects = scandir($path);
			
            if( sizeof($objects) > 0 )  {
                foreach( $objects as $file ) {
                    if( $file == "." || $file == ".." )
                        continue;
                    // go on
                    if( is_dir( $path.DS.$file ) ) {
                        $this->copy_r( $path.DS.$file, $dest.DS.$file);// , $verbose);//NOT VERBOSE IN SUB DIR FOR FAST..
                    }
                    else {
                        copy( $path.DS.$file, $dest.DS.$file );
						if ($verbose) 
							echo '<br>copy from:'. $path.DS.$file. ' to '. $dest.DS.$file;
                    }
                }
            }
            return true;
        }
        elseif( is_file($path) ) {
		    if ($verbose) 
				echo '<br>copy from:'.$path. ' to '.$dest;
            return copy($path, $dest);
        }
        else {
            return false;
        }
    }		
	
	//GOOGLE ANALYTICS
	//modify .html files add google analytics..
	//except if google-analytics.html exist, recreate it
    function put_ua_code_inhtml_dir($uacode=null) {
	    $ua = $uacode ? $uacode : 'UA-XXXXXXXX'; 
	
		$sourcedir = $this->prpath . '/html/';// . $dirname;
        $uascript = "
<script type=\"text/javascript\">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '$ua']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' :
'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(ga, s);
  })();
</script>		
";		

        // if google-analytics.html exist ...
		$gfile = $sourcedir .'/google-analytics.html';
        if (is_readable($gfile)) {
		    //echo 'zzz';
			$fm = @file_put_contents($gfile, $uascript);
			$ret = $fm ? '1 file affected.' : '0 files affected';
			return ($ret);			
        }		
 
	    $fmeter = 0;
		$ret .=  '<hr>'.$sourcedir.':<br>';		
		
	    if (!is_dir($sourcedir)) {
		   $ret .= '<br>Error, invalid sourcedir! '.$sourcedir;
		   return ($ret);//false		 
		}
		  
        $mydir = dir($sourcedir);
        while ($fileread = $mydir->read ()) { 
	
           if (($fileread!='.') && ($fileread!='..') && (!is_dir($sourcedir.'/'.$fileread)) ) { 
			  
			  if ((stristr($fileread,'.htm')) && (substr($fileread,0,2)!='cp'))  {//<<cpfilename restiction
				$fdata = @file_get_contents("$sourcedir/$fileread");
				
				if (stristr($fdata, "_gaq.push(['_setAccount',")) { 
				    //code already injected	
					//replace uacode only....
				    //$ret .= '<br>Replace UA-XXXXXXXX='.$ua;
					
					//find ua code inserted...
					$eua = array();
		            //preg_match_all('/<option value="(.*)">(.*)<\/option>/', $fdata, $eua);
					preg_match('/_gaq.push\(\[\'\_setAccount\', \'(.*)\'\]/', $fdata, $eua);
					//print_r($eua);
		            //echo $eua[1],'..<br>';					
					$existed_ua = $eua[1] ? $eua[1] : 'UA-XXXXXXXX';
					
					$fdata = str_replace($existed_ua,$ua,$fdata);  
				}
                else { //inject script
				    $ret .= '<br>Add Google code';//.$uascript;
					$fdata = str_replace('</head>',$uascript.'</head>',$fdata);				
                }    		
				
		        @file_put_contents("$sourcedir/$fileread", $fdata);
				//$ret .=  ' to:'.$sourcedir.'/'.$fileread.'<br>'; 
				$fmeter+=1; 				
			  }
           }
        }
        $mydir->close ();	
		
		$ret = $fmeter ? $fmeter . ' files affected.' : '0 files affeceted.';
		return ($ret);
	}

	//disabled
	protected function install_siwapp() {
	    //return true;//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		$symfony_path = $this->prpath . '/symfony';	
		$siwapp_path = $this->urlpath . '/siwapp';	
	
		//copy files...
		//copy symfony framework into cp directory
		$this->copy_r($this->upgrade_root_path . 'cp/symfony', $symfony_path, true);	
		//copy siwapp files into app root (can't be into cp rewrite engine error!!)
		$this->copy_r($this->upgrade_root_path . 'siwapp', $siwapp_path, true);	
		
		//chmode folders
		//... no need
		
		//override config.php..NO NEED..FIXED INSIDE FILE..?????
		/*$new_conf_contents = "
<?php
\$sw_installed = false;
\$options['sf_web_dir'] = dirname(__FILE__);
\$options['sf_root_dir'] = realpath('$symfony_path');		
";
        //save a copy
        @rename($siwapp_path . '/config.php',$siwapp_path.'/_config.php');
        //override		
        @file_put_contents($siwapp_path.'/config.php', $new_conf_contents);  
        */
		/*
        //override index.php NO NEED..FIXED INSIDE FILE..
        $find = "'siwapp', 'prod', false";
		$replace = "'siwapp', 'prod', true";
        $new_index_contents = str_replace($find, $replace, file_get_contents($siwapp_path . '/index.php'));				
        //save a copy
        @rename($siwapp_path . '/index.php',$siwapp_path.'/_index.php');
        //override		
        @file_put_contents($siwapp_path.'/index.php', $new_index_contents); 		
		*/
		//create db for siwapp
		$cpanel_user = 'stereobi';
		$cpanel_pass = 'Yi>~O,h/';	
        //$this->db_prefix = GetParam('dbprefix') ? GetParam('dbprefix') : 'stereobi_';		
		
        $cp = new cpanelx3($cpanel_user , $cpanel_pass);

		if ($cp) {
		    //read local config
			$mydbname = paramload('DATABASE','dbname') . 'siwapp'; //create db as exiting name plus siwapp word
		    $mydbuser = paramload('DATABASE','dbuser');
			$mydbpass = paramload('DATABASE','dbpwd');
		
			$db_error = $cp->_cpapi1_exec('Mysql', 'adddb', array($mydbname));	
            $this_log .= 'Add Database (' . $mydbname . '):' . $db_error.'<br/>';
            if ($db_error) {
			    $this->log = $db_error;
				return false;
            }			
			
			//user exist...
			/*$dbuser_error = $cp->_cpapi1_exec('Mysql', 'adduser', array($mydbuser, $mydbpass));
			$this_log .= 'Add Database user (' . $mydbuser . '):' . $dbuser_error.'<br/>';
            if ($dbuser_error) {
			    $this->log = $dbuser_error;
				return false;
            }*/				
			
			$adduser_error = $cp->_cpapi1_exec('Mysql', 'adduserdb', array($mydbname, $mydbuser,'all'));//select create delete insert update alter drop'));
			$this_log .= 'Add Database user (' . $mydbuser . ') to DB:' . $adduser_error.'<br/>';
            if ($adduser_error) {
			    $this->log = $adduser_error;
				return false;
            }				
			
			$addadmin_error = $cp->_cpapi1_exec('Mysql', 'adduserdb', array($mydbname,'admin','all'));
			$this_log .= 'Add Database Admin user (admin) to DB:' . $addadmin_error.'<br/>';	
            if ($addadmin_error) {
			    $this->log = $addadmin_error;
				return false;
            }			
		}
		else
		    $this->log .= "Can't access cp!";
		
		return true;
	}	
  
};
}
?>