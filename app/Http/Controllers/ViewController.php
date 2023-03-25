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
use App\Models\Calendar;
use App\Models\ProcessLog;

class ViewController extends Controller
{

    protected $table = 'process_details';
    protected $table_process_date = 'process_date';

    private $id;
    private $product_code;          // 伝票番号
    private $serial_code;           // 整理番号
    private $rep_code;              // 担当
    private $after_due_date;        // 納期
    private $customer;              // 得意先
    private $product_name;          // 品名
    private $end_user;              // エンドユーザー
    private $quantity;              // 数量
    private $status;                // ステータス

    private $process_1;             // 工程１
    private $departments_name_1;    // 部署１
    private $departments_code_1;    // 部署コード１
    private $work_name_1;           // 作業１
    private $work_code_1;           // 作業コード１
    private $start_process_date_1;  // 工程１開始日
    private $end_process_date_1;    // 工程１終了日

    private $comment;               // コメント
    private $created_user;          // 作成ユーザー
    private $updated_user;          // 修正ユーザー
    private $created_at;            // 作成日時
    private $updated_at;            // 修正日時
    private $is_deleted;            // 削除フラグ





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


        /*
        $product_code = !empty($_POST["product_code"]) ? $_POST['product_code'] : "";
        $after_due_date = !empty($_POST["after_due_date"]) ? $_POST['after_due_date'] : "";
        $customer = !empty($_POST["customer"]) ? $_POST['customer'] : "";
        $product_name = !empty($_POST["product_name"]) ? $_POST['product_name'] : "";
        $end_user = !empty($_POST["end_user"]) ? $_POST['end_user'] : "";
        $quantity = !empty($_POST["quantity"]) ? $_POST['quantity'] : "";
        $comment = !empty($_POST["comment"]) ? $_POST['comment'] : "";
        */
        $mode = !empty($_POST["mode"]) ? $_POST['mode'] : "";
        $select_html = !empty($_POST["select_html"]) ? $_POST['select_html'] : "";
        $action_msg = "";
        $result = "";
        $result_date = "";
        $wd_result = "";
        $result_msg = "";
        $html_after_due_date = "";
        $e_message = "";


        $result_details = $this->SearchProcessDetails($request);



