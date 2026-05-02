<?php
session_start();
require 'config.php';

if (!isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$messages = [];
$result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Portfolio</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-container {
            padding-top: 100px;
            padding-bottom: 2rem;
        }
        .admin-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--white);
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .admin-header h1 {
            font-size: 1.75rem;
        }
        .admin-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(79, 70, 229, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.25rem;
        }
        .stat-info h3 {
            font-size: 1.5rem;
            color: var(--dark);
            margin: 0;
        }
        .stat-info p {
            color: var(--gray);
            font-size: 0.875rem;
            margin: 0;
        }
        .messages-table {
            width: 100%;
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }
        .messages-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .messages-table th {
            background: var(--dark);
            color: var(--white);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }
        .messages-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--gray-light);
        }
        .messages-table tr:hover {
            background: var(--light);
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-unread {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }
        .status-read {
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary);
        }
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        .btn-danger {
            background: #ef4444;
            color: var(--white);
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: var(--transition);
        }
        .btn-danger:hover {
            background: #dc2626;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="home.php" class="logo">
                <i class="fas fa-code"></i> Eph<span>rem</span>
            </a>
            <ul class="nav-links">
                <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="admin.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout (<?php echo htmlspecialchars($_SESSION['full_name']); ?>)</a></li>
            </ul>
        </div>
    </nav>

    <div class="container admin-container">
        <div class="admin-header">
            <div>
                <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
                <p style="opacity: 0.9;">Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</p>
            </div>
            <a href="home.php" class="btn btn-outline" style="border-color: white; color: white;">
                <i class="fas fa-external-link-alt"></i> View Portfolio
            </a>
        </div>

        <div class="admin-stats">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                <div class="stat-info">
                    <h3><?php echo count($messages); ?></h3>
                    <p>Total Messages</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-envelope-open"></i></div>
                <div class="stat-info">
                    <h3><?php echo count(array_filter($messages, fn($m) => $m['is_read'] == 0)); ?></h3>
                    <p>Unread Messages</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <h3><?php echo count(array_filter($messages, fn($m) => $m['is_read'] == 1)); ?></h3>
                    <p>Read Messages</p>
                </div>
            </div>
        </div>

        <div class="messages-table">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($messages)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem; color: var(--gray);">
                                <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
                                No messages yet
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($messages as $index => $msg): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($msg['name']); ?></td>
                                <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                <td><?php echo htmlspecialchars($msg['subject'] ?: '-'); ?></td>
                                <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    <?php echo htmlspecialchars($msg['message']); ?>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($msg['created_at'])); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $msg['is_read'] ? 'status-read' : 'status-unread'; ?>">
                                        <?php echo $msg['is_read'] ? 'Read' : 'Unread'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="footer" style="margin-top: 2rem;">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; 2025 Admin Panel. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>