<?php
use Illuminate\Support\Facades\Storage;
use App\Models\CalendarSquare;


$html_result = "";
$cal_start_ym = "";
$ymd_after_due_date = "";
$ymd_receive_date = "";
$ymd_platemake_date = "";
$action_msg .= "mode：".$mode."<br>\n";
$select_html = !empty($_POST['select_html']) ? $_POST['select_html'] : "Default";

//echo "select_html = ".$select_html."<br>\n";
//var_dump($html_cal_main);

if(isset($result['result'])) {
	$resultdata = $result['result'];
		//var_dump($resultdata);
	
	if(isset($resultdata)) {
		foreach($resultdata as $key => $val) {

			//$number = 1 + $key;
			$product_code = $val->product_code;
			$serial_code = $val->serial_code;
			$rep_code = $val->rep_code;
			$after_due_date = $val->after_due_date;
			$customer = $val->customer;
			$product_name = $val->product_name;
			$end_user = $val->end_user;
			$quantity = $val->quantity;
			$receive_date = $val->receive_date;
			$platemake_date = $val->platemake_date;
			$status = $val->status;
			$comment = $val->comment;
			$created_user = $val->created_user;
			$updated_user = $val->updated_user;
			$created_at = $val->created_at;
			$updated_at = $val->updated_at;

			$html_after_due_date = !empty($after_due_date) ? date('n月j日', strtotime($after_due_date)) : "";
			$ymd_after_due_date = !empty($after_due_date) ? date('Y-m-d', strtotime($after_due_date)) : "";
			$ymd_receive_date = !empty($receive_date) ? date('Y-m-d', strtotime($receive_date)) : "";
			$ymd_platemake_date = !empty($platemake_date) ? date('Y-m-d', strtotime($platemake_date)) : "";

			//$editzone = true;

			//指定日時を月はじめに変換する date("Y-m-d H:i:s")
			//$after_due_date = "";
			$target_day = !empty($after_due_date) ? date("Y-m-1", strtotime($after_due_date)) : date("Y-m-d");
			//echo "targetday = ".$target_day."<br>\n";
			$cal_start_ym = !empty($target_day) ? date('Y-n', strtotime($target_day . ' -1 month')) : "";
			
			$calendar_squ_data = new CalendarSquare();	// インスタンス作成
			$html_calsqu = $calendar_squ_data->create_calendar( 3, $cal_start_ym, $after_due_date);	//開始年月～何か月分
					

		}
	}
	else {
		$action_msg .= "returnがありません<br>\n";
	}


} else {
	$action_msg .= "resultにデータがありません<br>\n";
	$resultdata = Array();
}

//var_dump($result_log);
if(!empty($result_log)) {
	//echo "<br><br>\n";
	//var_dump($result_log['result_log']['result']);
	$resultlog = $result_log['result_log']['result'];
}
else {
	$resultlog = Array();
}

	//var_dump($result[0]);
	//echo $result[0]->customer;

	//$datetest = new DateTime($after_due_date);
	//echo $datetest->format('Y-m-d');

	//$after_due_date = $result_details['after_due_date'];    // return $redata = [ の after_due_date を指す。
	//$test = $result_details['result'][0]->after_due_date;    // return result[]から取得する場合　[0]のキーが必要。


