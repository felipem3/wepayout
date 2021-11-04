## Instalação

```
docker run --rm \
-u "$(id -u):$(id -g)" \
-v $(pwd):/opt \
-w /opt \
laravelsail/php80-composer:latest \
composer install --ignore-platform-reqs
```

`cp ./.env-example .env`

`sail up -d`
### Execute as migrations
`sail artisan migrate`
### Execute a queue
`sail artisan queue:work`
### Execute a schedule
`sail artisan schedule:work`

### Rodando os testes
`sail artisan test`
