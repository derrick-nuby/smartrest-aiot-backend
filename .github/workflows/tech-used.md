# SmartRest AIoT Backend - Technologies Used

## 🏗️ **Core Framework & Runtime**

### **Laravel 12.x** (PHP Web Framework)
- **Purpose**: Backend API framework providing MVC architecture
- **Key Features**: 
  - RESTful API routing (`routes/api.php`)
  - Eloquent ORM for database management
  - Sanctum for API authentication
  - Built-in validation and middleware
- **Potential CI Issues**: 
  - Missing `.env` configuration in CI environment
  - Database connection issues
  - Cache/config optimization needed for production

### **PHP 8.2** (Runtime)
- **Purpose**: Server-side scripting language
- **Extensions Required**: 
  - `gd` (image processing)
  - `pdo_pgsql` (PostgreSQL driver)
  - `freetype`, `jpeg` (image libraries)
- **Potential CI Issues**: 
  - PHP version mismatch between local/CI
  - Missing PHP extensions in CI container

## 🗄️ **Database & Data Management**

### **PostgreSQL 16** (Primary Database)
- **Purpose**: Production database for user data, sensor readings, messaging
- **Configuration**: 
  - Database: `smartbed`
  - User: `derrickuser`
  - Port: `5432`
- **Schema**: 
  - UUID-based primary keys
  - Complex relationships (User → PatientProfile/DoctorProfile)
  - Sensor data with custom enum types
- **Potential CI Issues**: 
  - Database not available during CI build
  - Missing database seeding in CI
  - Connection timeout issues

### **Eloquent ORM** (Database Abstraction)
- **Models**: `User`, `PatientProfile`, `DoctorProfile`, `SensorReading`, `Message`, `Product`, `SystemLog`
- **Features**: 
  - UUID traits for all models
  - Factory-based data seeding
  - Relationship mapping (1:1, 1:many, many:many)

## 🔐 **Authentication & Security**

### **Laravel Sanctum** (API Authentication)
- **Purpose**: JWT-based API token authentication
- **Features**: 
  - Personal access tokens
  - SPA authentication
  - Mobile app support
- **Potential CI Issues**: 
  - Missing `APP_KEY` in CI environment
  - JWT secret configuration issues

### **BCrypt** (Password Hashing)
- **Configuration**: 12 rounds for production security
- **Purpose**: Secure password storage

## 📡 **API Documentation**

### **L5-Swagger (darkaonline/l5-swagger 9.0)** 
- **Purpose**: OpenAPI/Swagger documentation generation
- **Features**: 
  - Auto-generated API docs at `/api/documentation`
  - Request/response schema validation
  - Interactive API testing interface
- **Endpoints Documented**: 35+ API routes across 7 categories
- **Potential CI Issues**: 
  - Swagger annotation parsing errors
  - Missing OpenAPI configuration

## 🚀 **DevOps & Deployment**

### **Docker** (Containerization)
- **Base Image**: `php:8.2-cli`
- **Services**: 
  - Main application (currently commented out)
  - PostgreSQL database
  - pgAdmin for database management
- **Configuration**: Multi-stage build with Composer
- **Potential CI Issues**: 
  - Docker layer caching issues
  - Missing environment variables in container
  - Network connectivity between services

### **GitHub Actions** (CI/CD Pipeline)
- **Workflow**: `.github/workflows/laravel-ci.yml`
- **Stages**: 
  1. **Build Stage**: PHP 8.2 setup, Composer install, artifact creation
  2. **Docker Stage**: Docker Hub login, image build & push
- **Potential Issues**: 
  - Artifact download path mismatch (`backend` vs root)
  - Missing dependency installation in Docker context
  - Environment variable conflicts

### **Docker Hub** (Container Registry)
- **Repository**: `smartRest-backend`
- **Tags**: `latest` and commit SHA
- **Authentication**: Using repository secrets

## 🎨 **Frontend Assets (Minimal)**

