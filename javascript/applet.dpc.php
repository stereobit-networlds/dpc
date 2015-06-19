<?php
if (defined("PHTML_DPC")) {

if (!defined("APPLET_DPC")) {
define("APPLET_DPC",true);

$__DPC['APPLET_DPC'] = 'applet';

class applet {


	function applet() { 
	}
	
	
	function a_proscroll() {
	
	  $out = <<<EOF
<applet CODE="ProScroll" WIDTH="100%" HEIGHT="16">
  <param name="TEXT"
  value="$ Νεα Προιόντα ! It supports #red#m#green#u#blue#l#yellow#t#orange#i#white#c#lightGray#o#darkGray#l#cyan#o#magenta#r#pink#e#red#d #white# text, and even images! $">
  <param name="STYLE" value="bold">
  <param name="SIZE" value="14">
  <param name="SPEED" value="fast">
  <param name="IMAGES" value="image.gif image.gif">

</applet>  
EOF;

      return ($out);
	}
	
	function a_goo($image,$x=300,$y=320) {
	
	  $out = "	
	      <applet code=QGoo.class width=$x height=$y>
	      alt=\"Your browser understands the &lt;APPLET&gt; tag but isn't running the applet, for some reason.\"
	      Your browser is completely ignoring the &lt;APPLET&gt; tag!
          <param name=\"image\" value=\"$image\">
          <param name=\"bgcolor\" value=\"#2498FF\">
          </applet>";
	  
      return ($out);	  
	}

};
}
}
else die("HTML OUTPUT MUST BE ENABLED!");
?>