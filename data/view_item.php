<?php   

require_once("../Database.php");//connect to database
$script = basename($_SERVER['PHP_SELF']);
$urlRoot = str_replace($script,'',$_SERVER['PHP_SELF']);
$urlRoot = str_replace('data/','',$urlRoot);
header("Content-type:text/xml");//set content type and xml tag
echo "<?xml version=\"1.0\"?>";
$fields = '*';
    // to do process so kho if(type_worst_vertical = 100-SB1) 10,5
    $sql = "SELECT * FROM master_item";
    $rowsResult = MiQuery($sql, connection());
    
if(count($rowsResult)>0){ 
	echo("<rows>");
	if(!empty($rowsResult)){ 
		$cellStart = "<cell><![CDATA[";
        $cellEnd = "]]></cell>";
		foreach ($rowsResult as $row){
			$ID = $row['ID'];
			$ITEM = $row['ITEM'];
			$STT = $row['STT'];
			$LOP = $row['LOP'];
			$TIEN_TRINH = $row['TIEN_TRINH'];
			$PASS = $row['PASS'];
			$KHUNG = $row['KHUNG'];
			$VAT_TU = $row['VAT_TU'];
			$SETUP = $row['SETUP'];
			$TIME = $row['TIME'];
			$CHECK_AGI = $row['CHECK_AGI'];
			$FOD = $row['FOD'];
			$SCRAP = $row['SCRAP'];
			$UPDATED_BY = $row['UPDATED_BY'];
			$CREATED_TIME = $row['CREATED_TIME'];
			echo("<row id='".$ID."'>");
				echo $cellStart;  // LENGTH
					echo(0);  //value for product name                 
				echo $cellEnd;
				echo( $cellStart);  // LENGTH
					echo($ITEM);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($STT);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($LOP);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($TIEN_TRINH);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($PASS);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($KHUNG);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($VAT_TU);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($SETUP);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($TIME);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($CHECK_AGI);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($FOD);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($SCRAP);  //value for product name                 
				echo( $cellEnd);

				echo( $cellStart);  // LENGTH
					echo($UPDATED_BY);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($CREATED_TIME);  //value for product name                 
				echo( $cellEnd);

			echo("</row>");
		}
		// add 10 
		for($i=1;$i<=10;$i++){
			$ID = 'new_id_'.$i;
			$ITEM = '';
			$STT = '';
			$LOP = '';
			$TIEN_TRINH = '';
			$PASS = '';
			$KHUNG = '';
			$VAT_TU = '';
			$SETUP = '';
			$TIME = '';
			$CHECK_AGI = '';
			$FOD = '';
			$SCRAP = '';	
			echo("<row id='".$ID."'>");
				echo $cellStart;  // LENGTH
					echo(0);  //value for product name                 
				echo $cellEnd;
				echo( $cellStart);  // LENGTH
					echo($ITEM);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($STT);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($LOP);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($TIEN_TRINH);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($PASS);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($KHUNG);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($VAT_TU);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($SETUP);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($TIME);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($CHECK_AGI);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($FOD);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($SCRAP);  //value for product name                 
				echo( $cellEnd);
			echo("</row>");
		}
	}
	echo("</rows>");
}else{
	echo("<rows></rows>");
}
?>