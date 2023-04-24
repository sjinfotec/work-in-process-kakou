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

class ScheduleController extends Controller
{
    protected $table = 'process_details';
    protected $table_process_date = 'process_date';
    protected $table_process_log = 'process_log';

    
}
