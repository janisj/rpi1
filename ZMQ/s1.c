// Hello World server

#include "dbg.h" // some custom debug macros
#include "zhelpers.h"
#include <czmq.h>
#include <stdio.h>
#include <unistd.h>
#include <string.h>
#include <assert.h>


char* take_action(char *msg){
// takes action depending on received message
// the implementation of the server side logic goes here
	char *resp;
	if (~strcmp(msg,"ledon")){
	 	resp="ledon";
	}
	else{
		resp="ok2";
	}

return strdup(resp);
error:
return NULL;
}



int main (int argc, char *argv[] )
{
check(argc==2,"Command line arguments are: executable portnumber. For example: ./s1 5555 ");
void *context = zmq_ctx_new ();
void *responder = zmq_socket (context, ZMQ_REP);
check(responder, "Creating socket");
printf("Starting server on socekt %s\n",argv[1]);

char adr [256];
snprintf(adr,256,"%s%s","tcp://*:",argv[1]);

int rc = zmq_bind (responder,adr);
assert (rc == 0);
printf("Server started...\n");

	while (1) {
		char *msg=s_recv(responder);
		//q_recv (responder, buffer, 10, 0);
		printf ("%s\n",msg);

		char *response=take_action(msg);
		check(response,"Processing message.");

		rc=s_send(responder,response);//argv[2]);
		check(rc>=0,"Sending msg. Return val=%i",rc);

		free(response);
		free(msg);
	}

return 0;
error:
	return 1;
}


