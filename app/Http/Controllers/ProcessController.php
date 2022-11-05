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

class ProcessController extends Controller
{

    protected $table = 'process_details';

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





    public function index()
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
        $action_msg = "";
        $result = "";
        $result_msg = "";
        $html_after_due_date = "";
        $e_message = "検索 ： ".$s_product_code."";

        return view('process', [
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
            'result' => $result,
        ]);


    }




    public function postSearch()
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
        $action_msg = "";
        $result = "";
        $result_msg = "";
        $html_after_due_date = "";
        $e_message = "検索 ： ".$s_product_code."";





        try {

            if(isset($s_product_code)) {
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
                    'comment'
                );
                $data->where('product_code', $s_product_code);
                $result = $data
                ->get();
                $datacount = $data->count();




                if($datacount > 0) {
                    $r_after_due_date = $result[0]->after_due_date;
                    $html_after_due_date = !empty($r_after_due_date) ? date('n月j日', strtotime($r_after_due_date)) : "";
                    $result_msg = "OK";
                    $e_message .= " 伝票番号 = ".$result[0]->product_code." <> count = ".$datacount." <> date = ".$html_after_due_date;

                }
                else {

                    $e_message .= " データがありません <> count = ".$datacount." <> date = ".$html_after_due_date;
                    $result_msg = "none";
    

                }





                
            }

            //return $result;


        } catch (PDOException $e){
            //print('Error:'.$e->getMessage());
            $action_msg .= $e->getMessage().PHP_EOL."<br>\n";
            //die();
        }
        





	        return view('process', [
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
                'result' => $result,
	        ]);


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




}
