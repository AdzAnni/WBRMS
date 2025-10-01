<?php
include 'config.php';

// Get research statistics
$stmt = $pdo->query("SELECT 
    COUNT(*) as total_research,
    SUM(CASE WHEN remarks = 'Scopus-Indexed Journal' THEN 1 ELSE 0 END) as scopus,
    SUM(CASE WHEN publication_status = 'Published' THEN 1 ELSE 0 END) as published,
    SUM(CASE WHEN certificate_path IS NOT NULL AND certificate_path != '' THEN 1 ELSE 0 END) as certificates
    FROM research");
$research_stats = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automated Research Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary-color: #1a3e72;
            --primary-light: #2a4d8a;
            --secondary-color: #e8b013;
            --secondary-dark: #d19e0a;
            --accent-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4cc9f0;
            --transition-speed: 0.3s;
            --section-padding: 80px 0;
            --border-radius: 12px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark-color);
            overflow-x: hidden;
            line-height: 1.6;
        }
        
        /* Navigation */
        .navbar {
            padding: 20px 0;
            transition: all var(--transition-speed) ease;
            background-color: rgba(255, 255, 255, 0.98);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(10px);
        }
        
        .navbar.scrolled {
            padding: 12px 0;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            font-size: 1.2rem;
        }
        
        .navbar-brand img {
            height: 42px;
            margin-right: 12px;
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--dark-color);
            margin: 0 12px;
            position: relative;
            transition: all var(--transition-speed) ease;
        }
        
        .nav-link:hover, .nav-link.active {
            color: var(--primary-color);
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            bottom: -5px;
            left: 0;
            background-color: var(--secondary-color);
            transition: width var(--transition-speed) ease;
            border-radius: 2px;
        }
        
        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 10px 24px;
            font-weight: 600;
            transition: all var(--transition-speed) ease;
            border-radius: var(--border-radius);
            letter-spacing: 0.5px;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-light);
            border-color: var(--primary-light);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(26, 62, 114, 0.3);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 600;
            transition: all var(--transition-speed) ease;
            border-radius: var(--border-radius);
            padding: 10px 24px;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(26, 62, 114, 0.2);
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, rgba(26, 62, 114, 0.95), rgba(40, 82, 152, 0.95)), 
                        url('images/office-bg.jpg') no-repeat center center;
            background-size: cover;
            color: white;
            padding: 140px 0 120px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(232, 176, 19, 0.15), transparent 70%);
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .hero-title {
            font-size: 3.2rem;
            font-weight: 800;
            margin-bottom: 24px;
            line-height: 1.2;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: 32px;
            opacity: 0.92;
            max-width: 600px;
        }
        
        .hero-buttons .btn {
            margin-right: 16px;
            margin-bottom: 16px;
            min-width: 160px;
            text-align: center;
        }
        
        .hero-image {
            position: relative;
            animation: float 6s ease-in-out infinite;
            perspective: 1000px;
        }
        
        .hero-image img {
            max-width: 100%;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transform-style: preserve-3d;
            transform: rotateY(-5deg) rotateX(2deg);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .hero-badge {
            position: absolute;
            top: -15px;
            right: -15px;
            background: var(--secondary-color);
            color: #000;
            padding: 10px 15px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            z-index: 2;
            animation: pulse 2s infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotateY(-5deg) rotateX(2deg); }
            50% { transform: translateY(-20px) rotateY(-8deg) rotateX(4deg); }
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        /* Features Section */
        .features-section {
            padding: var(--section-padding);
            background-color: white;
            position: relative;
        }
        
        .section-title {
            font-size: 2.6rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            width: 60%;
            height: 5px;
            bottom: -12px;
            left: 0;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border-radius: 3px;
        }
        
        .section-subtitle {
            font-size: 1.15rem;
            color: #6c757d;
            margin-bottom: 60px;
            max-width: 750px;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }
        
        .feature-card {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 32px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all var(--transition-speed) ease;
            height: 100%;
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 0;
            background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
            transition: all var(--transition-speed) ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--box-shadow);
            border-color: rgba(26, 62, 114, 0.1);
        }
        
        .feature-card:hover::before {
            height: 100%;
        }
        
        .feature-icon {
            width: 75px;
            height: 75px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.9rem;
            margin-bottom: 25px;
            box-shadow: 0 8px 20px rgba(26, 62, 114, 0.2);
            transition: all var(--transition-speed) ease;
        }
        
        .feature-card:hover .feature-icon {
            transform: rotate(5deg) scale(1.1);
            background: linear-gradient(135deg, var(--secondary-color), var(--secondary-dark));
        }
        
        .feature-title {
            font-size: 1.35rem;
            font-weight: 600;
            margin-bottom: 18px;
            color: var(--primary-color);
            transition: all var(--transition-speed) ease;
        }
        
        .feature-card:hover .feature-title {
            color: var(--secondary-color);
        }
        
        /* Stats Section */
        .stats-section {
            padding: var(--section-padding);
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .stats-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('images/dots-pattern.png') repeat;
            opacity: 0.1;
        }
        
        .stats-section::after {
            content: '';
            position: absolute;
            bottom: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(232, 176, 19, 0.1), transparent 70%);
        }
        
        .stat-item {
            text-align: center;
            padding: 25px;
            position: relative;
            z-index: 1;
        }
        
        .stat-number {
            font-size: 3.2rem;
            font-weight: 800;
            margin-bottom: 12px;
            color: var(--secondary-color);
            font-family: 'Arial', sans-serif;
        }
        
        .stat-label {
            font-size: 1.15rem;
            opacity: 0.92;
            font-weight: 500;
        }
        
        /* CTA Section */
        .cta-section {
            padding: var(--section-padding);
            background: linear-gradient(135deg, rgba(26, 62, 114, 0.95), rgba(40, 82, 152, 0.95)), 
                        url('images/cta-bg.jpg') no-repeat center center;
            background-size: cover;
            color: white;
            text-align: center;
            position: relative;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
        }
        
        .cta-content {
            position: relative;
            z-index: 1;
        }
        
        .cta-title {
            font-size: 2.6rem;
            font-weight: 700;
            margin-bottom: 25px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .cta-text {
            font-size: 1.15rem;
            margin-bottom: 35px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0.92;
        }
        
        .cta-buttons .btn {
            margin: 0 10px 15px;
            min-width: 180px;
        }
        
        /* Footer */
        .footer {
            background: linear-gradient(135deg, #142f57, var(--primary-color));
            color: white;
            padding: 80px 0 30px;
            position: relative;
        }
        
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            background: linear-gradient(to right, var(--secondary-color), var(--accent-color));
        }
        
        .footer-logo {
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }
        
        .footer-logo img {
            height: 55px;
            margin-right: 15px;
        }
        
        .footer-logo-text {
            font-size: 1.3rem;
            font-weight: 700;
            color: white;
        }
        
        .footer-about {
            margin-bottom: 25px;
            opacity: 0.9;
        }
        
        .footer-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 25px;
            color: var(--secondary-color);
            position: relative;
            display: inline-block;
        }
        
        .footer-title::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 3px;
            bottom: -8px;
            left: 0;
            background-color: var(--secondary-color);
            border-radius: 2px;
        }
        
        .footer-links ul {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: all var(--transition-speed) ease;
            display: inline-block;
        }
        
        .footer-links a:hover {
            color: white;
            padding-left: 8px;
        }
        
        .footer-contact p {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
            opacity: 0.9;
        }
        
        .footer-contact i {
            margin-right: 12px;
            color: var(--secondary-color);
            margin-top: 5px;
            font-size: 1.1rem;
        }
        
        .social-links {
            display: flex;
            margin-top: 20px;
        }
        
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 50%;
            margin-right: 12px;
            transition: all var(--transition-speed) ease;
            font-size: 1.1rem;
        }
        
        .social-links a:hover {
            background-color: var(--secondary-color);
            transform: translateY(-3px);
            color: #000;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 25px;
            margin-top: 50px;
            text-align: center;
            font-size: 0.95rem;
            opacity: 0.8;
        }
        
        /* Animations */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }
        
        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }
        
        .delay-1 { transition-delay: 0.2s; }
        .delay-2 { transition-delay: 0.4s; }
        .delay-3 { transition-delay: 0.6s; }
        
        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background-color: var(--secondary-color);
            color: #000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            z-index: 99;
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-speed) ease;
        }
        
        .back-to-top.active {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-5px);
        }
        
        /* Responsive */
        @media (max-width: 1199px) {
            .hero-title {
                font-size: 2.8rem;
            }
        }
        
        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .section-title {
                font-size: 2.2rem;
            }
            
            .feature-card {
                padding: 25px;
            }
        }
        
        @media (max-width: 768px) {
            :root {
                --section-padding: 70px 0;
            }
            
            .hero-section {
                padding: 120px 0 80px;
                text-align: center;
            }
            
            .hero-title {
                font-size: 2.2rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
                margin-left: auto;
                margin-right: auto;
            }
            
            .hero-buttons {
                justify-content: center;
            }
            
            .hero-image {
                margin-top: 50px;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .section-subtitle {
                font-size: 1.05rem;
                margin-bottom: 40px;
            }
            
            .stat-number {
                font-size: 2.8rem;
            }
            
            .cta-title {
                font-size: 2.2rem;
            }
            
            .footer {
                padding: 60px 0 25px;
                text-align: center;
            }
            
            .footer-logo {
                justify-content: center;
            }
            
            .footer-about {
                margin-left: auto;
                margin-right: auto;
                max-width: 500px;
            }
            
            .footer-title::after {
                left: 50%;
                transform: translateX(-50%);
            }
            
            .social-links {
                justify-content: center;
            }
        }
        
        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-buttons .btn {
                width: 100%;
                margin-right: 0;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
            
            .feature-card {
                padding: 20px;
            }
            
            .feature-icon {
                width: 65px;
                height: 65px;
                font-size: 1.6rem;
            }
            
            .stat-item {
                padding: 15px;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
            
            .cta-title {
                font-size: 1.8rem;
            }
            
            .cta-buttons .btn {
                width: 100%;
                margin: 0 0 15px 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="logo/MSU_Sulu_Logo.png" alt="MSU-SULU Logo" loading="lazy">
                <span>Office of the Director for Research</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item ms-lg-3 my-2 my-lg-0">
                        <a href="login.php" class="btn btn-primary">Login <i class="fas fa-arrow-right ms-2"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="hero-title animate-on-scroll">Automated Research Management System</h1>
                        <p class="hero-subtitle animate-on-scroll delay-1">The comprehensive solution for managing, tracking, analyzing, and reporting research activities across MSU-SULU colleges departments.</p>
                        <div class="hero-buttons animate-on-scroll delay-2">
                            <a href="login.php" class="btn btn-primary">Get Started</a>
                            <a href="#features" class="btn btn-outline-light">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image animate-on-scroll delay-3">
                        <span class="hero-badge">Welcome</span>
                        <img src="images/preview.png" alt="Preview" class="img-fluid" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title animate-on-scroll">Powerful Features</h2>
                <p class="section-subtitle animate-on-scroll delay-1">Designed to simplify research management and enhance productivity</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card animate-on-scroll">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="feature-title">Comprehensive Analytics</h3>
                        <p>Gain insights with real-time analytics and visualizations of research output across all departments and colleges.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card animate-on-scroll delay-1">
                        <div class="feature-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="feature-title">Scopus Integration</h3>
                        <p>Track Scopus-indexed publications and generate reports to showcase your institution's research impact.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card animate-on-scroll delay-2">
                        <div class="feature-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3 class="feature-title">Document Management</h3>
                        <p>Securely store and manage research documents, certificates, and publication proofs in one centralized location.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card animate-on-scroll">
                        <div class="feature-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h3 class="feature-title">Progress Tracking</h3>
                        <p>Monitor research progress from submission to publication with customizable status updates.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card animate-on-scroll delay-1">
                        <div class="feature-icon">
                            <i class="fas fa-download"></i>
                        </div>
                        <h3 class="feature-title">Automated Reports</h3>
                        <p>Generate comprehensive reports for accreditation, funding applications, and institutional assessments.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card animate-on-scroll delay-2">
                        <div class="feature-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h3 class="feature-title">User Authentication</h3>
                        <p>Secure role-based access control to ensure proper authorization levels.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-3">
                    <div class="stat-item animate-on-scroll">
                        <div class="stat-number" data-count="<?php echo $research_stats['total_research']; ?>">0</div>
                        <div class="stat-label">Research Projects</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item animate-on-scroll delay-1">
                        <div class="stat-number" data-count="<?php echo $research_stats['scopus']; ?>">0</div>
                        <div class="stat-label">Scopus Publications</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item animate-on-scroll delay-2">
                        <div class="stat-number" data-count="<?php echo $research_stats['published']; ?>">0</div>
                        <div class="stat-label">Published Works</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item animate-on-scroll delay-3">
                        <div class="stat-number" data-count="<?php echo $research_stats['certificates']; ?>">0</div>
                        <div class="stat-label">Certified Research</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title animate-on-scroll">Ready to Transform Your Research Management?</h2>
                <p class="cta-text animate-on-scroll delay-1">Experience the power of streamlined research management and reporting.</p>
                <div class="animate-on-scroll delay-2">
                    <a href="login.php" class="btn btn-primary btn-lg me-3">Get Started Now</a>
                    <a href="#contact" class="btn btn-outline-light btn-lg">Contact Us</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="footer-logo">
                        <img src="logo/MSU_Sulu_Logo.png" alt="MSU-SULU Logo" loading="lazy">
                        <span class="footer-logo-text">MSU-SULU Research Office</span>
                    </div>
                    <div class="footer-about">
                        <p>The Automated Research Management System streamlines research tracking, analysis, and reporting for MSU-SULU Research Office.</p>
                    </div>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-5 mb-md-0">
                    <h4 class="footer-title">Quick Links</h4>
                    <div class="footer-links">
                        <ul>
                            <li><a href="#home">Home</a></li>
                            <li><a href="#features">Features</a></li>
                            <li><a href="login.php">Login</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-5 mb-md-0">
                    <h4 class="footer-title">Resources</h4>
                    <div class="footer-links">
                        <ul>
                            <li><a href="#">User Guide</a></li>
                            <li><a href="#">Support</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h4 class="footer-title">Contact Us</h4>
                    <div class="footer-contact">
                        <p><i class="fas fa-map-marker-alt"></i>Jolo, Sulu, Philippines</p>
                        <p><i class="fas fa-phone"></i> +63 931 757 7901</p>
                        <p><i class="fas fa-envelope"></i> kuzzello25@gmail.com</p>
                        <p><i class="fas fa-clock"></i> Mon-Fri: 8:00 AM - 5:00 PM</p>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 MSU-SULU Automated Research Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top"><i class="fas fa-arrow-up"></i></a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            const backToTop = document.querySelector('.back-to-top');
            
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            
            if (window.scrollY > 300) {
                backToTop.classList.add('active');
            } else {
                backToTop.classList.remove('active');
            }
        });
        
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    
                    // Update URL without refreshing
                    if (history.pushState) {
                        history.pushState(null, null, targetId);
                    } else {
                        location.hash = targetId;
                    }
                }
            });
        });
        
        // Animate stats counting
        function animateStats() {
            const statItems = document.querySelectorAll('.stat-item');
            
            statItems.forEach(item => {
                const numberElement = item.querySelector('.stat-number');
                const target = parseInt(numberElement.getAttribute('data-count'));
                const duration = 2000; // Animation duration in ms
                const step = target / (duration / 16); // 60fps
                let current = 0;
                
                const counter = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        clearInterval(counter);
                        current = target;
                    }
                    numberElement.textContent = Math.floor(current);
                }, 16);
            });
        }
        
        // Intersection Observer for animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    
                    // If it's the stats section, trigger counting
                    if (entry.target.classList.contains('stats-section')) {
                        animateStats();
                    }
                }
            });
        }, { threshold: 0.1 });
        
        // Observe all elements with animation classes
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });
        
        // Observe sections
        document.querySelectorAll('section').forEach(section => {
            observer.observe(section);
        });
        
        // Back to top button
        document.querySelector('.back-to-top').addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        // Force animate hero elements on load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.hero-section .animate-on-scroll').forEach(el => {
                el.classList.add('animated');
            });
        });
    </script>
</body>
</html>