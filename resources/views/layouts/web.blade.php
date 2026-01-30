<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('description', 'Munau College of Health Sciences and Technology - Leading Health Sciences Institution')">
    <title>@yield('title', 'Munau College of Health Sciences and Technology')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #1e3a8a;
            --secondary-color: #059669;
            --accent-color: #f59e0b;
            --light-bg: #f3f4f6;
            --text-dark: #1f2937;
            --text-light: #6b7280;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--primary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #1e40af;
            border-color: #1e40af;
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            font-weight: 600;
        }

        .btn-secondary:hover {
            background-color: #047857;
            border-color: #047857;
        }

        /* Navbar */
        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color) !important;
        }

        .nav-link {
            color: var(--text-dark) !important;
            font-weight: 500;
            transition: color 0.3s;
            margin: 0 0.5rem;
        }

        .nav-link:hover {
            color: var(--secondary-color) !important;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            color: white;
            padding: 6rem 0;
            text-align: center;
        }

        .hero h1 {
            color: white;
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            color: rgba(255,255,255,0.9);
        }

        /* Section */
        .section {
            padding: 5rem 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
            font-size: 2.5rem;
        }

        /* Footer */
        footer {
            background-color: var(--primary-color);
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 5rem;
        }

        footer h6 {
            color: white;
            margin-bottom: 1rem;
        }

        footer a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s;
        }

        footer a:hover {
            color: var(--accent-color);
        }

        .footer-section {
            margin-bottom: 2rem;
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        /* Card */
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 0.75rem;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }

        .card-title {
            color: var(--primary-color);
            font-weight: 700;
        }

        /* Breadcrumb */
        .breadcrumb {
            background-color: var(--light-bg);
            border-radius: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero {
                padding: 3rem 0;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .section {
                padding: 3rem 0;
            }

            .section-title {
                font-size: 1.75rem;
            }
        }
    </style>

    @yield('additional-css')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-graduation-cap"></i> Munau College
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">About</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="programDropdown" role="button" data-bs-toggle="dropdown">
                            Academics
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="programDropdown">
                            <li><a class="dropdown-item" href="{{ route('programs') }}">Programmes</a></li>
                            <li><a class="dropdown-item" href="{{ route('departments') }}">Departments</a></li>
                            <li><a class="dropdown-item" href="{{ route('admission-requirements') }}">Admission</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="infoDropdown" role="button" data-bs-toggle="dropdown">
                            Information
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="infoDropdown">
                            <li><a class="dropdown-item" href="{{ route('news') }}">News</a></li>
                            <li><a class="dropdown-item" href="{{ route('events') }}">Events</a></li>
                            <li><a class="dropdown-item" href="{{ route('gallery') }}">Gallery</a></li>
                            <li><a class="dropdown-item" href="{{ route('downloads') }}">Downloads</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('management') }}">Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white ms-2" href="{{ route('login') }}">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-3 footer-section">
                    <h6>About Munau</h6>
                    <div class="footer-links">
                        <ul>
                            <li><a href="{{ route('about') }}">About Us</a></li>
                            <li><a href="{{ route('about') }}#vision">Vision & Mission</a></li>
                            <li><a href="{{ route('about') }}#values">Core Values</a></li>
                            <li><a href="{{ route('management') }}">Management</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 footer-section">
                    <h6>Academics</h6>
                    <div class="footer-links">
                        <ul>
                            <li><a href="{{ route('departments') }}">Departments</a></li>
                            <li><a href="{{ route('programs') }}">Programmes</a></li>
                            <li><a href="{{ route('admission-requirements') }}">Admission</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 footer-section">
                    <h6>Resources</h6>
                    <div class="footer-links">
                        <ul>
                            <li><a href="{{ route('news') }}">News & Updates</a></li>
                            <li><a href="{{ route('events') }}">Events</a></li>
                            <li><a href="{{ route('downloads') }}">Downloads</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 footer-section">
                    <h6>Contact</h6>
                    <div class="footer-links">
                        <ul>
                            <li><a href="mailto:info@munaucollege.edu.ng">info@munaucollege.edu.ng</a></li>
                            <li><a href="tel:+234xxx">+234 (0) XXX XXX XXXX</a></li>
                            <li><a href="{{ route('contact') }}">Contact Form</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="bg-light">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; 2024 Munau College of Health Sciences and Technology. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-white me-3"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('additional-js')
</body>
</html>
