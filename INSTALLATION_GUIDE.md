# StageDesk Pro - Installation & Setup Guide

## System Requirements

### Minimum Requirements
- **PHP**: 8.2 or higher
- **MySQL**: 5.7 or higher (8.0 recommended)
- **Node.js**: 16.x or higher (for asset compilation)
- **Composer**: Latest version

### Recommended Setup
- Ubuntu 20.04+ or Windows with WSL2
- XAMPP 8.2+ with Apache, MySQL, PHP
- VS Code or PhpStorm IDE
- Git for version control

---

## Installation Steps

### 1. Clone Repository
```bash
git clone <repository-url>
cd stagedeskprofresh
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration
```bash
# Copy example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
```bash
# Edit .env file and configure database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stagedeskprofresh
DB_USERNAME=root
DB_PASSWORD=

# Run migrations
php artisan migrate

# (Optional) Seed sample data
php artisan db:seed
```

### 5. Storage Setup
```bash
# Create storage link for file uploads
php artisan storage:link

# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### 6. Build Assets
```bash
# Build frontend assets
npm run build

# Or for development with hot reload
npm run dev
```

### 7. Start Application
```bash
# Start Laravel development server
php artisan serve

# Application will be available at: http://localhost:8000
```

---

## Configuration Files

### .env Configuration
```env
APP_NAME=StageDesk
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stagedeskprofresh
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=mail.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@stagedeskpro.com"

FILESYSTEM_DISK=public
```

### Key Configuration Files
- `config/app.php` - Application settings
- `config/database.php` - Database configuration
- `config/mail.php` - Email configuration
- `config/filesystems.php` - File storage configuration

---

## Database Seeding

### Create Sample Data
```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=RoleSeeder
```

### Seeder Classes
1. **RoleSeeder** - Creates user roles (master_admin, company_admin, customer, artist)
2. **UserSeeder** - Creates sample users
3. **CompanySeeder** - Creates sample companies
4. **EventTypeSeeder** - Creates event types

---

## User Roles & Permissions

### Default Roles

#### Master Admin
- Full system access
- Manage all users and companies
- View all payments and subscriptions
- Verify payments
- Create/manage subscriptions

**Test Account:**
- Email: admin@stagedeskpro.com
- Password: password123

#### Company Admin
- Manage company profile
- View company artists
- Manage bookings (company level)
- View company payments
- Manage company subscriptions

#### Customer
- Create and manage bookings
- View own bookings
- Make payments
- View notifications
- Rate artists

#### Artist
- Manage profile and services
- View booking requests
- Submit proposals to bookings
- View own services
- Track earnings

---

## File Upload Configuration

### Storage Paths
```
storage/app/public/
├── artists/               # Artist profile images
├── payments/              # Payment attachments
├── companies/             # Company logos
├── users/                 # User avatars
└── blogs/                 # Blog images
```

### Upload Configuration (config/filesystems.php)
```php
'public' => [
    'driver' => 'local',
    'path' => 'storage/app/public',
    'url' => env('APP_URL') . '/storage',
    'visibility' => 'public',
    'permissions' => [
        'file' => 0644,
        'dir' => 0755,
    ],
],
```

### Size Limits
- Images: 2MB maximum
- Documents: 5MB maximum
- Supported formats: jpg, jpeg, png, pdf, doc, docx

---

## Caching & Performance

### Clear Cache
```bash
# Clear application cache
php artisan cache:clear

# Clear route cache
php artisan route:cache

# Clear view cache
php artisan view:clear

# Clear config cache
php artisan config:cache

# Clear all caches
php artisan cache:clear && php artisan route:clear && php artisan view:clear && php artisan config:clear
```

### Optimization
```bash
# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Cache routes for production
php artisan route:cache

# Cache configuration for production
php artisan config:cache
```

---

## Development Commands

### Useful Artisan Commands

#### Migrations
```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Reset database
php artisan migrate:reset

# Refresh database (reset + migrate)
php artisan migrate:refresh

# Migrate with seeding
php artisan migrate:fresh --seed
```

#### Database
```bash
# Start tinker (interactive shell)
php artisan tinker

# Create model with migration
php artisan make:model ModelName -m

# Create controller
php artisan make:controller ControllerName

# Create policy
php artisan make:policy PolicyName
```

#### Testing
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test tests/Unit/UserTest.php

# Run with coverage
php artisan test --coverage
```

---

## Debugging

### Enable Debug Mode
Edit `.env`:
```env
APP_DEBUG=true
```

### Debug Output
```php
// In controller or model
dd($variable);           // Die and dump
dump($variable);         // Just dump
\Log::info($message);    // Log to storage/logs/laravel.log
\Log::error($error);     // Log error
```

### Check Logs
```bash
# View latest logs
tail -f storage/logs/laravel.log

# Clear logs
rm storage/logs/laravel.log
```

### Laravel Debugbar
Install for development:
```bash
composer require barryvdh/laravel-debugbar --dev
```

---

## Deployment

### Production Checklist

#### Before Deployment
- [ ] Set `APP_DEBUG=false` in .env
- [ ] Set `APP_ENV=production` in .env
- [ ] Generate application key: `php artisan key:generate`
- [ ] Run migrations on production: `php artisan migrate --force`
- [ ] Cache configuration: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Optimize autoloader: `composer install --optimize-autoloader`

