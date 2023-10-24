@extends('layouts.main')
@section('content')

<div id="home_cnt">
					<div id="title_cnt">
						<h1 class="tstyle">伝票番号登録</h1>
					</div>

					
					<!-- main contentns row -->
					<div id="maincontents">
						<div id="search_fcnt">
							<h4>伝票番号検索</h4>

							<form id="searchform" name="searchform" method="POST">
								<input type="hidden" name="mode" id="mode" value="product_search">
								<input type="hidden" name="submode" id="submode" value="chkwrite">
								<input type="hidden" name="motion" id="motion" value="">

								<div id="form1">
								<input type="number" class="form_style1 w10e" name="s_product_id" id="s_product_id" value="">
								<button class="" type="button" onClick="SEARCHcollect()">検索</button>
								<!--<button class="" type="button" onClick="clickEvent('searchform','1','1','confirm','『 検索 』','product_search','chkwrite')">検索</button>-->
								</div>
								@csrf 
							</form>
						</div>
					</div>
					<!-- /main contentns row -->

					<div id="tbl_1">
					<form id="newform" name="newform" method="POST">
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
							<tbody id="result_search_view">
							@php //echo $html_result;
							@endphp
							</tbody>
						</table>
					</form>
					<form id="setprocess" name="setprocess" method="POST">
						<input type="hidden" name="s_product_code" value="" id="s_product_code">
						@csrf
					</form>
					</div>


					<div id="tbl_2" class="mgt40">
					<form id="new2form" name="new2form" method="POST">
						<table>
							<thead>
							<tr>
								<th>&emsp;</th>
								<th>&emsp;</th>
								<th>伝票番号</th>
								<th>登録</th>
							</tr>
							</thead>
							<tbody id="result_new_view">
							</tbody>
						</table>
					</form>
					</div>

					<div id="form1" class="mgt20">
						<form id="searchform2" name="searchform2" method="POST">
									<input type="hidden" name="mode" id="mode" value="all_search">
									<input type="hidden" name="submode" id="submode" value="chkwrite">
									<input type="hidden" name="motion" id="motion" value="">

										<button class="" type="button" onClick="Allcollect()">まとめて取得</button>
										&emsp;&emsp;<span>作業工程管理システム&emsp;<a href="http://192.168.0.93" target="_blank">http://192.168.0.93</a>&emsp;<a href="http://192.168.0.94" target="_blank">http://192.168.0.94</a> に登録されている納期が明日以降のデータを『まとめて取得』できます</span>
									
									@csrf 
						</form>
					</div>

					<div id="resultupdate" class="mgt40"></div>
					<div id="resultlist"><ul class="list-group"></ul></div>

					@isset($result['e_message'])<div id="error"> {!! $result['e_message'] !!} </div>@endisset

					<div>{{ $action_msg }}</div>
					<div>
						<div>{{ $mode }}</div>
					</div>


@endsection

@section('jscript')

