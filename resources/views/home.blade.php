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
	<div id="tips_cnt">
          <h3 class="">工程管理システム／使い方</h3>
          <ul class="lst1">
          <li>工程（伝票）を登録
            <ol class="lst2">
            <li>メニュー欄より『伝票番号登録』をクリック、登録したい伝票番号を入力し、『検索』をクリックすると、登録可能な製品が表示されます</li>
            <li>登録したい伝票番号列の左端にある『登録』をクリックし、工程を登録します</li>
            </ol>
          </li>
          <li>工程を作成（編集）する　＞その１
            <ol class="lst2">
            <li>メニュー欄より『作業工程作成』をクリック、作成・編集したい伝票番号を入力し、『検索』をクリック、検索されると編集画面に変わります</li>
            <li>製品情報の編集をするには、『編集』をクリックすると入力可能な表示に変わります</li>
            <li>入稿日や下版日を入力し、追加情報があればコメント欄に記入し『登録』をクリックして編集完了</li>
            <li>編集を登録せずに破棄する場合は『戻る』をクリック</li>
            <li>この伝票番号の作業工程を削除する場合は『削除』をクリックすると削除されます<br>削除すると該当伝票番号（製品）の各部署の工程情報も削除されます</li>
            </ol>
          </li>
          <li>工程を作成（部署の作業登録）する　＞その２
            <ol class="lst2">
            <li>その１から引き続き</li>
            <li>カレンダー下部に各部署のボタンがあり、登録したい部署をクリック</li>
            <li>該当部署の作業ボタンが表示され登録する作業ボタンをクリック</li>
            <li>ボタンの上部にチェックボタンが表示され、作業日をクリックしていきます（複数日程の同時登録可能）</li>
            <li>登録する場合は『登録』をクリックします</li>
            <li>登録を削除する場合は『削除』をクリックします<br>チェックマークが付いている日が削除されます</li>
            </ol>
          </li>
          <li>工程を見る
            <ol class="lst2">
            <li>メニュー欄より『作業工程閲覧』をクリック</li>
            <li>伝票番号で検索、その他の項目で検索ができます<br>『検索』をクリックし、検索にヒットすると該当工程が一覧表示させる</li>
            <li>『表示』をクリックすることで部署工程のカレンダーも表示されます</li>
            <li>作業を終えた工程は、■をクリックすると■にマークが付きます</li>
            <li>カレンダーの日付クリックすると該当日の作業一覧が表示されます</li>
            </ol>
          </li>
          </ul>
    </div>

	<div id="version_cnt"><a @click="viewBtn(2)">version 1.0</a></div>
	<div id="tbl_2">
		<table>
		<thead>
			<tr>
			<th>version</th>
			<th>date</th>
			<th>overview</th>
			</tr>
		</thead>
		<tbody>
			<tr><td>1.0</td><td>2023/01/18</td><td>初版</td></tr>
		</tbody>
		</table>
	
	</div>



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


/*
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
*/
</script>
@endsection

