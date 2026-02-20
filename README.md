# 🎭 StageDesk Pro - Entertainment Booking Platform

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.2-blue.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![Node.js](https://img.shields.io/badge/Node.js-18%2B-green.svg)](https://nodejs.org)

A comprehensive entertainment booking and management platform built with Laravel 12, designed to connect customers with professional DJs, artists, and entertainment companies. StageDesk Pro streamlines the entire booking process from initial customer request to event completion and review submission.

## 📖 Table of Contents

- [About](#-about)
- [Features](#-features)
- [Technology Stack](#-technology-stack)
- [Prerequisites](#-prerequisites)
- [Quick Start](#-quick-start)
- [Configuration](#-configuration)
- [User Roles](#-user-roles)
- [Project Structure](#-project-structure)
- [Database Schema](#-database-schema)
- [Documentation](#-documentation)
- [Testing](#-testing)
- [Deployment](#-deployment)
- [Support](#-support)
- [License](#-license)

## 🌟 About

StageDesk Pro is a full-featured booking management system that facilitates connections between entertainment service providers and customers. The platform offers:

- **Multi-Company Support**: Multiple entertainment companies can operate on the same platform
- **Role-Based Access Control**: Distinct interfaces for admins, companies, artists, and customers
- **Advanced Booking System**: Comprehensive booking workflow with notifications and confirmations
- **Payment Processing**: Integrated payment tracking and management
- **Subscription Management**: Flexible subscription tiers for companies
- **Communication Tools**: Real-time notifications and email notifications
- **Analytics Dashboard**: Business insights and performance metrics

## ✨ Features

### Core Functionality

#### 🎯 Booking Management
- **Multi-Step Booking Flow**: Intuitive booking process with validation
- **Event Type Selection**: Weddings, corporate events, parties, and more
- **Date/Time Management**: Advanced scheduling with Flatpickr integration
- **Service Customization**: Detailed requirements and restrictions
- **Auto-Create Customer Accounts**: Companies can create bookings for new customers
- **Booking History**: Complete transaction history for all users
- **Status Tracking**: Real-time booking status updates

#### 👥 User Management
- **Master Admin Portal**: Complete platform oversight
- **Company Admin Dashboard**: Company-specific management
- **Artist Portal**: Service management and booking handling
- **Customer Portal**: Easy booking and tracking
- **Profile Management**: Comprehensive user profiles
- **Role-Based Permissions**: Granular access control

#### 💼 Company Features
- **Company Registration**: Self-service company onboarding
- **Subscription Tiers**: Multiple pricing plans
- **Multi-Artist Management**: Manage multiple artists per company
- **Payment Tracking**: Revenue monitoring and reporting
- **Company Branding**: Custom logos and profiles
- **Analytics Dashboard**: Performance metrics and insights

#### 🎨 Artist Features
- **Artist Profiles**: Showcase portfolios and experience
- **Service Offerings**: Create and manage services
- **Booking Management**: Accept/reject booking requests
- **Price Proposals**: Negotiate pricing with customers
- **Rating System**: Build reputation through reviews
- **Availability Calendar**: Manage schedules

#### 🔔 Notification System
- **Real-Time Notifications**: In-app notification center
- **Email Notifications**: Automated email alerts
- **Notification Filtering**: By type, status, and date
- **Unread Indicators**: Visual notification badges
- **Notification History**: Complete notification log
- **Custom Notification Types**: Booking, payment, system alerts

#### 📧 Email System
- **Automated Emails**: Booking confirmations, account creation
- **Queue Management**: Async email processing
- **Professional Templates**: Responsive HTML email designs
- **Multiple Email Types**: Welcome, confirmation, updates, alerts
- **Email Tracking**: Delivery status monitoring

#### 💳 Payment Management
- **Payment Tracking**: Record and monitor payments
- **Payment Proofs**: Upload payment verification documents
- **Payment History**: Complete transaction records
- **Invoice Generation**: Automated invoicing
- **Payment Status**: Pending, completed, failed tracking

#### 📊 Dashboard & Analytics
- **Dynamic Statistics**: Real-time business metrics
- **Revenue Reports**: Financial performance tracking
- **Booking Analytics**: Booking trends and patterns
- **User Analytics**: User engagement metrics
- **Visual Charts**: Chart.js integration for data visualization
- **Export Functionality**: Data export capabilities

#### 📱 Blog & Content Management
- **Blog System**: Company and platform blogs
- **Category Management**: Organize content by categories
- **Comments**: User engagement on posts
- **Rich Content**: Support for images and formatting
- **SEO Optimization**: Meta tags and descriptions

### Advanced Features

- **Activity Logging**: Complete audit trail
- **Search & Filtering**: Advanced search across all modules
- **Responsive Design**: Mobile-first approach
- **Dark Mode**: Eye-friendly interface option
- **Soft Deletes**: Safe data removal with recovery
- **Data Validation**: Comprehensive form validation
- **Error Handling**: User-friendly error messages
- **Security Features**: CSRF protection, XSS prevention
- **API Support**: RESTful API endpoints
- **File Uploads**: Secure file handling

## 🛠 Technology Stack

### Backend
- **Framework**: Laravel 12.x
- **PHP**: 8.2+
- **Database**: MySQL 8.0+
- **ORM**: Eloquent
- **Authentication**: Laravel Sanctum/Breeze
- **Queue**: Redis/Database
- **Cache**: Redis/Memcached

### Frontend
- **Templating**: Blade
- **CSS Framework**: Bootstrap 5 & Tailwind CSS 4
- **JavaScript**: Alpine.js, Vanilla JS
- **Build Tool**: Vite 7
- **Icons**: Tabler Icons
- **Charts**: Chart.js
- **Date Picker**: Flatpickr

### Development Tools
- **Debugbar**: Laravel Debugbar
- **Testing**: PHPUnit
- **Code Quality**: Laravel Pint
- **Version Control**: Git
- **Package Manager**: Composer, npm

## 📋 Prerequisites

Before installing StageDesk Pro, ensure you have:

- **PHP**: 8.2 or higher with extensions:
  - BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML
- **MySQL**: 8.0+ or MariaDB 10.3+
- **Composer**: 2.0+
- **Node.js**: 18+ and npm
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Git**: For version control
- **Optional**: Redis for caching and queues

### Development Environment Options
- **XAMPP** (Windows/Mac/Linux)
- **Laravel Herd** (Mac/Windows)
- **Laravel Sail** (Docker-based)
- **Homestead** (Vagrant-based)

## 🚀 Quick Start

### 1. Clone the Repository

```bash
git clone https://github.com/uxamatahir8/stagedeskprofresh.git
cd stagedeskprofresh
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stagedeskpro
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations

```bash
# Create database tables and seed data
php artisan migrate --seed
```

### 6. Build Frontend Assets

```bash
# Development build
npm run dev

# Production build
npm run build
```

### 7. Start Development Server

```bash
# Start Laravel development server
php artisan serve

# In a new terminal, start queue worker
php artisan queue:work
```

### 8. Access the Application

Visit `http://localhost:8000` and log in with:

**Master Admin:**
- Email: `admin@stagedeskpro.local`
- Password: `password`

## ⚙️ Configuration

### Mail Configuration

Configure email settings in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@stagedeskpro.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Queue Configuration

For production, configure Redis or database queue:

```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Storage Configuration

Link storage for public file access:

```bash
php artisan storage:link
```

## 👥 User Roles

### Master Admin
- Complete platform control
- Manage all companies and users
- System configuration
- View all analytics
- Access to all features

### Company Admin
- Manage company profile
- Manage company artists
- View company bookings
- Handle payments
- Company analytics

### Artist
- Manage profile and services
- Accept/reject booking requests
- Propose pricing
- View booking history
- Track payments

### Customer
- Browse services
- Create booking requests
- Make payments
- Leave reviews
- View booking history

## 📁 Project Structure

```
stagedeskprofresh/
├── app/
│   ├── Console/              # Artisan commands
│   ├── Constants/            # Application constants
│   ├── Events/              # Event classes
│   ├── Helpers/             # Helper functions
│   ├── Http/
│   │   ├── Controllers/     # Request handlers
│   │   ├── Middleware/      # Custom middleware
│   │   └── Requests/        # Form requests
│   ├── Listeners/           # Event listeners
│   ├── Mail/                # Email templates
│   ├── Models/              # Eloquent models
│   ├── Policies/            # Authorization
│   └── Services/            # Business logic
├── bootstrap/               # Framework bootstrap
├── config/                  # Configuration files
├── database/
│   ├── migrations/          # Database migrations
│   ├── seeders/            # Database seeders
│   └── factories/          # Model factories
├── public/                  # Public assets
│   ├── css/                # Stylesheets
│   ├── js/                 # JavaScript files
│   ├── images/             # Images
│   └── plugins/            # Third-party plugins
├── resources/
│   ├── css/                # Source CSS
│   ├── js/                 # Source JavaScript
│   └── views/              # Blade templates
│       ├── dashboard/      # Admin views
│       ├── landing/        # Public views
│       └── emails/         # Email templates
├── routes/                  # Route definitions
│   ├── web.php             # Web routes
│   ├── admin.php           # Admin routes
│   ├── artist.php          # Artist routes
│   ├── customer.php        # Customer routes
│   └── bookings.php        # Booking routes
├── storage/                 # Application storage
│   ├── app/                # Application files
│   ├── framework/          # Framework files
│   └── logs/               # Log files
└── tests/                   # Test suite
    ├── Feature/            # Feature tests
    └── Unit/               # Unit tests
```

## 🗄️ Database Schema

### Core Tables

- **users**: User accounts and authentication
- **roles**: User role definitions
- **companies**: Company profiles
- **company_subscriptions**: Subscription management
- **artists**: Artist profiles and information
- **artist_services**: Services offered by artists
- **booking_requests**: Customer booking requests
- **booked_services**: Confirmed bookings
- **payments**: Payment records
- **notifications**: User notifications
- **event_types**: Event categories
- **packages**: Subscription packages
- **blogs**: Blog posts
- **comments**: Blog comments
- **activity_logs**: Audit trail

For detailed schema information, see [DATABASE_SCHEMA.md](DATABASE_SCHEMA.md).

## 📚 Documentation

Comprehensive documentation is available in the repository:

- **[PROJECT_README.md](PROJECT_README.md)**: Extended project overview
- **[INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md)**: Detailed installation steps
- **[DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)**: Production deployment guide
- **[DATABASE_SCHEMA.md](DATABASE_SCHEMA.md)**: Database structure
- **[API_DOCUMENTATION.md](API_DOCUMENTATION.md)**: API endpoints
- **[COMPLETE_PROJECT_DOCUMENTATION.md](COMPLETE_PROJECT_DOCUMENTATION.md)**: Complete technical documentation
- **[FEATURE_IMPLEMENTATION_SUMMARY.md](FEATURE_IMPLEMENTATION_SUMMARY.md)**: Feature details
- **[TESTING_GUIDE.md](TESTING_GUIDE.md)**: Testing procedures
- **[BOOKING_FLOW_DOCUMENTATION.md](BOOKING_FLOW_DOCUMENTATION.md)**: Booking process
- **[EMAIL_SYSTEM_DOCUMENTATION.md](EMAIL_SYSTEM_DOCUMENTATION.md)**: Email system
- **[NOTIFICATION_DASHBOARD_ENHANCEMENT.md](NOTIFICATION_DASHBOARD_ENHANCEMENT.md)**: Notification system
- **[DOCUMENTATION_INDEX.md](DOCUMENTATION_INDEX.md)**: Complete documentation index

## 🧪 Testing

Tests use **MySQL** (see `phpunit.xml`). Create a dedicated test database first so `RefreshDatabase` does not affect your dev data:

```bash
# In MySQL: CREATE DATABASE stagedeskpro_test;
# Or: mysql -u your_user -p -e "CREATE DATABASE stagedeskpro_test;"
```

### Run Tests

```bash
# Run all tests (uses stagedeskpro_test by default; override with .env.testing if needed)
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

### Manual Testing

Follow the comprehensive testing guide:

```bash
# See TESTING_GUIDE.md for detailed test scenarios
```

### Testing Checklist

- ✅ User registration and authentication
- ✅ Booking creation workflow
- ✅ Payment processing
- ✅ Email notifications
- ✅ Company management
- ✅ Artist profile management
- ✅ Search and filtering
- ✅ Dashboard analytics
- ✅ Role-based access control

## 🚢 Deployment

### Production Checklist

1. **Environment Configuration**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   ```

2. **Optimize Application**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan optimize
   ```

3. **Database**
   ```bash
   php artisan migrate --force
   ```

4. **Build Assets**
   ```bash
   npm run build
   ```

5. **Set Permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

6. **Configure Queue Worker**
   - Set up Supervisor for queue:work
   - Configure scheduled tasks in cron

7. **Security**
   - Enable HTTPS
   - Configure CORS
   - Set secure session cookies
   - Enable rate limiting

For complete deployment instructions, see [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md).

### Server Requirements

**Recommended Production Setup:**
- Ubuntu 22.04 LTS
- PHP 8.2+ with OPcache
- MySQL 8.0+ or MariaDB 10.6+
- Nginx 1.22+ or Apache 2.4+
- Redis 7.0+ for caching and queues
- SSL certificate (Let's Encrypt)
- Minimum 2GB RAM, 2 CPU cores
- 20GB storage

## 🔒 Security

StageDesk Pro implements multiple security measures:

- **CSRF Protection**: All forms protected against CSRF attacks
- **XSS Prevention**: Blade templating auto-escapes output
- **SQL Injection Prevention**: Eloquent ORM parameterized queries
- **Password Hashing**: Bcrypt hashing for passwords
- **Role-Based Access Control**: Granular permissions
- **Rate Limiting**: API and authentication rate limits
- **Secure Session Management**: HTTP-only, secure cookies
- **File Upload Validation**: Strict file type and size validation
- **SQL Injection Protection**: Prepared statements
- **Security Headers**: X-Frame-Options, CSP, etc.

## 🐛 Troubleshooting

### Common Issues

**Issue**: "Class not found" error
```bash
Solution: composer dump-autoload
```

**Issue**: Assets not loading
```bash
Solution: npm run build && php artisan optimize:clear
```

**Issue**: Queue not processing
```bash
Solution: php artisan queue:restart
```

**Issue**: Storage link broken
```bash
Solution: php artisan storage:link
```

**Issue**: Migration errors
```bash
Solution: php artisan migrate:fresh --seed
```

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📞 Support

For support and questions:

- **Documentation**: Check the comprehensive docs in the repo
- **Issues**: Open an issue on GitHub
- **Email**: support@stagedeskpro.com

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

Built with:
- [Laravel](https://laravel.com) - The PHP Framework
- [Bootstrap](https://getbootstrap.com) - CSS Framework
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS
- [Alpine.js](https://alpinejs.dev) - Lightweight JavaScript
- [Chart.js](https://www.chartjs.org) - Data visualization
- [Flatpickr](https://flatpickr.js.org) - Date/time picker
- [Tabler Icons](https://tabler-icons.io) - Icon set

## 📊 Project Status

**Current Version**: 1.0.0  
**Status**: Production Ready ✅  
**Last Updated**: January 2026

---

<p align="center">Made with ❤️ by the StageDesk Pro Team</p>
