Direcotries:

wwwrpi		- web server implementation. Should be linked/copied in httdocs.
zmq		- zmq implementations.


Files: wwwrpi/

index.html	- main web page.
click.php	- ajax requests from index. html  using POST.
lib.php		- custom php functions.
configure.json	- maps index.html objects and actions to ZMQ client actions, addresses and so on. Used by clikc.php.
c1		- ZMQ client called form click.php.
Read documentation in click.php


Instalation:
	1. Apache2 web server+ php5 , contianing wwrpi dir. 
	2. Change address in index.html and click.php to coresponding webserver and directories.



