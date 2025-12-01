#!/bin/bash

# Lamarin Docker Deployment - Quick Start Script
# Run this to quickly set up and deploy the application

set -e

echo "🚀 Lamarin Docker Deployment - Quick Start"
echo "========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

# Check if Docker is installed
print_status "Checking Docker installation..."
if ! command -v docker &> /dev/null; then
    print_error "Docker is not installed. Please install Docker first."
    exit 1
fi
print_success "Docker found: $(docker --version)"

# Check if Docker Compose is installed
print_status "Checking Docker Compose installation..."
if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi
print_success "Docker Compose found: $(docker-compose --version)"

echo ""
echo "========================================="

# Step 1: Check if .env exists
print_status "Checking .env file..."
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        print_warning ".env not found, copying from .env.example"
        cp .env.example .env
        print_success ".env created from .env.example"
    else
        print_error ".env file not found and no .env.example available"
        exit 1
    fi
else
    print_success ".env file exists"
fi

echo ""

# Step 2: Build Docker images
print_status "Building Docker images..."
docker-compose build --no-cache
print_success "Docker images built successfully"

echo ""

# Step 3: Start containers
print_status "Starting containers..."
docker-compose up -d
print_success "Containers started"

echo ""

# Step 4: Wait for database to be ready
print_status "Waiting for database to be ready (this may take a minute)..."
sleep 10

# Check if database is healthy
max_attempts=30
attempt=1
while [ $attempt -le $max_attempts ]; do
    if docker-compose exec -T db mysqladmin ping -h localhost -u root -proot &> /dev/null; then
        print_success "Database is ready!"
        break
    fi
    echo "  Attempt $attempt/$max_attempts - waiting for database..."
    sleep 2
    attempt=$((attempt + 1))
done

if [ $attempt -gt $max_attempts ]; then
    print_warning "Database took longer than expected to start. Continuing anyway..."
fi

echo ""

# Step 5: Run migrations
print_status "Running database migrations..."
docker-compose exec -T app php artisan migrate --force
print_success "Migrations completed"

echo ""

# Step 6: Generate API documentation
print_status "Generating API documentation..."
docker-compose exec -T app php artisan l5-swagger:generate
print_success "API documentation generated"

echo ""

# Step 7: Set permissions
print_status "Setting proper permissions..."
docker-compose exec -T app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec -T app chmod -R 755 storage bootstrap/cache
print_success "Permissions set"

echo ""
echo "========================================="
echo ""
echo "✅ Deployment completed successfully!"
echo ""
echo "📊 Container Status:"
docker-compose ps
echo ""
echo "🌐 Access Points:"
echo "  • API:               http://localhost:8000"
echo "  • API Documentation: http://localhost:8000/api/documentation"
echo ""
echo "📝 Useful Commands:"
echo "  • View logs:         docker-compose logs -f app"
echo "  • Access shell:      docker-compose exec app bash"
echo "  • Run artisan cmd:   docker-compose exec app php artisan <command>"
echo "  • Stop containers:   docker-compose down"
echo ""
echo "🔒 Security Notes:"
echo "  • Debug mode is DISABLED"
echo "  • Database port is NOT exposed"
echo "  • Security headers are ENABLED"
echo "  • Sensitive files are PROTECTED"
echo ""
print_success "Deployment ready for testing!"

