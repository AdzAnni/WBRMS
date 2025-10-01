<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['college'] = $user['college'];
        
        if ($user['role'] == 'coordinator') {
            header("Location: coordinator_dashboard.php");
        } else {
            header("Location: director_dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Management System - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1a3e72;
            --secondary-color: #e8b013;
            --light-color: #f8f9fa;
            --transition-speed: 0.3s;
            --logo-glow: 0 0 15px rgba(232, 176, 19, 0.6);
        }
        
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, rgba(26, 62, 114, 0.9), rgba(40, 82, 152, 0.9)), 
                        url('images/office-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            transition: background var(--transition-speed) ease;
        }
        
        .login-wrapper {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            animation: fadeIn 0.5s ease-out;
        }
        
        .login-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            display: flex;
            height: 480px;
            transition: transform var(--transition-speed) ease, box-shadow var(--transition-speed) ease;
        }
        
        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        .login-banner {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color), #0c2446);
            color: white;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-banner::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 120px;
            height: 120px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: all 0.5s ease;
        }
        
        .login-banner:hover::before {
            transform: scale(1.1);
        }
        
        .login-banner::after {
            content: '';
            position: absolute;
            bottom: -30px;
            left: -30px;
            width: 150px;
            height: 150px;
            background-color: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            transition: all 0.5s ease;
        }
        
        .login-banner:hover::after {
            transform: scale(1.1);
        }
        
        .logo-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 15px;
            perspective: 1000px;
        }
        
        .university-logo {
            width: 100%;
            height: 100%;
            object-fit: contain;
            position: absolute;
            top: 0;
            left: 0;
            transform-style: preserve-3d;
            animation: logoFloat 3s ease-in-out infinite;
            filter: drop-shadow(var(--logo-glow));
            transition: all 0.5s ease;
        }
        
        /* Back Button Styles */
        .back-button {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all var(--transition-speed) ease;
            color: var(--primary-color);
        }
        
        .back-button:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px) scale(1.1);
            box-shadow: 0 4px 15px rgba(26, 62, 114, 0.3);
        }
        
        .back-button:active {
            transform: translateY(0) scale(0.98);
        }

        @keyframes logoFloat {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
                filter: drop-shadow(0 0 12px rgba(232, 176, 19, 0.5));
            }
            50% {
                transform: translateY(-10px) rotate(3deg);
                filter: drop-shadow(0 0 20px rgba(232, 176, 19, 0.8));
            }
        }
        
        .logo-container:hover .university-logo {
            animation: logoSpin 1.5s ease-in-out;
            filter: drop-shadow(0 0 25px rgba(232, 176, 19, 1));
        }
        
        @keyframes logoSpin {
            0% {
                transform: rotateY(0deg) scale(1);
            }
            50% {
                transform: rotateY(180deg) scale(1.1);
            }
            100% {
                transform: rotateY(360deg) scale(1);
            }
        }
        
        /* Logo particles effect */
        .logo-particles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
        }
        
        .particle {
            position: absolute;
            background-color: var(--secondary-color);
            border-radius: 50%;
            opacity: 0;
        }
        
        .logo-container:hover .particle {
            animation: particle-animation 1.5s ease-out;
        }
        
        @keyframes particle-animation {
            0% {
                opacity: 0.8;
                transform: translate(0, 0) scale(0);
            }
            100% {
                opacity: 0;
                transform: translate(var(--tx), var(--ty)) scale(var(--scale));
            }
        }

        .logo-glow {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: radial-gradient(circle, rgba(232, 176, 19, 0.2) 0%, rgba(232, 176, 19, 0) 70%);
            border-radius: 50%;
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.5s ease;
        }

        .logo-container:hover .logo-glow {
            opacity: 1;
            transform: scale(1.5);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1.2);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.5);
                opacity: 0.8;
            }
        }
        
        .login-form {
            flex: 1;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .form-header {
            margin-bottom: 25px;
            text-align: center;
            animation: slideDown 0.5s ease-out;
        }
        
        .form-header h3 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 1.5rem;
        }
        
        .form-header p {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .btn-login {
            background-color: var(--primary-color);
            border: none;
            width: 100%;
            padding: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 6px;
            margin-top: 5px;
            transition: all var(--transition-speed) ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login:hover {
            background-color: #142f57;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 62, 114, 0.3);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }
        
        .btn-login:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }
        
        .form-control {
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            font-size: 0.9rem;
            transition: all var(--transition-speed) ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(26, 62, 114, 0.2);
            transform: translateX(2px);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 5px;
            font-size: 0.9rem;
            transition: all var(--transition-speed) ease;
        }
        
        .input-group-text {
            background-color: white;
            border-right: none;
            color: var(--primary-color);
            padding: 10px 12px;
            transition: all var(--transition-speed) ease;
        }
        
        .input-with-icon {
            border-left: none;
        }
        
        .password-toggle-container {
            background-color: white;
            border: 1px solid #e0e0e0;
            border-left: none;
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
            cursor: pointer;
            padding: 0 12px;
            display: flex;
            align-items: center;
            transition: all var(--transition-speed) ease;
        }
        
        .password-toggle-container:hover {
            background-color: var(--light-color);
        }
        
        .password-toggle {
            color: #6c757d;
            transition: all var(--transition-speed) ease;
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
            transform: scale(1.1);
        }
        
        .forgot-password {
            text-align: right;
            margin: 5px 0 15px;
        }
        
        .forgot-password a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.8rem;
            transition: all var(--transition-speed) ease;
            position: relative;
        }
        
        .forgot-password a:hover {
            text-decoration: underline;
            color: #142f57;
        }
        
        .forgot-password a::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 1px;
            bottom: 0;
            left: 0;
            background-color: var(--primary-color);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform var(--transition-speed) ease;
        }
        
        .forgot-password a:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }
        
        .system-features {
            margin-top: 20px;
        }
        
        .system-features p {
            margin-bottom: 10px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all var(--transition-speed) ease;
        }
        
        .system-features p:hover {
            transform: translateX(5px);
        }
        
        .system-features i {
            margin-right: 8px;
            color: var(--secondary-color);
            transition: all var(--transition-speed) ease;
        }
        
        .system-features p:hover i {
            transform: rotate(10deg) scale(1.1);
        }
        
        .support-link {
            font-size: 0.8rem;
            margin-top: 15px;
            color: #6c757d;
            text-align: center;
        }
        
        .alert {
            border-radius: 6px;
            padding: 10px;
            font-size: 0.85rem;
            margin-bottom: 15px;
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideDown {
            from { 
                opacity: 0;
                transform: translateY(-20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideIn {
            from { 
                opacity: 0;
                transform: translateX(-20px);
            }
            to { 
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 1;
            }
            20% {
                transform: scale(25, 25);
                opacity: 1;
            }
            100% {
                opacity: 0;
                transform: scale(40, 40);
            }
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                height: auto;
            }
            
            .login-banner {
                padding: 20px;
            }
            
            .login-form {
                padding: 25px;
            }
            
            .login-container:hover {
                transform: none;
            }
            
            .logo-container {
                width: 100px;
                height: 100px;
            }
        }
        
        @media (max-width: 576px) {
            .login-wrapper {
                padding: 10px;
            }
            
            .login-form {
                padding: 20px;
            }
            
            .form-header h3 {
                font-size: 1.3rem;
            }
            
            .logo-container {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>
    <a href="index.php" class="back-button" title="Return to Home">
        <i class="fas fa-home"></i>
    </a>

    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-banner">
                <div class="logo-container">
                    <div class="logo-glow"></div>
                    <img src="logo/MSU_Sulu_Logo.png" alt="MSU-SULU Logo" class="university-logo">
                    <div class="logo-particles" id="logoParticles"></div>
                </div>
                <h2 class="mb-2" style="font-size: 1.3rem;">Office of the Director for Research</h2>
                <p class="mb-3" style="font-size: 0.9rem;">Automated Research Management System</p>
                <div class="system-features">
                    <p><i class="fas fa-check-circle"></i> Streamlined Research Tracking</p>
                    <p><i class="fas fa-chart-bar"></i> Comprehensive Analytics</p>
                    <p><i class="fas fa-file-alt"></i> Document Management</p>
                </div>
            </div>
            
            <div class="login-form">
                <div class="form-header">
                    <h3>Welcome Back</h3>
                    <p>Sign in to access your research dashboard</p>
                </div>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control input-with-icon" id="username" name="username" placeholder="Username" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control input-with-icon" id="password" name="password" placeholder="Password" required>
                            <span class="password-toggle-container" id="togglePassword">
                                <i class="fas fa-eye password-toggle"></i>
                            </span>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="fas fa-sign-in-alt me-1"></i> Login
                    </button>
                    
                    <div class="support-link mt-3">
                        <small>Need help? <a href="mailto:kuzzello25@gmail.com">Contact support</a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');
            
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
            
            
            const formElements = document.querySelectorAll('.form-control, .btn-login, .forgot-password a');
            formElements.forEach((el, index) => {
                el.style.animation = `slideIn 0.5s ease-out ${index * 0.1}s forwards`;
                el.style.opacity = '0';
            });
            
           
            const particlesContainer = document.getElementById('logoParticles');
            const particleCount = 12;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                
                const size = Math.random() * 3 + 3;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                
               
                const angle = Math.random() * Math.PI * 2;
                const radius = 30 + Math.random() * 20;
                const x = 50 + Math.cos(angle) * radius;
                const y = 50 + Math.sin(angle) * radius;
                
                particle.style.left = x + '%';
                particle.style.top = y + '%';
                
               
                const tx = (Math.random() - 0.5) * 150;
                const ty = (Math.random() - 0.5) * 150;
                const scale = Math.random() + 0.5;
                
                particle.style.setProperty('--tx', tx + 'px');
                particle.style.setProperty('--ty', ty + 'px');
                particle.style.setProperty('--scale', scale);
                particle.style.animationDelay = (Math.random() * 0.5) + 's';
                
                particlesContainer.appendChild(particle);
            }
            
            const logoContainer = document.querySelector('.logo-container');
            const logo = document.querySelector('.university-logo');
            
            
            setInterval(() => {
                logo.style.animation = 'none';
                logo.offsetHeight; 
                logo.style.animation = 'logoSpin 1.5s ease-in-out';
                
                setTimeout(() => {
                    logo.style.animation = 'logoFloat 3s ease-in-out infinite';
                }, 1500);
                
                
                const particles = document.querySelectorAll('.particle');
                particles.forEach(p => {
                    p.style.animation = 'none';
                    p.offsetHeight; 
                    p.style.animation = 'particle-animation 1.5s ease-out';
                });
                
                
                const glow = document.querySelector('.logo-glow');
                glow.style.opacity = '1';
                glow.style.transform = 'scale(1.5)';
                setTimeout(() => {
                    glow.style.opacity = '0';
                    glow.style.transform = 'scale(0.8)';
                }, 1500);
                
            }, 8000);
        });
    </script>
</body>
</html>