<?
require_once realpath( dirname(__FILE__) . '/Cpanel/Util/Autoload.php');

class cpanelx3{

    var $cp;

    function __construct($cpuser=null,$cppass=null,$domain=null){
	
	    $host = $domain ? $domain : 'localhost'; 
	   
		$cpCfg = array(
			'cpanel'=>array(
				'service'=>array(
					'cpanel' => array(
							'host' => $host,
							'user' => $cpuser,
							'password' => $cppass
					)
				)
			)
		);
    
		$this->cp = Cpanel_PublicAPI::getInstance($cpCfg);
	 
    }
	
	function _cpapi1_exec($module, $func, $args=null) {
	
		$queryMF = array(
			'module' => $module,
			'function' => $func,
		);
		
	    $queryArgs = is_array($args) ? $args : array();
		
		$response = $this->cp->cpanel_api1_request('cpanel', $queryMF, $queryArgs);
			
		$ret = $response->data->result;
		
        return ($ret);
    }	
	
	function _cpapi2_exec($module, $func, $args=null) {
	
		$queryMF = array(
			'module' => $module,
			'function' => $func,
		);
		
	    $queryArgs = is_array($args) ? $args : array();
		
		$response = $this->cp->cpanel_api2_request('cpanel', $queryMF, $queryArgs);	
		
		foreach ($response->cpanelresult->data as $dataset) {
			foreach ($dataset as $key => $value) {
				$ret[$key] = $value;
			}
		}		
        return ($ret);
    }

}
?>