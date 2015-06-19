<?php
$__DPCSEC['RCITEMS_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("RCITEMS_DPC")) && (seclevel('RCITEMS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCITEMS_DPC",true);

$__DPC['RCITEMS_DPC'] = 'rcitems';

//$a = GetGlobal('controller')->require_dpc('nitobi/nitobi.lib.php');
//require_once($a);


$d = GetGlobal('controller')->require_dpc('shop/shcategories.dpc.php');
require_once($d);

//$z = GetGlobal('controller')->require_dpc('nitobi/nhandler.lib.php');//NOT USED
//require_once($z);

$e = GetGlobal('controller')->require_dpc('images/wateresize.lib.php');
require_once($e);

$f= GetGlobal('controller')->require_dpc('images/SimpleImage.lib.php');
require_once($f);

$g = GetGlobal('controller')->require_dpc('shell/pxml.lib.php');
require_once($g);		


$__EVENTS['RCITEMS_DPC'][0]='cpitems';
$__EVENTS['RCITEMS_DPC'][1]='cpvinput';
$__EVENTS['RCITEMS_DPC'][2]='cpvmodify';
$__EVENTS['RCITEMS_DPC'][3]='cpvdelete';
$__EVENTS['RCITEMS_DPC'][4]='cpvoffer';
$__EVENTS['RCITEMS_DPC'][5]='cpvactive';
$__EVENTS['RCITEMS_DPC'][6]='cpvphoto';
$__EVENTS['RCITEMS_DPC'][7]='cpvchphoto';
$__EVENTS['RCITEMS_DPC'][8]='cpvdelphoto';
$__EVENTS['RCITEMS_DPC'][9]='cpvrestorephoto';
$__EVENTS['RCITEMS_DPC'][10]='cpvrecode';
$__EVENTS['RCITEMS_DPC'][11]='cpvmail';
$__EVENTS['RCITEMS_DPC'][12]='cpvmsend';
$__EVENTS['RCITEMS_DPC'][13]='openfolder';
$__EVENTS['RCITEMS_DPC'][14]='cpattach2db';
$__EVENTS['RCITEMS_DPC'][15]='cpvdelattach';
$__EVENTS['RCITEMS_DPC'][16]='cpattachitem2db';
$__EVENTS['RCITEMS_DPC'][17]='cpvaddattach';
$__EVENTS['RCITEMS_DPC'][18]='cpvdbphoto';
$__EVENTS['RCITEMS_DPC'][19]='cpshowdbphoto';
$__EVENTS['RCITEMS_DPC'][20]='cpvmixphoto';
$__EVENTS['RCITEMS_DPC'][21]='cpvitemrss';
$__EVENTS['RCITEMS_DPC'][22]='cpeditframe';
$__EVENTS['RCITEMS_DPC'][23]='cpphotoframe';

$__ACTIONS['RCITEMS_DPC'][0]='cpitems';
$__ACTIONS['RCITEMS_DPC'][1]='cpvinput';
$__ACTIONS['RCITEMS_DPC'][2]='cpvmodify';
$__ACTIONS['RCITEMS_DPC'][3]='cpvdelete';
$__ACTIONS['RCITEMS_DPC'][4]='cpvoffer';
$__ACTIONS['RCITEMS_DPC'][5]='cpvactive';
$__ACTIONS['RCITEMS_DPC'][6]='cpvphoto';
$__ACTIONS['RCITEMS_DPC'][7]='cpvchphoto';
$__ACTIONS['RCITEMS_DPC'][8]='cpvdelphoto';
$__ACTIONS['RCITEMS_DPC'][9]='cpvrestorephoto';
$__ACTIONS['RCITEMS_DPC'][10]='cpvrecode';
$__ACTIONS['RCITEMS_DPC'][11]='cpvmail';
$__ACTIONS['RCITEMS_DPC'][12]='cpvmsend';
$__ACTIONS['RCITEMS_DPC'][13]='openfolder';
$__ACTIONS['RCITEMS_DPC'][14]='cpattach2db';
$__ACTIONS['RCITEMS_DPC'][15]='cpvdelattach';
$__ACTIONS['RCITEMS_DPC'][16]='cpattachitem2db';
$__ACTIONS['RCITEMS_DPC'][17]='cpvaddattach';
$__ACTIONS['RCITEMS_DPC'][18]='cpvdbphoto';
$__ACTIONS['RCITEMS_DPC'][19]='cpshowdbphoto';
$__ACTIONS['RCITEMS_DPC'][20]='cpvmixphoto';
$__ACTIONS['RCITEMS_DPC'][21]='cpvitemrss';
$__ACTIONS['RCITEMS_DPC'][22]='cpeditframe';
$__ACTIONS['RCITEMS_DPC'][23]='cpphotoframe';

$__LOCALE['RCITEMS_DPC'][0]='RCITEMS_DPC;Items;Προιόντα';
$__LOCALE['RCITEMS_DPC'][1]='_type;Category;Κατηγορία';
$__LOCALE['RCITEMS_DPC'][2]='_axia2;Cost_2;Κοστος 1';
$__LOCALE['RCITEMS_DPC'][3]='_axia1;Cost_1;Τιμή';
$__LOCALE['RCITEMS_DPC'][4]='_date1;Date;Ημ/νια';
$__LOCALE['RCITEMS_DPC'][5]='_fipi;Horses;Ιπποι';
$__LOCALE['RCITEMS_DPC'][6]='_km;Kilometers;Χλμ';
$__LOCALE['RCITEMS_DPC'][7]='_color;Color;Χρώμα';
$__LOCALE['RCITEMS_DPC'][8]='_etosk;Registration year;Ετος Κυκλ.';
$__LOCALE['RCITEMS_DPC'][9]='_model;Model;Μοντέλο';
$__LOCALE['RCITEMS_DPC'][10]='_marka;Marka;Μάρκα';
$__LOCALE['RCITEMS_DPC'][11]='_aucdate;Auction start;Έναρξη δημοπράτησης';
$__LOCALE['RCITEMS_DPC'][12]='_auctime;Auction end;Τέλος δημοπράτησης';
$__LOCALE['RCITEMS_DPC'][13]='_synal2;Synal_2;Συναλλαγη 2';
$__LOCALE['RCITEMS_DPC'][14]='_synal1;Synal_1;Συναλλαγη 1';
$__LOCALE['RCITEMS_DPC'][15]='_thesi;Thesis;Θέση';
$__LOCALE['RCITEMS_DPC'][16]='_arkyk;Registration number;Αριθμός κυκλοφορίας';
$__LOCALE['RCITEMS_DPC'][17]='_kybismos;Kybismos;Κυβισμός';
$__LOCALE['RCITEMS_DPC'][18]='_noumero;Number;Νούμερο';
$__LOCALE['RCITEMS_DPC'][19]='_flg_pwl;Flg_PWL;Flg_PWL';
$__LOCALE['RCITEMS_DPC'][20]='_numagor;Buy price;Ποσό αγοράς';
$__LOCALE['RCITEMS_DPC'][21]='_flag;Flag;Flag';
$__LOCALE['RCITEMS_DPC'][22]='_date2;Date_2;Ημ/νια 2';
$__LOCALE['RCITEMS_DPC'][23]='_sxolia;Remarks;Σχόλια';
$__LOCALE['RCITEMS_DPC'][24]='_id;ID;A/A';
$__LOCALE['RCITEMS_DPC'][25]='_active;ACTIVE;Ενεργό';
$__LOCALE['RCITEMS_DPC'][26]='_type2;Category;Κατηγορία';
$__LOCALE['RCITEMS_DPC'][27]='_photo;Photo;Φωτο';
$__LOCALE['RCITEMS_DPC'][28]='_type2;Type;Τύπος';
$__LOCALE['RCITEMS_DPC'][29]='_RCITEMPHOTO;Upload file;Συνημένο είδους';
$__LOCALE['RCITEMS_DPC'][30]='_add;Add Item;Εισαγωγή';
$__LOCALE['RCITEMS_DPC'][31]='_edit;Edit Item;Μεταβολή';
$__LOCALE['RCITEMS_DPC'][32]='_offer;Make it Offer;Ενεργοποίηση προσφοράς';
$__LOCALE['RCITEMS_DPC'][33]='_recode;Recode;Recode';
$__LOCALE['RCITEMS_DPC'][34]='_delete;Delete Item;Διαγραφή';
$__LOCALE['RCITEMS_DPC'][35]='_mail;Mail Item;Ταχυδρομείο';
$__LOCALE['RCITEMS_DPC'][36]='_code;Code;Κωδικός';
$__LOCALE['RCITEMS_DPC'][37]='_axia;Cost;Τιμή';
$__LOCALE['RCITEMS_DPC'][38]='_sysins;Insert date;Ημ/νια εισαγωγής';
$__LOCALE['RCITEMS_DPC'][39]='_itmdescr;Item name;Περιγραφή';
$__LOCALE['RCITEMS_DPC'][40]='_uniname1;MM A;Μονάδα μέτρησης Α';
$__LOCALE['RCITEMS_DPC'][41]='_uniname2;MM B;Μονάδα μέτρησης Β';
$__LOCALE['RCITEMS_DPC'][42]='_offer;Make offer;Είδος προσφοράς';
$__LOCALE['RCITEMS_DPC'][43]='_axia3;Cost_3;Τιμή';
$__LOCALE['RCITEMS_DPC'][44]='_percent;% Markup;% Επιβάρυνση';
$__LOCALE['RCITEMS_DPC'][45]='_axiapc;% Markup;% Επιβάρυνση';
$__LOCALE['RCITEMS_DPC'][46]='_itmname;Title;Τίτλος';
$__LOCALE['RCITEMS_DPC'][47]='_cat;Category;Κατηγορία ';
$__LOCALE['RCITEMS_DPC'][48]='_cat;Category;Κατηγορία ';
$__LOCALE['RCITEMS_DPC'][49]='_cat1;Category 1;Κατηγορία 1';
$__LOCALE['RCITEMS_DPC'][50]='_cat2;Category 2;Κατηγορία  2';
$__LOCALE['RCITEMS_DPC'][51]='_cat3;Category 3;Κατηγορία  3';
$__LOCALE['RCITEMS_DPC'][52]='_cat4;Category 4;Κατηγορία  4';
$__LOCALE['RCITEMS_DPC'][53]='_cat0;Category 0;Κατηγορία  0';
$__LOCALE['RCITEMS_DPC'][54]='_attach2db;Attachments to database;Μεταφορά συνημλενων στην βάση δεδομένων';
$__LOCALE['RCITEMS_DPC'][55]='_RCUPLOADDB;Upload;Μεταφόρτωση';
$__LOCALE['RCITEMS_DPC'][56]='_ATTACHED;Saved;Αποθηκεύτηκε';
$__LOCALE['RCITEMS_DPC'][57]='_NOTATTACHED;Not saved, there is no changes;Δεν Αποθηκεύτηκε, δεν υπάρχουν αλλαγές!';
$__LOCALE['RCITEMS_DPC'][58]='_code1;Code;Κωδικός';
$__LOCALE['RCITEMS_DPC'][59]='_code2;Code;Κωδικός';
$__LOCALE['RCITEMS_DPC'][60]='_code3;Code;Κωδικός';
$__LOCALE['RCITEMS_DPC'][61]='_code4;Code;Κωδικός';
$__LOCALE['RCITEMS_DPC'][62]='_code5;Code;Κωδικός';
$__LOCALE['RCITEMS_DPC'][63]='_itmactive;Active;Ενεργό';
$__LOCALE['RCITEMS_DPC'][64]='_itmremark;Remarks;Παρατηρήσεις';
$__LOCALE['RCITEMS_DPC'][65]='_uni1uni2;Units 1 to 2;Σχέση μονάδων 1 προς 2';
$__LOCALE['RCITEMS_DPC'][66]='_uni2uni1;Units 2 to 1;Σχέση μονάδων 2 προς 1';
$__LOCALE['RCITEMS_DPC'][67]='_price0;Price A;Τιμή Α';
$__LOCALE['RCITEMS_DPC'][68]='_price1;Price B;Τιμή Β';
$__LOCALE['RCITEMS_DPC'][69]='_pricepc;Price %;Ποσοστό έκπτωσης';
$__LOCALE['RCITEMS_DPC'][70]='_p1;Special 1;Ειδικό χαρακτηριστικό 1';
$__LOCALE['RCITEMS_DPC'][71]='_p2;Special 2;Ειδικό χαρακτηριστικό 2';
$__LOCALE['RCITEMS_DPC'][72]='_p3;Special 3;Ειδικό χαρακτηριστικό 3';
$__LOCALE['RCITEMS_DPC'][73]='_p4;Special 4;Ειδικό χαρακτηριστικό 4';
$__LOCALE['RCITEMS_DPC'][74]='_p5;Special 5;Ειδικό χαρακτηριστικό 5';
$__LOCALE['RCITEMS_DPC'][75]='_ypoloipo1;Remain A;Υπολοίπο Μονάδας μέτρησης Α';
$__LOCALE['RCITEMS_DPC'][76]='_ypoloipo2;Remain B;Υπολοίπο Μονάδας μέτρησης Β';
$__LOCALE['RCITEMS_DPC'][77]='_price2;Price C;Τιμή Γ';
$__LOCALE['RCITEMS_DPC'][78]='_resources;Resource;Πηγή';
$__LOCALE['RCITEMS_DPC'][79]='_weight;Weight;Βάρος';
$__LOCALE['RCITEMS_DPC'][80]='_volume;Volume;Όγκος';
$__LOCALE['RCITEMS_DPC'][81]='_OK;Success;Επιτυχώς';
$__LOCALE['RCITEMS_DPC'][82]='_sysupd;Update date;Ημ/νία τελευταίας ενημέρωσης';
$__LOCALE['RCITEMS_DPC'][83]='_uniida;uni ID;ID Μονάδας μέτρησης';
$__LOCALE['RCITEMS_DPC'][84]='_itmfname;Title;Τίτλος';
$__LOCALE['RCITEMS_DPC'][85]='_itmfdescr;Description;Περιγραφή';
$__LOCALE['RCITEMS_DPC'][86]='_RCITEMREMOTEPHOTO;Pick remote file;Συννημένο είδους (URL)';
$__LOCALE['RCITEMS_DPC'][87]='_RCITEMMIXPHOTO;Mix photo;Υδατογράφημα φωτογραφίας';
$__LOCALE['RCITEMS_DPC'][88]='_RCITEMPHOTOQUALITY;Photo quality (1..99);Συμπίεση (1..99)';
$__LOCALE['RCITEMS_DPC'][89]='_MYSUBMIT;Submit;Εκτέλεση';
$__LOCALE['RCITEMS_DPC'][90]='_EDITTEXT;Edit;Κείμενο';
$__LOCALE['RCITEMS_DPC'][91]='_EDITPHOTO;Photo;Φωτό';

   //    $fields = "code1,code2,code3,code4,code5,itmname,itmactive,itmfname,itmremark,itmdescr,itmfdescr,sysins,sysupd,uniida,uniname1,uniname2" .
     //            ",uni1uni2,uni2uni1,ypoloipo1,ypoloipo2,price0,price1,cat0,cat1,cat2,cat3,cat4,active,price2,pricepc,p1,p2,p3,p4,p5";

class rcitems /*extends shcategories*/ {

    var $title,$msg;
	var $result;
	var $path, $urlpath;
	var $imgpath,$restype,$thubpath,$publicthubpath;
	var $_grids;
	var $debug_sql;	
	var $charts;
	var $ajaxLink, $hasgraph;
    var $delete_attach,$restore_attach,$delete_item,$edit_item,$offer_item,$add_item,$sep,$mail_item;	
    var $image2add, $image2sold;
	var $imgpath2mail, $urlbase, $infolder;
	var $map_t,$map_f;
	var $img2path, $previewx, $previewy, $graphx, $graphy;	
	var $ptp, $defptp, $adptp, $defadptp;
	var $attach_path;
	var $img_small,$img_medium,$img_large;
	var $delete_attached_file;
	var $autoresize;
	var $autoinc, $syncurl;
	var $photodb, $erase2db;
	var $mixphoto, $photoquality, $mixx, $mixy;
	var $url;
	var $cseparator, $encodeimageid;
    var $editimage;	
	
	function rcitems() {
	  $GRX = GetGlobal('GRX');		
	  
      //shcategories::shcategories();	  
	
	  $this->debug_sql = true;	
	
	  $this->title = localize('RCITEMS_DPC',getlocal());	
	  $this->msg = null;		 
	  $this->result = null;		
	  $this->path = paramload('SHELL','prpath');  
	  
	  $this->infolder = paramload('ID','hostinpath');	  
	  $this->thubpath = $this->path . $this->infolder . '/images/thub/';	  
	  $this->urlpath = paramload('SHELL','urlpath').$this->infolder.'/';		  
	  $this->urlbase = paramload('SHELL','urlbase').$this->infolder.'/';
	  $murl = arrayload('SHELL','ip');
      $this->url = $murl[0]; 	  
	  
	  $this->defptp = '/images/thub/';
	  $this->ptp = remote_paramload('RCITEMS','respath',$this->path);
	  $this->publicthubpath = $this->ptp?'..'.$this->ptp:'..'.$this->defptp;	  
	  $this->imgpath = $this->ptp?'..'.$this->ptp:'..'.$this->defptp;
	  $this->defadptp = '/images/uphotos/';
      $this->adptp = remote_paramload('RCITEMS','adrespath',$this->path);	  
	  $this->img2path = $this->adptp?'..'.$this->adptp:'..'.$this->defadptp;	  
	  $this->imgpath2mail = $this->urlbase . $this->infolder . $this->ptp;//'/images/thub/';		  
	  $this->restype = remote_paramload('RCITEMS','restype',$this->path);//'.jpg'; 
	  
	  //3 sized scaled images
      $photo_bg = remote_paramload('RCITEMS','photobgpath',$this->path);		  
      $this->img_large = $photo_bg?"/images/$photo_bg/":null;	  	  
      $photo_md = remote_paramload('RCITEMS','photomdpath',$this->path);		  
      $this->img_medium = $photo_md?"/images/$photo_md/":null;	  	  
      $photo_sm = remote_paramload('RCITEMS','photosmpath',$this->path);		  
      $this->img_small = $photo_sm?"/images/$photo_sm/":null;	  
	      	
	  
	  $this->reset_on_import =  paramload('RCITEMS','resetonimport');  
	  
      //$this->_grids[] = new nitobi("Items");	  
	  $this->charts = new swfcharts;	
	  $this->ajaxLink = seturl('t=cpvstatsshow&statsid=');	  
	  
	  
      if ($GRX) {    
          $this->delete_attach = loadTheme('dphoto',localize('_delete',getlocal())); 
          $this->restore_attach = loadTheme('rphoto',localize('_restore',getlocal()));		  
          $this->delete_item = loadTheme('ditem',localize('_delete',getlocal())); 
          $this->edit_item = loadTheme('eitem',localize('_edit',getlocal()));			  
          $this->offer_item = loadTheme('iitem',localize('_offer',getlocal())); 
          $this->attach2db_item = loadTheme('ritem',localize('_attach2db',getlocal()));	
          $this->add_item = loadTheme('aitem',localize('_add',getlocal())); 
          $this->mail_item = loadTheme('mailitem',localize('_mail',getlocal())); 		  
		  
		  $this->sep ='&nbsp;';// loadTheme('lsep');		  		  
      } 
      else { 
          $this->delete_attach = localize('_delete',getlocal()); 
          $this->restore_attach = localize('_restore',getlocal());		  
          $this->delete_item = localize('_delete',getlocal()); 
          $this->edit_item = localize('_edit',getlocal());			  
          $this->offer_item = localize('_offer',getlocal()); 
          $this->attach2db_item = localize('_attach2db',getlocal());	
          $this->add_item = localize('_add',getlocal()); 
          $this->mail_item = localize('_mail',getlocal());		  
		  
		  $this->sep = "|";	
      }	
	  
	  //$this->image2add = paramload('RCITEMS','image2add'); 
      $this->image2add = remote_paramload('RCITEMS','image2add',$this->path);	  
	  //$this->image2sold = paramload('RCITEMS','image2sold'); 	  
      $this->image2sold = remote_paramload('RCITEMS','image2sold',$this->path);	
	  
	  $this->map_t = remote_arrayload('RCITEMS','maptitle',$this->path);	
	  $this->map_f = remote_arrayload('RCITEMS','mapfields',$this->path);
	  
	  $this->previewx = remote_paramload('RCITEMS','previewx',$this->path);
	  $this->previewy = remote_paramload('RCITEMS','previewy',$this->path);
	  $this->graphx = remote_paramload('RCITEMS','graphx',$this->path);
	  $this->graphy = remote_paramload('RCITEMS','graphy',$this->path);	
	  
	  $this->attachpath = $this->urlpath . 'cp/html/';  
	  $this->delete_attached_file = remote_paramload('RCITEMS','delattachfile',$this->path);		  	

      $this->autoresize = remote_arrayload('RCITEMS','autoresize',$this->path);		  
	  $this->autoinc = remote_arrayload('RCITEMS','autoinc',$this->path);	 
      $this->syncurl = remote_paramload('RCITEMS','syncurl',$this->path);	

      $this->photodb = remote_paramload('RCITEMS','photodb',$this->path);
      $this->erase2db = remote_paramload('RCITEMS','erase2db',$this->path);	  
	  $this->mixphoto = remote_paramload('RCITEMS','mixphoto',$this->path);	 
	  $this->photoquality = remote_paramload('RCITEMS','photoquality',$this->path);
	  
	  $mixx = remote_paramload('RCITEMS','mixx',$this->path);	 
	  $mixy = remote_paramload('RCITEMS','mixy',$this->path);	 	  
	  $this->mixx = $mixx?$mixx:'CENTER';	 
	  $this->mixy = $mixy?$mixy:'MIDDLE';

	  $csep = remote_paramload('RCITEMS','csep',$this->path); 
      $this->cseparator = $csep ? $csep : '^';	
      $this->encodeimageid = remote_paramload('RCITEMS','encodeimageid',$this->path);	  
	  $this->editimage = true;//false;
	}
	
    function event($sAction) {
       $db = GetGlobal('db');	
	   
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') {//die("Not logged in!");//	
	     if (!GetReq('editmode'))		 
	       die("Not logged in!");//	
		 else
     	   //header("Location: cp.php?editmode=1&encoding=" . GetReq('encoding'));  
   	       die("Not logged in! <A href='cp.php?editmode=1&encoding=".GetReq('encoding')."'>LOGIN</A>");//
	   }		
	   /////////////////////////////////////////////////////////////		   		

       if (!$this->msg) {
  
	     switch ($sAction) {	
		   case 'cpvitemrss'  :$this->item_rss(GetReq('id'),GetReq('cat'));
			                   break;		 
		 
		   case 'cpvaddattach':$this->add_attachment();
		                       $this->grid_javascript();
							  break;		 
		   case 'cpattachitem2db':$this->add_attachment2db(GetReq('id'));
		                       $this->grid_javascript();
							  break;		 
		   case 'cpvdelattach':$this->delete_attachments();
		                       $this->grid_javascript();
							  break;
		   case 'cpattach2db':$this->attach2db();
		                      $this->grid_javascript();
		                      break;
	       case 'cpvmsend'  : $this->send_mail();
                              $this->grid_javascript();
		                      //$this->read_list();
		                      break;	   
	       case 'cpvmail'   :
		                      break;		 	 
							  
		    case 'cpvmixphoto'  :  $this->photo2mix();
			                       break;							  
		    case 'cpshowdbphoto':  $this->show_photodb(GetReq('id'), GetReq('type'));
			                       //die in func
			                       break;
		    case 'cpvdbphoto'  :   $this->photo2db(GetReq('notexisted'));
			                       break;							  
		    case 'cpvchphoto'  :   $this->change_photo();
			                       break;
		    case 'cpvdelphoto' :   $this->delete_photo();
			                       break;								   
		    case 'cpvrestorephoto' : //in case of cp only forms needs id in post//GetReq('id'); 
			                       $this->item_sync_photo(GetParam('id'),GetParam('cat'),GetParam('notexisted'));
								   // if ((!GetReq('cat')) && (!GetReq('id'))) //when all codes
			                       //$this->directory_sync_photo();//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<all dir
			                       break;									   
		    case 'cpvphoto'    :   break;
		   
		    case 'cpeditframe':    echo $this->loadframe('edtem');
								   die();
		                           break;	
		    case 'cpphotoframe':   echo $this->loadframe2('phtem');
								   die();
		                           break;								   
							 
		    case 'cpvinput'    :   break;
		    case 'cpvmodify'   :   break;			
		    case 'cpvdelete'   :   $this->delete_from_list(); 
			                       $this->grid_javascript(); 
			                       //$this->sidewin(); 
			                       //$this->read_list();
			                       break;
								   
		    case 'cpvoffer'    :   $this->import_to_offers(); 
			                       $this->grid_javascript();   
			                       //$this->read_list();
			                       break;	
								   
		    case 'cpvactive'   :   $this->activate_list(); 
			                       //$this->read_list();
			                       break;									   							   
			
            case 'openfolder'  :			
	        case 'cpitems'     :   
			default :              $this->grid_javascript(); 
			                       //$this->sidewin(); 
			                       //$this->read_list(); 
                                   //$this->charts = new swfcharts;	
		                           $this->hasgraph = $this->charts->create_chart_data('statisticscat',"where year >=2000 and attr1='".urldecode(GetReq('cat'))."'");
								   break;	
													
										  
         }
      }
    }	

    function action($action)  {
         $db = GetGlobal('db');		 

	     //$this->reset_db();
		 
		 $out = $this->title();
		 		 
	     switch ($action) {			 
		    case 'cpvaddattach' :$out .= $this->add_attachment(1);
		                         break;

	        case 'cpvmsend'  :$out .= $this->list_items();
		                      break;	   
	        case 'cpvmail'   :$out .= $this->show_mail();
		                      break;
		    case 'cpvmixphoto' :
		    case 'cpvdbphoto'  :							  
		    case 'cpvchphoto'  :
			case 'cpvdelphoto' :
			case 'cpvitemrss'  :
			case 'cpvrestorephoto':
		    case 'cpvphoto'    :   $out .= $this->form_photo();
			                       break;		 
		    case 'cpvinput'    :   $out .= $this->form_insert2(null,null,null,null,'d',true);
			                       break;
		    case 'cpvmodify'   :   $out .= $this->form_modify();
			                       break;			
		    case 'cpvdelete'   :   $out .= $this->list_items();
			                       break;
		    case 'cpvoffer'    :   $out .= $this->list_items();
			                       break;			   
		    case 'cpvactive'   :   $out .= $this->list_items();
			                       break;									   						   
		    case 'cpattachitem2db' :									   
			case 'cpattach2db' :	
			case 'cpvdelattach':				 
            case 'openfolder'  :			
	        case 'cpitems'     :   
			default            :   $out .= $this->list_items();                       							
										  
         }		 

	     return ($out);
	}
	
	function grid_javascript() {
	
      if (iniload('JAVASCRIPT'))  {
	  
		   //$template = $this->set_template();   		      
		   
	       $code = $this->init_grids();	     		

		   //$code .= $this->_grids[0]->OnClick(17,'ItemDetails',$template);//,'VehicleCustomers','id',17);
	   
		   $js = new jscript;
		   //$js->setloadparams("init()");
           //$js->load_js('nitobi.grid.js');//javascript folder	 
           //$js->load_js('nitobi.grid.js',null,null,null,1); //local			   
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }		
	}
	
	/*function set_template() {
	       $x = $this->previewx?$this->previewx:160;
		   $y = $this->previewy?$this->previewy:120;
	
			
	       $edit = seturl("t=cpvmodify&cat=".GetReq('cat')."&rec=");
		   $add =  seturl("t=cpvinput&cat=".GetReq('cat')."&sel=".GetReq('sel'));
		   $off =  seturl("t=cpvoffer&cat=".GetReq('cat')."&rec=");	   
		   $del =  seturl("t=cpvdelete&rec=");	
		   $mail = seturl("t=cpvmail&rec=");
		   	   		   
		   $template .= "<A href=\"$edit'+i16+'\">".$this->edit_item."</A>". $this->sep;
		   $template .= "<A href=\"$add\">".$this->add_item."</A>". $this->sep;		   
		   $template .= "<A href=\"$del'+i16+'\">".$this->delete_item."</A>". $this->sep;		   		   		   			   
		   $template .= "<A href=\"$off'+i16+'\">".$this->offer_item."</A>". $this->sep;			   
		   $template .= "<A href=\"$mail'+i16+'\">".$this->mail_item."</A>". $this->sep;			   
		   $template .= "<br>";
		   
		   $template .= "<h4>'+update_stats_id(i0,i0,i3)+'</h4>";		   
	  						   			   
		   $template .= "<table width=\"100%\" class=\"group_win_body\">";	   
		   $template .= "<tr><td>".localize('_code',getlocal()).":</td><td><b>'+i0+'</b></td></tr>";	
		   $template .= "<tr><td>".localize('_sysins',getlocal()).":</td><td><b>'+i2+'</b></td></tr>";		
		   $template .= "<tr><td>".localize('_itmname',getlocal()).":</td><td><b>'+i3+'</b></td></tr>";
		   $template .= "<tr><td>".localize('_uniname1',getlocal()).":</td><td><b>'+i4+'</b></td></tr>";				   		   
		   $template .= "<tr><td>".localize('_uniname2',getlocal()).":</td><td><b>'+i5+'</b></td></tr>";		   		   
		   $template .= "<tr><td>".localize('_axia1',getlocal()).":</td><td><b>'+i6+'</b></td></tr>";		
		   $template .= "<tr><td>".localize('_cat0',getlocal()).":</td><td><b>'+i11+'</b></td></tr>";		
		   $template .= "<tr><td>".localize('_cat1',getlocal()).":</td><td><b>'+i12+'</b></td></tr>";				   		   
		   $template .= "<tr><td>".localize('_cat2',getlocal()).":</td><td><b>'+i13+'</b></td></tr>";	
		   $template .= "<tr><td>".localize('_cat3',getlocal()).":</td><td><b>'+i14+'</b></td></tr>";
		   $template .= "<tr><td>".localize('_cat4',getlocal()).":</td><td><b>'+i15+'</b></td></tr>";
		   		   		   
		   $template .= "<tr><td><A href=\"?t=cpvphoto&id='+i0+'\"><img src=\"$this->publicthubpath'+photo_name(i0)+'$this->restype\" width=$x height=$y></A>";	

		   $template .= "</td><td>'+i10+'</td></tr></table>";	     
		   //$template .= "'+i10+'"; //sxolia out of array
		   //$template .= "<h4>'+update_stats_id(i0,i0,i3)+'</h4>";		   
		   
		   return ($template);	
	}*/			

	function title() {
       $sFormErr = GetGlobal('sFormErr');

       //navigation status 
	   /*if (!GetReq('editmode')) {
          
	     if (GetSessionParam('REMOTELOGIN')) 
	       $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	     else  
           $out = setNavigator(seturl("t=cp","Control Panel"),$this->title); 	
       }*/
       //error message
	   $out .= setError($sFormErr);
	   
	   return ($out);
	}
	
	//for utf strings as products code..encode to digits for saving image
	public function encode_image_id($id=null) {
	    if (!$id) return null;

		if ($this->encodeimageid) 
			$out = md5($id);
		else
		    $out = $id;
			
        return $out;
	}	
	
    function form_photo($onlyforms=false, $form_cat=null, $form_item=null)  { 	
	
       $cat = GetReq('cat');		
       $sel = GetReq('sel');
	   //when form called
	   $fid = $form_item ? $form_item : (GetParam('id') ? GetParam('id') : GetReq('id'));
	   $fcat = $form_cat ? $form_cat : (GetParam('cat') ? GetParam('cat') : GetReq('cat'));	   
		  
	   $id = $this->encode_image_id(GetReq('id'));
	   
	   $photo = $this->imgpath .  $id . $this->restype; 
	   $ppath = $this->urlpath. /*'/images/thub/'*/$this->ptp . $id . $this->restype;  //echo $ppath;
	   $interface = $this->photodb;
	   
		 if (GetReq('editmode')) {
		   $filename = seturl("t=cpvchphoto&cat=".$cat."&id=".$id.'&editmode=1');
           $filename2 = seturl("t=cpvrestorephoto&cat=".$cat."&id=".$id.'&editmode=1');	 		   
		   $filename3 = seturl("t=cpvmixphoto&cat=".$cat."&id=".$id.'&editmode=1');
		 }   
		 else {
           $filename = seturl("t=cpvchphoto&cat=".$cat."&id=".$id);			  
		   $filename2 = seturl("t=cpvrestorephoto&cat=".$cat."&id=".$id);	
		   $filename3 = seturl("t=cpvmixphoto&cat=".$cat."&id=".$id);
		 }  
		
	     //upload file(s) form
         $uout  = "<FORM action=". "$filename" . " method=post ENCTYPE=\"multipart/form-data\" class=\"thin\">";
         $uout .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" VALUE=\"".$this->MAXSIZE."\">"; //max file size option in bytes
         $uout .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	   
         $uout .= localize('_RCITEMPHOTO',getlocal()) . ": <input type=FILE name=\"uploadfile\">";		
		 
		 //echo $this->urlpath.$this->img_large;
		 $uout .= "<select name=\"phototype\">";
		 if (($this->img_large) && (is_dir($this->urlpath.$this->img_large))) { //3 sized photos
		   $uout .= "<option>SMALL</option>";		 
		   $uout .= "<option>MEDIUM</option>";	
		   $uout .= "<option selected>LARGE</option>";			   		   
		 }    
		 else { //1 sized photo		 
		   $uout .= "<option>Default</option>";
		 }
		 $uout .= "<option>A</option>";		 
		 $uout .= "<option>B</option>";		 
		 $uout .= "<option>C</option>";		 
		 $uout .= "<option>D</option>";		 
		 $uout .= "<option>E</option>";		 
		 $uout .= "<option>F</option>";	
		 $uout .= "<option>G</option>";		 
		 $uout .= "<option>H</option>";		 
		 $uout .= "<option>I</option>";			 		 			 		 		 
		 $uout .= "</select>";
		 
		 //save for cp form call..
		 $uout .= "<input type=\"hidden\" name=\"id\" value=\"".$fid."\">";
		 $uout .= "<input type=\"hidden\" name=\"cat\" value=\"".$fcat."\">";		 		  
		  
         $uout .= "<input type=\"hidden\" name=\"FormName\" value=\"cpvchphoto\">"; 	
         $uout .= "<input type=\"hidden\" name=\"FormAction\" value=\"cpvchphoto\">&nbsp;";			
         $uout .= "<input type=\"submit\" name=\"Submit\" value=\"".localize('_RCUPLOADDB',getlocal())."\">";		
         $uout .= "</FONT></FORM>"; 	

         //.................................................
	     //url remote based file(s) form
		 if ($fid) //form call..is the same as id//($id) // = GetReq('id'))
		   $surl = $this->syncurl . $fid . $this->restype;
		 else
           $surl = $this->syncurl;		 
         $rout  = "<FORM action=". "$filename2" . " method=post class=\"thin\">";
         $rout .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	   
         $rout .= localize('_RCITEMPHOTO',getlocal()) . ": <input name=\"remotefile\" type=\"Text\" value=\"". $surl ."\" size=\"36\" maxlength=\"64\">";		
		 
		 //echo $this->urlpath.$this->img_large;
		 $rout .= "<select name=\"phototype\">";
		 if (($this->img_large) && (is_dir($this->urlpath.$this->img_large))) { //3 sized photos
		   $rout .= "<option>SMALL</option>";		 
		   $rout .= "<option>MEDIUM</option>";	
		   $rout .= "<option selected>LARGE</option>";			   		   
		 }    
		 else { //1 sized photo		 
		   $rout .= "<option>Default</option>";
		 }
		 $rout .= "<option>A</option>";		 
		 $rout .= "<option>B</option>";		 
		 $rout .= "<option>C</option>";		 
		 $rout .= "<option>D</option>";		 
		 $rout .= "<option>E</option>";		 
		 $rout .= "<option>F</option>";	
		 $rout .= "<option>G</option>";		 
		 $rout .= "<option>H</option>";		 
		 $rout .= "<option>I</option>";			 		 			 		 		 
		 $rout .= "</select>";
		 
		 //save for cp form call..
		 $rout .= "<input type=\"hidden\" name=\"id\" value=\"".$fid."\">";
		 $rout .= "<input type=\"hidden\" name=\"cat\" value=\"".$fcat."\">";		 		 
		  
         $rout .= "<input type=\"hidden\" name=\"FormName\" value=\"cpvrestorephoto\">"; 	
         $rout .= "<input type=\"hidden\" name=\"FormAction\" value=\"cpvrestorephoto\">&nbsp;";			
         $rout .= "<input type=\"submit\" name=\"Submit\" value=\"".localize('_RCUPLOADDB',getlocal())."\">";		
         $rout .= "</FONT></FORM>"; 		 
       //...................................................
	   //mix photo, sold...
         $mout  = "<FORM action=". "$filename2" . " method=post class=\"thin\">";
         $mout .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	   
         $mout .= localize('_RCITEMMIXPHOTO',getlocal()) . ": <input name=\"mixphoto\" type=\"Text\" value=\"". $this->mixphoto ."\" size=\"36\" maxlength=\"64\">" . 
		          localize('_RCITEMPHOTOQUALITY',getlocal()) . ": <input name=\"photoquality\" type=\"Text\" value=\"". $this->photoquality ."\" size=\"2\" maxlength=\"2\">"; 
         
		 $mout .= '<br>';
		 
         $mout .= "X potition:";
		 $mout .= "<select name=\"mixx\">";
		 $mout .= "<option>Default</option>";		 
		 $mout .= "<option>LEFT</option>";		 
		 $mout .= "<option>CENTER</option>";	
		 $mout .= "<option>RIGHT</option>";			   		   	 		 		 			 		 		 
		 $mout .= "</select>";	
		 
         $mout .= "Y potition:";
		 $mout .= "<select name=\"mixy\">";
		 $mout .= "<option>Default</option>";		 
		 $mout .= "<option>TOP</option>";		 
		 $mout .= "<option>MIDDLE</option>";	
		 $mout .= "<option>BOTTOM</option>";			   		   	 		 		 			 		 		 
		 $mout .= "</select>";			 
		 
         $mout .= "Target:";
		 $mout .= "<select name=\"phototype\">";
		 if (($this->img_large) && (is_dir($this->urlpath.$this->img_large))) { //3 sized photos
		   $mout .= "<option>SMALL</option>";		 
		   $mout .= "<option>MEDIUM</option>";	
		   $mout .= "<option>LARGE</option>";			   		   
		 }    
		 else { //1 sized photo		 
		   $mout .= "<option>Default</option>";
		 }
		 $mout .= "<option>A</option>";		 
		 $mout .= "<option>B</option>";		 
		 $mout .= "<option>C</option>";		 
		 $mout .= "<option>D</option>";		 
		 $mout .= "<option>E</option>";		 
		 $mout .= "<option>F</option>";	
		 $mout .= "<option>G</option>";		 
		 $mout .= "<option>H</option>";		 
		 $mout .= "<option>I</option>";			 		 			 		 		 
		 $mout .= "</select>";	
		 
		 //save for cp form call..
		 $mout .= "<input type=\"hidden\" name=\"id\" value=\"".$fid."\">";
		 $mout .= "<input type=\"hidden\" name=\"cat\" value=\"".$fcat."\">";		 
	   
         $mout .= "<input type=\"hidden\" name=\"FormName\" value=\"cpvmixphoto\">"; 	
         $mout .= "<input type=\"hidden\" name=\"FormAction\" value=\"cpvmixphoto\">&nbsp;";						
         $mout .= "<input type=\"submit\" name=\"Submit\" value=\"".localize('_MYSUBMIT',getlocal())."\">";		
         $mout .= "</FONT></FORM>"; 	
      		 
	   //$out = $this->show_categories();
       if ($onlyforms) { //rccontrolpanel call...
	   
		   if ($fid) {//only if fid present..
			$wina = new window2(localize('_RCITEMPHOTO',getlocal()),$uout,null,1,null,'SHOW',null,1);
			$fout = $wina->render();
			unset ($wina);	
		   }
           
		   //use for bulk action when category is the only param
		   $wina = new window2(localize('_RCITEMREMOTEPHOTO',getlocal()),$rout,null,1,null,'SHOW',null,1);
		   $fout .= $wina->render();
		   unset ($wina);			   
		   
		   if ($fid) {//only if fid present..
			$wina = new window2(localize('_RCITEMMIXPHOTO',getlocal()),$mout,null,1,null,'SHOW',null,1);
			$fout .= $wina->render();
			unset ($wina);		   
		   }
           return ($fout);	   
	   }		
	   

	   if (($this->img_large) && (is_dir($this->urlpath.$this->img_large))) { //3 sized photos	

	     //echo GetReq('id'),'..',$this->img_small, $id , $this->restype;	   
		 //echo '<br/>SMALL..',$this->img_small, $id , $this->restype;
		 //small..	
         if ($this->has_photo2db($id,$this->restype,'SMALL')) {
		   $photo = is_numeric($interface) ? seturl('t=cpshowdbphoto&id='.$id.'&type=SMALL') : $interface . '?id='.$id.'&type=SMALL';	   
	       $photos = "<img src=\"" . $photo . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">";// . "</A>";	   
           $photos .= seturl("t=cpvdelphoto&sizetype=SMALL&photoid=" . $id . "&cat=" . $cat .  "&id=" . $id . '&editmode='.GetReq('editmode'),$this->delete_attach);		   
         }  		 
	     elseif (file_exists($this->urlpath.$this->img_small. $id . $this->restype)) { 	 
		   $photo = '..'.$this->img_small. $id . $this->restype;
		   if (is_dir($this->path.'/ieditor/')) {
		     //$callback = base64_encode('cpitems.php?t=cpvphoto&id='.GetReq('id').'&encoding='.GetReq('encoding').'&editmode=1');
			 $_SESSION['PIE_RELOAD_PARENT_BROWSER_ON_EXIT'] = '../cpitems.php?t=cpvphoto&id='.GetReq('id').'&encoding='.GetReq('encoding').'&editmode=1';
			 $imagesrc = $this->img_small. $id . $this->restype;
			 $_SESSION['PIE_RELOAD_PARENT_BROWSER_ON_SAVE'] = '../cpitems.php?t=cpvchphoto&phototype=SMALL&sourcephoto='.$imagesrc.'&id='.GetReq('id').'&encoding='.GetReq('encoding').'&editmode=1';
			 $photos .= "<a href='ieditor/index.php?callback=$callback&imagesrc=$imagesrc'>" . "<img src=\"" . $photo . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";	
		   }	 
           else			   
		     $photos .= "<img src=\"" . $photo . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">";// . "</A>";
		   $photos .= seturl("t=cpvdelphoto&sizetype=SMALL&photoid=" . $id . "&cat=" . $cat .  "&id=" . GetReq('id') . '&editmode='.GetReq('editmode'),$this->delete_attach);
		 }
		 
		 $photos .= '<br>'; 
	     //echo '<br/>MEDIUM..',$this->img_medium, $id , $this->restype;			 
		 //medium..	
         if ($this->has_photo2db($id,$this->restype,'MEDIUM')) {
		   $photo = is_numeric($interface) ? seturl('t=cpshowdbphoto&id='.$id.'&type=MEDIUM') : $interface . '?id='.$id.'&type=MEDIUM';
		   $photos .= "<img src=\"" . $photo . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">";// . "</A>";	
           $photos .= seturl("t=cpvdelphoto&sizetype=MEDIUM&photoid=" . $id . "&cat=" . $cat .  "&id=" . $id . '&editmode='.GetReq('editmode'),$this->delete_attach);		   
         }  		 
	     elseif (file_exists($this->urlpath.$this->img_medium. $id . $this->restype)) { 		 
		   $photo = '..'.$this->img_medium. $id . $this->restype;		   
		   if (is_dir($this->path.'/ieditor/')) {
		     //$callback = base64_encode('cpitems.php?t=cpvphoto&id='.GetReq('id').'&encoding='.GetReq('encoding').'&editmode=1');
			 $_SESSION['PIE_RELOAD_PARENT_BROWSER_ON_EXIT'] = '../cpitems.php?t=cpvphoto&id='.GetReq('id').'&encoding='.GetReq('encoding').'&editmode=1';
			 $imagesrc = $this->img_medium. $id . $this->restype;
			 $_SESSION['PIE_RELOAD_PARENT_BROWSER_ON_SAVE'] = '../cpitems.php?t=cpvchphoto&phototype=MEDIUM&sourcephoto='.$imagesrc.'&id='.GetReq('id').'&encoding='.GetReq('encoding').'&editmode=1';
			 $photos .= "<a href='ieditor/index.php?callback=$callback&imagesrc=$imagesrc'>" . "<img src=\"" . $photo . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";	
		   }	 
           else			   
		     $photos .= "<img src=\"" . $photo . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">";// . "</A>";
	       $photos .= seturl("t=cpvdelphoto&sizetype=MEDIUM&photoid=" . $id . "&cat=" . $cat .  "&id=" . GetReq('id') . '&editmode='.GetReq('editmode'),$this->delete_attach);
		 }	
		 
		 $photos .= '<br>'; 		  
	     //echo '<br/>LARGE:..',$this->img_large, $id , $this->restype;			 
		 //large..	
         if ($this->has_photo2db($id,$this->restype,'LARGE')) {
		   $photo = is_numeric($interface) ? seturl('t=cpshowdbphoto&id='.$id.'&type=LARGE') : $interface . '?id='.$id.'&type=LARGE';
		   $photos .= "<img src=\"" . $photo . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">";// . "</A>";	
           $photos .= seturl("t=cpvdelphoto&sizetype=LARGE&photoid=" . $id . "&cat=" . $cat .  "&id=" . $id . '&editmode='.GetReq('editmode'),$this->delete_attach);		   
         }  		 
	     elseif (file_exists($this->urlpath.$this->img_large. $id . $this->restype)) {   
		   $photo = '..'.$this->img_large. $id . $this->restype;
		   if (is_dir($this->path.'/ieditor/')) {
		     //$callback = base64_encode('cpitems.php?t=cpvphoto&id='.GetReq('id').'&encoding='.GetReq('encoding').'&editmode=1');
			 $_SESSION['PIE_RELOAD_PARENT_BROWSER_ON_EXIT'] = '../cpitems.php?t=cpvphoto&id='.GetReq('id').'&encoding='.GetReq('encoding').'&editmode=1';
			 $imagesrc = $this->img_large. $id . $this->restype;
             //$saveback = base64_encode('cpitems.php?t=cpvchphoto&phototype=LARGE&sourcephoto='.$imagesrc.'&id='.GetReq('id').'&encoding='.GetReq('encoding').'&editmode=1');			 
			 $_SESSION['PIE_RELOAD_PARENT_BROWSER_ON_SAVE'] = '../cpitems.php?t=cpvchphoto&phototype=LARGE&sourcephoto='.$imagesrc.'&id='.GetReq('id').'&encoding='.GetReq('encoding').'&editmode=1';
			 $photos .= "<a href='ieditor/index.php?callback=$callback&imagesrc=$imagesrc'>" . "<img src=\"" . $photo . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</a>";	
			 //$photos .= "<a href='ieditor/index.php?imagesrc=$imagesrc'>" . "<img src=\"" . $photo . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</a>";	
		   }	 
           else			   
		     $photos .= "<img src=\"" . $photo . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">";// . "</A>";
		   $photos .= seturl("t=cpvdelphoto&sizetype=LARGE&photoid=" . $id . "&cat=" . $cat .  "&id=" . GetReq('id') . '&editmode='.GetReq('editmode'),$this->delete_attach);
		 }	
		 
		 $photos .= '<br>'; 		 
		 
		 //if ($photos) {		
		 
		   //multiple photos
		   for($i='A';$i<='I';$i++) {
		     $photoid = $id;
		     $ad_photo = $this->img2path .  $photoid . $i . $this->restype;
		     $aditional_pic_file = $this->urlpath . '/images/uphotos/' . $photoid . $i . $this->restype;
		     //echo '<br/>'.$aditional_pic_file;
             if ($this->has_photo2db($photoid,$this->restype,$i)) {
		       $photo = is_numeric($interface) ? seturl('t=cpshowdbphoto&id='.$photoid.'&type='.$i) : $interface . '?id='.$photoid.'&type='.$i;
		       $photos .= "<img src=\"" . $photo . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">";// . "</A>";	
               $photos .= seturl("t=cpvdelphoto&sizetype=$i&photoid=" . $photoid . "&cat=" . $cat .  "&id=" . $id . '&editmode='.GetReq('editmode'),$this->delete_attach);		   
             }  		 
	         elseif (file_exists($aditional_pic_file)) { 
			   if (is_dir($this->path.'/ieditor/')) {
				 $callback = base64_encode('cpitems.php?t=cpvphoto&id='.GetReq('id').'&encoding='.GetReq('encoding').'&editmode=1');
			     $imagesrc = $this->adptp . $photoid . $i . $this->restype;//$ad_photo; //..path back error
			     $photos .= "<br><br><a href='ieditor/index.php?callback=$callback&imagesrc=$imagesrc'>" . "<img src=\"" . $ad_photo . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";	
		       }	 
               else			 
		         $photos .= "<br><br><img src=\"" . $ad_photo . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">";// . "</A>";
			   //$photos .= "<br>";
		       $photos .= seturl("t=cpvdelphoto&photoid=" . $photoid . $i . "&cat=" . $cat .  "&id=" . GetReq('id') . '&editmode='.GetReq('editmode'),$this->delete_attach);
		     }	 
		   }
		 		 				
	       $viewdata[] = $photos;
	       $viewattr[] = "left;50%"; 	
		
		   $wina = new window(localize('_RCITEMPHOTO',getlocal()),$uout);
		   $winout .= $wina->render("center::100%::0::group_dir_body::left::0::0::");
		   unset ($wina);	

		   $wina = new window(localize('_RCITEMREMOTEPHOTO',getlocal()),$rout);
		   $winout .= $wina->render("center::100%::0::group_dir_body::left::0::0::");
		   unset ($wina);			   
		   
		   $wina = new window(localize('_RCITEMMIXPHOTO',getlocal()),$mout);
		   $winout .= $wina->render("center::100%::0::group_dir_body::left::0::0::");
		   unset ($wina);		

		   //edit text shortcut
		   $edittexturl = $this->urlbase.'cp/cpmhtmleditor.php?encoding='.GetReq('encoding').'&id='.GetReq('id').'&type=.html&editmode=1'; 
		   $tout = '<iframe src ="'.$edittexturl.'" width="100%" height="550px"><p>Your browser does not support iframes</p></iframe>'; 
           $wina = new window(localize('_EDITTEXT',getlocal()),$tout);
		   $winout .= $wina->render("center::100%::0::group_dir_body::left::0::0::");
		   unset ($wina);			   
		  		   
		  
	       $viewdata[] = $winout;//"&nbsp;";
	       $viewattr[] = "left;50%";		  
		   	   		   	   		   	   		   
	       $myrec = new window('',$viewdata,$viewattr);
	       $out .= $myrec->render("center::100%::0::group_article_selected::left::5::5::");
	       unset ($viewdata);
	       unset ($viewattr);						 
		 /*}  
	     else {
	   
	       $myrec = new window('Error',$uout);
	       $out .= $myrec->render("center::100%::0::group_article_selected::left::5::5::");	   
	     }
	   
	     if (!GetReq('editmode'))
	       $out .= "<br>" . seturl("t=cpitems&cat=".$cat."&sel=".$sel,"Επιστροφή"); 	
		  */    
	   }
	   else { // 1 sized photo
	   
         if ($this->has_photo2db($photoid,$this->restype,null)) {
		   $photo = is_numeric($interface) ? seturl('t=cpshowdbphoto&id='.$id.'&type=') : $interface . '?id='.$id.'&type=';
		   $photos .= "<img src=\"" . $photo . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">";// . "</A>";	
           $photos .= seturl("t=cpvdelphoto&sizetype=&photoid=" . $photoid . "&cat=" . $cat .  "&id=" . $id . '&editmode='.GetReq('editmode'),$this->delete_attach);

		   //multiple photos
		   for($i='A';$i<='I';$i++) {
		     $photoid = $id;

             if ($this->has_photo2db($photoid,$this->restype,$i)) {
		       $adphoto = is_numeric($interface) ? seturl('t=cpshowdbphoto&id='.$photoid.'&type='.$i) : $interface . '?id='.$photoid.'&type='.$i;
		       $photos .= "<img src=\"" . $adphoto . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">";// . "</A>";	
               $photos .= seturl("t=cpvdelphoto&sizetype=$i&photoid=" . $photoid . "&cat=" . $cat .  "&id=" . $id . '&editmode='.GetReq('editmode'),$this->delete_attach);		   
             } 			  
		   }
		 
	       $viewdata[] = $photos;
	       $viewattr[] = "left;50%"; 	
		
		   $wina = new window(localize('_RCITEMPHOTO',getlocal()),$uout);
		   $winout .= $wina->render("center::100%::0::group_dir_body::left::0::0::");
		   unset ($wina);	

		   //edit text shortcut
		   $edittexturl = $this->urlbase.'cp/cpmhtmleditor.php?encoding='.GetReq('encoding').'&id='.GetReq('id').'&type=.html&editmode=1'; 
		   $tout = '<iframe src ="'.$edittexturl.'" width="100%" height="550px"><p>Your browser does not support iframes</p></iframe>'; 
           $wina = new window(localize('_EDITTEXT',getlocal()),$tout);
		   $winout .= $wina->render("center::100%::0::group_dir_body::left::0::0::");
		   unset ($wina);			   
		  
	       $viewdata[] = $winout;//"&nbsp;";
	       $viewattr[] = "left;50%";		  
		   	   		   	   		   	   		   
		   
	       $myrec = new window('',$viewdata,$viewattr);
	       $out .= $myrec->render("center::100%::0::group_article_selected::left::5::5::");
	       unset ($viewdata);
	       unset ($viewattr);			   
         }  		 
	     elseif (file_exists($ppath)) {
		 
		   $photos = "<img src=\"" . $photo . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">";// . "</A>";
		   $photos .= seturl("t=cpvdelphoto&photoid=" . $id . "&cat=" . $cat .  "&id=" . $id . '&editmode='.GetReq('editmode'),$this->delete_attach);
		 
		   //multiple photos
		   for($i='A';$i<='I';$i++) {
		     $photoid = $id;
		     $ad_photo = $this->img2path .  $photoid . $i . $this->restype;
		     $aditional_pic_file = $this->urlpath . '/images/uphotos/' . $photoid . $i . $this->restype;
		     //echo $aditional_pic_file;
		     if (file_exists($aditional_pic_file)) { 
			   if (is_dir($this->path.'/ieditor/')) {
				 $callback = base64_encode('cpitems.php?t=cpvphoto&id='.$id.'&encoding='.GetReq('encoding').'&editmode=1');
			     $imagesrc = $ad_photo;
			     $photos .= "<br><br><a href='ieditor/index.php?callback=$callback&imagesrc=$imagesrc'>" . "<img src=\"" . $photo . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";	
		       }	 
               else				 
		         $photos .= "<br><br><img src=\"" . $ad_photo . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">";// . "</A>";
			   //$photos .= "<br>";
		       $photos .= seturl("t=cpvdelphoto&photoid=" . $photoid . $i . "&cat=" . $cat .  "&id=" . GetReq('id') . '&editmode='.GetReq('editmode'),$this->delete_attach);
		     }	 
		   }
		 
	       $viewdata[] = $photos;
	       $viewattr[] = "left;50%"; 	
		
		   $wina = new window(localize('_RCITEMPHOTO',getlocal()),$uout);
		   $winout .= $wina->render("center::100%::0::group_dir_body::left::0::0::");
		   unset ($wina);	

           //edit text shortcut
		   $edittexturl = $this->urlbase.'cp/cpmhtmleditor.php?encoding='.GetReq('encoding').'&id='.GetReq('id').'&type=.html&editmode=1'; 
		   $tout = '<iframe src ="'.$edittexturl.'" width="100%" height="550px"><p>Your browser does not support iframes</p></iframe>'; 
           $wina = new window(localize('_EDITTEXT',getlocal()),$tout);
		   $winout .= $wina->render("center::100%::0::group_dir_body::left::0::0::");
		   unset ($wina);			   
		  
	       $viewdata[] = $winout;//"&nbsp;";
	       $viewattr[] = "left;50%";		  
		   	   		   	   		   	   		   
		   
	       $myrec = new window('',$viewdata,$viewattr);
	       $out .= $myrec->render("center::100%::0::group_article_selected::left::5::5::");
	       unset ($viewdata);
	       unset ($viewattr);			 
	     }
	     else {
	   
	       $myrec = new window('Error',$uout);
	       $out .= $myrec->render("center::100%::0::group_article_selected::left::5::5::");	   
	     }
	   
	     if (!GetReq('editmode'))
	       $out .= "<br>" . seturl("t=cpitems&cat=".$cat."&sel=".$sel,"Επιστροφή"); 
		 
	   }//else of 1 sized photo	   
	   
	   return ($out);
	} 
	
	function change_photo($source_photo=null) {
	
	     $id = GetParam('id')?GetParam('id'):GetReq('id'); //in case of cp only forms needs id in post//GetReq('id');			
         $id = $this->encode_image_id($id);//check inside func		 
		 $ptype = GetParam('phototype');
		 
		 if (($_FILES['uploadfile']) && (strstr($_FILES['uploadfile']['type'],'jpeg')))  {
		    $attachedfile = $_FILES['uploadfile'];
			$mysourcephoto = $attachedfile['tmp_name'];
		 }
		 else
		    $mysourcephoto = $source_photo ? $this->urlpath . $source_photo : $this->urlpath . GetReq('sourcephoto');
		 
	     //if (($_FILES['uploadfile']) && (strstr($_FILES['uploadfile']['type'],'jpeg')))  {
		 if ($mysourcephoto) {

          $attachedfile = $_FILES['uploadfile'];
		  //print_r($attachedfile);
		  
		  if (in_array($ptype,array(0=>'A',1=>'B',2=>'C',3=>'D',4=>'E',5=>'F',6=>'G',7=>'H',8=>'I'))) {

		    $myfilename = $id .$ptype. $this->restype;//'.jpg'; 
			//echo GetReq('id'),'<br/>',$myfilename,"<br>";	
			
            $myfilepath = $this->urlpath . $this->adptp . $myfilename;			
	        $thubpath = $this->urlpath .  $this->ptp;			  
	   	    $thubnail = $thubpath . $myfilename;//null;// NO THUB...yes...
			//echo '...',$ptype,'...';
			
			//resize large [0] autoresize
            if (!empty($this->autoresize)) {							 
              $image = new SimpleImage();
              $image->load($mysourcephoto);//$attachedfile['tmp_name']);
							   						   
			  if ($dim_large = $this->autoresize[2]) {
                $image->resizeToWidth($dim_large);
                $image->save($this->urlpath . $this->adptp . $myfilename);
				//auto add to db
				$this->add_photo2db($id,$this->restype,$ptype);	
              }
              return 1;							   
			}					
		  }	
		  else {//create thubnail
		  
		    $myfilename = $id . $this->restype;//'.jpg'; //echo $myfilename,"<br>";			  
		  
		    switch ($ptype) {
			  case 'SMALL' : $myfilepath = $this->urlpath . $this->img_small . $myfilename;	
	                         $thubpath = $this->urlpath .  $this->img_small;			  
	   	                     $thubnail = $thubpath . $myfilename;
							 
                             //resize medium, small and save at once
                             if (!empty($this->autoresize)) {							 
                               $image = new SimpleImage();
                               $image->load($mysourcephoto);//$attachedfile['tmp_name']);
							   						   
							   if ($dim_small = $this->autoresize[0]) {
                                 $image->resizeToWidth($dim_small);
                                 $image->save($this->urlpath . $this->img_small . $myfilename);
								 //auto add to db
								 $this->add_photo2db($id,$this->restype,'SMALL');	
                               }
                               return 1;							   
							 }							 
			                 break;
							 
			  case 'MEDIUM' :$myfilepath = $this->urlpath . $this->img_medium . $myfilename;	
	                         $thubpath = $this->urlpath .  $this->img_medium;			  
	   	                     $thubnail = $thubpath . $myfilename;
							 
                             //resize medium, small and save at once
                             if (!empty($this->autoresize)) {							 
                               $image = new SimpleImage();
                               $image->load($mysourcephoto);//$attachedfile['tmp_name']);
							   
							   if ($dim_medium = $this->autoresize[1]) {
                                 $image->resizeToWidth($dim_medium);
                                 $image->save($this->urlpath . $this->img_medium . $myfilename);
								 //auto add to db
								 $this->add_photo2db($id,$this->restype,'MEDIUM');	
							   }							   
							   if ($dim_small = $this->autoresize[0]) {
                                 $image->resizeToWidth($dim_small);
                                 $image->save($this->urlpath . $this->img_small . $myfilename);
								 //auto add to db
								 $this->add_photo2db($id,$this->restype,'SMALL');	
                               }
                               return 1;							   
							 }
			                 break;
							 
			  case 'LARGE' : $myfilepath = $this->urlpath . $this->img_large . $myfilename;	
	                         $thubpath = $this->urlpath .  $this->img_large;			  
	   	                     $thubnail = $thubpath . $myfilename;
							
                             //resize large, medium and small and save at once	
                             if (!empty($this->autoresize)) {							 
                               $image = new SimpleImage();
                               $image->load($mysourcephoto);//$attachedfile['tmp_name']);
							   
							   if ($dim_large = $this->autoresize[2]) {
                                 $image->resizeToWidth($dim_large);
                                 $image->save($this->urlpath . $this->img_large . $myfilename);
								 //auto add to db
								 $this->add_photo2db($id,$this->restype,'SMALL');	
							   }								   
							   if ($dim_medium = $this->autoresize[1]) {
                                 $image->resizeToWidth($dim_medium);
                                 $image->save($this->urlpath . $this->img_medium . $myfilename);
								 //auto add to db
								 $this->add_photo2db($id,$this->restype,'MEDIUM');	
							   }
							   if ($dim_small = $this->autoresize[0]) {
                                 $image->resizeToWidth($dim_small);
                                 $image->save($this->urlpath . $this->img_small . $myfilename);
								 //auto add to db
								 $this->add_photo2db($id,$this->restype,'LARGE');	
							   }
                               return 1; 							   
							 }
			                 break;
							 							 							 
			  default      : //DEFAULT 1 sized photo
                             $myfilepath = $this->urlpath . $this->ptp . $myfilename;	
	                         $thubpath = $this->urlpath .  $this->ptp;			  
	   	                     $thubnail = $thubpath . $myfilename;
							 
							 //resize large autoresize
                             if (!empty($this->autoresize)) {							 
                               $image = new SimpleImage();
                               $image->load($mysourcephoto);//$attachedfile['tmp_name']);
							   						   
							   if ($dim_large = $this->autoresize[2]) {
                                 $image->resizeToWidth($dim_large);
                                 $image->save($this->urlpath . $this->ptp . $myfilename);
								 //auto add to db
								 $this->add_photo2db($id,$this->restype,'');	
                               }
                               return 1;							   
							 }								 
			}
					
		  }
		  
		  //ELSE (IF NOT AUTO-RESIZE...)
		  
		  //echo $myfilepath;	
		  $watermarkpath = $this->path;		  
	   
	      //GD process ...add watermark + resize source photo
		  //echo 'GD',$watermarkpath.$this->image2add;
		  if (is_file($watermarkpath.$this->image2add)) {//echo 'ZZZZZ';
	        $process_img = new wateresize();
	        //$process_img->loadimg($attachedfile['tmp_name'],0,0,'jpg',1,$watermarkpath,$this->image2add);
			$process_img->loadimg($mysourcephoto,0,0,'jpg',1,$watermarkpath,$this->image2add);
		    //$process_img->set_jpg_quality($attachedfile['size']);
			$process_img->set_jpg_quality(filesize($mysourcephoto));
	        $ret = $process_img->saveimg($myfilepath,$thubnail);	
	        unset($process_img);		
		  }   
	      else
	        //$ret = move_uploaded_file($attachedfile['tmp_name'],$myfilepath); 
			$ret = move_uploaded_file($mysourcephoto,$myfilepath);
			
		  //echo '>',$myfilepath;	
		  return ($ret);
	     }
		 
		 return (false);		  	  
	} 
	
	function delete_photo() {
	  $db = GetGlobal('db');
	  $sizetype = GetReq('sizetype');  
	  $photoid = GetReq('photoid');	  
	  $id = GetReq('id');
		
      if ($this->has_photo2db($photoid,$this->restype,null)) {

        $sSQL = "delete from pphotos ";
	    $sSQL .= " WHERE code='" . $id . "'";
	    if (isset($sizetype))
	      $sSQL .= " and stype='" . $sizetype . "'";
        else //is null
		  $sSQL .= " and stype=''";
		
        //echo $sSQL; 
	    $resultset = $db->Execute($sSQL,1);	
	  }
	  else {//..continue to file (2-step delete)
		if ($sizetype) {//($id==$photoid) {//master pic
		
		  switch ($sizetype) {
		    case 'SMALL' : $w = $this->img_small; break;
			case 'MEDIUM': $w = $this->img_medium; break;
			case 'LARGE' : $w = $this->img_large; break;
		    default      : $w = $this->ptp;
		  }
		}  
		else
		  $w = $this->adptp;
	
        $pic_file = $this->urlpath . $w . $photoid . $this->restype;
		if (file_exists($pic_file)) {
		  //echo "Delete $pic_file";
		  unlink($pic_file);
		}
      }		
	} 	
	
	function form_insert2($width=null, $height=null, $rows=null, $editlink=null,$mode=null, $noctrl=false) {
	    $height = $height ? $height : 800;
        $rows = $rows ? $rows : 36;
        $width = $width ? $width : null; //wide	
		$mode = $mode ? $mode : 'd';
		$noctrl = $noctrl ? 0 : 1;			
	    //$edtext = localize('_EDITTEXT',getlocal());
		//$edphoto = localize('_EDITPHOTO',getlocal());
	
	    if (!defined('MYGRID_DPC')) 
		   return ($this->form_insert());
		   
	    $lan = getlocal()?getlocal():0;
		
        $cat_selected = GetReq('cat');	
	    if (stristr($cat_selected ,$this->cseparator))
	      $cats = explode($this->cseparator,$cat_selected);  
	    else
	      $cats[] = $cat_selected;		
		  
        $active_code = 	$this->getmapf('code');	
        $editlink = $editlink ?
		            $editlink :
		            seturl("t=cpvphoto&editmode=1&id={".$active_code."}");
					
		$name_active = $lan?'itmname':'itmfname'; 
		$itmdescr = $lan?'itmdescr':'itmfdescr'; 
        $myfields = 'id,' . $active_code . ",$name_active,itmactive,active,";//itmremark,sysins,uniname1,uniname2,uni1uni2,price0,price1,";
		
		if (defined('RCTRANSACTIONS_DPC')) {
		  $myfields .= 'price0,price1,';
		}
		
		/*		
        $fields = 'cat0,cat1,cat2,cat3,cat4';
		//selected cat depth
		$catfields = explode(',',$fields);
	  	//print_r($catfields);	
		foreach ($cats as $i=>$mycat) {
  		  if ($catlevel = $mycat) {
			$_fields[] = $catfields[$i];
		  }
		}
		//add one extra for change to subdir
		//echo $i,count($catfields);
		if ($i<count($catfields))
			$_fields[] = $catfields[$i+1];
		
        $myfields .= implode(',',$_fields);// . 
		             //',price2,pricepc,p1,p2,p3,p4,p5,resources,weight,volume';			
		*/
        $myfields .= 'cat0,cat1,cat2';//,cat3,cat4'; //all categories depth  		

		$xsSQL = 'select * from (select '.$myfields . ' from products) as o';
        //echo $xsSQL;
  	      /*GetGlobal('controller')->calldpc_method("mygrid.column use grid1+id|".localize('_ID',getlocal()));
           GetGlobal('controller')->calldpc_method("mygrid.column use grid1+sysins|".localize('_SUBDATE',getlocal()).'|date|1');
           GetGlobal('controller')->calldpc_method("mygrid.column use grid1+$itmname|".localize('_FNAME',getlocal()).'|30|1');		   	
		   GetGlobal('controller')->calldpc_method("mygrid.column use grid1+active|".localize('_ACTIVE',getlocal()).'|boolean|1');	
		   GetGlobal('controller')->calldpc_method("mygrid.column use grid1+cat1|".localize('_CAT1',getlocal()).'|20|1');*/	
		  
        $search = null;//1
		$hidden = null;//1
		$link_option = null;//"target='_blank'"
		  
	    $farr = explode(',',$myfields);
	    foreach ($farr as $t) {
		   if ($t=='id') {
			  $type = 6;
			  $edit = null;//no edit id	
			  $options = null;	
              $align = 'left';				  
		   }
		   elseif ($t==$active_code) {
		      if ((($mode=='e')||($mode=='r')) && ($editlink)) {//only edit/read mode
				$type = 'link';
				$edit = 10;	//no edit maste code		  
				$options = $editlink ;
			  }
			  else {
				$type = 10;
				$edit = 1;//edit at new = d
				$options = null;				  
			  }
              $align = 'left';			  
		   }
		   elseif (stristr($t,'active')) {
		      $type = 'boolean';
			  $edit = 1;
			  $options = ($t=='itmactive') ? "1:0" : "101:0";	
              $align = 'left';			  
		   }
		   elseif (stristr($t,'price')) {
		      $type = 10;
			  $edit = 1;
			  $options = null;	
              $align = 'right';			  
		   }		   
		   elseif (stristr($t,'cat')) {
		      $type = 10;//'select';//if jqgrid is not paid, not applicable
			  $edit = 1;
			  $options = null; 
			  $align = 'left';
			  /*switch ($t) {//depth cat1,2,3 depends on selected cat...
			    case 'cat4'  : $options = str_replace('_',' ',$cats[4]);break;////$options = $cats[3] ? $cats[3].":".$cats[3] :null; break;
				case 'cat3'  : $options = str_replace('_',' ',$cats[3]);break;////$options = $cats[2] ? $cats[2].":".$cats[2] :null; break;
			    case 'cat2'  : $options = str_replace('_',' ',$cats[2]);break;////$options = $cats[1] ? $cats[1].":".$cats[1] :null; break;
			    case 'cat1'  : //$options = $cat1.':Male;F:Female;D:'.str_replace('_',' ',$cats[1]);break;
				               //$options = $cats[0] ? $cats[0].":".$cats[0] :null; break;
				               $options = GetGlobal('controller')->calldpc_method("mygrid.mylookup use select distinct cat3 from categories");
				               break;
			    case 'cat0'  : $options = str_replace('_',' ',$cats[0]);break;////break;
                default      : $options = null;			  
			  }*/
		   }		   
		   else {
		      $type = ($t==$name_active) ? 21 :20;//textarea for title
			  $edit = 1;
			  $options = null; 
			  $align = 'left';
		   }
	       $title = localize('_'.$t,getlocal());

		   GetGlobal('controller')->calldpc_method("mygrid.column use grid1+$t|".$title."|$type|$edit|$options|$link_option|$search|$hidden|$align");
		} 
	   
		$out = GetGlobal('controller')->calldpc_method("mygrid.grid use grid1+products+$xsSQL+$mode++id+$noctrl+1+$rows+$height+$width");
		
		return ($out);
	}

    function form_insert()  { 
       $db = GetGlobal('db');	
	   $lan = getlocal()?getlocal():0;
       $cat_selected = GetReq('cat');		
       //$subcat_selected = GetReq('subcat');	

	   if (stristr($cat_selected ,$this->cseparator))
	     $cats = explode($this->cseparator,$cat_selected);  
	   else
	     $cats[] = $cat_selected;	 
       //print_r($cats);		 
	   
	  if ($editmode = GetReq('editmode')) {//default form colors	
		    global $config;
			$config['FORM']['element_bgcolor1'] = 'EEEEEE';
			$config['FORM']['element_bgcolor2'] = 'DDDDDD';		

            $active_code = 	$this->getmapf('code');

		    $name_active = $lan?'itmname,itmactive':'itmactive,itmfname'; 
			$itmdescr = $lan?'itmdescr':'itmfdescr'; 
            $myfields = $active_code . ",$name_active,itmremark,$itmdescr,sysins,uniname1,uniname2,uni1uni2,price0,price1,";
			
            $fields = 'cat0,cat1,cat2,cat3,cat4';
			$catfields = explode(',',$fields);
		  	//print_r($catfields);	
			foreach ($cats as $i=>$mycat) {
			  if ($catlevel = $mycat) {
				$_fields[] = $catfields[$i];
				SetParam($catfields[$i],$catlevel); //set predef cat level
			  }
			}			
            //print_r($_fields);
			$myfields .= implode(',',$_fields) . ',active,price2,pricepc,p1,p2,p3,p4,p5,resources,weight,volume';	
            //echo $myfields;			
			
			SetParam('active',101);
			SetParam('itmactive',1);
			SetParam('sysins',date('Y-m-d h:m:s'));
			
			if ($this->autoinc) {
			
			  $sSQL = "select count('id') from products";
	          $resultset = $db->Execute($sSQL,2);	
	          //print_r($resultset->fields);
	          $maxid = $resultset->fields[0]; 

              SetParam($active_code,$maxid+1);			  
			}			
	  }	
      else
            $myfields = "code1,code2,code3,code4,code5,itmname,itmactive,itmfname,itmremark,itmdescr,itmfdescr,sysins,sysupd,uniida,uniname1,uniname2" .
                        ",uni1uni2,uni2uni1,ypoloipo1,ypoloipo2,price0,price1,cat0,cat1,cat2,cat3,cat4,active,price2,pricepc,p1,p2,p3,p4,p5,resources,weight,volume";	  
	
	
		if (defined('MYGRID_DPC')) {
		
		   $xsSQL = 'select * from (select id,'.$myfields . ' from products) as o';


		   /*GetGlobal('controller')->calldpc_method("mygrid.column use grid1+id|".localize('_ID',getlocal()));
           GetGlobal('controller')->calldpc_method("mygrid.column use grid1+sysins|".localize('_SUBDATE',getlocal()).'|date|1');
           GetGlobal('controller')->calldpc_method("mygrid.column use grid1+$itmname|".localize('_FNAME',getlocal()).'|30|1');		   	
		   GetGlobal('controller')->calldpc_method("mygrid.column use grid1+active|".localize('_ACTIVE',getlocal()).'|boolean|1');	
		   GetGlobal('controller')->calldpc_method("mygrid.column use grid1+cat1|".localize('_CAT1',getlocal()).'|20|1');*/	
		   $out = GetGlobal('controller')->calldpc_method("mygrid.grid use grid1+products+$xsSQL+d++id+1+1+20+400");
		   //return ($out);
		}	
	
	   //$out = $this->show_categories();	
	   
       //<phpdac> dataforms.setform use myform+myform+5+5+20+100+0+0 </phpdac>
	   GetGlobal('controller')->calldpc_method('dataforms.setform use myform+myform+5+5+50+100+0+0');
       //<phpdac> dataforms.setformadv use 0+0+30+20 </phpdac>
	   GetGlobal('controller')->calldpc_method('dataforms.setformadv use 0+0+50+10+id');	  
       //<phpdac> dataforms.setformgoto use _LIST </phpdac>
	   
       //GetGlobal('controller')->calldpc_method('dataforms.set_id use id');
	   
	   if (!GetParam('editmode')) //there is no id to go...
	     GetGlobal('controller')->calldpc_method("dataforms.setformgoto use DPCLINK:cpvphoto&editmode=$editmode:".localize('_OK',getlocal()));	  
		 
       //<phpdac> dataforms.getform use update.rccustomers+dataformsinsert,dataformsupdate,unsubscribe+Post+Clear++A,*B++id=39+dummy </phpdac>
	   
	   GetGlobal('controller')->calldpc_method('dataforms.setformtemplate use cpitemsadd');		   
	   
       /*$fields = "code1,code2,code3,code4,code5,itmname,itmactive,itmfname,itmremark,itmdescr,itmfdescr,sysins,sysupd,uniida,uniname1,uniname2" .
                 ",uni1uni2,uni2uni1,ypoloipo1,ypoloipo2,price0,price1,cat0,cat1,cat2,cat3,cat4,active,price2,pricepc,p1,p2,p3,p4,p5,resources,weight,volume";
	   */			 
	   $farr = explode(',',$myfields);
	   foreach ($farr as $t)
	     $title[] = localize('_'.$t,getlocal());
	   $titles = implode(',',$title);
				 
	   /*if ($cat_selected)
	     $subcat = get_selected_option_fromfile($cat_selected,'kategories',0) . '_opt';			 
	   else
	     $subcat = 'typos_opt';					 
       */
       $post = 	localize('_POST',getlocal());
	   $clear = localize('_CLEAR',getlocal());
	   $out .= GetGlobal('controller')->calldpc_method("dataforms.getform use insert.products+dataformsinsert&editmode=$editmode+$post+$clear+$myfields+$titles++dummy+dummy");	  
	   
       return ($out);
   }
   
    function form_modify()  {
      $db = GetGlobal('db');	
	  $lan = getlocal()?getlocal():0;
      $cat_selected = GetReq('cat');		

	  if (stristr($cat_selected ,$this->cseparator))
	    $cats = explode($this->cseparator,$cat_selected);  
	  else
	    $cats[] = $cat_selected;	  
	  
	  if ($editmode = GetReq('editmode')) {//default form colors	
		 global $config;
		 $config['FORM']['element_bgcolor1'] = 'EEEEEE';
	  	 $config['FORM']['element_bgcolor2'] = 'DDDDDD';
		 
	     $id = GetParam('id');
	     $mycat = GetReq('cat');		 
         $active_code = $this->getmapf('code');

		 $name_active = $lan?'itmname,itmactive':'itmactive,itmfname'; 
		 $itmdescr = $lan?'itmdescr':'itmfdescr'; 
         $myfields = $active_code . ",$name_active,itmremark,$itmdescr,sysupd,uniname1,uniname2,uni1uni2,price0,price1,";		 
		 $myfields .= 'cat0,cat1,cat2,cat3,cat4,active,price2,pricepc,p1,p2,p3,p4,p5,resources,weight,volume';
		 //echo $myfields;	
		 
		 SetParam('sysupd',date('Y-m-d h:m:s'));		 
		 	  
         $sSQL = "select id from products ";
	     $sSQL .= " WHERE $active_code='" . $id . "'";	
	     //echo $sSQL;
	  
	     $resultset = $db->Execute($sSQL,2);	
		 //print_r($resultset->fields);
		 $id = $resultset->fields['id']	;  
		 
	     GetGlobal('controller')->calldpc_method('dataforms.setform use myform+myform+5+5+50+100+0+0');
	     GetGlobal('controller')->calldpc_method('dataforms.setformadv use 0+0+50+10');	  
	     GetGlobal('controller')->calldpc_method("dataforms.setformgoto use DPCLINK:cpvphoto&editmode=$editmode&id=$id:".localize('_OK',getlocal()));//cpvmodify&id=$id;			  
	   } 	
	   else {
	     $id = GetParam('rec');
	     $mycat = GetReq('cat');
	     //$out = $this->show_categories();   
	   
	     //GET the subtype of record....
	   
         //<phpdac> dataforms.setform use myform+myform+5+5+20+100+0+0 </phpdac>
	     GetGlobal('controller')->calldpc_method('dataforms.setform use myform+myform+5+5+50+100+0+0');
         //<phpdac> dataforms.setformadv use 0+0+30+20 </phpdac>
	     GetGlobal('controller')->calldpc_method('dataforms.setformadv use 0+0+50+10');	  
         //<phpdac> dataforms.setformgoto use _LIST </phpdac>
	     GetGlobal('controller')->calldpc_method('dataforms.setformgoto use DPCLINK:cpitems:'.localize('_OK',getlocal()));	

         $myfields = "code1,code2,code3,code4,code5,itmname,itmactive,itmfname,itmremark,itmdescr,itmfdescr,sysins,sysupd,uniida,uniname1,uniname2" .
                     ",uni1uni2,uni2uni1,ypoloipo1,ypoloipo2,price0,price1,cat0,cat1,cat2,cat3,cat4,active,price2,pricepc,p1,p2,p3,p4,p5,resources";		 
	   }
       //<phpdac> dataforms.getform use update.rccustomers+dataformsinsert,dataformsupdate,unsubscribe+Post+Clear++A,*B++id=39+dummy </phpdac>
	   GetGlobal('controller')->calldpc_method('dataforms.setformtemplate use cpitemsmod');	   
	   
       /*$fields = "code1,code2,code3,code4,code5,itmname,itmactive,itmfname,itmremark,itmdescr,itmfdescr,sysins,sysupd,uniida,uniname1,uniname2" .
                 ",uni1uni2,uni2uni1,ypoloipo1,ypoloipo2,price0,price1,cat0,cat1,cat2,cat3,cat4,active,price2,pricepc,p1,p2,p3,p4,p5,resources";
		*/		 
	   $farr = explode(',',$myfields);
	   foreach ($farr as $t)
	     $title[] = localize('_'.$t,getlocal());
	   $titles = implode(',',$title);	 
		 
	
	   /*if ($mycat)
	     $subcat = get_selected_option_fromfile($mycat,'kategories',0) . '_opt';			 
	   else
	     $subcat = 'typos_opt';		 */
		//echo $subcat;
				 	                             
       $post = 	localize('_POST',getlocal());
	   $clear = localize('_CLEAR',getlocal());												 //kybismos_opt
	   $out .= GetGlobal('controller')->calldpc_method("dataforms.getform use update.products+dataformsupdate&editmode=$editmode+$post+$clear+$myfields+$titles++id=$id+dummy");	  
	   
       return ($out);
   }   

	function delete_from_list() {
        $db = GetGlobal('db');	
	
	    $id = GetReq('rec');
		
		$sSQL = "select id from products where id=".$id;
	    $resultset = $db->Execute($sSQL,2);
	    $ret = $db->fetch_array_all($resultset);	
		$result = $ret[0];
		//print_r($result);
		
		if ($result[0]==-$id) {//negative id ...delete permament
		
          $sSQL = "DELETE FROM products WHERE id=" . $id;	
		  $this->msg = "Delete completed!";	
		}
		else {//pre-delete
		    $sSQL = "update products set active=0 WHERE id=" . $id;
		  	$this->msg = "Prepared to delete!";
			
			$pret = $this->make_sold_photo($id);
			if ($pret==false)
			  $this->msg .= "[MODIFY PHOTO ERROR!]";				
		}
		$ret = $db->Execute($sSQL,1);	
	    //echo $sSQL;		
	} 
	
	//based on dir photos
	function directory_sync_photo() {

	    $codes = array();
        //if (!is_dir($this->syncurl))
		  //return null; 
		  
		//echo $this->syncurl,'>';  

        $mydir = opendir($this->syncurl);	
		
        //while ($fileread = $mydir->read ()) { 
		while (($fileread = readdir($mydir)) !== false) {
		    echo $fileread,'<br>';
		    $fileparts = explode('.',$fileread); 
		    
		    if (($ext=array_pop($fileparts)) && ($ext==$this->restype)) {
				echo $fileread,'<br>';
			    $codes[] = $fileparts[0];
				
			}	
		}	
		closedir($mydir);

		if (!empty($codes)) {
		
		    foreach ($codes as $i=>$item) {
			   echo $item,'<br>';
			}
        }		
	}		
	
	function item_sync_photo($id=null,$cat=null,$notexisted=null) {
      $db = GetGlobal('db');	
	  $i=0;
	  
	  if ($id) { //one item ..return to photo page
		  $ret = $this->make_sync_photo($id, $this->syncurl . $id . $this->restype);
		  //...goto action
		  return ($ret);
		  
	  }
	  elseif ($cat) {//cat items 
	  
	    $cat_tree = explode($this->cseparator,str_replace('_',' ',$cat));
	  
	    $code = $this->getmapf('code'); 
        $sSQL = "select id,".$code." from products WHERE ";
		
		foreach ($cat_tree as $c=>$cc)
	      $whereClause[] = "cat$c=" . $db->qstr(rawurldecode(str_replace('_',' ',$cc)));	
		  
		$sSQL .= implode(' and ', $whereClause);
	    $sSQL .= " and itmactive>0 and active>0";	
		
		if ($notexisted)
		  $sSQL .= " and ". $code . " NOT IN (select code from pphotos)";
		  
	    //echo $sSQL;	    
	  }
	  else {//all items
	  
	    $code = $this->getmapf('code'); 
        $sSQL = "select id,".$code." from products WHERE ";
	    $sSQL .= "itmactive>0 and active>0";	
		
		if ($notexisted)
		  $sSQL .= " and ". $code . " NOT IN (select code from pphotos)";		
	    //echo $sSQL;	  
	  }
	  
	  $resultset = $db->Execute($sSQL,2);	
	  $result = $resultset;
	  $max_items = $db->Affected_Rows(); 
	  $this->msg = 'Total items:'. $max_items . '<br>';	  
		
	  if (!empty($result)) {	

          set_time_limit(0);
	  
	      foreach ($result as $n=>$rec) {
		    //echo $rec[$code];
		    $s = $this->make_sync_photo($rec[$code], $this->syncurl . $rec[$code] . $this->restype, null);
			$this->msg .= $this->syncurl . $rec[$code] . $this->restype;
			if ($s) {
			  $i+=1;
			  $this->msg .= ' <b>success!</b><br>';
			}
            else 
			  $this->msg .= ' failed!<br>';	
		  }
		  
		  set_time_limit(30);
	  } 
	  $this->msg .= '<h2>' . $i . ' images updated.</h2>';		
	    
	  if (GetReq('editmode'))
	      die($this->msg);		  
	}
	
	function make_sync_photo($id=null,$url=null,$verbose=null) {
		$ptype = GetParam('phototype');
		if (!$ptype) {//when no post get param from config
		  $ptype = $this->img_large?'LARGE':null;//default thub 
		}
		$rfile = GetParam('remotefile')?GetParam('remotefile'):$url; //when no post get param from func
		if (!$rfile) return false;
		
		$myfile = explode('//',$rfile);
		//print_r($myfile);
		
		if ($myfile[0]=='local:')  //local path
			$remotefilename = $this->urlpath . $myfile[1];
		else //url...http://
			$remotefilename = $rfile; //as is
			
		//if has no id .jpg ..it is category sync  
		if (!stristr($myfile[1],$this->restype)) 
            $remotefilename .= $id . $this->restype;		  
		  
		//echo '>',$remotefilename;// . $id . $this->restype;
		$myremote_filename = $this->pick_remote_file($remotefilename);
		if ($verbose)
		  echo '>',$myremote_filename,'<br>';
		
		if ($myremote_filename) {
		  
		  if (in_array($ptype,array(0=>'A',1=>'B',2=>'C',3=>'D',4=>'E',5=>'F',6=>'G',7=>'H',8=>'I'))) {
		  
		    $myfilename = $id .$ptype. $this->restype;	
            $myfilepath = $this->urlpath . $this->adptp . $myfilename;			
	        $thubpath = $this->urlpath .  $this->ptp;			  
	   	    $thubnail = $thubpath . $myfilename;//null;// NO THUB...yes...
			
			//resize large [0] autoresize
            if (!empty($this->autoresize)) {							 
              $image = new SimpleImage();
              $image->load($myremote_filename);
							   						   
			  if ($dim_large = $this->autoresize[2]) {
                $image->resizeToWidth($dim_large);
                $image->save($this->urlpath . $this->adptp . $myfilename);
				//auto add to db
				$this->add_photo2db($id,$this->restype,$ptype);
              }
              return 1;							   
			}					
		  }	
		  else {//create thubnail	

		    $myfilename = $id . $this->restype;//'.jpg'; //echo $myfilename,"<br>";			  
		  
		    switch ($ptype) {
			  case 'SMALL' : $myfilepath = $this->urlpath . $this->img_small . $myfilename;	
	                         $thubpath = $this->urlpath .  $this->img_small;			  
	   	                     $thubnail = $thubpath . $myfilename;
							 
                             //resize medium, small and save at once
                             if (!empty($this->autoresize)) {							 
                               $image = new SimpleImage();
                               $image->load($myremote_filename);
							   						   
							   if ($dim_small = $this->autoresize[0]) {
                                 $image->resizeToWidth($dim_small);
                                 $image->save($this->urlpath . $this->img_small . $myfilename);
								 //auto add to db
								 $this->add_photo2db($id,$this->restype,'SMALL');
                               }
                               return 1;							   
							 }							 
			                 break;
							 
			  case 'MEDIUM' :$myfilepath = $this->urlpath . $this->img_medium . $myfilename;	
	                         $thubpath = $this->urlpath .  $this->img_medium;			  
	   	                     $thubnail = $thubpath . $myfilename;
							 
                             //resize medium, small and save at once
                             if (!empty($this->autoresize)) {							 
                               $image = new SimpleImage();
                               $image->load($myremote_filename);
							   
							   if ($dim_medium = $this->autoresize[1]) {
                                 $image->resizeToWidth($dim_medium);
                                 $image->save($this->urlpath . $this->img_medium . $myfilename);
								 //auto add to db
								 $this->add_photo2db($id,$this->restype,'MEDIUM');
							   }							   
							   if ($dim_small = $this->autoresize[0]) {
                                 $image->resizeToWidth($dim_small);
                                 $image->save($this->urlpath . $this->img_small . $myfilename);
								 //auto add to db
								 $this->add_photo2db($id,$this->restype,'SMALL');
                               }
                               return 1;							   
							 }
			                 break;
							 
			  case 'LARGE' : $myfilepath = $this->urlpath . $this->img_large . $myfilename;	
	                         $thubpath = $this->urlpath .  $this->img_large;			  
	   	                     $thubnail = $thubpath . $myfilename;
							
                             //resize large, medium and small and save at once	
                             if (!empty($this->autoresize)) {							 
                               $image = new SimpleImage();
                               $image->load($myremote_filename);
							   
							   if ($dim_large = $this->autoresize[2]) {
                                 $image->resizeToWidth($dim_large);
                                 $image->save($this->urlpath . $this->img_large . $myfilename);
								 //auto add to db
								 $this->add_photo2db($id,$this->restype,'SMALL');
							   }								   
							   if ($dim_medium = $this->autoresize[1]) {
                                 $image->resizeToWidth($dim_medium);
                                 $image->save($this->urlpath . $this->img_medium . $myfilename);
								 //auto add to db
								 $this->add_photo2db($id,$this->restype,'MEDIUM');								 
							   }
							   if ($dim_small = $this->autoresize[0]) {
                                 $image->resizeToWidth($dim_small);
                                 $image->save($this->urlpath . $this->img_small . $myfilename);
								 //auto add to db
								 $this->add_photo2db($id,$this->restype,'LARGE');								 
							   }
                               return 1; 							   
							 }
			                 break;
							 							 							 
			  default      : //DEFAULT 1 sized photo
                             $myfilepath = $this->urlpath . $this->ptp . $myfilename;	
	                         $thubpath = $this->urlpath .  $this->ptp;			  
	   	                     $thubnail = $thubpath . $myfilename;
							 
							 //resize large autoresize
                             if (!empty($this->autoresize)) {							 
                               $image = new SimpleImage();
                               $image->load($myremote_filename);
							   						   
							   if ($dim_large = $this->autoresize[2]) {
                                 $image->resizeToWidth($dim_large);
                                 $image->save($this->urlpath . $this->ptp . $myfilename);
								 //auto add to db
								 $this->add_photo2db($id,$this->restype,'');
                               }
                               return 1;							   
							 }								 
			}//case		  
		  }
		}  
		  
		return false;		  		  
	}
	
	
	function pick_remote_file($mysource=null,$saveto=null) {

	     if ($mysource) {
		    //echo $mysource;
		    if (!stristr($mysource,'http://')) {//local file
			  if (is_readable($mysource))
			    return $mysource;
			  else
                return false;			  
			}
            else {//http copy
			  $temptarget = $this->urlpath .'cp/uploads/temp'.$this->restype;
			  //echo $temptarget;
			  $ret = @copy($mysource,$temptarget);
			  if ($ret)
			    return ($temptarget); //local path, temp file
			  else
                return false;			  
 			}
		 }
		  
		 return false;	
	}
	
	function photo2db($notexisted=null) {
      $db = GetGlobal('db');	
	  $i=0;

	  $code = $this->getmapf('code'); 
      $sSQL = "select id,".$code." from products where ";
	  
	  if ($id = GetReq('id')) 
	    $sSQL .= $code . "='" . $id . "' and ";	
	  elseif ($cat = GetReq('cat')) {
	    $cat_tree = explode($this->cseparator,str_replace('_',' ',$cat));
		
		foreach ($cat_tree as $c=>$cc)
	      $whereClause[] = "cat$c=" . $db->qstr(rawurldecode(str_replace('_',' ',$cc)));	
		  
		$sSQL .= implode(' and ', $whereClause) . ' and ';	  	
	  }	
	  $sSQL .= " itmactive>0 and active>0";	
	  
	  if ($notexisted)
		$sSQL .= " and ". $code . " NOT IN (select code from pphotos)";		  
	  //echo $sSQL;
	  
	  $resultset = $db->Execute($sSQL,2);	
	  $result = $resultset;
	  //print_r($result);	
	  $max_items = $db->Affected_Rows();
	  //echo $max_items;  
	  $this->msg = 'Total items:'. $max_items . '<br>';
	  
	  if (!empty($result)) {		  
	    //echo $i,'>';
	    foreach ($result as $n=>$rec) {
		  //echo $this->attachpath,$rec[$code],'<br>';
		  if ($this->img_large) {//large,medium,small photos	
			
		    $i+= $this->add_photo2db($rec[$code],$this->restype,'SMALL');

		    $i+= $this->add_photo2db($rec[$code],$this->restype,'MEDIUM');

		    $i+= $this->add_photo2db($rec[$code],$this->restype,'LARGE');
          }
          else //one photo
		    $i+= $this->add_photo2db($rec[$code],$this->restype);
		}
	  } 
	  $this->msg .=  $i . ' photos added to dababase.';
	  
	  if (GetReq('editmode')) {
	    die($this->msg);
	  }
	}	
	
	function add_photo2db($itmcode,$type=null,$size=null) {
	  $itmcode = $itmcode?$itmcode:GetReq('id');
      $db = GetGlobal('db');	
	  $type = $type?$type:$this->restype;	  
      $myfilename = $itmcode . $this->restype;	
	  
	  if (!$this->photodb) return;
	  
	  switch ($size) {
	    case "LARGE" : $photo = $this->urlpath . $this->img_large . $myfilename; break;
	    case "MEDIUM": $photo = $this->urlpath . $this->img_medium . $myfilename; break;
        case "SMALL" : $photo = $this->urlpath . $this->img_small . $myfilename; break;
        default      : $photo = $this->urlpath . $this->ptp . $myfilename;		
	  }  

	  if (is_readable($photo)) {
	  	    
		$sSQL = "select code from pphotos ";
		$sSQL .= " WHERE code='" . $itmcode . "' and type='". $type . "' and stype='". $size ."'";
		//echo $sSQL;
	  
		$resultset = $db->Execute($sSQL,2);	
		$result = $resultset;
		$exist = $db->Affected_Rows();
	  
		$data = base64_encode(file_get_contents($photo));
	  
	    //65535 chars limit...
		//else keep the file version in images dir...
		if (strlen($data)<65535) {//cuted pic when max that 65535 (text field max width)
	  
			if ($exist) {
				$sSQL = "update pphotos set data='". $data ."'";
				$sSQL .= " WHERE code='" . $itmcode . "' and type='" . $type ."'";
				if (isset($size))
					$sSQL .= " and stype='" . $size . "'";		  		  
			}
			else 
				$sSQL = "insert into pphotos (data,type,code,stype) values ('". $data ."','" . $type ."','" . $itmcode ."','".$size."')";  	  
	
			//echo $sSQL;	
	  
			$db->Execute($sSQL,1);	
			$affected = $db->Affected_Rows();
	  
			if (($affected) && ($this->erase2db))
				unlink($photo); //<<<<<<<<<< !!!! DELETE	
			
		}//65535 limit
		else
		   echo '65535 limit!<br>';
	  }//is readable	
	  return ($affected);  	  
	}	
	
	function has_photo2db($itmcode=null,$type=null,$stype=null) {
      $db = GetGlobal('db');	
  
      $sSQL = "select code from pphotos ";
	  $sSQL .= " WHERE code='" . $itmcode . "'";
	  if (isset($type))
	    $sSQL .= " and type='". $type ."'";
	  if (isset($stype))
	    $sSQL .= " and stype='". $stype ."'";		

	  //echo $sSQL;
	  $resultset = $db->Execute($sSQL,2);	
	  $result = $resultset;
	  //print_r($result);	
	  
	  $exist = $db->Affected_Rows();
    
	  if ($result->fields[0])//($exits) 
	    return true;
	  
	  return false;
	}	
	
	function show_photodb($itmcode=null, $stype=null, $type=null) {
      $db = GetGlobal('db');
	  if (!$itmcode) return;
	  $type = $type?$type:$this->restype;
	  	  
      $sSQL = "select data,type,code from pphotos ";
	  $sSQL .= " WHERE code='" . $itmcode . "'";
	  if (isset($type))
	    $sSQL .= " and type='". $type ."'";
	  if (isset($stype))
	    $sSQL .= " and stype='". $stype ."'";		

	  
	  $resultset = $db->Execute($sSQL,2);	
	  $result = $resultset;	  
	  
	  $mime_type = 'image/'.str_replace('.','',$result->fields['type']);
	  //$mime_type = 'image/jpeg';
	  echo $mime_type;
	  //header('Content-type: ' . $mime_type);

	  if ($result->fields['code']) //photo exists
        echo base64_decode($result->fields['data']);
	  else {//additional photo or standart nopic
	    echo null;
      }  
	  
	  die();
	}
	
   function make_sold_photo($id,$restore=null) {
   
	      $thubpath = $this->urlpath . /*'/images/thub/'*/ $this->ptp;	
	      $spath = $this->urlpath . /*'/images/uphotos/'*/ $this->adptp;
		  
		  $myfilepath = $spath . "/" . sprintf("%05s",$id). $this->restype;				  
		  $myfilepath_backup = $spath . "/_" . sprintf("%05s",$id). $this->restype;		  
	      $thubnail = $thubpath . sprintf("%05s",$id). $this->restype;			
	      $mytarget = $spath . sprintf("%05s",$id). $this->restype;			
	   
	   
	      if (file_exists($myfilepath)) {//copy source 
		  
		    if ($restore) {
			  if (is_file($myfilepath_backup))
			    @copy($myfilepath_backup,$myfilepath);
			}   
            else { 
			  //do backup
			  @copy($myfilepath,$myfilepath_backup);
		 
		      if (is_file($this->path.$this->image2sold)) {		 
	            $process_img = new wateresize();
	            $process_img->loadimg($myfilepath,'CENTER','MIDDLE','jpg',1,$this->path,$this->image2sold);
	            $ret = $process_img->saveimg($mytarget,$thubnail);	
	            unset($process_img);		   
			  }	
	     
		      return ($ret);
            }
	      }	
		   
		  return (false); 
    }	

	//make sold photo alternative 
	function photo2mix() {	 	
	  $id = GetReq('id');	
	  $ptype = GetParam('phototype');	  
	  $restype = str_replace('.','',$this->restype);
	  $photomix = $_POST['mixphoto']?$_POST['mixphoto']:$this->photomix;
	  $qlt = $_POST['photoquality']?$_POST['photoquality']:null;
	
	  $this->mixx = $_POST['mixx']!='Default'?$_POST['mixx']:$this->mixx;
	  $this->mixy = $_POST['mixy']!='Default'?$_POST['mixy']:$this->mixy;	  
	  //echo $this->mixx,',',$this->mixy;
	
	  if ($photomix) {

          if (in_array($ptype,array(0=>'A',1=>'B',2=>'C',3=>'D',4=>'E',5=>'F',6=>'G',7=>'H',8=>'I'))) {
		    $myfilename = $id .$ptype. $this->restype;//'.jpg'; 
			//echo $myfilename,"<br>";	
			
            $myfilepath = $this->urlpath . $this->adptp . $myfilename;			
	        $thubpath = $this->urlpath .  $this->adptp;			  
	   	    $thubnail = $thubpath . $myfilename;//null;// NO THUB...yes...

		    if ((is_file($this->urlpath . $this->adptp . $photomix)) && (is_file($myfilepath))) {	
	           $process_img = new wateresize();
			   if ($qlt)
                 $process_img->quality = $qlt;			   
	           $process_img->loadimg($myfilepath,$this->mixx,$this->mixy,$restype,null,$thubpath,$photomix);
	           $ret = $process_img->saveimg($myfilepath);	
	           unset($process_img);	
			   echo "Image created. ";//,$thubpath,$photomix;
               return 1;							   
			}
            else 
			   echo "Image not found. ";//,$thubpath,$photomix;			
		  }	
		  else {//create thubnail
		  
		    $myfilename = $id . $this->restype;//'.jpg'; //echo $myfilename,"<br>";			  
		  
		    switch ($ptype) {
			  case 'SMALL' : $myfilepath = $this->urlpath . $this->img_small . $myfilename;	
	                         $thubpath = $this->urlpath .  $this->img_small;			  
	   	                     $thubnail = $thubpath . $myfilename;
							 
							 if ((is_file($this->urlpath . $this->img_small . $photomix)) && (is_file($myfilepath))) {	
	                           $process_img = new wateresize();
			                   if ($qlt)
                                 $process_img->quality = $qlt;								   
	                           $process_img->loadimg($myfilepath,$this->mixx,$this->mixy,$restype,null,$thubpath,$photomix);
	                           $ret = $process_img->saveimg($myfilepath);	
	                           unset($process_img);	
							   echo "Image created. ";//,$thubpath,$photomix;
                               return 1;							   
							 }	
                             else 
							   echo "Image not found. ";//,$thubpath,$photomix;							 
			                 break;
							 
			  case 'MEDIUM' :$myfilepath = $this->urlpath . $this->img_medium . $myfilename;	
	                         $thubpath = $this->urlpath .  $this->img_medium;			  
	   	                     $thubnail = $thubpath . $myfilename;
							 
							 if ((is_file($this->urlpath . $this->img_medium . $photomix)) && (is_file($myfilepath))) {	
	                           $process_img = new wateresize();
			                   if ($qlt)
                                 $process_img->quality = $qlt;								   
	                           $process_img->loadimg($myfilepath,$this->mixx,$this->mixy,$restype,null,$thubpath,$photomix);
	                           $ret = $process_img->saveimg($myfilepath);	
	                           unset($process_img);	
							   echo "Image created. ";//,$thubpath,$photomix;
                               return 1;							   
							 }
							 else 
							   echo "Image not found. ";//,$thubpath,$photomix;
			                 break;
							 
			  case 'LARGE' : $myfilepath = $this->urlpath . $this->img_large . $myfilename;	
	                         $thubpath = $this->urlpath .  $this->img_large;			  
	   	                     $thubnail = $thubpath . $myfilename;
							 
							 if ((is_file($this->urlpath . $this->img_large . $photomix)) && (is_file($myfilepath))) {	
	                           $process_img = new wateresize();
			                   if ($qlt)
                                 $process_img->quality = $qlt;								   
	                           $process_img->loadimg($myfilepath,$this->mixx,$this->mixy,$restype,null,$thubpath,$photomix);
	                           $ret = $process_img->saveimg($myfilepath);	
	                           unset($process_img);	
							   echo "Image created. ";//,$thubpath,$photomix;
                               return 1;							   
							 }
							 else 
							   echo "Image not found. ";//,$thubpath,$photomix;
							 
			                 break;
							 							 							 
			  default      : //DEFAULT 1 sized photo
                             $myfilepath = $this->urlpath . $this->ptp . $myfilename;	
	                         $thubpath = $this->urlpath .  $this->ptp;			  
	   	                     $thubnail = $thubpath . $myfilename;
							 
							 if ((is_file($this->urlpath . $this->ptp . $photomix)) && (is_file($myfilepath))) {	
	                           $process_img = new wateresize();
			                   if ($qlt)
                                 $process_img->quality = $qlt;								   
	                           $process_img->loadimg($myfilepath,$this->mixx,$this->mixy,$restype,null,$thubpath,$photomix);
	                           $ret = $process_img->saveimg($myfilepath);	
	                           unset($process_img);	
							   echo "Image created. ";//,$thubpath,$photomix;
                               return 1;							   
							 }
                             else 
							   echo "Image not found. ";//,$thubpath,$photomix;							 
			}//case					
		  }//thubnail
	  }//post
	}	
	
	//rss data
	function item_rss($format=null) {
      $db = GetGlobal('db');	
	  $lan = getlocal();
	  $sqldynamicparam = GetReq('gnparam');
	  $itmname = $lan?'itmname':'itmfname';
	  $itmdescr = $lan?'itmdescr':'itmfdescr';
      $format = GetReq('format')?GetReq('format'):'rss2';	  
	  $i=0;

	  $code = $this->getmapf('code'); 
      $sSQL = "select id,$code,$itmname,uniname1,uniname2,active,code4," .
	          "price0,price1,cat0,cat1,cat2,cat3,cat4,$itmdescr,itmremark from products where ";
			  
	  if ($id = GetReq('id'))
	    $sSQL .= $code . "='" . $id . "' and ";	
	  elseif ($cat = GetReq('cat')) {
	    $cat_tree = explode($this->cseparator,str_replace('_',' ',$cat));
		
		foreach ($cat_tree as $c=>$cc)
	      $whereClause[] = "cat$c=" . $db->qstr(rawurldecode(str_replace('_',' ',$cc)));	
		  
		$sSQL .= implode(' and ', $whereClause) . ' and ';	  	
	  }	
	  elseif ($qry = $sqldynamicparam)
	    $sSQL .= str_replace('_ISO_','=',str_replace('_S_',"'",$gry)) . ' and ';//iso=,s='
		
	  $sSQL .= " itmactive>0 and active>0";	
	    
	  //echo $sSQL;
	  
	  $resultset = $db->Execute($sSQL,2);	
	  $result = $resultset;
	  //print_r($result);	
	  $max_items = $db->Affected_Rows();
	  //echo $max_items;  
	  $this->msg = 'Total items:'. $max_items . '<br>';
	  
	  if (!empty($result)) {		  
	    //echo $i,'>';	
		
        $xml = new pxml();//'test');
		if ($encoding = GetReq('encoding'))
		  $xml->encoding = $encoding;	
		  
        $this->xml_formater($xml,$format,1);
		
	    foreach ($result as $n=>$rec) {
            //$i+=1;
            if ($rec['cat0'])
              $ck[0] = str_replace(' ','_',$rec['cat0']);
            if ($rec['cat1'])
              $ck[1] = str_replace(' ','_',$rec['cat1']);
            if ($rec['cat2'])
              $ck[2] = str_replace(' ','_',$rec['cat2']);
            if ($rec['cat3'])
              $ck[3] = str_replace(' ','_',$rec['cat3']);
            if ($rec['cat4'])
              $ck[4] = str_replace(' ','_',$rec['cat4']);
			  
            if (!empty($ck))
              $cat = implode($this->cseparator,$ck);

            //$pp = GetGlobal('controller')->calldpc_method($mykatalogscript.".read_policy");			  
			
		    //if ($rec[$pp]>0) { //check price... 
				   //$myp = GetGlobal('controller')->calldpc_method($mykatalogscript.".spt use ".$rec[$pp]."+".$ptax); 
					 
				   //echo $ptax,$myp;
				   /*if ($decimal_point)
		             $price = number_format($myp,2,$decimal_point,'.');
				   else
				     $price = number_format($myp,2); */	

                   $price = number_format($price1,2);					 
			       //echo $price,'>';
				      			      		   
				   $item_url = 'http://' . $this->url . '/' . seturl('t=kshow&cat='.GetReq('cat').'&id='.$rec[$code],null,null,null,null,1);
                   if ($this->photodb)
				     $item_photo_url = 'http://' . $this->url . '/showphoto.php?id='.$rec[$code].'&type=LARGE';
				   else
				     $item_photo_url = 'http://' . $this->url . '/' . $this->img_large . '/' . $rec[$code] . $this->restype;
				   
				   
		           $p[] = $rec[$code];
			       $p[] = $rec[$itmname];
                   $p[] = $item_url;			   
			       $p[] = GetReq('cat');//$cat;
			       $p[] = $rec[$itmdescr];
			       $p[] = $item_photo_url;
			       $p[] = $price;
				   $p[] = $rec['cat0'];
				   $p[] = $rec['cat1'];
				   $p[] = $rec['cat2'];
				   $p[] = $rec['cat3'];
				   $p[] = $rec['cat4'];	
                   $p[] = $rec['itmremark']; 				   
				   
			       $this->xml_formater($xml,$format,null,$p);
				   unset($p);	//holds record data to pass it at xml formater				  	 
			 
			       $i+=1;	
			//}//price check 
			  
		}
	  } 
	  $this->msg .=  $i . ' items added to xml file.';
	  
	  //save file
	  if ($id = GetReq('id')) 
	    $fn = $id .'.xml';
	  elseif ($cat = GetReq('cat'))
	    $fn = str_replace($this->cseparator,'.',$cat) .'.xml';
	  else
        $fn = 'rss.xml';	  
		
	  $xmlpath = $this->urlpath . '/' . 'rss';		   
      $file = $xmlpath. '/' . $fn;
      //echo '>',$file;
	  
      switch ($format) {
        case 'rss1'    : $xmlhead = "<?xml version=\"1.0\"?>"; break;  
	    case 'rss2'    : $xmlhead = "<?xml version=\"1.0\"?>"; break;  
		case 'atom'    : $xmlhead = "<?xml version=\"1.0\" encoding=\"utf-8\"?>"; break;		   
	    case 'skroutz' : 
		default        : $xmlhead = null;
      }
	  
      $data = $xml->getxml(1);//1		 
	  $xmldata = /*$xmlhead .*/ $data;	  
 	  
	  if (is_dir($xmlpath)) {
	     //echo $file,$data;

         if ($fd = @fopen($file, 'w')) {
           fwrite($fd, $xmldata);
           fclose($fd);
           $this->msg .= ' (saved succesfully!)';
         }
		 else
		   $this->msg .= ' (can\'t open file)';
      }
	  else   
	    $this->msg .= ' (invalid directory)';		
	    
	  
	  if (GetReq('editmode')) {
	    die($this->msg);
	  }
	}

	function xml_formater(& $xml,$format=null,$init=null,$params=null,$encoding=null) {
	
	      $date = date(DATE_RFC822);//'m-d-Y');
	   
	      if ($init) {
		  
		     if ($encoding)
			   $enc = $encoding;
			 else
			   $enc = $xml->charset;

             switch ($format) {
	           case 'skroutz' : $enc ='utf8';
			                    $xml->addtag('skroutzstore',null,null,"url=$this->url|name=$xml->urltitle|encoding=$enc");							
	                            $xml->addtag('products','skroutzstore',null,null);
								break;
			   case 'rss1'    : echo 'rss1';
	   					        break; 								
			   case 'rss2'    : $enc ='utf-8';
			                    $xml->addtag('rss',null,null,"version=2.0");							
	                            $xml->addtag('channel','rss',$xml->urltitle,null);
	                            $xml->addtag('title','channel',$xml->urltitle.', '.GetReq('cat'),null);								
	                            $xml->addtag('link','channel','http://' . $this->url,null);									
	                            $xml->addtag('description','channel',$xml->urltitle.', '.GetReq('cat'),null);									
	                            $xml->addtag('language','channel','en',null);									
	                            $xml->addtag('pubDate','channel',$date,null);									
	                            $xml->addtag('lastBuildDate','channel',$date,null);	
	                            $xml->addtag('docs','channel',null,null);																	
	                            $xml->addtag('generator','channel','stereobit.networlds PHPDAC 2.1',null);									
	                            $xml->addtag('managingEditor','channel',$xml->urltitle,null);									
	                            $xml->addtag('webMaster','channel',null,null);									
	                            $xml->addtag('ttl','channel','15',null);									
	   					        break; 
			   case 'atom'    : $enc ='utf-8';
			                    $xml->addtag('feed',null,null,"xmlns=http//www.w3.org/2005/Atom");							
	                            $xml->addtag('title','feed',$xml->urltitle,null);
	                            $xml->addtag('subtitle','feed',null,null);								
	                            $xml->addtag('link','feed','/',"href=http://".$this->url."/atom/|rel=self");									
	                            $xml->addtag('link','feed','/',"href=http://".$this->url);									
	                            $xml->addtag('id','feed',null,null);									
	                            $xml->addtag('updated','feed',null,null);									
	                            $xml->addtag('author','feed',$xml->urltitle,null);	
	                            $xml->addtag('name','author',$xml->urltitle,null);																	
	                            $xml->addtag('email','author',null,null);									
	   					        break; 								
               default        : $xml->addtag('default-xml',null,null,"url=$this->url|name=$xml->urltitle|encoding=$enc");							
	                            $xml->addtag('products','default-xml',null,null);
												
		     }		  
		     return 1;
		  }
		  else {
             //product loop xml tags 		  
             switch ($format) {
	           case 'skroutz' : 
			   $cats = explode($this->cseparator,$params[3]);	
			   $manufacturer = str_replace('_',' ',array_shift($cats));
			   $category = str_replace('_',' ',$params[3]);
			   $category = str_replace($this->cseparator,'/',$category);
	           $xf = ($this->itemfimagex?$this->itemfimagex:640);
	           $yf = ($this->itemfimagey?$this->itemfimagey:480);	
	           $xt = ($this->imagex?$this->imagex:160);
	           $yt = ($this->imagey?$this->imagey:120);				   		   
			   	  
               $xml->addtag('product','products',null,"id=".$params[0]);
			   
               $xml->addtag('name','product',$xml->cdata($params[1]),null);  //cdata val
               $xml->addtag('link','product',$params[2],null);  //http://... val
               $xml->addtag('price_with_vat','product',$params[6],null);  //price 11.11
               $xml->addtag('category','product',$xml->cdata($category),"id=".$params[3]); //cdata val = descr, id=code
               $xml->addtag('image','product',$params[5],"width=$xf|height=$yf");  //http://... image
               $xml->addtag('thumbnail','product',$params[5],"width=$xt|height=$yt");  //http://... thumbnail
               $xml->addtag('manufacturer','product',$xml->cdata($manufacturer),null);  //cdata val
               $xml->addtag('shipping','product',null,"type=accurate|currency=euro");  //ship cost 11.11
               $xml->addtag('sku','product','/',null);  //...
               $xml->addtag('ssku','product','/',null);  //...
               $xml->addtag('description','product',$xml->cdata($params[4]),null);  //cdata val		  
		       break;
			   case 'rss1'    : //echo 'rss1';
	   		   break; 								
			   case 'rss2'    : //echo 'rss2';
	           $xml->addtag('item','channel',null,null);				   
			   
               $xml->addtag('title','item',$xml->cdata($params[1]),null);				   
               $xml->addtag('link','item',$params[2],null);
               $xml->addtag('description','item',$xml->cdata($params[4]),null);				   			   				   			   
			   $xml->addtag('author','item',$xml->urltitle,null);
			   $xml->addtag('category','item',$xml->cdata($params[3]),null);
			   $xml->addtag('comments','item',$xml->cdata(strip_tags($params[12])),null);
               $xml->addtag('pubDate','item',$date,null);				   			   
               $xml->addtag('guid','item',$params[0],null);			   
	           break; 
			   case 'atom'    : //echo 'atom';
	           $xml->addtag('entry','feed',null,null);				   
			   
               $xml->addtag('title','entry',$params[1],null);				   
               $xml->addtag('link','entry',$params[2],null);
               $xml->addtag('id','entry',$params[0],null);				   			   				   			   
               $xml->addtag('updated','entry',$date,null);				   			   
               $xml->addtag('summary','entry',$params[4],null);				   
	   		   break; 
			   default ://seo links
               $xml->addtag('product','products',null,"id=");
			   
               $xml->addtag('name','product',$xml->cdata('name'),null);  //cdata val
               $xml->addtag('link','product',null,null);  //http://... val
               $xml->addtag('price_with_vat','product',null,null);  //price 11.11
               $xml->addtag('category','product',$xml->cdata('category'),"id=\"\""); //cdata val = descr, id=code
               $xml->addtag('image','product',null,"width=|height=");  //http://... image
               $xml->addtag('thumbnail','product',null,"width=|height=");  //http://... thumbnail
               $xml->addtag('manufacturer','product',$xml->cdata('manufacturer'),null);  //cdata val
               $xml->addtag('shipping','product',null,"type=|currency=");  //ship cost 11.11
               $xml->addtag('description','product',$xml->cdata('description'),null);  //cdata val		  			   
			 }
			 //dump xml  
			 //echo '<pre>';
			 //$xml->dumpxml();
			 //print_r($xml->index);
			 //echo '</pre>';
			 
		     return 1;
		  }
		  
		  return 0;
	}	
	
	function import_to_offers() {
        $db = GetGlobal('db');	
		
	    $sSQL = "select ".$this->getmapf('offer')." from products where id=".GetReq('rec');
		$ret = $db->Execute($sSQL,2);	 			
		
		switch ($ret->fields[0]) {
		  case 'yes' : $sw = 'no'; break;
		  case 'no'  : 
		  default    : $sw = 'yes';
		}
		//echo $sw,'>',$ret->fields[0];
	
	    $sSQL = "update products set ".$this->getmapf('offer')."='$sw' where id=".GetReq('rec');
		$db->Execute($sSQL,1);	 		
        //echo $sSQL;
		$this->msg = "Job completed!(Products offer status: $sw";		
	}
	
	function nvl($f) {
	  //echo $f,'>',"<br>";
	  
      return (isset($f)?$f:'0');	
	}
	
	function activate_list() {
       $db = GetGlobal('db');		
	
       foreach ($_POST as $name=>$value) {
	   
	     if (strstr($name,'record_')) {
		 
		   $p = explode('_',$name);
		   //echo "<br>",$p[1],':',$value;
		   
		   $selname = GetParam("active_vehicle_" . $p[1]);
		   //echo $selname,'--';
		   if ($selname!=null)//1 or 0 ..is the prev state now activate if is set...
		     $act[$p[1]] = 1;//$selname; 
		   else
		     $act[$p[1]] = 0;	
		 }  
	   }
	   //print_r($act);
	   foreach ($act as $id=>$val) {
	   
	     $sSQL = "update products set active=" . $val . " where id=" . $id;
		 //echo $sSQL,'<br>';
		 $ret = $db->Execute($sSQL,1);			 
	   }
	   
	   //create scroll list
	   $this->create_scroll_list($act);	
	}
   
	function list_items() {
	   $mycat = GetReq('cat');
	   
	   //$toprint .= $this->show_categories();
	   
	   /*if ($this->msg) $toprint .= $this->msg;	
	   if (GetReq('cat')) {
		  if (defined("RCCATEGORIES_DPC"))//text based cats
		    $toprint .= GetGlobal('controller')->calldpc_method('rccategories.show_categories use cpitems+1');		
          elseif (defined("RCKATEGORIES_DPC"))	   //ERROR!!!!
		    $toprint .= GetGlobal('controller')->calldpc_method('rckategories.show_menu use cpitems');		  
	     //$toprint .= $this->show_categories('cpitems',1);
       }*/		 
	   	   
	   $toprint .= $this->show_grids();
	   
	   //HIDDEN FIELD TO HOLD STATS ID FOR AJAX HANDLE
	   $out .= "<INPUT TYPE= \"hidden\" ID= \"statsid\" VALUE=\"0\" >";	
	   
	   /*$toprint .= $this->alphabetical();	
	   
	   $dater = new datepicker("/MDYT");	
	   $toprint .= $dater->renderspace(seturl("t=cpitems"),"cpitems");		 
	   unset($dater);*/		
	   
       $mywin = new window(null/*$this->title*/,$toprint);
       $out .= $mywin->render();

	   return ($out);	
	} 
	
	function deltext($text) {
	
	   return "<del>".$text."</del>";
	}  
	
	//used in frontpage to build search selection list...
	function make_selection_list() {
	
	   $fmarkes = file_get_contents($this->path.'marka.opt');
	   //print_r($fmarkes);
	   
	   $markes = explode(",",$fmarkes);
	      
	   //foreach ($markes as $id=>$marka) {
	     //$ret .= "<option>" . $marka . "</option>";
		 //echo $marka,"<br>";
	   //} 
	   $ret = "<option selected>--- Επιλογή Μάρκας ---</option>";
	   
       $ret .= "<option>";//selected>";		   
	   $ret .= implode("</option><option>",$markes);
	   $ret .= "</option>";

	   return ($ret);
	}	
	
	function create_scroll_list($allow_array=null,$step=null) {
	
	    $id=0; $left = 0;
		if ($step==null) $step = 172;
	    $data = null;
		//print_r($allow_array);
	
        $mydir = dir($this->thubpath);
        while ($fileread = $mydir->read ()) { 
	
           if (stristr ($fileread,$this->restype)) {

              $title = str_replace ($this->restype, "", $fileread); 
			  $num = (int) $title;
			  
			  //if has no letters the pic (gallery) ans active=true
			  if ((is_numeric($title)) && ($allow_array[$num]==1)) {
			    $ctitle = $this->codeit($title,false);
                //$dfiles[$title] = $fileread; 
			  
			    $imgfile = $this->publicthubpath . $fileread; 
			    $itemurl = 'index.php?t=kshow&id='.$num;
			  
                $data .= "<a class=lowz href=\"$itemurl\"><img id=trimg$id class=trimg style=\"left:$left px;\" src='$imgfile' width=160 height=120 alt=\"$ctitle\"></a> 
                        <div style=\"left:$left px;\" class='trprotokola'>$ctitle</div>\r\n";			  
				$id+=1;		
				$left+=$step;
			  }		  
           }
        }
        $mydir->close ();
		
		//save
		$file = $this->thubpath . "scroll_list.lst"; //echo $file,'>';
	        
        if ($fp = @fopen ($file , "w")) {
	        //echo $file,"<br>";
                 fwrite ($fp, $data);
                 fclose ($fp);
				 return true;
        }
        else {
              $this->msg = "File creation error ($file)!\n";
		      //echo "File creation error ($filename)!<br>";
        }
		
		return false;				
	}			
	
	/*function alphabetical($command='cpitems') {
	
	  $preparam = GetReq('alpha');
	  $cat = GetReq('cat');
	  
	  $ret .= seturl("t=$command","Αρχή") . "&nbsp;|";
	
	  for ($c=$preparam.'a';$c<$preparam.'z';$c++) {
	    $ret .= seturl("t=$command&cat=$cat&alpha=$c",$c) . "&nbsp;|";
	  }
	  //the last z !!!!!
	  $ret .= seturl("t=$command&cat=$cat&alpha=".$preparam."z",$preparam."z");
	  
      //$mywin = new window('',$ret);
      //$out = $mywin->render();	  
	  
	  return ($ret);
	}*/	
	
	function codeit($id,$colorize=true) {
	
	  $seira = 'A';
	
	  $c = sprintf("%06s",$id);
	  $a = intval(substr($c,0,3))+65;
	  $x = chr($a);
	  
	  $letter = strtoupper($x); //echo $letter,'>';
	  $code = $seira . $letter . substr($c,3,3); //echo $code;
	  
	  if ($colorize)
	    $ret = writecl($code,'#000000','#FF0000');
	  else 
	    $ret = $code;	
	  
	  return ($ret);
	}
	
	function decodeit($code) {	
	
	  $seira = strtoupper(substr($code,0,1)); 
	  $letter = strtoupper(substr($code,1,1)); 
	  $number = substr($code,2,3);
	  
	  $letter2number = sprintf("%03s",ord($letter)-65);
	  
	  //echo $letter2number;
	  
	  $ret = intval($letter2number . $number);
	  
	  return ($ret);
	}	
	
	function reset_db() {
        $db = GetGlobal('db'); 
	 
	    $sSQL0 = "drop table products";
	    $result0 = $db->Execute($sSQL0,1);	
	    if ($result0) $message = "Drop table ...\n";
		
	    //create table
	    $sSQL1 = "
CREATE TABLE `products` (
  `id` int(11) NOT NULL auto_increment,
  `code1` int(11) default NULL,
  `code2` int(11) default NULL,
  `code3` varchar(64) default NULL,
  `code4` varchar(64) default NULL,
  `code5` varchar(64) default NULL,
  `itmname` varchar(128) default NULL,
  `itmactive` tinyint(4) default NULL,
  `itmfname` varchar(128) default NULL,
  `itmremark` text,
  `itmdescr` text,
  `itmfdescr` text,
  `sysins` datetime default '0000-00-00 00:00:00',
  `uniida` int(11) default '0',
  `uniname1` varchar(64) default NULL,
  `uniname2` varchar(64) default NULL,
  `uni1uni2` float default '0',
  `uni2uni1` float default '0',
  `ypoloipo1` float default '0',
  `ypoloipo2` float default '0',
  `price0` float default '0',
  `price1` float default '0',
  `cat0` varchar(128) default NULL,
  `cat1` varchar(128) default NULL,
  `cat2` varchar(128) default NULL,
  `cat3` varchar(128) default NULL,
  `cat4` varchar(128) default NULL,
  `active` tinyint(4) default '0',
  `price2` float default '0',
  `pricepc` float default '0',
  `p1` text,
  `p2` text,
  `p3` text,
  `p4` text,
  `p5` text,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `code1` (`code1`,`code2`,`code3`,`code4`,`code5`),
  KEY `itmname` (`itmname`,`cat0`,`cat1`,`cat2`,`cat3`,`cat4`),
  FULLTEXT KEY `itmremark` (`itmremark`)
) ENGINE=MyISAM  DEFAULT CHARSET=greek COMMENT='products table' AUTO_INCREMENT=73 ;
";	    
	    $result1 = $db->Execute($sSQL1,1);
	    if ($result1) $message .= "Create table ...\n";
	  
	    setInfo($message);	  	
	}
	
	function loadframe($ajaxdiv=null) {
	    $db = GetGlobal('db');

		$id = GetReq('id');
		//due to str id of code2..transform from rec id
	    $sSQL = 'select ' . $this->getmapf('code') . ' from products where id='.$id;
		$ret = $db->Execute($sSQL,2);	 			
		//$id = $ret->fields[0];
	
	    //$editurl = seturl("t=cpvmodify&editmode=1&id=").GetReq('id');
		//$editurl = seturl("t=cpvphoto&editmode=1&id=").$ret->fields[0];//$id;
	    $editurl = $this->urlbase . "cp/cpmhtmleditor.php?htmlfile=&type=.html&editmode=1&id=".$ret->fields[0];
		$frame = "<iframe src =\"$editurl\" width=\"100%\" height=\"750px\"><p>Your browser does not support iframes</p></iframe>";    

		if ($ajaxdiv)
			return $ajaxdiv.'|'.$frame;
		else
			return ($frame);
	}	
	
	function loadframe2($ajaxdiv=null) {
	    $db = GetGlobal('db');

		$id = GetReq('id');
		//due to str id of code2..transform from rec id
	    $sSQL = 'select ' . $this->getmapf('code') . ' from products where id='.$id;
		$ret = $db->Execute($sSQL,2);	 			
		//$id = $ret->fields[0];
	
	    //$editurl = seturl("t=cpvmodify&editmode=1&id=").GetReq('id');
		$editurl = seturl("t=cpvphoto&editmode=1&id=").$ret->fields[0];//$id;
	    //$editurl = $this->urlbase . "cp/cpmhtmleditor.php?htmlfile=&type=.html&editmode=1&id=".$ret->fields[0];
		$frame = "<iframe src =\"$editurl\" width=\"100%\" height=\"350px\"><p>Your browser does not support iframes</p></iframe>";    

		if ($ajaxdiv)
			return $ajaxdiv.'|'.$frame;
		else
			return ($frame);
	}		
	
	function init_grids() {
	    $editurl = seturl("t=cpeditframe&id=");
        $editurl2 = seturl("t=cpphotoframe&id="); 
  		
		$out = "
function update_stats_id() {
  var str = arguments[0];
  var str1 = arguments[1];
  var str2 = arguments[2];
  statsid.value = str;
  //alert(statsid.value);
  sndReqArg('$this->ajaxLink'+statsid.value,'stats');
  return str1+' '+str2;
}

function edit_item() {
  var str = arguments[0];
  var str1 = arguments[1];
  var str2 = arguments[2];  
  var taskid;
  var custid;
  taskid = str;  
  custid = str1;
  sndReqArg('$editurl'+taskid,'edtem');
}
function photo_item() {
  var str = arguments[0];
  var str1 = arguments[1];
  var str2 = arguments[2];  
  var taskid;
  var custid;
  taskid = str;  
  custid = str1;
  sndReqArg('$editurl2'+taskid,'phtem');
}
";
        $out .= "\r\n";
        return ($out);
	}
	
	function show_grids() {
	   $item_id = GetReq('id');
	   $cat = rawurlencode(GetReq('cat'));	
	   $sel = GetReq('sel');
	   //string must be inseide " but js frid malfunction...
	   //so get rec id and transform ito frame call
	   $id = 'id';//$this->getmapf('code');
	   //..photo_item({".$id."});
	   $editlink = "javascript:edit_item({".$id."});";//seturl('t=cpvmodify&id={'.$id.'}');		   	   	   
	   
	   $rd = $this->form_insert2(800,440,20, $editlink, 'e', true);
	   $rd .= GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use stats");
       if ($this->hasgraph) {	   
		  $rd .= $this->show_graph('statistics',null,seturl('t=cpitems&cat='.$cat.'&id='.$item_id));
	   }	  
	   else
	      $rd .= "<h3>".localize('_GNAVAL',0)."</h3>";	   
	   
	   $datattr[] = $rd;
	   $viewattr[] = "left;50%";	   
	   
	   if ($item_id) {//preselected item
		 //$editurl = $editurl = seturl("t=cpvphoto&editmode=1&id=").$item_id;
		 $editurl = $this->urlbase . "cp/cpmhtmleditor.php?htmlfile=&type=.html&editmode=1&id=".$item_id;
		 $init_content = "<iframe src =\"$editurl\" width=\"100%\" height=\"750px\"><p>Your browser does not support iframes</p></iframe>";    
	   }
	   else
	     $init_content = null; 
		 
       $wd .= GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use edtem+".$init_content);	    	   

	   $datattr[] = $wd;
	   $viewattr[] = "left;50%";
	   
	   $myw = new window('',$datattr,$viewattr);
	   $ret = $myw->render();//"center::100%::0::group_article_selected::left::3::3::");
	   unset ($datattr);
	   unset ($viewattr);

	   /*..second call disable the first..
	   if ($item_id) {//preselected item
		 $editurl = $editurl = seturl("t=cpvphoto&editmode=1&id=").$item_id;
		 //$editurl = $this->urlbase . "cp/cpmhtmleditor.php?htmlfile=&type=.html&editmode=1&id=".$item_id;
		 $init_content = "<iframe src =\"$editurl\" width=\"100%\" height=\"750px\"><p>Your browser does not support iframes</p></iframe>";    
	   }
	   else
	     $init_content = null; 	   
       $ret .= GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use phtem+".$init_content);	   
       */
	   return ($ret);	
	}
	
	function show_mail() {
       $sFormErr = GetGlobal('sFormErr');
	   $id = GetReq('rec');
	   	
	   if (defined('ABCMAIL_DPC')) {
	     $ret = $sFormErr;
	     $ret .= GetGlobal('controller')->calldpc_method('abcmail.create_mail use cpvmsend++'.$id);
	   }
	   
	   return ($ret);
	}
	
	function send_mail() {
	
	   if (!defined('RCSHMAIL_DPC')) return;	
	
	   $from = GetParam('from');
	   $to = GetParam('to');	   
	   $subject = GetParam('subject');
	   $body = GetParam('mail_text');
	
	   if ($mailmeter = GetGlobal('controller')->calldpc_method('rcshmail.sendit use '.$from.'+'.$to.'+'.$subject.'+'.$body))
	     $this->mailmsg = "($mailmeter) messages send successfully";
	   else
	     $this->mailmsg = "Send failed";	 
	}
	
	function create_mailbody($item) {
        $db = GetGlobal('db');		 
		
        $sSQL = "SELECT id,sysins,code1,pricepc,price2,sysins,itmname,uniname1,uniname2,active,code4," .
	            "price0,price1,cat0,cat1,cat2,cat3,cat4,itmdescr,".$this->getmapf('code')." from products";	  
		$sSQL .= " WHERE id=" . $item;
		//$sSQL .= " and active>0"; //no need...
	    //echo $sSQL;
	    $resultset = $db->Execute($sSQL,2);
	    $result = $db->fetch_array_all($resultset);	 	
		
	    if (count($result)>0) {	
	     foreach ($result as $n=>$rec) {		
		   $marka = get_selected_option_fromfile($rec[7],'marka',0);	//echo $marka;
		   $color = get_selected_option_fromfile($rec[9],'colors',0);	
		   $extras = //"<br><blockquote><p>" . 
		             nl2br($rec[10]);
					 // . "</p></blockquote>";
					 
		   if ($rec[2]>0)
		     $price = ($rec[2]?number_format($rec[2],2,',','.'):"&nbsp;") . "<br>";
		   else 	 
		     $price = null;	  						 
		   
		   $recar[localize('_marka',getlocal())]=($marka!=null?$marka:"&nbsp;");
		   $recar[localize('_model',getlocal())]=($rec[6]?$rec[6]:"&nbsp;");
		   $recar[localize('_color',getlocal())]=($color!=null?$color:"&nbsp;"); 
		   $recar[localize('_kybismos',getlocal())]=($rec[8]?$rec[8]:"&nbsp;"); 
		   $recar[localize('_etosk',getlocal())]=($rec[5]?$rec[5]+0:"&nbsp;");
		   $recar[localize('_km',getlocal())]=($rec[4]?$rec[4]+0:"&nbsp;");
		   $recar[localize('_fipi',getlocal())]=($rec[3]?$rec[3]:"&nbsp;");	
		   $recar[localize('_extras',getlocal())]=($rec[10]?nl2br($extras):"&nbsp;");
		   $recar[localize('_code',getlocal())] = $this->codeit($rec[11],false);
		   $recar[localize('_axia',getlocal())]= writecl($price,'#000000','#FF0000') . "<br><br>";
		   //$recar[]=;
		   //set links as elements...
		   //$recar[seturl('t=klist&cat='.$rec[0].'&subcat='.$scat,'Επιστροφή...')] =  seturl('t=qbuy&id='.$id,'Εκδήλωση ενδιαφέροντος...');	   		   		   
		   
		   foreach ($recar as $title=>$val)
		     $ret .= $title . ":" . $val . "<br>";	  		 
			 
		   //get photo
		   $pfile = sprintf("%05s",$rec[12]); //echo $pfile,"<br>";
		   $photo = $this->imgpath2mail . $pfile . $this->restype;	   
		   if (!file_exists($this->thubpath . $pfile . $this->restype))
		     $photo = $this->imgpath2mail . 'nopic' . $this->restype;					 
		   
		 }	
         $link = $this->urlbase . '?t=kshow&id='.$item;		 
         $plink = "<A href=\"$link\">";		  
		 $ret .= $plink . "<img src=\"" . $photo . "\" width=\"400\" height=\"300\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">". "</A><br>"; 
		} 
		//echo $ret;
		return ($ret);
	   
	}	
	
	function getmapf($name) {
	
	  if (empty($this->map_t)) return 0;
	  
	  foreach ($this->map_t as $id=>$elm)
	    if ($elm==$name) break;
				
	  //$id = key($this->map_t[$name]) ;
	  $ret = $this->map_f[$id];
	  return ($ret);
	}	
	
	function show_graph($xmlfile,$title,$url=null,$ajaxid=null,$xmax=null,$ymax=null) {
	  $gx = $this->graphx?$this->graphx:$xmax?$xmax:550;
	  $gy = $this->graphy?$this->graphy:$ymax?$ymax:250;	
	  
	  $ret = $title; 
	  $ret .= $this->charts->show_chart($xmlfile,$gx,$gy,$url,$ajaxid);
	  return ($ret);
	}	
	
	function add_attachment_data($itmcode,$type=null,$data=null) {
      $db = GetGlobal('db');	
	  $lan = getlocal(); //lan handle ?
	  $one_attachment = remote_paramload('SHKATALOG','oneattach',$this->path);
	  $type = $type?$type:GetReq('type');
	  
	  if ($one_attachment) 
		$slan = null;
	  else
		$slan = $lan?$lan:'0';	  
	  //echo 'ZZZZa';	
	  //echo '>',$this->attachpath.$itmcode.$slan.$type,'>';
	  	    
      $sSQL = "select code from pattachments ";
	  $sSQL .= " WHERE code='" . $itmcode . "' and type='". $type ."'";
	  if (isset($slan))
	    $sSQL .= " and lan=" . $slan;	
	  //echo $sSQL;
	  
	  $resultset = $db->Execute($sSQL,2);	
	  $result = $resultset;
	  //print_r($result);	
	  $exist = $db->Affected_Rows();
	  
	  if ($exist) {
        $sSQL = "update pattachments set data='". str_replace('<SYN>','+',$data) ."'";
	    $sSQL .= " WHERE code='" . $itmcode . "' and type='" . $type ."'";
	    if (isset($slan))
	      $sSQL .= " and lan=" . $slan;		  		
		//echo $sSQL;	  
	  }
	  else {
	    if (isset($slan))
	      $sSQL = "insert into pattachments (data,type,code,lan) values ('". str_replace('<SYN>','+', $data) ."','" . $type ."','" . $itmcode ."',$slan)";
		else
          $sSQL = "insert into pattachments (data,type,code) values ('". str_replace('<SYN>','+',$data) ."','" . $type ."','" . $itmcode ."')";
		//echo $sSQL;	  	  
	  }
	  
	  $db->Execute($sSQL,1);	
	  $affected = $db->Affected_Rows();
	  //echo $affected;
	  if ($affected) {
		echo localize('_ATTACHED',getlocal());//"Attached!";
	  }
	  else {
	    //echo $sSQL,'<br>';
		echo localize('_NOTATTACHED',getlocal());//"Unable to attach!";
	  }//if
	  
	  /*if (GetReq('editmode')) {
	    die($msg);
	  }*/	  

	  return ($affected);  	  
	}	
	
	function attach2db() {
      $db = GetGlobal('db');	
	  $i=0;

	  $code = $this->getmapf('code'); 
      $sSQL = "select id,".$code." from products ";
	  //$sSQL .= " WHERE code5 like '%' and ";
	  $sSQL .= "where itmactive>0 and active>0";	
	  //echo $sSQL;
	  
	  $resultset = $db->Execute($sSQL,2);	
	  $result = $resultset;
	  //print_r($result);	
	  $max_items = $db->Affected_Rows();
	  //echo $max_items;  
	  $this->msg = 'Total items:'. $max_items . '<br>';
	  
	  if (!empty($result)) {		  
	    //echo $i,'>';
	    foreach ($result as $n=>$rec) {
		    //echo $this->attachpath,$rec[$code],'<br>';
		    $i+= $this->add_attachment2db($rec[$code],'.txt',1);

		    $i+= $this->add_attachment2db($rec[$code],'.htm',1);

		    $i+= $this->add_attachment2db($rec[$code],'.html',1);

		}
	  } 
	  $this->msg .=  $i . ' attached files added to dababase.';
	  
	  if (GetReq('editmode')) {
	    die($this->msg);
	  }
	}
	
	function add_attachment2db($itmcode,$type=null,$all=null) {
      $db = GetGlobal('db');	
	  $lan = getlocal(); //lan handle ?
	  $one_attachment = remote_paramload('SHKATALOG','oneattach',$this->path);
	  $type = $type?$type:GetReq('type');
	  
	  if ($one_attachment) 
		$slan = null;
	  else
		$slan = $lan?$lan:'0';	  
	  //echo 'ZZZZa';	
	  //echo '>',$this->attachpath.$itmcode.$slan.$type,'>';
	  if (is_readable($this->attachpath.$itmcode.$slan.$type)) {
	  	    
      $sSQL = "select code from pattachments ";
	  $sSQL .= " WHERE code='" . $itmcode . "' and type='". $type ."'";
	  if (isset($slan))
	    $sSQL .= " and lan=" . $slan;	
	  //echo $sSQL;
	  
	  $resultset = $db->Execute($sSQL,2);	
	  $result = $resultset;
	  //print_r($result);	
	  $exist = $db->Affected_Rows();
	  
	  $data = file_get_contents($this->attachpath.$itmcode.$slan.$type);
	  
	  if ($exist) {
        $sSQL = "update pattachments set data='". addslashes($data) ."'";
	    $sSQL .= " WHERE code='" . $itmcode . "' and type='" . $type ."'";
	    if (isset($slan))
	      $sSQL .= " and lan=" . $slan;		  		
		//echo $sSQL;	  
	  }
	  else {
	    if (isset($slan))
	      $sSQL = "insert into pattachments (data,type,code,lan) values ('". addslashes($data) ."','" . $type ."','" . $itmcode ."',$slan)";
		else
          $sSQL = "insert into pattachments (data,type,code) values ('". addslashes($data) ."','" . $type ."','" . $itmcode ."')";
		//echo $sSQL;	  	  
	  }
	  
	  $db->Execute($sSQL,1);	
	  $affected = $db->Affected_Rows();
	  //echo $affected;
	  if ($affected) {
	    //remove file
		if ($this->delete_attached_file)
		  unlink($this->attachpath.$itmcode.$slan.$type); //<<<<<<<<<< !!!! NO DELETE
		$msg = "Attached successfully!";
	  }
	  else {
	    //echo $sSQL,'<br>';
		$msg = /*$sSQL .*/ "Unable to attach!";
	  }//if
	  
	  if ((!$all) && (GetReq('editmode'))) {
	    die($msg);
	  }	  
	  }//is readable	
	  return ($affected);  	  
	}
	
	function has_attachment2db($itmcode=null,$type=null,$retattachment=null) {
      $db = GetGlobal('db');	
	  $lan = getlocal(); //lan handle ?
	  $one_attachment = remote_paramload('SHKATALOG','oneattach',$this->path);
	  if ($one_attachment) 
		$slan = null;
	  else
		$slan = $lan?$lan:'0';	  
	  //echo $slan,'>';
	  $code = $this->getmapf('code');	  
      $sSQL = "select data,type from pattachments ";
	  $sSQL .= " WHERE code='" . $itmcode . "'";
	  if (isset($type))
	    $sSQL .= " and type='". $type ."'";
	  if (isset($slan))
	    $sSQL .= " and lan=" . $slan;	
	  // echo $sSQL;
	  
	  $resultset = $db->Execute($sSQL,2);	
	  $result = $resultset;
	  //print_r($result);	
	  
	  //$exist = $db->Affected_Rows();
	  foreach ($result as $i=>$rec) {
	    $type = $rec['type'];
		$att = $rec['data'];
	  }	
	  
	  if ($retattachment) {
	    $attachment = $att;
		return ($attachment);
	  }
	  else
	    return ($type);//($exist);
	  /*if ($exits) {
	  }*/
	}
	
	function delete_attachments() {
      $db = GetGlobal('db');	
	  $lan = getlocal(); //lan handle ?
	  $one_attachment = remote_paramload('SHKATALOG','oneattach',$this->path);
	  if ($one_attachment) 
		$slan = null;
	  else
		$slan = $lan?$lan:'0';	  
	  //echo $slan,'>';
	  
	  $itmcode = GetReq('id');
	  $type=getReq('type');
  
      $sSQL = "delete from pattachments ";
	  $sSQL .= " WHERE code='" . $itmcode . "'";// . "' and type='". $type ."'";
	  //if (isset($slan))
	    //$sSQL .= " and lan=" . $slan;	
	  //echo $sSQL;
	  
	  $resultset = $db->Execute($sSQL,1);	
	  $result = $resultset;
	  //print_r($result);	
	  $exist = $db->Affected_Rows();
	  
	  if (GetReq('editmode')) {
	    if ($exist)
		  die('Attachments Deleted!');
		else  
		  die('Attachments NOT Deleted!');
	  }	
	  else	
	    return ($exist);	
	}
	
	function add_attachment($post=null) {
	
	  if (defined("RCUPLOAD_DPC"))
		$out = GetGlobal('controller')->calldpc_method('rcupload.uploadform use +'.GetReq('path'));	
	  else
	    $out = 'Upload module missing!';
	  
	  $out .= "
<script type=\"text/javascript\">
      _editor_url = \"http://www.networlds.org/javascripts/html_area/\";
      _editor_lang = \"en\";
    </script>

    <!-- load the main HTMLArea files -->
    <script type=\"text/javascript\" src=\"http://www.networlds.org/javascripts/html_area/htmlarea.js\"></script>

    <script type=\"text/javascript\">
      HTMLArea.loadPlugin(\"FullPage\");

      function initDocument() {
        var editor = new HTMLArea(\"htmleditor\");
        editor.registerPlugin(FullPage);
        editor.generate();
      }

      HTMLArea.onload = initDocument;
    </script>

<SCRIPT language=\"JavaScript\">
function submitform()
{
  document.htmlform.submit();
}
</SCRIPT>
";	  	
		
	  $out .= "\n<form name=\"htmlform\" action=\"".$_SERVER['PHP_SELF'].'?encoding='.$_GET['encoding'].'&htmlfile='.$_GET['htmlfile']."\" method=\"post\">";  
	 
	  $out .= '<div>'; 
          $out .= "\n <textarea wrap='virtual' id='htmleditor' name='htmltext' style='width: 100%' rows='22' autowrap>"./*load_spath($mydata).*/"</textarea>";	 
	  $out .= '</div>';
	 
	  $out .= "<input type=\"submit\" name=\"ok\" value=\"  submit  \" />";	  
	  $out .= "<input type=\"hidden\" name=\"file2saveon\" value=\"$targetfile\" />";	  
          $out .= "<input type=\"hidden\" name=\"filetemp\" value=\"" . $file . "\" />";	  


	  $out .= "</form>";
	  return ($out);
	}

	//copy show_last_viewed itesm to get array of data 
	function get_last_viewed_items($items=10,$img_width=null, $img_height=null) {
        $db = GetGlobal('db');
        $UserName = GetGlobal('UserName');			
	    $lan = $lang?$lang:getlocal();
	    $itmname = $lan?'itmname':'itmfname';
	    $itmdescr = $lan?'itmdescr':'itmfdescr';
        $width = $img_width ? "width=\"$img_width\" " : null;
		$height = $img_height ? "height=\"$img_height\" " : null;
		
        $sSQL = "select products.id,sysins,code1,pricepc,price2,sysins,itmname,itmfname,uniname1,uniname2,active,code4,".
	            "price0,price1,cat0,cat1,cat2,cat3,cat4,itmdescr,itmfdescr,itmremark,ypoloipo1,resources,weight,volume,".$this->getmapf('code').
				",stats.id,stats.tid from products, stats ";
		$sSQL .= " WHERE stats.tid=products.".$this->getmapf('code')." and products.itmactive>0 and products.active>0";				
		
		if ($UserName)
		  $sSQL .= " and (attr2='" . decode($UserName) . "' or attr2='". session_id() . "')";
		else  
		  $sSQL .= " and attr2='" . session_id() . "'";
		  
		$sSQL .= " GROUP BY stats.tid ORDER BY stats.id DESC limit " . $items;
		
		//echo $sSQL;	
	    $resultset = $db->Execute($sSQL,2);	
		//print_r($resultset);
		foreach ($resultset as $n=>$rec) {
		
		    $id = $rec[$this->getmapf('code')];
			$cat = $rec['cat0'] ? $rec['cat0'] : null;
			$cat .= $rec['cat1'] ? $this->cseparator.$rec['cat1'] : null;
			$cat .= $rec['cat2'] ? $this->cseparator.$rec['cat2'] : null;
			$cat .= $rec['cat3'] ? $this->cseparator.$rec['cat3'] : null;
			$cat .= $rec['cat4'] ? $this->cseparator.$rec['cat4'] : null;
			
			$item_url = 'http://' . $this->url . '/' . seturl('t=kshow&cat='.$cat.'&id='.$id,null,null,null,null,1);
			$item_name_url = seturl('t=kshow&cat='.$cat.'&id='.$id,$rec['itmname'],null,null,null,1);//$this->rewrite);			   
		
            if ($this->has_photo2db($id,$this->restype,'LARGE')) {
				$item_photo_url = 'http://' . $this->url . '/showphoto.php?id='.$id.'&type=LARGE';
				$item_photo_html = "<img src=\"" . $item_photo_url . "\" $width $height>";
				$item_photo_link = "<a href='$item_url'><img src=\"" . $item_photo_url . "\" $width $height></a>";
            }  		 
			elseif (file_exists($this->urlpath.$this->img_large. $id . $this->restype)) { 	 
				$item_photo_url = 'http://' . $this->url . '/' . $this->img_large . '/' . $id . $this->restype;
				$item_photo_html = "<img src=\"" . $item_photo_url . "\" $width $height>";
				$item_photo_link = "<a href='$item_url'><img src=\"" . $item_photo_url . "\" $width $height></a>";
		    }		
		
			$ret_array[] = array(
			                'code'=>$id,
			                'itmname'=>$rec['itmname'],
							'itmdescr'=>$rec['itmdescr'],
							'itmremark'=>$rec['itmremark'],
							'uniname1'=>$rec['uniname1'],
							'price0'=>str_replace('.',',',$rec['price0']),
							'price1'=>str_replace('.',',',$rec['price1']),
							'cat0'=>$rec['cat0'],
							'cat1'=>$rec['cat1'],
							'cat2'=>$rec['cat2'],
							'cat3'=>$rec['cat3'],
							'cat4'=>$rec['cat4'],
							'item_url'=>$item_url,
							'item_name_url'=>$item_name_url,
							'photo_url'=>$item_photo_url,
							'photo_html'=>$item_photo_html,
							'photo_link'=>$item_photo_link,
							);
		}
		
		return ($ret_array);		
	}	
	
	function create_page($template=null, $imgw=null,$imgh=null) {
	    if (($template) && (is_readable($template))) {
	        $template_data = @file_get_contents($template);
			//echo '>SEE:',$template_data;
		}	
	
	    $data = $this->get_last_viewed_items(100,90,90);
		//print_r($data);
		//echo count($data); >1 ?
		$tokens = array();
		$items = array();
		foreach ($data as $n=>$rec) {
		
			if ($template_data) { 
			    //$ret.= $rec['itmname'].'<BR/>'; 
			    $tokens[] = $rec['code'];
			    $tokens[] = $rec['itmname'];
				$tokens[] = $rec['itmdescr'];
				$tokens[] = $rec['itmremark'];
				$tokens[] = $rec['itmuniname1'];
				$tokens[] = $rec['price0'];
				$tokens[] = $rec['price1'];
				$tokens[] = $rec['cat0'];
				$tokens[] = $rec['cat1'];
				$tokens[] = $rec['cat2'];
				$tokens[] = $rec['cat3'];
				$tokens[] = $rec['cat4'];
				$tokens[] = $rec['item_name_url'];
				$tokens[] = $rec['item_url'];
				$tokens[] = $rec['photo_url'];
				$tokens[] = $rec['photo_html'];
				$tokens[] = $rec['photo_link'];
				$items[] = $this->combine_tokens($template_data, $tokens);
				unset($tokens);		
            }
            else
                $ret.= $rec['itmname'].'<BR/>'; 			
		}
		
		if (!empty($items)) { 
		//print_r($items);
	    //make table
		$linemax=2;
	    $itemscount = count($items);
	    $timestoloop = floor($itemscount/$linemax)+1;
	    $meter = 0;
	    for ($i=0;$i<$timestoloop;$i++) {
	      //echo $i,"---<br>";
	      for ($j=0;$j<$linemax;$j++) {
	        //echo $i*$j,"<br>";
		    $viewdata[] = (isset($items[$meter])? $items[$meter] : "&nbsp");
		    $viewattr[] = "center;10%";	
		    $meter+=1;	 
	      }
	    
	      $myrec = new window('',$viewdata,$viewattr);
          $ret .= $myrec->render("center::100%::0::::left::0::0::");		  
	      unset ($viewdata);
	      unset ($viewattr);	  
	    }		
		}
		
		return ($ret);
	}
	
	//tokens method	
	function combine_tokens($template_contents,$tokens) {
	
	    if (!is_array($tokens)) return;
		
		if (defined('FRONTHTMLPAGE_DPC')) {
		  $fp = new fronthtmlpage(null);
		  $ret = $fp->process_commands($template_contents);
		  unset ($fp);
          //$ret = GetGlobal('controller')->calldpc_method("fronthtmlpage.process_commands use ".$template_contents);		  		
		}		  		
		else
		  $ret = $template_contents;
		  
		//echo $ret;
	    foreach ($tokens as $i=>$tok) {
            //echo $tok,'<br>';
		    $ret = str_replace("$".$i."$",$tok,$ret);
	    }
		//clean unused token marks
		for ($x=$i;$x<20;$x++)
		  $ret = str_replace("$".$x."$",'',$ret);
		//echo $ret;
		return ($ret);
	}		

        function searchinbrowser() {
            $ret = "
           <form name=\"searchinbrowser\" method=\"post\" action=\"\">
           <input name=\"filter\" type=\"Text\" value=\"\" size=\"56\" maxlength=\"64\">
           <input name=\"Image\" type=\"Image\" src=\"../images/b_go.gif\" alt=\"\"    align=\"absmiddle\" width=\"22\" height=\"28\" hspace=\"10\" border=\"0\">
           </form>";

          $ret .= "<br>Last search: " . GetParam('filter');

          return ($ret);
        }					

};
}
?>