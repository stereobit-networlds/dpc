<?php

class nitobi {

    var $id;	
	var $titles;	

	var $datasource_section, $column_section;	
	var $onclick, $onclick_function_name;
	
	var $combo_col,$combo_data;
	var $onclick_combo_function_name;

	function nitobi($id) {
	
	  $this->id = $id;
	  $this->datasource_section = array();
	  $this->column_section = array();	
	  $this->titles = array();	
	  
	  $this->onclick = false;	  
	  $this->onclick_function_name = 'OnClick';	
	  
	  //COMBO V3
	  $this->combo_col = array();	
	  $this->combo_data = null;  
	  $this->onclick_combo_function_name = 'OnClickCombo';	  
	}
	
	function get_id() {
	
	  return ($this->id);
	}
	
//////////////////////////////////////////////////////////////////////// DATA GRID	
	
	function init_grid($i=null) {
	
	  //static $i=1;
	  //if (!$var)
	    $var = chr($i+97);
	
	  return ("$var = nitobi.initGrid('$this->id');");
	}
	
	//new version of nitobi grid
	function init_grid_new($i=null) {
	
	  //static $i=1;
	  //if (!$var)
	    $var = chr($i+97);
	
	  return ("$var = nitobi.loadComponent('$this->id');");
	}	
	
	function set_datasource($id,$data,$keys=null,$attributes=null,$lookup=false) {
	
	   $attr = explode("@",$attributes);
	   $fieldnames = $attr[0];
	   $types = $attr[1];
	   $defaults = $attr[2];
	
	   $ret = "<ntb:datasource id=\"$id\"><ntb:datasourcestructure id=\"$id\" Keys=\"$keys\" FieldNames=\"$fieldnames\" types=\"$types\" defaults=\"$defaults\"></ntb:datasourcestructure><ntb:data>\r\n";
			   			   
        foreach ($data as $k=>$v) {
		 
		  if (is_array($v)) {//array
		    //print_r($v);
		    $ret .= "<ntb:e xi=\"$k\" ";
			foreach ($v as $id=>$val) {
			 if (is_numeric($id)) {//only for fields with num as key, NOT filedname
			   if (is_numeric($val)) //numbers
			     $ret .= chr($id+97) . "=$val ";
			   else
		         $ret .= chr($id+97) . "=\"$val\" ";
			 }  
			} 
			$ret .= "></ntb:e>\r\n"; 
		  }  
		  else {//one element
		    if ($lookup)//key $k is the data and $v is the representation
			  $ret .= "<ntb:e a=\"$k\" b=\"$v\"></ntb:e>\r\n";
			else
		      $ret .= "<ntb:e a=\"$v\" xi=\"$k\"></ntb:e>\r\n";
		  }	
		}
		  
		$ret .= "			 
				</ntb:data>
		  </ntb:datasource>\r\n";
		  
		
		$this->datasource_section[] = $ret;  
	}
	
	
	//used when simple text names appear in titles
	function set_title($title) {
	
	    $this->titles[] = $title;	  
	}
	
	//bulk copy semicolon titles ..used to copy titles from obj grid 1to obj grid 2
	function set_titles($titles_sep_by_colon) {
	
	    $this->titles = explode("|",$titles_sep_by_colon);
	}
	
	function get_titles() {
	
	    return (implode("|",$this->titles));
	}
	
