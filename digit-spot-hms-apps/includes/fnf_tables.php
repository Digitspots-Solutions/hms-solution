<?php

#create tables

$var_fnf_tbL1 = "CREATE TABLE IF NOT EXISTS fd_analysis_tbl(
id bigint(50) AUTO_INCREMENT,
category varchar(50),
name varchar(500),
amount varchar(50) default 0,
ngroup int,
transaction_date date,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";


$var_fnf_tbL2 = "CREATE TABLE IF NOT EXISTS outlet_food_analysis_tbl(
id bigint(50) AUTO_INCREMENT,
pos int,
cover varchar(50) default 0,
food varchar(50) default 0,
transaction_date date,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

?>