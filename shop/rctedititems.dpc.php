<?php
$__DPCSEC['RCTEDITITEMS_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCTEDITITEMS_DPC")) && (seclevel('RCTEDITITEMS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCTEDITITEMS_DPC",true);

$__DPC['RCTEDITITEMS_DPC'] = 'rctedititems';

$d = GetGlobal('controller')->require_dpc('shop/rcitems.dpc.php');
require_once($d);

$__EVENTS['RCTEDITITEMS_DPC'][0]='cptedititems';
$__EVENTS['RCTEDITITEMS_DPC'][1]='cptsaveitems';
$__EVENTS['RCTEDITITEMS_DPC'][2]='cptnewitems';
 
$__ACTIONS['RCTEDITITEMS_DPC'][0]='cptedititems';
$__ACTIONS['RCTEDITITEMS_DPC'][1]='cptsaveitems';
$__ACTIONS['RCTEDITITEMS_DPC'][2]='cptnewitems';

$__DPCATTR['RCTEDITITEMS_DPC']['cptedititems'] = 'cptedititems,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCTEDITITEMS_DPC'][0]='RCTEDITITEMS_DPC;Add/Edit items;Add/Edit items';

class rctedititems extends rcitems {

    var $title,$selected_items;	
    var $imgxval, $imgyval;

    function rctedititems() {
	
	    rcitems::rcitems();
	
	    $this->title = localize("RCTEDITITEMS_DPC",getlocal());	
        $this->selected_items = null;
		$this->imgxval = (!empty($this->autoresize)) ? $this->autoresize[0] : 0;//90;//as it is
		$this->imgyval = 0;//90; //as it is
		$this->imgyval = 0;//90; //as it is
		$this->imgyval = 0;//90; //as it is
		$this->imgyval = 0;//90; //as it is
		$this->imgyval = 0;//90; //as it is
	}
	
	function event($event=null) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////		
	
