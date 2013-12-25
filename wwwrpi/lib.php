<?php
function get_config()
{
		
		$file = file_get_contents('./configure.json',false);
		return $file;

}
?>
