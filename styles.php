:root {
    --primary: #002147;
    --accent: #D4AF37;
    --accent-hover: #B8860B;

    --text-main: #2d3436;
    --text-muted: #636e72;

    --bg-light: #f5f7fb;
    --white: #ffffff;

    --border: #e6e6e6;

    --shadow-sm: 0 3px 10px rgba(0,0,0,0.05);
    --shadow-md: 0 12px 30px rgba(0,0,0,0.10);

    --transition: all 0.3s ease;
    --radius: 10px;
}







/* Premium Aside Gallery */

:root {
    --brand-dark: #0f172a;
    --brand-primary: #3b82f6;
    --brand-slate: #64748b;
    --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    --card-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
}

.home-wrapper {
    max-width: 1400px;
    margin: 0 auto;
    padding: 80px 40px;
    font-family: 'Inter', sans-serif;
}

.hero-split {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    margin-bottom: 100px;
}

/* Status Dot Animation */
.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #ffffff;
    padding:  16px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--brand-dark);
    box-shadow: var(--card-shadow);
    margin-bottom: 2rem;
}

.pulse-dot {
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
    animation: pulse-green 2s infinite;
}

/* Typography */
.premium-title {
    font-size: 4rem;
    line-height: 1.1;
    font-weight: 800;
    color: var(--brand-dark);
    letter-spacing: -2px;
    margin-bottom: 1.5rem;
}

.premium-title span {
    background: linear-gradient(to right, #3b82f6, #2563eb);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-subtext {
    font-size: 1.15rem;
    color: var(--brand-slate);
    line-height: 1.7;
    margin-bottom: 2.5rem;
}

/* Spec Grid */
.spec-grid {
    display: flex;
    gap: 30px;
    margin-bottom: 3rem;
}

.spec-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.spec-icon { font-size: 1.4rem; }
.spec-text strong { display: block; font-size: 1rem; color: var(--brand-dark); }
.spec-text span { font-size: 0.75rem; color: var(--brand-slate); }

/* Aside Image Gallery */
.image-aside-container {
    position: relative;
    display: flex;
    gap: 20px;
    height: 550px;
}

.aside-card {
    position: relative;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 30px 60px rgba(0,0,0,0.1);
    flex: 1;
    transition: transform 0.4s ease;
}

.first-card { transform: translateY(-30px); }
.second-card { transform: translateY(30px); }

.aside-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.aside-card:hover { transform: translateY(0) scale(1.02); }

.overlay-tag {
    position: absolute;
    bottom: 20px;
    left: 20px;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    padding: 6px 14px;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 700;
}

/* Mission Section */
.mission-statement {
    display: flex;
    justify-content: center;
}

.statement-card {
    background: white;
    padding: 60px;
    border-radius: 40px;
    box-shadow: var(--card-shadow);
    text-align: center;
    max-width: 900px;
    position: relative;
}

.card-line {
    width: 50px;
    height: 4px;
    background: var(--brand-primary);
    margin: 0 auto 20px;
    border-radius: 2px;
}

.statement-card .label {
    color: var(--brand-primary);
    font-weight: 800;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 1.5px;
}

.statement-card h2 {
    font-size: 1.8rem;
    margin-top: 1.5rem;
    line-height: 1.5;
    color: var(--brand-dark);
}

/* CTA Buttons */
.hero-actions { display: flex; gap: 20px; }

.cta-primary {
    background: var(--brand-dark);
    color: white;
    padding: 16px 36px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
}

.cta-primary:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }

.cta-secondary {
    color: var(--brand-dark);
    padding: 16px;
    text-decoration: none;
    font-weight: 600;
    border-bottom: 2px solid transparent;
    transition: 0.3s;
}

.cta-secondary:hover { border-color: var(--brand-primary); }

@keyframes pulse-green {
    0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}



/*About us page*/
/* --- About Page Premium Variables & Resets --- */
/* --- Core Page Variables & Structure Rules --- */
:root {
    --navy-dark: #0f172a;
    --blue-primary: #2563eb;
    --slate-text: #475569;
    --light-slate: #f8fafc;
    --border-color: #e2e8f0;
    --smooth-blur: rgba(255, 255, 255, 0.85);
}

