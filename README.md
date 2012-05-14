RaBe Busmaster
==============

PHP based middleware for rabe. As of now this has the following features.

- cloud based development on our private jenkins instance
- lots of automated build feature so you don't need to install the toolchain on a dev machine
- start-stop-daemon based php daemon infrastructure
- fully automated deployment based on puppet-infra-project and rabe-portage-overlay
- a sweet dependency injection container and basic libs (ie. the observer pattern)
- zeromq libraries for message based coding

In total this is configured as an enviroment for creating simple daemons. I have used this to implement open sound control (OSC) receiving capabilities. This currently logs all mreceived messages.

Next up are the following tasks.

- publish documentation on this (mostly the in code docs should be made available and extended very much)
- dbal and orm mapper (doctrine integration plus a defined roundtrippable workflow based on mysql workbench)
- add service feature to modules (devs need to be able to define Service.php in a module to create dojo compatible webservices using the mentioned orm tools)
- the service feature should also start supporting soap at a later stage, this needs to be taken into account early

Based on all that rabe-busmaster aims to create an enviroment where that we can use to continually develop and deploy new features to rabe,

For adding value during integration of the next features the plan is to implement a music search engine and the next generation of rabes songticker.

