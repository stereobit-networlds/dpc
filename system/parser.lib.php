<?php
if (!defined("PARSER_DPC")) {
define("PARSER_DPC",true);


class parser {

	var $userLevelID;
	var $agent;
	
	var $stype;
	var $sfile;
	var $data;
	var $url;
	
	var $dpcxml;

	function parser($scriptfile, $sourcetype) {

	  $UserSecID = GetGlobal('UserSecID');
	  $__USERAGENT = GetGlobal('__USERAGENT');	  

      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
	  $this->agent = $__USERAGENT;

	  $this->stype = $sourcetype;
      $this->sfile = $scriptfile;
	  
	  $this->dpcxml = false; //XUL DPC FUNCTION !!!!!

      switch ($this->stype) {
         case 1  : $this->data = file ("$scriptfile"); break; //file
         case 2  : $this->data = $scriptfile; break;          //array
		 case 3  : $this->data = explode("\n",$scriptfile); break;          //text
         default : $this->data = file ("$scriptfile"); 
      }
	  
	  $this->url = paramload('SHELL','protocol') . $_SERVER['HTTP_HOST'];	
	}


    function translate($summary=0) {

      $to_be_printed = null;
      //while (list ($line_num, $line) = each ($this->data)) {
	  foreach ($this->data as $line_num => $line) {	 	
		
		switch ($this->agent) {
		  case 'HTML' : $to_be_printed .= $this->parse($line); break;
		  case 'XML'  :
		  case 'XUL'  :
		  case 'GTK'  : $to_be_printed .= $this->parsexml($line); break;
		  case 'WAP'  : $to_be_printed .= $this->parsewap($line); break;	
	    }
	  }
	  
	  switch ($this->agent) {
		case 'HTML' :
		case 'WAP'  : $final_print = $to_be_printed;
		              break;
		case 'XML'  :
		case 'XUL'  :
		case 'GTK'  : if ($this->dpcxml==true) {
					    $final_print = $to_be_printed; //as is (XUL must returned)
					  }	
		              else { //GTKHTML articles
					    if ($to_be_printed) {
		                  $xml = new pxml(''); //doesn't include 'XUL' tag
			              $xml->addtag('GTKHTML',null,$xml->cdata($to_be_printed),'cdata=1');
					      $final_print = $xml->getxml();
					      unset($xml);	
						}
					  }
		              break;  
      }


	  return ($final_print);

  }
  
  function parse($line) {
  
	   $split = explode (";", $line);
	   
	   switch ($split[0]) {  

         case "HTML" :
			$html_param1 = chop ("$split[1]");
            $html_param2 = chop ("$split[2]");
			if ($html_param1=='INLINE')
			  $out = $html_param2;
			else {
              $html_text = html2txt("$html_param1");        
              $out = $html_text; 
			}
		    $this->dpcxml = false;			       
			break;

         case "LINK" :
			$link_param1 = chop ($split[1]); //link
            $link_param2 = chop ($split[2]); //alias
			$link_param3 = chop ($split[3]); //javascript
            $out = " <A href=\"$link_param1\" $link_param3>$link_param2</A>";
		    $this->dpcxml = false;				
			break;
		
         case "DPCLINK" :
			$url = chop ($split[1]); //link
            $title = chop ($split[2]); //alias
			$ssl = chop ($split[3]); //ssl
            $out = seturl(str_replace(",","&",$url),$title,$ssl);
		    $this->dpcxml = false;				
			break;
            
         case "CR" :
		 case "BR" :
            $out = "<br/>";
			//$this->dpcxml = ...//neutral
			break;
			
         case "HR" :
            $out = "<hr/>";
			//$this->dpcxml = ...//neutral			
			break;

         case "SP" :
            $out = "&nbsp";
		    $this->dpcxml = false;				
			break;
	
         case "VER" :
            $out = paramload('SHELL','version');
		    $this->dpcxml = false;				
			break;		
		
		 case "PIC" : 
			$pic_param1 = $this->url . chop ("$split[1]"); //pic path absolute
          	$pic_param2 = chop ("$split[2]"); //width     
            $pic_param3 = chop ("$split[3]"); //height     
            if ($split[4]) {
			  if (eregi("^http://",$split[4]))
			    $pic_param4 = $split[4];//link 
			  else
			    $pic_param4 = seturl(str_replace(',','&',$split[4]));//link 
			}  
                  
            if (($pic_param2) && ($pic_param3)) {
              if ($pic_param4) {
			     $out = "<A href=\"$pic_param4\"><IMG src=\"$pic_param1\" width=$pic_param2 height=$pic_param3 alt=\"\" border=0></A>";
			  } 
              else {
			     $out = "<IMG src=\"$pic_param1\" width=$pic_param2 height=$pic_param3 alt=\"\" border=0>";
			  }
            }
            else  {
              if ($pic_param4) {
			     $out = "<A href=\"$pic_param4\"><IMG src=\"$pic_param1\" alt=\"\" border=0></A>";
			  } 
              else {
			     $out = "<IMG src=\"$pic_param1\" alt=\"\" border=0>";
			  }
            }
		    $this->dpcxml = false;				
			break;
		
		 case "THEMEPIC" :	
			$pic_param1 = getThemepath() . chop ("$split[1]"); //pic name	
			
	        $out = "<IMG src=\"$pic_param1\" alt=\"\" border=0>";	
		    $this->dpcxml = false;								
            break;
		
		 case "TEXT" :
			$out = chop($split[1]);
		    $this->dpcxml = false;				
			break;
		
		 case "MORE" :
			$more = chop($split[1]);
            $out = " <A href=\"$more\">More</A>";
		    $this->dpcxml = false;				
			break;

         case "START_COLUMN" :
            $out = "<td ";
			if ($split[1]) $out .= " align=\"$split[1]\"";
			if ($split[2]) $out .= " width=\"$split[2]\"";
			if ($split[3]) $out .= " valign=\"$split[3]\"";						
            $out .= ">";
		    $this->dpcxml = false;				
			break;
		
		 case "END_COLUMN" :
            $out = "</td>";
		    $this->dpcxml = false;				
			break;

         case "START_ROW" :
            $out = "<tr>";
		    $this->dpcxml = false;				
			break;
		
		 case "END_ROW" :
            $out = "</tr>";
		    $this->dpcxml = false;				
			break;

         case "START_TABLE" :
			$t_width   = chop ($split[1]);
			$t_border  = chop ($split[2]);
			$t_class   = chop ($split[3]);
			$t_bground = $this->url . chop ($split[4]);	//absoloute path
            $out = "<table width=\"$t_width\" border=\"$t_border\" cellpadding=\"0\" cellspacing=\"0\" class=\"$t_class\" background=\"$t_bground\">";
		    $this->dpcxml = false;				
			break;
		
		 case "END_TABLE" :
            $out = "</table>";
		    $this->dpcxml = false;				
			break;

         case "START_ALIGN" :
            if ($split[1]) $align = $split[1];
                      else $align = "left"; 
            $out = "<div align = \"$align\">";
		    $this->dpcxml = false;					  
			break;
		
		 case "END_ALIGN" :
            $out = "</div>";
		    $this->dpcxml = false;				
			break;

         case "PRICE" :
            if (_SHOPCART_) {
			 // $to_be_printed .= getShopView($split[1],$split[2]); 
			}
			else { //as common text
              $price = chop ("$split[1]");
              $out = "Price : $price";			
			}
		    $this->dpcxml = false;				
			break;
		
         case "DPCSCRIPT" :
		  	$out = GetGlobal('controller')->calldpc_method($split[1],1);//no error!!!!!
			$this->dpcxml = true;
			break;	
		
		 case ""  : $out = null;
		            break;						
		
		 default :
		    $out = null;
		    $this->dpcxml = false;
	 }
	 
	 $out .= $this->parse_command($line); //old style call methods
	 $out .= $this->dpcscript($line); //new style call methods (UNDER CONSTRUCTION)  
		 
	 
	 return ($out);
  }
  
  function parsexml($line) {
  
	   $split = explode (";", $line);
	   
	   switch ($split[0]) {  
	     
         case "DPCSCRIPT" :
		  	$out = GetGlobal('controller')->calldpc_method($split[1]);
			$this->dpcxml = true;
			break;	
		
		 case ""  : $out = null;
		            break;	
		
		 //special tag indicates that article is XULED			
		 case "MIXED"  : $out = null;
		            $this->dpcxml = true;
		            break;						
		
		 default :  $out = null;
		            $this->dpcxml = false;
	   }
	 
	   $out .= $this->parse_command($line); //old style call methods
	   $out .= $this->dpcscript($line); //new style call methods (UNDER CONSTRUCTION)  
		 
	   return ($out); 
  }
  
  function parsewap($line) {
  
	 $split = explode (";", $line);
	   
	 switch ($split[0]) {  
	     
        case "CR"   : $out = "<br/>";
			          break;
					
		case "TEXT" : $out = chop ("$split[1]");
		              break; 
		
		default     : $out = null;
	 } 
		 
	 return ($out); 
  }  
  
  function parse_command($parseinput) {
      //global $__PARSECOM;
	  //global $__DPC;
	  $__PARSECOM = GetGlobal('__PARSECOM');
	  $__DPC = GetGlobal('__DPC');	  
	  
	  if (is_array($__PARSECOM)) {
	  
	    $pinput = explode(";",$parseinput);
	  
	    reset($__PARSECOM);
	    foreach ($__PARSECOM as $dpc_name => $func) {
	  
	      if (count($func)>0) {
	         foreach ($func as $funcexe => $command) {	  

		       if ($pinput[0] == $command) {
		  
                 if ( (defined($dpc_name)) && (seclevel($dpc_name,decode(GetSessionParam('UserSecID')))) ) {
			       $theclass = new $__DPC[$dpc_name];
                   $out = $theclass->$funcexe($pinput[1],$pinput[2],$pinput[3],$pinput[4],$pinput[5]);
			       unset ($theclass);
			  
			       $this->dpcxml = true;	  
			     }  
		       }
		     }
		  }
	    }
	  
	    return $out;	 
	   
	  }
  }
  
  //new call typr of dpc methods (can replace PARSECOM array)
  function dpcscript($line) { 
      
	  if (ereg("^<",$line)) {
	   $cmd = trim(ereg_replace("^<","",$line)); //echo $cmd;
	   $out = calldpc_method($cmd);   
	   
	   $this->dpcxml = true;
			 	   
	   return ($out);
	  } 
  }

};
}
?>