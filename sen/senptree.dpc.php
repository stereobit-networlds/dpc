<?php

$__DPCSEC['SENPTREE_DPC']='2;2;2;2;2;2;2;2;9';
$__DPCSEC['SENPTREEMENU_']='2;2;2;2;2;2;2;2;9';
$__DPCSEC['SENPTREEADMIN_']='9;2;2;2;2;2;2;9;9';

if ((!defined("SENPTREE_DPC")) && (seclevel('SENPTREE_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SENPTREE_DPC",true);

$__DPC['SENPTREE_DPC'] = 'senptree';

$__EVENTS['SENPTREE_DPC'][0]='senviewdir';
$__EVENTS['SENPTREE_DPC'][1]='senvp';
$__EVENTS['SENPTREE_DPC'][2]='viewabledir';

$__ACTIONS['SENPTREE_DPC'][0]='senviewdir';
$__ACTIONS['SENPTREE_DPC'][1]='senvp';
$__ACTIONS['SENPTREE_DPC'][2]='viewabledir';

$__DPCATTR['SENPTREE_DPC']['senviewdir'] = 'senviewdir,0,0,0,0,0,0,0,0,0';
$__DPCATTR['SENPTREE_DPC']['viewabledir'] = 'viewabledir,0,0,0,0,0,0,0,0,0';
$__DPCATTR['SENPTREE_DPC']['senvp'] = 'senvp,0,0,0,0,0,0,1,0,1'; 

$__LOCALE['SENPTREE_DPC'][0]= 'SENPTREE_DPC;Directory;ÊáôÜëïãïò';
$__LOCALE['SENPTREE_DPC'][1]= '_DIR;Directory;ÊáôÜëïãïò';


$__PARSECOM['SENPTREE_DPC']['render1']='_SENPTREE_';
$__PARSECOM['SENPTREE_DPC']['render2']='_SENPTREEMENU_';

class senptree extends sen {
	
    var $userLevelID;

	var $alias;
	var $ddir;
	var $dirview;
	var $homealias;
	var $nav_on;
	var $home;
	var $viewable;	
    var $viewstyle;
	var $resourcepath;
	var $restype;
	var $treeview;	
    var $outpoint;
	var $bullet;
	var $agent;
	var $allterms;
	var $anyterms;
	var $asphrase;	
	var $exclude;
	var $deflan;

	function senptree($alias="") { //alias = group
	    $GRX = GetGlobal('GRX');	
	    $UserSecID = GetGlobal('UserSecID'); 
		$__USERAGENT = GetGlobal('__USERAGENT');		
		
        sen::sen();				
		
        $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);		

		$this->agent = $__USERAGENT;
		
		//get current product view 
		//$this->dirview = GetSessionParam("PViewStyle");
	    
		//dirview per client			
 	    $viewperclient = arrayload('SENPTREE','viewperclient');			
	    $this->dirview  = $viewperclient[$this->userLevelID];
	    if (!$this->dirview) $this->dirview = paramload('SENPTREE','dirview');	
		
		//treeview per client
 	    $treeperclient = arrayload('SENPTREE','treeview');			
	    $this->treeview  = $treeperclient[$this->userLevelID];		
				
		$this->alias = $alias;
        $this->nav_on = paramload('SHELL','navigator');
        $this->home = localize(paramload('SENPTREE','diralias'),getlocal()); 

	    $this->viewable = 1;//!!!!!!!!!!!(($info[4]>=10) ? 1 : 0); //view or hide
		
	    //EXCLUDE DIRS
		$this->exclude = $this->get_exclude_trees();			
		  
	    //search params (must be declared in search dpc)
   	    $this->allterms = localize('_ALLTERMS',getlocal()); //echo $this->allterms;
	    $this->anyterms = localize('_ANYTERMS',getlocal());
	    $this->asphrase = localize('_ASPHRASE',getlocal()); 		  

        if ($GRX) {
			 $this->outpoint = loadTheme('point');
			 $this->bullet = loadTheme('bullet');
             $this->rightarrow = loadTheme('rarrow');
			 
			 if (paramload('SENPTREE','resources')) {
               //$this->resourcepath = paramload('SHELL','urlbase') . paramload('DIRECTORY','resources');	 
               $ip = $_SERVER['HTTP_HOST'];
               $pr = paramload('SHELL','protocol');		   
	           $this->resourcepath = $pr . $ip . paramload('SENPTREE','resources');	 			   
			   $this->restype = paramload('SENPTREE','restype');
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
		
		$this->deflan = paramload('SHELL','dlang');//2=greek where is the native lan for this functions
	}

    function event($sAction) {

        switch($sAction) {
	   		 
           case "senviewdir"            : break;	
		   case "viewabledir"           : $this->set_viewable(); break;	 		 
        }
	}

    function action($action) {
	    $g = GetReq('g');		

		switch ($action) {

		 case "senviewdir": $out = setNavigator(localize('SENPTREE_DPC',getlocal()));
			                $out .= $this->render3(3,$this->treeview,$g); 
							break;				
         case "viewabledir" :						
			case 'senvp'  : $out = $this->render3(3,$this->treeview,$g); 
							break;				
		}

		return ($out);
	}	

	function render1($viewtype=1,$mode=0,$group=null) {
		  
		$out = $this->view_category($this->read_sentree($group),$viewtype,$mode,$group); 

		return ($out);
	}
	
	//render for search results (used in search))
	function render1_searchresults($viewtype=1,$mode=0,$results) {
	
		$out = $this->view_category($results,$viewtype,$mode); 

		return ($out);	
	}
	
	//type = type view directory
	//mode = command to links (1..9)
    function view_category($ddir,$type=1,$mode=0,$group=null) {
		 
        if ($ddir)  {//print_r($ddir);

           //startup
           switch ($type) {
			   case 1 : $to_be_print = $this->outpoint . "&nbsp;"; break;
		   }
 				   	 
		   //if ($mode) $t = $mode;
		   //      else $t = $this->dirview;
		   $t = 'senvp';//$this->dirview;
				 
           reset($ddir);
           foreach ($ddir as $line_num => $line) {	//echo $line;		   
				  
				  //localization............................
				  if (($clanguage=getlocal())!=$this->deflan)
				    $loctitle = localize($line_num,$clanguage);
				  else  
				    $loctitle = $line_num;	
					
				  $title = $line_num;		
		  
				  if (trim($group)!=null) 
				    $gr = $group . "^" . $line;		   
				  else  
				    $gr = $line; 

                  switch ($type) {
                    default : 
                    case  1 :  $to_be_print .= seturl("t=$t&a=&g=$gr&p=1",$loctitle) . " " . $this->outpoint . " ";
                               break;

                    case  2 :  $to_be_print .=  seturl("t=$t&a=&g=$gr&p=1",$loctitle) . "<br>";
                               break;
							   
                    case  3 :  //$toprn = $this->bullet;
					           //IMAGE RESOURCE
							   if ($this->resourcepath) {
							     $filephoto = str_replace("/","_",$title); //remove invalid filename chars
							     $toprn = loadimagexy($this->resourcepath .$filephoto. $this->restype,45,30);
							   }	 
							   else
							     $toprn = $this->bullet;
								 
					           $toprn .= seturl("t=$t&a=&g=$gr&p=1","<B>".$loctitle."</B");

		                       $win1 = new window('',$toprn);
		                       $to_be_print .= $win1->render("center::100%::0::group_category_headtitle::left::0::0::");
		                       unset ($win1);
                               
                               //read subcategories
	                           $data2[] = $this->render1(1,0,$gr);
                               $attr2[] = "left;";	
		                       unset ($mydir);			
							   
		                       $win2 = new window('',$data2,$attr2);
		                       $to_be_print .= $win2->render("center::100%::0::group_category_title::left::0::0::");
		                       unset ($win2);

							   unset ($data2);
							   unset ($attr2);
                               break;
							   
					case  4 :  $mycat = seturl("t=$t&a=&g=$gr&p=1","<B>" . $loctitle . "</B>");				
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

	function render2($group=null,$treespaces='',$sp=0) {

      if (seclevel('SENPTREEMENU_',$this->userLevelID)) {

		  $out = $this->view_menu($group,$treespaces,$sp);
	  }
	  return ($out);
	}
	
	function view_menu($group=null,$treespaces='',$sp=0,$mode=0) {		
	   $g = GetReq('g');

	   static $cd = -1;
	   
	   //if ($mode) $mytype = $mode;
	   //      else $mytype = $this->dirview;	   
	   $mytype = 'senvp';//$this->dirview;	   
	   
	   //echo '>>',$cd;
	   $ptree = explode("^",$g); //print_r($ptree);
	   $depth = count($ptree)-1; //echo 'DEPTH:',$depth;
	    
	   //echo $group;	
	   $ddir = $this->read_sentree($group);
 
	   	 
       if ($ddir)  {
          reset($ddir);
          foreach ($ddir as $id => $line) {	
		 
		    if ($line) {
			
			  if (trim($group)!=null) 
			    $gr = $group . "^" . $line;			   
			  else 
			    $gr = $line; 
				
			  //after gr set ..localization............................!!!
			  if (($clanguage=getlocal())!=$this->deflan)
			    $line = localize($line,$clanguage);					
				
			  //$gr = $current_leaf;		 
			  $cgroup = $ptree[$cd+1]; //echo '>>',$cgroup;//$ptree[$depth];		 		

              $mycat = "$treespaces$this->outpoint <A href=\"" . seturl("t=$mytype&a=&g=$gr&p=1") . "\">";
	          if ($cgroup==$line) $mycat .= "<B>" . summarize((19-$sp),$line) . "</B>";
			                   else $mycat .= summarize((19-$sp),$line);		   
			  $mycat .= "</A>";					
 		      $data[] = $mycat;	
              $attr[] = "left;";			
				  
		      $win1 = new window('',$data,$attr);
		      $out .= $win1->render("center::99%::0::group_category_title::left::0::0::");
		      unset ($win1);

	          unset ($data);    
	          unset ($attr);	
			  
			  if ($cgroup==$line) {		  
			  	  $cd+=1;
				  $mysp=($cd+1) * 3;
				  $mytreespaces = str_repeat("&nbsp;",($cd+1)*3);	   
				  $out .= $this->view_menu($gr,$mytreespaces,$mysp);
			  }			  
			}
		  }
	   }	
	   
       return ($out); 			    
	   
	}

	function render3($viewtype=3,$viewtree=0,$group=null) {	
		 
	    switch ($this->agent) {			
			
		  case 'CLI'  :
		  case 'TEXT' : 
	      case 'XML'  : 
          case 'XUL'  :
		  case 'GTK'  : break;
		  case 'HTML' :
          default     :	//$out = $this->sen_tree(3,$this->treeview,$group);
						   
	                    $p1 = $viewtype . "_".urlencode($group);
	                    $p2 = 'stree';
	                    $classdpc = 'sen_tree';	 
		                $out = GetGlobal('controller')->calldpc_method_use_pointers('cache.getcache', 
				                             array(0=>&$p1,
											       1=>&$p2,
											       2=>&$classdpc,
												   3=>&$this,
												   4=>&$viewtype,
												   5=>&$viewtree,
												   6=>&$group)  
											      );	
												  
       }
	   
	   return ($out);												  		
	}


    function sen_tree($viewtype=3,$viewtree=0,$group=null) {   				  
		
        if (($this->nav_on) && ($group)) {		

          //directory path	
          $data2[] = $this->view_analyzedir($group,1);
          $attr2[] = "left;";

          if ($data2)  {
		     $win2 = new window('',$data2,$attr2);
		     $out .= $win2->render("center::100%::0::group_dir_headtitle::left::0::0::");
		     unset ($win2);
		  }
		}
		
		$scat = $this->render1($viewtype,0,$group);
        if ($scat)  {
		
           // view tree 
		   if (($viewtree) && ($group)) {
             //$mytr = new senptree();
		     $stree = $this->render2(); 
		     //unset($mytr);		
			 
		     $data3[] = $stree; //tree view
		     $attr3[] = "left;30%";  			 
           }		
				
           $data3[] = $scat; 
           $attr3[] = "left;";
		   $win2 = new window(localize('_DIR',getlocal())." :".$this->getgroup(1),$data3,$attr3);
		   $out .= $win2->render();//"center::100%::0::group_dir_title::left::0::0::");
		   unset ($win2);
		}		
		
        if (seclevel('SENPTREEADMIN_',$this->userLevelID)) $out .= $this->admin();								
		
        return ($out);
    }
	
	//cache mechanism
	function read_sentree($group=null,$userlevel=null) {
	
	  if ($userlevel!=null) {
	    $myuserl = intval($userlevel);
		$cacheyes = 1;
		//echo "CACHEYES";
	  }	
	  else {
	  	$myuserl = $this->userLevelID;
		$cacheyes = 0;
		//echo "CACHENO";
	  }	
	
      /*$this->t_stree = new ktimer;
	  $this->t_stree->start('stree');		*/
	  //$out = getcache(urlencode(str_replace("^","_",$group)),'sd'.$this->userLevelID,'read_tree',$this,$group);	

	  $uealias = urlencode(str_replace("^","_",$group));
	  $ext = 'sd'.$myuserl;
	  $thisclass = 'read_tree'; 

	  $out = GetGlobal('controller')->calldpc_method_use_pointers('cache.getcache',  
				                          array(0=>&$uealias,
									            1=>&$ext,
											    2=>&$thisclass,
												3=>&$this,
												4=>&$group,
												5=>null,
												6=>null,
												7=>null,
												8=>null,
												9=>null,
												10=>null,
												11=>&$cacheyes) 		  
											);	
											
	  /*$this->t_stree->stop('stree');
	  
	  $__USERAGENT = GetGlobal('__USERAGENT');
		
      switch ($__USERAGENT) {		  
	     case 'SH'   :
		 case 'CLI'  : echo $group, " : " , $this->t_stree->value('stree') , "\n";
		               break;
	     case 'HTML' :
	     default     : if (paramload('SHELL','debug')) {
	                     echo "<br>stree " , $this->t_stree->value('stree'); 											  
	                   }	
	  } */
	  return ($out);	
	} 
	
	//read tree table
	function read_tree($g=null) {
	   
	   if (strlen(trim($g))>0) {
	     $group = explode("^",$g);   //print_r($group);
	     $mg = count($group);
	     $depth = ($mg ? $mg : 0); //echo 'DEPTH:',$depth;
	   }
	   else
	     $depth = 0;	 
	   
	                   //distinct = no select
	   //$sSQL = 'select CTGLEVEL2 from ' . $this->T_category . " where CTGLEVEL1='ÊÁÔÇÃÏÑÉÅÓ ÅÉÄÙÍ'";
	   
	   switch ($depth) {
	       case 1 : $sSQL = "select CTGLEVEL3,CTGLEVEL2 from " . $this->T_category; break;
		   case 2 : $sSQL = "select CTGLEVEL4,CTGLEVEL3,CTGLEVEL2 from " . $this->T_category; break;
		   case 3 : $sSQL = "select CTGLEVEL5,CTGLEVEL4,CTGLEVEL3,CTGLEVEL2 from " . $this->T_category; break;
		   case 4 : $sSQL = "select null from "      . $this->T_category; break;
		   default: $sSQL = "select CTGLEVEL2 from " . $this->T_category; break;
	   }
	   $sSQL .= ' where '; 
	   switch ($depth) {
	       case 4 : 
	       case 3 : $sSQL .= "CTGLEVEL4='" . $group[2] . "' and ";
		   case 2 : $sSQL .= "CTGLEVEL3='" . $group[1] . "' and ";
		   case 1 : $sSQL .= "CTGLEVEL2='" . $group[0] . "' and ";
		   default: if (seclevel('SENPTREEADMIN_',$this->userLevelID)) {
		              $sSQL .= "CTGLEVEL1='ÊÁÔÇÃÏÑÉÅÓ ÅÉÄÙÍ'";
					}
					else {
					  $sSQL .= "(CTGLEVEL1='ÊÁÔÇÃÏÑÉÅÓ ÅÉÄÙÍ' and CTGLEVEL2 not like 'LOST+FOUND')"; 
					}  
	   }	
	   //echo $sSQL;   
	   //var_dump($this->exclude);
	   
       //cache queries 
	   if ($this->cachequeries) $result = $this->sen_db->CacheExecute($this->cachetime,$sSQL);
                           else $result = $this->sen_db->Execute($sSQL);
						   	
						   					   
	   if ($result) {      

	     while(!$result->EOF) {
		 
		   $f = $result->fields[0];
		   $_g = implode("^",array_reverse($result->fields)); //echo $_g,"<br>";
		 
	       if ( (trim($f)) && (!in_array(trim($_g),$this->exclude)) ) 
		     $res[$f] = $f;
		   
		   $result->MoveNext();
		   $i+=1;
	     }   	
		    
	     return ($this->distinct($res));
	   }
	}
	
	//cache search
	function search($text,$group=null,$type=null,$case=null) {
	
	  $uealias = $type . "_" . $group . "_" . $text;
	  $ext = 'ss'.$this->userLevelID;
	  $thisclass = 'senptree.search_sen';
	  //1st
	 /* $out = calldpc_method_use_pointers('cache.getcache',  
				                          array(0=>$uealias,
									            1=>&$ext,
											    2=>&$thisclass,
												3=>&$this,
												4=>&$text,
												5=>&$group,
												6=>&$type,
												7=>&$case))  
											);	*/  
	  //2nd									
      //$out = getcache($uealias,$ext,$thisclass,$this,$text,$group,$type,$case);
	  //3nd
	  //$out = calldpc_method($uealias,$ext,'cache.getcache_method use'."$thisclass+$this+$text+$group+$type+$case");
	  //4th kai to louri ths manas
  	  $ar = array(0=>&$text,1=>&$group,2=>&$type,3=>&$case); 
	  $out = GetGlobal('controller')->calldpc_method_use_pointers('cache.getcache_method_use_pointers',  
				                          array(0=>$uealias,
									            1=>$ext,
											    2=>$thisclass,
												3=>$ar));		  
	  
	  return ($out);	
	} 	

	function search_sen($text,$group=null,$type=null,$case=0) { 
	
	   //echo '>>>>',$text,'.',$group,'.',$type; 	   

   	   $userview_attr = (10 + $this->userLevelID);
		
       $searchcriteria = array(0=>"CTGLEVEL2",1=>"CTGLEVEL3",2=>"CTGLEVEL4",3=>"CTGLEVEL5");
       $terms = explode (" ", $text, 5); //5 = terms limit
	   //print_r($terms);print $type;
	   
	   switch ($type) {
	       case $this->anyterms : // OR
                                  reset($terms);
						          foreach ($terms as $word_no => $word) {
								  
		                            //unlocalize............in case of other lan
		                            if (($clanguage=getlocal())!=$this->deflan)
								      $transtext = unlocalize($word,getlocal(),$this->deflan,1);
								    else
								      $transtext = $word;
									  								  	
								    $mytext = '%'.$transtext.'%'; 
									$search_SQL .= "(" . $this->choiceSQL($searchcriteria,'OR','LIKE',$this->sen_db->qstr($mytext),$case) . ")";			   
												   
									if ($terms[$word_no+1]) $search_SQL .= " OR ";
	                              }								  
	                              break;
           case $this->allterms : // AND
                                  reset($terms);							   
						          foreach ($terms as $word_no => $word) {	
								  
		                            //unlocalize............in case of other lan
		                            if (($clanguage=getlocal())!=$this->deflan)
								      $transtext = unlocalize($word,getlocal(),$this->deflan,1);
								    else
								      $transtext = $word;									  
								  
								    $mytext = '%'.$transtext.'%';								  
									$search_SQL .= "(" . $this->choiceSQL($searchcriteria,'OR','LIKE',$this->sen_db->qstr($mytext),$case) . ")";			   
																						   
									if ($terms[$word_no+1]) $search_SQL .= " AND ";
	                              }							   		
	                              break;
		   default              :						  
           //case $this->asphrase : // AS IS = default
		                          //unlocalize............in case of other lan
		                          if (($clanguage=getlocal())!=$this->deflan)
								    $transtext = unlocalize($text,getlocal(),$this->deflan,1);
								  else
								    $transtext = $text;	
									
	                              $mytext = '%'.$transtext.'%';
								  $search_SQL = $this->choiceSQL($searchcriteria,'OR','LIKE',$this->sen_db->qstr($mytext),$case);			   
	   }
  	   //print $search_SQL;			
		
		//except hidden dirs from this user 
        $sSQL = "SELECT CTGLEVEL2,CTGLEVEL3,CTGLEVEL4,CTGLEVEL5 FROM " . $this->T_category; 
	    $sSQL .=" WHERE ( " .  $search_SQL . ")";		
        if (seclevel('SENPTREEADMIN_',$this->userLevelID)) {
		  $sSQL .= " AND CTGLEVEL1='ÊÁÔÇÃÏÑÉÅÓ ÅÉÄÙÍ'";
		}
		else {
		  $sSQL .= " AND CTGLEVEL1='ÊÁÔÇÃÏÑÉÅÓ ÅÉÄÙÍ' AND CTGLEVEL2 NOT LIKE 'LOST+FOUND'"; 
		}
		//search specific
		if (($group) && ($group!=localize('_ALL',getlocal()))) {
		      $sSQL .= " AND CTGLEVEL2='" . $group . "'";  //only CTGLEVEL2 !!!
		}					  		  

        if ($this->cachequeries) $res = $this->sen_db->CacheExecute($this->cachetime,$sSQL);
                            else $res = $this->sen_db->Execute($sSQL);
        //DEBUG
        //if (seclevel('_DEBUG',$this->userLevelID)) echo $sSQL;		   
        //echo 'SENTREE:',$sSQL;
		
        if ($res)  {			   
		   
       	     $mysearch = new search;
		
	          while (!$res->EOF) {
			       $a = null;
				  
                   //$a[0] = $res->fields[0]; //echo $a[0],">";
                   //$a[1] = $res->fields[1]; //echo $a[1],">";
                   //$a[2] = $res->fields[2]; //echo $a[2],">";
                   //$a[3] = $res->fields[3]; //echo $a[3],"<br>";
				   
				   foreach ($res->fields as $id=>$f) {
				   
					  $a[] = $f;
					  $mypath = $this->set_tree_path($a);
					  //find and check         /*was text*/
				      if (($mysearch->find($f,$transtext)) && (!in_array($mypath,$this->exclude))) {
					  
					     //$a[] = $f;
	                     $result[$f] = $mypath;//$this->set_tree_path($a);
						 break;
					  }
					  //$a[] = $f;
				   }
				                                   
                   $res->MoveNext();   
			  }
			  
			  unset ($mysearch);
        }

        //print_r($this->distinct($result));
		return ($this->distinct($result));
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
    
    function isparent($group=null) {
	   
	    if ($this->alias) $group = $this->alias; 

		if (is_array($this->read_sentree($group))) return 1;
		                                      else return 0;
	}	

    //get depth of group	
    function get_treedepth($group=null) {  
	
	    if ($this->alias) $group = $this->alias;
	
        $splitx = explode ("^", $group);         

        return (count($splitx));
    }	
	

    function analyzedir($group,$startup=0) {

        $adir = array();
		
        if ($startup) 
		  $adir[] = $this->home; //set home
  
        $splitx = explode ("^", $group);         
		
		foreach ($splitx as $id=>$category)
		  $adir[] = $category;


        //print_r ($adir);
        return ($adir);
    }


    function view_analyzedir($group,$startup=0,$symbol=null) { 
		$t = 'senvp';//$this->dirview; //GetReq('t'); 
		$g = GetReq('g');
		$a = GetReq('a');
		
		//select symbol		
		if ($symbol) $dmark = $symbol;
		        else $dmark = $this->rightarrow;		
		//analyze dir		
        $adirs = $this->analyzedir($group,$startup);
		
		
		//startup meters
		$max = count($adirs)-1; 
		if ($startup) $m = 1;
		         else $m = 0;
		
		foreach ($adirs as $id=>$cname) {

		  //localization............................
		  if (($clanguage=getlocal())!=$this->deflan)
		    $locname = localize($cname,$clanguage);
		  else  
		    $locname = $cname;	
		  
		  if ($id<$max) {
             if ($cname != $this->home) {
		   
		       if ($id>$m) $curl .= "^" . $cname;
			          else $curl .= $cname;
			   $mygroup = urlencode($curl);
			   
			   $aprint .= "<A href=\"" . seturl("t=$t&a=&g=$mygroup") . "\">";
		     }	
	         else {
   	           $aprint .= "<A href=\"" . seturl("t=") . "\">";
		     }	
		   
             $aprint .= $locname;//$cname;
             $aprint .= "</A>&nbsp;" . $dmark . "&nbsp;";		   	   	
		  }
		  else {
             //current directory   
             $aprint .= "<B>$locname</B>";//"<B>$cname</B>";
             $aprint .= "&nbsp;" . $dmark . "&nbsp;";			   
		  }
		}	
		  
        return ($aprint);
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
		$t = 'senvp';//GetReq('t');		
		$ar = urlencode(GetReq('a'));
		$gr = urlencode(GetReq('g')); 
		
        $filename = seturl("t=$t&a=$ar&g=$gr&p=$p");	

        //error message
        $toprint = setError($sFormErr);	
  
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
        $attr[] = "right;100%;"; 		    			

		$winb = new window('',$data,$attr);
		$toprint .= $winb->render("center::100%::0::group_dir_title::right::0::0::");
		unset ($winb);			
  
        return ($toprint);
     }
	 
   /////////////////////////////////////////////////////////////////
   // generate user selection list
   /////////////////////////////////////////////////////////////////
   function selectUser($select=0) {

     $levels = get_seclevels(); //is in sysdb
	          //????calldpc_method('senusers.get_seclevels'); //print_r($levels);
   
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
   
   function get_exclude_trees() {
   
     $ret = GetPreSessionParam('sexclude');
	 //print_r($ret);
	 
	 if (is_array($ret)) {
	 
       return ($ret);
     }
	 else {
       $file = paramload('SHELL','prpath')."exclude_sen.txt";
   
       if (file_exists($file)) {
         $f = fopen($file,"r");
	     $data = fread($f,filesize($file));
	     fclose($f);
	   
	     $ret = explode(",",$data);
	   
	     SetPreSessionParam('sexclude',$ret);
	     //print_r($ret);
	     return ($ret); 	   
	   }
	 }
   }
   
   function set_viewable() {
     
	 $g = GetReq('g');
	 
	 $group = explode("^",$g);
	 $cat = $group[count($group)-1];
	 //echo $cat.'>>>>>>>>>>>>>>>>'; 
	 
     $file = paramload('SHELL','prpath')."exclude_sen.txt";
	 	  
     if (file_exists($file)) {
	 
         $f = fopen($file,"a");
	     $data = fwrite($f,",".$g);
	     fclose($f);   
	 }	  
   }

};
}
?>