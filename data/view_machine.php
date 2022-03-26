<?php   
function formatDate($value){
	return date('d-M-y',strtotime($value));
}
require_once("../Database.php");//connect to database
$script = basename($_SERVER['PHP_SELF']);
$urlRoot = str_replace($script,'',$_SERVER['PHP_SELF']);
$urlRoot = str_replace('data/','',$urlRoot);
header("Content-type:text/xml");//set content type and xml tag
echo "<?xml version=\"1.0\"?>";
$fields = 'oh.ID,OH,oh.SO_LINE,UPDATED_BY';
    // to do process so kho if(type_worst_vertical = 100-SB1) 10,5
    $sql = "SELECT $fields FROM oh_so AS oh LEFT JOIN save_item AS s_i ON s_i.JOB_NO=oh.OH ORDER BY oh.ID";
    // echo $sql;      die;  
    $rowsResult = MiQuery($sql, connection());

if(count($rowsResult)>0){ 
	echo("<rows>");
	if(!empty($rowsResult)){ 
		$cellStart = "<cell><![CDATA[";
        $cellEnd = "]]></cell>";
		foreach ($rowsResult as $row){
			$ID = $row['ID'];
			$OH = $row['OH'];
			$SO_LINE = $row['SO_LINE'];
			$UPDATED_BY = $row['UPDATED_BY'];
			$link  = 'CREATE JOB SHEET^javascript:createJob("'.$OH.'");^_self';
			echo("<row id='".$ID."'>");
				echo( $cellStart);  // LENGTH
					echo($OH);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($SO_LINE);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($UPDATED_BY);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($link);  //value for product name                 
				echo( $cellEnd);
			echo("</row>");
		}
	}
	echo("</rows>");
}else{
	echo("<rows></rows>");
}
?>