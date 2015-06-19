<?php
if (defined("DATABASE_DPC")) { 

$__DPCSEC['HIGHLIGHT_DPC']='1;1;1;1;1;1;1;1;9';
$__DPCSEC['ADMINHL_']='2;1;1;1;1;1;1;2;9';

if ((!defined("HIGHLIGHT_DPC")) && (seclevel('HIGHLIGHT_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("HIGHLIGHT_DPC",true);

$__DPC['HIGHLIGHT_DPC'] = 'highlight';

$__EVENTS['HIGHLIGHT_DPC'][0]=localize('_HLIGHT1',getlocal());
$__EVENTS['HIGHLIGHT_DPC'][1]=localize('_HLIGHT2',getlocal());
$__EVENTS['HIGHLIGHT_DPC'][2]=localize('_HLIGHT3',getlocal());
$__EVENTS['HIGHLIGHT_DPC'][3]=localize('_HLIGHT4',getlocal());
$__EVENTS['HIGHLIGHT_DPC'][4]='reset_hl';
$__EVENTS['HIGHLIGHT_DPC'][5]=1;
$__EVENTS['HIGHLIGHT_DPC'][6]=2;
$__EVENTS['HIGHLIGHT_DPC'][7]=3;
$__EVENTS['HIGHLIGHT_DPC'][8]=4;
$__EVENTS['HIGHLIGHT_DPC'][9]=5;
$__EVENTS['HIGHLIGHT_DPC'][10]=6;
$__EVENTS['HIGHLIGHT_DPC'][11]=7;
$__EVENTS['HIGHLIGHT_DPC'][12]=8;
$__EVENTS['HIGHLIGHT_DPC'][13]=9;
$__EVENTS['HIGHLIGHT_DPC'][14]='offers2txt';
$__EVENTS['HIGHLIGHT_DPC'][15]='txt2offers';

$__ACTIONS['HIGHLIGHT_DPC'][0]='hl';
$__ACTIONS['HIGHLIGHT_DPC'][1]=1;
$__ACTIONS['HIGHLIGHT_DPC'][2]=2;
$__ACTIONS['HIGHLIGHT_DPC'][3]=3;
$__ACTIONS['HIGHLIGHT_DPC'][4]=4;
$__ACTIONS['HIGHLIGHT_DPC'][5]=5;
$__ACTIONS['HIGHLIGHT_DPC'][6]=6;
$__ACTIONS['HIGHLIGHT_DPC'][7]=7;
$__ACTIONS['HIGHLIGHT_DPC'][8]=8;
$__ACTIONS['HIGHLIGHT_DPC'][9]=9;
$__ACTIONS['HIGHLIGHT_DPC'][10]='offers2txt';
$__ACTIONS['HIGHLIGHT_DPC'][11]='txt2offers';

$__DPCATTR['HIGHLIGHT_DPC'][localize('_HLIGHT1',getlocal())] = '_HLIGHT1,0,0,0,0,0,1,0';
$__DPCATTR['HIGHLIGHT_DPC'][localize('_HLIGHT2',getlocal())] = '_HLIGHT2,0,0,0,0,0,1,0';
$__DPCATTR['HIGHLIGHT_DPC'][localize('_HLIGHT3',getlocal())] = '_HLIGHT3,0,0,0,0,0,1,0';
$__DPCATTR['HIGHLIGHT_DPC'][localize('_HLIGHT4',getlocal())] = '_HLIGHT4,0,0,0,0,0,1,0';
$__DPCATTR['HIGHLIGHT_DPC']['1'] = '1,0,0,0,0,0,0,1,0,1';
$__DPCATTR['HIGHLIGHT_DPC']['2'] = '2,0,0,0,0,0,0,1,0,1';
$__DPCATTR['HIGHLIGHT_DPC']['3'] = '3,0,0,0,0,0,0,1,0,1';
$__DPCATTR['HIGHLIGHT_DPC']['4'] = '4,0,0,0,0,0,0,1,0,1';
$__DPCATTR['HIGHLIGHT_DPC']['5'] = '5,0,0,0,0,0,0,1,0,1';
$__DPCATTR['HIGHLIGHT_DPC']['6'] = '6,0,0,0,0,0,0,1,0,1';
$__DPCATTR['HIGHLIGHT_DPC']['7'] = '7,0,0,0,0,0,0,1,0,1';
$__DPCATTR['HIGHLIGHT_DPC']['8'] = '8,0,0,0,0,0,0,1,0,1';
$__DPCATTR['HIGHLIGHT_DPC']['9'] = '9,0,0,0,0,0,0,1,0,1';


$__LOCALE['HIGHLIGHT_DPC'][0]='HIGHLIGHT_DPC;Offers;Προσφορές';
$__LOCALE['HIGHLIGHT_DPC'][1]='_HLIGHT1;HL This;HL περιοχής';
$__LOCALE['HIGHLIGHT_DPC'][2]='_HLIGHT2;HL Group;HL Ομάδα';
$__LOCALE['HIGHLIGHT_DPC'][3]='_HLIGHT3;DL This;DL περιοχής';
$__LOCALE['HIGHLIGHT_DPC'][4]='_HLIGHT4;DL Group;DL Ομάδα';
$__LOCALE['HIGHLIGHT_DPC'][5]='_HLIGHT5;Target group;Ομάδα στόχου';
$__LOCALE['HIGHLIGHT_DPC'][6]='_PRODOFFER;Offers for;Προσφορές για';

//$__PARSECOM['HIGHLIGHT_DPC']['render']='_HIGHLIGHTS_'; DISABLED use DPCSCRIPT 
$__BROWSECOM['HIGHLIGHT_DPC'] = 'commandbar';

class highlight {

     var $currentdir;
	 var $moneysymbol;	 
	 var $view;
	 var $splash;
	 var $star;
	 var $_CONTINUE; //continue actions in shell
	 
	 var $abspicpath;
	 var $restype;
	 var $nopic;	
	 var $resourcepicpath;	  

     function highlight($diralias='') {
        $UserSecID = GetGlobal('UserSecID');
		$GRX = GetGlobal('GRX');
		
		$this->_CONTINUE = 1;
		
        $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);		
		
 	    $viewperclient = arrayload('HIGHLIGHTS','viewperclient');			
	    $this->view  = $viewperclient[$this->userLevelID];
	    if (!$this->view) $this->view = paramload('HIGHLIGHTS','dview');			
		
        $this->moneysymbol = "&" . paramload('CART','cursymbol') . ";";		 
		
        if ($GRX) {
		  $this->splash = loadTheme('splash','',1);
		  $this->star = loadTheme('star','',1);		  
		}		
			 
		//OUT no defaulr root	 
	    //if ($diralias) $this->currentdir = $diralias;
		  //        else $this->currentdir = 'root';
				  
	    //$this->resourcepicpath = paramload('SHELL','urlbase') . paramload('PRODUCTS','dbres');
		
        $ip = $_SERVER['HTTP_HOST'];
        $pr = paramload('SHELL','protocol');		
	    $this->resourcepicpath = $pr .$ip . paramload('PRODUCTS','dbres');		
							  
	    $this->abspicpath = paramload('SHELL','prpath') . paramload('SHELL','picpath')  . paramload('PRODUCTS','dbres');
	    $this->restype = paramload('PRODUCTS','restype');
	    $this->nopic = paramload('PRODUCTS','nopic');				  
	 }
	 
     function event($sAction) {

       switch($sAction) {
	     case localize('_HLIGHT1',getlocal()) : $this->get(1);  break;
	     case localize('_HLIGHT2',getlocal()) : $this->get(2);  break;			 
	     case localize('_HLIGHT3',getlocal()) : $this->get(3);  break;
		 case localize('_HLIGHT4',getlocal()) : $this->get(4);  break;
	     case "reset_hl"                      : $this->reset_db();  break;	
		 case 1      : 
		 case 2      : 
		 case 3      : 
		 case 4      : 
         case 5      : 
		 case 6      : 
         case 7      : 
		 case 8      : 
		 case 9      : break;	
		 case 'offers2txt' : if	($this->exportxt()) setInfo("OK!");
		                                       else setInfo("Error!");  	 
		 case 'txt2offers' : if	($this->importxt()) setInfo("OK!");
		                                       else setInfo("Error!"); 											   
       }
 	}
	
	function action($act) {

	   $g = GetReq('g');

	   switch ($act) {
		 case 1           : 
		 case 2           : 
		 case 3           : 
		 case 4           : 
         case 5           : 
		 case 6           : 
         case 7           : 
		 case 8           : 
		 case 9           : $out = $this->render('2003',$g); 
		                    break;
		 case 'hl'        :	$out = $this->preview($g); 
                            $out .= $this->adminhl();
							$this->_CONTINUE = 0;	 
		                    break;  
		 case 'offers2txt': break;					 
		 case 'txt2offers': break;		 
	   }

	   return ($out);
	}
	
	function adminhl() {
	
       if (seclevel('ADMINHL_',$this->userLevelID)) {	   
  
         $filename = seturl("t=hl");		   
 
         $out = "<FORM action=". $filename . " method=post class=\"thin\">"; 	   
		 $out .= $this->commandbar();
	   	 $out .= "</FORM>";
	   }		
	   return ($out);
	}
	
	function read_hls() {
       $db = GetGlobal('db');

       $sSQL = "select * from offers where id=" . $db->qstr(ext2ascii($this->currentdir));
	   $res = $db->Execute($sSQL);	
	   
	   //echo $sSQL;
	
	   if ($res)  {
		 //print $res->fields[2];	
	     if (defined('FILESYSTEM_DPC')) {
	                 //$myfiles = new filesystem();
		             //$hlres = $myfiles->read($res->fields[2]);
			  	     //unset($myfiles);
	     }
  	     if (defined('PRODUCTS_DPC')) {
		             //$myhighl = new products();
		             //$hlres = $myhighl->read($res->fields[2]);
			         //unset($myhighl);
					 $hlres = GetGlobal('controller')->calldpc_method('products.read use '.$res->fields[2]);
	     }
	   }
	   //print_r($hlres);
	   
	   return ($hlres);
	}		 

    function render($vstyle=2003,$diralias) {			 
	     $g = GetReq('g');
		 
		 //added for parser compatibility where params mustbe at action function and not to constructor
	     if ($diralias) $this->currentdir = $diralias;	 

         //$hlres = getcache(urlencode($this->currentdir),"hls","read_hls",$this); 
		 $a = urlencode($this->currentdir);
		 $b = 'hls';
		 $c = 'highlight.read_hls';
         $hlres = GetGlobal('controller')->calldpc_method_use_pointers('cache.getcache_method_use_pointers',
		                                      array(0=>&$a,1=>&$b,2=>&$c));		 
		 
      			 
         if ($hlres) {
             $mydbrowser = new browse($hlres,null,null,null,'a,b');	   
	         $out2 = $mydbrowser->render($vstyle,0,$this); 
  	         unset ($mydbrowser);
			 
			 if ($this->currentdir==$g) {
			   $hlwin = new window(localize('_PRODOFFER',getlocal())." ".$this->currentdir,$out2);
			   $out = $hlwin->render();
			   unset ($hlwin); 
			 }
			 else 
			   $out = $out2;
		 }
		 
		 return ($out);
     }
	 
	 function preview($hlsel='') {
         $db = GetGlobal('db');
		 
		 if (!$hlsel) { //view main menu
	 
           $sSQL = "select id,data from offers";
		   $table = $db->Execute($sSQL);	
		 
           $i=1;
           while(!$table->EOF) {

            $htbl[] = //$i . ";" . 
		            $this->prepare($table->fields[1]) . ";" .
                    ext2ascii($table->fields[0]) . ";" ;

            //print_r($table->fields);
		    //print_r(explode(";",$table->fields[1]));
	        $table->MoveNext();
		    $i+=1;
	       }		

		   $out = setNavigator(localize('HIGHLIGHT_DPC',getlocal()));
		 
           $mydbrowser = new browse($htbl,localize('HIGHLIGHT_DPC',getlocal()));	   
	       $out .= $mydbrowser->render('preview',10,$this,1,1,1); 
  	       unset ($mydbrowser);
		 }
		 else { //view selected highlights

		   $mainhlurl = seturl("t=hl",localize('HIGHLIGHT_DPC',getlocal())); 
		   $out = setNavigator($mainhlurl,$hlsel);		 
		   //if ($hlsel) 
		   $out .= $this->render('2003',$hlsel);		  	 
		 }
		 
		 //frontpage like out
		 $hfp = new frontpage('highlight');
		 $fpout = $hfp->render($out);
		 unset($hfp);
		 
		 return ($fpout);     
	 }
	 
	 function prepare($field) {
	 
	    $hl_code = explode(";",$field);
		$picname = $hl_code[0];
		//echo $picname;
		
        $ret = (file_exists($this->abspicpath . $picname . $this->restype) ? $this->resourcepicpath . $picname . $this->restype : $this->resourcepicpath . $this->nopic . $this->restype);	
		
		//return first product code image file
		return ($ret);
	 }
	 
	 function commandbar() {
	 
        if (seclevel('ADMINHL_',$this->userLevelID)) {
          $out  = "<input type=\"submit\" name=\"FormAction\" value=\"" . localize('_HLIGHT1',getlocal()) . "\">&nbsp;";	
		  $out .= "<input type=\"submit\" name=\"FormAction\" value=\"" . localize('_HLIGHT3',getlocal()) . "\">&nbsp;";
		  $out .= localize('_HLIGHT5',getlocal()) . "<INPUT name=targetgroup size=15>&nbsp;";					
	      $out .= "<input type=\"submit\" name=\"FormAction\" value=\"" . localize('_HLIGHT2',getlocal()) . "\">&nbsp;";			    
		  $out .= "<input type=\"submit\" name=\"FormAction\" value=\"" . localize('_HLIGHT4',getlocal()) . "\">&nbsp;";				
		  
		  $wina = new window('',$out);
		  $winout = $wina->render("center::100%::0::group_dir_title::right::0::0::");
		  unset ($wina);		  
		}		
		return ($winout);
	 }

	 //save selected articles to offers table  
     function get($mode) {
        //global $_POST;
        $db = GetGlobal('db');
		
		$g = GetReq('g');   
	  
		  //get submited data
	      //while(list ($id, $data) = each ($HTTP_POST_VARS)) {
          foreach ($_POST as $id => $data) {		  
		    if (ereg(';',$data)) $tostore .= $data;	
		  }
		  $tgroup = ascii2ext(GetParam('targetgroup')); 
		  //print $tgroup;
		  if (!$tgroup) $tgroup=ascii2ext('root');
		  
		  //print $tostore;
	  
	      switch ($mode) {
		    //current dir
	        case 1 : $sSQL0 = "select * from offers where id=" . $db->qstr(ext2ascii($g));
		             $res0 = $db->Execute($sSQL0);
		             //update store data with existing value 
		             if ($res0) $tostore .= $res0->fields[2]; 
					 
			         $sSQL = "insert into offers (id,data) values (" . $db->qstr(ext2ascii($g)) . 
                             "," . $db->qstr($tostore) . ")"; 
                     $sSQL1 = "update offers set data=" . $db->qstr($tostore) .
				              " where id=" . $db->qstr(ext2ascii($g)); 						   
				     break;
		    //root 		   
		    case 2 : $sSQL0 = "select * from offers where id=" . $db->qstr($tgroup);
		             $res0 = $db->Execute($sSQL0);
		             //update store data with existing value 
		             if ($res0) $tostore .= $res0->fields[2];
					 
			         $sSQL = "insert into offers (id,data) values (" . $db->qstr($tgroup) . 
                             "," . $db->qstr($tostore) . ")"; 
				     $sSQL1 = "update offers set data=" . $db->qstr($tostore) .
				              " where id=" . $db->qstr($tgroup);	   
				     break;
		    //delete current dir data (last delete the root record)		   
		    case 3 : $sSQL = "delete from offers where id=" . $db->qstr(ext2ascii($g));
				     break;			
		    case 4 : $sSQL = "delete from offers where id=" . $db->qstr($tgroup);
				     break;							 	   
	      }
	      //print $sSQL;
		  $res = $db->Execute($sSQL);
		
		  if ($db->Affected_Rows()) 
		    setInfo($db->Affected_Rows()." rows affected !");
		  else {
	        //print $sSQL1;
		    $res = $db->Execute($sSQL1);		  
		    if ($db->Affected_Rows()) 
		      setInfo($db->Affected_Rows()." rows updated !");		  
		  }
   }	
   
   function reset_db() {
        $db = GetGlobal('db');

        //delete table if exist
  	    $sSQL = "drop table if exists offers";
        $db->Execute($sSQL);
		$sSQL = "create table offers " .
                    "(" .
	                "recid integer auto_increment primary key," .
	                "id varchar(64)," .
	                "data text," .
	                "UNIQUE (id)" .												
                    ")";
        $db->Execute($sSQL);   
			
		setInfo(" Reset successfully!");
    }  
	
	function exportxt() {
       $db = GetGlobal('db');
	   
	   $file = paramload('SHELL','prpath') . paramload('SHELL','dbpath') . "offers.txt";

       $sSQL = "select * from offers";
	   $table = $db->Execute($sSQL);	
	
	   if ($table)  {
	   
	     //read data
         $i=1;
		 $textdata = null;
         while(!$table->EOF) {

          $textdata .= $table->fields[1] . "<!>" .
		               $table->fields[2] . "\n";

          //print_r($table->fields);
	      $table->MoveNext();
		  $i+=1;
	     }		   
	      
		 //write data
		 $fd = @fopen( $file, "w" );
         if ($fd) {
		   fwrite($fd, $textdata);
		   fclose($fd);		
		  
	       return (true);		     
         }
		 else
		   return (false); 
	   }
	   
	   return (false);
	}	
	
	function importxt($reset=true) {
       $db = GetGlobal('db');

	   //read data	   	
	   $lines = file(paramload('SHELL','prpath') . paramload('SHELL','dbpath') . "offers.txt");
	   
       if ($lines) {
	   
	     if ($reset) $this->reset_db();
		 
		 foreach ($lines as $recnum=>$rec) {
		   $recfields = explode("<!>",$rec);
		   
           $sSQL = "insert into offers (id,data) values (" . $db->qstr($recfields[0]) . 
                   "," . $db->qstr($recfields[1]) . ")"; 		 
				   
		   $res = $db->Execute($sSQL);				   
		   
		 }
		 if ($db->Affected_Rows()) setInfo($db->Affected_Rows()." rows affected !");		 
		  
	     return (true);		     
       }
	   else
		 return (false); 	
	}	
	
	function browse($packdata,$view='') {
	  
	   $data = explode("||",$packdata);
	
	   switch ($view) {
	       case 'preview' : //highlight preview
		                    $out = $this->previewhl($data[0],$data[1]);
		                    break;
	   
           case 2003 : //highlighs view
					   $out = $this->viewhighlight($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9]);					   
					   break;
           case 2004 : //highlighs view 2
					   $out = $this->viewhighlight_vertical($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9]);					   
 	                   break;	
		   default   :			   								   
           case 2005 : //highlighs view 3
					   $out = $this->viewhighlight_horizontal($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9]);					   
		               break;		   
	   }
	   
	   return ($out);
	}
	
	function previewhl($id,$title) {
	   
	   $image = "<img src=\"" . $id . "\" width=\"100\" height=\"75\" border=\"0\" alt=\"". $title . "\">";
	
	   $data[] = seturl("t=hl&a=&g=$title",$image);//$id;
	   $datt[] = "left;20%";
	   $data[] = "<B><H3>" . seturl("t=hl&a=&g=$title",$title) . "</H3></B>"; 
	   $datt[] = "left;60%;middle;";// . ";" . $this->star . ";";
	   $data[] = "&nbsp;";
	   $datt[] = "center;20%;middle;;" . $this->star . ";";
	   
	   $myarticle = new window('',$data,$datt);
	   
       if (GetReq('a') == $title) 
	     $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::") . "<hr>";	   
	   else		   
	     $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::") . "<hr>";
	   
	   unset ($data);
	   unset ($datt);

	   return ($out);	   	   
	} 
	
    function viewhighlight($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1) {
   	
		//get current product view 	
	   $pview = $this->view;//GetSessionParam("PViewStyle"); //get saved view from products dpc !?	
	   	
	   $gr = urlencode($group);
	   $ar = urlencode($title);
	   
	   $link = seturl("t=$pview&a=$ar&g=$gr&p=" ,$title);

	   $data[] = seturl("t=$pview&a=$ar&g=$gr&p=" , 
	                    "<img src=\"" . $photo . "\" width=\"100\" height=\"75\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" );
	   //"<A href=\"$PHP_SELF?t=$this->view&a=$ar&g=$gr&p=\">" . //$plink .
	   //          "<img src=\"" . $photo . "\" width=\"100\" height=\"75\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";
	   $attr[] = "left;10%";
	   
	   $data[] = "<B>$link</B><br>" . $descr . "<B>";
	   $attr[] = "left;50%;middle";
	   
	   $data[] = /*localize('_PRICE',getlocal()) . " :" . */ "<B>" . str_replace(".",",",$price) . $this->moneysymbol . "</B>";	  
	   $attr[] = "center;20%;middle;;" . $this->star . ";";				  

	   //$data[] = dpc_extensions("$id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;",$group,$page);  
	   $data[] = GetGlobal('controller')->calldpc_method("metacache.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;+$group+$page",1) .
	             GetGlobal('controller')->calldpc_method("cart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;+$group+$page",1) .	   
	             GetGlobal('controller')->calldpc_method("neworder.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;+$group+$page",1);	   
	   $attr[] = "center;20%;middle";		   

	    
	   $myarticle = new window('',$data,$attr);
	   $out = $myarticle->render("center::100%::0::group_article_high::left::0::0::"). "<hr>";
	   unset ($data);
	   unset ($attr);

	   return ($out);
    }		
	
    function viewhighlight_vertical($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1) {
	   	
		//get current product view 		
	   $pview = $this->view;//GetSessionParam("PViewStyle");	//get saved view from products dpc !?	
	   $gr = urlencode($group);
	   $ar = urlencode($title);
	   
	   $description = $title . "\n". $descr . "\n" . str_replace(".",",",$price) . $this->moneysymbol;
	   	   
	   //$link = "<A href=\"$PHP_SELF?t=$this->view&a=$ar&g=$gr&p=\">";			  

	   $data[] = seturl("t=$pview&a=$ar&g=$gr&p=" , 
	                    "<img src=\"" . $photo . "\" width=\"145\" height=\"95\" border=\"0\" alt=\"". $description . "\">" );
	   $attr[] = "left";

	   $myarticle = new window('',$data,$attr);
	   $out = $myarticle->render("center::100%::0::group_article_high::left::0::0::");
	   unset ($data);
	   unset ($attr);

	   return ($out);
    }		
	
    function viewhighlight_horizontal($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1) {
	  
		//get current product view 	   	
	   $pview = $this->view;//GetSessionParam("PViewStyle");	//get saved view from products dpc !?		
	   $gr = urlencode($group);
	   $ar = urlencode($title);
	   
	   $description = $title . "\n". $descr . "\n" . str_replace(".",",",$price) . $this->moneysymbol;
	   	   
	   //$link = "<A href=\"$PHP_SELF?t=$this->view&a=$ar&g=$gr&p=\">";			  

	   //$data[] 
	   $out = seturl("t=$pview&a=$ar&g=$gr&p=" , 
	                 "<img src=\"" . $photo . "\" width=\"80\" height=\"55\" border=\"0\" alt=\"". $description . "\">" );

	   return ($out);
    }		

	function headtitle() {
	}	 

};
}
}
else die("DATABASE DPC REQUIRED!");
?>