#### Web Server Configuration
- [ ] Configure HTTPS/SSL certificate
- [ ] Set proper file permissions (755 for directories, 644 for files)
- [ ] Configure email service (SMTP, SendGrid, etc.)
- [ ] Set up database backups
- [ ] Configure logging and monitoring

#### Security
- [ ] Change default admin credentials
- [ ] Enable two-factor authentication (if available)
- [ ] Set up rate limiting
- [ ] Configure CORS if needed
- [ ] Enable CSRF protection

#### Monitoring
- [ ] Set up error tracking (Sentry, Rollbar)
- [ ] Configure uptime monitoring
- [ ] Set up database backups
- [ ] Monitor server logs

---

## Troubleshooting

### Common Issues

#### Issue: "SQLSTATE[HY000] [2002] Connection refused"
**Solution:**
```bash
# Make sure MySQL is running
# Check database credentials in .env
# Restart MySQL service
```

#### Issue: "Class not found" errors
**Solution:**
```bash
# Clear autoloader cache
composer dump-autoload

# Or
php artisan cache:clear
```

#### Issue: "Permission denied" errors
**Solution:**
```bash
# Fix storage permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Fix on Ubuntu
sudo chown -R www-data:www-data storage bootstrap/cache
```

#### Issue: "File not found" in assets
**Solution:**
```bash
# Rebuild assets
npm run build

# Or link storage
php artisan storage:link
```

#### Issue: "Unauthorized" on protected routes
**Solution:**
- Check authentication middleware
- Verify user has required role
- Check policy authorization
- Review route middleware

---

## Performance Optimization

### Database Optimization
```bash
# Analyze tables
php artisan tinker
DB::statement('ANALYZE TABLE users');
DB::statement('ANALYZE TABLE booking_requests');
DB::statement('ANALYZE TABLE payments');

# Rebuild indexes
php artisan tinker
DB::statement('OPTIMIZE TABLE users');
```

### Code Optimization
- Use eager loading: `with(['relationships'])`
- Pagination for large datasets
- Cache frequently accessed data
- Use database indexes
- Optimize queries with EXPLAIN

### Server Optimization
- Enable gzip compression
- Set up CDN for static assets
- Configure server-side caching
- Use Redis for session storage
- Monitor and optimize memory usage

---

## Maintenance

### Regular Tasks
```bash
# Daily
- Check application logs
- Monitor disk space
- Verify backups completed

# Weekly
- Review error reports
- Update security patches
- Check database integrity

# Monthly
- Update dependencies
- Review security updates
- Optimize database
- Archive old logs
```

### Backup Strategy
```bash
# Database backup
mysqldump -u root -p stagedeskprofresh > backup.sql

# Full backup with files
tar -czf stagedeskprofresh-backup.tar.gz /path/to/project/

# Automated backup (cron job)
0 2 * * * mysqldump -u root -p stagedeskprofresh > /backups/db-$(date +\%Y\%m\%d).sql
```

---

## Support & Documentation

### Key Resources
- Laravel Documentation: https://laravel.com/docs
- Bootstrap Documentation: https://getbootstrap.com/docs
- Blade Template Guide: https://laravel.com/docs/blade
- Eloquent ORM: https://laravel.com/docs/eloquent

### Getting Help
1. Check application logs: `storage/logs/laravel.log`
2. Review database schema: `DATABASE_SCHEMA.md`
3. Check implementation details: `IMPLEMENTATION_SUMMARY.md`
4. Search Laravel documentation
5. Use `php artisan tinker` for debugging

---

## Quick Start for Windows (XAMPP)

### Step-by-Step
1. Install XAMPP from https://www.apachefriends.org/
2. Start Apache and MySQL from XAMPP Control Panel
3. Extract project to `C:\xampp\htdocs\stagedeskprofresh`
4. Open terminal in project directory
5. Run `composer install`
6. Copy `.env.example` to `.env`
7. Edit `.env` with database credentials (root, empty password)
8. Run `php artisan key:generate`
9. Run `php artisan migrate`
10. Run `php artisan serve`
11. Open http://localhost:8000

### Database Access
- PhpMyAdmin: http://localhost/phpmyadmin
- Username: root
- Password: (leave empty)

---

## Continuous Integration/Deployment (CI/CD)

### GitHub Actions Example
```yaml
name: Laravel Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_DATABASE: stagedeskprofresh
          MYSQL_ROOT_PASSWORD: password
        options: >-
          --health-cmd="mysqladmin ping"
          --health-interval=10s
          --health-timeout=5s
          --health-retries=3

    steps:
    - uses: actions/checkout@v2
    - uses: php-actions/composer@v6
    - run: php artisan migrate
    - run: php artisan test
```

---

## Version Control

### Git Setup
```bash
# Initialize repository
git init

# Add remote
git remote add origin <repository-url>

# Create branch
git checkout -b development

# Standard workflow
git add .
git commit -m "Feature: Add payment system"
git push origin feature-branch
```

### Important Files to .gitignore
```
.env
.env.local
node_modules/
storage/
bootstrap/cache/
vendor/
.DS_Store
*.log
```

---

**Last Updated:** November 2024
**Version:** 1.0.0
**Status:** Production Ready

For detailed implementation information, see `IMPLEMENTATION_SUMMARY.md`
For database schema details, see `DATABASE_SCHEMA.md`
