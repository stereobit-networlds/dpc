<?php
/*
*	MessageBox 
*	for PHP-GTK
*	by Christian Weiske, cweiske@cweiske.de
*
*	last change: 2002-10-04 10:18
*
*	This script is freeware.
*	You can use it as long you leave this copyright notice where it is.
*
*	The MessageBox function is similar to the (Visual)C++ function MessageBox
*	Most styles are supported and named exactly like the original constants
*
*	Usage:
		//Load GTK if not happened yet
		dl( "php_gtk." . ( strstr( PHP_OS, "WIN") ? "dll" : "so"));
		require_once( "GTK_MessageBox.inc");

		$nAnswer = MessageBox( "This is the text.\nAnd this is a new line.", "The title", MB_YESNO + MB_ICONQUESTION + MB_DEFBUTTON2 + MB_CENTER);
		if( $nAnswer == IDYES) {
			MessageBox( "You've clicked YES", NULL, MB_ICONINFORMATION);
		} else {
			MessageBox( "You didn't click YES");
		}
*/


/*
* MessageBox() Flags
*/
//Buttons
define( "MB_OK"					, 0);
define( "MB_OKCANCEL"			, 1);
define( "MB_ABORTRETRYIGNORE"	, 2);
define( "MB_YESNOCANCEL"		, 3);
define( "MB_YESNO"				, 4);
define( "MB_RETRYCANCEL"		, 5);
define( "MB_OWN"				, 9);//this is not original, but a great enhancement
//Icons
define( "MB_ICONHAND"			, 10);
define( "MB_ICONQUESTION"		, 20);
define( "MB_ICONEXCLAMATION"	, 30);
define( "MB_ICONASTERISK"		, 40);
define( "MB_ICONWARNING"		, MB_ICONEXCLAMATION);
define( "MB_ICONERROR"			, MB_ICONHAND);
define( "MB_ICONINFORMATION"	, MB_ICONASTERISK);
define( "MB_ICONSTOP"			, MB_ICONHAND);
//Default button
define( "MB_DEFBUTTON1"			, 000);
define( "MB_DEFBUTTON2"			, 100);
define( "MB_DEFBUTTON3"			, 200);
define( "MB_DEFBUTTON4"			, 300);

//Text align
define( "MB_LEFT"				, 0000);//Left is default
define( "MB_CENTER"				, 4000);//this is not original MessageBox
define( "MB_RIGHT"				, 8000);
define( "MB_MIDDLE"				, MB_CENTER);

/*
* MessageBox Buttons
*/
define( "IDOK"					, 1);
define( "IDCANCEL"				, 2);
define( "IDABORT"				, 3);
define( "IDRETRY"				, 4);
define( "IDIGNORE"				, 5);
define( "IDYES"					, 6);
define( "IDNO"					, 7);
define( "IDCLOSE"				, 8);
define( "IDHELP"				, 9);



function MessageBox( $strText, $strCaption = NULL, $nStyle = 0, $arOwnButtons = NULL) {
	$msgbox		= new MessageBox( $strText, $strCaption, $nStyle, $arOwnButtons);
	return $msgbox->nPressedButton;
}//function MessageBox( $strText, $strCaption = NULL, $nStyle = 0)

class MessageBox
{
	var $dlgMsgbox;
	var $nPressedButton;

	function MessageBox( $strText, $strCaption = NULL, $nStyle = 0, $arOwnButtons = NULL)
	{
		$dlgMsgbox		= &new GtkDialog();
		$this->dlgMsgbox= &$dlgMsgbox;
		//$dlgMsgbox		->set_position(		GTK_WIN_POS_CENTER);
		$dlgMsgbox		->set_modal(		true);
		$dlgMsgbox		->set_default_size(	180, 100);
		$dlgMsgbox		->set_policy(		false, false, false);
		if( $strCaption != NULL) {
			$dlgMsgbox		->set_title(		$strCaption);
		}
		$dlgMsgbox		->connect(			"delete-event"	, array( &$this, "OnDelete"));
		$dlgMsgbox		->show();//must be shown now, cause I know no way to create the pixmap without the window
		
		$dlgVBox		= $dlgMsgbox->vbox; 
		$dlgBtnArea		= $dlgMsgbox->action_area; 
		$dlgBtnArea		->set_homogeneous( true);

		$lblMsg			= &new GtkLabel(	$strText);

		//Which flags are set?
		$strStyle		= str_pad( $nStyle, 7, "0", STR_PAD_LEFT);
		$strStyle		= strrev( $strStyle);

		/*
		*	Buttons
		*/
		$arStyle["buttons"]	= substr( $strStyle, 0, 1);
		$nButtons = -1;
		//OK
		if( $arStyle["buttons"] == MB_OK || $arStyle["buttons"] == MB_OKCANCEL) {
			$nButtons++;
			$btn[$nButtons]		= &new GtkButton(	"OK");
			$btn[$nButtons]		->set_flags(	GTK_CAN_DEFAULT);
			$btn[$nButtons]		->connect( "clicked", array( &$this, "OnButtonClick"), IDOK);
			$btn[$nButtons]		->show();
			$dlgBtnArea			->pack_start( $btn[$nButtons]);
		}
		//Yes
		if( $arStyle["buttons"] == MB_YESNOCANCEL || $arStyle["buttons"] == MB_YESNO) {
			$nButtons++;
			$btn[$nButtons]		= &new GtkButton(	"Yes");
			$btn[$nButtons]		->set_flags(	GTK_CAN_DEFAULT);
			$btn[$nButtons]		->connect( "clicked", array( &$this, "OnButtonClick"), IDYES);
			$btn[$nButtons]		->show();
			$dlgBtnArea			->pack_start( $btn[$nButtons]);
		}
		//No
		if( $arStyle["buttons"] == MB_YESNOCANCEL || $arStyle["buttons"] == MB_YESNO) {
			$nButtons++;
			$btn[$nButtons]		= &new GtkButton(	"No");
			$btn[$nButtons]		->set_flags(	GTK_CAN_DEFAULT);
			$btn[$nButtons]		->connect( "clicked", array( &$this, "OnButtonClick"), IDNO);
			$btn[$nButtons]		->show();
			$dlgBtnArea			->pack_start( $btn[$nButtons]);
		}
		//Abort
		if( $arStyle["buttons"] == MB_ABORTRETRYIGNORE) {
			$nButtons++;
			$btn[$nButtons]		= &new GtkButton(	"Abort");
			$btn[$nButtons]		->set_flags(	GTK_CAN_DEFAULT);
			$btn[$nButtons]		->connect( "clicked", array( &$this, "OnButtonClick"), IDABORT);
			$btn[$nButtons]		->show();
			$dlgBtnArea			->pack_start( $btn[$nButtons]);
		}
		//Retry
		if( $arStyle["buttons"] == MB_ABORTRETRYIGNORE || $arStyle["buttons"] == MB_RETRYCANCEL) {
			$nButtons++;
			$btn[$nButtons]		= &new GtkButton(	"Retry");
			$btn[$nButtons]		->set_flags(	GTK_CAN_DEFAULT);
			$btn[$nButtons]		->connect( "clicked", array( &$this, "OnButtonClick"), IDRETRY);
			$btn[$nButtons]		->show();
			$dlgBtnArea			->pack_start( $btn[$nButtons]);
		}
		//Ignore
		if( $arStyle["buttons"] == MB_ABORTRETRYIGNORE) {
			$nButtons++;
			$btn[$nButtons]		= &new GtkButton(	"Ignore");
			$btn[$nButtons]		->set_flags(	GTK_CAN_DEFAULT);
			$btn[$nButtons]		->connect( "clicked", array( &$this, "OnButtonClick"), IDIGNORE);
			$btn[$nButtons]		->show();
			$dlgBtnArea			->pack_start( $btn[$nButtons]);
		}
		//Cancel
		if( $arStyle["buttons"] == MB_OKCANCEL || $arStyle["buttons"] == MB_YESNOCANCEL || $arStyle["buttons"] == MB_RETRYCANCEL) {
			$nButtons++;
			$btn[$nButtons]		= &new GtkButton(	"Cancel");
			$btn[$nButtons]		->set_flags(	GTK_CAN_DEFAULT);
			$btn[$nButtons]		->connect( "clicked", array( &$this, "OnButtonClick"), IDCANCEL);
			$btn[$nButtons]		->show();
			$dlgBtnArea			->pack_start( $btn[$nButtons]);
		}
		if( $arStyle["buttons"] == MB_OWN) {
			if( $arOwnButtons == NULL) {
				$arOwnButtons = array( IDOK => "OK");
			}
			foreach( $arOwnButtons as $nResult => $strCaption) {
				$nButtons++;
				$btn[$nButtons]		= &new GtkButton(	$strCaption);
				$btn[$nButtons]		->set_flags(	GTK_CAN_DEFAULT);
				$btn[$nButtons]		->connect( "clicked", array( &$this, "OnButtonClick"), $nResult);
				$btn[$nButtons]		->show();
				$dlgBtnArea			->pack_start( $btn[$nButtons]);
			}
		}//Own buttons

		/*
		*	Icon
		*/
		$arStyle["icon"]		= substr( $strStyle, 1, 1) . "0";
		$arIcon	= $this->_GetIcon( $arStyle["icon"]);
		if( is_array( $arIcon)) {
			$boxMsg		= &new GtkHBox();
			list($pixmap, $mask)= Gdk::pixmap_create_from_xpm_d( $dlgMsgbox->window, NULL, $arIcon);
			$pxmIcon	= &new GtkPixmap($pixmap, $mask);
			$pxmIcon	->set_padding( 10, 5);
			$boxMsg		->set_border_width( 2);
			$boxMsg		->pack_start( $pxmIcon);
			$lblMsg		->set_padding( 5, 5);
			$boxMsg		->pack_start( $lblMsg);
			$pxmIcon	->show();
			$boxMsg		->show();
			$dlgVBox	->pack_start(		$boxMsg); 
		} else {
			$lblMsg		->set_padding( 15, 15);
			$dlgVBox	->pack_start(		$lblMsg); 
		}




		/*
		*	Default Button
		*/
		$arStyle["defbutton"]	= substr( $strStyle, 2, 1);
		if( $nButtons < $arStyle["defbutton"]) {
			$arStyle["defbutton"] = 0;
		}
		$btn[$arStyle["defbutton"]]	->grab_default();

		/*
		*	Text style
		*/
		$arStyle["misc"]		= substr( $strStyle, 3, 1) . "000";
		if( $arStyle["misc"] == MB_RIGHT) {
			$lblMsg				->set_justify( GTK_JUSTIFY_RIGHT);
		} else if( $arStyle["misc"] == MB_CENTER) {
			$lblMsg				->set_justify( GTK_JUSTIFY_CENTER);
		} else {
			$lblMsg				->set_justify( GTK_JUSTIFY_LEFT);
		}
		$lblMsg					->show();
		$dlgMsgbox				->set_position(		GTK_WIN_POS_CENTER);
		$dlgMsgbox				->show();

		
		GTK::main();
		return $this->nPressedButton;
	}//function MessageBox( $strText, $strCaption, $nStyle)



