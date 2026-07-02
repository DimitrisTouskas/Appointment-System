-- Sample data. Safe to run on a database that already has other rows —
-- appointments look up the seeded users' ids dynamically instead of assuming 1/2/3.
-- Run only once per database (username/email are UNIQUE, a second run will error on duplicates).
-- Test login for all seeded users: password "Password123!"

INSERT INTO users (username, first_name, last_name, email, password, role) VALUES
('maria_p', 'Maria', 'Papadopoulou', 'maria@example.com', '$2y$10$PkLHx/Jtzt55BbAdbzKEgOrAuo7DxCgLmihKXbn8IvpqVQXOSdbP2', 'user'),
('john_doe', 'John', 'Smith', 'john@example.com', '$2y$10$PkLHx/Jtzt55BbAdbzKEgOrAuo7DxCgLmihKXbn8IvpqVQXOSdbP2', 'user'),
('admin_user', 'Admin', 'Account', 'admin@example.com', '$2y$10$PkLHx/Jtzt55BbAdbzKEgOrAuo7DxCgLmihKXbn8IvpqVQXOSdbP2', 'admin');

INSERT INTO appointments (user_id, appointment_date, appointment_time, status, notes) VALUES
((SELECT id FROM users WHERE username='maria_p'), '2026-07-05', '10:30', 'pending', 'Πρώτη συνάντηση, φέρε φάκελο'),
((SELECT id FROM users WHERE username='maria_p'), '2026-07-08', '09:15', 'completed', 'Follow up σε 2 εβδομάδες'),
((SELECT id FROM users WHERE username='john_doe'), '2026-07-06', '14:00', 'confirmed', NULL),
((SELECT id FROM users WHERE username='john_doe'), '2026-07-10', '17:45', 'cancelled', 'Ακύρωση λόγω ασθένειας'),
((SELECT id FROM users WHERE username='admin_user'), '2026-07-12', '11:00', 'pending', 'Επανέλεγχος'),
((SELECT id FROM users WHERE username='admin_user'), '2026-07-14', '16:30', 'confirmed', 'Τελευταίο ραντεβού μήνα');
