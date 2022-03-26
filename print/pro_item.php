<?php
	$AGI = $result_item['AGI']?$result_item['AGI']:"";	
	$FOD = $result_item['FOD']?$result_item['FOD']:"";
	$CREATED_DATE = $result_item['CREATED_DATE']?$result_item['CREATED_DATE']:"";
	if(!empty($CREATED_DATE)){
		$CREATED_DATE = date('d-M-Y',strtotime($CREATED_DATE));
	}
	$MACHINE_TYPE = $result_item['MACHINE_TYPE']?$result_item['MACHINE_TYPE']:"";
	$JOB_NO = $result_item['JOB_NO']?$result_item['JOB_NO']:"";
	$BARCODE = '';
	if($JOB_NO){
		//$BARCODE = '<img style="height:25px;margin-left: -20px;" src="barcode.php?text='.$JOB_NO.'" />';
		$BARCODE = '<img src="barcode.php?text='.$JOB_NO.'" />';
	}
	$PD = $result_item['PD']?$result_item['PD']:"";

	if(!empty($PD)){
		
		$PD = date('d-M-y',strtotime($PD)); //
		//@TanDoan 20190916: tru them 1 ngay Promise date
		//$PD = date("d-M-y", strtotime("$PD - 1 day"));

	}
	$PRINTING_TYPE = $result_item['PRINTING_TYPE']?$result_item['PRINTING_TYPE']:"";
	$QTY = $result_item['QTY']?$result_item['QTY']:0;
	if($QTY){
		$QTY = number_format($QTY);
	}
	$RBO = $result_item['RBO']?$result_item['RBO']:"";

	$REMARK_1 = '';
	$REMARK_MLA = '';

	if($RBO=='LOLLY TOGS'||$RBO=='REEBOK INTERNATIONAL LTD'||$RBO=='EUROPE ADIDAS'||$RBO=='ADIDAS CHINA'||$RBO=='ADIDAS AMERICA'){
		$REMARK_1 = 'LẤY MẪU THEO QUY TRÌNH ADIDAS ( 3 SHEET ĐẦU VÀ 3 SHEET CUỐI)';
	}


	$RBO_MLA_CHECK = array(
		"ADIDAS",
		"AMAZON",
		"COLUMBIA SPORTSWEAR",
		"DECATHLON",
		"FAST RETAILING",
		"H&M",
		"INDITEX",
		"NIKE",
		"PRIMARK",
		"PUMA",
		"REEBOK INTERNATIONAL LTD",
		"RYOHIN KEIKAKU",
		"TARGET STORES",
		"UNDER ARMOUR/K P SPORTS",
		"UNIQLO",
		"VICTORIA'S SECRET",
		"WALMART"
	);

	foreach ($RBO_MLA_CHECK as $rbo_mla_item_check) {
		if ( strpos(strtoupper($RBO), strtoupper($rbo_mla_item_check))!== false) {
			$REMARK_MLA = '<span style="color:red;font-weight:bold;font-size:22px;"> MLA</span>';
			break;
		}
	}

	$ITEM = $result_item['ITEM']?$result_item['ITEM']:"";
	$CUSTOMER_ITEM = $result_item['CUSTOMER_ITEM']?$result_item['CUSTOMER_ITEM']:"";
	$NUMBER_FILM = $result_item['NUMBER_FILM']?$result_item['NUMBER_FILM']:"";
	$TOTAL_PASSES_1 = $result_item['TOTAL_PASSES_1']?$result_item['TOTAL_PASSES_1']:"";
	$TOTAL_COLOUR = $result_item['TOTAL_COLOUR']?$result_item['TOTAL_COLOUR']:"";
	$LABEL_SIZE = $result_item['LABEL_SIZE']?$result_item['LABEL_SIZE']:"";
	$LABEL_SIZE = str_replace("*","X",$LABEL_SIZE);
	$LABEL_SIZE = str_replace("+",",",$LABEL_SIZE);
	$LABEL_SIZE_ARR = [];
	if(!empty($LABEL_SIZE)){
		$LABEL_SIZE_ARR = explode(",",$LABEL_SIZE);
	}
	/*
	echo "<pre>";
	print_r($LABEL_SIZE_ARR);die;
	*/
	$UPS = $result_item['UPS']?$result_item['UPS']:"";
	$UPS = str_replace("*","X",$UPS);
	$UPS = str_replace("+",",",$UPS);
	$UPS_ARR = [];
	if(!empty($UPS)){
		$UPS_ARR = explode(",",$UPS);
	}
	$UPS_CAL = $result_item['UPS_CAL']?$result_item['UPS_CAL']:"";
	$TOTAL_TIME = $result_item['TOTAL_TIME']?$result_item['TOTAL_TIME']:"";
	$SHEET_BATCHING = $result_item['SHEET_BATCHING']?$result_item['SHEET_BATCHING']:"";
	$ORGINAL_NEED = $result_item['ORGINAL_NEED']?$result_item['ORGINAL_NEED']:"";
	$TOTAL_SETUP = $result_item['TOTAL_SETUP']?$result_item['TOTAL_SETUP']:"";
	$PACKING = $result_item['PACKING']?$result_item['PACKING']:"";
	$PRINTING = $result_item['PRINTING']?$result_item['PRINTING']:"";
	$SCRAP_DESIGN = $result_item['SCRAP_DESIGN']?$result_item['SCRAP_DESIGN']:"";
	$SCRAP_SETUP = $result_item['SCRAP_SETUP']?$result_item['SCRAP_SETUP']:"";
	$SCRAP_ERROR = $result_item['SCRAP_ERROR']?$result_item['SCRAP_ERROR']:"";
	$SCRAP_PRINTING = $result_item['SCRAP_PRINTING']?$result_item['SCRAP_PRINTING']:"";
	$TOTAL_SCRAP = $result_item['TOTAL_SCRAP']?$result_item['TOTAL_SCRAP']:"";
	$PAPER_COMPENSATE = $result_item['PAPER_COMPENSATE']?$result_item['PAPER_COMPENSATE']:"";
	$TOTAL_SHEET = $result_item['TOTAL_SHEET']?$result_item['TOTAL_SHEET']:"";
	$TOTAL_PASSES_2 = $result_item['TOTAL_PASSES_2']?$result_item['TOTAL_PASSES_2']:"";
	$TIME_RUNNING = $result_item['TIME_RUNNING']?$result_item['TIME_RUNNING']:"";
	$PLANNING_NAME = $result_item['PLANNING_NAME']?$result_item['PLANNING_NAME']:"";
	$NUMBER_SO = $result_item['NUMBER_SO']?$result_item['NUMBER_SO']:0;
	$NUMBER_ITEM = $result_item['NUMBER_ITEM']?$result_item['NUMBER_ITEM']:0;
	$MATERIAL_CODE = $result_item['MATERIAL_CODE']?$result_item['MATERIAL_CODE']:"";

	//@TanDoan 20190916
	// //kiem tra neu dang HT-C00001-500*650 (doi 500-600) thanh 550-700
	// if (strpos($MATERIAL_CODE, '-500*600') !==false ) {
	// 	str_replace('-500*600','-550*700', $MATERIAL_CODE );
	// }

	$MATERIAL_NAME = $result_item['MATERIAL_NAME']?$result_item['MATERIAL_NAME']:"";
	$MATERIAL_SIZE = $result_item['MATERIAL_SIZE']?$result_item['MATERIAL_SIZE']:"";
	$SIZE_ARR = [];
	if(!empty($MATERIAL_SIZE)){
		$SIZE_ARR = explode("*",$MATERIAL_SIZE);
	}
	$UPDATED_BY = $result_item['UPDATED_BY']?$result_item['UPDATED_BY']:"";





?>