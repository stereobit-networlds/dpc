<?php

$__DPCSEC['DATEPICK_DPC']='2;2;2;2;2;2;2;2;9';

if ((!defined("DATEPICK_DPC")) && (seclevel('DATEPICK_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("DATEPICK_DPC",true);

$__DPC['DATEPICK_DPC'] = 'datepicker';

$__LOCALE['DATEPICK_DPC'][0] = '_GETDATE;Select Date;Επιλογή ημ/νίας';
$__LOCALE['DATEPICK_DPC'][1] = '_APO;From:;Απο:';
$__LOCALE['DATEPICK_DPC'][2] = '_EOS;To  :;Εως:';
$__LOCALE['DATEPICK_DPC'][3] = '_OK;Ok;Ok';

class datepicker {

    var $datepick_button;
	var $dateformat;

    function datepicker($dateformat=null) {
	   $GRX = GetGlobal('GRX');	
	
       if (iniload('JAVASCRIPT')) {
	   
		   $js = new jscript;
           $js->load_js('ts_picker.js');	 
		   unset ($js);
	   }
	   
	   $this->dateformat = $dateformat;
	   
       if ($GRX) {   	 
         $this->datepick_button  = loadTheme('godate_b',localize('_GETDATE',getlocal()));
       }
	   else {
         $this->datepick_button  = "[D]";
	   }	   
	}

	function javascript()
	{
		$jscrpt = "";
		
		return ($jscrpt);
	}
	 
    function render($id,$formname,$len=20,$val='') {
	
	    $out  = "<input type=\"text\" name=\"$id\" value=\"$val\" maxlenght=\"$len\">";
		$out .= "<a href=\"javascript:show_calendar('document.$formname.$id', document.$formname.$id.value);\">" .
		        $this->datepick_button . "</a>";
		        //"<img src=\"image/cal.gif\" width=\"16\" height=\"16\" border=\"0\" " .
				//"alt=\"" . localize('_GETDATE',getlocal()) . "\"></a>";
				
		return ($out);			
	}
	
	function renderspace($furl="",$fname="datespace") {
	
		  $dater = new datepicker();		
	
          $dd = "<form name=\"dpick\" action=\"$furl\" method=\"POST\" class=\"thin\">";
          $dd .= "<input type=\"hidden\" name=\"FormName\" value=\"Datespace\">";
		  
          $dd .= localize('_APO',getlocal());
		  $dd .= $dater->render("apo","dpick") . "&nbsp;";		  
          $dd .= localize('_EOS',getlocal()); 
		  $dd .= $dater->render("eos","dpick") . "&nbsp;";

          $dd .= "<input type=\"hidden\" name=\"FormAction\" value=\"$fname\">";
          $dd .= "<input type=\"submit\" value=\"" . localize('_OK',getlocal()) . "\">";
          $dd .= "</form>";
	 
          $data1[] = $dd;
          $attr1[] = "left";
		  
	      $swin = new window('',$data1,$attr1);
	      $out = $swin->render();//"center::100%::0::group_win_body::left::0::0::");	
	      unset ($swin);
		  
		  return ($out);
	}	
};
}
?>