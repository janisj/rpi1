<?php
function get_config()
{
		
		$file = file_get_contents('./configure.json',false);
		return $file;
/* //needs apc installation on php belov 5.5... 

	if (apc_exists('config')) {
		return  apc_fetch('config');
	} else {
		
		$file = file_get_contents('./configure.json',false);
		if ($file==FALSE){
		return FALSE;
		}
    		apc_store('config',$file,60*60);
		return $file;
	}	
*/
}
?>
