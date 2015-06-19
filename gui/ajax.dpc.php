<?php
if (defined("JAVASCRIPT_DPC")) {
	   
$__DPCSEC['AJAX_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("AJAX_DPC")) && (seclevel('AJAX_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("AJAX_DPC",true);

$__DPC['AJAX_DPC'] = 'ajax';

class ajax {  

   var $ajax_broker_page;

   function ajax() {
   
      $char_set  = arrayload('SHELL','char_set');	  
      $charset  = paramload('SHELL','charset');	 
	     
	  if (($charset=='utf-8') || ($charset=='utf8'))
	    $this->encoding = 'utf-8';
	  else  
	    $this->encoding = $char_set[getlocal()];    
   
	   $this->ajax_broker_page = 'rpc.php';		   
	
       if (iniload('JAVASCRIPT')) {
	   
	       $code = $this->javascript_ajax();	   	
	   
		   $js = new jscript;
		   //$js->setLoadParams("initEditor()");   
		   //$js->load_js("ajax.js");		   		   
		   	 	      
           $js->load_js($code,null,1);		   			   
		   unset ($js);
	   }	
   }
   
   //url is of type "page.ext?t=x&z=2.." without broker
   function setajaxurl($url,$title) {
   
       $ret = "<a href=\"javascript:sndUrl('$url')\">$title</a>";  
   }   
   
   //url is of type "t=x&z=2.." using broker
   function setajaxreq($req,$title) {
   
       $ret = "<a href=\"javascript:sndReq('$req')\">$title</a>";  
   }
   
   //url is of type "class.method.use.a+b+c..." using broker
   function setajaxdpcreq($dpcmethod,$title) {
   
       $ret = "<a href=\"javascript:sndDPCReq('$dpcmethod')\">$title</a>";  
   }   
   
   //set div in page
   function setajaxdiv($id, $content=null) {
   
       $ret =  "<div id=\"$id\">$content</div>"; 
	   return ($ret);
   }
   
   //replace return(data) with ajax specific return data
   function ajax_return($id,$data=null) {
   
       $ret = "$id|$data";
	   return ($ret);   
   }
	
   function javascript_ajax()  {
   
      $apage = $this->ajax_broker_page;
   
      $jscript = <<<EOF
function createRequestObject() {
    var ro;
    var browser = navigator.appName;
    if(browser == "Microsoft Internet Explorer"){
        ro = new ActiveXObject("Microsoft.XMLHTTP");
    }else{
        ro = new XMLHttpRequest();
    }
    return ro;
}
var http = createRequestObject();
function sndUrl(url) {
    http.open('get', url+'&ajax=1');
    http.onreadystatechange = handleResponse;
    http.send(null);
}
function sndReqArg(url) {
    var params = url+'&ajax=1';

    http.open('post', params, true);
    http.setRequestHeader("Content-Type", "text/html; charset=$this->encoding");
    //http.setRequestHeader("charset", "$this->encoding");
    http.setRequestHeader("encoding", "$this->encoding");	
    //http.setRequestHeader("Content-length", params.length);//<<<	
    //http.setRequestHeader("Connection", "close");	//<<<
    http.onreadystatechange = handleResponse;	
    http.send(null);
}
function sndReq(action) {
    http.open('get', '$apage?t='+action);
    http.onreadystatechange = handleResponse;
    http.send(null);
}
//Expanding this approach a bit to send multiple parameters in the
//request, for example, would be really simple.  Something like:
function sndDPCReq(dpcmethod,arg) {
    http.open('get', '$apage?dpc='+dpcmethod);
    http.onreadystatechange = handleResponse;
    http.send(null);
}
function handleResponse() {
    if(http.readyState == 4){
        var response = http.responseText;
        var update = new Array();
		//encode response
		//response.setCharacterEncoding("$this->encoding");
		//response.setContentType("text/html;charset=$this->encoding");
		//response = encodeURI(response);	
        //trim response
        response = response.replace( /^\s+/g, "" ); // strip leading 
        response = response.replace( /\s+$/g, "" ); // strip trailing		

        if(response.indexOf('|' != -1)) {
            //alert(response); 	
            update = response.split('|');
            document.getElementById(update[0]).innerHTML = update[1];
        }	
    }
}

EOF;

      return ($jscript);
   }	
	
};
}
}
else die("JAVASCRIPT DPC REQUIRED!");
?>