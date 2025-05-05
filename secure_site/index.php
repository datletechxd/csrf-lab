<?php
session_start();
include "db_config.php";
 
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

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
                    <?php if (
                        isset($_SESSION["loggedin"]) &&
                        $_SESSION["loggedin"] === true
                    ): ?>
                        <span class="welcome">Xin chào, <?php echo htmlspecialchars(
                            $_SESSION["username"]
                        ); ?></span>
                        <a href="logout.php" class="btn btn-logout">Đăng Xuất</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-login">Đăng Nhập</a>
                        <a href="register.php" class="btn btn-register">Đăng Ký</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <main>
            <?php if (
                isset($_SESSION["loggedin"]) &&
                $_SESSION["loggedin"] === true
            ): ?>
                <section class="post-form">
                    <h2>Đăng bài mới</h2>
                    <form action="post.php" method="post">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <textarea name="content" placeholder="Nhập nội dung bài viết..." required></textarea>
                        <button type="submit" class="btn btn-submit">Đăng Bài</button>
                    </form>
                </section>
            <?php endif; ?>

            <section class="posts-list">
                <h2>Bài Đăng Gần Đây</h2>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="post">
                            <div class="post-header">
                                <div class="post-author"><?php echo htmlspecialchars(
                                    $row["username"]
                                ); ?></div>
                                <div class="post-date"><?php echo $row[
                                    "post_date"
                                ]; ?></div>
                            </div>
                            <div class="post-content">
                                <?php echo htmlspecialchars($row["content"]); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="no-posts">Chưa có bài đăng nào.</p>
                <?php endif; ?>
            </section>
        </main>

        <footer>
            <p>Demo CSRF Attack - Bảo mật web và ứng dụng</p>
        </footer>
    </div>
</body>
</html>