-- Student Registration System
-- Database name: php

CREATE DATABASE IF NOT EXISTS `php`;
USE `php`;

-- users table (login + admin)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    address VARCHAR(255) NOT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- default admin account
-- username: admin
-- password: admin123
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@student.com', MD5('admin123'), 'admin');

-- sample students
INSERT INTO students (name, email, phone, address) VALUES
('Ahmad Ali', 'ahmad@example.com', '0912345678', 'Damascus'),
('Sara Omar', 'sara@example.com', '0987654321', 'Aleppo'),
('Mohamed Omar', 'mohamed@example.com', '098473321', 'Latakia'),
('Omar Ahmad', 'omar@example.com', '0987624321', 'Homs'),
('Ahmad Mohamed', 'ahmad2@example.com', '0965335431', 'Tartous');
