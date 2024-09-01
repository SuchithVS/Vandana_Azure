CREATE TABLE IF NOT EXISTS admins (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT INTO admins (username, password) VALUES ('admin', 'password');

CREATE TABLE IF NOT EXISTS books (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    genre VARCHAR(100),
    published_year INT,
    isbn VARCHAR(20),
    image_url VARCHAR(255),
    status ENUM('available', 'borrowed') DEFAULT 'available'
);

INSERT INTO books (title, author, genre, published_year, isbn, image_url, status)
VALUES ('Sample Book', 'John Doe', 'Fiction', 2023, '1234567890', 'https://via.placeholder.com/150', 'available');