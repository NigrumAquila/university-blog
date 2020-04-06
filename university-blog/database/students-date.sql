-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 06 2020 г., 07:56
-- Версия сервера: 10.1.37-MariaDB
-- Версия PHP: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `students-date`
--
CREATE DATABASE IF NOT EXISTS `students-date` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `students-date`;

-- --------------------------------------------------------

--
-- Структура таблицы `exam_marks`
--

CREATE TABLE IF NOT EXISTS `exam_marks` (
  `exam_mark_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_subj` smallint(6) DEFAULT NULL,
  `student` smallint(6) DEFAULT NULL,
  `mark` tinyint(4) DEFAULT NULL,
  `date_exam` date DEFAULT NULL,
  PRIMARY KEY (`exam_mark_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `exam_marks`
--

INSERT INTO `exam_marks` (`exam_mark_id`, `group_subj`, `student`, `mark`, `date_exam`) VALUES
(1, 1, 1, 4, '2019-11-01'),
(2, 1, 2, 5, '2019-11-01'),
(3, 1, 3, 4, '2019-11-02'),
(4, 2, 1, 5, '2019-11-06'),
(5, 2, 2, 4, '2019-11-13'),
(6, 2, 3, 4, '2019-11-01'),
(7, 3, 1, 1, '2019-11-02'),
(8, 3, 2, 1, '2019-11-01'),
(9, 3, 3, 1, '2019-11-02'),
(10, 4, 1, 5, '2019-11-06'),
(11, 4, 2, 5, '2013-11-01'),
(12, 4, 3, 5, '2019-11-01'),
(13, 5, 1, 0, '2019-11-01'),
(14, 5, 2, 1, '2019-11-07'),
(15, 5, 3, 0, '2019-11-06'),
(16, 6, 4, 4, '2019-11-20'),
(17, 6, 5, 5, '2019-11-02'),
(18, 6, 6, 2, '2019-11-07'),
(19, 6, 7, 5, '2019-11-02'),
(20, 7, 4, 5, '2019-11-04'),
(21, 7, 5, 2, '2013-11-11'),
(22, 7, 6, 5, '2019-11-01'),
(23, 7, 7, 2, '2019-11-13'),
(24, 8, 4, 1, '2019-11-01'),
(25, 8, 5, 1, '2019-11-05');

-- --------------------------------------------------------

--
-- Структура таблицы `facultys`
--

CREATE TABLE IF NOT EXISTS `facultys` (
  `fac_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `fac_abbrev` char(10) DEFAULT NULL,
  `fac_name` char(50) DEFAULT NULL,
  PRIMARY KEY (`fac_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `facultys`
--

INSERT INTO `facultys` (`fac_id`, `fac_abbrev`, `fac_name`) VALUES
(1, 'И', 'Информатика'),
(2, 'ИС', 'Информационные системы'),
(3, 'УЭР', 'Управление эксплуатационной работой'),
(4, 'ТиПМ', 'Теоретическая и прикладная механика');

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `group_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `group_name` char(10) DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`group_id`, `group_name`) VALUES
(1, 'ИС-09-01'),
(2, 'ИС-09-02'),
(3, 'ИС-10-01'),
(4, 'ИС-10-02'),
(5, 'ПО-10-01'),
(6, 'ПО-10-02');

-- --------------------------------------------------------

--
-- Структура таблицы `groups_subjects`
--

CREATE TABLE IF NOT EXISTS `groups_subjects` (
  `gr_sub_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `group_name` smallint(6) DEFAULT NULL,
  `subject` smallint(6) DEFAULT NULL,
  `lecturer` smallint(6) DEFAULT NULL,
  `exam_test` enum('экзамен','зачет') DEFAULT NULL,
  PRIMARY KEY (`gr_sub_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `groups_subjects`
--

INSERT INTO `groups_subjects` (`gr_sub_id`, `group_name`, `subject`, `lecturer`, `exam_test`) VALUES
(1, 1, 7, 6, 'экзамен'),
(2, 1, 1, 5, 'экзамен'),
(3, 1, 4, 12, 'зачет'),
(4, 1, 8, 11, 'экзамен'),
(5, 1, 5, 13, 'зачет'),
(6, 2, 3, 4, 'экзамен'),
(7, 2, 2, 3, 'экзамен'),
(8, 2, 6, 9, 'зачет'),
(9, 3, 2, 3, 'экзамен'),
(10, 3, 4, 8, 'зачет'),
(11, 3, 6, 9, 'экзамен'),
(12, 4, 1, 5, 'экзамен'),
(13, 4, 4, 8, 'зачет'),
(14, 5, 8, 10, 'экзамен'),
(15, 5, 7, 1, 'зачет'),
(16, 6, 7, 1, 'экзамен'),
(17, 6, 4, 12, 'зачет');

-- --------------------------------------------------------

--
-- Структура таблицы `lecturers`
--

CREATE TABLE IF NOT EXISTS `lecturers` (
  `lec_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `surname` char(20) DEFAULT NULL,
  `name` char(15) DEFAULT NULL,
  `patronymic` char(20) DEFAULT NULL,
  `post` tinyint(4) DEFAULT NULL,
  `faculty` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`lec_id`),
  KEY `post` (`post`),
  KEY `faculty` (`faculty`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `lecturers`
--

INSERT INTO `lecturers` (`lec_id`, `surname`, `name`, `patronymic`, `post`, `faculty`) VALUES
(1, 'Петров', 'Иван', 'Сергеевич', 2, 1),
(2, 'Сергеев', 'Игорь', 'Павлович', 1, 4),
(3, 'Антонова', 'Татьяна', 'Сергеевна', 3, 2),
(4, 'Иванов', 'Сергей', 'Васильевич', 4, 1),
(5, 'Климова', 'Ольга', 'Владимировна', 1, 3),
(6, 'Карелин', 'Андрей', 'Михайлович', 2, 1),
(7, 'Федоров', 'Виктор', 'Федорович', 2, 4),
(8, 'Степанов', 'Илья', 'Иванович', 5, 2),
(9, 'Тимофеев', 'Иван', 'Сергеевич', 3, 3),
(10, 'Симонов', 'Иван', 'Сергеевич', 4, 4),
(11, 'Рощина', 'Татьяна', 'Сергеевна', 2, 4),
(12, 'Вербицкая', 'Елена', 'Петровна', 2, 1),
(13, 'Решетник', 'Татьяна', 'Сергеевна', 2, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `marks`
--

CREATE TABLE IF NOT EXISTS `marks` (
  `mark_id` tinyint(4) NOT NULL,
  `mark` char(20) DEFAULT NULL,
  PRIMARY KEY (`mark_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `marks`
--

INSERT INTO `marks` (`mark_id`, `mark`) VALUES
(0, 'не зачтено'),
(1, 'зачтено'),
(2, 'неудовлетворительно'),
(3, 'удовлетворительно'),
(4, 'хорошо'),
(5, 'отлично');

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` tinyint(4) NOT NULL,
  `post` char(25) DEFAULT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`post_id`, `post`) VALUES
(1, 'ассистент'),
(2, 'доцент'),
(3, 'преподаватель'),
(4, 'профессор'),
(5, 'старший преподаватель'),
(6, 'заведующий кафедрой');

-- --------------------------------------------------------

--
-- Структура таблицы `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `stud_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `group_name` smallint(6) NOT NULL,
  `number` char(9) NOT NULL,
  `surname` char(15) DEFAULT NULL,
  `name` char(10) DEFAULT NULL,
  `patronymic` char(15) DEFAULT NULL,
  `gender` enum('м','ж') DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  PRIMARY KEY (`stud_id`),
  UNIQUE KEY `number` (`number`) USING BTREE,
  KEY `group` (`group_name`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `students`
--

INSERT INTO `students` (`stud_id`, `group_name`, `number`, `surname`, `name`, `patronymic`, `gender`, `birthday`) VALUES
(1, 1, '091110001', 'Бачин', 'Иван', 'Сергеевич', 'м', '1992-03-10'),
(2, 1, '091110002', ' Ляповка', 'Игорь', 'Павлович', 'м', '1992-12-21'),
(3, 1, '091110003', 'Разумова', 'Татьяна', 'Сергеевна', 'ж', '1993-01-01'),
(4, 2, '091110021', 'Кустов', 'Сергей', 'Васильевич', 'м', '1993-02-09'),
(5, 2, '091110022', 'Климова', 'Ольга', 'Владимировна', 'ж', '1993-01-04'),
(6, 2, '091110023', 'Корнеев', 'Андрей', 'Михайловича', 'м', '1992-08-13'),
(7, 2, '091110024', 'Степанов', 'Илья', 'Иванович', 'м', '1992-01-23'),
(8, 3, '101110011', 'Тимофеев', 'Иван', 'Сергеевич', 'м', '1993-06-16'),
(9, 3, '101110012', 'Тропинин', 'Эдуард', 'Александрович', 'м', '1992-11-25'),
(10, 4, '101110021', 'Рязанова', 'Светлана', 'Олеговна', 'ж', '1993-02-28'),
(11, 4, '101110025', 'Верба', 'Елена', 'Петровна', 'ж', '1992-09-18'),
(12, 5, '102110010', 'Симаков', 'Федор', 'Иванович', 'м', '1993-01-20'),
(13, 5, '102110001', 'Углов', 'Никита', 'Алексеевич', 'м', '1993-01-09'),
(14, 6, '102110025', 'Углов', 'Никита', 'Алексеевич', 'м', '1993-01-09'),
(15, 6, '102110029', 'Сибиряков', 'Андрей', 'Денисович', 'м', '1993-04-12'),
(16, 6, '102110027', 'Марченко', 'Валентина', 'Михайловна', 'ж', '1993-05-06');

-- --------------------------------------------------------

--
-- Структура таблицы `subjects`
--

CREATE TABLE IF NOT EXISTS `subjects` (
  `subj_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `subj_name` char(50) DEFAULT NULL,
  `hour` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`subj_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `subjects`
--

INSERT INTO `subjects` (`subj_id`, `subj_name`, `hour`) VALUES
(1, 'математика', 56),
(2, 'информатика', 94),
(3, 'физика', 120),
(4, 'история', 72),
(5, 'ЭВМ', 36),
(6, 'английский', 82),
(7, 'базы данных', 110),
(8, 'компьютерные сети', 96);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `rights` varchar(1) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `np` (`name`,`password`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `name`, `password`, `rights`) VALUES
(1, 'admin', 'admin', 'a'),
(4, 'manager', 'manager', 'm');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
