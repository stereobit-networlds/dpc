<?php
define("SWFCHARTS_DPC",true);

$__DPC['SWFCHARTS_DPC'] = 'swfcharts';

class swfcharts {

   var $xml_source;
   var $library_path;
   var $result, $path;
   var $charviews, $charttypes;
   var $ctype, $cview;

   function swfcharts() {

     $this->xml_source = 'sample.xml';
     $this->library_path = 'charts_library';
	 if ($remoteuser=GetSessionParam('REMOTELOGIN')) 
		$this->path = paramload('SHELL','urlpath')."/$remoteuser/".paramload('ID','hostinpath').'/';	
	 else 
		$this->path = paramload('SHELL','urlpath')."/".paramload('ID','hostinpath').'/';
		
     $this->chartviews = array(0=>'column',1=>'bar',2=>'line',3=>'parallel 3d column',4=>'pie',5=>'3d pie',6=>'area',7=>'candlestick',8=>'scatter',9=>'polar');
	 $this->charttypes = array(0=>'All years',1=>'Per Year');
   }
   
   function set_xml_source($xml_file) {
     $this->xml_source = $xml_file;
   }

   function render($id,$width,$height) {

      $ret =   "<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\"	codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" 	WIDTH=\"$width\" 	HEIGHT=\"$height\" 	id=\"$id\" 	ALIGN=\"\"><PARAM NAME=movie VALUE=\"charts.swf?library_path=$this->charts_library&xml_source=$this->xml_source\"><PARAM NAME=quality VALUE=high><PARAM NAME=bgcolor VALUE=#666666><EMBED src=\"charts.swf?library_path=$this->library_path&xml_source=$this->xml_source\"       quality=high        bgcolor=#666666         WIDTH=\"$width\"        HEIGHT=\"$height\"        NAME=\"charts\"        ALIGN=\"\"        swLiveConnect=\"true\"        TYPE=\"application/x-shockwave-flash\"        PLUGINSPAGE=\"http://www.macromedia.com/go/getflashplayer\"></EMBED></OBJECT>";
      return ($ret);
   }
   
   //alias of render
   function chart($id,$width,$height) {
   
      $ret = $this->render($id,$width,$height);
	  return ($ret);
   }   
   
   function gauge($id,$width,$height) {
      $w = $width?$width:400;
      $h = $height?$height:250;	  

      //$ret =   "<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\"	codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" 	WIDTH=\"$width\" 	HEIGHT=\"$height\" 	id=\"$id\" 	ALIGN=\"\"><PARAM NAME=movie VALUE=\"gauge.swf?library_path=$this->charts_library&xml_source=$this->xml_source\"><PARAM NAME=quality VALUE=high><PARAM NAME=bgcolor VALUE=#666666><EMBED src=\"gauge.swf?library_path=$this->library_path&xml_source=$this->xml_source\"       quality=high        bgcolor=#666666         WIDTH=\"$width\"        HEIGHT=\"$height\"        NAME=\"charts\"        ALIGN=\"\"        swLiveConnect=\"true\"        TYPE=\"application/x-shockwave-flash\"        PLUGINSPAGE=\"http://www.macromedia.com/go/getflashplayer\"></EMBED></OBJECT>";
	  $ret  = "
	  <OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\"
	codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" 
	WIDTH=\"$w\"
	HEIGHT=\"$h\"
	id=\"gauge\">
<PARAM NAME=\"movie\" VALUE=\"gauge.swf?xml_source=$this->xml_source\" />
<PARAM NAME=\"quality\" VALUE=\"high\" />
<PARAM NAME=\"bgcolor\" VALUE=\"#666666\" />
<param name=\"allowScriptAccess\" value=\"sameDomain\" />

<EMBED src=\"gauge.swf?xml_source=$this->xml_source\" 
	quality=\"high\" 
	bgcolor=\"#666666\" 
	WIDTH=\"$w\" 
	HEIGHT=\"$h\" 
	NAME=\"gauge\" 
	allowScriptAccess=\"sameDomain\" 
	swLiveConnect=\"true\" 
	TYPE=\"application/x-shockwave-flash\" 
	PLUGINSPAGE=\"http://www.macromedia.com/go/getflashplayer\">
</EMBED>
</OBJECT>
";

      return ($ret);
   }   

