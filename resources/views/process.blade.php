<?php
use Illuminate\Support\Facades\Storage;

$html_result = "";
$cal_start_ym = "";
$ymd_after_due_date = "";
if(isset($result)) {
		var_dump($result);
	
	if(isset($result[0])) {
		foreach($result as $key => $val) {

			//echo "key".$key."<br>\n";
			//$res = $result[$key];
			$number = 1 + $key;
			$product_code = $val->product_code;
			$after_due_date = $val->after_due_date;
			$customer = $val->customer;
			$product_name = $val->product_name;
			$end_user = $val->end_user;
			$quantity = $val->quantity;
			$comment = $val->comment;
			$html_after_due_date = !empty($after_due_date) ? date('n月j日', strtotime($after_due_date)) : "";
			$ymd_after_due_date = !empty($after_due_date) ? date('Y-m-d', strtotime($after_due_date)) : "";
			$html_result .= <<<EOF
				<tr>
					<td>{$number} <span id="btn_cnt_new"><button class="" type="button" onClick="NEWcollect()">axios登録</button></span></td>
					<td><input type="hidden" name="product_code" id="product_code" value="{$product_code}">{$product_code}</td>
					<td><input type="hidden" name="after_due_date" id="after_due_date" value="{$after_due_date}">{$html_after_due_date}</td>
					<td><input type="hidden" name="customer" id="customer" value="{$customer}">{$customer}</td>
					<td><input type="hidden" name="product_name" id="product_name" value="{$product_name}">{$product_name}</td>
					<td><input type="hidden" name="end_user" id="end_user" value="{$end_user}">{$end_user}</td>
					<td><input type="hidden" name="quantity" id="quantity" value="{$quantity}">{$quantity}</td>
				</tr>

			EOF;
			//指定日時を月はじめに変換する date("Y-m-d H:i:s")
			//$after_due_date = "";
			$target_day = !empty($after_due_date) ? date("Y-m-1", strtotime($after_due_date)) : date("Y-m-d");
			//echo "targetday = ".$target_day."<br>\n";
			$cal_start_ym = !empty($target_day) ? date('Y-n', strtotime($target_day . ' -1 month')) : "";
			
			

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

//$datetest = new DateTime($after_due_date);
//echo $datetest->format('Y-m-d');

$csvfile = Storage::get('schedule.csv');	// /storage/app/schedule.csv にアクセス
//$csvfile = Storage::disk('public')->get('sample.txt');	// /storage/app/public/sample.txt にアクセス
/*
//行で分割
$csvfile_data = explode("\n", $csvfile);
// foreachで行単位でループ処理
foreach($csvfile_data as $key => $value){
	// 空行の場合飛ばす
	if( !$value ) continue;
	// 行をカンマで分割
	$company_schedule_arr[] = explode(",", $value);
}
*/

//行で分割
//$csvfile_arr = explode("\n", $csvfile);
//$csvfile_join = join(',', $csvfile_arr);
//$company_schedule_arr = explode(",", $csvfile_join);	//カンマで配列
//var_dump($company_schedule_arr);

function scheduleDays($check_date,$company_schedule_arr) {
	$schedulearr = Array(
		'2022-01-01','2022-01-02','2022-01-03','2022-01-04','2022-01-09','2022-01-10','2022-01-16','2022-01-22','2022-01-23','2022-01-30',
		'2020-01-05','2020-01-11','2020-01-12','2020-01-19','2020-01-25','2020-01-26'
	);
	return $result_chk = in_array($check_date, $company_schedule_arr);
}




function calendar2($result,$after_due_date,$wd_result) {
	//DateTimeインスタンスを作成
	$today = new DateTime();
	$due_date = new DateTime($after_due_date);
	$f_today = $today->format('Y-m-d');
	$f_due_date = $due_date->format('Y-m-d');
	$ym_html = '';
	$body = '';


	


	/*
	if(isset($_GET['t']) && preg_match('/\A\d{4}-\d{2}\z/', $_GET['t'])) {
	//クエリ情報を基にしてDateTimeインスタンスを作成
	$start_day = new DateTime($_GET['t'] . '-01');
	} else {
	//当月初日のDateTimeインスタンスを作成
	$start_day = new DateTime('first day of this month');
	}
	*/
	
	//該当日（納品日・納期）
	$start_day =  !empty($after_due_date) ? $due_date : $today;

	

	//カレンダー表示月の前月の年月を取得
	$dt = clone($start_day);
	$prev_month =  $dt->modify('-1 month')->format('Y-m');

	//カレンダー表示月の翌月の年月を取得
	$dt = clone($start_day);
	$next_month =  $dt->modify('+1 month')->format('Y-m');


	//カレンダー表示月の年と月を取得
	$year_month = $start_day->format('Y-m');

	//表示月初日の曜日を数値で取得
	//$w = $start_day->format('w');
	$w = 40;

	//表示月初日をカレンダーの開始日に変更する
	$start_day->modify('-' . $w . ' day');

	//表示月末日のDateTimeインスタンスを作成
	//$end_day = new DateTime('last day of ' . $year_month);
	$end_day = new DateTime($after_due_date);

	//カレンダーの終了日を取得するため月末の曜日を数値で取得
	$w = $end_day->format('w');

	//土曜日を数値にすると6。そこから月末の曜日に対応する数を引いてやれば、カレンダー末尾に追加すべき日数が判明する。
	//+1しているのはDatePeriodの特性を考慮するため
	$w = 6 - $w + 1;

	//月末をカレンダーの終了日の翌日に変更する
	$end_day->modify('+' . $w . ' day');

	//カレンダーに表示する期間のインスタンスを作成する
	$period = new DatePeriod(
	$start_day,
	new DateInterval('P1D'),
	$end_day
	);


	$weekarr = Array('日', '月', '火', '水', '木', '金', '土');	//曜日

	// 休日データの読み込み
	$csvfile = Storage::get('schedule.csv');
	$csvfile_arr = explode("\n", $csvfile);	//行で分割
	$csvfile_join = join(',', $csvfile_arr);	//配列をカンマで連結（一列のカンマ付き文字列）
	$company_schedule_arr = explode(",", $csvfile_join);	//カンマで配列


	//htmlに描写するための変数
	$ym_judge = '';
	$tag_on = 0;
	//$ym_html .= '<span>'.$prev_month.'</span>';
	//$ym_html .= '<span>'.$year_month.'</span>';

	if(isset($result[0])) {
		$res = $result[0];
		$start_process_date_1 = !empty($res->start_process_date_1) ? date("Y-m-d", strtotime($res->start_process_date_1)) : '';
		$end_process_date_1 = !empty($res->end_process_date_1) ? date("Y-m-d", strtotime($res->end_process_date_1)) : '';
		$start_process_date_2 = !empty($res->start_process_date_2) ? date("Y-m-d", strtotime($res->start_process_date_2)) : '';
		$end_process_date_2 = !empty($res->end_process_date_2) ? date("Y-m-d", strtotime($res->end_process_date_2)) : '';
		$start_process_date_3 = !empty($res->start_process_date_3) ? date("Y-m-d", strtotime($res->start_process_date_3)) : '';
		$end_process_date_3 = !empty($res->end_process_date_3) ? date("Y-m-d", strtotime($res->end_process_date_3)) : '';
		echo '<br>process date 1 : '. $start_process_date_1 .'～'. $end_process_date_1 .'<br>';
		echo 'process date 2 : '. $start_process_date_2 .'～'. $end_process_date_2 .'<br>';
		echo 'process date 3 : '. $start_process_date_3 .'～'. $end_process_date_3 .'<br>';
		var_dump($wd_result);
		echo 'var_dump end : <br>';

		$body = '<div id="calendar">';
	

		foreach ($period as $day) {
			$ymd_day =  $day->format('Y-m-d');
			//当月以外の日付はgreyクラスを付与してCSSで色をグレーにする
			$grey_class = $day->format('Y-m') === $year_month ? '' : 'grey';

			$ym_date = $day->format('Y年n月') ;
			if($ym_date !== $ym_judge)  {
				if($tag_on > 0) $body .= '</div></div><!--end class f-->';
				//$flex_jc = $tag_on > 0 ? 'flex_jc_s' : 'flex_jc_e';
				$flex_jc = $tag_on > 0 ? 'justify-content: start;' : 'justify-content: end;';
				$body .= '<div class="f"><div class="ymstyle gc1"><b>'.$ym_date.'</b></div><div id="calendar_dayzone" style="'.$flex_jc.'">';

			}

			
			//本日にはtodayクラスを付与してCSSで数字の見た目を変える due_date $html_due_date $today->format('Y-m-d')
			$today_class = $day->format('Y-m-d') === $f_today ? 'today' : '';
			$due_class = $day->format('Y-m-d') == $f_due_date ? 'background:red; color:#FFF; font-weight:bold;' : '';
			//会社の休日
			$company_class = scheduleDays($day->format('Y-m-d'),$company_schedule_arr) ? "datebox" : "";


			$pd1_class =  ($start_process_date_1 <= $day->format('Y-m-d')) && ($day->format('Y-m-d')) <= $end_process_date_1 ?  'd1c1' : '';
			$pd2_class =  ($start_process_date_2 <= $day->format('Y-m-d')) && ($day->format('Y-m-d')) <= $end_process_date_2 ?  'd2c1' : '';
			$pd3_class =  ($start_process_date_3 <= $day->format('Y-m-d')) && ($day->format('Y-m-d')) <= $end_process_date_3 ?  'd3c1' : '';


			$fdw = $day->format('w');
			
			//その曜日が日曜日ならタグを挿入する
			if ($day->format('w') == 0) {
				//$body .= '<tr>';
				$style_bg = 'background:#e86;';
			}
			elseif ($day->format('w') == 6) {
				$style_bg = 'background:#9be;';
			}
			else {
				$style_bg = '';
			}
			$checked = '';

			$workspace = sprintf('
				<div id="workin">
					<div class="line"><div class="%s">%s</div></div>
					<div class="line"><div class="%s">%s</div></div>
					<div class="line"><div class="%s">%s</div></div>
					<div class="line">
						<input type="checkbox" name="work_date['.$ymd_day.']" value="'.$ymd_day.'" id="work'.$ymd_day.'" class="chkonff" '.$checked.'>
						<label for="work'.$ymd_day.'" class="wclabel transition2"></label>
					</div>
					<div>
						<input type="hidden" name="work_datexxx['.$ymd_day.']" value="'.$ymd_day.'">
					</div>
				</div>
				',
				$pd1_class,
				'&ensp;',
				$pd2_class,
				'&ensp;',
				$pd3_class,
				'&ensp;',
				$day->format('j')
			);



			//sprintfを使って整形しながらhtml部分を作成する
			//'<td style="%s %s %s"><div>%s</div><div>%s</div></td>',
			/*
			$body .= sprintf(
				'<td class="youbi_%d %s %s">%d</td>',
				$day->format('w'),
				$today_class,
				$grey_class,
				$day->format('d')
			);
			*/
			$body .= sprintf(
				'<div class="day_cnt" style="%s"><div style="%s">%s</div><div class="datestyle %s %s"><span>%s</span></div>'.$workspace.'</div>',
				$due_class,
				$style_bg,
				$weekarr[$fdw],
				$company_class,
				$today_class,
				$day->format('j')
			);

			
			//その曜日が土曜日なら</tr>タグを挿入する
			if ($day->format('w') == 6) {
				//$body .= '</tr>';
			}

			if($ym_date !== $ym_judge) {

			//$body .= '</div>';

			}
			$ym_judge = $ym_date;
			$tag_on += 1;

		}

	$body .= '</div></div><!--end class f--></div><!--end id calendar-->';

	}

$cal_htmlxxx = <<<EOF
	<table class="calendar">
		<thead class="calendar-head">
		<tr class="calendar-row">
			<!-- リンクにクエリ情報を設定する -->
			<th><a href="?t={$prev_month}">&laquo;</a></th>
			<th colspan="5"><a href="">{$year_month}</a></th>
			<th><a href="?t={$next_month}">&raquo;</a></th>
		</tr>
		</thead>
		<tbody>
		<tr class="calendar-row">
			<td>日</td>
			<td>月</td>
			<td>火</td>
			<td>水</td>
			<td>木</td>
			<td>金</td>
			<td>土</td>
		</tr>
			{$body}
		</tbody>
		<tfoot>
		<tr class="calendar-row">
			<th colspan="7"><a href="">today</a></th>
		</tr>
		</tfoot>
	</table>
	{$f_due_date}
	
EOF;
$cal_html = <<<EOF
	{$ym_html}
	{$body}
	{$f_due_date}

EOF;

	return $cal_html;
}
$html_cal2 = calendar2($result,$after_due_date,$wd_result);	//開始年月～何か月分


// カレンダー表示
function create_calendar( $num = 1, $set = false, $after_due_date) {
	
	$cal_parts = "";
	//今日
	$date = new DateTime();
	$today = $date->format( 'Y-n-j' );
	//該当日（納品日）
	$today =  !empty($after_due_date) ? date('Y-n-j', strtotime($after_due_date)) : $today;
 
	//最初の月
	if( $set ) {
		$date = new DateTime( $set );
	}
 
	for( $i = 0; $i < $num; ++$i ) {
		$month = $date->format( 'Y-n' );	//描画する月
		$date->modify( '+1 months' );	//1ヶ月すすめる
		list( $y, $m ) = explode( '-', $month );	//年-月の分離
		//月の初めの曜日
		$start_date = new DateTime( 'first day of ' .$month );
		$week = $start_date->format( 'w' );
		//月の終わりの日
		$end_date  = new DateTime( 'last day of ' .$month );
		$end = $end_date->format( 'j' );
		//カレンダーテーブル
		$cal_parts .= '<table>';
		$cal_parts .= '<thead><tr><th colspan="7" class="month">'.$y.'年'.$m.'月</tr></thead>';
		$cal_parts .= '<tbody>';
		$cal_parts .= '<tr><th>日</th><th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th>土</th></tr>';
		//週の数
		$week_line = 0;
 
		for( $day = 1; $day <= $end; ++$day ) {
			//1日、もしくは日曜日
			if( $day == 1 || $week == 0 ) {
				$cal_parts .= '<tr>';
				++$week_line;
			}
			//1日かつ日曜日ではない
			if( $day == 1 && $week != 0 ) {
				for( $c = 0; $c < $week; ++$c ) {
					$cal_parts .= '<td class="blank">&ensp;</td>';
				}
			}
			//曜日の色
			if($week == 0) {
				$stylecss = "gc3";
			}
			elseif($week == 6) {
				$stylecss = "gc4";
			}
			else {
				$stylecss = "";
			}
			//日
			if ( $month.'-'.$day == $today ) {
				$cal_parts .= '<td class="'.$stylecss.'"><strong>'.$day.'</strong></td>';	
			} else {
				$cal_parts .= '<td class="'.$stylecss.'">'.$day.'</td>';
			}
			//最終日かつ土曜日ではない
			if( $day == $end && $week != 6 ) {
				for( $c = $week; $c < 6; ++$c ) {
					$cal_parts .= '<td class="blank">&ensp;</td>';
				}
			}
 			//最終日、もしくは土曜日
			if( $day == $end || $week == 6 ) {
				$cal_parts .= '</tr>';
			}
 			//曜日をすすめる
			if( $week == 6 ) {
				$week = 0;
			} else {
				++$week;
			}
		}
 
		//表示するカレンダーが複数かつ週の数が6未満の場合
		if( $num != 1 && $week_line < 6 ) {
			for( $n = $week_line; $n < 6; ++$n ) {
				$cal_parts .= '<tr>';
				for( $c = 0; $c < 7; ++$c ) {
					$cal_parts .= '<td class="blank">&ensp;</td>';
				}
				$cal_parts .= '</tr>';
			}
		}
 
		$cal_parts .= '</tbody>';
		$cal_parts .= '</table>';
 
	}
	return $cal_parts;
	 
}
//$html_cal = create_calendar();	//今月
//$html_cal = create_calendar( 12 );	//何か月分
//$html_cal = create_calendar( 2, '2020-1' );	//開始年月～何か月分

$html_cal = create_calendar( 2, $cal_start_ym, $after_due_date);	//開始年月～何か月分


?>
@extends('layouts.main')
@section('content')
				<div id="contents_area">
					<div id="title_cnt">
						<h1 class="tstyle">作業工程作成</h1>
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
									<input type="number" class="form_style1 w10e" name="s_product_code" id="s_product_code" value="{{ $s_product_code }}">
									<button class="" type="button" onClick="clickEvent('searchform','1','1','confirm','『 検索 』','product_search','chkwrite')">検索</button>
								</div>
								@csrf 
							</form>
						</div>
						<form id="updateform" name="updateform" method="POST">
							<!--
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
									<tbody id="result_search_view">
									@php echo $html_result;
									@endphp
									</tbody>
								</table>
							</div>
							-->
							<div id="form2" class="mgt20">
								<div class="form_style">
									<label for="product_code" class="">伝票番号</label>
									<input type="text" class="input_style" name="product_code" id="product_code" value="{{ $product_code }}">
								</div>
								<div class="form_style">
									<label for="after_due_date" class="">納期</label>
									<input type="date" class="input_style" name="after_due_date" id="after_due_date" value="{{ $ymd_after_due_date }}">
								</div>
								<div class="form_style">
									<label for="customer" class="">得意先</label>
									<input type="text" class="input_style" name="customer" id="customer" value="{{ $customer }}">
								</div>
								<div class="form_style">
									<label for="product_name" class="">品名</label>
									<input type="text" class="input_style" name="product_name" id="product_name" value="{{ $product_name }}">
								</div>
								<div class="form_style">
									<label for="end_user" class="">エンドユーザー</label>
									<input type="text" class="input_style" name="end_user" id="end_user" value="{{ $end_user }}">
								</div>
								<div class="form_style">
									<label for="quantity" class="">数量</label>
									<input type="text" class="input_style" name="quantity" id="quantity" value="{{ $quantity }}">
								</div>
								<div class="form_style">
									<label for="comment" class="">コメント</label>
									<input type="text" class="input_style" name="comment" id="comment" value="{{ $comment }}">
								</div>

							</div>
						</form>





						<div id="resultupdate"></div>
						<div id="resultlist"><ul class="list-group"></ul></div>
						<div id="error">{{ $e_message }}</div>

						<div>{{ $action_msg }}</div>
						<div>
							<div>modeの値：{{ $mode }}</div>
						</div>
						<div id="resultstr"></div>

						<form id="addprocessform" name="addprocessform" method="POST">
							<input type="hidden" name="mode" id="mode" value="wp_search">
							<input type="hidden" name="submode" id="submode" value="">
							<input type="hidden" name="motion" id="motion" value="">
							<input type="number" class="form_style1 w10e" name="s_product_code" id="s_product_code" value="{{ $s_product_code }}">
							<input type="hidden" name="work_name" id="work_name" value=""> 
							<input type="hidden" name="departments_name" id="departments_name" value=""> 
							@csrf



						@php
							echo $html_cal2;
						@endphp
						
							<!--
							<div id="form_cnt">
								<div>工程表１</div>
								<div class="datezone">
									<label for="start_process_date_1" class="transition2">開始日</label>
									<input type="date" class="form_style1" name="start_process_date_1" value="" id="start_process_date_1">
								</div>
								<div class="datezone">
									<label for="end_process_date_1" class="transition2">終了日</label>
									<input type="date" class="form_style1" name="end_process_date_1" value="" id="end_process_date_1">
								</div>
							</div>
							<div id="form_cnt">
								<div>工程表２</div>
								<div class="datezone">
									<label for="start_process_date_1" class="transition2">開始日</label>
									<input type="date" class="form_style1" name="start_process_date_2" value="" id="start_process_date_2">
								</div>
								<div class="datezone">
									<label for="end_process_date_1" class="transition2">終了日</label>
									<input type="date" class="form_style1" name="end_process_date_2" value="" id="end_process_date_2">
								</div>
							</div>
							<div id="form_cnt">
								<div>工程表３</div>
								<div class="datezone">
									<label for="start_process_date_1" class="transition2">開始日</label>
									<input type="date" class="form_style1" name="start_process_date_3" value="" id="start_process_date_3">
								</div>
								<div class="datezone">
									<label for="end_process_date_1" class="transition2">終了日</label>
									<input type="date" class="form_style1" name="end_process_date_3" value="" id="end_process_date_3">
								</div>
							</div>
							-->

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

							<button class="" type="button" onClick="clickEvent('addprocessform','1','1','confirm_update','『 登録 』','product_search','chkwrite')">登録</button>


						</form>

						@php
							echo $html_cal;
						@endphp

					</div>
					<!-- /main contentns row -->



@endsection

@section('jscript')

<script type="text/javascript">
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
				fm.action = '/process/search';
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
				fm.action = '/process/insert';
				fm.submit();
			}
			else {
				console.log('キャンセルがクリックされました');
			}
		}
		else if(cf == 'confirm_updatexx') {
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
				'	<label for="work_code' + index2 + '" class="label transition2" onclick="WORKDATEchecked(\'\',\'' + element.name + '\',\'{{ $wd_result }}\',\'select_workname\',\'\',\'' + element.id + '\',\'' + data.department + '\')">' + element.name + '</label>\n' +
				'</div>\n'
				);



			});
			text.push(
				'<div id="workname">\n' +
				'	<input type="radio" name="work_code" value="DEL" id="work_code_del">\n' + 
				'	<label for="work_code_del" class="label del transition2" onclick="clickEvent(\'\',\'削除\',\'\',\'select_workname\',\'\',\'\',\'\')">削除</label>\n' +
				'</div>\n'
			);

			document.getElementById('resultbtn').innerHTML = text.join('');


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
				'	<label for="work_code' + index2 + '" class="label transition2" onclick="clickEvent(\'\',\'' + element.name + '\',\'{{ $wd_result }}\',\'select_workname\',\'\',\'\',\'\')">' + element.name + '</label>\n' +
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
				BUTTON_collect = '<button class="style3" type="button" onClick="NEWcollect('+ appendcount +')">axios登録</button>';
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
		window.error(error.response);
	});
}