?>
@extends('layouts.main')
@section('content')
				<div id="contents_area">
					<div id="title_cnt">
						<h1 class="tstyle">スケジュール／</h1>
					</div>
					<!-- main maincontents row -->
					<div id="maincontents">
					@if($select_html === 'Default')
						<div id="search_fcnt">
							<!--<h4>検索</h4>-->

							<form id="searchform" name="searchform" method="POST">
								<input type="hidden" name="mode" id="mode" value="">
								<input type="hidden" name="submode" id="submode" value="">
								<input type="hidden" name="select_html" id="select_html" value="">

								<div id="form1">
									<div>
										<label for="s_product_code" class="w4e">伝票番号</label>
										<input type="number" class="form_style1 w10e" name="s_product_code" id="s_product_code" value="{{ $s_product_code }}" step="1" min="0">
									</div>
									<div>
										<button class="gc5 transition1 mgla" type="button" onClick="formReset('s_product_code')">伝票番号クリア</button>
									</div>
									<div class="mgl40">
										<label for="duedate" class="w4e">納期</label>
										<input type="date" class="form_style1 w10e" name="duedate_start" id="duedate" value="{{ $duedate_start }}">
										～
										<input type="date" class="form_style1 w10e" name="duedate_end" id="duedate" value="{{ $duedate_end }}">
									</div>
									<div>
										<!--<button class="gc5 transition1 mgla" type="button" onClick="this.form.reset()">リセット</button>-->
										<button class="gc5 transition1 mgla" type="button" onClick="formReset_3( Array('s_customer','s_product_name','s_end_user') )">クリア</button>
									</div>
								</div>
								<div id="form1" class="mgt10">
									<div class="form_zone">
										<label for="s_customer" class="">得意先</label>
										<input type="text" class="form_style1" name="s_customer" id="s_customer" value="{{ $s_customer }}"> 
									</div>
									<div class="form_zone">
										<label for="s_product_name" class="">品名</label>
										<input type="text" class="form_style1" name="s_product_name" id="s_product_name" value="{{ $s_product_name }}">
									</div>
									<div class="form_zone">
										<label for="s_end_user" class="">エンドユーザー</label>
										<input type="text" class="form_style1" name="s_end_user" id="s_end_user" value="{{ $s_end_user }}">
									</div>
								</div>
								<div id="form1" class="mgt10">
									<button class="transition1" type="button" onClick="clickEvent('searchform','1','Default','confirm','検索','some_search','chkwrite')">検索</button>
									<div id="error">{!! $result['e_message'] !!}</div>
								</div>
									<!--<div id="error">{{ $e_message }}</div>-->

								@csrf 
							</form>
						</div>
						
						<form id="updateform" name="updateform" method="POST">
							<input type="hidden" name="mode" id="mode" value="">
							<input type="hidden" name="submode" id="submode" value="">
							<input type="hidden" name="select_html" id="select_html" value="">
							<input type="hidden" name="s_product_code" id="s_product_code" value="">
							<div id="tbl_1" class="mgt10">
								<table>
									<thead>
										<tr>
											<th></th>
											<th>伝票番号</th>
											<th>納期</th>
											<th>得意先</th>
											<th>品名</th>
											<th>エンドユーザー</th>
											<th>数量</th>
											<th>入稿日</th>
											<th>下版日</th>
											<th>コメント</th>
										</tr>
									</thead>
									<tbody>
									@forelse ($resultdata as $val)
										<tr>
											<td class="nbr">
												<button type="button" onClick="clickEvent('updateform','{{ $val->product_code }}','oneView','view','表示','some_search','')">表示</button>
												<button class="style5" type="button" onClick="clickEvent('updateform','{{ $val->product_code }}','','confirm_process','下記の工程を編集します','','')">編集</button>
											</td>
											<td class="">{{ $val->product_code }}</td>
											<td class="">{!! date('Y-m-d', strtotime($val->after_due_date)) !!}</td>
											<td class="">{{ $val->customer }}</td>
											<td class="">{{ $val->product_name }}</td>
											<td class="">{{ $val->end_user }}</td>
											<td class="">{{ $val->quantity }}</td>
											<td class="">@php echo isset($val->receive_date) ? date('Y-m-d', strtotime($val->receive_date)) : ""; @endphp</td>
											<td class="">@php echo isset($val->platemake_date) ? date('Y-m-d', strtotime($val->platemake_date)) : ""; @endphp</td>
											<td class="">{{ $val->comment }}</td>
										</tr>

									@empty
										<tr><td colspan="10">no data</td></tr>
									@endforelse
									</tbody>
								</table>
							</div>


							@csrf
						</form>


						<div id="resultupdate"></div>
						<div id="resultstr"></div>

						<form id="addprocessform" name="addprocessform" method="POST">
							<input type="hidden" name="mode" id="mode" value="wp_search">
							<input type="hidden" name="submode" id="submode" value="">
							<input type="hidden" name="motion" id="motion" value="">
							<input type="hidden" class="form_style1 w10e" name="s_product_code" id="s_product_code" value="{{ $s_product_code }}">
							<input type="hidden" name="work_name" id="work_name" value=""> 
							<input type="hidden" name="departments_name" id="departments_name" value=""> 
							@csrf

							

						@php
							echo $html_cal_main;
						@endphp
						


						</form>

						@if($result['datacount'] === 1)
						<div class="mgt20">
							{!! $html_calsqu !!}
						</div>
						@endif
						<div>{!! $action_msg !!}</div>


					@elseif($select_html === 'oneView')

					@endif

					</div>
					<!-- /main maincontents row -->



@endsection

@section('jscript')