   function create_chart_data($rid,$wclause=null,$gclause=null) { 

      $db = GetGlobal('db');	
	  //echo $this->path."cp/reports/$rid.grh",'>';
	  //echo $this->path."cp/reports/$rid.grh";
	  $gparams = @file_get_contents($this->path."cp/reports/$rid.grh");

	  if ($gparams) {

	    $gp = explode(",",$gparams);
		//print_r($gp);
		$type = explode(";",$gp[0]);
		$scolor = explode(";",$gp[1]);
		$data_rows = explode(";",$gp[2]);
		$select = str_replace(";",",",$gp[3]);
		$selectclause = ($select?','.$select:null);
		$whereclause = $wclause?$wclause:$gp[4];
		$groupby = ($gclause?$gclause:str_replace('+',',',$gp[5]));		
		$orderby = str_replace('+',',',$gp[6]);
		$horizontal_labels = explode("|",$gp[7]);
		$vertical_labels = explode("|",$gp[8]);
		$adata = $gp[9];
	  }  


	  $sfile = @file_get_contents($this->path."cp/reports/$rid.sql");
	  //echo $sfile;
	  if ($sfile) {
	     //manipulate sql
		 $ss = explode('from',$sfile);
		 if (stristr($ss[1],'where')) {
		   $ww = explode('where',$ss[1]); //in case of where in sql ..drop it
		   $gSQL = $ss[0] . $selectclause . ' from ' . $ww[0] . ' '. $whereclause . ' ' . $groupby . ' '. $orderby;
		 }
		 else
		   $gSQL = $ss[0] . $selectclause . ' from ' . $ss[1] . ' '. $whereclause . ' ' . $groupby . ' '. $orderby;

		 //echo $gSQL;

		 if ($fp=fopen($this->path."cp/reports/sqlout.txt","w+")) {

		   fwrite($fp,$gSQL);
		   fclose($fp);
		 }  		 

	     $resultset = $db->Execute($gSQL,2);
		 //$num_data = print_r($resultset,1);  	 
		 $ret = $resultset->fields;	 
	     //echo "<pre>";
		 //print_r($ret); 
		 //echo "</pre>";
		 if (empty($ret)) return false;//exit
		 
         //read data
		 $maxy = 0;//max year array
		 foreach ($resultset as $r=>$rec) {
		   $y = $rec['year'];
		   $m = $rec['month'];
		   
		   //prevent to save 01 as 1 from substr of date in some sql
		   $dm = intval($m);
		   
		   $b[$y] = $rec['hits'];
		   $datalines[$dm][$y] = $rec['hits'];//array($y=>$rec['hits']);//$b;
		   $maxy = $maxy>count($b)?$maxy:count($b);
		 }
		 $num_data .= $maxy.'>>>>>>>>>>>>>>>>>>>>';
		 $yk = array_keys($b);
		 $num_data .= print_r($yk,1);		 
		 
		 //selection of type
		 $show = GetReq('ctype')?GetReq('ctype'):0;
		 
		 ksort($datalines);
		 $num_data .= print_r($datalines,1);		 
		 reset($datalines);
		 
		 //normalization
         foreach ($horizontal_labels as $month) {		 
           //$num_data .= $month . print_r($datalines[$month],1);
		   if (is_array($datalines[$month])) {
		     //$num_data .= $month . '--';
			 
		     switch ($show) {
			 case 1: //per year region
			 //foreach ($datalines[$month] as $year=>$hits)  
			   //$nd[$month][$year] = $hits;//array($year=>$hits);  
			 foreach ($yk as $y=>$year)
			   $nd[$month][$year] = $datalines[$month][$year];
			 break;
			
			 case 0 ://all years as one
			 default:	
		     $h = 0;   		   				   
		     //foreach ($datalines[$month] as $year=>$hits)
			 //  $h += $hits; 
			 foreach ($yk as $y=>$year)
			   $h += $datalines[$month][$year];
			 $nd[$month] = $h;
			 }
		   }
		   else
		     $nd[$month] = 0;
		 }
		 		 
		 ksort($nd);				 			 
		 $num_data .= print_r($nd,1);		 			 
		 reset($nd);
		 
		 //selection of view
		 $chart_type = GetReq('cview')?$this->chartviews[GetReq('cview')]:$this->chartviews[0];
		 $chart_data = "<chart>";		 
		 $chart_data.= "<chart_type>".$chart_type."</chart_type>";
		 $chart_data.= "<chart_data>";
		 
		 $labels = array_keys($nd);
		 $chart_data .= '<row><null/>';
		 $chart_data .= '<string>'. implode('</string><string>',$labels) . '</string></row>';
		 
		 switch ($show) {
		 case 1:// multiyear view		 
		 foreach ($yk as $i=>$year) {		 
		   $chart_data .= '<row><string>'.$year.'</string>';
		   reset($nd);		 
		   foreach ($nd as $i=>$n) {		 		 
		      if ((is_array($n)) && (isset($n[$year])))
			    $chart_data .=  '<number>'.$n[$year].'</number>';
			  else
			    $chart_data .=  '<null/>';  
		   } 	  
		   $chart_data .= '</row>';		 
		 }		 
		 break;
		 
		 case 0 :
		 default :
		 $chart_data .= '<row><string>hits</string>';
		 foreach ($nd as $i=>$n) {
		    if ($n>0)
			  $chart_data .=  '<number>'.$n.'</number>';
			else
			  $chart_data .=  '<null/>';
		 }	    		 
		 $chart_data .= '</row>';		 
		 }		 
		 
		 $chart_data .= '</chart_data>\r\n';		 
		 $chart_data .= "</chart>\r\n";		 
		 
		 $num_data .= $chart_data;
		 
		 if ($fp=fopen($this->path."cp/reports/$rid.data","w+")) {
		   $num_data .= $gSQL;
           $num_data .= implode('-',$horizontal_labels).'\r\n';
           $num_data .= implode('-',$vertical_labels).'\r\n';		   		   
		   fwrite($fp,$num_data);
		   fclose($fp);
		 }

		 if ($fp=fopen($this->path."cp/reports/$rid.xml","w+")) {
		   fwrite($fp,$chart_data);
		   fclose($fp);
		   return true;
		 }	 

	     return false;//data file not created!
	  }
	  else
	    return false;
    }	

