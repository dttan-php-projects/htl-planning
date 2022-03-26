<?php
	// style lại code
	echo '<tr>';
		echo '<td colspan="" class="'.$non_border.'">';
			echo '<table class="" style="width:100%;height:100%;border-collapse:collapse;"  cellpadding="0" cellspacing="0">';
				echo '<tr class="">';
					echo '<td colspan="1" style="width:12%">&nbsp;</td>';
					echo '<td class="'.$pt8_b.'" colspan="1" style="width:7%">Số</td>';
					echo '<td class="'.$pt8_b.'" colspan="1" style="width:15%">Đơn hàng</td>';
					echo '<td class="'.$pt8_b.'" colspan="1" style="width:10%">Số Lượng</td>';
					echo '<td class="'.$pt8_b.'" colspan="1" style="width:12%">Đơn vị</td>';
					echo '<td colspan="1" class="<?php echo $non_border;?>">&nbsp;</td>';
					echo '<td colspan="1" class="'.$pt8_b.'" style="width:12%">Ghi chú</td>';
					echo '<td colspan="1" class="'.$pt8_b.'" style="width:7%">Số</td>';
					echo '<td colspan="3" class="'.$pt8_b.'" style="width:17%">Mã hệ thống</td>';
				echo '</tr>';
				echo '<tr class="">';
					// @tandoan - 20200929: Lấy UOM, nếu đơn có SET thì remark ĐƠN HÀNG SET
					
					$result_so = MiQuery("SELECT * FROM save_so WHERE JOB_NO='$id';",connection());
					$UOM_LIST = '';
					$remarkSpecialItem = '';

					if(!empty($result_so)){ 

						// @tandoan - 20210407: Lấy so_line đầu tiên, kiểm tra Automail cột ORDER TYPE NAME có BNH ==> FRU IC LH
						$result_so_check = $result_so[0]; 
						$so_line_check = trim($result_so_check['SO_LINE']);

						// lấy ORDER TYPE NAME
						$order_type_name = getOrderTypeName($so_line_check);
						
						$remarkFRUIC = remarkFRUIC($order_type_name);

						foreach ($result_so as $key => $so){ 
							$UOM_LIST .= $so['UOM']; 

							// @TanDoan - 20210528: mail: Re: Highlight the requirement of Resmed in order forms. Tu.Ngo: Nếu có nhiều Item thì chỉ cần 1 item có vẫn hiển thị
							if (empty($remarkSpecialItem) )	$remarkSpecialItem = remarkSpecialItem(trim($so['ITEM']) );
								
						} 
					}
					if (!empty($UOM_LIST) ) {
						$UOM = (stripos($UOM_LIST, 'SET') !==false ) ? 'CÓ SET': 'PCS';
					} else {
						$UOM = '';
					}
					

					echo '<td class="'.$pt10_b.'" rowspan="9">SO#<br/><br/> Đơn <br/><br/> hàng</td>';
					echo '<td colspan="1">1</td>';
					echo '<td colspan="1" class="nen_vang">ĐƠN HÀNG '.$NUMBER_SO.' SO</td>';
					echo '<td colspan="1" class="nen_vang">'.$QTY.'</td>';
					echo '<td colspan="1" class="nen_vang">'.$UOM.'</td>';
					echo '<td colspan="1" class="'.$non_border.'">&nbsp;</td>';
					echo '<td colspan="1" class="check_fod">';
						echo !empty($arr_supply[0])?$arr_supply[0]['FOD']:'&nbsp;';
					echo '</td>';
					echo '<td colspan="1">1</td>';
					echo '<td colspan="3" '; echo !empty($arr_supply[0])?'class="nen_vang"':''; echo '>';
						echo !empty($arr_supply[0])?$arr_supply[0]['ORACLE_ITEM']:'&nbsp;';
					echo '</td>';
				echo '</tr>';
				echo '<tr class="">';
					echo '<td colspan="1">2</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1" class="'.$non_border.'">&nbsp;</td>';
					echo '<td colspan="1" class="check_fod">';
						echo !empty($arr_supply[1])?$arr_supply[1]['FOD']:'&nbsp;';
					echo '</td>';
					echo '<td colspan="1">2</td>';
					echo '<td colspan="3" '; echo !empty($arr_supply[1])?'class="nen_vang"':''; echo '>';
						echo !empty($arr_supply[1])?$arr_supply[1]['ORACLE_ITEM']:'&nbsp;';
					echo '</td>';
				echo '</tr>';
				echo '<tr class="">';
					echo '<td colspan="1">3</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1" class="'.$non_border.'">&nbsp;</td>';
					echo '<td colspan="1" class="check_fod">';
						echo !empty($arr_supply[2])?$arr_supply[2]['FOD']:'&nbsp;';
					echo '</td>';
					echo '<td colspan="1">3</td>';
					echo '<td colspan="3"'; echo !empty($arr_supply[2])?'class="nen_vang"':''; echo '>';
						echo !empty($arr_supply[2])?$arr_supply[2]['ORACLE_ITEM']:'&nbsp;';
					echo '</td>';
				echo '</tr>';
				echo '<tr class="">';
					echo '<td colspan="1">4</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1" class="'.$non_border.'">&nbsp;</td>';
					echo '<td colspan="1" class="check_fod">';
						echo !empty($arr_supply[3])?$arr_supply[3]['FOD']:'&nbsp;';
					echo '</td>';
					echo '<td colspan="1">4</td>';
					echo '<td colspan="3"'; echo !empty($arr_supply[3])?'class="nen_vang"':''; echo '>';
						echo !empty($arr_supply[3])?$arr_supply[2]['ORACLE_ITEM']:'&nbsp;';
					echo '</td>';
				echo '</tr>';
				echo '<tr class="">';
					echo '<td colspan="1">5</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1" class="'.$non_border.'">&nbsp;</td>';
					echo '<td colspan="1" class="check_fod">';
						echo !empty($arr_supply[4])?$arr_supply[4]['FOD']:'&nbsp;';
					echo '</td>';
					echo '<td colspan="1">5</td>';
					echo '<td colspan="3"'; echo !empty($arr_supply[4])?'class="nen_vang"':''; echo '>';
						echo !empty($arr_supply[4])?$arr_supply[4]['ORACLE_ITEM']:'&nbsp;';
					echo '</td>';
				echo '</tr>';
				echo '<tr class="">';
					echo '<td colspan="1">6</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1" class="'.$non_border.'">&nbsp;</td>';
					echo '<td colspan="1" class="check_fod">';
						echo !empty($arr_supply[5])?$arr_supply[5]['FOD']:'&nbsp;';
					echo '</td>';
					echo '<td colspan="1">6</td>';
					echo '<td colspan="3"'; echo !empty($arr_supply[5])?'class="nen_vang"':''; echo '>';
					echo !empty($arr_supply[5])?$arr_supply[5]['ORACLE_ITEM']:'&nbsp;';
					echo '</td>';
				echo '</tr>';
				echo '<tr class="">';
					echo '<td colspan="1">7</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1" class="'.$non_border.'">&nbsp;</td>';
					echo '<td colspan="1" class="check_fod">';
						echo !empty($arr_supply[6])?$arr_supply[6]['FOD']:'&nbsp;';
					echo '</td>';
					echo '<td colspan="1">7</td>';
					echo '<td colspan="3"'; echo !empty($arr_supply[6])?'class="nen_vang"':''; echo '>';
						echo !empty($arr_supply[6])?$arr_supply[6]['ORACLE_ITEM']:'&nbsp;';
					echo '</td>';
				echo '</tr>';
				echo '<tr class="">';
					echo '<td colspan="1">8</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1" class="'.$non_border.'">&nbsp;</td>';
					echo '<td colspan="1" class="check_fod">';
						echo !empty($arr_supply[7])?$arr_supply[7]['FOD']:'&nbsp;';
					echo '</td>';
					echo '<td colspan="1">8</td>';
					echo '<td colspan="3"'; echo !empty($arr_supply[7])?'class="nen_vang"':''; echo '>';
						echo !empty($arr_supply[7])?$arr_supply[7]['ORACLE_ITEM']:'&nbsp;';
					echo '</td>';
				echo '</tr>';
				echo '<tr class="">';
					echo '<td colspan="1">9</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1">&nbsp;</td>';
					echo '<td colspan="1" class="'.$non_border.'">&nbsp;</td>';
					echo '<td colspan="1" class="check_fod">';
						echo !empty($arr_supply[8])?$arr_supply[8]['FOD']:'&nbsp;';
					echo '</td>';
					echo '<td colspan="1">9</td>';
					echo '<td colspan="3"'; echo !empty($arr_supply[8])?'class="nen_vang"':''; echo '>';
						echo !empty($arr_supply[8])?$arr_supply[8]['ORACLE_ITEM']:'&nbsp;';
					echo '</td>';
				echo '</tr>';
			echo '</table>';
		echo '</td>';
	echo '</tr>';
?>

<script>
    //reload UOM
    document.getElementById("UOM").innerHTML = '<span style="color:red;font-weight:bold;font-size:18px;"><?php echo (!empty($UOM) && stripos(strtoupper($UOM),'SET') !==false ) ? 'ĐƠN HÀNG SET' : 'ĐƠN HÀNG PCS' ; ?></span>';

</script>