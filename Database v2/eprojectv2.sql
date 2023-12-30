CREATE DATABASE eprojectv2;
USE eprojectv2;

-- Create Tables
CREATE TABLE Category (
  category_id INT PRIMARY KEY AUTO_INCREMENT,
  category_name VARCHAR(30)
);

CREATE TABLE Admin (
  admin_id INT PRIMARY KEY AUTO_INCREMENT,
  admin_username VARCHAR(30),
  admin_password VARCHAR(255),
  admin_nickname NVARCHAR(100)
);

CREATE TABLE User (
  user_id INT PRIMARY KEY AUTO_INCREMENT,
  user_username VARCHAR(30),
  user_password VARCHAR(255),
  user_email VARCHAR(255),
  user_nickname NVARCHAR(100)
);

CREATE TABLE Post (
  post_id INT PRIMARY KEY AUTO_INCREMENT,
  post_title NVARCHAR(255),
  post_content TEXT,
  post_image VARCHAR(255),
  upload_date DATETIME DEFAULT NOW(),
  category_id INT,
  user_id INT,
  admin_id INT,
  rate INT DEFAULT 0,
  status INT DEFAULT 0,
  FOREIGN KEY (category_id) REFERENCES Category(category_id),
  FOREIGN KEY (user_id) REFERENCES User(user_id),
  FOREIGN KEY (admin_id) REFERENCES Admin(admin_id)
);

CREATE TABLE Comment (
  comment_id INT PRIMARY KEY AUTO_INCREMENT,
  comment_content TEXT,
  user_id INT,
  post_id INT,
  admin_id INT,
  comment_date DATETIME DEFAULT NOW(),
  FOREIGN KEY (user_id) REFERENCES User(user_id),
  FOREIGN KEY (admin_id) REFERENCES Admin(admin_id),
  FOREIGN KEY (post_id) REFERENCES Post(post_id)
);

CREATE TABLE Feedback (
  fb_id INT PRIMARY KEY AUTO_INCREMENT,
  fb_title NVARCHAR(100),
  fb_content TEXT,
  user_id INT,
  FOREIGN KEY (user_id) REFERENCES User(user_id)
);

CREATE TABLE Response (
  response_id INT PRIMARY KEY AUTO_INCREMENT,
  response_title NVARCHAR(100),
  response_content TEXT,
  admin_id INT,
  fb_id INT,
  FOREIGN KEY (admin_id) REFERENCES Admin(admin_id),
  FOREIGN KEY (fb_id) REFERENCES Feedback(fb_id)
);

-- Insert sample datas
Insert into Admin
values (default, 'hieptt2305', '1234', 'Trịnh Tuấn Hiệp');

INSERT INTO User (user_username, user_password, user_nickname)
VALUES ('UserName', '1234', 'Anonymous');

INSERT INTO Category (category_name)
VALUES ('Sociaty'), ('Sports'), ('Beauty'), ('Business'), ('Technology');

select * from post;
