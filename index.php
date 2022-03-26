<?php
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	require_once("Database.php");

	function getAutomailUpdated()
    {
        $result = 'loading...';
        $data = MiQuery("SELECT `STATUS`, `CREATEDDATE` FROM autoload_log ORDER BY ID DESC;", connection('au_avery') );
        if (!empty($data) ) {
            $data = $data[0];
            $status = $data['STATUS'];
            $created_date = $data['CREATEDDATE'];

            if ($status == 'OK' ) {
                $result = $created_date;
            } else {

                $dataOK = MiQuery("SELECT `STATUS`, `CREATEDDATE` FROM autoload_log WHERE `STATUS`='OK' ORDER BY ID DESC;", connection('au_avery') );
                $created_date_OK = '';
                if (!empty($dataOK) ) {
                    $dataOK = $dataOK[0];
                    $created_date_OK = $dataOK['CREATEDDATE'];
                }

                // 01: Không save được
				if ($status == 'ERR_01' ) {
					$result = "$created_date_OK. (ERR 01 (UPDATE) lúc $created_date)";
				} else if ($status == 'ERR_02' ) { // có rỗng dữ liệu PACKING,...
					$result = "$created_date_OK. (ERR 02 (EMPTY DATA) lúc $created_date)";
				} else if ($status == 'ERR_03' ) { // File không đọc được
					$result = "$created_date_OK. (ERR 03 (File Lỗi) lúc $created_date)";
				} 
            }
            
        }

        return $result;
    }

	function getUser() 
    {
        $email = isset($_COOKIE["VNRISIntranet"]) ? trim($_COOKIE["VNRISIntranet"]) : "";
        return $email;
    }

    function planning_user_statistics($email, $program )
    {
        if (!empty($email) ) {
            $table = 'planning_user_statistics';
            $ip = $_SERVER['REMOTE_ADDR'];

            $url = "http://" .$_SERVER["SERVER_ADDR"] .$_SERVER["REQUEST_URI"];

            $METADATA = "HTTP_COOKIE: " . $_SERVER["HTTP_COOKIE"]. "PATH: " .$_SERVER["PATH"]. "SERVER_ADDR" .$_SERVER["SERVER_ADDR"]. "SERVER_PORT" .$_SERVER["SERVER_PORT"]. "DOCUMENT_ROOT" .$_SERVER["DOCUMENT_ROOT"]. "SCRIPT_FILENAME" .$_SERVER["SCRIPT_FILENAME"];
            $METADATA = mysqli_real_escape_string(connection("au_avery"), $METADATA);

            // update data
            $key = $email . $program;
            $updated = date('Y-m-d H:i:s');
            $check = MiQuery("SELECT `email` FROM $table WHERE CONCAT(`email`,`program`) = '$key';", connection('au_avery') );
            if (!empty($check) ) {
                $sql = "UPDATE $table SET `ip` = '$ip', `url` = '$url', `METADATA` = '$METADATA', `updated` = '$updated'  WHERE `email` = '$email' AND `program` = '$program';";
            } else {
                // Thêm mới. Tự động nên không trả về kết quả
                $sql = "INSERT INTO $table (`email`, `program`, `ip`, `url`, `METADATA`, `updated`) VALUE ('$email', '$program', '$ip',  '$url', '$METADATA', '$updated');";
            }

            return MiNonQuery2( $sql,connection("au_avery"));
            
        }
        
        
    }

	$email = getUser();

	// check role
	$arrayAdmin = [];
	//echo $sql;      die;  
	$rowsResult = MiQuery("SELECT * FROM user;", connection());
	if(!empty($rowsResult)){
		foreach ($rowsResult as $row){
			$arrayRole[]=$row['EMAIL'];
			if(!empty($row['IS_ADMIN'])){
				$arrayAdmin[] = $row['EMAIL'];
			}		
		}
	}else{
		$arrayRole = ['long.dang','thuthuy.do','khoa.huynh','van.mai','phuongdung.pham','quyen.tk.nguyen','nam.nguyen','ly.tran','tu.ngo', 'tan.doan', 'truong.hoang'];
	}
	$updateDB = 0;
	$isAdmin = 0;
	$user = '';
	if(!empty($_COOKIE["VNRISIntranet"])){
		$user = $_COOKIE["VNRISIntranet"];
		if(!empty($user)){
			// DB
			if(in_array($user,$arrayRole)){
				$updateDB = 1;
			}
			if(!empty($user)&&in_array($user,$arrayAdmin)){
				$isAdmin = 1;
			}else{
				$isAdmin = 0;
			}
		}
	}

?>

<!DOCTYPE html>
<html>
<head>
    <title>H T L Planning</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="icon" href="./Module/images/Logo.ico" type="image/x-icon">
	<meta name="google" content="notranslate" />
	<link rel="STYLESHEET" type="text/css" href="./Module/dhtmlx/skins/skyblue/dhtmlx.css">
	<script src="./Module/dhtmlx/codebase/dhtmlx.js" type="text/javascript"></script>
	<script src="./Module/JS/Date.format.min.js" type="text/javascript"></script>
	<script src="./Module/JS/jquery-1.10.1.min.js"></script>

<style>
    html, body {
        width: 100%;
        height: 100%;
        padding: 0;
        margin: 0;
		font-family: "Source Sans Pro","Helvetica Neue",Helvetica;
		background-repeat: no-repeat;
		background-size: 100%;
    }
    .formShow input,.formShow select{ 
            font-size:12px !important; 
            font-weight:bold;
    }
    @media only screen and (max-width: 1600px) {
        
    }
	.dhxtoolbar_btn_pres .dhxtoolbar_text{
		font-weight:bold!important;
	}
	.cls_test .dhxform_label_align_left label{
		font-weight:bold!important;
	}
	.dhxwin_active .objbox td a:visited{
		color:red!important;
	}
</style>
<script>
var updateDB = <?php echo $updateDB;?>;
//var updateDB = <?php echo "1"; //$updateDB;?>;
var isAdmin = <?php echo $isAdmin;?>;
var user_name = '<?php echo $user;?>';
var LayoutMain;
var MainMenu;
var ToolbarMain;
var RootPath = '<?php echo $_SERVER['REQUEST_URI'];?>';
var RootDataPath = RootPath+'data/';
var LayoutForm;
var SoForm;
var SoGrid;
var MaterialGrid;
var SizeGrid;

var checked_SOLINE = [];
var noGrid;
var check_gg = 0;

<?php
	// get automail new
	$automail_updated = 'Automail updated: '. getAutomailUpdated();
	if (strpos($automail_updated, 'ERR') !==false ) {
		$automail_updated = '<span style=\"color:red;\">'.$automail_updated.'</span>';
	}
	
    if(!isset($_COOKIE["VNRISIntranet"])) {
        echo 'var HeaderTile = "'.$automail_updated.'<a style=\'color:blue;font-style:italic;padding-left:10px\'>Hi Guest | <a href=\"./Module/login/index.php?URL=HTL\">Login</a></a>";var UserVNRIS = "";';
    } else {
        echo 'var HeaderTile = "'.$automail_updated.'<a style=\'color:blue;font-style:italic;padding-left:10px\'>Hi '.$_COOKIE["VNRISIntranet"].' | <a href=\"./Module/login/Logout.php\">Logout</a></a>";var UserVNRIS = "'.$_COOKIE["VNRISIntranet"].'";';
    }
