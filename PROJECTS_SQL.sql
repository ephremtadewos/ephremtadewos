-- ============================================
-- COMPLETE PROJECTS DATABASE - ALL IN ONE
-- ============================================
-- Contains: Electricity Billing, Student Management, 
-- Library Management, Hospital Management, E-Commerce
-- ============================================

-- Create main database
CREATE DATABASE IF NOT EXISTS portfolio;
USE portfolio;

-- ============================================
-- 1. PORTFOLIO CORE TABLES
-- ============================================

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS contact_messages;
CREATE TABLE contact_messages (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_read TINYINT(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS projects;
CREATE TABLE projects (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    technologies VARCHAR(255),
    category VARCHAR(50),
    image_url VARCHAR(255),
    demo_link VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- 2. ELECTRICITY BILLING SYSTEM
-- ============================================

CREATE DATABASE IF NOT EXISTS electricity_billing;
USE electricity_billing;

DROP TABLE IF EXISTS customers;
CREATE TABLE customers (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    address VARCHAR(255),
    meter_number VARCHAR(50) UNIQUE NOT NULL,
    connection_type VARCHAR(20) DEFAULT 'residential',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive', 'disconnected') DEFAULT 'active'
);

DROP TABLE IF EXISTS meter_readings;
CREATE TABLE meter_readings (
    reading_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    reading_date DATE NOT NULL,
    previous_reading DECIMAL(10,2),
    current_reading DECIMAL(10,2) NOT NULL,
    units_consumed DECIMAL(10,2),
    recorded_by VARCHAR(100),
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
);

DROP TABLE IF EXISTS bills;
CREATE TABLE bills (
    bill_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    reading_id INT,
    billing_month VARCHAR(20) NOT NULL,
    units_consumed DECIMAL(10,2),
    rate_per_unit DECIMAL(10,4),
    fixed_charge DECIMAL(10,2) DEFAULT 50.00,
    total_amount DECIMAL(10,2),
    due_date DATE,
    status ENUM('pending', 'paid', 'overdue') DEFAULT 'pending',
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
);

DROP TABLE IF EXISTS payments;
CREATE TABLE payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    bill_id INT NOT NULL,
    customer_id INT NOT NULL,
    amount_paid DECIMAL(10,2) NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_method ENUM('cash', 'bank_transfer', 'mobile_money'),
    transaction_id VARCHAR(100),
    FOREIGN KEY (bill_id) REFERENCES bills(bill_id)
);

DROP TABLE IF EXISTS rate_structure;
CREATE TABLE rate_structure (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tier_name VARCHAR(50),
    min_units INT,
    max_units INT,
    rate_per_unit DECIMAL(10,4),
    effective_from DATE
);

INSERT INTO rate_structure (tier_name, min_units, max_units, rate_per_unit) VALUES
('Tier 1', 0, 50, 0.50),
('Tier 2', 51, 100, 0.75),
('Tier 3', 101, 200, 1.00),
('Tier 4', 201, NULL, 1.50);

-- ============================================
-- 3. STUDENT MANAGEMENT SYSTEM
-- ============================================

CREATE DATABASE IF NOT EXISTS student_management;
USE student_management;

DROP TABLE IF EXISTS students;
CREATE TABLE students (
    student_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    date_of_birth DATE,
    gender ENUM('male', 'female', 'other'),
    address VARCHAR(255),
    class_id INT,
    admission_date DATE,
    status ENUM('active', 'graduated', 'suspended') DEFAULT 'active'
);

DROP TABLE IF EXISTS classes;
CREATE TABLE classes (
    class_id INT PRIMARY KEY AUTO_INCREMENT,
    class_name VARCHAR(50) NOT NULL,
    section VARCHAR(10),
    academic_year INT,
    class_teacher_id INT
);

DROP TABLE IF EXISTS subjects;
CREATE TABLE subjects (
    subject_id INT PRIMARY KEY AUTO_INCREMENT,
    subject_name VARCHAR(100) NOT NULL,
    subject_code VARCHAR(20),
    class_id INT,
    FOREIGN KEY (class_id) REFERENCES classes(class_id)
);

DROP TABLE IF EXISTS grades;
CREATE TABLE grades (
    grade_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    exam_type ENUM('quiz', 'midterm', 'final', 'assignment'),
    marks DECIMAL(5,2),
    graded_date DATE,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id)
);

DROP TABLE IF EXISTS attendance;
CREATE TABLE attendance (
    attendance_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    class_id INT,
    date DATE NOT NULL,
    status ENUM('present', 'absent', 'late', 'excused'),
    FOREIGN KEY (student_id) REFERENCES students(student_id)
);

-- ============================================
-- 4. LIBRARY MANAGEMENT SYSTEM
-- ============================================

CREATE DATABASE IF NOT EXISTS library_management;
USE library_management;

DROP TABLE IF EXISTS books;
CREATE TABLE books (
    book_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(100),
    isbn VARCHAR(20) UNIQUE,
    publisher VARCHAR(100),
    publish_year INT,
    category VARCHAR(50),
    quantity INT DEFAULT 1,
    available_quantity INT,
    price DECIMAL(10,2),
    shelf_location VARCHAR(50)
);

DROP TABLE IF EXISTS members;
CREATE TABLE members (
    member_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    membership_type ENUM('student', 'teacher', 'staff', 'guest'),
    membership_date DATE,
    expiry_date DATE,
    status ENUM('active', 'expired', 'suspended') DEFAULT 'active'
);

DROP TABLE IF EXISTS borrow_records;
CREATE TABLE borrow_records (
    record_id INT PRIMARY KEY AUTO_INCREMENT,
    book_id INT NOT NULL,
    member_id INT NOT NULL,
    borrow_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE,
    status ENUM('borrowed', 'returned', 'overdue'),
    FOREIGN KEY (book_id) REFERENCES books(book_id),
    FOREIGN KEY (member_id) REFERENCES members(member_id)
);

DROP TABLE IF EXISTS reservations;
CREATE TABLE reservations (
    reservation_id INT PRIMARY KEY AUTO_INCREMENT,
    book_id INT NOT NULL,
    member_id INT NOT NULL,
    reservation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'fulfilled', 'cancelled'),
    FOREIGN KEY (book_id) REFERENCES books(book_id),
    FOREIGN KEY (member_id) REFERENCES members(member_id)
);