	//used when titles linked at columnspace (editors)
	function set_text_column($title,$xdatafld,$width="200",$sortable="true",$mode=null,$datasource=null,$displayfields=null,$valuefield=null,$cv='active',$uv='inactive') {
	
	    $this->titles[] = $xdatafld;
	   
	    $ret = "<ntb:textcolumn label=\"$title\" xdatafld=\"$xdatafld\" sortenabled=\"$sortable\" width=\"$width\">";
        //$ret = "<ntb:textcolumn label=\"$title\" xdatafld=\"$xdatafld\" width=\"$width\">";		
		
		switch ($mode) {	
		    case 'PASSWORD' :
            $ret .= "<ntb:passwordeditor></ntb:passwordeditor>";
					 break;			
		    case 'HYPERLINK' ://open in the same window (default) otherwise use set_hyperlink_column method
            $ret .= "<ntb:linkeditor openwindow=\"false\"></ntb:linkeditor>";
					 break;			
		    case 'TEXTAREA' :
            $ret .= "<ntb:textareaeditor></ntb:textareaeditor>";
					 break;			
		    case 'LISTBOX' :
            $ret .= "<ntb:listboxeditor datasourceid=\"$datasource\" displayfields=\"$displayfields\" valuefield=\"$valuefield\" ></ntb:listboxeditor>";
					 break;
			case 'LOOKUP' :
			$ret .= "<ntb:lookupeditor datasourceid=\"$datasource\" displayfields=\"$displayfields\" valuefield=\"$valuefield\" ></ntb:lookupeditor>\r\n";
			         break;
			case 'CHECKBOX' :
			$ret .= "<ntb:checkboxeditor datasourceid=\"$datasource\"  checkedvalue=\"$cv\" uncheckedvalue=\"$uv\" displayfields=\"$displayfields\" valuefield=\"$valuefield\"></ntb:checkboxeditor>";
			         break;
			case 'TEXT' :		 
			default : $ret .= ""; //none		 		  		 
		}
				
		$ret .= "</ntb:textcolumn>\r\n";
			  
		$this->column_section[] = $ret; 			  	
	}
	
	//error at image path ????
	function set_image_column($title,$xdatafld,$width="200") {
	
		$ret = "<ntb:textcolumn	label=\"$title\" xdatafld=\"$xdatafld\"	sortenabled=\"false\" width=\"$width\" initial=\"images/docicon.gif\"><ntb:imageeditor></ntb:imageeditor></ntb:textcolumn>\r\n";
			
		$this->column_section[] = $ret; 			
	}
	
	function set_hyperlink_column($title,$xdatafld,$width="75",$sortable="true",$openwindow="true") {
	
		$ret = "<ntb:textcolumn	label=\"$title\" xdatafld=\"$xdatafld\"	sortenabled=\"$sortable\" width=\"$width\"><ntb:linkeditor openwindow=\"$openwindow\"></ntb:linkeditor></ntb:textcolumn>\r\n";
			
		$this->column_section[] = $ret; 			
	}	
	

/* DATE masking
     Format Pattern                         Result
     --------------                         -------
     "yyyy.MM.dd G 'at' hh:mm:ss z"    ->>  1996.07.10 AD at 15:08:56 PDT
     "EEE, MMM d, ''yy"                ->>  Wed, July 10, '96
     "h:mm a"                          ->>  12:08 PM
     "hh 'o''clock' a, zzzz"           ->>  12 o'clock PM, Pacific Daylight Time
     "K:mm a, z"                       ->>  0:00 PM, PST
     "yyyyy.MMMMM.dd GGG hh:mm aaa"    ->>  1996.July.10 AD 12:08 PM	
	 */ 
	function set_number_column($title,$xdatafld,$width="75",$sortable="true",$mask="#,#0",$is_date=false) {
	
	    if ($is_date)
		  $ret = "<ntb:datecolumn";
		else
		  $ret = "<ntb:numbercolumn";
		
		$ret .="label=\"$title\" xdatafld=\"$xdatafld\"	sortenabled=\"$sortable\" mask=\"$mask\" width=\"$width\">";
		
	    if ($is_date)
		  $ret .="</ntb:datecolumn>\r\n";
		else				
		  $ret .="</ntb:numbercolumn>\r\n";
			
		$this->column_section[] = $ret; 			
	}	
			
	
	function flush_section($section) {
	
	    foreach ($this->$section as $ds)
		  $ret .= $ds;
		  
		return ($ret);  
	}	
	
