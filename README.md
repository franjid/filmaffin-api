# Filmaffin API

API based on Filmaffinity.com data

1) Copy `.env.dist` to `.env`
2) Copy `code/api/.env.dist` to `code/api/.env`
3) Run:
    ```
    docker-compose up
    ```

## Index films

```
docker-compose exec php bin/console filmaffin:index:films
```

## Index frequently updated films
```
docker-compose exec php bin/console filmaffin:index:films:frequently_updated
```

## Check the docs

```
http://localhost/api/doc
```

## More info

Refer to the [API docs](./code/api/README.md)
