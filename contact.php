<?php
require 'config.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $created_at = date('Y-m-d H:i:s');

    if (empty($name) || empty($email) || empty($message)) {
        $response['message'] = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Please enter a valid email address.';
    } else {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $subject, $message, $created_at);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Thank you! Your message has been sent successfully.';
        } else {
            $response['message'] = 'Error: Unable to send message. Please try again.';
        }

        $stmt->close();
    }

    $conn->close();

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
?>

<?php if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Ephrem Tadewos</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        :root { --primary: #6366f1; --primary-dark: #4f46e5; --primary-light: #818cf8; --bg: #eef2ff; }
        body { background: var(--bg); min-height: 100vh; }
        
        .navbar { background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); padding: 1rem 0; position: fixed; width: 100%; top: 0; z-index: 1000; box-shadow: 0 4px 30px rgba(0,0,0,0.1); }
        .navbar-container { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 0 2rem; }
        .logo { font-size: 1.5rem; font-weight: 700; color: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 10px; }
        .nav-links { display: flex; list-style: none; gap: 2rem; }
        .nav-links a { color: #1f2937; text-decoration: none; font-weight: 500; transition: color 0.3s; }
        .nav-links a:hover, .nav-links a.active { color: var(--primary); }
        
        .hero { background: linear-gradient(135deg, #312e81 0%, #4338ca 50%, #6366f1 100%); padding: 160px 20px 80px; position: relative; overflow: hidden; }
        .hero::before { content: ''; position: absolute; inset: 0; background: url('https://images.unsplash.com/photo-1557804506-669a67965ba0?w=1920') center/cover; opacity: 0.1; }
        .hero::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, transparent 0%, rgba(99,102,241,0.2) 100%); }
        .hero-content { position: relative; z-index: 2; text-align: center; max-width: 700px; margin: 0 auto; }
        .hero h1 { font-size: 3rem; color: white; margin-bottom: 15px; font-weight: 700; letter-spacing: -1px; }
        .hero p { font-size: 1.15rem; color: rgba(255,255,255,0.85); }
        .hero-icon { width: 100px; height: 100px; background: linear-gradient(135deg, var(--primary), var(--primary-light)); border-radius: 25px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 25px; box-shadow: 0 20px 40px rgba(99,102,241,0.4); }
        .hero-icon i { font-size: 2.5rem; color: white; }
        
        .contact-container { max-width: 1100px; margin: -40px auto 60px; padding: 0 20px; position: relative; z-index: 10; }
        
        .contact-grid { display: grid; grid-template-columns: 1fr 1.5fr; gap: 30px; }
        
        .info-card { background: white; border-radius: 24px; padding: 40px; box-shadow: 0 25px 50px rgba(0,0,0,0.1); }
        .info-card h3 { font-size: 1.4rem; color: #1f2937; margin-bottom: 25px; font-weight: 600; }
        
        .contact-item { display: flex; align-items: flex-start; gap: 18px; margin-bottom: 25px; padding: 20px; background: linear-gradient(135deg, #eef2ff, #e0e7ff); border-radius: 16px; transition: all 0.3s; }
        .contact-item:hover { transform: translateX(5px); box-shadow: 0 10px 25px rgba(99,102,241,0.15); }
        .contact-item .icon { width: 55px; height: 55px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .contact-item .icon i { color: white; font-size: 1.3rem; }
        .contact-item .text h4 { color: #1f2937; font-size: 1rem; margin-bottom: 5px; font-weight: 600; }
        .contact-item .text p { color: #6b7280; font-size: 0.9rem; margin: 0; }
        
        .social-links { display: flex; gap: 15px; margin-top: 30px; }
        .social-link { width: 50px; height: 50px; background: linear-gradient(135deg, #eef2ff, #c7d2fe); border-radius: 12px; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.3s; }
        .social-link:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(99,102,241,0.3); }
        .social-link i { color: var(--primary); font-size: 1.3rem; }
        
        .form-card { background: white; border-radius: 24px; padding: 45px; box-shadow: 0 25px 50px rgba(0,0,0,0.1); }
        .form-card h3 { font-size: 1.4rem; color: #1f2937; margin-bottom: 30px; font-weight: 600; }
        
        .form-group { margin-bottom: 22px; }
        .form-group label { display: block; color: #374151; font-weight: 500; margin-bottom: 10px; font-size: 0.95rem; }
        .form-group label i { margin-right: 8px; color: var(--primary); }
        .form-control { width: 100%; padding: 16px 20px; border: 2px solid #e5e7eb; border-radius: 14px; font-size: 1rem; font-family: 'Poppins', sans-serif; transition: all 0.3s; background: #f9fafb; }
        .form-control:focus { outline: none; border-color: var(--primary); background: white; box-shadow: 0 0 0 4px rgba(99,102,241,0.1); }
        .form-control::placeholder { color: #9ca3af; }
        
        .btn-submit { width: 100%; padding: 18px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; border: none; border-radius: 14px; font-size: 1.05rem; font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 12px; box-shadow: 0 10px 30px rgba(99,102,241,0.3); }
        .btn-submit:hover { transform: translateY(-3px); box-shadow: 0 15px 40px rgba(99,102,241,0.4); }
        
        .alert { padding: 18px 24px; border-radius: 14px; margin-bottom: 25px; font-weight: 500; display: flex; align-items: center; gap: 12px; }
        .alert-success { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; border: 1px solid #fecaca; }
        
        .footer { background: #312e81; padding: 50px 0 20px; color: white; text-align: center; }
        .footer p { opacity: 0.7; }
        
        @media (max-width: 768px) {
            .hero h1 { font-size: 2.2rem; }
            .contact-grid { grid-template-columns: 1fr; }
            .nav-links { display: none; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="home.php" class="logo"><i class="fas fa-code"></i> Ephrem</a>
            <ul class="nav-links">
                <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="home.php#about">About</a></li>
                <li><a href="home.php#skills">Skills</a></li>
                <li><a href="home.php#projects">Projects</a></li>
                <li><a href="contact.php" class="active">Contact</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="hero">
        <div class="hero-content">
            <div class="hero-icon"><i class="fas fa-envelope-open-text"></i></div>
            <h1>Get In Touch</h1>
            <p>Have a question or want to work together? I'd love to hear from you!</p>
        </div>
    </div>
    
    <div class="contact-container">
        <div class="contact-grid">
            <div class="info-card">
                <h3><i class="fas fa-info-circle"></i> Contact Information</h3>
                
                <div class="contact-item">
                    <div class="icon"><i class="fas fa-envelope"></i></div>
                    <div class="text">
                        <h4>Email</h4>
                        <p>eph.man.tade@gmail.com</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <div class="icon"><i class="fas fa-phone"></i></div>
                    <div class="text">
                        <h4>Phone</h4>
                        <p>+251-94-895-6850</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="text">
                        <h4>Location</h4>
                        <p>Wolaita, Ethiopia</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <div class="icon"><i class="fas fa-globe"></i></div>
                    <div class="text">
                        <h4>Website</h4>
                        <p>ephremtadewos.me</p>
                    </div>
                </div>
                
                <h4 style="margin-top: 25px; margin-bottom: 15px; color: #374151;">Follow Me</h4>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-github"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                </div>
            </div>
            
            <div class="form-card">
                <h3><i class="fas fa-paper-plane"></i> Send a Message</h3>
                
                <?php if (!empty($response['message'])): ?>
                    <div class="alert <?php echo $response['success'] ? 'alert-success' : 'alert-error'; ?>">
                        <i class="fas <?php echo $response['success'] ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                        <?php echo htmlspecialchars($response['message']); ?>
                    </div>
                <?php endif; ?>
                
                <form id="contactForm" method="POST" action="contact.php">
                    <div class="form-group">
                        <label for="name"><i class="fas fa-user"></i> Your Name *</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email Address *</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="your.email@example.com" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject"><i class="fas fa-tag"></i> Subject</label>
                        <input type="text" id="subject" name="subject" class="form-control" placeholder="What's this about?">
                    </div>
                    
                    <div class="form-group">
                        <label for="message"><i class="fas fa-comment-dots"></i> Message *</label>
                        <textarea id="message" name="message" class="form-control" placeholder="Write your message here..." rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <footer class="footer">
        <p>&copy; 2025 Ephrem Tadewos. All rights reserved.</p>
    </footer>
    
    <script>
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('contact.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    this.reset();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                alert('Something went wrong. Please try again.');
            });
        });
    </script>
</body>
</html>
<?php endif; ?>