<?php
session_start();
require 'config.php';

$is_logged_in = isset($_SESSION['user_id']);
$user_name = $is_logged_in ? $_SESSION['full_name'] : 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ephrem Tadewos - Portfolio</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="home.php" class="logo">
                <i class="fas fa-code"></i> Eph<span>rem</span>
            </a>
            <ul class="nav-links">
                <li><a href="#home" class="active">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#skills">Skills</a></li>
                <li><a href="#projects">Projects</a></li>
                <li><a href="#contact">Contact</a></li>
                <?php if ($is_logged_in): ?>
                    <li><a href="admin.php"><i class="fas fa-cog"></i> Dashboard</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php else: ?>
                    <li><a href="index.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <section id="home" class="hero-section">
        <div class="container" style="padding-top: 80px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;">
                <div class="hero-content">
                    <p style="color: var(--primary-light); font-weight: 600; margin-bottom: 1rem;">
                        <i class="fas fa-wave-square"></i> Hello, I'm
                    </p>
                    <h1 style="font-size: 3.5rem;">Ephrem <span>Tadewos</span></h1>
                    <p style="font-size: 1.2rem; line-height: 1.8;">
                        A passionate <strong>Full Stack Developer</strong> and <strong>IT Student</strong> at Wolaita Sodo University. 
                        I love building modern, responsive web applications and exploring new technologies.
                    </p>
                    <div class="hero-buttons" style="margin-top: 2rem;">
                        <a href="#projects" class="btn btn-primary">
                            <i class="fas fa-briefcase"></i> View My Work
                        </a>
                        <a href="#contact" class="btn btn-outline">
                            <i class="fas fa-envelope"></i> Contact Me
                        </a>
                    </div>
                    <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                        <a href="https://github.com" target="_blank" class="social-link" style="width: 45px; height: 45px;">
                            <i class="fab fa-github"></i>
                        </a>
                        <a href="https://linkedin.com" target="_blank" class="social-link" style="width: 45px; height: 45px;">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="https://t.me/@Epha5T" target="_blank" class="social-link" style="width: 45px; height: 45px;">
                            <i class="fab fa-telegram"></i>
                        </a>
                        <a href="https://www.facebook.com/profile.php?id=100094674243991" target="_blank" class="social-link" style="width: 45px; height: 45px;">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </div>
                </div>
                <div style="text-align: center; position: relative;">
                    <div style="position: relative; display: inline-block;">
                        <img src="ephu..jpg" alt="Ephrem Tadewos" style="width: 350px; height: 400px; object-fit: cover; border-radius: 30px; box-shadow: 0 30px 60px rgba(0,0,0,0.4);">
                        <div style="position: absolute; top: -15px; left: -15px; right: -15px; bottom: -15px; border: 3px solid var(--primary-light); border-radius: 30px; z-index: -1;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="section" style="background: var(--white);">
        <div class="container">
            <div class="section-title">
                <h2>About Me</h2>
                <p>Get to know me better - my background, education, and passion for technology</p>
            </div>
            <div class="about-grid">
                <div class="about-image" style="position: relative;">
                    <img src="IMG_20260403_084516_194.jpg" alt="Ephrem Tadewos" style="width: 100%; height: 400px; object-fit: cover; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.3);">
                    <div style="position: absolute; bottom: -20px; right: -20px; width: 150px; height: 150px;">
                        <img src="BBB.png" alt="Profile" style="width: 100%; height: 100%; border-radius: 16px; border: 4px solid white; box-shadow: 0 10px 30px rgba(0,0,0,0.2); object-fit: cover;">
                    </div>
                    <div style="position: absolute; top: 20px; left: -30px; width: 100px; height: 100px; border-radius: 50%; border: 4px solid white; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden;">
                        <img src="AAA.jpg" alt="Profile Badge" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>
                <div class="about-content">
                    <h3>About Me</h3>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #475569;">
                        First of all, thanks for all. I am a passionate student and beginner developer, interested in web development, Java programming, and database systems. I love learning new technologies and building small projects to improve my skills.
                    </p>
                    
                    <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; border-radius: 16px; margin: 1.5rem 0; border-left: 4px solid #4f46e5;">
                        <h4 style="color: #1f2937; margin-bottom: 1rem;"><i class="fas fa-user"></i> Personal Information</h4>
                        <p style="color: #475569; line-height: 1.8;">
                            My name is <strong>Ephrem Tadewos</strong>. I was born in Wolaita, Bodditi town, Sodober kebele. My father is <strong>Tadewos Ersado</strong> and my mother is <strong>Tadelech Omate</strong>. I was born on 12/02/1997 E.C. (approx. 2005 in Gregorian calendar). I have two brothers and three sisters.
                        </p>
                        <p style="color: #475569; line-height: 1.8; margin-top: 1rem;">
                            Both my mother and father are governmental workers. They work hard and trust in God.
                        </p>
                    </div>
                    
                    <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 1.5rem; border-radius: 16px; margin: 1.5rem 0; border-left: 4px solid #f59e0b;">
                        <h4 style="color: #1f2937; margin-bottom: 1rem;"><i class="fas fa-graduation-cap"></i> Education History</h4>
                        <p style="color: #475569; line-height: 1.8;">
                            I began my study when I was 5 years old. I never dropped or left my class. I started my elementary class in <strong>Boditi Ideget Primary School</strong> and my secondary and preparatory class in <strong>Boditi Secondary and Preparatory School</strong>. I scored good grades in all classes with God's help and also succeeded in the entrance exam.
                        </p>
                    </div>
                    
                    <div style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); padding: 1.5rem; border-radius: 16px; margin: 1.5rem 0; border-left: 4px solid #10b981;">
                        <h4 style="color: #1f2937; margin-bottom: 1rem;"><i class="fas fa-university"></i> University</h4>
                        <p style="color: #475569; line-height: 1.8;">
                            After joining <strong>Wolaita Sodo University (WSU)</strong>, I scored good grades in both semesters of the first year. My hope is to join the School of Informatics, Department of Information Technology. I scored good grades in the first semester of my department and am now a <strong>3rd Year, 1st Semester</strong> student. I hope to score good grades in the remaining of my studying time and work hard to achieve my hopes.
                        </p>
                    </div>
                    
                    <div class="about-info">
                        <div class="info-item">
                            <i class="fas fa-graduation-cap" style="color: #4f46e5;"></i>
                            <span>3rd Year IT Student</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-map-marker-alt" style="color: #4f46e5;"></i>
                            <span>Wolaita, Ethiopia</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-envelope" style="color: #4f46e5;"></i>
                            <span>eph.man.tade@gmail.com</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-phone" style="color: #4f46e5;"></i>
                            <span>+251-94-895-6850</span>
                        </div>
                    </div>
                    <div style="margin-top: 1.5rem;">
                        <a href="#contact" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Let's Connect
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="skills" class="section" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white;">
        <div class="container">
            <div class="section-title">
                <h2 style="color: white;">My Skills</h2>
                <p style="color: #94a3b8;">Technologies and tools I work with</p>
            </div>
            <div class="grid grid-4">
                <div class="skill-card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid #475569; padding: 2rem; border-radius: 20px; text-align: center; transition: all 0.3s;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #e34f26, #f97316); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i class="fab fa-html5" style="font-size: 2.5rem; color: white;"></i>
                    </div>
                    <h3 style="color: white; margin-bottom: 0.5rem;">HTML5</h3>
                    <div style="background: #1f2937; border-radius: 10px; height: 10px; margin-top: 1rem; overflow: hidden;">
                        <div style="background: linear-gradient(90deg, #e34f26, #f97316); width: 90%; height: 100%; border-radius: 10px;"></div>
                    </div>
                    <span style="color: #94a3b8; font-size: 0.9rem; margin-top: 0.5rem; display: block;">90% Expert</span>
                </div>
                <div class="skill-card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid #475569; padding: 2rem; border-radius: 20px; text-align: center; transition: all 0.3s;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #1572b6, #3b82f6); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i class="fab fa-css3-alt" style="font-size: 2.5rem; color: white;"></i>
                    </div>
                    <h3 style="color: white; margin-bottom: 0.5rem;">CSS3</h3>
                    <div style="background: #1f2937; border-radius: 10px; height: 10px; margin-top: 1rem; overflow: hidden;">
                        <div style="background: linear-gradient(90deg, #1572b6, #3b82f6); width: 85%; height: 100%; border-radius: 10px;"></div>
                    </div>
                    <span style="color: #94a3b8; font-size: 0.9rem; margin-top: 0.5rem; display: block;">85% Advanced</span>
                </div>
                <div class="skill-card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid #475569; padding: 2rem; border-radius: 20px; text-align: center; transition: all 0.3s;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #f7df1e, #eab308); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i class="fab fa-js" style="font-size: 2.5rem; color: #1f2937;"></i>
                    </div>
                    <h3 style="color: white; margin-bottom: 0.5rem;">JavaScript</h3>
                    <div style="background: #1f2937; border-radius: 10px; height: 10px; margin-top: 1rem; overflow: hidden;">
                        <div style="background: linear-gradient(90deg, #f7df1e, #eab308); width: 70%; height: 100%; border-radius: 10px;"></div>
                    </div>
                    <span style="color: #94a3b8; font-size: 0.9rem; margin-top: 0.5rem; display: block;">70% Intermediate</span>
                </div>
                <div class="skill-card">
                    <div class="skill-icon"><i class="fab fa-php" style="color: #777bb4;"></i></div>
                    <div class="skill-name">PHP</div>
                    <div class="skill-level"><div class="skill-progress" style="width: 75%;"></div></div>
                </div>
                <div class="skill-card">
                    <div class="skill-icon"><i class="fab fa-java" style="color: #007396;"></i></div>
                    <div class="skill-name">Java</div>
                    <div class="skill-level"><div class="skill-progress" style="width: 65%;"></div></div>
                </div>
                <div class="skill-card">
                    <div class="skill-icon"><i class="fas fa-database" style="color: #336791;"></i></div>
                    <div class="skill-name">MySQL</div>
                    <div class="skill-level"><div class="skill-progress" style="width: 80%;"></div></div>
                </div>
                <div class="skill-card">
                    <div class="skill-icon"><i class="fab fa-python" style="color: #3776ab;"></i></div>
                    <div class="skill-name">Python</div>
                    <div class="skill-level"><div class="skill-progress" style="width: 60%;"></div></div>
                </div>
                <div class="skill-card">
                    <div class="skill-icon"><i class="fab fa-git-alt" style="color: #f05032;"></i></div>
                    <div class="skill-name">Git</div>
                    <div class="skill-level"><div class="skill-progress" style="width: 70%;"></div></div>
                </div>
            </div>
        </div>
    </section>

    <section id="projects" class="section" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
        <div class="container">
            <div class="section-title">
                <h2 style="font-size: 2.5rem; color: #1f2937; margin-bottom: 0.5rem;">My Projects</h2>
                <p style="font-size: 1.1rem; color: #64748b; max-width: 600px; margin: 0 auto;">Some of the projects I've worked on - showcasing my skills in Java, PHP, and web development</p>
            </div>
            <div class="grid grid-3">
                <!-- Electricity Billing System -->
                <div class="project-card">
                    <div class="project-image" style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title">Electricity Billing System</h3>
                        <p class="project-description">Complete electricity billing and meter reading management system with bill generation and payment tracking.</p>
                        <div class="project-tags">
                            <span class="tag">Java</span>
                            <span class="tag">MySQL</span>
                            <span class="tag">Swing</span>
                        </div>
                        <a href="projects/electricity_billing.php" class="btn btn-primary btn-sm" style="margin-top:10px; display:inline-block;">
                            <i class="fas fa-eye"></i> View Project
                        </a>
                    </div>
                </div>
                
                <!-- E-Commerce System -->
                <div class="project-card">
                    <div class="project-image">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title">E-Commerce System</h3>
                        <p class="project-description">Full-featured online shopping platform with cart, checkout, and admin panel.</p>
                        <div class="project-tags">
                            <span class="tag">PHP</span>
                            <span class="tag">MySQL</span>
                            <span class="tag">HTML/CSS</span>
                        </div>
                        <a href="projects/ecommerce.php" class="btn btn-primary btn-sm" style="margin-top:10px; display:inline-block;">
                            <i class="fas fa-eye"></i> View Project
                        </a>
                    </div>
                </div>
                
                <!-- Student Management System -->
                <div class="project-card">
                    <div class="project-image">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title">Student Management System</h3>
                        <p class="project-description">Student database management system for educational institutions with grades and attendance.</p>
                        <div class="project-tags">
                            <span class="tag">Java</span>
                            <span class="tag">MySQL</span>
                            <span class="tag">Swing</span>
                        </div>
                        <a href="projects/student_management.php" class="btn btn-primary btn-sm" style="margin-top:10px; display:inline-block;">
                            <i class="fas fa-eye"></i> View Project
                        </a>
                    </div>
                </div>
                
                <!-- Library Management System -->
                <div class="project-card">
                    <div class="project-image" style="background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title">Library Management System</h3>
                        <p class="project-description">Book inventory, member management, and borrowing/returning system for libraries.</p>
                        <div class="project-tags">
                            <span class="tag">Java</span>
                            <span class="tag">MySQL</span>
                            <span class="tag">Swing</span>
                        </div>
                        <a href="projects/library_management.php" class="btn btn-primary btn-sm" style="margin-top:10px; display:inline-block;">
                            <i class="fas fa-eye"></i> View Project
                        </a>
                    </div>
                </div>
                
                <!-- Hospital Management System -->
                <div class="project-card">
                    <div class="project-image" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title">Hospital Management System</h3>
                        <p class="project-description">Patient records, appointment scheduling, and billing system for healthcare facilities.</p>
                        <div class="project-tags">
                            <span class="tag">Java</span>
                            <span class="tag">MySQL</span>
                            <span class="tag">Swing</span>
                        </div>
                        <a href="projects/hospital_management.php" class="btn btn-primary btn-sm" style="margin-top:10px; display:inline-block;">
                            <i class="fas fa-eye"></i> View Project
                        </a>
                    </div>
                </div>
                
                <!-- Calculator App -->
                <div class="project-card">
                    <div class="project-image">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title">Calculator App</h3>
                        <p class="project-description">Modern calculator with scientific functions and history feature.</p>
                        <div class="project-tags">
                            <span class="tag">HTML</span>
                            <span class="tag">CSS</span>
                            <span class="tag">JS</span>
                        </div>
                        <a href="projects/calculator.php" class="btn btn-primary btn-sm" style="margin-top:10px; display:inline-block;">
                            <i class="fas fa-eye"></i> View Project
                        </a>
                    </div>
                </div>
                
                <!-- Contact Management -->
                <div class="project-card">
                    <div class="project-image">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title">Contact Management</h3>
                        <p class="project-description">Contact form with database storage and email integration.</p>
                        <div class="project-tags">
                            <span class="tag">PHP</span>
                            <span class="tag">MySQL</span>
                            <span class="tag">AJAX</span>
                        </div>
                        <a href="contact.php" class="btn btn-primary btn-sm" style="margin-top:10px; display:inline-block;">
                            <i class="fas fa-eye"></i> View Project
                        </a>
                    </div>
                </div>
                
                <!-- Portfolio Website -->
                <div class="project-card">
                    <div class="project-image" style="background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title">Portfolio Website</h3>
                        <p class="project-description">Personal portfolio with authentication, contact form, and admin dashboard.</p>
                        <div class="project-tags">
                            <span class="tag">PHP</span>
                            <span class="tag">MySQL</span>
                            <span class="tag">CSS3</span>
                        </div>
                        <a href="home.php" class="btn btn-primary btn-sm" style="margin-top:10px; display:inline-block;">
                            <i class="fas fa-eye"></i> View Project
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="section contact-section">
        <div class="container">
            <div class="section-title">
                <img src="BBB.png" alt="Contact" style="width: 80px; height: 80px; border-radius: 50%; margin-bottom: 1rem; object-fit: cover; border: 3px solid var(--primary);">
                <h2>Get In Touch</h2>
                <p>Have a project in mind or want to collaborate? Let's talk!</p>
            </div>
            <div class="contact-grid">
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                        <div class="contact-details">
                            <h4>Email</h4>
                            <p>eph.man.tade@gmail.com</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fas fa-phone"></i></div>
                        <div class="contact-details">
                            <h4>Phone / WhatsApp</h4>
                            <p>+251-94-895-6850<br>+251-71-610-5802</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="contact-details">
                            <h4>Location</h4>
                            <p>Wolaita, Ethiopia</p>
                            <p style="font-size: 0.75rem; color: var(--secondary);">Open for remote work</p>
                        </div>
                    </div>
                    <div class="social-links">
                        <a href="https://linkedin.com" target="_blank" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://github.com" target="_blank" class="social-link"><i class="fab fa-github"></i></a>
                        <a href="https://t.me/@Epha5T" target="_blank" class="social-link"><i class="fab fa-telegram"></i></a>
                        <a href="https://www.facebook.com/profile.php?id=100094674243991" target="_blank" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
                <div class="contact-form">
                    <form id="contactForm" method="POST" action="contact.php">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Your Name</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" class="form-control" placeholder="What's this about?">
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" class="form-control" placeholder="Write your message here..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    Eph<span>rem</span>
                </div>
                <div class="footer-links">
                    <a href="#home">Home</a>
                    <a href="#about">About</a>
                    <a href="#projects">Projects</a>
                    <a href="#contact">Contact</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Ephrem Tadewos. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    document.querySelectorAll('.nav-links a').forEach(link => link.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });

        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.nav-links a');
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 100) {
                    current = section.getAttribute('id');
                }
            });
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>