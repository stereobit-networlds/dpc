<?php
//PHP5
$__DPCSEC['GREEKMAP_DPC']='2;2;2;2;2;2;2;2;9';

if (!defined("GREEKMAP_DPC")) {
define("GREEKMAP_DPC",true);

$__DPC['GREEKMAP_DPC'] = 'greekmap';


$__EVENTS['GREEKMAP_DPC'][0]='greekmap';

$__ACTIONS['GREEKMAP_DPC'][0]='greekmap';

$__DPCATTR['GREEKMAP_DPC']['greekmap'] = 'greekmap,1,0,1,1,1,0,0,0,0';

//$__LOCALE['GREEKMAP_DPC'][0]='GREEKMAP_DPC;Map;Χάρτης';
$__LOCALE['GREEKMAP_DPC'][10]='_EVROS;Evros;Εβρος';
$__LOCALE['GREEKMAP_DPC'][11]='_RODOPI;Evros;Εβρος';
$__LOCALE['GREEKMAP_DPC'][12]='_XANTHI;Evros;Εβρος';
$__LOCALE['GREEKMAP_DPC'][13]='_DRAMA;Evros;Εβρος';
$__LOCALE['GREEKMAP_DPC'][14]='_KAVALA;Evros;Εβρος';
$__LOCALE['GREEKMAP_DPC'][15]='_SERRES;Evros;Εβρος';
$__LOCALE['GREEKMAP_DPC'][16]='_KILKIS;Evros;Εβρος';
$__LOCALE['GREEKMAP_DPC'][17]='_PELLA;Evros;Εβρος';
$__LOCALE['GREEKMAP_DPC'][18]='_8ESSALONIKI;Evros;Εβρος';
$__LOCALE['GREEKMAP_DPC'][19]='_XALKIDIKI;Evros;Εβρος';


/*usage:
        $out .= "<form name=\"gmap\" action=\"\" method=\"POST\" class=\"thin\">";
        $out .= "<input type=\"hidden\" name=\"FormName\" value=\"GreekMap\">";		
		$m = new greekmap;
		$out .= $m->render2('state','gmap');
        $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"GreekMap\">";
        $out .= "<input type=\"submit\" value=\"" . localize('_OK',getlocal()) . "\">";
        $out .= "</form>";	
*/ 

class greekmap {

  private $grx;
  private $states;
  private $path;
  private $redirect;
  private $map_button;
  
  function __construct($redirection=null) {
  
	 $this->grx = GetGlobal('GRX'); 
	 
     $this->urlpath = paramload('SHELL','urlpath');//$_SERVER['HTTP_HOST'];
	 $this->path = $this->urlpath . "/images/greece";	
	 
	 $this->redirect = $redirection;	 
	 
     if (iniload('JAVASCRIPT')) {	
	   
	       //$javas = $this->javascript();//used by render
	   
		   $js = new jscript;
           //$js->load_js($javas,"",1);	//used by render
		   $js->load_js("greekmap.js"); //used by render2
		   unset ($js);		
	 }
	 else
	   die("Javascript required!");
	   
     if ($this->grx) {   	 
         $this->map_button  = loadTheme('godate_b',localize('_GREEKMAP',getlocal()));
     }
	 else {
         $this->map_button  = "[MAP]";
	 }		   	  
  }
  
  public function render($title=null) {
  
    $state = GetParam('state');
    
	if (!$state)
	  $out = $this->create_map($this->path);
	else //redirect  
      $out = calldpc_method($redirect." use ".$state);
  
    return ($out);
  }
  
  //render it as opened win dialog for a text string return (see datepick)
  function render2($id,$formname,$len=20,$val='') {
	
	    $out  = "<input type=\"text\" name=\"$id\" value=\"$val\" maxlenght=\"$len\">";
		$out .= "<a href=\"javascript:show_greekmap('document.$formname.$id', '$this->path');\">" .
		        $this->map_button . "</a>";
				
		return ($out);			
  }
  
  function select() {
 
       $out .= "<form name=\"gmap\" action=\"\" method=\"POST\" class=\"thin\">";
       $out .= "<input type=\"hidden\" name=\"FormName\" value=\"GreekMap\">";		
	   $out .= $this->render2('state','gmap');
       $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"GreekMap\">";
       $out .= "<input type=\"submit\" value=\"" . localize('_OK',getlocal()) . "\">";
       $out .= "</form>";	
	   
	   return ($out);  
  }  
  
  
  
  
  function create_map($source) {
   
    $ret = <<<EOF
        <div id="greece" style="position:absolute; width:400px; height:375px; z-index:1; left: 215px; top: 108px;">
		<img src="$source/GREECE.gif" width="400" height="375" border="0" name="Map" usemap="#MapGr" ></div>
		<div id="div1" style="position:absolute; width:51px; height:79px; z-index:2;   left: 481px; top: 109px; visibility: hidden"><img src="$source/evros.gif" width="52" ALT="?. ?ί???" height="76" border="0"></div>
		<div id="div2" style="position:absolute; width:45px; height:31px; z-index:3;   left: 461px; top: 131px; visibility: hidden"><img src="$source/rodopi.gif" ALT="?. ??d?p??" width="42" height="28" border="0"></div>
		<div id="div3" style="position:absolute; width:41px; height:64px; z-index:4;   left: 435px; top: 129px; visibility: hidden"><img src="$source/xan8i.gif" ALT="?. ??????" width="35" height="30" border="0"></div>
		<div id="div4" style="position:absolute; width:56px; height:41px; z-index:5;   left: 398px; top: 121px; visibility: hidden"><img src="$source/drama.gif" ALT="?. ???µa?" width="51" height="31" border="0"></div>
		<div id="div5" style="position:absolute; width:50px; height:49px; z-index:6;   left: 409px; top: 139px; visibility: hidden"><img src="$source/kavala.gif" ALT="?. ?aί??a?" width="42" height="36" border="0"></div>
		<div id="div6" style="position:absolute; width:66px; height:42px; z-index:7;   left: 364px; top: 129px; visibility: hidden"><img src="$source/serres.gif" ALT="?. Se????" width="56" height="35" border="0"></div>
		<div id="div7" style="position:absolute; width:59px; height:35px; z-index:8;   left: 334px; top: 133px; visibility: hidden"><img src="$source/kilkis.gif" ALT="?. ??????" width="45" height="30" border="0"></div>
		<div id="div8" style="position:absolute; width:43px; height:34px; z-index:9;   left: 313px; top: 142px; visibility: hidden"><img src="$source/pella.gif" ALT="?. ????a?" width="39" height="28" border="0"></div>
		<div id="div9" style="position:absolute; width:60px; height:40px; z-index:10 ; left: 346px; top: 151px; visibility: hidden"><a href="javascript:windowOpen('shops.asp?prefecture_id=13&idioma=4', '100', '100', '500', '400');" onMouseOut="hideEverything()"><img src="$source/8essaloniki.gif" ALT="?. Tessa???????" width="57" height="35" border="0"></a></div>
		<div id="div10" style="position:absolute; width:77px; height:44px; z-index:11; left: 370px; top: 171px; visibility: hidden"><img src="$source/xalkidiki.gif" ALT="?. ?a???d????" width="64" height="40" border="0"></div>
		<div id="div11" style="position:absolute; width:33px; height:39px; z-index:12; left: 329px; top: 176px; visibility: hidden"><img src="$source/pieria.gif" ALT="?. ??e??a?" width="25" height="33" border="0"></div>
		<div id="div12" style="position:absolute; width:38px; height:32px; z-index:13; left: 318px; top: 165px; visibility: hidden"><img src="$source/hma8ia.gif" ALT="?. ?µa??a?" width="34" height="26" border="0"></div>
		<div id="div13" style="position:absolute; width:48px; height:30px; z-index:14; left: 278px; top: 153px; visibility: hidden"><img src="$source/florina.gif" ALT="?. F?????a?" width="37" height="24" border="0"></div>
		<div id="div14" style="position:absolute; width:38px; height:38px; z-index:15; left: 270px; top: 163px; visibility: hidden"><img src="$source/kastoria.gif" ALT="?. ?ast?????" width="32" height="32" border="0"></div>
		<div id="div15" style="position:absolute; width:60px; height:51px; z-index:16; left: 281px; top: 165px; visibility: hidden"><img src="$source/kozani.gif" ALT="?. ???????" width="52" height="45" border="0"></div>
		<div id="div16" style="position:absolute; width:55px; height:62px; z-index:17; left: 319px; top: 194px; visibility: hidden"><a href="javascript:windowOpen('shops.asp?prefecture_id=24&idioma=4', '100', '100', '500', '400');" onMouseOut="hideEverything()"><img src="$source/larisa.gif" ALT="?. ????sa?" width="45" height="59" border="0"></a></div>
		<div id="div17" style="position:absolute; width:58px; height:34px; z-index:18; left: 282px; top: 210px; visibility: hidden"><img src="$source/trikala.gif" ALT="?. ????????" width="51" height="28" border="0"></div>
		<div id="div18" style="position:absolute; width:52px; height:28px; z-index:19; left: 277px; top: 190px; visibility: hidden"><img src="$source/grevena.gif" ALT="?. G?eίe???" width="44" height="23" border="0"></div>
		<div id="div19" style="position:absolute; width:47px; height:62px; z-index:20; left: 247px; top: 184px; visibility: hidden"><img src="$source/ioannina.gif" ALT="?. ??a??????" width="45" height="58" border="0"></div>
		<div id="div20" style="position:absolute; width:28px; height:37px; z-index:21; left: 240px; top: 213px; visibility: hidden"><img src="$source/8esprotia.gif" ALT="?. Tesp??t?a?" width="23" height="30" border="0"></div>
		<div id="div21" style="position:absolute; width:31px; height:30px; z-index:22; left: 254px; top: 235px; visibility: hidden"><img src="$source/preveza.gif" ALT="?. ???ίe?a?" width="26" height="26" border="0"></div>
		<div id="div22" style="position:absolute; width:31px; height:38px; z-index:23; left: 270px; top: 229px; visibility: hidden"><img src="$source/arta.gif" ALT="?. ¶?ta?" width="27" height="29" border="0"></div>
		<div id="div23" style="position:absolute; width:47px; height:37px; z-index:24; left: 295px; top: 228px; visibility: hidden"><img src="$source/karditsa.gif" ALT="?. ?a?d?tsa?" width="42" height="30" border="0"></div>
		<div id="div24" style="position:absolute; width:62px; height:28px; z-index:25; left: 345px; top: 227px; visibility: hidden"><a href="javascript:windowOpen('shops.asp?prefecture_id=9&idioma=4', '100', '100', '500', '400');" onMouseOut="hideEverything()"><img src="$source/magnisia.gif" ALT="?. ?a???s?a?" width="85" height="35" border="0"></a></div>
		<div id="div25" style="position:absolute; width:81px; height:60px; z-index:26; left: 368px; top: 258px; visibility: hidden"><img src="$source/eboia.gif" ALT="?. ??ί??a?" width="78" height="59" border="0"></div>
		<div id="div26" style="position:absolute; width:58px; height:142px; z-index:27;left: 361px; top: 296px; visibility: hidden"><a href="javascript:windowOpen('shops.asp?prefecture_id=3&idioma=4', '100', '100', '500', '400');" onMouseOut="hideEverything()"><img src="$source/attiki.gif" ALT="?. ?tt????" width="56" height="139" border="0"></a></div>
		<div id="div27" style="position:absolute; width:58px; height:29px; z-index:28; left: 345px; top: 282px; visibility: hidden"><img src="$source/biotia.gif" ALT="?. ????t?a?" width="56" height="24" border="0"></div>
		<div id="div28" style="position:absolute; width:71px; height:50px; z-index:29; left: 315px; top: 245px; visibility: hidden"><img src="$source/f8iotida.gif" ALT="?. F???t?da?" width="68" height="42" border="0"></div>
		<div id="div29" style="position:absolute; width:39px; height:35px; z-index:30; left: 316px; top: 271px; visibility: hidden"><img src="$source/fokida.gif" ALT="?. F???da?" width="34" height="27" border="0"></div>
		<div id="div30" style="position:absolute; width:35px; height:39px; z-index:31; left: 295px; top: 245px; visibility: hidden"><img src="$source/evritania.gif" ALT="?. ????ta??a?" width="26" height="31" border="0"></div>
		<div id="div31" style="position:absolute; width:63px; height:54px; z-index:32; left: 264px; top: 245px; visibility: hidden"><img src="$source/aitoloak.gif" ALT="?. ??t???a?a??a??a?" width="61" height="52" border="0"></div>
		<div id="div32" style="position:absolute; width:29px; height:46px; z-index:33; left: 217px; top: 212px; visibility: hidden"><img src="$source/kerkyra.gif" ALT="?. ??????a?" width="27" height="41" border="0"></div>
		<div id="div33" style="position:absolute; width:15px; height:21px; z-index:34; left: 257px; top: 266px; visibility: hidden"><img src="$source/lefkada.gif" ALT="?. ?e???da?" width="9" height="13" border="0"></div>
		<div id="div34" style="position:absolute; width:26px; height:29px; z-index:35; left: 245px; top: 286px; visibility: hidden"><img src="$source/kefalonia.gif" ALT="?. ?efa??????" width="22" height="23" border="0"></div>
		<div id="div35" style="position:absolute; width:18px; height:20px; z-index:36; left: 257px; top: 316px; visibility: hidden"><img src="$source/zakyn8os.gif" ALT="?. ?a??????" width="15" height="17" border="0"></div>
		<div id="div36" style="position:absolute; width:51px; height:28px; z-index:37; left: 330px; top: 306px; visibility: hidden"><img src="$source/korin8os.gif" ALT="?. ???????a?" width="45" height="25" border="0"></div>
		<div id="div37" style="position:absolute; width:53px; height:36px; z-index:38; left: 292px; top: 294px; visibility: hidden"><a href="javascript:windowOpen('shops.asp?prefecture_id=30&idioma=4', '100', '100', '500', '400');" onMouseOut="hideEverything()"><img src="$source/ahaia.gif" ALT="?. ??a?a?" width="47" height="30" border="0"></a></div>
		<div id="div38" style="position:absolute; width:44px; height:43px; z-index:39; left: 279px; top: 309px; visibility: hidden"><img src="$source/hlia.gif" ALT="?. ??e?a?" width="40" height="39" border="0"></div>
		<div id="div39" style="position:absolute; width:42px; height:49px; z-index:40; left: 300px; top: 342px; visibility: hidden"><a href="javascript:windowOpen('shops.asp?prefecture_id=17&idioma=4', '100', '100', '500', '400');" onMouseOut="hideEverything()"><img src="$source/messinia.gif" ALT="?. ?ess???a?" width="38" height="40" border="0"></a></div>
		<div id="div40" style="position:absolute; width:46px; height:52px; z-index:41; left: 331px; top: 351px; visibility: hidden"><img src="$source/lakonia.gif" ALT="?. ?a????a?" width="43" height="48" border="0"></div>
		<div id="div41" style="position:absolute; width:59px; height:51px; z-index:42; left: 311px; top: 320px; visibility: hidden"><img src="$source/arkadia.gif" ALT="?. ???ad?a?" width="54" height="48" border="0"></div>
		<div id="div42" style="position:absolute; width:62px; height:38px; z-index:43; left: 339px; top: 323px; visibility: hidden"><img src="$source/argolida.gif" ALT="?. ??????da?" width="51" height="35" border="0"></div>
		<div id="div43" style="position:absolute; width:41px; height:30px; z-index:44; left: 388px; top: 439px; visibility: hidden"><img src="$source/xania.gif" ALT="?. ?a????" width="38" height="27" border="0"></div>
		<div id="div44" style="position:absolute; width:37px; height:22px; z-index:45; left: 423px; top: 453px; visibility: hidden"><img src="$source/re8ymno.gif" ALT="?. ?e??µ???" width="31" height="18" border="0"></div>
		<div id="div45" style="position:absolute; width:40px; height:29px; z-index:46; left: 444px; top: 453px; visibility: hidden"><img src="$source/hrakleio.gif" ALT="?. ??a??e???" width="40" height="28" border="0"></div>
		<div id="div46" style="position:absolute; width:45px; height:21px; z-index:47; left: 477px; top: 456px; visibility: hidden"><img src="$source/lasi8i.gif" ALT="?. ?as?????" width="41" height="20" border="0"></div>
		<div id="div47" style="position:absolute; width:77px; height:98px; z-index:48; left: 509px; top: 341px; visibility: hidden"><img src="$source/dodekanisa.gif" ALT="?. ??de?a??s??" width="102" height="118" border="0"></div>
		<div id="div48" style="position:absolute; width:50px; height:49px; z-index:49; left: 420px; top: 314px; visibility: hidden"><img src="$source/kyklades.gif" ALT="?. ?????d??" width="88" height="90" border="0"></div>
		<div id="div49" style="position:absolute; width:13px; height:9px; z-index:50;  left: 504px; top: 323px; visibility: hidden"><img src="$source/samos.gif" ALT="?. S?µ??" width="50" height="17" border="0"></div>
		<div id="div50" style="position:absolute; width:12px; height:18px; z-index:51; left: 484px; top: 281px; visibility: hidden"><img src="$source/xios.gif" ALT="?. ????" width="29" height="25" border="0"></div>
		<div id="div51" style="position:absolute; width:61px; height:45px; z-index:52; left: 457px; top: 206px; visibility: hidden"><img src="$source/lesvos.gif" ALT="?. ??sί??" width="77" height="56" border="0"></div>

		<div id="div52" style="position:absolute; width:420px; height:45px; z-index:53; left: 218px; top: 85px; visibility: visible">
			<span style="font-family:Verdana; font-size:8pt;">?a?a?a?? ep????te ??µ? ??a ?a de?te ta s?µe?a pa???s?a? µa?</b>.</span>
		</div>

		<MAP NAME="MapGr">
			<AREA SHAPE="polygon" COORDS="313,6, 315,10, 315,24, 303,33, 303,46, 292,55, 287,52, 276,52, 274,76, 266,75, 274,44, 284,38, 285,34, 287,25, 296,19, 294,11, 292,4, 301,2" onmouseover="hideEverything(); show('div1')">
			<AREA SHAPE="polygon" COORDS="286,30, 286,38, 279,44, 273,48, 265,50, 255,47, 250,44, 247,33, 252,29, 263,28, 266,26, 285,26" onmouseover="hideEverything(); show('div2')">
			<AREA SHAPE="polygon" COORDS="254,26, 251,30, 248,37, 250,43, 244,46, 234,48, 229,32, 220,32, 232,23, 242,21" onmouseover="hideEverything(); show('div3')">
			<AREA SHAPE="polygon" COORDS="225,17, 232,24, 223,34, 205,43, 196,38, 186,29, 184,21, 222,13, 225,15" onmouseover="hideEverything(); show('div4')">
			<AREA SHAPE="polygon" COORDS="231,33, 234,40, 233,65, 224,64, 211,54, 200,58, 194,53, 208,43, 231,31" onmouseover="hideEverything(); show('div5')">
			<AREA SHAPE="polygon" COORDS="184,23, 187,29, 198,37, 204,44, 192,55, 174,52, 151,32, 149,27, 181,22" onmouseover="hideEverything(); show('div6')">
			<AREA SHAPE="polygon" COORDS="150,27, 151,31, 159,34, 164,39, 164,43, 161,48, 156,50, 147,53, 144,53, 135,52, 125,45, 122,41, 129,36, 139,33, 143,34, 147,25" onmouseover="hideEverything(); show('div7')">
			<AREA SHAPE="polygon" COORDS="124,36, 124,41, 122,43, 133,50, 136,53, 134,55, 134,57, 119,59, 99,56, 99,49, 113,34" onmouseover="hideEverything(); show('div8')">
			<AREA SHAPE="polygon" COORDS="173,50, 177,51, 185,55, 187,55, 183,64, 161,74, 157,77, 155,75, 149,76, 146,70, 151,65, 148,63, 143,66, 138,67, 132,57, 133,52, 137,51, 145,51, 150,53, 156,49, 165,43" HREF="shops.asp?prefecture_id=13" onmouseover="hideEverything(); show('div9')">
			<AREA SHAPE="polygon" COORDS="192,66, 194,69, 191,72, 202,77, 217,86, 217,91, 212,88, 201,80, 189,79, 186,82, 196,89, 199,99, 194,98, 180,85, 170,84, 171,89, 181,98, 185,102, 171,98, 161,82, 155,77, 157,74, 160,74, 165,68, 185,63" onmouseover="hideEverything(); show('div10')">
			<AREA SHAPE="polygon" COORDS="137,70, 136,83, 135,93, 137,100, 130,97, 124,92, 123,89, 117,89, 114,84, 123,74, 135,68" onmouseover="hideEverything(); show('div11')">
			<AREA SHAPE="polygon" COORDS="129,58, 133,59, 137,67, 129,71, 116,81, 110,73, 104,64, 107,57" onmouseover="hideEverything(); show('div12')">
			<AREA SHAPE="polygon" COORDS="100,47, 98,51, 100,57, 97,64, 85,66, 81,60, 74,59, 67,62, 64,50" onmouseover="hideEverything(); show('div13')">
			<AREA SHAPE="polygon" COORDS="81,58, 84,63, 85,71, 80,76, 77,75, 67,81, 62,86, 56,72, 64,67, 71,61, 78,56" onmouseover="hideEverything(); show('div14')">
			<AREA SHAPE="polygon" COORDS="104,62, 111,72, 114,74, 116,85, 102,100, 98,97, 97,91, 90,87, 82,83, 69,88, 66,84, 69,77, 81,74, 84,73, 87,68, 94,63, 99,57, 105,58" onmouseover="hideEverything(); show('div15')">
			<AREA SHAPE="polygon" COORDS="119,89, 124,87, 126,91, 127,95, 138,99, 147,114, 147,120, 137,134, 133,141, 129,144, 119,137, 117,133, 118,122, 113,116, 104,108, 108,93, 118,86" HREF="shops.asp?prefecture_id=24" onmouseover="hideEverything(); show('div16')">
			<AREA SHAPE="polygon" COORDS="101,103, 106,107, 107,111, 118,120, 101,123, 96,126, 80,129, 71,126, 68,116, 70,111, 73,107, 77,103, 81,102" onmouseover="hideEverything(); show('div17')">
			<AREA SHAPE="polygon" COORDS="87,85, 96,88, 99,96, 103,100, 105,100, 103,105, 79,105, 71,104, 66,100, 63,87, 68,86, 76,85, 86,84" onmouseover="hideEverything(); show('div18')">
			<AREA SHAPE="polygon" COORDS="63,84, 63,91, 66,95, 72,101, 77,105, 74,109, 74,111, 70,116, 71,122, 65,127, 64,128, 46,129, 42,121, 39,116, 40,112, 36,93, 44,91, 52,83, 55,77, 60,78" onmouseover="hideEverything(); show('div19')">
			<AREA SHAPE="polygon" COORDS="40,109, 41,115, 40,119, 46,124, 45,132, 33,134, 28,112, 36,106" onmouseover="hideEverything(); show('div20')">
			<AREA SHAPE="polygon" COORDS="64,129, 58,140, 52,147, 49,152, 46,144, 39,134, 41,131" onmouseover="hideEverything(); show('div21')">
			<AREA SHAPE="polygon" COORDS="71,123, 80,129, 79,141, 72,144, 59,147, 55,142, 57,138, 58,134, 64,127, 66,121" onmouseover="hideEverything(); show('div22')">
			<AREA SHAPE="polygon" COORDS="121,123, 116,148, 105,147, 100,141, 96,144, 88,138, 80,138, 82,127, 94,126, 116,120" onmouseover="hideEverything(); show('div23')">
			<AREA SHAPE="polygon" COORDS="155,122, 178,142, 190,143, 207,130, 213,135, 207,133, 200,139, 201,143, 205,150, 197,149, 187,148, 168,144, 156,148, 156,145, 162,144, 155,135, 150,135, 145,141, 152,151, 147,153, 133,150, 130,148, 136,133, 135,129, 150,119" HREF="shops.asp?prefecture_id=9" onmouseover="hideEverything(); show('div24')">
			<AREA SHAPE="polygon" COORDS="169,152, 180,164, 184,165, 201,169, 225,155, 230,165, 224,166, 207,182, 214,197, 225,203, 220,207, 217,206, 213,206, 207,195, 205,192, 195,184, 181,182, 179,175, 156,160, 155,156, 162,151" onmouseover="hideEverything(); show('div25')">
			<AREA SHAPE="polygon" COORDS="197,190, 201,197, 197,201, 200,223, 161,324, 146,299, 148,293, 155,298, 163,286, 170,236, 163,230, 172,215, 168,207, 157,205, 161,196, 171,193, 179,195, 189,187" HREF="shops.asp?prefecture_id=3" onmouseover="hideEverything(); show('div26')">
			<AREA SHAPE="polygon" COORDS="140,175, 162,177, 182,183, 185,191, 178,197, 166,195, 158,197, 140,190, 130,177, 132,174" onmouseover="hideEverything(); show('div27')">
			<AREA SHAPE="polygon" COORDS="128,141, 131,146, 135,149, 151,153, 147,156, 137,157, 133,160, 157,170, 160,171, 168,173, 167,176, 165,178, 148,176, 142,175, 133,177, 131,173, 130,170, 115,165, 101,159, 104,147, 116,143, 118,137" onmouseover="hideEverything(); show('div28')">
			<AREA SHAPE="polygon" COORDS="121,164, 124,165, 132,174, 131,178, 134,187, 131,186, 125,183, 120,187, 110,185, 101,180, 105,174, 110,164, 114,163" onmouseover="hideEverything(); show('div29')">
			<AREA SHAPE="polygon" COORDS="94,139, 99,141, 105,142, 104,150, 102,155, 106,165, 97,167, 88,164, 85,157, 81,152, 84,138" onmouseover="hideEverything(); show('div30')">
			<AREA SHAPE="polygon" COORDS="82,143, 84,154, 89,159, 88,163, 102,167, 108,162, 108,166, 103,180, 102,182, 91,186, 83,187, 76,183, 67,188, 66,184, 67,181, 62,173, 53,154, 68,152, 67,148, 69,143, 77,142, 80,138" onmouseover="hideEverything(); show('div31')">
			<AREA SHAPE="polygon" COORDS="16,106, 13,113, 15,115, 16,122, 25,130, 25,141, 14,124, 2,105" onmouseover="hideEverything(); show('div32')">
			<AREA SHAPE="polygon" COORDS="49,160, 47,170, 41,166, 48,158" onmouseover="hideEverything(); show('div33')">
			<AREA SHAPE="polygon" COORDS="47,180, 47,189, 51,199, 37,197, 35,190, 30,195, 33,185, 40,183, 40,178" onmouseover="hideEverything(); show('div34')">
			<AREA SHAPE="polygon" COORDS="56,219, 53,219, 49,222, 42,210, 46,207, 53,213" onmouseover="hideEverything(); show('div35')">
			<AREA SHAPE="polygon" COORDS="135,201, 148,210, 149,206, 148,202, 157,206, 158,210, 152,212, 155,214, 159,219, 154,222, 135,219, 125,217, 115,215, 118,208, 123,199, 133,200" onmouseover="hideEverything(); show('div36')">
			<AREA SHAPE="polygon" COORDS="113,193, 123,200, 120,207, 111,216, 101,214, 96,209, 89,212, 77,194, 85,196, 92,195, 101,187" HREF="shops.asp?prefecture_id=30" onmouseover="hideEverything(); show('div37')">
			<AREA SHAPE="polygon" COORDS="82,204, 85,208, 92,210, 100,208, 96,223, 99,227, 104,234, 94,238, 88,236, 79,225, 74,219, 65,208, 74,203, 76,200" onmouseover="hideEverything(); show('div38')">
			<AREA SHAPE="polygon" COORDS="105,236, 109,246, 114,249, 118,259, 120,273, 101,261, 97,273, 89,265, 85,249, 90,240, 94,237, 99,234" HREF="shops.asp?prefecture_id=17" onmouseover="hideEverything(); show('div39')">
			<AREA SHAPE="polygon" COORDS="133,245, 139,257, 147,259, 151,259, 152,277, 157,290, 143,277, 137,270, 130,273, 127,287, 124,290, 121,285, 117,259, 117,244, 124,246, 126,243" onmouseover="hideEverything(); show('div40')">
			<AREA SHAPE="polygon" COORDS="111,215, 114,215, 124,219, 130,232, 134,235, 140,236, 148,260, 137,257, 128,243, 124,246, 116,250, 112,251, 103,238, 100,230, 96,223, 99,213" onmouseover="hideEverything(); show('div41')">
			<AREA SHAPE="polygon" COORDS="159,225, 162,229, 171,235, 174,238, 163,240, 157,249, 156,239, 155,236, 139,229, 133,235, 128,231, 127,225, 124,217, 130,216" onmouseover="hideEverything(); show('div42')">
			<AREA SHAPE="polygon" COORDS="185,334, 193,339, 203,335, 204,339, 203,342, 208,344, 209,348, 208,357, 173,349, 177,339, 181,339, 183,331" onmouseover="hideEverything(); show('div43')">
			<AREA SHAPE="polygon" COORDS="238,347, 234,355, 226,362, 218,359, 208,352, 215,347, 233,345" onmouseover="hideEverything(); show('div44')">
			<AREA SHAPE="polygon" COORDS="248,349, 267,353, 266,357, 263,359, 264,363, 267,369, 229,370, 234,354, 243,347" onmouseover="hideEverything(); show('div45')">
			<AREA SHAPE="polygon" COORDS="277,351, 279,361, 301,354, 300,366, 292,367, 264,366, 263,356, 266,352, 271,349" onmouseover="hideEverything(); show('div46')">
			<AREA SHAPE="polygon" COORDS="396,294, 384,349, 330,349, 294,282, 316,238, 336,235" onmouseover="hideEverything(); show('div47')">
			<AREA SHAPE="polygon" COORDS="236,208, 243,215, 246,223, 260,231, 292,266, 280,296, 254,293, 204,272, 214,220, 234,206" onmouseover="hideEverything(); show('div48')">
			<AREA SHAPE="polygon" COORDS="338,217, 335,221, 311,231, 289,229, 295,225, 302,224, 328,215" onmouseover="hideEverything(); show('div49')">
			<AREA SHAPE="polygon" COORDS="295,176, 297,179, 295,194, 287,196, 269,173" onmouseover="hideEverything(); show('div50')">
			<AREA SHAPE="polygon" COORDS="256,100, 259,99, 267,100, 308,136, 318,152, 310,153, 281,146, 242,126, 248,98" onmouseover="hideEverything(); show('div51')">
		</MAP>	
EOF;

   return ($ret);   
  }     
  
  function javascript() {
  
    $ret = "
// ************ microsoft explorer (4.x) **********************************************
if (document.all) {
			layerRef='document.all';
			styleRef='.style.';}
// ************* netscape (4.x) *******************************************************
else if (document.layers) {
			layerRef='document.layers';
			styleRef='.';}
	
function hideEverything()
	{
	    eval(layerRef+'[\"'+'div1'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div2'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div3'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div4'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div5'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div6'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div7'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div8'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div9'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div10'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div11'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div12'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div13'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div14'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div15'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div16'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div17'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div18'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div19'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div20'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div21'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div22'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div23'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div24'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div25'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div26'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div27'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div28'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div29'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div30'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div31'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div32'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div33'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div34'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div35'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div36'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div37'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div38'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div39'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div40'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div41'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div42'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div43'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div44'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div45'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div46'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div47'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div48'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div49'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div50'+'\"]'+styleRef+'visibility=\"hidden\"');
	    eval(layerRef+'[\"'+'div51'+'\"]'+styleRef+'visibility=\"hidden\"');
	}	
	
    // ************* dynamic HTML Show command ********************************************
    function show(n)
    {
	  eval(layerRef+'[\"'+n+'\"]'+styleRef+'visibility=\"visible\"');
	  eval(layerRef+'[\"'+n+'\"]'+styleRef+'display=\"\"');
    }

    // ************* dynamic HTML Hide command ********************************************
    function hide(n)
    {
	  eval(layerRef+'[\"'+n+'\"]'+styleRef+'visibility=\"hidden\"');
	  eval(layerRef+'[\"'+n+'\"]'+styleRef+'display=\"none\"');
    }	
";	
    return ($ret);
  }
  
  function __destruct() {
  }
};
}
?>