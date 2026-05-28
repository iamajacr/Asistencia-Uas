<?php
session_start();

// Definimos las credenciales fijas
$usuario_correcto = "admin";
$password_correcto = "fimaz2026";

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['usuario'] ?? '';
    $pass = $_POST['password'] ?? '';

    if ($user === $usuario_correcto && $pass === $password_correcto) {
        $_SESSION['autenticado'] = true;
       
        header("Location: m_reporte.php"); 
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrativo · FIMAZ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: #f0f4fa url('FIMAZ.png') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 36px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 380px;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        /* ESTILO PARA EL LOGO */
        .logo-login {
            max-width: 160px;
            height: auto;
            margin-bottom: 10px;
        }

        h2 { color: #1e3b5c; margin-bottom: 25px; font-weight: 700; }
        .input-group { margin-bottom: 20px; text-align: left; }
        .input-group label { display: block; margin-bottom: 8px; color: #1b3b5c; font-weight: 600; font-size: 0.85rem; letter-spacing: 0.5px; }
        input {
            width: 100%;
            height: 54px;
            padding: 0 18px;
            border: 2px solid #d9e2ef;
            border-radius: 20px;
            box-sizing: border-box;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.2s;
        }
        input:focus { border-color: #1e3b5c; }
        button {
            width: 100%;
            height: 54px;
            background: #1e3b5c;
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 10px;
            box-shadow: 0 4px 0 #0f2336;
        }
        button:hover { background: #2c5685; transform: translateY(-1px); }
        button:active { transform: translateY(2px); box-shadow: 0 1px 0 #0f2336; }
        
        .error { color: #b02e2e; background: #fff0f0; padding: 12px; border-radius: 15px; margin-bottom: 20px; font-size: 0.9rem; border: 1px solid #f5c2c2; }
    </style>
</head>
<body>
    <div class="login-card">
        <!-- LOGO INSTITUCIONAL -->
        <img src="logo_fimaz.jpg" alt="Logo FIMAZ" class="logo-login">
        
        <h2>Panel de Control</h2>
        
        <?php if($error): ?>
            <div class="error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="input-group">
                <label>USUARIO</label>
                <input type="text" name="usuario" placeholder="admin" required autocomplete="off">
            </div>
            <div class="input-group">
                <label>CONTRASEÑA</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit">INICIAR SESIÓN</button>
        </form>
    </div>
</body>
</html>