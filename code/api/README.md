# Filmaffin API

API based on Filmaffinity.com data

## Installation

Rename `.env.dist` to `.env`

Run:
```
composer install
```

## Run tests
```
./bin/phpunit
```

## Index all films
Index all films in Elasticsearch reading from DB executing
```
bin/console filmaffin:films:index --env=prod --no-debug
```

## Index frequently updated films
Index films that are frequently updated (popular, in theatres...)
```
bin/console filmaffin:films:index:frequently-updated --env=prod --no-debug
```

## Check the docs
Run:
```
symfony server:start
```

And go to
```
http://localhost:8000/api/doc
```

## TODO

* Add database structure
* Add example data
