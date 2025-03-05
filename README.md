## Getting Started

### Notes
- I have used Docker since I encountered some issues when trying to run the application locally.
- I had to modify the `host` config.json  from `127.0.0.1` to `0.0.0.0` to be able to expose the application to the host machine.
- I added `tokenWriteOnly` token to `TokenDataProvider` to test the case where a token exist but doesn't have read permission.
- If you have Docker Compose installed you can run the application by following the new instructions below.
- Alternatively, you can still run the application locally by following the original instructions.

### Installing

Execute the following command to run the application:

``` bash
docker compose up --build
```

After successful start, you should be able to access the application here http://localhost:1337

### Tests

To run tests, use the following command:

``` bash
docker compose exec app php vendor/bin/phpunit Test
```


## Below is the original README.md file from the task


# ❗ Please do not fork this repository ❗

# Yoummday Refactoring Task
This project only includes the route `GET /has_permission/{token}` which has to decide if the provided token exists and has the required permission.
Your task is to refactor the endpoint and create tests, if necessary.

# Requirements
- php 8.1
- composer

# Installation
```shell
$ composer install
```

# Run
```shell 
$ php src/main.php
```
Expected output: 
```shell
[INFO] Registering GET /has_permission/{token}
[INFO] Server running on 127.0.0.1:1337
```

# Testing
```shell
$ php vendor/bin/phpunit Test
```
