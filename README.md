#Filmaffin API

API based on Filmaffinity.com data

## Index films
Index films in Elasticsearch reading from DB executing
```
app/console filmaffin:index:films
```

## Check the docs
Run:
```
php app/console server:run
```
And go to
```
http://127.0.0.1:8000/api/doc
```
