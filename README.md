# Ozone RMS - Record Management System

A comprehensive web-based Record Management System for schools built with PHP and MySQL.

## Features

- **User Authentication**: Secure login system with role-based access (Admin, Teacher, Parent, Student)
- **Student Dashboard**: Students can view their grades, attendance, and courses
- **Parent Portal**: Parents can monitor their child's academic progress
- **Admin Dashboard**: Administrators can manage student records and system settings
- **Contact Form**: Public contact form for inquiries
- **Responsive Design**: Mobile-friendly interface

## Database Setup

The system uses MySQL with the database name: `ozone_rms`

### Main Tables:
1. **users** - User credentials and roles
2. **students** - Student information linked to users
3. **contact_messages** - Contact form submissions

## Quick Start

1. **Initialize Database:**
   - Visit: `http://localhost/it's rms/setup_database.php`

2. **Access Application:**
   - Home: `http://localhost/it's rms/index.html`
   - Login: `http://localhost/it's rms/login.php`

## Demo Credentials

| Role | Username | Password |
|------|----------|----------|
| Admin | admin | admin123 |
| Student | student1 | student123 |
| Parent | parent1 | parent123 |

## Pages & Features

### Public Pages
- `index.html` - Home page
- `About.html` - About page
- `Staff.html` - Staff directory
- `contact.php` - Contact form

### Authentication
- `login.html` / `login.php` - Login page and processing
- `register.html` / `register.php` - Registration page and processing
- `logout.php` - Logout handler

### Dashboards
- `admin.php` - Admin/Teacher dashboard
- `student.php` - Student dashboard
- `parent.php` - Parent dashboard

### Configuration
- `db.php` - Database connection
- `setup_database.php` - Database initialization

## Security

✓ Prepared statements (SQL injection prevention)
✓ Password hashing with bcrypt
✓ Session-based authentication
✓ Input validation and sanitization
✓ HTML entity encoding

## System Requirements

- PHP 7.0+
- MySQL 5.7+
- Apache/XAMPP
- Modern web browser

## File Structure

```
├── index.html / Home.html     # Home pages
├── About.html / About.php      # About pages
├── Staff.html / Staff.php      # Staff pages
├── contact.html / contact.php  # Contact form
├── login.html / login.php      # Login pages
├── register.html / register.php # Registration pages
├── admin.php                   # Admin dashboard
├── student.php                 # Student dashboard
├── parent.php                  # Parent dashboard
├── db.php                      # DB connection
├── logout.php                  # Logout handler
├── setup_database.php          # DB initialization
├── styles.css                  # Stylesheet
└── README.md                   # This file
```

## Troubleshooting

**Database Error?**
- Run: `http://localhost/it's rms/setup_database.php`
- Check MySQL is running in XAMPP
- Verify credentials in db.php

**Login Issues?**
- Use demo credentials above
- Check database was initialized
- Clear browser cookies

**Contact Form Not Sending?**
- Verify contact_messages table exists
- Check database connection
- Ensure form method is POST

---

**Version**: 1.0 | **Database**: ozone_rms | **Built**: May 2026
