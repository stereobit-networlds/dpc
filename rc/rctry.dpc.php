<?php

$__DPCSEC['RCTRY_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCTRY_DPC")) && (seclevel('RCTRY_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCTRY_DPC",true);

$__DPC['RCTRY_DPC'] = 'rctry';

$d = GetGlobal('controller')->require_dpc('newsrv/newsrv.dpc.php');
require_once($d); 

GetGlobal('controller')->get_parent('NEWSRV_DPC','RCTRY_DPC');

$__EVENTS['RCTRY_DPC'][0]='try';
 
$__ACTIONS['RCTRY_DPC'][0]='try';

$__DPCATTR['RCTRY_DPC']['try'] = 'try,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCTRY_DPC'][0]='RCTRY_DPC;Try;Try';

class rctry extends newsrv {

    var $message;
	
	function rctry() {
	
	    newsrv::newsrv();
	
	    //newsrv
	    $this->prpath = paramload('SHELL','prpath');
	    $this->path = paramload('SHELL','prpath') . paramload('RCTRY','dirname'); 
	    //echo $this->path;  
		
	    $this->title = localize('RCTRY_DPC',getlocal());		

		$m = paramload('RCTRY','message');	
		$ff = paramload('SHELL','prpath').$m;
		if (is_file($ff)) {
		  $this->message = file_get_contents($ff);
		}
		else
		  $this->message = $m; //plain text		
	}
	
    function event($sAction) {
		
       newsrv::event($sAction);			
    }
  
    function action($action) {
	
	 $form_submited = GetSessionParam('FORMSUBMITED') ;

	 SetSessionParam("TRYPROCCESS",1);	 
	 
     $out = setNavigator($this->title);	  	 
	 
	 //if ($form_submited) {
	   //$out .= 'form submited';
	   $this->readnews($this->nbuffer,$this->preordered); 
	   $out .= $this->shownews($this->nbuffer);
	 /*}
	 else {
	   $out = $this->message;
	   $out .= GetGlobal('controller')->calldpc_method('rcform.action');
	 }*/
	 
	 
	 return ($out);
    } 
	
	//overwrite
	function shownews($nbuffer=null,$head=null) {

       if ($head) $out = setNavigator(localize("NEWSRV_DPC",getlocal()));
	   
	   $maxc = count($this->newsfiles);
	   
	   if ($nbuffer) $this->nbuffer = $nbuffer;   
	   //if ($maxc<$this->nbuffer) $this->nbuffer = $maxc;
	
	   //for ($i=0;$i<$this->nbuffer;$i++) {
	   $i=0;
	   if (is_array($this->newsfiles)) {
	   foreach ($this->newsfiles as $times=>$file) {
	   
	     $f = $this->path.$file; //echo $f;
	     $contents = file_get_contents($f);
		 
		 $prd = explode(".",$file);
		 $contents .= "Release: ". $this->get_product_info($prd[0]) . " | ";
		 
		 $contents .= seturl("t=root&g=".$prd[0]." history","History") . " | ";		 
 		 $contents .= seturl("t=root&g=".$prd[0]." overview","Overview") . " | ";

		 reset($this->extensions);
		 /*foreach ($this->extensions as $num=>$ext) {
		   if (stristr($file,$ext)) 
		     $title = str_replace($ext,'',$file);
			 //added locale '_locale' replace
			 $locext = '_'.getlocal();
			 $title = str_replace($locext,'',$title);
		 }  */
		 $title = $this->newstitles[$times];

		 $data[] = //'<h4>' . $title . "</h4>" . 
		           //'[<b>' . date("d-m-Y h:s",$times) . '</b>] ' .
				   $contents;
		 $attr[] = "left;99%";
		 
		 if ($p=$this->photofiles[$times]) {
		   $url = paramload('NEWSRV','dirname') . $p;
		   $data[] = "<img src=\"$url\" width=\"100\" height=\"100\" alt=\"\">";
		   $attr[] = "right;1%";
		 } 		 
				 
	     $mynews = new window(null,$data,$attr);
	     $out .= $mynews->render(" ::100%::0::group_article_body::left;100%;::");	   	 
		 unset ($mynews);
		 unset ($data); unset ($attr);
		 
		 $out .= "<hr>";
		 $i+=1;		 
		 if (($nbuffer) && ($i>=$nbuffer)) break;//local call
		 elseif ($i>$this->nbuffer) break;//global call
		 //else echo '>',$i; 
	   }
	   }
	   return ($out);
	}
	
    function get_product_info($product_id) {
   
      $selected_product = $product_id;
	  
	  //read the attributes
      $actfile = paramload('SHELL','prpath') . "product_details" . ".ini";							
	  //echo $actfile;
	  //echo $selected_product;
	 
      if ($pdetails=@parse_ini_file($actfile,1)) {
         
		 //print_r($pdetails);
		 
		 $myproduct = $pdetails[$selected_product];
		 
		 if (is_array($myproduct)) {
		   		 
		   return $myproduct['shareware_release'];
		 }
      }
	  
      return null;	  
    } 		
  
};
}
?>