	//mode = livescrolling,locallivescrolling,localnonpaging, localstandard, standard
    function set_grid($xml_island,$width="500",$height="300",$mode="locallivescrolling",$pager=null,
	                  $toolbar="true",$copy="false",$paste="false",$insert="true",$delete="true") {	
	
       $ret = "
		<ntb:grid id=\"$this->id\" 
		  width=\"$width\"
		  height=\"$height\"
		  mode=\"$mode\"
		  theme=\"nitobi\"				  	  
		  rowsperpage=\"$pager\"		  
		  rowinsertenabled=\"$insert\"  
          rowdeleteenabled=\"$delete\"  
		  datasourceid=\"$xml_island\" 
		  toolbarenabled=\"$toolbar\"
		  copyenabled=\"$copy\"  
          pasteenabled=\"$paste\"\r\n";
		  
		  if ($this->onclick) 
		    $ret .= "
		  oncellclickevent=\"".$this->onclick_function_name."(eventArgs)\"\r\n";		    

		  
          $ret .= "onbeforecopyevent=\"\"   
          onaftercopyevent=\"\"  
          onbeforepasteevent=\"\"  
          onafterpastevent=\"\">\r\n";
		  
		//keygenerator="GetNewRecordID();"
		//autosaveenabled="true"	
		//frozenleftcolumncount="2"  	 
		//	  rowhighlightenabled="true"
	    //rowselectenabled="true"
	    //multirowselectenabled="true"
	    //autosaveenabled="false" 
		  
		  if ($ds = $this->flush_section('datasource_section')) {	
		    $ret .= "<ntb:datasources>\r\n" .
		             $ds .		
		            "</ntb:datasources>\r\n"; 
		  }
		  
		  
		  if ($cols = $this->flush_section('column_section')) {
		    $ret .= "<ntb:columns>\r\n" . 
			        $cols . 
					"</ntb:columns>\r\n";
		  }
		  
		$ret .= "</ntb:grid>\r\n";

        return ($ret);	
	}
	
	//REMOTE DATA
	//mode = livescrolling,locallivescrolling,localnonpaging, localstandard, standard
    function set_grid_remote($gethandler,$savehandler,$width="500",$height="300",$mode="locallivescrolling",$pager=null,
	                         $toolbar="true",$copy="false",$paste="false",$insert="true",$delete="true") {	
	
	   switch ($pager) {
	     case 20 : $y = 530; break;
		 case 17 : $y = 460; break;
	     case 10 : $y = 300; break;
		 default : $y = 300; break;
	   }
	
       $ret = "
		<ntb:grid id=\"$this->id\" 
		  width=\"$width\"
		  height=\"$height\"
		  mode=\"$mode\"
		  theme=\"nitobi\"
		  rowsperpage=\"$pager\"		  
		  rowinsertenabled=\"$insert\"  
          rowdeleteenabled=\"$delete\"  
		  gethandler=\"$gethandler\" 
		  savehandler=\"$savehandler\" 		  
		  toolbarenabled=\"$toolbar\"
		  copyenabled=\"$copy\"  
          pasteenabled=\"$paste\"";
		  
		  if ($this->onclick)
		    $ret .= "
		  oncellclickevent=\"".$this->onclick_function_name."(eventArgs)\"\r\n";		    
		  
          $ret .= "onbeforecopyevent=\"\"  
          onaftercopyevent=\"\"  
          onbeforepasteevent=\"\"  
          onafterpastevent=\"\"";
		  
		  $ret .= ">\r\n";
		  
		//keygenerator="GetNewRecordID();"
		//autosaveenabled="true"	
		//frozenleftcolumncount="2" 
		//	  rowhighlightenabled="true"
	    //rowselectenabled="true"
	    //multirowselectenabled="true"
	    //autosaveenabled="false" 		 	  
		  
		  if ($ds = $this->flush_section('datasource_section')) {	
		    $ret .= "<ntb:datasources>\r\n" .
		             $ds .		
		            "</ntb:datasources>\r\n"; 
		  }
		  
		  
		  if ($cols = $this->flush_section('column_section')) {
		    $ret .= "<ntb:columns>\r\n" . 
			        $cols . 
					"</ntb:columns>\r\n";
		  }
		  
		$ret .= "</ntb:grid>\r\n";

        return ($ret);	
	}	
	
