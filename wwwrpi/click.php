<?php
include 'lib.php';

// have to:
//	 modify lib.php use apc
//	error handling
//	security
//	
// on error prints error message...
// sends messages via socet programm and passes back the response from the server
/* The inputs are : $_POST['id'] (id) and $_POST['data'] (data) variables.
	The id corresponds to the id atribute (top) in configure.json file.
	The string in datat should be one of the the command attributes, where value of each command attribute is the actual value (command) to pass to the socket server. 
	Socket server should implement all the commands descirbed in command array.
	Address atribute describes socket connection, adrress and port of the server responsible for id object. 


example of json:
{"id1":{
  "address":"tcp://localhost:5555",
  "type": "",
  "description":"This is led on-off example json configuration.",
  "command": {
    "comand1": "servercommand1",
    "comand2": "servercommand2"
  }}
The "id1" is passed by $_POST[id]. $_POST['data'] can be one of "command1" or "command2". 
The socket server will receive servercommand1 or servercommand2, depending on the $_POST['data'] value.
The connection will be on tcp://localhost:5555 socket.
The socket server should pass back.. -- to implement pass back commad in json...

The corresponding html file should have form element for example
<input type="radio" name="id1" id="someuniqueid"  value="command1" >
where the value of the attirbute name is the id to be passed as $_POST[id]. The value of the atribute value should be one of command arrays atributes "command1" or "command2".
*/

//$_POST=$_GET;
try{

	if (!array_key_exists('data',$_POST) || !array_key_exists('id',$_POST)){ throw new Exception('Variables are not set.'); }
	$id=$_POST['id'];
	$data=$_POST['data'];
	$conf=get_config();
	$arr_conf=json_decode($conf, true);
	if (!is_array($arr_conf) || !array_key_exists($id, $arr_conf)){throw new Exception('Json configuration lacking item '.$id);}
		
	$adr=$arr_conf[$id]['address'];
	$cmd= $arr_conf[$id]['command'][$data];
	/*
	//  comunicate to the server using C executable c1. 
	//  c1 should be called: c1 address command
	//$address='/opt/lampp/htdocs/proj1/c1 '.$arr_conf[$id]['address'].' '. $arr_conf[$id]['command'][$data].' 2>&1';
		
	$address='/opt/lampp/htdocs/wwwrpi/c1 '.$adr.' '.$cmd;
	//check config.json file
	$return=1;
	$str=exec($address,$output,$return);
	if($return==0){
		$reply=$output[0];
	}else{
	// on error 
	$err= "comunication error";
	echo "Error:".$err;
	}
	*/
	// comunicate wiht server using zmq_php extension
	//if loading problems. first check instalation. if problems check version compatibility when building zmq_php.
	$context = new ZMQContext();
	// Socket to talk to server
	$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
	$requester->connect($adr);
	$requester->send($cmd);
	$reply = $requester->recv();

	// create response
	echo json_encode(array("id"=>$_POST['id'],"data"=>$reply));


} catch (Exception $e) {
    echo 'Error: ',  $e->getMessage(), "\n";
}
?>
