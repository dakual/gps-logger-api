```sh
docker run --rm -it -v "$(pwd)/app:/app" composer init
docker run --rm -it -v "$(pwd)/app:/app" composer require slim/slim
```


```sh
curl --location 'http://localhost:8080/api' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer test' \
--data '{
    "device":"12345678",
    "latitude":34.34433,
    "longitude":43.53432
}'
```


```sh
curl --location 'http://localhost:8080/positions' \
--header 'Authorization: Bearer test' \
--form 'device="12345678"' \
--form 'time="1"'
```



