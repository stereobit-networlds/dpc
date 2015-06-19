<?php
/**
 * file_utils is a class designed to bring you basic commands to work over an fs.
 * <br>Last update: October 24, 2006.
 * <br>Author: Marcelo Entraigas <m_entraigas at yahoo dot com>.
 * <br>Licence: BSD License.
 */
define('_linux', strpos(_path,':')? false : true);
define('_slash', _linux? '/' : chr(92));

class file_utils {
  var $files    = array();
  var $folders  = array();
  
  /**
   * class constructor.
   * here is defined the default working path.
   *
   * @param string $path
   */
  function file_utils ($path='') {
    if($path==='')
      define('_path', dirname(__FILE__)._slash);
    else{
    //check for '/./' & '//'
    $path   = str_replace(_slash.'.'._slash, _slash, $path);
    $path   = str_replace(_slash._slash, _slash, $path);
    //check for '/../'
    $buscar = sprintf("%s[^%s]+%s..%s", _slash, _slash, _slash, _slash);
    $buscar = str_replace('.','\.',$buscar);
    $path   = eregi_replace($buscar, _slash, $path);
    //silly patch '/../'
    $path   = str_replace(_slash.'..'._slash, _slash, $path);
    if($path==='') $path='/';
    if(is_dir($path))
      define('_path', $path);
    }
	
	//echo _path;
  }

  /**
   * List a folder content and put it on $this->folders or $this->files.
   *
   * @param string $path
   */
  function ls($path='',$filter=null){
  
    clearstatcache();
    $handle = @opendir($path);
    if($handle==false){
      $path = _path;
    }
    $handle = @opendir($path);
    if($handle != false){
    	while(false!==($filename=@readdir($handle))){
    		$filepath           = $path . _slash . $filename;
    	    $flag               = 'folders';
    		$tmp['filepath']    = $filepath;
    		$tmp['description'] = htmlentities($filename);
    		$tmp['perms']       = sprintf("%o",@fileperms($filepath));
    		$tmp['time']        = date("H:i m-d-y",@filemtime($filepath));
			
    		if(@is_file($filepath)){
			  if ($filter) {
			    if (stristr($filename,$filter)) {
                  $flag  = 'files';
                  $tmp['size'] = filesize($filepath);
				}  
			  }
			  else {//no filter
                $flag  = 'files';
                $tmp['size'] = filesize($filepath);			  
			  } 	
            }
    		$eval = sprintf("\$this->%s['%s'] = \$tmp;", $flag, addslashes($filename));
			//echo $eval;
    		eval($eval);
    	}
    }
    @closedir($handle);
    @ksort($this->files);
    @ksort($this->folders);
	
	//echo "<pre>";
	//print_r($this->folders);
	//echo "</pre>";
  }
  
  /**
   * Get a human redable size
   *
   * @param integer $size
   * @return string
   */
  function get_size($size){
    $size = (int) $size;
    if($size<1000)
      $size = sprintf("%0.0f B",$size);
    elseif ($size<(1024*1000))
      $size = sprintf("%0.2f KB",$size/1024);
    elseif ($size<(1024*1024*1000))
      $size = sprintf("%0.2f MB",$size/(1024*1024));//1048576
    else
      $size = sprintf("%0.2f GB",$size/(1024*1024*1024));//1073741824
    return $size;
  }
  
  /**
   * Dowload a file from server
   *
   * @param string $file
   */
  function download($file){
    if(is_file($file) && @fopen($file,'r')){
    	header("Content-type: application/force-download");
    	header(sprintf("Content-Disposition: attachment; filename=%s",basename($file)));
    	@readfile($file);
    }else{
      header('HTTP/1.0 401 Unauthorized');
    }
  	exit;
  }
  
  /**
   * Make a folder on the server
   *
   * @param string $dir
   * @param string $perm
   */
  function mkdir ($dir, $perm='0777'){ 
    $tmp  = explode(_slash, $dir);
    $path = '';
    foreach ($tmp as $local) {
    	$path .= $local . _slash; 
      $mkdir = "if(@mkdir('$dir',$perm)==false) return false;";
      eval($mkdir);
    }
  }

  /**
   * Cahnge file perms
   *
   * @param string $file
   * @param string $perm
   */
  function chmod ($file, $perm) {
    $perm  = ereg('[1-7]{1,3}',$perm)? sprintf("0%d",$perm) : "'$perm'";
  	$chmod = "@chmod('$file', $perm);";
  	eval($chmod);
	//echo $file,'>>>',$perm;
	//chmod('$file', $perm);
  }
  
  /**
   * Upload a file/s to the server
   *
   * @param string $to
   */
  function upload ($to) {
    foreach ($_FILES as $file) {
      if(is_uploaded_file($file['tmp_name'])){
        @move_uploaded_file($file['tmp_name'], $to . basename($file['name']));
        @chmod($to . basename($file['name']), 0755);
      }
    }
  }
  
  /**
   * Delete a file from the server
   *
   * @param string $filename
   */
  function rm ($filename) {
    @unlink($filename);
  }
  
  /**
   * Generate/overwrite a file with content
   *
   * @param string $content
   * @param string $to
   * @return true|false
   */
  function save ($content, $to){
    if(!empty($content) and $fp = @fopen($to, 'w')) {
      @fwrite($fp, $content);
      return @fclose($fp);
    }
    return false;
  }

  /**
   * Generate/append a file with content
   *
   * @param string $content
   * @param string $to
   * @return true|false
   */
  function append ($string, $to){
    if(!empty($string) and $fp = @fopen($to, 'a')) {
      @fwrite($fp, $string);
      return @fclose($fp);
    }
    return false;
  }
}
?>