	function show_chart($rid,$x=800,$y=500,$goto=null,$ai=null) {
	  $ai = $ai?$ai:GetReq('ai');
	  $param = explode(';',$params);
      
	  if ($goto) {
	    $out .= $this->chartviews($goto,$ai);
	    $out .= $this->charttypes($goto,$ai);
        $out .= "<br/>"; 		
	  }

	  $pf1 = $this->path."cp/reports/$rid.xml";
	  $pf2 = $this->path."cp/reports/$rid.php";	

	  if (is_readable($pf1)) //static xml
	    $type = 'xml';
	  elseif (is_readable($pf2)) //dynamic xml	
	    $type = 'php';

	  if ($type) {
		   $this->set_xml_source('reports/'.$rid.'.'.$type);
		   $out .= $this->render('usage',$x,$y);	  
	  } 
      return ($out);
	}  
	
	function chartviews($goto=null,$ajaxid=null) {
		$name = 'chartviews';
		$selection = GetReq('cview');		

		$r .= "<select name=\"cviews\" class=\"".$style."\"".( $size != 0 ? "size=\"".$size."\"" : "");
		if ($ajaxid)
		    $r .= " onChange=\"sndReqArg('$goto'+statsid.value+this.options[this.selectedIndex].value,'$ajaxid');\">";
		else	  
			$r .= " onChange=\"location=this.options[this.selectedIndex].value\">";
			  
		if (!empty($this->chartviews)) {	  
		  $r .= "<option value=''>------View------</option>";
		  reset($this->chartviews);
		  while (list ($value, $title) = each ($this->chartviews)) {
		    if ($ajaxid)
		      $myvalue = '&ctype='. GetReq('ctype') .'&cview='. $value . '&nocache='.microtime();
		    else		  
              $myvalue = $goto . '&ctype='. GetReq('ctype') . '&cview='. $value . '&nocache='.microtime();		  
		    //$loctitle = localize($title,getlocal());
			$r .= "<option value=\"$myvalue\"".($value == $selection ? " selected" : "").">$title</option>";
		  }	
	    }		
		$r .= "</select>";
		
		return ($r);
							  		  			  	   
	}
	
