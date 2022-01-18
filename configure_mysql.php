<?php
	//connect to the database
	$db = mysql_connect("localhost", "rahilpat_user", "user") or die("could not connect.");
	if(!$db)die("database doesn't exist");
	if(!mysql_select_db("rahilpat_important_feed",$db)) die("no database selected.");

	//create database structure
	mysql_query("
	CREATE TABLE IF NOT EXISTS posts
	(
		id		varchar(255) NOT NULL PRIMARY KEY,
		user_id	varchar(255) NOT NULL
	);") or die(mysql_error()); //? unsigned didnt work
?>