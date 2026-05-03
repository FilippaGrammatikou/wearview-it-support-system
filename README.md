<p align="center">
  <img alt="PHP" src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" />
  <img alt="MariaDB" src="https://img.shields.io/badge/MariaDB-10.11-003545?style=for-the-badge&logo=mariadb&logoColor=white" />
  <img alt="Docker" src="https://img.shields.io/badge/Docker-Compose-2496ED?style=for-the-badge&logo=docker&logoColor=white" />
  <img alt="JavaScript" src="https://img.shields.io/badge/JavaScript-Client%20Validation-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" />
  <img alt="CSS" src="https://img.shields.io/badge/CSS-Responsive%20UI-1572B6?style=for-the-badge&logo=css3&logoColor=white" />
</p>

# WearView IT Support System

This is an earlier full-stack coursework app with PHP, MariaDB, Docker, forms, validation, and a complete user workflow for a 2024 university coursework archive, later cleaned and Dockerized for local execution.

The application models a small internal IT support workflow for a fictional organisation. Staff users can submit technical fault reports, while technician users can view submitted jobs, mark them complete, and delete completed records.

This repository has been cleaned for GitHub publication. It does not depend on the original university hosting environment, university webspace URLs, assignment materials, or private database credentials.

## Features

* Staff and technician login flow
* Fault report submission form
* Client-side and server-side form validation
* MariaDB/MySQL persistence through PHP PDO
* Technician dashboard for incomplete and completed jobs
* Complete and delete actions for technician users
* Responsive HTML/CSS interface
* Docker Compose setup for local execution
* Sanitised local demo database seed

## Tech Stack

* PHP 8.3
* Apache
* MariaDB
* HTML
* CSS
* JavaScript
* Docker Compose

## Running the Project

### Requirements

* Docker Desktop
* A web browser

No separate PHP, Apache, MySQL, XAMPP, WAMP, or Laragon installation is required when using Docker.

### Start the application

From the repository root, run:

```bash
docker compose up --build
```

Then open:

```text
http://localhost:8080
```

The root URL redirects to:

```text
http://localhost:8080/LoginPage.php
```

### Demo Credentials

These credentials are for the local seeded demo database only.

| Role       | Username      | Password             |
| ---------- | ------------- | -------------------- |
| Staff      | `staffmember` | `staffdemopass!123_` |
| Technician | `admin`       | `techdemopass!456_`  |

## Database Seed

The local database is created from:

```text
database/init.sql
```

The seed contains demo users and fictional example support tickets.

Data created while using the app is stored in a local Docker volume. It is not committed to Git.

To reset the database back to the original seed data:

```bash
docker compose down -v
docker compose up --build
```

## Environment Configuration

The default app port is configured through:

```text
.env.example
```

To use a different local port, copy it to `.env`:

```bash
cp .env.example .env
```

Then edit:

```env
APP_PORT=8090
```

and run:

```bash
docker compose up --build
```

## Repository Context

This project remains close to the original coursework implementation. The cleanup focused on making the project safe to publish and easy to run locally through Docker, rather than rewriting it into a modern production application.

The repository excludes:

* University webspace links
* Assignment briefs or marking material
* Private database credentials
* Local-only configuration files
* Generated or environment-specific files

## Limitations

This is an academic archive, not a production system.

Known limitations include:

* Demo passwords are stored in plain text to preserve the original coursework scope.
* The application uses simple page-level PHP rather than a framework.
* Form security is limited compared with a production application.
* The database seed is fictional and intended only for local demonstration.

## Status

Archived and cleaned for portfolio reference.
