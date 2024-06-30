[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-24ddc0f5d75046c5622901739e7c5dd533143b0c8e959d652212380cedb1ea36.svg)](https://classroom.github.com/a/RzG31vzy)
# ITVB23OWS Development Pipelines starter code

This repository contains starter code for the course ITVB23OWS Development pipelines,
which is part of the HBO-ICT Software Engineering program at Hanze University of
Applied Sciences in Groningen.

This is a deliberately poor software project, containing bugs and missing features. It
is not intended as a demonstration of proper software engineering techniques.

The application contains PHP 8.3 code and should run using the built-in PHP server,
which can be started using the following command.

```
php -S localhost:8000 -t public/
```
############################################################

Om PHP unit te starten:
```
./vendor/bin/phpunit
```

om de docker containers te starten:
```
docker-compose up -d
```
De undo werkt maar de eerste keer moet je twee keer op de knop drukken. 

De AI is wel werkend voor wit maar je moet nog wel eerst een zet doen voor wit om hem op gang te helpen.
Dus eerst een willekeurige zet op wit, dan 1 op zwart (die jij daarna speelt). Hierna kan je met AI move de zetten van wit genereren.

############################################################

This application is licensed under the MIT license, see `LICENSE.md`. Questions
and comments can be directed to
[Ralf van den Broek](https://github.com/ralfvandenbroek).
