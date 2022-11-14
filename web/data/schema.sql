CREATE DATABASE taskforce CHARACTER SET utf8 COLLATE utf8_general_ci;
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
  avatar_file_id int NULL,
  birthday DATETIME,
  phone VARCHAR(32),
  telegram VARCHAR(64),
  bio TEXT,
  status TINYTEXT,
  is_executor BOOLEAN NOT NULL,
  show_contacts BOOLEAN NOT NULL,
  vk_id int
  FOREIGN KEY (avatar_file_id) REFERENCES files (id),
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
