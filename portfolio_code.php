<?php
/*
 * ============================================
 * PORTFOLIO PROJECT - ESSENTIAL CODE FILES
 * ============================================
 * 
 * This file contains:
 * 1. SQL - Database Setup Script
 * 2. Java - Sample Java Project (Student Management System)
 * 
 * Created: May 2025
 * Author: Ephrem Tadewos
 * ============================================
 */


echo "============================================\n";
echo "  PORTFOLIO PROJECT - ESSENTIAL CODE\n";
echo "============================================\n\n";


// ============================================
// PART 1: SQL DATABASE SETUP
// ============================================

$sql_code = "-- ============================================
-- SQL DATABASE SETUP FOR PORTFOLIO
-- ============================================

-- Create Database
CREATE DATABASE IF NOT EXISTS portfolio;
USE portfolio;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contact Messages Table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_read TINYINT(1) DEFAULT 0
);

-- Projects Table (for portfolio)
CREATE TABLE IF NOT EXISTS projects (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    technologies VARCHAR(255),
    image_url VARCHAR(255),
    project_url VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insert Sample Projects
INSERT INTO projects (title, description, technologies) VALUES
('E-Commerce System', 'Full-featured online shopping platform', 'PHP, MySQL, HTML, CSS'),
('Student Management', 'Student database management system', 'Java, MySQL, Swing'),
('Calculator App', 'Modern calculator with scientific functions', 'HTML, CSS, JavaScript'),
('Contact Manager', 'Contact form with database storage', 'PHP, MySQL, AJAX');

-- ============================================
-- SAMPLE QUERIES
-- ============================================

-- Insert new user
INSERT INTO users (full_name, email, password_hash) 
VALUES ('John Doe', 'john@example.com', '\$2y\$10\$abcdefghijklmnopqrstuvwxyz');

-- Get all messages
SELECT * FROM contact_messages ORDER BY created_at DESC;

-- Get user by email
SELECT * FROM users WHERE email = 'user@example.com';

-- Update message status
UPDATE contact_messages SET is_read = 1 WHERE id = 1;

-- Delete old messages
DELETE FROM contact_messages WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY);
";

echo "============================================\n";
echo "  PART 1: SQL CODE\n";
echo "============================================\n\n";
echo $sql_code;


// ============================================
// PART 2: JAVA STUDENT MANAGEMENT SYSTEM
// ============================================

$java_code = "
// ============================================
// JAVA STUDENT MANAGEMENT SYSTEM
// ============================================

// File: Student.java (Model Class)
/*
package com.portfolio.studentmanager;

public class Student {
    private int id;
    private String name;
    private String email;
    private String phone;
    private String department;
    
    // Constructor
    public Student() {}
    
    public Student(int id, String name, String email, String phone, String department) {
        this.id = id;
        this.name = name;
        this.email = email;
        this.phone = phone;
        this.department = department;
    }
    
    // Getters and Setters
    public int getId() { return id; }
    public void setId(int id) { this.id = id; }
    
    public String getName() { return name; }
    public void setName(String name) { this.name = name; }
    
    public String getEmail() { return email; }
    public void setEmail(String email) { this.email = email; }
    
    public String getPhone() { return phone; }
    public void setPhone(String phone) { this.phone = phone; }
    
    public String getDepartment() { return department; }
    public void setDepartment(String department) { this.department = department; }
    
    @Override
    public String toString() {
        return \"Student{\" +
                \"id=\" + id +
                \", name='\" + name + '\\'' +
                \", email='\" + email + '\\'' +
                \", phone='\" + phone + '\\'' +
                \", department='\" + department + '\\'' +
                '}';
    }
}
*/

// ============================================
// File: DBConnection.java (Database Connection)
/*
package com.portfolio.studentmanager;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class DBConnection {
    private static final String URL = \"jdbc:mysql://localhost:3306/portfolio\";
    private static final String USER = \"root\";
    private static final String PASSWORD = \"\";
    
    public static Connection getConnection() throws SQLException {
        try {
            Class.forName(\"com.mysql.cj.jdbc.Driver\");
            return DriverManager.getConnection(URL, USER, PASSWORD);
        } catch (ClassNotFoundException e) {
            throw new SQLException(\"MySQL JDBC Driver not found\", e);
        }
    }
    
    public static void closeConnection(Connection conn) {
        if (conn != null) {
            try {
                conn.close();
            } catch (SQLException e) {
                e.printStackTrace();
            }
        }
    }
}
*/

