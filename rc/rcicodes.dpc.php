<?php

$__DPCSEC['RCICODES_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCICODES_DPC")) && (seclevel('RCICODES_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCICODES_DPC",true);

$__DPC['RCICODES_DPC'] = 'rcicodes';


$__EVENTS['RCICODES_DPC'][0]='seticode';
$__EVENTS['RCICODES_DPC'][1]='geticode';
$__EVENTS['RCICODES_DPC'][2]='genicode';
 
$__ACTIONS['RCICODES_DPC'][0]='seticode';
$__ACTIONS['RCICODES_DPC'][1]='geticode';
$__ACTIONS['RCICODES_DPC'][2]='genicode';


$__DPCATTR['RCICODES_DPC']['seticode'] = 'seticode,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCICODES_DPC'][0]='RCICODES_DPC;Enter your invitation code;Enter your invitation code';

class rcicodes  {

    var $title,$item,$type,$getit,$reason;
	var $error_message;
	var $obj;
	
	var $must_pay;
	
	function rcicodes() {
		
	 	 $this->title = localize('RCICODES_DPC',getlocal()); 
		 $this->item = null;
		 $this->type = null;
		 $thid->getit = false;	
		 $this->reason = null;	 
		 
		 $this->obj = null;
		 
		 $this->must_pay = false;
		 
		 $m = paramload('RCICODES','message');	
		 $ff = paramload('SHELL','prpath') . $m;
		 if (is_file($ff)) {
		   $this->error_message = file_get_contents($ff);
		 }
		 else
		   $this->error_message = $m; //plain text			  		  		  
	}
	
    function event($event=null) {
	
	  switch ($event) {
	  
	    case 'genicode' : $code = $this->insert_icode();
		                  break;
	  
	    case 'geticode' : $code = GetParam('icode');
		
						  if (($code) && ($this->verify_icode($code))) {
							$this->get_item($code);
							$this->disable_icode($code);
							
							//set session param that code inserted 
							SetSessionParam("CODESUBMITED",1);
							//echo $this->type,'>';
							
							switch ($this->type) {
							  
                              case 'fullversion' :
							  if ($this->must_pay==true) {
							    GetGlobal('controller')->calldpc_method("rcpaypal.process_payment use ". $this->item);
							  }
							  else {
							    GetGlobal('controller')->calldpc_method("rcpickit.get_product use ". $this->item);
							    //send mail
							    GetGlobal('controller')->calldpc_method("rcpickit.send_downloaded_mail use " . $this->item);								  
							    //$this->obj = new rcpickit;
							    //$this->obj->get_product($this->item);
							    //$this->obj->send_downloaded_mail($this->item);
							  }	
							  break;	
							  						
							  case 'shareware' :
							  GetGlobal('controller')->calldpc_method("rcdownload.get_product use ". $this->item ."+1");//=instand
							  //send mail
							  GetGlobal('controller')->calldpc_method("rcdownload.send_downloaded_mail use " . $this->item);						  
							  //$this->obj = new rcdownload;
							  //$this->obj->get_product($this->item,1);
							  //$this->obj->send_downloaded_mail($this->item);							  
							  break;
							}
							$this->getit = true;
						  }
						  else
						    $this->getit = false;
		                  break;
	    case 'seticode' :
		                  break;				  					  
		//case 'instant'  : break;	
			
	  }
			
    }
  
    function action($action=null) {
	   	 
	  //$this->reset_db();
	 
	  switch ($action) {
	  
	    case 'genicode' : $out .= $this->show_icode();
		                  break;	  
	  
	    case 'geticode' : $out = setNavigator($this->title) ;
						  if ($this->getit===true) {
						    //&& (is_object($this->obj))) {
						    //$out .= "Valid code".$this->type;
							//$out .= 'getit=true';
							
							switch ($this->type) {
							
                              case 'fullversion' :
							  $out .= GetGlobal('controller')->calldpc_method("rcpickit.show_product_link use ". $this->item . "+1+fullversion");
							  //$out .= $this->obj->show_product_link($this->item,1);
							  break;
							  
							  case 'shareware' :
							  $out .= GetGlobal('controller')->calldpc_method("rcdownload.show_product_link use ". $this->item);
							  //$out .= $this->obj->show_product_link($this->item);
							  break;
							}  
						  }
						  else {
						    $out .= $this->error_message;//"Invalid code";//$this->reason;//"Invalid code";
						    $out .= $this->iform();
						  }
		                  break;
						  
	    case 'seticode' : $out = setNavigator($this->title) ;
		
	                      $template = paramload('SHELL','prpath') . "inputcode.tpl";
	                      $out .= file_get_contents($template); 			
		                  $out .= $this->iform();
		                  break;	
						  
		//case 'instant'  : $out .= $this->instant_download($this->item);
			//			  break;
				
	  }	 
	 
	 return ($out);
    }
	
