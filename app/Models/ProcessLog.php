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

class ProcessLog extends Model
{
    protected $table_process_details = 'process_details';
    protected $table_process_date = 'process_date';
    protected $table_process_log = 'process_log';
    use HasFactory;


    public function LogSearch($request)
    {


        $s_product_code = !empty($_POST["s_product_code"]) ? $_POST['s_product_code'] : "";
        $s_product_name = !empty($request->s_product_name) ? $request->s_product_name : "";
        $mode = !empty($request->mode) ? $request->mode : "";
        $result_log = "";
        $result_msg = "";
        $viewmode = "watch";
        $e_message = "ログ ： ".$s_product_code."";

        $result_log = $this->SearchProcessLog($request);
        //echo "result_details['datacount'] = ".$result_details['datacount']."<br>\n";
        $work_date = $result_log['f_work_date'];    // return $redata = [ の 項目f_work_date を指す。
        //$after_due_date = $result_details['result'][0]->after_due_date;    // return result[]から取得する場合　[0]のキーが必要。
    

        $redata = array();
        $redata = [
			'product_code' => $s_product_code,
			's_product_name' => $s_product_name,
            'result_log' => $result_log,
            'mode' => $mode, 
            'e_message' => $e_message, 
            'result_msg' => $result_msg,
        ];

        return $redata;

    }




    public function SearchProcessLog($request) {
        $s_product_code = !empty($_POST["s_product_code"]) ? $_POST['s_product_code'] : "";
        $mode = !empty($_POST["mode"]) ? $_POST['mode'] : "";
        $action_msg = "";
        $result = "";
        $result_msg = "";
        $f_work_date = "";
        $html_f_work_date = "";
        $e_message = "ログサーチ ： ".$s_product_code."";
        $departments_code_arr = Array('2','4','5','3','6','7','8','10');

        $motion = $request->motion;

        $params = $request->only([
            's_product_code',
            'mode',
            'submode',
        ]);

        try {

            if(isset($s_product_code)) {
                $data = DB::table($this->table_process_log)
                ->select(
                    'work_date',
                    'product_code',
                    'motion',
                    'departments_name',
                    'departments_code',
                    'work_name',
                    'work_code',
                    'process_name',
                    'status',
                    'created_at'
                );
                if(!empty($params['s_product_code'])) {
                    $data->where('product_code', $params['s_product_code']);
                    $data->where('status', '=', 0);
                    //$viewmode['pcode'] = 1;
                    //$default = false;
                }
                //$data->orderByRaw('FIELD(departments_code, '.implode(',', $departments_code_arr).')');
                $result = $data
                ->get();
                $datacount = $data->count();



                if($datacount > 0) {
                    $f_work_date = $result[0]->work_date;
                    $html_f_work_date = !empty($f_work_date) ? date('n 月 j 日', strtotime($f_work_date)) : "";
                    $e_message .= " 伝票番号 = ".$result[0]->product_code." <> count = ".$datacount." <> date = ".$html_f_work_date;
                    $result_msg = "OK log";

                }
                else {

                    $e_message .= " データがありません <> count = ".$datacount."";
                    $result_msg = "none";
    

                }
               
            }

        } catch (PDOException $e){
            $action_msg .= $e->getMessage().PHP_EOL."<br>\n";
        }

        $redata = array();
        $redata = [
			'product_code' => $s_product_code,
            'datacount' => $datacount, 
            'html_f_work_date' => $html_f_work_date,
			'f_work_date' => $f_work_date,
            'result' => $result,
            'mode' => $mode, 
            'e_message' => $e_message, 
            'result_msg' => $result_msg,
        ];

        return $redata;



    }




}
