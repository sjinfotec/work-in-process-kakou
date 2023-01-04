<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use DateTime;
use DatePeriod;
use DateInterval;


class CalendarSquare extends Model
{
    use HasFactory;

    // カレンダー表示
    function create_calendar( $num = 1, $set = false, $after_due_date) {
        
        $cal_parts = '<div id="cal_cnt">';
        //今日
        $date = new DateTime();
        $today = $date->format( 'Y-n-j' );
        //該当日（納品日）
        $thisday =  !empty($after_due_date) ? date('Y-n-j', strtotime($after_due_date)) : $today;
    
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
            $cal_parts .= '<tr class="color_gray"><th class="color3">日</th><th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th class="color4">土</th></tr>';
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
                if ( $month.'-'.$day == $thisday ) {
                    $cal_parts .= '<td class="'.$stylecss.' color_red"><strong>'.$day.'</strong></td>';	
                } 
                elseif ( $month.'-'.$day == $today ) {
                    $cal_parts .= '<td class="'.$stylecss.' today">'.$day.'</td>';	
                } 
                else {
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
        $cal_parts .= '</div><!--end cal_cnt-->';
        return $cal_parts;
        
    }
    //$html_cal = create_calendar();	//今月
    //$html_cal = create_calendar( 12 );	//何か月分
    //$html_cal = create_calendar( 2, '2020-1' );	//開始年月～何か月分


}
