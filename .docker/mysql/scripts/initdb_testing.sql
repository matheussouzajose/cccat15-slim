create
database if not exists cccat15_testing;

create table cccat15_testing.account
(
    account_id   binary(36) not null
        primary key,
    name         varchar(255) not null,
    email        varchar(255) not null unique,
    cpf          varchar(255) not null,
    car_plate    varchar(255) null,
    is_passenger tinyint(1) default 0 not null,
    is_driver    tinyint(1) default 0 not null,
    created_at   timestamp default CURRENT_TIMESTAMP null,
    updated_at   timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP
);

create table cccat15_testing.position
(
    position_id binary(36) not null
        primary key,
    ride_id     binary(36) null,
    lat         decimal(20,15) null,
    `long`      decimal(20,15) null,
    created_at  timestamp default CURRENT_TIMESTAMP null,
    updated_at  timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP
);

create table cccat15_testing.ride
(
    ride_id      binary(36) not null
        primary key,
    passenger_id binary(36) null,
    driver_id    binary(36) null,
    fare         decimal(20,15) null,
    distance     decimal(20,15) null,
    status       varchar(255) null,
    from_lat     decimal(20,15) null,
    from_long    decimal(20,15) null,
    to_lat       decimal(20,15) null,
    to_long      decimal(20,15) null,
    last_lat     decimal(20,15) null,
    last_long    decimal(20,15) null,
    created_at   timestamp default CURRENT_TIMESTAMP null,
    updated_at   timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP
);

create table cccat15_testing.ride_projection
(
    ride_id         binary(36) null,
    status          varchar(255) null,
    date            timestamp null,
    fare            decimal(20,15) null,
    distance        decimal(20,15) null,
    passenger_name  varchar(255) null,
    passenger_email varchar(255) null,
    driver_name     varchar(255) null,
    driver_email    varchar(255) null,
    created_at      timestamp default CURRENT_TIMESTAMP null,
    updated_at      timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP
);