	function iform() {
	
	  //return from pay
	  if (GetReq('action')) {
	  
        GetGlobal('controller')->calldpc_method("rcpaypal.event");	  
	    $out .= GetGlobal('controller')->calldpc_method("rcpaypal.action");
		return ($out);
	  }

      $filename = seturl("t=geticode",0,1);      

      $toprint  = "<FORM action=". $filename . " method=post class=\"thin\">";
	  $toprint .= "<STRONG>Input invitation code :</STRONG>"; 
	  $toprint .= "<input type=\"text\" name=\"icode\" value=\"\" size=\"64\" maxlength=\"128\">";
	  $toprint .= "<input type=\"submit\" name=\"Submit\" value=\"Ok\">"; 
      $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"geticode\">";
      $toprint .= "</FORM>";
	   
	  $data2[] = $toprint; 
  	  $attr2[] = "left";

	  $swin = new window($this->title,$data2,$attr2);
	  $out .= $swin->render("center::100%::0::group_dir_body::left::0::0::");	
	  unset ($swin);

      return ($out);
	}
	
	function insert_icode($product=null,$type=null,$cmail=null,$daysofexpire=1)  {
        $db = GetGlobal('db');
        $product = $product ? $product : GetReq('product');		
		$type= $type ? $type : GetReq('type');
		$cmail = $cmail ? $cmail : GetReq('email');
		$daysofexpire = GetReq('expire') ? GetReq('expire') : 1;
		$must_pay = GetReq('pay') ? 1 : 0;
		
		$invcode = $this->generate_code($product.$type.$cmail);//'123-124';//generate code
		$days = time() + ($daysofexpire * 24 * 60 * 60);//one
		$expire = date('Y-m-d',$days);		
		
        $sSQL2 = "insert into icodes ";
		$sSQL2 .= "(insdate,active,itemcode,itemname,email,downtimes,invcode,expire,mustpay) values (";
		$sSQL2 .= $db->qstr(date('Y-m-d')) . 
		          ",1,'$type','$product','$cmail',0,'$invcode','$expire',$must_pay";		
		$sSQL2 .= ")";
																		
        $db->Execute($sSQL2,1);  	
		//echo $sSQL2;
		
		return $invcode;
	}
	
	function show_icode() {
	
        $product = $product ? $product : GetReq('product');		
		$type = $type ? $type : GetReq('type');
		$cmail = $cmail ? $cmail : GetReq('email');
		$daysofexpire = GetReq('expire') ? GetReq('expire') : 1;

        $invcode = $this->generate_code($product.$type.$cmail);
        //return $invcode; 		
		
	    $template = paramload('SHELL','prpath') . "showcode.tpl";
	    $out = str_replace('@CODE@',$invcode,file_get_contents($template));
		
		$html_out = '<html><head><base href="http://stereobit.gr"></head>'. $out . '</html>';
		
        //$this->tell_by_mail(
	    GetGlobal('controller')->calldpc_method("rcdownload.tell_by_mail use " .
	                      $this->type . " invitation code"."+".
	                      'sales@stereobit.gr'."+".
		                  $cmail."+".
						  $html_out);		

        return ($out); 		
	}
	
	function disable_icode($icode) {
        $db = GetGlobal('db');	
	
        $sSQL2 = "update icodes set active=0";	
		$sSQL2 .= " where invcode=" . $db->qstr($icode);
        $db->Execute($sSQL2,1);
		//echo $sSQL2;		
	}
	
