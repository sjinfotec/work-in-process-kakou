<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\CalendarAll;
use App\Models\ProcessLog;

class ScheduleController extends Controller
{
    protected $table = 'process_details';
    protected $table_process_date = 'process_date';
    protected $table_process_log = 'process_log';


    public function index(Request $request)
    {
        

        $this->serial_code = $request->serial_code;
        $params = $request->only([
            'duedate_start',
            'duedate_end',
        ]);

        $s_product_code = !empty($_POST["s_product_code"]) ? $_POST['s_product_code'] : "";
        $duedate_start = !empty($_POST["duedate_start"]) ? $_POST['duedate_start'] : "";
        $duedate_end = !empty($_POST["duedate_end"]) ? $_POST['duedate_end'] : "";
        $s_customer = !empty($_POST["s_customer"]) ? $_POST['s_customer'] : "";
        $s_product_name = !empty($_POST["s_product_name"]) ? $_POST['s_product_name'] : "";
        $s_end_user = !empty($_POST["s_end_user"]) ? $_POST['s_end_user'] : "";

        $mode = !empty($_POST["mode"]) ? $_POST['mode'] : "";
        $select_html = !empty($_POST["select_html"]) ? $_POST['select_html'] : "";
        $action_msg = "";
        $result = "";
        $result_date = "";
        $wd_result = "";
        $result_msg = "";
        $html_cal = "";
        $html_after_due_date = "";
        $viewmode = "watch";
        $e_message = "";


        $result_details = $this->SearchProcessDetails($request);
        foreach($result_details['result'] AS $key => $val) { 
            //var_dump($val);
            //echo "key->".$key."<br>\n";
            //echo "key->".$val->product_code."<br>\n";
            $product_code = $val->product_code;
            $result_date = $this->SearchProcessDate($request,$product_code);
            //$test = $result_details['result'][0]->after_due_date;    // return result[]から取得する場合　[0]のキーが必要。
            if($result_details['datacount'] > 0) {
                $after_due_date = $result_details['after_due_date'];    // return $redata = [ の after_due_date を指す。

                $calendar_data = new CalendarAll();	// インスタンス作成
                $html_cal .= $calendar_data->calendar($result_details,$after_due_date,$wd_result,$result_date,$viewmode,$key);	//開始年月～何か月分


            }


        }


        //var_dump($html_cal);

        return view('schedule', [
            's_product_code' => $s_product_code,
            'mode' => $mode,
            'action_msg' => $action_msg,
            'e_message' => $e_message,
            'result' => $result_details,
            'result_date' => $result_date,
            'wd_result' => $wd_result,
            'html_cal_main' => $html_cal,
            'select_html' => $select_html,
            'duedate_start' => $duedate_start,
            'duedate_end' => $duedate_end,
            's_customer' => $s_customer,
            's_product_name' => $s_product_name,
            's_end_user' => $s_end_user,

        ]);


    }




