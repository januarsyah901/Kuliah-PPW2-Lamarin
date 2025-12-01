# Docker Deployment Guide & Security Checklist

## 📋 Prerequisites

- Docker dan Docker Compose ter-install
- Minimal 2GB RAM untuk MySQL
- Port 8000 tersedia

## 🚀 Setup Instructions

### 1. Clone dan Setup Environment

```bash
cd /Users/mrfrog/Documents/kuliah/PPW-2/Pertemuan\ 11/lamarin

# Copy .env.example ke .env
cp .env.example .env

# Generate APP_KEY (jika belum ada)
php artisan key:generate
```

### 2. Configure Environment Variables

Edit `.env` dan sesuaikan:

```env
DB_PASSWORD=your_secure_password_here
DB_ROOT_PASSWORD=your_root_password_here
```

Untuk development, gunakan kredensial default di `.env.example`.

### 3. Build dan Run Containers

```bash
# Build images dan start containers
docker-compose up -d

# Check status
docker-compose ps

# View logs
docker-compose logs -f app
docker-compose logs -f db
docker-compose logs -f webserver
```

### 4. Initialize Database

```bash
# Run migrations
docker-compose exec app php artisan migrate

# Seed database (jika ada)
docker-compose exec app php artisan db:seed
```

### 5. Generate API Documentation

```bash
# Generate Swagger docs
docker-compose exec app php artisan l5-swagger:generate
```

### 6. Access Application

- **API:** http://localhost:8000
- **Swagger Docs:** http://localhost:8000/api/documentation

---

## 🔒 Security Checklist

### Sebelum Production

- [ ] Ubah `APP_DEBUG=false` di `.env`
- [ ] Ubah `APP_ENV=production` di `.env`
- [ ] Generate secure APP_KEY
- [ ] Ubah `DB_PASSWORD` dan `DB_ROOT_PASSWORD`
- [ ] Set `MYSQL_ROOT_PASSWORD` di docker-compose.yml atau gunakan env var
- [ ] Disable `L5_SWAGGER_GENERATE_ALWAYS=false`
- [ ] Konfigurasi proper logging
- [ ] Setup backup strategy
- [ ] Enable HTTPS/SSL
- [ ] Setup firewall rules

### Environment Variables yang Wajib Di-set

```bash
# Security
APP_DEBUG=false
APP_ENV=production

# Database
DB_PASSWORD=<strong_password>
MYSQL_ROOT_PASSWORD=<strong_root_password>

# Optional: Mail
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
```

---

## 🛠️ Common Commands

### Container Management

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# Rebuild images
docker-compose build --no-cache

# View specific service logs
docker-compose logs -f app

# Access container shell
docker-compose exec app bash
```

### Database Operations

```bash
# Run migrations
docker-compose exec app php artisan migrate

# Rollback migrations
docker-compose exec app php artisan migrate:rollback

# Seed database
docker-compose exec app php artisan db:seed

# Access MySQL shell
docker-compose exec db mysql -u root -p jobportal
```

### Cache & Optimization

```bash
# Clear all caches
docker-compose exec app php artisan cache:clear

# Optimize application
docker-compose exec app php artisan optimize

# View config cache
docker-compose exec app php artisan config:cache

# View route cache
docker-compose exec app php artisan route:cache
```

---

## 📊 Performance Tips

1. **Enable Config Caching:**
   ```bash
   docker-compose exec app php artisan config:cache
   ```

2. **Enable Route Caching:**
   ```bash
   docker-compose exec app php artisan route:cache
   ```

3. **Optimize Composer:**
   ```bash
   composer dump-autoload --optimize
   ```

4. **Setup Database Indexes:**
   - Ensure proper indexes pada frequently queried columns

5. **Enable MySQL Query Cache:**
   - Tambahkan ke docker-compose.yml MySQL section

---

## 🐛 Troubleshooting

### Port Already in Use

```bash
# Kill process on port 8000
lsof -ti:8000 | xargs kill -9

# Or use different port in docker-compose.yml
ports:
  - "8001:80"
```

### Database Connection Failed

```bash
# Check if db container is healthy
docker-compose ps

# Restart db container
docker-compose restart db

# Check MySQL logs
docker-compose logs db
```

### Permission Denied on Storage

```bash
# Fix permissions
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 755 storage bootstrap/cache
```

### Out of Disk Space

```bash
# Clean up unused images and volumes
docker image prune
docker volume prune
```

---

## 🔄 Backup & Restore

### Backup Database

```bash
# Backup MySQL
docker-compose exec db mysqldump -u root -p jobportal > backup_$(date +%Y%m%d_%H%M%S).sql
```

### Restore Database

```bash
# Restore MySQL
docker-compose exec -T db mysql -u root -p jobportal < backup_20240101_120000.sql
```

### Backup Application Files

```bash
# Backup entire project
tar -czf lamarin_backup_$(date +%Y%m%d_%H%M%S).tar.gz .
```

---

## 📝 Notes

- **Default Credentials (Development Only):**
  - DB Username: `root`
  - DB Password: `root`
  - Database: `jobportal`

- **Volume Persistence:**
  - Database data disimpan di `db_data` volume
  - Application files disimpan di bind mount (current directory)

- **Network:**
  - Semua services terhubung via container network
  - Database hanya accessible dari app container

---

## 🆘 Support

Untuk troubleshooting lebih lanjut:
1. Check container logs: `docker-compose logs -f`
2. Verify container is running: `docker-compose ps`
3. Test connectivity: `docker-compose exec app curl http://db:3306`

