<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ config('site.name') }} - {{ config('site.motto') }}. {{ config('site.tagline') }}">
    <meta name="keywords" content="streaming, live streaming, content creators, video platform, monetization">
    
    <title>{{ config('site.name') }} - {{ config('site.tagline') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --accent-color: #ec4899;
            --dark-bg: #0f172a;
            --light-bg: #f8fafc;
            --text-dark: #1e293b;
            --text-light: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #1a1a1a;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero-section {
            height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.3) 0%, rgba(45, 45, 45, 0.3) 100%), url('{{ asset('images/static_files/loginimage.png') }}') center/cover no-repeat;
            background-attachment: fixed;
            overflow: hidden;
        }

        .hero-particles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            font-weight: 400;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .hero-social-proof .badge {
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
        }

        .bounce-animation {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        .btn-hero {
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            margin: 0.5rem;
        }

        .btn-primary-hero {
            background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
            color: white;
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.3);
        }

        .btn-primary-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(236, 72, 153, 0.4);
            color: white;
        }

        .btn-secondary-hero {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .btn-secondary-hero:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-2px);
        }

        /* Features Section */
        .features-section {
            padding: 100px 0;
            background: white;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid rgba(99, 102, 241, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(99, 102, 241, 0.2);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: white;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }

        .feature-description {
            color: var(--text-light);
            line-height: 1.6;
        }

        /* Stats Section */
        .stats-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: white;
        }

        .stat-item {
            text-align: center;
            padding: 2rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* CTA Section */
        .cta-section {
            padding: 100px 0;
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
            color: white;
            text-align: center;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
        }

        .cta-description {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        /* Navigation */
        .navbar-custom {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar-custom.scrolled {
            background: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .navbar-brand-custom {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link-custom {
            color: var(--text-dark);
            font-weight: 500;
            margin: 0 1rem;
            transition: color 0.3s ease;
        }

        .nav-link-custom:hover {
            color: var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .hero-image img {
                max-height: 300px !important;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .hero-social-proof .badge {
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            .cta-title {
                font-size: 2rem;
            }
        }

        /* Loading Animation */
        .loading-spinner {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Loading Screen -->
    <div class="loading-spinner" id="loadingSpinner">
        <div class="spinner"></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar-custom" id="navbar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="navbar-brand-custom">
                    <img src="{{ asset('images/static_files/logo.png') }}" alt="{{ config('site.name') }}" style="height: 30px; width: auto; margin-right: 8px;">
                    {{ config('site.name') }}
                </div>
                <div class="d-none d-md-flex">
                    <a href="#features" class="nav-link-custom">Features</a>
                    <a href="#superstars" class="nav-link-custom">Superstars</a>
                    <a href="#stats" class="nav-link-custom">Stats</a>
                    <a href="#cta" class="nav-link-custom">Get Started</a>
                </div>
                <div>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/admin/dashboard') }}" class="btn btn-primary btn-sm rounded-pill">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm rounded-pill me-2">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary btn-sm rounded-pill">
                                    <i class="bi bi-person-plus me-2"></i>Sign Up
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="hero">
        <div class="hero-particles" id="particles"></div>
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12">
                    <div class="hero-content text-center">
                        <div class="hero-buttons mb-4" data-aos="fade-up" data-aos-delay="200">
                            <a href="#features" class="btn-hero btn-primary-hero btn-lg me-3">
                                <i class="bi bi-play-circle me-2"></i>Start Streaming
                            </a>
                            <a href="#superstars" class="btn-hero btn-secondary-hero btn-lg">
                                <i class="bi bi-stars me-2"></i>Watch Superstars
                            </a>
                        </div>
                        <div class="hero-social-proof" data-aos="fade-up" data-aos-delay="300">
                            <div class="d-flex justify-content-center align-items-center flex-wrap">
                                <span class="badge bg-dark text-white me-3 mb-2">
                                    <i class="bi bi-people-fill me-2"></i>10,000+ Creators
                                </span>
                                <span class="badge bg-dark text-white me-3 mb-2">
                                    <i class="bi bi-star-fill me-2"></i>500+ Superstars
                                </span>
                                <span class="badge bg-dark text-white mb-2">
                                    <i class="bi bi-broadcast me-2"></i>Live 24/7
                                </span>
                            </div>
                        </div>
                        <div class="scroll-indicator mt-5" data-aos="fade-up" data-aos-delay="600">
                            <div class="bounce-animation">
                                <i class="bi bi-chevron-down text-white fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold mb-3" data-aos="fade-up">
                    Why Choose StreamHub?
                </h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">
                    Everything you need to succeed as a content creator
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-camera-video"></i>
                        </div>
                        <h3 class="feature-title">HD Streaming</h3>
                        <p class="feature-description">
                            Stream in crystal-clear 1080p HD with our advanced streaming technology. 
                            Low latency, high quality, zero buffering.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <h3 class="feature-title">Monetization</h3>
                        <p class="feature-description">
                            Multiple revenue streams including subscriptions, donations, 
                            and ad revenue. Get paid what you deserve.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <h3 class="feature-title">Community</h3>
                        <p class="feature-description">
                            Build a loyal community with chat, polls, and interactive features. 
                            Engage with your audience in real-time.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h3 class="feature-title">Analytics</h3>
                        <p class="feature-description">
                            Detailed insights into your performance. Track growth, 
                            engagement, and revenue with professional analytics.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3 class="feature-title">Security</h3>
                        <p class="feature-description">
                            Enterprise-grade security to protect your content and community. 
                            Safe, secure, and reliable streaming platform.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="700">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h3 class="feature-title">24/7 Support</h3>
                        <p class="feature-description">
                            Round-the-clock support from our dedicated team. 
                            We're here to help you succeed every step of the way.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Superstars Section -->
    <section class="superstars-section py-5" id="superstars" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold mb-3" data-aos="fade-up">
                    Stream Your Superstars
                </h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">
                    Connect with your favorite creators and watch exclusive content
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="superstar-avatar mb-3">
                                <i class="bi bi-person-circle" style="font-size: 3rem; color: var(--primary-color);"></i>
                            </div>
                            <h5 class="card-title">Live Streams</h5>
                            <p class="card-text text-muted">
                                Watch your favorite superstars stream live in real-time. 
                                Never miss a moment with instant notifications.
                            </p>
                            <div class="mt-3">
                                <span class="badge bg-danger rounded-pill">
                                    <i class="bi bi-circle-fill me-1"></i>Live Now
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="superstar-avatar mb-3">
                                <i class="bi bi-star-fill" style="font-size: 3rem; color: #fbbf24;"></i>
                            </div>
                            <h5 class="card-title">Exclusive Content</h5>
                            <p class="card-text text-muted">
                                Access premium content from top superstars. 
                                Behind-the-scenes footage, tutorials, and more.
                            </p>
                            <div class="mt-3">
                                <span class="badge bg-warning rounded-pill">
                                    <i class="bi bi-crown me-1"></i>Premium
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="superstar-avatar mb-3">
                                <i class="bi bi-chat-heart-fill" style="font-size: 3rem; color: #ec4899;"></i>
                            </div>
                            <h5 class="card-title">Direct Interaction</h5>
                            <p class="card-text text-muted">
                                Chat directly with superstars during streams. 
                                Get personalized responses and build connections.
                            </p>
                            <div class="mt-3">
                                <span class="badge bg-success rounded-pill">
                                    <i class="bi bi-chat-dots me-1"></i>Interactive
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="500">
                <div class="d-flex justify-content-center align-items-center mb-3">
                    <img src="{{ asset('images/static_files/logo.png') }}" alt="{{ config('site.name') }}" style="height: 60px; width: auto; margin-right: 15px;">
                    <h3 class="mb-0">Join the Superstar Community</h3>
                </div>
                <p class="text-muted mb-4">
                    Discover amazing creators, support their work, and become part of the streaming revolution
                </p>
                <div>
                    <a href="#cta" class="btn btn-primary btn-lg rounded-pill me-3">
                        <i class="bi bi-play-circle me-2"></i>Start Watching
                    </a>
                    <a href="#features" class="btn btn-outline-primary btn-lg rounded-pill">
                        <i class="bi bi-info-circle me-2"></i>Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section" id="stats">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6" data-aos="fade-up">
                    <div class="stat-item">
                        <div class="stat-number" data-counter="10000">0</div>
                        <div class="stat-label">Active Streamers</div>
                    </div>
                </div>
                <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-item">
                        <div class="stat-number" data-counter="5000000">0</div>
                        <div class="stat-label">Monthly Viewers</div>
                    </div>
                </div>
                <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-item">
                        <div class="stat-number" data-counter="25000000">0</div>
                        <div class="stat-label">Hours Streamed</div>
                    </div>
                </div>
                <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-item">
                        <div class="stat-number" data-counter="99">0</div>
                        <div class="stat-label">% Uptime</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section" id="cta">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="cta-title" data-aos="fade-up">
                        Ready to Start Your Streaming Journey?
                    </h2>
                    <p class="cta-description" data-aos="fade-up" data-aos-delay="100">
                        Join thousands of creators who are already building their audience 
                        and earning money with StreamHub.
                    </p>
                    <div data-aos="fade-up" data-aos-delay="200">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-hero btn-primary-hero btn-lg">
                                <i class="bi bi-rocket-takeoff me-2"></i>Start Free Today
                            </a>
                        @else
                            <a href="#features" class="btn-hero btn-primary-hero btn-lg">
                                <i class="bi bi-play-circle me-2"></i>Get Started
                            </a>
                        @endif
                        <a href="#features" class="btn-hero btn-secondary-hero btn-lg">
                            <i class="bi bi-info-circle me-2"></i>Explore Features
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-white py-4" style="background: rgba(0,0,0,0.2);">
        <div class="container text-center">
            <p class="mb-0">
                &copy; 2024 StreamHub. The future of streaming is here.
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true
        });

        // Hide loading screen
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loadingSpinner').style.opacity = '0';
                setTimeout(function() {
                    document.getElementById('loadingSpinner').style.display = 'none';
                }, 500);
            }, 1000);
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Generate particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 50;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 6 + 's';
                particle.style.animationDuration = (Math.random() * 3 + 3) + 's';
                particlesContainer.appendChild(particle);
            }
        }

        // Counter animation
        function animateCounters() {
            const counters = document.querySelectorAll('[data-counter]');
            const speed = 200;

            counters.forEach(counter => {
                const animate = () => {
                    const target = +counter.getAttribute('data-counter');
                    const count = +counter.innerText.replace(/[^0-9]/g, '');
                    const increment = target / speed;

                    if (count < target) {
                        counter.innerText = Math.ceil(count + increment);
                        setTimeout(animate, 10);
                    } else {
                        counter.innerText = target.toLocaleString();
                    }
                };

                // Start animation when element is in view
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            animate();
                            observer.unobserve(entry.target);
                        }
                    });
                });

                observer.observe(counter);
            });
        }

        // Initialize
        createParticles();
        animateCounters();
    </script>
</body>
</html>