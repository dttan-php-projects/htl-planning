<?php
$sql_supply = "SELECT * FROM avery_thermal.save_material WHERE ID_SAVE_ITEM='$id'";
$result_supply = MiQuery($sql_supply,connection());
$arr_supply = [];
$arr_so_line = [];
if(!empty($result_supply)){
    foreach ($result_supply as $key => $supply) {      
        $arr_supply[$key]['SO_LINE'] 							=  !empty($supply['SO_LINE'])?$supply['SO_LINE']:'';	
		$arr_so_line[]  										=  !empty($supply['SO_LINE'])?$supply['SO_LINE']:'';		
        $arr_supply[$key]['ITEM'] 								=  !empty($supply['ITEM'])?$supply['ITEM']:'';	
        $arr_supply[$key]['INTERNAL_ITEM'] 	 					=  !empty($supply['INTERNAL_ITEM'])?$supply['INTERNAL_ITEM']:'';	
		$arr_supply[$key]['ITEM_DES'] 							=  !empty($supply['ITEM_DES'])?$supply['ITEM_DES']:'';	
		$arr_supply[$key]['QTY'] 								=  !empty($supply['QTY'])?$supply['QTY']:0;	
		$arr_supply[$key]['MATERIAL_CODE'] 						=  !empty($supply['MATERIAL_CODE'])?$supply['MATERIAL_CODE']:'';	
		$arr_supply[$key]['MATERIAL_DES'] 						=  !empty($supply['MATERIAL_DES'])?$supply['MATERIAL_DES']:'';	
		$arr_supply[$key]['EA_SHT'] 							=  !empty($supply['EA_SHT'])?$supply['EA_SHT']:0;	
		$arr_supply[$key]['YD'] 								=  !empty($supply['YD'])?$supply['YD']:0;	
		$arr_supply[$key]['MT'] 								=  !empty($supply['MT'])?$supply['MT']:0;	
		$arr_supply[$key]['MATERIAL_QTY'] 						=  !empty($supply['MATERIAL_QTY'])?$supply['MATERIAL_QTY']:0;	
		$arr_supply[$key]['LENGTH'] 							=  !empty($supply['LENGTH'])?$supply['LENGTH']:0;	
		$arr_supply[$key]['WIDTH'] 								=  !empty($supply['WIDTH'])?$supply['WIDTH']:0;	
		$arr_supply[$key]['INK_CODE'] 							=  !empty($supply['INK_CODE'])?$supply['INK_CODE']:'';	
		$arr_supply[$key]['INK_DES'] 							=  !empty($supply['INK_DES'])?$supply['INK_DES']:'';	
		$arr_supply[$key]['INK_QTY'] 							=  !empty($supply['INK_QTY'])?$supply['INK_QTY']:0;	
		$arr_supply[$key]['MULTIPLE'] 							=  !empty($supply['MULTIPLE'])?$supply['MULTIPLE']:'';	
		$arr_supply[$key]['SAMPLE'] 							=  !empty($supply['SAMPLE'])?$supply['SAMPLE']:'';	
		$arr_supply[$key]['SO_UPS'] 							=  !empty($supply['SO_UPS'])?$supply['SO_UPS']:'';	
    }
}
$count_material = count($arr_supply);
$SO_LINE_TEXT = '';
if(count($arr_so_line)==1){
	$SO_LINE_TEXT = $arr_so_line[0];
}else{
	$SO_LINE_TEXT = $arr_so_line[0];
	$SO_LINE_TEXT = substr($SO_LINE_TEXT,0,8);
}
$BARCODE = '<img src="barcode.php?text='.$SO_LINE_TEXT.'" />';
?>