.about-wrapper {
    max-width: 1400px;
    margin: 0 auto;
    padding: 80px 40px;
    font-family: 'Inter', sans-serif;
}

/* --- Top Header Typography --- */
.about-hero {
    text-align: center;
    max-width: 850px;
    margin: 0 auto 90px auto;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #e0e7ff;
    color: var(--blue-primary);
    padding: 6px 14px;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 24px;
}

.badge-dot {
    width: 6px;
    height: 6px;
    background: var(--blue-primary);
    border-radius: 50%;
}

.about-main-title {
    font-size: 3.5rem;
    font-weight: 900;
    color: var(--navy-dark);
    letter-spacing: -2px;
    margin-bottom: 20px;
    line-height: 1.1;
}

.about-main-title span {
    color: var(--blue-primary);
}

.about-subtitle {
    font-size: 1.25rem;
    line-height: 1.6;
    color: var(--slate-text);
}

/* --- Split Presentation Layout --- */
.about-split-layout {
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 70px;
    align-items: flex-start;
    margin-bottom: 120px;
}

/* Text Side Content Block Styles */
.section-label {
    display: block;
    color: var(--blue-primary);
    font-weight: 800;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 1.5px;
    margin-bottom: 12px;
}

.content-block h2 {
    font-size: 2.2rem;
    color: var(--navy-dark);
    font-weight: 800;
    letter-spacing: -1px;
    margin-bottom: 20px;
}

.content-block p {
    font-size: 1.1rem;
    line-height: 1.7;
    color: var(--slate-text);
    margin-bottom: 30px;
}

/* Clean Image Block Positioned Below Intro Text */
.embedded-showcase-image {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    height: 320px;
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
    margin-bottom: 45px;
}

.embedded-showcase-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Dynamic Value Matrix Cards */
.values-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

.value-card {
    background: var(--light-slate);
    border: 1px solid var(--border-color);
    padding: 30px;
    border-radius: 20px;
}

.card-indicator-line {
    width: 40px;
    height: 4px;
    background: var(--blue-primary);
    border-radius: 10px;
    margin-bottom: 20px;
}

.value-card h3 {
    font-size: 1.25rem;
    color: var(--navy-dark);
    margin-bottom: 12px;
    font-weight: 700;
}

.value-card p {
    font-size: 0.95rem;
    line-height: 1.6;
    color: var(--slate-text);
    margin: 0;
}

/* --- Right Side Aside Showcase Visual Layout --- */
.image-aside-container {
    position: relative;
    height: 720px; /* Aligns comfortably with the left side stack height */
}

.aside-showcase-card {
    position: relative;
    width: 100%;
    height: 100%;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 30px 60px rgba(15, 23, 42, 0.12);
    background: #ffffff;
    transition: transform 0.4s ease;
}

.aside-showcase-card:hover {
    transform: translateY(-5px);
}

.aside-showcase-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* Shared Premium Micro-Tag Element styling */
.overlay-tag {
    position: absolute;
    bottom: 24px;
    left: 24px;
    background: var(--smooth-blur);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 8px 18px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--navy-dark);
    border: 1px solid rgba(255, 255, 255, 0.4);
}

.glow-effect {
    position: absolute;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(37, 99, 235, 0.05) 0%, rgba(255,255,255,0) 70%);
    top: 30%;
    right: -10%;
    z-index: -1;
    pointer-events: none;
}

/* --- Bottom Core System Feature Showcase Block --- */
.system-focus-card {
    background: var(--navy-dark);
    color: #ffffff;
    padding: 60px;
    border-radius: 32px;
    box-shadow: 0 40px 80px -15px rgba(15, 23, 42, 0.2);
}

.tech-pill {
    display: inline-block;
    background: rgba(255, 255, 255, 0.1);
    color: #93c5fd;
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 16px;
}

.system-focus-card h2 {
    font-size: 2rem;
    font-weight: 800;
    margin: 0 0 35px 0;
}

.system-desc-split {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 30px;
}

