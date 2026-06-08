<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ config('app.name') }} &bull; E-Learning</title>
    <meta charset="utf-8" />
    <meta name="description" content="E-Learning — learn anytime, anywhere with tutors and courses built around you." />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ asset('metronic/assets/media/logos/elearning-favicon.png') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" />

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e3a8a;
            --accent: #0ea5e9;
            --black: #0a0a0a;
            --black-2: #141414;
            --ink: #0f172a;
            --muted: #64748b;
            --line: #e2e8f0;
            --paper: #f8fafc;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            margin: 0; color: var(--ink); background: #fff;
            line-height: 1.6;
        }
        a { text-decoration: none; }
        .container { max-width: 1140px; margin: 0 auto; padding: 0 1.5rem; }

        /* ---------- Navbar ---------- */
        .nav {
            position: sticky; top: 0; z-index: 50;
            background: rgba(10,10,10,0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .nav-inner { display: flex; align-items: center; justify-content: space-between; height: 72px; }
        .brand { display: flex; align-items: center; gap: 0.65rem; }
        .brand img { width: 38px; height: 38px; }
        .brand span {
            font-family: 'Poppins', sans-serif; font-weight: 700;
            color: #fff; font-size: 1.15rem; letter-spacing: -0.01em;
        }
        .nav-actions { display: flex; align-items: center; gap: 0.75rem; }
        .btn {
            font-family: 'Poppins', sans-serif; font-weight: 600;
            font-size: 0.92rem; border-radius: 10px; cursor: pointer;
            padding: 0.6rem 1.25rem; border: 0; display: inline-block;
            transition: transform .12s ease, filter .18s ease, background .18s ease, color .18s ease;
        }
        .btn-ghost {
            background: transparent; color: #fff;
            border: 1.5px solid rgba(255,255,255,0.25);
        }
        .btn-ghost:hover { background: rgba(255,255,255,0.08); }
        .btn-primary {
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            box-shadow: 0 12px 24px -12px rgba(37,99,235,0.7);
        }
        .btn-primary:hover { transform: translateY(-1px); filter: brightness(1.07); }
        .btn-lg { padding: 0.95rem 1.75rem; font-size: 1rem; }

        /* ---------- Hero ---------- */
        .hero {
            position: relative; overflow: hidden;
            background: var(--black); color: #fff;
            padding: 5.5rem 0 6rem;
        }
        .hero::after {
            content: ""; position: absolute; inset: 0; pointer-events: none;
            background:
                radial-gradient(700px 420px at 78% 18%, rgba(37,99,235,0.20), transparent 62%),
                radial-gradient(560px 420px at 12% 92%, rgba(14,165,233,0.14), transparent 62%);
        }
        .hero-grid {
            position: relative; z-index: 1;
            display: grid; grid-template-columns: 1.05fr 0.95fr;
            align-items: center; gap: 3rem;
        }
        .hero-eyebrow {
            display: inline-block; font-family: 'Poppins', sans-serif;
            font-size: 0.78rem; font-weight: 600; letter-spacing: 0.12em;
            text-transform: uppercase; color: #93c5fd;
            background: rgba(37,99,235,0.15); border: 1px solid rgba(37,99,235,0.3);
            padding: 0.4rem 0.85rem; border-radius: 999px; margin-bottom: 1.5rem;
        }
        .hero h1 {
            font-family: 'Poppins', sans-serif; font-weight: 800;
            font-size: clamp(2.4rem, 4.5vw, 3.6rem); line-height: 1.08;
            margin: 0 0 1.25rem; letter-spacing: -0.02em;
        }
        .hero h1 .accent {
            background: linear-gradient(120deg, #60a5fa, #38bdf8);
            -webkit-background-clip: text; background-clip: text; color: transparent;
        }
        .hero p {
            font-size: 1.12rem; color: #cbd5e1; max-width: 520px;
            margin: 0 0 2rem;
        }
        .hero-cta { display: flex; gap: 0.85rem; flex-wrap: wrap; }
        .hero-art {
            display: flex; align-items: center; justify-content: center;
        }
        .hero-art img {
            width: 280px; height: 280px;
            filter: drop-shadow(0 30px 60px rgba(0,0,0,0.55));
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-16px); } }

        /* ---------- Stats strip ---------- */
        .stats {
            background: var(--black-2); color: #fff;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .stats-grid {
            display: grid; grid-template-columns: repeat(3, 1fr);
            text-align: center; padding: 2.25rem 0;
        }
        .stat strong {
            font-family: 'Poppins', sans-serif; font-size: 2rem;
            display: block; color: #fff;
        }
        .stat span { color: #94a3b8; font-size: 0.92rem; }

        /* ---------- Features ---------- */
        .section { padding: 5rem 0; }
        .section-head { text-align: center; max-width: 620px; margin: 0 auto 3rem; }
        .section-head h2 {
            font-family: 'Poppins', sans-serif; font-weight: 800;
            font-size: clamp(1.8rem, 3vw, 2.4rem); margin: 0 0 0.85rem;
            letter-spacing: -0.02em;
        }
        .section-head p { color: var(--muted); font-size: 1.05rem; margin: 0; }
        .features {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;
        }
        .feature {
            background: #fff; border: 1px solid var(--line);
            border-radius: 18px; padding: 2rem 1.75rem;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }
        .feature:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -22px rgba(15,23,42,0.25);
            border-color: rgba(37,99,235,0.3);
        }
        .feature-icon {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin-bottom: 1.1rem;
            background: linear-gradient(135deg, rgba(37,99,235,0.12), rgba(14,165,233,0.12));
        }
        .feature h3 {
            font-family: 'Poppins', sans-serif; font-weight: 700;
            font-size: 1.15rem; margin: 0 0 0.5rem;
        }
        .feature p { color: var(--muted); font-size: 0.95rem; margin: 0; }

        /* ---------- CTA band ---------- */
        .cta-band {
            background: var(--black); color: #fff;
            border-radius: 24px; padding: 3.5rem 2rem; text-align: center;
            position: relative; overflow: hidden;
        }
        .cta-band::after {
            content: ""; position: absolute; inset: 0; pointer-events: none;
            background: radial-gradient(600px 300px at 50% 0%, rgba(37,99,235,0.22), transparent 60%);
        }
        .cta-band h2 {
            position: relative; z-index: 1;
            font-family: 'Poppins', sans-serif; font-weight: 800;
            font-size: clamp(1.8rem, 3vw, 2.4rem); margin: 0 0 0.85rem;
        }
        .cta-band p { position: relative; z-index: 1; color: #cbd5e1; margin: 0 0 1.75rem; }
        .cta-band .hero-cta { position: relative; z-index: 1; justify-content: center; }

        /* ---------- Footer ---------- */
        .footer {
            background: var(--black-2); color: #94a3b8;
            padding: 2.5rem 0; margin-top: 4rem;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .footer-inner {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 1rem;
        }
        .footer .brand span { font-size: 1.05rem; }
        .footer-links { display: flex; gap: 1.5rem; }
        .footer-links a { color: #94a3b8; font-size: 0.9rem; }
        .footer-links a:hover { color: #fff; }

        @media (max-width: 900px) {
            .hero-grid { grid-template-columns: 1fr; text-align: center; }
            .hero p { margin-left: auto; margin-right: auto; }
            .hero-cta { justify-content: center; }
            .hero-art { order: -1; }
            .hero-art img { width: 200px; height: 200px; }
            .features { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr; gap: 1.5rem; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <header class="nav">
        <div class="container nav-inner">
            <a href="#" class="brand">
                <img src="{{ asset('metronic/assets/media/logos/elearning-logo.png') }}" alt="E-Learning" />
                <span>E-Learning</span>
            </a>
            <nav class="nav-actions">
                <a href="{{ route('login') }}" class="btn btn-ghost">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
            </nav>
        </div>
    </header>

    <!-- Hero -->
    <section class="hero">
        <div class="container hero-grid">
            <div>
                <span class="hero-eyebrow">Online Learning Platform</span>
                <h1>Learn anytime,<br><span class="accent">anywhere you are.</span></h1>
                <p>Book sessions with expert tutors, join interactive classes, and track your progress — all in one platform built around how you learn best.</p>
                <div class="hero-cta">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get Started</a>
                    <a href="{{ route('login') }}" class="btn btn-ghost btn-lg">I have an account</a>
                </div>
            </div>
            <div class="hero-art">
                <img src="{{ asset('metronic/assets/media/logos/elearning-logo.png') }}" alt="E-Learning" />
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats">
        <div class="container stats-grid">
            <div class="stat"><strong>500+</strong><span>Active Students</span></div>
            <div class="stat"><strong>50+</strong><span>Expert Tutors</span></div>
            <div class="stat"><strong>20+</strong><span>Subjects Covered</span></div>
        </div>
    </section>

    <!-- Features -->
    <section class="section">
        <div class="container">
            <div class="section-head">
                <h2>Everything you need to succeed</h2>
                <p>A complete learning experience designed for students and tutors alike.</p>
            </div>
            <div class="features">
                <div class="feature">
                    <div class="feature-icon">📚</div>
                    <h3>Personalized Learning</h3>
                    <p>Courses and tutoring matched to your level, goals, and preferred learning style.</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">👩‍🏫</div>
                    <h3>Expert Tutors</h3>
                    <p>Connect with qualified tutors and book one-on-one sessions that fit your schedule.</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">📈</div>
                    <h3>Track Progress</h3>
                    <p>Monitor exam results and improvement over time with clear, simple insights.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA band -->
    <section class="container">
        <div class="cta-band">
            <h2>Ready to start learning?</h2>
            <p>Create your free account today and join a community of learners.</p>
            <div class="hero-cta">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Create an Account</a>
                <a href="{{ route('register.tutor') }}" class="btn btn-ghost btn-lg">Apply as a Tutor</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-inner">
            <a href="#" class="brand">
                <img src="{{ asset('metronic/assets/media/logos/elearning-logo.png') }}" alt="E-Learning" style="width:32px;height:32px;" />
                <span style="color:#fff;">E-Learning</span>
            </a>
            <div class="footer-links">
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
                <a href="{{ route('register.tutor') }}">Become a Tutor</a>
            </div>
        </div>
        <div class="container" style="margin-top:1.25rem; font-size:0.85rem;">
            &copy; {{ date('Y') }} Nawwarah — E-Learning. All rights reserved.
        </div>
    </footer>

</body>
</html>
