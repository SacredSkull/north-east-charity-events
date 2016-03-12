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

### Updating
If a new change includes modifications to the database configuration or the project's dependencies, they will need to be applied. Running **upgrade.bat** will do this. Currently looking into a way to fully automate this process!

### Troubleshooting
If the server doesn't work for some reason, try using the **reset.bat**. This forces the slower full boot for the machine, and therefore will take a bit longer. Upgrades are automatically applied, in case that was the issue.

## Folder outline
```
/
|-- logs/                       // Log files
|   |-- access.nginx.log          // Nginx (web server access - not errors!) logs
|   |-- app.log                   // PHP app log
|   |-- error.nginx.log           // Nginx logs, errors
|   `-- php.error.log             // Contains problems with PHP compilations
|-- src/                          // PHP application folder
|   |-- cache/                    // Twig cache
|   |-- config/                   // Config folder
|   |   |-- propel/                 // Propel config folder
|   |   |   `-- schema.xml            // Database schema - turned into code & tables by Propel
|   |   |-- slim/                   // Slim config
|   |   `-- bootstrap.php           // Bootstrap - holds everything together
|   |-- NorthEastEvents/          // "Models" - classes like Users, Events, Comments, etc. Custom logic is added to the files in this directory.
|   |   |-- Base/                   // Base classes - NOT FOR EDITING!
|   |   |-- Map/                    // Table classes - NOT FOR EDITING
|   |   `-- *.php                   // Custom logic files, edit as necessary
|   |-- public/                   // Stuff that directly faces users/web browsers - images, css and the index file
|   |   |-- include/                // Resources for a client
|   |   |   |-- css/                  // Contains CSS which is compiled from LESS/SASS or is just raw CSS.
|   |   |   |-- img/                  // Images
|   |   |   |-- js/                   // Javascript
|   |   |   `-- less/                 // LESS (CSS preprocesser)
|   |   `-- index.php               // Main file (the magic starts here)
|   |-- routes/                   // "Controller" files - contains the code to handle a specific route - e.g., event.php controls /event/3 and user.php controls /user/gary.
|   |-- templates/                // "Views" - Twig template files
|   |-- vendor/                   // PHP dependencies (managed by Composer) are stored here
|   |-- build.xml                 // ANT build (for testing)
|   |-- composer.json             // PHP composer file (libraries, dependencies, etc.)
|   |-- phpmd.xml                 // PHP MD testing
|   `-- phpunit.xml               // Unit testing config
|-- tests/                      // Test folder
|-- nginx.conf                  // Nginx config
|-- README.md                   // This file
|-- reset.bat                   // Stops server, starts from fresh - uses reset.sh
|-- reset.sh
|-- setup-database.sql          // Database setup script
|-- start.bat                   // Starts server
|-- stop.bat                    // Stops and suspends server (saving memory to file)
|-- upgrade.bat                 // Forces configuration and dependency updates - uses upgrade.sh
|-- upgrade.sh
`-- Vagrantfile                 // Virtual machine configuration file
```

## Too long, didn't read

### How to use
You're probably going to exist mostly in the *src/templates/\** and *src/include/less/\** folders.
- Start with *start.bat*, end with *stop.bat*.
- To view the site you go to http://localhost:8080  (for testing)
- You may be told to occasionally run *upgrade.bat*.
- If you have trouble, run *reset.bat*.
- After you commit, Jenkins will load your new changes and give them a spin to see if they work - results on Slack!

### Stuff you need to know
Read up on some [Twig](http://twig.sensiolabs.org/doc/templates.html), [Pure](http://purecss.io) and optionally, some [LESS](http://lesscss.org) too.