?>
    var widthScreen = screen.width;
    var widthSo = 450;
	var widthSoSelect = 750;
	var heightSoSelect = 360;
    if(widthScreen<=1600){
        widthSo = 388;
		var widthSoSelect = 650;
    }
	
	function setCookie(cname,cvalue,exdays){
		var d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		var expires = "expires=" + d.toGMTString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	function getCookie(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return -1;
	}	
	// xxxx document.cookie = "auto_sample=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    function initLayout(){
        LayoutMain = new dhtmlXLayoutObject({
            parent: document.body,
            pattern: "5H",
            offsets: {
                top: 65
            },
            cells: [
                {id: "a", header: true, text: "LIST SO", width: widthSo}, 
				{id: "b", header: true, text: "LIST ITEM"},               
                {id: "c", header: true, text: "PRINTING FOLLOW", width:widthSoSelect, height:heightSoSelect},                
                {id: "d", header: true, text: "LIST MATERIAL"},
				{id: "e", header: true, text: "NO"}
            ]
        });
    }
    function initMenu(){
        MainMenu = new dhtmlXMenuObject({
				parent: "menuObj",
				icons_path: "./Module/dhtmlx/common/imgs_Menu/",
				json: "./Module/menu/Menu.json",
				top_text: HeaderTile
        });
    }
	
	var dhxWinsMachine;
	function LoadFormMachine(){		
        if(!dhxWinsMachine){
            dhxWinsMachine= new dhtmlXWindows();// show window form to add length
        }
        if (!dhxWinsMachine.isWindow("windowMachine")){
            // var win = myWins.createWindow(string id, int x, int y, int width, int height); // Creating New Window              
            windowMachine = dhxWinsMachine.createWindow("windowMachine", 491,65,395,286);
            windowMachine.setText("Window Update OH & SO LINE");
            /*necessary to hide window instead of remove it*/
            windowMachine.attachEvent("onClose", function(win){
                if (win.getId() == "windowMachine") 
                    win.hide();
            });
			formData = [
				{type: "fieldset", label: "Uploader", list:[
					{type: "upload", name: "myFiles", autoStart: true, inputWidth: 330, url: "data/machine_upload.php"}
				]}
			];
			MachineForm = windowMachine.attachForm(formData, true);			
			MachineForm.attachEvent("onFileAdd",function(realName){
				// your code here
				dhxWinsMachine.window("windowMachine").progressOn();
			});
			MachineForm.attachEvent('onUploadFail', function(fileName,extra){
				alert(extra.mess);
				var myUploader = MachineForm.getUploader('myFiles');
				myUploader.clear();
				dhxWinsMachine.window("windowMachine").progressOff();
			});
			MachineForm.attachEvent('onUploadFile', function(state,fileName,extra){
				alert(extra.mess);
				dhxWinsMachine.window("windowMachine").progressOff();
				location.reload();				
			});
			
        }else{
            dhxWinsMachine.window("windowMachine").show(); 
        } 
    }
	/********************************************/
	//@tandoan
	var dhxWinsItem_s;
	function item_upload_sum(){
        if(!dhxWinsItem_s){
            dhxWinsItem_s= new dhtmlXWindows();// show window form to add length
        }
        if (!dhxWinsItem_s.isWindow("windowItem")){
            // var win = myWins.createWindow(string id, int x, int y, int width, int height); // Creating New Window
            windowItem = dhxWinsItem_s.createWindow("windowItem", 491,66,395,288);
            windowItem.setText("Window Update ITEM");
            /*necessary to hide window instead of remove it*/
            windowItem.attachEvent("onClose", function(win){
                if (win.getId() == "windowItem")
                    win.hide();
            });
			formData = [
				{type: "fieldset", label: "Uploader", list:[
					{type: "upload", name: "myFiles", autoStart: true, inputWidth: 330, url: "data/item_upload_sum.php"}
				]}
			];
			ItemForm = windowItem.attachForm(formData, true);
			ItemForm.attachEvent("onFileAdd",function(realName){
				// your code here
				dhxWinsItem_s.window("windowItem").progressOn();
			});
			ItemForm.attachEvent('onUploadFail', function(fileName,extra){
				alert(extra.mess);
				var myUploader = ItemForm.getUploader('myFiles');
				myUploader.clear();
				dhxWinsItem_s.window("windowItem").progressOff();
			});
			ItemForm.attachEvent('onUploadFile', function(state,fileName,extra){
				alert(extra.mess);
				dhxWinsItem_s.window("windowItem").progressOff();
				location.reload();
			});

        }else{
            dhxWinsItem_s.window("windowItem").show();
        }
    }
	///////////////////////////////////////////////////////////////////////////////////


	
	var dhxWinsItem;
	function loadFormItem(){		
        if(!dhxWinsItem){
            dhxWinsItem= new dhtmlXWindows();// show window form to add length
        }
        if (!dhxWinsItem.isWindow("windowItem")){
            // var win = myWins.createWindow(string id, int x, int y, int width, int height); // Creating New Window              
            windowItem = dhxWinsItem.createWindow("windowItem", 491,66,395,288);
            windowItem.setText("Window Update ITEM");
            /*necessary to hide window instead of remove it*/
            windowItem.attachEvent("onClose", function(win){
                if (win.getId() == "windowItem") 
                    win.hide();
            });
			formData = [
				{type: "fieldset", label: "Uploader", list:[
					{type: "upload", name: "myFiles", autoStart: true, inputWidth: 330, url: "data/item_upload.php"}
				]}
			];
			ItemForm = windowItem.attachForm(formData, true);			
			ItemForm.attachEvent("onFileAdd",function(realName){
				// your code here
				dhxWinsItem.window("windowItem").progressOn();
			});
			ItemForm.attachEvent('onUploadFail', function(fileName,extra){
				alert(extra.mess);
				var myUploader = ItemForm.getUploader('myFiles');
				myUploader.clear();
				dhxWinsItem.window("windowItem").progressOff();
			});
			ItemForm.attachEvent('onUploadFile', function(state,fileName,extra){
				alert(extra.mess);
				dhxWinsItem.window("windowItem").progressOff();
				location.reload();				
			});
			
        }else{
            dhxWinsItem.window("windowItem").show(); 
        } 
    }
	
	var dhxWinsFOD;
	function loadFormFOD(){		
        if(!dhxWinsFOD){
            dhxWinsFOD= new dhtmlXWindows();// show window form to add length
        }
        if (!dhxWinsFOD.isWindow("windowFOD")){
            // var win = myWins.createWindow(string id, int x, int y, int width, int height); // Creating New Window              
            windowFOD = dhxWinsFOD.createWindow("windowFOD", 491,359,395,154);
            windowFOD.setText("Window Update FOD");
            /*necessary to hide window instead of remove it*/
            windowFOD.attachEvent("onClose", function(win){
                if (win.getId() == "windowFOD") 
                    win.hide();
            });
			formData = [
				{type: "fieldset", label: "Uploader", list:[
					{type: "upload", name: "myFiles", autoStart: true, inputWidth: 330, url: "data/fod_upload.php"}
				]}
			];
			FODForm = windowFOD.attachForm(formData, true);			
			FODForm.attachEvent("onFileAdd",function(realName){
				// your code here
				dhxWinsFOD.window("windowFOD").progressOn();
			});
			FODForm.attachEvent('onUploadFail', function(fileName,extra){
				alert(extra.mess);
				var myUploader = FODForm.getUploader('myFiles');
				myUploader.clear();
				dhxWinsFOD.window("windowFOD").progressOff();
			});
			FODForm.attachEvent('onUploadFile', function(state,fileName,extra){
				alert(extra.mess);
				dhxWinsFOD.window("windowFOD").progressOff();
				location.reload();				
			});
			
        }else{
            dhxWinsFOD.window("windowFOD").show(); 
        } 
    }
	
	
	var dhxWinsListMachine;
    var viewMachineGrid;
	var MachineGrid;
    function loadListMachine(){		
        if(!dhxWinsListMachine){
            dhxWinsListMachine= new dhtmlXWindows();// show window form to add length
        }
        if (!dhxWinsListMachine.isWindow("windowViewMachine")){
            // var win = myWins.createWindow(string id, int x, int y, int width, int height); // Creating New Window              
            windowViewMachine = dhxWinsListMachine.createWindow("windowViewMachine", 493,65,660,650);
			dhxWinsListMachine.window("windowViewMachine").progressOn();
            windowViewMachine.setText("Window View OH");
            /*necessary to hide window instead of remove it*/
            windowViewMachine.attachEvent("onClose", function(win){
                if (win.getId() == "windowViewMachine") 
                    win.hide();
            });
            MachineGrid= windowViewMachine.attachGrid();
            MachineGrid.enableSmartRendering(true);
			MachineGrid.attachHeader("#text_filter,#text_filter,#text_filter");	
			MachineGrid.setHeader('OH,SO LINE,CREATED BY,');
			MachineGrid.setImagePath("./Module/dhtmlx/skins/skyblue/imgs/");	
			MachineGrid.setInitWidths("210,105,160,150");
			MachineGrid.setColAlign("left,left,left,left");
			MachineGrid.setColTypes("ed,ed,ro,link");
			MachineGrid.setColSorting("str,str,str,str");		
            MachineGrid.init();  
            MachineGrid.load(RootDataPath+'view_machine.php',function(){				
				//updateMachine();
			}); 
        }else{
            dhxWinsListMachine.window("windowViewMachine").show(); 
        } 
		dhxWinsListMachine.window("windowViewMachine").progressOff();
    }
	
	var dhxWinsListItem;
    var viewItemGrid;
	var ItemMasterGrid;
    function loadListItem(){		
        if(!dhxWinsListItem){
            dhxWinsListItem= new dhtmlXWindows();// show window form to add length
        }
        if (!dhxWinsListItem.isWindow("windowViewItem")){
            // var win = myWins.createWindow(string id, int x, int y, int width, int height); // Creating New Window              
            windowViewItem = dhxWinsListItem.createWindow("windowViewItem", 493,65,1450,655);
			dhxWinsListItem.window("windowViewItem").progressOn();
            windowViewItem.setText("Window View Item");
            /*necessary to hide window instead of remove it*/
            windowViewItem.attachEvent("onClose", function(win){
                if (win.getId() == "windowViewItem") 
                    win.hide();
            });
            ItemMasterGrid= windowViewItem.attachGrid();
            ItemMasterGrid.enableSmartRendering(true);
			ItemMasterGrid.attachHeader(",#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter");	
			var delete_button = '<input type="button" id="DeleteItem" value="DELETE" onclick="deleteItem()">';
			if(!updateDB){
				delete_button = '';
			}
			ItemMasterGrid.setHeader(delete_button+',ITEM,STT,LOP,TIEN_TRINH,PASS,KHUNG,VAT_TU,SETUP,TIME,CHECK_AGI,FOD,SCRAP,UPDATED BY,UPDATED TIME');
			ItemMasterGrid.setImagePath("./Module/dhtmlx/skins/skyblue/imgs/");	
			ItemMasterGrid.setInitWidths("90,100,60,90,120,70,70,130,70,55,90,65,60,120,120");
			ItemMasterGrid.setColAlign("left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
			ItemMasterGrid.setColTypes("ch,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed");
			ItemMasterGrid.setColSorting("na,str,str,str,str,str,str,str,str,str,str,str,str,str,str");		
            ItemMasterGrid.init();  
            ItemMasterGrid.load(RootDataPath+'view_item.php',function(){				
				updateItem();
			}); 
        }else{
            dhxWinsListItem.window("windowViewItem").show(); 
        } 
		dhxWinsListItem.window("windowViewItem").progressOff();
    }
	
	function deleteItem(){
		var checkIDs = [];
		ItemMasterGrid.forEachRow(function(id){
			if(ItemMasterGrid.cells(id,0).getValue()==1){
				checkIDs.push(id);
			}
		});
		if(!checkIDs.length>0){
			alert("Vui lòng chọn dòng để XÓA");
			return false;
		}else{
			confirm_delete = confirm("Bạn có muốn XÓA những item đã chọn!!!");
			if(confirm_delete){
				var url_delete = RootDataPath+'delete_item.php';
				// get all checkbox
				$.ajax({
					url: url_delete,
					type: "POST",
					data: {data: JSON.stringify(checkIDs)},
					dataType: "json",
					beforeSend: function(x) {
						if (x && x.overrideMimeType) {
							x.overrideMimeType("application/j-son;charset=UTF-8");
						}
					},
					success: function(result) {
						if(result.status){
							// reload	
							for(var i=0;i<checkIDs.length;i++){
								ItemMasterGrid.deleteRow(checkIDs[i]);
							}
						}else{
							alert(result.mess);							
						}
					}
				});					
			}
		}
	}
	
	function updateItem(){
		ItemMasterGrid.attachEvent("onEnter", function(id,ind){
			// your code here
			var url_update = RootDataPath+'update_item.php';
			var ITEM = ItemMasterGrid.cells(id,1).getValue();
			ITEM = ITEM.trim();
			var STT = ItemMasterGrid.cells(id,2).getValue();
			STT = STT.trim();
			var LOP = ItemMasterGrid.cells(id,3).getValue();
			LOP = LOP.trim();
			var TIEN_TRINH = ItemMasterGrid.cells(id,4).getValue();
			TIEN_TRINH = TIEN_TRINH.trim();
			var PASS = ItemMasterGrid.cells(id,5).getValue();
			PASS = PASS.trim();
			var KHUNG = ItemMasterGrid.cells(id,6).getValue();
			KHUNG = KHUNG.trim();
			var VAT_TU = ItemMasterGrid.cells(id,7).getValue();
			VAT_TU = VAT_TU.trim();
			var SETUP = ItemMasterGrid.cells(id,8).getValue();
			SETUP = SETUP.trim();
			var TIME = ItemMasterGrid.cells(id,9).getValue();
			TIME = TIME.trim();
			var CHECK_AGI = ItemMasterGrid.cells(id,10).getValue();
			CHECK_AGI = CHECK_AGI.trim();
			var FOD = ItemMasterGrid.cells(id,11).getValue();
			FOD = FOD.trim();
			var SCRAP = ItemMasterGrid.cells(id,12).getValue();
			SCRAP = SCRAP.trim();
			var objUA = {
				ITEM:ITEM,
				STT:STT,
				LOP:LOP,
				TIEN_TRINH:TIEN_TRINH,
				PASS:PASS,
				KHUNG:KHUNG,
				VAT_TU:VAT_TU,
				SETUP:SETUP,
				TIME:TIME,
				CHECK_AGI:CHECK_AGI,
				FOD:FOD,
				SCRAP:SCRAP,
				ITEM_ID:id
			};			
			$.ajax({
				url: url_update,
				type: "POST",
				data: {data: JSON.stringify(objUA)},
				dataType: "json",
				beforeSend: function(x) {
					if (x && x.overrideMimeType) {
					x.overrideMimeType("application/j-son;charset=UTF-8");
					}
				},
				success: function(result) {
					if(result.status){
						// change ID
						if(result.id){
							ItemMasterGrid.changeRowId(id,result.id);
						}
						alert('Update dữ liệu thành công!!!!');
					}else{
						alert(result.mess);
					}
				}
			});
		});
	}
	
	var ToolbarMaterial;		
    function initToolbar(){		
		if(updateDB){
			ToolbarMaterial = LayoutMain.cells("b").attachToolbar({
				icons_path: "./Module/dhtmlx/common/imgs/",
				align: "left",
			});
			ToolbarMaterial.addButton("view_oh",1, "VIEW OH SO", "page_info.gif"); // show button to add length
			ToolbarMaterial.addButton("update_oh",2, "UPDATE OH SO", "save.gif"); // show button to add length
			ToolbarMaterial.addButton("view_item",3, "VIEW ITEM", "page_info.gif"); // show button to add length
			ToolbarMaterial.addButton("update_item",4, "UPDATE ITEM", "save.gif"); // show button to add length
			ToolbarMaterial.addButton("export_all_item",5, "EXPORT ITEM", "xlsx.gif"); // show button to add length
			if(isAdmin){
				ToolbarMaterial.addButton("view_user",null, "VIEW USER", "page_info.gif");
			}
			ToolbarMaterial.attachEvent("onClick", function(name){
				if(name == "view_oh")
				{
					loadListMachine();
				}
				else if(name == "update_oh")
				{
					LoadFormMachine();
				}
				else if(name == "view_item")
				{
					loadListItem();
				}
				else if(name == "update_item")
				{
					loadFormItem();
				}
				else if(name == "export_all_item"){
					var url_export = RootDataPath+'report_item_all.php';
					document.location.href = url_export;
				}else if(name == "view_user")
				{
					loadListUser();
				}
			});
			ToolbarPrint = LayoutMain.cells("c").attachToolbar({
				icons_path: "./Module/dhtmlx/common/imgs/",
				align: "left",
			});
			ToolbarPrint.addButton("update_fod",1, "UPDATE FOD", "save.gif"); // show button to add length
			ToolbarPrint.attachEvent("onClick", function(name){
				if(name == "update_fod")
				{
					loadFormFOD();
				}
			});
		}		
        ToolbarMain = new dhtmlXToolbarObject({
            parent: "ToolbarBottom",
            icons_path: "./Module/dhtmlx/common/imgs/",
            align: "left",
        });
        // end 
        ToolbarMain.addText("", 1, "<a style='font-size:20pt;font-weight:bold'>Avery Dennison H&nbsp;T&nbsp;L</a>");
		/*
        ToolbarMain.addText("", 2, "SO");
        ToolbarMain.addInput("so",3,"");   
		*/
		ToolbarMain.addText("", 2, "OH");
        ToolbarMain.addInput("OH",3,""); 
		ToolbarMain.addText("", 4, "FROM DATE");
        ToolbarMain.addInput("from_date",5,""); // set for test 27210890
		ToolbarMain.addText("",6, "TO DATE");
        ToolbarMain.addInput("to_date",7,""); // set for test 27210890
		var from_date_input = ToolbarMain.getInput("from_date");
		var to_date_input = ToolbarMain.getInput("to_date");		
		myCalendar = new dhtmlXCalendarObject([from_date_input,to_date_input]);
		myCalendar.setDateFormat("%d-%M-%y");
		ToolbarMain.addSpacer("to_date");	
		if(updateDB){
			ToolbarMain.addButton("export",20, "Export", "xlsx.gif"); 
			ToolbarMain.hideItem("export");
			ToolbarMain.addButton("saveNo",21, "Save No", "save.gif");    
			ToolbarMain.hideItem('saveNo');
			ToolbarMain.addButton("printNo",22, "Print No", "print.gif");
			ToolbarMain.hideItem('printNo');

			ToolbarMain.addButton("upload_item_sum",19, "Upload Item Special", "xlsx.gif");
			
			ToolbarMain.addButton("view_no",23, "View No", "page_info.gif");
			ToolbarMain.addButton("report_no",24, "Report", "page_info.gif");
			ToolbarMain.addButton("export_all_no",25, "Export All OH", "xlsx.gif");			
		}
        ToolbarMain.attachEvent("onClick", function(name)
        {
			if (name == "upload_item_sum") {
				item_upload_sum();
			}
            else if(name == "printNo")
            {
                saveDatabase(true);
            }
            else if(name == "view_no")
            {
                viewNO();
            }else if(name == "saveNo"){
                saveDatabase(false);
            }
            else if(name == "report_no"){
                reportNo();
            }
            else if(name == "export"){
				noGrid.enableCSVHeader(true);
                noGrid.setCSVDelimiter(',');
				var csv = noGrid.serializeToCSV();
				if (csv == null) return;
				filename = new Date().format('d-m-Y__H:i:s')+'.csv';   //  07-06-2016 06:38:34
				if (!csv.match(/^data:text\/csv/i)) {
					csv = 'data:text/csv;charset=utf-8,' + csv;
				}		
				// data = csv;
				data = encodeURI(csv);
				//data = CSVToArray(data,',');
				for (var k=0;k<=100;k++){
					data = data.replace('&amp;','&');
				}
				link = document.createElement('a');
				link.setAttribute('href', data);
				link.setAttribute('download', filename);
				link.click();
            }        
            else if(name == "ExportLength"){        
                /*        
                dhxWinsReportLen.window("windowReportLen").show();
                dhxWinsReportLen.window("windowReportLen").hide(); 
                */               
                LenGrid.toExcel(RootPath+'codebase/grid-excel-php/generate.php');
            }
            else if(name == "export_all_no"){
				var from_date_value = ToolbarMain.getValue("from_date");
				var to_date_value = ToolbarMain.getValue("to_date");
                var url_export = RootDataPath+'report_no_all.php?from_date_value='+from_date_value+'&to_date_value='+to_date_value;
                document.location.href = url_export;
            }			
        }); 	
    }	
	
	var dhxWinsListUser;
	var viewUserGrid;
	var UserGrid;
	function loadListUser(){		
		if(!dhxWinsListUser){
			dhxWinsListUser= new dhtmlXWindows();// show window form to add length
		}
		if (!dhxWinsListUser.isWindow("windowViewMachine")){
			// var win = myWins.createWindow(string id, int x, int y, int width, int height); // Creating New Window              
			windowViewMachine = dhxWinsListUser.createWindow("windowViewMachine", 493,65,560,665);
			dhxWinsListUser.window("windowViewMachine").progressOn();
			windowViewMachine.setText("Window View USER");
			/*necessary to hide window instead of remove it*/
			windowViewMachine.attachEvent("onClose", function(win){
				if (win.getId() == "windowViewMachine") 
					win.hide();
			});
			UserGrid= windowViewMachine.attachGrid();
			UserGrid.enableSmartRendering(true);
			UserGrid.attachHeader(",#text_filter,#text_filter,#text_filter,#text_filter");	
			var delete_button = '<input type="button" id="DeleteMachine" value="DELETE" onclick="DeleteUser()">';
			UserGrid.setHeader(delete_button+',EMAIL,SET ADMIN = 1,UPDATED BY');
			UserGrid.setImagePath("./Module/dhtmlx/skins/skyblue/imgs/");	
			UserGrid.setInitWidths("90,150,*,150");
			UserGrid.setColAlign("left,left,left,left");
			UserGrid.setColTypes("ch,ed,ed,ro");
			UserGrid.setColSorting("na,str,str,str");		
			UserGrid.init();  
			UserGrid.load(RootDataPath+'view_user.php',function(){				
				updateUser();
			}); 
		}else{
			dhxWinsListUser.window("windowViewMachine").show(); 
		} 
		dhxWinsListUser.window("windowViewMachine").progressOff();
	}
	
	function updateUser(){
		UserGrid.attachEvent("onEnter", function(id,ind){
			// your code here
			var url_update = RootDataPath+'update_user.php';
			var EMAIL = UserGrid.cells(id,1).getValue();
			EMAIL = EMAIL.trim();
			var NOTE = UserGrid.cells(id,2).getValue();
			NOTE = NOTE.trim();
			var objUA = {
				EMAIL:EMAIL,
				NOTE:NOTE,
				ITEM_ID:id
			};			
			$.ajax({
				url: url_update,
				type: "POST",
				data: {data: JSON.stringify(objUA)},
				dataType: "json",
				beforeSend: function(x) {
					if (x && x.overrideMimeType) {
					x.overrideMimeType("application/j-son;charset=UTF-8");
					}
				},
				success: function(result) {
					if(result.status){
						// change ID
						if(result.id){
							UserGrid.changeRowId(id,result.id);
						}
						alert('Update dữ liệu thành công!!!!');
					}else{
						alert(result.mess);
					}
				}
			});
		});
	}
		
	function DeleteUser(){
		var checkIDs = [];
		UserGrid.forEachRow(function(id){
			if(UserGrid.cells(id,0).getValue()==1){
				checkIDs.push(id);
			}
		});
		if(!checkIDs.length>0){
			alert("Vui lòng chọn dòng để XÓA");
			return false;
		}else{
			confirm_delete = confirm("Bạn có muốn XÓA những item đã chọn!!!");
			if(confirm_delete){
				var url_delete = RootDataPath+'delete_user.php';
				// get all checkbox
				$.ajax({
					url: url_delete,
					type: "POST",
					data: {data: JSON.stringify(checkIDs)},
					dataType: "json",
					beforeSend: function(x) {
						if (x && x.overrideMimeType) {
							x.overrideMimeType("application/j-son;charset=UTF-8");
						}
					},
					success: function(result) {
						if(result.status){
							// reload	
							for(var i=0;i<checkIDs.length;i++){
								UserGrid.deleteRow(checkIDs[i]);
							}
						}else{
							alert(result.mess);							
						}
					}
				});					
			}
		}
	}
	
	var dhxWins;
    var dhxWinsReport;
    function reportNo(){
        if(!dhxWinsReport){
            dhxWinsReport= new dhtmlXWindows();// show window form to add length
        }   
        if (!dhxWinsReport.isWindow("windowReport")){
            // var win = myWins.createWindow(string id, int x, int y, int width, int height); // Creating New Window              
            windowReportNo = dhxWinsReport.createWindow("windowReport", 492,65,1203,652);
            dhxWinsReport.window("windowReport").progressOn();
            windowReportNo.setText("Window Report NO");
            /*necessary to hide window instead of remove it*/
            windowReportNo.attachEvent("onClose", function(win){
                if (win.getId() == "windowReport") 
                    win.hide();
                    ToolbarMain.hideItem("export");
                    ToolbarMain.showItem("export_all_no");
            });
            formData = [
				{type: "button",value: "Export", offsetLeft: 10, offsetTop: 0},
				{type: "container", name: "reportGrid", label: "", inputWidth: 720,}
			];
            myForm = windowReportNo.attachForm(formData, true);	
            noGrid = windowReportNo.attachGrid();
            // noGrid = new dhtmlXGridObject(myForm.getContainer("reportGrid"));
            noGrid.setImagePath("./Module/dhtmlx/skins/skyblue/imgs/");
            noGrid.setHeader("DATE,LENH SX,ITEM CODE,RBO,SO,QTY,UPS,VAT TU,NGAY GIAO,SO MAU,TIEN TRINH,SO LUOT,SO KHUNG,NEED SHEET,SHEET BATCHING,SHEET SET UP,SHEET PACKING,TOTAL SHEET,MA VAT TU,KICH THUOC,SO TO BAN DAU,SO TO DONG GOI,SO TO IN,CUSTOMER ITEM,TONG LUOT IN,TONG LUOT MAU,CHIEU RONG,CHIEU DAI,RUNNING TIME,NOTE,SO MAY,BATCHING SCRAP,SET UP SCRAP,RUNNING SCRAP,TOTAL SCRAP,SO KHUNG, SO FILM,TG CHAY HANG,TG CANH CHINH,FOD,SO TIEN TRINH,SCRAP");   //sets the headers of columns
            noGrid.attachHeader("#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter");	//the method takes the columns' filters as a parameter
            noGrid.setColumnIds("DATE,LENH SX,ITEM CODE,RBO,SO#,QTY,UPS,VAT TU,NGAY GIAO,SO MAU,TIEN TRINH,SO LUOT,SO KHUNG,NEED SHEET,SHEET BATCHING,SHEET SET UP,SHEET PACKING,TOTAL SHEET,MA VAT TU,KICH THUOC,SO TO BAN DAU,SO TO DONG GOI,SO TO IN,CUSTOMER ITEM,TONG LUOT IN,TONG LUOT MAU,CHIEU RONG,CHIEU DAI,RUNNING TIME,NOTE,SO MAY,BATCHING SCRAP,SET UP SCRAP,RUNNING SCRAP,TOTAL SCRAP,SO KHUNG, SO FILM,TG CHAY HANG,TG CANH CHINH,FOD,SO TIEN TRINH,SCRAP");         //sets the columns' ids
            noGrid.setInitWidths("77,100,87,90,88,52,42,145,86,70,98,71,85,90,117,96,107,93,141,93,111,126,82,142,106,120,93,84,98,63,75,125,100,122,98,98,70,105,115,50,100,100");   //sets the initial widths of columns
            noGrid.setColAlign("left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");     //sets the alignment of columns
            noGrid.setColTypes("ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed");    //sets the types of columns
            noGrid.setColSorting("str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");  //sets the sorting types of columns
            noGrid.enableSmartRendering(true);
            noGrid.init();
			var from_date_value = ToolbarMain.getValue("from_date");
			var to_date_value = ToolbarMain.getValue("to_date");
            noGrid.load(RootDataPath+'report_no_new.php?from_date_value='+from_date_value+'&to_date_value='+to_date_value, function(){ //takes the path to your data feed							
            ToolbarMain.showItem("export");     
            // hide no all
            ToolbarMain.hideItem("export_all_no");
            dhxWinsReport.window("windowReport").progressOff();
            });
        }else{
            dhxWinsReport.window("windowReport").show();             
            ToolbarMain.showItem("export");
            ToolbarMain.hideItem("export_all_no");
        } 
    }
	
	
	var dhxWins;
    var viewNOGrid;
    function viewNO(){		
        if(!dhxWins){
            dhxWins= new dhtmlXWindows();// show window form to add length
        } 		
        if (!dhxWins.isWindow("windowViewNo")){
            // var win = myWins.createWindow(string id, int x, int y, int width, int height); // Creating New Window              
            windowViewNo = dhxWins.createWindow("windowViewNo", 493,65,829,665);	
			dhxWins.window("windowViewNo").progressOn();
            windowViewNo.setText("Window View NO");
            /*necessary to hide window instead of remove it*/
            windowViewNo.attachEvent("onClose", function(win){
                if (win.getId() == "windowViewNo") 
                    win.hide();
            });
            viewNOGrid = windowViewNo.attachGrid();
            viewNOGrid.enableSmartRendering(true);
			viewNOGrid.attachHeader("#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter");		
			viewNOGrid.enableMultiselect(true);				
            viewNOGrid.init();  
			var from_date_value = ToolbarMain.getValue("from_date");
			var to_date_value = ToolbarMain.getValue("to_date");
			viewNOGrid.load(RootDataPath+'view_no.php?from_date_value='+from_date_value+'&to_date_value='+to_date_value,function(){	
				dhxWins.window("windowViewNo").progressOff();
			});
        }else{
            dhxWins.window("windowViewNo").show(); 
        } 		
    }
	
    function initSoGrid(){
        SoGrid = LayoutMain.cells("a").attachGrid();
        SoGrid.setImagePath("./Module/dhtmlx/skins/skyblue/imgs/");
		// check init 
		SoGrid.setHeader(",NO.,SO LINE,QTY,ITEM,RBO,PD,REQ,ORDER,CUSTOMER_ITEM,MATERIAL CODE,MATERIAL NAME,MATERIAL SIZE,AGI,FOD,SCRAP,UOM COST");   //sets the headers of columns
		// SoGrid.attachHeader("#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#text_filter,,#text_filter,#text_filter");	//the method takes the columns' filters as a parameter
		SoGrid.setColumnIds(",NO.,SO LINE,QTY,ITEM,RBO,PD,REQ,ORDER,CUSTOMER_ITEM,MATERIAL CODE,MATERIAL NAME,MATERIAL SIZE,AGI,FOD,SCRAP,UOMCOST");         //sets the columns' ids
		SoGrid.setInitWidths("28,45,78,41,66,55,85,85,87,140,110,110,110,55,55,55,100");   //sets the initial widths of columns
		SoGrid.setColAlign("left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");     //sets the alignment of columns
		SoGrid.setColTypes("ch,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed");    //sets the types of columns
		SoGrid.setColSorting("na,na,na,na,na,na,na,na,na,na,na,na,na,na,na,na,na");
        SoGrid.init();
        SoGrid.attachEvent("onRowSelect", function(id,ind){ // Fire When user click on row in grid            
            console.log(id);
			return false;
        });     
        SoGrid.attachEvent("onCheck", function(rId,cInd,state){// fires after the state of a checkbox has been changed     
            processCheckSo(rId,cInd,state);
        });       
    }

    function delete_no(no){
		confirm_delete = confirm("Bạn có muốn XÓA "+no);
		if(confirm_delete){
			var url_delete = RootDataPath+'delete_no.php';
			$.ajax({
			url: url_delete,
				type: "POST",
				data: {data: no},
				dataType: "json",
				beforeSend: function(x) {
					if (x && x.overrideMimeType) {
					x.overrideMimeType("application/j-son;charset=UTF-8");
					}
				},
				success: function(result) {
					if(result.status){
						// reload
						viewNOGrid.forEachRow(function(id){							
							if(viewNOGrid.cells(id,1).getValue()===no){
								viewNOGrid.deleteRow(id);
							}
						});
					}else{
						alert('Có Lỗi trong quá trình XÓA '+no);
					}
				}
			});
		}else{
			
		}
	}
	var dataOH = [];
	function edit_oh(OH){
		// get all data from NO
		if(!input_OH){
			input_OH = ToolbarMain.getInput("OH");
		}
		input_OH.value=OH;
		getDataOH(OH);
	}	

	// @tandoan: Hàm tính running time 20200401
	function getRunningTime(machine_type, total_passed_2, total_time) {
		if (machine_type.toUpperCase() == 'ATMA') {
			machine_number = 400;
		} else if (machine_type.toUpperCase() == 'SAKURAI') {
			machine_number = 1000;
		} else if (machine_type.toUpperCase() == 'FAPL_MAY_NHO') {
			machine_number = 1000;
		} else if (machine_type.toUpperCase() == 'FAPL_MAY_LON') {
			machine_number = 1000;
		}

		return (((total_passed_2 / machine_number) * 60) + total_time) / 60;
	}
	var combobox;
	function getDataOH(OH){
		var url_save = RootDataPath+'get_data_oh.php?OH='+OH; 
		$.ajax({
		url: url_save,
			type: "POST",
			async: false,
			//data: {data: JSON.stringify(jsonObjects) },
			dataType: "json",
			beforeSend: function(x) {
				if (x && x.overrideMimeType) {
				x.overrideMimeType("application/j-son;charset=UTF-8");
				}
			},
			success: function(result) {
				if(result.status){
					dataOH = result.data;
					// load data So
					var	LIST_SO = dataOH.SO;
					var LIST_ITEM_ORACLE = dataOH.ITEM_ORACLE;
					var LIST_PROCESS = dataOH.PROCESS;
					var LIST_MATERIAL = dataOH.MATERIAL;
					var MAIN_ITEM = dataOH.MAIN_ITEM;
					var STT_SO = 0;
					var STT_ITEM_ORACLE = 0;
					var STT_PROCESS = 0;
					SoGrid.clearAll();					
					initItemGrid();
					initProcess();
					initMaterial();
					ItemGrid.clearAll();
					ProcessGrid.clearAll();
					MaterialGrid.clearAll();
					LayoutMain.cells("e").detachObject(); // reset
					for(var j=0;j<LIST_SO.length;j++){
						STT_SO++;
						var AGI = LIST_SO[j]['AGI'];
						var CUSTOMER_ITEM = LIST_SO[j]['CUSTOMER_ITEM'];
						var FOD = LIST_SO[j]['FOD'];
						var ITEM = LIST_SO[j]['ITEM'];
						var QTY = LIST_SO[j]['QTY'];
						var SO_LINE = LIST_SO[j]['SO_LINE'];
						var uniqueID = SoGrid.uid();
						var data_add = [1,STT_SO,SO_LINE,QTY,ITEM,'','','','',CUSTOMER_ITEM,'','','',AGI,FOD];						
						SoGrid.addRow(uniqueID,data_add);
					}
					for(var j=0;j<LIST_ITEM_ORACLE.length;j++){
						STT_ITEM_ORACLE++;
						var ORACLE_ITEM = LIST_ITEM_ORACLE[j]['ORACLE_ITEM'];
						var FOD = LIST_ITEM_ORACLE[j]['FOD'];
						var AGI = LIST_ITEM_ORACLE[j]['AGI'];
						var MATERIAL_CODE = LIST_ITEM_ORACLE[j]['MATERIAL_CODE'];
						var RBO = LIST_ITEM_ORACLE[j]['RBO'];
						var uniqueID = ItemGrid.uid();
						var data_add = [STT_ITEM_ORACLE,ORACLE_ITEM,AGI,FOD,'',RBO,MATERIAL_CODE];						
						ItemGrid.addRow(uniqueID,data_add);
					}
					for(var j=0;j<LIST_PROCESS.length;j++){
						STT_PROCESS++;
						var PRINTING_FOLLOW = LIST_PROCESS[j]['PRINTING_FOLLOW'];
						var PROCESS_1 = LIST_PROCESS[j]['PROCESS_1'];
						var PROCESS_2 = LIST_PROCESS[j]['PROCESS_2'];
						var PROCESS_3 = LIST_PROCESS[j]['PROCESS_3'];
						var PROCESS_4 = LIST_PROCESS[j]['PROCESS_4'];
						var PROCESS_5 = LIST_PROCESS[j]['PROCESS_5'];
						var PASSES = LIST_PROCESS[j]['PASSES'];
						var SCREEN = LIST_PROCESS[j]['SCREEN'];
						var TIME = LIST_PROCESS[j]['TIME'];
						var SETUP = LIST_PROCESS[j]['SETUP'];
						var uniqueID = ProcessGrid.uid();
						var data_add = [STT_PROCESS,PRINTING_FOLLOW,PROCESS_1,PROCESS_2,PROCESS_3,PROCESS_4,PROCESS_5,PASSES,SCREEN,TIME,SETUP];						
						ProcessGrid.addRow(uniqueID,data_add);
					}
					// setColLabel 
					for (var i=0; i<ItemGrid.getRowsNum();i++){
						ProcessGrid.setColLabel(i+2,ItemGrid.cellByIndex(i,1).getValue());
					}
					// GET MATERIAL
					//console.log(MAIN_ITEM);
					var uniqueID = MaterialGrid.uid();
					MATERIAL_CODE = MAIN_ITEM['MATERIAL_CODE'];
					MATERIAL_NAME = MAIN_ITEM['MATERIAL_NAME'];
					MATERIAL_SIZE = MAIN_ITEM['MATERIAL_SIZE'];
					var data_add = [MATERIAL_CODE,MATERIAL_NAME,MATERIAL_SIZE];
					MaterialGrid.addRow(uniqueID,data_add);
					combobox = MaterialGrid.getCombo(2);
					combobox.put('500*600','500*600');
					combobox.put('550*700','550*700');
					changeMaterialSize();
					// init NO
					SoForm = LayoutMain.cells("e").attachForm();
					SoForm.loadStruct(RootDataPath+'frm_no.php',function(){
						// add data
						for (var i=0; i<ItemGrid.getRowsNum();i++){
							if(CHECK_AGI==''){
								CHECK_AGI = ItemGrid.cellByIndex(i,2).getValue();
							}
							if(CHECK_FOD==''){
								CHECK_FOD = ItemGrid.cellByIndex(i,3).getValue();
							}
						}
						SoForm.setItemValue('JOB_NO',OH);
						SoForm.setItemValue('MACHINE_TYPE',MAIN_ITEM.MACHINE_TYPE);
						SoForm.setItemValue('PRINTING_TYPE',MAIN_ITEM.PRINTING_TYPE);
						SoForm.setItemValue('QTY',MAIN_ITEM.QTY);
						SoForm.setItemValue('PD',MAIN_ITEM.PD);
						SoForm.setItemValue('RBO',MAIN_ITEM.RBO);						
						SoForm.setItemValue('ITEM',MAIN_ITEM.ITEM);
						SoForm.setItemValue('CUSTOMER_ITEM',MAIN_ITEM.CUSTOMER_ITEM);
						SoForm.setItemValue('NUMBER_FILM',MAIN_ITEM.NUMBER_FILM);
						SoForm.setItemValue('TOTAL_PASSES_1',MAIN_ITEM.TOTAL_PASSES_1);
						SoForm.setItemValue('TOTAL_COLOUR',MAIN_ITEM.TOTAL_COLOUR);
						SoForm.setItemValue('LABEL_SIZE',MAIN_ITEM.LABEL_SIZE);
						SoForm.setItemValue('UPS',MAIN_ITEM.UPS);
						SoForm.setItemValue('TOTAL_TIME',MAIN_ITEM.TOTAL_TIME);
						SoForm.setItemValue('SHEET_BATCHING',MAIN_ITEM.SHEET_BATCHING);
						SoForm.setItemValue('ORGINAL_NEED',MAIN_ITEM.ORGINAL_NEED);
						SoForm.setItemValue('TOTAL_SETUP',MAIN_ITEM.TOTAL_SETUP);
						SoForm.setItemValue('PACKING',MAIN_ITEM.PACKING);
						SoForm.setItemValue('PRINTING',MAIN_ITEM.PRINTING);
						SoForm.setItemValue('SCRAP_DESIGN',MAIN_ITEM.SCRAP_DESIGN);
						SoForm.setItemValue('SCRAP_SETUP',MAIN_ITEM.SCRAP_SETUP);
						SoForm.setItemValue('SCRAP_ERROR',MAIN_ITEM.SCRAP_ERROR);
						SoForm.setItemValue('SCRAP_PRINTING',MAIN_ITEM.SCRAP_PRINTING);
						SoForm.setItemValue('TOTAL_SCRAP',MAIN_ITEM.TOTAL_SCRAP);
						SoForm.setItemValue('PAPER_COMPENSATE',MAIN_ITEM.PAPER_COMPENSATE);
						SoForm.setItemValue('TOTAL_SHEET',MAIN_ITEM.TOTAL_SHEET);
						SoForm.setItemValue('TOTAL_PASSES_2',MAIN_ITEM.TOTAL_PASSES_2);
						SoForm.setItemValue('TIME_RUNNING',MAIN_ITEM.TIME_RUNNING);
						SoForm.setItemValue('PLANNING_NAME',MAIN_ITEM.PLANNING_NAME);
						SO_UPS_CAL = MAIN_ITEM.UPS_CAL;
						SoForm.attachEvent("onChange", function (name, value, state){
							if(name=='MACHINE_TYPE'){
								var MACHINE_TYPE = SoForm.getItemValue('MACHINE_TYPE');
								// var SAKURAI = (MACHINE_TYPE=="SAKURAI")?1000:400;
								// TIME_RUNNING = ((SoForm.getItemValue('TOTAL_PASSES_2')/SAKURAI)*60+SoForm.getItemValue('TOTAL_TIME'))/60;

								// @tandoan: get Running time function 20200401
								TOTAL_PASSES_2 = SoForm.getItemValue('TOTAL_PASSES_2');
								TOTAL_TIME = SoForm.getItemValue('TOTAL_TIME');

								TIME_RUNNING = getRunningTime(MACHINE_TYPE, TOTAL_PASSES_2, TOTAL_TIME);
								console.log('1: TIME_RUNNING: '+ TIME_RUNNING);
								SoForm.setItemValue('TIME_RUNNING',TIME_RUNNING.toFixed(1));
							}
						});
						SoForm.attachEvent("onBlur", function(name){
							if(name=='UPS'){
								C22_TEXT = SoForm.getItemValue('UPS');
								if(C22_TEXT){
									C22_ARRAY = C22_TEXT.split("+");
									if(C22_TEXT.indexOf("*")!==-1){
										C22_VALUE = 0;
										for(var i=0;i<C22_ARRAY.length;i++){
											var C22_ARRAY_TEXT = C22_ARRAY[i].split("*");
											C22_VALUE+=Number(C22_ARRAY_TEXT[0])*Number(C22_ARRAY_TEXT[1]);
										}
									}else{				
										C22_VALUE = 1;
										for(var i=0;i<C22_ARRAY.length;i++){
											  C22_VALUE*=Number(C22_ARRAY[i]);
										}
									}						
									ORGINAL_NEED = SoForm.getItemValue('QTY')/C22_VALUE;
									SO_UPS_CAL = C22_VALUE;
									console.log("SO_UPS_CAL:"+SO_UPS_CAL);
									SoForm.setItemValue('ORGINAL_NEED',ORGINAL_NEED.toFixed(2)); // tandoan: làm tròn 2 chữ số
									SCRAP_SETUP = (SoForm.getItemValue('TOTAL_SETUP')/ORGINAL_NEED)*100;
									SoForm.setItemValue('SCRAP_SETUP',SCRAP_SETUP.toFixed(1));
								}					
							}				
						});
						INPUT_UPS = SoForm.getInput('UPS');
						INPUT_UPS.onkeypress = function(event){
							var keycode = (event.keyCode ? event.keyCode : event.which);
							if(keycode == '13'){
								C22_TEXT = SoForm.getItemValue('UPS');
								if(C22_TEXT){
									C22_ARRAY = C22_TEXT.split("+");
									if(C22_TEXT.indexOf("*")!==-1){
										C22_VALUE = 0;
										for(var i=0;i<C22_ARRAY.length;i++){
											var C22_ARRAY_TEXT = C22_ARRAY[i].split("*");
											C22_VALUE+=Number(C22_ARRAY_TEXT[0])*Number(C22_ARRAY_TEXT[1]);
										}
									}else{				
										C22_VALUE = 1;
										for(var i=0;i<C22_ARRAY.length;i++){
											  C22_VALUE*=Number(C22_ARRAY[i]);
										}
									}						
									ORGINAL_NEED = SoForm.getItemValue('QTY')/C22_VALUE;
									SO_UPS_CAL = C22_VALUE;
									SoForm.setItemValue('ORGINAL_NEED',ORGINAL_NEED.toFixed(2)); // @tandoan: lam tron 2 chu so
									SCRAP_SETUP = (SoForm.getItemValue('TOTAL_SETUP')/ORGINAL_NEED)*100;
									SoForm.setItemValue('SCRAP_SETUP',SCRAP_SETUP.toFixed(1));
								}
							}
						}
						SoForm.attachEvent("onBlur", function(name){
							if(name=='SHEET_BATCHING'){
								SHEET_BATCHING = Number(SoForm.getItemValue('SHEET_BATCHING'));
								if(SHEET_BATCHING){
									//TOTAL_SHEET = SHEET_BATCHING*(1+Number(SoForm.getItemValue('SCRAP_ERROR')/100))+Number(SoForm.getItemValue('TOTAL_SETUP'));
									TOTAL_SHEET = SHEET_BATCHING+(Number(SoForm.getItemValue('SCRAP_ERROR')/100)*SoForm.getItemValue('ORGINAL_NEED'))+Number(SoForm.getItemValue('TOTAL_SETUP'));
									SoForm.setItemValue('TOTAL_SHEET',TOTAL_SHEET.toFixed(0));
									PRINTING = Number(TOTAL_SHEET-SoForm.getItemValue('TOTAL_SETUP'));
									SoForm.setItemValue('PRINTING',PRINTING.toFixed(0));
									PACKING = Number(PRINTING-(SHEET_BATCHING*SoForm.getItemValue('SCRAP_ERROR')/100));
									PACKING = PACKING.toFixed(0);
									SoForm.setItemValue('PACKING',PACKING);
									SCRAP_DESIGN = ((SHEET_BATCHING-SoForm.getItemValue('ORGINAL_NEED'))/SoForm.getItemValue('ORGINAL_NEED'))*100;
									SoForm.setItemValue('SCRAP_DESIGN',SCRAP_DESIGN.toFixed(1));
									SCRAP_PRINTING = ((TOTAL_SHEET-PRINTING)/SoForm.getItemValue('ORGINAL_NEED'))*100;
									SoForm.setItemValue('SCRAP_PRINTING',SCRAP_PRINTING.toFixed(1));
									TOTAL_SCRAP = ((TOTAL_SHEET-SoForm.getItemValue('ORGINAL_NEED'))/SoForm.getItemValue('ORGINAL_NEED'))*100;
									SoForm.setItemValue('TOTAL_SCRAP',TOTAL_SCRAP.toFixed(1));
									PAPER_COMPENSATE = (TOTAL_SHEET-SHEET_BATCHING);
									SoForm.setItemValue('PAPER_COMPENSATE',PAPER_COMPENSATE.toFixed(0));
									TOTAL_PASSES_2 = (TOTAL_SHEET*SoForm.getItemValue('TOTAL_PASSES_1'));
									SoForm.setItemValue('TOTAL_PASSES_2',Math.ceil(TOTAL_PASSES_2));
									var MACHINE_TYPE = SoForm.getItemValue('MACHINE_TYPE');
									// var SAKURAI = (MACHINE_TYPE=="SAKURAI")?1000:400;
									// TIME_RUNNING = ((TOTAL_PASSES_2/SAKURAI)*60+SoForm.getItemValue('TOTAL_TIME'))/60;

									// @tandoan: get Running time function 20200401
									TOTAL_TIME = SoForm.getItemValue('TOTAL_TIME');
									TIME_RUNNING = getRunningTime(MACHINE_TYPE, TOTAL_PASSES_2, TOTAL_TIME);
									console.log('2: TIME_RUNNING: '+ TIME_RUNNING);

									SoForm.setItemValue('TIME_RUNNING',TIME_RUNNING.toFixed(1));
								}					
							}				
						});	
						INPUT_SHEET_BATCHING = SoForm.getInput('SHEET_BATCHING');
						INPUT_SHEET_BATCHING.onkeypress = function(event){var keycode = (event.keyCode ? event.keyCode : event.which);
							if(keycode == '13'){
								SHEET_BATCHING = Number(SoForm.getItemValue('SHEET_BATCHING'));
								if(SHEET_BATCHING){
									//TOTAL_SHEET = SHEET_BATCHING*(1+Number(SoForm.getItemValue('SCRAP_ERROR')/100))+Number(SoForm.getItemValue('TOTAL_SETUP'));
									TOTAL_SHEET = SHEET_BATCHING+(Number(SoForm.getItemValue('SCRAP_ERROR')/100)*SoForm.getItemValue('ORGINAL_NEED'))+Number(SoForm.getItemValue('TOTAL_SETUP'));
									SoForm.setItemValue('TOTAL_SHEET',TOTAL_SHEET.toFixed(0));
									PRINTING = Number(TOTAL_SHEET-SoForm.getItemValue('TOTAL_SETUP'));
									SoForm.setItemValue('PRINTING',PRINTING.toFixed(0));
									PACKING = Number(PRINTING-(SHEET_BATCHING*SoForm.getItemValue('SCRAP_ERROR')/100));
									PACKING = PACKING.toFixed(0);
									SoForm.setItemValue('PACKING',PACKING);
									SCRAP_DESIGN = ((SHEET_BATCHING-SoForm.getItemValue('ORGINAL_NEED'))/SoForm.getItemValue('ORGINAL_NEED'))*100;
									SoForm.setItemValue('SCRAP_DESIGN',SCRAP_DESIGN.toFixed(1));
									SCRAP_PRINTING = ((TOTAL_SHEET-PRINTING)/SoForm.getItemValue('ORGINAL_NEED'))*100;
									SoForm.setItemValue('SCRAP_PRINTING',SCRAP_PRINTING.toFixed(1));
									TOTAL_SCRAP = ((TOTAL_SHEET-SoForm.getItemValue('ORGINAL_NEED'))/SoForm.getItemValue('ORGINAL_NEED'))*100;
									SoForm.setItemValue('TOTAL_SCRAP',TOTAL_SCRAP.toFixed(1));
									PAPER_COMPENSATE = (TOTAL_SHEET-SHEET_BATCHING);
									SoForm.setItemValue('PAPER_COMPENSATE',PAPER_COMPENSATE.toFixed(0));
									TOTAL_PASSES_2 = (TOTAL_SHEET*SoForm.getItemValue('TOTAL_PASSES_1'));
									SoForm.setItemValue('TOTAL_PASSES_2',Math.ceil(TOTAL_PASSES_2));
									var MACHINE_TYPE = SoForm.getItemValue('MACHINE_TYPE');
									// var SAKURAI = (MACHINE_TYPE=="SAKURAI")?1000:400;
									// TIME_RUNNING = ((TOTAL_PASSES_2/SAKURAI)*60+SoForm.getItemValue('TOTAL_TIME'))/60;

									// @tandoan: get Running time function 20200401
									TOTAL_TIME = SoForm.getItemValue('TOTAL_TIME');
									TIME_RUNNING = getRunningTime(MACHINE_TYPE, TOTAL_PASSES_2, TOTAL_TIME);
									console.log('3: TIME_RUNNING: '+ TIME_RUNNING);

									SoForm.setItemValue('TIME_RUNNING',TIME_RUNNING.toFixed(1));
								}
							}
						}
						create_OH_exist = 1;
						// SHOW BUTTON SAVE
						ToolbarMain.showItem('saveNo');
						ToolbarMain.showItem('printNo');
						SoForm.enableLiveValidation(true);
						SoForm.validate();
					});
				}else{
					alert(result.mess);
				}				
			}
		});
	}
	
    function processCheckSo(rId,cInd,state){ 
        checked_SOLINE = []; // reset checked_SOLINE when filter
		checked_ITEM = [];
		for (var i=0; i<SoGrid.getRowsNum();i++){
			if(SoGrid.cellByIndex(i,0).getValue()=='1'){
				so_line = SoGrid.cellByIndex(i,2).getValue().trim();
				grid_id = SoGrid.getRowId(i);
				var obj = {so_line:so_line,grid_id:grid_id};
				checked_SOLINE.push(obj);
				var item_code = SoGrid.cellByIndex(i,4).getValue().trim();
				if(checked_ITEM.length<1||!inArray(item_code,checked_ITEM)){
					checked_ITEM.push(item_code);
				}
			}
		}
        if(checked_SOLINE.length){                
			initListItem();    
        }else{
            // reset 
           resetSO();
        }
    }

    function resetSO(){
		// reset form NO
		LayoutMain.cells("e").detachObject(); // reset
		ItemGrid.clearAll();
		MaterialGrid.clearAll();
    }
	
	var ProcessGrid;
	var CHECK_FOD = '';
	var CHECK_AGI = '';
	function initListProcess(){		
		getListProcess();
	}	
	var SO_UPS_CAL;
	var CUSTOMER_ARR = [];	
	function uniques(arr) {
		var a = [];
		for (var i=0, l=arr.length; i<l; i++)
			if (a.indexOf(arr[i]) === -1 && arr[i] !== '')
				a.push(arr[i]);
		return a;
	}	
	
	function initNO(){
		var MIN_DATE = new Date('18-Dec-9999');	
		var MIN_DATE_TEXT = '';
		var RBO = '';			
		var PRODUCT_TYPE = 'AGF';		
		var ITEM_TEXT = '';
		var CUSTOMER_ITEM = '';
		var NUMBER_FILM = 0;
		var TOTAL_PASSES_1 = 0;
		var TOTAL_COLOUR = 0;
		var LABEL_SIZE = '';
		var UPS = '';
		var TOTAL_TIME = 0;
		var TOTAL_SETUP = 1;
		var ORGINAL_NEED = 0;
		var SHEET_BATCHING = '';
		var PACKING = 0;
		var PRINTING = 0;
		var SCRAP_DESIGN = 0;
		var SCRAP_SETUP = 0;
		var SCRAP_PRINTING = 0;
		var TOTAL_SCRAP = '';
		var PAPER_COMPENSATE = '';
		var TOTAL_SHEET = '';
		var TOTAL_PASSES_2 = '';
		var TIME_RUNNING = '';
		var PLANNING_NAME = '';
		var SCRAP_ERROR = 0;
		SO_UPS_CAL = 0;
		SoForm = LayoutMain.cells("e").attachForm();		
        SoForm.loadStruct(RootDataPath+'frm_no.php',function(){
			var QTY = 0;
			SoForm.getInput('LABEL_SIZE').focus();
			// init data to form
			// set PD
			//SCRAP_ERROR = ItemGrid.cellByIndex(0,4).getValue();
			for (var i=0; i<SoGrid.getRowsNum();i++){		
				if(SoGrid.cellByIndex(i,0).getValue()=='1'){	
					var checkDateTmp = SoGrid.cellByIndex(i,7).getValue();
					var checkDate = new Date(checkDateTmp);
					if(MIN_DATE>checkDate){
						MIN_DATE = checkDate;
						MIN_DATE_TEXT = checkDateTmp;
					}				
					
					//console.log(Number(SoGrid.cellByIndex(i,3).getValue()));
					QTY+=Number(SoGrid.cellByIndex(i,3).getValue());
					if(SoGrid.cellByIndex(i,9).getValue()){
						CUSTOMER_ARR.push(SoGrid.cellByIndex(i,9).getValue());
					}
				}
			}			
			//console.log(QTY);
			// CHECK AGI
			for (var i=0; i<ItemGrid.getRowsNum();i++){
				if(CHECK_AGI==''){
					CHECK_AGI = ItemGrid.cellByIndex(i,2).getValue();
				}
				if(CHECK_FOD==''){
					CHECK_FOD = ItemGrid.cellByIndex(i,3).getValue();
				}
				if(SCRAP_ERROR<Number(ItemGrid.cellByIndex(i,4).getValue())){
					SCRAP_ERROR = ItemGrid.cellByIndex(i,4).getValue();
				}
			}
			for (var i=0; i<SoGrid.getRowsNum();i++){		
				if(SoGrid.cellByIndex(i,0).getValue()=='1'){
					ITEM_TEXT = ItemGrid.cellByIndex(i,1).getValue();
					CUSTOMER_ITEM = SoGrid.cellByIndex(i,9).getValue();
					RBO = SoGrid.cellByIndex(i,5).getValue();
					break;
				}
			}
			for (var i=0; i<ProcessGrid.getRowsNum();i++){					
				if(ProcessGrid.cellByIndex(i,7).getValue()>0){
					TOTAL_PASSES_1+= Number(ProcessGrid.cellByIndex(i,7).getValue());
				}				
				if(ProcessGrid.cellByIndex(i,9).getValue()>0){
					TOTAL_TIME+= Number(ProcessGrid.cellByIndex(i,9).getValue());
				}
				if(ProcessGrid.cellByIndex(i,11).getValue().indexOf("lop muc")!==-1){
					TOTAL_COLOUR+= 1;
				}
				if(ProcessGrid.cellByIndex(i,10).getValue()>0){
					TOTAL_SETUP+= Number(ProcessGrid.cellByIndex(i,10).getValue());
				}
			}
			var ITEM_ARRAY_TMP = [];
			for (var i=0; i<ItemGrid.getRowsNum();i++){
				if(ItemGrid.cellByIndex(i,1).getValue()){
					ITEM_ARRAY_TMP.push(ItemGrid.cellByIndex(i,1).getValue());
				}
			}
			ITEM_ARRAY_TMP = uniques(ITEM_ARRAY_TMP);
			if(ITEM_ARRAY_TMP.length>1){
				ITEM_TEXT = 'Item Gộp';
			}
			CUSTOMER_ARR = uniques(CUSTOMER_ARR);
			if(CUSTOMER_ARR.length>1){
				CUSTOMER_ITEM = 'Item Gộp';
			}
			// set PD			
			SoForm.setItemValue('PD',MIN_DATE_TEXT);
			// set AGI
			if(CHECK_AGI==='AGI'){
				PRODUCT_TYPE = 'AGI';
			}
			//QTY = 21360;
			NUMBER_FILM = ProcessGrid.getRowsNum();
			SoForm.attachEvent("onBlur", function(name){
				if(name=='UPS'){
					C22_TEXT = SoForm.getItemValue('UPS');
					if(C22_TEXT){
						C22_ARRAY = C22_TEXT.split("+");
						if(C22_TEXT.indexOf("*")!==-1){
							C22_VALUE = 0;
							for(var i=0;i<C22_ARRAY.length;i++){
								var C22_ARRAY_TEXT = C22_ARRAY[i].split("*");
								C22_VALUE+=Number(C22_ARRAY_TEXT[0])*Number(C22_ARRAY_TEXT[1]);
							}
						}else{				
							C22_VALUE = 1;
							for(var i=0;i<C22_ARRAY.length;i++){
								  C22_VALUE*=Number(C22_ARRAY[i]);
							}
						}						
						ORGINAL_NEED = QTY/C22_VALUE;
						SO_UPS_CAL = C22_VALUE;
						SoForm.setItemValue('ORGINAL_NEED',ORGINAL_NEED.toFixed(2)); // @tandoan: lam tron 2 chu so
						SCRAP_SETUP = (TOTAL_SETUP/ORGINAL_NEED)*100;
						SoForm.setItemValue('SCRAP_SETUP',SCRAP_SETUP.toFixed(1));
					}					
				}				
			});
			INPUT_UPS = SoForm.getInput('UPS');
			INPUT_UPS.onkeypress = function(event){
				var keycode = (event.keyCode ? event.keyCode : event.which);
				if(keycode == '13'){
					C22_TEXT = SoForm.getItemValue('UPS');
					if(C22_TEXT){
						C22_ARRAY = C22_TEXT.split("+");
						if(C22_TEXT.indexOf("*")!==-1){
							C22_VALUE = 0;
							for(var i=0;i<C22_ARRAY.length;i++){
								var C22_ARRAY_TEXT = C22_ARRAY[i].split("*");
								C22_VALUE+=Number(C22_ARRAY_TEXT[0])*Number(C22_ARRAY_TEXT[1]);
							}
						}else{				
							C22_VALUE = 1;
							for(var i=0;i<C22_ARRAY.length;i++){
								  C22_VALUE*=Number(C22_ARRAY[i]);
							}
						}						
						ORGINAL_NEED = QTY/C22_VALUE;
						SO_UPS_CAL = C22_VALUE;
						SoForm.setItemValue('ORGINAL_NEED',ORGINAL_NEED.toFixed(2));
						SCRAP_SETUP = (TOTAL_SETUP/ORGINAL_NEED)*100;
						SoForm.setItemValue('SCRAP_SETUP',SCRAP_SETUP.toFixed(1));
					}
				}
			}
			SoForm.attachEvent("onBlur", function(name){
				if(name=='SHEET_BATCHING'){
					SHEET_BATCHING = Number(SoForm.getItemValue('SHEET_BATCHING'));
					if(SHEET_BATCHING){
						TOTAL_SHEET = SHEET_BATCHING*(1+Number(SCRAP_ERROR/100))+Number(TOTAL_SETUP);
						SoForm.setItemValue('TOTAL_SHEET',TOTAL_SHEET.toFixed(0));
						PRINTING = Number(TOTAL_SHEET-TOTAL_SETUP);
						SoForm.setItemValue('PRINTING',PRINTING.toFixed(0));
						PACKING = Number(PRINTING-(SHEET_BATCHING*SCRAP_ERROR/100));
						PACKING = PACKING.toFixed(0);
						SoForm.setItemValue('PACKING',PACKING);
						SCRAP_DESIGN = ((SHEET_BATCHING-ORGINAL_NEED)/ORGINAL_NEED)*100;
						SoForm.setItemValue('SCRAP_DESIGN',SCRAP_DESIGN.toFixed(1));
						SCRAP_PRINTING = ((TOTAL_SHEET-PRINTING)/ORGINAL_NEED)*100;
						SoForm.setItemValue('SCRAP_PRINTING',SCRAP_PRINTING.toFixed(1));
						TOTAL_SCRAP = ((TOTAL_SHEET-ORGINAL_NEED)/ORGINAL_NEED)*100;
						SoForm.setItemValue('TOTAL_SCRAP',TOTAL_SCRAP.toFixed(1));
						PAPER_COMPENSATE = (TOTAL_SHEET-SHEET_BATCHING);
						SoForm.setItemValue('PAPER_COMPENSATE',PAPER_COMPENSATE.toFixed(0));
						TOTAL_PASSES_2 = (TOTAL_SHEET*TOTAL_PASSES_1);
						SoForm.setItemValue('TOTAL_PASSES_2',Math.ceil(TOTAL_PASSES_2));
						var MACHINE_TYPE = SoForm.getItemValue('MACHINE_TYPE');
						// var SAKURAI = (MACHINE_TYPE=="SAKURAI")?1000:400;
						// TIME_RUNNING = ((TOTAL_PASSES_2/SAKURAI)*60+TOTAL_TIME)/60;

						// @tandoan: get Running time function 20200401
						TIME_RUNNING = getRunningTime(MACHINE_TYPE, TOTAL_PASSES_2, TOTAL_TIME );
						console.log('4: TIME_RUNNING: '+ TIME_RUNNING);

						SoForm.setItemValue('TIME_RUNNING',TIME_RUNNING.toFixed(1));
					}					
				}				
			});	
			INPUT_SHEET_BATCHING = SoForm.getInput('SHEET_BATCHING');
			INPUT_SHEET_BATCHING.onkeypress = function(event){var keycode = (event.keyCode ? event.keyCode : event.which);
				if(keycode == '13'){
					SHEET_BATCHING = Number(SoForm.getItemValue('SHEET_BATCHING'));
					if(SHEET_BATCHING){
						TOTAL_SHEET = SHEET_BATCHING*(1+Number(SCRAP_ERROR/100))+Number(TOTAL_SETUP);
						SoForm.setItemValue('TOTAL_SHEET',TOTAL_SHEET.toFixed(0));
						PRINTING = Number(TOTAL_SHEET-TOTAL_SETUP);
						SoForm.setItemValue('PRINTING',PRINTING.toFixed(0));
						PACKING = Number(PRINTING-(SHEET_BATCHING*SCRAP_ERROR/100));
						PACKING = PACKING.toFixed(0);
						SoForm.setItemValue('PACKING',PACKING);
						SCRAP_DESIGN = ((SHEET_BATCHING-ORGINAL_NEED)/ORGINAL_NEED)*100;
						SoForm.setItemValue('SCRAP_DESIGN',SCRAP_DESIGN.toFixed(1));
						SCRAP_PRINTING = ((TOTAL_SHEET-PRINTING)/ORGINAL_NEED)*100;
						SoForm.setItemValue('SCRAP_PRINTING',SCRAP_PRINTING.toFixed(1));
						TOTAL_SCRAP = ((TOTAL_SHEET-ORGINAL_NEED)/ORGINAL_NEED)*100;
						SoForm.setItemValue('TOTAL_SCRAP',TOTAL_SCRAP.toFixed(1));
						PAPER_COMPENSATE = (TOTAL_SHEET-SHEET_BATCHING);
						SoForm.setItemValue('PAPER_COMPENSATE',PAPER_COMPENSATE.toFixed(0));
						TOTAL_PASSES_2 = (TOTAL_SHEET*TOTAL_PASSES_1);
						SoForm.setItemValue('TOTAL_PASSES_2',Math.ceil(TOTAL_PASSES_2));
						var MACHINE_TYPE = SoForm.getItemValue('MACHINE_TYPE');
						// var SAKURAI = (MACHINE_TYPE=="SAKURAI")?1000:400;
						// TIME_RUNNING = ((TOTAL_PASSES_2/SAKURAI)*60+TOTAL_TIME)/60;

						// @tandoan: get Running time function 20200401

						TIME_RUNNING = getRunningTime(MACHINE_TYPE, TOTAL_PASSES_2, TOTAL_TIME );
						console.log('5: TIME_RUNNING: '+ TIME_RUNNING);

						SoForm.setItemValue('TIME_RUNNING',TIME_RUNNING.toFixed(1));
					}
				}
			}
			SoForm.attachEvent("onBlur", function(name){
				if(name=='LABEL_SIZE'){
					SoForm.getInput('UPS').focus();
				}				
			});
			SoForm.attachEvent("onChange", function (name, value, state){
				if(name=='MACHINE_TYPE'){
					var MACHINE_TYPE = SoForm.getItemValue('MACHINE_TYPE');
					// var SAKURAI = (MACHINE_TYPE=="SAKURAI")?1000:400;
					// TIME_RUNNING = ((TOTAL_PASSES_2/SAKURAI)*60+TOTAL_TIME)/60;

					// @tandoan: get Running time function 20200401
					TIME_RUNNING = getRunningTime(MACHINE_TYPE, TOTAL_PASSES_2, TOTAL_TIME );
					console.log('6: TIME_RUNNING: '+ TIME_RUNNING);

					SoForm.setItemValue('TIME_RUNNING',TIME_RUNNING.toFixed(1));
				}
				// your code here
			});
			// set AGI
			SoForm.setItemValue('PRINTING_TYPE',PRODUCT_TYPE);
			SoForm.setItemValue('QTY',QTY);
			// set RBO
			SoForm.setItemValue('RBO',RBO);
			SoForm.setItemValue('ITEM',ITEM_TEXT);
			SoForm.setItemValue('CUSTOMER_ITEM',CUSTOMER_ITEM);
			SoForm.setItemValue('NUMBER_FILM',NUMBER_FILM);
			SoForm.setItemValue('TOTAL_PASSES_1',TOTAL_PASSES_1);
			SoForm.setItemValue('TOTAL_COLOUR',TOTAL_COLOUR);
			SoForm.setItemValue('LABEL_SIZE',LABEL_SIZE);
			SoForm.setItemValue('UPS',UPS);
			SoForm.setItemValue('TOTAL_TIME',TOTAL_TIME);
			SoForm.setItemValue('TOTAL_SETUP',TOTAL_SETUP);	
			SoForm.setItemValue('SHEET_BATCHING',SHEET_BATCHING);
			SoForm.setItemValue('PACKING',PACKING);
			SoForm.setItemValue('SCRAP_ERROR',SCRAP_ERROR);
			SoForm.setItemValue('TIME_RUNNING',TIME_RUNNING);
			SoForm.setItemValue('JOB_NO',input_OH.value);			
			// SHOW BUTTON SAVE
			ToolbarMain.showItem('saveNo');
			ToolbarMain.showItem('printNo');
			SoForm.enableLiveValidation(true);
			if(user_name=='phuongdung.pham'){
				$(".select_planing .dhxform_select").prop('selectedIndex',0);
			}else if(user_name=='khoa.huynh'){
				$(".select_planing .dhxform_select").prop('selectedIndex',1);
			}else if(user_name=='long.dang'){
				$(".select_planing .dhxform_select").prop('selectedIndex',2);
			}else if(user_name=='quyen.tk.nguyen'){
				$(".select_planing .dhxform_select").prop('selectedIndex',3);
			}else if(user_name=='nam.nguyen'){
				$(".select_planing .dhxform_select").prop('selectedIndex',4);
			}else if(user_name=='ly.tran'){
				$(".select_planing .dhxform_select").prop('selectedIndex',5);
			}else if(user_name=='tu.ngo'){
				$(".select_planing .dhxform_select").prop('selectedIndex',6);
			}
			else{
				$(".select_planing .dhxform_select").prop('selectedIndex',0);
			}			
			SoForm.validate();			
		});		
	}

	function saveDatabase(print){
		// stop edit cell
		SoGrid.editStop();
		ItemGrid.editStop();
		MaterialGrid.editStop();
		ProcessGrid.editStop();	
		save = 'SAVE';
		if(print){
			save = 'PRINT';
		}     
		if(ItemGrid.getRowsNum()){
			var ITEM_CODE = ItemGrid.cellByIndex(0,1).getValue();
			if(!ITEM_CODE){
				alert("Vui lòng nhập ITEM ORACLE để "+save+"!!!");                               
				return false;
			}
		}else{
			alert("Vui lòng nhập ITEM ORACLE để "+save+"!!!");                                
			return false;
		}
		if(ProcessGrid.getRowsNum()){
			var PROCESS_CODE = ProcessGrid.cellByIndex(0,1).getValue();
			if(!PROCESS_CODE){
				alert("Vui lòng nhập TIẾN TRÌNH IN để "+save+"!!!");                                
				return false;
			}
		}else{
			alert("Vui lòng nhập TIẾN TRÌNH IN để "+save+"!!!");                                
			return false;
		}
		if(MaterialGrid.getRowsNum()){
			var MATERIAL_CODE = MaterialGrid.cellByIndex(0,0).getValue();
			if(!MATERIAL_CODE){
				alert("Vui lòng nhập MATERIAL để "+save+"!!!");                                
				return false;
			}
		}else{
			alert("Vui lòng nhập MATERIAL để "+save+"!!!");                                
			return false;
		}
		save_so = [];
		obj_so = {};
		NUMBER_SO=0;
		var sum_qty_so = 0;
		for (var i=0;i<SoGrid.getRowsNum();i++){
			if(SoGrid.cellByIndex(i,0).getValue()=='1'){
				NUMBER_SO++;
				var SO_LINE = SoGrid.cellByIndex(i,2).getValue();
				var SO_LINE_QTY = SoGrid.cellByIndex(i,3).getValue();
				var ITEM = SoGrid.cellByIndex(i,4).getValue();
				var CUSTOMER_ITEM = SoGrid.cellByIndex(i,9).getValue();
				var AGI = SoGrid.cellByIndex(i,13).getValue();
				var FOD = SoGrid.cellByIndex(i,14).getValue();
				var RBO_SO = SoGrid.cellByIndex(i,5).getValue();
				var UOM = SoGrid.cellByIndex(i,16).getValue();
				sum_qty_so+=Number(SO_LINE_QTY);
				obj_so = {
					SO_LINE					:	SO_LINE.trim(),
					SO_LINE_QTY				:	SO_LINE_QTY.trim(),
					ITEM					:	ITEM.trim(),
					CUSTOMER_ITEM			:	CUSTOMER_ITEM.trim(),
					AGI						:	AGI,
					FOD						:	FOD,
					RBO_SO					:	RBO_SO,		
					UOM						: 	UOM
				};
				save_so.push(obj_so);
			}			               
		}
		// end so
		save_item_oracle = [];
		obj_item_oracle = {};		
		for (var i=0; i<ItemGrid.getRowsNum(); i++){
			var ITEM_CODE = ItemGrid.cellByIndex(i,1).getValue();
			var AGI = ItemGrid.cellByIndex(i,2).getValue();
			var FOD = ItemGrid.cellByIndex(i,3).getValue();
			var RBO = ItemGrid.cellByIndex(i,5).getValue();
			var MATERIAL_CODE = ItemGrid.cellByIndex(i,6).getValue();
			if(ITEM_CODE){
				obj_item_oracle = {
				ITEM_CODE				:	ITEM_CODE.trim(),
				AGI						:	AGI.trim(),
				FOD						:	FOD.trim(),
				RBO						:	RBO.trim(),
				MATERIAL_CODE			:	MATERIAL_CODE.trim(),
				};
				save_item_oracle.push(obj_item_oracle);
			}		               
		}
		// end ITEM ORACLE
		save_process = [];
		obj_process = {};
		for (var i=0; i<ProcessGrid.getRowsNum(); i++){
			var PRINTING_FOLLOW = ProcessGrid.cellByIndex(i,1).getValue();
			var	PROCESS_1 		= ProcessGrid.cellByIndex(i,2).getValue();
			if(PROCESS_1.indexOf("*")!==-1){
				var PROCESS_ARR = PROCESS_1.split("*");
				if(PROCESS_ARR.length==2){
					PROCESS_1 = PROCESS_ARR[0];
				}				
			}
			var PROCESS_2 		= ProcessGrid.cellByIndex(i,3).getValue();
			if(PROCESS_2.indexOf("*")!==-1){
				var PROCESS_ARR = PROCESS_2.split("*");
				if(PROCESS_ARR.length==2){
					PROCESS_2 = PROCESS_ARR[0];
				}				
			}
			var PROCESS_3 		= ProcessGrid.cellByIndex(i,4).getValue();
			if(PROCESS_3.indexOf("*")!==-1){
				var PROCESS_ARR = PROCESS_3.split("*");
				if(PROCESS_ARR.length==2){
					PROCESS_3 = PROCESS_ARR[0];
				}				
			}
			var PROCESS_4 		= ProcessGrid.cellByIndex(i,5).getValue();
			if(PROCESS_4.indexOf("*")!==-1){
				var PROCESS_ARR = PROCESS_4.split("*");
				if(PROCESS_ARR.length==2){
					PROCESS_4 = PROCESS_ARR[0];
				}				
			}
			var PROCESS_5 		= ProcessGrid.cellByIndex(i,6).getValue();
			if(PROCESS_5.indexOf("*")!==-1){
				var PROCESS_ARR = PROCESS_5.split("*");
				if(PROCESS_ARR.length==2){
					PROCESS_5 = PROCESS_ARR[0];
				}				
			}
			var PASSES 			= ProcessGrid.cellByIndex(i,7).getValue();
			var SCREEN 			= ProcessGrid.cellByIndex(i,8).getValue();
			var TIME 			= ProcessGrid.cellByIndex(i,9).getValue();
			var SHEET 			= ProcessGrid.cellByIndex(i,10).getValue();
			if(PRINTING_FOLLOW){
				obj_process = {
					PRINTING_FOLLOW		:	PRINTING_FOLLOW.trim(),
					PROCESS_1			:	PROCESS_1.trim(),
					PROCESS_2			:	PROCESS_2.trim(),
					PROCESS_3			:	PROCESS_3.trim(),
					PROCESS_4			:	PROCESS_4.trim(),
					PROCESS_5			:	PROCESS_5.trim(),
					PASSES				:	PASSES.trim(),
					SCREEN				:	SCREEN.trim(),
					TIME				:	TIME.trim(),
					SHEET				:	SHEET.trim(),
				};
				save_process.push(obj_process);
			}					               
		}
		MATERIAL_CODE = MaterialGrid.cellByIndex(0,0).getValue();
		MATERIAL_CODE = MATERIAL_CODE.trim();
		MATERIAL_NAME = MaterialGrid.cellByIndex(0,1).getValue();
		MATERIAL_NAME = MATERIAL_NAME.trim();
		MATERIAL_SIZE = MaterialGrid.cellByIndex(0,2).getValue();
		MATERIAL_SIZE = MATERIAL_SIZE.trim();
		AGI = CHECK_AGI;
		AGI = AGI.trim();
		FOD = CHECK_FOD;
		FOD = FOD.trim();
		CREATED_DATE = SoForm.getItemValue('CREATED_DATE');
		CREATED_DATE = CREATED_DATE.trim();
		MACHINE_TYPE = SoForm.getItemValue('MACHINE_TYPE');
		MACHINE_TYPE = MACHINE_TYPE.trim();
		JOB_NO = SoForm.getItemValue('JOB_NO');
		JOB_NO = JOB_NO.trim();
		PD = SoForm.getItemValue('PD');
		PD = PD.trim();
		PRINTING_TYPE = SoForm.getItemValue('PRINTING_TYPE');
		PRINTING_TYPE = PRINTING_TYPE.trim();
		QTY = SoForm.getItemValue('QTY').toString();
		QTY = QTY.trim();
		if(QTY!=sum_qty_so){
			alert("TỔNG SỐ LƯỢNG KHÔNG BẰNG NHAU VUI LÒNG KIỂM TRA!!!");
			return false;
		}
		RBO = SoForm.getItemValue('RBO');
		RBO = RBO.trim();
		ITEM = SoForm.getItemValue('ITEM');
		ITEM = ITEM.trim();
		CUSTOMER_ITEM = SoForm.getItemValue('CUSTOMER_ITEM');
		CUSTOMER_ITEM = CUSTOMER_ITEM.trim();
		NUMBER_FILM = SoForm.getItemValue('NUMBER_FILM').toString();
		NUMBER_FILM = NUMBER_FILM.trim();
		TOTAL_PASSES_1 = SoForm.getItemValue('TOTAL_PASSES_1').toString();
		TOTAL_PASSES_1 = TOTAL_PASSES_1.trim();
		TOTAL_COLOUR = SoForm.getItemValue('TOTAL_COLOUR').toString();
		TOTAL_COLOUR = TOTAL_COLOUR.trim();
		LABEL_SIZE = SoForm.getItemValue('LABEL_SIZE');
		LABEL_SIZE = LABEL_SIZE.trim();
		UPS = SoForm.getItemValue('UPS').toString();
		UPS = UPS.trim();
		UPS_CAL = SO_UPS_CAL;
		TOTAL_TIME = SoForm.getItemValue('TOTAL_TIME');
		SHEET_BATCHING = SoForm.getItemValue('SHEET_BATCHING');
		ORGINAL_NEED = SoForm.getItemValue('ORGINAL_NEED');
		TOTAL_SETUP = SoForm.getItemValue('TOTAL_SETUP');
		PACKING = SoForm.getItemValue('PACKING');
		PRINTING = SoForm.getItemValue('PRINTING');
		SCRAP_DESIGN = SoForm.getItemValue('SCRAP_DESIGN');
		SCRAP_SETUP = SoForm.getItemValue('SCRAP_SETUP');
		SCRAP_ERROR = SoForm.getItemValue('SCRAP_ERROR');
		SCRAP_PRINTING = SoForm.getItemValue('SCRAP_PRINTING');
		TOTAL_SCRAP = SoForm.getItemValue('TOTAL_SCRAP');
		PAPER_COMPENSATE = SoForm.getItemValue('PAPER_COMPENSATE');
		TOTAL_SHEET = SoForm.getItemValue('TOTAL_SHEET');
		TOTAL_PASSES_2 = SoForm.getItemValue('TOTAL_PASSES_2');
		TIME_RUNNING = SoForm.getItemValue('TIME_RUNNING');
		PLANNING_NAME = SoForm.getItemValue('PLANNING_NAME');
		NUMBER_SO = NUMBER_SO;	
		NUMBER_ITEM = ItemGrid.getRowsNum();
		NUMBER_SCREEN = ProcessGrid.getRowsNum();
		save_item ={AGI:AGI,FOD:FOD,CREATED_DATE:CREATED_DATE,MACHINE_TYPE:MACHINE_TYPE,JOB_NO:JOB_NO,PD:PD,PRINTING_TYPE:PRINTING_TYPE,QTY:QTY,RBO:RBO,ITEM:ITEM,CUSTOMER_ITEM:CUSTOMER_ITEM,NUMBER_FILM:NUMBER_FILM,TOTAL_PASSES_1:TOTAL_PASSES_1,TOTAL_COLOUR:TOTAL_COLOUR,LABEL_SIZE:LABEL_SIZE,UPS:UPS,UPS_CAL:UPS_CAL,TOTAL_TIME:TOTAL_TIME,SHEET_BATCHING:SHEET_BATCHING,ORGINAL_NEED:ORGINAL_NEED,TOTAL_SETUP:TOTAL_SETUP,PACKING:PACKING,PRINTING:PRINTING,SCRAP_DESIGN:SCRAP_DESIGN,SCRAP_SETUP:SCRAP_SETUP,SCRAP_ERROR:SCRAP_ERROR,SCRAP_PRINTING:SCRAP_PRINTING,TOTAL_SCRAP:TOTAL_SCRAP,PAPER_COMPENSATE:PAPER_COMPENSATE,TOTAL_SHEET:TOTAL_SHEET,TOTAL_PASSES_2:TOTAL_PASSES_2,TIME_RUNNING:TIME_RUNNING,PLANNING_NAME:PLANNING_NAME,NUMBER_SO:NUMBER_SO,NUMBER_ITEM:NUMBER_ITEM,MATERIAL_CODE:MATERIAL_CODE,MATERIAL_NAME:MATERIAL_NAME,MATERIAL_SIZE:MATERIAL_SIZE,create_OH_exist:create_OH_exist,NUMBER_SCREEN:NUMBER_SCREEN};    
		var jsonObjects = {
			"save_item": save_item,
			"save_so":save_so,
			"save_item_oracle":save_item_oracle,
			"save_process":save_process,
		}; 
		
		/*
		console.log(jsonObjects);
		return false;
		*/
		var url_save = RootDataPath+'save_item.php'; 
		$.ajax({
		url: url_save,
			type: "POST",
			data: {data: JSON.stringify(jsonObjects) },
			dataType: "json",
			beforeSend: function(x) {
				if (x && x.overrideMimeType) {
				x.overrideMimeType("application/j-son;charset=UTF-8");
				}
			},
			success: function(result) {
				if(result.status){
					alert("Save DATA Thành Công!!!");
					location.reload();
					if(print){
						var wi = window.open('about:blank', '_blank');
						//$(wi.document.body).html("<p>Please wait while you are being redirected...</p>");
						link = RootPath+'print.php?id='+encodeURIComponent(result.JOB_NO);
						wi.location.href = link;
					}                                
				}else{
					alert(result.mess);
					//location.reload();
				}
			}
		});    
    }
	
	function initProcess(){
		ProcessGrid = LayoutMain.cells("c").attachGrid();
		ProcessGrid.setImagePath("./Module/dhtmlx/skins/skyblue/imgs/");
		// check init 
		ProcessGrid.setHeader("NO.,PRINTING,,,,,,PASSES,SCREEN,TIME,SHEET,LOP");   //sets the headers of columns
		// SoGrid.attachHeader("#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#text_filter,,#text_filter,#text_filter");	//the method takes the columns' filters as a parameter
		ProcessGrid.setColumnIds("NO.,PRINTING,,,,,,PASSES,SCREEN,TIME,SHEET,LOP");         //sets the columns' ids
		ProcessGrid.setInitWidths("35,85,95,100,100,100,90,60,60,50,53,85");   //sets the initial widths of columns
		ProcessGrid.setColAlign("left,left,left,left,left,left,left,left,left,left,left,left");     //sets the alignment of columns
		ProcessGrid.setColTypes("ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed,ed");    //sets the types of columns
		ProcessGrid.setColSorting("str,na,na,na,na,na,na,na,na,na,na,na");
		ProcessGrid.init();
	}
	
	function getListProcess(){		
		var url_get_process = RootDataPath+'get_process.php'; 
		$.ajax({
		url: url_get_process,
			type: "POST",
			data: {data: checked_ITEM},
			dataType: "json",
			beforeSend: function(x) {
				if (x && x.overrideMimeType) {
					x.overrideMimeType("application/j-son;charset=UTF-8");
				}
			},
			success: function(result) {
				if(result.status){ // TRUE
					initProcess();
					if(result.data.length>0){
						STT = 0;
						for(var i=0;i<result.data.length;i++){	
							STT++;
							// add row							
							PRINTING_FOLLOW = result.data[i].LOP;;
							PROCESS_1 = result.data[i].PROCESS_1;
							PROCESS_2 = result.data[i].PROCESS_2;
							PROCESS_3 = result.data[i].PROCESS_3;
							PROCESS_4 = result.data[i].PROCESS_4;
							PROCESS_5 = result.data[i].PROCESS_5;
							PROCESS_6 = result.data[i].PROCESS_6;
							PROCESS_7 = result.data[i].PROCESS_7;
							PASSES = result.data[i].PASS;
							SCREEN = result.data[i].KHUNG;
							TIME = result.data[i].TIME;
							SHEET = result.data[i].SETUP;
							LOP = result.data[i].LOP_FORMAT;
							var uniqueID = ProcessGrid.uid();
							var data_add = [STT,PRINTING_FOLLOW,PROCESS_1,PROCESS_2,PROCESS_3,PROCESS_4,PROCESS_5,PASSES,SCREEN,TIME,SHEET,LOP];							
							ProcessGrid.addRow(uniqueID,data_add);
						}		
						ProcessGrid.attachEvent("onEnter", function(id,ind){
							// resorting STT
							if(ind==0){
								ProcessGrid.sortRows(0,"str", "asc"); // sorts grid
							}
						});
						// setColLabel 
						for (var i=0; i<ItemGrid.getRowsNum();i++){
							if(ItemGrid.cellByIndex(i,1).getValue())
							ProcessGrid.setColLabel(i+2,ItemGrid.cellByIndex(i,1).getValue());
						}
						for (var i=0; i<2;i++){
							var uniqueID = ProcessGrid.uid();
							var data_add = ['','','','','','','','','','','',''];							
							ProcessGrid.addRow(uniqueID,data_add);
						}
						// init NO
						initNO();
						// change QTY SO LINE
						
					}
				}else{
					alert(result.mess);
				}
			}
		});
	}
	
	function changeQtySo(){
		SoGrid.attachEvent("onEnter", function(id,ind){
			// change qty
			if(ind==3){
				if(!SoForm.getItemValue('LABEL_SIZE')||!SoForm.getItemValue('UPS')||!SoForm.getItemValue('SHEET_BATCHING')){
					alert('Vui lòng nhập thông tin: LABEL SIZE, UPS, SHEET BATCHING!!!');
				}else{
					updateQty();
				}				
			}			
		});
	}
	
	function updateQty(){
		var QTY = 0;
		for (var i=0; i<SoGrid.getRowsNum();i++){		
			if(SoGrid.cellByIndex(i,0).getValue()=='1'){
				//console.log(Number(SoGrid.cellByIndex(i,3).getValue()));
				QTY+=Number(SoGrid.cellByIndex(i,3).getValue());
			}
		}	
		SoForm.setItemValue('QTY',QTY);
		TOTAL_PASSES_1 = 0;
		TOTAL_TIME = 0;		
		for (var i=0; i<ProcessGrid.getRowsNum();i++){					
			if(ProcessGrid.cellByIndex(i,7).getValue()>0){
				TOTAL_PASSES_1+= Number(ProcessGrid.cellByIndex(i,7).getValue());
			}			
			if(ProcessGrid.cellByIndex(i,9).getValue()>0){
				TOTAL_TIME+= Number(ProcessGrid.cellByIndex(i,9).getValue());
			}
			/*
			if(ProcessGrid.cellByIndex(i,11).getValue().indexOf("lop muc")!==-1){
				TOTAL_COLOUR+= 1;
			}
			if(ProcessGrid.cellByIndex(i,10).getValue()>0){
				TOTAL_SETUP+= Number(ProcessGrid.cellByIndex(i,10).getValue());
			}
			*/
		}
		SCRAP_ERROR = ItemGrid.cellByIndex(0,4).getValue();
		C22_TEXT = SoForm.getItemValue('UPS');
		if(C22_TEXT){
			C22_ARRAY = C22_TEXT.split("+");
			if(C22_TEXT.indexOf("*")!==-1){
				C22_VALUE = 0;
				for(var i=0;i<C22_ARRAY.length;i++){
					var C22_ARRAY_TEXT = C22_ARRAY[i].split("*");
					C22_VALUE+=Number(C22_ARRAY_TEXT[0])*Number(C22_ARRAY_TEXT[1]);
				}
			}else{				
				C22_VALUE = 1;
				for(var i=0;i<C22_ARRAY.length;i++){
					  C22_VALUE*=Number(C22_ARRAY[i]);
				}
			}	
			TOTAL_SETUP = 1;
			for (var i=0; i<ProcessGrid.getRowsNum();i++){
				if(ProcessGrid.cellByIndex(i,10).getValue()>0){
					TOTAL_SETUP+= Number(ProcessGrid.cellByIndex(i,10).getValue());
				}	
			}			
			ORGINAL_NEED = QTY/C22_VALUE;
			SO_UPS_CAL = C22_VALUE;
			SoForm.setItemValue('ORGINAL_NEED',ORGINAL_NEED.toFixed(2)); // @tandoan: lam tron 2 chu so
			SCRAP_SETUP = (TOTAL_SETUP/ORGINAL_NEED)*100;
			SoForm.setItemValue('SCRAP_SETUP',SCRAP_SETUP.toFixed(1));
			SHEET_BATCHING = Number(SoForm.getItemValue('SHEET_BATCHING'));
			if(SHEET_BATCHING){
				TOTAL_SHEET = SHEET_BATCHING*(1+Number(SCRAP_ERROR/100))+Number(TOTAL_SETUP);
				SoForm.setItemValue('TOTAL_SHEET',TOTAL_SHEET.toFixed(0));
				PRINTING = Number(TOTAL_SHEET-TOTAL_SETUP);
				SoForm.setItemValue('PRINTING',PRINTING.toFixed(0));
				PACKING = Number(PRINTING-(SHEET_BATCHING*SCRAP_ERROR/100));
				PACKING = PACKING.toFixed(0);
				SoForm.setItemValue('PACKING',PACKING);
				SCRAP_DESIGN = ((SHEET_BATCHING-ORGINAL_NEED)/ORGINAL_NEED)*100;
				SoForm.setItemValue('SCRAP_DESIGN',SCRAP_DESIGN.toFixed(1));
				SCRAP_PRINTING = ((TOTAL_SHEET-PRINTING)/ORGINAL_NEED)*100;
				SoForm.setItemValue('SCRAP_PRINTING',SCRAP_PRINTING.toFixed(1));
				TOTAL_SCRAP = ((TOTAL_SHEET-ORGINAL_NEED)/ORGINAL_NEED)*100;
				SoForm.setItemValue('TOTAL_SCRAP',TOTAL_SCRAP.toFixed(1));
				PAPER_COMPENSATE = (TOTAL_SHEET-SHEET_BATCHING);
				SoForm.setItemValue('PAPER_COMPENSATE',PAPER_COMPENSATE.toFixed(0));
				TOTAL_PASSES_2 = (TOTAL_SHEET*TOTAL_PASSES_1);
				SoForm.setItemValue('TOTAL_PASSES_2',Math.ceil(TOTAL_PASSES_2));
				var MACHINE_TYPE = SoForm.getItemValue('MACHINE_TYPE');
				// var SAKURAI = (MACHINE_TYPE=="SAKURAI")?1000:400;
				// TIME_RUNNING = ((TOTAL_PASSES_2/SAKURAI)*60+TOTAL_TIME)/60;

				// @tandoan: get Running time function 20200401
				TIME_RUNNING = getRunningTime(MACHINE_TYPE, TOTAL_PASSES_2, TOTAL_TIME );
				console.log('7: TIME_RUNNING: '+ TIME_RUNNING);

				SoForm.setItemValue('TIME_RUNNING',TIME_RUNNING.toFixed(1));
			}
		}
	}
	
	var ItemGrid;
	function initItemGrid(){
		ItemGrid = LayoutMain.cells("b").attachGrid();
        ItemGrid.setImagePath("./Module/dhtmlx/skins/skyblue/imgs/");
		// check init 
		ItemGrid.setHeader("NO.,ORACLE ITEM,AGI,FOD,SCRAP,RBO,MATERIAL CODE");   //sets the headers of columns
		// SoGrid.attachHeader("#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#text_filter,,#text_filter,#text_filter");	//the method takes the columns' filters as a parameter
		ItemGrid.setColumnIds("NO.,ORACLE ITEM,AGI,FOD,SCRAP,RBO,MATERIAL CODE");         //sets the columns' ids
		ItemGrid.setInitWidths("55,110,60,60,60,190,*");   //sets the initial widths of columns
		ItemGrid.setColAlign("left,left,left,left,left,left,left");     //sets the alignment of columns
		ItemGrid.setColTypes("ed,ed,ed,ed,ed,ed,ed");    //sets the types of columns
		ItemGrid.setColSorting("na,na,na,na,na,na,na");
        ItemGrid.init();
	}
	function initListItem(){
		initItemGrid();
		// ADD ITEM
		var STT = 0;
		for (var i=0; i<SoGrid.getRowsNum();i++){
			if(SoGrid.cellByIndex(i,0).getValue()=='1'){
				var ITEM = SoGrid.cellByIndex(i,4).getValue();
				var check = 1;
				for(var j=0;j<ItemGrid.getRowsNum();j++){
					if(ITEM == ItemGrid.cellByIndex(j,1).getValue()){
						check = 0;
					}
				}
				if(check){
					STT++;
					var uniqueID = ItemGrid.uid();				
					var AGI = SoGrid.cellByIndex(i,13).getValue();
					var FOD = SoGrid.cellByIndex(i,14).getValue();
					var SCRAP = SoGrid.cellByIndex(i,15).getValue();
					var RBO = SoGrid.cellByIndex(i,5).getValue();
					var MATERIAL_CODE = SoGrid.cellByIndex(i,10).getValue();
					var data_add = [STT,ITEM,AGI,FOD,SCRAP,RBO,MATERIAL_CODE];
					// console.log(data_add);
					ItemGrid.addRow(uniqueID,data_add);
				}								
			}			
		}		
		// LOAD MATERIAL
		initListMaterial();
		initListProcess();
		// ADD MORE
		for(var i=0;i<2;i++){
			var uniqueID = ItemGrid.uid();
			var data_add = ['','','','','','',''];
			// console.log(data_add);
			ItemGrid.addRow(uniqueID,data_add);
		}
		// END ADD 	
		ItemGrid.attachEvent("onEnter", function(id,ind){
			// resorting STT
			if(ind==0){
				ItemGrid.sortRows(0,"str", "asc"); // sorts grid
				// reset 
				checked_ITEM = [];
				for (var i=0; i<ItemGrid.getRowsNum();i++){
					var item_code = ItemGrid.cellByIndex(i,1).getValue().trim();
					checked_ITEM.push(item_code);
				}				
				initListProcess();
			}
		});
	}
	var MaterialGrid;
	function initMaterial(){
		MaterialGrid = LayoutMain.cells("d").attachGrid();
        MaterialGrid.setImagePath("./Module/dhtmlx/skins/skyblue/imgs/");
		// check init 
		MaterialGrid.setHeader("MATERIAL CODE,MATERIAL NAME,MATERIAL SIZE");   //sets the headers of columns
		// SoGrid.attachHeader("#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#text_filter,,#text_filter,#text_filter");	//the method takes the columns' filters as a parameter
		MaterialGrid.setColumnIds("MATERIAL CODE,MATERIAL NAME,MATERIAL SIZE");         //sets the columns' ids
		MaterialGrid.setInitWidths("185,200,*");   //sets the initial widths of columns
		MaterialGrid.setColAlign("left,left,left");     //sets the alignment of columns
		MaterialGrid.setColTypes("ed,ed,co");    //sets the types of columns
		MaterialGrid.setColSorting("na,na,na");
        MaterialGrid.init();
	}
	function initListMaterial(){
		initMaterial();
		// GET MATERIAL
		var MATERIAL_CODE = '';
		var MATERIAL_NAME = '';
		var MATERIAL_SIZE = '';
		if(SoGrid.getRowsNum()){
			for(var i=0; i<SoGrid.getRowsNum();i++){
				if(SoGrid.cellByIndex(i,0).getValue()=='1'){
					var uniqueID = MaterialGrid.uid();
					MATERIAL_CODE = SoGrid.cellByIndex(i,10).getValue();
					MATERIAL_NAME = SoGrid.cellByIndex(i,11).getValue();
					MATERIAL_SIZE = SoGrid.cellByIndex(i,12).getValue();
					var data_add = [MATERIAL_CODE,MATERIAL_NAME,MATERIAL_SIZE];
					MaterialGrid.addRow(uniqueID,data_add);
					combobox = MaterialGrid.getCombo(2);
					combobox.put('500*600','500*600');
					combobox.put('550*700','550*700');
					changeMaterialSize();
					break;
				}
			}
		}		
	}
	
	function inArray(target, array)
	{

	/* Caching array.length doesn't increase the performance of the for loop on V8 (and probably on most of other major engines) */

	  for(var i = 0; i < array.length; i++) 
	  {
		if(array[i] === target)
		{
		  return true;
		}
	  }

	  return false; 
	}
	
	var dataSo={rows:[]};
	var checked_ITEM = [];
	var input_OH = '';
	var checkOHExist = 0;
	function filterOH(){		
        if(!input_OH){
			input_OH = ToolbarMain.getInput("OH");
		}
        input_OH.focus(); // set focus
        //input_OH.value="OH1812-";
        input_OH.onkeypress = function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){ // enter on input text  
				create_OH_exist = 0;
				//checked_SOLINE = []; // reset checked_SOLINE when filter				
                var text = $(this).val();  
                text = text.trim();
				loadSO(text);
            }        
        }
    }
	function createJob(OH){
		if(!input_OH){
			input_OH = ToolbarMain.getInput("OH");
		}
		input_OH.value=OH;
		loadSO(OH);
	}
	var create_OH_exist = 0;
	var checkUOM;
	function loadSO(OH){
		if(!updateDB){
			alert('Vui lòng đăng nhập vào hệ thống!');
			location.reload();
			return false;
		}
		if(OH==""){
			LayoutMain.cells("a").progressOff();
			return false;
		}
		// CHECK EXIST SO
		checkExistOH(OH);
		if(checkOHExist==1){
			confirm_delete = confirm("OH NÀY ĐÃ ĐƯỢC TẠO LỆNH, BẠN CÓ MUỐN TIẾP TỤC TẠO LỆNH!!!");
			if(!confirm_delete){
				location.reload();
				return false;
			}else{
				create_OH_exist = 1;
			}
		}

		checkUOM(OH);

		var data = {
			OH : OH,
			checkUOM : checkUOM
		}
		console.log("data:: " + JSON.stringify(data) );
		LayoutMain.cells("a").progressOn();
		// call ajax @@@@@@
		var url_load_grid = RootDataPath+'load_grid.php'; 
		$.ajax({
		url: url_load_grid,
			type: "POST",
			data: {data: JSON.stringify(data) },
			dataType: "json",
			beforeSend: function(x) {
				if (x && x.overrideMimeType) {
					x.overrideMimeType("application/j-son;charset=UTF-8");
				}
			},
			success: function(result){
				if(result.status){
					var length = result.data.length;
					for(var i = 0;i<length;i++){
						dataSo.rows.push(result.data[i]);
					}							   
					SoGrid.parse(dataSo,"json");
					checked_SOLINE = []; // reset checked_SOLINE when filter
					for (var i=0; i<SoGrid.getRowsNum();i++){								
						SoGrid.cellByIndex(i,1).setValue(i+1);	
						if(SoGrid.cellByIndex(i,0).getValue()=='1'){
							so_line = SoGrid.cellByIndex(i,2).getValue().trim();
							grid_id = SoGrid.getRowId(i);
							var obj = {so_line:so_line,grid_id:grid_id};								
							checked_SOLINE.push(obj);
							var item_code = SoGrid.cellByIndex(i,4).getValue().trim();
							if(checked_ITEM.length<1||!inArray(item_code,checked_ITEM)){
								checked_ITEM.push(item_code);
							}
						}																
					}
					LayoutMain.cells("a").progressOff();
					// load list item
					if(checked_SOLINE.length>0){
						initListItem();
					}							
				}else{
					alert(result.mess);
					location.reload();
					LayoutMain.cells("a").progressOff();
				}				
			}
		});
		//event.stopPropagation();
	}

	// @tandoan: check UOM
	
	function checkUOM(OH) {
		var url_load_grid = RootDataPath+'checkUOM_conn.php'; 
		$.ajax({
		url: url_load_grid,
			type: "POST",
			async: false,
			data: {data: OH},
			dataType: "json",
			beforeSend: function(x) {
				if (x && x.overrideMimeType) {
					x.overrideMimeType("application/j-son;charset=UTF-8");
				}
			},
			success: function(result){
				
				if (result.status == false ) {
					conf = confirm(result.message );
					if (!conf ) location.href = './';
				} else {
					
				}

				checkUOM = result.status;
				
			}
		});
	}
	function checkExistOH(OH){
		var url_save = RootDataPath+'check_oh.php?OH='+OH; 
		$.ajax({
		url: url_save,
			type: "POST",
			async: false,
			//data: {data: JSON.stringify(jsonObjects) },
			dataType: "json",
			beforeSend: function(x) {
				if (x && x.overrideMimeType) {
				x.overrideMimeType("application/j-son;charset=UTF-8");
				}
			},
			success: function(result) {
				checkOHExist = result;
			}
		});
	}
	function checkScrap(){
		var url_save = RootDataPath+'check_scrap.php'; 
		$.ajax({
		url: url_save,
			type: "POST",
			async: false,
			//data: {data: JSON.stringify(jsonObjects) },
			dataType: "json",
			beforeSend: function(x) {
				if (x && x.overrideMimeType) {
				x.overrideMimeType("application/j-son;charset=UTF-8");
				}
			},
			success: function(result) {
				if(result.length){					
					alert("ITEM: "+result.join()+ " CÓ SỐ SCRAP KHÔNG BẰNG NHAU VUI LÒNG KIỂM TRA LẠI!");
				}
			}
		});
	}
	
	function changeMaterialSize(){
		MaterialGrid.attachEvent("onEditCell", function(stage,rId,cInd,nValue,oValue){
			if(stage==2){ 
				var meterial_str = MaterialGrid.cellByIndex(0,0).getValue();
				var meterial_arr = meterial_str.split("-");
				MaterialGrid.cellByIndex(0,0).setValue(meterial_arr[0]+'-'+meterial_arr[1]+'-'+nValue);
				return true;
			}
		});
	}
	
	String.prototype.capitalize = function() {
		return this.charAt(0).toUpperCase() + this.slice(1);
	}
	
    $(document).ready(function(){	
		
		var VNRISIntranet = '<?php echo getUser(); ?>';
        console.log("VNRISIntranet: "+VNRISIntranet);
        if (!VNRISIntranet ) {
            var pr = prompt('Nhập tiền tố email trước @. Ví dụ: tan.doan', '');
            pr = pr.trim();
            if (!pr || pr.indexOf('@') !== -1 ) {
                alert('Bạn vui lòng nhập đúng tiền tố email là phần trước @');
            } else {
                // Save email đến bảng thống kê (au_avery.planning_user_statistics)
                setCookie('VNRISIntranet', pr, 30 );
                // setCookie('VNRISIntranet', pr, 30 );
                var VNRISIntranet = '<?php echo getUser(); ?>';
                var pr_s = '<?php echo planning_user_statistics($email, "HTL_Planning"); ?>';
                console.log('save planning_user_statistics: ' + pr_s);
                
                check_gg = 1;
            }
            
           
        }
		
		if (check_gg) location.href = './';

        initLayout();
        initMenu();
        initToolbar(); 
        initSoGrid();
        //filterSO(); 
		filterOH();
		//changeOH();
		checkScrap();
		changeQtySo();
		//changeMaterialSize();
    });    
</script>
</head>
<body>
    <div style="height: 30px;background:#205670;font-weight:bold">
		<div id="menuObj"></div>
    </div>
    <div style="position:absolute;width:100%;top:35;background:white">
		<div id="ToolbarBottom" ></div>
    </div>
</body>
</html>