<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $sql = "SELECT id, full_name, password_hash FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            $error = "Error: " . $conn->error;
        } else {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password_hash'])) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['full_name'] = $row['full_name'];
                    header("Location: home.php");
                    exit;
                } else {
                    $error = "Invalid email or password.";
                }
            } else {
                $error = "Invalid email or password.";
            }

            $stmt->close();
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
    <title>Login - My Portfolio</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div style="font-size: 3rem; margin-bottom: 1rem; color: var(--primary);">
                    <i class="fas fa-code"></i>
                </div>
                <h1>Welcome Back</h1>
                <p>Sign in to access your portfolio</p>
            </div>

            <?php if ($error != ""): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           placeholder="Enter your email" required 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" class="form-control" 
                           placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>

            <div class="auth-footer">
                <p>Don't have an account? <a href="register.php">Create one</a></p>
                <p style="margin-top: 1rem;"><a href="home.php" style="font-size: 0.875rem;">View Portfolio as Guest</a></p>
            </div>
        </div>
    </div>
</body>
</html>