	function charttypes($goto=null,$ajaxid=null) {
		$name = 'charttypes';
		$selection = GetReq('ctype');
		
		$r .= "<select name=\"".$name."\" class=\"".$style."\"".( $size != 0 ? "size=\"".$size."\"" : "");
		if ($ajaxid)
		    $r .= " onChange=\"sndReqArg('$goto'+statsid.value+this.options[this.selectedIndex].value,'$ajaxid');\">";
		else
			$r .= " onChange=\"location=this.options[this.selectedIndex].value\">";
			  
		if (!empty($this->charttypes)) {	  
		  $r .= "<option value=''>------Type------</option>";
		  reset($this->charttypes);
		  while (list ($value, $title) = each ($this->charttypes)) {
		    if ($ajaxid)
		      $myvalue = '&cview='. GetReq('cview') .'&ctype='. $value . '&nocache='.microtime();
		    else		  
              $myvalue = $goto . '&cview='. GetReq('cview') .'&ctype='. $value . '&nocache='.microtime();		  
		    //$loctitle = localize($title,getlocal());
			$r .= "<option value=\"$myvalue\"".($value == $selection ? " selected" : "").">$title</option>";
		  }	
	    }		
		$r .= "</select>";
		
		return ($r);
							  		  			  	   
	}
	
	
	
	
   function create_gauge_data($rid,$wclause=null,$gclause=null,$gtype=null,$gx=null,$gy=null,$title=null,$image=null) {

      $db = GetGlobal('db');	
	  //echo $this->path."cp/reports/$rid.grh",'>';
	  $gparams = @file_get_contents($this->path."cp/reports/$rid.grh");

	  if ($gparams) {

	    $gp = explode(",",$gparams);
		//print_r($gp);
		$type = explode(";",$gp[0]);
		$scolor = explode(";",$gp[1]);
		$data_rows = explode(";",$gp[2]);
		$select = str_replace(";",",",$gp[3]);
		$selectclause = ($select?','.$select:null);
		$whereclause = $wclause?$wclause:$gp[4];
		$groupby = ($gclause?$gclause:str_replace('+',',',$gp[5]));		
		$orderby = str_replace('+',',',$gp[6]);
		$horizontal_labels = explode("|",$gp[7]);
		$vertical_labels = explode("|",$gp[8]);
		$adata = $gp[9];
	  }  


	  $sfile = @file_get_contents($this->path."cp/reports/$rid.sql");
	  //echo $sfile;
	  if ($sfile) {
	     //manipulate sql
		 $ss = explode('from',$sfile);
		 if (stristr($ss[1],'where')) {
		   $ww = explode('where',$ss[1]); //in case of where in sql ..drop it
		   $gSQL = $ss[0] . $selectclause . ' from ' . $ww[0] . ' '. $whereclause . ' ' . $groupby . ' '. $orderby;
		 }
		 else
		   $gSQL = $ss[0] . $selectclause . ' from ' . $ss[1] . ' '. $whereclause . ' ' . $groupby . ' '. $orderby;

		 //echo $gSQL;

		 if ($fp=fopen($this->path."cp/reports/sqlout.txt","w+")) {

		   fwrite($fp,$gSQL);
		   fclose($fp);
		 }  		 

	     $resultset = $db->Execute($gSQL,2);
		 //$num_data = print_r($resultset,1);  	 
		 $ret = $resultset->fields;	 
	     //echo "<pre>";
		 //print_r($ret); 
		 //echo "</pre>";
		 if (empty($ret)) return false;//exit
		 
         //read data
		 $maxy = 0;//max year array
		 foreach ($resultset as $r=>$rec) {

		   $datalines[$r] = $rec;

		 }

		 //$num_data .= print_r($datalines,1);
		 //convert value to angle
		 //.....
		 $rotate = intval($datalines['hits']);//30;
		 $nd2rotate = 0;		 			 		 			 
		 
         ///////////////////////////////////////////////////////////////////////
		 
		 $chart_data = "<gauge>" . PHP_EOL;
	
		 switch ($gtype) {
		 case 2 :
		          $chart_data.= $this->php_gauge($title,$rotate,$nd2rotate); break;
		 case 1 : 
		          $chart_data.= $this->image_gauge($gx,$gy,$image,$title,$rotate); break;		 		 
		 default :
		          $chart_data.= $this->simple_gauge($title);//image_gauge(200,150);
		 }
		 
		 $chart_data .= "</gauge>" . PHP_EOL;		 
		 
		 //$num_data .= $gx.'x'.$gy.'-'.$gtype . '>>>>';
		 $num_data .= $chart_data;
		 
		 if ($fp=fopen($this->path."cp/reports/$rid.data","w+")) {
		   //$num_data .= $gSQL;
           //$num_data .= implode('-',$horizontal_labels).'\r\n';
           //$num_data .= implode('-',$vertical_labels).'\r\n';		   		   
		   fwrite($fp,$num_data);
		   fclose($fp);
		 }

		 if ($fp=fopen($this->path."cp/reports/$rid.xml","w+")) {
		   fwrite($fp,$chart_data);
		   fclose($fp);
		   return true;
		 }	 

	     return false;//data file not created!
	  }
	  else
	    return false;
    }	
	
	
	function show_gauge($rid,$x,$y,$goto=null, $ai=null) {
	  $ai = $ai?$ai:GetReq('ai');
	  $param = explode(';',$params);
      
	  if ($goto) {
	    $out .= "<br/>";
	    //$out .= $this->chartviews($goto,$ai);
	    $out .= $this->charttypes($goto,$ai);
        $out .= "<br/>"; 		
	  }

	  $pf1 = $this->path."cp/reports/$rid.xml";
	  $pf2 = $this->path."cp/reports/$rid.php";	

	  if (is_readable($pf1)) //static xml
	    $type = 'xml';
	  elseif (is_readable($pf2)) //dynamic xml	
	    $type = 'php';

	  if ($type) {
		   $this->set_xml_source('reports/'.$rid.'.'.$type);
		   $out .= $this->gauge('usage',$x,$y);	  
	  } 
      return ($out);
	}  
	
