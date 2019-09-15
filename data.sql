-- Host: localhost   Create Database: recruiment_questions
-- ------------------------------------------------------
DROP DATABASE IF EXISTS recruiment_questions;
CREATE DATABASE recruiment_questions;
USE recruiment_questions;

--
-- Table structure for table `answers`
--

DROP TABLE IF EXISTS `answers`;
CREATE TABLE `answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(5) NOT NULL,
  `to_question` int(11) NOT NULL,
  `answer_text` varchar(5000) NOT NULL,
  `link` varchar(200) DEFAULT NULL,
  `author` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `votes` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `favourites`
--

DROP TABLE IF EXISTS `favourites`;
CREATE TABLE `favourites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `question_number` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8mb4;


--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(5) NOT NULL,
  `category` varchar(50) NOT NULL,
  `title` varchar(300) NOT NULL,
  `answers` int(11) NOT NULL,
  `author` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `votes` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;


--
-- Table structure for table `remind_password`
--

DROP TABLE IF EXISTS `remind_password`;
CREATE TABLE `remind_password` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `token` varchar(30) NOT NULL,
  `date` datetime NOT NULL,
  `time` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;


--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `email` varchar(150) NOT NULL,
  `pass` varchar(300) NOT NULL,
  `site` varchar(200) DEFAULT NULL,
  `date` date NOT NULL,
  `added_questions` int(11) NOT NULL,
  `added_answers` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4;


--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
CREATE TABLE `votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `answer_number` int(11) NOT NULL,
  `up` tinyint(1) NOT NULL,
  `down` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4;
