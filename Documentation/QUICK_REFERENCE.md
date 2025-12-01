# 📌 DOCKER DEPLOYMENT - QUICK REFERENCE CARD

Gunakan file ini sebagai quick reference untuk perintah dan konfigurasi yang paling sering digunakan.

---

## 🚀 DEPLOYMENT COMMANDS

### Initial Setup
```bash
# Copy environment file
cp .env.example .env

# Run automated deployment
./deploy.sh

# Manual alternative - start containers
docker-compose up -d

# Wait for database (usually 30 seconds)
sleep 30

# Run migrations
docker-compose exec app php artisan migrate

# Generate API docs
docker-compose exec app php artisan l5-swagger:generate
```

### Container Management
```bash
# View all containers
docker-compose ps

# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# Stop and remove volumes (full reset)
docker-compose down -v

# Rebuild images
docker-compose build --no-cache

# View logs
docker-compose logs -f [service]    # app, db, webserver

# Restart specific service
docker-compose restart [service]
```

---

## 📊 MONITORING COMMANDS

### Health Check
```bash
# View container status
docker-compose ps

# Check specific service logs
docker-compose logs -f app
docker-compose logs -f db
docker-compose logs -f webserver

# Test API
curl http://localhost:8000

# Check security headers
curl -I http://localhost:8000

# Database health
docker-compose exec db mysqladmin ping -h localhost -u root -proot
```

### Performance Monitoring
```bash
# CPU and memory usage
docker stats

# Docker system info
docker system df

# Container details
docker-compose exec [service] ps aux
```

---

## 🔧 LARAVEL COMMANDS

### Artisan Commands (via Docker)
```bash
# General format
docker-compose exec app php artisan [command]

# Migrations
docker-compose exec app php artisan migrate           # Run migrations
docker-compose exec app php artisan migrate:rollback # Rollback
docker-compose exec app php artisan migrate:refresh  # Refresh

# Database seeding
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan db:seed --class=UserSeeder

# Cache management
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache

# Optimization
docker-compose exec app php artisan optimize

# API Documentation
docker-compose exec app php artisan l5-swagger:generate

# Tinker (interactive shell)
docker-compose exec app php artisan tinker
```

---

## 🗄️ DATABASE COMMANDS

### MySQL Access & Operations
```bash
# Access MySQL shell
docker-compose exec db mysql -u root -p -e "SELECT VERSION();"

# Access MySQL interactive
docker-compose exec db mysql -u root -p
# Password: root

# Database operations
docker-compose exec db mysqldump -u root -proot jobportal > backup.sql
docker-compose exec -T db mysql -u root -proot jobportal < backup.sql

# View databases
docker-compose exec db mysql -u root -proot -e "SHOW DATABASES;"

# View tables
docker-compose exec db mysql -u root -proot jobportal -e "SHOW TABLES;"
```

### Backup & Restore
```bash
# Create backup
docker-compose exec db mysqldump -u root -proot jobportal > backup_$(date +%Y%m%d_%H%M%S).sql

# Restore backup
docker-compose exec -T db mysql -u root -proot jobportal < backup_20250101_120000.sql

# Full system backup
tar -czf lamarin_backup_$(date +%Y%m%d_%H%M%S).tar.gz .
```

---

## 🔐 SECURITY & PERMISSIONS

### File Permissions
```bash
# Fix storage permissions
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 755 storage bootstrap/cache

# View permissions
docker-compose exec app ls -la storage/

# Set specific permissions
docker-compose exec app chmod 755 storage/logs
```

### Secret Management
```bash
# Generate APP_KEY
docker-compose exec app php artisan key:generate

# Generate secure password (macOS)
openssl rand -base64 32

# Generate secure password (Linux)
head -c 32 /dev/urandom | base64
```

---

## 🐛 TROUBLESHOOTING COMMANDS

### Debug Issues
```bash
# Check service logs
docker-compose logs [service]

# Follow logs (real-time)
docker-compose logs -f [service]

# Access container shell
docker-compose exec [service] bash

# Check environment
docker-compose exec app php -r "phpinfo();"

# Test database connection
docker-compose exec app php artisan tinker
# Then run: DB::connection()->getPdo();
```

### Fix Common Issues
```bash
# Kill process using port 8000
lsof -ti:8000 | xargs kill -9

# Clear Docker cache
docker system prune

# Remove all unused images
docker image prune -a

# Remove all unused volumes
docker volume prune

# Restart all containers
docker-compose restart

# Force rebuild
docker-compose build --no-cache && docker-compose up -d
```

---

## 📁 IMPORTANT FILES

### Configuration Files
```
.env                              - Application environment
docker-compose.yml                - Container orchestration
Dockerfile                        - Image definition
nginx/conf.d/default.conf        - Nginx configuration
.dockerignore                     - Build optimization
.env.example                      - Environment template
```

### Documentation Files
```
DOCKER_DEPLOYMENT.md              - Complete guide
DOCKER_SECURITY_AUDIT.md          - Security issues
DOCKER_DOCUMENTATION_INDEX.md     - Documentation index
FINAL_SECURITY_AUDIT_REPORT.md    - Audit results
IMPLEMENTATION_CHECKLIST.md       - Implementation guide
```

---

## 🌐 ACCESS POINTS

### Local Development
- **API Root**: http://localhost:8000
- **API Documentation**: http://localhost:8000/api/documentation
- **Database**: localhost:3306 (internal only)

