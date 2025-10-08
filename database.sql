CREATE DATABASE theonary;
USE theonary;

CREATE TABLE user(
	id INT AUTO_INCREMENT PRIMARY KEY,
	first_name VARCHAR(50),
	last_name VARCHAR(50),
	middle_name VARCHAR(50),
	username VARCHAR(50) UNIQUE,
	password VARCHAR(255),
	user_type ENUM ('admin', 'employee') DEFAULT 'employee'
);

CREATE TABLE daily_time_record(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    record_date DATE,
    time_in DATETIME,
    time_out DATETIME,
    FOREIGN KEY(user_id) REFERENCES user(id)
);

-- Add the default admin account
INSERT INTO user (first_name, last_name, middle_name, username, password, user_type) VALUES ('admin', 'admin', 'adminson', 'admin', '$2y$10$Q87R8xF.vM/Y6XhI/NYxVeDdVgA21h12LAj2fwmZQ4d6MHc7NVkkO', 'admin');

-- To generate new password hashes, use this:
-- php -r "echo password_hash("YourPassword", PASSWORD_DEFAULT) . PHP_EOL;"