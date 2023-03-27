<?php
use Illuminate\Support\Facades\Storage;
use App\Models\CalendarSquare;


$html_result = "";
$cal_start_ym = "";
$ymd_after_due_date = "";
$ymd_receive_date = "";
$ymd_platemake_date = "";
$editzone = false;
$action_msg .= "mode：".$mode."<br>\n";
//$select_html = !empty($_POST['select_html']) ? $_POST['select_html'] : "Default";

//var_dump($result);
//echo "<br><br>1:\n";
echo "select_html = ".$select_html."<br>\n";
//echo "<br><br>2:\n";

if(isset($result['result_date'])) {
	$resultdate = $result['result_date']['result'];
	//var_dump($resultdate);
	//echo "<br><br>\n";
}
else {
	$action_msg .= "resultにデータがありません<br>\n";
	$resultdate = Array();
}

if(isset($result['result_details'])) {
	$resultdetails = $result['result_details']['result'];
		//var_dump($resultdetails);
		//echo "<br><br>\n";
		//echo $result['result_details']['pcode'];
		//echo "<br><br>\n";
		//echo "wday = ".$result['result_date']['wday'];
		//echo "<br><br>\n";

		
	if(isset($resultdetails)) {
		foreach($resultdetails as $key => $val) {

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
			$markday1 = !empty($result['result_date']['wday']) ? date("Y-m-d", strtotime($result['result_date']['wday'])) : date("Y-m-d");
			$target_day = !empty($after_due_date) ? date("Y-m-1", strtotime($after_due_date)) : date("Y-m-d");
			//echo "markday1 = ".$markday1."<br>\n";
			$cal_start_ym = !empty($target_day) ? date('Y-n', strtotime($target_day . ' -1 month')) : "";
			
			$calendar_squ_data = new CalendarSquare();	// インスタンス作成
			$html_calsqu = $calendar_squ_data->create_calendar( 3, $cal_start_ym, $after_due_date, $markday1);	//開始年月～何か月分
					

		}
	}
	else {
		$action_msg .= "returnがありません<br>\n";
	}




} else {
	$action_msg .= "resultにデータがありません<br>\n";
	$resultdetails = Array();
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
						<h1 class="tstyle">作業工程／詳細</h1>
					</div>
					<!-- main contentns row -->
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
										<button class="gc5 transition1 mgla" type="button" onClick="this.form.reset()">リセット</button>
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
									@forelse ($resultdetails as $val)
										<tr>
											<td class="">
												<button type="button" onClick="clickEvent('searchform','{{ $val->product_code }}','oneView','view','表示','some_search','')">表示</button>
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




							@if ($editzone === true)
							<div id="form1" class="mgt20">
								<input type="hidden" name="mode" id="mode" value="">
								<input type="hidden" name="select_html" id="select_html" value="">
								<input type="hidden" name="serial_code" id="serial_code" value="{{ $serial_code }}">
								<input type="hidden" name="rep_code" id="rep_code" value="{{ $rep_code }}">
								<input type="hidden" name="s_product_code" id="s_product_code" value="{{ $product_code }}">
								<button class="gc5 transition1 mgla" type="button" onClick="clickEvent('updateform','1','Edit','goedit','『 編集 』','product_search','chkwrite')">編集</button>
							</div>
							@endif

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
						

							@if ($editzone === true)
								<div id="form_cnt">
									<div>
										<input type="radio" name="departments_code" value="2" id="departments_code2">
										<label for="departments_code2" class="label transition2" onclick="WORKcollect(2,'情報処理課［制作］')">情報処理課［制作］</label>
									</div>
									<div>
										<input type="radio" name="departments_code" value="3" id="departments_code3">
										<label for="departments_code3" class="label transition2" onclick="WORKcollect(3,'情報処理課［データ］')">情報処理課［データ］</label>
									</div>
									<div>
										<input type="radio" name="departments_code" value="4" id="departments_code4">
										<label for="departments_code4" class="label transition2" onclick="WORKcollect(4,'印刷課１')">印刷課１</label>
									</div>
									<div>
										<input type="radio" name="departments_code" value="5" id="departments_code5">
										<label for="departments_code5" class="label transition2" onclick="WORKcollect(5,'印刷課２')">印刷課２</label>
									</div>
									<div>
										<input type="radio" name="departments_code" value="6" id="departments_code6">
										<label for="departments_code6" class="label transition2" onclick="WORKcollect(6,'加工課１')">加工課１</label>
									</div>
									<div>
										<input type="radio" name="departments_code" value="7" id="departments_code7">
										<label for="departments_code7" class="label transition2" onclick="WORKcollect(7,'加工課２')">加工課２</label>
									</div>
								</div>
								<div id="resultwp"></div>
								<div id="resultbtn"></div>
								<div id="motionbtn"></div>
								
							@endif

						</form>


						<div>{!! $action_msg !!}</div>


					@elseif($select_html === 'oneView')
							<div id="tbl_1" class="">
								<div id="top_cnt">
									<button class="style3 transition1" type="button" onClick="javascript:history.back();">戻る</button>
									<button class="mgla style3 transition1" type="button" onClick="javascript:history.back();">戻る</button>
								</div>
								<table>
									<thead>
										<tr>
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
									@forelse ($resultdetails as $val)
										<tr>
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

						{!! $html_cal_main !!}


					@elseif($select_html === 'dayView')
						<form id="updateform" name="updateform" method="POST">
							<div id="tbl_1" class="">
								<div id="top_cnt">
									<button class="style3 transition1" type="button" onClick="javascript:history.back();">戻る</button>
									<button class="mgla style3 transition1" type="button" onClick="javascript:history.back();">戻る</button>
								</div>
							</div>

							<div id="tbl_1" class="">
								@empty($result['result_date']['f_work_date'])
								<h2 class="color_gray">{{ $result['result_date']['html_f_work_date'] }} 作業なし</h2>
								@else
								<h2 class="color5">{{ $result['result_date']['html_f_work_date'] }}</h2>
								@endempty

								<input type="hidden" name="s_id" id="s_id" value="">
								<input type="hidden" name="s_performance" id="s_performance" value="">
								<input type="hidden" name="s_comment" id="s_comment" value="">
								<input type="hidden" name="mode" id="mode" value="performance_update">
								<table>
									<thead>
										<tr>
											<th class="stylebg1">&nbsp;</th>
											<th class="stylebg1 w20r">部署名</th>
											<th class="stylebg1">作業名</th>
											<th class="stylebg1">実績</th>
											<th class="stylebg1 w20r">進捗</th>
										</tr>
									</thead>
									<tbody>
									@forelse ($resultdate as $val)
										<tr>
											<!--<td class="">{!! date('Y 年 m 月 d 日', strtotime($val->work_date)) !!}</td>-->
											<td class="stylebg2">
												<button id="btn_{{ $val->id }}" class="style5 display_none" type="button" onClick="clickEvent('updateform','{{ $val->id }}','','confirm_work_update','『 更新 』','','')">更新</button>	
												<input type="hidden" class="input_style" name="id" id="id" value="{{ $val->id }}">
												<span id="log_{{ $val->id }}"></span>
											</td>
											<td class="stylebg2">{{ $val->departments_name }}</td>
											<td class="stylebg2">{{ $val->work_name }}</td>
											<td class="stylebg2"><input type="text" class="form_style2" name="performance" id="performance_{{ $val->id }}" value="{{ $val->performance }}"></td>
											<td class="stylebg2">{{ $val->status }}</td>
										</tr>

										<script type="text/javascript">
											const input{{ $val->id }} = document.getElementById('performance_{{ $val->id }}');
											const log{{ $val->id }} = document.getElementById('log_{{ $val->id }}');
											const btn{{ $val->id }} = document.getElementById('btn_{{ $val->id }}');
											input{{ $val->id }}.addEventListener('input', updateValue);
											// addEventListener('change', updateValue)
											function updateValue(e) {
												//log{{ $val->id }}.textContent = e.target.value;
												log{{ $val->id }}.innerHTML = '<span class="color_red">※変更中</span>';
												//log{{ $val->id }}.innerHTML += e.target.value;
												btn{{ $val->id }}.classList.remove("display_none");
											}
										</script>

									@empty
										<tr><td colspan="5">作業なし</td></tr>
									@endforelse
									</tbody>
								</table>
							</div>

							<input type="hidden" class="form_style2" name="performance" id="performance" value="未使用">
							<p id="log"></p>

							<div id="tbl_1" class="">

								<table>
									<thead>
										<tr>
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
									@forelse ($resultdetails as $val)
										<tr>
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
						</form>

							@if($result['result_details']['datacount'] === 1)
								<div class="mgt20">
								{!! $html_calsqu !!}
								</div>
							@endif




						<div id="resultupdate"></div>
						<div id="resultstr"></div>
						<div id="resultlist"><ul></ul></div>

					@endif

					</div>
					<!-- /main contentns row -->



@endsection

@section('jscript')

<script type="text/javascript">
// 未使用↓
//const input = document.querySelector('input');
const input = document.getElementById('performance');
const log = document.getElementById('log');
input.addEventListener('change', updateValue);
function updateValue(e) {
  log.textContent = e.target.value;
}
//未使用↑


function clickEvent(fname,val1,val2,cf,com1,md,smd) {
	var fm = document.getElementById(fname);
	//var tname = document.getElementsByName(val1);
	//Submit値を操作
	//fm.edit_id.value = val;
	//fm.tname.value = val;
	//tname[0].value = val;	//[0]を付けないとundefind

	//alert('clickEvent 引数 = ' + fname + ' 、 ' + tn + ' 、 ' + val + ' 、 ' + cf);

		if(cf == 'confirm') {
			//var Jname = fm.name.value;
			var Js_product_code = fm.s_product_code.value;
			//var result = window.confirm( com1 +'\\n\\n店舗名 : '+ Jname +'\\nコード : '+ Jname_code +'');
			//var result = window.confirm(Jproduct_id + ' ' + com1 + 'します');
			var result = val1;
			if( result ) {
				//document.defineedit.edit_id.value = val;
				//document.defineedit.submit();
				fm.mode.value = md;
				fm.select_html.value = val2;
				fm.action = '/view/search';
				fm.submit();
			}
			else {
				console.log('キャンセルがクリックされました');
			}
		}
		else if(cf == 'goedit') {
				//fm.work_code.value = 'DEL';
				fm.mode.value = md;
				fm.select_html.value = val2;
				fm.action = '/process/search';
				fm.submit();
		}
		else if(cf == 'view') {
				//fm.work_code.value = 'DEL';
				fm.mode.value = md;
				fm.s_product_code.value = val1;
				fm.select_html.value = val2;
				fm.action = '/view/search';
				fm.submit();
		}
		else if(cf == 'confirm_work_update') {
			var Jperformance = document.getElementById('performance_' + val1).value;
			//var Jstatus = fm.status.value;
			//var result = window.confirm( com1 +'\n伝票番号 : '+ Jproduct_code +'');
			var result = window.confirm( com1 +'\n'+ Jperformance +'');
			if( result ) {
				//fm.s_id.value = val1;
				//fm.s_performance.value = Jperformance;
				//fm.action = '/w';
				//fm.submit();
				this.ListUpdate(val1);
			}
		}
		else if(cf == 'process_details_update') {
			var Jcustomer = fm.customer.value;
			var Jproduct_name = fm.product_name.value;
			var Jend_user = fm.end_user.value;
			//var Js_product_code = fm.s_product_code.value;
			//var result = window.confirm( com1 +'\\n\\n店舗名 : '+ Jname +'\\nコード : '+ Jname_code +'');
			var result = window.confirm('得意先 : ' + Jcustomer + '\n' + '品名 : ' + Jproduct_name + '\n' + 'エンドユーザー : ' + Jend_user + '\n\n' + com1 + ' します');
			if( result ) {
				fm.mode.value = md;
				//fm.motion.value = 'reload';
				fm.action = '/process/update';
				fm.submit();
			}
			else {
				console.log('キャンセルがクリックされました');
			}
		}
		else if(cf == 'confirm_update') {
			var Jwork_name = fm.work_name.value;
			var Jdepartments_name = fm.departments_name.value;
			//var Js_product_code = fm.s_product_code.value;
			//var result = window.confirm( com1 +'\\n\\n店舗名 : '+ Jname +'\\nコード : '+ Jname_code +'');
			var result = window.confirm('部署名 : ' + Jdepartments_name + '\n工程 : ' + Jwork_name + '\n' + com1 + 'します');
			if( result ) {
				//fm.mode.value = md;
				fm.motion.value = 'reload';
				fm.action = '/process/insert';
				fm.submit();
			}
			else {
				console.log('キャンセルがクリックされました');
			}
		}
		else if(cf == 'select_workname') {
			document.getElementById('work_name').value = val1;
			var result = window.confirm('result : ' + val2 + '');
			let text = [];
			let obj = JSON.parse(val2);
			obj.forEach(function(element, index3, array){
				//$('#resultwp').prepend('<button class="style5" type="button" >' + element.name + '</button>\n');
				//text.push('<button class="style5" type="button" >' + element.name + '</button>\n');

				/** 日付を文字列にフォーマットする */
				var d = new Date(element.work_date);
				var formatted = 
					`${d.getFullYear()}-` +
					`${(d.getMonth()+1).toString().padStart(2, '0')}-` +
					`${d.getDate().toString().padStart(2, '0')}`
					.replace(/\n|\r/g, '');

				text.push(
				index3 + ':' + element.work_date + ' :' + formatted + '\n'
				);
				document.getElementById('work' + formatted).checked = true;


			});
			document.getElementById('resultstr').innerHTML = text.join('');

			//document.getElementById('work2022-11-09').checked = true;

		}
		else if(cf == 'select_del') {


			//var Jwork_name = fm.work_name.value;
			//var Jdepartments_name = fm.departments_name.value;
			//var Js_product_code = fm.s_product_code.value;
			//value="DEL" id="work_code_del"
			//var result = window.confirm( com1 +'\\n\\n店舗名 : '+ Jname +'\\nコード : '+ Jname_code +'');
			var result = window.confirm('部署名 : ' + val1 + '\n' + com1 + 'します');
			if( result ) {
				//fm.work_code.value = 'DEL';
				fm.mode.value = md;
				fm.action = '/process/insert';
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



var addcount = Number('1');
// 画面を更新する処理
function appendListADD(dataarr,n) {
	//$.each(dataarr, function(index, data) {
		//console.log('appendList in 配列index = ' + index + ' ; data =' + data );
		//$('#list ul').append("No. : " + data.t_number + "<br>名前 : " + data.name + "<br>name_code : " + data.name_code + '');
		//$('#resultupdate').html = ( "message--" + data.e_message );

		var btnid = document.getElementById('btn_' + dataarr.id);
		var logid = document.getElementById('log_' + dataarr.id);

		//document.getElementById('resultupdate').innerHTML = '<div class="txt1">' + dataarr.e_message + '</div>\n';
		const statusv = '<span style="color:green;">OK</span>';
		if(dataarr.result_msg == 'OK') {
			//$('#resultlist ul').prepend('<li><span>' + index + '</span>&emsp;<span class="txtcolor1">&#10004;</span>&emsp;No.&ensp;<span class="dtnum">' + data + '</span> ' + statusv + '</li>\n');
			$('#resultlist ul').prepend('<li><span>' + dataarr.id + '</span>&emsp;<span class="dtnum">' + dataarr.e_message + '</span> ' + statusv + '</li>\n');
			
			//document.getElementById('btn_cnt_new' + data.listcount).innerHTML = '<span class="color_green">' + '<button class="style5" type="button" disabled>登録完了</button>' + '</span>'+
			//'<button class="style3" type="button" onClick="clickEvent(\'setprocess\','+ n +',\'\',\'confirm_process\',\'下記の工程を作成します\',\'\',\'\')">工程作成</button>';
			//$('#resultstr').prepend('<tr><td>' + data.listcount + '</td><td class="txtcolor1">&#10004;</td><td>No.&ensp;<span class="dtnum">' + data.product_code + '</span></td><td>' + statusv + '</td></tr>\n');

			btnid.classList.add("display_none");
			logid.innerHTML = '<span class="color_green">更新済</span>';

		}
		else {
			statusv = '<span style="color:red;">NG</span>';
			$('#resultlist ul').prepend('<li><span>' + dataarr.id + '</span>&emsp;<span class="dtnum">' + dataarr.e_message + '</span> ' + statusv + '</li>\n');
		}
		addcount = addcount + 1;
	//});
}


function ListUpdate(n) {
	var Jperformance = document.getElementById('performance_' + n).value;
	//var Jcomment = document.getElementById('comment_' + n).value;
	var Mode = document.getElementById('mode').value;

	//var Jlistcount = document.getElementById('listcount' + n).value;
	//var Jproduct_code = document.getElementById('product_code' + n).value;
	//var Jserial_code = document.getElementById('serial_code' + n).value;
	//var Jrep_code = document.getElementById('rep_code' + n).value;
	//var Jafter_due_date = document.getElementById('after_due_date' + n).value;
	//var Jcustomer = document.getElementById('customer' + n).value;
	//var Jproduct_name = document.getElementById('product_name' + n).value;
	//var Jend_user = document.getElementById('end_user' + n).value;
	//var Jquantity = document.getElementById('quantity' + n).value;
	//var Jcomment = document.getElementById('comment' + n).value;
	//var collectdate = document.getElementById('collect_date').value;
	//var Mode = document.getElementById('mode').value;
	//var Wpdate = document.getElementById('today').value;
	//console.log("mode :" + Mode);
	//console.log("Jproduct_code :" + Jproduct_code);
	const res = axios.post("/work/update", {
		s_id: n,
		performance: Jperformance,
		//comment: Jcomment,
		mode: Mode,
	})
	.then(response => {
		appendListADD(response.data,n);
		
	})
	.catch(error => {
		console.log('error message = ' + error.message );
	});
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




	var addcount = Number('1');
// 画面を更新する処理
function appendListWORK(dataarr) {
	$.each(dataarr, function(index, data) {
		//console.log('appendList in 配列index = ' + index);
		//console.log('appendList addcount ' + addcount);
		const res = data.result[index];

		document.getElementById('resultupdate').innerHTML = '<div class="txt1">' + data.e_message + '</div>\n';
		var statusv = '<span style="color:green;">OK</span>';
		if(data.result_msg === 'OK') {
			if(data.chk_status === 'esse') {
				statusv = '<span style="color:orange;">上書き</span>';
			}
			//$('#resultlist ul').prepend('<li><span>' + addcount + '</span>&emsp;<span class="txtcolor1">&#10004;</span>&emsp;No.&ensp;<span class="dtnum">' + data.product_code + '</span> ' + statusv + '</li>\n');
			
			//document.getElementById('resultwp').innerHTML = '<span class="color_green">' + '<button class="style5" type="button" disabled>' + res.name + '</button>' + '</span>';
			//$('#result_new_view').prepend('<tr><td>' + data.listcount + '</td><td class="txtcolor1">&#10004;</td><td>No.&ensp;<span class="dtnum">' + data.product_code + '</span></td><td>' + statusv + '</td></tr>\n');
			console.log('appendListWORK in depa ' + data.department);
			let text = [];
			data.result.forEach(function(element, index2, array){
				//$('#resultwp').prepend('<button class="style5" type="button" >' + element.name + '</button>\n');
				//text.push('<button class="style5" type="button" >' + element.name + '</button>\n');

				text.push(
				'<div id="workname">\n' +
				'	<input type="radio" name="work_code" value="' + element.id + '" id="work_code' + index2 + '">\n' + 
				'	<label for="work_code' + index2 + '" class="label transition2" onclick="WORKDATEchecked(\'\',\'' + element.name + '\',\'\',\'select_workname\',\'\',\'' + element.id + '\',\'' + data.department + '\')">' + element.name + '</label>\n' +
				'</div>\n'
				);



			});
			text.push(
				'<div id="workname">\n' +
				'	<input type="radio" name="work_code" value="900" id="work_code900">\n' + 
				'	<label for="work_code900" class="label transition2" onclick="WORKDATEchecked(\'\',\'その他\',\'\',\'select_workname\',\'\',\'900\',\'' + data.department + '\')">その他</label>\n' +
				'</div>\n'
				);

			document.getElementById('resultbtn').innerHTML = text.join('');
			document.getElementById('motionbtn').innerHTML = "";



		}
		else {
			//statusv = '<span style="color:red;">NG</span>';
			//$('#resultlist ul').prepend('<li><span>' + addcount + '</span>&emsp;<span class="txtcolor3">&#10006;</span>&emsp;No.&ensp;<span class="dtnum">' + data.product_code + '</span> ' + statusv + '</li>\n');
			document.getElementById('resultbtn').innerHTML = "作業一覧を取得できませんでした";

		}
		addcount = addcount + 1;
	});
}

function WORKcollect(n,dn) {
	var Mode = document.getElementById('mode').value;
	//var Jdepartments_name = document.getElementById('departments_name' + n).value;
	document.getElementById('departments_name').value = dn;
	//console.log('WORKcollect in depa ' + n);

	var details = {name: "pro", team: ""};
	//var Wpdate = document.getElementById('today').value;
	console.log("mode :" + Mode);
	const res = axios.post("/process/workget", {
		department: n,
		departments_name: dn,
		mode: Mode,
		details: details,
	})
	.then(response => {
		appendListWORK(response.data);
		this.chkOnOff('.chkonff');
		
	})
	.catch(error => {
		window.error(error.response);
	});
}



// 部署における作業日の取得
function appendWORKDATE(dataarr) {
	//console.log('appendWORKDATE in ' + dataarr[0].result_msg);
	let text2 = [];

	$.each(dataarr, function(index3, data) {
		//const res = data.wd_result[index3];
		//console.log('appendWORKDATE in result_msg ok' + data.result_msg);

		document.getElementById('resultupdate').innerHTML = '<div class="txt1">' + data.e_message + '</div>\n';
		if(data.result_msg === 'OK') {
			let text = [];
			
			data.wd_result.forEach(function(element, index4, array){

				/*
				text.push(
				'<div id="workname">\n' +
				'	<input type="radio" name="work_code" value="' + element.id + '" id="work_code' + index2 + '">\n' + 
				'	<label for="work_code' + index2 + '" class="label transition2" onclick="clickEvent(\'\',\'' + element.name + '\',\'\',\'select_workname\',\'\',\'\',\'\')">' + element.name + '</label>\n' +
				'</div>\n'
				);
				*/

				var d = new Date(element.work_date);
				var formatted = 
					`${d.getFullYear()}-` +
					`${(d.getMonth()+1).toString().padStart(2, '0')}-` +
					`${d.getDate().toString().padStart(2, '0')}`
					.replace(/\n|\r/g, '');

				text.push(
				index4 + ':' + element.work_date + ' :' + formatted + '\n'
				);
				document.getElementById('work' + formatted).checked = true;




			});

			document.getElementById('resultstr').innerHTML = text.join('');


		}
		else {
			//statusv = '<span style="color:red;">NG</span>';
			//$('#resultlist ul').prepend('<li><span>' + addcount + '</span>&emsp;<span class="txtcolor3">&#10006;</span>&emsp;No.&ensp;<span class="dtnum">' + data.product_code + '</span> ' + statusv + '</li>\n');
			document.getElementById('resultstr').innerHTML = "作業日を取得できませんでした";

		}

		text2.push(
				'<div id="workname">\n' +
				'	<input type="radio" name="work_code" value="DEL" id="work_code_del">\n' + 
				'	<label for="work_code_del" class="label del transition2" onclick="clickEvent(\'addprocessform\',\'' + data.department + '\',\'\',\'select_del\',\'削除\',\'delete\',\'\')">削除</label>\n' +
				'<button class="" type="button" onClick="clickEvent(\'addprocessform\',\'1\',\'1\',\'confirm_update\',\'『 登録 』\',\'product_search\',\'chkwrite\')">登録</button>\n' +
				'</div>\n'
			);
		document.getElementById('motionbtn').innerHTML = text2.join('');

	});
}

function WORKDATEchecked(fname,val1,val2,cf,com1,wc,dc) {
	document.getElementById('work_name').value = val1;

	var Js_product_code = document.getElementById('s_product_code').value;
	//var Jdepartments_code = document.getElementById('departments_code').value;
	//var Jwork_code = document.getElementById('work_code').value;
	var Mode = document.getElementById('mode').value;
	//var Jdepartments_name = document.getElementById('departments_name' + n).value;
	//document.getElementById('departments_name').value = dn;
	//var details = {name: "pro", team: ""};
	//var Wpdate = document.getElementById('today').value;
	//console.log("mode :" + Mode);
	/*
	let check_onoff = document.querySelectorAll(".chkonff");
	for (let i in check_onoff) {
		if (check_onoff.hasOwnProperty(i)) {
			check_onoff[i].checked = false;
		}
	}
	*/
	this.chkOnOff('.chkonff');	// 最初に作業日のチェックを全て外す（外すチェックのclassを指定する）
	const res = axios.post("/process/wdget", {
		s_product_code: Js_product_code,
		departments_code: dc,
		work_code: wc,
		mode: Mode
	})
	.then(response => {
		console.log('WORKDATEchecked then ' + response.data[0].result_msg);
		appendWORKDATE(response.data);
		
	})
	.catch(error => {
		console.log('WORKDATEchecked catch ' + error.response);
		//window.error(error.response);
	});
}



























	function formReset(fname) {
		//var fm = document.getElementById(fname);
		//fm.reset();
		var textForm = document.getElementById(fname);
		textForm.value = '';
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