<script type="text/javascript">
	function clickEvent(fname,val1,val2,cf,com1,md,smd) {
	var fm = document.getElementById(fname);

		if(cf == 'confirm') {
			var Js_product_code = fm.s_product_code.value;
			//var result = window.confirm( com1 +'\\n\\n店舗名 : '+ Jname +'\\nコード : '+ Jname_code +'');
			//var result = window.confirm(Js_product_code + ' ' + com1 + 'します');
			var result = val1;
			if( result ) {
				fm.mode.value = md;
				fm.select_html.value = val2;
				fm.action = '/view/search';
				fm.submit();
			}
			else {
				console.log('キャンセルがクリックされました');
			}
		}
		else if(cf == 'view') {
				var Jurlsd = '';
				if(smd) Jurlsd = '?sd=' + smd;
				fm.mode.value = md;
				fm.s_product_code.value = val1;
				fm.select_html.value = val2;
				fm.action = '/view/search' + Jurlsd;
				fm.submit();
		}
		else if(cf == 'confirm_process') {
			//var Jproduct_code = document.getElementById('product_code' + val1).value;
			//var Jstatus = fm.status.value;
			//var result = window.confirm( com1 +'\n伝票番号 : '+ Jproduct_code +'');
			var result = window.confirm( com1 +'\n伝票番号 : '+ val1 +'');
			if( result ) {
				fm.s_product_code.value = val1;
				fm.select_html.value = val2;
				fm.action = '/process/search';
				fm.submit();
			}
		}
		else {
			fm.submit();
		}
	}



function chkOnOff(c) {
	//let check_onoff = document.querySelectorAll(".chkonff");
	let check_onoff = document.querySelectorAll(c);
	for (let i in check_onoff) {
		if (check_onoff.hasOwnProperty(i)) {
			check_onoff[i].checked = false;
		}
	}
}










function appendSTTS(dataarr) {
	$.each(dataarr, function(index, data) {
		//console.log('appendSTTS in each data -> ' + data + ' index -> ' + index);
		//var id_status = 'status' + dataarr['work_date'] + '_' + dataarr['work_code'];
		var id_status = 'status' + dataarr['work_date'] + '_' + dataarr['work_code'] + '_' + dataarr['uid'];
		//console.log('appendSTTS in each id_status -> ' + id_status);

		if(index == 'e_message') document.getElementById('resultupdate').innerHTML = '<div class="txt1">' + data + '</div>\n';
		//var statusv = '<span style="color:green;">OK</span>';
		if(dataarr['result'] === 1) {
			//$('#resultlist ul').prepend('<li><span>' + addcount + '</span>&emsp;<span class="txtcolor1">&#10004;</span>&emsp;No.&ensp;<span class="dtnum">' + data.product_code + '</span> ' + statusv + '</li>\n');
			console.log('appendSTTS in product_code ' + dataarr['product_code']);
			let text = [];
			text.push('' + dataarr['product_code'] + '\n');
			document.getElementById('resultstr').innerHTML = text.join('');
			document.getElementById(id_status).innerHTML = "○";

		}
		else {
			//statusv = '<span style="color:red;">NG</span>';
			//$('#resultlist ul').prepend('<li><span>' + addcount + '</span>&emsp;<span class="txtcolor3">&#10006;</span>&emsp;No.&ensp;<span class="dtnum">' + data.product_code + '</span> ' + statusv + '</li>\n');
			document.getElementById('resultstr').innerHTML = "";

		}
		
	});
}


function statusChange(wd,pc,dc,wc,dn,wn,subMode) {
	var Mode = 'status_update';
	//var Jdepartments_name = document.getElementById('departments_name' + n).value;
	//document.getElementById('departments_name').value = dn;
	//console.log('WORKcollect in depa ' + n);
	//var result = window.confirm( com1 +'\\n\\n店舗名 : '+ Jname +'\\nコード : '+ Jname_code +'');
	var result = window.confirm( wd + '\n部署： ' + dn + '\n作業： ' + wn + '\n\nステータスを変更します');
	if( result ) {
		var details = {name: "pro", team: ""};
		//console.log("mode :" + Mode);
		//console.log("work_date :" + wd);
		const res = axios.post("/work/sttsup", {
			work_date: wd,
			product_code: pc,
			departments_code: dc,
			work_code: wc,
			departments_name: dn,
			work_name: wn,
			mode: Mode,
			submode: subMode,
			details: details,
		})
		.then(response => {
			appendSTTS(response.data);
			
		})
		.catch(error => {
			console.log('error message = ' + error.message );
		});
	}
	else {
		console.log('キャンセルがクリックされました');
	}

}




	function formReset(fname) {
		//var fm = document.getElementById(fname);
		//fm.reset();
		var textForm = document.getElementById(fname);
		textForm.value = '';
	}
	function formReset_2() {
		document.getElementById('s_customer').value = "";
		document.getElementById('s_product_name').value = "";
		document.getElementById('s_end_user').value = "";
	}
	function formReset_3($arr) {
		for ( var $key in $arr ) {
			document.getElementById($arr[$key]).value = "";
			//console.log('formReset_3 ' + $arr[$key]);
    	}

	}

	function unChecked(cl) {
		let boxes = document.querySelectorAll(cl);
		for (let i = 0; i < boxes.length; i++) {
			boxes[i].checked = false;
		}
	}
	function checked(cl) {
		let boxes = document.querySelectorAll(cl);
		for (let i = 0; i < boxes.length; i++) {
			boxes[i].checked = true;
		}
	}




</script>
@endsection

