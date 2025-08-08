# Installation & Deployment Guide - CMS Faisal

## Table of Contents

1. [System Requirements](#system-requirements)
2. [Local Development Setup](#local-development-setup)
3. [Environment Configuration](#environment-configuration)
4. [Database Setup](#database-setup)
5. [Asset Building](#asset-building)
6. [Production Deployment](#production-deployment)
7. [Docker Setup](#docker-setup)
8. [Troubleshooting](#troubleshooting)

## System Requirements

### Minimum Requirements
- **PHP**: 8.2 or higher
- **Composer**: 2.0 or higher
- **Node.js**: 18.0 or higher
- **NPM/PNPM**: Latest stable version
- **Database**: MySQL 8.0+ or MariaDB 10.3+
- **Web Server**: Apache 2.4+ or Nginx 1.18+

### Recommended Requirements
- **PHP**: 8.3+
- **Memory**: 2GB RAM minimum, 4GB recommended
- **Storage**: 10GB available space
- **Database**: MySQL 8.0+ with InnoDB engine

### PHP Extensions Required
```
- BCMath
- Ctype
- cURL
- DOM
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PCRE
- PDO
- Tokenizer
- XML
- GD or Imagick (for image processing)
```

## Local Development Setup

### Step 1: Clone the Repository

```bash
# Clone the repository
git clone https://github.com/Faisd405/cms-faisal.git
cd cms-faisal

# Or if you're starting fresh
composer create-project laravel/laravel cms-faisal
cd cms-faisal
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
pnpm install
# or
npm install
```

### Step 3: Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Environment

Edit the `.env` file with your local settings:

```env
# Application
APP_NAME="CMS Faisal"
APP_ENV=local
APP_KEY=base64:your-generated-key
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cms_faisal
DB_USERNAME=root
DB_PASSWORD=

# Cache & Session
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Mail (for development)
MAIL_MAILER=log
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_ADDRESS="noreply@cms-faisal.com"
MAIL_FROM_NAME="${APP_NAME}"

# Frontend
VITE_APP_NAME="${APP_NAME}"
```

### Step 5: Database Setup

```bash
# Create database
mysql -u root -p
CREATE DATABASE cms_faisal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed
```

### Step 6: Build Assets

```bash
# Development build with hot reload
pnpm run dev

# Or build for development
pnpm run build
```

### Step 7: Start Development Server

```bash
# Start Laravel development server
php artisan serve

# The application will be available at http://localhost:8000
```

## Environment Configuration

### Development Environment

```env
# .env for development
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cms_faisal_dev
DB_USERNAME=root
DB_PASSWORD=

# Cache & Queue
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

# Mail
MAIL_MAILER=log

# Frontend
VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### Staging Environment

```env
# .env for staging
APP_ENV=staging
APP_DEBUG=false
APP_URL=https://staging.cms-faisal.com

# Database
DB_CONNECTION=mysql
DB_HOST=your-staging-db-host
DB_PORT=3306
DB_DATABASE=cms_faisal_staging
DB_USERNAME=staging_user
DB_PASSWORD=secure_password

# Cache & Queue
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-smtp-username
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls
```

### Production Environment

```env
# .env for production
APP_ENV=production
APP_DEBUG=false
APP_URL=https://cms-faisal.com

# Database
DB_CONNECTION=mysql
DB_HOST=your-production-db-host
DB_PORT=3306
DB_DATABASE=cms_faisal_production
DB_USERNAME=production_user
DB_PASSWORD=very_secure_password

# Cache & Queue
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=redis_password
REDIS_PORT=6379

# Session & Cookie
SESSION_DRIVER=redis
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls

# File Storage
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-aws-access-key
AWS_SECRET_ACCESS_KEY=your-aws-secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-s3-bucket
AWS_USE_PATH_STYLE_ENDPOINT=false

# Security
SANCTUM_STATEFUL_DOMAINS=cms-faisal.com,www.cms-faisal.com
```

## Database Setup

### MySQL Configuration

#### Creating Database and User

```sql
-- Connect to MySQL as root
mysql -u root -p

-- Create database
CREATE DATABASE cms_faisal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user for production
CREATE USER 'cms_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON cms_faisal.* TO 'cms_user'@'localhost';
FLUSH PRIVILEGES;

-- Exit MySQL
EXIT;
```

#### Database Configuration Optimization

Add to `/etc/mysql/mysql.conf.d/mysqld.cnf`:

```ini
[mysqld]
# InnoDB settings
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_file_per_table = 1
innodb_flush_method = O_DIRECT

# Connection settings
max_connections = 200
wait_timeout = 600
interactive_timeout = 600

# Character set
character-set-server = utf8mb4
collation-server = utf8mb4_unicode_ci

# Query cache
query_cache_type = 1
query_cache_size = 128M
```

### Running Migrations

```bash
# Run migrations
php artisan migrate

# Run migrations with seeding
php artisan migrate --seed

# Rollback migrations (if needed)
php artisan migrate:rollback

# Reset and re-run migrations
php artisan migrate:fresh --seed
```

### Database Seeding

```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=UserSeeder

# Create sample data for development
php artisan db:seed --class=DevelopmentSeeder
```

## Asset Building

### Development Build

```bash
# Build assets for development
pnpm run build

# Watch for changes and rebuild (hot reload)
pnpm run dev

# Check for linting issues
pnpm run eslint

# Fix linting issues automatically
pnpm run eslint-fix
```

### Production Build

```bash
# Build optimized assets for production
pnpm run build

# Build with analysis (to check bundle size)
pnpm run build -- --analyze
```

### Build Configuration

#### Vite Configuration (`vite.config.js`)

```javascript
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import { resolve } from 'path'

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
        },
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['vue', 'axios'],
                    ui: ['flowbite-vue', '@tinymce/tinymce-vue'],
                    utils: ['lodash', 'pinia']
                }
            }
        },
        chunkSizeWarningLimit: 1000
    }
})
```

## Production Deployment

### Server Requirements

#### Apache Configuration

Create virtual host configuration:

```apache
<VirtualHost *:80>
    ServerName cms-faisal.com
    ServerAlias www.cms-faisal.com
    DocumentRoot /var/www/cms-faisal/public
    
    <Directory /var/www/cms-faisal/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/cms-faisal_error.log
    CustomLog ${APACHE_LOG_DIR}/cms-faisal_access.log combined
</VirtualHost>

<VirtualHost *:443>
    ServerName cms-faisal.com
    ServerAlias www.cms-faisal.com
    DocumentRoot /var/www/cms-faisal/public
    
    SSLEngine on
    SSLCertificateFile /path/to/your/certificate.crt
    SSLCertificateKeyFile /path/to/your/private.key
    
    <Directory /var/www/cms-faisal/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx Configuration

```nginx
server {
    listen 80;
    listen 443 ssl http2;
    server_name cms-faisal.com www.cms-faisal.com;
    root /var/www/cms-faisal/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # SSL Configuration
    ssl_certificate /path/to/your/certificate.crt;
    ssl_certificate_key /path/to/your/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
}
```

### Deployment Script

Create a deployment script (`deploy.sh`):

```bash
#!/bin/bash

# CMS Faisal Deployment Script

set -e

echo "Starting deployment..."

# Variables
APP_DIR="/var/www/cms-faisal"
BRANCH="main"
PHP_VERSION="8.3"

# Navigate to application directory
cd $APP_DIR

# Enable maintenance mode
php artisan down

# Pull latest changes
git fetch origin
git reset --hard origin/$BRANCH

# Install/update dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
pnpm install --frozen-lockfile
pnpm run build

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize

# Set proper permissions
chown -R www-data:www-data $APP_DIR
chmod -R 755 $APP_DIR
chmod -R 775 $APP_DIR/storage
chmod -R 775 $APP_DIR/bootstrap/cache

# Restart services
systemctl reload php$PHP_VERSION-fpm
systemctl reload nginx

# Disable maintenance mode
php artisan up

echo "Deployment completed successfully!"
```

### File Permissions

```bash
# Set proper ownership
sudo chown -R www-data:www-data /var/www/cms-faisal

# Set directory permissions
sudo find /var/www/cms-faisal -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/cms-faisal -type f -exec chmod 644 {} \;

# Set writable directories
sudo chmod -R 775 /var/www/cms-faisal/storage
sudo chmod -R 775 /var/www/cms-faisal/bootstrap/cache
```

### SSL Setup with Let's Encrypt

```bash
# Install Certbot
sudo apt update
sudo apt install certbot python3-certbot-nginx

# Obtain SSL certificate
sudo certbot --nginx -d cms-faisal.com -d www.cms-faisal.com

# Test auto-renewal
sudo certbot renew --dry-run
```

## Docker Setup

### Dockerfile

```dockerfile
FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
RUN npm install && npm run build

# Change current user to www
USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
```

### Docker Compose

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: cms-faisal-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
      - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
    networks:
      - cms-network

  nginx:
    image: nginx:alpine
    container_name: cms-faisal-nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    networks:
      - cms-network

  mysql:
    image: mysql:8.0
    container_name: cms-faisal-mysql
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: cms_faisal
      MYSQL_USER: cms_user
      MYSQL_PASSWORD: cms_password
      MYSQL_ROOT_PASSWORD: root_password
    volumes:
      - mysql_data:/var/lib/mysql
    ports:
      - "3306:3306"
    networks:
      - cms-network

  redis:
    image: redis:alpine
    container_name: cms-faisal-redis
    restart: unless-stopped
    ports:
      - "6379:6379"
    networks:
      - cms-network

networks:
  cms-network:
    driver: bridge

volumes:
  mysql_data:
    driver: local
```

### Docker Commands

```bash
# Build and start containers
docker-compose up -d --build

# Stop containers
docker-compose down

# View logs
docker-compose logs -f app

# Execute commands in container
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed

# Rebuild specific service
docker-compose up -d --build app
```

## Troubleshooting

### Common Issues

#### 1. Permission Denied Errors

```bash
# Fix storage permissions
sudo chmod -R 775 storage/
sudo chmod -R 775 bootstrap/cache/
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
```

#### 2. Composer Memory Issues

```bash
# Increase PHP memory limit
php -d memory_limit=-1 /usr/local/bin/composer install

# Or set in php.ini
memory_limit = 2G
```

#### 3. Node.js Build Issues

```bash
# Clear npm cache
npm cache clean --force

# Delete node_modules and reinstall
rm -rf node_modules
npm install

# Use specific Node version
nvm use 18
npm install
```

#### 4. Database Connection Issues

```bash
# Check MySQL service
sudo systemctl status mysql

# Restart MySQL
sudo systemctl restart mysql

# Check database exists
mysql -u root -p -e "SHOW DATABASES;"
```

#### 5. Asset Build Failures

```bash
# Clear Vite cache
rm -rf node_modules/.vite

# Clear Laravel caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Rebuild assets
npm run build
```

### Log Files

Check these log files for debugging:

```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/error.log
tail -f /var/log/nginx/access.log

# Apache logs
tail -f /var/log/apache2/error.log

# PHP-FPM logs
tail -f /var/log/php8.3-fpm.log

# MySQL logs
tail -f /var/log/mysql/error.log
```

### Performance Optimization

#### PHP-FPM Configuration

```ini
; /etc/php/8.3/fpm/pool.d/www.conf
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.process_idle_timeout = 10s
pm.max_requests = 500
```

#### PHP Configuration

```ini
; /etc/php/8.3/fpm/php.ini
memory_limit = 256M
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
max_input_vars = 3000
opcache.enable = 1
opcache.memory_consumption = 128
opcache.interned_strings_buffer = 8
opcache.max_accelerated_files = 4000
opcache.revalidate_freq = 2
opcache.fast_shutdown = 1
```

### Backup Strategy

```bash
#!/bin/bash
# backup.sh

# Database backup
mysqldump -u cms_user -p cms_faisal > backup_$(date +%Y%m%d_%H%M%S).sql

# File backup
tar -czf files_backup_$(date +%Y%m%d_%H%M%S).tar.gz \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='storage/logs' \
    /var/www/cms-faisal

# Upload to S3 (optional)
aws s3 cp backup_$(date +%Y%m%d_%H%M%S).sql s3://your-backup-bucket/database/
aws s3 cp files_backup_$(date +%Y%m%d_%H%M%S).tar.gz s3://your-backup-bucket/files/
```

---

*This installation and deployment guide provides comprehensive instructions for setting up CMS Faisal in various environments. Always test deployments in a staging environment before applying to production.*
