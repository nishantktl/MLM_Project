<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Winning - Earn Daily</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">

    <style>
        body {
            background: #0f172a;
            color: #fff;
            font-family: Arial, sans-serif;
        }

        .hero-section {
            padding: 100px 0;
            background: linear-gradient(135deg, #0f172a, #1e293b);
        }

        .highlight {
            color: #22c55e;
        }

        .stat-card,
        .feature-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 25px;
            height: 100%;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #22c55e;
        }

        .feature-icon {
            font-size: 2.5rem;
            color: #22c55e;
        }

        .cta-box {
            background: linear-gradient(135deg, #22c55e, #2563eb);
            border-radius: 24px;
            padding: 50px 30px;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px 0;
            color: #94a3b8;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-transparent py-3">
    <div class="container">
        <a class="navbar-brand" href="#">Money Winning</a>
        <div>
            <a href="<?= base_url('login') ?>" class="btn btn-outline-light me-2">Login</a>
            <a href="<?= base_url('register') ?>" class="btn btn-success">Register</a>
        </div>
    </div>
</nav>

<section class="hero-section text-center text-lg-start">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <span class="badge bg-success-subtle text-success px-3 py-2 mb-3">🎁 Get ₹50 Signup Bonus</span>
                <h1 class="display-4 fw-bold mb-4">
                    Earn, Play and <span class="highlight">Win Daily</span>
                </h1>
                <p class="lead text-light opacity-75 mb-4">
                    Join Money Winning and receive ₹50 instantly after registration.
                    Secure wallet, transparent withdrawals, and daily earning opportunities.
                </p>
                <a href="<?= base_url('register') ?>" class="btn btn-success btn-lg px-4 me-2">Get Started</a>
                <a href="<?= base_url('login') ?>" class="btn btn-outline-light btn-lg px-4">Login</a>
            </div>

            <div class="col-lg-6">
                <div class="feature-card">
                    <h4 class="mb-4">Why Choose Us?</h4>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3"><i class="mdi mdi-check-circle text-success me-2"></i> Instant registration</li>
                        <li class="mb-3"><i class="mdi mdi-check-circle text-success me-2"></i> ₹50 signup bonus</li>
                        <li class="mb-3"><i class="mdi mdi-check-circle text-success me-2"></i> Secure wallet system</li>
                        <li class="mb-3"><i class="mdi mdi-check-circle text-success me-2"></i> Fast withdrawals</li>
                        <li><i class="mdi mdi-check-circle text-success me-2"></i> Admin support</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-value">10K+</div>
                    <div>Registered Users</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-value">₹25L+</div>
                    <div>Total Withdrawals</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-value">500+</div>
                    <div>Daily Winners</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-value">₹50</div>
                    <div>Signup Bonus</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Platform Features</h2>
            <p class="text-light opacity-75">Everything you need to manage your earnings.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="feature-card text-center">
                    <div class="feature-icon"><i class="mdi mdi-flash"></i></div>
                    <h5 class="mt-3">Instant Registration</h5>
                    <p class="text-light opacity-75 small">Create your account in less than a minute.</p>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="feature-card text-center">
                    <div class="feature-icon"><i class="mdi mdi-gift"></i></div>
                    <h5 class="mt-3">Signup Bonus</h5>
                    <p class="text-light opacity-75 small">Receive ₹50 instantly after registration.</p>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="feature-card text-center">
                    <div class="feature-icon"><i class="mdi mdi-wallet"></i></div>
                    <h5 class="mt-3">Secure Wallet</h5>
                    <p class="text-light opacity-75 small">Manage deposits, income, and withdrawals.</p>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="feature-card text-center">
                    <div class="feature-icon"><i class="mdi mdi-cash-fast"></i></div>
                    <h5 class="mt-3">Fast Withdrawals</h5>
                    <p class="text-light opacity-75 small">Quick approval and transparent charges.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="cta-box text-center text-white">
            <h2 class="fw-bold mb-3">Ready to Start?</h2>
            <p class="mb-4">Create your account today and claim your ₹50 welcome bonus.</p>
            <a href="<?= base_url('register') ?>" class="btn btn-light btn-lg px-5 fw-bold">Register Now</a>
        </div>
    </div>
</section>

<footer class="text-center">
    <div class="container">
        © <?= date('Y') ?> Money Winning. All rights reserved.
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
