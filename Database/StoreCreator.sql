
CREATE DATABASE yashPatelStore;

use yashPatelStore;


create TABLE products(
    prodID int(11) NOT NULL AUTO_INCREMENT,
    prodName varchar(255) NOT NULL,
    prodImage varchar(255) NOT NULL,
    prodDiscription varchar(255) NOT NULL,
    prodQuantity int(11) NOT NULL,
    prodPrice float NOT NULL,
    PRIMARY KEY (`prodID`)
);

create table customers(
	custID int not null AUTO_INCREMENT,
    custName varchar(255) not null,
    custAddress varchar(255) not null,
    custContact varchar(50) not null,
    PRIMARY KEY(custID)
);

CREATE TABLE orders(
    ordID int NOT null AUTO_INCREMENT,
    custID int not null,
    paymentMethod varchar(20) not null,
    ordDate date not null,
    PRIMARY KEY(ordID),
    FOREIGN KEY(custID) REFERENCES customers(custID)
);

CREATE TABLE orderDetails(
	ordID int not null,
    prodID int not null,
    prodPrice int not null,
    ordQuantity int not null,
    ordTotalPrice float not null,
    PRIMARY KEY(ordID,prodID),
    FOREIGN KEY(prodID) REFERENCES products(prodID),
    FOREIGN KEY(ordID) REFERENCES orders(ordID)
);