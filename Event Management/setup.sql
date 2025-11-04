-- SQL to create database, tables, and sample rows for Event Management
CREATE DATABASE IF NOT EXISTS tridib_events DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tridib_events;

CREATE TABLE IF NOT EXISTS events (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  event_date DATETIME NOT NULL,
  location VARCHAR(255) DEFAULT NULL,
  capacity INT DEFAULT 0,
  image VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS registrations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  event_id INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(50) DEFAULT NULL,
  registered_at DATETIME NOT NULL,
  FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample events
INSERT INTO events (title,description,event_date,location,capacity) VALUES
('Open Source Meetup','A meetup to discuss open-source projects and contributions.',DATE_ADD(NOW(), INTERVAL 7 DAY),'Community Hall',50),
('Web Development Workshop','Beginner friendly workshop on HTML, CSS, PHP.',DATE_ADD(NOW(), INTERVAL 14 DAY),'Room 201, Tech Center',30);
