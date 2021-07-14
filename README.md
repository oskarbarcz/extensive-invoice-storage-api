# Extensive Invoice Storage

Hello! This is MVP of my new project. Return here later for more content.

## Development
### Dependencies:

* Docker engine v1.13 or higher. Your OS provided package might be a little old, if you encounter problems, do upgrade.
  See [https://docs.docker.com/engine/installation](https://docs.docker.com/engine/installation)
* Docker compose v1.12 or higher. See [docs.docker.com/compose/install](https://docs.docker.com/compose/install/)

### Run project
Once you're done, simply `cd` to your project and run 
```shell
docker-compose up -d
```
This will initialise and start all the 
containers, then leave them running in the background.

### Local environment services
You'll need to configure your application to use any services you enabled:

Service|Type of service|Hostname|Public port number
------|----------|---------|-----------
__PHP FPM__|php instance|php-fpm|_none_
__MySQL__|database|mysql|3306 (_default_)
__Redis__|cache and queue|redis|6379 (_default_)
__Nginx__|web server|redis|80 (_default_)
## Tests
Application has tested all endpoints (check `./tests/Application`) in __end2end__ manner.

Unit tests are written only for code which is modifying framework behavior and only checks the modified part. See the
example what's being tested and what should be tested (eg. `./tests/Unit/Domain/InvoiceTest.php`). Remember: __Code
coverage is not as important as the common sense in writing tests__. Do not write pointless tests for getters, setters,
etc. Keep tests and assertions simple.

You can run app tests with the command below:
```shell
./vendor/bin/phpunit
```

In case of contribution here, please follow the TDD (write a test before an implementation). Thanks in advance!

## Code quality
Code quality checker and fixer is available. Run this command in PHP container's shell.

```shell
./vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix
```
## Tips & Tricks
### Application file permissions
As in all server environments, your application needs the correct file permissions to work proberly. You can change the files throught the container, so you won't care if the user exists or has the same ID on your host.
```shell
docker-compose exec php-fpm chown -R www-data:www-data /application/public
```
