<!DOCTYPE html>
<html>
<head>
<title>PRINT H T L</title>
<link rel="stylesheet" href="<?php echo $urlRoot.'print/css/style.css';?>">
</head>
<script type="text/javascript">
    window.onload = function() { 
        //window.print(); 		  
		// setTimeout(function () { window.close(); }, 100);
	  }
 </script>
 <?php
	$a_pt25_b = 'aleft bold pt25';
	$pt25_b = 'bold pt25';
	$a_pt22_b = 'aleft bold pt22';
	$pt22_b = 'bold pt22';
	$a_pt16_b = 'aleft bold pt16';
	$pt16_b = 'bold pt16';
	$a_pt14_b = 'aleft bold pt14';
	$pt14_b = 'bold pt14';
	$a_pt12_b = 'aleft bold pt12';
	$pt12_b = 'bold pt12';
    $a_pt10_b = 'aleft bold pt10';
    $pt10_b = 'bold pt10';
    $pt8_b = 'bold pt8';
	$pt8 = 'pt8';
    $a_pt8_b = 'aleft bold pt8';
    $pt6_b = 'bold pt6';
    $a_pt6_b = 'aleft bold pt6';
    $non_border = 'none_border';
    $spacer = '<td class="none_border">&nbsp;</td>';
 ?>
