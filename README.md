## Done

- User registration and login
- Session timeout management
- Write student details to the DB
- Display exam dates and subjects dynamically from the DB

## To DO

- check existing tables relational logic
- update details table model/schema from the db
- create required new tables as per DB Structure section
- and update server/backend logic accordingly and update the server and othere files of the codebase

## DB structure

- table - columns 
- users - username, password
- subjects - subject_name
- class - class, section
- user_subjects - user_id (primary key of users table), subject_id (multiple primary keys of subjects table)
- user_class - user_id (primary key of users table), class_id (primary key of class table)
- details - user_id (primary key of the user table), name, roll_no

## Pages

- index.php - looks good (no changes)
- login.php and signup.php - looks good (no changes)
- server.php - looks goos (no changes)
- db.php - looks good (no changes)
- logout.php - looks good (no changes)
- rename exams.php to subjects.php and read subjects table data and dynamically display  in a table
- details.php - fetch the data from the details table and present the data in a form and provide options to update if any data found other wise provide empty input fields to enter details and if clicked update button should write to the database's details table, even if the values are null
- accorrdingly modify server2.php to perform the details.php tasks 
- home page/ index.php - should display the details of the user in a table along with the subjects and class and section for the user and should be read only.

-- Create fresh tables with proper structure
CREATE TABLE subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(255) NOT NULL,
    teacher_name VARCHAR(255) NOT NULL,
    exam_date DATE NOT NULL
) ENGINE=InnoDB;

CREATE TABLE class (
    class_id INT AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(50) NOT NULL,
    section VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE user_class (
    user_id INT NOT NULL,
    class_id INT NOT NULL,
    PRIMARY KEY (user_id, class_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (class_id) REFERENCES class(class_id)
) ENGINE=InnoDB;

CREATE TABLE details (
    user_id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    roll_no VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE user_subjects (
    user_id INT NOT NULL,
    subject_id INT NOT NULL,
    PRIMARY KEY (user_id, subject_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id)
) ENGINE=InnoDB;