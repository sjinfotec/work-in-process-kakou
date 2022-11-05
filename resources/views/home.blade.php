<?php
$html_result = "";
if(isset($result)) {
		var_dump($result);
	
	if(isset($result[0])) {
		foreach($result as $key => $val) {

			//echo "key".$key."<br>\n";
			//$res = $result[$key];
			$number = 1 + $key;
			$product_id = $val->product_id;
			$customer = $val->customer;
			$product_name = $val->product_name;
			$end_user = $val->end_user;
			$after_due_date = $val->after_due_date;
			$quantity = $val->quantity;
			$html_after_due_date = !empty($after_due_date) ? date('y年n月j日', strtotime($after_due_date)) : "";
			$html_result .= <<<EOF
				<tr>
					<td>{$number}</td>
					<td>{$product_id}</td>
					<td>{$html_after_due_date}</td>
					<td>{$customer}</td>
					<td>{$product_name}</td>
					<td>{$end_user}</td>
					<td>{$quantity}</td>
				</tr>

			EOF;

		}
	}
	else {
		$action_msg .= "returnがありません";
	}


} else {
	$action_msg .= "resultにデータがありません";
}

//var_dump($result[0]);
//echo $result[0]->customer;



?>
@extends('layouts.main')
@section('content')

<div id="home_cnt">
					<div class="">
						<h1>伝票番号登録</h1>
					</div>
					<!-- main contentns row -->
					<div id="maincontents">

					<form id="searchform" name="payform" method="POST">
						<input type="hidden" name="mode" id="mode" value="payview">
						<input type="hidden" name="submode" id="submode" value="chkwrite">
						<input type="hidden" name="motion" id="motion" value="">
				

						<input type="number" name="product_id" id="product_id" value="">
						<input type="text" name="str1" id="str1" value="">
						<input type="text" name="str2" id="str2" value="">
						<input type="text" name="str3" id="str3" value="">

						<input type="date" class="form_style w8" id="today" name="wpdate" value="">&emsp;
						<button class="btn_style" type="button" onClick="clickEvent('searchform','','1','confirm','『 検索 』 します。','payview','chkwrite')">検索</button>
						<button class="btn_style" type="button" onClick="UPDATEcollect()">axios検索</button>
						@csrf 
					</form>


		
					</div>
					<!-- /main contentns row -->

					<div>
						<div>modeの値：{{ $mode }}</div>
						<div>変数の値1：{{ $str1 }}</div>
						<div>変数の値2：{{ $str2 }}</div>
						<div>変数の値3：{{ $str3 }}</div>
					</div>
					<div>{{ $action_msg }}</div>

					<div id="tbl_1">
						<table>
							<thead>
							<tr>
								<th>&emsp;</th>
								<th>伝票番号</th>
								<th>納期</th>
								<th>得意先</th>
								<th>品名</th>
								<th>エンドユーザー</th>
								<th>数量</th>

							</tr>
							</thead>
							<tbody>
							@php echo $html_result;
							@endphp
							</tbody>
						</table>
					</div>

					<div id="resultupdate"></div>
					<div id="resultlist"><ul class="list-group"></ul></div>
					<div id="error"></div>


@endsection

@section('jscript')