	function verify_icode($icode) {
        $db = GetGlobal('db');	
	
        $sSQL2 = "select invcode,expire from icodes";	
		$sSQL2 .= " where invcode=" . $db->qstr($icode);
		$sSQL2 .= " and active=1 and expire>=" . $db->qstr(date('d-m-Y'));
        $resultset = $db->Execute($sSQL2,2);
		//echo $sSQL2;
	    //$ret = $db->fetch_array($resultset);	
        $ret = $resultset->fields[0];
		
		//print_r($ret);
		//echo $ret[1];
		//if (($ret[1]) && ($ret[1]<date('Y-m-d'))) $this->reason = 'Expired';
		//elseif (!$ret[0]) $this->reason = 'Invalid';
		
		return ($ret?true:false);				
	}
	
	function get_item($icode) {
        $db = GetGlobal('db');	
	
        $sSQL2 = "select itemname,itemcode,mustpay from icodes";	
		$sSQL2 .= " where invcode=" . $db->qstr($icode);
        $resultset = $db->Execute($sSQL2,2);
		//echo $sSQL2;
	    //$ret = $db->fetch_array($resultset);			  
		$ret1 = $resultset->fields[0];
		$ret2 = $resultset->fields[1];
		$ret3 = $resultset->fields[2];
		//print_r($resultset->fields);
		
		$this->item = $ret1;
		$this->type = $ret2;			
		$this->must_pay = $ret3;
		//echo $this->type,">>>";
	}		
	 
	
    function reset_db() {
        $db = GetGlobal('db'); 

        //delete table if exist
  	    $sSQL1 = "drop table icodes";
        $db->Execute($sSQL1,1);
		$sSQL2 = "create table icodes " .
                    "(id integer auto_increment primary key,
                     insdate DATETIME, 
					 active integer,
					 itemcode VARCHAR(128),
					 itemname VARCHAR(128),
					 downtimes integer,
					 invcode VARCHAR(128),
					 expire DATETIME
					 )";																
        $db->Execute($sSQL2,1);   
		//echo $sSQL2;	

        echo "Code table reset successfully!\n";		
     }	
	
	 function generate_code($input) {
	 
	    $max = strlen($input);
		
		$key = $input.date('F j, Y, g:i a');
		$times = 128 - strlen($key);//must fill until 128 length
		if ($times>0)
		  $fkey = $key . str_repeat('x',$times);
		else
		  $fkey = $key;  
		//echo $fkey."<br>";
		$md5input = md5($fkey);
		
	    for ($i=0;$i<$max;$i++) {
		  $ret .= strtoupper($md5input[$i]);
		  if (($i==3) || ($i==7) || ($i==11) || ($i==15) || ($i==19) || ($i==23) || ($i==27))
		    $ret .= "-";
		}  
		
		return ($ret);
	 }
	 
    function instant_download($product_id) {
  
      //$file = $this->prpath . $this->download_path . $product_id . $this->ftype;	   
	  //echo "PICKIT:",$file;  
  
	  switch ($this->type) {
				
         case 'fullversion' :
		                      $file = paramload('SHELL','prpath') . 
							          paramload('RCPICKIT','dirsource') . 
									  $product_id . 
									  paramload('RCPICKIT','filetype');
							  break;
							  
	     case 'shareware'  :  $file = paramload('SHELL','prpath') . 
							          paramload('RCDOWNLOAD','dirsource') . 
								 	  $product_id . 
									  paramload('RCDOWNLOAD','filetype');
							  break;
	   }	 

       $downloadfile = new DOWNLOADFILE($file);
	   
       //$this->tell_by_mail(
	   GetGlobal('controller')->calldpc_method("rcdownload.tell_by_mail use " .
	                      $this->type . " file downloaded"."+".
	                      'support@re-coding.com'."+".
		                  'billy@re-coding.com'."+".
						  $file);	 	   
	   
       if (!$downloadfile->df_download()) {
	     //echo "Sorry, we are experiencing technical difficulties downloading this file. Please report this error to Technical Support.";	   	   
         $ret = $this->error;	 
	   }
	   //else
	     // $ret = "OK";	
	   
	   return ($ret);
    }	 
}
};
?>