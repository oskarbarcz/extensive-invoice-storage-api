# Extensive Invoice Storage

Hello! This is MVP of my new project. Return here later for more content.

## Development

## Code quality

Code quality checker and fixer is available. Run this command in PHP container's shell.

```shell
vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix
```

### Tips & tricks

#### Local user

Some changes in project file may cause user being resetted to non-host one. This will lock possiblity to edit this
files. Use command below **in your host OS** to regain privileges correctly again:

```shell
sh bin/ucup
```

You may be asked for password for current logged in user.