<script type="text/javascript">
	function clickEvent(fname,tn,val,cf,com1,md,smd) {
	var fm = document.getElementById(fname);
	var tname = document.getElementsByName(tn);
	//Submit値を操作
	//fm.edit_id.value = val;
	//fm.tname.value = val;
	//tname[0].value = val;	//[0]を付けないとundefind

	//alert('clickEvent 引数 = ' + fname + ' 、 ' + tn + ' 、 ' + val + ' 、 ' + cf);

		if(cf == 'confirm') {
			//var Jname = fm.name.value;
			//var Jname_code = fm.name_code.value;
			//var result = window.confirm( com1 +'\\n\\n店舗名 : '+ Jname +'\\nコード : '+ Jname_code +'');
			var result = window.confirm( com1 );
			if( result ) {
				//document.defineedit.edit_id.value = val;
				//document.defineedit.submit();
				fm.mode.value = md;
				fm.submit();
			}
			else {
				console.log('キャンセルがクリックされました');
			}
		}
		else if(cf == 'confirm_pay') {
			//var result = window.confirm( com1 +'');
				//tname[0].value = val;
				fm.pay_month.value = val;
				fm.mode.value = md;
				fm.submit();
		}
		else if(cf == 'confirm_update') {
			var Jshop = fm.shop.value;
			var Jshop_code = fm.sp.value;
			var Jpay_month = fm.pay_month.value;
			var Jstatus = fm.status.value;
			var result = window.confirm( com1 +'\\nショップ : '+ Jshop +'\\nコード : '+ Jshop_code +'\\n : '+ Jpay_month +'');
			if( result ) {
				//document.defineedit.edit_id.value = val;
				//document.defineedit.submit();
				//tname[0].value = val;
				//fm.pay_month.value = val;
				fm.motion.value = val;
				fm.mode.value = md;
				fm.submode.value = smd;
				fm.submit();
			}
			else {
				console.log('キャンセルがクリックされました');
			}

		}
		else {
			fm.submit();
		}
	}


	var appendcnt = Number('1');
// 画面を更新する処理
function appendList(data) {
	$.each(data, function(num, data) {
		//console.log('appendList in ' + num);
		//console.log('appendList appendcnt ' + appendcnt);
		//$('#list ul').append("No. : " + data.t_number + "<br>名前 : " + data.name + "<br>name_code : " + data.name_code + '');
		//$('#resultupdate').html = ( "message--" + data.e_message );
		document.getElementById('resultupdate').innerHTML = '<div class="txt1">' + data.e_message + '</div>\n';
		var statusv = '<span style="color:green;">OK</span>';
		if(data.result_msg === 'OK') {
			if(data.chk_status === 'esse') {
				statusv = '<span style="color:orange;">上書き</span>';
			}
			$('#resultlist ul').prepend('<li><span>' + appendcnt + '</span>&emsp;<span class="txtcolor1">&#10004;</span>&emsp;No.&ensp;<span class="dtnum">' + data.str1 + '</span> ' + statusv + '</li>\n');
		}
		else {
			statusv = '<span style="color:red;">NG</span>';
			$('#resultlist ul').prepend('<li><span>' + appendcnt + '</span>&emsp;<span class="txtcolor3">&#10006;</span>&emsp;No.&ensp;<span class="dtnum">' + data.str1 + '</span> ' + statusv + '</li>\n');
		}
		appendcnt = appendcnt + 1;
	});
}
// エラー処理
function error(error) {
    $('#list').empty();
    $('#error').append(error);
}

// 基本的にはresponse.dataにデータが返る
function UPDATEcollect() {
	var Pstr1 = document.getElementById('str1').value;
	var Pstr2 = document.getElementById('str2').value;
	var Pstr3 = document.getElementById('str3').value;
	//var collectdate = document.getElementById('collect_date').value;
	var Mode = document.getElementById('mode').value;
	var Wpdate = document.getElementById('today').value;
	console.log("mode :" + mode);
	//console.log("t_number cn :" + tn);
	const res = axios.post("/list/search", {
		str1: Pstr1,
		str2: Pstr2,
		str3: Pstr3,
		mode: Mode,
		wpdate: Wpdate,
	})
	.then(response => {
		appendList(response.data);
		
	})
	.catch(error => {
		window.error(error.response.data);
	});
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



	window.onload = function () {
		//今日の日時を表示
		var date = new Date()
		var year = date.getFullYear()
		var month = date.getMonth() + 1
		var day = date.getDate()

		var toTwoDigits = function (num, digit) {
			num += ''
			if (num.length < digit) {
				num = '0' + num
			}
			return num
		}

		var yyyy = toTwoDigits(year, 4)
		var mm = toTwoDigits(month, 2)
		var dd = toTwoDigits(day, 2)
		var ymd = yyyy + "-" + mm + "-" + dd;

		document.getElementById("today").value = ymd;
	}
</script>
@endsection

