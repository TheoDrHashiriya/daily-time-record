CREATE DATABASE theonary;
USE theonary;
CREATE TABLE user(
	id INT AUTO_INCREMENT PRIMARY KEY,
	first_name VARCHAR(50),
	last_name VARCHAR(50),
	middle_name VARCHAR(50),
	username VARCHAR(50) UNIQUE,
	password VARCHAR(255),
	created_by INT NULL,
	FOREIGN KEY (created_by) REFERENCES user(id),
	role INT DEFAULT 2,
	FOREIGN KEY (role) REFERENCES user_role(id)
);
CREATE TABLE user_role(
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(50) UNIQUE
);
CREATE TABLE event_record(
	id INT AUTO_INCREMENT PRIMARY KEY,
	record_date DATE NOT NULL,
	time DATETIME NOT NULL,
	type INT NOT NULL,
	FOREIGN KEY (type) REFERENCES event_record_type(id),
	user_id INT NOT NULL,
	FOREIGN KEY (user_id) REFERENCES user(id)
);
CREATE TABLE event_record_type(
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(50) UNIQUE
);
-- INITIAL INSERTIONS
INSERT INTO user_role (id, name) -- Explicit IDs so the user role defaulting to 2 always works
VALUES (1, 'admin'),
	(2, 'employee'),
	(3, 'manager');
INSERT INTO event_record_type(name)
VALUES ('time_in'),
	('time_out');
-- Add the default admin account
INSERT INTO user (
		first_name,
		last_name,
		middle_name,
		username,
		password,
		role
	)
VALUES (
		'John',
		'Amery',
		'Smith',
		'admin',
		'$2y$10$Q87R8xF.vM/Y6XhI/NYxVeDdVgA21h12LAj2fwmZQ4d6MHc7NVkkO',
		1
	);
-- To generate new password hashes, use this:
-- php -r "echo password_hash("YourPassword", PASSWORD_DEFAULT) . PHP_EOL;"