    function set_grid_remote_new($gethandler,$savehandler,$width="500",$height="300",$mode=null,$frozencolumn=null,
	                         $toolbar=null,$insert=null,$delete=null,$resize=null) {	
	
	$mode = $mode?$mode:'livescrolling';
	$toolbar = $toolbar?$toolbar:'false';
	$insert = $insert?$insert:'false';
	$delete = $delete?$delete:'false';	
	
	
       $ret = "<ntb:grid id=\"$this->id\" 
		width=\"$width\"
		height=\"$height\"
		mode=\"$mode\"	
		rowinsertenabled=\"$insert\"
		rowdeleteenabled=\"$delete\"		
		gethandler=\"".$gethandler."\"";
		
	if ($savehandler)
	 $ret .= "savehandler=\"$savehandler\"";	
		
	if ($frozencolumn)	
	 $ret .= "frozenleftcolumncount=\"2\"";
	 
	if ($resize) 
	 $ret .= "gridresizeenabled=\"true\"";	 
	 
	$ret .= "oncellclickevent=\"".$this->onclick_function_name."(eventArgs)\"
		toolbarenabled=\"$toolbar\"
		theme=\"nitobi\">";
		  
		  if ($ds = $this->flush_section('datasource_section')) {	
		    $ret .= "<ntb:datasources>\r\n" .
		             $ds .		
		            "</ntb:datasources>\r\n"; 
		  }		  
		  
		  if ($cols = $this->flush_section('column_section')) {
		    $ret .= "<ntb:columns>\r\n" . $cols . "</ntb:columns>\r\n";
		  }
		  
		$ret .= "</ntb:grid>\r\n";

        return ($ret);	
	}	
	
	//used to pass data from onclick js function	
	function set_detail_div($id,$width,$height,$bcolor,$message=null) {
	
	   $ret .= "<div id=\"$id\" style=\"background-color:#$bcolor; border-right: 1px solid #999999; border-bottom: 1px solid #999999; width: $width px; height:$height px;\">
	            <p style=\"padding-left: 10px;\">$message</p>
	           </div>";
			   
	   return ($ret);		   	
	}	
	
	
	//used by oncellclickevent="ChooseCustomer(eventArgs)"  in grid's attr
	//if second grid gethandler param change provide paramfield and i0,i1,i2 param as 0,1,2
	function OnClick($columns,$div_id=null,$html_template=null,$secondGrid=null,$param=null,$value=0,$newname=null) {
	
	  $this->onclick = true; //enable option
	  $this->onclick_function_name  = ($newname?$newname:'OnClick');
	  
	  $mygrid = $this->id;
	
	  $ret = "
function ".$this->onclick_function_name."(eventArgs)
{
	var myRow = eventArgs.cell.getRow();
	
	var myMasterGrid = nitobi.getComponent('$mygrid');
";
      
	  $max = $columns;//count($this->titles);
      for ($i=0;$i<$max;$i++) {
	    $ret .= "var i$i = myMasterGrid.getCellObject(myRow,$i).getValue();\r\n";
		$vars[] = "i$i";
	  }	
   

      if ($div_id) {	
	  
	    $ret .= "var mydiv = document.getElementById('$div_id');\r\n";
	    $variables = implode(",",$vars);
		$jvariables = implode("+'<br>'+",$vars);
	  
	    if ($html_template) {
		  $innerhtml = $html_template;//vsprintf($html_template,$vars);
		  $ret .= "mydiv.innerHTML = '$innerhtml';\r\n";
		}
		else {
		  $innerhtml = $jvariables;
		  $ret .= "mydiv.innerHTML = $innerhtml;\r\n";
		}  
		//echo $innerhtml;  
		   
      }
      if (($secondGrid) && (isset($param)) && (isset($value))) {	
	    $ret .= "
	var myDetailGrid = nitobi.getComponent('$secondGrid');
	myDetailGrid.getDataSource().setGetHandlerParameter('$param', i$value);
	myDetailGrid.dataBind();";
  	  }
	  
	  $ret .= " 
	return true;
}";

      return ($ret);
	}	
	
	function GetNewRecordId() {
	
	  $ret = "function GetNewRecordID()   
{   
    // Use the native cross-browser nitobi Ajax object   
    var myAjaxRequest = new nitobi.ajax.HttpRequest();   
       
    // Define the url for your generatekey script   
    myAjaxRequest.handler = 'allocaterecord.asp?rnd=' + Math.random();   
    myAjaxRequest.async = false;   
    myAjaxRequest.get();   
       
    // return the result to the grid   
    return myAjaxRequest.httpObj.responseText;   
}  ";

      return ($ret);
	}		  		  		
	
//////////////////////////////////////////////////////////////////////// COMBO V3	

