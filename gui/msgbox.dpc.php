<?php
//PHP5
$__DPCSEC['MSGBOX_DPC']='2;2;2;2;2;2;2;2;9';

if (!defined("MSGBOX_DPC")) {//&& (seclevel('MSGBOX_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("MSGBOX_DPC",true);

$__DPC['MSGBOX_DPC'] = 'msgbox';

/* EXAMPLE
include("msgbox.inc.php");
$a=new msgBox("This is just a Message ","OKCancel","Jack Said!" ); 
// define the links
$links=array("ok.php","cancel.php"); 
// make the links
$a->makeLinks($links);
// draw the message box 
$a->showMsg(); 
*/

class msgbox {

  private $grx;
  
  var $msgButtons;
  var $msgPrompt;	
  var $msgTitle;
  var $msgIcon;
  var $msgLinks;  
  
  function __construct($prompt='prompt',$buttons='OKOnly',$title="Application Message") {
  
	 $this->grx = GetGlobal('GRX');  
	 
	 switch($buttons){
			case "OKOnly" :
				$this->msgButtons=array("OK");
				$this->msgIcon="i";
				break;
			case "OKCancel":
				$this->msgButtons=array("OK","Cancel");
				$this->msgIcon="s";
				break;
			case "AbortRetryIgnore":
				$this->msgButtons=array("Abort","Retry","Ignore");
				$this->msgIcon="x";
				break;
			case "YesNoCancel":
				$this->msgButtons=array("Yes","No","Cancel");
				$this->msgIcon="s";
				break;
			case "YesNo":
				$this->msgButtons=array("Yes","No");
				$this->msgIcon="s";
				break;
			case "RetryCancel":
				$this->msgButtons=array("Retry","Cancel");
				$this->msgIcon="r";
				break;
	 } // end switch
		
	 // set the title
	 $this->msgPrompt=$prompt;
	 $this->msgTitle=$title;	
	 
	 $javas = '';//$this->do_javas();
	 $styles = $this->do_styles();		  
	 
     if (iniload('JAVASCRIPT')) {	
	   
		   $js = new jscript;
           $js->load_js($javas,"",1,$styles);	
		   unset ($js);		
	 }	  
  }
  
  function makeLinks($linksArray){
  
	 $this->msgLinks=$linksArray;
  }	
	
  function render(){
		//print_r($this->msgButtons);
		$ret = "<table width=\"40%\"  border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"2\" class=\"msg\">";
		$ret.= "  <tr>";
		$ret.= "	<td class=\"msgTitle\" colspan=\"2\" align=\"left\"><b>".$this->msgTitle."&nbsp;</b></td>";
		$ret.= "  </tr>";
		$ret.= "  <tr>";
		$ret.= "	<td width=\"5%\">";
		$ret.= "<span class=\"msgIcon\">".$this->msgIcon."</span>";
		$ret.= "  </td>";
		$ret.= "	<td valign=\"top\">".$this->msgPrompt."</td>";
		$ret.= "  </tr>";
		$ret.= "  <tr>";
		$ret.= "<td colspan=\"2\" valign=\"top\" align=\"center\">";
			for($idx=0;$idx<count($this->msgButtons);$idx++){
				$ret.= "<span class=\"msgButton\">";
				$ret.= "<a href=\"".$this->msgLinks[$idx]."\" class=\"msglinks\">";			
				$ret.= $this->msgButtons[$idx];
				$ret.= "</a>";
				$ret.= "</span>";
				$ret.= "&nbsp;";
			}
		$ret.= "</td>";
		$ret.= "  </tr>";
		$ret.= "</table>";
		
		return ($ret);
  }  
  
  function do_styles(){//main css styles to control the msgbox look

	$styles= '<style type="text/css">
.msg{
	background: ActiveBorder;	
	padding: 6px;
	border: outset thin;
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size: x-small;
}

.msgButton{
	background: ButtonFace;
	color: ButtonText;
	padding: 4px;
	text-decoration: none;
	border: groove thin;
}

.msgTitle{
	background: ActiveCaption;
	color: HighlightText;		
}

.msglinks{
	text-decoration: none;
	color: ButtonText;
}

.msgIcon{
	font-family: Webdings;
	font-weight: bolder;
	font-size: xx-large;
}	
</style>';
	
	return $styles;	 
  }
  
  function __destruct() {
  }
};
}
?>