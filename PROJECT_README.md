# 🎭 StageDesk Pro - Complete Booking Platform

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.2-blue.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)

A comprehensive DJ and entertainment booking platform built with Laravel 11, featuring advanced project management, artist marketplace, payment processing, and subscription management.

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 or higher
- MySQL 8.0 or higher
- Node.js 18+ and npm
- Composer 2.0+

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/yourusername/stagedeskprofresh.git
cd stagedeskprofresh

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate app key
php artisan key:generate

# 6. Create database and run migrations
php artisan migrate --seed

# 7. Build frontend assets
npm run build

# 8. Start the development server
php artisan serve

# 9. Start queue worker (for notifications and jobs)
php artisan queue:work
```

Visit `http://localhost:8000` and log in with:
- Email: `admin@stagedeskpro.local`
- Password: `password`

## 📋 Features

### Core Features
- ✅ **Artist Management** - Register, profile management, service offerings
- ✅ **Service Management** - Create and manage entertainment services
- ✅ **Booking System** - Advanced booking with date/time management
- ✅ **Payment Processing** - Integrated payment gateway
- ✅ **Subscription Management** - Flexible subscription plans
- ✅ **User Notifications** - Real-time notifications for bookings and updates
- ✅ **Company Management** - Multi-company support with subscription tiers
- ✅ **Role-Based Access** - Master Admin, Company Admin, Artist, Customer roles

### Admin Features
- 📊 Dashboard with real-time statistics
- 👥 User management and role assignment
- 💰 Payment tracking and reports
- 📈 Revenue analytics
- 🔔 Notification management
- ⚙️ System settings and configuration

### Artist Features
- 📝 Service creation and management
- 💼 Portfolio showcase
- 📅 Booking calendar
- 💵 Payment tracking
- ⭐ Reviews and ratings
- 📱 Mobile-friendly interface

### Customer Features
- 🔍 Service discovery
- 📅 Easy booking interface
- 💳 Secure payment
- 📧 Booking confirmation
- ⭐ Rate and review services
- 📱 Booking history

## 🏗️ Architecture

### Directory Structure
```
stagedeskprofresh/
├── app/
│   ├── Http/Controllers/      # Application controllers
│   ├── Models/                # Database models (11 enhanced models)
│   ├── Mail/                  # Email templates
│   ├── Events/                # Application events
│   ├── Listeners/             # Event listeners
│   ├── Policies/              # Authorization policies
│   └── Helpers/               # Helper functions
├── resources/
│   ├── views/                 # Blade templates (15+)
│   ├── css/                   # Stylesheets
│   └── js/                    # JavaScript files
├── routes/
│   ├── web.php                # Web routes
│   ├── api.php                # API routes (if applicable)
│   └── *.php                  # Resource routes
├── database/
│   ├── migrations/            # Database migrations
│   ├── factories/             # Model factories
│   └── seeders/               # Database seeders
├── config/                    # Application configuration
├── storage/                   # Application storage
├── tests/                     # Test suite
└── public/                    # Web root
```

### Database Schema

**Core Models:**
- **User** - User accounts with roles
- **Role** - User roles (Master Admin, Company Admin, Artist, Customer)
- **Company** - Company profiles and information
- **CompanySubscription** - Company subscription tiers
- **Artist** - Artist profiles and information
- **ArtistServices** - Services offered by artists
- **BookingRequest** - Booking requests from customers
- **BookedService** - Confirmed bookings
- **Payment** - Payment records
- **Notification** - User notifications
- **SupportTicket** - Customer support tickets

**Supporting Models:**
- Package, PackageFeatures, EventType, BlogCategory, Comment, ArtistRequest, AffiliateComission, Settings, SocialLink, Countries, States, Cities

## 🔐 Security Features

- 🛡️ CSRF protection on all forms
- 🔑 Secure password hashing
- 👮 Role-based access control (RBAC)
- 📝 Authorization policies for resource access
- 🔒 SQL injection prevention (Eloquent ORM)
- 🌐 XSS prevention (Blade templating)
- 📧 Rate limiting on API endpoints
- 🔄 Secure session management
- 💳 PCI compliance for payment processing

## 📊 API Endpoints

### Artist Endpoints
- `GET /api/artists` - List all artists
- `GET /api/artists/{id}` - Get artist details
- `POST /api/artists` - Create new artist
- `PUT /api/artists/{id}` - Update artist
- `DELETE /api/artists/{id}` - Delete artist

### Service Endpoints
- `GET /api/services` - List services
- `GET /api/services/{id}` - Get service details
- `POST /api/services` - Create service
- `PUT /api/services/{id}` - Update service
- `DELETE /api/services/{id}` - Delete service

### Booking Endpoints
- `GET /api/bookings` - List bookings
- `POST /api/bookings` - Create booking
- `GET /api/bookings/{id}` - Get booking details
- `PUT /api/bookings/{id}` - Update booking
- `DELETE /api/bookings/{id}` - Cancel booking

### Payment Endpoints
- `GET /api/payments` - List payments
- `POST /api/payments` - Process payment
- `GET /api/payments/{id}` - Get payment details

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test class
php artisan test --filter=BookingTest

