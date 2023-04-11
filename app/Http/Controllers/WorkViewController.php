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
use App\Models\WorkView;

class WorkViewController extends Controller
{

    public function daySearch(Request $request)
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
        $select_html = "dayView";
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


        $workview_data = new WorkView();	// インスタンス作成
        $result_daysearch = $workview_data->SearchData($request);
        //$result_date = $workview_data->SearchProcessDate($request);
        //echo "result_details['datacount'] = ".$result_details['datacount']."<br>\n";
        /*
        if($result_details['datacount'] === 1) {
            $after_due_date = $result_details['after_due_date'];    // return $redata = [ の after_due_date を指す。
            //$test = $result_details['result'][0]->after_due_date;    // return result[]から取得する場合　[0]のキーが必要。
            $calendar_data = new Calendar();	// インスタンス作成
            $html_cal = $calendar_data->calendar($result_details,$after_due_date,$wd_result,$result_date,$viewmode);	//開始年月～何か月分
    
        }
        */
        //$result = array_merge($result_details, $result_dete);

	        return view('workview', [
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
                'result' => $result_daysearch,
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


    public function changeStatus(Request $request)
    {


        //$select_html = !empty($_POST["select_html"]) ? $_POST['select_html'] : "";
        $select_html = "dayView";
        $action_msg = "";
        $result = "";
        $result_msg = "";
        $viewmode = "watch";
        $e_message = "ステータス変更 ： ";

        $s_product_name = !empty($_POST["s_product_name"]) ? $_POST['s_product_name'] : "";


        $workview_data = new WorkView();	// インスタンス作成
        $result_sttschange = $workview_data->STTSchange($request);


        return response()->json([
            'result' => $result_sttschange['result'],
            'product_code' => $result_sttschange['product_code'],
            'work_date' => $result_sttschange['work_date'],
            'work_code' => $result_sttschange['work_code'],
            'mode' => $result_sttschange['mode'],
            'result_msg' => $result_sttschange['result_msg'],
            'e_message' => $result_sttschange['e_message'],
            'uid' => $result_sttschange['uid'],
        ]);


        /*

        Config::get('const.RESPONCE_ITEM.messagedata') => $this->array_messagedata

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

        */
    }


    public function fix(Request $request)
    {


        //$select_html = !empty($_POST["select_html"]) ? $_POST['select_html'] : "";
        $select_html = "dayView";
        $action_msg = "";
        $result = "";
        $result_msg = "";
        $viewmode = "watch";
        $e_message = "ステータス変更 ： ";

        $s_product_name = !empty($_POST["s_product_name"]) ? $_POST['s_product_name'] : "";


        $workview_data = new WorkView();	// インスタンス作成
        $result_sttschange = $workview_data->uodateWork($request);


        return response()->json([
            'id' => $result_sttschange['id'],
            'result' => $result_sttschange['result'],
            'mode' => $result_sttschange['mode'],
            'result_msg' => $result_sttschange['result_msg'],
            'e_message' => $result_sttschange['e_message']
            
        ]);


    }





}
