drop schema if exists f1;
CREATE SCHEMA f1 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ;
use f1;


create table pilot
( id int auto_increment  primary key,
  pilot_name varchar(30) not null unique,
  nationality varchar(20) not null,
  birth_date date not null,
  carnumber int not null,
  points int,
  wins int,
  podiums int,
  titles int
);

create table team
( id int auto_increment primary key,
  brand_name varchar(30) not null unique,
  points int,
  money_spent float,
  nationality varchar(20) not null,
  titles int
);

create table race
( id int auto_increment primary key,
  length float,
  country varchar(30) not null,
  turns int,
  best_laptime time,
  best_laptime_driver varchar(30),
  race_date date
);

create table competed
(  
	id int not null auto_increment unique,
    pilot_id int not null,
  race_id int not null,
  start_position int not null,
  finish_position int not null,
  foreign key(pilot_id) references pilot(id),
  foreign key(race_id) references race(id),
  primary key (race_id, pilot_id)
);

create table team_member
( id int auto_increment primary key,
pilot_id int not null,
team_id int not null,
contract_started date not null,
contract_expired date,
foreign key(pilot_id) references pilot(id),
foreign key(team_id) references team(id)
);

insert into pilot(pilot_name, nationality, birth_date, carnumber, points, wins, podiums, titles) values('Lando_Norris','Anglia','1999-11-13',4,340,0,13,0);
insert into pilot(pilot_name, nationality, birth_date, carnumber, points, wins, podiums, titles) values('Lewis_Hamilton','Anglia','1985-07-07',7,4193,103,182,7);
insert into pilot(pilot_name, nationality, birth_date, carnumber, points, wins, podiums, titles) values('Daniel_Riccardo','Ausztrália','1989-07-01',3,420,8,20,0);
insert into pilot(pilot_name, nationality, birth_date, carnumber, points, wins, podiums, titles) values('Carlos_Sainz_Jr','Spanyol','1994-09-01',55,370,1,9,0);
insert into pilot(pilot_name, nationality, birth_date, carnumber, points, wins, podiums, titles) values('Charles_Leclrec','Monakó','1997-10-16',16,360,9,24,0);
insert into pilot(pilot_name, nationality, birth_date, carnumber, points, wins, podiums, titles) values('Sebastian_Vettel','Német','1987-07-03',5,3065,54,122,4);
insert into pilot(pilot_name, nationality, birth_date, carnumber, points, wins, podiums, titles) values('Fernando_Alonso','Spanyol','1981-07-29',14,1982,32,98,2);
insert into pilot(pilot_name, nationality, birth_date, carnumber, points, wins, podiums, titles) values('Michael_Schumacher','Német','1969-01-03',7,1566,91,154,7);
insert into pilot(pilot_name, nationality, birth_date, carnumber, points, wins, podiums, titles) values('Mick_Schumacher','Német','1999-03-22',47,0,0,0,0);

insert into team(brand_name, points, money_spent, nationality, titles) values('McLaren',12870,7.4,'Anglia',8);
insert into team(brand_name, points, money_spent, nationality, titles) values('Mercedes',25000,17.9,'Anglia',8);
insert into team(brand_name, points, money_spent, nationality, titles) values('Ferrari',32050,23.2,'Olaszország',16);
insert into team(brand_name, points, money_spent, nationality, titles) values('Haas',34,3.8,'USA',0);
insert into team(brand_name, points, money_spent, nationality, titles) values('RedBull_Raceing',17680,12.4,'Ausztria',4);
insert into team(brand_name, points, money_spent, nationality, titles) values('Aston_Martin',460,16.3,'Anglia',0);
insert into team(brand_name, points, money_spent, nationality, titles) values('Alpine',534,4.9,'Anglia',0);

insert into race(length, country, turns, best_laptime, best_laptime_driver, race_date) values(4.381,'Hungary22',14,'00:01:16.627', 'Lewis_Hamilton', '2022-07-31');
insert into race(length, country, turns, best_laptime, best_laptime_driver, race_date) values(5.412,'Bahrain22',24,'00:01:34.570', 'Charles_Leclerc', '2022-03-20');
insert into race(length, country, turns, best_laptime, best_laptime_driver, race_date) values(6.17,'Saudi_Arabia22',32,'00:01:31.600', 'Charles_Leclerc', '2022-03-27');
insert into race(length, country, turns, best_laptime, best_laptime_driver, race_date) values(5.27,'Australia22',18,'00:01:24.125', 'Michael_Schumacher', '2022-04-10');
insert into race(length, country, turns, best_laptime, best_laptime_driver, race_date) values(5.793,'Italy22',21,'00:01:21.046', 'Rubens_Barichello', '2022-04-24');
insert into race(length, country, turns, best_laptime, best_laptime_driver, race_date) values(4.381,'Hungary12',14,'00:01:16.627', 'Lewis_Hamilton', '2012-07-31');
insert into race(length, country, turns, best_laptime, best_laptime_driver, race_date) values(5.412,'Bahrain12',24,'00:01:34.570', 'Charles_Leclerc', '2012-03-20');
insert into race(length, country, turns, best_laptime, best_laptime_driver, race_date) values(3.37,'Monaco12',19,'00:01:12.970', 'Lewis_Hamilton', '2012-06-05');


