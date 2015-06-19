<?php
define("RCSIDEWIN_DPC",true);

$__DPC['RCSIDEWIN_DPC'] = 'rcsidewin';

$d = GetGlobal('controller')->require_dpc('frontpage/onlinehelp.dpc.php');
require_once($d); 

class rcsidewin extends onlinehelp {

    var $projectpath;
	var $picpath,$webpath;
	var $rules;
	
	var $sparam,$rllfile;	
	
	var $helpwin,$sh_symbol,$hd_symbol;
	var $external_data;

	function rcsidewin() {
	  $GRX = GetGlobal('GRX');	
  
      //onlinehelp::onlinehelp();
      
      $this->projectpath = paramload('SHELL','prpath');
	  
	  //$this->webpath = $this->projectpath . paramload('RCSONLINESUPPORT','webres');
	  //$this->picpath = paramload('RCSONLINESUPPORT','picres');
	  	 
	  //$this->sparam = '_RCSOLHELP';
	  //$this->rllfile = 'rcsolhelp.rll';	 		 
	  //$this->read_rules($this->sparam,$this->rllfile);		
	  
      if ($GRX) {    
          $this->hd_symbol = loadTheme('support_off','hide support'); 
          $this->sh_symbol = loadTheme('support_on','show support');		  
      } 
      else { 
          $this->sh_symbol = 'Support';
		  $this->hd_symbol = 'No Support';
      }		  	  	  
	}

	//overwrite 
    function render($pw='100%',$pal='',$show=null) { 
	  
	  $ret = null;
	  
	  if ($this->external_data)
		$ret = $this->external_data;	  
	  
	  if (!$ret) $ret = "&nbsp;";//"Empty!";
	  
	  //$ret = 'zzz';
	  if (($show) || (GetReq('sidewin')>0)/* || ($this->external_data)*/) //AT LOADING PAGE...
	    $this->showhide = 'SHOW';
	  else
	    $this->showhide = 'HIDE';
	   
	  $this->helpwin = new window2("SideWin",$ret,null,1,null,$this->showhide,1,1);
			
	  $wret .= $this->helpwin->render("center::100%::0::group_article_selected::left::0::0::");
	  
	  //unset ($hwin);		  
	  
	  return ($wret);
	}
	
	function set_show($data) {
	
	   
	  $this->external_data = $data;
	}
	
	function set_show_calldpc($dpc) {
	  //use PARAMS as use for recursion calldpc to calldpc function
      $this->external_data = GetGlobal('controller')->calldpc_method(str_replace('PARAMS','use',$dpc));		
	}
	
	function button($name=null,$noname=null) {
	    $GRX = GetGlobal('GRX');		
	
        if ($GRX) {    
          $this->hd_symbol = loadTheme('support_off','hide support'); 
          $this->sh_symbol = loadTheme('support_on','show support');		  
        } 
        else { 
          $this->sh_symbol = 'Support';
		  $this->hd_symbol = 'No Support';
        }			
	    
	    //$name = 'Help';
		//$noname ="No help";
		
        if ($name) {    
          $this->sh_symbol = $name;
		  $this->hd_symbol = (isset($noname)?$noname:$name);
		  //echo 'A';
        }	
	    
	    $hidename = $this->hd_symbol;
		$showname = $this->sh_symbol;
	
		$title = "SideWin";
	    $myshowname = (isset($showname) ? $showname : $title); 
		$myhidename = (isset($hidename) ? $hidename : $title); 	
			

        $hide_url = setjsurl($myhidename,"javascript:expand('show_$title');contract('hide_$title');contract('$title')","hide_$title","style=\"display:none\"");
        $show_url = setjsurl($myshowname,"javascript:expand('$title');expand('hide_$title');contract('show_$title')","show_$title");	
		  		
		$urldouble = $hide_url . $show_url;
		
		$ret = $urldouble; 	
		
	    return ($ret);	
	}
	
	function buttontext() {
	
		$title = "SideWin";
	    $myshowname = $title; 
		$myhidename = $title; 	
			

        $hide_url = setjsurl($myhidename,"javascript:expand('show_$title');contract('hide_$title');contract('$title')","hide_$title","style=\"display:none\"");
        $show_url = setjsurl($myshowname,"javascript:expand('$title');expand('hide_$title');contract('show_$title')","show_$title");	
		  		
		$urldouble = $hide_url . $show_url;
		
		$ret = $urldouble; 	
		
	    return ($ret);		
	}
	

}
?>