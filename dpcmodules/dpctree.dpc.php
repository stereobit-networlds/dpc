<?php

$d = GetGlobal('controller')->require_dpc('dpcmodules/dpctree.lib.php');
require_once($d); 
	
	
$__DPCSEC['DPCTREE_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("DPCTREE_DPC")) && (seclevel('DPCTREE_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("DPCTREE_DPC",true);

$__DPC['DPCTREE_DPC'] = 'dpctree';
 
$__EVENTS['DPCTREE_DPC'][0]='dpctree';

$__ACTIONS['DPCTREE_DPC'][0]='dpctree';

$__DPCATTR['DPCTREE_DPC']['DPCTREE'] = 'DPCTREE,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['DPCTREE_DPC'][0]='DPCTREE_DPC;DPC Tree;DPC Tree';

class dpctree {	
 
    var $path,$dtree,$dpcs;

    function dpctree() {
  
      $this->path = _APPPATH_ . "/dpc/";
	  
	  $this->dtree = new dpc_tree($this->path); 
    }
  
    function event($sAction) {
	
	  $this->dpcs = $this->dtree->read_dpcs();
    }
  
    function action($action) {

	 $out = $this->viewtree();
	 
	 return ($out);
    }  
	
	function viewtree() {
	
	  print_r($this->dpcs);
	
	  return ($ret);	
	} 
}
};

?>