insert into competed(pilot_id, race_id, start_position, finish_position) values(1,2,13,15);
insert into competed(pilot_id, race_id, start_position, finish_position) values(1,3,9,7);
insert into competed(pilot_id, race_id, start_position, finish_position) values(1,4,10,2);
insert into competed(pilot_id, race_id, start_position, finish_position) values(1,5,7,12);
insert into competed(pilot_id, race_id, start_position, finish_position) values(2,2,17,15);
insert into competed(pilot_id, race_id, start_position, finish_position) values(2,3,9,5);
insert into competed(pilot_id, race_id, start_position, finish_position) values(2,4,16,11);
insert into competed(pilot_id, race_id, start_position, finish_position) values(2,5,13,3);
insert into competed(pilot_id, race_id, start_position, finish_position) values(5,2,1,1);
insert into competed(pilot_id, race_id, start_position, finish_position) values(5,3,3,2);
insert into competed(pilot_id, race_id, start_position, finish_position) values(5,4,2,1);
insert into competed(pilot_id, race_id, start_position, finish_position) values(9,4,18,19);
insert into competed(pilot_id, race_id, start_position, finish_position) values(6,2,17,12);
insert into competed(pilot_id, race_id, start_position, finish_position) values(6,3,14,14);
insert into competed(pilot_id, race_id, start_position, finish_position) values(6,4,9,4);
insert into competed(pilot_id, race_id, start_position, finish_position) values(6,5,12,10);
insert into competed(pilot_id, race_id, start_position, finish_position) values(6,6,3,1);
insert into competed(pilot_id, race_id, start_position, finish_position) values(6,7,1,1);
insert into competed(pilot_id, race_id, start_position, finish_position) values(6,8,4,2);
insert into competed(pilot_id, race_id, start_position, finish_position) values(7,2,10,10);
insert into competed(pilot_id, race_id, start_position, finish_position) values(7,3,6,3);
insert into competed(pilot_id, race_id, start_position, finish_position) values(7,4,4,6);
insert into competed(pilot_id, race_id, start_position, finish_position) values(7,5,9,8);
insert into competed(pilot_id, race_id, start_position, finish_position) values(7,6,2,2);
insert into competed(pilot_id, race_id, start_position, finish_position) values(7,7,3,2);
insert into competed(pilot_id, race_id, start_position, finish_position) values(7,8,1,1);
insert into competed(pilot_id, race_id, start_position, finish_position) values(8,6,5,3);
insert into competed(pilot_id, race_id, start_position, finish_position) values(8,7,8,4);
insert into competed(pilot_id, race_id, start_position, finish_position) values(8,8,2,3);

insert into team_member(pilot_id, team_id, contract_started, contract_expired) values(1,1,'2017-01-01', '2024-01-01');
insert into team_member(pilot_id, team_id, contract_started, contract_expired) values(2,1,'2007-01-01', '2012-01-01');
insert into team_member(pilot_id, team_id, contract_started, contract_expired) values(2,2,'2013-01-01', '2023-01-01');
insert into team_member(pilot_id, team_id, contract_started, contract_expired) values(3,5,'2014-01-01', '2018-01-01');
insert into team_member(pilot_id, team_id, contract_started, contract_expired) values(3,1,'2021-01-01', '2023-01-01');
insert into team_member(pilot_id, team_id, contract_started, contract_expired) values(4,1,'2019-01-01', '2020-01-01');
insert into team_member(pilot_id, team_id, contract_started, contract_expired) values(4,3,'2021-01-01', '2023-01-01');
insert into team_member(pilot_id, team_id, contract_started, contract_expired) values(5,3,'2018-01-01', '2025-01-01');
insert into team_member(pilot_id, team_id, contract_started, contract_expired) values(6,5,'2009-01-01', '2014-01-01');
insert into team_member(pilot_id, team_id, contract_started, contract_expired) values(6,6,'2021-01-01', '2023-01-01');
insert into team_member(pilot_id, team_id, contract_started, contract_expired) values(7,3,'2010-01-01', '2014-01-01');
insert into team_member(pilot_id, team_id, contract_started, contract_expired) values(7,7,'2021-01-01', '2023-01-01');
insert into team_member(pilot_id, team_id, contract_started, contract_expired) values(8,2,'2010-01-01', '2013-01-01');
insert into team_member(pilot_id, team_id, contract_started, contract_expired) values(9,4,'2020-01-01', '2023-01-01');