# Generate coverage report
php artisan test --coverage

# Run with verbose output
php artisan test -v
```

## 📚 Documentation

### Additional Documentation
- [Deployment Guide](DEPLOYMENT_GUIDE.md) - Complete deployment instructions
- [API Documentation](docs/API.md) - Detailed API documentation
- [Database Schema](docs/DATABASE.md) - Database design and relationships
- [Architecture Overview](docs/ARCHITECTURE.md) - System architecture
- [Development Guide](docs/DEVELOPMENT.md) - Development guidelines

### Safety & Monitoring
- [Safety Checks](safety-check.sh) - Run pre-deployment safety checks
- [Monitoring Script](monitoring.sh) - Monitor application health post-deployment

## ⚙️ Configuration

### Environment Variables (.env)
```env
APP_NAME=StageDesk
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_APP_KEY_HERE

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=stagedeskprofresh
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@stagedeskpro.local
MAIL_FROM_NAME="StageDesk Pro"
```

### Configuration Files
- `config/app.php` - Application settings
- `config/auth.php` - Authentication
- `config/database.php` - Database configuration
- `config/mail.php` - Email configuration
- `config/queue.php` - Queue configuration
- `config/filesystems.php` - File storage

## 🚀 Deployment

### Before Deployment
```bash
# Run safety checks
bash safety-check.sh

# Create database backup
mysqldump -u root stagedeskprofresh > backup-$(date +%Y%m%d).sql

# Verify all tests pass
php artisan test
```

### Deployment Steps
1. Pull latest code: `git pull origin main`
2. Install dependencies: `composer install --no-dev`
3. Update environment: `php artisan env:check`
4. Clear caches: `php artisan cache:clear && php artisan config:cache`
5. Run migrations: `php artisan migrate --force`
6. Build assets: `npm run build`
7. Start queue worker: `php artisan queue:work`

See [Deployment Guide](DEPLOYMENT_GUIDE.md) for detailed instructions.

## 📊 Performance

### Optimization Tips
- Use database indexes on frequently queried columns
- Enable query caching for static data
- Compress CSS and JavaScript assets
- Implement CDN for static assets
- Use pagination for large datasets
- Lazy load images on frontend
- Enable HTTP/2 on server

### Monitoring
```bash
# Start continuous health monitoring
bash monitoring.sh start

# Run single health check
bash monitoring.sh check

# Clean old log files
bash monitoring.sh clean
```

## 🐛 Troubleshooting

### Common Issues

**Database Connection Error**
```bash
# Verify database credentials in .env
# Test connection: php artisan tinker -> DB::connection()->getPdo()
php artisan config:clear
php artisan cache:clear
```

**Permission Denied on storage/
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

**Routes Not Working**
```bash
php artisan route:clear
php artisan route:cache
php artisan serve
```

**Migrations Won't Run**
```bash
php artisan migrate:rollback
php artisan migrate --fresh --seed
```

## 🤝 Contributing

1. Create a feature branch: `git checkout -b feature/your-feature`
2. Commit your changes: `git commit -am 'Add new feature'`
3. Push to the branch: `git push origin feature/your-feature`
4. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support, email support@stagedeskpro.local or visit [https://stagedeskpro.local/support](https://stagedeskpro.local/support)

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com)
- UI Framework: [Bootstrap 5](https://getbootstrap.com)
- Icons: [Tabler Icons](https://tabler-icons.io)
- Payment Gateway: [Stripe](https://stripe.com)
- Email Service: [Mailgun](https://www.mailgun.com)

## 📈 Project Status

**Version:** 1.0.0  
**Status:** Production Ready ✅  
**Last Updated:** January 24, 2026  

### Completed Features
- [x] User authentication and authorization
- [x] Artist management system
- [x] Service booking system
- [x] Payment processing
- [x] Subscription management
- [x] Notification system
- [x] Admin dashboard
- [x] Company management
- [x] Role-based access control
- [x] Database optimization
- [x] Error handling
- [x] Security hardening
- [x] Deployment automation
- [x] Health monitoring
- [x] Safety checks

### Upcoming Features
- [ ] Advanced analytics
- [ ] API versioning
- [ ] GraphQL API
- [ ] Mobile app
- [ ] Real-time chat
- [ ] Video conferencing
- [ ] AI recommendations
- [ ] Automated invoicing

## 🎯 Roadmap

**Q1 2026**
- Mobile app (iOS/Android)
- Advanced analytics dashboard
- Automated invoicing system

**Q2 2026**
- API versioning (v2)
- GraphQL implementation
- Real-time notifications with WebSockets

**Q3 2026**
- AI-powered recommendations
- Video conferencing integration
- Advanced reporting

**Q4 2026**
- International payment support
- Multi-language support
- Advanced marketplace features

---

**🎉 Thank you for using StageDesk Pro! Happy booking!**

For the latest updates, visit [https://github.com/yourusername/stagedeskprofresh](https://github.com/yourusername/stagedeskprofresh)
