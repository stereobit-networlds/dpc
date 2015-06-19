<?php
if (defined("SENPRODUCTS_DPC")) {

$__DPCSEC['SENPNEW_DPC']='2;1;1;1;2;2;2;2;9';

if ((!defined("SENPNEW_DPC")) && (seclevel('SENPNEW_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SENPNEW_DPC",true);

$__DPC['SENPNEW_DPC'] = 'sen_newproducts';

$__EVENTS['SENPNEW_DPC'][0]= "snewprd";

$__ACTIONS['SENPNEW_DPC'][0]="snewprd";

$__LOCALE['SENPNEW_DPC'][0]='SENPNEW_DPC;New Products;Νέα Προιόντα';

$__PARSECOM['SENPNEW_DPC']['render']='_SENNEWPRODUCTS_';

class sen_newproducts extends senproducts {

	var $userLevelID;
	var $datebefore;

	function sen_newproducts() {
	   $UserSecID = GetGlobal('UserSecID');    
	   
       senproducts::senproducts();	   

	   $this->userLevelID = decode($UserSecID);	      	    
	   
	   $daysbefore = paramload('SENPNEW','backdays');
	   $dateformat = paramload('SENPNEW','dateformat');	   
	   $this->datebefore = date($dateformat, mktime(0,0,0, date(m), date(d)-$daysbefore,date(Y))); 
	   //print $this->datebefore;   
	   //$this->datebefore = "2004-09-24";
	   //print $this->datebefore;   
	}	

	function event($evn) {      
	   	  
	   switch ($evn) {	
	     case "snewprd"  : //$res = $this->read(); 
		                  break;				 	      	 
	   }
	}
	
	function action($act) {

	   $out = setNavigator(localize('SENPNEW_DPC',getlocal()));	
	   
	  //APPLET TEST
	 /*  $x = new applet;
	   $out .= $x->a_proscroll();
	   $out .= $x->a_goo('/images/camerondiaz.jpg');
	   unset($x);*/
	
	   switch ($act) {	
	     case "snewprd"  : $out .= $this->render(); break;				 	      	 
	   }	
	   
	   return ($out); 
	}	

    function render($vstyle='snewprd') { 
	
        //$this->retable = getcache("senpnew","spn","read_products",$this);	
	    $id = 'senpnew';
	    $ext = 'sn' . $this->userLevelID;
	    $thisclass = 'read_products'; 
		
	    $this->retable = GetGlobal('controller')->calldpc_method_use_pointers('cache.getcache',  
			                                          array(0=>&$id,
									                        1=>&$ext,
											                2=>&$thisclass,
												            3=>&$this)  
										              );												  
		//$this->retable = $this->read_products();											  
        //print_r($this->retable);													  
													  			
        if ($this->retable) {
		         if ($vstyle=='snewprd')
			       $mydbrowser = new browse($this->retable,localize('SENPNEW_DPC',getlocal()));	   
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
    function read_products() {
	   
           $sSQL = "SELECT " . $this->select . " FROM " . $this->T_products;			   
		   $sSQL .= " WHERE SYS_INS>'" . $this->datebefore . "'"; //01/Mar/03')"; //based on dates
		   
		   //$sSQL .= " AND [Ειδών.Internet]=TRUE";      //<<<<<<<<<<<<<<<<<<<<<<
		   //check if AND criteria array exist
		   //if ($this->criteriaAND[0]) $sSQL .=  " AND " .$this->choiceSQL($this->criteriaAND,'AND','=','TRUE');		   
		  
           //echo "NEW PRODUCTS:",$sSQL;
		   
           //DEBUG
           //if (seclevel('_DEBUG',$this->userLevelID)) print $sSQL;

		   //cache queries 
		   if ($this->cachequeries) $result = $this->sen_db->CacheExecute($this->cachetime,$sSQL);
                               else $result = $this->sen_db->Execute($sSQL);

							   
           //print_r($result);							   
		   $retable = (array) $this->prepare($result);
		   
	       //print_r($this->retable);

	       return ($retable);
	}
   
	function browse($packdata,$view='') {
	   $p = GetReq('p');
	  
	   $data = explode("||",$packdata);
	
	   switch ($view) {

						  case 'snewprd': $out = $this->viewnewproducts($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9],$data[10],$data[11],$data[12],$data[13],$data[14],$data[15]);
						              break; 
									  
						  case 2051 : $out = $this->viewnewproducts_vertical($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9],$data[10],$data[11]);
						              break;
									  
						  case 2052 : $out = $this->viewnewproducts_horizontal($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9],$data[10],$data[11]);
						              break;									  									  		   
	   }
	   
	   return ($out);
	}     



    function viewnewproducts($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1,$uninameA=null,$uninameB=null,$sxA=null,$sxB=null,$ypoA=null,$ypoB=null) {	   	
	   $p = GetReq('p');
	   $a = GetReq('a');	   

	   $gr = urlencode($group);
	   $ar = urlencode($title);	

	   $link = seturl("t=senvp&a=$ar&g=$gr&p=$p" , $title);
	   
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
		         "<H4>" . $price . $this->moneysymbol ."</H4>";
		      	 //"<br>" . localize('_BOXTYPE',getlocal()) . " :" . $boxtype .
				 //"<br>" . "barcode :" . $barcode;	  
	   $attr[] = "center;20%";
	   
	   //if ((floatval(str_replace(",",".",$price))>0.001) && ($ypoA>0)) {	  			     		  
	      $data[] = GetGlobal('controller')->calldpc_method("metacache.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$uninameA;$uninameB;$sxA;$sxB;$ypoA;$ypoB+$group+$page",1) .
	                GetGlobal('controller')->calldpc_method("sencart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$uninameA;$uninameB;$sxA;$sxB;$ypoA;$ypoB+$group+$page",1) .	   
	                GetGlobal('controller')->calldpc_method("neworder.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$uninameA;$uninameB;$sxA;$sxB;$ypoA;$ypoB+$group+$page",1);			  
	      $attr[] = "center;20%";			  		 		
	   /*}		  
       else {
	      $data[] = $this->notavail;//localize('_NOTAVAL',getlocal()); 
	      $attr[] = "center;20%";		  
	   }*/	   
	   
	   $myarticle = new window('',$data,$attr);
       if ($a == $title) 
	     $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::") . "<hr>";	     
	   else	   
	     $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::") . "<hr>";
	   unset ($data);
	   unset ($attr);

	   return ($out);
	}	
	
	
    function viewnewproducts_vertical($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1) {
	   	
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
	   $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::");
	   unset ($data);
	   unset ($attr);

	   return ($out);
    }		
	
    function viewnewproducts_horizontal($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1) {
	
	   static $meter;
	   
	   $meter+=1;
	  
		//get current product view 	   	
	   $pview = 'senvp';//$this->view;//GetSessionParam("PViewStyle");	//get saved view from products dpc !?		
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
}
else die("SENPRODUCTS DPC REQUIRED!");
?>