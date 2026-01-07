# CineExplore

#### CineExplore – Explore the World of Cinema

Installation
-------------

PHP8+ is required

**Get repository**

```
$ git clone https://github.com/rafenocontact/CineExplore.git
$ cd CineExplore
```

**Run docker**

```
$ docker-compose up -d
```

Install dependencies
----------------------------

```
$ docker-compose exec php composer install
$ docker-compose exec php npm install
$ docker-compose exec php npm run dev
```

**If you're not using docker**

```
$ composer install
$ npm install
$ npm run dev
$ php -S localhost:8009 -t public
```

**Link to open application:** http://localhost:8009

Test
----------------------------
**Launch unit test**

```
$ docker-compose exec php ./vendor/bin/phpunit
```
