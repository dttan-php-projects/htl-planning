<?php
function formatDate($value,$format='d-M-Y',$remove=1){
    $display = '';
    if(!empty($value)){
        $dateFormat = explode(" ",$value);
        if(!empty($dateFormat[0])){
            $dateArray = explode("/",$dateFormat[0]);
            $date = $dateArray[1];
            $month = $dateArray[0];
            $year = $dateArray[2];            
            if(strlen($date)===1){
                $date="0".$date;
            }
            if(strlen($month)===1){
                $month="0".$month;
            }
            $day = $date."-".$month."-".$year;
            $dayTime = strtotime($date."-".$month."-".$year);
            if($format==='dd-mm-YYYY'){
                if($remove){
                    // -2 if monday else -1                
                    if(date('w',$dayTime)==1){
                        $display = date('d-m-Y', strtotime("-2 day", $dayTime));
                    }else{
                        $display = date('d-m-Y', strtotime("-1 day", $dayTime));
                    }
                }else{
                    $display = date('d-m-Y', $dayTime);
                }
                
            }elseif($format==='dd.mm.YYYY'){
                // -2 if monday else -1     
                if($remove){
                    if(date('w',$dayTime)==1){
                        $display = date('d.m.Y', strtotime("-2 day", $dayTime));
                    }else{
                        $display = date('d.m.Y', strtotime("-1 day", $dayTime));
                    }
                }else{
                    $display = date('d.m.Y', $dayTime);
                }          
                
            }else{
                // 3-Nov-18	
                if($remove){
                    if(date('w',$dayTime)==1){
                        $display = date('d-M-Y', strtotime("-2 day", $dayTime));
                    }else{
                        $display = date('d-M-Y', strtotime("-1 day", $dayTime));
                    }
                }else{
                    $display = date('d-M-Y', $dayTime);
                }                
            }
            return $display;
        }
    }
    return "";    
}

function formatDate2($value,$format='d-M-Y',$remove=1){
    $display = '';
    if(!empty($value)){
        $dateFormat = explode(" ",$value);
        if(!empty($dateFormat[0])){
            $dateArray = explode("/",$dateFormat[0]);
            $date = $dateArray[1];
            $month = $dateArray[0];
            $year = $dateArray[2];            
            if(strlen($date)===1){
                $date="0".$date;
            }
            if(strlen($month)===1){
                $month="0".$month;
            }
            $day = $date."-".$month."-".$year;
            $dayTime = strtotime($date."-".$month."-".$year);
            if($format==='dd-mm-YYYY'){
                if($remove){
                    // -3 if tuesday else -2              
                    if(date('w',$dayTime)==2 || date('w',$dayTime)==1 ){
                        $display = date('d-m-Y', strtotime("-3 day", $dayTime));
                    } else {
                        $display = date('d-m-Y', strtotime("-2 day", $dayTime));
                    }
                }else{
                    $display = date('d-m-Y', $dayTime);
                }
                
            }elseif($format==='dd.mm.YYYY'){
                // -3 if tuesday else -2
                if($remove){
                    if(date('w',$dayTime)==2 || date('w',$dayTime)==1 ){
                        $display = date('d.m.Y', strtotime("-3 day", $dayTime));
                    }else{
                        $display = date('d.m.Y', strtotime("-2 day", $dayTime));
                    }
                }else{
                    $display = date('d.m.Y', $dayTime);
                }          
                
            }else{
                // -3 if tuesday else -2
                if($remove){
                    if(date('w',$dayTime)==2 || date('w',$dayTime)==1 ){
                        $display = date('d-M-Y', strtotime("-3 day", $dayTime));
                    }else{
                        $display = date('d-M-Y', strtotime("-2 day", $dayTime));
                    }
                }else{
                    $display = date('d-M-Y', $dayTime);
                }                
            }
            return $display;
        }
    }
    return "";    
}

function getFileExcel($fileName){	
	//Nh??ng file PHPExcel
	require_once "../../Module/PHPExcel/IOFactory.php";
	//Ti???n h??nh x??c th???c file
	$objFile = PHPExcel_IOFactory::identify($fileName);
	$objData = PHPExcel_IOFactory::createReader($objFile);
	//Ch??? ?????c d??? li???u
	$objData->setReadDataOnly(true);

	// Load d??? li???u sang d???ng ?????i t?????ng
	$objPHPExcel = $objData->load($fileName);
	//Ch???n trang c???n truy xu???t
	$sheet = $objPHPExcel->setActiveSheetIndex(0);
	//L???y ra s??? d??ng cu???i c??ng
	$Totalrow = $sheet->getHighestRow();
	//L???y ra t??n c???t cu???i c??ng
	$LastColumn = $sheet->getHighestColumn();
	//Chuy???n ?????i t??n c???t ???? v??? v??? tr?? th???, VD: C l?? 3,D l?? 4
	$TotalCol = PHPExcel_Cell::columnIndexFromString($LastColumn);
	//T???o m???ng ch???a d??? li???u
	$data = [];
	//Ti???n h??nh l???p qua t???ng ?? d??? li???u
	//----L???p d??ng, V?? d??ng ?????u l?? ti??u ????? c???t n??n ch??ng ta s??? l???p gi?? tr??? t??? d??ng 2 , n????u co?? Ti??u ?????? , 1 kh??ng co?? ti??u ??????
	for ($i = 2; $i <= $Totalrow; $i++) {
    //----L???p c???t
		for ($j = 0; $j < $TotalCol; $j++) {
			// Ti???n h??nh l???y gi?? tr??? c???a t???ng ?? ????? v??o m???ng
			$dataValue = $sheet->getCellByColumnAndRow($j, $i)->getValue();
			$dataValue = trim($dataValue);
			$data[$i - 1][$j] = $dataValue;
		}	
	}
	//Hi???n th??? m???ng d??? li???u
	return $data;
}
function round_up($value,$pre){
	return ceil($value*pow(10,$pre)) / pow(10,$pre);
}
?>