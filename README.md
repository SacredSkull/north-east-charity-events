# North east charity events
This project makes use of several libraries/frameworks:

PHP:
- Slim, framework
- Propel, ORM (optional)
- Monolog, logging
- Twig, template

Javascript:
- jQuery

HTML/CSS:
- Twitter's Bootstrap *or* Yahoo's Pure

## Starting development
Rather than having to set up a local server, I have included a [Vagrant](http://vagrantup.com) setup which will immediately give you a working server system (that's the theory, anyway). The alternative is setting up your server stack (such as XAMPP or WAMP, for Windows), which would take some time to look into, if you haven't before.

### Starting
To start the virtual server, either run **start.bat** or using a terminal, change directory to this one and run *vagrant up*.

### Shutting down
To properly shutdown the server, either run **stop.bat**. Running this instead of just quitting will launch it far faster, next time! Using a terminal, change directory to this one and run *vagrant suspend* or *vagrant halt* - but suspending will bring it back up again much faster.


### Troubleshooting
If the server doesn't work for some reason, try using the **reset.bat**. This forces the slower full boot for the machine, and therefore will take a bit longer.

## Folder outline
```
/
  Site/

    cache/              // Twig temporary files

    generated-*/        // Propel stuff

    include/            // Resources for a client
      css/                // Contains CSS which is compiled from LESS/SASS or is just raw CSS.
      img/                // Images
      js/                 // Contains js/jquery
      less/       // Contains LESS or SASS - whichever is chosen.

    logs/               // Log files

    routes/             // "Controller" files - contains the code to handle a specific route e.g.,
                            event.php controls /event/3 and user.php controls /user/gary.

    templates/          // "View" files

    composer.json       // Handles PHP dependencies.
    gulpfile.js         // Gulp runs tasks, like updating PHP libraries
    index.php           // Main file

  Vagrantfile         // Virtual machine file
  nginx.conf          // Copied to virtual machine
  start.bat           // Starts server
  stop.bat            // Stops and suspends server
```

### Too long, didn't read
You're probably going to exist mostly in the *site/templates/* and *site/include/less* folders.
Read up on some [Twig](http://twig.sensiolabs.org/doc/templates.html), [Pure](http://purecss.io) and optionally, some [LESS](http://lesscss.org) too.