.system-desc-split p {
    font-size: 1.05rem;
    line-height: 1.7;
    color: #94a3b8;
    margin: 0;
}

/* --- Responsive Layout Adjustments --- */
@media (max-width: 1024px) {
    .about-split-layout {
        grid-template-columns: 1fr;
        gap: 50px;
    }
    
    .image-aside-container {
        height: 450px;
    }
    
    .system-desc-split {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .about-main-title {
        font-size: 2.5rem;
    }
}


/*home layout Container Layout */
/* Base Styles /
:root {
    --dark: #0f172a;
    --primary: #2563eb;
    --gray: #64748b;
    --soft-bg: #f8fafc;
}

.home-wrapper {
    max-width: 1400px;
    margin: 0 auto;
    padding: 80px 40px;
}

/* Hero Split Layout *
.hero-split {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 100px;
    align-items: center;
    margin-bottom: 100px;
}

/* Content Styling *
.brand-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #e0e7ff;
    color: var(--primary);
    padding: 6px 14px;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 24px;
}

.dot {
    width: 6px;
    height: 6px;
    background: #10b981;
    border-radius: 50%;
}

.main-title {
    font-size: 4rem;
    line-height: 1.1;
    font-weight: 800;
    color: var(--dark);
    margin-bottom: 24px;
}

.main-title span { color: var(--primary); }

.lead-text {
    font-size: 1.2rem;
    color: var(--gray);
    line-height: 1.6;
    margin-bottom: 40px;
    max-width: 500px;
}

/* Feature Pills *
.feature-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 40px;
}

.pill {
    background: white;
    border: 1px solid #e2e8f0;
    padding: 10px 20px;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--dark);
}

/* Image Visuals *
.visual-side {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.image-box {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.image-box:hover { transform: translateY(-10px); }

.image-box img {
    width: 100%;
    height: 450px;
    object-fit: cover;
    display: block;
}

.tag {
    position: absolute;
    bottom: 20px;
    left: 20px;
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(5px);
    color: white;
    padding: 6px 16px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
}

/* Mission Card *
.mission-section {
    text-align: center;
}

.mission-card {
    background: var(--soft-bg);
    padding: 60px;
    border-radius: 30px;
    border: 1px solid #e2e8f0;
}

.mission-icon { font-size: 2.5rem; margin-bottom: 20px; }

.mission-card h3 {
    font-size: 1.8rem;
    margin-bottom: 15px;
    color: var(--dark);
}

.mission-card p {
    font-size: 1.15rem;
    color: var(--gray);
    max-width: 700px;
    margin: 0 auto;
}

/* Buttons *
.btn-solid {
    background: var(--dark);
    color: white;
    padding: 16px 32px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 700;
}

.btn-text {
    margin-left: 20px;
    color: var(--primary);
    text-decoration: none;
    font-weight: 700;
}










/* Main Section Spacing 
.hero-visual {
    padding: 60px 0;
    max-width: 1200px;
    margin: 0 auto;
}

/* Grid Layout *
.image-grid {
    display: grid;
    grid-template-columns: 1fr 1fr; /* Two equal columns *
    gap: 30px;                     /* Professional spacing between images *
    padding: 0 20px;
}

/* Individual Image Card Styling *
.image-card {
    position: relative;
    border-radius: 16px;           /* Modern rounded look *
    overflow: hidden;
    background: #fff;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08); /* Soft professional shadow *
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.image-card:hover {
    transform: translateY(-8px);   /* Interactive lift effect *
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
}

.image-card img {
    width: 100%;
    height: 350px;                 /* Fixed height for uniformity *
    object-fit: cover;             /* Prevents stretching *
    display: block;
}

/* Subtle Overlay Label *
.image-label {
    position: absolute;
    bottom: 20px;
    left: 20px;
    background: rgba(255, 255, 255, 0.9);
    padding: 8px 16px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 700;
    color: #0f172a;
    backdrop-filter: blur(4px);
}

/* Main Section Spacing *
.hero-visual {
    padding: 60px 0;
    max-width: 1200px;
    margin: 0 auto;
}

/* Grid Layout *
.image-grid {
    display: grid;
    grid-template-columns: 1fr 1fr; /* Two equal columns */
   /* gap: 30px;                    

/* Individual Image Card Styling *
.image-card {
    position: relative;
    border-radius: 16px;           /* Modern rounded look *
    overflow: hidden;
    background: #fff;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08); /* Soft professional shadow *
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.image-card:hover {
    transform: translateY(-8px);   /* Interactive lift effect *
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
}

.image-card img {
    width: 100%;
    height: 350px;                 /* Fixed height for uniformity *
    object-fit: cover;             /* Prevents stretching *
    display: block;
}

/* Subtle Overlay Label *
.image-label {
    position: absolute;
    bottom: 20px;
    left: 20px;
    background: rgba(255, 255, 255, 0.9);
    padding: 8px 16px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 700;
    color: #0f172a;
    backdrop-filter: blur(4px);
}

/* Responsive Fix for Mobile */





/* Container for the image section */
/*.hero-visual {
    width: 100%;             /* Occupies the full width of its parent container */
   /* max-width: 800px;       /* Prevents the image from becoming too large on wide screens */
   /* margin: 4px auto;       /* Centers the section and adds space above/below */
   /* padding:  2px;         /* Adds internal spacing so it doesn't touch the screen edges on mobile *//*
}

/* Wrapper for the image effects */
/*.image-wrapper {
   /* position: relative;
    width: 100%;             /* Ensures the wrapper fills the section */
   /* border-radius: 20px;     /* Soft, modern rounded corners */
    /*overflow: hidden;        /* Keeps the image inside the rounded corners */
    /*box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); /* Adds a professional "lifted" depth */
   /* transition: transform 0.3s ease; /* Prepares it for a hover effect *//*
}

.image-wrapper:hover {
    transform: translateY(-5px); /* Subtly moves up when the user hovers *//*
}

/* The actual image styling */
/*.image-wrapper img {
    display: block;          /* Removes unwanted bottom gaps */
  /*  width: 100%;             /* Makes image responsive */
   /* height: auto;            /* Maintains original aspect ratio */
   /* object-fit: cover;       /* Ensures the image fills the area beautifully *//*
}

/* =========================
   RESET
========================= */
/* Center the login container on the page */
#auth-section {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 60px 20px;
    background-color: #f8fafc; /* Light administrative background */
}