    function simple_gauge($title=null,$span=0) {
		 $mytitle = $title?$title:'dashboard';	
	
		 $ret = "
   <circle x='200' y='125' radius='120' fill_color='ff6600' />
   <text x='165' y='70' width='200' size='20' color='ff9966'>$mytitle</text>
	
   <rotate x='200' y='125'>   
      <line x1='200' y1='25' x2='200' y2='125' thickness='6' color='88ff00' />	
      <circle x='200' y='50' radius='10' />   
   </rotate>
	
   <circle x='200' y='125' radius='30' />		 
		 ";
		 
		 return ($ret);
	}
	
    function image_gauge($x=null,$y=null,$image=null,$title=null,$span=0) {//400,300
         $myimage = $image?$image:'speed.jpg';
		 $mytitle = $title?$title:'dashboard';
		 $x = $x?$x:400;
		 $y = $y?$y:300;
		 
		 $x2 = $x/2;
		 $y2 = $y/2;
		 $x3 = $x2-1;
		 $y3 = $y2-10;
		 $x1p = $x2 +1; 		 $x2p = $x2 +2;
		 $x3p = $x2 -3; 		 $x4p = $x2 -4;
		 $y1p = $y2 -24;         $y2p = (($x/10)-10)+1;//30;
		 $x1r = $x2 -6;
		 $y1r = $y2 +4;
		 $rw = 10;
		 $r = 14;
		 $textsize = ($x/8)-1;		 	
	
		 $ret = "
	<image url='$myimage' />
	
	<rotate x='$x3' y='$y3' start='250' span='$span' step='3' shake_frequency='95' shake_span='3' shadow_alpha='15'>
		<polygon fill_color='ff4400' fill_alpha='90' line_alpha='0'>
			<point x='$x3p' y='$y2p' />
			<point x='$x1p' y='$y2p' />
			<point x='$x2p' y='$y1p' />
			<point x='$x4p' y='$y1p' />
		</polygon>
		<rect x='$x1r' y='$y1r' width='$rw' height='$y2p' fill_color='000000' fill_alpha='90' line_alpha='50' />
	</rotate>
	
	<circle x='$x3' y='$y3' radius='$r' fill_color='000000' fill_alpha='50' line_alpha='0' />
		 ";
		
		/* $ret .= "		
	<text x='$x' y='0' width='300' size='$textsize' color='ffffff' alpha='15' align='left' rotation='90'>$mytitle</text>		 
		 ";*/
		 
		 return ($ret);
	}	
	
