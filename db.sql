CREATE TABLE books (
    id int(11) NOT NULL AUTO_INCREMENT,
    sapid varchar(18) NOT NULL,
    hostname varchar(14) NOT NULL,
    loopback varchar(100) NOT NULL,
    mac_address varchar(50) NOT NULL,
    creation_date DATE NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='books table' AUTO_INCREMENT=1;
