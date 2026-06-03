<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>RMS</title>


    <link rel="stylesheet" href="styles.css" />
    <!--<style>
        .hidden { display: none; }
        #auth-section {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }
        .h1{
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--primary);
        }
        .auth-box {
            width: 100%;
            max-width: 450px;
        }
        .form-toggle {
            display: flex;
            margin-bottom: -1px; /* Overlap border */
        }
        .form-toggle button {
            flex: 1;
            padding: 1rem;
            border: 1px solid #eee;
            background: #f8f9fa;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            transition: var(--transition);
        }
        .form-toggle button.active {
            background: var(--white);
            border-bottom: 3px solid var(--accent);
            color: var(--primary);
        }
        .auth-form h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: var(--primary);
            height:60%;
        }
        .input-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }
        .input-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: lightblue;
        }
        .input-group input, .input-group select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: var(--accent);
            font-family: inherit;
        }
        .auth-form button[type="submit"] {
            width: 100%;
            margin-top: 1rem;
        }
        .auth-form p {
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }
    </style>-->
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <h1 class="logo">Record <span>Management System</span></h1>
            <nav class="main-nav">
                <!-- <a href="Home.html">Home</a> -->
                <a href="About.html">About Us</a>
                <!--<div class="dropdown">
                    <a href="Admissions.html" class="dropbtn">Admissions ▾</a>
                    <div class="dropdown-content">
                        <a href="Admissions.html#process">Apply Now</a>
                        <a href="Admissions.html#requirements">Requirements</a>
                        <a href="Admissions.html#dates">Tuition</a>
                    </div>-->
                <!--</div>
                <a href="Academic.html">Academic</a>-->
                <a href="contact.php">Contact Us</a>
                <a href="login.html">Login</a>
            </nav> 
        </div>
        
    </header>








<main class="home-wrapper">
    <section class="hero-split">
        <div class="content-side">
            <div class="status-indicator">
                <span class="pulse-dot"></span>
                RECORD MANAGEMENT SYSTEM
            </div>
            
            <h1 class="premium-title">
                WELCOME TO <span>RMS</span></h1>
            
            <p class="hero-subtext">
                Ozone RMS is a unified, enterprise-grade environment designed to synchronize 
                student performance and family engagement in real-time.
            </p>
            
            <div class="spec-grid">
                <div class="spec-item">
                    <div class="spec-icon">📊</div>
                    <div class="spec-text"><strong>99.9%</strong><span>Uptime</span></div>
                </div>
                <div class="spec-item">
                    <div class="spec-icon">📁</div>
                    <div class="spec-text"><strong>Secure</strong><span>AES-256</span></div>
                </div>
                <div class="spec-item">
                    <div class="spec-icon">💬</div>
                    <div class="spec-text"><strong>Live</strong><span>Sync</span></div>
                </div>
            </div>

            <div class="hero-actions">
                <a href="login.html" class="cta-primary">Get Started</a>
                <a href="About.html" class="cta-secondary">Learn More</a>
            </div>
        </div>

        <div class="visual-side">
            <div class="image-aside-container">
                <div class="aside-card first-card">
                    <img src="photo_2026-05-15_12-50-29.jpg" alt="Main Campus">
                    <div class="overlay-tag">Principal Campus</div>
                </div><br><br>
                <!--<div class="aside-card second-card">
                    <img src="photo_2026-05-15_12-50-35.jpg" alt="Students">
                    <div class="overlay-tag">Student Life</div>
                </div>-->
                <div class="glow-effect"></div>
            </div>
        </div>
    </section>

    <section class="mission-statement">
        <div class="statement-card">
            <div class="card-line"></div>
            <span class="label">Our Purpose</span>
            <h2>Connecting schools and families through a reliable, secure system that empowers student success and simplifies institutional complexity.</h2>
        </div>
    </section>
</main>




    <!--This is the home page--
    <main class="home-wrapper">
    <section class="hero-split">
        <div class="content-side">
            <div class="brand-badge">
                <span class="dot"></span> 
                Institutional Portal v2.0
            </div>
            
            <h1 class="main-title">
                Precision in <br>
                <span>Student Management</span>
            </h1>
            
            <p class="lead-text">
                Ozone RMS provides a secure, unified ecosystem for schools to track performance, 
                streamline attendance, and enhance parent-faculty communication.
            </p>
            
            <div class="feature-pills">
                <div class="pill">📊 Performance Analytics</div>
                <div class="pill">📅 Attendance Hub</div>
                <div class="pill">🔔 Direct Alerts</div>
            </div>

            <div class="cta-group">
                <a href="login.html" class="btn-solid">Get Started</a>
                <a href="About.html" class="btn-text">System Overview </a>
            </div>
        </div>

        <div class="visual-side">
            <div class="image-box box-a">
                <img src="photo_2026-05-15_12-50-29.jpg" alt="Main Campus">
                <div class="tag">Main Campus</div>
            </div>
            <div class="image-box box-b">
                <img src="photo_2026-05-15_12-50-35.jpg" alt="Student Life">
                <div class="tag">Student Life</div>
            </div>
        </div>
    </section>

    <section class="mission-section">
        <div class="mission-card">
            <div class="mission-icon">🎯</div>
            <h3>Core Mission</h3>
            <p>Our Mission
To create a strong connection between schools and families by providing a reliable system that supports student success, improves communication, and makes record management simple and efficient.</p>
        </div>
    </section>
</main>









-->

    <footer class="site-footer">
        <div class="container">
             <strong>Ozone High School</strong><br> 
            <small>© 2026 Record Management System || All Rights Reserved</small>
        </div>
    </footer>

    <script>
        // Use the same logic but updated for the professional feel
        const loginForm = document.getElementById('login-form');
       // const signupForm = document.getElementById('signup-form');
        const showLoginBtn = document.getElementById('show-login');
       // const showSignupBtn = document.getElementById('show-signup');
        //const linkToSignup = document.getElementById('link-to-signup');
        const linkToLogin = document.getElementById('link-to-login');

        /*function toggleToSignup(e) {
            if(e) e.preventDefault();
            loginForm.classList.add('hidden');
            signupForm.classList.remove('hidden');
            showSignupBtn.classList.add('active');
            showLoginBtn.classList.remove('active');
        }*/

        function toggleToLogin(e) {
            if(e) e.preventDefault();
            signupForm.classList.add('hidden');
            loginForm.classList.remove('hidden');
            showLoginBtn.classList.add('active');
            showSignupBtn.classList.remove('active');
        }

        showSignupBtn.addEventListener('click', toggleToSignup);
        linkToSignup.addEventListener('click', toggleToSignup);
        showLoginBtn.addEventListener('click', toggleToLogin);
        linkToLogin.addEventListener('click', toggleToLogin);

        signupForm.addEventListener('submit', function(e) {
            const pw = document.getElementById('new-password').value;
            const cpw = document.getElementById('confirm-password').value;
            if (pw !== cpw) {
                e.preventDefault();
                alert('Verification failed: Passwords do not match.');
            }
        });
    </script>
</body>
</html>