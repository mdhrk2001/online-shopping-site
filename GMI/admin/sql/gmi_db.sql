-- Create Database
CREATE DATABASE IF NOT EXISTS gmi_db;

-- Use the Database
USE gmi_db;

-- Create Users Table
CREATE TABLE IF NOT EXISTS admins (
    adminsId INT AUTO_INCREMENT PRIMARY KEY,         -- Unique admin ID
    adminsEmail VARCHAR(255) NOT NULL UNIQUE,        -- Admin's email (unique)
    adminsPassword VARCHAR(255) NOT NULL,            -- Encrypted password
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Timestamp of registration
);



-- Create Database
CREATE DATABASE IF NOT EXISTS gmi_db;

-- Use the Database
USE gmi_db;

-- Create Users Table
CREATE TABLE IF NOT EXISTS users (
    usersId INT AUTO_INCREMENT PRIMARY KEY,         -- Unique user ID
    usersName VARCHAR(255) NOT NULL,                -- User's name
    usersPhone VARCHAR(15) NOT NULL,                -- User's phone number
    usersEmail VARCHAR(255) NOT NULL UNIQUE,        -- User's email (unique)
    usersHandle VARCHAR(10) NOT NULL UNIQUE,         -- User's handle (unique)
    usersPassword VARCHAR(255) NOT NULL,            -- Encrypted password
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Timestamp of registration
);



-- Create the database
CREATE DATABASE IF NOT EXISTS gmi_db;

-- Use the database
USE gmi_db;

-- Create the items table
CREATE TABLE IF NOT EXISTS items (
    product_id VARCHAR(50) PRIMARY KEY,               -- Auto-incrementing unique identifier
    p_name VARCHAR(45) NOT NULL,                       -- Product name with a maximum of 45 characters
    price DECIMAL(10, 2) NOT NULL,                   -- Product price with up to 10 digits and 2 decimal places
    p_description TEXT,                                -- Optional product description
    category VARCHAR(50) NOT NULL,                  -- Product category
    photo_path VARCHAR(255),                        -- Path to the product's main photo
    featured_photos TEXT,                           -- JSON or comma-separated list of paths for featured photos
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Automatically store creation time
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Automatically store last update time
);




-- Create the database
CREATE DATABASE IF NOT EXISTS gmi_db;

-- Use the database
USE gmi_db;

-- Create the cart table
CREATE TABLE IF NOT EXISTS cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(50) NOT NULL,  -- Must match `items.product_id` type and length
    usersId INT NOT NULL,             -- Ensure this matches `users.user_id` data type
    FOREIGN KEY (product_id) REFERENCES items(product_id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    FOREIGN KEY (usersId) REFERENCES users(usersId)  -- Adjust column name to match `users` table
        ON DELETE CASCADE 
        ON UPDATE CASCADE
);

