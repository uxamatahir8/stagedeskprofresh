# 🚀 StageDesk Pro - Quick Reference Card

**Print this card and keep it handy!**

---

## ⚡ Quick Commands

### Installation & Setup
```bash
composer install              # Install PHP dependencies
npm install                   # Install Node packages
php artisan key:generate      # Generate app key
php artisan migrate           # Run database migrations
npm run build                 # Build frontend assets
```

### Running Application
```bash
php artisan serve             # Start development server
php artisan queue:work        # Start queue worker
bash monitoring.sh start &    # Start health monitoring
```

### Safety & Verification
```bash
bash safety-check.sh          # Pre-deployment checks
bash deploy.sh                # Automated deployment
bash monitoring.sh check      # Single health check
php artisan tinker            # Interactive shell
```

### Useful Artisan Commands
```bash
php artisan make:controller   # Create controller
php artisan make:model        # Create model
php artisan make:migration    # Create migration
php artisan migrate:rollback  # Rollback migrations
php artisan config:cache      # Cache config
php artisan route:cache       # Cache routes
php artisan cache:clear       # Clear all cache
```

---

## 📚 Documentation Guide

| Need | File | Time |
|------|------|------|
| Quick Start | [PROJECT_README.md](PROJECT_README.md) | 10 min |
| Installation | [INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md) | 20 min |
| Deployment | [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) | 30 min |
| Database Info | [DATABASE_SCHEMA.md](DATABASE_SCHEMA.md) | 15 min |
| API Endpoints | [API_DOCUMENTATION.md](API_DOCUMENTATION.md) | 20 min |
| Error Explanation | [ERROR_ANALYSIS_REPORT.md](ERROR_ANALYSIS_REPORT.md) | 20 min |
| Master Index | [DOCUMENTATION_INDEX.md](DOCUMENTATION_INDEX.md) | 5 min |

---

## 🔧 File Locations

### Application Code
- **Controllers:** `app/Http/Controllers/`
- **Models:** `app/Models/`
- **Views:** `resources/views/`
- **Routes:** `routes/`
- **Database:** `database/`

### Configuration
- **Database:** `config/database.php`
- **Mail:** `config/mail.php`
- **App Settings:** `.env`
- **Queue:** `config/queue.php`

### Logs & Storage
- **Logs:** `storage/logs/laravel.log`
- **Deployments:** `logs/deployment-*.log`
- **Safety Checks:** `logs/safety-check-*.log`
- **Monitoring:** `logs/monitoring-*.log`

---

## 🎯 Common Issues & Solutions

### Application Won't Start
```bash
# 1. Check .env file exists
test -f .env && echo "OK" || echo "Missing .env"

# 2. Check app key is set
grep "APP_KEY=" .env

# 3. Check database connection
php artisan tinker -> DB::connection()->getPdo()

# 4. Check permissions
chmod -R 755 storage bootstrap/cache
```

### Database Issues
```bash
# 1. Verify connection
mysql -u root -p stagedeskprofresh

# 2. Run migrations
php artisan migrate

# 3. Rollback if needed
php artisan migrate:rollback

# 4. Seed database
php artisan db:seed
```

### Permission Issues
```bash
# Fix storage permissions
chmod -R 755 storage bootstrap/cache

# Fix on Linux/Mac
sudo chown -R www-data:www-data storage bootstrap/cache
```

### Port Already in Use
```bash
# Use different port
php artisan serve --port=8001

# Or kill process using 8000
lsof -ti:8000 | xargs kill -9
```

---

## 📊 Health Check Summary

Run this before deployment:
```bash
bash safety-check.sh
```

It checks:
- ✅ PHP syntax
- ✅ Configuration
- ✅ File permissions
- ✅ Composer dependencies
- ✅ Routes
- ✅ Blade templates
- ✅ Security
- ✅ Database connectivity

---

## 🔐 Security Checklist

Before going live:
- [ ] `APP_DEBUG=false` in `.env`
- [ ] Strong database password
- [ ] HTTPS certificate ready
- [ ] CORS configured properly
- [ ] API rate limiting enabled
- [ ] Database backup configured
- [ ] Error logging enabled
- [ ] Monitoring active

---

## 📱 API Quick Reference

### Base URL
```
http://localhost:8000/api
```

### Authentication
```
All endpoints require bearer token:
Authorization: Bearer {token}
```

### Common Endpoints
```
GET    /api/artists           # List artists
GET    /api/artists/{id}      # Get artist
POST   /api/artists           # Create artist
GET    /api/services          # List services
POST   /api/bookings          # Create booking
GET    /api/payments          # List payments
```

See [API_DOCUMENTATION.md](API_DOCUMENTATION.md) for full list.

---

## 💾 Database Backup

### Create Backup
```bash
mysqldump -u root -p stagedeskprofresh > backup-$(date +%Y%m%d).sql
```

### Restore from Backup
```bash
mysql -u root -p stagedeskprofresh < backup-YYYYMMDD.sql
```

### Backup Location
- Default: `backups/` directory
- Automated: Created during deployment
- Schedule: Should be daily in production

