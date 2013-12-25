Direcotries:

wwwrpi		- web server implementation. Should be linked/copied in httdocs.
zmq		- zmq implementations.


Files: wwwrpi/

index.html	- main web page.
click.php	- ajax requests from index. html  using POST.
lib.php		- custom php functions.
configure.json	- descripbes how to map index.html objects and actions to ZMQ client actions, addresses and so on. Used by click.php.
c1		- ZMQ client called from click.php.
Read documentation in click.php


Instalation:
	1. Apache2 web server+ php5 >5.5 (opcache, zmq_php). Copy wwwrpi to htdocs.
	2. Change address in index.html and click.php to coresponding webserver and directories.
	3. Run servers ZMQ/s1