	function init_combo() {
	
	
	  return ("nitobi.initCombo('$this->id');");
	}
	
	function set_combo_column($label,$width,$index=null) {
	  
	  if (trim($label))  
	       $this->combo_col[] = "\r\n<ntb:ComboColumnDefinition Width=\"{$width}px\" HeaderLabel=\"$label\" DataFieldIndex=$index ></ntb:ComboColumnDefinition>";
		 else
		   $this->combo_col[] = "\r\n<ntb:ComboColumnDefinition Width=\"{$width}px\" DataFieldIndex=$index></ntb:ComboColumnDefinition>";  	  
	   
	  
	}
	
	function set_combo_column_img($image,$width,$index=null) {
	  
	    $this->combo_col[] = "\r\n<ntb:ComboColumnDefinition ColumnType=\"IMAGE\" Width=\"{$width}px\" HeaderLabel=\"<img src=$image>\" DataFieldIndex=$index ></ntb:ComboColumnDefinition>";  
	}	
	
	function set_combo_data($data,$labels) {
	
	   $ret = "<ntb:ComboValues fields=\"$labels\">\r\n";
	   
	   foreach ($data as $id=>$record) {	
	     if (is_array($record)) {//array   
		   $ret .= "<ntb:ComboValue ";
	       foreach ($record as $r=>$field) {
			 $ret .= chr($r+97) . "=\"$field\" ";
		   }
		   $ret .= "></ntb:ComboValue>\r\n";		   
		 }
		 else {//one element
		   $ret .= "<ntb:ComboValue a=\"$record\"></ntb:ComboValue>\r\n";
		 }  
	   }
	   $ret .= "</ntb:ComboValues>\r\n";
	   
	   $this->combo_data = $ret;
	}
	
    function set_combo($width="175",$widthlist="360",$heightlist="300",$initsearch="",$mode="classic") {	
	
       $ret = "	
			<ntb:Combo id=\"$this->id\" Mode=\"$mode\" InitialSearch=\"$initsearch\">
				<ntb:ComboTextBox Width=\"{$width}px\" DataFieldIndex=0 ></ntb:ComboTextBox>
                <ntb:ComboList Width=\"{$widthlist}px\" AllowPaging=\"false\" Height=\"{$heightlist}px\" >";
	    
	   foreach ($this->combo_col as $num=>$data) {

		 $ret .= $data;   
	   }	
	   
	   $ret .= "
	    	  </ntb:ComboList>
";
       $ret .= $this->combo_data . "
";			  
			  
	   $ret .= "</ntb:Combo>
";

       return ($ret);
    }		
	
    function set_combo_remote($gethandler="chandler.php",$width="175",$widthlist="360",$heightlist="300",$initsearch="",$mode="classic",$page=0) {	
	
       $ret = "	
			<ntb:Combo id=\"$this->id\" Mode=\"$mode\" InitialSearch=\"$initsearch\">
				<ntb:ComboTextBox Width=\"{$width}px\" DataFieldIndex=0 ></ntb:ComboTextBox>";
	   if ($page>0)			
		 $ret .= "<ntb:ComboList Width=\"{$widthlist}px\" Height=\"{$heightlist}px\" DatasourceUrl=\"$gethandler\" PageSize=\"$page\" >";
	   else
	     $ret .= "<ntb:ComboList Width=\"{$widthlist}px\" AllowPaging=\"false\" Height=\"{$heightlist}px\" DatasourceUrl=\"$gethandler\" >";	 
	    
	   foreach ($this->combo_col as $num=>$data) {
		   
		 $ret .= $data;   
	   }	
	   
	   $ret .= "
	    	  </ntb:ComboList>
			</ntb:Combo>
";

       return ($ret);
    }	
	
