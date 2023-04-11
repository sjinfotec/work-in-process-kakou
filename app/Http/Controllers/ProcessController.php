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

class ProcessController extends Controller
{

    protected $table = 'process_details';
    protected $table_process_date = 'process_date';
    protected $table_process_log = 'process_log';

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
        $result_date = "";
        $wd_result = "";
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
            'e_message' => $e_message,
            'result' => $result,
            'result_date' => $result_date,
            'wd_result' => $wd_result,
            'html_cal_main' => '',
            'select_html' => '',

        ]);


    }




    public function workdateSearch($request)
    {
        //$json = file_get_contents('php://input');
        //$data = json_decode($json, true);
        $content = $request->getContent();
        $data = json_decode($content, true) ?? [];
        
        $ng_write = "";
        //var_dump($data);


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
                    $e_message .= " 伝票番号 = ".$result[0]->product_code." <> ヒット件数 = ".$datacount." <> ";

                }
                else {

                    $e_message .= " データがありません <> ヒット件数 = ".$datacount." <>  = ";
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
        $result_msg = "";
        $html_after_due_date = "";
        $viewmode = "editing";
        $e_message = "検索 ： ".$s_product_code."  ".$after_due_date."";


        $result_details = $this->SearchProcessDetails($request);
        $result_date = $this->SearchProcessDate($request);
        $processlog_data = new ProcessLog();	// インスタンス作成
        $result_logsearch = $processlog_data->LogSearch($request);

        //var_dump($result_details['result'][0]); 
        //echo $av = $result_details['datacount'] ?: "なし<br>\n";
        //echo "<br><br>\n";
        //var_dump($result_date['result']); 
        //$result = array_merge($result_details, $result_dete);
        $after_due_date = $result_details['after_due_date'];    // return $redata = [ の after_due_date を指す。


        if($result_details['datacount'] > 0) {
            $afd = $result_details['result'][0]->after_due_date ?:"";    // return result[]から取得する場合　[0]のキーが必要。
            $calendar_data = new Calendar();	// インスタンス作成
            $html_cal = $calendar_data->calendar($result_details,$after_due_date,$wd_result,$result_date,$viewmode);	//開始年月～何か月分
        }
        else {
            $afd = "";
            $html_cal = "";
        }



	        return view('process', [
                's_product_code' => $s_product_code,
                'product_code' => $product_code,
                'after_due_date' => $afd,
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
	        ]);

    }


    public function SearchProcessDetails($request) {
        $s_product_code = !empty($_POST["s_product_code"]) ? $_POST['s_product_code'] : "";
        $product_code = !empty($_POST["product_code"]) ? $_POST['product_code'] : "";
        $after_due_date = !empty($_POST["after_due_date"]) ? $_POST['after_due_date'] : "";
        $customer = !empty($_POST["customer"]) ? $_POST['customer'] : "";
        $product_name = !empty($_POST["product_name"]) ? $_POST['product_name'] : "";
        $end_user = !empty($_POST["end_user"]) ? $_POST['end_user'] : "";
        $quantity = !empty($_POST["quantity"]) ? $_POST['quantity'] : "";
        $receive_date = !empty($_POST["receive_date"]) ? $_POST['receive_date'] : "";
        $platemake_date = !empty($_POST["platemake_date"]) ? $_POST['platemake_date'] : "";
        $comment = !empty($_POST["comment"]) ? $_POST['comment'] : "";
        $mode = !empty($_POST["mode"]) ? $_POST['mode'] : "";
        $action_msg = "";
        $result = "";
        $wd_result = "";
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
                    'receive_date',
                    'platemake_date',
                    'status',
                    'comment',
                    'created_user',
                    'updated_user',
                    'created_at',
                    'updated_at'
                );
                $data->where('product_code', $s_product_code);
                $result = $data
                ->get();
                $datacount = $data->count();



                if($datacount > 0) {
                    $r_after_due_date = $result[0]->after_due_date;
                    $html_after_due_date = !empty($r_after_due_date) ? date('n月j日', strtotime($r_after_due_date)) : "";
                    $result_msg = "OK";
                    $e_message .= " 伝票番号 : ".$result[0]->product_code." <> 納期 : ".$html_after_due_date." <> ".$datacount."";

                }
                else {

                    $e_message .= " データがありません <> ".$datacount."";
                    $result_msg = "none";
                    $r_after_due_date = "";
    

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
            'html_after_due_date' => $html_after_due_date,
			'product_code' => $s_product_code,
			'after_due_date' => $r_after_due_date,
			'customer' => $customer,
			'product_name' => $product_name,
			'end_user' => $end_user,
			'quantity' => $quantity,
			'comment' => $comment,
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
        $e_message = "検索 ： ".$s_product_code."";

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
                        if($department == 3) {
                            //削除実行
                            /*
                            foreach ($result as $key => $val) {
                                if($key <= 2){
                                    unset($result[$key]);   //削除実行
                                }
                            }
                            //indexを詰める
                            $result = array_values($result);
                            */
                            //その他の手法
                            //$result = array_diff($target, array('c', 'e'));
                            //$result = array_splice($result, 1, 2);
                            $result = Array();
                            $result[0] = [
                                'id' => '1032',
                                'name' => 'テストデータ',
                                'department_id' => '3'
                                ];
                            $result[1] = [
                                'id' => '1033',
                                'name' => '本番データ',
                                'department_id' => '3'
                                ];
                            $result[2] = [
                                'id' => '1034',
                                'name' => '発送',
                                'department_id' => '3'
                                ];
                            $result[3] = [
                                'id' => '44',
                                'name' => 'PC',
                                'department_id' => '3'
                                ];
                            $result[4] = [
                                'id' => '1031',
                                'name' => '作成',
                                'department_id' => '3'
                                ];
                            $result[] = [
                                'id' => '61',
                                'name' => '打ち合わせ',
                                'department_id' => '3'
                                ];
                                
                        }
                        if($department == 6) {
                            $result[] = [
                                'id' => '1021',
                                'name' => '仕分',
                                'department_id' => '6'
                                ];
                        }




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

                        if($department == 1) {
                            $result[] = [
                                'id' => '1041',
                                'name' => '納期変更',
                                'department_id' => '1'
                                ];
                            $result_msg = "OK";
                        }
                        if($department == 8) {
                            $result[] = [
                                'id' => '1001',
                                'name' => 'チェック',
                                'department_id' => '8'
                                ];
                            $result[] = [
                                'id' => '1002',
                                'name' => '箱入荷',
                                'department_id' => '8'
                                ];
                            $result[] = [
                                'id' => '1003',
                                'name' => 'ラベル',
                                'department_id' => '8'
                                ];
                            $result[] = [
                                'id' => '1004',
                                'name' => '伝票出力済',
                                'department_id' => '8'
                                ];
                            $result[] = [
                                'id' => '1005',
                                'name' => '起票',
                                'department_id' => '8'
                                ];
                            $result_msg = "OK";
                        }
                        if($department == 10) {
                            $result[] = [
                                'id' => '1011',
                                'name' => '横持',
                                'department_id' => '10'
                                ];
                            $result[] = [
                                'id' => '1012',
                                'name' => '出荷',
                                'department_id' => '10'
                                ];
                            $result_msg = "OK";
                        }
                        if($department == 13) {
                            $result[] = [
                                'id' => '41',
                                'name' => '東レ',
                                'department_id' => '13'
                                ];
                            $result[] = [
                                'id' => '42',
                                'name' => 'ドットプリンタ',
                                'department_id' => '13'
                                ];
                            $result[] = [
                                'id' => '43',
                                'name' => 'オンデマンド',
                                'department_id' => '13'
                                ];
                            $result[] = [
                                'id' => '61',
                                'name' => '打ち合わせ',
                                'department_id' => '13'
                                ];
                            $result_msg = "OK";
                        }
                        if($department == 29) {
                            $result[] = [
                                'id' => '1029',
                                'name' => 'コメント',
                                'department_id' => '29'
                                ];
                            $result_msg = "OK";
                        }


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















    public function updateProcessDetails(Request $request)
    {


        $details = isset($_POST['details']) ? $_POST['details'] : [];
        /*
        foreach($details AS $key => $val) {
            $name = $val;
        }
        */

        $s_product_code = !empty($_POST["s_product_code"]) ? $_POST['s_product_code'] : "";
        $product_code = !empty($_POST["product_code"]) ? $_POST['product_code'] : "";
        $after_due_date = !empty($_POST["after_due_date"]) ? $_POST['after_due_date'] : "";
        $customer = !empty($_POST["customer"]) ? $_POST['customer'] : "";
        $product_name = !empty($_POST["product_name"]) ? $_POST['product_name'] : "";
        $end_user = !empty($_POST["end_user"]) ? $_POST['end_user'] : "";
        $quantity = !empty($_POST["quantity"]) ? $_POST['quantity'] : "";
        $comment = !empty($_POST["comment"]) ? $_POST['comment'] : "";
        $mode = !empty($_POST["mode"]) ? $_POST['mode'] : "";
        $motion = !empty($_POST["motion"]) ? $_POST['motion'] : "";

        $this->serial_code = $request->serial_code;
        $params = $request->only([
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
            'created_at',
            'updated_user',
            'updated_at',
        ]);




        $action_msg = "";
        $result = "";
        $result_date = "";
        $result_logsearch = "";
        $wd_result = "";
        $result_msg = "";
        $html_after_due_date = "";
        $html_cal = "";
        $viewmode = "editing";
        $e_message = "検索 ： ".$s_product_code."";


        $listcount = isset($_POST['listcount']) ? $_POST['listcount'] : "";
        $this->status = isset($_POST['status']) ? $_POST['status'] : "";

        //$mode = isset($_POST['mode']) ? $_POST['mode'] : "";
        $upkind = isset($_POST['upkind']) ? $_POST['upkind'] : "";
        $details = isset($_POST['details']) ? $_POST['details'] : [];

        //echo "processController mode = ".$mode."<br>\n";

        $reqarr = $request->only([
            'work_name', 
            'departments_name'
        ]);


        try {
            $systemdate = Carbon::now();
            $re_data = [];
            $make_instance_true = false;

            if($mode == 'delete') {
                $count1 = DB::table($this->table_process_date)
                ->where('product_code', $s_product_code)
                ->delete();
                $count2 = DB::table($this->table)
                ->where('product_code', $s_product_code)
                ->delete();
                $count3 = DB::table($this->table_process_log)
                ->where('product_code', $s_product_code)
                ->delete();


                $result_details = "";
                $result_date = "";
                $after_due_date = "";
                $html_cal = "";
                $select_html = 'Resultview';

                $e_message = "削除しました -> 伝票番号 : ".$params['product_code']." ／ 品名 : ".$params['product_name']."";
                $result_msg = "DEL";

    
            }
            else if($mode == 'process_status_rec') {
                $update = [
                    'status'    => 'REC',
                    'updated_at'    => now(),
                ];
                $updateresult = DB::table($this->table)
                ->where('product_code', $s_product_code)
                ->update($update);

                $updateresult = DB::table($this->table_process_log)
                ->where('product_code', $s_product_code)
                ->increment('status',1);
                //->update($update);


                $make_instance_true = true;
                $select_html = 'Default';
                $e_message = "再確定 -> 伝票番号 : ".$params['product_code']." ／ 品名 : ".$params['product_name']." ／ 納期 : ".$params['after_due_date'];


            }
            else if($mode == 'process_status_change') {
                $update = [
                    'status'    => $params['status'],
                    'updated_at'    => now(),
                ];
                $updateresult = DB::table($this->table)
                ->where('product_code', $s_product_code)
                ->update($update);

                $make_instance_true = true;
                $select_html = 'Default';
                $e_message = "初期工程確定 -> 伝票番号 : ".$params['product_code']." ／ 品名 : ".$params['product_name']." ／ 納期 : ".$params['after_due_date'];


            }
            else {

                $updateresult = DB::table($this->table)
                ->updateOrInsert(
                    [
                        'product_code' => $s_product_code, 
                        'created_user' => 'system',
                    ],
                    [

                        'serial_code' => $params['serial_code'], 
                        'rep_code' => $params['rep_code'], 
                        'after_due_date' => $params['after_due_date'], 
                        'customer' => $params['customer'], 
                        'product_name' => $params['product_name'], 
                        'end_user' => $params['end_user'], 
                        'quantity' => $params['quantity'], 
                        'receive_date' => $params['receive_date'], 
                        'platemake_date' => $params['platemake_date'], 
                        'status' => $params['status'], 
                        'comment' => $params['comment'], 
                        'updated_user' => $params['updated_user'], 
                        'updated_at' => $systemdate,

                    ]
                );
                
                $make_instance_true = true;

                $select_html = 'Default';
                $e_message = "登録 -> 伝票番号 : ".$params['product_code']." ／ 品名 : ".$params['product_name']." ／ 納期 : ".$params['after_due_date'];
                $result_msg = "OK";


            }

            //DB::commit();

            if($make_instance_true) {
                $result_details = $this->SearchProcessDetails($request);
                $result_date = $this->SearchProcessDate($request);
                $after_due_date = $result_details['after_due_date'];    // return $redata = [ の after_due_date を指す。
                $test = $result_details['result'][0]->after_due_date;    // return result[]から取得する場合　[0]のキーが必要。
                $calendar_data = new Calendar();	// インスタンス作成
                $html_cal = $calendar_data->calendar($result_details,$after_due_date,$wd_result,$result_date,$viewmode);	//開始年月～何か月分
                $processlog_data = new ProcessLog();	// インスタンス作成
                $result_logsearch = $processlog_data->LogSearch($request);
        
            }
            
            
    
            //$wd_result = $this->workdateSearch($request);

        }catch(\PDOException $pe){
            Log::error('class = '.__CLASS__.' method = '.__FUNCTION__.' '.str_replace('{0}', $this->table, Config::get('const.LOG_MSG.data_insert_error')).'$pe');
            Log::error($pe->getMessage());
            throw $pe;
        }catch(\Exception $e){
            Log::error('class = '.__CLASS__.' method = '.__FUNCTION__.' '.str_replace('{0}', $this->table, Config::get('const.LOG_MSG.data_insert_error')).'$e');
            Log::error($e->getMessage());
            throw $e;
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
            'e_message' => $e_message,
            'result' => $result_details,
            'result_date' => $result_date,
            'result_log' => $result_logsearch,
            'html_cal_main' => $html_cal,
            'select_html' => $select_html,
        ]);

    }






    public function insertData(Request $request)
    {

        $details = isset($_POST['details']) ? $_POST['details'] : [];

        $s_product_code = !empty($_POST["s_product_code"]) ? $_POST['s_product_code'] : "";
        $product_code = !empty($_POST["product_code"]) ? $_POST['product_code'] : "";
        $customer = !empty($_POST["customer"]) ? $_POST['customer'] : "";
        $product_name = !empty($_POST["product_name"]) ? $_POST['product_name'] : "";
        $end_user = !empty($_POST["end_user"]) ? $_POST['end_user'] : "";
        $quantity = !empty($_POST["quantity"]) ? $_POST['quantity'] : "";
        $comment = !empty($_POST["comment"]) ? $_POST['comment'] : "";
        $after_due_date = !empty($_POST["after_due_date"]) ? $_POST['after_due_date'] : "";
        $action_msg = "";
        $result = "";
        $result_date = "";
        $wd_result = "";
        $result_msg = "";
        $html_after_due_date = "";
        $html_cal = "";
        $viewmode = "editing";
        $e_message = "検索 ： ".$s_product_code."";


        $listcount = isset($_POST['listcount']) ? $_POST['listcount'] : "";
        $this->work_date = !empty($_POST['work_date']) ? $_POST['work_date'] : "";
        $this->product_code = !empty($_POST['product_code']) ? $_POST['product_code'] : "";
        $this->departments_name = !empty($_POST['departments_name']) ? $_POST['departments_name'] : "";
        $this->departments_code = !empty($_POST['departments_code']) ? $_POST['departments_code'] : "";
        $this->work_name = !empty($_POST['work_name']) ? $_POST['work_name'] : "";
        $this->work_code = !empty($_POST['work_code']) ? $_POST['work_code'] : "";
        $this->process_name = !empty($_POST['process_name']) ? $_POST['process_name'] : "";
        $this->status = isset($_POST['status']) ? $_POST['status'] : "";

        $reqarr = $request->only([
            'work_name', 
            'departments_name'
        ]);
        $mode = $request->mode;
        $submode = $request->submode;
        $motion = $request->motion;
        $params = $request->only([
            'product_code',
            'departments_name',
            'departments_code',
            'work_name',
            'work_code',
            'process_name',
            'status',
        ]);

        //$gethost = gethostname();
        //$gethost = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $ipaddr = $_SERVER["REMOTE_ADDR"];


        try {
            $re_data = [];
            $systemdate = Carbon::now();


/*
            $data = DB::table($this->table)
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
            $result = $data
            ->get();
            $datacount = $data->count();



            if($datacount > 0) {
                $f_work_date = $result[0]->work_date;
                $html_f_work_date = !empty($f_work_date) ? date('n月j日', strtotime($f_work_date)) : "";
                $result_msg = "OK date";
                $e_message .= " 伝票番号 = ".$result[0]->product_code." <> count = ".$datacount." <> date = ".$html_f_work_date;

            }

            
*/
            if(isset($s_product_code)) {
                $pddata = DB::table($this->table)
                ->where('product_code', $s_product_code)
                ->pluck('status');

                //pluck('name', 'id'); //key-value の形での取得
                //echo "\n<br>\nstatus = ".$pddata[0]."<br>\n";
            }

            $work_date_arr = $request->only(['work_date']);
            $str = "";
            $count = "";
            $updateresult = "";
            if(is_array($work_date_arr)) {
                foreach($work_date_arr AS $key => $wdarr) {
                    foreach($wdarr AS $wdkey => $val) {
                        $str .= "wdkey=".$wdkey.":val=".$val;
                        $loginserttrue = false;
    
                        if($mode == 'delete') {
                            $updateresult = DB::table($this->table_process_date)
                            ->where('work_date', $val)
                            ->where('product_code', $s_product_code)
                            ->where('departments_code', $params['departments_code'])
                            ->where('work_code', $params['work_code'])
                            ->delete();
                            $loginserttrue = true;
                
                        }
                        
                        elseif($mode == 'status_update') {
                            $statusresult = DB::table($this->table_process_date)
                            ->where('work_date', $val)
                            ->where('product_code', $s_product_code)
                            ->where('departments_code', $this->departments_code)
                            ->where('work_code', $this->work_code)
                            ->update(
                                [
                                    'status' => '完了',
                                    'updated_user' => $ipaddr,
                                    'updated_at' => $systemdate
                                ]
                            );

                            

                
                        }
                        
                        else {

                            $chkupdate = DB::table($this->table_process_date)
                            ->where('work_date', $val)
                            ->where('product_code', $s_product_code)
                            ->where('departments_code', $params['departments_code'])
                            ->where('work_code', $params['work_code'])
                            ->exists();
            
                            $action_msg .= "act--".$chkupdate." @ ";
                            /*
                            $updateresult = DB::table($this->table_process_date)
                            ->updateOrInsert(
                                [
                                    'work_date' => $val,
                                    'product_code' => $s_product_code, 
                                    'departments_code' => $this->departments_code,
                                    'work_code' => $this->work_code,
                                ],
                                [
                                    'departments_name' => $this->departments_name, 
                                    'work_name' => $this->work_name, 
                                    'process_name' => $this->process_name, 
                                    'created_user' => $ipaddr,
                                    'created_at' => $systemdate
                

                                ]
                            );
                            */
                            //insert(['name'=>'山田太郎','email'=>'yamada@test.com'])
                            if(empty($chkupdate))  {
                                $updateresult = DB::table($this->table_process_date)
                                ->insert(
                                    [
                                        'work_date' => $val,
                                        'product_code' => $s_product_code, 
                                        'departments_code' => $params['departments_code'],
                                        'work_code' => $params['work_code'],
                                        'departments_name' => $params['departments_name'], 
                                        'work_name' => $params['work_name'], 
                                        'process_name' => '', 
                                        'created_user' => $ipaddr,
                                        'created_at' => $systemdate
                                    ]
                                );
                                $loginserttrue = true;
                                //echo "updateresult ->> ".$updateresult."<br>\n";
                                /*
                                $insertdata[] = array(
                                    'work_date' => $val,
                                    'product_code' => $s_product_code,
                                    'motion' => $motion,
                                    'departments_name' => $params['departments_name'], 
                                    'departments_code' => $params['departments_code'],
                                    'work_name' => $params['work_name'], 
                                    'work_code' => $params['work_code'],
                                    'process_name' => '', 
                                    'created_user' => $ipaddr,
                                    'created_at' => $systemdate
                                );
                                */

                            }     


                        }

                        if($loginserttrue) {
                            $insertdata[] = array(
                                'work_date' => $val,
                                'product_code' => $s_product_code,
                                'motion' => $motion,
                                'departments_name' => $params['departments_name'], 
                                'departments_code' => $params['departments_code'],
                                'work_name' => $params['work_name'], 
                                'work_code' => $params['work_code'],
                                'process_name' => '', 
                                'created_user' => $ipaddr,
                                'created_at' => $systemdate
                            );
                        }


                    }   // end foreach

                    


                }  // end foreach
            }

            if($updateresult == 1) {
                //$updateresult = 'yes';
                if($pddata[0] == 'REC') {
                    $logresult = DB::table($this->table_process_log)->insert($insertdata); 
                }
            }
            else {
                //$updateresult = 'no';
            }



       
            //DB::commit();
            //$wd_result = $this->workdateSearch($request);


            $result_details = $this->SearchProcessDetails($request);
            $result_date = $this->SearchProcessDate($request);
            $after_due_date = $result_details['after_due_date'];    // return $redata = [ の after_due_date を指す。
            $test = $result_details['result'][0]->after_due_date;    // return result[]から取得する場合　[0]のキーが必要。
            $product_code = $result_details['result'][0]->product_code;
            $product_name = $result_details['result'][0]->product_name;
            $calendar_data = new Calendar();	// インスタンス作成
            $html_cal = $calendar_data->calendar($result_details,$after_due_date,$wd_result,$result_date,$viewmode);	//開始年月～何か月分
            $processlog_data = new ProcessLog();	// インスタンス作成
            $result_logsearch = $processlog_data->LogSearch($request);





        }catch(\PDOException $pe){
            Log::error('class = '.__CLASS__.' method = '.__FUNCTION__.' '.str_replace('{0}', $this->table, Config::get('const.LOG_MSG.data_insert_error')).'$pe');
            Log::error($pe->getMessage());
            throw $pe;
        }catch(\Exception $e){
            Log::error('class = '.__CLASS__.' method = '.__FUNCTION__.' '.str_replace('{0}', $this->table, Config::get('const.LOG_MSG.data_insert_error')).'$e');
            Log::error($e->getMessage());
            throw $e;
        }

        
        
        $e_message = "伝票番号 : ".$product_code.", 品名 : ".$product_name.", 納期 : ".$after_due_date;
        $result_msg = "OK";
        $action_msg .= $updateresult;

        /*
        $redata = array();
        $redata[] = [
            'id' => $id, 
            'listcount' => $listcount, 
            'work_date' => $this->work_date, 
            'product_code' => $this->product_code, 
            'departments_name' => $this->departments_name, 
            'departments_code' => $this->departments_code, 
            'work_name' => $this->work_name, 
            'work_code' => $this->work_code, 
            'process_name' => $this->process_name, 
            'status' => $this->status, 
            'mode' => $mode, 
            'e_message' => $e_message, 
            'result_msg' => $result_msg,
            'name' => $details['name'],
        ];
        if(!empty($redata)) {
            echo json_encode($redata, JSON_UNESCAPED_UNICODE);
        }
        //'chk_status' => $chk_status, 'acmsg' => $action_msg            
        */

        //Log::info("insertData in POST --".implode($reqarr)." + ".$str);

        //$result = $this->postSearch();
        return view('process', [
            's_product_code' => $s_product_code,
            'product_code' => $product_code,
            'customer' => $customer,
            'product_name' => $product_name,
            'end_user' => $end_user,
            'quantity' => $quantity,
            'comment' => $comment,
            'after_due_date' => $after_due_date,
            'mode' => $mode,
            'action_msg' => $action_msg,
            'e_message' => $e_message,
            'result' => $result_details,
            'result_date' => $result_date,
            'result_log' => $result_logsearch,
            'wd_result' => $wd_result,
            'html_cal_main' => $html_cal,
            'select_html' => '',
        ]);


    }


}
