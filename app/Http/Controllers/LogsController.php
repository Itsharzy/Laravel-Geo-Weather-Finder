<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogsController extends Controller
{
    public static function getLogsData($limit) {

        return DB::table('logs')->limit($limit)->orderByDesc('created_at')->get();

    }

    public static function saveLog($data) {
        DB::table('logs')->insert([
            'ip' => $data['ip'],
            'location' => $data['city'],
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