var addcount = Number('1');
// 画面を更新する処理
function appendListADD(dataarr) {
	$.each(dataarr, function(index, data) {
		//console.log('appendList in 配列index = ' + index);
		//console.log('appendList addcount ' + addcount);
		//$('#list ul').append("No. : " + data.t_number + "<br>名前 : " + data.name + "<br>name_code : " + data.name_code + '');
		//$('#resultupdate').html = ( "message--" + data.e_message );

		/*
		'product_id' => $product_id, 
		'after_due_date' => $after_due_date, 
		'customer' => $customer, 
		'product_name' => $product_name, 
		'end_user' => $end_user, 
		'quantity' => $quantity, 
		'mode' => $mode, 
		'e_message' => $e_message, 
		'result_msg' => $result_msg
		*/

		document.getElementById('resultupdate').innerHTML = '<div class="txt1">' + data.e_message + '</div>\n';
		var statusv = '<span style="color:green;">OK</span>';
		if(data.result_msg === 'OK') {
			if(data.chk_status === 'esse') {
				statusv = '<span style="color:orange;">上書き</span>';
			}
			$('#resultlist ul').prepend('<li><span>' + addcount + '</span>&emsp;<span class="txtcolor1">&#10004;</span>&emsp;No.&ensp;<span class="dtnum">' + data.product_code + '</span> ' + statusv + '</li>\n');
			
			document.getElementById('btn_cnt_new' + data.listcount).innerHTML = '<span class="color_green">' + '<button class="style5" type="button" disabled>登録完了</button>' + '</span>';
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
		appendListADD(response.data);
		
	})
	.catch(error => {
		window.error(error.response);
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

