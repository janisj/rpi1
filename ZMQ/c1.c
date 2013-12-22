// Hello World client
#include "dbg.h" // some custom debug macros
#include "zhelpers.h"
#include <czmq.h>
#include <string.h>
#include <stdio.h>
#include <unistd.h>

int main (int argc, char *argv[] )
{
	check(argc>=3,"Number of arguments should be >2:command server message.For example ./c1 tcp://localhost:5555 msg\n");
	
	void *context = zmq_ctx_new ();
	void *requester = zmq_socket (context, ZMQ_REQ);
	check (requester,"Creating socekt");
//	zmq_connect (requester, "tcp://localhost:5555");
	int rc=zmq_connect (requester, argv[1]);
	check(rc==0,"Conecting.");

//	zmq_send (requester, *argv(2), length(*argv(2)), 0);
	char *request=argv[2];
	rc=s_send(requester, request);//argv[2]);
	check(rc>=0,"Sending msg. Return val=%i",rc);

	char *response = s_recv(requester);
	printf("%s\n",response);
	//zmq_recv (requester, buffer, 10, 0);
	free(response);	
	zmq_close (requester);
	zmq_ctx_destroy (context);

return 0;

error:

return 1;

}

