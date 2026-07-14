<?php
session_start();
include '../config/koneksi.php';

// Redirect jika sudah login
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, username, nama_lengkap, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $admin = $result->fetch_assoc();
            
            if ($password === $admin['password']) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_nama'] = $admin['nama_lengkap'];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Password salah!';
            }
        } else {
            $error = 'Username tidak ditemukan!';
        }
        $stmt->close();
    } else {
        $error = 'Harap isi semua field!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Zara Jaya Motor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #00ff88;
            --primary-dark: #00cc6a;
            --secondary-color: #0a1929;
            --text-light: #ffffff;
            --shadow: rgba(0, 255, 136, 0.3);
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: linear-gradient(135deg, var(--secondary-color) 0%, #0d2738 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(0, 255, 136, 0.1), transparent);
            top: -250px;
            right: -250px;
            animation: float 6s ease-in-out infinite;
        }

        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(0, 255, 136, 0.1), transparent);
            bottom: -200px;
            left: -200px;
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, 30px); }
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(10px);
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header i {
            font-size: 3.5em;
            color: var(--primary-color);
            margin-bottom: 15px;
            animation: rotate 3s ease-in-out infinite;
        }

        @keyframes rotate {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(15deg); }
        }

        .login-header h1 {
            font-size: 2em;
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
            font-weight: 800;
        }

        .login-header p {
            color: #666;
            font-size: 0.95em;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--secondary-color);
            font-weight: 600;
            font-size: 0.95em;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 1.1em;
        }

        .form-group input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1em;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
            background: white;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--shadow);
        }

        .error-message {
            background: #ffe0e0;
            color: #d32f2f;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9em;
            border-left: 4px solid #d32f2f;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .error-message i {
            font-size: 1.2em;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--secondary-color);
            border: none;
            border-radius: 10px;
            font-size: 1.1em;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
            box-shadow: 0 5px 20px var(--shadow);
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px var(--shadow);
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .back-home {
            text-align: center;
            margin-top: 25px;
        }

        .back-home a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95em;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-home a:hover {
            color: var(--primary-color);
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 20px;
                padding: 40px 30px;
            }

            .login-header h1 {
                font-size: 1.6em;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-tools"></i>
            <h1>Login Admin</h1>
            <p>Zara Jaya Motor</p>
        </div>

        <?php if ($error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" placeholder="Masukkan username" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <div class="back-home">
            <a href="/zjm/index.php">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>