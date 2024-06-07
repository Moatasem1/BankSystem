create table user(
	id INT AUTO_INCREMENT PRIMARY KEY,
	first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(10) NOT NULL
);

RENAME TABLE user TO users;


CREATE TABLE clients (
    account_number INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    pin_code VARCHAR(225) NOT NULL,
    account_balance DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

ALTER TABLE clients AUTO_INCREMENT=1000;

ALTER TABLE clients
ADD CONSTRAINT unique_user_id UNIQUE (user_id);


select * from users;
select * from clients;

select * from users 
 join clients on user_id