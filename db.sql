CREATE DATABASE IF NOT EXISTS courses_portal;
CREATE TABLE IF NOT EXISTS courses_portal.users (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` VARCHAR(50) UNIQUE NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `first_name` VARCHAR(255) NOT NULL,
    `last_name` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS courses_portal.courses (
    `course_id` INT PRIMARY KEY AUTO_INCREMENT,
    `course_name` VARCHAR(255) UNIQUE NOT NULL,
    `semester` ENUM('spring', 'summer', 'fall') NOT NULL,
    `max_student_limit` INT NOT NULL
);

INSERT INTO courses_portal.courses (course_name, semester, max_student_limit)
VALUES
    ('Biology 101', 'spring', 12),
    ('Chemistry 201', 'summer', 12),
    ('History 301', 'fall', 12),
    ('Mathematics 102', 'spring', 12),
    ('Physics 202', 'summer', 12),
    ('Literature 302', 'fall', 12),
    ('Computer Science 103', 'spring', 12),
    ('Art 203', 'summer', 12),
    ('Economics 303', 'fall', 12),
    ('Psychology 104', 'spring', 12),
    ('Sociology 204', 'summer', 12),
    ('Geology 304', 'fall', 12),
    ('Political Science 105', 'spring', 12),
    ('Music 205', 'summer', 12),
    ('Philosophy 305', 'fall', 12),
    ('Statistics 106', 'spring', 12),
    ('Environmental Science 206', 'summer', 12),
    ('Anthropology 306', 'fall', 12),
    ('Business 107', 'spring', 12),
    ('Foreign Language 207', 'summer', 12),
    ('Marketing 307', 'fall', 12),
    ('Health Sciences 108', 'spring', 12),
    ('Engineering 208', 'summer', 12),
    ('Nutrition 308', 'fall', 12);

CREATE TABLE IF NOT EXISTS courses_portal.courses_registration (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` VARCHAR(50) NOT NULL,
    `course_id` INT NOT NULL,
    `registered_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES courses_portal.users(user_id),
    FOREIGN KEY (course_id) REFERENCES courses_portal.courses(course_id)
);

CREATE TABLE IF NOT EXISTS courses_portal.courses_waitlist (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` VARCHAR(50) NOT NULL,
    `course_id` INT NOT NULL,
    `waitlisted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES courses_portal.users(user_id),
    FOREIGN KEY (course_id) REFERENCES courses_portal.courses(course_id)
);

INSERT INTO courses_portal.users (user_id, email, password, first_name, last_name, phone)
VALUES
    ('student1', 'student1@example.com', '$2a$10$6419460a1b4b4d2b1ba33uSm5RJLtJMbIi5nws2OnFejIbdVBsruC', 'John', 'Doe', '123456789'),
    ('student2', 'student2@example.com', '$2a$10$6419460a1b4b4d2b1ba33uSm5RJLtJMbIi5nws2OnFejIbdVBsruC', 'Jane', 'Smith', '987654321'),
    ('student3', 'student3@example.com', '$2a$10$6419460a1b4b4d2b1ba33uSm5RJLtJMbIi5nws2OnFejIbdVBsruC', 'Alice', 'Johnson', '555123789'),
    ('student4', 'student4@example.com', '$2a$10$6419460a1b4b4d2b1ba33uSm5RJLtJMbIi5nws2OnFejIbdVBsruC', 'Bob', 'Anderson', '111222333'),
    ('student5', 'student5@example.com', '$2a$10$6419460a1b4b4d2b1ba33uSm5RJLtJMbIi5nws2OnFejIbdVBsruC', 'Emily', 'Brown', '444555666'),
    ('student6', 'student6@example.com', '$2a$10$6419460a1b4b4d2b1ba33uSm5RJLtJMbIi5nws2OnFejIbdVBsruC', 'Michael', 'Wilson', '777888999'),
    ('student7', 'student7@example.com', '$2a$10$6419460a1b4b4d2b1ba33uSm5RJLtJMbIi5nws2OnFejIbdVBsruC', 'Sophia', 'Martinez', '000111222'),
    ('student8', 'student8@example.com', '$2a$10$6419460a1b4b4d2b1ba33uSm5RJLtJMbIi5nws2OnFejIbdVBsruC', 'Daniel', 'Garcia', '999888777'),
    ('student9', 'student9@example.com', '$2a$10$6419460a1b4b4d2b1ba33uSm5RJLtJMbIi5nws2OnFejIbdVBsruC', 'Olivia', 'Lopez', '666555444'),
    ('student10', 'student10@example.com', '$2a$10$6419460a1b4b4d2b1ba33uSm5RJLtJMbIi5nws2OnFejIbdVBsruC', 'William', 'Hernandez', '333222111'),
    ('student11', 'student11@example.com', '$2a$10$6419460a1b4b4d2b1ba33uSm5RJLtJMbIi5nws2OnFejIbdVBsruC', 'Amelia', 'Gonzalez', '888999000'),
    ('student12', 'student12@example.com', '$2a$10$6419460a1b4b4d2b1ba33uSm5RJLtJMbIi5nws2OnFejIbdVBsruC', 'Liam', 'Rodriguez', '777666555'),
    ('student13', 'student13@example.com', '$2a$10$6419460a1b4b4d2b1ba33uSm5RJLtJMbIi5nws2OnFejIbdVBsruC', 'Ella', 'Perez', '222333444'),
    ('student14', 'student14@example.com', '$2a$10$6419460a1b4b4d2b1ba33uSm5RJLtJMbIi5nws2OnFejIbdVBsruC', 'James', 'Sanchez', '555666777'),
    ('student15', 'student15@example.com', '$2a$10$6419460a1b4b4d2b1ba33uSm5RJLtJMbIi5nws2OnFejIbdVBsruC', 'Avery', 'Ramirez', '888777666'),
    ('student16', 'student16@example.com', '$2a$10$6419460a1b4b4d2b1ba33uSm5RJLtJMbIi5nws2OnFejIbdVBsruC', 'Grace', 'Torres', '111222333'),
    ('student17', 'student17@example.com', '$2a$10$6419460a1b4b4d2b1ba33uSm5RJLtJMbIi5nws2OnFejIbdVBsruC', 'Benjamin', 'Flores', '444555666'),
    ('student18', 'student18@example.com', '$2a$10$6419460a1b4b4d2b1ba33uSm5RJLtJMbIi5nws2OnFejIbdVBsruC', 'Mia', 'Gomez', '777888999'),
    ('student19', 'student19@example.com', 'zv', 'Lucas', 'Reyes', '000111222'),
    ('student20', 'student20@example.com', '$2a$10$6419460a1b4b4d2b1ba33uSm5RJLtJMbIi5nws2OnFejIbdVBsruC', 'Harper', 'Morgan', '999888777');
