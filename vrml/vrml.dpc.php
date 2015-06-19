<?php

$__DPCSEC['VRML_DPC']='2;1;1;1;1;1;1;2;9';

if ((!defined("VRML_DPC")) && (seclevel('VRML_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("VRML_DPC",true);

$__DPC['VRML_DPC'] = 'vrml';

$__EVENTS['VRML_DPC'][0] = 'vrml';

$__ACTIONS['VRML_DPC'][0]= 'vrml';

$__LOCALE['VRML_DPC'][0]='VRML_DPC;Vrml;Vrml';

//require_once("api.lib.php");
GetGlobal('controller')->include_dpc('vrml/api.lib.php');

class vrml {

    function vrml() {
	
	   $this->datadir = paramload('SHELL','prpath');
	   
	   //$this->create_vrml();	test
	   
	}
	
    function event($action) { 
    } 		
	
    function action($action) {

	   $out = $this->view("test.wrl"); 
	   
	   return ($out);
    }	
	
	function create_vrml() {   

       $v = new api_vrml("\"Titre\"",array("\"Infos 1\"","\"Infos 2\""),"test.wrl");

       $b1 = new Box("3 5 2");
       $v->addNode($b1);

       $m1 = new Material("0.3 0.5 0.8","0.2","0.0 0.0 0.0 ","0.0 0.0 0.0 ","0.2","0");
       $a1 = new Appearance($m1);
       $s1 = new Shape($a1,$b1);
       $v->addNode($s1);

       // test avec un transform :

       $m2 = new Material("0.7 0.2 0.2","0.2","0.0 0.0 0.0 ","0.0 0.0 0.0 ","0.2","0.4");
       $a2 = new Appearance($m2);
       $b2 = new Box("4 4 4");
       $s2 = new Shape($a2,$b2);

       $sph1 = new Sphere("3");
       $s3 = new Shape($a2,$sph1);

       $m2->setTransparency("0");
       $a3 = new Appearance($m2);
       $cyl1 = new Cylinder("0.1","7");
       $s4 = new Shape($a3,$cyl1);

       $t1 = new Transform(array($s2,$s3));
       $t1->setTranslation("-6 0 0");

       $t2 = new Transform(array($s1));
       $t2->setTranslation("6 0 0");

       $t3 = new Transform(array($s4));
       $t3->setTranslation("6 -3 0");

       $v->addNode(array($t1,$t2,$t3));

       $v->generate();
	} 
	  
    function view($vrmlfile) {
	  
        $page .= "<EMBED SRC=$vrmlfile align=center height=90% width=100% hspace=0 vspace=0 border=1></EMBED>";
        $page .= "If needed, download the necessary plug-in here : <a href=http://www.karmanaut.com/cosmo/player/ target=_blank>http://www.karmanaut.com/cosmo/player/</a>";
		
        return ($page);	  
	} 
	  
};
}	  
?>