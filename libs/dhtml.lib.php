<?php
//..use it with dhtmlwindow.js new cpanel features
class dhtml{


    function __construct(){
	}
	
	function dhtml_javascript($code) {
 
		if (iniload('JAVASCRIPT')) {
			$js = new jscript;		   
			$js->load_js($code,"",1);			   
			unset ($js);
		}		
	}	

	function open_dialog($id,$type,$title,$link=null,$onClose=false, $w=640,$h=480,$res=1,$scroll=1) {
		$onClose = $onClose ? "dialog$id.onclose=function(){return window.confirm(\"$onClose\")} " : null;
		   
		$this->dhtml_javascript("
var dialog$id=dhtmlwindow.open(\"$id\", \"$type\", \"$link\", \"$title\", \"width={$w}px,height={$h}px,resize={$res},scrolling={$scroll},center=0\", \"recal\")
dialog$id.moveTo('middle', 'middle'); return false;
$onClose		
");	    

		//$this->dhtml_javascript("divwin=dhtmlwindow.open('divbox', 'div', 'somediv', '#4: DIV Window Title', 'width=450px,height=300px,left=200px,top=150px,resize=1,scrolling=1'); return false");
	}

	function dhtml_link($id, $type, $title, $link, $onClose=false, $w=640,$h=480,$res=1,$scroll=1,$l=300,$t=100) {
		$id = $id ? $id : 'x';    
		$title = $title? $title : '#';
		$type = $type ? $type : 'ajax'; //div,inline,iframe
		$onClose = $onClose ? "win$id.onclose=function(){return window.confirm(\"$onClose\")} " : null;
	
		dhtml_javascript("function link_$id(){ 
win$id=dhtmlwindow.open(\"$id\", \"$type\", \"$link\", \"$title\", \"width={$w}px,height={$h}px,left={$l}px,top={$t}px,resize={$res},scrolling={$scroll}\")
$onClose} 
}");

		$ret = "<a href=\"#\" onClick=\"link_$id(); return false\">".$title."</a>";
		return ($ret);
	}
 
}
?> 