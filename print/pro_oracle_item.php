<?php
$sql_supply = "SELECT * FROM save_oracle_item WHERE JOB_NO='$id'";
$result_supply = MiQuery($sql_supply,connection());
$arr_supply = [];
if(!empty($result_supply)){
    foreach ($result_supply as $key => $supply) {      
        $arr_supply[$key]['ORACLE_ITEM'] 		= $supply['ORACLE_ITEM'];
        $arr_supply[$key]['FOD'] 				= $supply['FOD'];
        $arr_supply[$key]['AGI'] 				= $supply['AGI'];
    }
}
?>