	function php_gauge($title=null,$span1=0,$span2=0) {
		 $mytitle = $title?$title:'dashboard';	
	
		 $ret = "	
	<!-- small gauge -->
	<circle x='240' y='160' radius='48' fill_color='88ff66' fill_alpha='80' line_thickness='8' line_alpha='30' />
	<circle x='240' y='160' radius='45' start='37' end='170' fill_color='000000' fill_alpha='75' />
	<circle x='240' y='160' radius='42' fill_color='88ff66' fill_alpha='90' />
	<circle x='240' y='160' radius='20' fill_color='000000' fill_alpha='25' />
	<text x='256' y='131' width='100' size='11' color='000000' alpha='75' align='left'>H</text>
	<text x='241' y='182' width='100' size='11' color='000000' alpha='75' align='left'>C</text>
		 ";	

	     //this is a PHP function that generates the XML to to draw radial ticks
	     //any script language can be used to generate the XML code like this
	     $ret .= $this->RadialTicks( 240, 160, 37, 8, 40, 168, 5, 4, "333333" );
	
		 $ret .= "	
	<rotate x='240' y='160' start='80' span='$span2' step='1' shake_frequency='95' shake_span='10' shadow_alpha='30' shadow_x_offset='2' shadow_y_offset='2' >
		<rect x='238' y='130' width='4' height='50' fill_color='ff4400' fill_alpha='90' line_alpha='0' />
	</rotate>
	
	
	
	
	
	<!-- flashing light -->
	<circle x='270' y='145' radius='3' fill_color='440000' fill_alpha='80' line_thickness='1' line_alpha='10' />
	<rotate x='270' y='300' start='0' span='360000' step='120' shake_frequency='0' shake_span='0' shadow_alpha='0'>
		<circle x='270' y='145' radius='3' fill_color='FF5500' fill_alpha='100' line_thickness='0' line_alpha='10' />
	</rotate>
	
	
	
	
	<!-- large gauge -->
	<circle x='145' y='130' radius='110' fill_color='555555' fill_alpha='100' line_thickness='6' line_color='333333' line_alpha='90' />
	<circle x='145' y='130' radius='100' start='240' end='480' fill_color='99bbff' fill_alpha='90' line_thickness='4' line_alpha='20' />
	<circle x='145' y='130' radius='94' start='50' end='115' fill_color='FF4400' fill_alpha='100' />
	<circle x='145' y='130' radius='80' start='240' end='480' fill_color='99bbff' fill_alpha='80' />
	<circle x='145' y='130' radius='40' fill_color='333333' fill_alpha='100' line_alpha='0' />
	<circle x='145' y='130' radius='90' start='130' end='230' fill_color='333333' fill_alpha='100' line_alpha='0' />
	
		 ";
		 
	$ret .= $this->RadialTicks( 145, 130, 80, 15, 250, 387, 6, 8, "000000" );
	$ret .= $this->RadialTicks( 145, 130, 80, 15, 263, 400, 6, 4, "000000" );
	$ret .= $this->RadialTicks( 145, 130, 80, 15, 55, 110, 3, 4, "99bbff" );
	$ret .= $this->RadialNumbers( 145, 130, 80, 0, 8, 245, 465, 9, 14, "444444" );
		 
		 
		 $ret .= "		 
	<rotate x='145' y='130' start='0' span='$span1' step='1' shake_frequency='100' shake_span='2' shadow_alpha='15'>
		<rect x='143' y='40' width='4' height='100' fill_color='ffffff' fill_alpha='90' line_alpha='0' />
	</rotate>
	
	<circle x='145' y='130' radius='30' fill_color='111111' fill_alpha='100' line_thickness='5' line_alpha='50' />
	<text x='95' y='180' width='100' size='14' color='ffffff' alpha='70' align='center'>x1000 r/min</text>
	
	
	
	
	<!-- background elements -->
	<text x='-10' y='250' width='500' size='24' color='000000' alpha='20' align='left'>||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||</text>
	<text x='400' y='0' width='300' size='49' color='ffffff' alpha='15' align='left' rotation='90'>$mytitle</text>
		 ";	
	
		 return ($ret);	
	
	}
	
