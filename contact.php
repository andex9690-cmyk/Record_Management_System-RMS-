<?php
include 'db.php';

$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
$old_values = $_SESSION['old_values'] ?? [
    'fullname' => '',
    'email' => '',
    'subject' => '',
    'message' => ''
];

unset($_SESSION['success_message'], $_SESSION['error_message'], $_SESSION['old_values']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us | Ozone High School</title>
    <link rel="stylesheet" href="styles.css" />
</head>

<body>

    <!-- Header -->
    <header class="site-header">
        <div class="container header-inner">
           <h1 class="logo">Record <span>Management System </span></h1>
            <nav class="main-nav">
                <a href="index.html">Home</a>
                <a href="About.html">About Us</a>
                <a href="Staff.html">Staff</a>
                <a href="contact.php" class="active">Contact Us</a>
                <a href="login.html" class="btn-login-nav">Login</a>
            </nav>
        </div>
    </header>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">

            <!-- Header -->
            <div class="section-header">
                <h1>Contact Us</h1>
                <p>
                    We value open communication with our students, parents, and community members.
                    If you have any questions, feedback, or inquiries, please feel free to reach out
                    using the information below or send us a message through the contact form.
                </p>
            </div>

            <!-- Content Grid -->
             <div class="contact-grid-wrapper">
        <!-- Left Side: Interactive School Information Card -->
        <section class="info-side-card">
            <div class="card-accent-header"></div>
            <h3>School Information</h3>
            
            <div class="info-list-stack">
                <div class="info-row-item">
                    <span class="info-icon">🏫</span>
                    <div class="info-text">
                        <label>School Name</label>
                        <p>Ozone High School</p>
                    </div>
                </div>
                <div class="info-row-item">
                    <span class="info-icon">📍</span>
                    <div class="info-text">
                        <label>Location</label>
                        <p>Gedera, Addis Ababa, Ethiopia</p>
                    </div>
                </div>
                <div class="info-row-item">
                    <span class="info-icon">📞</span>
                    <div class="info-text">
                        <label>Phone</label>
                        <p>+251 953462733</p>
                    </div>
                </div>
                <div class="info-row-item">
                    <span class="info-icon">✉️</span>
                    <div class="info-text">
                        <label>Email Address</label>
                        <p>ozonehighschool2@gmail.com</p>
                    </div>
                </div>
                <div class="info-row-item">
                    <span class="info-icon">🕒</span>
                    <div class="info-text">
                        <label>Office Hours</label>
                        <p>Monday – Friday | 8:00 AM – 5:00 PM</p>
                    </div>
                </div>
            </div>
        </section>


         <!-- Right Side: Premium Send a Message Card -->
        <section class="form-side-card">
            <h3>Send a Message</h3>
            <p class="form-subtitle">Fill out the form below and our team will respond as soon as possible.</p>

            <?php if ($success_message): ?>
                <div class="form-notice success">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="form-notice error">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <form id="contact-form" action="send_message.php" method="POST">
                <div class="contact-input-group">
                    <label for="fullname">Full Name</label>
                    <div class="contact-field-wrapper">
                        <span class="field-icon-inner">👤</span>
                        <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" required value="<?php echo htmlspecialchars($old_values['fullname'] ?? ''); ?>" />
                    </div>
                </div>

                <div class="contact-input-group">
                    <label for="email">Email Address</label>
                    <div class="contact-field-wrapper">
                        <span class="field-icon-inner">✉️</span>
                        <input type="email" id="email" name="email" placeholder="Enter your email address" required value="<?php echo htmlspecialchars($old_values['email'] ?? ''); ?>" />
                    </div>
                </div>

                <div class="contact-input-group">
                    <label for="subject">Subject</label>
                    <div class="contact-field-wrapper">
                        <span class="field-icon-inner">📌</span>
                        <input type="text" id="subject" name="subject" placeholder="Enter subject" required value="<?php echo htmlspecialchars($old_values['subject'] ?? ''); ?>" />
                    </div>
                </div>

                <div class="contact-input-group">
                    <label for="message">Message</label>
                    <div class="contact-field-wrapper textarea-wrapper">
                        <textarea id="message" name="message" rows="5" placeholder="Write your message here..." required><?php echo htmlspecialchars($old_values['message'] ?? ''); ?></textarea>
                    </div>
                </div>

                <button type="submit" class="btn-submit-contact">Send Message</button>
            </form>
        </section>
    </div>
</div>
            <!--<div class="contact-grid">
                

                 School Information -->
                <!--<div class="contact-card">
                    <h2>School Information</h2>
                    <div class="contact-info">
                        <p><strong>School Name:</strong> Ozone High School</p>
                        <p><strong>Location:</strong> Gedera, Addis Ababa, Ethiopia</p>
                        <p><strong>Phone:</strong> +251 953462733</p>
                        <p><strong>Email:</strong> ozonehighschool2@gmail.com</p>
                        <p><strong>Office Hours:</strong> Monday – Friday | 8:00 AM – 5:00 PM</p>
                    </div>
                </div>-->

                <!-- Contact Form -->
                <!--<div class="contact-card">
                    <h2>Send a Message</h2>
                    <p>Fill out the form below and our team will respond as soon as possible.</p>

                    ?php if ($success_message): ?>
                        <div style="color: green; margin-bottom: 1rem; padding: 0.75rem; background: #e0ffe0; border-radius: 5px;">
                            ?php echo htmlspecialchars($success_message); ?>
                        </div>
                    ?php endif; ?>

                    ?php if ($error_message): ?>
                        <div style="color: red; margin-bottom: 1rem; padding: 0.75rem; background: #ffe0e0; border-radius: 5px;">
                            ?php echo htmlspecialchars($error_message); ?>
                        </div>
                    ?php endif; ?>

                    <form class="contact-form" method="POST" action="contact.php">
                        <label>Full Name:</label>
                        <input type="text" name="fullname" placeholder="Enter your full name" required value="<?php echo htmlspecialchars($fullname ?? ''); ?>" /><br><br>

                        <label>Email Address:</label>
                        <input type="email" name="email" placeholder="Enter your email address" required value="<?php echo htmlspecialchars($email ?? ''); ?>" /><br><br>

                        <label>Subject:</label>
                        <input type="text" name="subject" placeholder="Enter subject" required value="<?php echo htmlspecialchars($subject ?? ''); ?>" /><br><br>

                        <label>Message:</label>
                        <textarea name="message" rows="5" placeholder="Write your message here..." required><?php echo htmlspecialchars($message ?? ''); ?></textarea><br><br>

                        <button type="submit">Send Message</button>
                    </form>
                </div>
            </div>-->

            <!-- Closing Message -->
            <div class="section-footer">
                <p>
                    Thank you for reaching out to Ozone High School.
                    We appreciate your interest and will respond promptly to your inquiry.
                </p>
            </div>
        </div>
    </section>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <h3>Ozone High School</h3>
                </div>
            </div>
            <hr>
            <p class="copyright">&copy; 2026 Ozone High School. Built for Excellence.</p>
        </div>
    </footer>

    <script src="theme.js"></script>

</body>

</html>