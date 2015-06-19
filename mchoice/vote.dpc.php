<?php

$__DPCSEC['VOTE_DPC']='1;1;1;1;1;1;1;1;9';
$__DPCSEC['_VIEWVOTE']='2;1;1;1;1;2;2;2;9';

if ((!defined("VOTE_DPC")) && (seclevel('VOTE_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("VOTE_DPC",true);

$__DPC['VOTE_DPC'] = 'vote';
 
$__EVENTS['VOTE_DPC'][0] = 'vote';
$__EVENTS['VOTE_DPC'][1] = 'viewvote';

$__ACTIONS['VOTE_DPC'][0]= 'vote';
$__ACTIONS['VOTE_DPC'][1]= 'viewvote';

$__LOCALE['VOTE_DPC'][0]="_VOTE;Vote;Ψήφισε";
$__LOCALE['VOTE_DPC'][1]="_VRESLT;Vote Results;Αποτελέσματα";
$__LOCALE['VOTE_DPC'][2]="_VMSG;Thank you. Your Vote is;Ευχαριστουμε για την συμμετοχή σας. Επιλέξατε";
$__LOCALE['VOTE_DPC'][3]="_VOTETOTAL;Total Votes :;Συνολικοι Ψήφοι:";
//_LOCALE['VOTE_DPC'][1="VOTE_DPC;Vote;Ψήφισε";

$__PARSECOM['VOTE_DPC']['parsecom']='_VOTE_';

//include_once("mchoice.dpc.php");
//GetGlobal('controller')->include_dpc('mchoice/mchoice.dpc.php');
require_once(GetGlobal('controller')->require_dpc('mchoice/mchoice.dpc.php'));

class vote extends multichoice {
	
	var $userLevelID;
	var $name;
	var $texts;
	var $cform;
	var $initchecked;
	
	var $pretext;
	var $datadir;
	var $errmsg;
	var $resvotes;
	var $votecounter;
	
	function vote($name='vote',$pretext='',$ctextsstring=null,$checked=0,$cform=FALSE) {
	   $UserSecID = GetGlobal('UserSecID');
	
       multichoice::multichoice($name,$ctextsstring,$checked,$cform);   
	   
	   $this->pretext = $pretext;
	   
	   $this->datadir = paramload('SHELL','prpath');	   
	   $this->errmsg="";
	   $this->resvotes = null;
	}
	

    function event($action) { 
	   $t = GetReq('t');
	   $a = getReq('a'); 	   
	   
	   switch ($action) {
	      case "viewvote" : $actfile = $this->datadir . _with($a) . ".vte";
		                    $votes = file($actfile);
		                    if (is_array($votes)) {
							  //calculate results
                              $this->resvotes = array_count_values($votes);
							  $this->votecounter = count($votes);
	                        }
	                        else {
	                          $this->errmsg = "Empty file !";
		                      setInfo("Empty file !");
		                    } 
		                    break;
		  
	      default         : $myvote = GetParam("$t") . "\n";	   
	                        //echo $t,GetParam("$t"); 
	   
	                        if (isset($myvote)) {	    
                             $actfile = $this->datadir . _with($t) . ".vte";							
                             if ($fp = fopen ($actfile , "a+")) {
                                  fwrite ($fp, $myvote);
                                  fclose ($fp);
	                         }
	                         else {
	                          $this->errmsg = "File creation error !";
		                      setInfo("File creation error !");
		                     }
							} 	    
		}   
    }
	
    function action($action) { 
	   $t = GetReq('t');
	   
	   switch ($action) {
	      case "viewvote" : if (!$this->errmsg) {
	                          if ($this->resvotes) {
							  
							     $res = setNavigator(localize('_VRESLT',getlocal()));
								 
	                             foreach($this->resvotes as $votesel => $result) {
								      
									   $prc = round((100*$result)/$this->votecounter,2); 
								 
								       $elem[] = "<H4>$votesel</H4>";
									   $elat[] = "left;$prc%;center;#FF0000";
								       $elem[] = "<H4>$prc%</H4>";
									   $elat[] = "right";									   
								   	   $mwin = new window('',$elem,$elat);
	                                   $res .= $mwin->render();
	                                   unset ($mwin);	
									   unset($elem);
									   unset($elat);
								 }  
							     $res .= localize('_VOTETOTAL',getlocal()) . " " . $this->votecounter;								 
	                          }
	                        }
	                        else
	                          $res = $this->errmsg;	
		                    break;
		  
	      default    : $myvote = GetParam("$t");
	                   if (!$errmsg) {
	                     if ($myvote) {
	                       $out = "<H3>" . localize('_VMSG',getlocal()) . " " . $myvote . "!!!</H3>";	
						   
						   //SetSessionParam("vote","true");
							  
	                       $mwin = new window('',$out);
	                       $res = $mwin->render();
	                       unset ($mwin);							  							     
	                     }
	                   }
	                   else
	                     $res = $this->errmsg;					   
	   }   

	   $out = $res;
	   	   				   	 
	   return ($out);	   	 
    }	
  
    function parsecom($name='vote',$pretext='',$ctextsstring=null,$checked=0) {
	   
	   $this->name = $name;
	   $this->texts = explode(",",$ctextsstring); 
	   $this->initchecked = $checked;
	   $this->pretext = $pretext;	    	
		
       $myaction = seturl("t=$this->name&a=&g="); 	   
	   
       $out .= "<FORM method=\"POST\" action=\"";
       $out .= "$myaction";
       $out .= "\" name=\"$this->name\">";	
	   
	   $out .= $this->pretext . "<br>";
	
	   foreach($this->texts as $num => $choice) {
	   
	     if ($this->initchecked==($num+1)) $chk = 'checked';
		                              else $chk = '';
	   
         $out .= "<input type=\"radio\" name=\"$this->name\" value=\"$choice\" $chk>$choice";
		 $out .= "<br>";
	   }
	    
       $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"vote\">";		 
       $out .= "<input type=\"submit\" name=\"submit\" value=\"" . localize('_VOTE',getlocal()) . "\">";		 
       $out .= "</FORM>";	
	   
       if (seclevel('_VIEWVOTE',$this->userLevelID)) 
	   //if (GetSessionParam("vote")=="true")
	     $out .= seturl("t=viewvote&a=$this->name&g=",localize('_VRESLT',getlocal()));	   	 					   
					 
	   return ($out);
    } 	
	
};
}
?>