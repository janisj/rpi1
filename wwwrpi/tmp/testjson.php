<?php
include 'lib.php';
echo exec("whoami");
$out=get_config();
$arr=json_decode($out,true);
//var_dump($arr);
var_dump($arr['led1']['command']['ledon']);
echo "<br>";
var_dump($out);







?>
