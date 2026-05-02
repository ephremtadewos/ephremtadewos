<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$message = "";
$error = "";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portfolio";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all fields.";
    } elseif (strlen($full_name) < 2) {
        $error = "Full name must be at least 2 characters.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $check_sql = "SELECT id FROM users WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        
        if (!$check_stmt) {
            $error = "Error preparing check: " . $conn->error;
        } else {
            $check_stmt->bind_param("s", $email);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                $error = "Email is already registered.";
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $created_at = date('Y-m-d H:i:s');
                $sql = "INSERT INTO users (full_name, email, password_hash, created_at) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                
                if (!$stmt) {
                    $error = "Error preparing insert: " . $conn->error;
                } else {
                    $stmt->bind_param("ssss", $full_name, $email, $password_hash, $created_at);

                    if ($stmt->execute()) {
                        $message = "Registration successful! You can now <a href='index.php'>sign in</a>.";
                    } else {
                        $error = "Error: " . $stmt->error;
                    }

                    $stmt->close();
                }
            }
            $check_stmt->close();
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - My Portfolio</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div style="font-size: 3rem; margin-bottom: 1rem; color: var(--primary);">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1>Create Account</h1>
                <p>Join and start building your portfolio</p>
            </div>

            <?php if ($error != ""): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($message != ""): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="full_name"><i class="fas fa-user"></i> Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" 
                           placeholder="Enter your full name" required
                           value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           placeholder="Enter your email" required
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" class="form-control" 
                           placeholder="Create a password (min 6 characters)" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password"><i class="fas fa-lock"></i> Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" 
                           placeholder="Confirm your password" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>

            <div class="auth-footer">
                <p>Already have an account? <a href="index.php">Sign in</a></p>
            </div>
        </div>
    </div>
</body>
</html>