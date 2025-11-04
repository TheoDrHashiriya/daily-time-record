CREATE DATABASE theonary;
USE theonary;
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
	hashed_password VARCHAR(255) NOT NULL,
	created_by INT NULL,
	FOREIGN KEY (created_by) REFERENCES user(id),
	user_role INT DEFAULT 2,
	FOREIGN KEY (user_role) REFERENCES user_role(id)
);
CREATE TABLE event_record_type(
	id INT AUTO_INCREMENT PRIMARY KEY,
	type_name VARCHAR(50) UNIQUE NOT NULL
);
CREATE TABLE event_record(
	id INT AUTO_INCREMENT PRIMARY KEY,
	event_date DATE NOT NULL,
	event_time DATETIME NOT NULL,
	event_type INT NOT NULL,
	FOREIGN KEY (event_type) REFERENCES event_record_type(id),
	user_id INT NOT NULL,
	FOREIGN KEY (user_id) REFERENCES user(id)
);
-- INITIAL INSERTIONS
-- Explicit IDs so the user role defaulting to 2 always works
INSERT INTO user_role (id, role_name)
VALUES (1, 'admin'),
	(2, 'employee'),
	(3, 'manager');
-- Same idea here, to be certain when querying
INSERT INTO event_record_type(id, type_name)
VALUES (1, 'time_in'),
	(2, 'time_out');
-- Add the default admin account
INSERT INTO user (
		first_name,
		last_name,
		middle_name,
		username,
		hashed_password,
		user_role
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
-- php -r "echo password_hash('YourPassword', PASSWORD_DEFAULT) . PHP_EOL;"