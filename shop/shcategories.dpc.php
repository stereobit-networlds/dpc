<?php
$__DPCSEC['SHCATEGORIES_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("SHCATEGORIES_DPC")) && (seclevel('SHCATEGORIES_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHCATEGORIES_DPC",true);

$__DPC['SHCATEGORIES_DPC'] = 'shcategories';

$__EVENTS['SHCATEGORIES_DPC'][0]='shcategories';
$__EVENTS['SHCATEGORIES_DPC'][1]='category';
$__EVENTS['SHCATEGORIES_DPC'][2]='openf';

$__ACTIONS['SHCATEGORIES_DPC'][0]='shcategories';
$__ACTIONS['SHCATEGORIES_DPC'][1]='category';
$__ACTIONS['SHCATEGORIES_DPC'][2]='openf';

$__LOCALE['SHCATEGORIES_DPC'][0]='SHCATEGORIES_DPC;Categories;Κατηγορίες';

class shcategories {

    var $title, $path, $catpath, $catfkey, $cext, $initfile, $cfolder;
	var $menu, $openfolder, $closefolder;	

	function shcategories() {
	  $GRX = GetGlobal('GRX');		
	
	  $this->title = localize('SHCATEGORIES_DPC',getlocal());	
	  
	  if ($remoteuser=GetSessionParam('REMOTELOGIN')) 
		  $this->path = paramload('SHELL','prpath')."instances/$remoteuser/";	
	  else
		  $this->path = paramload('SHELL','prpath');	
	  
	  $this->catpath = $this->path . paramload('SHCATEGORIES','path').'/';//echo $this->catpath;
	  //echo paramload('CATEGORIES','path'),'>';
	  $this->catfkey = paramload('SHCATEGORIES','fkey')?paramload('SHCATEGORIES','fkey'):0;
	  $this->cext = paramload('SHCATEGORIES','extension')?paramload('SHCATEGORIES','extension'):'txt';
	  $this->initfile = paramload('SHCATEGORIES','init')?paramload('SHCATEGORIES','init'):'categories';	 	    
	  
	  $this->cfolder = paramload('SHCATEGORIES','path')?paramload('SHCATEGORIES','path').'/':'';
	  
      if ($GRX) {
			 $this->outpoint = loadTheme('point');
			 $this->openfolder = loadTheme('openfolder');			 
			 $this->closefolder = loadTheme('closefolder');				 
			 $this->bullet = loadTheme('bullet');
             $this->rightarrow = loadTheme('rarrow');
			 
			 if (remote_paramload('SHCATEGORIES','resources',$this->path)) {
               //$this->resourcepath = paramload('SHELL','urlbase') . paramload('DIRECTORY','resources');	 
               $ip = $_SERVER['HTTP_HOST'];
               $pr = paramload('SHELL','protocol');	
               $subdir = paramload('ID','hostinpath');				   	   
	           $this->resourcepath = $pr . $ip . $subdir . remote_paramload('SHCATEGORIES','resources',$this->path);	 			   
			   $this->restype = remote_paramload('SHCATEGORIES','restype',$this->path);
			 }
			 else  
			   $this->resourcepath = null;
	  }
	  else {
			 $this->outpoint = "|";
			 $this->openfolder = "[+]";			 
			 $this->closefolder = "[-]";
			 $this->bullet = "&nbsp;";
	         $this->rightarrow = ">";
			 
             $this->resourcepath = null;	 
	  }	 
	  
	  //$this->exclude = $this->get_exclude_trees();		   
      $this->home = localize(paramload('SHELL','rootalias'),getlocal()); 
	  $this->menu = paramload('CATEGORIES','menu');	  
	}  
	
	function event($event=null) {
	
	    switch ($event) {
          case 'openf' : 
		                        break;			
          case 'shcategories' :
		  default             :								  
        }			
    }
	
	function action($action=null) {	
	
	    switch ($action) {	
          case 'openf' : $out .= $this->show_subcategories();
		                        break;		
          case 'shcategories' :
		  default             :								  
        }	
		
		return ($out);		
    }		
	
	function show_categories($cmd=null,$tree=null,$list=null,$nohead=0) {
	
	    $tcmd = ($cmd?$cmd:'cats');	
		if (trim(GetReq('cat'))){
		  $cstring = explode('^',GetReq('cat'));//print_r($cstring);
  	      $depth = count($cstring);		
		}  
		else {
		  $cstring = null;  
	      $depth = 0;				  
		}  
        //echo $depth,'>';
		$selection = $cstring[$depth-1];
		
		//browse  
		if ($tree) {
		if (is_array($cstring)) {
		  for ($c=0;$c<$depth;$c++) {
		  
		    //check virtual links	(cats who see other cats)
		    $parts = explode('~',$c);
            $names = $parts[0];
		    $link = $part[1];//if exist then modify cmd 			  
		  
		    $leaf = $cstring[$c];
			$leaf_cat = $cstring[$c-1];
			$leaf_link = ($cstring[$c-1])?$cstring[$c-1].'^'.$cstring[$c]:$cstring[$c];
			
		    //check @names and remove it form titles
		    if (strstr($leaf,'@')) {
		      $p = explode('@',$leaf);
		      $leaf_title = str_replace('_','',$p[0]);
		    }	
		    else
		      $leaf_title = str_replace('_',' ',$leaf);				
					  
		    $cfile =  $this->catpath . str_repeat('_',$c).trim(str_replace('_',' ',$leaf)) .'.'. $this->cext; 
			//echo $cfile;
		    if (is_readable($cfile)) { 
		      if (!$nohead) { 
		      $closefolder = seturl("t=openfolder&cat=$leaf_cat",$this->closefolder);		  
		      $closecat = seturl("t=$tcmd&cat=$leaf_link&sel=".$leaf,'<b>'.$leaf_title.'</b>');
		  	  
		      $leaf_data[] = $closefolder . $closecat . '<br>';
			  $leaf_attr[] = "left;100%";
			
		      $w = new window('',$leaf_data,$leaf_attr);
		      $out .= $w->render("center::100%::0::group_article_body::left::0::0::");
		      unset($w);	
			
			  unset ($leaf_data); unset ($leaf_attr);
              }
			}
		  }	
		}
		//browse	
		}			  
		
		if ($depth) {
		  $us = ($depth ? str_repeat('_',$depth-1):null);
		  $myfile =  $this->catpath . $us .trim(str_replace('_',' ',$selection)) .'.'. $this->cext;  
		}  
		else
	      $myfile =  $this->catpath . $this->initfile .'.'. $this->cext;		
		
		if (!is_readable($myfile)) {
		  /*if (($depth-2)<=0) 
		    $d = 0;
		  else
		    $d = $depth-2;*/
		  $myfile = $this->catpath . @str_repeat('_',$depth-2).trim(str_replace('_',' ',$cstring[$depth-2])) .'.'. $this->cext;
		}  
		  
		  
	    $contents =  @file_get_contents($myfile);
		$cats = explode(",",$contents);	
		
		foreach ($cats as $id=>$c) {
		
		  //check virtual links	(cats who see other cats)
		  $parts = explode('~',$c);
          $names = $parts[0];
		  $link = $part[1];//if exist then modify cmd 	
		
		  $ct = explode(';',$names); 
		  $ck = str_replace(' ','_',$ct[$this->catfkey]);
		  $cd = $ct[getlocal()];		  
		  
		  //check @names and remove it form titles
		  if (strstr($cd,'@')) {
		    $p = explode('@',$cd);
		    $title = $p[0];
		  }	
		  else
		    $title = $cd;		  
		  
		  $ufile = $this->catpath . str_repeat('_',$depth).trim(str_replace('_',' ',$ck)) .'.'. $this->cext;
		  //echo $ufile;
		  if (is_readable($ufile)) {
		    $go = true;
		    $presel = GetReq('cat')?GetReq('cat').'^'.$ck:$ck;	
		  }
		  else {
		    $go = false;
		    $presel = GetReq('cat'); //as is
		  }	

		  $openlink = ($link?'&clink='.$link:null);		  
		  $openfolder = seturl("t=openfolder&cat=$presel",$this->openfolder);		  
		  $opencat = seturl("t=$tcmd&cat=$presel&sel=".$ck.$openlink,$title);
		  
	 	  $mydata = $openfolder . $opencat . '<br>';
		  if ($list) {
			  
             $w = new window('',$mydata);
             $out .= $w->render("center::100%::0::group_article_body::left::0::0::");
		     unset($w);				  
		  }
		  else {			  
            $data[] = $mydata;
		    $attr[] = "left;10%";
		  }
		  
		}
		
		if (!$list) {
		  $w = new window('',$data,$attr);
		  $out .= $w->render();
		  unset($w);
		}
		
		return ($out);
	}	
	
	//?????
	function open_folder($cmd=null) {
	
	    $tcmd = ($cmd?$cmd:'cats');	
		if ($d1 = GetReq('subcat'))
		  $selected_cat = '_'.$d1;
		else  
		  $selected_cat = GetReq('cat')?GetReq('cat'):$this->initfile;
		  
        $file =  $this->catpath . str_replace('_',' ',$selected_cat).'.'.$this->cext;
		//echo $file;
	    $contents =  file_get_contents($file);
		$cats = explode(",",$contents);			
				
		foreach ($cats as $id=>$c) {
		
		  $ct = explode(';',$c); 
		  $ck = str_replace(' ','_',$ct[$this->catfkey]);
		  $cd = $ct[getlocal()];
		  
		  $data[] = seturl("t=$tcmd&cat=$ck",$cd);
		  $attr[] = "left;10%";
		}
		
		$w = new window('',$data,$attr);
		$out = $w->render();
		unset($w);
		
		return ($out);				  
	}

	//under construction 
	function show_subcategories($cmd=null,$maincat=null,$style=null) {
	
        $separator = loadTheme('separator');		
	    $tcmd = ($cmd?$cmd:'cats');		
	
	    if ($maincat)
		  $selected_cat = $maincat;
		else
	      $selected_cat = GetReq('cat');
		  
		$depth = count(explode("^",$selected_cat))-1;  
		  
		//$mfile = $this->cfolder . $this->initfile .'.'. $this->cext;   
		//echo $this->cfolder . $this->initfile,'<br>';
			  
		//$titlecat = get_selected_option_fromfile($selected_cat,$mfile,0,$this->cext);
		
        $file =  $this->catpath . str_repeat('_',$depth).trim(str_replace('_',' ',$selected_cat)).'.'.$this->cext;	
	    echo $file;
	    if (is_readable($file)) {

	      $contents =  file_get_contents($file);
		  $cats = explode(",",$contents);
		  
		  //$ct = explode(';',$c); 
		  //$ck = str_replace(' ','_',$ct[$this->catfkey]);
		  //$cd = $ct[getlocal()];
		
		  switch ($style) {
		    
			case 'LIST' : break;
			
		    case 'LINE' :
		    default :
			
			if (count($cats)>0)
			  $mod = round(100/count($cats));
		
		    //echo $mod,';;;;;';    
	        $data[] = $separator;
	        $attr[] = "left;1%";				
			  
		    foreach ($cats as $id=>$c) {
			
		      //check virtual links	(cats who see other cats)
		      $parts = explode('~',$c);
              $names = $parts[0];
		      $link = $part[1];//if exist then modify cmd 	
		
		      $ct = explode(';',$names); 
		      $ck = str_replace(' ','_',$ct[$this->catfkey]);
		      $cd = $ct[getlocal()];		  
		  
		      //check @names and remove it form titles
		      if (strstr($cd,'@')) {
		       $p = explode('@',$cd);
		       $title = $p[0];
		      }	
		      else
		       $title = $cd;					
			   
		      $openlink = ($link?'&clink='.$link:null);		  
		      $openfolder = seturl("t=openfolder&cat=$presel",$this->openfolder);		  
		      $opencat = seturl("t=$tcmd&cat=$presel&sel=".$ck.$openlink,$title);
		  
	 	      $mydata = $openfolder . $opencat . '<br>';			   
		
		      //$data[] = seturl("t=klist&cat=$c",$c);
		      $data[] = $openfolder . $opencat;//seturl("t=$tcmd&cat=$selected_cat&subcat=$ck",$cd);
		      $attr[] = "left;$mod%";
			  
	          $data[] = $separator;
	          $attr[] = "left;1%";				  
		    }
			
		
		    $w = new window('',$data,$attr);
		    $out = $w->render("center::100%::0::group_katalog::left::0::0::");
		    unset($w);
		  }
		}
		
		return ($out);
	}	
	
	function show_all_categories($cmd=null) {
	    
		$sel_cat = is_numeric(GetReq('cat'))?GetReq('cat'):-1;
		$sel_scat = is_numeric(GetReq('subcat'))?GetReq('subcat'):-1;
	
	    $tcmd = ($cmd?$cmd:'cats');	
	
	    $file =  $this->catpath . $this->initfile .'.'. $this->cext;
		
	    $contents =  file_get_contents($file);
		$cats = explode(",",$contents);	
		
		foreach ($cats as $id=>$c) {
		
		  //check virtual links	(cats who see other cats)
		  $parts = explode('~',$c);
          $names = $parts[0];
		  $link = $part[1];//if exist then modify cmd 	
		
		  $ct = explode(';',$names); 
		  $ck = str_replace(' ','_',$ct[$this->catfkey]);
		  $cd = $ct[getlocal()];			  	  
		  
		  //check @names and remove it form titles
		  if (strstr($cd,'@')) {
		    $p = explode('@',$cd);
		    $title = $p[0];
		  }	
		  else
		    $title = $cd;		
			
		  $openlink = ($link?'&clink='.$link:null);		  
		  $mydata = "<b>" . seturl("t=$tcmd&cat=$ck".$openlink,$title) . "</b><br>";
		  if ($ck==$sel_cat) {
		    $mydata = writecl($mydata,'#FFFFFF','#FF0000');
			$selected = true;
	      }		
		  else
		    $selected = false;
		  
	      $subfile =  $this->catpath . trim(str_replace('_',' ',$ck)) .'.'. $this->cext;	
		  $subcontents =  @file_get_contents($subfile);	
		  $subcats = explode(",",$subcontents);	
		  //print_r($subcats);
		  	  
		  foreach ($subcats as $sid=>$sc) {
		  
		    //check virtual links	(cats who see other cats)
		    $parts = explode('~',$sc);
            $names = $parts[0];
		    $link2 = $part[1];//if exist then modify cmd 	
		
		    $ct2 = explode(';',$names); 
		    $ck2 = str_replace(' ','_',$ct2[$this->catfkey]);
		    $cd2 = $ct2[getlocal()];			  
		  
		    //check @names and remove it form titles
		    if (strstr($cd2,'@')) {
		      $p = explode('@',$cd2);
		      $title2 = $p[0];
		    }	
		    else
		      $title2 = $cd2;		  		  

		    $openlink2 = ($link2?'&clink='.$link2:null);		  
		    $openfolder = seturl("t=openfolder&cat=$ck&subcat=$ck2",$this->openfolder);		  
		    $link = seturl("t=$tcmd&cat=$ck&subcat=$ck2".$openlink2,$title2);
			
		    if (($ck2==$sel_scat) && $selected) 
			  $mydata .= $openfolder . writecl($link,'#FFFFFF','#FF0000') . "<br>";
			else
		      $mydata .= $openfolder . $link . "<br>";		  
		  }
		  
          $data[] = $mydata;
		  $attr[] = "left;10%";
		  
		  unset($mydata);		    
		}
		
		$w = new window('',$data,$attr);
		$out = $w->render();
		unset($w);
		
		return ($out);	    
	}
	//?????
	function show_categories_tree($cmd=null,$tree=null) {
	
	    $tcmd = ($cmd?$cmd:'cats');			
	
	    $file =  $this->catpath . $this->initfile .'.'. $this->cext;
		
	    $contents =  file_get_contents($file);
		$cats = explode(",",$contents);

		foreach ($cats as $id=>$c) {
		
		  $ct = explode(';',$c); 
		  $ck = str_replace(' ','_',$ct[$this->catfkey]);
		  $cd = $ct[getlocal()];			
			
	      $openfolder = seturl("t=openfolder&cat=$ck",$this->openfolder);		
		  $data[] = seturl("t=$tcmd&cat=$ck",$cd);
		  
		  $file =  $this->catpath . trim(str_replace('_',' ',$ck)) .'.'. $this->cext;
		  
	      if (is_readable($file)) {
	        $contents =  file_get_contents($file); //echo $file;
		    $subcats = explode(",",$contents);
			if (is_array($subcats)) {
		      foreach ($subcats as $sid=>$sc) {
			  
		        $ct2 = explode(';',$sc); 
		        $ck2 = str_replace(' ','_',$ct2[$this->catfkey]);
		        $cd2 = $ct2[getlocal()];				  
			  
		        $data[] = $openfolder . seturl("t=$tcmd&cat=$ck&subcat=$ck2",$cd2);
		      }
			}
		  }		  
		}	
		
		foreach ($data as $menuitem)
		  $menu .= $menuitem . "<br>";
		  
		//$w = new window(localize('_type2',getlocal()),$menu);
		//$out = $w->render("center::100%::0::group_article_head::left::0::0::");
		//unset($w);	
		
		return ($menu);	  
	  
	}	
	
	function show_tree($cmd=null,$myleaf=null,$mydepth=null,$nbsp=2,$root=null) {
	
	    $tcmd = ($cmd?$cmd:'cats');		
	    $tcat = ($root?null:GetReq('cat'));		
				
		if (trim($tcat)){
		  $cstring = explode('^',$tcat);
		  //print_r($cstring);
  	      $depth = count($cstring);		
		}  
		else {
		  $cstring = null;  
	      $depth = 0;				  
		} 
		    		
		 
        //echo $depth,'>';
		$selection = ($myleaf?$myleaf:$cstring[$depth-1]);			
		if ($mydepth) $depth = $mydepth;
		else $depth = 0;
		
		if (($myleaf) && ($depth>0)) 
		  $myfile =  $this->catpath . str_repeat('_',$depth-1).trim(str_replace('_',' ',$myleaf)) .'.'. $this->cext;  
		else
	      $myfile =  $this->catpath . $this->initfile .'.'. $this->cext;		
		//echo $myfile,'<br>';
		/*if (!is_readable($myfile) && $depth>0) {
		  $myfile = $this->catpath . str_repeat('_',$mydepth-2).trim($cstring[$depth-2]) .'.'. $this->cext;
		  echo $myfile;
		} */ 		
		//if ($mydepth>1) echo $myfile;
	    $contents =  @file_get_contents($myfile);
		$cats = explode(",",$contents);

		foreach ($cats as $id=>$c) {
		
		  //check virtual links	(cats who see other cats)
		  $parts = explode('~',$c);
          $names = $parts[0];
		  $link = $parts[1];//if exist then modify cmd 		
		  //print_r($parts);	
		
		  $ct = explode(';',$names); 
		  $ck = str_replace(' ','_',$ct[$this->catfkey]);
		  $cd = $ct[getlocal()];	
		  
		  //check @names and remove it form titles
		  if (strstr($cd,'@')) {
		    $p = explode('@',$cd);
		    $title = $p[0];
		  }	
		  else
		    $title = $cd;		  
		  
		  if ($depth>0) {
		  
		    $presel = ''; 
			
		    reset($cstring);
			for ($i=0;$i<$depth;$i++) {
			    $presel .= $cstring[$i].'^';				  
			}   
            //echo '<br>',$presel,'-';	
		    $ufile = $this->catpath . str_repeat('_',$depth).trim(str_replace('_',' ',$ck)) .'.'. $this->cext;
		    //echo $ufile;
		    if (is_readable($ufile)) {
		      $go = true;
		      //$presel = GetReq('cat')?GetReq('cat').'^'.$ck:$ck;	
			  $presel .= $ck;  
			  //echo $ufile,'<br>';
		    }
		    else {
		      $go = false;
			  //$presel = substr($presel,0,-1);//exclude ^
		      $presel = GetReq('cat'); //as is
              //echo '-',$presel,'<br>';				  
		    }				
		    //$presel = GetReq('cat')?GetReq('cat').'^'.$ck:$ck; //as is  			  		
		  }	
		  else
		    $presel = $ck;	
		
		  $openlink = ($link?'&clink='.$link:null);
		  $openfolder = seturl("t=openfolder&cat=$presel",$this->openfolder);		  
		  $opencat = seturl("t=$tcmd&cat=$presel&sel=".$ck.$openlink,$title);
		  
	 	  $menu .= str_repeat('&nbsp;',$depth*$nbsp). $openfolder . $opencat . '<br>';		
          //if ($mydepth>1) echo $ck,':',$cstring[$depth],'<br>';
		  if ($ck==$cstring[$depth]) $menu .= $this->show_tree($cmd,$ck,$depth+1); 
		}	
		
		
		return ($menu);	  
	  
	}	
	
	function show_sub_tree($cmd=null) {
	    $tcmd = ($cmd?$cmd:'cats');		
		if (trim(GetReq('cat'))){
		  $cstring = explode('^',GetReq('cat'));//print_r($cstring);
  	      $depth = count($cstring);		
		}  
		else {
		  $cstring = null;  
	      $depth = 0;				  
		}  
        //echo $depth,'>';
		if (is_array($cstring)) {//print_r($cstring);
		  $selection = $cstring[$depth-1];	
	       
	      //foreach ($cstring as $id=>$leaf) {//2 time with recussion ???
			//if ($leaf) $out .= $this->show_tree($cmd,$leaf,$id+1);		  
		  //}
		  			
		  //if ($leaf=array_pop($cstring)) //last element
		  if ($leaf=array_shift($cstring)) //first element
			$out .= $this->show_tree($cmd,$leaf,$depth-1,0);
		}
		return ($out);
	}	
	
	//not implemented....
    function show_selected_categories($showroot=null,$selcat=null) {
      $myselcat = $selcat?$selcat:GetReq('cat');
	  
	  if ($myselcat) {
	  
		$cstring = explode('^',$myselcat);//print_r($cstring);
  	    $depth = count($cstring);	
	    $selection = $cstring[$depth-1];	
        //echo $depth,'>';				  
	  
	  }
	  else {
	    if ($showroot) {//root categories..base
		  $cstring = null;  
	      $depth = 0;	
		    		
		}
	  }
    }	
	
    //get depth of group	
    function get_treedepth($group=null) {  
	
	    if (!$group) $group = GetReq('cat');
		$selection = GetReq('sel');
	
        $splitx = explode ("^", $group);
		
	    if ($selection!=array_pop($splitx)) 
		    $cats = explode ("^", $group.'^'.$selection);
		else
		    $cats = explode ("^", $group);
		         

        return (count($cats)-1);
    }		
	
    function analyzedir($group,$startup=0) {
        $sel = GetReq('sel');
		
	    //if ($selection= GetReq('sel')) 
		  //$group .= '^'.$selection;
        $adir = array();
		
        if ($startup) { 
		  $adir[] = $this->home; //set home
		}  
  
        $splitx = explode ("^", $group);         
		
		foreach ($splitx as $id=>$category) {
		  //check @names and remove it form titles
		  $p = explode('@',$category);
		  $adir[] = $p[0];
		}  
		  
		  
	    if (($sel) && ($sel!=$category)) {//last cat 
		   //check @names and remove it form titles
		   if (strstr($sel,'@')) {
		      $ps = explode('@',$sel);
		      $sel_title = $ps[0];//str_replace('_','',$p[0]);
		   }	
                   else $sel_title = $sel;

		  $adir[] = $sel_title;		  
            }

        //print_r ($adir);
        return ($adir);
    }
	
    function view_analyzedir($cmd=null,$prefix=null,$startup=0,$symbol=null) { 
		//$t = GetReq('t');//$this->dirview; //GetReq('t'); 
		$t = ($cmd?$cmd:GetReq('t'));	
		$g = GetReq('cat');
		$a = GetReq('a');
		
		//select symbol		
		if ($symbol) $dmark = $symbol;
		        else $dmark = $this->rightarrow;		
		//analyze dir		
        $adirs = $this->analyzedir($g,$startup);
		
		
		//startup meters
		$max = count($adirs)-1; 
		if ($startup) $m = 1;
		         else $m = 0;
		//print_r($adirs);
		foreach ($adirs as $id=>$cname) {

		  //localization............................
		 /* if (($clanguage=getlocal())!=$this->deflan)
		    $locname = localize($cname,$clanguage);
		  else  */
		    $locname = str_replace('_',' ',$cname);	
		  
		  if ($id<$max) {
             if ($cname != $this->home) {
		   
		       if ($id>$m) $curl .= "^" . $cname;
			          else $curl .= $cname;
			   $mygroup = $curl;//urlencode($curl);
			   
			   $aprint .= "<A href=\"" . seturl("t=$t&a=&cat=$mygroup") . "\">";
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
		  
        return ($prefix.$aprint);
    }	
	
    function tree_navigation($cmd=null,$prefix=null,$home=0) {
	
	    //if ($prefix) $home=0; else $home=1;
	
        //if ($this->nav_on) {		
   
           //directory path	
          $data2[] = $this->view_analyzedir($cmd,$prefix,$home);
          $attr2[] = "left;";

          if ($data2)  {
		     $win2 = new window('',$data2,$attr2);
		     $out .= $win2->render("center::100%::0::group_dir_headtitle::left::0::0::");
		     unset ($win2);
		  }  
		  
		//}				  
		  
		return ($out);
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
	

	function search_tree($text2find=null,$cmd='katalog',$myleaf=null,$mydepth=null,$presel=null) {
	    $tcmd = ($cmd?$cmd:'cats');	
		static $cstring = array();	
	
	    $menu_break = $break?$break:paramload('CATEGORIES','break');
	    $menu_fpro = paramload('CATEGORIES','fpro');	
	    $menu_itype = paramload('CATEGORIES','itype');	
		
		$selection = ($myleaf?$myleaf:$cstring[$depth-1]);			
		if ($mydepth) $depth = $mydepth;
		else $depth = 0;
		
		if (($myleaf) && ($depth>0)) {
		  $myfile =  $this->catpath . str_repeat('_',$depth-1).trim(str_replace('_',' ',$myleaf)) .'.'. $this->cext;    
		}  
		else {
	      $myfile =  $this->catpath . $this->initfile .'.'. $this->cext;						
		}  
			
		
	    $contents =  @file_get_contents($myfile);
		if (!$contents) return;
		
		$cats = explode(",",$contents);	
		
		$pyramid = 1; //default
		
		if ($pyramid) 
		  $first_break = round(count($cats)/$menu_break);//calculate...
		//echo $first_break;

		$first_time = 1;   
		  
		foreach ($cats as $id=>$c) {
		
		  //check virtual links	(cats who see other cats)
		  $parts = explode('~',$c);
          $names = $parts[0];
		  $link = $parts[1];//if exist then modify cmd 		
		  //print_r($parts);	
		
		  $ct = explode(';',$names); 
		  $ck = str_replace(' ','_',$ct[$this->catfkey]);
		  $cd = $ct[getlocal()];	 		  
		  
		  //check @names and remove it form titles
		  if (strstr($cd,'@')) {
		    $p = explode('@',$cd);
		    $title = $p[0];
		  }	
		  else
		    $title = $cd;		
			
		  if (!empty($cstring))	{
		   /* foreach ($cstring as $i=>$c) {//remove @ from titles
			  $cs = explode('@',$c);
			  $cst[] = $cs[0]; 
			}*/  
		    $category_path = str_replace('_',' ',implode($this->rightarrow,$cstring)).$this->rightarrow;			
		  }	
		  else
   		    $category_path = "";
			 
		  array_push($cstring,$ck);
		  $presel = implode("^",$cstring); 	 
			
		  $openlink = ($link?'&clink='.$link:null);
		  $openfolder = seturl("t=openfolder&cat=$presel",$this->openfolder);		  
		  $opencat = seturl("t=$tcmd&cat=$presel&sel=".$ck.$openlink,$title);

		  if ((stristr(strtoupper($title),$text2find)) || (stristr(strtolower($title),$text2find))) {		  
		    $menu .= $category_path . $opencat . "&nbsp;<hr>";
		  }
		  $menu .= $this->search_tree($text2find,'katalog',$ck,$mydepth+1,$presel);
		  
		  array_pop($cstring);
		  
		}  						   	
		
		/*$win2 = new window('',$menu);
		$out = $win2->render("center::100%::0::group_article_body::$myalign::0::0::");
		unset ($win2);		
		*/
	
        //$data = $this->show_categories($cmd);
		$out .= $menu?$menu:null; 
		
		return ($out);	  	
	}	
	
	function grx_menu($cmd=null,$align=null,$pyramid=null,$break=null) {
	   $tcmd = ($cmd?$cmd:'cats');		
	   $tcat = GetReq('cat');
	   $lang = getlocal()?getlocal():'0';

       //echo $this->get_color();		
				
	   if (trim($tcat)){
		  $cstring = explode('^',$tcat);
		  //print_r($cstring);
  	      $maincat = $cstring[0];		
	   } 	
	   $myalign = $align?$align:'center';
	   $pager = 1;
	
	   if ($this->menu) {
	   
	    $menu_break = $break?$break:paramload('CATEGORIES','break');
	    $menu_fpro = paramload('CATEGORIES','fpro');	
	    $menu_itype = paramload('CATEGORIES','itype');			
		
        $myfile =  $this->catpath . $this->initfile .'.'. $this->cext;
	    $contents =  @file_get_contents($myfile);
		$cats = explode(",",$contents);
		
		$pyramid = 1; //default
		
		if ($pyramid) 
		  $first_break = round(count($cats)/$menu_break);//calculate...
		//echo $first_break;

		$first_time = 1;   
		  
		foreach ($cats as $id=>$c) {
		
		  //check virtual links	(cats who see other cats)
		  $parts = explode('~',$c);
          $names = $parts[0];
		  $link = $parts[1];//if exist then modify cmd 		
		  //print_r($parts);	
		
		  $ct = explode(';',$names); 
		  $ck = str_replace(' ','_',$ct[$this->catfkey]);
		  $cd = $ct[getlocal()];	
		  
		  //check @names and remove it form titles
		  if (strstr($cd,'@')) {
		    $p = explode('@',$cd);
		    $title = $p[0];
		  }	
		  else
		    $title = $cd;		
			
		  $openlink = ($link?'&clink='.$link:null);
		  $openfolder = seturl("t=openfolder&cat=$presel",$this->openfolder);		  
		  $opencat = seturl("t=$tcmd&cat=$presel&sel=".$ck.$openlink,$title);
		  
		  $num = $id+1;
		  
          if ($ck==$maincat)
		    $icon = loadicon("/".$lang."s_".$menu_fpro.$num.$menu_itype,$title);
		  else 		  
		    $icon = loadicon('/'.$lang . $menu_fpro.$num.$menu_itype,$title);
		  $menu_item = seturl("t=$tcmd&cat=$ck&sel=".$ck.$openlink,$icon);
		  
		  $menu .= $menu_item;
		  
		  if (($pyramid) && ($first_time) && ($first_break==$pager)) {
		    $first_time = 0; //reset		  
		    $menu .= '<br>';
			$pager=1;			
		  }		  
		  elseif ($menu_break==$pager) {
		    $menu .= '<br>';
			$pager=1;
		  }	
		  else
		    $pager+=1;		  
		  
		}  						   
	   }
	   
       if ($menu)  {
		     $win2 = new window('',$menu);
		     $out = $win2->render("center::100%::0::group_article_body::$myalign::0::0::");
		     unset ($win2);
	   }
	   
	   return ($out);
	}

  function get_color($defcolor='000000') {

      $mycat = GetReq('cat');
   
      $file = $this->catpath.'colors.ini'; //echo $file;

      if (($mycat) && is_readable($file)) {

          $mycatarr = explode('^',$mycat);

          $colors = parse_ini_file($file); //print_r($colors);
          $sel = $colors[str_replace('_',' ',$mycatarr[0])];
          $ret = $sel?$sel:$defcolor;      

          return ($ret);
      }

      return ($defcolor); //black
  }	
  					
};
}
?>