    function set_combo_linked($gethandler="chandler.php",$width="175",$widthlist="360",$heightlist="300",$value="",$mode="classic",$page=0) {	
	
       $ret = "	
			<ntb:Combo id=\"$this->id\" Mode=\"$mode\" ";
	   if ($this->onclick) 	
	     $ret .= "OnSelectEvent=\"" .  $this->onclick_combo_function_name . "()\""; 		
	   
	   $ret .= ">
";
	   
	   $ret .= "<ntb:ComboTextBox Width=\"{$width}px\" DataFieldIndex=0 Editable=\"false\" Value=\"$value\"></ntb:ComboTextBox>";
	   if ($page>0)			
		 $ret .= "<ntb:ComboList Width=\"{$widthlist}px\" Height=\"{$heightlist}px\" DatasourceUrl=\"$gethandler\" PageSize=\"$page\" >";
	   else
	     $ret .= "<ntb:ComboList Width=\"{$widthlist}px\" AllowPaging=\"false\" Height=\"{$heightlist}px\" DatasourceUrl=\"$gethandler\">";	 
	    
	   foreach ($this->combo_col as $num=>$data) {
		   
		 $ret .= $data;   
	   }	
	   
	   $ret .= "
	    	  </ntb:ComboList>
			</ntb:Combo>
";

       return ($ret);
    }	
	