<body >
	<div style="height:100%; width:100%;"> <!-- fix page break -->
        <table style="width:99%;height:99%;border-collapse:collapse;margin-left:0%;"  cellpadding="0" cellspacing="0">
            <!-- Header -->
			<tr>
				<td colspan="" class="<?php echo $non_border;?>">
					<table class="padding" style="width:100%;height:100%;border-collapse:collapse;"  cellpadding="0" cellspacing="0">
						<tr class="">
							<td style="width:100%" colspan="4" class="none_border">
								<table class="" style="width:100%;height:100%;border-collapse:collapse;"  cellpadding="0" cellspacing="0">
									<tr class="">
										<td class="<?php echo $pt14_b;?> none_border" colspan="1" style="width:15%"><?php echo $AGI;?></td>
										<td colspan="1" class="<?php echo $pt14_b;?> none_border" style="width:15%"><?php echo $FOD;?></td>
										<td class="<?php echo $pt8_b;?> none_border" colspan="1"><?php echo $REMARK_1;?></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr class="">
							<td colspan="4" class="<?php echo $pt14_b;?> <?php echo $non_border;?>">JOB SHEET/ LỆNH SẢN XUẤT HTL</td>
						</tr>
						<tr class="">
							<td colspan="2" class="<?php echo $non_border;?> aleft"><?php echo $BARCODE;?></td>
							<td class="<?php echo $a_pt8_b;?> <?php echo $non_border;?>" colspan="1">Type machine/Máy:</td><td class="<?php echo $a_pt8_b;?> <?php echo $non_border;?>" colspan="1"><?php echo $MACHINE_TYPE;?></td>
						</tr>
						<tr class="">
							<td class="<?php echo $a_pt8_b;?> <?php echo $non_border;?>" colspan="1" style="width:25%">Date/Ngày Làm Lệnh:</td><td colspan="1" class="aleft <?php echo $non_border;?>"><?php echo $CREATED_DATE;?></td>	
							<td class="<?php echo $a_pt8_b;?> <?php echo $non_border;?>" colspan="1">Promise Date/Ngày G.Hàng:</td><td colspan="1" class="aleft nen_vang <?php echo $non_border;?>"><?php echo $PD;?></td>	
						</tr>
						<tr class="">
							<td class="<?php echo $a_pt8_b;?> <?php echo $non_border;?>" colspan="1">Job No/ Số Lệnh:</td><td colspan="1" class="<?php echo $a_pt14_b;?> <?php echo $non_border;?>"><?php echo $JOB_NO;?></td>	
							<td class="<?php echo $a_pt8_b;?> <?php echo $non_border;?>" colspan="1">Type product/Thể loại in:</td><td colspan="1" class="aleft <?php echo $non_border;?>"><?php echo $PRINTING_TYPE;?></td>	
						</tr>
						<tr class="">
							<td class="<?php echo $a_pt8_b;?> <?php echo $non_border;?>" colspan="1">Quantity/Số lượng:</td><td colspan="1" class="aleft bold pt10 <?php echo $non_border;?>"> <?php echo $QTY;?></td>	
							<td class="<?php echo $a_pt8_b;?> <?php echo $non_border;?>" colspan="1">RBO:</td><td colspan="1" class="aleft nen_vang <?php echo $non_border;?>"><?php echo $RBO;?></td>	
						</tr>
						<tr class="">
							<td class="<?php echo $a_pt8_b;?> <?php echo $non_border;?>" colspan="1">Số film:</td><td colspan="1" class="aleft <?php echo $non_border;?>"><?php echo $NUMBER_FILM;?></td>	
							<td class="<?php echo $a_pt8_b;?> <?php echo $non_border;?>" colspan="1">Oracle Item/Mã hệ thống:</td><td colspan="1" class="aleft <?php echo $non_border;?>"><?php echo $ITEM;?></td>	
						</tr>
						<tr class="">
							<td class="<?php echo $a_pt8_b;?> <?php echo $non_border;?>" colspan="1">Mã K.hàng:</td><td colspan="1" class="aleft <?php echo $non_border;?>"><?php echo $CUSTOMER_ITEM;?></td>	
							<td class="<?php echo $a_pt8_b;?> <?php echo $non_border;?>" colspan="1">Total Pass/Tổng số lượt in:</td><td colspan="1" class="aleft <?php echo $non_border;?>"><?php echo $TOTAL_PASSES_1;?></td>	
						</tr>
						<tr class="">
							<td class="<?php echo $a_pt8_b;?> <?php echo $non_border;?>" colspan="1">Số Khung:</td><td colspan="1" class="aleft <?php echo $non_border;?>"><?php echo $COUNT_PROCESS;?></td>	
							<td class="<?php echo $a_pt8_b;?> <?php echo $non_border;?>" colspan="1">Total colour pass/T.s.lượt màu:</td><td colspan="1" class="aleft <?php echo $non_border;?>"><?php echo $TOTAL_COLOUR;?></td>	
						</tr>
					</table>
				</td>            
            </tr>
			<!--End header -->
			<!-- SO LINE -->
			<?php
				if($NUMBER_SO>9){
					require_once($pathERP."/print/layout_multiple_so.php"); //  xu ly supply
				}else{
					require_once($pathERP."/print/layout_so.php"); //  xu ly supply
				}
				
			?>
			<!--END SO LINE -->
			<tr>
				<td colspan="" class="<?php echo $non_border ;?>">
					<table class="" style="width:100%;height:100%;border-collapse:collapse;"  cellpadding="0" cellspacing="0">
						<tr class="">
							<td colspan="3" class="<?php echo $a_pt8_b;?> <?php echo $non_border;?>">Labl Size/K.Thước nhãn:</td>
							<td colspan="1" class="<?php echo $non_border;?>"><?php echo !empty($LABEL_SIZE_ARR[0])?$LABEL_SIZE_ARR[0]:'&nbsp;';?></td>
							<td colspan="1" class="<?php echo $non_border;?>"><?php echo !empty($LABEL_SIZE_ARR[1])?$LABEL_SIZE_ARR[1]:'&nbsp;';?></td>
							<td colspan="1" class="<?php echo $non_border;?>"><?php echo !empty($LABEL_SIZE_ARR[2])?$LABEL_SIZE_ARR[2]:'&nbsp;';?></td>
							<td colspan="1" class="<?php echo $non_border;?>"><?php echo !empty($LABEL_SIZE_ARR[3])?$LABEL_SIZE_ARR[3]:'&nbsp;';?></td>
							<td colspan="1" class="<?php echo $non_border;?>">&nbsp;</td>
							<td colspan="1" class="<?php echo $non_border;?>">&nbsp;</td>
							<td colspan="1" class="<?php echo $non_border;?>">&nbsp;</td>
							<td colspan="1" class="<?php echo $non_border;?>">&nbsp;</td>
						</tr>
						<tr class="">
							<td colspan="2" class="<?php echo $a_pt8_b;?> <?php echo $non_border;?>">Ups (pcs/tờ):</td>
							<td colspan="1" class="<?php echo $pt8_b;?> <?php echo $non_border;?>"><?php echo $UPS_CAL;?></td>
							<td colspan="1" class="<?php echo $non_border;?>"><?php echo !empty($UPS_ARR[0])?$UPS_ARR[0]:'&nbsp;';?></td>
							<td colspan="1" class="<?php echo $non_border;?>"><?php echo !empty($UPS_ARR[1])?$UPS_ARR[1]:'&nbsp;';?></td>
							<td colspan="1" class="<?php echo $non_border;?>"><?php echo !empty($UPS_ARR[2])?$UPS_ARR[2]:'&nbsp;';?></td>
							<td colspan="1" class="<?php echo $non_border;?>"><?php echo !empty($UPS_ARR[3])?$UPS_ARR[3]:'&nbsp;';?></td>
							<td colspan="1" class="<?php echo $non_border;?>"><?php echo $TOTAL_PASSES_1;?></td>
							<td colspan="1" class="<?php echo $non_border;?>"><?php echo $COUNT_PROCESS;?></td>
							<td colspan="1" class="<?php echo $non_border;?>"><?php echo $TOTAL_TIME;?></td>
							<td colspan="1" class="<?php echo $non_border;?>"><?php echo $TOTAL_SETUP;?></td>
						</tr>
						<tr class="">
							<td colspan="1" style="width:12%;" rowspan="2" class="<?php echo $pt8_b;?>">Printing follow Thứ tự in:</td>
							<td colspan="1" style="width:6%;" rowspan="2" class="<?php echo $pt8_b;?>">Số màu mực</td>
							<td colspan="5" class="<?php echo $pt8_b;?>">Process/Tiến trình</td>
							<td rowspan="2" style="width:8%;"  class="<?php echo $pt8_b;?>">Passes/Số lượt</td>
							<td rowspan="2" style="width:8%;"  class="<?php echo $pt8_b;?>">Screen/Số khung</td>
							<td rowspan="2" style="width:8%;"  class="<?php echo $pt8_b;?>">Thời gian canh chỉnh (phút)</td>
							<td rowspan="2" style="width:8%;" class="<?php echo $pt8_b;?>">Số tờ canh chỉnh (tờ)</td>
						</tr>
						<tr class="">
							<td colspan="2" style="width:13%;" class="<?php echo $pt8_b;?>"><?php echo !empty($arr_supply[0])?$arr_supply[0]['ORACLE_ITEM']:'&nbsp;';?></td>
							<td colspan="1" style="width:13%;" class="<?php echo $pt8_b;?>"><?php echo !empty($arr_supply[1])?$arr_supply[1]['ORACLE_ITEM']:'&nbsp;';?></td>
							<td colspan="1" style="width:13%;" class="<?php echo $pt8_b;?>"><?php echo !empty($arr_supply[2])?$arr_supply[2]['ORACLE_ITEM']:'&nbsp;';?></td>
							<td colspan="1" style="width:13%;" class="<?php echo $pt8_b;?>"><?php echo !empty($arr_supply[3])?$arr_supply[3]['ORACLE_ITEM']:'&nbsp;';?></td>
						</tr>
						<?php
						$STT = 0;
						foreach ($arr_process as $key => $process){ 
							$STT++;
							$PRINTING_FOLLOW = $process['PRINTING_FOLLOW'];
							$PROCESS_1 	= $process['PROCESS_1']?$process['PROCESS_1']:'&nbsp;';
							$PROCESS_2 	= $process['PROCESS_2']?$process['PROCESS_2']:'&nbsp;';
							$PROCESS_3 	= $process['PROCESS_3']?$process['PROCESS_3']:'&nbsp;';
							$PROCESS_4 	= $process['PROCESS_4']?$process['PROCESS_4']:'&nbsp;';
							$PASSES 	= $process['PASSES'];
							$SCREEN 	= $process['SCREEN'];
							$TIME 		= $process['TIME'];
							$SETUP 		= $process['SETUP'];
						?>
						<tr class="">
							<td colspan="1" class="aleft"><?php echo $PRINTING_FOLLOW;?></td>
							<td colspan="1"><?php echo $STT==1?$STT:'&nbsp;';?></td>
							<td colspan="2" <?php echo ($PROCESS_1!='&nbsp;')?'class="nen_vang"':'';?>><?php echo $PROCESS_1;?></td>
							<td colspan="1" <?php echo ($PROCESS_2!='&nbsp;')?'class="nen_vang"':'';?>><?php echo $PROCESS_2;?></td>
							<td colspan="1" <?php echo ($PROCESS_3!='&nbsp;')?'class="nen_vang"':'';?>><?php echo $PROCESS_3;?></td>
							<td colspan="1" <?php echo ($PROCESS_4!='&nbsp;')?'class="nen_vang"':'';?>><?php echo $PROCESS_4;?></td>
							<td colspan="1" <?php echo !empty($PASSES)?'class="nen_vang"':'';?>><?php echo $PASSES;?></td>
							<td colspan="1" <?php echo !empty($SCREEN)?'class="nen_vang"':'';?>><?php echo $SCREEN;?></td>
							<td colspan="1" <?php echo !empty($TIME)?'class="nen_vang"':'';?>><?php echo $TIME;?></td>
							<td colspan="1" <?php echo !empty($SETUP)?'class="nen_vang"':'';?>><?php echo $SETUP;?></td>
						</tr>
						<?php } ?>
						<?php
						for($i=1;$i<=8-$STT;$i++){
						?>
						<tr class="">
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="2">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
						</tr>
						<?php } ?>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="" class="<?php echo $non_border ;?>">
					<table class="padding" style="width:100%;height:100%;border-collapse:collapse;"  cellpadding="0" cellspacing="0">
						<tr class="">
							<td colspan="2" style="width:25%;" rowspan="3" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?>">Material/Vật liệu:</td>
							<td colspan="4" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?>">Code/Mã vật tư:</td>
							<td colspan="4" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?> nen_vang"><?php echo $MATERIAL_CODE ;?></td>
						</tr>
						<tr class="">
							<td colspan="4" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?>">Name/ Tên vật tư:</td>
							<td colspan="4" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?> nen_cam"><?php echo $MATERIAL_NAME ;?></td>
						</tr>
						<tr class="">
							<td colspan="4" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?>">Size/Kích thước:</td>
							<td colspan="1" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?>"><?php echo !empty($SIZE_ARR[0])?$SIZE_ARR[0]:'&nbsp;';?></td>
							<td colspan="3" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?>"><?php echo !empty($SIZE_ARR[1])?$SIZE_ARR[1]:'&nbsp;';?></td>
						</tr>
						<tr class="">
							<td colspan="1" rowspan="4" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?>">Good quality output (Số sheet):</td>
							<td colspan="5" class="aleft <?php echo $non_border ;?>">Original Need/Số tờ ban đầu:</td>
							<td colspan="1" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?>"><?php echo $ORGINAL_NEED ;?></td>
							<td colspan="2" class="aleft <?php echo $non_border ;?>">% Phế phẩm thiết kế:</td>
							<td colspan="1" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?>"><?php echo $SCRAP_DESIGN ;?>%</td>
						</tr>
						<tr class="">
							<td colspan="5" class="aleft <?php echo $non_border ;?>">Sheet after batching/Số tờ thiết kế:</td>
							<td colspan="1" class="aleft <?php echo $non_border ;?>"><?php echo $SHEET_BATCHING ;?></td>
							<td colspan="2" class="aleft <?php echo $non_border ;?>">% Phế phẩm canh chỉnh:</td>
							<td colspan="1" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?>"><?php echo $SCRAP_SETUP ;?>%</td>
						</tr>
						<tr class="">
							<td colspan="5" class="aleft <?php echo $non_border ;?>">Packing/ Số tờ sau khi đóng gói:</td>
							<td colspan="1" class="aleft <?php echo $non_border ;?>"><?php echo $PACKING ;?></td>
							<td colspan="2" class="aleft <?php echo $non_border ;?>">% Phế phẩm lỗi:</td>
							<td colspan="1" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?>"><?php echo $SCRAP_ERROR ;?>%</td>
						</tr>
						<tr class="">
							<td colspan="5" class="aleft <?php echo $non_border ;?>">Printing/Số tờ sau khi in</td>
							<td colspan="1" class="aleft <?php echo $non_border ;?>"><?php echo $PRINTING ;?></td>
							<td colspan="2" class="aleft <?php echo $non_border ;?>">% Phế phẩm in:</td>
							<td colspan="1" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?>"><?php echo $SCRAP_PRINTING ;?>%</td>
						</tr>
						<tr class="">
							<td colspan="6" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?>">Paper compensate/Tổng số tờ bù hao:</td>
							<td colspan="1" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?>"><?php echo $PAPER_COMPENSATE ;?></td>
							<td colspan="2" class="aleft <?php echo $non_border ;?>">Tổng % phế phẩm:</td>
							<td colspan="1" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?>"><?php echo $TOTAL_SCRAP ;?>%</td>
						</tr>
						<tr class="">
							<td colspan="3" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?>" style="width:40%;">Total sheet/Tổng số tờ đơn hàng:</td>
							<td colspan="1" class="nen_vang <?php echo $a_pt8_b;?> <?php echo $non_border ;?>" style="width:15%;"><?php echo $TOTAL_SHEET ;?></td>
							<td colspan="3" style="width:17%;padding-left:4%" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?>">Tổng số lượt:</td>
							<td colspan="1" class="nen_vang <?php echo $a_pt8_b;?> <?php echo $non_border ;?>"><?php echo $TOTAL_PASSES_2 ;?></td>
							<td colspan="1" class="<?php echo $a_pt8_b;?> <?php echo $non_border ;?>">Số giờ chạy:</td>
							<td colspan="1" class="nen_vang <?php echo $a_pt8_b;?> <?php echo $non_border ;?>"><?php echo $TIME_RUNNING ;?></td>
						</tr>
						<!-- end process-->
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="" class="<?php echo $non_border ;?>">
					<table class="padding" style="width:100%;height:100%;border-collapse:collapse;"  cellpadding="0" cellspacing="0">	
						<tr class="">
							<td colspan="1" style="width:22%" class="<?php echo $pt8_b;?>">Công đoạn</td>
							<td colspan="1" style="width:10%" class="<?php echo $pt8_b;?>">Số lượng</td>
							<td colspan="1" style="width:12%" class="<?php echo $pt8_b;?>">Ngày</td>
							<td colspan="1" style="width:13%" class="<?php echo $pt8_b;?>">Ký Tên</td>
							<td colspan="1" style="width:5%" class="<?php echo $non_border ;?>">&nbsp;</td>
							<td colspan="1" style="width:16%" class="<?php echo $pt8_b;?>">Công đoạn</td>
							<td colspan="1" class="<?php echo $pt8_b;?>">Số lượng</td>
							<td colspan="1" style="width:8%" class="<?php echo $pt8_b;?>">Ngày</td>
							<td colspan="1" class="<?php echo $pt8_b;?>">Ký Tên</td>							
						</tr>
						<tr class="">
							<td colspan="1" class="aleft">Planning/Kế hoạch.</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1" class="<?php echo $pt8_b;?>"><?php echo $CREATED_DATE;?></td>
							<td colspan="1" class="<?php echo $pt8_b;?>"><?php echo $PLANNING_NAME;?></td>
							<td colspan="1" class="<?php echo $non_border ;?>">&nbsp;</td>
							<td colspan="1" class="aleft">Operator Vận hành</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>							
						</tr>
						<tr class="">
							<td colspan="1" class="aleft">Prepress/Thiết kế</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1" class="<?php echo $non_border ;?>">&nbsp;</td>
							<td colspan="1" class="aleft">Tr. ca/G. sát</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>							
						</tr>
						<tr class="">
							<td colspan="1" class="aleft">Screen room/Phòng Khung</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1" class="<?php echo $non_border ;?>">&nbsp;</td>
							<td colspan="1" class="aleft">Số tờ QC  nhận</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>							
						</tr>
						<tr class="">
							<td colspan="1" class="aleft">Ink Room/Phòng Mực</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1" class="<?php echo $non_border ;?>">&nbsp;</td>
							<td colspan="1" class="aleft">Số lượng QC kiểm</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>							
						</tr>
						<tr class="">
							<td colspan="1" class="aleft">Mat. Handling/Giao vật tư</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1" class="<?php echo $non_border ;?>">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1">&nbsp;</td>							
						</tr>
						<tr class="">
							<td colspan="1" class="<?php echo $pt8_b;?> aleft">Remark/Chú ý:</td>
							<td colspan="8" class="aleft">TUÂN THEO MẪU ĐÃ APPROVED.</td>						
						</tr>
						<tr class="">
							<td colspan="9" class="<?php echo $non_border ;?>">&nbsp;</td>
											
						</tr>
						<tr class="">
							<td colspan="1" class="<?php echo $non_border ;?> <?php echo $pt8;?> aleft">2-PL-012-F01 </td>	
							<td colspan="5" class="<?php echo $non_border ;?> <?php echo $pt8;?>">Trang: 1/1 </td>
							<td colspan="3" class="<?php echo $non_border ;?> <?php echo $pt8;?> aright">Ngày ban hành: 01-05-2016 </td>
						</tr>
					 </table>
				</td>
			</tr>
        </table>
    </div>
</body>
</html>