-- ============================================
-- 5. HOSPITAL MANAGEMENT SYSTEM
-- ============================================

CREATE DATABASE IF NOT EXISTS hospital_management;
USE hospital_management;

DROP TABLE IF EXISTS patients;
CREATE TABLE patients (
    patient_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE,
    gender ENUM('male', 'female'),
    email VARCHAR(100),
    phone VARCHAR(20),
    address VARCHAR(255),
    blood_group VARCHAR(10),
    emergency_contact VARCHAR(50),
    registration_date DATE DEFAULT (CURRENT_DATE),
    status ENUM('active', 'inactive') DEFAULT 'active'
);

DROP TABLE IF EXISTS doctors;
CREATE TABLE doctors (
    doctor_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    specialization VARCHAR(100),
    qualification VARCHAR(100),
    experience_years INT,
    phone VARCHAR(20),
    email VARCHAR(100),
    consultation_fee DECIMAL(10,2),
    available_days VARCHAR(50),
    status ENUM('available', 'on_leave') DEFAULT 'available'
);

DROP TABLE IF EXISTS appointments;
CREATE TABLE appointments (
    appointment_id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATETIME NOT NULL,
    reason VARCHAR(255),
    status ENUM('scheduled', 'completed', 'cancelled', 'no_show'),
    notes TEXT,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)
);

DROP TABLE IF EXISTS medical_records;
CREATE TABLE medical_records (
    record_id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    visit_date DATETIME,
    diagnosis VARCHAR(255),
    symptoms TEXT,
    prescription TEXT,
    follow_up_date DATE,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)
);

DROP TABLE IF EXISTS billing;
CREATE TABLE billing (
    bill_id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    appointment_id INT,
    bill_date DATE,
    consultation_fee DECIMAL(10,2),
    medicine_cost DECIMAL(10,2),
    total_amount DECIMAL(10,2),
    payment_status ENUM('pending', 'paid', 'partial'),
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id)
);

-- ============================================
-- 6. E-COMMERCE SYSTEM
-- ============================================

CREATE DATABASE IF NOT EXISTS ecommerce;
USE ecommerce;

DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL,
    parent_id INT,
    description TEXT,
    image VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active'
);

DROP TABLE IF EXISTS products;
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(200) NOT NULL,
    category_id INT,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    image VARCHAR(255),
    status ENUM('active', 'inactive', 'out_of_stock') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS orders;
CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2),
    shipping_address TEXT,
    payment_method ENUM('cash', 'card', 'mobile_money'),
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    order_status ENUM('processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'processing',
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

DROP TABLE IF EXISTS order_items;
CREATE TABLE order_items (
    item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2),
    subtotal DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

DROP TABLE IF EXISTS cart;
CREATE TABLE cart (
    cart_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- ============================================
-- SAMPLE DATA INSERTION
-- ============================================

-- Sample for Electricity Billing
USE electricity_billing;
INSERT INTO customers (first_name, last_name, email, phone, address, meter_number) VALUES
('John', 'Smith', 'john@example.com', '1234567890', '123 Main St', 'MTR001'),
('Sarah', 'Johnson', 'sarah@example.com', '1234567891', '456 Oak Ave', 'MTR002');

-- Sample for Student Management
USE student_management;
INSERT INTO students (first_name, last_name, email, phone, date_of_birth, class_id) VALUES
('Mike', 'Brown', 'mike@example.com', '9876543210', '2005-05-15', 1),
('Emily', 'Davis', 'emily@example.com', '9876543211', '2005-08-20', 1);

-- Sample for Library
USE library_management;
INSERT INTO books (title, author, isbn, category, quantity, available_quantity) VALUES
('Java Programming', 'James Gosling', '978-0134685991', 'Programming', 5, 5),
('Data Structures', 'Cormen', '978-0262033848', 'Programming', 3, 3);

-- Sample for Hospital
USE hospital_management;
INSERT INTO patients (first_name, last_name, date_of_birth, phone, blood_group) VALUES
('Alex', 'Wilson', '1990-03-10', '555-0101', 'A+'),
('Maria', 'Garcia', '1985-07-22', '555-0102', 'O+');

INSERT INTO doctors (first_name, last_name, specialization, qualification, experience_years) VALUES
('Dr. James', 'Anderson', 'Cardiology', 'MD, PhD', 15),
('Dr. Lisa', 'Wang', 'Pediatrics', 'MD', 10);

-- Sample for E-Commerce
USE ecommerce;
INSERT INTO categories (category_name, description) VALUES
('Electronics', 'Electronic devices and gadgets'),
('Books', 'Physical and digital books'),
('Clothing', 'Apparel and accessories');

INSERT INTO products (product_name, category_id, price, stock_quantity) VALUES
('Laptop', 1, 999.99, 10),
('Smartphone', 1, 599.99, 25),
('Programming Book', 2, 49.99, 50);

-- ============================================
-- VERIFICATION QUERIES
-- ============================================

SHOW DATABASES;
SELECT 'Database setup complete!' AS status;