---

## 🚨 Emergency Procedures

### Application Down
1. Check logs: `tail -f storage/logs/laravel.log`
2. Check status: `bash monitoring.sh check`
3. Restart: `php artisan serve`

### Database Down
1. Check connection: `mysql -u root -p`
2. Restore backup: `mysql -u root -p < backup.sql`
3. Verify: `php artisan tinker -> DB::connection()->getPdo()`

### Disk Full
1. Check space: `df -h`
2. Clear logs: `rm storage/logs/*.log`
3. Clear cache: `php artisan cache:clear`

### Unauthorized Access
1. Review logs: `grep -i "unauthorized" storage/logs/laravel.log`
2. Check users: `php artisan tinker -> User::all()`
3. Reset password: `php artisan tinker -> User::find(1)->update(['password' => Hash::make('newpass')])`

---

## 📞 Contact Points

### Documentation Resources
- **Master Index:** [DOCUMENTATION_INDEX.md](DOCUMENTATION_INDEX.md)
- **Getting Started:** [PROJECT_README.md](PROJECT_README.md)
- **Emergencies:** [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)

### Support Email
- support@stagedeskpro.local

### Key Files
- Config: `.env`
- Logs: `storage/logs/`
- Routes: `routes/`

---

## ✅ Pre-Launch Checklist (5 min)

- [ ] Run `bash safety-check.sh` - passed
- [ ] Database backed up - done
- [ ] Environment configured - done
- [ ] .env variables set - done
- [ ] Migrations run - done
- [ ] Assets built - done
- [ ] Application starts - done
- [ ] Can access UI - done
- [ ] Can login - done
- [ ] Monitoring ready - done

**All green? You're ready to go live!** 🎉

---

## 🎯 By Role

### Developer
- Clone repo
- Run `bash safety-check.sh`
- Run `php artisan serve`
- See [PROJECT_README.md](PROJECT_README.md)

### DevOps Engineer
- Review [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)
- Run `bash deploy.sh`
- Configure `monitoring.sh`
- Set up backups

### Project Manager
- See [PROJECT_COMPLETION_CERTIFICATE.md](PROJECT_COMPLETION_CERTIFICATE.md)
- Review [COMPLETION_CHECKLIST.md](COMPLETION_CHECKLIST.md)
- Check [FINAL_DELIVERY_SUMMARY.md](FINAL_DELIVERY_SUMMARY.md)

### System Administrator
- Follow [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)
- Configure monitoring
- Set up backups
- Monitor logs

---

## 📋 One-Time Setup

```bash
# First time only
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link

# Then everyday
php artisan serve
php artisan queue:work  # in separate terminal
```

---

## 🔄 Deployment Cycle

```
1. Development
   ↓
2. Testing
   ↓
3. Pre-deployment
   → bash safety-check.sh
   → mysqldump backup
   ↓
4. Deployment
   → bash deploy.sh
   ↓
5. Verification
   → bash monitoring.sh check
   ↓
6. Monitoring
   → bash monitoring.sh start &
   ↓
7. Production
   🎉 Live!
```

---

## 🎓 Learning Resources

### Videos/Docs to Read
1. [Laravel Docs](https://laravel.com/docs)
2. [Eloquent ORM](https://laravel.com/docs/eloquent)
3. [Blade Templates](https://laravel.com/docs/blade)
4. [Routing](https://laravel.com/docs/routing)

### Code to Study
- Controllers: `app/Http/Controllers/`
- Models: `app/Models/`
- Views: `resources/views/`

---

## ⭐ Pro Tips

1. **Use Tinker** - `php artisan tinker` to test code interactively
2. **Read Logs** - `tail -f storage/logs/laravel.log` for debugging
3. **Cache Issues** - Run `php artisan cache:clear` when stuck
4. **Route Issues** - Run `php artisan route:cache` after changes
5. **Config Issues** - Run `php artisan config:cache` after changes
6. **Database Issues** - Use `.env` file, not hardcoded credentials
7. **Permission Issues** - Always `chmod 755 storage` and `bootstrap/cache`

---

## 📞 Quick Help

**Can't remember what to do?**

1. Check this card first
2. See [DOCUMENTATION_INDEX.md](DOCUMENTATION_INDEX.md)
3. Read relevant documentation
4. Check logs in `storage/logs/`
5. Run `bash safety-check.sh`

---

## 🎊 You're All Set!

**Everything you need to deploy, run, and monitor StageDesk Pro is ready.**

### Next 5 Minutes
1. Read [PROJECT_README.md](PROJECT_README.md)
2. Follow [INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md)
3. Run `bash safety-check.sh`

### Next 15 Minutes
1. Run `bash deploy.sh`
2. Access `http://localhost:8000`
3. Start `bash monitoring.sh start &`

### Ready for Production?
1. Read [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)
2. Create backups
3. Deploy with confidence!

---

**Version:** 1.0.0  
**Updated:** January 24, 2026  
**Status:** ✅ Production Ready  

🎭 **Welcome to StageDesk Pro!** 🎭

Print this card and keep it on your desk! 📌

