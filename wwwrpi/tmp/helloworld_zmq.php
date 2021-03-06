

<?php
/*
* Hello World client
* Connects REQ socket to tcp://localhost:5555
* Sends "Hello" to server, expects "World" back
* @author Ian Barber <ian(dot)barber(at)gmail(dot)com>
*/
//dl('/usr/lib/php5/20090626+lfs/zmq.so');
$context = new ZMQContext();

// Socket to talk to server
echo "Connecting to hello world server…\n";
$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
$requester->connect("tcp://localhost:5556");

for ($request_nbr = 0; $request_nbr != 10; $request_nbr++) {
printf ("Sending request %d…\n", $request_nbr);
$requester->send("ledon");

$reply = $requester->recv();
printf ("Received reply %d: [%s]\n", $request_nbr, $reply);
}

?>
