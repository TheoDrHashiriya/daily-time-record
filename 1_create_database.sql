-- To generate new password hashes, use this:
-- php -r "echo password_hash('YourPassword', PASSWORD_DEFAULT) . PHP_EOL;"
CREATE DATABASE IF NOT EXISTS theonary;
USE theonary;
CREATE TABLE department(
	id INT AUTO_INCREMENT PRIMARY KEY,
	department_name VARCHAR(50) UNIQUE NOT NULL,
	abbreviation VARCHAR(10) UNIQUE NOT NULL,
	standard_time_in TIME NOT NULL,
	standard_time_out TIME NOT NULL,
	created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE user_role(
	id INT AUTO_INCREMENT PRIMARY KEY,
	role_name VARCHAR(50) UNIQUE NOT NULL
);
CREATE TABLE user(
	id INT AUTO_INCREMENT PRIMARY KEY,
	first_name VARCHAR(50) NOT NULL,
	last_name VARCHAR(50) NOT NULL,
	middle_name VARCHAR(50) NULL,
	username VARCHAR(100) UNIQUE NOT NULL,
	email VARCHAR(50) UNIQUE NOT NULL,
	hashed_password VARCHAR(255) NOT NULL,
	FOREIGN KEY (created_by) REFERENCES user(id),
	user_role INT DEFAULT 2,
	FOREIGN KEY (user_role) REFERENCES user_role(id),
	department INT NOT NULL,
	FOREIGN KEY (department) REFERENCES department(id)
	created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	created_by INT NULL,
);
CREATE TABLE notification(
	id INT AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(100) NOT NULL,
	content TEXT NOT NULL,
	has_been_read BOOLEAN DEFAULT FALSE,
	created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	created_by INT NULL,
	FOREIGN KEY (created_by) REFERENCES user(id)
);
CREATE TABLE event_record_type(
	id INT AUTO_INCREMENT PRIMARY KEY,
	type_name VARCHAR(50) UNIQUE NOT NULL
);
CREATE TABLE event_record(
	id INT AUTO_INCREMENT PRIMARY KEY,
	event_time DATETIME NOT NULL,
	event_type INT NOT NULL,
	FOREIGN KEY (event_type) REFERENCES event_record_type(id),
	user_id INT NOT NULL,
	FOREIGN KEY (user_id) REFERENCES user(id)
);