/* The outer box */
.auth-box {
    width: 100%;
    max-width: 450px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    overflow: hidden; /* Keeps the form-toggle corners clean */
    border: 1px solid #e2e8f0;
}

/* The toggle button at the top */
.form-toggle {
    display: flex;
    background: #f1f5f9;
}

.form-toggle button {
    flex: 1;
    padding: 15px;
    border: none;
    background: transparent;
    font-weight: 700;
    color: #64748b;
    cursor: default; /* Since it's just 'Login' currently */
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 1px;
}

.form-toggle button.active {
    background: #ffffff;
    color: #0f172a;
    border-bottom: 2px solid #3b82f6; /* Accent color */
}

/* The form area */
.info-card {
    padding: 40px;
}

.auth-form h2 {
    margin-bottom: 25px;
    font-size: 1.5rem;
    color: #0f172a;
    text-align: center;
}

/* Grouping inputs and labels */
.input-group {
    margin-bottom: 20px;
}

.input-group label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: #334155;
}

/* Inputs and Select box styling */
.input-group input, 
.input-group select {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.2s ease;
    background-color: #ffffff;
}

.input-group input:focus, 
.input-group select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* The Submit Button */
.btn-primary {
    width: 100%;
    padding: 14px;
    background-color: #0f172a;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.2s ease;
}

.btn-primary:hover {
    background-color: #1e293b;
}










* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}
.home-intro {
    background-image: url('images/pexels-julia-m-cameron-4144223.jpg');
    background-size: cover;
    background-position: center;
    height: 100vh;
}

body {
    font-family: 'Poppins', sans-serif;
    background: var(--bg-light);
    color: var(--text-main);
    line-height: 1.7;
}

