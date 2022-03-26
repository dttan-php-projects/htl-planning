<?php
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	ini_set('max_execution_time',300);  // set time 5 minutes
	require_once("../Database.php");//connect to database

	// query
		$rowsResult = MiQuery("SELECT * FROM master_item;", connection());
	//output data in XML format 
		$filename = date("d_m_Y__H_i_s");
		header('Content-Encoding: UTF-8');
		header('Content-Type: text/csv; charset=utf-8');  
		header("Content-type: text/csv");
		header("Cache-Control: no-store, no-cache");
		header("Content-Disposition: attachment; filename=$filename.csv");  
		$output = fopen("php://output", "w");  
		$header = [
			"ITEM","STT","LOP","TIEN_TRINH","PASS","KHUNG","VAT TU","SETUP","TIME","CHECK_AGI","FOD","SCRAP"
		];
	// add header data to csv
		fputcsv($output, $header);  
	// check data
	if(count($rowsResult)>0){ 
		if(!empty($rowsResult)){
			foreach ($rowsResult as $row){
				$ITEM = $row['ITEM'];
				$STT = $row['STT'];
				$LOP = $row['LOP'];
				$TIEN_TRINH = $row['TIEN_TRINH'];
				$PASS = $row['PASS'];
				$KHUNG = $row['KHUNG'];
				$VAT_TU = $row['VAT_TU'];

				// //@TanDoan 20190916
				// //kiem tra neu dang HT-C00001-500*650 (doi 500-600) thanh 550-700
				// if (strpos($VAT_TU, '-500*600') !==false ) {
				// 	$VAT_TU = str_replace('-500*600','-550*700', $VAT_TU );
				// }

				$SETUP = $row['SETUP'];
				$TIME = $row['TIME'];
				$CHECK_AGI = $row['CHECK_AGI'];
				$FOD = $row['FOD'];
				$SCRAP = $row['SCRAP'];
				$arrayOutputTMP = [$ITEM,$STT,$LOP,$TIEN_TRINH,$PASS,$KHUNG,$VAT_TU,$SETUP,$TIME,$CHECK_AGI,$FOD,$SCRAP."%"];
				fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
				fputcsv($output, $arrayOutputTMP);
			}				
		}							
	}
	fclose($output);  