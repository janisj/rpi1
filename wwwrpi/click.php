<?php
include 'lib.php';
define("REQUEST_TIMEOUT", 100);//msec
define("REPEAT_REQUEST",10);// REPEAT_RQUEST*REQUEST_TIMEOUT gives total time to wait for server to respond
// Sends messages via socet programm and passes back the response from the server.
//On error: outputs error message...

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
//	var_dump($arr_conf);
	if (!is_array($arr_conf) || !array_key_exists($id, $arr_conf)){throw new Exception('Json configuration lacking item '.$id);}
	if (!array_key_exists('command',$arr_conf[$id]) || !array_key_exists($data,$arr_conf[$id]['command'])){throw new Exception('Json configuration item:'.$id.' lacking command:'.$data);}
		
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
	// uses poll for non blocking request,
	//if loading problems. first check instalation. if problems check version compatibility when building zmq_php.
	$context = new ZMQContext();
	// Socket to talk to server
	$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
	$requester->setSockOpt(ZMQ::SOCKOPT_LINGER, 0);
	$requester->connect($adr);

	$requester->send($cmd);
	// Poll socket for a reply, with timeout
	$poll = new ZMQPoll();
	$poll->add($requester, ZMQ::POLL_IN);
//	$reply = $requester->recv();


	$read = array();

	for($tmpi=1;$tmpi<REPEAT_REQUEST;$tmpi++){

		$events = $poll->poll($read, $write, REQUEST_TIMEOUT);
		// If we got a reply, process it
		if ($events > 0) {
			// Configure socket to not wait at close time
			// create response
			$reply=$read[0]->recv();
			echo json_encode(array("id"=>$_POST['id'],"data"=>$reply));
			break;
		}
	}
	if ($events==0){
			// no response.
			throw new Exception('No response'); 
		}
	

}catch (Exception $e) {
    //echo 'Error: ',  $e->getMessage(), "\n";
	echo json_encode(array("error"=>$e->getMessage()));
}
?>