### **Vite** (Build Tool)
- **Purpose**: Asset compilation and development server
- **Configuration**: Laravel plugin with Tailwind CSS
- **Assets**: `resources/css/app.css`, `resources/js/app.js`
- **Potential CI Issues**: 
  - Node.js not installed in CI
  - Missing `npm install` step
  - Asset compilation failures

### **Tailwind CSS** (Utility-First CSS)
- **Integration**: Via Vite plugin
- **Purpose**: Styling for minimal frontend views

## 📬 **External Services**

### **Gmail SMTP** (Email Service)
- **Configuration**: 
  - Host: `smtp.gmail.com`
  - Port: `587` (TLS encryption)
  - App Password authentication
- **Purpose**: Password reset, email verification, notifications
- **Potential CI Issues**: 
  - SMTP credentials not available in CI
  - Email testing failures

## 🧪 **Testing Framework**

### **Pest PHP** (Testing Framework)
- **Purpose**: Modern PHP testing with expressive syntax
- **Configuration**: Laravel plugin integration
- **Files**: `tests/Feature/`, `tests/Unit/`
- **Potential CI Issues**: 
  - Tests not running in CI pipeline
  - Database not properly seeded for tests

## 📊 **Data Seeding & Factories**

### **Database Factories**
- **Models**: All major models have factory classes
- **Purpose**: Generate realistic test data
- **Seeder**: `SmartRestSeeder` creates complete dataset
- **Data Generated**: 
  - 1 Admin user
  - 5 Doctors with profiles  
  - 20 Patients with profiles
  - 3 Customers
  - 10 Products
  - Thousands of sensor readings
  - Hundreds of messages and system logs

## 🔧 **Build & Configuration Issues Identified**

### **Critical CI/CD Issues**

1. **Artifact Path Mismatch**
   ```yaml
   # Issue: Artifact saved to root but extracted to 'backend' folder
   - name: Download build artifact
     uses: actions/download-artifact@v4
     with:
       name: smartRest-backend
       path: backend  # Should be '.' or update Dockerfile context
   ```

2. **Missing Database in CI**
   - Laravel requires database connection even for basic builds
   - No PostgreSQL service configured in CI workflow
   - May need SQLite for CI testing

3. **Missing Node.js Dependencies**
   - Vite configuration present but no Node.js setup in CI
   - `package.json` exists but `npm install` not run

4. **Environment Variables**
   - `.env` file not created in CI environment
   - Missing `APP_KEY` generation step
   - Database credentials not configured

### **Recommended CI/CD Fixes**

1. **Add PostgreSQL Service to CI**
   ```yaml
   services:
     postgres:
       image: postgres:16
       env:
         POSTGRES_PASSWORD: postgres
       options: >-
         --health-cmd pg_isready
         --health-interval 10s
         --health-timeout 5s
         --health-retries 5
   ```

2. **Add Environment Setup**
   ```yaml
   - name: Create .env file
     run: |
       cp .env.example .env
       php artisan key:generate
   ```

3. **Fix Artifact Path**
   ```yaml
   - name: Download build artifact
     uses: actions/download-artifact@v4
     with:
       name: smartRest-backend
       path: .  # Changed from 'backend'
   ```

4. **Add Node.js Setup** (if frontend assets needed)
   ```yaml
   - name: Setup Node.js
     uses: actions/setup-node@v3
     with:
       node-version: '18'
   - name: Install dependencies
     run: npm install
   ```

## 📈 **API Architecture Overview**

### **Route Categories** (35 endpoints total)
1. **Authentication & Session Management** (10 endpoints)
2. **User Management** (5 endpoints)  
3. **Product Catalog** (4 endpoints)
4. **Sensor Data Collection** (3 endpoints)
5. **Messaging & Notifications** (4 endpoints)
6. **Analytics & Reports** (2 endpoints)
7. **System & Device Management** (2 endpoints)

### **Data Flow Architecture**
```
IoT Devices → Sensor API → PostgreSQL → Analytics → Dashboard
    ↓            ↓            ↓            ↓           ↓
Device Auth → JWT Tokens → Data Storage → Reports → Web/Mobile
```

This comprehensive tech stack supports a full-featured IoT backend with secure authentication, real-time data collection, user management, and administrative capabilities.