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
    

    public function calendar($result,$after_due_date,$wd_result,$result_date) {
        //DateTimeインスタンスを作成
        $today = new DateTime();
        $due_date = new DateTime($after_due_date);
        $f_today = $today->format('Y-m-d');
        $f_due_date = $due_date->format('Y-m-d');
        $ym_html = '';
        $body = '';
        $work_date_arr = Array();
        //$action_msg .= $f_due_date."<br>\n";
    
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
    
            foreach($resdate AS $key => $val) {
                $work_date_arr[] = date("Y-m-d", strtotime($resdate[$key]->work_date));
                $work_date = date("Y-m-d", strtotime($resdate[$key]->work_date));
                $departments_code = $resdate[$key]->departments_code;
                $departments_name = $resdate[$key]->departments_name;
                $work_name = $resdate[$key]->work_name;
                $work_code = $resdate[$key]->work_code;
                $work_code_wdkey[$work_date][$work_name] = $resdate[$key]->work_code;
                $work_name_wdkey[$work_date][$work_name] = $resdate[$key]->departments_name;
                $departments_code_wdkey[$work_date][$work_name] = $resdate[$key]->departments_code;
                $departments_name_wdkey[$work_date][$work_name] = $resdate[$key]->departments_name;
                $wname_dcode_wdkey[$work_name] = $resdate[$key]->departments_code;
            }
            //print_r($wname_dcode_wdkey);
            //echo "<br><br>\n";
            //print_r($work_name_wdkey);
            //echo "<br><br>\n";
            
    
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
                    
                    foreach($work_name_wdkey[$ymd_day] AS $key => $val) {
                        $dcode = $wname_dcode_wdkey[$key];
    
                        $departments_name = $departments_name_wdkey[$ymd_day][$key];
                        $wcode = $work_code_wdkey[$ymd_day][$key];
                        $dc = $departments_code_wdkey[$ymd_day][$key];
                        $d = $dc % 10;
                        if($d == 0) $d = 10;
                        $departments_name = $rewcode !== $wcode ? "<span class=\"worktext\">".$departments_name."</span>" : "";
                        //echo "wc = ".$wc." : w = ".$w." : d = ".$d."<br>\n";
                        //echo $ymd_day."は配列内に存在します , ".$key." , ".$val."<br>\n";
                        $class_w = $class_array1[$d];
                        $line[$d][$dkey] .= "<a href=\"\"><div class=\"workitem {$class_w}\" title=\"".$key." , ".$val."\">&nbsp;".$departments_name."</div></a>";
                        $redcode = $dcode;
                        $rewcode = $wcode;
    
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
                //会社の休日
                $company_class = $this->scheduleDays($day->format('Y-m-d'),$company_schedule_arr) ? "datebox" : "";
    
                $pd1_class =  '';
                $pd2_class =  '';
                $pd3_class =  '';
                $pd4_class =  '';
                $pd5_class =  '';
                $pd6_class =  '';
    
                $line1 = $line[2][$dkey];
                $line2 = $line[3][$dkey];
                $line3 = $line[4][$dkey];
                $line4 = $line[5][$dkey];
                $line5 = $line[6][$dkey];
                $line6 = $line[7][$dkey];
                $line7 = $line[1][$dkey].$line[8][$dkey].$line[9][$dkey].$line[10][$dkey];
    
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
                    $day->format('j')
                );
    
    
                $body .= sprintf(
                    '<div class="day_cnt" style="%s"><a href=""><div style="%s">%s</div><div class="datestyle %s %s">%s</div></a>'.$workspace.'</div>',
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
    
    $cal_html = <<<EOF
        {$ym_html}
        {$body}
        {$f_due_date}
    
    EOF;
    
        return $cal_html;
    }
    
}