// ============================================
// File: StudentDAO.java (Data Access Object)
/*
package com.portfolio.studentmanager;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class StudentDAO {
    
    // Add Student
    public boolean addStudent(Student student) {
        String sql = \"INSERT INTO students (name, email, phone, department) VALUES (?, ?, ?, ?)\";
        
        try (Connection conn = DBConnection.getConnection();
             PreparedStatement pstmt = conn.prepareStatement(sql)) {
            
            pstmt.setString(1, student.getName());
            pstmt.setString(2, student.getEmail());
            pstmt.setString(3, student.getPhone());
            pstmt.setString(4, student.getDepartment());
            
            return pstmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        }
    }
    
    // Get All Students
    public List<Student> getAllStudents() {
        List<Student> students = new ArrayList<>();
        String sql = \"SELECT * FROM students ORDER BY id DESC\";
        
        try (Connection conn = DBConnection.getConnection();
             Statement stmt = conn.createStatement();
             ResultSet rs = stmt.executeQuery(sql)) {
            
            while (rs.next()) {
                Student s = new Student();
                s.setId(rs.getInt(\"id\"));
                s.setName(rs.getString(\"name\"));
                s.setEmail(rs.getString(\"email\"));
                s.setPhone(rs.getString(\"phone\"));
                s.setDepartment(rs.getString(\"department\"));
                students.add(s);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return students;
    }
    
    // Update Student
    public boolean updateStudent(Student student) {
        String sql = \"UPDATE students SET name=?, email=?, phone=?, department=? WHERE id=?\";
        
        try (Connection conn = DBConnection.getConnection();
             PreparedStatement pstmt = conn.prepareStatement(sql)) {
            
            pstmt.setString(1, student.getName());
            pstmt.setString(2, student.getEmail());
            pstmt.setString(3, student.getPhone());
            pstmt.setString(4, student.getDepartment());
            pstmt.setInt(5, student.getId());
            
            return pstmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        }
    }
    
    // Delete Student
    public boolean deleteStudent(int id) {
        String sql = \"DELETE FROM students WHERE id=?\";
        
        try (Connection conn = DBConnection.getConnection();
             PreparedStatement pstmt = conn.prepareStatement(sql)) {
            
            pstmt.setInt(1, id);
            return pstmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        }
    }
    
    // Search Student
    public Student searchStudent(int id) {
        String sql = \"SELECT * FROM students WHERE id=?\";
        
        try (Connection conn = DBConnection.getConnection();
             PreparedStatement pstmt = conn.prepareStatement(sql)) {
            
            pstmt.setInt(1, id);
            ResultSet rs = pstmt.executeQuery();
            
            if (rs.next()) {
                Student s = new Student();
                s.setId(rs.getInt(\"id\"));
                s.setName(rs.getString(\"name\"));
                s.setEmail(rs.getString(\"email\"));
                s.setPhone(rs.getString(\"phone\"));
                s.setDepartment(rs.getString(\"department\"));
                return s;
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }
}
*/

