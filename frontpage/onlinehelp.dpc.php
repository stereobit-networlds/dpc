<?php

/*TEST NEW SEC APPROACH.............WITH PCNTL..
if (defined("FRONTPAGE_DPC")) {

$__DPCSEC['HEADER_DPC']='1;1;1;1;1;1;1;1;9';

if ( (!defined("HEADER_DPC")) && (seclevel('HEADER_DPC',decode(GetSessionParam('UserSecID')))) ) {*/
define("ONLINEHELP_DPC",true);

$__DPC['ONLINEHELP_DPC'] = 'onlinehelp';

$d = GetGlobal('controller')->require_dpc('frontpage/advs.dpc.php');
require_once($d); 

class onlinehelp extends advertiser {

    var $projectpath;
	var $picpath,$webpath;
	var $rules;
	
	var $sparam,$rllfile;

	function onlinehelp() {
  
      //advertiser::advertiser();
      
      $this->projectpath = paramload('SHELL','prpath');
	  
	  $this->webpath = $this->projectpath . paramload('ONLINEHELP','webres');
	  $this->picpath = paramload('ONLINEHELP','picres');
	  
	  $this->sparam = '_OLHELP';
	  $this->rllfile = 'olhelp.rll';	 
	  $this->read_rules($this->sparam,$this->rllfile);	  	  
	}

	//overwrite 
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
	
	//overwrite
    function gettarget() { 	
	
	  $targets = array();
	  //magic dac..get the contents of loaded page and analyze it
	  $loadedpage = GetGlobal('controller')->calldpc_var('pcntl.fpdata');
	  //echo $loadedpage;
	  
	  //$ret = $loadedpage;
	  $rules = GetPreSessionParam($this->sparam);//????
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
	
	//overwrite
	function read_rules($sesparam,$file) {
	
	  //if rules exists return
	  if (is_array(GetPreSessionParam($sesparam))) return 0;
	
	  $frules = file_get_contents($this->projectpath . $file);
	
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
	  SetPreSessionParam($sesparam,$rules);//???
	  $this->rules = $rules;
	}

}
/*}
}
else die("FRONTPAGE DPC REQUIRED!");*/
?>