### Container Names
- **App Container**: laravel-app
- **Webserver Container**: nginx-server
- **Database Container**: mysql-db

### Ports
- **External HTTP**: 8000 (host) → 80 (container)
- **PHP-FPM**: 9000 (internal only)
- **MySQL**: 3306 (internal only)

---

## ⚙️ CONFIGURATION QUICK REFERENCE

### .env Key Variables
```env
APP_DEBUG=false                              # Always false
APP_ENV=local                                # local/staging/production
APP_URL=http://localhost:8000               # Application URL

DB_HOST=db                                   # Docker service name
DB_PORT=3306                                 # Internal port
DB_DATABASE=jobportal                        # Database name
DB_USERNAME=root                             # Database user
DB_PASSWORD=root                             # Database password

L5_SWAGGER_GENERATE_ALWAYS=false             # Never generate on each request
```

### Docker Compose Key Services
```yaml
app:                  # PHP-FPM application
webserver:           # Nginx web server
db:                  # MySQL database
```

---

## 📈 USEFUL ALIASES (Add to ~/.zshrc or ~/.bashrc)

```bash
# Alias for common commands
alias dc='docker-compose'
alias dcup='docker-compose up -d'
alias dcdown='docker-compose down'
alias dclogs='docker-compose logs -f'
alias dcps='docker-compose ps'
alias dcexec='docker-compose exec'

# Laravel commands via Docker
alias dart='docker-compose exec app php artisan'
alias dmigrate='docker-compose exec app php artisan migrate'
alias dseed='docker-compose exec app php artisan db:seed'
alias dclear='docker-compose exec app php artisan cache:clear'

# Database commands
alias ddb='docker-compose exec db mysql -u root -p'
alias ddbpwd='echo "Password: root"'
```

Usage: `dcup` instead of `docker-compose up -d`

---

## 🔄 DAILY OPERATIONS

### Morning Check
```bash
# Verify containers are running
docker-compose ps

# Check for any errors
docker-compose logs app --tail=50

# Database health
docker-compose exec db mysqladmin ping -h localhost -u root -proot
```

### Before End of Day
```bash
# Create backup
docker-compose exec db mysqldump -u root -proot jobportal > backup_$(date +%Y%m%d_%H%M%S).sql

# Check disk usage
docker system df

# View important logs
docker-compose logs app --tail=20
```

### Weekly Tasks
```bash
# Clean up unused images
docker image prune

# Update composer dependencies
docker-compose exec app composer update

# Run tests
docker-compose exec app php artisan test
```

---

## 🆘 EMERGENCY PROCEDURES

### Container Crashed
```bash
# View logs to see error
docker-compose logs app

# Restart container
docker-compose restart app

# If still broken - rebuild
docker-compose build --no-cache
docker-compose up -d
```

### Database Issues
```bash
# Check database health
docker-compose logs db

# Restart database
docker-compose restart db

# Wait and test connection
sleep 30
docker-compose exec app php artisan tinker
# Then: DB::connection()->getPdo();
```

### Out of Disk Space
```bash
# Check usage
docker system df

# Clean up
docker image prune -a
docker volume prune
docker container prune
```

### Need Full Reset
```bash
# WARNING: This removes everything
docker-compose down -v

# Rebuild everything
docker-compose build --no-cache

# Start fresh
docker-compose up -d
docker-compose exec app php artisan migrate
```

---

## 📊 PERFORMANCE TUNING

### Monitor Performance
```bash
# Real-time stats
docker stats

# Check database queries
docker-compose exec app php artisan tinker
# Then: DB::enableQueryLog(); // run some code // dd(DB::getQueryLog());

# Profile requests (if configured)
docker-compose logs app | grep "took"
```

### Optimization Commands
```bash
# Cache configuration
docker-compose exec app php artisan config:cache

# Cache routes
docker-compose exec app php artisan route:cache

# Optimize autoloader
docker-compose exec app composer dump-autoload --optimize

# Clear all caches
docker-compose exec app php artisan optimize:clear
```

---

## 🔍 COMMON QUESTIONS

### Q: How do I access MySQL?
```bash
docker-compose exec db mysql -u root -proot jobportal
```

### Q: How do I run migrations?
```bash
docker-compose exec app php artisan migrate
```

### Q: How do I view logs?
```bash
docker-compose logs -f app
```

### Q: How do I add a new dependency?
```bash
docker-compose exec app composer require package/name
```

### Q: How do I backup database?
```bash
docker-compose exec db mysqldump -u root -proot jobportal > backup.sql
```

### Q: How do I restart everything?
```bash
docker-compose restart
```

### Q: How do I see what's running?
```bash
docker-compose ps
```

### Q: How do I stop everything?
```bash
docker-compose down
```

---

## 📝 NOTES

- Always verify containers are running before performing operations
- Database takes ~30 seconds to initialize on first start
- Default credentials: root/root (change for production)
- Port 8000 must be available on your machine
- Some commands require shell access to container (`docker-compose exec app bash`)

---

## 📖 For More Information

- Full Guide: `DOCKER_DEPLOYMENT.md`
- Documentation Index: `DOCKER_DOCUMENTATION_INDEX.md`
- Security Details: `FINAL_SECURITY_AUDIT_REPORT.md`
- Implementation Guide: `IMPLEMENTATION_CHECKLIST.md`

---

**Last Updated:** December 1, 2025
**Print this and keep it handy!**


