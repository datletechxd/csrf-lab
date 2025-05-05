<?php
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}

include 'db_config.php';

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["username"]))){
        $username_err = "Vui lòng nhập tên đăng nhập.";
    } else{
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("s", $param_username);
            
            $param_username = trim($_POST["username"]);
            
            if($stmt->execute()){
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $username_err = "Tên đăng nhập này đã tồn tại.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Đã xảy ra lỗi, vui lòng thử lại sau.";
            }

            $stmt->close();
        }
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Vui lòng nhập mật khẩu.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Mật khẩu phải có ít nhất 6 ký tự.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Vui lòng xác nhận mật khẩu.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Mật khẩu không khớp.";
        }
    }
    
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        $sql = "INSERT INTO users (username, password, token) VALUES (?, ?, ?)";
         
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("sss", $param_username, $param_password, $param_token);
            
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_token = bin2hex(random_bytes(32));
            
            if($stmt->execute()){
                header("location: login.php");
            } else{
                echo "Đã xảy ra lỗi, vui lòng thử lại sau.";
            }

            $stmt->close();
        }
    }
    $conn->close();
}
?>
 
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .register-container {
            max-width: 400px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .form-group .invalid-feedback {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .form-group .btn-register {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #42B72A;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <h2>Đăng Ký</h2>
            <p>Vui lòng điền thông tin để tạo tài khoản.</p>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Tên đăng nhập</label>
                    <input type="text" name="username" value="<?php echo $username; ?>" class="<?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>    
                
                <div class="form-group">
                    <label>Mật khẩu</label>
                    <input type="password" name="password" value="<?php echo $password; ?>" class="<?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                
                <div class="form-group">
                    <label>Xác nhận mật khẩu</label>
                    <input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>" class="<?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                
                <div class="form-group">
                    <input type="submit" class="btn btn-register" value="Đăng Ký">
                </div>
                
                <p>Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a>.</p>
            </form>
        </div>
    </div>
</body>
</html>