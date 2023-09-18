# Taskforce
![PHP Version](https://img.shields.io/badge/php-%5E8.0-blue)
![MySQL Version](https://img.shields.io/badge/mysql-latest-orange)
![Yii2 Version](https://img.shields.io/badge/Yii2-%5E2.0.45-83C933)

## О проекте

«TaskForce» — это онлайн площадка для поиска исполнителей на разовые задачи. Сайт функционирует как биржа объявлений, где заказчики — физические лица публикуют задания. Исполнители могут откликаться на эти задания, предлагая свои услуги и стоимость работ. Проект разработан на базе фреймворка Yii2, использует методологию ООП и паттерн MVC. Также используется внешний API Яндекс.Карт и Вконтакте.

## Функциональность

Основные возможности, реализованные в проекте:

- Регистрация на сайте;
- Авторизация;
- Регистрация и авторизация через социальную сеть VK;
- Профиль пользователя, отображаемый на отдельной странице:
  - аватар,
  - город,
  - возраст,
  - информация о пользователе,
  - блок с контактами,
  - специализации (для исполнителей),
  - отзывы заказчиков (для исполнителей),
  - статистика выполненных заказов (для исполнителей),
  - дата регистрации (для исполнителей),
  - место в общем рейтинге (для исполнителей),
  - статус (для исполнителей);
- Редактирование профиля пользователя:
  - аватар,
  - электронная почта,
  - день рождения,
  - номер телефона,
  - telegram,
  - информация о себе,
  - пароль (с подтверждением старого);
- Вывод списка заданий с пагинацией (каждый пользователь видит только задания из своего города, а также задания в формате удаленной работы);
- Фильтрация заданий по категориям, времени размещения, наличию откликов и формату работы (удаленная работа или обычная);
- Вывод карточки задания;
- Статусы заданий (в зависимости от того, есть ли на задание отклики, находится ли задание в работе, выполнено оно или провалено);
- Отображение локации задания на Яндекс.Карте через внешний API;
- Возможность оставить отклик на задание (для исполнителей);
- Просмотр откликов на задания (для заказчиков);
- Раздел “Мои задания” для заказчиков:
  - список заданий, находящихся в процессе,
  - список просроченных заданий,
  - список завершенных заданий;
- Раздел “Мои задания” для исполнителей:
  - список новых заданий, по которым ещё не выбран исполнитель,
  - список заданий, над которыми работают исполнители,
  - список завершенных заданий;
- Страница с формой добавления нового задания (для заказчиков), включающая в себя следующие поля:
  - заголовок,
  - описание задания,
  - категория задания,
  - локация,
  - бюджет,
  - срок исполнения,
  - файлы задания;
- Размещение откликов на задания (для исполнителей);
- Выбор исполнителя на задание (для заказчиков);
- Размещение отзывов на исполнителей (для заказчиков);
- Система рейтинга исполнителей;
- Валидация всех форм;
- Возврат страницы с ошибкой 404, если пользователь пытается открыть страницу с несуществующим пользователем или заданием.

## Обзор проекта

[![Видео](web/img/cover-taskforce.png)](https://youtu.be/mDWVi3cPgNI)

## Начало работы

Чтобы развернуть проект локально или на хостинге, выполните последовательно несколько действий:

1. Клонируйте репозиторий:

```bash
git clone git@github.com:kiipod/1622797-task-force-4.git taskforce
```

2. Перейдите в директорию проекта:

```bash
cd taskforce
```

3. Установите зависимости, выполнив команду:

```bash
composer install
```

4. Настройте веб-сервер таким образом, чтобы корневая директория указывала на папку web внутри проекта. Например, в случае с размещением проекта в директории `public_html` это можно сделать с помощью команды:

```bash
ln -s web public_html
```

5. Создайте базу данных для проекта, используя схему из файла `schema.sql`:

```sql
CREATE DATABASE taskforce 
       CHARACTER SET utf8 
       COLLATE utf8_general_ci;
       
USE taskforce;

/* Таблица городов */
CREATE TABLE cities (
    id int AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    latitude DECIMAL(11, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL
);

/* Таблица категорий заданий */
CREATE TABLE categories (
    id int AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(128) NOT NULL,
    icon VARCHAR (20)
);

/* Таблица пользователей */
CREATE TABLE users (
    id int AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(64) NOT NULL,
    city_id int NOT NULL,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    rating TINYINT DEFAULT 0,
    grade FLOAT DEFAULT 0,
    avatar VARCHAR(255) NULL,
    birthday DATETIME,
    phone VARCHAR(32),
    telegram VARCHAR(64),
    bio TEXT,
    status TINYTEXT,
    is_executor BOOLEAN NOT NULL,
    show_contacts BOOLEAN NOT NULL,
    vk_id int
    FOREIGN KEY  (city_id) REFERENCES cities (id)
);

/* Таблца связывающая исполнителя и категории */
CREATE TABLE executorCategory (
    category_id int NOT NULL,
    user_id int NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (category_id) REFERENCES categories (id)
);

/* Таблица задач */
CREATE TABLE tasks (
    id int AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    city_id int,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    category_id int NOT NULL,
    customer_id int NOT NULL,
    executor_id int,
    status TINYTEXT DEFAULT new,
    budget int,
    period_execution DATE,
    FOREIGN KEY (customer_id) REFERENCES users (id),
    FOREIGN KEY (executor_id) REFERENCES users (id),
    FOREIGN KEY (category_id) REFERENCES categories (id),
    FOREIGN KEY (city_id) REFERENCES cities (id)
);

/* Таблица файлов */
CREATE TABLE files (
    id int AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(255) NOT NULL UNIQUE
);

/* Таблица файлов задач */
CREATE TABLE tasksFiles (
    id int AUTO_INCREMENT PRIMARY KEY,
    task_id int NOT NULL,
    file_id int NOT NULL,
    FOREIGN KEY (task_id) REFERENCES tasks (id),
    FOREIGN KEY (file_id) REFERENCES files (id)
);

/* Таблица отзывов о исполнителях */
CREATE TABLE feedback (
    id int AUTO_INCREMENT PRIMARY KEY,
    customer_id int NOT NULL,
    executor_id int NOT NULL,
    task_id int NOT NULL,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    description TEXT NOT NULL,
    grade TINYINT NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES users (id),
    FOREIGN KEY (executor_id) REFERENCES users (id),
    FOREIGN KEY (task_id) REFERENCES tasks (id)
);

/* Таблица с откликами исполнителей */
CREATE TABLE offers (
    id int AUTO_INCREMENT PRIMARY KEY,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    task_id int NOT NULL,
    executor_id int NOT NULL,
    price int,
    comment TEXT,
    refuse BOOLEAN NOT NULL,
    FOREIGN KEY (executor_id) REFERENCES users (id),
    FOREIGN KEY (task_id) REFERENCES tasks (id)
);
```

6. Заполните базу данных тестовыми данными, запустив команду:
```
php yii fixture/load <ModelName>
```
После этого вы должны увидеть в таблице `ModelName` тестовые записи, каждая со случайными значениями.

7. Настройте подключение к базе данных в файле `config\db.php`, указав в нем параметры своего окружения. Например, это может выглядеть так:

```php
<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=taskforce',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',
];
```

## Техническое задание

[Посмотреть техническое задание проекта](tz.md)
