<?php
session_start();
include "db_config.php";

$sql = "SELECT posts.content, posts.post_date, users.username 
        FROM posts 
        INNER JOIN users ON posts.user_id = users.id 
        ORDER BY posts.post_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mạng Xã Hội Demo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="site-header">
                <h1>Mạng Xã Hội Demo</h1>
                <div class="user-actions">
                    <a href="login.php" class="btn btn-login">Đăng Nhập</a>
                    <a href="register.php" class="btn btn-register">Đăng Ký</a>
                </div>
            </div>
        </header>

        <main>
            <section class="posts-list">
                <h2>Bài Đăng Gần Đây</h2>
                <p class="no-posts">Chưa có bài đăng nào.</p>
            </section>
        </main>

        <footer>
            <p>Demo CSRF Attack - Bảo mật web và ứng dụng</p>
        </footer>
    </div>
</body>
</html>