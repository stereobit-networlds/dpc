<?php

$__DPCSEC['DIRECTORY_DPC']='2;2;2;2;2;2;2;2;9';
$__DPCSEC['DIRMENU_']='2;2;2;2;2;2;2;2;9';
$__DPCSEC['DIRADMIN_']='9;2;2;2;2;2;2;9;9';

if ((!defined("DIRECTORY_DPC")) && (seclevel('DIRECTORY_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("DIRECTORY_DPC",true);

$__DPC['DIRECTORY_DPC'] = '_directory';

$__EVENTS['DIRECTORY_DPC'][0]='createdir';
$__EVENTS['DIRECTORY_DPC'][1]='viewabledir';
$__EVENTS['DIRECTORY_DPC'][2]='viewdir';
$__EVENTS['DIRECTORY_DPC'][3]=1;
$__EVENTS['DIRECTORY_DPC'][4]=2;
$__EVENTS['DIRECTORY_DPC'][5]=3;
$__EVENTS['DIRECTORY_DPC'][6]=4;
$__EVENTS['DIRECTORY_DPC'][7]=5;
$__EVENTS['DIRECTORY_DPC'][8]=6;
$__EVENTS['DIRECTORY_DPC'][9]=7;
$__EVENTS['DIRECTORY_DPC'][10]=8;
$__EVENTS['DIRECTORY_DPC'][11]=9;
$__EVENTS['DIRECTORY_DPC'][12]='txt2sql_directories';
$__EVENTS['DIRECTORY_DPC'][13]='sql2txt_directories';
$__EVENTS['DIRECTORY_DPC'][14]='updatedir';

$__ACTIONS['DIRECTORY_DPC'][0]='viewdir';
$__ACTIONS['DIRECTORY_DPC'][1]=1;
$__ACTIONS['DIRECTORY_DPC'][2]=2;
$__ACTIONS['DIRECTORY_DPC'][3]=3;
$__ACTIONS['DIRECTORY_DPC'][4]=4;
$__ACTIONS['DIRECTORY_DPC'][5]=5;
$__ACTIONS['DIRECTORY_DPC'][6]=6;
$__ACTIONS['DIRECTORY_DPC'][7]=7;
$__ACTIONS['DIRECTORY_DPC'][8]=8;
$__ACTIONS['DIRECTORY_DPC'][9]=9;

$__DPCATTR['DIRECTORY_DPC']['createdir'] = 'createdir,0,0,0,0,0,1,0,0'; 
$__DPCATTR['DIRECTORY_DPC']['viewabledir'] = 'viewabledir,0,0,0,0,0,1,0,0';
$__DPCATTR['DIRECTORY_DPC']['viewdir'] = 'viewdir,0,0,0,0,0,0,1,0';
$__DPCATTR['DIRECTORY_DPC']['txt2sql_directories'] = 'txt2sql_directories,0,0,0,0,0,1,0,0';
$__DPCATTR['DIRECTORY_DPC']['sql2txt_directories'] = 'sql2txt_directories,0,0,0,0,0,1,0,0';
$__DPCATTR['DIRECTORY_DPC']['1'] = '1,0,0,0,0,0,0,1,0,1';
$__DPCATTR['DIRECTORY_DPC']['2'] = '2,0,0,0,0,0,0,1,0,1';
$__DPCATTR['DIRECTORY_DPC']['3'] = '3,0,0,0,0,0,0,1,0,1';
$__DPCATTR['DIRECTORY_DPC']['4'] = '4,0,0,0,0,0,0,1,0,1';
$__DPCATTR['DIRECTORY_DPC']['5'] = '5,0,0,0,0,0,0,1,0,1';
$__DPCATTR['DIRECTORY_DPC']['6'] = '6,0,0,0,0,0,0,1,0,1';
$__DPCATTR['DIRECTORY_DPC']['7'] = '7,0,0,0,0,0,0,1,0,1';
$__DPCATTR['DIRECTORY_DPC']['8'] = '8,0,0,0,0,0,0,1,0,1';
$__DPCATTR['DIRECTORY_DPC']['9'] = '9,0,0,0,0,0,0,1,0,1';

$__LOCALE['DIRECTORY_DPC'][0]= 'DIRECTORY_DPC;Directory;Κατάλογος';
$__LOCALE['DIRECTORY_DPC'][1]= '_DIR;Directory;Κατάλογος';


$__PARSECOM['DIRECTORY_DPC']['render1']='_CATEGORIES_';
$__PARSECOM['DIRECTORY_DPC']['render2']='_MENU_';

class _directory {

    var $usedatabase;
    var $name;
	var $path;
	var $fullpath;
	var $alias;
	var $aliasinfo;
	var $ddir;
	var $dmeter;
	var $directory_mark;
	var $dirview;
	var $aliasfile;
	var $homedir;
	var $homealias;
	var $drres;
	var $dbres;
	var $nav_on;
	var $home;
	var $viewable;
	var $selgroup;
	var $resourcepath;
	var $restype;
	var $treeview;
	
    var $userLevelID;

    var $viewstyle;
    var $outpoint;
	var $bullet;
	
	var $_CONTINUE;
	
	var $agent;

	function _directory($alias="") { 
	    $UserSecID = GetGlobal('UserSecID');
	    $__USERAGENT = GetGlobal('__USERAGENT');	
	    $GRX = GetGlobal('GRX');
		
		$this->_CONTINUE = 1;			
		
        $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);		

		$this->agent = $__USERAGENT;
		
		//get current product view 
		//$this->dirview = GetSessionParam("PViewStyle");
	    
		//dirview per client			
 	    $viewperclient = arrayload('DIRECTORY','viewperclient');			
	    $this->dirview  = $viewperclient[$this->userLevelID];
	    if (!$this->dirview) $this->dirview = paramload('DIRECTORY','dirview');	
		
		//treeview per client
 	    $treeperclient = arrayload('DIRECTORY','treeview');			
	    $this->treeview  = $treeperclient[$this->userLevelID];		
				
        $this->usedatabase = iniload('DATABASE');//paramload('DIRECTORY','dirdb');		
		$this->dmeter = 0;
		$this->alias = $alias;
        $this->directory_mark = paramload('DIRECTORY','dirmark');
		$this->homedir = paramload('DIRECTORY','dirname');
		$this->drres = paramload('DIRECTORY','drres');
        $this->nav_on = paramload('SHELL','navigator');
        $this->home = localize(paramload('DIRECTORY','diralias'),getlocal()); 

        $this->aliasfile = file (paramload('SHELL','prpath') . "directories.csv");

        $info = $this->getaliasinfo($this->alias,2);
	    $this->path = $info[0];
	    $this->name = $info[1];
		$this->viewable = (($info[4]>=10) ? 1 : 0); //view or hide
		$this->selgroup = (($info[4]>=10) ? ($info[4]-10) : $info[4]); //user group to apply

        $this->fullpath = paramload('DIRECTORY','dirpath') . $this->path . $this->name; 

        if ($this->drres) $pathres = $this->drres;
		             else $pathres = $this->fullpath;

        if ($GRX) {
			 $this->outpoint = loadTheme('point');
			 $this->bullet = loadTheme('bullet');
             $this->rightarrow = loadTheme('rarrow');
			 
			 if (paramload('DIRECTORY','resources')) {
               //$this->resourcepath = paramload('SHELL','urlbase') . paramload('DIRECTORY','resources');	 
               $ip = $_SERVER['HTTP_HOST'];
               $pr = paramload('SHELL','protocol');		   
	           $this->resourcepath = $pr . $ip . paramload('DIRECTORY','resources');	 			   
			   $this->restype = paramload('DIRECTORY','restype');
			 }
			 else  
			   $this->resourcepath = null;
		}
	    else {
			 $this->outpoint = "|";
			 $this->bullet = "&nbsp;";
	         $this->rightarrow = ">";
			 
             $this->resourcepath = null;	 
		}
	}

    function event($sAction) {

       switch($sAction) {

         case "createdir"          : $this->create_dir();  break;
         case "updatedir"          : $this->update_dir();  break;		 
         case "viewabledir"        : $this->setview_dir(); break;
		 case "txt2sql_directories": $this->txt2sql_directories(); break;		 
		 case "sql2txt_directories": $this->sql2txt_directories(); break;		 
         case "viewdir"            : break;			 		 
       }
	}

    function action($action) {
	    $g = GetReq('g');			

		switch ($action) {
			case "viewdir": $out = setNavigator(localize('DIRECTORY_DPC',getlocal()));
			                $out .= $this->render3(3,$this->treeview); 
							break;				
		    case 1        : 
		    case 2        : 
		    case 3        : 
		    case 4        : 
            case 5        : 
		    case 6        : 
            case 7        : 
		    case 8        : 
		    case 9        : $this->_directory($g);
			                $out = $this->render3(3,$this->treeview); 
							break;				
		}

		return ($out);
	}

	function render1($viewtype=1,$mode=0) {
	      $t = GetReq('t');

          if (is_array($this->alias)) { //in case of table array
			$ddir = (array)$this->alias;
		  }
		  else {                        //in case of disk directory
		 	if ($this->homedir) $ddir = $this->read_dirs();		
		  }
		  $out = $this->view_category($ddir,$viewtype,$mode); 

		return ($out);
	}

	function render2($treespaces='',$sp=0) {

      if (seclevel('DIRMENU_',$this->userLevelID)) {
          if (is_array($this->alias)) { //in case of table array
			$ddir = (array)$this->alias;
		  }
		  else {                        //in case of disk directory
		 	if ($this->homedir) $ddir = $this->read_dirs();		
		  }
		  $out = $this->view_menu($ddir,$treespaces,$sp);
	  }
	  return ($out);
	}

	function render3($viewtype=3,$viewtree=0) {
	    $g = GetReq('g');
		 
	    switch ($this->agent) {			
			
		  case 'CLI'  :
		  case 'TEXT' : 
	      case 'XML'  : 
          case 'XUL'  :
		  case 'GTK'  : break;
		  case 'HTML' :
          default     :		 
	                    //$out = $this->view_head_dir($viewtype,$viewtree); 
		                //return ($out);		
                        //$out = getcache($viewtype."_".urlencode($g),'tree','view_head_dir',$this,$viewtype,$viewtree); 
	                    $p1 = $viewtype."_".urlencode($g);
	                    $p2 = 'tree';
	                    $classdpc = 'view_head_dir';	 
		                $out = GetGlobal('controller')->calldpc_method_use_pointers('cache.getcache', 
				                             array(0=>&$p1,
											       1=>&$p2,
											       2=>&$classdpc,
												   3=>&$this,
												   4=>&$viewtype,
												   5=>&$viewtree)
												   //3=>&$classdata)  
											      );						
		}				
		return ($out);		
	}



    function view_head_dir($viewtype=3,$viewtree=0) {
	    $g = GetReq('g'); 				  

        if (($this->nav_on) && ($g)) {		

          //directory path	
          $data2[] = $this->view_analyzedir();
          $attr2[] = "left;";

          if ($data2)  {
		     $win2 = new window('',$data2,$attr2);
		     $out .= $win2->render("center::100%::0::group_dir_headtitle::left::0::0::");
		     unset ($win2);
		  }
		}

        // view subcategories 
        $mycat = new _directory($this->alias);
		$scat = $mycat->render1($viewtype); 
		unset($mycat);	
		
        if ($scat)  {
		
           // view tree 
		   if (($viewtree) && ($g)) {
             $mytr = new _directory();
		     $stree = $mytr->render2(); 
		     unset($mytr);		
			 
		     $data3[] = $stree; //tree view
		     $attr3[] = "left;30%";  			 
           }
				
           $data3[] = $scat; 
           $attr3[] = "left;";
		   $win2 = new window(localize('_DIR',getlocal())." :".$g,$data3,$attr3);
		   $out .= $win2->render();//"center::100%::0::group_dir_title::left::0::0::");
		   unset ($win2);
		}		
		
        if (seclevel('DIRADMIN_',$this->userLevelID)) $out .= $this->admin();

        return ($out);
    }
	
	
	//type = type view directory
	//mode = command to links (1..9)
    function view_category($ddir,$type=1,$mode=0) {
	    $g = GetReq('g');
	    $t = GetReq('t');		

        $usealias = paramload('DIRECTORY','usealias');
		 
        if ($ddir)  {

           //startup
           switch ($type) {
			   case 1 : $to_be_print = $this->outpoint . "&nbsp;"; break;
		   }
 				   	 
		   if ($mode) $t = $mode;
		         else $t = $this->dirview;
				 
           reset($ddir);
           //while (list ($line_num, $line) = each ($ddir)) {
           foreach ($ddir as $line_num => $line) {	

			      if (!$usealias) { 
					  if ($this->alias) $newline = $this->alias . "/" . $line;
					               else $newline = $line;
				  }
				  else $newline = $line; 
				  
				  $gr = urlencode($newline);

                  switch ($type) {
                    default : 
                    case  1 :  $to_be_print .= seturl("t=$t&a=&g=$gr&p=1",$line) . " " . $this->outpoint . " ";
                               break;

                    case  2 :  $to_be_print .=  seturl("t=$t&a=&g=$gr&p=1",$line) . "<br>";
                               break;
							   
                    case  3 :  //$toprn = $this->bullet;
					           //IMAGE RESOURCE
							   if ($this->resourcepath) {
							     $filephoto = str_replace("/","_",$line); //remove invalid filename chars
							     $toprn = loadimagexy($this->resourcepath .$filephoto. $this->restype,45,30);
							   }	 
							   else
							     $toprn = $this->bullet;
								 
					           $toprn .= seturl("t=$t&a=&g=$gr&p=1","<B>".$line."</B");

		                       $win1 = new window('',$toprn);
		                       $to_be_print .= $win1->render("center::100%::0::group_category_headtitle::left::0::0::");
		                       unset ($win1);
                               
                               //read subcategories
                               $mysubdir = $line; 

                               $mydir = new _directory($line);
	                           $data2[] = $mydir->render1(1,0);
                               $attr2[] = "left;";	
		                       unset ($mydir);			
							   
		                       $win2 = new window('',$data2,$attr2);
		                       $to_be_print .= $win2->render("center::100%::0::group_category_title::left::0::0::");
		                       unset ($win2);

							   unset ($data2);
							   unset ($attr2);
                               break;
							   
					case  4 :  $mycat = seturl("t=$t&a=&g=$gr&p=1","<B>" . $line . "</B>");				
 		                       $data[] = $mycat;	
                               $attr[] = "left;";									   
							   
		                       $win1 = new window('',$data,$attr);
		                       $to_be_print .= $win1->render("center::100%::0::group_category_title::left::0::0::");
		                       unset ($win1);

	                           unset ($data);    
	                           unset ($attr);							   					
					           break;		                             
                  }
	       }
	   }

       return ($to_be_print);
	}


    function view_menu ($ddir,$treespaces='',$sp=0,$mode=0) {
	   $g = GetReq('g');
	   $t = GetReq('t');

       $usealias = paramload('DIRECTORY','usealias');

	   $mytype = $this->dirview;
	   if ($mode) $mytype = $mode;
	         else $mytype = $this->dirview;

       if ($ddir)  {
         reset($ddir);
         //while (list ($line_num, $line) = each ($ddir)) {
         foreach ($ddir as $line_num => $line) {	
		
           if ($line) {           

			  if (!$usealias) { 
			       if ($this->alias) $newline = $this->alias . "/" . $line;
			                    else $newline = $line;
			  }
			  else $newline = $line;

			  $splitline = explode (";", $line); 			  
		      $mygroup = $newline;			  
			  $gr = urlencode($mygroup);

              $mycat = "$treespaces$this->outpoint <A href=\"" . seturl("t=$mytype&a=&g=$gr&p=1") . "\">";
	          if ($mygroup==$g) $mycat .= "<B>" . summarize((19-$sp),$splitline[0]) . "</B>";
			               else $mycat .= summarize((19-$sp),$splitline[0]);		   
			  $mycat .= "</A>";					
 		      $data[] = $mycat;	
              $attr[] = "left;";			
				  
		      $win1 = new window('',$data,$attr);
		      $out .= $win1->render("center::99%::0::group_category_title::left::0::0::");
		      unset ($win1);

	          unset ($data);    
	          unset ($attr);					    	
			 	
				
			  //seach subdirs	    				  		  
   	          $targetdir = $this->getaliasinfo($g,2);
			  $sourcedir = $this->getaliasinfo($mygroup,2);
			  $mytdir = $targetdir[0] . $targetdir[1];			  
			  				  
			  if (strstr($mytdir,$sourcedir[1])) {
   				  $splitdir = explode ("/",$mytdir);
			      $spaces = "&nbsp;&nbsp;"; 
				  $sp2 = 2;
   			      $i=2;				  
				  while ($splitdir[$i]) {
					  $mydirgroup = $this->getaliasinfo($splitdir[$i],1);
					  $mydrgr = urlencode($mydirgroup[2]);
                      $mycat2 = "$spaces$this->outpoint <A href=\"" . seturl("t=$mytype&a=&g=$mydrgr&p=1") . "\">";
	                  if ($mydirgroup[2]==$g) $mycat2 .= "<B>" . summarize((19-$sp2),$mydirgroup[2]) . "</B>";
					                     else $mycat2 .= summarize((19-$sp2),$mydirgroup[2]);
			          $mycat2 .= "</A>";	
 		              $data1[] = $mycat2;	
                      $attr1[] = "left;";			
				  
		              $win2 = new window('',$data1,$attr1);
		              $out .= $win2->render("center::99%::0::group_category_title::left::0::0::");
		              unset ($win2);
	                  unset ($data1);    
	                  unset ($attr1);		
					  
				      $i+=1;					  				  
					  $spaces .= "&nbsp;&nbsp;"; 
					  $sp2 = $sp2 + 2;
				  }
				  $mytreespaces = $spaces . $treespaces; 
				  $sp3 = $sp2 + $sp;
				  $mysubcat = new _directory($targetdir[2]);
				  $out .= $mysubcat->render2($mytreespaces,$sp3);
				  unset($mysubcat);
			  }	
		   }	  				   		
	     }
       }

      return ($out); 
    }
    
    function isparent() {

		if ($this->read_dirs()) return 1;
		                   else return 0;
	}

	function read_dirs() {
	  
	  //$out = getcache(urlencode($this->alias),'dr'.$this->userLevelID,'read_directory',$this);
	  
	  $uealias = urlencode($this->alias);
	  $ext = 'dr'.$this->userLevelID;
	  $thisclass = 'read_directory'; 
	  $out = GetGlobal('controller')->calldpc_method_use_pointers('cache.getcache',  
				                          array(0=>&$uealias,
									            1=>&$ext,
											    2=>&$thisclass,
												3=>&$this)  
											);	  
	  
	  return ($out);
	  //return ($this->read_directory());
	}
	
	function read_directory() {

	    if (is_dir($this->fullpath)) {
          $mydir = dir($this->fullpath);
		 
          while ($fileread = $mydir->read ()) {
	   
           //read directories
		   if (($fileread!='.') && ($fileread!='..'))  {

               if ($this->directory_mark) {
			   
  	             if (stristr ($fileread,$this->directory_mark)) {
                    $finddir = $this->getaliasinfo($fileread,1);
					
					//check if viewable
					if ($this->is_viewable_to_user($this->userLevelID,$finddir[4])) {
		                $ddir[] = $finddir[2];
                        $this->dmeter+=1; 						
					}
			     }
               }
			   else {
                  $finddir = $this->getaliasinfo($fileread,1);
				  
				  //check if viewable			  
				  if ($this->is_viewable_to_user($this->userLevelID,$finddir[4])) {
		                $ddir[] = $finddir[2];
                        $this->dmeter+=1; 						
				  }			  
			   }
		   }
	      }
	      $mydir->close ();
        }
        
		return ($ddir);
	}

	function search($text,$group='') {
		$db = GetGlobal('db');

        $usedatabase = paramload('DIRECTORY','dirdb');
		$userview_attr = (10 + $this->userLevelID);

  	    $mysearch = new search;
        if ($usedatabase) {
		
		   //except hidden dirs from this user 
           $sSQL = "SELECT * FROM sysdir WHERE dirview<=" . $userview_attr;
		   //search specific
		   if (($group) && ($group!=localize('_ALL',getlocal())))
		      $sSQL .= " AND diralias=" . $db->qstr($group);

           if ($sSQL) $res = $db->Execute($sSQL);	
           //DEBUG
           //if (seclevel('_DEBUG',$this->userLevelID)) echo $sSQL;		   

           if ($res)  {
	          while (!$res->EOF) {

                   if ($mysearch->find($res->fields["diralias"],$text)) {
                     $result[] = $res->fields["diralias"];                                 
                   }
                   $res->MoveNext();   
			  }
	       }
		}
		else {
           if ($this->aliasfile) {
              //while (list ($dline_num, $dline) = each ($this->aliasfile)) {
	          foreach ($this->aliasfile as $dline_num => $dline) {	

                 $dsplit = explode (";", $dline); 
		         //search specific
		         if (($group) && ($group!=localize('_ALL',getlocal()))) {
				   if ($dsplit[2]==$group) {
                     if ($mysearch->find($dsplit[2],$text)) {
                       $result[] = $dsplit[2];                                 
                     }				   
				   }
				 }
				 else { 
                   if ($mysearch->find($dsplit[2],$text)) {
                       $result[] = $dsplit[2];                                 
                   }
				 }
              }
           }
		}

	    unset ($mysearch);

        //print_r($result);
		return ($result);
	}

	function create_dir() {
		$db = GetGlobal('db');
		$g = GetReq('g');
		$newcategory = GetReq('newcategory');//get dir name,code form data
		$codecategory = GetReq('codecategory');

        $usealias = paramload('DIRECTORY','usealias');
        $usedatabase = paramload('DIRECTORY','dirdb');
        $abspath = paramload('DIRECTORY','dirpath');

        $mydir = $this->getaliasinfo($g,2);
		$location = $abspath . "/" . $mydir[0] . $mydir[1] . "/" ;
        //$location = $abspath . "/" . $this->path . $this->name . "/"; 
    
		$newdir = $codecategory . paramload('DIRECTORY','dirmark');
		$mynewdirpath = $location . $newdir; 

		//create dir to admin write-enabled directory	
		if (mkdir($mynewdirpath, 0700)) {
    	   $myinfo = "Category $newdir created successfully !"; 
					   
		   //update directory file map
		   if ($usealias) {		   
					   
		     if ($usedatabase) {
				         $dpath = $mydir[3] . $mydir[1] . "/";
						 $dname = $newdir;
						 $dalias = $newcategory;
						 
						 $sSQL = "insert into sysdir (dirpath,dirname,diralias,dirview)" .
						         " values (" .
								 $db->qstr($dpath) . "," .
								 $db->qstr(ext2ascii($dname)) . "," .
								 $db->qstr($dalias) . "," .								 								 
								 "10)"; //viewable by default
								   
						 $result = $db->Execute($sSQL);		
						 if ($db->Affected_Rows())    
						   $myinfo .= "<br>Map file updated successfully !";
						 else
					       $myinfo .= "<br>Map file not updated !!!"; 						 
			  }
		      else {
					     $data = "\n" . $mydir[3] . $mydir[1] ."/;" . $newdir . ";" . $newcategory . ";";
					     if ($this->update_mapfile($data)) 
						   $myinfo .= "<br>Map file updated successfully !"; 
					     else 
					       $myinfo .= "<br>Map file not updated !!!"; 
			  }
			}	 
		  }
		  else {
		      $myinfo = "Failed to create $newdir !"; 				   
		}

		setInfo($myinfo);
	}
	
	//ONLY FOR SQL DB !!!!!!!!!!!!!!!!
	function update_dir() {
		$db = GetGlobal('db');
		$updcategory = GetReq('updcategory');//get dir name,code form data
		$codecategory = GetReq('codecategory');

        $usedatabase = paramload('DIRECTORY','dirdb'); 	   
					   
		if ($usedatabase) {
				         $new_alias = $updcategory;
		                 $searchcode = $codecategory . paramload('DIRECTORY','dirmark');
						 
						 $sSQL = "UPDATE sysdir set diralias=" . $db->qstr($new_alias) .
						         " where dirname=" . $db->qstr(ext2ascii($searchcode));
							 
						 $result = $db->Execute($sSQL);
						 		
						 if ($db->Affected_Rows())    
						   $myinfo .= "<br>Map file updated successfully !";
						 else
					       $myinfo .= "<br>Map file not updated !!!"; 						 				   
		}

		setInfo($myinfo);
	}	

    ///////////////////////////////////////////////////////////////
    // update directory map file (directories.csv)
    ///////////////////////////////////////////////////////////////
    function update_mapfile($data) {

       // update file (if properties & security policy are ok)
       $actfile = paramload('SHELL','prpath') . "directories.csv";
       if ($fp = fopen ($actfile , "a+")) {
          fwrite ($fp, $data);
          fclose ($fp);
	      return 1;
       }
       return 0;
    }	

    ///////////////////////////////////////////////////////////////////
    // get info about alias category name as array of path/name/alias
    //      -----be aware to have spaces as _ in $alias----
    // $aliastype means what part of line will be translated path, 
    // name or alias
    ///////////////////////////////////////////////////////////////////
	function getaliasinfo($alias,$aliastype) {
	
	   if ((is_string($alias))) {	
	
	     //$out = getcache(urlencode($alias),'als','getaliasinfo2',$this,$alias,$aliastype);     
		 
	     $uealias = urlencode($alias);//echo '>>>>',$alias;
	     $ext = 'als';
	     $classdpc = 'getaliasinfo2'; 
		 $out = GetGlobal('controller')->calldpc_method_use_pointers('cache.getcache',  
				                             array(0=>&$uealias,
											       1=>&$ext,
											       2=>&$classdpc,
												   3=>&$this,
												   4=>&$alias,
												   5=>&$aliastype)  
											);
       }											
	   
	   return ($out);
	   //return ($this->getaliasinfo2($alias,$aliastype));
	}
	
    function getaliasinfo2($alias,$aliastype) {
	   $db = GetGlobal('db');
	   $g = GetReq('g');

       $usealias = paramload('DIRECTORY','usealias');
  
       if ($usealias) {
         if ($this->usedatabase) { 
            switch ($aliastype) { 
              case 0 : $sSQL = "select * from sysdir where dirpath="  . $db->qstr($alias); break;   
              case 1 : $sSQL = "select * from sysdir where dirname="  . $db->qstr(ext2ascii($alias)); break;  //encode chars (mysql char problem)
              case 2 : $sSQL = "select * from sysdir where diralias=" . $db->qstr($alias); break;  	  	  
	        }

            //print $sSQL;

		    //cache queries 
		    if (paramload('DATABASE','cacheq')) $result = $db->CacheExecute(paramload('DATABASE','qcachetime'),$sSQL);
                                           else $result = $db->Execute($sSQL);
	

            if($result)  {		  
                   $aliasinfo[0] = $this->homedir . $result->fields["dirpath"];
                   $aliasinfo[1] = ascii2ext($result->fields["dirname"]); //decode chars (mysql char problem)
                   $aliasinfo[2] = $result->fields["diralias"];
                   $aliasinfo[3] = $result->fields["dirpath"];
                   $aliasinfo[4] = $result->fields["dirview"];					   

	               //print_r($aliasinfo);
                   return ($aliasinfo); 	  
	        }	
         }
         else { 
            //$aliasfile = file (paramload('SHELL','prpath') . "directories.csv");????allready defined

            if ($alias) {
              //while (list ($dline_num, $dline) = each ($aliasfile)) {
              foreach ($this->aliasfile as $dline_num => $dline) {				  
                 $dsplit = explode (";", $dline);             

                 if ($dsplit[$aliastype] == $alias ) {
                   $aliasinfo[0] = $this->homedir . $dsplit[0];
                   $aliasinfo[1] = $dsplit[1];
                   $aliasinfo[2] = $dsplit[2];
                   $aliasinfo[3] = $dsplit[0];						
                   //print_r ($aliasinfo);

                   return ($aliasinfo);                                    
                 }
              }
            }
         }
         

         //home dir (=default)		  
         $aliasinfo[0] = $this->homedir;
         $aliasinfo[1] = "/";
         $aliasinfo[2] = $this->homedir;
         $aliasinfo[3] = $this->homedir;	  
                 
	   }
	   else {
         if (!$alias) {		  
           $aliasinfo[0] = "";
           $aliasinfo[1] = $this->homedir . "/";
           $aliasinfo[2] = $this->homedir;
           $aliasinfo[3] = $this->homedir;	  
		 }
		 else {
           $aliasinfo[0] = $this->homedir . "/";
           $aliasinfo[1] = $alias . "/";
           $aliasinfo[2] = $alias;
           $aliasinfo[3] = $alias;	  
		 }
	   }

       //print_r ($aliasinfo);
	   //$this->aliasinfo = $aliasinfo;
       return ($aliasinfo);  
    }

    //get depth of directory	
    function getdirdepth() { 
	 
        $splitx = explode ("/", $this->path);         
        $i=1;

        while ($splitx[$i]!="") {     
          $i+=1; 
        }

        return ($i);
    }	
	
    ///////////////////////////////////////////////////////////
    //analyze directory path
    //input : the directory path
    //output: array with the aliases of path's dir parts
    ///////////////////////////////////////////////////////////
    function analyzedir() {

        $adir = array();
        $adir[] = $this->home; //set home
  
        $splitx = explode ("/", $this->path);         
        $i=1;

        while ($splitx[$i]!="") {   
          $nextfinddir = $this->getaliasinfo($splitx[$i],1); 
          if ($nextfinddir) { 
             $adir[] = $nextfinddir[2];
          }   
          $i+=1; 
        }

        //print_r ($adir);
        return ($adir);
    }

    ///////////////////////////////////////////////////////////
    //view analyzed directory 
    //input : the directory path
    //output: html code
    ///////////////////////////////////////////////////////////
    function view_analyzedir() {
		$g = GetReq('g'); 
		$t = GetReq('t'); 
		$a = GetReq('a'); 				

        $adirs = $this->analyzedir();

        $i=0;
        while ($adirs[$i]!="") {
           //check for "home" link, must be empty to view the front page 
	       //elsewhere it view article directory
           if ($adirs[$i]!= $this->home) {
			   $mygroup = urlencode($adirs[$i]);
			   $aprint .= "<A href=\"" . seturl("t=$t&a=&g=$mygroup") . "\">";
		   }
	       else {
   	           $aprint .= "<A href=\"" . seturl("t=&a=&g=") . "\">";
		   }
  
           $aprint .= $adirs[$i];
           $aprint .= "</A>&nbsp;" . $this->rightarrow . "&nbsp;";
	
	       $i+=1;    
        }

        //current directory   
        $aprint .= "<B>$this->alias</B>";
        $aprint .= "&nbsp;" . $this->rightarrow . "&nbsp;";
  
        return ($aprint);
    }
	
	//enable-disable dirview status
	function setview_dir() {
	   $db = GetGlobal('db');
	   $g = GetReq('g');
	   
	   $ulev = GetParam("userlevel"); 
	   
	   if (!GetParam("dirview")) $val = 0 + $ulev;
	                        else $val =10 + $ulev;   
	   
	   //echo $val;
       $sSQL = "update sysdir set dirview=" . $val .
	           " where diralias=" . $db->qstr($g);   	  	  

       //echo $sSQL;

       $result = $db->Execute($sSQL);
	   if ($db->Affected_Rows()) setInfo($db->Affected_Rows() . ' row(s) affectd');	    	
	}

	//check view/hide attr
	function is_viewable_to_user($user,$attr) {
	
	  if ($this->usedatabase) {	//if database supported...
	    //examples :
		//           00 = hide from all
		//           01 = hide from 1 tyoe user and above
		//           10 = show to all
		//           11 = show to 1 type use and above 
	
	    //first digit indicate view or hide 1..=view
	    if ($attr>=10) {
		  //seconf digit indicate viewing from digit to admin
		  $user_attr = $user + 10;
		  if ($user_attr>=$attr) return TRUE;
		                    else return FALSE;
		}
		else { //0..=hide
		  //seconf digit indicate hidding from digit to admin		
		  $user_attr = $user;
		  if ($user_attr>=$attr) return FALSE;
		                    else return TRUE;
		}
	  }
	  else //show to all
	    return TRUE;	 

	}	

    function admin() {
	    $sFormErr = GetGlobal('sFormErr');
	    $p = GetReq('p');	
		$g = GetReq('g'); 
		$t = GetReq('t'); 
		$a = GetReq('a');
		
		$ar = urlencode($a);
		$gr = urlencode($g); 
		
        $filename = seturl("t=$t&a=$ar&g=$gr&p=$p");	
  
        $toprint = "";

        //error message
        $toprint .= setError($sFormErr);	
  
	    //create directory form
        $form .= "<FORM action=". "$filename" . " method=post class=\"thin\">";
        $form .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	   
        $form .= "Category name: <input type=\"text\" name=\"newcategory\" maxlenght=\"20\">";	    
        $form .= "Category code: <input type=\"text\" name=\"codecategory\" size=\"5\" maxlenght=\"3\">";			
        $form .= "<input type=\"hidden\" name=\"FormName\" value=\"CreateCategory\">"; 	
        $form .= "<input type=\"hidden\" name=\"FormAction\" value=\"createdir\">&nbsp;";		
        $form .= "<input type=\"submit\" name=\"Submit1\" value=\"Create\">";		
        $form .= "</FONT></FORM>";  		
		
	    //update directory form
        $form .= "<FORM action=". "$filename" . " method=post class=\"thin\">";
        $form .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	   
        $form .= "New name: <input type=\"text\" name=\"updcategory\" maxlenght=\"20\">";	    
        $form .= "Existing code: <input type=\"text\" name=\"codecategory\" size=\"5\" maxlenght=\"3\">";			
        $form .= "<input type=\"hidden\" name=\"FormName\" value=\"UpdateCategory\">"; 	
        $form .= "<input type=\"hidden\" name=\"FormAction\" value=\"updatedir\">&nbsp;";		
        $form .= "<input type=\"submit\" name=\"Submit2\" value=\"Update\">";		
        $form .= "</FONT></FORM>"; 		

        $data[] = $form;  
        $attr[] = "left;50%;"; 
		
		if ($this->usedatabase) {
	      //enable-disable dir view
          if ($this->viewable) $check = "checked"; else $check = "";		  
          $form2 = "<FORM action=". "$filename" . " method=post class=\"thin\">";
          $form2 .= "Viewable" . ":<input type=\"checkbox\" name=\"dirview\" value=\"$check \"". $check . ">";
		  $form2 .= $this->selectuser($this->selgroup);
          $form2 .= "<input type=\"hidden\" name=\"FormName\" value=\"SetView\">";		  
          $form2 .= "<input type=\"hidden\" name=\"FormAction\" value=\"viewabledir\">&nbsp;";		
          $form2 .= "<input type=\"submit\" name=\"Submit3\" value=\"Ok\">";		   
          $form2 .= "</FORM>";
		  
          $data[] = $form2;  
          $attr[] = "right;50%;"; 		    			
		}

		$winb = new window('',$data,$attr);
		$toprint .= $winb->render("center::100%::0::group_dir_title::right::0::0::");
		unset ($winb);			
  
        return ($toprint);
     }
	 
   /////////////////////////////////////////////////////////////////
   // generate user selection list
   /////////////////////////////////////////////////////////////////
   function selectUser($select=0) {

     $levels = explode(",",paramload('USERS','groups'));//get_seclevels(); //print_r($levels);
   
     if ($levels) { 
       reset ($levels);
       //asort ($levels);

       $toprint .= "<select name=\"userlevel\">\n";//<OPTION value=\"1\">ALL</OPTION>\n";

       foreach ($levels as $lan_num => $lan_descr) {
	   
	     //not display users above this user
	     if ($lan_num<=$this->userLevelID) {	
	   
	       //is selected ?
		   if ($lan_num==$select) $issel = 'selected';
		                     else $issel = '';
		   //have description					 
           if ($lan_descr!='') 
		     $toprint .= "<OPTION value=\"$lan_num\" $issel>$lan_descr</OPTION>\n";
		 }  
       }
	   
	   $toprint .= "\n</select>";
     }
   
     return ($toprint);
   }	
   
   
   function txt2sql_directories() {
	   $db = GetGlobal('db'); 
	   
       $aliasfile = file (paramload('SHELL','prpath') . "directories.csv");
	
       $rec_counter = 0;
	   
       if ($aliasfile) {
	   
              //delete table if exist		
			  $sSQL = "drop table if exists sysdir";
              $db->Execute($sSQL);
              $sSQL = "create table sysdir " .
			          "(" .
	                  "dir_id integer auto_increment primary key," .
	                  "dirpath varchar(64)," .
	                  "dirname varchar(64)," .
	                  "diralias varchar(64)," .
                      "dirview integer," .
                      "UNIQUE (dirname)," .
                      "UNIQUE (diralias)" .					  
					  ")";			  
              $db->Execute($sSQL);			  
			  	   
              //while (list ($dline_num, $dline) = each ($aliasfile)) {
	          foreach ($aliasfile as $dline_num => $dline) {	
                 $dsplit = explode (";", $dline);             

				 //get/set viewable attribute
				 $rview = (trim($dsplit[3])? trim($dsplit[3]) : 10); //10=default view
				 
                 $sSQL = "insert into sysdir (dirpath,dirname,diralias,dirview)" .  
				         " values (" .  
                         $db->qstr(trim($dsplit[0])) . "," . 
			             $db->qstr(ext2ascii(trim($dsplit[1]))) . "," .  //ext2ascii solve char problem
                         $db->qstr(trim($dsplit[2])) . "," .
					     $db->qstr($rview) . ")";
                                  
                 $db->Execute($sSQL);
				 if ($db->Affected_Rows()) $rec_counter++;
				                   else echo $dsplit[2] , ">" , $dsplit[1] , "<br>"; 
								   							   
              }  
			  
			  setInfo($rec_counter . " Records affected");
       }
   }   
   
   function sql2txt_directories() {
	   $db = GetGlobal('db'); 
	   
       $filename = paramload('SHELL','prpath') . "directories.csv";
	
       $rec_counter = 0;
	   
       if ($db) {
					  
              $sSQL = "select * from sysdir";				  		  
              $res = $db->Execute($sSQL);			  
			  	  
			  //print_r($res->fields);	   
              while(!$res->EOF) {
			  
                $dirdata .= $res->fields[1] . ";" . 
				            ascii2ext($res->fields[2]) . ";" .
							$res->fields[3] . ";" .
							$res->fields[4] . ";\n";
				
	            $res->MoveNext();	
				$rec_counter+=1;	  
	          }	
			  
		      //write to text file prev data
		      if ($fp = fopen($filename,"w")) {
		        fwrite($fp,$dirdata);
		        fclose($fp);
				
			    setInfo($rec_counter . " Records copied to $filename!");
		      }			  
			  
       }   
   }   

};
}
?>