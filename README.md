## Configuration

Install dependencies:
```sh
composer install --ignore-platform-reqs
```

Install roadrunner:
```sh
vendor/bin/rr get --location bin/
```

Run Api server in docker (on 13340 port, by default)
```sh
docker-compose up
```

Create MySQL schema by running command in php-container:
```sh
php bin/console doctrine:migration:migrate
```

