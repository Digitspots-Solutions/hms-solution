# DigitSpots HMS Solution - Application Overview

Welcome to the **DigitSpots HMS (Hotel Management System) Solution**. This repository serves as the central hub and landing page for a suite of interconnected business applications designed to manage hotel operations, point-of-sale activities, and travel management.

## 🚀 Application Functionality

The software suite is built on a standard **HTML, CSS, JavaScript, PHP, and MySQL** stack and is divided into three distinct sub-applications. The root repository acts as a portal granting clients access to their enabled services.

1. **Hotel Management (`digit-spot-hms-hotel`)**: 
   A comprehensive module for internal booking, room reservations, guest management, and recreation services.
2. **Point of Sale (`digit-spot-hms-pos`)**: 
   Designed for hotel outlets, supermarkets, event centers, and lounges to handle transactions and inventory.
3. **Travel Master (`digit-spot-hms-travel-master`)**: 
   An upcoming module focused on travel and transport management (Currently in-view).

**Architecture Note**: 
The Hotel Management and Point of Sale applications are functionally independent in terms of their codebase but **share the same MySQL database (`hmsdb`)**. This allows for unified reporting and data centralization across a hotel's various operational facets.

---

## 🛠️ Local Development Setup

The application currently supports local development through Docker Compose, which containerizes the Apache web server, PHP environment, and MariaDB database.

1. Ensure you have **Docker** and **Docker Compose** installed.
2. Run the following command in the root directory:
   ```bash
   docker-compose up -d --build
   ```
3. The application will be accessible at `http://localhost/digit-spot-hms/`.
4. The database is automatically seeded with the `hmsdb.sql` schema on the first run.

*(Legacy Local Setup: Historically, the application was deployed via a local Apache WAMP server over a Local Area Network (LAN), mapping client systems to the server's network IP).*

---

## ⚠️ Current Application Shortcomings & Needed Improvements

The application is currently transitioning from a legacy on-premise model to a modern cloud-native architecture. As such, several code-level improvements must be made:

### 1. Hardcoded Configuration & Lack of Environment Variables
- **Current State**: Database credentials and domain paths are hardcoded into PHP files (e.g., `connection_string.php`, `php_paths.php`). During deployment, a bash script manually replaces these strings using `sed`.
- **Improvement**: Implement environment variables (e.g., using `.env` files or PHP's `getenv()`). This will completely decouple configuration from code, improving security and removing the need for hacky deployment string replacements.

### 2. Local File System Storage (Instance Storage)
- **Current State**: User uploads (images, documents, reports) are stored directly on the local server file system within the `www/html` directory.
- **Improvement**: Migrate all file uploads and media handling to **Amazon S3** using the AWS SDK for PHP. Because files are stored locally, the application cannot be horizontally scaled. If an EC2 instance is terminated, local files are lost.

### 3. Monolithic Coupling
- **Current State**: The applications share the same database but are completely separate folders with duplicated assets and logic.
- **Improvement**: Refactor shared business logic into reusable PHP components (using Composer for dependency management) to avoid code duplication across the Hotel and POS systems.

### 4. Logging & Error Handling
- **Current State**: Relies on standard PHP error output which can obscure production issues.
- **Improvement**: Integrate a robust logging library (like Monolog) to stream application logs to a centralized cloud service (like Amazon CloudWatch).
