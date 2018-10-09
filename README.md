Mail parser

Парсит email со страниц указанного сайта на указанную глубину.


Install

Для работы требуется ext-mongodb: ^1.5.0

1. Склонировать проект.
2. Установить библиотеку PHP для MongoDB 

$ composer require mongodb/mongodb

3. Создать в MongoDB базу данных "email_parser"
(коннект к базе сейчас не настраивается, используется стандартный "mongodb://localhost:27017")
