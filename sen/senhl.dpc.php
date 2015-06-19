<?php
if (defined("SENPRODUCTS_DPC")) {

$__DPCSEC['SENHL_DPC']='2;2;2;2;2;2;2;2;9';
$__DPCSEC['ADMINHL_']='2;1;1;1;1;1;1;2;9';

if ((!defined("SENHL_DPC")) && (seclevel('SENHL_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SENHL_DPC",true);

$__DPC['SENHL_DPC'] = 'senhl';

$__EVENTS['SENHL_DPC'][0]='senvp';

$__ACTIONS['SENHL_DPC'][0]='shl';
$__ACTIONS['SENHL_DPC'][1]='senvp';

//$__DPCATTR['SENHL_DPC']['senvp'] = 'senvp,0,0,0,0,0,0,1,0,1'; no need defined in senproducts

$__LOCALE['SENHL_DPC'][0]='SENHL_DPC;Offers;ÐñïóöïñÝò';
$__LOCALE['SENHL_DPC'][1]='_PRODOFFER;Offers for;ÐñïóöïñÝò ãéá';

$__DPCPROC['SENHL_DPC'] = 1;

class senhl extends senproducts {

	 var $moneysymbol;	 
	 var $splash;
	 var $star;
	 var $_CONTINUE; //continue actions in shell
	 
	 var $dir_res_path;
	 var $dir_res_type;
	 var $grx;
	 var $view;

     function senhl() {
	    $GRX = GetGlobal('GRX');	
	    $UserSecID = GetGlobal('UserSecID'); 
		
		senproducts::senproducts();
		
		$this->_CONTINUE = 1;
		
		//NO NEED ... INHERIT FROM SENPRODUCTS	!!!!!	???????  !!!!!!
        $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);		
		
	    //SELECT CRITERIA BASED ON SELECTED LANGUAGE
		//SELECT STATEMENT OVERWRITE
		$myselect = (paramload('SENHL','select'));		
	    $current_language = getlocal(); //echo $current_language,'>>>>';
	    $myselect = paramload('SENHL','select'.$current_language); //????????		
		
        if ($myselect) $this->select = $myselect;
	 
		//echo '>>>>>',$this->view; //NOT WORKKING !!!!!
 	    $viewperclient = arrayload('SENPRODUCTS','viewperclient');			
	    $this->view  = $viewperclient[$this->userLevelID];
	    if (!$this->view) $this->view = paramload('SENPRODUCTS','dview');
		//echo '>>>>>',$this->view;		

		
        $this->moneysymbol = "&" . paramload('CART','cursymbol') . ";";		 
		
        if ($GRX) {
		  $this->splash = loadTheme('splash','',1);
		  $this->star = loadTheme('star','',1);		  
  		  $this->dir_res_path = GetGlobal('controller')->calldpc_var('senptree.resourcepath');
		  $this->dir_res_type = GetGlobal('controller')->calldpc_var('senptree.restype');		  
		  $this->grx = 1;
		}					  		
	 }
	 
     function event($sAction) {

       switch($sAction) {
		 case 1      : 
		 case 2      : 
		 case 3      : 
		 case 4      : 
         case 5      : 
		 case 6      : 
         case 7      : 
		 case 8      : 
		 case 9      : 
		 case 'senvp': break;	 											   
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
		 case 9           : 
		 case 'senvp'     : $out = $this->render('2003',$g);
		                    break;
		 case 'shl'       :	$out = $this->preview($g); 
							$this->_CONTINUE = 0;	 
		                    break;  	 
	   }

	   return ($out);
	}
	
	function is_highlighted($codcode) {
	
	   $sSQL = "select POLHSH from " . $this->T_offers . " where codcode='" . $codcode . "'";
	   $result = $this->sen_db->Execute($sSQL);	
	   //echo $sSQL;
	   //print_r($result->fields);
	   $ret = $result->fields[0];
	   return ($ret);
	}
	
	function read_hls($g=null) {
	   
	   if (strlen(trim($g))>0) {
	   
	     if ($g{0}=="@") { //group must start with _ to mean an other root tree
		   $root_group = str_replace("@","",$g); //throw _
		   $depth = 0; //no depth for this type of root tree ?????
		 }
		 else {
	       $group = explode("^",$g); //print_r($group);
	       $mg = count($group);
	       $depth = ($mg ? $mg : 0);
		 }  
	   }
	   else
	     $depth = 0;	

		 	      
       $sSQL = "SELECT " . $this->select . " FROM " . $this->T_offers;//"PANIK_VIEW_EIDH_OFFERS";
	   $sSQL .= " WHERE ";
	   
	   switch ($depth) {
	       case 4 : $sSQL .= "CTGLEVEL5='" . $group[3] . "' AND ";
	       case 3 : $sSQL .= "CTGLEVEL4='" . $group[2] . "' AND ";
		   case 2 : $sSQL .= "CTGLEVEL3='" . $group[1] . "' AND ";
		   case 1 : $sSQL .= "CTGLEVEL2='" . $group[0] . "' AND ";
		   default: 
		            if ($root_group) //field = nai
					  $sSQL .= $root_group . "='ÍÁÉ' " . " AND ";//NAI\
					
				    $sSQL .= "CTGLEVEL1='ÊÁÔÇÃÏÑÉÅÓ ÅÉÄÙÍ'";
					/*NO NEED !!!!!!!   		   
					else //default = KATHGORIES EIDVN	 
		              $sSQL .= "(PROSF_EIDON='ÍÁÉ' OR PROSF_WEEK='ÍÁÉ' OR PROSF_MONTH='ÍÁÉ') " .
					           "AND CTGLEVEL1='ÊÁÔÇÃÏÑÉÅÓ ÅÉÄÙÍ'";
					  
					//if no admin  
					if (!seclevel('SENPTREEADMIN_',$this->userLevelID)) {  
					  $sSQL .= " AND CTGLEVEL2 NOT LIKE 'LOST+FOUND'"; 
					}
					
                    if (trim($this->criteriaAND[0])!=null) //first array obj not null 
	                  $sSQL .=  " AND " . $this->choiceSQL($this->criteriaAND,'AND','=','\'ÅÌÖÁÍÉÆÅÔÁÉ\'',1);	*/
	   }	 
	   //$sSQL .= " CODCODE='50000'";  	   

	   //echo "       OFFERS:",$sSQL;
	
	   //cache queries //?????????????????? AT LOGIN/LOGOUT
	   if ($this->cachequeries) $result = $this->sen_db->CacheExecute($this->cachetime,$sSQL);
                           else $result = $this->sen_db->Execute($sSQL);			   
	
	   					   
	   if ($result) $ret = (array) $this->prepare($result,1,11);
	   //print_r($result->fields);   
	   
	   return ($ret);
	}	
	
	//overwritten due to the diff select
    function prepare($table,$nopreprice=null) {
	   
	   $a = GetReq('a');   

       $i=0;

       //select array key
	   switch ($a) {
		   case 1 : $index = 0; break;
		   case 2 : $index = 1; break;
		   case 3 : $index = 8; break;  
		   default : $index = 0;
	   }
	   
       while(!$table->EOF) {

		  $key = $table->fields[$index];

          $dataout[$key] = $table->fields[0] . ";" . 
                       $this->preparestr($table->fields[1]) . ";" .
			           $table->fields[2] . ";" .
			           $table->fields[3] . ";" .
			           $this->prepare_group($table->fields[11],
			                                $table->fields[12],
			                                $table->fields[13],
			                                $table->fields[14]) . ";" .
			           $table->fields[5] . ";" .
			           $this->preparestr($table->fields[6]) . ";" .
			           //$this->resourcepicpath . $table->fields[7] . $this->restype . ";" .
					   (file_exists($this->abspicpath . $table->fields[7] . $this->restype) ? 
					     $this->resourcepicpath . $table->fields[7] . $this->restype : 
						 $this->resourcepicpath . $this->nopic . $this->restype) 
					   . ";" .
			           $this->prepare_price(floatval($table->fields[8]),$nopreprice) . ";" .//price
					   $this->prepare_price(floatval($table->fields[9]),$nopreprice) . ";" .//old price
			           $table->fields[10] . ";" .
					   $table->fields[15] . ";" .//$this->lookup($this->T_boxtype,$table->fields[14],'Êùäéêüò',1) . ";" .
					   $table->fields[16] . ";" .
					   $table->fields[17] . ";" .
					   $table->fields[18] . ";" .
					   $table->fields[19] . ";" .
					   $table->fields[20];
					   
          //print_r($table->fields);
	      $table->MoveNext();
		  $i+=1;
	   }
	   //print_r($dataout);

       $this->numResult = $i;
       //DEBUG
       if (seclevel('_DEBUG',$this->userLevelID)) echo "Total Rec:" , $this->numResult;

	   //print_r($dataout);
	   return ($dataout);
	   
	}	
	
	function read_hls_tree() {
	
           $sSQL = "SELECT " . "CTGLEVEL2,CTGLEVEL3,CTGLEVEL4,CTGLEVEL5" /*. $this->select*/ . " FROM " . $this->T_offers;		 
		   $sSQL .= " WHERE ";
           $sSQL .= "(PROSF_EIDON='ÍÁÉ' OR PROSF_WEEK='ÍÁÉ' OR PROSF_MONTH='ÍÁÉ') ";// .
					          // "AND CTGLEVEL1='ÊÁÔÇÃÏÑÉÅÓ ÅÉÄÙÍ'";
		
		   /*no need!!!!!				  
		   //if no admin  
		   if (!seclevel('SENPTREEADMIN_',$this->userLevelID)) {  
			  $sSQL .= " AND CTGLEVEL2 NOT LIKE 'LOST+FOUND'"; 
		   }
		   		   
           if (trim($this->criteriaAND[0])!=null) //first array obj not null 
	          $sSQL .=  " AND " . $this->choiceSQL($this->criteriaAND,'AND','=','\'ÅÌÖÁÍÉÆÅÔÁÉ\'',1);			   
		   */	  
	       //echo 'OFFERSPREVIEW:'.$sSQL; 
	       //cache queries 
	       if ($this->cachequeries) $table = $this->sen_db->CacheExecute($this->cachetime,$sSQL);
                               else $table = $this->sen_db->Execute($sSQL);	
				   
           $i=1;		 
           while(!$table->EOF) {
		     $tbl[] = $this->set_tree_name(array(0=>$table->fields[0],1=>$table->fields[1],2=>$table->fields[2],3=>$table->fields[3])) . ";" . 
			          $this->set_tree_path(array(0=>$table->fields[0],1=>$table->fields[1],2=>$table->fields[2],3=>$table->fields[3])) . ";";
	         $table->MoveNext();
		     $i+=1;			 
		   }
	
		   $htbl = $this->distinct($tbl);		   
		   //echo $sSQL;		 
		   //print_r($htbl);	
		   return ($htbl);
	}	 

    function render($vstyle=2003,$group) {		
	     $g = GetReq('g');	
		 	 
	     $this->t_hls = new ktimer;
	     $this->t_hls->start('hls');	
	 
         //$hlres = getcache(urlencode($group),"shl","read_hls",$this); 
		 $a = urlencode($group);
		 $b = 'shl';
		 $c = 'senhl.read_hls';
		 $var = array(0=>&$group);
         $hlres = GetGlobal('controller')->calldpc_method_use_pointers('cache.getcache_method_use_pointers',
		                                      array(0=>&$a,1=>&$b,2=>&$c,3=>&$var));	
											  
	   
	     $this->t_hls->stop('hls');
	     if (paramload('SHELL','debug')) echo "<br>hls " , $this->t_hls->value('hls'); 												  	 
		 
      			 
         if ($hlres) {
             $mydbrowser = new browse($hlres,null,null,null,'a,b');	   
	         $out2 = $mydbrowser->render($vstyle,0,$this); 
  	         unset ($mydbrowser);
			 
			 if ($group==$g) { //standart offer window per category
			   $hlwin = new window(localize('_PRODOFFER',getlocal())." ".str_replace('_','',$this->getgroup()),$out2);
			   $out = $hlwin->render();
			   unset ($hlwin); 
			 }
			 else //side bars of offers appear title when $g!=group(predefined)
			   $out = $out2;
		 }
		 
		 return ($out);
     }
	 
	function set_tree_path($array_path) {
	  
	    //$ret = implode("^",$array_path);
		$ret = null;
		$max = count($array_path);
		foreach ($array_path as $id=>$path) {
		  
		  if (trim($path)) {
		    if ($id==0) $ret .= $path; 
			       else $ret .= "^" . $path;
		  }    
		}
		
		return $ret;
	}	 
	
	function set_tree_name($array_path) {
	  
	    //$ret = implode("^",$array_path);
		$ret = null;
		$max = count($array_path);
		foreach ($array_path as $id=>$path) {
		  
		  if (trim($path)) $ret=$path;   
		}
		
		return $ret;
	}		
	 
	 function preview($group=null) {
		 
		 if (!$group) { //view main menu
		   
	       $this->t_hls = new ktimer;
	       $this->t_hls->start('hls_tree');	
	 
		   /*$a = 'hlstree';//default
		   $b = 'shl';
		   $c = 'senhl.read_hls_tree';
           $htbl = calldpc_method_use_pointers('cache.getcache_method_use_pointers',
		                                      array(0=>&$a,1=>&$b,2=>&$c));	
											  
	       */
		   //still valid...
           $htbl = GetGlobal('controller')->calldpc_method('cache.getcache_method use hlstree+shl+senhl.read_hls_tree'); 		   
		   
	       $this->t_hls->stop('hls_tree');
	       if (paramload('SHELL','debug')) echo "<br>hls tree" , $this->t_hls->value('hls_tree');
		 
           $out = setNavigator(localize('SENHL_DPC',getlocal()));		 
      			 
           if ($htbl) {		   
             $mydbrowser = new browse($htbl,localize('SENHL_DPC',getlocal()));	   
	         $out .= $mydbrowser->render('preview',10,$this,1,1,1); 
  	         unset ($mydbrowser);
		   }	 
		 }
		 else { //view selected highlights

		   $mainhlurl = seturl("t=shl",localize('SENHL_DPC',getlocal())); 
		   $out = setNavigator($mainhlurl,$hlsel);		 
		   //if ($hlsel) 
		   $out .= $this->render('2003',$group);		  	 
		 }
		 
		 //frontpage like out
		 $hfp = new frontpage('senhl',0);
		 $fpout = $hfp->render($out);
		 unset($hfp);
		 
		 return ($fpout);     
	 }
	 
	 function preparef($field) {
	 
	    $hl_code = explode(";",$field);
		$picname = $hl_code[0];
		//echo $picname;
		
        $ret = (file_exists($this->abspicpath . $picname . $this->restype) ? $this->resourcepicpath . $picname . $this->restype : $this->resourcepicpath . $this->nopic . $this->restype);	
		
		//return first product code image file
		return ($ret);
	 }	
	
	function browse($packdata,$view='') {
	  
	   $data = explode("||",$packdata);
	
	   switch ($view) {
	       case 'preview' : //highlight preview
		                    $out = $this->previewhl($data[0],$data[1]);
		                    break;
	   
           case 2003 : //highlighs view
					   $out = $this->viewhighlight($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],$data[10],$data[11],$data[12],$data[13],$data[14],$data[15],$data[16]);					   
					   break;
           case 2004 : //highlighs view 2
					   $out = $this->viewhighlight_vertical($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],$data[10]);					   
 	                   break;	
		   default   :			   								   
           case 2005 : //highlighs view 3
					   $out = $this->viewhighlight_horizontal($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],$data[10]);					   
		               break;		   
	   }
	   
	   return ($out);
	}
	
	function previewhl($id,$title) {
	
	   if ($this->grx) {
	     $image = "<img src=\"" . $this->dir_res_path . $id . $this->dir_res_type . 
	              "\" width=\"100\" height=\"75\" border=\"0\" alt=\"". $id . "\">";
	     $data[] = seturl("t=shl&a=&g=$title",$image);//$id;
	   }
	   else
	     $data[] = "&nbsp;";
		 
	   $datt[] = "left;20%";
	   $data[] = "<B><H3>" . seturl("t=shl&a=&g=$title",$id) . "</H3></B>"; 
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
	
    function viewhighlight($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$oldprice=0,$quant=1,$uninameA=null,$uninameB=null,$sxA=null,$sxB=null,$ypoA=null,$ypoB=null) {
   	
		//get current product view 	
	   $pview = 'senvp';//$this->view;//GetSessionParam("PViewStyle"); //get saved view from products dpc !?	

	   $gr = urlencode($group); //echo $group,"<br>";
	   $ar = urlencode($title);
	   
	   $link = seturl("t=$pview&a=$ar&g=$gr&p=" ,$title);

	   $data[] = seturl("t=$pview&a=$ar&g=$gr&p=" , 
	                    "<img src=\"" . $photo . "\" width=\"100\" height=\"75\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" );
	   //"<A href=\"$PHP_SELF?t=$this->view&a=$ar&g=$gr&p=\">" . //$plink .
	   //          "<img src=\"" . $photo . "\" width=\"100\" height=\"75\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";
	   $attr[] = "left;10%";
	   
	   $data[] = "<B>$link</B><br>" . $descr . "<B>";
	   $attr[] = "left;50%;middle";
	   
	   $delprice = "<del>" . $this->prepare_price($oldprice) . $this->moneysymbol . "</del><br/>";
	   $data[] = $delprice . "<B>" . $price . $this->moneysymbol . "</B>";	  
	   $attr[] = "center;20%;middle;;" . $this->star . ";";				  

	   //symbols
	   $data[] = GetGlobal('controller')->calldpc_method("metacache.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$uninameA;$uninameB;$sxA;$sxB;$ypoA;$ypoB+$group+$page",1) .
	             GetGlobal('controller')->calldpc_method("sencart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$uninameA;$uninameB;$sxA;$sxB;$ypoA;$ypoB+$group+$page",1) .	   
	             GetGlobal('controller')->calldpc_method("neworder.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$uninameA;$uninameB;$sxA;$sxB;$ypoA;$ypoB+$group+$page",1);	   
	   $attr[] = "center;20%;middle";		   		 

	    
	   $myarticle = new window('',$data,$attr);
	   $out = $myarticle->render("center::100%::0::group_article_high::left::0::0::"). "<hr>";
	   unset ($data);
	   unset ($attr);

	   return ($out);
    }		
	
    function viewhighlight_vertical($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$oldprice,$quant=1) {
	   	
		//get current product view 		
	   $pview = 'senvp';//$this->view;//GetSessionParam("PViewStyle");	//get saved view from products dpc !?	
	   $gr = urlencode($group);
	   $ar = urlencode($title);
	   
	   $description = $title . "\n". $descr . "\n" . $price . $this->moneysymbol;
	   	   
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
	
    function viewhighlight_horizontal($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$oldprice,$quant=1) {
	  
		//get current product view 	   	
	   $pview = 'senvp';//$this->view;//GetSessionParam("PViewStyle");	//get saved view from products dpc !?		
	   $gr = urlencode($group);
	   $ar = urlencode($title);
	   
	   $description = $title . "\n". $descr . "\n" . $price . $this->moneysymbol;
	   	   
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
else die("SENPRODUCTS DPC REQUIRED!");
?>