// ============================================
// File: Main.java (Main Application)
/*
package com.portfolio.studentmanager;

import java.util.List;
import java.util.Scanner;

public class Main {
    public static void main(String[] args) {
        Scanner scanner = new Scanner(System.in);
        StudentDAO studentDAO = new StudentDAO();
        int choice;
        
        do {
            System.out.println(\"\n=== STUDENT MANAGEMENT SYSTEM ===\");
            System.out.println(\"1. Add Student\");
            System.out.println(\"2. View All Students\");
            System.out.println(\"3. Update Student\");
            System.out.println(\"4. Delete Student\");
            System.out.println(\"5. Search Student\");
            System.out.println(\"6. Exit\");
            System.out.print(\"Enter your choice: \");
            
            choice = scanner.nextInt();
            scanner.nextLine();
            
            switch (choice) {
                case 1:
                    System.out.println(\"\n--- Add Student ---\");
                    System.out.print(\"Name: \");
                    String name = scanner.nextLine();
                    System.out.print(\"Email: \");
                    String email = scanner.nextLine();
                    System.out.print(\"Phone: \");
                    String phone = scanner.nextLine();
                    System.out.print(\"Department: \");
                    String dept = scanner.nextLine();
                    
                    Student newStudent = new Student(0, name, email, phone, dept);
                    if (studentDAO.addStudent(newStudent)) {
                        System.out.println(\"Student added successfully!\");
                    } else {
                        System.out.println(\"Failed to add student.\");
                    }
                    break;
                    
                case 2:
                    System.out.println(\"\n--- All Students ---\");
                    List<Student> students = studentDAO.getAllStudents();
                    for (Student s : students) {
                        System.out.println(s);
                    }
                    break;
                    
                case 3:
                    System.out.println(\"\n--- Update Student ---\");
                    System.out.print(\"Enter Student ID: \");
                    int updateId = scanner.nextInt();
                    scanner.nextLine();
                    
                    Student existing = studentDAO.searchStudent(updateId);
                    if (existing != null) {
                        System.out.print(\"New Name (\" + existing.getName() + \"): \");
                        String newName = scanner.nextLine();
                        System.out.print(\"New Email (\" + existing.getEmail() + \"): \");
                        String newEmail = scanner.nextLine();
                        System.out.print(\"New Phone (\" + existing.getPhone() + \"): \");
                        String newPhone = scanner.nextLine();
                        System.out.print(\"New Department (\" + existing.getDepartment() + \"): \");
                        String newDept = scanner.nextLine();
                        
                        existing.setName(newName.isEmpty() ? existing.getName() : newName);
                        existing.setEmail(newEmail.isEmpty() ? existing.getEmail() : newEmail);
                        existing.setPhone(newPhone.isEmpty() ? existing.getPhone() : newPhone);
                        existing.setDepartment(newDept.isEmpty() ? existing.getDepartment() : newDept);
                        
                        if (studentDAO.updateStudent(existing)) {
                            System.out.println(\"Student updated successfully!\");
                        }
                    } else {
                        System.out.println(\"Student not found.\");
                    }
                    break;
                    
                case 4:
                    System.out.println(\"\n--- Delete Student ---\");
                    System.out.print(\"Enter Student ID: \");
                    int deleteId = scanner.nextInt();
                    
                    if (studentDAO.deleteStudent(deleteId)) {
                        System.out.println(\"Student deleted successfully!\");
                    } else {
                        System.out.println(\"Failed to delete student.\");
                    }
                    break;
                    
                case 5:
                    System.out.println(\"\n--- Search Student ---\");
                    System.out.print(\"Enter Student ID: \");
                    int searchId = scanner.nextInt();
                    
                    Student found = studentDAO.searchStudent(searchId);
                    if (found != null) {
                        System.out.println(found);
                    } else {
                        System.out.println(\"Student not found.\");
                    }
                    break;
                    
                case 6:
                    System.out.println(\"Exiting...\");
                    break;
                    
                default:
                    System.out.println(\"Invalid choice!\");
            }
        } while (choice != 6);
        
        scanner.close();
    }
}
*/

// ============================================
// SQL Table for Java Project
/*
-- Add this to MySQL for Java project
CREATE DATABASE IF NOT EXISTS studentdb;
USE studentdb;

CREATE TABLE IF NOT EXISTS students (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE,
    phone VARCHAR(20),
    department VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
*/

echo "\n\n============================================\n";
echo "  PART 2: JAVA CODE\n";
echo "============================================\n\n";

echo "See Java code above (commented in source file).\n\n";
echo "To use Java code:\n";
echo "1. Create a new Java project in Eclipse/VS Code\n";
echo "2. Add MySQL JDBC driver to classpath\n";
echo "3. Create packages: com.portfolio.studentmanager\n";
echo "4. Copy the Java files above into the package\n";
echo "5. Create the 'students' table in MySQL\n";
echo "6. Run Main.java\n\n";

echo "============================================\n";
echo "  QUICK REFERENCE\n";
echo "============================================\n\n";

echo "PHP PROJECT:\n";
echo "- Database: portfolio\n";
echo "- Files: index.php, register.php, home.php, contact.php, admin.php\n";
echo "- CSS: style.css\n";
echo "- Run: http://localhost/proj/setup.php\n\n";

echo "JAVA PROJECT:\n";
echo "- Database: studentdb\n";
echo "- Table: students\n";
echo "- Package: com.portfolio.studentmanager\n";
echo "- Requirements: MySQL JDBC Driver\n\n";

echo "============================================\n";
echo "  END OF CODE REFERENCE\n";
echo "============================================\n";
?>