        return view('view', [
            's_product_code' => $s_product_code,
            'mode' => $mode,
            'action_msg' => $action_msg,
            'e_message' => $e_message,
            'result' => $result_details,
            'result_date' => $result_date,
            'wd_result' => $wd_result,
            'html_cal_main' => '',
            'select_html' => $select_html,
            'duedate_start' => $duedate_start,
            'duedate_end' => $duedate_end,
            's_customer' => $s_customer,
            's_product_name' => $s_product_name,
            's_end_user' => $s_end_user,

        ]);


    }


    public function oneSearch(Request $request)
    {
        
        $s_product_code = !empty($_POST["s_product_code"]) ? $_POST['s_product_code'] : "";
        $product_code = !empty($_POST["product_code"]) ? $_POST['product_code'] : "";
        $after_due_date = !empty($_POST["after_due_date"]) ? $_POST['after_due_date'] : "";
        $customer = !empty($_POST["customer"]) ? $_POST['customer'] : "";
        $product_name = !empty($_POST["product_name"]) ? $_POST['product_name'] : "";
        $end_user = !empty($_POST["end_user"]) ? $_POST['end_user'] : "";
        $quantity = !empty($_POST["quantity"]) ? $_POST['quantity'] : "";
        $comment = !empty($_POST["comment"]) ? $_POST['comment'] : "";
        $mode = !empty($_POST["mode"]) ? $_POST['mode'] : "";
        $select_html = !empty($_POST["select_html"]) ? $_POST['select_html'] : "";
        $viewmode = "watch";
        $action_msg = "";
        $result = "";
        $result_date = "";
        $wd_result = "";
        $result_msg = "";
        $html_after_due_date = "";
        $e_message = "";


        $result_details = $this->SearchProcessDetails($request);

        if($result_details['datacount'] === 1) {
            $after_due_date = $result_details['after_due_date'];    // return $redata = [ の after_due_date を指す。
            //$test = $result_details['result'][0]->after_due_date;    // return result[]から取得する場合　[0]のキーが必要。
            $calendar_data = new Calendar();	// インスタンス作成
            $html_cal = $calendar_data->calendar($result_details,$after_due_date,$wd_result,$result_date,$viewmode);	//開始年月～何か月分
    
        }


        return view('view', [
            's_product_code' => $s_product_code,
            'product_code' => $product_code,
            'after_due_date' => $after_due_date,
            'customer' => $customer,
            'product_name' => $product_name,
            'end_user' => $end_user,
            'quantity' => $quantity,
            'comment' => $comment,
            'mode' => $mode,
            'action_msg' => $action_msg,
            'e_message' => $e_message,
            'result' => $result_details,
            'result_date' => $result_date,
            'wd_result' => $wd_result,
            'html_cal_main' => '',
            'select_html' => $select_html,

        ]);


    }








    public function postSearch(Request $request)
    {


        $s_product_code = !empty($_POST["s_product_code"]) ? $_POST['s_product_code'] : "";
        $product_code = !empty($_POST["product_code"]) ? $_POST['product_code'] : "";
        $after_due_date = !empty($_POST["after_due_date"]) ? $_POST['after_due_date'] : "";
        $customer = !empty($_POST["customer"]) ? $_POST['customer'] : "";
        $product_name = !empty($_POST["product_name"]) ? $_POST['product_name'] : "";
        $end_user = !empty($_POST["end_user"]) ? $_POST['end_user'] : "";
        $quantity = !empty($_POST["quantity"]) ? $_POST['quantity'] : "";
        $comment = !empty($_POST["comment"]) ? $_POST['comment'] : "";
        $mode = !empty($_POST["mode"]) ? $_POST['mode'] : "";
        $select_html = !empty($_POST["select_html"]) ? $_POST['select_html'] : "";
        $action_msg = "";
        $result = "";
        $wd_result = "";
        $result_logsearch = "";
        $result_msg = "";
        $html_after_due_date = "";
        $html_cal = "";
        $viewmode = "watch";
        $e_message = "検索 ： ".$s_product_code."  ".$after_due_date."";

        $duedate_start = !empty($_POST["duedate_start"]) ? $_POST['duedate_start'] : "";
        $duedate_end = !empty($_POST["duedate_end"]) ? $_POST['duedate_end'] : "";
        $s_customer = !empty($_POST["s_customer"]) ? $_POST['s_customer'] : "";
        $s_product_name = !empty($_POST["s_product_name"]) ? $_POST['s_product_name'] : "";
        $s_end_user = !empty($_POST["s_end_user"]) ? $_POST['s_end_user'] : "";


        $result_details = $this->SearchProcessDetails($request);
        $result_date = $this->SearchProcessDate($request);
        //echo "result_details['datacount'] = ".$result_details['datacount']."<br>\n";
        if($result_details['datacount'] == 1) {
            $after_due_date = $result_details['after_due_date'];    // return $redata = [ の after_due_date を指す。
            //$test = $result_details['result'][0]->after_due_date;    // return result[]から取得する場合　[0]のキーが必要。
            $calendar_data = new Calendar();	// インスタンス作成
            $html_cal = $calendar_data->calendar($result_details,$after_due_date,$wd_result,$result_date,$viewmode);	//開始年月～何か月分
            $processlog_data = new ProcessLog();	// インスタンス作成
            $result_logsearch = $processlog_data->LogSearch($request);

        }
        //$result = array_merge($result_details, $result_dete);

	        return view('view', [
                's_product_code' => $s_product_code,
                'product_code' => $product_code,
                'after_due_date' => $after_due_date,
                'customer' => $customer,
                'product_name' => $product_name,
                'end_user' => $end_user,
                'quantity' => $quantity,
                'comment' => $comment,
            	'mode' => $mode,
                'action_msg' => $action_msg,
                'e_message' => $e_message,
                'result' => $result_details,
                'result_date' => $result_date,
                'result_log' => $result_logsearch,
                'wd_result' => '',
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
        $lnum = 20;  // limitの件数
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
                    $data->where('after_due_date', '>=', $params['duedate_start'])->limit(30);
                    $viewmode['duedatestart'] = 1;
                    $default = false;
                    $limiton = true;
                }
                if(!empty($params['duedate_end'])) {
                    $data->where('after_due_date', '<=', $params['duedate_end'])
                    ->limit(30);
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
                    $data->whereDate('after_due_date', '>=', $systemdate);
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
                    $e_message .= "検索結果 ： ".$datacount." 件<br>\n";
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
                        $e_message .= "納期　本日以降  ".$limitcount." 件の表示<br>\n";
                    }
                    if(isset($limiton)) {
                        $e_message .= "結果表示は 30件までです。<br>\n";
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


    public function SearchProcessDate($request) {
        $s_product_code = !empty($_POST["s_product_code"]) ? $_POST['s_product_code'] : "";
        $mode = !empty($_POST["mode"]) ? $_POST['mode'] : "";
        $action_msg = "";
        $result = "";
        $result_msg = "";
        $f_work_date = "";
        $html_f_work_date = "";
        $e_message = "工程 ： ".$s_product_code."";

        try {

            if(isset($s_product_code)) {
                $data = DB::table($this->table_process_date)
                ->select(
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
























    public function workdateSearch($request)
    {
        //$json = file_get_contents('php://input');
        //$data = json_decode($json, true);
        $content = $request->getContent();
        $data = json_decode($content, true) ?? [];
        
        $ng_write = "";
        var_dump($data);


        if(!empty($data)) {
            $s_product_code = !empty($data['s_product_code']) ? $data['s_product_code'] : "";
            $product_code = !empty($data['product_code']) ? $data['product_code'] : "";
            $departments_code = !empty($data['departments_code']) ? $data['departments_code'] : "";
            $work_code = !empty($data['work_code']) ? $data['work_code'] : "";
            $mode = !empty($data['mode']) ? $data['mode'] : "";
        }
        else {
            $s_product_code = !empty($_POST['s_product_code']) ? $_POST['s_product_code'] : "";
            $product_code = !empty($_POST['product_code']) ? $_POST['product_code'] : "";
            $departments_code = !empty($_POST['departments_code']) ? $_POST['departments_code'] : "";
            $work_code = !empty($_POST['work_code']) ? $_POST['work_code'] : "";
            $mode = !empty($_POST['mode']) ? $_POST['mode'] : "";
        }


        $action_msg = "";
        $result = "";
        $wd_result = "";
        $result_msg = "";
        $html_after_due_date = "";
        $e_message = "検索 ： ".$s_product_code."";

        $work_date_arr = $request->only(['work_date']);
        $str = "";
        if(is_array($work_date_arr)) {
            foreach($work_date_arr AS $key => $wdarr) {
                foreach($wdarr AS $wdkey => $val) {
                    $str .= "wdkey=".$wdkey.":val=".$val;
                }
            }
        }




        try {

            if(isset($s_product_code)) {
                $data = DB::table($this->table_process_date)
                ->select(
                    'work_date',
                    'product_code',
                    'departments_name',
                    'departments_code',
                    'work_name',
                    'work_code',
                    'process_name',
                    'status'

                );
                $data->where('product_code', $s_product_code);
                $data->where('departments_code', $departments_code);
                $data->where('work_code', $work_code);
                $result = $data
                ->get();
                $datacount = $data->count();



                if($datacount > 0) {
                    $result_msg = "OK";
                    $e_message .= " 伝票番号 = ".$result[0]->product_code." <> count = ".$datacount." <> ";

                }
                else {

                    $e_message .= " データがありません <> count = ".$datacount." <>  = ";
                    $result_msg = "none";
    

                }



                
            }

            //return $result;


        } catch (PDOException $e){
            //print('Error:'.$e->getMessage());
            $action_msg .= $e->getMessage().PHP_EOL."<br>\n";
            //die();
        }
        
//$wd_result = (['departments_code' => $departments_code, 'work_code' => $work_code, 's_product_code' => $s_product_code, '文字列' => '日本語']);
        $redata = [
            's_product_code' => $s_product_code,
            'product_code' => $product_code,
            'departments_code' => $departments_code,
            'work_code' => $work_code,
            'wd_result' => $result,
            'e_message' => $e_message, 
            'result_msg' => $result_msg,
            'mode' => $mode, 
        ];

            return $redata;

    }

    public function workDate(Request $request)
    {
        $content = $request->getContent();
        $data = json_decode($content, true) ?? [];
        
        $ng_write = "";
        //var_dump($data);

        //$result = $this->workdateSearch($request);

        if(!empty($data)) {
            $s_product_code = !empty($data['s_product_code']) ? $data['s_product_code'] : "";
            $product_code = !empty($data['product_code']) ? $data['product_code'] : "";
            $departments_code = !empty($data['departments_code']) ? $data['departments_code'] : "";
            $work_code = !empty($data['work_code']) ? $data['work_code'] : "";
            $mode = !empty($data['mode']) ? $data['mode'] : "";
        }
        else {
            $s_product_code = !empty($_POST['s_product_code']) ? $_POST['s_product_code'] : "";
            $product_code = !empty($_POST['product_code']) ? $_POST['product_code'] : "";
            $departments_code = !empty($_POST['departments_code']) ? $_POST['departments_code'] : "";
            $work_code = !empty($_POST['work_code']) ? $_POST['work_code'] : "";
            $mode = !empty($_POST['mode']) ? $_POST['mode'] : "";
        }


        $action_msg = "";
        $result = "";
        $wd_result = "";
        $result_msg = "";
        $html_after_due_date = "";
        $e_message = "検索 ： ".$s_product_code."";

        $work_date_arr = $request->only(['work_date']);
        $str = "";
        if(is_array($work_date_arr)) {
            foreach($work_date_arr AS $key => $wdarr) {
                foreach($wdarr AS $wdkey => $val) {
                    $str .= "wdkey=".$wdkey.":val=".$val;
                }
            }
        }




        try {

            if(isset($s_product_code)) {
                $data = DB::table($this->table_process_date)
                ->select(
                    'work_date',
                    'product_code',
                    'departments_name',
                    'departments_code',
                    'work_name',
                    'work_code',
                    'process_name',
                    'status'

                );
                $matchThese = ['product_code' => $s_product_code, 'departments_code' => $departments_code, 'work_code' => $work_code];
                $data->where($matchThese);
                $result = $data
                ->get();
                $datacount = $data->count();



                if($datacount > 0) {
                    $result_msg = "OK";
                    $e_message .= " 伝票番号 = ".$result[0]->product_code." <> count = ".$datacount." <> ";

                }
                else {

                    $e_message .= " データがありません <> count = ".$datacount." <>  = ";
                    $result_msg = "none";
    

                }
                $e_message .= "<br>matchThese = ".implode(',',$matchThese);

                
            }

            //return $result;


        } catch (PDOException $e){
            //print('Error:'.$e->getMessage());
            $action_msg .= $e->getMessage().PHP_EOL."<br>\n";
            //die();
        }



        $redata[] = [
            's_product_code' => $s_product_code,
            'departments_code' => $departments_code,
            'work_code' => $work_code,
            'wd_result' => $result,
            'e_message' => $e_message, 
            'result_msg' => $result_msg,
            'mode' => $mode
        ];

            //return $redata;
            if(!empty($redata)) {
                $jsondata = json_encode($redata, JSON_UNESCAPED_UNICODE);
                $pattern = ['/"\s*"/', '/null/'];
                $replace = ['""', '""'];
                $jsonresult = preg_replace($pattern, $replace, $jsondata);
    
                //echo $jsonresult;
                echo $jsondata;
            }
    



    }















    public function getRequestFunc(Request $request)
    {
        
        $mode = !empty($_POST["mode"]) ? $_POST['mode'] : "";
        $action_msg = "";

	        return view('process', [
            	'mode' => $mode,
                'action_msg' => $action_msg,
	        ]);


    }

    public function getWORK()
    {

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        $ng_write = "";


        //$s_product_id = isset($data['s_product_id']) ? $data['s_product_id'] : "";
        $department = isset($data['department']) ? $data['department'] : "";
        $mode = isset($data['mode']) ? $data['mode'] : "";
        $submode = isset($data['submode']) ? $data['submode'] : "";
        $details = isset($data['details']) ? $data['details'] : "";

        $action_msg = "";
        $result = "";
        $result_msg = "";
        $html_after_due_date = "";
        $e_message = "検索 ： ".$department."";

        try {

            if(isset($department)) {
                /*
                $check_data = DB::table($this->table)
                ->select(
                    'product_code AS product_id',
                    'serial_code AS serial_id',
                    'rep_code AS rep_id',
                    'after_due_date',
                    'customer',
                    'product_name',
                    'end_user',
                    'quantity',
                    'comment'
                );
                $check_data->where('product_code', $s_product_id);
                $check_count = $check_data->count();
                */

                //table('tasks')作業（掃除等含むのでそのままでは使えない）  table('equipments')機械
                $data = DB::connection('nippou')->table('equipments')
                ->select(
                    'id',
                    'name',
                    'department_id'
                );
                $data->where('department_id', $department);
                //$data->where('before_due_date', '2022/10/24');
                $result = $data
                ->get();
                $count = $data->count();

                    if($count > 0){
                        //$r_after_due_date = $result[0]->after_due_date;
                        //$html_after_due_date = !empty($r_after_due_date) ? date('n月j日', strtotime($r_after_due_date)) : "";
                        //$e_message .= " 既に登録されています <> count = ".$count." <> date = ".$html_after_due_date;
                        $result_msg = "OK";
                    }
                    else {

                        /*
                    $result = $check_data
                    ->get();
                    $r_after_due_date = $result[0]->after_due_date;
                    $html_after_due_date = !empty($r_after_due_date) ? date('n月j日', strtotime($r_after_due_date)) : "";
                    $e_message .= " 日報に登録なし <> count = ".$count." <> date = ".$html_after_due_date;
                    */
                    $result_msg = "nothing";

                    }

                
            }

            //return $result;


        } catch (PDOException $e){
            //print('Error:'.$e->getMessage());
            $action_msg .= $e->getMessage().PHP_EOL."<br>\n";
            //die();
        }
        


        $redata = array();
        $redata[] = [
            'result' => $result,
            'department' => $department,
            'mode' => $mode, 
            'e_message' => $e_message, 
            'result_msg' => $result_msg
        ];
        if(!empty($redata)) {
            $jsondata = json_encode($redata, JSON_UNESCAPED_UNICODE);
            $pattern = ['/"\s*"/', '/null/'];
            $replace = ['""', '""'];
            $jsonresult = preg_replace($pattern, $replace, $jsondata);

            echo $jsonresult;
            //echo json_encode($redata, JSON_UNESCAPED_UNICODE);
        }

    }











}