	   switch ($event) {
	    
		 case 'cptsaveitems' : 
	     default :
	   }
	}
	
	function action($action=null) {
	  
	   switch ($action) {
	   
	     case 'cptnewitems' : 
		 case 'cptsaveitems': 
	     default            : 

	   }	   
	   
	   return ($ret);
	}
	
	
	//override 
	function get_last_viewed_items($items=10,$img_width=null, $img_height=null) {
        $db = GetGlobal('db');
        $UserName = GetGlobal('UserName');			
	    $lan = $lang?$lang:getlocal();
	    $itmname = $lan?'itmname':'itmfname';
	    $itmdescr = $lan?'itmdescr':'itmfdescr';
		$codefield = $this->getmapf('code');
		
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
		$ix =1;
		foreach ($resultset as $n=>$rec) {
		
		    $id = $rec[$codefield];
			
		    //read posted data per item..
		    $out_of_list = GetParam($id) ? true:false;//remove from rendering list
			//if (GetParam($id)) continue;
		    $img_width = GetParam('imagex_'.$id) ? GetParam('imagex_'.$id) : $img_width;
		    $img_height = GetParam('imagey_'.$id) ? GetParam('imagey_'.$id) : $img_height;		
            $width = $img_width ? "width=\"$img_width\" " : null;
		    $height = $img_height ? "height=\"$img_height\" " : null;				
			
			$cat = $rec['cat0'] ? str_replace(' ','_',$rec['cat0']) : null;
			$cat .= $rec['cat1'] ? '^'.str_replace(' ','_',$rec['cat1']) : null;
			$cat .= $rec['cat2'] ? '^'.str_replace(' ','_',$rec['cat2']) : null;
			$cat .= $rec['cat3'] ? '^'.str_replace(' ','_',$rec['cat3']) : null;
			$cat .= $rec['cat4'] ? '^'.str_replace(' ','_',$rec['cat4']) : null;
			
			$item_url = 'http://' . $this->url . '/' . seturl('t=kshow&cat='.$cat.'&id='.$id,null,null,null,null,1);
			$item_name_url = seturl('t=kshow&cat='.$cat.'&id='.$id,$rec['itmname'],null,null,null,1);//$this->rewrite);			   
			$item_name_url_base = "<a href='$item_url'>".$rec['itmname']."</a>";
		
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

			//fetch extra html code 
            $attachment = $this->has_attachment2db($id,'.html', true);	
            //replace relative path image/files with absolute path = url
            if (stristr($attachment,'src="images/'))
                $attachment = str_replace('src="images/','src="'.$this->url.'/images/');			
		
		    $order_id = 'order_'.$id;
		    $i = GetParam($order_id) ? GetParam($order_id) : $ix++;
			$ret_array[$i] = array(
			                'disabled'=>$out_of_list,
			                'code'=>$id,
			                'itmname'=>$rec[$itmname],
							'itmdescr'=>$rec[$itmdescr],
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
							'item_name_url'=>$item_name_url_base,
							'photo_url'=>$item_photo_url,
							'photo_html'=>$item_photo_html,
							'photo_link'=>$item_photo_link,
							'attachment'=>$attachment,
							);
		}
		
		return ($ret_array);		
	}	
	
	//fetch current viewed category items for template mail creation
	function get_category_items($items=10,$img_width=null, $img_height=null,$cat=null) {
        $db = GetGlobal('db');		
	    $lan = $lang?$lang:getlocal();
	    $itmname = $lan?'itmname':'itmfname';
	    $itmdescr = $lan?'itmdescr':'itmfdescr';
	    $cat_tree = explode('^',str_replace('_',' ',$cat));	
        $codefield = $this->getmapf('code');		
		
        $sSQL = "select id,sysins,code1,pricepc,price2,sysins,itmname,itmfname,uniname1,uniname2,active,code4,".
	            "price0,price1,cat0,cat1,cat2,cat3,cat4,itmdescr,itmfdescr,itmremark,ypoloipo1,resources,weight,volume,".$this->getmapf('code').
				" from products WHERE ";

		foreach ($cat_tree as $c=>$cc)
	      $whereClause[] = "cat$c=" . $db->qstr(rawurldecode(str_replace('_',' ',$cc)));	
		  
		$sSQL .= implode(' and ', $whereClause);
	    $sSQL .= " and itmactive>0 and active>0";	
        $sSQL .= " limit " . $items;		
		
		//echo $sSQL;	
	    $resultset = $db->Execute($sSQL,2);	
		//print_r($resultset);
		$ix =1;
		foreach ($resultset as $n=>$rec) {
		
		    $id = $rec[$codefield];
			
		    //read posted data per item..
		    $out_of_list = GetParam($id) ? true:false;//remove from rendering list
			//if (GetParam($id)) continue;
		    $img_width = GetParam('imagex_'.$id) ? GetParam('imagex_'.$id) : $img_width;
		    $img_height = GetParam('imagey_'.$id) ? GetParam('imagey_'.$id) : $img_height;		
            $width = $img_width ? "width=\"$img_width\" " : null;
		    $height = $img_height ? "height=\"$img_height\" " : null;				
			
			
			$cat = $rec['cat0'] ? str_replace(' ','_',$rec['cat0']) : null;
			$cat .= $rec['cat1'] ? '^'.str_replace(' ','_',$rec['cat1']) : null;
			$cat .= $rec['cat2'] ? '^'.str_replace(' ','_',$rec['cat2']) : null;
			$cat .= $rec['cat3'] ? '^'.str_replace(' ','_',$rec['cat3']) : null;
			$cat .= $rec['cat4'] ? '^'.str_replace(' ','_',$rec['cat4']) : null;
			
			$item_url = 'http://' . $this->url . '/' . seturl('t=kshow&cat='.$cat.'&id='.$id,null,null,null,null,1);
			$item_name_url = seturl('t=kshow&cat='.$cat.'&id='.$id,$rec['itmname'],null,null,null,1);//$this->rewrite);			   
		    $item_name_url_base = "<a href='$item_url'>".$rec['itmname']."</a>";
			
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

			//fetch extra html code 
            $attachment = $this->has_attachment2db($id,'.html', true);	
            //replace relative path image/files with absolute path = url
            if (stristr($attachment,'src="images/'))
                $attachment = str_replace('src="images/','src="'.$this->url.'/images/');			
		
		    $order_id = 'order_'.$id;
		    $i = GetParam($order_id) ? GetParam($order_id) : $ix++;
			$ret_array[$i] = array(
			                'disabled'=>$out_of_list,
			                'code'=>$id,
			                'itmname'=>$rec[$itmname],
							'itmdescr'=>$rec[$itmdescr],
							'itmremark'=>$rec['itmremark'],
							'uniname1'=>$rec['uniname1'],
							'price0'=>str_replace('.',',',sprintf("%01.2f",$rec['price0'])),
							'price1'=>str_replace('.',',',sprintf("%01.2f",$rec['price1'])),
							'cat0'=>$rec['cat0'],
							'cat1'=>$rec['cat1'],
							'cat2'=>$rec['cat2'],
							'cat3'=>$rec['cat3'],
							'cat4'=>$rec['cat4'],
							'item_url'=>$item_url,
							'item_name_url'=>$item_name_url_base,
							'photo_url'=>$item_photo_url,
							'photo_html'=>$item_photo_html,
							'photo_link'=>$item_photo_link,
							'attachment'=>$attachment,
							);
		}
		
		return ($ret_array);		
	}	
	
	//fetch current viewed category items for template mail creation
	function get_specific_item($items=10,$img_width=null, $img_height=null, $id=null) {
        $db = GetGlobal('db');		
	    $lan = $lang?$lang:getlocal();
	    $itmname = $lan?'itmname':'itmfname';
	    $itmdescr = $lan?'itmdescr':'itmfdescr';	
        $codefield = $this->getmapf('code');		
		
        $sSQL = "select id,sysins,code1,pricepc,price2,sysins,itmname,itmfname,uniname1,uniname2,active,code4,".
	            "price0,price1,cat0,cat1,cat2,cat3,cat4,itmdescr,itmfdescr,itmremark,ypoloipo1,resources,weight,volume,".$this->getmapf('code').
				" from products WHERE ";

		$sSQL .= $codefield . "='" . $id ."'";
	    $sSQL .= " and itmactive>0 and active>0";	
        $sSQL .= " limit " . $items;		
		
		//echo $sSQL;	
	    $resultset = $db->Execute($sSQL,2);	
		//print_r($resultset);
		$ix =1;
		foreach ($resultset as $n=>$rec) {
		
		    $id = $rec[$codefield];
			
		    //read posted data per item..
		    $out_of_list = GetParam($id) ? true:false;//remove from rendering list
			//if (GetParam($id)) continue;
		    $img_width = GetParam('imagex_'.$id) ? GetParam('imagex_'.$id) : $img_width;
		    $img_height = GetParam('imagey_'.$id) ? GetParam('imagey_'.$id) : $img_height;		
            $width = $img_width ? "width=\"$img_width\" " : null;
		    $height = $img_height ? "height=\"$img_height\" " : null;				
			
			
			$cat = $rec['cat0'] ? str_replace(' ','_',$rec['cat0']) : null;
			$cat .= $rec['cat1'] ? '^'.str_replace(' ','_',$rec['cat1']) : null;
			$cat .= $rec['cat2'] ? '^'.str_replace(' ','_',$rec['cat2']) : null;
			$cat .= $rec['cat3'] ? '^'.str_replace(' ','_',$rec['cat3']) : null;
			$cat .= $rec['cat4'] ? '^'.str_replace(' ','_',$rec['cat4']) : null;
			
			$item_url = 'http://' . $this->url . '/' . seturl('t=kshow&cat='.$cat.'&id='.$id,null,null,null,null,1);
			$item_name_url = seturl('t=kshow&cat='.$cat.'&id='.$id,$rec['itmname'],null,null,null,1);//$this->rewrite);			   
		    $item_name_url_base = "<a href='$item_url'>".$rec['itmname']."</a>";
			
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

			//fetch extra html code 
            $attachment = $this->has_attachment2db($id,'.html', true);
            //replace relative path image/files with absolute path = url
            if (stristr($attachment,'src="images/'))
                $attachment = str_replace('src="images/','src="'.$this->url.'/images/');   			
		
		    $order_id = 'order_'.$id;
		    $i = GetParam($order_id) ? GetParam($order_id) : $ix++;
			$ret_array[$i] = array(
			                'disabled'=>$out_of_list,
			                'code'=>$id,
			                'itmname'=>$rec[$itmname],
							'itmdescr'=>$rec[$itmdescr],
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
							'item_name_url'=>$item_name_url_base,
							'photo_url'=>$item_photo_url,
							'photo_html'=>$item_photo_html,
							'photo_link'=>$item_photo_link,
							'attachment'=>$attachment,
							);
		}
		
		return ($ret_array);
	}		
	
	//override
	function create_page($template=null, $imgw=null,$imgh=null) {
	    $id = GetReq('id');
	    $cat = GetReq('cat');
	    $imgxval = $imgw ? $imgw : $this->imgxval; 
		$imgyval = $imgh ? $imgh : $this->imgyval;		
		//print_r($_GET);
	
	    if (($template) && (is_readable($template))) {
	        $template_data = @file_get_contents($template);
		}	
	    
		if ($id) 
		   $this->selected_items = $this->get_specific_item(1,$imgxval,$imgyval,$id); 
		elseif ($cat)
		   $this->selected_items = $this->get_category_items(100,$imgxval,$imgyval,$cat);
		else
	       $this->selected_items = $this->get_last_viewed_items(100,$imgxval,$imgyval);
		   
		//..order array by key
		ksort($this->selected_items);
		
		//print_r($this->selected_items);
		//echo count($this->selected_items); //>1 ?
				
		
		$tokens = array();
		$items = array();
		foreach ($this->selected_items as $n=>$rec) {
		
		  //is not out of rendering list
		  if ($rec['disabled']===false) {
		  
			if ($template_data) { 
			    //$ret.= $rec['itmname'].'<BR/>'; 
			    $tokens[] = $rec['code'];
			    $tokens[] = $rec['itmname'];
				$tokens[] = $rec['itmdescr'];
				$tokens[] = $rec['itmremark'];
				$tokens[] = $rec['uniname1'];
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
				$tokens[] = $rec['attachment'];
				$items[] = $this->combine_tokens($template_data, $tokens);
				unset($tokens);		
            }
            else //default view..
                $ret.= $rec['itmname'].'<BR/>'; 
		  }//disabled item		
		}
		
		if (!empty($items)) {

			//make table
			$linemax=2;
			$itemscount = count($items);
			if ($itemscount>1) {   
				/*$timestoloop = floor($itemscount/$linemax)+1;
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
				}*/
				//LINE BY LINE...
				foreach ($items as $i=>$item)
				    $ret .= $item;
            }
            else //one item
                $ret = $items[0];			
		}
		
		return ($ret);
	}
	
	//when no create_page..just read items to show...
	function read_selected_items() {
	    $id = GetReq('id'); 
	    $cat = GetReq('cat');
		
		if ($id) 
		   $this->selected_items = $this->get_specific_item(1,$this->imgxval,$this->imgyval,$id); 
		elseif ($cat)
		   $this->selected_items = $this->get_category_items(100,$this->imgxval,$this->imgyval,$cat);
		else
	       $this->selected_items = $this->get_last_viewed_items(100,$this->imgxval,$this->imgyval);
		   	
		//..order array by key
		ksort($this->selected_items);			
	}

	//redesign form
	function show_selected_items($submitname=null,$submitaction,$taction=null, $template=null, $xval=null, $yval=null) {
	    $xval = $xval ? $xval : $this->imgxval; 
		$yval = $yval ? $yval : $this->imgyval;
	    $myact = $taction ? $taction : 'cptedititems';
	    $myaction = seturl("t=$myact");
        $submitaction = $submitaction ? $submitaction :	'cptedititems';	
		//print_r($_POST);
		
		if (!empty($this->selected_items)) {		
			$ret = "<form method=\"post\" name=\"RCTEDITITEMS\" action=\"$myaction\">";
            
			foreach ($this->selected_items as $n=>$rec) {
			    $order = $n;//+1;
			    $id = $rec['code'];
			    //read posted data per item..
				$is_disbaled = $rec['disabled'];//is out of rendering list

				$selected = /*GetParam($id)*/($is_disbaled) ? 'checked':null;//remove from rendering list
			    $setxval = GetParam('imagex_'.$id) ? GetParam('imagex_'.$id) : $xval;
			    $setyval = GetParam('imagey_'.$id) ? GetParam('imagey_'.$id) : $yval;
				
				$recdata[] = "<input type=\"checkbox\" name=\"$id\" $selected>";
				$recdata[] = "<INPUT type=\"text\" name=order_$id value=\"$order\" maxlenght=\"3\" size=3>";
			    $recdata[] = "<INPUT type=\"text\" name=imagex_$id value=\"$setxval\" maxlenght=\"3\" size=3>";
			    $recdata[] = "<INPUT type=\"text\" name=imagey_$id value=\"$setyval\" maxlenght=\"3\" size=3>";
			    //$x = intval(100/count($recdata)); echo $x;
			    foreach ($rec as $i=>$recname) {
				    $recdata[] = $recname;
				    $recattr[] = "left;10%";
                }			
				$linewin = new window('',$recdata, $recattr);
				$ret .= $linewin->render("center::100%::0::group_article_selected::left::0::0::");	
				unset ($linewin);
                unset ($recdata);
                unset ($recattr); 				
	        }
			$ret .= "<hr><input type=submit value=\"$submitname\">";
	        $ret .= "<input type=\"hidden\" name=\"FormAction\" value=\"$submitaction\">"; 
			
            //save rcshsubsqueue mail params
            if ($template) 			
			    $ret .= "<input type=\"hidden\" name=\"template\" value=\"$template\">"; 
            /*if ($images=GetSessionParam('images')) 			
			    $ret .= "<input type=\"hidden\" name=\"template\" value=\"$images\">"; 				
            if ($attachments=GetSessionParam('attachments')) 			
			    $ret .= "<input type=\"hidden\" name=\"template\" value=\"$attachments\">";*/ 
				
            $ret .= "</form>";  
        }			
	    else
		    $ret = 'None';
			
	    return ($ret);		
	}			

};
}
?>