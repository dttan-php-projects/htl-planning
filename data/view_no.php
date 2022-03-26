<?php   
	function formatDate($value){ return date('d-M-y',strtotime($value)); }
	require_once("../Database.php");//connect to database
	$table = "save_item";

	$script = basename($_SERVER['PHP_SELF']);
	$urlRoot = str_replace($script,'',$_SERVER['PHP_SELF']);
	$urlRoot = str_replace('data/','',$urlRoot);
	header("Content-type:text/xml");//set content type and xml tag
	echo "<?xml version=\"1.0\"?>";
	$FROM_DATE = $_GET['from_date_value'];
	$FROM_DATE = date('Y-m-d',strtotime($FROM_DATE));
	$TO_DATE = $_GET['to_date_value'];
	$TO_DATE = date('Y-m-d',strtotime($TO_DATE));
	$fields = '*';
	// to do process so kho if(type_worst_vertical = 100-SB1) 10,5
	if($FROM_DATE!='1970-01-01'&&$TO_DATE!='1970-01-01'){
		$sql = "SELECT * FROM $table WHERE (CREATED_DATE>='$FROM_DATE' AND CREATED_DATE<='$TO_DATE') ORDER BY ID desc";
	}else{
		$sql = "SELECT * FROM $table ORDER BY ID DESC LIMIT 0,2000";
	}

	//echo $sql;      die;  
	$rowsResult = MiQuery($sql, connection());
		
	if(count($rowsResult)>0){ 
		$header = '<head>
						<column width="75" type="ed" align="left" sort="str">DATE</column>
						<column width="135" type="ed" align="left" sort="str">JOB_NO</column>
						<column width="80" type="ed" align="left" sort="str">NUMBER SO</column>
						<column width="110" type="ro" align="left" sort="str">CREATED BY</column>
						<column width="80" type="ed" align="left" sort="str">ITEM</column>
						<column width="110" type="ed" align="left" sort="str">RBO</column>
						<column width="55" type="link" align="left" sort="str"></column>
						<column width="45" type="link" align="left" sort="str"></column>
						<column width="*" type="link" align="left" sort="str"></column>
					</head>';
		echo("<rows>");
		// check role
		$deleteNO = 0;
		$isAdmin = 0;
		$arrayAdmin = [];
		$rowsResultRole = MiQuery("SELECT * FROM user", connection());
		if(!empty($rowsResultRole)){
			foreach ($rowsResultRole as $row){
				$arrayRole[]=$row['EMAIL'];
				if(!empty($row['IS_ADMIN'])){
					$arrayAdmin[] = $row['EMAIL'];
				}
			}
		}else{
			$arrayRole = ['long.dang','thuthuy.do','khoa.huynh','van.mai','phuongdung.pham','quyen.tk.nguyen','nam.nguyen','ly.tran','tu.ngo'];
		}
		$user = '';
		if(!empty($_COOKIE["VNRISIntranet"])){
			$user = $_COOKIE["VNRISIntranet"];
			if(in_array($user,$arrayRole)){
				$deleteNO = 1;
			}else{
				$deleteNO = 0;
			}
			if(!empty($user)&&in_array($user,$arrayAdmin)){
				$isAdmin = 1;
			}else{
				$isAdmin = 0;
			}
		}		
		echo $header;
		if(!empty($rowsResult)){  
			$ID = 0;
			$cellStart = "<cell><![CDATA[";
			$cellEnd = "]]></cell>";
			foreach ($rowsResult as $row){
				$SAVE_DATE = $row['CREATED_DATE']; 
				$SAVE_DATE = formatDate($SAVE_DATE);
				$ID++;
				$NUMBER_NO = $row['JOB_NO'];
				$NUMBER_NO_ENCODE = urlencode($row['JOB_NO']);
				$EMAIL = $row['UPDATED_BY'];
				$NUMBER_SO = $row['NUMBER_SO'];
				$ITEM_NUMBER = $row['ITEM'];
				$RBO = $row['RBO'];
				if($deleteNO && ($user == $EMAIL || $isAdmin)){
					$Deletelink  = 'DELETE^javascript:delete_no("'.$NUMBER_NO.'");^_self';
					$Editlink  = 'EDIT^javascript:edit_oh("'.$NUMBER_NO.'");^_self';
				}	
				$linkPrint = "print.php?id=$NUMBER_NO_ENCODE";		
				echo("<row id='".$ID."'>");
				echo( $cellStart);  // LENGTH
					echo($SAVE_DATE);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($NUMBER_NO);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($NUMBER_SO);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($EMAIL);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($ITEM_NUMBER);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo($RBO);  //value for product name                 
				echo( $cellEnd);
				echo( $cellStart);  // LENGTH
					echo("Print NO^$linkPrint");  //value for product name                 
				echo( $cellEnd);
				if($deleteNO && ($user == $EMAIL || $isAdmin)){
					echo( $cellStart);  // LENGTH
					echo $Editlink;  //value for product name                 
					echo( $cellEnd);
				}
				if($deleteNO && ($user == $EMAIL || $isAdmin)){
					echo( $cellStart);  // LENGTH
					echo $Deletelink;  //value for product name                 
					echo( $cellEnd);
				}				
				echo("</row>");
			}
		}
		echo("</rows>");
	}else{
		echo("<rows></rows>");
	}