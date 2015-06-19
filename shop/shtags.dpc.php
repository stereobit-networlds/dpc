<?php
$__DPCSEC['SHTAGS_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("SHTAGS_DPC")) && (seclevel('SHTAGS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHTAGS_DPC",true);

$__DPC['SHTAGS_DPC'] = 'shtags';
 
$__EVENTS['SHTAGS_DPC'][0]='cpshtags';
$__EVENTS['SHTAGS_DPC'][1]='kshow';
$__EVENTS['SHTAGS_DPC'][2]='klist';

$__ACTIONS['SHTAGS_DPC'][0]='cpshtags';
$__ACTIONS['SHTAGS_DPC'][1]='kshow';
$__ACTIONS['SHTAGS_DPC'][2]='klist';

//GetGlobal('controller')->get_parent('SHKATALOGMEDIA_DPC','SHTAGS_DPC');

$__DPCATTR['SHTAGS_DPC']['cpshtags'] = 'cpshtags,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['SHTAGS_DPC'][0]='SHTAGS_DPC;Tags;Tags';

class shtags {

   var $result, $zeroprice_msg;
   var $default_tag;
   var $meta_file, $default_meta_file;
   var $prpath;
   var $item,$descr,$price,$keywords;

   function shtags() {
   
     $this->prpath = paramload('SHELL','prpath');
   
     $this->default_tag = paramload('SHTAGS','tags');//"vehicles, sales, cars, motorcycles";
	 
	 $mf = remote_paramload('SHTAGS','metafile',$this->prpath);
	 $this->meta_file = $mf?$mf:'meta.txt'; 
	 
	 $df = remote_paramload('SHTAGS','defmetafile',$this->prpath);
	 $this->default_meta_file = $df?$df:'metad.txt'; 	 
   }
   
   function event($evn=null) {
   
	 //if ((GetReq('id')) || (GetReq('cat'))) {
	   $this->get_data_info();
	 //}   
     //echo 'tag';	 
   }
   
   function action($act=null) {
     //echo 'z';
	 //$this->get_data_info();
   }
   
   function get_category_info() {
     $cat = GetReq('cat');
   
     if ($cat) {
       if (defined("RCCATEGORIES_DPC")) {
	     	$cstring = explode('^',str_replace('_',' ',$cat));
	   }
       elseif (defined("RCKATEGORIES_DPC")) {	
	     	$cstring = rawurldecode(explode('^',str_replace('_',' ',$cat)));	   	        
	   }
	   
	   $ret = implode(',',$cstring);
			   
	   if ($this->meta_file) {
		
		  $out = $this->get_meta_file($ret);
		  return ($out);
	   }	   
	   else
		  return ($ret); 	   
	 }  
   }
   
   function get_data_info() {
		$item = GetReq('id');	
		$cat = GetReq('cat');
	    $lan = getlocal();
	    $itmname = $lan?'itmname':'itmfname';
	    $itmdescr = $lan?'itmdescr':'itmfdescr';		
		//echo '>';
		
		if (defined('SHKATALOGMEDIA_DPC')) {
		  //ECHO 'A';
		  $this->result = GetGlobal('controller')->calldpc_var("shkatalogmedia.result");
		  $ppol = GetGlobal('controller')->calldpc_method("shkatalogmedia.read_policy");
		}  
		elseif (defined('SHKATALOG_DPC')) {
		  //ECHO 'B';
		  $this->result = GetGlobal('controller')->calldpc_var("shkatalog.result");
          $ppol = GetGlobal('controller')->calldpc_method("shkatalog.read_policy");		  
		} 		
		//else echo 'NODPC';  
		
        //print_r($this->result->sql);
		//echo $this->result->fields['id'];
		
	    if ((!$this->result) || (!$item) || (!$cat)) {
		  $out = @file_get_contents($this->prpath . $this->default_meta_file);
		  //return ($out); 		
		  $this->page_tags = $out;
		}
   
	  /*  foreach ($this->result as $n=>$rec) {
		
		   $item = $rec['itmname'];
		   $descr = nl2br($rec['itmremark']);
		   //$price = $rec['price0'];		
		   
		   if ($rec['price0']>0)
		     $price = ($rec['price0']?number_format($rec['price0'],2,',','.'):"&nbsp;") . "<br>";
			 // . $this->zeroprice_msg;
		   else 	 
		     $price = $this->zeroprice_msg;			   
		}
		
		$extras1 = str_replace("<br>",",",$extras);
		$extras2 = str_replace("<br />",",",$extras1);		
	*/
	
		if ($cat4 = $this->result->fields['cat4']) {
		    $mytree[] = $cat4;
		}	
		if ($cat3 = $this->result->fields['cat3']) {
		    $mytree[] = $cat3;			
		}	
		if ($cat2 = $this->result->fields['cat2']) {
		    $mytree[] = $cat2;		
		}	
		if ($cat1 = $this->result->fields['cat1']) {
		    $mytree[] = $cat1;
		}	
		if ($cat0 = $this->result->fields['cat0']) {
		    $mytree[] = $cat0;			
		}	
	    //print_r($mytree);
		
        $thetree = (!empty($mytree))?implode(',',$mytree):null;
		
	    if ($item) {
		
		  $this->item = $this->result->fields[$itmname];
		  $this->descr = $this->result->fields[$itmdescr];
		  $this->price = $this->result->fields[$ppol];
		  $this->keywords = str_replace(' ',',',$this->item) . ',' . str_replace(' ',',',$this->descr) . ',' . $this->price . 
		                    ',' . $thetree;
		}
		elseif ($cat) {
		  $this->item = (!empty($mytree))? array_shift($mytree):null;
		  $this->descr = $this->item .',' . $thetree;
		  $this->price = null;
		  $this->keywords = $this->item .',' . $thetree;		
		}	
		
		
	    $ret = $this->item . "<@>" . 
		       $this->descr . "<@>" . 
			   $this->price . "<@>" . 
			   $this->keywords;
			   
		//echo '>',$ret;
		
	    if (!$ret) {
		  //echo 'noret';
		  $out = @file_get_contents($this->prpath . $this->default_meta_file);
		  //return ($out); 		
		  $this->page_tags = $out;
		}			
		
        if (is_readable($this->prpath .'/'. $this->meta_file)) {
		    //echo 'Z2';
		    $out = $this->get_meta_file(explode('<@>',$ret));
		}	
		else {
		    //default tags
		    $out = @file_get_contents($this->prpath . $this->default_meta_file);
		}
		
        //return ($out); //..not it called from event..call get_page_tags to retreive..		
        $this->page_tags = $out;
   }
   
   function get_meta_file($tags=null) {
       //print_r($tags);
	   
       if ($meta_tags = @file_get_contents($this->prpath . $this->meta_file)) {
	   
	     foreach ($tags as $i=>$t) {
		   //echo $i,'=>',$t;
	       $meta_tags = str_replace('$'.$i.'$',$t,$meta_tags);
		 }
		 //echo '>',$i;
		 //clean
		 for ($x=$i;$x<=20;$x++)
		   $meta_tags = str_replace('$'.$i.'$','',$meta_tags);
		   
	     return ($meta_tags);	   
	   }   
   } 
   
   function get_page_info($key=null) {
       //echo '>'.$this->{$key};
	   
       if ($key)
	     return ($this->{$key});
       else 
	     return ($this->page_tags);
   }
};
}
?>