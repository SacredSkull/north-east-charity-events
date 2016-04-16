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
Rather than having to set up a local server, I have included a [Docker](http://docker.com) setup which will immediately give you a working server system (that's the theory, anyway). The alternative is setting up your server stack (such as XAMPP or WAMP, for Windows), which would take some time to look into, if you haven't before.
### IMPORTANT
You must clone/store this **IN YOUR USERS FOLDER** - e.g. *C:/Users/lazylewis/* - **otherwise the server WILL NOT WORK**.

### Starting
To start the server, run **start.bat**. You will be given an IP address like (probably exactly like) `192.168.99.100:8080` - this is how you access the server (http://192.168.99.100:8080/). If you want to make it more convenient, add `<your_given_IP_address_WITHOUT_:8080> docker.dev` to your `hosts` file (google it) - and you can access it via http://docker.dev:8080/

### Shutting down
To properly shutdown the server, either run **stop.bat**.

### Adding Data/Viewing
if this a freshly cloned repo, you need to first run `upgrade.bat`. 
You now have a full working web stack - but no data.  To fill it with fake data, go to `http://<your_ip_address>:8080/data`. If an error shows, run `upgrade.bat` again or try recloning. Otherwise it'll give you some times taken to add the data. `http://<your_ip_address>:8080/` should now have events on the front page.

### Updating
If a new change includes modifications to the database configuration or the project's dependencies, they will need to be applied. Running **upgrade.bat** will do this.

### Troubleshooting
Try running `git config --global core.autocrlf input`, deleting the repo (ASSUMING YOU HAVEN'T MADE ANY CHANGES, OBVIOUSLY) and recloning `git clone https://github.com/SacredSkull/north-east-charity-events.git`

### Important folders
src/NorthEastEvents/public <-- CSS, JS, Images, etc..
src/NorthEastEvents/templates <-- templated twig html files

### Tips for templating

[This documentation is your friend.](http://twig.sensiolabs.org/doc/templates.html)

| URL                	| Resource                                                	| Template name             	| Available variables         	| Example use                                                                                          	| Notes                                                                                              	|
|--------------------	|---------------------------------------------------------	|---------------------------	|-----------------------------	|------------------------------------------------------------------------------------------------------	|----------------------------------------------------------------------------------------------------	|
| /                  	| Homepage                                                	| home.html.twig            	| events, current_user        	| {% for event in events %}{{ event.getTitle }}{% endfor %}                                            	| Featured events on this page.                                                                      	|
| /me                	| (Your) User Account page                                	| /users/me.html.twig       	| current_user                	| {{ current_user.getUsername }}                                                                       	| All public/private info is shown (except for password)                                             	|
| /register          	| Register user (form)                                    	| /users/register.html.twig 	| -                           	| -                                                                                                    	| -                                                                                                  	|
| /users             	| Show (paginated) list of users                          	| /users/users.html.twig    	| users, current_user         	| `{% for user in users %}{{ user.getUsername }}{% endfor %}` -- more details coming about pagination  	| Paginated, and uses a special format                                                               	|
| /user/4            	| Show PUBLIC details of user ID 4                        	| /users/user.html.twig     	| user, current_user          	| `{{ user.Username }}` <-- NOTE THE DIFFERENCE vs user.getUsername                                    	| To prevent information leak, this page as a special user format (see left)                         	|
| /events            	| Show (paginated) list of events                         	| /events/events.html.twig  	| events, current_user        	| `{% for event in events  %}{{ event.getTitle }}{% endfor %}` -- more details coming about pagination 	| Paginated, and uses a special format                                                               	|
| /event/69          	| Show details of event ID 69 (including list of threads) 	| /events/event.html.twig   	| event, current_user         	| `{{ event.getTitle }}  {% for thread in event.getThreads %}{{ thread.getTitle }}{% endfor %}`        	| -                                                                                                  	|
| /event/69/thread/2 	| Show comments in thread #2 from event ID 69             	| /events/thread.html.twig  	| event, current_user, thread 	| `{{ thread.getTitle }} {% for comment in thread.getComments %}{{ comment.getBodyHTML}}{% endfor %}`  	| Either use `event` or `thread.getEvent` to show event information.                                 	|
| /event/create      	| Create a new event page (form)                          	| /events/create.html.twig  	| current_user                	| -                                                                                                    	| Don't worry about whether or not the User is permitted to do this - that's handled in the backend. 	|

## Too long, didn't read

### How to use
You're probably going to exist mostly in the *src/templates/\** and *src/include/less/\** folders.
- Start with *start.bat*, end with *stop.bat*.
- To view the site you go to http://localhost:8080  (for testing)
- You may be told to occasionally run *upgrade.bat*.
- If you have trouble, run *reset.bat*.
- After you commit, <del>Jenkins</del> Phil will load your new changes and give them a spin to see if they work - results on Slack!

## Stuff you need to know
### Frontend
Read up on some [Twig](http://twig.sensiolabs.org/doc/templates.html), [Bootstrap](http://getbootstrap.com/) and optionally, some [LESS](http://lesscss.org) too. Javascript is also optional, if you're completely new, I'd recommend diving straight into [jQuery](http://jquery.com/)

### Backend
Some PHP
