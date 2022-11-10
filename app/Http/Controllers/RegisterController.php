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

class RegisterController extends Controller
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
    

        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
         $authusers = Auth::user();
         $data = $request->session()->all();
         $value = $request->session()->get('code');
         $mode = !empty($_POST["mode"]) ? $_POST['mode'] : "";
         $str1 = !empty($_GET["str1"]) ? $_GET['str1'] : "";
         $str2 = !empty($_POST["str2"]) ? $_POST['str2'] : "";
         $str3 = "デフォルトファンクション function index()";
         $action_msg = "";
          

        return view('register',
            compact(
                'authusers','data','value',
                'mode','str1','str2','str3','action_msg',

            ));

    }


    public function getRequestFunc(Request $request)
    {


        
        $mode = !empty($_POST["mode"]) ? $_POST['mode'] : "";
        $str1 = !empty($_GET["str1"]) ? $_GET['str1'] : "";
        $str2 = !empty($_POST["str2"]) ? $_POST['str2'] : "";
        $str3 = "変数の値";
        $action_msg = "";

	        return view('register', [
            	'mode' => $mode,
            	'str1' => $str1,
            	'str2' => $str2,
            	'str3' => $str3,
                'action_msg' => $action_msg,

	        ]);



    }



        

    

 

    public function postRequestFunc(Request $request)
    {

	
        $mode = !empty($_POST["mode"]) ? $_POST['mode'] : "";
        $wpdate = !empty($_POST["wpdate"]) ? $_POST['wpdate'] : "";
        $s_product_id = !empty($_POST["s_product_id"]) ? $_POST['s_product_id'] : "";
        $str1 = !empty($_POST["str1"]) ? $_POST['str1'] : "";
        $str2 = !empty($_POST["str2"]) ? $_POST['str2'] : "";
        $str3 = "変数の値";
        $action_msg = "";
        $result = "";



        try {


            if(isset($s_product_id)) {
                $data = DB::connection('nippou')->table('product_details')
                ->select(
                    'product_id',
                    'serial_id',
                    'rep_id',
                    'customer',
                    'product_name',
                    'end_user',
                    'quantity',
                    'after_due_date',
                    'comment'
                );
                $data->where('product_id', $s_product_id);
                //$data->where('before_due_date', '2022/10/24');
                $result = $data
                ->get();

            }

            //return $result;







        } catch (PDOException $e){
            //print('Error:'.$e->getMessage());
            $action_msg .= $e->getMessage().PHP_EOL."<br>\n";
            //die();
        }
        

        





	        return view('register', [
            	'mode' => $mode,
            	'wpdate' => $wpdate,
            	'str1' => $str1,
            	'str2' => $str2,
            	'str3' => $str3,
                'action_msg' => $action_msg,
                'result' => $result,
	        ]);



    }






    public function searchData(Request $request)
    {

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        $ng_write = "";


        $s_product_id = isset($data['s_product_id']) ? $data['s_product_id'] : "";
        $mode = isset($data['mode']) ? $data['mode'] : "";
        $submode = isset($data['submode']) ? $data['submode'] : "";
        $motion = isset($data['motion']) ? $data['motion'] : "";

        $action_msg = "";
        $result = "";
        $result_msg = "";
        $html_after_due_date = "";
        $e_message = "検索 ： ".$s_product_id."";

        try {

            if(isset($s_product_id)) {
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


                $data = DB::connection('nippou')->table('product_details')
                ->select(
                    'product_id',
                    'serial_id',
                    'rep_id',
                    'customer',
                    'product_name',
                    'end_user',
                    'quantity',
                    'after_due_date',
                    'comment'
                );
                $data->where('product_id', $s_product_id);
                //$data->where('before_due_date', '2022/10/24');
                $result = $data
                ->get();
                $count = $data->count();


                if($check_count > 0) {

                    if($count > 0){
                        $r_after_due_date = $result[0]->after_due_date;
                        $html_after_due_date = !empty($r_after_due_date) ? date('n月j日', strtotime($r_after_due_date)) : "";
                        $e_message .= " 既に登録されています <> count = ".$count." <> date = ".$html_after_due_date;
                        $result_msg = "already";
                    }
                    else {


                    $result = $check_data
                    ->get();
                    $r_after_due_date = $result[0]->after_due_date;
                    $html_after_due_date = !empty($r_after_due_date) ? date('n月j日', strtotime($r_after_due_date)) : "";
                    $e_message .= " 日報に登録なし <> count = ".$count." <> date = ".$html_after_due_date;
                    $result_msg = "NOnippou";


                    }
    

        

                }
                else {

                    if($count > 0){
                        $r_after_due_date = $result[0]->after_due_date;
                        $html_after_due_date = !empty($r_after_due_date) ? date('n月j日', strtotime($r_after_due_date)) : "";
                        $e_message .= " <> count = ".$count." <> date = ".$html_after_due_date;
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
            's_product_id' => $s_product_id, 
            'result' => $result, 
            'html_after_due_date' => $html_after_due_date, 
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



    public function newData(Request $request)
    {

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        $listcount = isset($data['listcount']) ? $data['listcount'] : "";
        $this->product_code = !empty($data['product_code']) ? $data['product_code'] : "";
        $this->serial_code = !empty($data['serial_code']) ? $data['serial_code'] : "";
        $this->rep_code = !empty($data['rep_code']) ? $data['rep_code'] : "";
        $this->customer = !empty($data['customer']) ? $data['customer'] : "";
        $this->product_name = !empty($data['product_name']) ? $data['product_name'] : "";
        $this->end_user = !empty($data['end_user']) ? $data['end_user'] : "";
        $this->quantity = !empty($data['quantity']) ? $data['quantity'] : "";
        //$this->status = isset($data['status']) ? $data['status'] : "";
        $this->after_due_date = !empty($data['after_due_date']) ? $data['after_due_date'] : "";
        //$this->comment = $data['comment'] == 'null' ? "" : $data['comment'];
        $this->comment = !empty($data['comment']) ? $data['comment'] : "";

        $mode = isset($data['mode']) ? $data['mode'] : "";
        $upkind = isset($data['upkind']) ? $data['upkind'] : "";
        $details = isset($data['details']) ? $data['details'] : [];
        foreach($details AS $key => $val) {
            $name = $val;

        }






        try {
            $re_data = [];
            $systemdate = Carbon::now();
            if($upkind == 3) {
            //$this->company_id = DB::table($this->table)->max('company_id') + 1;
            //$this->product_id = DB::table($this->table)->max('product_id') + 1;
            //$this->order_info = 'a';
            //$this->created_user = 'system';
            }
            if($upkind == 2) {
                //$this->product_id = DB::table($this->table)->max('product_id') + 1;
                //$this->order_info = 'a';
                //$this->created_user = 'system';
            }
            //$this->now_inventory = isset($this->now_inventory) ? $this->now_inventory : "";
            //$this->nbox = isset($this->nbox) ? $this->nbox : "";
    
            $id = DB::table($this->table)->insertGetId(
                [
                    'product_code' => $this->product_code,
                    'serial_code' => $this->serial_code,
                    'rep_code' => $this->rep_code,
                    'after_due_date' => $this->after_due_date,
                    'customer' => $this->customer,
                    'product_name' => $this->product_name,
                    'end_user' => $this->end_user,
                    'quantity' => $this->quantity,
                    'status' => '0',
                    'comment' => $this->comment,
                    'created_user' => 'system',
                    'created_at' => $systemdate,
                    'updated_at' => NULL
    
                ]
            );

            /*
            if($upkind == 1){
                
                DB::table($this->table)
                ->where('id', $this->id)
                ->update([
                    'status' => '',
                ]);

            }
            */
            $re_data['id'] = $id;
            DB::commit();
            //return $re_data;

        }catch(\PDOException $pe){
            Log::error('class = '.__CLASS__.' method = '.__FUNCTION__.' '.str_replace('{0}', $this->table, Config::get('const.LOG_MSG.data_insert_error')).'$pe');
            Log::error($pe->getMessage());
            throw $pe;
        }catch(\Exception $e){
            Log::error('class = '.__CLASS__.' method = '.__FUNCTION__.' '.str_replace('{0}', $this->table, Config::get('const.LOG_MSG.data_insert_error')).'$e');
            Log::error($e->getMessage());
            throw $e;
        }


        







        
        $e_message = "登録 ： ".$this->product_code." ＆ ".$this->product_name."　納期 ： ".$this->after_due_date;
        $result_msg = "OK";


        $redata = array();
        $redata[] = [
            'id' => $id, 
            'listcount' => $listcount, 
            'product_code' => $this->product_code, 
            'after_due_date' => $this->after_due_date, 
            'customer' => $this->customer, 
            'product_name' => $this->product_name, 
            'end_user' => $this->end_user, 
            'quantity' => $this->quantity, 
            'comment' => $this->comment, 
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

    }




}
