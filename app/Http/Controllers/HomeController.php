<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\WeatherController;

class HomeController extends Controller
{

    public function index() {

        $clientIp = getClientIp();
        $data['ip'] = ($clientIp == '' ? \request()->ip() : $clientIp);
        
        $data['logs'] = LogsController::getLogsData(10);

        return view('welcome', $data);
    }

    public function getWeather(Request $request) {
        $rules = [ 'ip' => 'required|ip'];

        $validatedData = $request->validate($rules);
   
        $data = $this->getAddress($request);
        $data['ip'] = $request['ip'];

        LogsController::saveLog($data);

        $data['logs'] = LogsController::getLogsData(10);

        return view('welcome', $data);
    }

    private function getAddress(Request $request) {
        $data = [];
        //get ip information
        $url = "http://ip-api.com/json/".$request['ip'];
        $json = json_decode(file_get_contents($url), true);
        //check to ensure correct response recieved if not try other API
        if (array_key_exists('lat', $json) && array_key_exists('lon', $json)) {
            
            $data['city']       = $json['city'];
            $data['latitude']   = $json['lat'];
            $data['longitude']  = $json['lon'];

        } else {
            $url = "https://freeipapi.com/api/json/".$request['ip'];
            $json = json_decode(file_get_contents($url), true);
            if (array_key_exists('latitude', $json) && array_key_exists('longitude', $json)) {

                $data['latitude']   = $json['latitude'];
                $data['longitude']  = $json['longitude'];
                $data['city']       = $json['cityName'];

            }
        }

        $data['weather'] = WeatherController::getWeather($data['city']);

        if (empty($data['weather'])) {
            if (array_key_exists('latitude', $data)) {
                $url = "https://api.open-meteo.com/v1/forecast?latitude=".$data['latitude']."&longitude=".$data['longitude']."&hourly=temperature_2m,relativehumidity_2m,visibility,windspeed_10m&daily=weathercode,temperature_2m_max,temperature_2m_min,windspeed_10m_max&timezone=Europe%2FLondon";
                $data['weathertemp'] = json_decode(file_get_contents($url), true);
            }
            $data['weather'] = [];
    
            if (array_key_exists('weathertemp', $data)) {
                for ($i=0; $i < 5; $i++) { 
    
                    $data['weather'][] = [
                        'date'      => date("M jS, Y", strtotime($data['weathertemp']['daily']['time'][$i])),
                        'max'       => round($data['weathertemp']['daily']['temperature_2m_max'][$i],0),
                        'min'       => round($data['weathertemp']['daily']['temperature_2m_min'][$i],0),
                        'type'      => WeatherController::getWeatherCodeName($data['weathertemp']['daily']['weathercode'][$i]),
                        'graphic'   => WeatherController::getWeatherCodeIcon($data['weathertemp']['daily']['weathercode'][$i]),
                    ];
    
                }
    
                WeatherController::saveWeather($data['weather'], $data['city']);
            } else {
                $data['error'] = 'failed to recieve weather information.';
            }
        } else {
            $data['weather'] = json_decode($data['weather']->data, true);
        }

        
        return $data;
    }
}
