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
	//Nhúng file PHPExcel
	require_once "../../Module/PHPExcel/IOFactory.php";
	//Tiến hành xác thực file
	$objFile = PHPExcel_IOFactory::identify($fileName);
	$objData = PHPExcel_IOFactory::createReader($objFile);
	//Chỉ đọc dữ liệu
	$objData->setReadDataOnly(true);

	// Load dữ liệu sang dạng đối tượng
	$objPHPExcel = $objData->load($fileName);
	//Chọn trang cần truy xuất
	$sheet = $objPHPExcel->setActiveSheetIndex(0);
	//Lấy ra số dòng cuối cùng
	$Totalrow = $sheet->getHighestRow();
	//Lấy ra tên cột cuối cùng
	$LastColumn = $sheet->getHighestColumn();
	//Chuyển đổi tên cột đó về vị trí thứ, VD: C là 3,D là 4
	$TotalCol = PHPExcel_Cell::columnIndexFromString($LastColumn);
	//Tạo mảng chứa dữ liệu
	$data = [];
	//Tiến hành lặp qua từng ô dữ liệu
	//----Lặp dòng, Vì dòng đầu là tiêu đề cột nên chúng ta sẽ lặp giá trị từ dòng 2 , nếu có Tiêu Đề , 1 không có tiêu đề
	for ($i = 2; $i <= $Totalrow; $i++) {
    //----Lặp cột
		for ($j = 0; $j < $TotalCol; $j++) {
			// Tiến hành lấy giá trị của từng ô đổ vào mảng
			$dataValue = $sheet->getCellByColumnAndRow($j, $i)->getValue();
			$dataValue = trim($dataValue);
			$data[$i - 1][$j] = $dataValue;
		}	
	}
	//Hiển thị mảng dữ liệu
	return $data;
}
function round_up($value,$pre){
	return ceil($value*pow(10,$pre)) / pow(10,$pre);
}
?>