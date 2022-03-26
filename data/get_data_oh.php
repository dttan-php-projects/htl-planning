<?php
	$OH = $_GET['OH']; // 
	require_once("../Database.php");
	$SQL_SO = "SELECT save_so.SO_LINE,save_so.QTY,save_so.ITEM,save_so.CUSTOMER_ITEM,save_so.FOD,save_so.AGI FROM save_item join save_so on save_item.JOB_NO=save_so.JOB_NO WHERE save_item.JOB_NO='$OH'";
	$RESULT_SO = MiQuery($SQL_SO,connection());
	$SQL_ITEM = "SELECT * FROM save_oracle_item WHERE JOB_NO='$OH'";
	$RESULT_ITEM_ORACLE = MiQuery($SQL_ITEM,connection());
	$SQL_PROCESS = "SELECT * FROM save_printing WHERE JOB_NO='$OH'";
	$RESULT_PROCESS = MiQuery($SQL_PROCESS,connection());
	$SQL_MAIN_ITEM = "SELECT * FROM save_item WHERE JOB_NO='$OH'";
	$RESULT_MAIN_ITEM = MiQuery($SQL_MAIN_ITEM,connection());
	$RESULT_MAIN_ITEM[0]['PD'] = date('d-M-Y',strtotime($RESULT_MAIN_ITEM[0]['PD']));
	$DATA = [
		'SO' => $RESULT_SO,
		'ITEM_ORACLE' => $RESULT_ITEM_ORACLE,
		'PROCESS' => $RESULT_PROCESS,
		'MAIN_ITEM' => $RESULT_MAIN_ITEM[0],
	];
	$response = [
		'status' => true,
		'data' =>  $DATA,
	];
	echo json_encode($response);
	
