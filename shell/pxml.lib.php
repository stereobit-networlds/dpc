<?php
if (!defined("PXML_DPC")) {
define("PXML_DPC",true);

class pxml {  

   var $xmlgen;

   var $xmlset;
   var $content;
   
   var $urltitle;
   var $urladdr;
   var $version;
   var $charset;
   var $encoding = "utf-8";   
   var $cache = "no-store, no-cache, must-revalidate, post-check=0, pre-check=0";  
   
   var $index;    

   function pxml($xmlroot='',$content='',$xmlset='XMLDPC') {	  

      $this->xmlset  = $xmlroot; //$xmlset; //root tag
	  $this->content = $content;
	  
	  //init xml array
	  if ($this->xmlset) $this->xmlgen = array('_XMLNODEVAL'=>null,
	                                           '_XMLNODENAM'=>$this->xmlset);  
	  else 
	    $this->xmlgen = array();
	  
      $this->urltitle = paramload('SHELL','urltitle');
      $this->urladdr  = paramload('SHELL','urlbase');
      $this->version  = paramload('SHELL','version');
      $this->charset  = paramload('SHELL','charset');	
	  
      //header('Content-Type: text/xml; charset=UTF-8');
      //if ($this->cache != "") header("Cache-Control: ".$this->cache);		    
	  
	  $this->index = array();
   }  

   function render() { 

	  $out = $this->_startXML();
	  
	  if ($this->content) 
	    $out .= $this->content;	
	  else 
        //error correction when no xml exist	 			 	  
	    $out .= "\n<$this->xmlset>null</$this->xmlset>"; 
	  
      $out .= $this->_endXML();
	  
	  return ($out);
   }   
   

   function _startXML($ver='1.0',$enc='UTF-8',$dtd=null,$standalone=1) {
    
     if ($enc) $enc = "encoding=\"".$enc."\" "; else $enc = "";	
	 if ($standalone) $sal = 'standalone="yes" ';
	 
     $toprint .= "<?xml version='".$ver."' " . $enc . $sal . '?>';
	 
     if (($dtd) && (!$standalone)) $toprint .= "\n<!DOCTYPE ". $this->xmlset .' PUBLIC "'.$dtd.'">';

     return ($toprint);
   }

   function _endXML() {

     $toprint .= "\n<!-- end of document -->\n";
	 
     return ($toprint);
   }
   
   
   
   //PUBLIC   
   function addtag($name,$parent,$val,$attr=null,$comment=null) {
     
     $this->xmlgen = $this->_setag($name,$parent,$val,$attr,null,$comment);
	 
	 //echo '<pre>';	   
	 //print_r($this->xmlgen);   
	 //echo '</pre>';	 
   } 
   
   //PRIVATE called by addtag
   function _setag($name,$parent,$val,$attr=null,$level=null,$comment=null) {
   
     static $node_name; //holds recursional previous node name
   
     //preset
	 if (!isset($parent)) $parent = $this->xmlset;   
     if (!isset($level)) $levelmem = &$this->xmlgen;
	                else $levelmem = &$level;
	  
	 //set the tag attributes
	 if (isset($attr)) {
	   if (is_array($attr)) { //array
	     $tattr = $attr;
	   }
	   else { //string
	     $tattr=null;
	     $attributes = explode("|",$attr);
	     foreach ($attributes as $attr_name=>$attr_value) {
		   $iso = explode("=",$attr_value);
	       $tattr[trim($iso[0])] = trim($iso[1]);
		 }  
	   }
     }
	 //set the node value and name as attributes 
	 if (is_array($tattr)) //warning with arg 2 is not a array...
	    $node = array_merge(array('_XMLNODENAM'=>$name,'_XMLNODEVAL'=>$val,'_XMLNODECMD'=>$comment),$tattr);
	 else
	    $node = array('_XMLNODENAM'=>$name,'_XMLNODEVAL'=>$val,'_XMLNODECMD'=>$comment);//without attr
	 //print_r($levelmem);
	 //recursional
	 if (is_array($levelmem)) {
	 
	   if ($levelmem['_XMLNODENAM']==$parent) {//echo 'a-'; //means exist one and only node 
	     //echo $name,':',$parent;	   
	     if (is_array($levelmem['_XMLNODEVAL'])) {
		   if (array_key_exists('_XMLNODENAM',$levelmem['_XMLNODEVAL'])) {//check if it is 1 node
		     //2nd node aty same level
		     $levelmem['_XMLNODEVAL'] = array_merge(array(0=>$levelmem['_XMLNODEVAL']),array(1=>$node));
		   }
		   else {	 		 
			 //n..nd node at same level
		     $levelmem['_XMLNODEVAL'][] = $node;//multiple childs
		   }   
	       //echo 'MULTAG<pre>';	   
	       //print_r($levelmem);   
	       //echo '</pre>';			   
		 }  
         else
		   $levelmem['_XMLNODEVAL'] = $node; 	 
		   
		 //$this->index[$name]+=1; 
		 $this->index[$parent]+=1; //childs of parent		 
		   			 
		 return ($levelmem);
	   }
	   else {//echo 'b-'; //go deeper...
		 //$node_name = $levelmem['_XMLNODENAM'];	     
		 //if ($node_name) {//error in root 
		 if (array_key_exists('0',$levelmem)) {//means many smae name nodes..take index node
		   //echo '<pre>'; print_r($this->index); echo '</pre>';
		   $node_id = $this->index[$node_name]-1;		 
		   $node_data = $levelmem[$node_id];
		   //echo '<br>MULTIPLE NODES:',$parent,':',$node_name,':',$node_id; 
		   if (is_array($node_data)) {		   
             if ($res = $this->_setag($name,$parent,$val,$attr,$node_data,$comment)) {
		         $levelmem[$node_id] = $res;
			     return ($levelmem);			  
			 }
		   }	 		   
		 }		 
		 else {//means unique node
		   $node_name = $levelmem['_XMLNODENAM'];//save namespace = parent name
		   
	       foreach ($levelmem as $node_id=>$node_data) {
		     if (is_array($node_data)) {	 
		        if ($res = $this->_setag($name,$parent,$val,$attr,$node_data,$comment)) {
		           $levelmem[$node_id] = $res;
			       return ($levelmem);			  
			    }
		     }
		   } 
		 } 
	   }	
	    
	 }//array
	  	 
	 return 0; 
   }
   
   //PUBLIC
   function getxml($headers=0,$eol="\n",$spc=" ",$spc_num=0,$tab=2) {
     
	 //print_r($this->xmlgen);
	 
	 if ($headers) $out = $this->_startXML();
	 $out .= $this->_buildXML($this->xmlgen,$eol,$spc,$spc_num,$tab);
	 if ($headers) $out .= $this->_endXML();
	 
	 if ($this->encoding=='utf-8') {
	   //echo 'utf-8';
	   return (utf8_encode($out));
	 }  
	 else
	   return ($out);
   } 
   
   function dumpxml() {
   
     print_r($this->xmlgen);
   }
   
   //PRIVATE
   function _buildXML($tree,$eol="\n",$spc=" ",$spc_num=0,$tab=2) {
   
     if (is_array($tree)) {
	 
	   for ($i=0;$i<$spc_num;$i++) $spaces .= $spc;

	   if ($tree['_XMLNODENAM']) {
	     $xmlname = $tree['_XMLNODENAM'];
	   }
	   if ($tree['_XMLNODEVAL']) {
	     $xmlvalue = $tree['_XMLNODEVAL'];//$this->transxml($tree['_XMLNODEVAL']); //translate special chars
	   }   
	   if ($tree['_XMLNODECMD']) {
	     $xmlcomm = $tree['_XMLNODECMD'];
	   }	   
	   	 
       //get childrens
       foreach ($tree as $id=>$node) {
	     if (is_array($node)) {	 
		   if ($ch==false) {
		     $spc_num += $tab;
			 $ch = true;
		   }	 
	       $xmlchildrens .= $this->_buildXML($node,$eol,$spc,$spc_num,$tab); //recursion
		 }
		 elseif (($id!='_XMLNODENAM') && ($id!='_XMLNODEVAL') && ($id!='_XMLNODECMD')) {
		   $xmlattr .= " $id='$node'";
		 }
	   }
	   //render
	   if ($xmlvalue=='/') { //<TAG /> style
	     if ($xmlcomm) $xmlout  = $eol . "<!-- " . $xmlcomm . " -->";
	     if ($xmlname) $xmlout .= $eol . $spaces . "<" . $xmlname . $xmlattr . "/>";	   
	   }
	   else { //<TAG></TAG> style
	     if ($xmlcomm) $xmlout  = $eol . "<!-- " . $xmlcomm . " -->";	   
	     if ($xmlname) $xmlout .= $eol . $spaces . "<" . $xmlname . $xmlattr . ">";
		 if (!is_array($xmlvalue))
		   if ($xmlvalue) $xmlout.= $eol . $spaces . $xmlvalue;//is_array($xmlvalue)?null:$xmlvalue ;????;		 
	     $xmlout .= $xmlchildrens;
	     if ($xmlname) $xmlout .= $eol . $spaces . "</" . $xmlname .">";	 
	   }
	 }
	 //echo $xmlout,'<br>';
	 
	 return ($xmlout); 
   }
   
   //PRIVATE :replace &,>,<,'," PROBLEM with symplols like '&nbsp'<----------------
   function _transxml($text) {
   
     //first replace allready translated text to original symbols
	 $preout  = str_replace("&amp;","&",$text);
	 $preout1 = str_replace("&lt;","<",$preout);
	 $preout2 = str_replace("&gt;",">",$preout1);
	 $preout3 = str_replace("&apos;","'",$preout2); 	 	 
	 $preout4 = str_replace("&quot","\"",$preout3);
	 
	 //next set original symbols to its equal symbols	 
	 $out4 = str_replace("\"","&quot;",$preout4);
	 $out3 = str_replace("'","&apos;",$preout4);
	 $out2 = str_replace(">","&gt;",$preout4);
	 $out1 = str_replace("<","&lt;",$preout4);
	 $out  = str_replace("&","&amp;",$preout4);	 	 	 	 
	 
	 return ($out);
   }
   
   function xstr($str) {
   
     return (str_replace("&","&amp;",$str));
   }
   
   function cdata($data) {
   
     return "<![CDATA[" . $data . "]]>";
   }
   
   //return the root tag of xml text without xml struct read
   function readtag($xmltext) {
     
	 $text = ltrim($xmltext); //left rrim
	 
	 $i = 1; //bypass "<"
	 while ($text[$i]!='>') {
	   $preout .= $text[$i];
	   $i+=1;
	 }
	 
	 $tagpart = explode(" ",$preout); //split in case of tag attr
	 
	 return ($tagpart[0]); //roottag name 
   }
    
};
}
?>