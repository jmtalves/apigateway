create database if not exists apigateway;
CREATE TABLE routes (
    route VARCHAR(255),
    ip VARCHAR(255),
    status TINYINT,
    PRIMARY KEY (route, ip)
);