    public function SearchProcessDetails($request) {
        $s_product_code = !empty($_POST["s_product_code"]) ? $_POST['s_product_code'] : "";
        //$product_code = !empty($_POST["product_code"]) ? $_POST['product_code'] : "";
        //$after_due_date = !empty($_POST["after_due_date"]) ? $_POST['after_due_date'] : "";
        $s_customer = !empty($_POST["s_customer"]) ? $_POST['s_customer'] : "";
        $s_product_name = !empty($_POST["s_product_name"]) ? $_POST['s_product_name'] : "";
        $s_end_user = !empty($_POST["s_end_user"]) ? $_POST['s_end_user'] : "";
        //$quantity = !empty($_POST["quantity"]) ? $_POST['quantity'] : "";
        //$receive_date = !empty($_POST["receive_date"]) ? $_POST['receive_date'] : "";
        //$platemake_date = !empty($_POST["platemake_date"]) ? $_POST['platemake_date'] : "";
        //$comment = !empty($_POST["comment"]) ? $_POST['comment'] : "";
        $mode = !empty($_POST["mode"]) ? $_POST['mode'] : "";
        $action_msg = "";
        $result = "";
        $wd_result = "";
        $result_msg = "";
        $html_after_due_date = "";
        $e_message = "";
        $systemdate = Carbon::now();
        $three_days_ago = Carbon::today()->subDay(3);
        $one_days_ago = Carbon::today()->subDay(1);
        $lnum = 5000;  // limitの件数
        $default = true;
        $viewmode = Array();


        $this->motion = $request->motion;
        $params = $request->only([
            's_product_code',
            'duedate_start',
            'duedate_end',
            's_customer',
            's_product_name',
            's_end_user',
            'mode',
            'submode',
        ]);



        try {

                $data = DB::table($this->table)
                ->select(
                    'id',
                    'product_code',
                    'serial_code',
                    'rep_code',
                    'after_due_date',
                    'customer',
                    'product_name',
                    'end_user',
                    'quantity',
                    'receive_date',
                    'platemake_date',
                    'work_need_days',
                    'status',
                    'comment',
                    'created_user',
                    'updated_user',
                    'created_at',
                    'updated_at'
                );
                if(!empty($params['s_product_code'])) {
                    $data->where('product_code', $params['s_product_code']);
                    $viewmode['pcode'] = 1;
                    $default = false;
                }
                if(!empty($params['duedate_start'])) {
                    $data->where('after_due_date', '>=', $params['duedate_start'])->limit(300);
                    $viewmode['duedatestart'] = 1;
                    $default = false;
                    $limiton = true;
                }
                if(!empty($params['duedate_end'])) {
                    $data->where('after_due_date', '<=', $params['duedate_end'])
                    ->limit(300);
                    $duecount = $data->count();
                    $viewmode['duedatestart'] = 1;
                    $default = false;
                    $limiton = true;
                }
                if(!empty($params['s_customer'])) {
                    $data->where('customer', 'LIKE', '%'.$params['s_customer'].'%');
                    $viewmode['customer'] = 1;
                    $default = false;
                }
                if(!empty($params['s_product_name'])) {
                    $data->where('product_name', 'LIKE', '%'.$params['s_product_name'].'%');
                    $viewmode['pname'] = 1;
                    $default = false;
                }
                if(!empty($params['s_end_user'])) {
                    $data->where('end_user', 'LIKE', '%'.$params['s_end_user'].'%');
                    $viewmode['enduser'] = 1;
                    $default = false;
                }

                if($default) {                    
                    $data->whereDate('after_due_date', '>=', $one_days_ago);
                    $data
                    //->orderBy('after_due_date', 'desc')
                    ->orderBy('after_due_date', 'asc')
                    ->limit($lnum);
                    $viewmode['default'] = 1;
                }

                $result = $data
                ->get();
                $datacount = $data->count();



                if($datacount > 0) {
                    $r_after_due_date = $result[0]->after_due_date;
                    $html_after_due_date = !empty($r_after_due_date) ? date('n月j日', strtotime($r_after_due_date)) : "";
                    $result_msg = "OK";
                    //$e_message .= "検索結果 ： ".$datacount." 件<br>\n";
                    if(isset($viewmode['pcode'])) {
                        $e_message .= "伝票番号 『 ".$result[0]->product_code." 』 検索しました<br>\n";
                    }
                    if(isset($viewmode['duedatestart'])) {
                        $e_message .= "期間 『 ".$params['duedate_start']." ～ ".$params['duedate_end']." 』 検索しました<br>\n";
                    }
                    if(isset($viewmode['customer'])) {
                        $e_message .= "得意先 『 ".$params['s_customer']." 』 検索しました<br>\n";
                    }
                    if(isset($viewmode['pname'])) {
                        $e_message .= "品名 『 ".$params['s_product_name']." 』 検索しました<br>\n";
                    }
                    if(isset($viewmode['enduser'])) {
                        $e_message .= "エンドユーザー 『 ".$params['s_end_user']." 』 検索しました<br>\n";
                    }
                    if(isset($viewmode['default'])) {
                        $limitcount = $datacount < $lnum ? $datacount : $lnum ;
                        //$e_message .= "納期　本日以降  ".$limitcount." 件の表示<br>\n";
                        $e_message .= "".$limitcount." 件の表示<br>\n";
                    }
                    if(isset($limiton)) {
                        $e_message .= "結果表示は 300件までです。<br>\n";
                    }


                }
                else {
                    $r_after_due_date = "";

                    $e_message .= " 結果 ： ".$datacount." 件 データがありません";
                    $result_msg = "none";

    

                }



                

            //return $result;
            //$wd_result = $this->workdateSearch($request);


        } catch (PDOException $e){
            //print('Error:'.$e->getMessage());
            $action_msg .= $e->getMessage().PHP_EOL."<br>\n";
            //die();
        }

        /*
        	's_customer' => $params['s_customer'],
			's_product_name' => $params['s_product_name'],
			's_end_user' => $params['s_end_user'],
			'quantity' => $quantity,
			'comment' => $comment,
        */

        $redata = array();
        $redata = [
            'datacount' => $datacount, 
            'html_after_due_date' => $html_after_due_date,
			'product_code' => $s_product_code,
			'after_due_date' => $r_after_due_date,
			's_customer' => $s_customer,
			's_product_name' => $s_product_name,
			's_end_user' => $s_end_user,
            'result' => $result,
            'mode' => $mode, 
            'e_message' => $e_message, 
            'result_msg' => $result_msg,
        ];

        return $redata;



    }


    public function SearchProcessDate($request,$s_product_code) {
        //$s_product_code = !empty($_POST["s_product_code"]) ? $_POST['s_product_code'] : "";
        $mode = !empty($_POST["mode"]) ? $_POST['mode'] : "";
        $action_msg = "";
        $result = "";
        $result_msg = "";
        $f_work_date = "";
        $html_f_work_date = "";
        $e_message = "工程 ： ".$s_product_code."";

        //echo "s_product_code >> ".$s_product_code."<br>\n";

        try {

            if(isset($s_product_code)) {
                $data = DB::table($this->table_process_date)
                ->select(
                    'id',
                    'work_date',
                    'product_code',
                    'departments_name',
                    'departments_code',
                    'work_name',
                    'work_code',
                    'process_name',
                    'status',
                    'performance',
                    'comment'
                );
                $data->where('product_code', $s_product_code);
                $result = $data
                ->get();
                $datacount = $data->count();



                if($datacount > 0) {
                    $f_work_date = $result[0]->work_date;
                    $html_f_work_date = !empty($f_work_date) ? date('n月j日', strtotime($f_work_date)) : "";
                    $result_msg = "OK date";
                    $e_message .= " 伝票番号 = ".$result[0]->product_code." <> count = ".$datacount." <> date = ".$html_f_work_date;

                }
                else {

                    $e_message .= " データがありません <> count = ".$datacount."";
                    $result_msg = "none";
    

                }



                
            }

            //return $result;
            //$wd_result = $this->workdateSearch($request);


        } catch (PDOException $e){
            //print('Error:'.$e->getMessage());
            $action_msg .= $e->getMessage().PHP_EOL."<br>\n";
            //die();
        }
        

        $redata = array();
        $redata = [
            'datacount' => $datacount, 
            'html_f_work_date' => $html_f_work_date,
			'product_code' => $s_product_code,
			'f_work_date' => $f_work_date,
            'result' => $result,
            'mode' => $mode, 
            'e_message' => $e_message, 
            'result_msg' => $result_msg,
        ];

        return $redata;



    }



    
}