<script type="text/javascript">
	function clickEvent(fname,val1,val2,cf,com1,md,smd) {
	var fm = document.getElementById(fname);
	//var tname = document.getElementsByName(val1);
	//fm.tname.value = val;
	//tname[0].value = val;	//[0]を付けないとundefind
	//alert('clickEvent 引数 = ' + fname + ' 、 ' + tn + ' 、 ' + val + ' 、 ' + cf);

		if(cf == 'confirm') {
			var Js_product_id = fm.s_product_id.value;
			//var result = window.confirm(Js_product_id + ' ' + com1 + 'します');
			var result = val1;
			if( result ) {
				fm.mode.value = md;
				fm.submit();
			}
			else {
				console.log('キャンセルがクリックされました');
			}
		}
		else if(cf == 'confirm_process') {
			var Jproduct_code = document.getElementById('product_code' + val1).value;
			//var Jstatus = fm.status.value;
			var result = window.confirm( com1 +'\n伝票番号 : '+ Jproduct_code +'');
			if( result ) {
				fm.s_product_code.value = Jproduct_code;
				fm.action = '/process/search';
				fm.submit();
			}
		}
		else if(cf == 'confirm_pay') {
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


	var appendcount = Number('1');
// 画面を更新する処理
function appendList(arrdata) {
	$.each(arrdata, function(index, data) {
		console.log('appendList in 配列キー ' + index);
		//console.log('appendList appendcount ' + appendcount);
		//$('#list ul').append("No. : " + data.t_number + "<br>名前 : " + data.name + "<br>name_code : " + data.name_code + '');
		//$('#resultupdate').html = ( "message--" + data.e_message );
		const res = data.result[index];
		document.getElementById('resultupdate').innerHTML = '<div class="txt1">' + data.e_message + '</div>\n';
		var statusv = '<span style="color:green;">OK</span>';
		var html_after_due_date = '';
		if(data.result_msg) {
			var BUTTON_collect = '';
			if(data.result_msg == 'OK') {
				BUTTON_collect = '<button class="style3" type="button" onClick="NEWcollect('+ appendcount +')">登録</button>';
			}
			if(data.result_msg == 'already') {
				BUTTON_collect = '<button class="style4" type="button" disabled>登録済</button>';
			}
			if(data.result_msg == 'NOnippou') {
				BUTTON_collect = '<button class="style4" type="button" disabled>日報登録なし</button>';
			}
			var r_quantity = res.quantity ? res.quantity : '';
			if(data.chk_status === 'esse') {
				statusv = '<span style="color:orange;">上書き</span>';
			}
			$('#resultlist ul').prepend('<li><span>' + appendcount + '</span>&emsp;<span class="txtcolor1">&#10004;</span>&emsp;No.&ensp;<span class="dtnum">' + data.s_product_id + '</span> ' + statusv + '</li>\n');

			$('#result_search_view').prepend(
				'<tr>'+
				'<td><input type="hidden" name="listcount" id="listcount'+ appendcount +'" value="' + appendcount + '">'+ appendcount +' <span id="btn_cnt_new'+ appendcount +'">'+ BUTTON_collect +'</span></td>'+
				'<td><input type="hidden" name="product_code" id="product_code'+ appendcount +'" value="' + res.product_id + '">' + res.product_id + '</td>'+
				'<td><input type="hidden" name="after_due_date" id="after_due_date'+ appendcount +'" value="' + res.after_due_date + '">' + data.html_after_due_date + '</td>'+
				'<td><input type="hidden" name="customer" id="customer'+ appendcount +'" value="' + res.customer + '">' + data.result[index].customer + '</td>'+
				'<td><input type="hidden" name="product_name" id="product_name'+ appendcount +'" value="' + res.product_name + '">' + res.product_name + '</td>'+
				'<td><input type="hidden" name="end_user" id="end_user'+ appendcount +'" value="' + res.end_user + '">' + res.end_user + '</td>'+
				'<td><input type="hidden" name="quantity" id="quantity'+ appendcount +'" value="' + r_quantity + '">' + r_quantity + ''+
					'<input type="hidden" name="serial_code" id="serial_code'+ appendcount +'" value="' + res.serial_id + '">'+
					'<input type="hidden" name="rep_code" id="rep_code'+ appendcount +'" value="' + res.rep_id + '">'+
					'<input type="hidden" name="comment" id="comment'+ appendcount +'" value="' + res.comment + '">'+
				'</td>'+
				'</tr>'
			);


		}
		else {
			statusv = '<span style="color:red;">NG</span>';
			$('#resultlist ul').prepend('<li><span>' + appendcount + '</span>&emsp;<span class="txtcolor3">&#10006;</span>&emsp;No.&ensp;<span class="dtnum">' + data.s_product_id + '</span> ' + statusv + '</li>\n');
		}
		appendcount = appendcount + 1;
		//index += 1;
	});
}
// エラー処理
function error(error) {
    $('#list').empty();
    $('#error').append(error);
}



// 基本的にはresponse.dataにデータが返る
function SEARCHcollect() {
	var Ps_product_id = document.getElementById('s_product_id').value;
	var Pmotion = document.getElementById('motion').value;
	var Psubmode = document.getElementById('submode').value;
	var Mode = document.getElementById('mode').value;
	//var Wpdate = document.getElementById('today').value;
	console.log("Mode :" + Mode);
	const res = axios.post("/regi/search", {
		s_product_id: Ps_product_id,
		motion: Pmotion,
		submode: Psubmode,
		mode: Mode,
	})
	.then(response => {
		appendList(response.data);
		
	})
	.catch(error => {
		console.log('error message = ' + error.message );
		//window.error(error.response);
	});
}





var addcount = Number('1');
// 画面を更新する処理
function appendListADD(dataarr,n) {
	$.each(dataarr, function(index, data) {
		//console.log('appendList in 配列index = ' + index);
		//console.log('appendList addcount ' + addcount);
		//$('#list ul').append("No. : " + data.t_number + "<br>名前 : " + data.name + "<br>name_code : " + data.name_code + '');
		//$('#resultupdate').html = ( "message--" + data.e_message );

		document.getElementById('resultupdate').innerHTML = '<div class="txt1">' + data.e_message + '</div>\n';
		var statusv = '<span style="color:green;">OK</span>';
		if(data.result_msg === 'OK') {
			if(data.chk_status === 'esse') {
				statusv = '<span style="color:orange;">上書き</span>';
			}
			//$('#resultlist ul').prepend('<li><span>' + addcount + '</span>&emsp;<span class="txtcolor1">&#10004;</span>&emsp;No.&ensp;<span class="dtnum">' + data.product_code + '</span> ' + statusv + '</li>\n');
			
			document.getElementById('btn_cnt_new' + data.listcount).innerHTML = '<span class="color_green">' + '<button class="style5" type="button" disabled>登録完了</button>' + '</span>'+
			'';
			//'<button class="style3" type="button" onClick="clickEvent(\'setprocess\','+ n +',\'\',\'confirm_process\',\'下記の工程を作成します\',\'\',\'\')">工程作成</button>';
			$('#result_new_view').prepend('<tr><td>' + data.listcount + '</td><td class="txtcolor1">&#10004;</td><td>No.&ensp;<span class="dtnum">' + data.product_code + '</span></td><td>' + statusv + '</td></tr>\n');



		}
		else {
			statusv = '<span style="color:red;">NG</span>';
			$('#resultlist ul').prepend('<li><span>' + addcount + '</span>&emsp;<span class="txtcolor3">&#10006;</span>&emsp;No.&ensp;<span class="dtnum">' + data.product_code + '</span> ' + statusv + '</li>\n');
		}
		addcount = addcount + 1;
	});
}


function NEWcollect(n) {
	var Jlistcount = document.getElementById('listcount' + n).value;
	var Jproduct_code = document.getElementById('product_code' + n).value;
	var Jserial_code = document.getElementById('serial_code' + n).value;
	var Jrep_code = document.getElementById('rep_code' + n).value;
	var Jafter_due_date = document.getElementById('after_due_date' + n).value;
	var Jcustomer = document.getElementById('customer' + n).value;
	var Jproduct_name = document.getElementById('product_name' + n).value;
	var Jend_user = document.getElementById('end_user' + n).value;
	var Jquantity = document.getElementById('quantity' + n).value;
	var Jcomment = document.getElementById('comment' + n).value;
	//var collectdate = document.getElementById('collect_date').value;
	var Mode = document.getElementById('mode').value;
	var details = {name: "Ronaldo", team: "Juventus"};
	//var Wpdate = document.getElementById('today').value;
	console.log("mode :" + Mode);
	console.log("Jproduct_code :" + Jproduct_code);
	const res = axios.post("/regi/new", {
		listcount: Jlistcount,
		product_code: Jproduct_code,
		serial_code: Jserial_code,
		rep_code: Jrep_code,
		customer: Jcustomer,
		product_name: Jproduct_name,
		end_user: Jend_user,
		quantity: Jquantity,
		after_due_date: Jafter_due_date,
		comment: Jcomment,
		mode: Mode,
		details: details,
	})
	.then(response => {
		appendListADD(response.data,n);
		
	})
	.catch(error => {
		console.log('error message = ' + error.message );
	});
}





function appendAll(dataarr) {
	$.each(dataarr, function(index, data) {
		//console.log('appendList in 配列index = ' + index);
		//console.log('appendList addcount ' + addcount);
		//$('#list ul').append("No. : " + data.t_number + "<br>名前 : " + data.name + "<br>name_code : " + data.name_code + '');
		//$('#resultupdate').html = ( "message--" + data.e_message );

		document.getElementById('resultupdate').innerHTML = '<div class="txt1">' + data.e_message + '</div>\n';
		var statusv = '<span style="color:green;">OK</span>';
		if(data.result_msg === 'OK') {
			
			$('#result_new_view').prepend('<tr><td>' + data.count + ' 件</td><td class="txtcolor1">&#10004;</td><td>No.&ensp;<span class="dtnum">' + '</span></td><td>' + statusv + '</td></tr>\n');

		}
		else {
			statusv = '<span style="color:red;">NG</span>';
			$('#resultlist ul').prepend('<li><span>' + addcount + '</span>&emsp;<span class="txtcolor3">&#10006;</span>&emsp;No.&ensp;<span class="dtnum">' + data.product_code + '</span> ' + statusv + '</li>\n');
		}
		addcount = addcount + 1;
	});
}





function Allcollect() {
	var Pmotion = document.getElementById('motion').value;
	var Psubmode = document.getElementById('submode').value;
	var Mode = document.getElementById('mode').value;
	//var Wpdate = document.getElementById('today').value;
	console.log("Mode :" + Mode);
	const res = axios.post("/regi/all", {
		motion: Pmotion,
		submode: Psubmode,
		mode: Mode,
	})
	.then(response => {
		appendAll(response.data);
		
	})
	.catch(error => {
		console.log('error message = ' + error.message );
		//window.error(error.response);
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


</script>
@endsection

