<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Upshift challenge:

This project was made for the technical challenge of the Upshift company. It is a classical REST API providing clients CRUD access for managing their companies and gigs. Authentication is [Laravel Sanctum](https://laravel.com/docs/8.x/sanctum), which came with the default Laravel installation and is providing token authentication for the API.

## Starting the API:
The most out of the box set up can be done via the [Laravel Sail](https://laravel.com/docs/8.x/sail), which uses needed containers for the application, which are defined in this [file](/docker-compose.yml). 
> Laravel Sail uses [Docker engine](https://docs.docker.com/engine/install/) and [Docker-compose](https://docs.docker.com/compose/) for building containers, make sure you have installed them before proceeding.

- If the default configuration of to-be-made containers looks good to you, before you build them, please make sure you have made a copy of a given example [.env](/.env/.example) file and renaming it to .env. The default settings can be left if you haven't changed the previously mentioned docker-compose YAML file.
> Before continuing further, please shut down the processes listening on ports: 80, 3306 and 6379 (if you haven't changed the default ports on containers), so the newly build containers can listen to mentioned ports.
- Firstly, make composer install needed vendor files with:
~~~bash 
    cd /{project-path}/
    composer install
~~~~
- To build containers, you will need the next command:
~~~bash
    cd /{project-path}/
    (sudo) ./vendor/bin/sail up -d
~~~~
> Note: sudo may not be needed \\if you installed the Docker as a [non-root user](https://docs.docker.com/engine/install/linux-postinstall/\#manage-docker-as-a-non-root-user)

> If the containers were successfully built and ran, you can access the app with the url [http://localhost](http://localhost)

- After the containers were built, you need to generate the key \\for the API with:
~~~bash
(sudo) ./vendor/bin/sail artisan key:generate
~~~
- To run migrations \\for your Database, use this \command:
~~~bash
(sudo) ./vendor/bin/sail artisan migrate
~~~
> You can add --seed parameter to the command above, which will populate your DB with a randomly generated data of 10.000 users, with every user having a company or two and every company having 2 to 5 gigs. Note, this can take a long time to execute, use only if necessary.

- If you can access the API home page with your browser, \then the API is up and running.


Have fun and \for more info, please look at the [API documentation](https://documenter.getpostman.com/view/24493270/2s8YmPsMeM).
