-- INITIAL INSERTIONS
INSERT INTO department (
		department_name,
		abbreviation,
		standard_time_in,
		standard_time_out
	)
VALUES (
		'College of Computer Science',
		'CCS',
		'08:30:00',
		'17:30:00'
	),
	(
		'College of Liberal Arts',
		'CLA',
		'08:00:00',
		'17:00:00'
	),
	(
		'College of Engineering',
		'COE',
		'07:30:00',
		'18:00:00'
	);
-- Explicit IDs so the user role defaulting to 2 always works
INSERT INTO user_role (id, role_name)
VALUES (1, 'admin'),
	(2, 'employee'),
	(3, 'manager');
-- Add the default admin account
INSERT INTO `user` (
		`id`,
		`first_name`,
		`last_name`,
		`middle_name`,
		`username`,
		`hashed_password`,
		`created_by`,
		`user_role`,
		`department`
	)
VALUES (
		1,
		'John',
		'Amery',
		'Smith',
		'admin',
		'$2y$10$Q87R8xF.vM/Y6XhI/NYxVeDdVgA21h12LAj2fwmZQ4d6MHc7NVkkO',
		NULL,
		1,
		1
	),
	(
		2,
		'Rex',
		'Saurus',
		'Tyrant',
		'trex445',
		'$2y$10$wiQf8w9SlyKP3l8hOxzqTeRNhh/RhtmhU.CkJ8t0xHNJL4TIU5tCi',
		1,
		2,
		1
	),
	(
		3,
		'Theodore',
		'Jomandado',
		'Hashiriya',
		'tva',
		'$2y$10$p8ko7/MPmLwe7nojRNcx4OgM4ig9kUJARkb1g/gfMNnPNScNn3Qxi',
		1,
		2,
		1
	);
-- Same idea here, to be certain when querying
INSERT INTO `notification` (
		`id`,
		`title`,
		`content`,
		`has_been_read`,
		`created_at`,
		`created_by`
	)
VALUES (
		8,
		'Time In',
		'tva has timed in on Mon, Nov 17, 2025, at 01:26:39 PM.',
		0,
		'2025-11-17 13:26:39',
		3
	),
	(
		9,
		'Time Out',
		'tva has timed out on Mon, Nov 17, 2025, at 01:26:48 PM.',
		0,
		'2025-11-17 13:26:48',
		3
	),
	(
		10,
		'Time In',
		'trex445 has timed in on Mon, Nov 17, 2025, at 02:32:35 PM.',
		0,
		'2025-11-17 14:32:35',
		2
	),
	(
		11,
		'Time Out',
		'tva has timed out on Mon, Nov 17, 2025, at 03:55:30 PM.',
		0,
		'2025-11-17 15:55:30',
		3
	),
	(
		12,
		'Time In',
		'tva has timed in on Sat, Nov 22, 2025, at 10:52:50 AM.',
		0,
		'2025-11-22 10:52:50',
		3
	),
	(
		13,
		'Time In',
		'trex445 has timed in on Sat, Nov 22, 2025, at 11:49:26 AM.',
		0,
		'2025-11-22 11:49:26',
		2
	);
INSERT INTO event_record_type (id, type_name)
VALUES (1, 'time_in'),
	(2, 'time_out');
INSERT INTO `event_record` (`id`, `event_time`, `event_type`, `user_id`)
VALUES (26, '2025-11-05 17:31:57', 1, 2),
	(27, '2025-11-05 17:32:10', 2, 2),
	(32, '2025-11-05 18:00:31', 1, 3),
	(33, '2025-11-05 18:00:41', 2, 3),
	(34, '2025-11-08 11:10:43', 1, 2),
	(35, '2025-11-08 11:12:00', 1, 3),
	(36, '2025-11-08 12:20:41', 2, 3),
	(37, '2025-11-08 19:48:36', 2, 2),
	(38, '2025-11-10 15:47:58', 1, 2),
	(39, '2025-11-10 15:54:56', 1, 3),
	(40, '2025-11-10 15:55:25', 2, 3),
	(41, '2025-11-10 15:55:37', 2, 2),
	(42, '2025-11-11 10:47:35', 1, 3),
	(43, '2025-11-11 10:47:45', 1, 2),
	(44, '2025-11-11 11:15:38', 2, 3),
	(46, '2025-11-12 11:52:39', 1, 2),
	(50, '2025-11-16 21:37:31', 1, 3),
	(62, '2025-11-17 13:26:39', 1, 3),
	(63, '2025-11-17 13:26:48', 2, 3),
	(64, '2025-11-17 14:32:35', 1, 2),
	(65, '2025-11-22 10:52:50', 1, 3),
	(66, '2025-11-22 11:49:26', 1, 2);