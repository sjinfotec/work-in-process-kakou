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

class GetWork extends Model
{
    use HasFactory;
    
	public function getWORK($s_department_code)
	{

		$json = file_get_contents('php://input');
		$data = json_decode($json, true);
		
		$ng_write = "";

        $s_department_code = !empty($s_department_code) ? $s_department_code : "";
        //echo "GetWork s_department_code -> ".$s_department_code."<br>\n";

		//$s_product_id = isset($data['s_product_id']) ? $data['s_product_id'] : "";
		$department = isset($data['department']) ? $data['department'] : $s_department_code;
		$mode = isset($data['mode']) ? $data['mode'] : "";
		$submode = isset($data['submode']) ? $data['submode'] : "";
		$details = isset($data['details']) ? $data['details'] : "";
        //echo "GetWork department -> ".$department."<br>\n";
       

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

        return $redata;

        /*
        //json形式で渡すなら
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




}
