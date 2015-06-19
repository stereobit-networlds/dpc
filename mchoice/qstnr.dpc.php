<?php

$__DPCSEC['QSTNR_DPC']='1;1;1;1;1;1;1;1;9';
$__DPCSEC['QSTNRCANCEL_']='1;1;1;1;1;1;1;1;9';
$__DPCSEC['QSTNRESULTS_']='2;1;1;1;1;1;1;2;9';

if ((!defined("QSTNR_DPC")) && (seclevel('QSTNR_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("QSTNR_DPC",true);

$__DPC['QSTNR_DPC'] = 'qstnr';
 
$__EVENTS['QSTNR_DPC'][0] = 'question';
$__EVENTS['QSTNR_DPC'][1] = 'viewquest';

$__ACTIONS['QSTNR_DPC'][0]= 'question';
$__ACTIONS['QSTNR_DPC'][1]= 'viewquest';

$__DPCATTR['QSTNR_DPC']['question'] = 'question,0,1,1,0,0,0,0'; 
$__DPCATTR['QSTNR_DPC']['viewquest'] = 'viewquest,0,1,1,0,0,0,0'; 

//$__LOCALE['QSTNR_DPC'][0]="QSTNR_DPC;Questionaire;Ερωτηματολόγιο";
$__LOCALE['QSTNR_DPC'][1]="_QSTNR;Questionaire;Ερωτηματολόγιο";
$__LOCALE['QSTNR_DPC'][2]="_NEXT;Next;Επόμενο";
$__LOCALE['QSTNR_DPC'][3]="_END;End;Τέλος";
$__LOCALE['QSTNR_DPC'][4]="_QSTNRCANCEL;Cancel;Ακυρο";



//include_once("mchoice.dpc.php");
//GetGlobal('controller')->include_dpc('mchoice/mchoice.dpc.php');
require_once(GetGlobal('controller')->require_dpc('mchoice/mchoice.dpc.php'));

class qstnr extends multichoice {
	
	var $userLevelID;

	var $datadir;
	var $picdir;
	
	var $question;
	var $answer_set;
	var $queststep;
	var $step;
	var $errmsg;
	var $resvotes;
	var $votecounter;	
	
	var $alone;
	
	function qstnr() {
	   $controller = GetGlobal('controller');
	   $UserSecID = GetGlobal('UserSecID');
	   $step = GetReq('step'); 
	   
       $this->alone = $controller->get_attribute('QSTNR_DPC','question',3);	   
	   
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
       $this->step = ((isset($step)) ? ($step) : 0);	//echo '>>>',$this->step;   	   
	   
	   $this->datadir = paramload('SHELL','prpath');	   
	   $this->picdir = paramload('SHELL','picpath');		   
	   
	   $this->question = arrayload('QSTNR','questions'); //print_r($this->question);
	   $this->answer_set = arrayload('QSTNR','answers'); //echo($this->answer_set);	
	   
	   $this->datadir = paramload('SHELL','prpath');
	   $this->errmsg = null;	   	      
	}
	

    function event($action) {   	      
	   
	   switch ($action) {
	      case "viewquest" : $this->calculate_answer($this->step); break;	  
		  
	      default          : $this->save_answer($this->step);
		                     $this->event_loop($this->step); 
		                     $this->save_cookie(count($this->question)); //save cookie at the end of qstns  
		}   
    }
	
    function action($action) {   
	   
	   switch ($action) {
	      case "viewquest" : $res = $this->show_answer($this->step); break;
		  
	      default          : $res = $this->action_loop($this->step);			   
	   }   

	   $out = $res;
	   	   				   	 
	   return ($out);	   	 
    }	
	
	function event_loop($step=0) {

	   $answers = str_replace(".",",",$this->answer_set[$step]); 
       //echo $answers;
	   $this->queststep = & new multichoice('question',$answers,0,false);
	   
	}
	
	function action_loop($step=0) {

	   if (!$this->alone)
         $ret = setNavigator(localize('_QSTNR',getlocal()));	
	   else
	     $ret = "<BR/><BR/>";	  
	   
	   $qs1[] = loadimage(paramload('QSTNR','logo')); 
	   $qa1[] = 'left;50%';	       
	   
	   $img = arrayload('QSTNR','images'); 
	   $qs2[] = loadimage($img[$step]); 
	   $qa2[] = 'left;50%';	   
		
	   if ($step==0) {
	     $qs1[] = "";	   
	   	 $qs2[] = "<br/><br/><B>" . paramload('QSTNR','intro') . "</B><br/>". $this->start_button();	   
	   }	   	   
	   else
	   if ($step<count($this->question)) {
	     $qs1[] = "<br/><br/><B>" . $this->question[$step] . "</B>";	   		   
	     $qs2[] = $this->load_quest($step);	   
	   }
	   else {
	     $qs1[] = "";	   
	   	 $qs2[] = "<br/><br/><B>" . paramload('QSTNR','outro') . "</B><br/>" . $this->end_button();	
	   }	 
		 
	   $qa1[] = 'left;50%';   		     
	   $qa2[] = 'left;50%';
	   	   
		   
	   $win = new window('',$qs1,$qa1);
	   $winout = $win->render("center::100%::0::group_article_body::left::0::0::");
	   unset ($win);	
	   		   
	   $win = new window('',$qs2,$qa2);
	   $winout .= $win->render("center::100%::0::group_article_body::left::0::0::");
	   unset ($win);
	   
	   $win = new window(localize('_QSTNR',getlocal()),$winout);
	   $ret .= $win->render("center::50%::0::group_win_body::left::0::0::");
	   unset ($win);	   
	   
	   return ($ret);
	} 	
	
	function load_quest() {
	
	   $this->step+=1;  //inc step
	   
       $myaction = seturl("t=question&step=".$this->step); 	   
	   
       $out .= "<form method=\"POST\" action=\"";
       $out .= "$myaction";
       $out .= "\" name=\"question\">";
	   
       $out .= $this->queststep->render();	   	
	   
       $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"question\">";		 
       $out .= "<input type=\"submit\" name=\"submit\" value=\"".localize('_NEXT',getlocal())."\">";		 
       $out .= "</FORM>";
	   
       if (seclevel('QSTNRCANCEL_',$this->userLevelID)) 
         $out .= $this->end_button();		   
	   
	   return ($out);			   	
	}
	
	function start_button($action='question') {
	   
       $myaction = seturl("t=$action&step=1"); 	
	   
       $out .= "<form method=\"POST\" action=\"";
       $out .= "$myaction";
       $out .= "\" name=\"question\">";	 
       $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"$action\">";		 
       $out .= "<input type=\"submit\" name=\"submit\" value=\"".localize('_NEXT',getlocal())."\">";		   
       $out .= "</FORM>";	
	   
       if (seclevel('QSTNRCANCEL_',$this->userLevelID)) 
         $out .= $this->end_button();		 
	   	   	 	   
	   return ($out);     
	}
	
	function end_button() {
	  
	   $this->save_cookie($this->step);//save cookie on current step
	
       $myaction = seturl("t=".paramload('QSTNR','goto')); 	
	   
       $out .= "<form method=\"POST\" action=\"$myaction\">";	 
       //$out .= "<input type=\"hidden\" name=\"FormAction\" value=\"question\">";		 
       $out .= "<input type=\"submit\" name=\"submit\" value=\"".localize('_END',getlocal())."\">";		 
       $out .= "</FORM>";	
	   
	   return ($out);  	
	}
	
	function save_cookie($onstep) {
	
	   if ((paramload('SHELL','cookies')) && ($this->step==$onstep)) {

		 $val = paramload('QSTNR','cookie');
		 
	     if ($_COOKIE[$val]) {
		  //echo "COOKIE OK";
		 } 
		 else {
		   //echo "NO COOKIE";		 
		   $exp = paramload('QSTNR','expire');
           setcookie($val, "1", time() + $exp);
		 }
	   }
	}
	
	function save_answer($step) {
	
	   if ($step>=0) {
	   	   
         $answer = GetParam('question') . "\n";	   
	     $afile = str_replace('?','',$this->question[$step-1]); //step-1 = prev quest
	     //echo GetParam('question'); 
	   
	     if ((isset($answer)) && ($afile)) {	    
              $actfile = $this->datadir . $afile . ".txt";							
              if ($fp = @fopen ($actfile , "a+")) {
                 fwrite ($fp, $answer);
                 fclose ($fp);
	          }
	          else {
	             $this->errmsg = "File creation error !";
		         setInfo("File creation error !");
		      }	
	      }
		}
	}	
	
	function calculate_answer($step) {
	
       if (seclevel('QSTNRESULTS_',$this->userLevelID)) 	{
	   
	   if ($step>=0) {
	   	      
	     $afile = str_replace('?','',$this->question[$step]);
	     //echo GetParam('question'); 
	   
	     if ($afile) {	    
              $actfile = $this->datadir . $afile . ".txt";							
              if (file_exists($actfile)) {
			                $fdata = file($actfile);
		                    if (is_array($fdata)) {
							  //calculate results
                              $this->resvotes = array_count_values($fdata);
							  $this->votecounter = count($fdata);
	                        }
	                        else {
	                          $this->errmsg = "Empty file !";
		                      setInfo("Empty file !");
		                    }			  
	          }
	          else {
	             $this->errmsg = "Can't read file or file not exist!";
		         setInfo("Can't read file or file not exist!");
		      }	
	      }
	   }	
	   
	   }//security
	}
	
	function design_answer() {
	
	   $this->step+=1;  //inc step
	   	
       if (!$this->errmsg) {
	       if ($this->resvotes) {
							  
			  //$res = setNavigator(localize('_VRESLT',getlocal()));
						 
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
		
		//next button   
        $myaction = seturl("t=viewquest&step=".$this->step); 	   
	   
        $res .= "<form method=\"POST\" action=\"";
        $res .= "$myaction";
        $res .= "\" name=\"question\">";			 
			 
        $res .= "<input type=\"hidden\" name=\"FormAction\" value=\"viewquest\">";		 
        $res .= "<input type=\"submit\" name=\"submit\" value=\"".localize('_NEXT',getlocal())."\">";		 
        $res .= "</FORM>";				   
		   
		return ($res);  
	}			
	
	function show_answer($step) {
	
       if (seclevel('QSTNRESULTS_',$this->userLevelID)) 	{	
	
	     if (!$this->alone)
           $ret = setNavigator(localize('_QSTNR',getlocal()));	
	     else
	       $ret = "<BR/><BR/>";
		 
	     $qs1[] = loadimage(paramload('QSTNR','logo')); 
	     $qa1[] = 'left;50%';	       
	   
	     $img = arrayload('QSTNR','images'); 
	     $qs2[] = loadimage($img[$step]); 
	     $qa2[] = 'left;50%';	
	   
	     if ($step==0) {
	       $qs1[] = "";	   
	   	   $qs2[] = "<br/><br/><B>" . paramload('QSTNR','intro') . "</B><br/>". $this->start_button('viewquest');	   
	     }	   	   
	     else
	     if ($step<count($this->question)) {
	       $qs1[] = "<br/><br/><B>" . $this->question[$step] . "</B>";	   		   
	       $qs2[] = $this->design_answer(); //$this->load_quest($step);	   
	     }
	     else {
	       $qs1[] = "";	   
	   	   $qs2[] = "<br/><br/><B>" . paramload('QSTNR','outro') . "</B><br/>" . $this->end_button();	
	     }	 
		 
	     $qa1[] = 'left;50%';   		     
	     $qa2[] = 'left;50%';
	   
	     $win = new window('',$qs1,$qa1);
	     $winout = $win->render("center::100%::0::group_article_body::left::0::0::");
	     unset ($win);	
	   		   
	     $win = new window('',$qs2,$qa2);
	     $winout .= $win->render("center::100%::0::group_article_body::left::0::0::");
	     unset ($win);
	   
	     $win = new window(localize('_QSTNR',getlocal()),$winout);
	     $ret .= $win->render("center::50%::0::group_win_body::left::0::0::");
	     unset ($win);	   
	   }
	   
	   return ($ret);	   	    		 		
	}
	
	function free() {
	   //release object
	   unset ($this->queststep);
	}

};
}
?>