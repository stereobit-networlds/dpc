<?php

$__DPCSEC['RCPRICELIST_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCPRICELIST_DPC")) && (seclevel('RCPRICELIST_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCPRICELIST_DPC",true);

$__DPC['RCPRICELIST_DPC'] = 'rcpricelist';

$d = GetGlobal('controller')->require_dpc('newsrv/newsrv.dpc.php');
require_once($d); 

GetGlobal('controller')->get_parent('NEWSRV_DPC','RCTRY_DPC');

$__EVENTS['RCPRICELIST_DPC'][0]='pricelist';
 
$__ACTIONS['RCPRICELIST_DPC'][0]='pricelist';

$__DPCATTR['RCPRICELIST_DPC']['pricelist'] = 'pricelist,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCPRICELIST_DPC'][0]='RCPRICELIST_DPC;Price list;Price list';

class rcpricelist extends newsrv {

    var $message;
	
	function rcpricelist() {
	
	    newsrv::newsrv();
	
	    //newsrv
	    $this->prpath = paramload('SHELL','prpath');
	    $this->path = paramload('SHELL','prpath') . paramload('RCPRICELIST','dirname'); 
	    //echo $this->path;  
		
	    $this->title = localize('RCPRICELIST_DPC',getlocal());		
	}
	
    function event($sAction) {
		
       //newsrv::event($sAction);	
	   
   	   $this->readnews($this->nbuffer,$this->preordered); 		
    }
  
    function action($action) { 
	 
     $out = setNavigator($this->title);	  	 

	 $out .= $this->shownews($this->nbuffer,null,1);
	 
	 return ($out);
    } 
	
	//overwrite
	function shownews($nbuffer=null,$head=null,$iniorder=null) {

       if ($head) $out = setNavigator(localize("RCPRICELIST_DPC",getlocal()));
	   
	   $maxc = count($this->newsfiles);
	   
	   if ($nbuffer) $this->nbuffer = $nbuffer;   
	   //if ($maxc<$this->nbuffer) $this->nbuffer = $maxc;
	   
	   if ($iniorder)
	     $this->newsfiles = $this->reorder_by_ini($this->newsfiles);
	
	   //for ($i=0;$i<$this->nbuffer;$i++) {
	   $i=0;
	   if (is_array($this->newsfiles)) {
	   foreach ($this->newsfiles as $times=>$file) {
	   //echo $times,':',$file,'<br>';
	     $f = $this->path.$file; //echo $f;
	     $contents = str_replace('%20',' ',file_get_contents($f));//remove %20 blank spaces
		 
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
		   		 
		   return $myproduct['product_release'];
		 }
      }
	  
      return null;	  
    } 
	
	function reorder_by_ini($data) {
       $actfile = paramload('SHELL','prpath') . "product_details" . ".ini";		
	   $pdetails=@parse_ini_file($actfile,1);
	   //print_r($pdetails);
	   if (is_array($data)) {
	      $i=0;
		  foreach ($pdetails as $p=>$pd) {
		    foreach ($this->extensions as $num=>$ext) {
		      if (in_array($p.$ext,$data)) {
			    $neworder[] = $p.$ext;
			  }
			}  
		  }

		  //print_r($neworder);
		  return ($neworder);
	   }
	}			
  
};
}
?>