	//PHP function that generates the XML code to draw radial ticks
	function RadialTicks ( $x_center, $y_center, $radius,  $length, $start_angle, $end_angle, $ticks_count, $thickness, $color ){
		
		for ( $i=$start_angle; $i<=$end_angle; $i+=($end_angle-$start_angle)/($ticks_count-1) ){
			$ret .= "	<line x1='".($x_center+sin(deg2rad($i))*$radius)."' y1='".($y_center-cos(deg2rad($i))*$radius)."' x2='".($x_center+sin(deg2rad($i))*($radius+$length))."' y2='".($y_center-cos(deg2rad($i))*($radius+$length))."' thickness='".$thickness."' color='".$color."' />";
		
		}
		
		return ($ret);
	}

	//PHP function that generates the XML code to draw radial numbers
	function RadialNumbers ( $x_center, $y_center, $radius,  $start_number, $end_number, $start_angle, $end_angle, $ticks_count, $font_size, $color ){
		
		$number=$start_number;
		
		for( $i=$start_angle; $i<=$end_angle; $i+=($end_angle-$start_angle)/($ticks_count-1) ){
			$ret .= "	<text x='".($x_center+sin(deg2rad($i))*$radius)."' y='".($y_center-cos(deg2rad($i))*$radius)."' width='200' size='".$font_size."' color='".$color."' align='left' rotation='".$i."'>".$number."</text>";
			$number += ($end_number-$start_number)/($ticks_count-1);
		
		}
		
		return ($ret);		
	}
		
		

// charts.php v4.5

// ------------------------------------------------------------------------

// Copyright (c) 2003-2007, maani.us

// ------------------------------------------------------------------------

// This file is part of "PHP/SWF Charts"

//

// PHP/SWF Charts is a shareware. See http://www.maani.us/charts/ for

// more information.

// ------------------------------------------------------------------------	

function InsertChart( $flash_file, $library_path, $php_source, $width=400, $height=250, $bg_color="666666", $transparent=false, $license=null ){

	$php_source=urlencode($php_source);
	$library_path=urlencode($library_path);
	$html="<OBJECT classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' ";
	$html.="WIDTH=".$width." HEIGHT=".$height." id='charts' ALIGN=''>";
	$u=(strpos ($flash_file,"?")===false)? "?" : ((substr($flash_file, -1)==="&")? "":"&");
	$html.="<PARAM NAME=movie VALUE='".$flash_file.$u."library_path=".$library_path."&php_source=".$php_source;
	if($license!=null){$html.="&license=".$license;}
	$html.="'> <PARAM NAME=quality VALUE=high> <PARAM NAME=bgcolor VALUE=#".$bg_color."> ";
	if($transparent){$html.="<PARAM NAME=wmode VALUE=transparent> ";}
	$html.="<EMBED src='".$flash_file.$u."library_path=".$library_path."&php_source=".$php_source;
	if($license!=null){$html.="&license=".$license;}
	$html.="' quality=high bgcolor=#".$bg_color." WIDTH=".$width." HEIGHT=".$height." NAME='charts' ALIGN='' swLiveConnect='true' ";
	if($transparent){$html.="wmode=transparent ";}
	$html.="TYPE='application/x-shockwave-flash' PLUGINSPAGE='http://www.macromedia.com/go/getflashplayer'></EMBED></OBJECT>";
	return $html;
}
//====================================
function SendChartData( $chart=array() ){
	$xml="<chart>\r\n";
	$Keys1= array_keys((array) $chart);
	for ($i1=0;$i1<count($Keys1);$i1++){
		if(is_array($chart[$Keys1[$i1]])){
			$Keys2=array_keys($chart[$Keys1[$i1]]);
			if(is_array($chart[$Keys1[$i1]][$Keys2[0]])){
				$xml.="\t<".$Keys1[$i1].">\r\n";
				for($i2=0;$i2<count($Keys2);$i2++){
					$Keys3=array_keys((array) $chart[$Keys1[$i1]][$Keys2[$i2]]);
					switch($Keys1[$i1]){
						case "chart_data":
						$xml.="\t\t<row>\r\n";
						for($i3=0;$i3<count($Keys3);$i3++){
							switch(true){
								case ($chart[$Keys1[$i1]][$Keys2[$i2]][$Keys3[$i3]]===null):
								$xml.="\t\t\t<null/>\r\n";
								break;
								
								case ($Keys2[$i2]>0 and $Keys3[$i3]>0):
								$xml.="\t\t\t<number>".$chart[$Keys1[$i1]][$Keys2[$i2]][$Keys3[$i3]]."</number>\r\n";
								break;
								
								default:
								$xml.="\t\t\t<string>".$chart[$Keys1[$i1]][$Keys2[$i2]][$Keys3[$i3]]."</string>\r\n";
								break;
							}
						}
						$xml.="\t\t</row>\r\n";
						break;

						case "chart_value_text":
						$xml.="\t\t<row>\r\n";
						$count=0;

						for($i3=0;$i3<count($Keys3);$i3++){
							if($chart[$Keys1[$i1]][$Keys2[$i2]][$Keys3[$i3]]===null){$xml.="\t\t\t<null/>\r\n";}
							else{$xml.="\t\t\t<string>".$chart[$Keys1[$i1]][$Keys2[$i2]][$Keys3[$i3]]."</string>\r\n";}
						}
						$xml.="\t\t</row>\r\n";
						break;
						/*case "link_data_text":

						$xml.="\t\t<row>\r\n";

						$count=0;

						for($i3=0;$i3<count($Keys3);$i3++){

							if($chart[$Keys1[$i1]][$Keys2[$i2]][$Keys3[$i3]]===null){$xml.="\t\t\t<null/>\r\n";}

							else{$xml.="\t\t\t<string>".$chart[$Keys1[$i1]][$Keys2[$i2]][$Keys3[$i3]]."</string>\r\n";}

						}

						$xml.="\t\t</row>\r\n";

						break;*/

						case "draw":
						$text="";
						$xml.="\t\t<".$chart[$Keys1[$i1]][$Keys2[$i2]]['type'];
						for($i3=0;$i3<count($Keys3);$i3++){
							if($Keys3[$i3]!="type"){
								if($Keys3[$i3]=="text"){$text=$chart[$Keys1[$i1]][$Keys2[$i2]][$Keys3[$i3]];}
								else{$xml.=" ".$Keys3[$i3]."=\"".$chart[$Keys1[$i1]][$Keys2[$i2]][$Keys3[$i3]]."\"";}
							}
						}
						if($text!=""){$xml.=">".$text."</text>\r\n";}
						else{$xml.=" />\r\n";}
						break;

						default://link, etc.
						$xml.="\t\t<value";
						for($i3=0;$i3<count($Keys3);$i3++){
							$xml.=" ".$Keys3[$i3]."=\"".$chart[$Keys1[$i1]][$Keys2[$i2]][$Keys3[$i3]]."\"";
						}
						$xml.=" />\r\n";
						break;
					}
				}
				$xml.="\t</".$Keys1[$i1].">\r\n";
			}
			else {
				if($Keys1[$i1]=="chart_type" or $Keys1[$i1]=="series_color" or $Keys1[$i1]=="series_image" or $Keys1[$i1]=="series_explode" or $Keys1[$i1]=="axis_value_text"){							
					$xml.="\t<".$Keys1[$i1].">\r\n";
					for($i2=0;$i2<count($Keys2);$i2++){
						if($chart[$Keys1[$i1]][$Keys2[$i2]]===null){$xml.="\t\t<null/>\r\n";}
						else{$xml.="\t\t<value>".$chart[$Keys1[$i1]][$Keys2[$i2]]."</value>\r\n";}
					}
					$xml.="\t</".$Keys1[$i1].">\r\n";
				}else{//axis_category, etc.
					$xml.="\t<".$Keys1[$i1];
					for($i2=0;$i2<count($Keys2);$i2++){
						$xml.=" ".$Keys2[$i2]."=\"".$chart[$Keys1[$i1]][$Keys2[$i2]]."\"";
					}
					$xml.=" />\r\n";
				}
			}
		}else{//chart type, etc.
			$xml.="\t<".$Keys1[$i1].">".$chart[$Keys1[$i1]]."</".$Keys1[$i1].">\r\n";
		}
	}
	$xml.="</chart>\r\n";
	echo $xml;
}
//==================================== 	 
}
?>