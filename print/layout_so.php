<?php
	//  style lại code 
	echo '<tr>';
		echo '<td colspan="" class="'.$non_border.'">';
			echo '<table class="" style="width:100%;height:100%;border-collapse:collapse;"  cellpadding="0" cellspacing="0">'; 
				echo '<tr class="">';
					echo '<td colspan="1" style="width:12%">&nbsp;</td>';
					echo '<td class="'.$pt8_b.'" colspan="1" style="width:7%">Số</td>';
					echo '<td class="'.$pt8_b.'" colspan="1" style="width:15%">Đơn hàng</td>';
					echo '<td class="'.$pt8_b.'" colspan="1" style="width:10%">Số Lượng</td>';
					echo '<td class="'.$pt8_b.'" style="width:12%">Đơn vị</td>';
					echo '<td colspan="1" class="'.$non_border.'">&nbsp;</td>';
					echo '<td colspan="1" class="'.$pt8_b.'" style="width:12%">Ghi chú</td>';
					echo '<td colspan="1" class="'.$pt8_b.'" style="width:7%">Số</td>';
					echo '<td colspan="3" class="'.$pt8_b.'" style="width:17%">Mã hệ thống</td>';
				echo '</tr>';

				// handle
				$STT_SO = 0;
				$result_so = MiQuery("SELECT * FROM save_so WHERE JOB_NO='$id';",connection());
				if(!empty($result_so)){

					// @tandoan - 20210407: Lấy so_line đầu tiên, kiểm tra Automail cột ORDER TYPE NAME có BNH ==> FRU IC LH
					$result_so_check = $result_so[0]; 
					$so_line_check = trim($result_so_check['SO_LINE']);

					// lấy ORDER TYPE NAME
					$order_type_name = getOrderTypeName($so_line_check);
					// remark FRU IC LH
					$remarkFRUIC = remarkFRUIC($order_type_name);




					foreach ($result_so as $key => $so){
						$STT_SO++;
						$SO_LINE = $so['SO_LINE'];
						$QTY_SO = $so['QTY'];
						
						$ITEM_SO = $so['ITEM'];
						$FOD_SO = $so['FOD'];
						$UOM = ($so['UOM'] == 'EA') ? 'PCS' : $so['UOM'];
						$QTY_TMP = (strtoupper($UOM) == 'SET') ? number_format($so['QTY']/2) : number_format($so['QTY']);
						$UOM_SHOW = !empty($UOM) ? ($QTY_TMP .' -'. $UOM) : '';
						echo '<tr class="">';

							if($STT_SO==1){ echo '<td class="'.$pt10_b.'" rowspan="9">SO#<br/><br/> Đơn <br/><br/> hàng</td>'; }
							echo '<td colspan="1">'.$STT_SO.'</td>';
							echo '<td colspan="1" class="nen_vang">'.$SO_LINE.'</td>';
							echo '<td colspan="1" class="nen_vang">'.number_format($QTY_SO).'</td>';
							echo '<td colspan="1" class="nen_vang" style="text-align:right;padding-right:3px;">'.$UOM_SHOW.'</td>';
							echo '<td colspan="1" class="'.$non_border.'">&nbsp;</td>';
							echo '<td colspan="1" class="check_fod">'.$FOD_SO.'</td>';
							echo '<td colspan="1">'.$STT_SO.'</td>';
							echo '<td colspan="3" class="nen_vang">'.$ITEM_SO.'</td>';
						echo '</tr>';	


						// @TanDoan - 20210528: mail: Re: Highlight the requirement of Resmed in order forms. Tu.Ngo: Nếu có nhiều Item thì chỉ cần 1 item có vẫn hiển thị
						if (empty($remarkSpecialItem) )	$remarkSpecialItem = remarkSpecialItem(trim($ITEM_SO) );
					}
				}

				for($i=$STT_SO+1;$i<=9;$i++){
					echo '<tr class="">';
						echo '<td colspan="1">'.$i.'</td>';
						echo '<td colspan="1">&nbsp;</td>';
						echo '<td colspan="1">&nbsp;</td>';
						echo '<td colspan="1">&nbsp;</td>';
						echo '<td colspan="1" class="'.$non_border.'">&nbsp;</td>';
						echo '<td colspan="1" class="check_fod">&nbsp;</td>';
						echo '<td colspan="1">'.$i.'</td>';
						echo '<td colspan="3">&nbsp;</td>';
					echo '</tr>';
			
				}
			echo '</table>';

		echo '</td>';
	echo '</tr>';
?>

<script>
    //reload UOM
    document.getElementById("UOM").innerHTML = '<span style="color:red;font-weight:bold;font-size:18px;"><?php echo (!empty($UOM) && strtoupper($UOM) == 'SET') ? 'ĐƠN HÀNG SET' : 'ĐƠN HÀNG PCS' ; ?></span>';

	
</script>