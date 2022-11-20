### Application execution

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/)
2. Run `docker-compose build --pull --no-cache` to build fresh images
3. Run `docker-compose up` (the logs will be displayed in the current shell)
4. Open `https://localhost:82` in your favorite web browser
5. Run `docker-compose down --remove-orphans` to stop the Docker containers.

## CONTAINER 1

project folder
```scala
/var/www/html
```

install packages in both containers
```scala
composer install
```

run migrations
```scala
php bin/console doctrine:migrations:migrate
```

test url to get user
```scala
GET http://localhost:82/user/1
```
add user
```scala
POST http://localhost:82/users
{
    "email" : "1asd@abv.bg",
    "firstName" : "first name",
    "lastName" : "Last name"
}
```

## CONTAINER 2

project folder
```scala
/var/www/html
```

run command to consume messages
```phpt
php bin/console messenger:consume -vv external_messages
```

Logs are written in 
```scala
var/log/dev.log
```

Run tests
```scala
php bin/phpunit
```

Run phpunit coverage
```scala
php bin/phpunit --coverage-html ./coverage
```
