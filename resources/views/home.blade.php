<?php
echo "<pre>\n";
//var_dump($authusers);
echo "<br><br>\n";
//var_dump($data);
echo "<br>\n";
//var_dump($value);
echo "</pre>\n";

echo "<pre>\n";
var_dump($str1);
var_dump($str2);
var_dump($str3);
echo "</pre>\n";



?>
@extends('layouts.main')
@section('content')

<div id="home_cnt">
					<div class="">
						<h1>ホーム</h1>
					</div>
					<!-- main contentns row -->
					<div id="maincontents">

					<form id="payform" name="payform" method="POST">
					<input type="hidden" name="mode" value="payview">
					<input type="hidden" name="submode" value="chkwrite">
					<input type="hidden" name="motion" value="">
				


					<input type="date" class="form_style w8" id="today" name="pay_date" value="">&emsp;
					<button class="btn_style" type="button" onClick="clickEvent('payform','','1','confirm_update','\\n選択項目を『 支払い済み 』 にします。','payview','chkwrite')">支払い済み</button>

					</form>

					</div>
					<!-- /main contentns row -->

					<div>
						<div>変数の値1：{{ $str1 }}</div>
						<div>変数の値2：{{ $str2 }}</div>
						<div>変数の値3：{{ $str3 }}</div>
					</div>

@endsection

@section('jscript')

<script>
	function clickEvent(fname,tn,val,cf,com1,md,smd) {
	var fm = document.getElementById(fname);
	var tname = document.getElementsByName(tn);
	//Submit値を操作
	//fm.edit_id.value = val;
	//fm.tname.value = val;
	//tname[0].value = val;	//[0]を付けないとundefind

	//alert('clickEvent 引数 = ' + fname + ' 、 ' + tn + ' 、 ' + val + ' 、 ' + cf);

		if(cf == 'confirm') {
			var Jname = fm.name.value;
			var Jname_code = fm.name_code.value;
			var result = window.confirm( com1 +'\\n\\n店舗名 : '+ Jname +'\\nコード : '+ Jname_code +'');
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

