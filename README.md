# Modern Portfolio Website

A modern, responsive portfolio website built with PHP, MySQL, and custom CSS.

## Features

### Authentication System
- User Registration with validation
- Secure Login with password hashing
- Session management
- Logout functionality

### Portfolio Sections
- **Home/Hero** - Introduction with social links
- **About** - Personal information and background
- **Skills** - Technical skills with progress indicators
- **Projects** - Project showcase with descriptions and tags
- **Contact** - Contact form with database storage

### Admin Dashboard
- View all contact messages
- Message statistics
- Read/Unread status tracking

## Setup Instructions

### 1. Database Setup
1. Make sure MySQL is running (XAMPP/WAMP)
2. Open phpMyAdmin and create a database named `portfolio`
3. Run the setup script: Visit `http://localhost/proj/setup.php` in your browser

### 2. Configuration
The database connection is already configured in `config.php`:
- Server: localhost
- Username: root
- Password: (empty)
- Database: portfolio

### 3. Running the Project
1. Start your web server (Apache in XAMPP/WAMP)
2. Open browser and navigate to: `http://localhost/proj/`
3. Register a new account
4. Login to access the portfolio and admin dashboard

## File Structure

```
proj/
├── index.php         - Login page
├── register.php      - Registration page
├── home.php          - Main portfolio page
├── contact.php       - Contact form handler
├── admin.php         - Admin dashboard
├── logout.php        - Logout handler
├── setup.php         - Database setup script
├── config.php        - Database connection
├── style.css         - Main stylesheet
└── images/           - Project images
```

## Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 7+
- **Database**: MySQL
- **Icons**: Font Awesome 6
- **Fonts**: Google Fonts (Poppins)

## Screenshots

The portfolio features:
- Modern dark theme navigation
- Gradient hero section
- Animated skill cards
- Project showcase grid
- Contact form with validation
- Responsive design for all devices

## Security Features

- Password hashing using PHP's `password_hash()`
- Prepared statements for SQL injection prevention
- Session management
- Input sanitization with `htmlspecialchars()`
- Email validation

## License

This project is for educational purposes.