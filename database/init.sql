CREATE DATABASE IF NOT EXISTS wearview_supportdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE wearview_supportdb;

DROP TABLE IF EXISTS issues;
CREATE TABLE issues (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  reporter_name VARCHAR(100) NOT NULL,
  reporter_email VARCHAR(100) NOT NULL,
  fault_title VARCHAR(100) NOT NULL,
  location VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  status ENUM('incomplete','complete') NOT NULL DEFAULT 'incomplete',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INT NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('staff','tech') NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO issues
  (reporter_name, reporter_email, fault_title, location, description, status, created_at, updated_at)
VALUES
  ('Demo Staff User', 'staff.user@example.com', 'Projector not displaying', 'Room A12', 'The classroom projector powers on but does not display the connected laptop screen.', 'incomplete', '2024-06-28 16:23:51', NULL),
  ('Demo Lecturer User', 'lecturer.user@example.com', 'Network connection unavailable', 'Computer Lab 2', 'Several desktop machines cannot connect to the wired network during scheduled lab sessions.', 'complete', '2024-06-30 14:48:58', '2024-06-30 14:49:49');

-- Local demo credentials only. Do not reuse these in a real deployment.
INSERT INTO users (username, password, role)
VALUES
  ('staffmember', 'staffdemopass!123_', 'staff'),
  ('admin', 'techdemopass!456_', 'tech');
