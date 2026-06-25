<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oxygen Gym & Spa - Hazmiyeh</title>
        <link rel="icon" href="{{ asset('images/o2-icon.png') }}" type="image/png">

    <!-- Assuming you have Bootstrap 5 compiled, or use the CDN for now -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom Styles for the Landing Page */
        .hero-section {
            /* Using a live high-quality gym photo from Unsplash */
            background: linear-gradient(to right, rgba(0, 0, 0, 0.9) 10%, rgba(0, 0, 0, 0.3) 100%),
                url('/images/hero-bg.webp') no-repeat center right;
            background-size: cover;
            height: 100vh;
            color: white;
            display: flex;
            align-items: center;                                                                                                
        }

        .hero-title {
            font-size: 4.5rem;
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1.1;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .hero-subtitle {
            font-size: 1.25rem;
            font-weight: 300;
            max-width: 600px;
            color: #e0e0e0;
        }

        .text-oxygen {
            color: #00d2ff;
            /* Adjust to match your blue-green gradient brand */
        }

        .coach-card img {
            height: 300px;
            object-fit: cover;
        }

        /* Fix for Tailwind & Bootstrap clash hiding the navbar */
        .navbar-collapse.collapse {
            visibility: visible !important;
        }
    </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-dark text-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-black fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-oxygen app-brand-text " href="#">OXYGEN GYM</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto text-center mt-3 mt-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#location">Location</a></li>
                    <li class="nav-item"><a class="nav-link" href="#coaches">Personal Training</a></li>
                    {{-- <li class="nav-item"><a class="nav-link" href="#about">About</a></li> --}}
                    <!-- Link to your internal system login -->
                    <li class="nav-item mt-2 mt-lg-0"><a class="nav-link btn btn-outline-info ms-lg-3"
                            href="{{ route('login') }}">Staff Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 text-start">
                    <!-- Small badge above the title -->
                    <span
                        class="badge bg-info text-dark mb-3 px-3 py-2 rounded-pill fw-bold text-uppercase tracking-wide">
                        Welcome to Hazmiyeh's Best
                    </span>

                    <h1 class="hero-title mb-4">
                        Transform Your Body at <br>
                        <span class="text-oxygen">Oxygen Gym</span>
                    </h1>

                    <p class="hero-subtitle mb-5 fw-bold text-white">
                        State-of-the-art equipment, expert coaches, and a community that pushes you further. Your
                        fitness journey starts here.
                    </p>

                    <!-- Dual Buttons -->
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="#location"
                            class="btn btn-info btn-lg px-5 py-3 rounded-pill fw-bold text-dark shadow-sm">
                            Visit Us Today
                        </a>
                        <a href="#coaches"
                            class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill fw-bold shadow-sm">
                            Meet Our Coaches
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About & Location Section -->
    <section id="location" class="py-5 bg-black">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="fw-bold text-oxygen mb-4">Where to find us</h2>
                    <p class="lead">Located in the heart of Hazmiyeh, Oxygen Gym & Spa offers everything you need to
                        reach your fitness goals.</p>
                    <ul class="list-unstyled fs-5 mt-4">
                        <li class="mb-3">📍 <strong>Address:</strong> [Insert Hazmiyeh Address Here]</li>
                        <li class="mb-3">📞 <strong>Phone:</strong> +961 XX XXX XXX</li>
                        <li class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                🕒 <strong>Hours:</strong>
                            </div>
                            <div class="ms-4">
                                <div class="d-flex justify-content-between border-bottom border-secondary pb-1 mb-1"
                                    style="max-width: 270px;">
                                    <span>Mon - Fri</span>
                                    <span>6:00 AM - 11:00 PM</span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom border-secondary pb-1 mb-1"
                                    style="max-width: 270px;">
                                    <span>Saturday</span>
                                    <span>7:00 AM - 8:00 PM</span>
                                </div>
                                <div class="d-flex justify-content-between text-danger" style="max-width: 270px;">
                                    <span>Sunday</span>
                                    <span>Closed</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <!-- Placeholder for Google Maps iframe -->
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                        style="height: 350px;">
                        <p class="text-white-50">Google Maps Embed Goes Here</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Coaches Section -->
    <section id="coaches" class="py-5">
        <div class="container py-5 text-center">
            <h2 class="fw-bold text-oxygen mb-5">Meet Our Expert Coaches</h2>
            <div class="row g-4">
                <!-- Coach 1 -->
                <div class="col-md-4">
                    <div class="card bg-black border-secondary text-light coach-card h-100">
                        <div class="card-body">
                            <h4 class="card-title text-oxygen">Coach Name</h4>
                            <p class="card-text">Specializes in weight loss, strength training, and functional fitness.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Coach 2 -->
                <div class="col-md-4">
                    <div class="card bg-black border-secondary text-light coach-card h-100">
                        <div class="card-body">
                            <h4 class="card-title text-oxygen">Coach Name</h4>
                            <p class="card-text">Bodybuilding champion with 10 years of experience in muscle
                                hypertrophy.</p>
                        </div>
                    </div>
                </div>
                <!-- Coach 3 -->
                <div class="col-md-4">
                    <div class="card bg-black border-secondary text-light coach-card h-100">
                        <div class="card-body">
                            <h4 class="card-title text-oxygen">Coach Name</h4>
                            <p class="card-text">Expert in mobility, recovery, and high-intensity interval training
                                (HIIT).</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black text-center py-4 border-top border-secondary">
        <div class="container">
            <p class="mb-0 text-white-50">&copy; {{ date('Y') }} Oxygen Gym & Spa. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
