<?php
$__DPCSEC['FORM_DPC']='2;2;2;2;2;2;2;2;9';

if ((!defined("FORM_DPC")) && (seclevel('FORM_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("FORM_DPC",true);

$__LOCALE['FORM_DPC'][0]='_SUBMIT;Submit;Αποστολή';
$__LOCALE['FORM_DPC'][1]='_RESET;Reset;Καθαρισμός';

	/*
	table v.1.0.1b
		
	By Llorenη Herrera [lha@hexoplastia.com]
	Please do not remove this credits
	
	Version history:
	. Multiple select improvement by Wolfsmutter.
	*/
	
	define ("FORM_METHOD_GET",				"get");
	define ("FORM_METHOD_POST",				"post");
    define ("FORM_METHOD_ENCTYPE",          "ENCTYPE=\"multipart/form-data\"");
	
	define ("FORM_GROUP_MAIN",				"%%main%%");
	define ("FORM_GROUP_HIDDEN",			"%%hidden%%");
	
	class form_group
	{
		var			$name;
		var			$title;
		var         $showhide;
		var         $state;
		
		function form_group ($name, $title, $showhide=null, $state=null)
		{
			$this->name		= $name;
			$this->title	= $title;
			$this->showhide	= $showhide;
			$this->state	= $state;
		}
	}
	
	class form_element
	{
		var			$title;
		var			$name;
		var			$value;
		
		var         $encoding;
		
		function form_element ($title, $name, $value)
		{
			$this->title	= $title;
			$this->name		= $name;
			$this->value	= $value;
			
			$this->encoding = paramload('SHELL','charset');
		}
	}	
	
	class form_element_text extends form_element
	{
		var			$style = "formText";
		var			$size;
		var			$maxlength;
		var			$isreadonly = false;
		
		function form_element_text ($title, $name, $value, $style, $size, $maxlength, $isreadonly)
		{
			$this->form_element		($title, $name, $value);
			
			if ($style != "") $this->style = $style;
			else $this->style = paramload('FORM','text_style');
			
			$this->size				= $size;
			$this->maxlength		= $maxlength;
			$this->isreadonly		= $isreadonly;
		}
		
		function getTag ()
		{
			return "<input type=\"text\" name=\"".$this->name."\" value=\"".$this->value."\" class=\"".$this->style."\" size=\"".$this->size."\" maxlength=\"".$this->maxlength."\" ".($this->isreadonly ? " readonly" : "")."><BR>";
		}
	}
	
	class form_element_onlytext extends form_element
	{
		var	$style =  "formTextOnly";
		
		function form_element_onlytext ($title, $value, $style)
		{
			$this->form_element		($title, "", $value);
			if ($style != "") $this->style = $style;
			else $this->style = paramload('FORM','textonly_style');
		}
		
		function getTag ()
		{
			return "<span class=\"".$this->style."\">".$this->value."</span><br>";
		}
	}
	
	class form_element_password extends form_element
	{
		var			$style = "formText";
		var			$size;
		var			$maxlength;
		
		function form_element_password ($title, $name, $value, $style, $size, $maxlength)
		{
			$this->form_element		($title, $name, $value);
			
			if ($style != "") $this->style			= $style;
			else $this->style = paramload('FORM','text_style');
			
			$this->size				= $size;
			$this->maxlength		= $maxlength;
		}
		
		function getTag ()
		{
			return "<input type=\"password\" name=\"".$this->name."\" value=\"".$this->value."\" class=\"".$this->style."\" size=\"".$this->size."\" maxlength=\"".$this->maxlength."\"><BR>";
		}
	}
	
	class form_element_combo extends form_element
	{
		var			$style = "formCombo";
		var			$size;
		var			$values;
		var			$multiple;
		
		function form_element_combo ($title, $name, $value, $style, $size, $values, $multiple)
		{
			$this->form_element		($title, $name, $value);
			
			if ($style != "") $this->style			= $style;
			else $this->style = paramload('FORM','combo_style');
			
			$this->size				= $size;
			$this->values			= $values;
			$this->multiple			= $multiple;
		}
		
		function getTag ()
		{
			$r = "";
			$r .= "<select name=\"".$this->name."\" class=\"".$this->style."\"".( $this->size != 0 ? "size=\"".$this->size."\"" : "").($this->multiple ? " multiple" : "").">";
			while (list ($value, $title) = each ($this->values))
				$r .= "<option value=\"$value\"".($value == $this->value ? " selected" : "").">$title</option>";
			$r .= "</select>";
			return $r;
		}
	}
	
	class form_element_radio extends form_element
	{
		var			$style = "formRadio";
		var			$size;
		var			$values;
		
		function form_element_radio ($title, $name, $value, $style, $size, $values)
		{
			$this->form_element		($title, $name, $value);
			
			if ($style != "") $this->style			= $style;
			else $this->style = paramload('FORM','radio_style');
			
			$this->size				= $size;
			$this->values			= $values;
		}
		
		function getTag ()
		{
			$r = "";
			$r .= "<span class=\"".$this->style."\">";
			while (list ($value, $title) = each ($this->values))
				$r .= "<input class=\"".$this->style."\" type=radio name=\"".$this->name."\" value=\"$value\"".($value == $this->value ? " checked" : "").">$title<br>\n";
			return $r;
		}
	}
	
	class form_element_checkbox extends form_element
	{
		var			$style = "formCheckbox";
		var			$size;
		var			$values;
		
		function form_element_checkbox ($title, $name, $value, $style)
		{
			$this->form_element		($title, $name, $value);
			
			if ($style != "") $this->style			= $style;
			else $this->style = paramload('FORM','checkbox_style');
			
			$this->size				= $size;
			$this->values			= $values;
		}
		
		function getTag ()
		{
			$r = "";
			$r .= "<span class=\"".$this->style."\">";
			$r .= "<input type=checkbox name=\"".$this->name."\" value=\"1\"".($this->value ? " checked" : "").">$title<br>\n";
			return $r;
		}
	}
	
	class form_element_textarea extends form_element
	{
		var			$style = "formTextarea";
		var			$cols;
		var			$rows;
		
		function form_element_textarea ($title, $name, $value, $style, $cols, $rows)
		{
			$this->form_element		($title, $name, $value);
			
			if ($style != "") $this->style			= $style;
			else $this->style = paramload('FORM','textarea_style');
			
			$this->cols				= $cols;
			$this->rows				= $rows;
		}
		
		function getTag ()
		{
			return "<textarea cols=".$this->cols." rows=".$this->rows." name=\"".$this->name."\" class=\"".$this->style."\">".$this->value."</textarea><BR>";
		}
		/*
			<textarea cols="1" rows="2" name="name"></textarea>
		*/
	}
	
	class form_element_hidden extends form_element
	{
		function form_element_hidden ($name, $value)
		{
			$this->form_element		("", $name, $value);
		}
		
		function getTag ()
		{
			return "<input type=\"hidden\" name=\"".$this->name."\" value=\"".$this->value."\">";
		}
	}
	
	class form
	{
		var			$title;
		var			$name;
		var			$method;
		var			$action;
		
		var			$groups;
		var			$elements;
		
		// Styles
		var			$width					= "100%";		
		
		var			$title_bgcolor			= "#004BAB";
		var			$title_style			= "title_style";
		
		var			$group_bgcolor			= "#B9D1F0";
		var			$group_style			= "group_style";
		
		var			$element_bgcolor		= array ("#E6E7E8", "#F1F2F2");
		var			$element_style			= "element_style";
		var			$element_separator		= ":";//"<img src=images/item.gif>";
		
		var			$issubmit				= true;
		var			$submit_bgcolor			= "#B9D1F0";
		var			$submit_title			= ".:    enviar    .:";
		var			$submit_style			= "formbutton";
		
		var			$isreset				= false;
		var			$reset_title			= ".: borrar .:";
		var			$reset_style			= "formreset";
		
		var         $tokens;
		
		function form ($title, $name, $method, $action,$resetbutton=false,$enctype=null)
		{
            $this->width	        = paramload('FORM','width');		
		    $this->title_bgcolor    = paramload('FORM','title_bgcolor');
		    $this->title_style	    = paramload('FORM','title_style');
		    $this->group_bgcolor    = paramload('FORM','group_bgcolor');
		    $this->group_style	    = paramload('FORM','group_style');
		    $this->element_bgcolor  = array (paramload('FORM','element_bgcolor1'),paramload('FORM','element_bgcolor2'));
		    $this->element_style    = paramload('FORM','element_style');
		    $this->element_separator= paramload('FORM','separator');
		    $this->submit_bgcolor   = paramload('FORM','submit_bgcolor');
		    $this->submit_title     = localize(paramload('FORM','submit_title'),getlocal());
		    $this->submit_style     = paramload('FORM','submit_style');
		    $this->reset_title      = localize(paramload('FORM','reset_title'),getlocal());
		    $this->reset_style      = paramload('FORM','reset_style');	
			$this->enctype          = $enctype;														
		
			$this->title	= $title;
			$this->name		= $name;
			$this->method	= $method;
			$this->action	= $action;
			$this->addGroup	(FORM_GROUP_HIDDEN, "");
			$this->addGroup	(FORM_GROUP_MAIN, "");
			
			$this->isreset = $resetbutton;
		}
		
		function addGroup ($name, $title, $showhide=null, $state=null)
		{
			$this->groups[] = new form_group ($name, $title, $showhide, $state);
		}
		
		function addElement ($group, $element)
		{
			$this->elements[$group][] = $element;
		}
		
		//...tokens only in this class..to be continue
		function getForm ($cp=0,$cs=0,$submit_title=null,$reset_title=null,$tokens_on=null,$submitjs=null)
		{
		    if ($submit_title) $this->submit_title = $submit_title;
		    if ($reset_title) $this->reset_title = $reset_title;	
			$enc = $this->enctype?FORM_METHOD_ENCTYPE:null;
			$js = $submitjs?'onClick="'.$submitjs.'"' : null;		
		
		    if ($tokens_on) {
			  
			  $tokens[] = "<form method=\"".$this->method . "\" $enc name=\"".$this->name."\" action=\"".$this->action."\">\n";
			}
			else {
			  $r = "";
			  $r .= "<form method=\"".$this->method . "\" $enc name=\"".$this->name."\" action=\"".$this->action."\" style=\"margin: 0px;\">\n";
		  	  $r .= "<table border=0 width=".$this->width." cellpadding=$cp cellspacing=$cs>\n";
              //dont print title	//$r .= "<tr>\n\t<td colspan=3 bgcolor=".$this->title_bgcolor."><span class=\"".$this->title_style."\">".$this->title."</td>\n</tr>\n";
            }
			
			for ($group_i=0; $group_i<sizeof ($this->groups); $group_i++)
			{
				$group = $this->groups[$group_i];
				
				if ($group->name != FORM_GROUP_MAIN && $group->name != FORM_GROUP_HIDDEN)
				{
				    if ((defined('WINDOW2_DPC')) && ($group->showhide)) {//show hide groups
	                  $win = new window2($group->name,$group->title,null,1,null,$group->status,1);
	                  $r .= $win->render();
	                  unset ($win);						
					}
					else {//plain table
					  $r .= "<tr>\n\t<td colspan=3 height=2></td>\n</tr>\n";
					  $r .= "<tr>\n\t<td colspan=3 bgcolor=".$this->group_bgcolor."><span class=\"".$this->group_style."\">".$group->title."</td>\n</tr>\n";
					}  
				}
				
				$color = 0;
				for ($element_i=0; $element_i<sizeof ($this->elements[$this->groups[$group_i]->name]); $element_i++)
					switch ($group->name)
					{
						case FORM_GROUP_HIDDEN:
							$r .= $this->elements[$this->groups[$group_i]->name][$element_i]->getTag ()."\n";
							break;
						default:
							$element = $this->elements[$this->groups[$group_i]->name][$element_i];
							$bgcolor = $this->element_bgcolor[$color];
							if ($color >= sizeof ($this->element_bgcolor)-1) $color = 0; else $color ++;
							$r .= "<tr bgcolor=$bgcolor>\n\t<td valign=top><div align=right class=\"".$this->element_style."\">".$element->title."</td>\n\t<td width=1 valign=top>".$this->element_separator."</td>\n\t<td>";
							$r .= $element->getTag ()."\n";
							$r .= "</td>\n</tr>\n";
							break;
					}
			}
			
			$r .= "<tr>\n\t<td colspan=3 height=2></td>\n</tr>\n";
			if ($this->issubmit || $this->isreset)
			{
				$r .= "<tr bgcolor=\"".$this->submit_bgcolor."\">\n\t<td colspan=3 valign=center><div align=center>";
				if ($this->issubmit) {
				    if ($tokens_on)
					  $tokens[] = "<input type=submit value=\"".$this->submit_title."\" $js class=\"".$this->submit_style."\">";
					else  
					  $r .= "<input type=submit value=\"".$this->submit_title."\" $js class=\"".$this->submit_style."\">";
			    }		
				if ($this->isreset) {
			      if ($tokens_on)				
				    $tokens[] = "<input type=reset value=\"".$this->reset_title."\" class=\"".$this->reset_style."\">";
				  else
					$r.= "&nbsp;<input type=reset value=\"".$this->reset_title."\" class=\"".$this->reset_style."\">";
				}	
				$r .= "</td>\n</tr>";
			}
			
			if ($tokens_on) 
			  $tokens[] = "</form>\n";
			else
			  $r .= "</form>\n";
			  
			$r .= "</table>\n";
			
			return $r;
		}
		
		/////////////////////////////////////////////
		//added (????)
	/*	function checkform() {
			for ($group_i=0; $group_i<sizeof ($this->groups); $group_i++)
			{
				$group = $this->groups[$group_i];
				
				for ($element_i=0; $element_i<sizeof ($this->elements[$this->groups[$group_i]->name]); $element_i++)
					//switch ($group->name)
					{
						//default:
							$element = $this->elements[$this->groups[$group_i]->name][$element_i];
							//$r .= "<tr bgcolor=$bgcolor>\n\t<td valign=top><div align=right class=\"".$this->element_style."\">".$element->title."</td>\n\t<td width=1 valign=top>".$this->element_separator."</td>\n\t<td>";
							//$r .= $element->getTag ()."\n";
							echo $element->name , ">>";
							break;
					}
			}		
		}*/
		
		function checkform() {
		  
		  //if (!$recaptcha) return true;//no validation if no recaptcha
		  
          if ($_POST["recaptcha_response_field"]) {
            $resp = recaptcha_check_answer ($privatekey,
                                            $_SERVER["REMOTE_ADDR"],
                                            $_POST["recaptcha_challenge_field"],
                                            $_POST["recaptcha_response_field"]);

            if ($resp->is_valid) {
                  echo "You got it!";
            } else {
                # set the error code so that we can display it
                $error = $resp->error;
            }
		  }
		  return ($error);		
		}  

	}
	
	//////////////////////////////////////////////////////////////////////////////////////
	//added
	class form_element_date extends form_element
	{
	
        var         $datepick_button ;	
		var         $formname;
	
		var			$style = "formText";
		var			$size;
		var			$maxlength;
		var			$isreadonly = false;
		
		function form_element_date ($title, $formname, $name, $value, $style, $size, $maxlength, $isreadonly)
		{
		
	        $GRX = GetGlobal('GRX');	
	
            if (iniload('JAVASCRIPT')) {	
	   
		     //$js = new jscript;
             //$js->load_js('ts_picker.js');	 
		     //unset ($js);
	        }		
		
            if ($GRX) {   	 
              $this->datepick_button  = loadTheme('godate_b',localize('_GETDATE',getlocal()));
            }
	        else {
              $this->datepick_button  = "[D]";
	        }
			
			$this->formname = $formname;
					
			$this->form_element		($title, $name, $value);
			
			if ($style != "") $this->style = $style;
			else $this->style = paramload('FORM','text_style');
			
			$this->size				= $size;
			$this->maxlength		= $maxlength;
			$this->isreadonly		= $isreadonly;
		}
		
		function getTag ()
		{
			return "<input type=\"text\" name=\"".$this->name."\" value=\"".$this->value."\" class=\"".$this->style."\" size=\"".$this->size."\" maxlength=\"".$this->maxlength."\" ".($this->isreadonly ? " readonly" : "").">".
			       "<a href=\"javascript:show_calendar('document.$this->formname.$this->name', document.$this->formname.$this->name.value);\">" .
		           $this->datepick_button . "</a><BR>";
			
		}
	}
	
	//added
	class form_element_recaptcha
	{
	
        var         $pubkey;	
		var         $privkey;
		
		function form_element_recaptcha ($pubkey=null, $privkey=null)
		{
			
			$this->pubkey			= $pubkey;
			$this->privkey   		= $privkey;
			$this->error            = null;
		}
		
		function getTag ()
		{
		    if (defined('RECAPTCHA_DPC')) {
	          $out  = recaptcha_get_html($this->pubkey, $this->privkey);
			  return ($out);
			}
			
		}
/* response
*# was there a reCAPTCHA response?
if ($_POST["recaptcha_response_field"]) {
        $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

        if ($resp->is_valid) {
                echo "You got it!";
        } else {
                # set the error code so that we can display it
                $error = $resp->error;
        }
}*/		
	}	
	
	//added
	class form_element_greekmap extends form_element
	{
	
        var         $map_button ;	
		var         $formname;
	
		var			$style = "formText";
		var			$size;
		var			$maxlength;
		var			$isreadonly = false;
		var         $path;
		
		function form_element_greekmap ($title, $formname, $name, $value, $style, $size, $maxlength, $isreadonly)
		{
		
	        $GRX = GetGlobal('GRX');	
			
            $ip = paramload('SHELL','ip');//$_SERVER['HTTP_HOST'];
            $pr = paramload('SHELL','protocol');		   
	        $this->path = $pr . $ip . "/images/greece";				
	
            if (iniload('JAVASCRIPT')) {	
	   
		      $js = new jscript;
		      $js->load_js("greekmap.js"); 
		      unset ($js);
	        }		
		
            if ($GRX) {   	 
              $this->map_button  = loadTheme('gomap_b',localize('_GREEKMAP',getlocal()));
            }
	        else {
              $this->map_button  = "[M]";
	        }
			
			$this->formname = $formname;
					
			$this->form_element		($title, $name, $value);
			
			if ($style != "") $this->style = $style;
			else $this->style = paramload('FORM','text_style');
			
			$this->size				= $size;
			$this->maxlength		= $maxlength;
			$this->isreadonly		= $isreadonly;
		}
		
		function getTag ()
		{
		
	        $out  = "<input type=\"text\" name=\"".$this->name."\" value=\"".$this->value."\" class=\"".$this->style."\" size=\"".$this->size."\" maxlength=\"".$this->maxlength."\" ".($this->isreadonly ? " readonly" : "").">";
		    $out .= "<a href=\"javascript:show_greekmap('document.$this->formname.$this->name', '$this->path');\">" .
		            $this->map_button . "</a>";		
			return ($out);
			
		}
	}
	
	//added
	class form_element_combo_file extends form_element
	{
		var			$style = "formCombo";
		var			$size;
		var			$values;
		var			$multiple;
		
		function form_element_combo_file ($title, $name, $value, $style, $size, $multiple, $lookupfile, $valueasid=null)
		{
			$this->form_element		($title, $name, $value);
			
			if ($style != "") $this->style			= $style;
			else $this->style = paramload('FORM','combo_style');
			
			$this->value			= $value;
			$this->size				= $size;
			$this->multiple			= $multiple;

			$lfile = paramload('SHELL','prpath') . $lookupfile . '.opt';
            //echo $lfile;
            if (is_file($lfile)) {
  
              $fd = fopen($lfile, 'r');
              $ret = fread($fd, filesize($lfile));
              fclose($fd); 	   
	
             $result = explode(',',$ret);  //print_r($result);
             $lan = getlocal();
			 
			/* if (stristr($this->encoding,'utf')) {
			   $utf = true;
               $encodingsperlan = arrayload('SHELL','char_set'); //print_r($encodingsperlan);
               $enc = ($enc?$enc:$encodingsperlan[$lan]);			   
			 }*/  
  
             if ($result) {
			 
               foreach ($result as $id=>$value)  {
	             //language selection
	             $lan_value = explode(";",$value);
				 
		         $val = $lan_value[$lan];
		        /* if ($val) $this->values[] = $utf?iconv($enc,$this->encoding,$val):$val;
		              else $this->values[] = $utf?iconv($enc,$this->encoding,$lan_value[0]):$lan_value[0];
                        */
                        if ($valueasid)
                        $this->values[$val]=$val;
                        else
                        $this->values[]=$val;
               }			 
			 }
		   }
		   else
		     $this->values = array(0=>'a',1=>'b');	 
		}
		
		function getTag ()
		{
			$r = "";
			$r .= "<select name=\"".$this->name."\" class=\"".$this->style."\"".( $this->size != 0 ? "size=\"".$this->size."\"" : "").($this->multiple ? " multiple" : "").">";
			while (list ($value, $title) = each ($this->values)) {
			    
				if (($this->value)&& (stristr($title,$this->value))) 
				  $sel = 1;
				else
				  $sel = 0;  
				  
				//echo $title," ",$this->value,"<br>";  
				 
				$r .= "<option value=\"$value\"".($sel ? " selected" : "").">$title</option>";
		    }		
			$r .= "</select>";
			return $r;
		}
	}
	
	
	class form_element_radio_file extends form_element
	{
		var			$style = "formRadio";
		var			$size;
		var			$values;
		
		function form_element_radio_file ($title, $name, $value, $style, $size, $lookupfile)
		{
			$this->form_element		($title, $name, $value);
			
			if ($style != "") $this->style			= $style;
			else $this->style = paramload('FORM','radio_style');
			
			$this->size				= $size;
			
			//$this->values			= $values;
			$lfile = paramload('SHELL','prpath') . $lookupfile . '.opt';
            //echo $lfile;
            if (is_file($lfile)) {
  
              $fd = fopen($lfile, 'r');
              $ret = fread($fd, filesize($lfile));
              fclose($fd); 	   
	
             $result = explode(',',$ret);  //print_r($result);
             $lan = getlocal();
  
             if (is_array($result)) {
			 
               foreach ($result as $id=>$value)  {
	             //language selection
	             $lan_value = explode(";",$value);
		         $val = $lan_value[$lan];
		         if ($val) $this->values[] = $val;
		              else $this->values[] = $lan_value[0];
               }			 
			 }
		   }
		   else
		     $this->values = array(0=>'a',1=>'b');			
		}
		
		function getTag ()
		{
			$r = "";
			$r .= "<span class=\"".$this->style."\">";
			while (list ($value, $title) = each ($this->values))
				$r .= "<input class=\"".$this->style."\" type=radio name=\"".$this->name."\" value=\"$title\"".($value == $this->value ? " checked" : "").">$title<br>\n";
			return $r;
		}
	}
	
	class form_element_radio_file_images extends form_element_radio_file {
	
		function form_element_radio_file_images ($title, $name, $value, $style, $size, $lookupfile,$imgtype='.jpg') {
		
		  form_element_radio_file::form_element_radio_file($title, $name, $value, $style, $size, $lookupfile);
		  $this->imgtype = $imgtype;
		}
		
		function getTag ()
		{
			$r = "";
			$r .= "<span class=\"".$this->style."\">";
			while (list ($value, $title) = each ($this->values)) {
				$r .= "<input class=\"".$this->style."\" type=radio name=\"".$this->name."\" value=\"$title\"".($value == $this->value ? " checked" : "").">";
				$r .= loadicon("/".$title . $this->imgtype,$title);
				$r .= "<br>"; 
		    }		
			return $r;
		}			  
	}		
	
	class form_element_checkboxes extends form_element
	{
		var			$style = "formCheckbox";
		var			$size;
		var			$values;
		
		function form_element_checkboxes ($title, $name, $value, $style,$lookupfile)
		{
			$this->form_element		($title, $name, $value);
			
			if ($style != "") $this->style			= $style;
			else $this->style = paramload('FORM','checkbox_style');
			
			$this->size				= $size;
			//$this->values			= $values;
			$lfile = paramload('SHELL','prpath') . $lookupfile . '.opt';
            //echo $lfile;
            if (is_file($lfile)) {
  
              $fd = fopen($lfile, 'r');
              $ret = fread($fd, filesize($lfile));
              fclose($fd); 	   
	
             $result = explode(',',$ret);  //print_r($result);
             $lan = getlocal();
  
             if ($result) {
			 
               foreach ($result as $id=>$value)  {
	             //language selection
	             $lan_value = explode(";",$value);
		         $val = $lan_value[$lan];
		         if ($val) $this->values[] = $val;
		              else $this->values[] = $lan_value[0];
               }			 
			 }
		   }
		   else
		     $this->values = array(0=>'a',1=>'b');					
		}
		
		function getTag ()
		{
			$r = "";
			$r .= "<span class=\"".$this->style."\">";
			
			foreach ($this->values as $id=>$val)
			  $r .= "<input type=checkbox name=\"".$this->name.$id."\" value=\"1\"".($this->value ? " checked" : "").">$val<br>\n";
			return $r;
		}
	}	
	
	//added
	class form_element_dpcode extends form_element
	{
		
		function form_element_dpcode ($title,$code)
		{
		
		}
		
		function getTag ()
		{
		
            $out = getdpc_method($code); 
			return ($out);
			
		}
	}	
	
	//added
	class form_element_upload extends form_element_text
	{
		var $MAXSIZE;
		
		function form_element_upload ($title, $name, $value, $style, $size, $maxlength,$mxsize=null)
		{
		   $this->MAXSIZE = $mxsize;
           $this->form_element_text ($title, $name, $value, $style, $size, $maxlength,0);		
		}
		
		function getTag ()
		{
            $out .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" VALUE=\"".$this->MAXSIZE."\">";		
			$out .= "<input type=FILE name=\"".$this->name."\" class=\"".$this->style."\">";
            //$out .=  "<input type=\"text\" name=\"".$this->name."\" value=\"".$this->value."\" class=\"".$this->style."\" size=\"".$this->size."\" maxlength=\"".$this->maxlength."\" ".($this->isreadonly ? " readonly" : "")."><BR>";
			return ($out);
			
		}
	}	
	
	//added
	class form_element_htmlarea extends form_element
	{
		var			$style = "formTextarea";
		var			$cols;
		var			$rows;
		
		function form_element_htmlarea ($title, $name, $value, $style, $cols, $rows)
		{
			$this->form_element		($title, $name, $value);
			
			if ($style != "") $this->style			= $style;
			else $this->style = paramload('FORM','textarea_style');
			
			$this->cols				= $cols;
			$this->rows				= $rows;
		}
		
		function getTag ()
		{
			return "<TEXTAREA id=\"".$this->name."\" cols=".$this->cols." rows=".$this->rows." name=\"".$this->name."\" class=\"".$this->style."\">".$this->value."</TEXTAREA><BR>";
		}
		/*
			<textarea cols="1" rows="2" name="name"></textarea>
		*/
	}
	
	class form_element_link extends form_element
	{
		var			$style = "formText";
		var			$size;
		var			$maxlength;
		var			$isreadonly = false;
		
		var $query;
		
		function form_element_link ($title, $query, $name, $value=null)
		{
			$this->form_element		($title, $name, $value=null);
			
			//if ($style != "") $this->style = $style;
			//else $this->style = paramload('FORM','text_style');
			
			//$this->size				= $size;
			//$this->maxlength		= $maxlength;
			//$this->isreadonly		= $isreadonly;
			
			$this->query = $query;
		}
		
		function getTag ()
		{
			//return "<input type=\"text\" name=\"".$this->name."\" value=\"".$this->value."\" class=\"".$this->style."\" size=\"".$this->size."\" maxlength=\"".$this->maxlength."\" ".($this->isreadonly ? " readonly" : "")."><BR>";
			
			//qyery,title,ssl
			return seturl($this->query,$this->name,$this->value);
		}
	}	
	
	
	//sdded
	class mform extends form {
	
	   function mform($title, $name, $method, $action,$resetbutton=false,$enctype=null) {
	     
		  form::form($title, $name, $method, $action,$resetbutton,$enctype);
	   }
	   
	   //override
       function getForm ($actions_array,$cp=0,$cs=0,$submit_title=null,$reset_title=null)
	   {
		    if ($submit_title) $this->submit_title = $submit_title;
		    if ($reset_title) $this->reset_title = $reset_title;
			$enc = $this->enctype?FORM_METHOD_ENCTYPE:null;								
		
			$r = "";
			$r .= "<form method=\"".$this->method."\" $enc name=\"".$this->name."\" action=\"".$this->action."\" style=\"margin: 0px;\">\n";
			$r .= "<table border=0 width=".$this->width." cellpadding=$cp cellspacing=$cs>\n";
//dont print title	//$r .= "<tr>\n\t<td colspan=3 bgcolor=".$this->title_bgcolor."><span class=\"".$this->title_style."\">".$this->title."</td>\n</tr>\n";
			
			for ($group_i=0; $group_i<sizeof ($this->groups); $group_i++)
			{
				$group = $this->groups[$group_i];
				
				if ($group->name != FORM_GROUP_MAIN && $group->name != FORM_GROUP_HIDDEN)
				{
					$r .= "<tr>\n\t<td colspan=3 height=2></td>\n</tr>\n";
					$r .= "<tr>\n\t<td colspan=3 bgcolor=".$this->group_bgcolor."><span class=\"".$this->group_style."\">".$group->title."</td>\n</tr>\n";
				}
				
				$color = 0;
				for ($element_i=0; $element_i<sizeof ($this->elements[$this->groups[$group_i]->name]); $element_i++)
					switch ($group->name)
					{
						case FORM_GROUP_HIDDEN:
							$r .= $this->elements[$this->groups[$group_i]->name][$element_i]->getTag ()."\n";
							break;
						default:
							$element = $this->elements[$this->groups[$group_i]->name][$element_i];
							$bgcolor = $this->element_bgcolor[$color];
							if ($color >= sizeof ($this->element_bgcolor)-1) $color = 0; else $color ++;
							$r .= "<tr bgcolor=$bgcolor>\n\t<td valign=top><div align=right class=\"".$this->element_style."\">".$element->title."</td>\n\t<td width=1 valign=top>".$this->element_separator."</td>\n\t<td>";
							$r .= $element->getTag ()."\n";
							$r .= "</td>\n</tr>\n";
							break;
					}
			}
			
			$r .= "<tr>\n\t<td colspan=3 height=2></td>\n</tr>\n";
			if ($this->issubmit || $this->isreset)
			{
				$r .= "<tr bgcolor=\"".$this->submit_bgcolor."\">\n\t<td colspan=3 valign=center><div align=center>";
				
				if (count($actions_array)>1) {//multiple actions
				  //EXTRA ACTIONS	
			      foreach ($actions_array as $id=>$act) {
			      //echo $act;
			      //if ($id>0) //0= standart action handled by standart form
			        $r .= "&nbsp;<input type=submit name=\"FormAction\" value=\"".$act."\" class=\"".$this->submit_style."\">";
			      }					
				}
				else 
				if ($this->issubmit)
					$r .= "<input type=submit value=\"".$this->submit_title."\" class=\"".$this->submit_style."\">";				
					
				if ($this->isreset)
					$r.= "&nbsp;<input type=reset value=\"".$this->reset_title."\" class=\"".$this->reset_style."\">";
				$r .= "</td>\n</tr>";
			}
			
			$r .= "</form>\n";
			$r .= "</table>\n";
			
			return $r;
		}
		
       function getFormTokens ($actions_array,$cp=0,$cs=0,$submit_title=null,$reset_title=null)
	   {
		    if ($submit_title) $this->submit_title = $submit_title;
		    if ($reset_title) $this->reset_title = $reset_title;			
		
			$tokens[] = "<form method=\"".$this->method."\" name=\"".$this->name."\" action=\"".$this->action."\">";

			for ($group_i=0; $group_i<sizeof ($this->groups); $group_i++)
			{
				$group = $this->groups[$group_i];
				
				/*if ($group->name != FORM_GROUP_MAIN && $group->name != FORM_GROUP_HIDDEN)
				{
					$r .= "<tr>\n\t<td colspan=3 height=2></td>\n</tr>\n";
					$r .= "<tr>\n\t<td colspan=3 bgcolor=".$this->group_bgcolor."><span class=\"".$this->group_style."\">".$group->title."</td>\n</tr>\n";
				}*/
				
				$color = 0;
				for ($element_i=0; $element_i<sizeof ($this->elements[$this->groups[$group_i]->name]); $element_i++)
					switch ($group->name)
					{
						case FORM_GROUP_HIDDEN:
							$tokens[] = $this->elements[$this->groups[$group_i]->name][$element_i]->getTag ();
							break;
						default:
							/*$element = $this->elements[$this->groups[$group_i]->name][$element_i];
							$bgcolor = $this->element_bgcolor[$color];
							if ($color >= sizeof ($this->element_bgcolor)-1) $color = 0; else $color ++;
							$r .= "<tr bgcolor=$bgcolor>\n\t<td valign=top><div align=right class=\"".$this->element_style."\">".$element->title."</td>\n\t<td width=1 valign=top>".$this->element_separator."</td>\n\t<td>";*/
							$tokens[] = $element->getTag ();
							//$r .= "</td>\n</tr>\n";
							break;
					}
			}
			
			//$r .= "<tr>\n\t<td colspan=3 height=2></td>\n</tr>\n";
			if ($this->issubmit || $this->isreset)
			{
				//$r .= "<tr bgcolor=\"".$this->submit_bgcolor."\">\n\t<td colspan=3 valign=center><div align=center>";
				
				if (count($actions_array)>1) {//multiple actions
				  //EXTRA ACTIONS	
			      foreach ($actions_array as $id=>$act) {
			      //echo $act;
			      //if ($id>0) //0= standart action handled by standart form
			        $mytact .= "&nbsp;<input type=submit name=\"FormAction\" value=\"".$act."\">";
			      }				
				}
				else
				if ($this->issubmit)
					$mytact  = "<input type=submit value=\"".$this->submit_title."\">";				
					
				if ($this->isreset)
					$mytact  = "&nbsp;<input type=reset value=\"".$this->reset_title."\">";
				//$r .= "</td>\n</tr>";
			}
			
			$mytact  = "</form>";
			//$r .= "</table>\n";
			
			$tokens[] = $mytact;				
			
			return ($tokens);
		}		
	}
		
}
?>