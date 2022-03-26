<?php   
	function formatDate($value){ return date('d-M-y',strtotime($value)); }
	require_once("../Database.php");//connect to database

	$script = basename($_SERVER['PHP_SELF']);
	$urlRoot = str_replace($script,'',$_SERVER['PHP_SELF']);
	$urlRoot = str_replace('data/','',$urlRoot);
	header("Content-type:text/xml");//set content type and xml tag
	echo "<?xml version=\"1.0\"?>";

	$rowsResult = MiQuery("SELECT * FROM user", connection());
		
	if(count($rowsResult)>0){ 
		echo("<rows>");
		if(!empty($rowsResult)){ 
			$cellStart = "<cell><![CDATA[";
			$cellEnd = "]]></cell>";
			foreach ($rowsResult as $row){
				$ID = $row['ID'];
				$EMAIL = $row['EMAIL'];
				$IS_ADMIN = $row['IS_ADMIN'];
				$UPDATED = $row['UPDATED_BY'];
				/*
				if($deleteNO){
					$link  = 'DELETE^javascript:deleteMS('.$ID.');^_self';
				}	
				*/
				echo("<row id='".$ID."'>");
					echo $cellStart;  // LENGTH
						echo(0);  //value for product name                 
					echo $cellEnd;
					echo( $cellStart);  // LENGTH
						echo($EMAIL);  //value for product name                 
					echo( $cellEnd);
					echo( $cellStart);  // LENGTH
						echo($IS_ADMIN);  //value for product name                 
					echo( $cellEnd);
					echo( $cellStart);  // LENGTH
						echo($UPDATED);  //value for product name                 
					echo( $cellEnd);
				echo("</row>");
			}
			for($i=1;$i<=7;$i++){
				$ID = 'new_id_'.$i;
				$EMAIL = '';
				$IS_ADMIN = '';
				echo("<row id='".$ID."'>");
					echo $cellStart;  // LENGTH
						echo(0);  //value for product name                 
					echo $cellEnd;
					echo( $cellStart);  // LENGTH
						echo($EMAIL);  //value for product name                 
					echo( $cellEnd);
					echo( $cellStart);  // LENGTH
						echo($IS_ADMIN);  //value for product name                 
					echo( $cellEnd);
					echo( $cellStart);  // LENGTH
						echo('');  //value for product name                 
					echo( $cellEnd);
				echo("</row>");
			}
		}
		echo("</rows>");
	}else{
		echo("<rows></rows>");
	}
	?>