<?php
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	ini_set('max_execution_time',300);  // set time 5 minutes
	$getDate = date('Y-m',strtotime("-6 month"));
	$getDate = $getDate."-01";
	
	function formatDate($value){
		return date('d-M-y',strtotime($value));
	}
	//connect to database
		require_once("../Database.php");
		$table = "save_item";
	// get data
		$FROM_DATE = $_GET['from_date_value'];
		$FROM_DATE = date('Y-m-d',strtotime($FROM_DATE));
		$TO_DATE = $_GET['to_date_value'];
		$TO_DATE = date('Y-m-d',strtotime($TO_DATE));
	// check report
		if($FROM_DATE!='1970-01-01'&&$TO_DATE!='1970-01-01'){
			$where = " (CREATED_DATE>='$FROM_DATE' AND CREATED_DATE<='$TO_DATE') ";
		}else{
			$where = " CREATED_DATE>='$getDate' ";
		}

	// query
		$sql = "SELECT * FROM $table WHERE $where ORDER BY ID ASC;";
		$rowsResult = MiQuery($sql, connection());
	//output data in XML format 
	
		$filename = date("d_m_Y__H_i_s");
		header('Content-Encoding: UTF-8');
		header('Content-Type: text/csv; charset=utf-8');  
		header("Content-type: text/csv");
		header("Cache-Control: no-store, no-cache");
		header("Content-Disposition: attachment; filename=$filename.csv");  
		$output = fopen("php://output", "w");  
		$header = [
			"DATE","LENH SX","ITEM CODE","RBO","SO#","QTY","UPS","VAT TU","NGAY GIAO","SO MAU","TIEN TRINH","SO LUOT","SO KHUNG","NEED SHEET","SHEET BATCHING","SHEET SET UP","SHEET PACKING","TOTAL SHEET","MA VAT TU","KICH THUOC","SO TO BAN DAU","SO TO DONG GOI","SO TO IN","CUSTOMER ITEM","TONG LUOT IN","TONG LUOT MAU","CHIEU RONG","CHIEU DAI","RUNNING TIME","NOTE","SO MAY","BATCHING SCRAP","SET UP SCRAP","RUNNING SCRAP","TOTAL SCRAP","SO KHUNG"," SO FILM","TG CHAY HANG","TG CANH CHINH","FOD","SO TIEN TRINH","SCRAP"
		];
	
		fputcsv($output, $header);  
	// check data
	if(count($rowsResult)>0){ 
		if(!empty($rowsResult)){
			foreach ($rowsResult as $row){
				$CREATED_DATE = $row['CREATED_DATE'];
				$CREATED_DATE = formatDate($CREATED_DATE);
				$JOB_NO = $row['JOB_NO'];

				$QTY = $row['QTY'];
				$UPS_CAL = $row['UPS_CAL'];
				$MATERIAL_CODE = $row['MATERIAL_CODE'];
				$MATERIAL_SIZE = $row['MATERIAL_SIZE'];
				$PD = $row['PD'];
				$PD = formatDate($PD);
				$SO_MAU = '';
				$TIEN_TRINH = '';
				$LUOT = '1';
				$KHUNG = '';
				$ORGINAL_NEED = $row['ORGINAL_NEED'];
				$SHEET_BATCHING = $row['SHEET_BATCHING'];
				$TOTAL_SETUP = $row['TOTAL_SETUP'];
				$SCRAP_ERROR = $row['SCRAP_ERROR'];
				$CAL_SHEET_PACKING = round(($SCRAP_ERROR/100)*$SHEET_BATCHING);
				$TOTAL_SHEET = $row['TOTAL_SHEET'];
				$PACKING = $row['PACKING'];
				$PRINTING = $row['PRINTING'];
				//$CUSTOMER_ITEM = $row['CUSTOMER_ITEM'];
				$TOTAL_PASSES_1 = $row['TOTAL_PASSES_1'];
				$TOTAL_PASSES_2 = $row['TOTAL_PASSES_2'];
				$TOTAL_COLOUR = $row['TOTAL_COLOUR'];
				$CHIEU_RONG = '';
				$CHIEU_DAI = '';
				$NOTE = $row['FOD'];
				$MACHINE_TYPE = $row['MACHINE_TYPE'];
				$SCRAP_DESIGN = (float)$row['SCRAP_DESIGN'];
				$SCRAP_SETUP = (float)$row['SCRAP_SETUP'];
				$SCRAP_ERROR = (float)$row['SCRAP_ERROR'];
				$SCRAP_PRINTING = (float)$row['SCRAP_PRINTING'];
				$TOTAL_SCRAP = (float)$row['TOTAL_SCRAP'];
				$NUMBER_SCREEN = $row['NUMBER_SCREEN'];
				$NUMBER_SO = $row['NUMBER_SO'];
				$NUMBER_FILM = $row['NUMBER_FILM'];
				$TIME_RUNNING_1 = $row['TIME_RUNNING'];
				if($MACHINE_TYPE=='SAKURAI'){
					$TIME_RUNNING_2 = ($TOTAL_PASSES_2/1200)*60;
				}else{
					$TIME_RUNNING_2 = ($TOTAL_PASSES_2/600)*60;
				}
				$TIME_RUNNING_2 = round($TIME_RUNNING_2,2);
				$TOTAL_TIME = $row['TOTAL_TIME'];
				$sql_SO = "SELECT * FROM save_so WHERE JOB_NO='$JOB_NO'";
				$rowsResultSO = MiQuery($sql_SO, connection());		
				$sql_PRINTING = "SELECT * FROM save_printing WHERE JOB_NO='$JOB_NO'";
				$rowsResultPRINTING = MiQuery($sql_PRINTING, connection());
				$COUNT_MAX = $NUMBER_SO>$NUMBER_SCREEN?$NUMBER_SO:$NUMBER_SCREEN;
				$M22 = round(($SCRAP_ERROR/100)*$SHEET_BATCHING);
				// check gop item muc
				$count_muc = 0;
				if(!empty($rowsResultPRINTING[0]['PROCESS_1'])){
					$count_muc++;		
				}
				if(!empty($rowsResultPRINTING[0]['PROCESS_2'])){
					$count_muc++;		
				}
				if(!empty($rowsResultPRINTING[0]['PROCESS_3'])){
					$count_muc++;		
				}
				if(!empty($rowsResultPRINTING[0]['PROCESS_4'])){
					$count_muc++;		
				}
				if(!empty($rowsResultPRINTING[0]['PROCESS_5'])){
					$count_muc++;		
				}				
				$CHECK_GOP = 1;
				if($count_muc==2){
					for($i=1;$i<$COUNT_MAX;$i++){
						if(!empty($rowsResultPRINTING[$i])){
							if(!($rowsResultPRINTING[$i]['PROCESS_2']==$rowsResultPRINTING[$i]['PROCESS_1'])){
								$CHECK_GOP = 0;
							}
						}
					}
				}
				if($count_muc==3){
					for($i=1;$i<$COUNT_MAX;$i++){
						if(!empty($rowsResultPRINTING[$i])){
							if(!($rowsResultPRINTING[$i]['PROCESS_3']==$rowsResultPRINTING[$i]['PROCESS_2']&&$rowsResultPRINTING[$i]['PROCESS_3']==$rowsResultPRINTING[$i]['PROCESS_1'])){
								$CHECK_GOP = 0;
							}
						}
					}
				}
				if($count_muc==4){
					for($i=1;$i<$COUNT_MAX;$i++){
						if(!empty($rowsResultPRINTING[$i])){
							if(!($rowsResultPRINTING[$i]['PROCESS_4']==$rowsResultPRINTING[$i]['PROCESS_3']&&$rowsResultPRINTING[$i]['PROCESS_3']==$rowsResultPRINTING[$i]['PROCESS_2']&&$rowsResultPRINTING[$i]['PROCESS_2']==$rowsResultPRINTING[$i]['PROCESS_1'])){
								$CHECK_GOP = 0;
							}
						}
					}
				}
				for($i=0;$i<=$COUNT_MAX;$i++){
					$SO_LINE = !empty($rowsResultSO[$i])?$rowsResultSO[$i]['SO_LINE']:'';
					$TIEN_TRINH = '';
					$TIEN_TRINH_ARR = [];
					if($i==0){
						$TIEN_TRINH_ARR[]='Shrink';
					}else{
						if(!empty($rowsResultPRINTING[$i-1])){
							if(!empty($rowsResultPRINTING[$i-1]['PROCESS_1'])){
								if($i==1){
									if($CHECK_GOP){
										if(!in_array($rowsResultPRINTING[$i-1]['PROCESS_1'],$TIEN_TRINH_ARR)){
											$TIEN_TRINH_ARR[]=$rowsResultPRINTING[$i-1]['PROCESS_1'];
										}
									}else{
										$TIEN_TRINH_ARR[]=$rowsResultPRINTING[$i-1]['PROCESS_1'];
									}								
								}else{
									if(!in_array($rowsResultPRINTING[$i-1]['PROCESS_1'],$TIEN_TRINH_ARR)){
										$TIEN_TRINH_ARR[]=$rowsResultPRINTING[$i-1]['PROCESS_1'];
									}
								}							
							}
							if(!empty($rowsResultPRINTING[$i-1]['PROCESS_2'])){
								if($i==1){
									if($CHECK_GOP){
										if(!in_array($rowsResultPRINTING[$i-1]['PROCESS_2'],$TIEN_TRINH_ARR)){
											$TIEN_TRINH_ARR[]=$rowsResultPRINTING[$i-1]['PROCESS_2'];
										}
									}else{
										$TIEN_TRINH_ARR[]=$rowsResultPRINTING[$i-1]['PROCESS_2'];
									}
								}else{
									if(!in_array($rowsResultPRINTING[$i-1]['PROCESS_2'],$TIEN_TRINH_ARR)){
										$TIEN_TRINH_ARR[]=$rowsResultPRINTING[$i-1]['PROCESS_2'];
									}
								}							
							}
							if(!empty($rowsResultPRINTING[$i-1]['PROCESS_3'])){
								if($i==1){
									if($CHECK_GOP){
										if(!in_array($rowsResultPRINTING[$i-1]['PROCESS_3'],$TIEN_TRINH_ARR)){
											$TIEN_TRINH_ARR[]=$rowsResultPRINTING[$i-1]['PROCESS_3'];
										}
									}else{
										$TIEN_TRINH_ARR[]=$rowsResultPRINTING[$i-1]['PROCESS_3'];
									}
								}else{
									if(!in_array($rowsResultPRINTING[$i-1]['PROCESS_3'],$TIEN_TRINH_ARR)){
										$TIEN_TRINH_ARR[]=$rowsResultPRINTING[$i-1]['PROCESS_3'];
									}
								}							
							}
							if(!empty($rowsResultPRINTING[$i-1]['PROCESS_4'])){
								if($i==1){
									if($CHECK_GOP){
										if(!in_array($rowsResultPRINTING[$i-1]['PROCESS_4'],$TIEN_TRINH_ARR)){
											$TIEN_TRINH_ARR[]=$rowsResultPRINTING[$i-1]['PROCESS_4'];
										}
									}else{
										$TIEN_TRINH_ARR[]=$rowsResultPRINTING[$i-1]['PROCESS_4'];
									}
								}else{
									if(!in_array($rowsResultPRINTING[$i-1]['PROCESS_4'],$TIEN_TRINH_ARR)){
										$TIEN_TRINH_ARR[]=$rowsResultPRINTING[$i-1]['PROCESS_4'];
									}
								}							
							}
							if(!empty($rowsResultPRINTING[$i-1]['PROCESS_5'])){
								if($i==1){
									if($CHECK_GOP){
										if(!in_array($rowsResultPRINTING[$i-1]['PROCESS_5'],$TIEN_TRINH_ARR)){
											$TIEN_TRINH_ARR[]=$rowsResultPRINTING[$i-1]['PROCESS_5'];
										}
									}else{
										$TIEN_TRINH_ARR[]=$rowsResultPRINTING[$i-1]['PROCESS_5'];
									}
								}else{
									if(!in_array($rowsResultPRINTING[$i-1]['PROCESS_5'],$TIEN_TRINH_ARR)){
										$TIEN_TRINH_ARR[]=$rowsResultPRINTING[$i-1]['PROCESS_5'];
									}
								}							
							}
						}					
					}
					$TIEN_TRINH = implode("+",$TIEN_TRINH_ARR);
					$TIEN_TRINH	= trim($TIEN_TRINH,"+");
					if(!empty($SO_LINE)||!empty($TIEN_TRINH)){					
						$QTY_SO = !empty($rowsResultSO[$i])?$rowsResultSO[$i]['QTY']:'';
						if($i==0){
							$SO_LUOT = 1;
						}else{
							$SO_LUOT = !empty($rowsResultPRINTING[$i-1])?$rowsResultPRINTING[$i-1]['PASSES']:'';
						}
						if($i==0){
							$SO_KHUNG = '';
						}else{
							$SO_KHUNG = !empty($rowsResultPRINTING[$i-1])?$rowsResultPRINTING[$i-1]['SCREEN']:'';
						}
						$FOD = !empty($rowsResultSO[$i])?$rowsResultSO[$i]['FOD']:'';
						$ITEM = !empty($rowsResultSO[$i])?$rowsResultSO[$i]['ITEM']:'';
						$RBO = '';
						if(!empty($ITEM)){
							$RBO = $row['RBO'];
							//$RBO = !empty($rowsResultSO[$i])?$rowsResultSO[$i]['RBO']:'';
						}
						$rowsResultRBO = '';
						if(!empty($ITEM)){
							$sql_RBO = "SELECT RBO FROM save_oracle_item WHERE JOB_NO='$JOB_NO' AND ORACLE_ITEM='$ITEM';";
							$rowsResultRBO = MiQuery($sql_RBO, connection());
							if(is_array($rowsResultRBO)){
								$rowsResultRBO = $rowsResultRBO[0]['RBO'];
							}
						}
						$CUSTOMER_ITEM = !empty($rowsResultSO[$i])?$rowsResultSO[$i]['CUSTOMER_ITEM']:'';
						$RBO = str_replace(',','.',$RBO);
						$arrayOutputTMP = [$CREATED_DATE,$JOB_NO,$ITEM,$rowsResultRBO,$SO_LINE,$QTY_SO,$UPS_CAL,$MATERIAL_CODE,$PD,$i==0?1:'',$TIEN_TRINH,$SO_LUOT,$SO_KHUNG,$ORGINAL_NEED,$SHEET_BATCHING,$TOTAL_SETUP,$M22,$TOTAL_SHEET,$MATERIAL_CODE,$MATERIAL_SIZE,$ORGINAL_NEED,$PACKING,$PRINTING,$CUSTOMER_ITEM,$TOTAL_PASSES_1,$TOTAL_COLOUR,'','',$TIME_RUNNING_1,'',$MACHINE_TYPE,$SCRAP_DESIGN."%",$SCRAP_SETUP."%",$SCRAP_PRINTING."%",$TOTAL_SCRAP."%",$NUMBER_SCREEN,$NUMBER_FILM,$TIME_RUNNING_2,$TOTAL_TIME,$FOD,$NUMBER_SCREEN,$SCRAP_ERROR."%"];
						//fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
						fputcsv($output, $arrayOutputTMP);
					}				
				}							
			}
		}
	} 
	fclose($output);