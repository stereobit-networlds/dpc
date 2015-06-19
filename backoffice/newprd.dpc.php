<?php
//if (defined("PRODUCTS_DPC")) {

$__DPCSEC['NEWPRD_DPC']='2;1;2;2;2;2;2;2;9';

if ((!defined("NEWPRD_DPC")) && (seclevel('NEWPRD_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("NEWPRD_DPC",true);

$__DPC['NEWPRD_DPC'] = 'new_products';

$__EVENTS['NEWPRD_DPC'][0]= "newprd";
/*$__EVENTS['NEWPRD_DPC'][1]=1;
$__EVENTS['NEWPRD_DPC'][2]=2;
$__EVENTS['NEWPRD_DPC'][3]=3;
$__EVENTS['NEWPRD_DPC'][4]=4;
$__EVENTS['NEWPRD_DPC'][5]=5;
$__EVENTS['NEWPRD_DPC'][6]=6;
$__EVENTS['NEWPRD_DPC'][7]=7;
$__EVENTS['NEWPRD_DPC'][8]=8;
$__EVENTS['NEWPRD_DPC'][9]=9;*/

$__ACTIONS['NEWPRD_DPC'][0]="newprd";
/*$__ACTIONS['NEWPRD_DPC'][1]=1;
$__ACTIONS['NEWPRD_DPC'][2]=2;
$__ACTIONS['NEWPRD_DPC'][3]=3;
$__ACTIONS['NEWPRD_DPC'][4]=4;
$__ACTIONS['NEWPRD_DPC'][5]=5;
$__ACTIONS['NEWPRD_DPC'][6]=6;
$__ACTIONS['NEWPRD_DPC'][7]=7;
$__ACTIONS['NEWPRD_DPC'][8]=8;
$__ACTIONS['NEWPRD_DPC'][9]=9;*/

$__LOCALE['NEWPRD_DPC'][0]='NEWPRD_DPC;New Products;Νέα Προιόντα';

$__PARSECOM['NEWPRD_DPC']['render']='_NEWPRODUCTS_';

class new_products extends products {

	var $userLevelID;
	var $notavail;
	var $view;
	var $resourcepicpath;
	
	var $datebefore;
	
	var $select;
	var $criteria;
	var $criteriaOR;
	var $criteriaAND;	
	
	var $abspicpath;
	var $restype;
	var $nopic;	

	function new_products() {
	   $UserSecID = GetGlobal('UserSecID');
	   $GRX = GetGlobal('GRX');	   
	   
       backoffice::backoffice();	   

	   $this->userLevelID = decode($UserSecID);
	   
 	   $viewperclient = arrayload('PRODUCTS','viewperclient');			
	   $this->view  = $viewperclient[$this->userLevelID];
	   if (!$this->view) $this->view = paramload('PRODUCTS','dview');
	   
       $this->directory_mark = paramload('DIRECTORY','dirmark');	
	   //$this->resourcepicpath = paramload('SHELL','urlbase') . paramload('PRODUCTS','dbres');	   
       $ip = $_SERVER['HTTP_HOST'];
       $pr = paramload('SHELL','protocol');		   
	   $this->resourcepicpath = $pr . $ip . paramload('PRODUCTS','dbres');		      	    
	   
	   $daysbefore = paramload('NEW_PRODUCTS','backdays');
	   
	   $this->datebefore = date("d/m/Y", mktime(0,0,0, date(m), date(d)-$daysbefore,date(Y))); 
	   //print $this->datebefore;
	   
	   if ($GRX) {
         $this->notavail = loadTheme('notavail',localize('_NOTAVAL',getlocal()));	   
	   }
	   else {
         $this->notavail = localize('_NOTAVAL',getlocal());	   
	   } 
	   
	   $this->select = paramload('NEW_PRODUCTS','select');	    
	   $this->criteria = explode("+",paramload('NEW_PRODUCTS','where'));//print paramload('PRODUCTS','where');
	   $this->criteriaOR = explode(",",$this->criteria[0]); //print_r($this->criteriaOR);	   
	   $this->criteriaAND = explode(",",$this->criteria[1]); //print_r($this->criteriaAND);  	   
	   
	   //get abs path 
	   $this->abspicpath = paramload('SHELL','prpath') . paramload('SHELL','picpath')  . paramload('PRODUCTS','dbres');
	   $this->restype = paramload('PRODUCTS','restype');
	   $this->nopic = paramload('PRODUCTS','nopic');	   
	}	

	function event($evn) {      
	   	  
	   switch ($evn) {	
	     case "newprd"  : //$res = $this->read(); 
		                  break;				 	      	 
	   }
	}
	
	function action($act) {

	   $out = setNavigator(localize('NEWPRD_DPC',getlocal()));	
	  
	   $x = new applet;
	   $out .= $x->a_proscroll();
	   $out .= $x->a_goo('/images/camerondiaz.jpg');
	   unset($x);
	
	   switch ($act) {	
	     case "newprd"  : $out .= $this->render(); break;				 	      	 
	   }	
	   
	   return ($out); 
	}	

    function render($vstyle='newprd') { 
	
        //$this->retable = getcache("new_products","prd","read",$this);	
	    $id = 'new_products';
	    $ext = 'prd';
	    $thisclass = 'read'; 
	    $this->retable = GetGlobal('controller')->calldpc_method_use_pointers('cache.getcache',  
			                                          array(0=>&$id,
									                        1=>&$ext,
											                2=>&$thisclass,
												            3=>&$this)  
										              );
													  			
        if ($this->retable) {
		         if ($vstyle=='newprd')
			       $mydbrowser = new browse($this->retable,localize('NEWPRD_DPC',getlocal()));	   
				 else
                   $mydbrowser = new browse($this->retable,"");	
				   				 
	             $out = $mydbrowser->render($vstyle,20,$this,1,0,0,0,1,1,1,1); 
  	             unset ($mydbrowser);	   
		}
		else {
			     $swin = new window('',localize('_EMPTYDIR',getlocal()));
	             $out = $swin->render();
	             unset ($swin);	
		}			

        return ($out);
    }

	//overwriten 
    function read() {
	   
	   $this->get_MetaTable();
	   
           /*$sSQL = "SELECT Κωδικός,Περιγραφή,'path','dba','group','0',Παρατήρηση,Κωδικός,[Τιμή λιανικής],'1'" .
		       ",Οικογένεια,Υποοικογένεια,Ομάδα,Κατηγορία,[Τίτλος Μ_Μέτρ],[Ειδ_κωδικός] from " .			   
		           $this->T_products;*/
           $sSQL = "SELECT " . $this->select . " FROM " . $this->T_products;			   
		   
		   //$sSQL .= " WHERE [Κωδ_Προμηθευτή]='FU-001'"; //based on supplier
		   $sSQL .= " WHERE [Ειδών.Ημ_τελ_αγοράς]>=#" . $this->datebefore . "#"; //01/01/03#)"; //based on dates
		   
		   //$sSQL .= " AND [Ειδών.Internet]=TRUE";      //<<<<<<<<<<<<<<<<<<<<<<
		   //check if AND criteria array exist
		   if ($this->criteriaAND[0]) $sSQL .=  " AND " .$this->choiceSQL($this->criteriaAND,'AND','=','TRUE');		   
		  
           //echo $sSQL;
		   
           //DEBUG
           //if (seclevel('_DEBUG',$this->userLevelID)) print $sSQL;

		   //cache queries 
		   if ($this->cachequeries) $result = $this->db_con->CacheExecute($this->cachetime,$sSQL);
                               else $result = $this->db_con->Execute($sSQL);

           //print_r($result);							   
		   $this->retable = (array) $this->prepare($result);
		   
	       //print_r($this->retable);

	   return ($this->retable);
	}
   
	function browse($packdata,$view='') {
	   $p = GetReq('p');
	  
	   $data = explode("||",$packdata);
	
	   switch ($view) {
					      case 1 : 
						  case 2 : 
						  case 3 :
						  case 4 :      
					      case 5 : 
						  case 6 : 
                          case 7 : 
						  case 8 : 
                          case 9 : 
						  case 'newprd': $out = $this->viewnewproducts($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9],$data[10],$data[11]);
						              break; 
									  
						  case 2051 : $out = $this->viewnewproducts_vertical($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9],$data[10],$data[11]);
						              break;
									  
						  case 2052 : $out = $this->viewnewproducts_horizontal($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9],$data[10],$data[11]);
						              break;									  									  		   
	   }
	   
	   return ($out);
	}     



    function viewnewproducts($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1,$boxtype='',$barcode=null) {	   	
	   $p = GetReq('p');
	   $a = GetReq('a');	   

	   $gr = urlencode($group);
	   $ar = urlencode($title);	
	   	   
       if ($a == $title) {

	   $link = seturl("t=$this->view&a=$ar&g=$gr&p=$p" , $title);
	   
       if (iniload('JAVASCRIPT')) {		
  	         $plink = "<A href=\"#\" ";	   
	         //call javascript for opening a new browser win for the img		   
	         //$params = $photo . ";Image;width=300,height=200;";
	         $params = "$photo;280;340;<B>$title</B><br>$descr;";
			 
			 $js = new jscript;
	         $plink .= $js->JS_function("js_popimage",$params); 
			 unset ($js);

	         $plink .= ">"; 
	   }
	   else
             $plink = "<A href=\"$photo\">";

	   $data[] = $chkbox . $plink . "<img src=\"" . $photo . "\" width=\"100\" height=\"75\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";
	   $attr[] = "left;10%";
	   
	   $data[] = "<B>$title<br></B>" . $descr . "<B>";
	   $attr[] = "left;50%";
	   
	   
       $data[] = "$id<br>" .
		         localize('_PRICE',getlocal()) . " :<B>" . $price . $this->moneysymbol . "</B>" .
		         "<br>" . localize('_BOXTYPE',getlocal()) . " :" . $boxtype .
				 "<br>" . "barcode :" . $barcode;	 	  
	   $attr[] = "center;20%";

	   if ($price>0) {		  	
	      $data[] = GetGlobal('controller')->calldpc_method("metacache.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1) .
	                GetGlobal('controller')->calldpc_method("cart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1) .	   
	                GetGlobal('controller')->calldpc_method("neworder.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1);			  
	      $attr[] = "center;20%";			  		
	   }		  
       else {
	      $data[] = $this->notavail;//localize('_NOTAVAL',getlocal()); 
	      $attr[] = "center;20%";		  
	   }	   
	   
	   $myarticle = new window('',$data,$attr);
	   $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::") . "<hr>";
	   unset ($data);
	   unset ($attr);

	   return ($out);
	   
       }
	   else {	

	   $link = seturl("t=$this->view&a=$ar&g=$gr&p=$p" , $title);
	   
       if (iniload('JAVASCRIPT')) {		
  	         $plink = "<A href=\"#\" ";	   
	         //call javascript for opening a new browser win for the img		   
	         //$params = $photo . ";Image;width=300,height=200;";
 	         $params = "$photo;280;340;<B>$title</B><br>$descr;";

			 $js = new jscript;
	         $plink .= $js->JS_function("js_popimage",$params); 
			 unset ($js);

	         $plink .= ">"; 
	   }
	   else
             $plink = "<A href=\"$photo\">";

	   $data[] = $chkbox . $plink . "<img src=\"" . $photo . "\" width=\"100\" height=\"75\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";
	   $attr[] = "left;10%";
	   
	   $data[] = "<B>$title<br></B>" . $descr . "<B>";
	   $attr[] = "left;50%";
	   	   
	   $data[] = "$id<br>" .
		         localize('_PRICE',getlocal()) . " :<B>" . $price . $this->moneysymbol . "</B>" .
		      	 "<br>" . localize('_BOXTYPE',getlocal()) . " :" . $boxtype .
				 "<br>" . "barcode :" . $barcode;	  
	   $attr[] = "center;20%";
	   
	   if ($price>0) {		  			     		  
	      $data[] = GetGlobal('controller')->calldpc_method("metacache.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1) .
	                GetGlobal('controller')->calldpc_method("cart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1) .	   
	                GetGlobal('controller')->calldpc_method("neworder.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1);			  
	      $attr[] = "center;20%";			  		 		
	   }		  
       else {
	      $data[] = $this->notavail;//localize('_NOTAVAL',getlocal()); 
	      $attr[] = "center;20%";		  
	   }	   
	   
	   $myarticle = new window('',$data,$attr);
	   $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::") . "<hr>";
	   unset ($data);
	   unset ($attr);

	   return ($out);
    }	
	}	
	
	
    function viewnewproducts_vertical($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1) {
	   	
		//get current product view 		
	   $pview = $this->view;//GetSessionParam("PViewStyle");	//get saved view from products dpc !?	
	   $gr = urlencode($group);
	   $ar = urlencode($title);
	   
	   $description = $title . "\n". $descr . "\n" . $price . $this->moneysymbol;
	   	   
	   //$link = "<A href=\"$PHP_SELF?t=$this->view&a=$ar&g=$gr&p=\">";			  

	   $data[] = seturl("t=$pview&a=$ar&g=$gr&p=" , 
	                    "<img src=\"" . $photo . "\" width=\"145\" height=\"95\" border=\"0\" alt=\"". $description . "\">" );
	   $attr[] = "left";

	   $myarticle = new window('',$data,$attr);
	   $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::");
	   unset ($data);
	   unset ($attr);

	   return ($out);
    }		
	
    function viewnewproducts_horizontal($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1) {
	
	   static $meter;
	   
	   $meter+=1;
	  
		//get current product view 	   	
	   $pview = $this->view;//GetSessionParam("PViewStyle");	//get saved view from products dpc !?		
	   $gr = urlencode($group);
	   $ar = urlencode($title);
	   
	   $description = $title . "\n". $descr . "\n" . $price . $this->moneysymbol;
	   	   
	   //$link = "<A href=\"$PHP_SELF?t=$this->view&a=$ar&g=$gr&p=\">";			  

	   //$data[] 
	   $out = seturl("t=$pview&a=$ar&g=$gr&p=" , 
	                 "<img src=\"" . $photo . "\" width=\"80\" height=\"55\" border=\"0\" alt=\"". $description . "\">" );
		
	   //change line	
	   if ($meter>=5) {
	     $meter=0; 
		 $out.="<br>";
	   }				 

	   return ($out);
    }	


	function headtitle() {					 	
	}			
	
	
};
}
//}
//else die("PRODUCTS DPC REQUIRED!");
?>