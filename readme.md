# Client XXXX

XXXX XXXX XXXX

## Tecnology

- PHP 7.2
- Framework PHP Laravel 5.8
- POSTGRESQL database
- HTML5, CSS3, Javascript
- AngularJS
- VueJs

## Important work

- First project working alone as a backend
- Fist time using an OSSAPI - app/Helpers/OSSAPI 
  - The controllers that most use this is in app/Http/Controller/Clinte
- First time making double authentication - Web and Admin

### Required dependencies

- PHP >= 7.2
- OpenSSL PHP Extension
- PDO PHP Extension
- MbString PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- PostgreSQL PHP Extension
- Apache Server
- PGSQL Server

### Installation Instructions

```
git clone https://<user>@repository.git

cp .env.example .env

Edit database configuration, Smtp configuration and etc.

composer install

php artisan key:generate

php artisan cache:clear

```

### Database

```
php artisan migrate
php artisan db:seed
```


## Deploy on Production

### Admin
Change the urls inpublic/gerenciador/app.environment.js

```
gulp build || gulp watch
```

### Web

```
npm run prod
```

## Authors

* **Alberto de Almeida Guilherme - Backend Programmer** 