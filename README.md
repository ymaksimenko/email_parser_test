Mail parser

Парсит email со страниц указанного сайта на указанную глубину.
Парсер обрабатывает только сырой HTML, возвращенный сервером с запрошенного url. Предподготовки страницы перед парсингом нет. Страницы которые формируются на JavaScript(подгрузка и обработка данных через Ajax запросы), iframe сейчас не обрабатываются.

Для запуска требуется:
- в Apache должен быть включен rewrite_module, и должны обрабатываться инструкции .htaccess размещенных в подкаталогах.
- установленная MongoDB
- драйвер momgo для php (ext-mongodb) версии 1.5.0 или выше


Install
1. Склонировать проект.
2. Установить библиотеку PHP для MongoDB 

$ composer require mongodb/mongodb

3. Создать в MongoDB базу данных "email_parser"
(коннект к базе сейчас не настраивается, используется стандартный "mongodb://localhost:27017")
