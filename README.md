# North east charity events
This project makes use of several libraries/frameworks:

PHP:
- Slim, framework
- Propel, ORM (optional)
- Logger, logging
- Twig, template

Javascript:
- jQuery

HTML/CSS:
- Twitter's Bootstrap *or* Yahoo's Pure

## Starting development
Rather than having to set up a local server (such as XAMPP or WAMP), I have included a [Vagrant](http://vagrantup.com) file which will immediately give you a working server system. The alternative is setting up your server stack, which would take some time to look into, if you haven't before.

To start the virtual server, either run **start.bat** or using a terminal, change directory to this one and run *vagrant up*.

To finish the virtual server, either run **stop.bat** or using a terminal, change directory to this one and run *vagrant suspend* or *vagrant halt* - but suspending will bring it back up again much faster.

## Folder outline
Some of these folders do not currently exist in the current repository state.
```
/
  Site/
    cache/              // Twig temporary files
    generated-*/        // Propel stuff
    include/            // Resources for a client
      css/                // Contains CSS which is compiled from LESS/SASS or is just raw CSS.
      less OR sass/       // Contains LESS or SASS - whichever is chosen.
      js/                 // Contains js/jquery
    /logs               // Log files
    /routes             // "Controller" files - contains the code to handle a specific route e.g., 
                            event.php controls /event/3 and user.php controls /user/gary.
    /templates          // "View" files
    composer.json       // Handles PHP dependencies.
    gulpfile.js         // Gulp runs tasks, like updating PHP libraries
    index.php           // Main file
  Vagrantfile         // Virtual machine file
  nginx.conf          // Copied to virtual machine
  start.bat           // Starts server
  stop.bat            // Stops and suspends server
```
