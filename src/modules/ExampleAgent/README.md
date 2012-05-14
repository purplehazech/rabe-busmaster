Example Agent
=============

This is the canonical example module. It aims to contain a bit of everything except business logic. Think of it as an empty shell that you can inhabit with your own code.

The available features are usually configured in services.xml to quite some degree. Please take into account that services.xml only gets read at build time and is not available on production.

This module contains the following features.

Daemon
------

The file Daemon.php contains a simple standalone daemon that does nothing much. If you really want to use the deamon infrastructure you will need to add some zmq magic dust to services.xml and inject some sockets in __constuct().