	function OnClickCombo($thiscombo_id,$combo2handle,$dataUrlMasked,$label=null,$name=null) {
	
	  $this->onclick = true; //enable option
	  $this->onclick_combo_function_name  = ($name?$name:'OnClickCombo');
	  
	  $mycombo = $this->id;
	
	  $ret = "
function ".$this->onclick_combo_function_name."(eventArgs)
{	
		// Get the combo objects using their ids.
		thisCombo = document.getElementById(\"$mycombo\").object;
		handleCombo = document.getElementById(\"$combo2handle\").object;		
		
		// Get the selected id.
		var selectionId = thisCombo.GetFieldFromActiveRow(\"$thiscombo_id\");	
		
		// Clear the list and any other selected values.
		handleCombo.GetList().Clear();			
		
		// Use a postback to get data from the datasource.
        var dataurl = \"$dataUrlMasked\";
        url = dataurl.replace(/<@>/g, selectionId);  		
		alert(url);
		handleCombo.GetList().SetDatasourceUrl(url);
		handleCombo.GetList().GetPage(0,0,\"\");		
		
		handleCombo.SetTextValue(\"$label\");		
";



	  $ret .= " 
	return true;
}";

      return ($ret);
	} 
	
//////////////////////////////////////////////////////////////////////// CALENDAR	 
    function set_datepicker_input($id,$selected_date=null,$theme=null,$mask=null,$editmask=null) {	
	
	  $mytheme = $theme?$theme:'leopard';
	  $sd = $selected_date?$selected_date:'today';
	  $msk = $mask?$mask:"EE MMM dd yyyy";
	  $emask = $editmask?$editmask:"yyyy-MM-dd";
	
	  $ret = "<ntb:datepicker id=\"$id\" theme=\"$mytheme\" selecteddate=\"$sd\"><ntb:dateinput displaymask=\"$msk\" editmask=\"$emask\"></ntb:dateinput></ntb:datepicker>";
		
		
		//			<ntb:datepicker id="dp" theme="flex" selecteddate="today"
		//				longdaynames="['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samdi']">
		//				<ntb:dateinput></ntb:dateinput>
		//				<ntb:calendar></ntb:calendar>
		//			</ntb:datepicker>
				
	
	  
	  return ($ret);
	}	
	
    function set_datepicker_calendar($id,$onClick=null,$mask=null,$r=null,$c=null,$min_date=null,$theme=null,$lang=null) {	
	
	  $onClick = $onClick?"ondateselected=\"".$onClick."(this)\"":null;//ondateselected=\"onClickCalndr(this)\"
	  $mask = $mask?$mask:"EE MMM d, yyyy";//didnt work!!!
	  
	  $mytheme = $theme?$theme:'minimal';
	  $md = $min_date?$min_date:'yesterday';
	  $r = $r?$r:1;
	  $c = $c?$c:1;
	  
	  //problems inside ....use js 
	  /*$lang = 0;
	  if ($lang) {
	    $days = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
	    foreach ($days as $day)
	      $d[] = localize($day,getlocal());
	    $dret = "['" . implode("','",$d) . "']";  
	    
	    $i=0;
	    foreach ($days as $mind) {
	      $mn = localize($mind,getlocal());	    
	      $md[$i] = substr($mn,0,2);	    
	      $i+=1;
	    }  
	    $mdret = "['" . implode("','",$md) . "']"; 	    
	      
	    $months = array('January','February','March','April','May','June','July','August','September','October','November','December');
	    foreach ($months as $month)
	      $m[] = localize($month,getlocal());	    
	    $mret = "['" . implode("','",$m) . "']"; 	
	    		          
	      
	  }*/
	
	  $ret = "<ntb:datepicker id=\"$id\" theme=\"$mytheme\" mindate=\"$md\" submitmask=\"$mask\" $onClick><ntb:calendar monthrows=\"$r\" monthcolumns=\"$c\"";
	  /*if ($lang) {
	    $ret .= " longDayNames=\"$dret\"";
	    $ret .= " longMonthNames=\"$mret\"";	    
	    $ret .= " minDayNames=\"$mdret\"";	    
	  } */ 
	  $ret .= "></ntb:calendar></ntb:datepicker>";
			
	  
	  return ($ret);
	}	
	
//////////////////////////////////////////////////////////////////////// CALLOUT
    
	function callout_init($x,$y,$title=null,$body=null,$skin=null,$direction=null,$attachdiv=null,$timer=1,$movx=null,$movy=null) {
	
	  $skin = $skin?$skin:'xp';//vista
	  $title = $title?$title:'';
	  $body = $body?$body:'';
	  $direction = $direction?$direction:0;//'No Direction';	  
	
	  $ret = "
var timerObj;
var ".$this->id.";	

function moveCallout() {
	
	var myPosition = ".$this->id.".getXY();
	//objref.destroy();
	".$this->id.".moveTo(myPosition.x+2, myPosition.y+2);
	
	".$this->id.".setTitle('New X: ' + (myPosition.x+2) + ' / New Y: ' + (myPosition.y+2));
	timerObj = setTimeout(function() {moveCallout(".$this->id.")}, 10)


}

function createCallout(formobj, moveAfterward) {

	".$this->id." = new nitobi.callout.Callout('".$skin."');
	".$this->id.".setCalloutDirection(parseInt(".$direction."));
	".$this->id.".moveTo(".$x.", ".$y.");	
	".$this->id.".setTitle('".$title."');
	".$this->id.".setBody('".$body."');	
	".$this->id.".show();
	
	if (moveAfterward) {
		".$this->id.".setOnAppear(function() {moveCallout(".$this->id.")})
		".$this->id.".setOnDestroy(function() {clearTimeout(timerObj)})	
	}
}

function runCallout() {
  
	".$this->id." = new nitobi.callout.Callout('".$skin."');
	".$this->id.".attachToElement('".$attachdiv."');
	".$this->id.".setTitle('".$title."');
	".$this->id.".setBody('".$body."');
	setTimeout(\"".$this->id.".show()\", ".$timer.");	

}";
    return ($ret);
	}
	
	function callout($id, $title=null,$body=null) {
	
	  if ($title) {
	    $ret .= "$id.setTitle('$title');";
	    $ret .= "$id.setBody('$body');";		
	  }	
	
      $ret .= "createCallout(this.form); return false;";
	  return ($ret);	
	}					
	function callout_move($id,$title=null,$body=null) {
	
	  if ($title) {
	    $ret .= "$id.setTitle('$title');";
	    $ret .= "$id.setBody('$body');";		
	  }	
	
      $ret .= "createCallout(this.form, true); return false;";
	  return ($ret);	
	}	
	
//////////////////////////////////////////////////////////////////////// TREE
   
   function tree_init() {
     //js to pre-load
   }

   function set_tree($gethandler,$theme=null,$width=null,$height=null,$overx=null,$overy=null) {
   
     $mytheme = $theme?$theme:'folders';
     $width = $width?$width:100;
     $height = $height?$height:100;
     $xoverflow = $xoverflow?$xoverflow:'hidden';
     $yoverflow = $yoverflow?$yoverflow:'auto'; 	 	 	 
   
     $ret = "<style>#tree-container {	width: ".$width."px;	height: ".$height."px;	overflow-y: ".$yoverflow.";	overflow-x: ".$xoverflow.";}</style>";   
     $ret .= "<div id=\"tree-container\"><ntb:tree id=\"".$this->id."\" theme=\"$mytheme\" gethandler=\"$gethandler\"></ntb:tree></div>";
	 return ($ret);
   }
   
   function set_tree_static($staticdata,$theme=null,$width=null,$height=null,$overx=null,$overy=null) {
   
     $mytheme = $theme?$theme:'folders';
     $width = $width?$width:100;
     $height = $height?$height:100;
     $xoverflow = $xoverflow?$xoverflow:'hidden';
     $yoverflow = $yoverflow?$yoverflow:'auto'; 	 	 	 
   
     $ret = "<style>#tree-container {	width: ".$width."px;	height: ".$height."px;	overflow-y: ".$yoverflow.";	overflow-x: ".$xoverflow.";}</style>";   
     $ret .= "<div id=\"tree-container\"><ntb:tree id=\"".$this->id."\" theme=\"$mytheme\">".$staticdata."</ntb:tree></div>";
	 return ($ret);
   }   
   
//////////////////////////////////////////////////////////////////////// TABS   		
   function tabstrip_init($type=null) {
     $type  = $type?$type:'iframe';
   
			$out = "
var init = function() {  
     nitobi.loadComponent(\"".$this->id."\")  
}  
   

function add()  
{  
     var label = arguments[0];
     var url = arguments[1];

     var t1 = nitobi.getComponent(\"".$this->id."\");  
     var tabs = t1.getTabs();  
     var tab = new nitobi.tabstrip.Tab();  
     tab.setLabel(label);  
     tab.setContainerType(\"".$type."\");   
     tab.setSource(url);  
     tab.setWidth(\"190px\");  
     tabs.add(tab);  
     t1.render();  
     return false;  
}  
   
function remove()  
{  
     var t1 = nitobi.getComponent(\"".$this->id."\");  
     var tabs = t1.getTabs();  
     tabs.remove(3);  
     t1.render();  
     return false;  
}     			
";
        return ($out);	 
   }

   function set_tabstrip($initlabel,$initurl,$type=null,$theme=null,$width=null,$height=null,$tabheadalign=null,$effect=null) {
   
      $mytheme = $theme?$theme:'nitobi';
      $width = $width?$width:'640px';
      $height = $height?$height:'400px';
      $tabheadalign = $tabheadalign?$tabheadalign:'center';	  
	  $effect = $effect?"activateeffect=\"$effect\"":null;
      $type  = $type?$type:'iframe';	  	  
   
      $ret = "<ntb:tabstrip id=\"".$this->id."\" width=\"".$width."\" height=\"".$height."\" theme=\"".$mytheme."\">";
      $ret.= "<ntb:tabs height=\"\" align=\"".$tabheadalign."\" overlap=\"15\" ".$effect.">";  
      //$ret.= "<ntb:tab width=\"120px\" tooltip=\"Welcome.\" label=\"Home\" source=\"http://www.bbc.co.uk\" containertype=\"iframe\" ></ntb:tab>";  
      //$ret.= "<ntb:tab width=\"120px\" tooltip=\"Welcome.\" label=\"Forums\" source=\"http://forums.nitobi.com\" containertype=\"iframe\" ></ntb:tab>";
      //$ret.= "<ntb:tab width=\"120px\" tooltip=\"Welcome.\" label=\"KB\" source=\"http://www.google.com\" containertype=\"iframe\" cssclass=\"custom\" ></ntb:tab>";
	  switch ($type) {
        case 'iframe' : $ret.= "<ntb:tab width=\"190px\" label=\"".$initlabel."\" source=\"".$initurl."\" containertype=\"".$type."\" ></ntb:tab>";	  break;
        case 'dom'    : $ret.= "<ntb:tab width=\"190px\" label=\"".$initlabel."\" source=\"".$initurl."\"></ntb:tab>"; break;
        case 'ajax'   : $ret.= "<ntb:tab width=\"190px\" label=\"".$initlabel."\" source=\"".$initurl."\" loadondemandenabled=\"true\"></ntb:tab>"; break;
		//iframe
        default : $ret.= "<ntb:tab width=\"190px\" label=\"".$initlabel."\" source=\"".$initurl."\" containertype=\"".$type."\" ></ntb:tab>";		
	  }	
      $ret.= "</ntb:tabs>";  
      $ret.= "</ntb:tabstrip>";
 
      return ($ret);  	  
   }
}	
?>