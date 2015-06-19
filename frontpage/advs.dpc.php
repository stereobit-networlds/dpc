<?php

/*TEST NEW SEC APPROACH.............WITH PCNTL..
if (defined("FRONTPAGE_DPC")) {

$__DPCSEC['HEADER_DPC']='1;1;1;1;1;1;1;1;9';

if ( (!defined("HEADER_DPC")) && (seclevel('HEADER_DPC',decode(GetSessionParam('UserSecID')))) ) {*/
define("ADVS_DPC",true);

$__DPC['ADVS_DPC'] = 'advertiser';

$__PARSECOM['ADVS_DPC']['render']='_ADVERTISE_';

class advertiser {

    var $projectpath;
	var $picpath,$webpath;
	var $rules;

	function advertiser() {
      $UserSecID = GetGlobal('UserSecID');  

      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
      $this->projectpath = paramload('SHELL','prpath');
	  
	  $this->webpath = $this->projectpath . paramload('ADVERTISER','webres');
	  $this->picpath = paramload('ADVERTISER','picres');
	  	 
	  $this->read_rules();	  	  
	}

    function render($pw='100%',$pal='') { 
	  
	  $ret = null;
	  $targetarray = $this->gettarget();
	  if (is_array($targetarray)) {
	    //array_reverse($targetarray,true);
		//print_r($targetarray);
	    foreach ($targetarray as $id=>$targetfile) {
	      //html
	      if ((stristr($targetfile, '.htm')) || (stristr($targetfile, '.html'))) {
	        $ret .= @file_get_contents($this->projectpath.$targetfile);
	      }//pic
	      elseif ((stristr($targetfile, '.jpg')) || (stristr($targetfile, '.gif'))) {
	        $ret .= loadimage($targetfile,'advertiser',$this->picpath);
	      }//flash
	      elseif (stristr($targetfile, '.swf')) {
	        $swf = new flash;
		    $ret .= $swf->render($targetfile,$this->picpath,200,400);
		    unset($swf);
	      }//a frontpage
	      elseif (stristr($targetfile, '.xgi')) {	  
	        $parts = explode(".",$targetfile);
	        $fr = new frontpage($parts[0]);
		    $ret .= $fr->render();
		    unset($fr);
	      }
          elseif (stristr($targetfile, '.dpc.php')) {//php script
			$params = explode(",",$targetfile);//get params after ,
	        require_once($this->projectpath.$params[0]);			
	        $parts = explode(".",$params[0]);
	        $dpc = new $parts[0];
		    $ret .= $dpc->render($params[1],$params[2],$params[3]);
		    unset($fr);
	      }	
		  else {//default dpc script
		    $ret .= GetGlobal('controller')->calldpc_method($targetfile);
		  }    
	    }
	  }	
	  return ($ret);
	}
	
    function gettarget() { 	
	
	  $targets = array();
	  //magic dac..get the contents of loaded page and analyze it
	  $loadedpage = GetGlobal('controller')->calldpc_var('pcntl.data');
	  
	  //$ret = $loadedpage;
	  $rules = GetPreSessionParam('_ADVRULES');//????
	  $rules = $this->rules;
	  //print_r($rules);
      if (is_array($rules)) {
	    //print_r($rules);
	    foreach ($rules as $target=>$exp) {
		  $words = explode(",",$exp);
		  foreach ($words as $id=>$term) {
		    if (@stristr(strip_tags($loadedpage),$term)) {
			  if (!in_array($target,$targets))
			    $targets[] = $target; //array of target rules (multiple)
			  //print_r($targets);
			}  
		  }
		}
	  }
	  
	  if (count($targets)>0)
	    return ($targets);
	  else	
	    return (null);
    }
	
	function read_rules() {
	
	  //if rules exists return
	  if (is_array(GetPreSessionParam('_ADVRULES'))) return 0;
	
	  $frules = file_get_contents($this->projectpath . "advrules.rll");
	
	  $tokens = explode(";",$frules);
	  
	  foreach ($tokens as $id=>$data) { 
	    //echo $data,"<br>";
		$tdata = trim($data);
	    if ($tdata[0]!="#") {		
	      $part = explode("->",$tdata);
		  $togo = trim($part[0]);
		  $tosearch = trim($part[1]);
	      $rules[$togo] = $tosearch; 
		}  
	  }
	  //print_r($rules);
	  SetPreSessionParam('_ADVRULES',$rules);//???
	  $this->rules = $rules;
	}

}
/*}
}
else die("FRONTPAGE DPC REQUIRED!");*/
?>