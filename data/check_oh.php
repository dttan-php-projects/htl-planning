<?php
	require_once("../Database.php");//connect to database
	
	$EXIST_SO = 0;
	$OH = $_GET['OH'];
	$sql_count = "SELECT COUNT(ID) FROM save_item WHERE JOB_NO='$OH'";
	$rows_count = MiQuery($sql_count, connection());
	if(!empty($rows_count)){
		$EXIST_SO  = 1;
	}
	echo $EXIST_SO;
