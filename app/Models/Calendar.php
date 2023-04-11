<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use DateTime;
use DatePeriod;
use DateInterval;



class Calendar extends Model
{
    use HasFactory;


    public function scheduleDays($check_date,$company_schedule_arr) {
        $schedulearr = Array(
            '2022-01-01','2022-01-02','2022-01-03','2022-01-04','2022-01-09','2022-01-10','2022-01-16','2022-01-22','2022-01-23','2022-01-30',
            '2020-01-05','2020-01-11','2020-01-12','2020-01-19','2020-01-25','2020-01-26'
        );
        return $result_chk = in_array($check_date, $company_schedule_arr);
    }
    

    public function calendar($result,$after_due_date,$wd_result,$result_date,$viewmode) {
        //DateTimeインスタンスを作成
        //var_dump($result['result']);
        $today = new DateTime();
        $due_date = new DateTime($after_due_date);
        $f_today = $today->format('Y-m-d');
        $f_due_date = $due_date->format('Y-m-d');

        $receive_date = $result['result'][0]->receive_date;
        if(!empty($receive_date)) {
            $dt_receive_date = new DateTime($receive_date);
            $f_receive_date = $dt_receive_date->format('Y-m-d');
        }
        else {
            $f_receive_date = "";
        }

        $platemake_date = $result['result'][0]->platemake_date;
        if(!empty($platemake_date)) {
            $dt_platemake_date = new DateTime($platemake_date);
            $f_platemake_date = $dt_platemake_date->format('Y-m-d');
            }
        else {
            $f_platemake_date = "";
        }

        $ym_html = '';
        $body = '';
        $work_date_arr = Array();

        //echo $f_receive_date;

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

        //何日前から表示するか（カレンダー開始日）
        $sd = 40;
    
        //表示月初日をカレンダーの開始日に変更する
        $start_day->modify('-' . $sd . ' day');
    
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
        //$resultdata = $result['result'];
        if(isset($result['result'])) {
            $res = $result['result'];
            $resdate = $result_date['result'];
            //var_dump($res);
            //echo $res[0]->product_code;
            $performance_wdkey = Array();
            $comment_wdkey = Array();
            $i = 0;
            $workdatechk = "";
            $worknamechk = "";
            foreach($resdate AS $key => $val) {
                $work_date_arr[] = date("Y-m-d", strtotime($resdate[$key]->work_date));
                $work_date = date("Y-m-d", strtotime($resdate[$key]->work_date));
                $departments_code = $resdate[$key]->departments_code;
                $departments_name = $resdate[$key]->departments_name;
                $work_name = $resdate[$key]->work_name;
                $work_code = $resdate[$key]->work_code;
                $id = $resdate[$key]->id;
                $work_code_wdkey[$work_date][$work_name] = $resdate[$key]->work_code;
                $work_code_wdkey_idkey[$work_date][$work_name][$id] = $resdate[$key]->work_code;
                $work_name_wdkey[$work_date][$work_name] = $resdate[$key]->departments_name;
                $work_name_wdkey_idkey[$work_date][$work_name][$id] = $resdate[$key]->departments_name;
                //$work_name2_wdkey[$work_date][$work_name2] = $resdate[$key]->departments_name;
                $departments_code_wdkey[$work_date][$work_name] = $resdate[$key]->departments_code;
                $departments_code_wdkey_idkey[$work_date][$work_name][$id] = $resdate[$key]->departments_code;
                $departments_name_wdkey[$work_date][$work_name] = $resdate[$key]->departments_name;
                $departments_name_wdkey_idkey[$work_date][$work_name][$id] = $resdate[$key]->departments_name;
                $performance_wdkey[$work_date][$work_name] = $resdate[$key]->performance;
                $performance_wdkey_idkey[$work_date][$work_name][$id] = $resdate[$key]->performance;
                $comment_wdkey[$work_date][$work_name] = $resdate[$key]->comment;
                $comment_wdkey_idkey[$work_date][$work_name][$id] = $resdate[$key]->comment;
                $wname_dcode_wdkey[$work_name] = $resdate[$key]->departments_code;
                $wname_dcode_wdkey_idkey[$work_name][$id] = $resdate[$key]->departments_code;
                $status_wdkey[$work_date][$work_name] = $resdate[$key]->status;
                $status_wdkey_idkey[$work_date][$work_name][$id] = $resdate[$key]->status;
                $i = $i + 1;
                $workdatechk = $work_date;
                $worknamechk = $work_name;
                //echo "id -> ".$id."<br>\n";

            }
            //print_r($wname_dcode_wdkey);
            //echo "<br><br>\n";
            //print_r($comment_wdkey);
            //echo "<br><br>\n";
            //vur_dump($performance_wdkey);
            //echo "<br><br>\n";


            //echo count($performance_wdkey, COUNT_RECURSIVE) . "<br>\n";
            
            // 実績の収集
            $result_pt = false;
            $performance_table = "<div id='tbl_com'>\n<h4>実績一覧</h4>\n<table>\n";
            $performance_table .= "<thead>\n<tr><th>日時</th><th>作業</th><th>実績</th>\n</thead>\n<tbody>";
            ksort($performance_wdkey_idkey);
            foreach($performance_wdkey_idkey AS $perwdkey => $perwdarr) {
                //echo "perwdkey->".$perwdkey."<br>\n";
                foreach($perwdarr AS $perkey => $pervalarr) {
                    foreach($pervalarr AS $key => $perval) {
                        if(isset($perval)) {
                            $pdateTimeObj = new DateTime($perwdkey);
                            $pdateStr = $pdateTimeObj->format('Y年n月j日');
                            //echo "perwdkey->".$pdateStr."<br>\n";
                            //echo "perkey->".$perkey."<br>\n";
                            //echo "perval->".$perval."<br>\n";
                            $performance_table .= "<tr><td>{$pdateStr}</td><td>{$perkey}</td><td>{$perval}</td></tr>\n";
                            $result_pt = true;
                        }
                    }
                }
            }
            $performance_table .= "</tbody>\n</table>\n</div><!--end performance_html-->\n";
            $performance_table = $result_pt ? $performance_table : ""; 

  

            // コメントの収集
            $result_ct = false;
            $comment_table = "<div id='tbl_com'>\n<h4>コメント一覧</h4>\n<table>\n";
            $comment_table .= "<thead>\n<tr><th>日時</th><th>作業</th><th>コメント</th>\n</thead>\n<tbody>";
            ksort($comment_wdkey_idkey);
            foreach($comment_wdkey_idkey AS $comwdkey => $comwdarr) {
                foreach($comwdarr AS $comkey => $comvalarr) {
                    foreach($comvalarr AS $key => $comval) {
                        if(isset($comval)) {
                            $dateTimeObj = new DateTime($comwdkey);
                            $dateStr = $dateTimeObj->format('Y年n月j日');
                            //echo "comwdkey->".$dateStr."<br>\n";
                            //echo "comkey->".$comkey."<br>\n";
                            //echo "comval->".$comval."<br>\n";
                            $comment_table .= "<tr><td>{$dateStr}</td><td>{$comkey}</td><td>{$comval}</td></tr>\n";
                            $result_ct = true;
                        }
                    }
                }
            }
            $comment_table .= "</tbody>\n</table>\n</div><!--end comment_html-->\n";
            $comment_table = $result_ct ? $comment_table : ""; 

    
            $class_array1 = Array(
                '1' => 'd1c1',
                '2' => 'd2c1',
                '3' => 'd3c1',
                '4' => 'd4c1',
                '5' => 'd5c1',
                '6' => 'd6c1',
                '7' => 'd7c1',
                '8' => 'd8c1',
                '9' => 'd9c1',
                '10' => 'd10c1'
            );
            $redcode = "";
            $rewcode = "";
            $pc = isset($resdate[0]) ? $resdate[0]->product_code : '';
    
    
            $body = '<div id="calendar">';
    
            foreach ($period as $dkey => $day) {
                $ymd_day =  $day->format('Y-m-d');
                //当月以外の日付はgreyクラスを付与してCSSで色をグレーにする
                $grey_class = $day->format('Y-m') === $year_month ? '' : 'grey';
                //echo "ymd_day = ".$ymd_day." : dkey = ".$dkey."<br>\n";
    
                if(in_array($ymd_day, $work_date_arr)) {
                    //echo $ymd_day."は配列内に存在します , ".$departments_name_wdkey[$ymd_day]." , ".$work_name_wdkey[$ymd_day]."<br>\n";
                    $line[1][$dkey] = "";
                    $line[2][$dkey] = "";
                    $line[3][$dkey] = "";
                    $line[4][$dkey] = "";
                    $line[5][$dkey] = "";
                    $line[6][$dkey] = "";
                    $line[7][$dkey] = "";
                    $line[8][$dkey] = "";
                    $line[9][$dkey] = "";
                    $line[10][$dkey] = "";

                    
                    foreach($work_name_wdkey_idkey[$ymd_day] AS $key => $valarr) {
	                    foreach($valarr AS $idkey => $val) {
	                        $dcode = $wname_dcode_wdkey_idkey[$key][$idkey];
	                        $departments_name = $departments_name_wdkey_idkey[$ymd_day][$key][$idkey];
	                        $comment = isset($comment_wdkey_idkey[$ymd_day][$key][$idkey]) ? $comment_wdkey_idkey[$ymd_day][$key][$idkey] : "";
	                        $wcode = $work_code_wdkey_idkey[$ymd_day][$key][$idkey];
	                        //echo "work_name_wdkey in ".$key."<br>\n";

	                        $status_str = mb_substr($status_wdkey_idkey[$ymd_day][$key][$idkey], 0, 1) ?: "";
	                        $status = $status_str ? "<span id=\"status".$ymd_day."_".$wcode."_".$idkey."\" class=\"color6\">".$status_str."</span>": "<span id=\"status".$ymd_day."_".$wcode."_".$idkey."\" class=\"color6 bold\">&nbsp;</span>";
	                        $status_mode = $status_str ? "rechange" : "change"; 

	                        $dc = $departments_code_wdkey_idkey[$ymd_day][$key][$idkey];
	                        $d = $dc % 10;
	                        if($d == 0) $d = 10;
	                        //$departments_name = $rewcode !== $wcode ? "<span class=\"worktext\">".$departments_name."</span>" : "";
	                        $departments_name = "<span class=\"worktext\">".$departments_name."</span>";
	                        $comment_html = $rewcode !== $wcode ? "<span class=\"worktext wt2\">".$comment."</span>" : "";
	                        //echo "wcode = ".$wcode." : val = ".$val." : ymd = ".$ymd_day." :  d = ".$d."<br>\n";
	                        //echo $ymd_day."は配列内に存在します , ".$key." , ".$val."<br>\n";
	                        $class_w = $class_array1[$d];
	                        if($dc == 13) $class_w = 'd13c1';
	                        if($dc == 29) {
	                            $class_w = 'd29c1';
	                            $line[$d][$dkey] .= "<div class=\"workitem {$class_w}\" title=\"".$comment."\">".$status."".$comment_html."</div>";
	                        }
	                        else {
	                            $line[$d][$dkey] .= "<div onClick=\"return statusChange('{$ymd_day}','{$pc}','{$dc}','{$wcode}','{$val}','{$key}','{$status_mode}');\" class=\"workitem {$class_w}\" title=\"".$key." , ".$val."\">".$status."".$departments_name."</div>";
	                        }
	                        $redcode = $dcode;
	                        $rewcode = $wcode;
	                    }
                    }
                 
                    
                       
                }
                else {
                    $line[1][$dkey] = "";
                    $line[2][$dkey] = "";
                    $line[3][$dkey] = "";
                    $line[4][$dkey] = "";
                    $line[5][$dkey] = "";
                    $line[6][$dkey] = "";
                    $line[7][$dkey] = "";
                    $line[8][$dkey] = "";
                    $line[9][$dkey] = "";
                    $line[10][$dkey] = "";
                }
    
    
    
 
    
    
                $ym_date = $day->format('Y年n月') ;
                if($ym_date !== $ym_judge)  {
                    if($tag_on > 0) $body .= '</div></div><!--end class f-->';
                    //$flex_jc = $tag_on > 0 ? 'flex_jc_s' : 'flex_jc_e';
                    $flex_jc = $tag_on > 0 ? 'justify-content: start;' : 'justify-content: end;';
                    $body .= '<div class="f"><div class="ymstyle gc1"><b>'.$ym_date.'</b></div><div id="calendar_dayzone" style="'.$flex_jc.'">';
    
                }
    
                
                //本日にはtodayクラスを付与してCSSで数字の見た目を変える due_date $html_due_date $today->format('Y-m-d')
                $today_class = $day->format('Y-m-d') === $f_today ? 'today' : '';
                $due_class = $day->format('Y-m-d') === $f_due_date ? 'background:red; color:#FFF; font-weight:bold;' : '';
                $receive_class = $day->format('Y-m-d') === $f_receive_date ? 'background:#088; color:#FFF; font-weight:bold;' : '';
                $platemake_class = $day->format('Y-m-d') === $f_platemake_date ? 'background:#808; color:#FFF; font-weight:bold;' : '';
                if($day->format('Y-m-d') === $f_platemake_date && $day->format('Y-m-d') === $f_receive_date)   {
                    $re_pl_html = '<div class="platemake" title="入稿・下版日">&emsp;<span class="str1">入稿・下版日</span></div>';
                }
                elseif($day->format('Y-m-d') === $f_platemake_date)   {
                    $re_pl_html = '<div class="platemake" title="下版日">&emsp;<span class="str1">下版日</span></div>';
                }
                elseif($day->format('Y-m-d') === $f_receive_date)   {
                    $re_pl_html = '<div class="receive" title="入稿日">&emsp;<span class="str1">入稿日</span></div>';
                }
                else    {
                    $re_pl_html = '<div class="">&emsp;</div>';
                }
                
                //会社の休日
                $company_class = $this->scheduleDays($day->format('Y-m-d'),$company_schedule_arr) ? "datebox" : "";
    
                $pd1_class =  '';
                $pd2_class =  '';
                $pd3_class =  '';
                $pd4_class =  '';
                $pd5_class =  '';
                $pd6_class =  '';
                $pd7_class =  '';
                $pd8_class =  '';
    
                $line1 = $line[2][$dkey];
                $line2 = $line[3][$dkey];
                $line3 = $line[4][$dkey];
                $line4 = $line[5][$dkey];
                $line5 = $line[6][$dkey];
                $line6 = $line[7][$dkey];
                $line7 = $line[1][$dkey].$line[8][$dkey].$line[10][$dkey];
                $line8 = $line[9][$dkey];
    
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


                $workdate_html = "";
                if($viewmode === 'editing')    {
                    $workdate_html .= '<div class="line"><div class="btn_shade transition2">'."\n";
                    $workdate_html .= '							<input type="checkbox" name="work_date['.$ymd_day.']" value="'.$ymd_day.'" id="work'.$ymd_day.'" class="chkonff" '.$checked.'>'."\n";
                    $workdate_html .= '							<label for="work'.$ymd_day.'" class="wclabel transition2"></label>'."\n";
                    $workdate_html .= '						</div></div>'."\n";
                }

    
                $workspace = sprintf('
                    <div id="workin">
                        <div class="line"><div class="%s">%s</div></div>
                        <div class="line"><div class="%s">%s</div></div>
                        <div class="line"><div class="%s">%s</div></div>
                        <div class="line"><div class="%s">%s</div></div>
                        <div class="line"><div class="%s">%s</div></div>
                        <div class="line"><div class="%s">%s</div></div>
                        <div class="line"><div class="%s">%s</div></div>
                        <div class="line"><div class="%s">%s</div></div>
                        '.$workdate_html.'
                    </div>
                    ',
                    $pd8_class,
                    $line8,
                    $pd1_class,
                    $line1,
                    $pd2_class,
                    $line2,
                    $pd3_class,
                    $line3,
                    $pd4_class,
                    $line4,
                    $pd5_class,
                    $line5,
                    $pd6_class,
                    $line6,
                    $pd7_class,
                    $line7,
                    $day->format('j')
                );
    
    
                $body .= sprintf('
                    <div class="day_cnt" style="%s">
                    <a href="/work/day?pcode='.$res[0]->product_code.'&wday=%s">
                    <div style="%s">%s</div><div class="datestyle %s %s">%s</div>
                    </a>
                    %s
                    '.$workspace.'
                    <a href="/work/day?pcode='.$res[0]->product_code.'&wday=%s">
                    <div style="%s">%s</div><div class="datestyle %s ">%s</div>
                    </a>
                    </div>
                    ',
                    $due_class,
                    $day->format('Y-m-d'),
                    $style_bg,
                    $weekarr[$fdw],
                    $company_class,
                    $today_class,
                    $day->format('j'),
                    $re_pl_html,
                    $day->format('Y-m-d'),
                    $style_bg,
                    $weekarr[$fdw],
                    $company_class,
                    $day->format('j'),
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
    
        $body .= "</div></div><!--end class f-->\n";
        $body .= "</div><!--end id calendar-->\n";
    
        }
    
    $cal_html = <<<EOF
        {$performance_table}
        {$comment_table}
        {$ym_html}
        {$body}
        <!--<div class="mgla"><button type="button" class="gc1" onClick="unChecked('.chkonff')">UNCHECK ALL</button></div>-->
        納期 ： {$f_due_date}


    
    EOF;
    
        return $cal_html;
    }
    
}