/* =========================
   LAYOUT
========================= */

.container {
    max-width: 1200px;
    margin: auto;
    padding: 0 20px;
}

/* =========================
   HEADER
========================= */

.site-header {
    background: var(--primary);
    padding: 15px 0;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: var(--shadow-md);
}

.header-inner {
    display: flex;
    justify-content: space-between;
    align-items: center;
    text-align: center;
}

.logo {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    color: white;
}

.logo span {
    color: var(--accent);
}

.main-nav {
    display: flex;
    gap: 10px;
    align-items: center;
}

.main-nav a {
    color: rgba(255,255,255,0.85);;
    text-decoration: none;
    padding: 8px 14px;
    border-radius: var(--radius);
    font-size: 0.9rem;
    transition: var(--transition);
}

.main-nav a:hover,
.main-nav a.active {
    background: rgba(255,255,255,0.1);
    color: var(--accent);
}

.section-header {
    text-align: center;
    margin: 60px 0 40px;
}

.section-header h1,
.section-header h2 {
    font-size: 2.5rem;
    color: var(--primary);
    margin-bottom: 15px;
    font-family: 'Playfair Display', serif;
}

.section-header p {
    max-width: 750px;
    margin: auto;
    color: var(--text-muted);
}

/* =========================
   CARDS (ALL PAGES)
========================= */

.card,
.info-card,
.contact-card,
.admissions-card {
    background: var(--white);
    padding: 30px;
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.card:hover,
.info-card:hover,
.contact-card:hover,
.admissions-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
}

/* =========================
   GRIDS
========================= */

.contact-grid,
.admissions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 25px;
}

/* =========================
   FORMS (CONTACT + ADMISSIONS)
========================= */

input,
textarea,
select {
    width: 100%;
    padding: 12px;
    margin-top: 8px;
    margin-bottom: 15px;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    font-size: 0.95rem;
    transition: var(--transition);
}

input:focus,
textarea:focus,
select:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(212,175,55,0.2);
}

button {
    background: var(--primary);
    color: var(--white);
    padding: 12px 25px;
    border: none;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}

button:hover {
    background: var(--accent);
    color: var(--primary);
}

/* =========================
   LISTS
========================= */

ul {
    padding-left: 20px;
}

ul li {
    margin-bottom: 10px;
}

/* =========================
   PAGE LINKS
========================= */

.page-links {
    text-align: center;
    margin: 50px 0;
}

.page-links a {
    display: inline-block;
    margin: 8px;
    padding: 10px 18px;
    background: var(--primary);
    color: var(--white);
    border-radius: var(--radius);
    text-decoration: none;
    transition: var(--transition);
}

.page-links a:hover {
    background: var(--accent);
    color: var(--primary);
}

/* =========================
   FOOTER TEXT
========================= */

.section-footer {
    text-align: center;
    margin-top: 40px;
    color: var(--text-muted);
}
footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: var(--primary);
    color: white;
    text-align: center;
  
    margin: 1px;
}

/* =========================
   HERO (HOME PAGE)
========================= */

.home-intro {
    padding: 100px 0;
    text-align: center;
    background: var(--white);
    border-bottom: 1px solid #eee;
}

.home-intro h1 {
    font-size: 3rem;
    color: var(--primary);
    font-family: 'Playfair Display', serif;
}

.home-intro h4 {
    color: var(--text-muted);
    font-weight: 300;
    letter-spacing: 2px;
}

/* =========================
   BUTTON STYLE (OPTIONAL)
========================= */

.btn-primary {
    display: inline-block;
    background: var(--primary);
    color: var(--white);
    padding: 12px 28px;
    border-radius: 50px;
    text-decoration: none;
    transition: var(--transition);
}

.btn-primary:hover {
    background: var(--accent);
    color: var(--primary);
}

/* =========================
   RESPONSIVE
========================= */

@media (max-width: 768px) {

    .header-inner {
        flex-direction: column;
        gap: 15px;
    }

    .main-nav {
        flex-wrap: wrap;
        justify-content: center;
    }

    .home-intro h1 {
        font-size: 2rem;
    }

}