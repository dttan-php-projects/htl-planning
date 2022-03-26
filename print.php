<?php
	$script = basename($_SERVER['PHP_SELF']);
	$urlRoot = str_replace($script,'',$_SERVER['PHP_SELF']);
	require_once("Database.php");//connect to database


	function remarkFRUIC($ORDER_TYPE_NAME)
	{
		$remark = '';
		if (!empty($ORDER_TYPE_NAME) ) {
			if (stripos($ORDER_TYPE_NAME, 'BNH') !== false ) {
				$remark = "FRU IC";
			}
		}

		return $remark;
		
	}

	function getOrderTypeName($so_line_check )
	{
		$order_type_name = '';
		$so_line_check_arr = explode('-', $so_line_check);
		$order_number_check = $so_line_check_arr[0];
		$line_number_check = $so_line_check_arr[1];
		$sql = "SELECT `ORDER_TYPE_NAME` FROM `vnso_total` WHERE `ORDER_NUMBER`='$order_number_check' AND `LINE_NUMBER`='$line_number_check' ORDER BY ID DESC LIMIT 1;";
		$result_automail_check = MiQuery($sql,connection("au_avery"));

		return $result_automail_check;
	}

	function remarkSpecialItem($internal_item )
	{
		$remark = '';

		$list[] = array( 'internal_item' => 'ATE550953', 'remark' => '<span style="font-weight:bold;">ĐƠN HÀNG CHỈ ĐƯỢC CẮT LỆCH +-1MM</span>');
		$list[] = array( 'internal_item' => 'ATE550950', 'remark' => '<span style="font-weight:bold;">ĐƠN HÀNG CHỈ ĐƯỢC CẮT LỆCH +-1MM</span>');
		$list[] = array( 'internal_item' => 'ATE461334A', 'remark' => '<span style="font-weight:bold;">ĐƠN HÀNG CHỈ ĐƯỢC CẮT LỆCH +-1MM</span>');

		foreach ($list as $value ) {
			$item_check = $value['internal_item'];
			if (strpos($internal_item, $item_check) !== false ) {
				if (strpos($internal_item, $value['internal_item']) !== false ) {
					$remark = $value['remark'];
					break;
				}
			}
		}

		return $remark;

	}

	if(empty($_GET['id'])){
		echo 'VUI LÒNG NHẬP LỆNH SẢN XUẤT';die;
	}
	$id = urldecode($_GET['id']);
	if(!empty($id)){
		$sql_item = "SELECT * FROM save_item WHERE JOB_NO='$id'";
		$result_item = MiQuery($sql_item,connection());	
		if(empty($result_item[0])){
			echo 'LỆNH SẢN XUẤT KHÔNG TỒN TẠI.';die;
		}
		$result_item = $result_item[0];
		$pathERP = dirname($_SERVER['SCRIPT_FILENAME']);
		if(!empty($result_item)){    
			require_once($pathERP."/print/pro_item.php"); //  xu ly item
			require_once($pathERP."/print/pro_process.php"); //  xu ly supply
			require_once($pathERP."/print/pro_oracle_item.php"); //  xu ly supply
			require_once($pathERP."/print/layout_main.php"); //  xu ly supply
		}else{
			echo 'LỆNH SẢN XUẤT KHÔNG TỒN TẠI.';die;
		}
	}else{
		echo 'VUI LÒNG NHẬP LỆNH SẢN XUẤT';
	}
	?>