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
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class FileUpload extends Model
{
    protected $table_process_details = 'process_details';
    protected $table_process_date = 'process_date';
    use HasFactory;


    // ファイルアップロード
    function files_upload( $files, $dir = './tmp/', $action_msg = false, $result_message = false) {
		$file = Array();
		$file[4] = "";
		$file[5] = "";
		$file[6] = "";

		if(isset($files['upload_file']['tmp_name'])) {
			foreach ($files['upload_file']['tmp_name'] as $no => $tmp_name) {
				//ファイルをテンポラリから保存場所へ移動
				$action_msg .= "tmp_name = ".$tmp_name."## no = ".$no."\n";
				$fileurl = $dir.$files['upload_file']['name'][$no];
				if (is_uploaded_file($files["upload_file"]["tmp_name"][$no])) {
					if (move_uploaded_file($tmp_name, $fileurl)) {
						$result_message .= $files['upload_file']['name'][$no]."をアップロードしました<br>\n";
						$file[$no] = $files['upload_file']['name'][$no];
						//$file[$no] = mb_encode_mimeheader( $files['upload_file']['name'][$no], "ISO-2022-JP", "UTF-8" );
					} else {
						//エラー処理
						//$file[$no] = "";
						$action_msg .= "ファイルなし<br>\n";
					}
				}
				else {
					$file[$no] = "";
					$action_msg .= "ファイルが選択されていません。<br>\n";
				}

			}
		}

		$select_mode = 'confirm';

        $redata = array();
        $redata = [
            'files_arr' => $file,
            'e_message' => $action_msg, 
            'result_msg' => $result_message,
        ];

        return $redata;

    }


}
