<?php
$__DPCSEC['RCCATEGORIES_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("RCCATEGORIES_DPC")) && (seclevel('RCCATEGORIES_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCCATEGORIES_DPC",true);

$__DPC['RCCATEGORIES_DPC'] = 'rccategories';

$d = GetGlobal('controller')->require_dpc('shop/shcategories.dpc.php');
require_once($d);

$__EVENTS['RCCATEGORIES_DPC'][0]='cpcategories';
$__EVENTS['RCCATEGORIES_DPC'][1]='cats';
$__EVENTS['RCCATEGORIES_DPC'][2]='editcats';
$__EVENTS['RCCATEGORIES_DPC'][3]='openfolder';
$__EVENTS['RCCATEGORIES_DPC'][4]='savecats';
$__EVENTS['RCCATEGORIES_DPC'][5]='delcats';
$__EVENTS['RCCATEGORIES_DPC'][6]='addcats';
$__EVENTS['RCCATEGORIES_DPC'][7]='gencats';
$__EVENTS['RCCATEGORIES_DPC'][8]='makesqlcats';

$__ACTIONS['RCCATEGORIES_DPC'][0]='cpcategories';
$__ACTIONS['RCCATEGORIES_DPC'][1]='cats';
$__ACTIONS['RCCATEGORIES_DPC'][2]='editcats';
$__ACTIONS['RCCATEGORIES_DPC'][3]='openfolder';
$__ACTIONS['RCCATEGORIES_DPC'][4]='savecats';
$__ACTIONS['RCCATEGORIES_DPC'][5]='delcats';
$__ACTIONS['RCCATEGORIES_DPC'][6]='addcats';
$__ACTIONS['RCCATEGORIES_DPC'][7]='gencats';
$__ACTIONS['RCCATEGORIES_DPC'][8]='makesqlcats';

$__LOCALE['RCCATEGORIES_DPC'][0]='RCCATEGORIES_DPC;Categories;Κατηγορίες';

class rccategories extends shcategories {

    var $title, $path, $catpath, $catfkey, $cext, $initfile;
	var $post, $sqlok, $sqlcmd;

	function rccategories() {

      shcategories::shcategories();

	  $this->title = localize('RCCATEGORIES_DPC',getlocal());
	  $this->post = false;

	  $this->sqlok = 0;
	  $this->sqlcmd = null;
	}

	function event($event=null) {

	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//
	   /////////////////////////////////////////////////////////////

	    switch ($event) {
		  case "savecats":      $this->save_category();
		                        $this->post = true;
		                        break;
		  case "delcats" :      $this->delete_category();
		                        $this->post = true;
		                        break;
          case 'openfolder' :
		                        break;
          case 'cats' :
		                        break;
		  case 'gencats'  :
		                        break;
		  case 'makesqlcats':   //$this->generate_sql_categories(GetReq('keys'));
		                        break;
		  case 'addcats'  :
		                        break;
          case 'editcats' :
		                        break;
          case 'cpcategories' :
		  default             :
        }
    }

	function action($action=null) {

	   if (GetSessionParam('REMOTELOGIN'))
	     $winout = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title);
	   else
         $winout = setNavigator(seturl("t=cp","Control Panel"),$this->title);


	    switch ($action) {
		  case "delcats" :      $title = $this->title;
		                        $out .= $this->menu(1);
		                        $winout .= $this->editform();
		                        $out .= $this->show_tree('editcats',null,0,2,1);
		                        break;
		  case "savecats":      $title = $this->title;
		                        $out .= $this->menu(1);
		                        $winout .= $this->editform();
		                        $out .= $this->show_tree('editcats');
		                        break;

          case 'openfolder' :   $title = $this->tree_navigation('cats',null,0);
		                        $out .= $this->menu();
		                        $out .= $this->show_tree('editcats');
		                        $out .= $this->show_categories(null,1,1);
								//$out .= $this->show_sub_tree();
		                        break;
		  case 'gencats'  :     $title = 'Generate Categories';
		                        $out .= $this->generate_categories(GetReq('keys'));
		                        break;
		  case 'makesqlcats':   $title = 'Generate SQL Categories';
		                        $out .= $this->generate_sql_categories(GetReq('keys'));
		                        $this->msg = $this->sqlok . ' sql queries executed succesfully!';
		                        $out = $this->msg . '<br><br>' . $this->sqlcmd;
		                        break;
          case 'addcats' :      $add=true;
          case 'editcats':      $title = $this->tree_navigation('cats',null,0);
		                        $out .= $this->menu(1);
		                        $out .= $this->show_tree('editcats');
								$out .= $this->editform($add);
								$out .= $this->show_categories(null,1,1);
		                        break;
          case 'cats'    :      $title = $this->title;
		                        $out .= $this->show_tree('editcats');
								$out .= $this->show_categories(null,1,1);
		                        break;
          case 'cpcategories' :
		  default             :	$title = $this->title;
		                        $out .= $this->menu();
		                        $out .= $this->show_tree('editcats');
								$out .= $this->editform();
        }


		$win2 = new window($title,$out);
		$winout .= $win2->render("center::100%::0::group_dir_headtitle::left::0::0::");
		unset ($win2);


		return ($winout);
    }

	function form($cmd=null) {

		//test
		$out .= $this->show_tree('editcats');
		$out .= $this->show_categories(null,1);	//the above
		$out .= $this->show_sub_tree();
        $out .= $this->show_all_categories();

		return ($out);
	}

  function editform($add=false) {

     $sFormErr = GetGlobal('sFormErr');

     $myaction = seturl("t=savecats");

	 if ($this->post==true) {

	   (isset($this->msg)? $msg=$this->msg : $msg = "Data submited!");

	   $swin = new window("Post",$msg);
	   $out .= $swin->render("center::50%::0::group_win_body::center::0::0::");
	   unset ($swin);

	 }
	 else { //show the form plus error if any

       $out .= setError($sFormErr . $this->msg);

	   //if ($this->standalone)
	     //$out .= $this->show_directory("Load");
	   $depth = $this->get_treedepth();

	   //echo $depth,'>>>';

	   if ($add) {
	     $myfile = 'NEWCATEGORY';
		 $file = str_repeat('_',$depth) . $myfile . '.txt';
		 $editfile = 'write here!';
	   }
	   else {
	     $myfile = GetReq('sel')?GetReq('sel'):$this->initfile;
	     $file = str_repeat('_',$depth) . str_replace('_',' ',$myfile) . '.txt';
         $editfile = $this->loadfromfile($file);
	   }

	   $form = new form(localize('RCCATEGORIES_DPC',getlocal()), "RCCATEGORIES", FORM_METHOD_POST, $myaction, true);

	   $form->addGroup			("title",			"Title.");
	   $form->addGroup			("body",			"Body.");

       $form->addElement		("title", new form_element_text("Title",  "title",		$file,				"forminput",			90,				255,	0));

	   $form->addElement		("body",new form_element_textarea($file,  "body",$editfile,	"formtextarea",	80,	20));

	   // Adding a hidden field
	   $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "savecats"));

	   // Showing the form
	   $fout = $form->getform ();

	   //$fwin = new window(localize('AMAIL_DPC',getlocal()),$fout);
	   //$out .= $fwin->render();
	   //unset ($fwin);

	   $out .= $fout;

	 }


     return ($out);
  }

  function save_category() {

    $this->write2file(GetParam('title'),GetParam('body'));

  }

  function loadfromfile($filename) {

	 $file = $this->catpath . $filename;

     if ($fp = @fopen ($file , "r")) {

                 $ret = fread ($fp, filesize($file));
                 fclose ($fp);
     }
     else {
         $this->msg = $ret = "File reading error ($filename)!\n";
		 //echo "File reading error ($filename)!<br>";
     }

	 return ($ret);
  }


  function write2file($filename,$data) {

	 $file = $this->catpath . $filename;
	 //echo $file,$data;

     if ($fp = @fopen ($file , "w")) {
	    //echo $file,"<br>";
                 fwrite ($fp, $data);
                 fclose ($fp);
     }
     else {
         $this->msg = "File creation error ($filename)!\n";
		 //echo "File creation error ($filename)!<br>";
     }
  }

  function delete_category() {
      $selection = GetReq('sel');
	  $depth = $this->get_treedepth();

      $file =  $this->catpath . str_repeat('_',$depth) . $selection.'.'.$this->cext;
	  if (is_readable($file)) {
		//echo $file;
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

	      $subfile =  $this->catpath . str_repeat('_',$depth+1) . trim($ck) .'.'. $this->cext;
		  //echo $subfile,'<br>';
		  if (file_exists($subfile)) {
		    $this->msg = 'Sub categories exists. Delete them first!';
			return (false);
		  }
		}
	  }
	  if (@unlink($file)) {
  	    $this->msg = 'Category deleted!';
	    return true;
	  }
      $this->msg = 'File error!'."($file)";
	  return false;
  }

  function menu($adv=null) {

       if ($adv) {

         $cmd = seturl('t=addcats&cat='.GetReq('cat').'&sel='.GetReq('sel'),'Add Category') . '|';
         $cmd .= seturl('t=delcats&cat='.GetReq('cat').'&sel='.GetReq('sel'),'Delete Category') . '|';
	   }

       $cmd .= seturl("t=gencats","Generate categories");
	   //if (defined("RCKATEGORIES_DPC")) {
	     $cmd .= '|';
         $cmd .= seturl("t=makesqlcats","Convert to SQL");
	   //}

	   $myadd = new window('',$cmd);
	   $out .= $myadd->render("center::100%::0::group_article_selected::right::0::0::");
	   unset ($myadd);

	   return ($out);
  }

  //export text ... bug in first sub tree (write main cat 2 times)
  function generate_categories($keys=false,$current=null,$leaf=null,$depth=0) {

        //echo $current , '<br>';


		if ($leaf) {
		  $us = ($depth ? str_repeat('_',$depth-1):null);
		  $myfile =  $this->catpath . $us .trim($leaf) .'.'. $this->cext;
		}
		else
	      $myfile =  $this->catpath . $this->initfile .'.'. $this->cext;

		if (!is_readable($myfile)) {//last leaf
		  return ('^'.$leaf.'<br>');
		}

		if ($current)
		    $current .= '^' . $leaf;
		else
		    $current = $leaf;


	    $contents =  @file_get_contents($myfile);
		$cats = explode(",",$contents);

		foreach ($cats as $id=>$c) {
		  //check virtual links	(cats who see other cats)
		  $parts = explode('~',$c);
          $names = $parts[0];
		  $link = $part[1];//if exist then modify cmd

		  $ct = explode(';',$names);
		  $ck = $ct[$this->catfkey];
		  $cd = $ct[getlocal()];

		  //check @names and remove it form titles
		  if (strstr($cd,'@')) {
		    $p = explode('@',$cd);
		    $title = $p[0];
		  }
		  else
		    $title = $cd;

		  if ($keys) $ret = $ck;
		        else $ret = $title;

		  //if ($current==$leaf)
		  //$out .= $this->generate_categories($current,$ck,$depth+1);
		  //else

		  //curent . current when recursion????
          $out .= $current . $this->generate_categories($keys,$current,$ret,$depth+1);

		}

		//$current = null;
		$this->save_categories_option_file(str_replace("<br>",",\r\n",$out));

        return ($out);
  }

  function save_categories_option_file($data=null) {

	 $file = $this->path . 'categories.opt';
	 //echo $file,$data;

     if ($fp = @fopen ($file , "w")) {
	    //echo $file,"<br>";
                 fwrite ($fp, $data);
                 fclose ($fp);
     }
     else {
         $ret = "File creation error ($filename)!\n";
		 //echo "File creation error ($filename)!<br>";
     }

	 return ($ret);
  }

  //read text categories and import it to sql table categories
  //export text ... bug in first sub tree (write main cat 2 times)
  function generate_sql_categories($keys=false,$current=null,$leaf=null,$depth=0,$gid=null) {

        static $ctgid = 1;


		if ($leaf) {
		  $us = ($depth ? str_repeat('_',$depth-1):null);
		  $myfile =  $this->catpath . $us .trim($leaf) .'.'. $this->cext;

		  $this->sqlcmd .= sprintf("%020s",$gid); 
		  $this->sqlcmd .= str_repeat('-',$depth*2).$leaf.'<br>'; //export for use .......
		}
		else
	      $myfile =  $this->catpath . $this->initfile .'.'. $this->cext;

		if (!is_readable($myfile)) {//last leaf
		  return ('^'.$leaf.'<br>');//<<<<<<<<<<<<<<<<<<<<use to preview..remove from sql
		}

		if ($current)
		    $current .= '^' . $leaf;
		else
		    $current = $leaf;


	    $contents =  @file_get_contents($myfile);
		$cats = explode(",",$contents);

		foreach ($cats as $id=>$c) {
		  //check virtual links	(cats who see other cats)
		  $parts = explode('~',$c);
          $names = $parts[0];
		  $link = $part[1];//if exist then modify cmd

		  $ct = explode(';',$names);
		  $ck = $ct[$this->catfkey];
		  $cd = $ct[getlocal()];

		  //check @names and remove it form titles
		  if (strstr($cd,'@')) {
		    $p = explode('@',$cd);
		    $title = $p[0];
		  }
		  else
		    $title = $cd;

		  if ($keys) $ret = $ck;
		        else $ret = $title;

		  //if ($current==$leaf)
		  //$out .= $this->generate_categories($current,$ck,$depth+1);
		  //else

          switch ($depth) {
            case 5 : $a=100000; break;
            case 4 : $a=10000; break;
            case 3 : $a=1000; break;
			case 2 : $a=100; break;
            case 1 : $a=10; break;
            default :$a=1;
		  }

		  $myctgid = ($id+1) + $gid*$a;

		  //curent . current when recursion????
          $mytxtcats = $current . $this->generate_sql_categories($keys,$current,$ret,$depth+1,$myctgid);
		  $out .= $mytxtcats;

	      /*if (defined("RCKATEGORIES_DPC")) {
            GetGlobal('controller')->calldpc_method('rckategories.generate_from_text use '.$mytxtcats.'+'.$ctgid++);
	      }
	      else
	        $out .= '<br>Invalid operation!<br>';		  */ //problem with common commands when both dpc loaded


		  if ($this->generate_from_text($mytxtcats,$myctgid)) {
		  	$this->sqlok+=1;
		  }; //,$ctgid++

		}

		$ctgid+=1;

	    return ($out);
  }

  //import to categories table from text cats (called from rccategories)
  function generate_from_text($cat,$ctgid) {
    $db = GetGlobal('db');

    //echo $cat;
	$tcat = explode('^',str_replace('<br>','',$cat));//br came from text processing at the end of string

    $sSQL = "insert into categories (ctgid,cat1";
    foreach ($tcat as $id=>$c) {
	  $cid = $id+2;
	  $sSQL .= ',cat'.$cid;
	}
	$sSQL.= ") values (";
	$sSQL.= $ctgid . ",'ΚΑΤΗΓΟΡΙΕΣ ΕΙΔΩΝ'";

	reset($tcat);
	foreach ($tcat as $id=>$c)
	  $sSQL .= ",'".trim($c)."'";
	$sSQL .= ')';

	//echo $sSQL,'<br>';

	$result = $db->Execute($sSQL,2);
   	if (!$result) {
	   echo $sSQL,' failed!<br>';
	   return false;
	}
	else
       return true;
	//echo $ctgid,' ',array_pop($tcat),'<br>';
  }

};
}
?>