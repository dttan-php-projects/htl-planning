<?php
$CREATED_DATE = date('d-M-Y');
echo "
[
	{type: 'settings', position: 'label-left', labelWidth: 'auto', inputWidth: 'auto'},
	{type: 'fieldset', width: 'auto', blockOffset: 0,label:'Detail', offsetLeft: '20',offsetTop: '20',list: [
		{type: 'settings', position: 'label-left', labelWidth: '105', inputWidth: 130, labelAlign: 'left'},
		{type: 'input', label: 'Date:', labelAlign: 'left', icon: 'icon-input', name:'CREATED_DATE',id:'CREATED_DATE',className: '',value:'$CREATED_DATE'},
		{type: 'input', label: 'Job No:', labelAlign: 'left', icon: 'icon-input', name:'JOB_NO',id:'JOB_NO',className: '',value:''},
		{type: 'select', label: 'Type Product',name:'PRINTING_TYPE',id:'PRINTING_TYPE',className:'formShow',options:[
			{value: 'AGI', text: 'AGI'},
			{value: 'AGF', text: 'AGF'},
		]},
		{type: 'input', label: 'RBO:', labelAlign: 'left', icon: 'icon-input', name:'RBO',id:'RBO',className: ''},
		{type: 'input', label: 'Customer code:', labelAlign: 'left', icon: 'icon-input', name:'CUSTOMER_ITEM',id:'CUSTOMER_ITEM',className: ''},
		{type: 'input', label: 'Total Pass:', labelAlign: 'left', icon: 'icon-input', name:'TOTAL_PASSES_1',id:'TOTAL_PASSES_1',className: ''},
		{type: 'input', label: 'Label Size:', labelAlign: 'left', icon: 'icon-input', name:'LABEL_SIZE',id:'LABEL_SIZE',className: '',required:true,validate:'NotEmpty'},
		{type: 'input', label: 'Total Time:', labelAlign: 'left', icon: 'icon-input', name:'TOTAL_TIME',id:'TOTAL_TIME',className: ''},
		{type: 'input', label: 'Need Sheet:', labelAlign: 'left', icon: 'icon-input', name:'ORGINAL_NEED',id:'ORGINAL_NEED',className: 'cls_test'},
		{type: 'input', label: 'Packing:', labelAlign: 'left', icon: 'icon-input', name:'PACKING',id:'PACKING',className: ''},
		{type: 'input', label: 'Scrap Design:', labelAlign: 'left', icon: 'icon-input', name:'SCRAP_DESIGN',id:'SCRAP_DESIGN',className: 'cls_test'},
		{type: 'input', label: 'Scrap Allowance:', labelAlign: 'left', icon: 'icon-input', name:'SCRAP_ERROR',id:'SCRAP_ERROR',className: ''},
		{type: 'input', label: 'Total Scrap:', labelAlign: 'left', icon: 'icon-input', name:'TOTAL_SCRAP',id:'TOTAL_SCRAP',className: 'cls_test'},
		{type: 'input', label: 'Total sheet:', labelAlign: 'left', icon: 'icon-input', name:'TOTAL_SHEET',id:'TOTAL_SHEET',className: 'cls_test'},
		{type: 'input', label: 'Time Running:', labelAlign: 'left', icon: 'icon-input', name:'TIME_RUNNING',id:'TIME_RUNNING',className: ''},
		{type: 'newcolumn', offset: 20},
		{type: 'select', label: 'Type Machine:',name:'MACHINE_TYPE',className:'formShow',options:[
			{value: 'SAKURAI', text: 'SAKURAI'},
			{value: 'ATMA', text: 'ATMA'},
			{value: 'FAPL_MAY_NHO', text: 'FAPL_MAY_NHO'},
			{value: 'FAPL_MAY_LON', text: 'FAPL_MAY_LON'},
		]},		
		{type: 'input', label: 'Promise Date:', labelAlign: 'left', icon: 'icon-input', name:'PD',id:'PD',className: ''},
		{type: 'input', label: 'Quantity:', labelAlign: 'left', icon: 'icon-input', name:'QTY',id:'QTY',className: ''},
		{type: 'input', label: 'Oracle Item:', labelAlign: 'left', icon: 'icon-input', name:'ITEM',id:'ITEM',className: ''},
		{type: 'input', label: 'Số Film:', labelAlign: 'left', icon: 'icon-input', name:'NUMBER_FILM',id:'NUMBER_FILM',className: '',required:true,validate:'NotEmpty'},
		{type: 'input', label: 'Total Colour:', labelAlign: 'left', icon: 'icon-input', name:'TOTAL_COLOUR',id:'TOTAL_COLOUR',className: '',required:true,validate:'NotEmpty'},
		{type: 'input', label: 'Ups (pcs):', labelAlign: 'left', icon: 'icon-input', name:'UPS',id:'UPS',className: '',required:true,validate:'NotEmpty'},
		{type: 'input', label: 'Sheet Batching:', labelAlign: 'left', icon: 'icon-input', name:'SHEET_BATCHING',id:'SHEET_BATCHING',className: '',validate:'NotEmpty',required:true},
		{type: 'input', label: 'Total Setup:', labelAlign: 'left', icon: 'icon-input', name:'TOTAL_SETUP',id:'TOTAL_SETUP',className: ''},		
		{type: 'input', label: 'Printing:', labelAlign: 'left', icon: 'icon-input', name:'PRINTING',id:'PRINTING',className: ''},
		{type: 'input', label: 'Scrap Setup:', labelAlign: 'left', icon: 'icon-input', name:'SCRAP_SETUP',id:'SCRAP_SETUP',className: ''},
		{type: 'input', label: 'Scrap Printing:', labelAlign: 'left', icon: 'icon-input', name:'SCRAP_PRINTING',id:'SCRAP_PRINTING',className: ''},
		{type: 'input', label: 'Paper Compensate:', labelAlign: 'left', icon: 'icon-input', name:'PAPER_COMPENSATE',id:'PAPER_COMPENSATE',className: ''},		
		{type: 'input', label: 'Total Pass:', labelAlign: 'left', icon: 'icon-input', name:'TOTAL_PASSES_2',id:'TOTAL_PASSES_2',className: ''},
		{type: 'select', label: 'Planning:',name:'PLANNING_NAME',id:'PLANNING_NAME',className:'formShow select_planing',options:[
			{value: 'Phương Dung', text: 'Phương Dung'},
			{value: 'Khoa Huỳnh', text: 'Khoa Huỳnh'},
			{value: 'Long Đặng', text: 'Long Đặng'},
			{value: 'Quyên Nguyễn', text: 'Quyên Nguyễn'},
			{value: 'Nam Nguyễn', text: 'Nam Nguyễn'},
			{value: 'Ly Trần', text: 'Ly Trần'},
			{value: 'Tú Ngô', text: 'Tú Ngô'},
			{value: 'Tùng Lê', text: 'Tùng Lê'}
		]},
		
	]}
]";
?>