	function OnButtonClick( $objButton, $nButton ) 
	{
		$this->dlgMsgbox		->destroy();		
		GTK::main_quit();
		$this->nPressedButton	= $nButton;
		return $this->nPressedButton;
	}//function OnButtonClick( $objButton, $nButton )

	//Don't allow the user to close the window with the (x)
	function OnDelete() 
	{
		return true;	
	}



	function _GetIcon( $nImage) 
	{
		$arIcon = NULL;
		switch( $nImage) {
			case MB_ICONHAND:
				//Icon: Error
				$arIcon = array(
				"32 32 255 2",
				"  	c None",	". 	c #FFFFFF",	"+ 	c #FB6A6A",	"@ 	c #FE5A5A",	"# 	c #FE5353",	"$ 	c #FF4F4F",	"% 	c #FB6161",	"& 	c #FB807F",	"* 	c #FE3F3F",	"= 	c #FF4646",	"- 	c #FF6464",	"; 	c #FF7575",	"> 	c #FF8181",	", 	c #FF7E7E",	"' 	c #FF7272",
				") 	c #FF6363",	"! 	c #FA2C2C",	"~ 	c #F06E6E",	"{ 	c #FF7979",	"] 	c #FF8C8C",	"^ 	c #FF9A9A",	"/ 	c #FFA5A5",	"( 	c #FFA2A2",	"_ 	c #FF6D6D",	": 	c #FF5D5D",	"< 	c #FF4B4B",	"[ 	c #FF3838",	"} 	c #EC2121",	"| 	c #D28C8C",	"1 	c #FE2B2B",
				"2 	c #FF4D4D",	"3 	c #FF6868",	"4 	c #FF9393",	"5 	c #FF3B3B",	"6 	c #FF2727",	"7 	c #D94646",	"8 	c #FF2E2E",	"9 	c #FF6666",	"0 	c #FF4242",	"a 	c #FF3636",	"b 	c #D22828",	"c 	c #FF5656",	"d 	c #FF4949",	"e 	c #F62D2D",	"f 	c #F62020",
				"g 	c #BA2929",	"h 	c #F82020",	"i 	c #FC6464",	"j 	c #F75F5F",	"k 	c #FF3131",	"l 	c #FE2121",	"m 	c #FE1818",	"n 	c #FF1D1D",	"o 	c #FF2424",	"p 	c #F43D3D",	"q 	c #FF3333",	"r 	c #EB2525",	"s 	c #E91919",	"t 	c #974E4E",	"u 	c #FB4C4C",	"v 	c #826B6B",
				"w 	c #FA2626",	"x 	c #FF1212",	"y 	c #FE0C0C",	"z 	c #FD0707",	"A 	c #F30505",	"B 	c #F50606",	"C 	c #F31B1B",	"D 	c #6F5F5F",	"E 	c #E41C1C",	"F 	c #D21010",	"G 	c #8B7676",	"H 	c #F83B3B",	"I 	c #9B7676",	"J 	c #BBD6D6",	"K 	c #956B6B",	"L 	c #F10000",
				"M 	c #F60000",	"N 	c #E90000",	"O 	c #E50000",	"P 	c #DD0000",	"Q 	c #715757",	"R 	c #9BBDBD",	"S 	c #9BC1C1",	"T 	c #A85F5F",	"U 	c #D91919",	"V 	c #DC1616",	"W 	c #921212",	"X 	c #E73434",	"Y 	c #C1E0E0",	"Z 	c #E00000",	"` 	c #EC0000",	" .	c #D40000",
				"..	c #FDFFFF",	"+.	c #A6D6D6",	"@.	c #BF7272",	"#.	c #F61010",	"$.	c #E21515",	"%.	c #D51515",	"&.	c #CF1313",	"*.	c #C20C0C",	"=.	c #D56060",	"-.	c #F5FFFF",	";.	c #D10000",	">.	c #796061",	",.	c #A5C1C1",	"'.	c #FAFFFF",	").	c #DB5353",	"!.	c #E80606",
				"~.	c #E30F0F",	"{.	c #C70F0F",	"].	c #CE0D0D",	"^.	c #671F1F",	"/.	c #D61919",	"(.	c #E54E4E",	"_.	c #CBE8E8",	":.	c #F3FFFF",	"<.	c #D60000",	"[.	c #ABCBCB",	"}.	c #E10B0B",	"|.	c #DA0D0D",	"1.	c #900606",	"2.	c #E56161",	"3.	c #F30E0E",	"4.	c #EB0000",
				"5.	c #D4ECEC",	"6.	c #AE8585",	"7.	c #C5DDDD",	"8.	c #D20000",	"9.	c #DB0505",	"0.	c #D20B0B",	"a.	c #CA0A0A",	"b.	c #BC0A0A",	"c.	c #A60505",	"d.	c #514E4E",	"e.	c #D73B3B",	"f.	c #D61212",	"g.	c #EB1313",	"h.	c #CC4141",	"i.	c #F6FFFF",	"j.	c #CB0000",
				"k.	c #D40707",	"l.	c #D40909",	"m.	c #CF0808",	"n.	c #C60808",	"o.	c #BB0707",	"p.	c #B50808",	"q.	c #B30505",	"r.	c #372C2C",	"s.	c #CB1F1F",	"t.	c #D00E0E",	"u.	c #DA0202",	"v.	c #F9FFFF",	"w.	c #C60000",	"x.	c #CE0101",	"y.	c #D00606",	"z.	c #C90606",
				"A.	c #C30505",	"B.	c #B60606",	"C.	c #AE0606",	"D.	c #B80707",	"E.	c #241717",	"F.	c #C11111",	"G.	c #D90A0A",	"H.	c #DC0D0D",	"I.	c #D50303",	"J.	c #C54846",	"K.	c #E8F8F8",	"L.	c #CC3A3A",	"M.	c #C90000",	"N.	c #CB0303",	"O.	c #CA0505",	"P.	c #C50505",
				"Q.	c #BE0404",	"R.	c #B20303",	"S.	c #A90505",	"T.	c #B90505",	"U.	c #C11F1F",	"V.	c #C20808",	"W.	c #D20303",	"X.	c #C20000",	"Y.	c #BE0303",	"Z.	c #B60303",	"`.	c #AD0202",	" +	c #A30303",	".+	c #CE0606",	"++	c #BD0000",	"@+	c #DAF5F5",	"#+	c #C50000",
				"$+	c #C10303",	"%+	c #B80202",	"&+	c #B00202",	"*+	c #A60202",	"=+	c #A10303",	"-+	c #9E0202",	";+	c #BB0404",	">+	c #C80505",	",+	c #BA0000",	"'+	c #AAC5C5",	")+	c #B70000",	"!+	c #AA0101",	"~+	c #890202",	"{+	c #BB0303",	"]+	c #C10101",	"^+	c #B90000",
				"/+	c #C8E0E0",	"(+	c #B30000",	"_+	c #B10000",	":+	c #A20000",	"<+	c #9A0000",	"[+	c #6D0101",	"}+	c #A30404",	"|+	c #CA4D4D",	"1+	c #A90101",	"2+	c #AC0000",	"3+	c #A50000",	"4+	c #9B0202",	"5+	c #930202",	"6+	c #380101",	"7+	c #A50202",	"8+	c #A5CBCB",
				"9+	c #AF0000",	"0+	c #B50000",	"a+	c #BA4848",	"b+	c #9D0000",	"c+	c #940202",	"d+	c #910202",	"e+	c #8C0202",	"f+	c #0F0201",	"g+	c #AC3131",	"h+	c #950000",	"i+	c #930000",	"j+	c #8B0000",	"k+	c #550101",	"l+	c #AE2727",	"m+	c #A10000",	"n+	c #8F0000",
				"o+	c #900000",	"p+	c #890000",	"q+	c #800000",	"r+	c #980000",	"s+	c #C55959",	"t+	c #A70000",	"u+	c #B33A3A",	"v+	c #860000",	"w+	c #820000",	"x+	c #970000",	"y+	c #9F0000",	"z+	c #8C0000",	"A+	c #850000",	"B+	c #7F0000",	"C+	c #5D0101",	"D+	c #4A0101",
				"E+	c #780101",	"F+	c #740101",
				"                        + @ # $ # % &                           ",
				"                  * = - ; > > , ' ) # * ! ~                     ",
				"              * = ) { ] ^ / ( ^ ] , _ : < [ } |                 ",
				"            1 2 3 , 4 ( / / ( ^ ] > ; 3 @ < 5 6 7               ",
				"          8 2 9 , ] 4 4 4 ] , { ' ' _ - @ $ 0 a 6 b             ",
				"        1 = : _ { > > ; 9 c d = d 2 # c $ d * [ e f g           ",
				"      h 5 2 : 9 i j c 0 k l m m n o k a p [ a q ! r s t         ",
				"    p 8 * < $ u v v w x y z A A B y y C D t o 6 w } E F G       ",
				"    } k 5 * H I J J K L M N O N N M P Q R S T m m E U V W       ",
				"  X r 8 k 8 I J . . Y K Z N O O `  .Q R . ..+.@.#.$.%.&.*.D     ",
				"  V } w 6 o =.Y -.. . Y I P N N ;.>.,.. . . '.).!.~.F {.].^.    ",
				"  /.E C l n z (._.:.. . _.I <. .G [.. . . . (.O }.|.].*.{.1.    ",
				"2.%.$.s C C 3.4.7 5.-.. . 5.6.6.7.... . . (.8.9.|.0.a.b.b.c.d.  ",
				"e.&.f.$.g.g.~.Z P h.5.i.. . -.:.. . . . 7 j.8.k.l.m.n.o.p.q.r.  ",
				"s.{.t.|.~.~.}.u.<. .h.5.'.. . . . ..v.7 w.j.x.y.m.z.A.B.C.D.E.  ",
				"F.*.a.0.G.H.G.I.8.8. .J.K.. . . . i.L.w.j.M.N.O.O.P.Q.R.S.T.E.  ",
				"U.b.V.a.m.l.k.W.;. .X.6.K.. ..... v.| X.M.w.w.P.P.Y.Z.`. +C.E.  ",
				"L.p.o.P.z..+y.x. .++v J . . . . . . @+6.++#+#+$+Y.%+&+*+=+-+E.  ",
				"=.C.B.;+A.>+N.8.,+>.'+. . . . . . . . _.I )+X.++%+R.!+-+-+~+E.  ",
				"  c.C.Z.{+]+x.^+Q ,.. . . . L.).'.-.. . /+K (+^+_+!+:+<+-+[+    ",
				"  }+c.`.(+++_+D '+. . . . h._+(+|+@+i.. . _.I 1+2+3+4+5+4+6+    ",
				"  g  +7+2+(+G 8+... . . |+9+0+)+(+a+@+v.. . @+6.:+b+c+d+e+f+    ",
				"    R.-+3+!+g+J :.. . h.2+(+(+(+(+2+a+5.. . . g+h+i+j+5+k+      ",
				"    l+-+<+m+m+l+'.. |+3+9+9+9+&+9+2+3+a+. . l+n+o+p+j+q+f+      ",
				"      *+c+h+<+r+a+s+b+t+1+!+!+1+1+3+:+<+s+u+j+j+v+w+j+6+        ",
				"        7+d+o+h+i+x+b+:+:+:+:+:+m+y+<+x+z+j+j+A+B+j+k+          ",
				"          -+n+z+n+i+h+r+<+r+<+<+r+x+h+n+j+v+w+B+p+C+            ",
				"            i+n+p+p+z+z+n+o+o+o+n+j+j+v+w+B+w+w+D+              ",
				"              E+d+j+A+A+A+A+v+v+A+w+q+B+w+A+F+6+                ",
				"                  F+v+p+A+w+w+q+q+q+w+w+[+6+                    ",
				"                        C+[+F+F+[+C+D+                          ",
				"                                                                ");
				break;
			
			case MB_ICONQUESTION:
				//Icon: Question
				$arIcon = array(
				"32 32 252 2",
				"  	c None",	". 	c #FFFFFF",	"+ 	c #BBC6D2",	"@ 	c #E1E6EB",	"# 	c #EAEBED",	"$ 	c #EDEDED",	"% 	c #EDEDEE",	"& 	c #EBECEE",	"* 	c #E4E9ED",	"= 	c #DAE3ED",	"- 	c #F1F1F0",	"; 	c #F4F4F5",	"> 	c #F8F8F9",	", 	c #F9FAFB",	"' 	c #F9FBFD",	") 	c #F4F6F9",	"! 	c #EBEEF3",	"~ 	c #D9DEE1",	"{ 	c #A1B2C2",	"] 	c #F9F9FA",	"^ 	c #FDFDFE",
				"/ 	c #EEF2F6",	"( 	c #DDE4EB",	"_ 	c #CCD5DD",	": 	c #E3E4E6",	"< 	c #FAFDFF",	"[ 	c #EFF5FA",	"} 	c #BECDDB",	"| 	c #E8E9EB",	"1 	c #FCFEFF",	"2 	c #F9FDFF",	"3 	c #C6D7EB",	"4 	c #8099CB",	"5 	c #718EC5",	"6 	c #7995C7",	"7 	c #9EB4CE",	"8 	c #F2F8FC",	"9 	c #E4EBF2",	"0 	c #DFE5ED",	"a 	c #C4D0DB",	"b 	c #C3C6CA",	"c 	c #DFE1E3",
				"d 	c #F6FAFE",	"e 	c #E9F0F9",	"f 	c #5D7CAD",	"g 	c #002480",	"h 	c #2853A8",	"i 	c #001C75",	"j 	c #00185C",	"k 	c #0F2C6D",	"l 	c #8096BE",	"m 	c #F3FCFF",	"n 	c #F1FAFF",	"o 	c #DFE8F3",	"p 	c #B3C5D6",	"q 	c #F3F9FF",	"r 	c #F5FDFF",	"s 	c #6888AC",	"t 	c #001A6A",	"u 	c #143D90",	"v 	c #CADCF2",	"w 	c #E4EDFA",	"x 	c #305397",
				"y 	c #002274",	"z 	c #00247B",	"A 	c #84A1CE",	"B 	c #E9F5FF",	"C 	c #EBF5FC",	"D 	c #DAE5F1",	"E 	c #D2DFEC",	"F 	c #F0F8FF",	"G 	c #143578",	"H 	c #002987",	"I 	c #708ABC",	"J 	c #013194",	"K 	c #DEECFC",	"L 	c #E8F4FF",	"M 	c #E3F1FF",	"N 	c #EEFBFF",	"O 	c #FBFFFF",	"P 	c #E1ECF9",	"Q 	c #96A0A9",	"R 	c #D0D6DC",	"S 	c #DAE1E8",
				"T 	c #F8FFFF",	"U 	c #EDF7FF",	"V 	c #032977",	"W 	c #022D8E",	"X 	c #365CA6",	"Y 	c #9AB3D4",	"Z 	c #1F4899",	"` 	c #DFEFFF",	" .	c #EAFAFF",	"..	c #D1E1F2",	"+.	c #ADC3D7",	"@.	c #EDF7FC",	"#.	c #EAF5FF",	"$.	c #6988BE",	"%.	c #406FC8",	"&.	c #8DA5CA",	"*.	c #CDE2F9",	"=.	c #E3F3FF",	"-.	c #DCEDFF",	";.	c #DBEDFF",	">.	c #DDF0FF",
				",.	c #D1E6F9",	"'.	c #B2C6DA",	").	c #AABFD2",	"!.	c #A4A7AA",	"~.	c #6F747A",	"{.	c #E6F3FF",	"].	c #E4F2FF",	"^.	c #BCD5F3",	"/.	c #C6DDF9",	"(.	c #DDEDFD",	"_.	c #07399B",	":.	c #E4F4FF",	"<.	c #D9ECFF",	"[.	c #D8EBFF",	"}.	c #DFF5FF",	"|.	c #D6E9FA",	"1.	c #9CA7B1",	"2.	c #B7C3CE",	"3.	c #E1F0FF",	"4.	c #1A3E88",	"5.	c #3566C8",
				"6.	c #5A83D0",	"7.	c #C3DCF6",	"8.	c #DAEEFF",	"9.	c #D5EAFF",	"0.	c #D3E9FF",	"a.	c #D6EDFE",	"b.	c #BCD1E5",	"c.	c #97A6B2",	"d.	c #D4E1EF",	"e.	c #DDEEFF",	"f.	c #E1F3FF",	"g.	c #88B2F7",	"h.	c #C8E0F9",	"i.	c #D1E8FF",	"j.	c #CFE7FF",	"k.	c #D2EBFF",	"l.	c #BCC9D7",	"m.	c #D6EBFF",	"n.	c #0C3B9C",	"o.	c #98BBE2",	"p.	c #CCE6FF",
				"q.	c #CEE8FF",	"r.	c #B3CBE2",	"s.	c #8B99A7",	"t.	c #A8B9CB",	"u.	c #B8C6D5",	"v.	c #B5D1F0",	"w.	c #6287C8",	"x.	c #CCE5FF",	"y.	c #C9E4FF",	"z.	c #CAE5FF",	"A.	c #78818B",	"B.	c #A1B5CA",	"C.	c #B0BFCE",	"D.	c #204EA6",	"E.	c #8AAFE3",	"F.	c #C8E3FF",	"G.	c #C5E2FF",	"H.	c #C6E3FF",	"I.	c #8DA7C3",	"J.	c #859DB7",	"K.	c #A2B3C3",
				"L.	c #C0DCFB",	"M.	c #C4E0FF",	"N.	c #C3E1FF",	"O.	c #BBD9F8",	"P.	c #ABC7E2",	"Q.	c #788EA5",	"R.	c #737576",	"S.	c #95AABE",	"T.	c #90AFDB",	"U.	c #BEDDFC",	"V.	c #C1E0FF",	"W.	c #BFDFFF",	"X.	c #BCDCFC",	"Y.	c #ABCBEC",	"Z.	c #9EBBD9",	"`.	c #8DABC8",	" +	c #617388",	".+	c #5E5E5E",	"++	c #98ABBD",	"@+	c #A3B9CE",	"#+	c #CBE7FF",
				"$+	c #466CAC",	"%+	c #C4E4FF",	"&+	c #BDDEFF",	"*+	c #B9DCFD",	"=+	c #94AFCA",	"-+	c #7395B8",	";+	c #54585B",	">+	c #C3E3FF",	",+	c #09317D",	"'+	c #A8CEF5",	")+	c #BFE1FF",	"!+	c #BADDFF",	"~+	c #B6DAFE",	"{+	c #7E9EBE",	"]+	c #505A64",	"^+	c #809BB6",	"/+	c #90A6BC",	"(+	c #C1E2FF",	"_+	c #0F3FA1",	":+	c #1B4AAB",	"<+	c #AED3F9",
				"[+	c #A4C7E9",	"}+	c #96B6D8",	"|+	c #7B9CBD",	"1+	c #576F88",	"2+	c #7C99B5",	"3+	c #AAC8E7",	"4+	c #B2D3F4",	"5+	c #B7D9FB",	"6+	c #B9DBFD",	"7+	c #9FC4F0",	"8+	c #B2D8FD",	"9+	c #ACD1F7",	"0+	c #A4C8EC",	"a+	c #92B3D1",	"b+	c #789ABF",	"c+	c #708DAB",	"d+	c #A2C2E3",	"e+	c #B5DCFF",	"f+	c #9FC2E7",	"g+	c #95B8DB",	"h+	c #8DAECF",
				"i+	c #58636F",	"j+	c #7191B3",	"k+	c #85A1BD",	"l+	c #82A4C4",	"m+	c #84A9CF",	"n+	c #6D93B9",	"o+	c #6C6C6C",	"p+	c #61686E",	"q+	c #7897B7",	"r+	c #7998B8",	"s+	c #7997B8",	"t+	c #8BAFD2",	"u+	c #9BC3EB",	"v+	c #769ABE",	"w+	c #4B4F52",	"x+	c #565656",	"y+	c #8CB4DB",	"z+	c #7EA3C8",	"A+	c #96C0E9",	"B+	c #73A2D2",	"C+	c #6D99C4",
				"                    + @ # $ $ $ % & * = =                       ",
				"              + * % - ; > , , , ' , ) ; ! * ~ {                 ",
				"          + @ & - ; ] ^ . . . . . . . ' ) / ! ( _               ",
				"        + : $ ; , . . . . . . . . . . . . < [ ! * } {           ",
				"      + : | ) 1 . . 1 . 2 3 4 5 6 7 = . . . . 8 9 0 a b         ",
				"    + c * / 1 . < d < e f g h h i j k l m n < . 8 o = p b       ",
				"    ~ @ / < . d q q r s t u v w x y z t A r B m . C D E { {     ",
				"  + ~ 9 d 1 q F F F e G H g I . l t J i x K L M N O P E } Q {   ",
				"  R S e T F U U U U P V W H X N Y y J z Z v L ` ` N  ...+.{ {   ",
				"+ R = @.n #.#.L L L B $.h %.6 r &.y J g x *.=.-.;.>. .,.'.).!.~.",
				"+ _ D U #.{.{.{.].].{.K ^./.(. .f i _.J f :.;.<.[.[.}.|.3 ).1.~.",
				"2.a D L ].].3.M 3.3.` 3.:.=.:.v 4.W 5.6.7.8.9.9.9.0.a.,.b.+.c.~.",
				"2.} d.M 3.` ` ` e.e.;.;.<.<.f.A g 5.g.h.<.0.i.i.i.j.k.*.b.'.c.~.",
				"2.l.E ` e.-.-.;.<.<.[.[.m.m.a.Z n.o.a.9.j.j.j.j.p.p.q.h.r.+.s.~.",
				"t.u.3 <.<.<.[.m.9.9.9.0.i.m.v.J w.}.i.p.p.x.x.x.y.y.z.7.r.7 A.~.",
				"B.C.b.,.m.9.9.0.0.i.i.i.j.k.v.D.E.a.x.z.F.F.F.F.G.H.G.v.+.I.~.~.",
				"J.K.p 3 i.i.j.j.j.j.p.x.x.x.q.L.y.p.F.F.G.G.G.M.N.N.O.P.7 Q.R.  ",
				"  S.t.r.h.j.p.x.z.y.F.F.y.p.T.$.A U.G.M.N.V.V.W.W.X.Y.Z.`. +.+  ",
				"  J.++@+r./.z.F.F.F.H.G.#+A j j j $+%+W.W.&+&+&+*+Y.Z.=+-+;+    ",
				"    J.S.@+r.L.H.G.N.N.V.>+x t H y ,+'+)+!+!+!+~+Y.Z.`.{+]+.+    ",
				"      ^+/+7 P.^.W.V.W.W.(+$+i _+:+Z <+!+~+~+<+[+}+`.|+1+.+      ",
				"        2+/+=+Z.3+4+5+6+W.7+X %.g.E.!+8+9+0+o.a+I.b+1+.+        ",
				"          c+^+I.=+Y Z.d+Y.4+9+<+e+e+'+f+g+h+`.|+s i+.+          ",
				"            Q.j+^+k+k+l+m+h+}+f+9+'+o.h+l+{+n+1+]+o+            ",
				"                p+p+c+q+r+s+{+t+u+u+t+v+f 1+w+.+                ",
				"                    p+p+p+2+2+l+o.u+m+1+x+p+                    ",
				"                          p+2+l+y+u+m+p+                        ",
				"                            p+z+m+A+B+p+                        ",
				"                              p+n+y+B+i+                        ",
				"                                p+C+B+i+                        ",
				"                                  p+s p+                        ",
				"                                    p+p+                        ");
				break;

			case MB_ICONEXCLAMATION:
				//Icon: Exclamatio
				$arIcon = array(
				"32 32 249 2",	"  	c None",	". 	c #FFFFFF",	"+ 	c #F5F70F",	"@ 	c #FFFF07",	"# 	c #C7D538",	"$ 	c #3160C5",	"% 	c #F3F300",	"& 	c #F9F600",	"* 	c #FFFF00",	"= 	c #C2C11A",	"- 	c #2444AA",	"; 	c #EBEA00",	"> 	c #FDFA03",	", 	c #FFFF2A",	"' 	c #51534F",	") 	c #223FA5",	"! 	c #EAE600",	"~ 	c #FEFD17",	"{ 	c #FFFF68",	"] 	c #FFFE0D",
				"^ 	c #090E7B",	"/ 	c #E5E100",	"( 	c #F2EB00",	"_ 	c #FFFF18",	": 	c #FFFF31",	"< 	c #FFFF22",	"[ 	c #464655",	"} 	c #1D359A",	"| 	c #E9E101",	"1 	c #F9F404",	"2 	c #FFFF11",	"3 	c #FFFF09",	"4 	c #FFF800",	"5 	c #060D74",	"6 	c #E7DD00",	"7 	c #FFFF0E",	"8 	c #FFFF1C",	"9 	c #FFFF16",	"0 	c #FFF500",	"a 	c #FFFD00",	"b 	c #FFF503",
				"c 	c #FFFF13",	"d 	c #FEF703",	"e 	c #C3B802",	"f 	c #B3AC1F",	"g 	c #E9DB00",	"h 	c #F2E300",	"i 	c #A7A611",	"j 	c #000001",	"k 	c #000009",	"l 	c #E3E223",	"m 	c #FCF000",	"n 	c #FFFA00",	"o 	c #FFF003",	"p 	c #000007",	"q 	c #0A0030",	"r 	c #000032",	"s 	c #5C4D2E",	"t 	c #FFFF1E",	"u 	c #FFF300",	"v 	c #040C7F",	"w 	c #E6D401",
				"x 	c #FFFF0B",	"y 	c #000016",	"z 	c #1B0347",	"A 	c #020045",	"B 	c #4F3B22",	"C 	c #FFFF15",	"D 	c #FFEB00",	"E 	c #433854",	"F 	c #1E3AA3",	"G 	c #E2D30A",	"H 	c #FAE803",	"I 	c #FFFF03",	"J 	c #100A06",	"K 	c #00001B",	"L 	c #15003E",	"M 	c #735922",	"N 	c #FFFF24",	"O 	c #FFED00",	"P 	c #FFF700",	"Q 	c #A29828",	"R 	c #091183",
				"S 	c #F2DA01",	"T 	c #FFF60E",	"U 	c #594915",	"V 	c #00002E",	"W 	c #A89015",	"X 	c #FFF915",	"Y 	c #FBE500",	"Z 	c #2E2E62",	"` 	c #F8E504",	" .	c #FFFB13",	"..	c #B4A301",	"+.	c #372168",	"@.	c #3D274F",	"#.	c #E3CC02",	"$.	c #FFF70D",	"%.	c #FFE700",	"&.	c #FFF100",	"*.	c #060F85",	"=.	c #E5CE00",	"-.	c #EBD500",	";.	c #FFF00D",
				">.	c #FFE800",	",.	c #D0BC00",	"'.	c #000022",	").	c #5D4EA6",	"!.	c #FFF512",	"~.	c #FFE300",	"{.	c #F9E100",	"].	c #323260",	"^.	c #2544A3",	"/.	c #DAC615",	"(.	c #FFEA05",	"_.	c #271822",	":.	c #645032",	"<.	c #FFE400",	"[.	c #FFF40E",	"}.	c #FADE00",	"|.	c #FFEF00",	"1.	c #98892C",	"2.	c #0B1982",	"3.	c #E6CA00",	"4.	c #E9CF00",
				"5.	c #FEE708",	"6.	c #00007C",	"7.	c #FFE706",	"8.	c #F8DB00",	"9.	c #242765",	"0.	c #2747A4",	"a.	c #E1CB0E",	"b.	c #F3D500",	"c.	c #FFEE11",	"d.	c #FFEA08",	"e.	c #8C7B1D",	"f.	c #00006A",	"g.	c #CCAB06",	"h.	c #FFE100",	"i.	c #FFF716",	"j.	c #FFF81A",	"k.	c #F9D601",	"l.	c #ECCD00",	"m.	c #FEE206",	"n.	c #FFF111",	"o.	c #FFDF00",
				"p.	c #C3A50F",	"q.	c #EDCA01",	"r.	c #FFDD00",	"s.	c #2B2C62",	"t.	c #2748A9",	"u.	c #E8C900",	"v.	c #F3D200",	"w.	c #1D1043",	"x.	c #FFDB00",	"y.	c #FFF91D",	"z.	c #908031",	"A.	c #0F1E85",	"B.	c #E8C600",	"C.	c #FEDE0B",	"D.	c #FFD900",	"E.	c #FFE40A",	"F.	c #FCD600",	"G.	c #282964",	"H.	c #284AAB",	"I.	c #E7C505",	"J.	c #F7D002",
				"K.	c #FFEC14",	"L.	c #FFD600",	"M.	c #FFF219",	"N.	c #FFFD25",	"O.	c #FDD500",	"P.	c #E5BF00",	"Q.	c #ECC600",	"R.	c #FFE811",	"S.	c #000012",	"T.	c #FFD500",	"U.	c #FFDC03",	"V.	c #FFD300",	"W.	c #F5CE00",	"X.	c #D5BA1B",	"Y.	c #F1C900",	"Z.	c #FFE310",	"`.	c #FFDD06",	" +	c #241911",	".+	c #FFE914",	"++	c #FFEF1C",	"@+	c #F9CC00",
				"#+	c #887535",	"$+	c #101E84",	"%+	c #EAC100",	"&+	c #FCD107",	"*+	c #FFE20D",	"=+	c #000004",	"-+	c #311A61",	";+	c #1B0869",	">+	c #7A625A",	",+	c #FFDA05",	"'+	c #FED506",	")+	c #FECF00",	"!+	c #13186F",	"~+	c #42108F",	"{+	c #E3B800",	"]+	c #BEB2FF",	"^+	c #AD9E53",	"/+	c #F6C800",	"(+	c #584E4E",	"_+	c #C8B025",	":+	c #DEB500",
				"<+	c #E5BA00",	"[+	c #F1C500",	"}+	c #F6C700",	"|+	c #F5C701",	"1+	c #F5C901",	"2+	c #F7CA01",	"3+	c #FFD100",	"4+	c #836921",	"5+	c #B3973D",	"6+	c #FACB00",	"7+	c #EFC100",	"8+	c #9A985E",	"9+	c #D6AC00",	"0+	c #DAB100",	"a+	c #DFB300",	"b+	c #E2B600",	"c+	c #FCCB00",	"d+	c #F1C100",	"e+	c #E0B400",	"f+	c #504751",	"g+	c #B6A336",
				"h+	c #CBA502",	"i+	c #CDA400",	"j+	c #CDA600",	"k+	c #CEA500",	"l+	c #CFA700",	"m+	c #DDB202",	"n+	c #816B36",	"o+	c #0D1373",	"p+	c #1C1F6A",	"q+	c #3356A7",	"r+	c #2D306A",	"s+	c #313060",	"t+	c #323060",	"u+	c #32305F",	"v+	c #35325D",	"w+	c #294CAC",	"x+	c #2A4CAB",	"y+	c #2C4DA8",	"z+	c #2E51AD",
				"                                                                ",
				"                            + @ # $                             ",
				"                          % & * * = -                           ",
				"                        ; ; > , * * ' )                         ",
				"                        ! ; ~ { ] * = ^                         ",
				"                      / / ( _ : < & * [ }                       ",
				"                      | ! 1 2 3 , 4 * = 5                       ",
				"                    6 6 ( 7 3 * 8 9 0 a [ }                     ",
				"                    | | b c d e 3 < 4 * f ^                     ",
				"                  g g h 7 i j j k l c m n [ }                   ",
				"                  g g o c p k q r s t u n f v                   ",
				"                w w h 7 x j y z A B C 9 D u E F                 ",
				"                G g H c I J K L A M I N O P Q R                 ",
				"              w w S T ] * U V z z W * t X Y u Z )               ",
				"              w w `  .o * ..V +.@.#.P $.N %.&.Q *.              ",
				"            =.=.-.;.$.>.a ,.'.).E n O >.t !.~.{.].^.            ",
				"            /.=.S !.(.%.&.>._.).:.0 >.<.[.t }.|.1.2.            ",
				"          3.3.4.5.;.<.<.>.* U 6.W n %.~.7.t 5.8.~.9.0.          ",
				"          a.3.b.c.d.~.~.<.a e.f.g.4 <.h.h.i.j.k.>.1.2.          ",
				"        3.3.l.m.n.h.o.o.h.&.p.A q.|.h.o.r.7.N m.r.b.s.t.        ",
				"        /.u.v.c.5.r.r.o.o.<.k.w.o.>.o.r.r.x.j.y.k.>.z.A.        ",
				"      B.B.q.C.c.r.x.x.x.r.h.<.p.<.h.x.x.r.D.m., E.F.r.G.H.      ",
				"      I.B.J.K.E.D.D.x.x.o.D O 0 D >.x.x.x.D.L.M.N.O.~.1.2.      ",
				"    P.P.Q.C.R.D.L.L.D.x.D g.S.p _.F.h.D.D.L.T.U.N.C.V.W.Z 0.    ",
				"    X.P.Y.Z.`.T.V.L.L.x.>. +j K r M &.L.L.T.T.T..+++@+h.#+$+    ",
				"  P.P.%+&+*+L.V.V.V.V.D.o.=+S.-+;+>+O L.V.V.V.V.,+++'+)+Y.!+~+  ",
				"  {+{+%+'+,+L.L.L.L.L.D.%.B y ;+]+^+<.L.L.L.L.T.L.*+'+/+L.(+~+  ",
				"  _+:+<+[+}+|+1+1+|+1+2+3+D.4+>+5+V.6+/+1+|+1+|+/+/+[+7+V.#+5   ",
				"  8+9+0+:+a+a+a+a+a+a+a+b+<+6+c+d+b+e+a+a+a+a+a+a+e+{+7+)+f+!+  ",
				"    g+0+h+i+j+i+i+i+i+j+i+k+l+l+k+k+j+j+i+i+j+i+i+k+9+m+n+o+p+  ",
				"      q+r+s+t+t+t+t+t+t+t+u+u+u+u+u+t+t+t+t+t+t+t+s+v+Z o+p+    ",
				"        $ H.H.w+w+w+w+w+w+w+w+w+w+w+w+w+w+w+w+w+w+w+w+x+y+z+    ");
				break;

			case MB_ICONASTERISK:
				//Icon: Information
				$arIcon = array(
				"32 32 255 2",	"  	c None",	". 	c #FFFFFF",	"+ 	c #F4F7FA",	"@ 	c #E6EEF6",	"# 	c #E2E8EE",	"$ 	c #E9EBED",	"% 	c #EEEEEE",	"& 	c #E6EBF0",	"* 	c #E0E9F2",	"= 	c #E9F1F9",	"- 	c #EBF1F6",	"; 	c #EBEEF2",	"> 	c #F1F0EE",	", 	c #F1F2F2",	"' 	c #F7F6F7",	") 	c #FBFCFC",	"! 	c #F6F7F9",	"~ 	c #F2F4F5",	"{ 	c #DADDE4",	"] 	c #E8F0F7",
				"^ 	c #E1E4E7",	"/ 	c #EAECED",	"( 	c #F0EFF0",	"_ 	c #F8F9FA",	": 	c #D6DCE3",	"< 	c #B0B5C3",	"[ 	c #FAFDFE",	"} 	c #F1F5F8",	"| 	c #CCD5E2",	"1 	c #E4ECF6",	"2 	c #ECECEC",	"3 	c #FDFFFF",	"4 	c #99A7BC",	"5 	c #0E245D",	"6 	c #000F53",	"7 	c #A6B6C9",	"8 	c #F4F8FC",	"9 	c #C7CFDC",	"0 	c #DFE2E3",	"a 	c #E6E7E9",	"b 	c #32437A",
				"c 	c #001C77",	"d 	c #002A91",	"e 	c #31508E",	"f 	c #FBFFFF",	"g 	c #F8FCFE",	"h 	c #D3DFEC",	"i 	c #B1BCCF",	"j 	c #F7FCFF",	"k 	c #F5FAFF",	"l 	c #002686",	"m 	c #2F61C5",	"n 	c #2358BB",	"o 	c #3F63A9",	"p 	c #F3FBFF",	"q 	c #EEF7FF",	"r 	c #F1F8FF",	"s 	c #F9FBFE",	"t 	c #DCE4ED",	"u 	c #CDDBEA",	"v 	c #F3F9FF",	"w 	c #F9FEFF",
				"x 	c #A5BBD4",	"y 	c #1A4599",	"z 	c #4C7BD1",	"A 	c #ABC3E2",	"B 	c #ECF6FF",	"C 	c #EBF5FF",	"D 	c #E9F5FF",	"E 	c #F1FAFF",	"F 	c #DDEAF6",	"G 	c #D6E2EE",	"H 	c #B6C6DA",	"I 	c #AFAFC3",	"J 	c #EEF8FF",	"K 	c #E1ECFB",	"L 	c #C7DBF1",	"M 	c #E9F3FF",	"N 	c #E7F3FF",	"O 	c #E7F4FF",	"P 	c #E5F2FF",	"Q 	c #E4F1FF",	"R 	c #EBF7FF",
				"S 	c #EAF6FD",	"T 	c #D5E4F2",	"U 	c #808BAE",	"V 	c #BBC6D1",	"W 	c #CED4DC",	"X 	c #ECF4FF",	"Y 	c #E2F1FF",	"Z 	c #DFEFFF",	"` 	c #E9F7FF",	" .	c #F3FDFF",	"..	c #D9EAF8",	"+.	c #B5CDE4",	"@.	c #706688",	"#.	c #D1D7DD",	"$.	c #ECF4FB",	"%.	c #657BA3",	"&.	c #5F6E99",	"*.	c #375080",	"=.	c #E0F0FF",	"-.	c #DDEEFF",	";.	c #DCEDFF",
				">.	c #DBEDFF",	",.	c #E9FAFF",	"'.	c #DFF0FC",	").	c #CBDEF1",	"!.	c #C0D2E4",	"~.	c #BECAD6",	"{.	c #9AB4D3",	"].	c #143A87",	"^.	c #00227E",	"/.	c #D3E6F9",	"(.	c #D8ECFF",	"_.	c #D8EBFF",	":.	c #D7EBFF",	"<.	c #DCF0FF",	"[.	c #E0F4FF",	"}.	c #8392B3",	"|.	c #837794",	"1.	c #CEE2F5",	"2.	c #00349C",	"3.	c #002B93",	"4.	c #355AA2",
				"5.	c #D0E3F8",	"6.	c #D5EAFF",	"7.	c #D4E9FF",	"8.	c #D9EFFF",	"9.	c #B3C9DD",	"0.	c #8996B4",	"a.	c #514169",	"b.	c #E3F2FF",	"c.	c #88A2C9",	"d.	c #002988",	"e.	c #00339A",	"f.	c #CAE2F9",	"g.	c #D2E9FF",	"h.	c #D1E8FF",	"i.	c #D3EBFF",	"j.	c #C0D7EF",	"k.	c #4D3D66",	"l.	c #B7C4CF",	"m.	c #819CC4",	"n.	c #002A85",	"o.	c #3158A0",
				"p.	c #C7DFF8",	"q.	c #D1E7FF",	"r.	c #CEE7FF",	"s.	c #CDE6FF",	"t.	c #CBE6FF",	"u.	c #CBE5FF",	"v.	c #C9E3FF",	"w.	c #B8D0E9",	"x.	c #A7C2DD",	"y.	c #7D8EB1",	"z.	c #B2C1D0",	"A.	c #7B99C4",	"B.	c #C1DCF8",	"C.	c #C9E4FF",	"D.	c #C7E3FF",	"E.	c #6E86AD",	"F.	c #D5EDFF",	"G.	c #C4E2FF",	"H.	c #C4E1FF",	"I.	c #C3E1FF",	"J.	c #B4D2F0",
				"K.	c #9BB5CF",	"L.	c #4A5586",	"M.	c #2E56A1",	"N.	c #BCDAF8",	"O.	c #C0E0FF",	"P.	c #B9D8F8",	"Q.	c #9EB9D4",	"R.	c #82A3C4",	"S.	c #200C40",	"T.	c #342250",	"U.	c #99AFCA",	"V.	c #9BAEC1",	"W.	c #CBE9FF",	"X.	c #002A86",	"Y.	c #BFE0FF",	"Z.	c #BDDEFF",	"`.	c #B8D8F9",	" +	c #A9C8E6",	".+	c #8DAAC7",	"++	c #506495",	"@+	c #889FB7",
				"#+	c #C5E0FB",	"$+	c #D3F1FF",	"%+	c #7091C0",	"&+	c #002E95",	"*+	c #AECFF2",	"=+	c #BADDFF",	"-+	c #B3D8FB",	";+	c #A7C7E8",	">+	c #8FABC7",	",+	c #6888B1",	"'+	c #24194D",	")+	c #94ABC3",	"!+	c #BEDCFA",	"~+	c #5F80B2",	"{+	c #1445AA",	"]+	c #7CA1D0",	"^+	c #B8DBFF",	"/+	c #B1D5F9",	"(+	c #A3C5E6",	"_+	c #93B3D3",	":+	c #8AA7C4",
				"<+	c #7396BA",	"[+	c #2F2E62",	"}+	c #8999BB",	"|+	c #829DB6",	"1+	c #5682CF",	"2+	c #6A98E7",	"3+	c #A4C9EE",	"4+	c #9BBDE0",	"5+	c #3F4174",	"6+	c #7F8BAE",	"7+	c #7B97B6",	"8+	c #87A1BB",	"9+	c #BFE3FF",	"0+	c #BFE4FF",	"a+	c #BCE1FF",	"b+	c #A2C6EA",	"c+	c #89AAC9",	"d+	c #7F9FBE",	"e+	c #9298B2",	"f+	c #7E9BB8",	"g+	c #8DAFD0",
				"h+	c #92B6DB",	"i+	c #96BADD",	"j+	c #AED4FC",	"k+	c #332150",	"l+	c #53608C",	"m+	c #7C99B6",	"n+	c #7B9ABA",	"o+	c #7D9CBE",	"p+	c #9BC3EB",	"q+	c #7799BA",	"r+	c #6C93BB",	"s+	c #5F5176",	"t+	c #6D6285",	"u+	c #7896B8",	"v+	c #8CB3DA",	"w+	c #34386D",	"x+	c #190439",	"y+	c #71759B",	"z+	c #402E5A",	"A+	c #8DAACD",	"B+	c #84A9CD",
				"C+	c #88B4E1",	"D+	c #729FCB",	"E+	c #83AFDB",	"F+	c #638FC2",
				"                  + @ # $ % % % % % & * = +                     ",
				"              - ; $ > , ' ) . . . ) ! ~ , & { ]                 ",
				"          ^ # / ( ~ _ ) . . : < : . . [ + } ; & |               ",
				"        1 ^ 2 , _ 3 . . . 4 5 6 5 7 . . . [ 8 - & | 9           ",
				"      * 0 a ~ [ . . 3 3 - b c d c e @ f f . . g ] # h i         ",
				"    * 0 a - [ . f j k j } e l m n o = p q r j . s 1 t u 4       ",
				"  ^ : ^ ; g . j v v r r w x y z z A j B C C D E 3 k F G H I     ",
				"  : { # 8 3 k r r r J q B p K L K J M M N O P Q R f S T u U     ",
				"V W { @ w v q q B B X q E J E E J C P Q Q Q Y Y Z `  ...u +.@.  ",
				"V #.t $.p X C M M M R H %.%.%.&.*.%...P =.Z Z -.;.>.,.'.).!.&.  ",
				"~.W t B C N N N P Q R {.].^.l l c e /.Y ;.>.>.(._.:.<.[.).!.}.|.",
				"V 9 G O N P Q Y Y =.=.Y 1.o d 2.3.4.5.<.(.:.:.:.6.7.6.8.L 9.0.a.",
				"V ~.G b.Y =.Z Z -.-.;.-.,.c.d.e.d 4.f.8.6.7.7.g.g.h.h.i.j.9.0.k.",
				"l.~.h Z -.-.;.;.>.>.(._.[.m.n.e.d o.p.:.q.q.r.r.r.s.r.r.j.9.0.k.",
				"i V u >.>.>.(.:.:.6.6.7.<.m.n.e.d o.p.i.s.s.t.u.u.u.u.v.w.x.y.k.",
				"7 z.!./.:.6.6.7.7.g.h.h.8.A.n.e.d o.B.r.u.C.C.v.v.D.C.B.+.x E.k.",
				"4 7 H L h.h.h.h.h.r.s.s.F.A.n.e.d o.B.s.D.D.D.G.H.G.I.J.x.K.L.a.",
				"4 4 x +.f.q.s.s.u.u.C.C.i.A.n.e.d M.N.C.I.I.O.O.O.O.P.A Q.R.S.T.",
				"  U.V.x w.f.t.C.C.C.D.D.W.A.X.e.d M.P.G.O.Y.Z.Z.Z.`. +Q..+++T.  ",
				"  @+@+V.x w.#+D.G.G.H.I.$+%+d.&+d ].*+W.Z.=+=+=+-+;+{.>+,+'+T.  ",
				"    @+@+)+x +.!+I.I.I.`.~+y {+m m {+M.]+^+=+^+/+(+_+:+<+[+T.    ",
				"      }+|+)+K.x.J.`.Y.*+4.z 1+1+1+2+2+1+-+/+3+4+_+:+<+5+S.      ",
				"        6+7+8+>+{.x.;+*+Y.Y.9+9+0+a+=+-+b+4+_+c+d+,+[+S.        ",
				"          e+%.f+8+:+>+g+h+i+4+3+j+j+3+i+g+:+d+<+++'+k+          ",
				"            e+l+l+E.m+n+n+o+R.g+p+3+i+c+q+r+L.[+S.s+            ",
				"                l+t+5+l+&.%.u+R.h+p+v+,+w+'+x+T.                ",
				"                      |.|.t+y+o+g+p+v+L.z+x+                    ",
				"                              A+B+p+C+L.x+                      ",
				"                                r+v+C+b x+                      ",
				"                                  D+E+b x+                      ",
				"                                    F+b x+                      ",
				"                                      T.x+                      ");
				break;
		}//switch $nImage

		return $arIcon;
	}//function GetIcon( $nImage);

}//class MessageBox()

?>