<?php
use Illuminate\Support\Facades\Storage;
use App\Models\CalendarSquare;


$html_result = "";
$cal_start_ym = "";
$ymd_after_due_date = "";
$ymd_receive_date = "";
$ymd_platemake_date = "";
$action_msg .= "mode：".$mode."<br>\n";
//$select_html = !empty($_POST['select_html']) ? $_POST['select_html'] : "Default";
$select_html = !empty($select_html) ? $select_html : "Default";
//echo "select_html = ".$select_html."<br>\n";

if(isset($result['result'])) {
	$resultdata = $result['result'];
	$resultgetworkdata = $result['result_getwork'];
	$arrdata = json_decode($resultgetworkdata[0]['result'], true);
	//var_dump($arrdata);
	//echo "<br>\n";
	/*
	foreach($arrdata AS $wkey => $wval){
		echo "key=".$wkey." val=".$wval['name']."<br>\n";
	}
	*/

	//var_dump($resultdata);
	//echo "<br>\n";
	//var_dump($resultgetworkdata);
	
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
			$work_need_days = $val->work_need_days;
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


function WorkItem($arrdata,$valwkcode,$num,$pcode)	{
	// 作業（加工）のセレクトボックスHTML

	$html_select_work = "";
	$html_select_work .= "<select class='' name='".$pcode."_wkcode".$num."' id='".$pcode."_wkcode".$num."'>\n";
	$html_select_work .= "	<option></option>\n";
		foreach ($arrdata as $arrgval)	{
			if ($arrgval['id'] == $valwkcode) {
				$html_select_work .= "<option value='".$arrgval['id']."' selected>".$arrgval['name']."</option>\n";
			} else {
				$html_select_work .= "<option value='".$arrgval['id']."'>".$arrgval['name']."</option>\n";
			}
		}
	$html_select_work .= "\n</select>\n";
	return $html_select_work;

}

function WorkStr($arrdata,$valwkcode,$num,$pcode)	{
	// 作業（加工）のテキスト
	$html_text_work = "";
		foreach ($arrdata as $arrgval)	{
			if ($arrgval['id'] == $valwkcode) {
				$html_text_work .= "".$arrgval['name']."";
			} 
		}
	return $html_text_work;
}


?>
@extends('layouts.main')
@section('content')
				<div id="contents_area">
					<div id="title_cnt">
						<h1 class="tstyle">作業工程／検索・閲覧</h1>
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
							<input type="hidden" name="status" id="status" value="">
							<input type="hidden" name="wkcom01" id="wkcom01" value="">
							<input type="hidden" name="wkcom02" id="wkcom02" value="">
							<input type="hidden" name="wkcom03" id="wkcom03" value="">
							<input type="hidden" name="wkcom04" id="wkcom04" value="">
							<input type="hidden" name="wkcom05" id="wkcom05" value="">
							<input type="hidden" name="wkcode01" id="wkcode01" value="">
							<input type="hidden" name="wkcode02" id="wkcode02" value="">
							<input type="hidden" name="wkcode03" id="wkcode03" value="">
							<input type="hidden" name="wkcode04" id="wkcode04" value="">
							<input type="hidden" name="wkcode05" id="wkcode05" value="">
							<input type="hidden" name="oldlog" id="oldlog" value="">
							<div id="tbl_1" class="mgt10 w1920">
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
											<th>印刷開始日</th>
											<!--<th>加工作業必要日数</th>-->
											<th>加工作業1</th>
											<th>作業メモ1</th>
											<th>加工作業2</th>
											<th>作業メモ2</th>
											<th>加工作業3</th>
											<th>作業メモ3</th>
											<th>加工作業4</th>
											<th>作業メモ4</th>
											<th>加工作業5</th>
											<th>作業メモ5</th>
											<th></th>
											<th>進捗</th>
											<th>コメント</th>
										</tr>
									</thead>
									<tbody>
									@forelse ($resultdata as $val)
										@php 
											if($val->status !== "1") {
												$status_str = "<span class='nbr'>進行中</span><button class=\"style5\" type=\"button\" onClick=\"clickEvent('updateform','$val->product_code','1','update_status','下記の工程の作業を終了します','process_status_change','')\">完了</button>";
												$css1 = "";
											}
											else {
												$status_str = "<span class='nbr'>作業終了</span>";
												$css1 = "gc9";
											}

											$wkakou_btn = "<button class=\"style5\" type=\"button\" onClick=\"clickEvent('updateform','$val->product_code','1','update_wkakou','加工作業を更新します','kwupdate','')\">更新</button>";

											$js_html = <<<EOF
											<script type="text/javascript">
												const inputcode1{$val->product_code} = document.getElementById('{$val->product_code}_wkcode01');
												const inputcom1{$val->product_code} = document.getElementById('{$val->product_code}_wkcom01');
												const inputcode2{$val->product_code} = document.getElementById('{$val->product_code}_wkcode02');
												const inputcom2{$val->product_code} = document.getElementById('{$val->product_code}_wkcom02');
												const inputcode3{$val->product_code} = document.getElementById('{$val->product_code}_wkcode03');
												const inputcom3{$val->product_code} = document.getElementById('{$val->product_code}_wkcom03');
												const inputcode4{$val->product_code} = document.getElementById('{$val->product_code}_wkcode04');
												const inputcom4{$val->product_code} = document.getElementById('{$val->product_code}_wkcom04');
												const inputcode5{$val->product_code} = document.getElementById('{$val->product_code}_wkcode05');
												const inputcom5{$val->product_code} = document.getElementById('{$val->product_code}_wkcom05');
												const log{$val->product_code} = document.getElementById('log_{$val->product_code}');
												const bgtag{$val->product_code} = document.getElementById('logbg_{$val->product_code}');
												//const btn{$val->product_code} = document.getElementById('btn_{$val->product_code}');
												inputcode1{$val->product_code}.addEventListener('input', updateValue);
												inputcom1{$val->product_code}.addEventListener('input', updateValue);
												inputcode2{$val->product_code}.addEventListener('input', updateValue);
												inputcom2{$val->product_code}.addEventListener('input', updateValue);
												inputcode3{$val->product_code}.addEventListener('input', updateValue);
												inputcom3{$val->product_code}.addEventListener('input', updateValue);
												inputcode4{$val->product_code}.addEventListener('input', updateValue);
												inputcom4{$val->product_code}.addEventListener('input', updateValue);
												inputcode5{$val->product_code}.addEventListener('input', updateValue);
												inputcom5{$val->product_code}.addEventListener('input', updateValue);
												// addEventListener('change', updateValue)
												function updateValue(e) {
													const oldpcode = document.getElementById('oldlog').value;
													const oldpcid = document.getElementById('log_' + oldpcode);
													const oldbgtag = document.getElementById('logbg_' + oldpcode);
													console.log('oldpcode=' + oldpcode + ' oldpcid=' + oldpcid);
													if(oldpcid)	{
														oldpcid.innerHTML = '';
														oldbgtag.innerHTML = '';
													}
													//log{$val->id}.textContent = e.target.value;
													log{$val->product_code}.innerHTML = '<span class="color_red">※変更中</span> 『更新』で保存できます';
													bgtag{$val->product_code}.innerHTML = '<span class="">&emsp;</span>';
													//log{$val->id}.innerHTML += e.target.value;
													//btn{$val->product_code}.classList.remove("display_none");
													document.getElementById('oldlog').value = {$val->product_code};
													//var oldlog = "log{$val->product_code}";
													//console.log('oldlogend=' + oldlog);
												}
											</script>
											EOF;


										@endphp
										<tr class="{!! $css1 !!}">
											<td class="nbr">
											<span class="bgtag" id="logbg_{{ $val->product_code }}"></span>
										@if($val->category === 'c1')
											<button type="button" onClick="clickEvent('updateform','{{ $val->product_code }}','oneView','otherserverview','c1表示','some_search','c1')">{!! $val->category !!}表示</button>
										@elseif($val->category === 'c2')
											<button type="button" onClick="clickEvent('updateform','{{ $val->product_code }}','oneView','otherserverview','c2表示','some_search','c2')">{!! $val->category !!}表示</button>
										@else
											<button type="button" onClick="clickEvent('updateform','{{ $val->product_code }}','oneView','view','表示','some_search','')" class="style3">表示</button>
										@endif

												<!--<button type="button" onClick="clickEvent('updateform','{{ $val->product_code }}','oneView','view','表示','some_search','')">表示</button>-->
												<!--<button class="style5" type="button" onClick="clickEvent('updateform','{{ $val->product_code }}','','confirm_process','下記の工程を編集します','','')">編集</button>-->
											</td>
											<td class="">{{ $val->product_code }}</td>
											<td class="nbr">{!! date('Y-m-d', strtotime($val->after_due_date)) !!}</td>
											<td class="nbr">{{ $val->customer }}</td>
											<td class="nbr">{{ $val->product_name }}</td>
											<td class="nbr">{{ $val->end_user }}</td>
											<td class="nbr">{{ $val->quantity }}</td>
											<td class="nbr">@php echo isset($val->receive_date) ? date('Y-m-d', strtotime($val->receive_date)) : ""; @endphp</td>
											<!--<td class="">@php echo isset($val->platemake_date) ? date('Y-m-d', strtotime($val->platemake_date)) : ""; @endphp</td>-->
											<!--<td class="">{{ $val->work_need_days }}</td>-->
											<td class="position_relative">@php echo WorkItem($arrdata,$val->wkcode01,'01',$val->product_code) @endphp<span class="comtext" id="log_{{ $val->product_code }}"></span></td>
											<td class=""><input type="text" class="w14e" name="{{$val->product_code}}_wkcom01" id="{{$val->product_code}}_wkcom01" value="{{$val->wkcom01}}"></td>
											<td class="">@php echo WorkItem($arrdata,$val->wkcode02,'02',$val->product_code) @endphp</td>
											<td class=""><input type="text" class="w14e" name="{{$val->product_code}}_wkcom02" id="{{$val->product_code}}_wkcom02" value="{{$val->wkcom02}}"></td>
											<td class="">@php echo WorkItem($arrdata,$val->wkcode03,'03',$val->product_code) @endphp</td>
											<td class=""><input type="text" class="w14e" name="{{$val->product_code}}_wkcom03" id="{{$val->product_code}}_wkcom03" value="{{$val->wkcom03}}"></td>
											<td class="">@php echo WorkItem($arrdata,$val->wkcode04,'04',$val->product_code) @endphp</td>
											<td class=""><input type="text" class="w14e" name="{{$val->product_code}}_wkcom04" id="{{$val->product_code}}_wkcom04" value="{{$val->wkcom04}}"></td>
											<td class="">@php echo WorkItem($arrdata,$val->wkcode05,'05',$val->product_code) @endphp</td>
											<td class=""><input type="text" class="w14e" name="{{$val->product_code}}_wkcom05" id="{{$val->product_code}}_wkcom05" value="{{$val->wkcom05}}"></td>
											<td class="">@php echo $wkakou_btn; @endphp</td>
											<td class="nbr">@php echo $status_str; @endphp</td>
											<td class="mw20e">{{ $val->comment }}</td>
										</tr>
										@php echo $js_html; @endphp
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
							//echo $html_cal_main;
						@endphp
						


						</form>

						@if($result['datacount'] === 1)
						<div class="mgt20">
							{!! $html_calsqu !!}
						</div>
						@endif
						<div>{!! $action_msg !!}</div>


					@elseif($select_html === 'oneView')
							<div id="tbl_1" class="">
								<div id="top_cnt">
									<div>
										<button class="style3 transition1" type="button" onClick="javascript:history.back();">戻る</button>
										<button class="style3 transition1" type="button" onClick="javascript:location.reload();">描画更新</button>
									</div>

									<div id="confirm_area" class="">
									<input type="hidden" name="status" id="status" value="">
									<!--{!! isset($status) ? $status:""; !!}-->
										@if(isset($resultlog[0]->work_date))
										<div class="btn_result b1">再確定待ち</div>
										<div class="result_log">
											<table>
												<tr>
													<th>内容</th><th>作業日</th><th>部署</th><th>作業</th><th>更新日</th>
												</tr>
												@forelse ($resultlog as $val)
												<tr>
													<td class="{{ $val->motion == '削除' ? 'color2' : 'color1'}}">{{ $val->motion }}</td>
													<td class="">{!! date('Y年m月d日', strtotime($val->work_date)) !!}</td>
													<td class="">{{ $val->departments_name }}</td>
													<td class="">{{ $val->work_name }}</td>
													<td class="">@php echo isset($val->created_at) ? date('Y年m月d日', strtotime($val->created_at)) : ""; @endphp</td>
												</tr>
												@empty
												<tr><td colspan="5">変更はありません</td></tr>
												@endforelse
											</table>
										</div>
										@else
											@if(isset($status))
												@if($status == "REC")
													<div class="btn_result">工程確定済み</div>
												@elseif($status == "1")
													<div class="btn_result gc10">終了</div>
												@else
													<!--<div class="btn_result">未確定</div>-->
												@endif
											@else

											@endif
										@endif
									</div>

									<div>
										<button class="mgla style3 transition1" type="button" onClick="javascript:history.back();">戻る</button>
									</div>
								</div>
								<form id="viewform" name="viewform" method="POST">
								<input type="hidden" name="mode" id="mode" value="">
								<input type="hidden" name="select_html" id="select_html" value="">
								<table>
									<thead>
										<tr>
											<th>伝票番号</th>
											<th>納期</th>
											<th>得意先</th>
											<th>品名</th>
											<th>エンドユーザー</th>
											<th>数量</th>
											<th>印刷開始日</th>
											<th>加工作業必要日数</th>
											<th>コメント</th>
										</tr>
									</thead>
									<tbody>
									@forelse ($resultdata as $val)
										<tr>
											<td class="">{{ $val->product_code }}<input type="hidden" name="s_product_code" id="s_product_code" value="{{ $val->product_code }}"></td>
											<td class="">{!! date('Y-m-d', strtotime($val->after_due_date)) !!}</td>
											<td class="">{{ $val->customer }}</td>
											<td class="">{{ $val->product_name }}</td>
											<td class="">{{ $val->end_user }}</td>
											<td class="">{{ $val->quantity }}</td>
											<td class="">@php echo isset($val->receive_date) ? date('Y-m-d', strtotime($val->receive_date)) : ""; @endphp</td>
											<!--<td class="">@php echo isset($val->platemake_date) ? date('Y-m-d', strtotime($val->platemake_date)) : ""; @endphp</td>-->
											<td class="">{{ $val->work_need_days }}</td>
											<td class="">{{ $val->comment }}</td>
										</tr>

									@empty
										<tr><td colspan="10">no data</td></tr>
									@endforelse
									</tbody>
								</table>

								<table>
									<thead>
										<tr>
											<th>加工作業1</th>
											<th>作業メモ1</th>
											<th>加工作業2</th>
											<th>作業メモ2</th>
											<th>加工作業3</th>
											<th>作業メモ3</th>
											<th>加工作業4</th>
											<th>作業メモ4</th>
											<th>加工作業5</th>
											<th>作業メモ5</th>
										</tr>
									</thead>
									<tbody>
									@forelse ($resultdata as $val)
										<tr>
											<td class="">@php echo WorkStr($arrdata,$val->wkcode01,'01',$val->product_code) @endphp</td>
											<td class="">{{ $val->wkcom01 }}</td>
											<td class="">@php echo WorkStr($arrdata,$val->wkcode02,'02',$val->product_code) @endphp</td>
											<td class="">{{ $val->wkcom02 }}</td>
											<td class="">@php echo WorkStr($arrdata,$val->wkcode03,'03',$val->product_code) @endphp</td>
											<td class="">{{ $val->wkcom03 }}</td>
											<td class="">@php echo WorkStr($arrdata,$val->wkcode04,'04',$val->product_code) @endphp</td>
											<td class="">{{ $val->wkcom04 }}</td>
											<td class="">@php echo WorkStr($arrdata,$val->wkcode05,'05',$val->product_code) @endphp</td>
											<td class="">{{ $val->wkcom05 }}</td>
										</tr>

									@empty
										<tr><td colspan="10">no data</td></tr>
									@endforelse
									</tbody>
								</table>



								@csrf
								</form>
							</div>
							@php
								// fileリンク
								$file_msg = "";
								$filelink_html = "";
								$html_flink = "";
								$result_view = false;
								$directory = "public";
								$dirfiles = Storage::files($directory);
								foreach($dirfiles as $key => $filename) {
									//$result = mb_strpos($filename, $product_code);
									if($result = mb_strpos($filename, $product_code)) {
										$url = Storage::url($filename);
										$file_msg .= $result."<a href='".$url."' target='_blank'>".$filename."</a> ";
										$file_msg .= "url -> ".$url." ++++ filename -> ".$filename." <br>\n";
										$filelink_html .= "<div>\n";
										$filelink_html .= $key.". <a href='".$url."' target='_blank'>".basename($filename)."</a>&emsp;";
										//$filelink_html .= "<input type='checkbox' value='".$url."' name='delchk[]' id='delchk".$key."'>";
										$filelink_html .= "</div>\n";
										$result_view = true;
									} 
								}

								if($result_view) {
									$html_flink .= "<div id='form3' class=''>\n";
									$html_flink .= "<label for='platemake_date' class=''>ファイル</label>\n";
									$html_flink .= $filelink_html;
									$html_flink .= "</div>\n";

								}

								@endphp
								{!! $html_flink !!}



						<div id="resultupdate"></div>
						<div id="resultstr"></div>


					<!--!! html_cal_main !! -->

					@endif

					</div>
					<!-- /main contentns row -->



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
		else if(cf == 'otherserverview') {
				var Jurlsd = '';
				var Jaction = '';
				if(val1) Jurlsd = '?s_product_code=' + val1;
				if(smd == 'c1') Jaction = 'http://192.168.0.93';
				if(smd == 'c2') Jaction = 'http://192.168.0.94';
				fm.mode.value = md;
				//fm.s_product_code.value = val1;
				fm.select_html.value = val2;
				//fm.action = 'http://192.168.0.42/view/search' + Jurlsd;
				//fm.method = 'get';
				//fm.submit();
				window.open(Jaction + '/view/getsearch' + Jurlsd + '&select_html=' + val2 + '&mode=' + md);
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
		else if(cf == 'update_status') {
			//var Jproduct_code = document.getElementById('product_code' + val1).value;
			//var Jstatus = fm.status.value;
			//var result = window.confirm( com1 +'\n伝票番号 : '+ Jproduct_code +'');
			var result = window.confirm( com1 +'\n伝票番号 : '+ val1 +'');
			if( result ) {
				fm.mode.value = md;
				fm.status.value = val2;
				fm.s_product_code.value = val1;
				fm.action = '/view/update';
				fm.submit();
			}
		}
		else if(cf == 'update_wkakou') {
			//var Jproduct_code = document.getElementById('product_code' + val1).value;
			//var Jstatus = fm.status.value; kwupdate
			//var result = window.confirm( com1 +'\n伝票番号 : '+ Jproduct_code +'');
			var result = window.confirm( com1 +'\n伝票番号 : '+ val1 +'');
			if( result ) {
				fm.mode.value = md;
				fm.status.value = val2;
				fm.s_product_code.value = val1;
				var wkcom_01 = val1 + '_wkcom01';
				console.log('update_wkakou in wkcom_01 -> ' + wkcom_01);
				//fm.wkcom01.value = fm.wkcom_01.value;
				fm.wkcom01.value = document.getElementById(wkcom_01).value;
				var wkcom_02 = val1 + '_wkcom02';
				fm.wkcom02.value = document.getElementById(wkcom_02).value;
				var wkcom_03 = val1 + '_wkcom03';
				fm.wkcom03.value = document.getElementById(wkcom_03).value;
				var wkcom_04 = val1 + '_wkcom04';
				fm.wkcom04.value = document.getElementById(wkcom_04).value;
				var wkcom_05 = val1 + '_wkcom05';
				fm.wkcom05.value = document.getElementById(wkcom_05).value;

				var wkcode_01 = val1 + '_wkcode01';
				fm.wkcode01.value = document.getElementById(wkcode_01).value;
				var wkcode_02 = val1 + '_wkcode02';
				fm.wkcode02.value = document.getElementById(wkcode_02).value;
				var wkcode_03 = val1 + '_wkcode03';
				fm.wkcode03.value = document.getElementById(wkcode_03).value;
				var wkcode_04 = val1 + '_wkcode04';
				fm.wkcode04.value = document.getElementById(wkcode_04).value;
				var wkcode_05 = val1 + '_wkcode05';
				fm.wkcode05.value = document.getElementById(wkcode_05).value;

				fm.action = '/view/updatekakou';
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

