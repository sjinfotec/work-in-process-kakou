<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WorkView extends Model
{

    protected $table_process_details = 'process_details';
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
    private $receive_date;          // 入稿日
    private $platemake_date;        // 下版日
    private $status;                // ステータス
    private $comment;               // コメント
    private $created_user;          // 作成ユーザー
    private $updated_user;          // 修正ユーザー
    private $created_at;            // 作成日時
    private $updated_at;            // 修正日時
    private $is_deleted;            // 削除フラグ


    private $work_date;             // 作業日
    private $departments_name;      // 部署
    private $departments_code;      // 部署コード
    private $work_name;             // 作業
    private $work_code;             // 作業コード
    private $process_name;          // 工程名
    private $performance;           // 作業実績


    use HasFactory;
    public function SearchData(Request $request)
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
        //$select_html = !empty($_POST["select_html"]) ? $_POST['select_html'] : "";
        $action_msg = "";
        $result = "";
        $wd_result = "";
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
        $after_due_date = $result_details['after_due_date'];    // return $redata = [ の after_due_date を指す。
        //$test = $result_details['result'][0]->after_due_date;    // return result[]から取得する場合　[0]のキーが必要。
    

        $redata = array();
        $redata = [
            'html_after_due_date' => $html_after_due_date,
			'product_code' => $s_product_code,
			's_customer' => $s_customer,
			's_product_name' => $s_product_name,
			's_end_user' => $s_end_user,
            'result_details' => $result_details,
            'result_date' => $result_date,
            'mode' => $mode, 
            'e_message' => $e_message, 
            'result_msg' => $result_msg,
        ];

        return $redata;

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
            'pcode',
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

                $data = DB::table($this->table_process_details)
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
                if(!empty($params['pcode'])) {
                    $data->where('product_code', $params['pcode']);
                    $viewmode['pcode'] = 1;
                    $default = false;
                }
                if(!empty($params['s_product_code'])) {
                    $data->where('product_code', $params['s_product_code']);
                    $viewmode['pcode'] = 1;
                    $default = false;
                }
                if(!empty($params['duedate_start'])) {
                    $data->where('after_due_date', '>=', $params['duedate_start']);
                    $viewmode['duedatestart'] = 1;
                    $default = false;
                }
                if(!empty($params['duedate_end'])) {
                    $data->where('after_due_date', '<=', $params['duedate_end']);
                    $viewmode['duedatestart'] = 1;
                    $default = false;
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
            'pcode' => $params['pcode'],
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
        $departments_code_arr = Array('2','4','5','3','6','7');

        $params = $request->only([
            'pcode',
            'wday',
            'departments_code',
            'work_code',
            'mode',
            'submode',
        ]);

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
                if(!empty($params['pcode'])) {
                    $data->where('product_code', $params['pcode']);
                    $viewmode['pcode'] = 1;
                    $default = false;
                }
                if(!empty($params['wday'])) {
                    $data->where('work_date', $params['wday']);
                    $viewmode['wday'] = 1;
                    $default = false;
                }
                $data->orderByRaw('FIELD(departments_code, '.implode(',', $departments_code_arr).')');
                $result = $data
                ->get();
                $datacount = $data->count();



                if($datacount > 0) {
                    $f_work_date = $result[0]->work_date;
                    $html_f_work_date = !empty($f_work_date) ? date('n 月 j 日', strtotime($f_work_date)) : "";
                    $result_msg = "OK date";
                    $e_message .= " 伝票番号 = ".$result[0]->product_code." <> count = ".$datacount." <> date = ".$html_f_work_date;

                }
                else {

                    $html_f_work_date = date('n 月 j 日', strtotime($params['wday']));
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
			'wday' => $params['wday'],
            'result' => $result,
            'mode' => $mode, 
            'e_message' => $e_message, 
            'result_msg' => $result_msg,
        ];

        return $redata;



    }



    public function STTSchange(Request $request)
    {
        $result_date = $this->updateProcessDate($request);
        return $result_date;


    }

    public function updateProcessDate($request) {
        $action_msg = "";
        $result = "";
        $result_msg = "";
        $e_message = "作業工程 ： ";
        $systemdate = Carbon::now();
        $ipaddr = mb_substr($_SERVER["REMOTE_ADDR"].'', 0, 50);

        $params = $request->only([
            'work_date',
            'product_code',
            'departments_code',
            'work_code',
            'departments_name',
            'work_name',
            'mode',
            'submode',
        ]);

        try {

            $status_str = $params['submode'] == 'change' ? '完了' : '';



            if($params['mode'] == 'status_update') {
                $updateresult = DB::table($this->table_process_date)
                ->where('work_date', $params['work_date'])
                ->where('product_code', $params['product_code'])
                ->where('departments_code', $params['departments_code'])
                ->where('work_code', $params['work_code'])
                ->update(
                    [
                        'status' => $status_str,
                        'updated_user' => $ipaddr,
                        'updated_at' => $systemdate
                    ]
                );

            }



                if($updateresult) {
                    if($params['submode'] == 'rechange') {
                        $sshtml = "作業未完に変更";
                    }
                    elseif($params['submode'] == 'change') {
                        $sshtml = "完了";
                    }
                    else {$sshtml = "";}
                    $result_msg = "OK";
                    $e_message .= "".$sshtml." => 作業日 : ".$params['work_date']." => 部署 : ".$params['departments_name']." => 作業名 : ".$params['work_name']."";

                }
                else {

                    $e_message .= "status_str -> ".$status_str." & ".$params['submode']."<br>";
                    $result_msg = "none";
    

                }

            //$e_message .= "work_date ".$params['work_date'].": product_code ".$params['product_code'].": departments_code ".$params['departments_code'].": work_code ".$params['work_code']."";

        } catch (PDOException $e){
            //print('Error:'.$e->getMessage());
            $action_msg .= $e->getMessage().PHP_EOL."<br>\n";
            //die();
        }
        

        $redata = array();
        $redata = [
			'product_code' => $params['product_code'],
            'work_date' => $params['work_date'],
            'work_code' => $params['work_code'],
            'result' => $updateresult,
            'mode' => $params['mode'], 
            'e_message' => $e_message, 
            'result_msg' => $result_msg,
        ];

        return $redata;



    }





















}
