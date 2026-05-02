-- ============================================
-- PORTFOLIO DATABASE - COMPLETE SQL SETUP
-- ============================================
-- Run this file in phpMyAdmin to setup your database
-- Author: Ephrem Tadewos
-- Date: May 2025
-- ============================================

-- Drop existing database if exists (optional)
-- DROP DATABASE IF EXISTS portfolio;

-- Create Database
CREATE DATABASE IF NOT EXISTS portfolio;
USE portfolio;

-- ============================================
-- USERS TABLE - Authentication
-- ============================================
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- CONTACT MESSAGES TABLE
-- ============================================
DROP TABLE IF EXISTS contact_messages;

CREATE TABLE contact_messages (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_read TINYINT(1) DEFAULT 0,
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- PROJECTS TABLE - Portfolio Projects
-- ============================================
DROP TABLE IF EXISTS projects;

CREATE TABLE projects (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    technologies VARCHAR(255),
    image_url VARCHAR(255),
    project_url VARCHAR(255),
    category VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert Sample Projects
INSERT INTO projects (title, description, technologies, category, project_url) VALUES
('Electricity Billing System', 'Complete electricity billing and meter reading management system with bill generation and payment tracking', 'Java, MySQL, Swing', 'Java', 'electricity_billing/'),
('E-Commerce System', 'Full-featured online shopping platform with cart, checkout, and admin panel', 'PHP, MySQL, HTML, CSS', 'PHP', 'ecommerce/'),
('Student Management System', 'Student database management system for educational institutions with grades and attendance', 'Java, MySQL, Swing', 'Java', 'student_management/'),
('Library Management System', 'Book inventory, member management, and borrowing/returning system for libraries', 'Java, MySQL, Swing', 'Java', 'library_management/'),
('Hospital Management System', 'Patient records, appointment scheduling, and billing system for healthcare facilities', 'Java, MySQL, Swing', 'Java', 'hospital_management/'),
('Calculator App', 'Modern calculator with scientific functions and history feature', 'HTML, CSS, JavaScript', 'Web', 'calculator/'),
('Contact Management System', 'Contact form with database storage and email integration', 'PHP, MySQL, AJAX', 'PHP', 'contact_system/'),
('Portfolio Website', 'Personal portfolio with authentication, contact form, and admin dashboard', 'PHP, MySQL, CSS3', 'Web', 'index.php');

-- ============================================
-- SKILLS TABLE - Technical Skills
-- ============================================
DROP TABLE IF EXISTS skills;

CREATE TABLE skills (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    skill_name VARCHAR(100) NOT NULL,
    skill_level INT(3) DEFAULT 0,
    category VARCHAR(50),
    icon VARCHAR(50),
    sort_order INT(3) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO skills (skill_name, skill_level, category, icon, sort_order) VALUES
('HTML5', 90, 'Frontend', 'fab fa-html5', 1),
('CSS3', 85, 'Frontend', 'fab fa-css3-alt', 2),
('JavaScript', 70, 'Frontend', 'fab fa-js', 3),
('PHP', 75, 'Backend', 'fab fa-php', 4),
('Java', 65, 'Backend', 'fab fa-java', 5),
('Python', 60, 'Backend', 'fab fa-python', 6),
('MySQL', 80, 'Database', 'fas fa-database', 7),
('Git', 70, 'Tools', 'fab fa-git-alt', 8);

-- ============================================
-- ADMIN SETTINGS TABLE
-- ============================================
DROP TABLE IF EXISTS admin_settings;

CREATE TABLE admin_settings (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) UNIQUE,
    setting_value TEXT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO admin_settings (setting_key, setting_value) VALUES
('site_name', 'Ephrem Tadewos Portfolio'),
('site_title', 'Full Stack Developer | IT Student'),
('email', 'eph.man.tade@gmail.com'),
('phone', '+251-94-895-6850'),
('location', 'Wolaita, Ethiopia');

-- ============================================
-- TEST QUERIES - Verify Setup
-- ============================================

-- Check tables created
SHOW TABLES;

-- Check users table structure
DESCRIBE users;

-- Check projects inserted
SELECT * FROM projects;

-- Check skills inserted
SELECT * FROM skills;

-- ============================================
-- COMMON ISSUES FIX
-- ============================================

-- If you get "Table doesnt exist" error, run these:
-- 1. CREATE DATABASE portfolio;
-- 2. USE portfolio;
-- 3. Run this entire file

-- If you get "Access denied", check:
-- 1. MySQL username is "root"
-- 2. MySQL password is empty ""
-- 3. MySQL service is running in XAMPP

-- ============================================
-- END OF SQL SETUP
-- ============================================
-- Database is ready! Go to http://localhost/proj/setup.php
-- ============================================