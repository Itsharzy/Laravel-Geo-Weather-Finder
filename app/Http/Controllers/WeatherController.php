<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WeatherController extends Controller
{
    public static function getWeatherCodeName($code) {

        switch ($code) {
            case '0':
                return 'clear';
                break;
            case '1':
            case '2':
            case '3':
                return 'Cloudy';
                break;
            case '45':
            case '48':
                return 'Foggy';
                break;
            case '51':
            case '53':
            case '55':
            case '56':
            case '57':
                return 'Drizzle';
                break;
            case '61':
            case '63':
            case '65':
                return 'Rain';
                break;
            case '66':
            case '67':
                return 'Hail';
                break;
            case '71':
            case '73':
            case '75':
            case '77':
                return 'Snow';
                break;
            case '80':
            case '81':
            case '82':
                return 'Showers';
                break;
            case '85':
            case '86':
                return 'Snow Showers';
                break;    
            case '95':
            case '96':
            case '99':
                return 'Thunder';
                break;         
            default:
                return '';
                break;
        }

    }
    public static function getWeatherCodeIcon($code) {

        switch ($code) {
            case '0':
                return 'typ-weather-sunny';
                break;
            case '1':
            case '2':
            case '3':
                return 'typ-weather-cloudy';
                break;
            case '45':
            case '48':
                return 'wi-fog';
                break;
            case '51':
            case '53':
            case '55':
            case '56':
            case '57':
                return 'ik-rain';
                break;
            case '61':
            case '63':
            case '65':
                return 'wi-rain';
                break;
            case '66':
            case '67':
                return 'wi-rain-mix';
                break;
            case '71':
            case '73':
            case '75':
            case '77':
                return 'bi-snow';
                break;
            case '80':
            case '81':
            case '82':
                return 'wi-rain';
                break;
            case '85':
            case '86':
                return 'wi-snow';
                break;    
            case '95':
            case '96':
            case '99':
                return 'gmdi-thunderstorm-o';
                break;         
            default:
                return '';
                break;
        }

    }

    public static function saveWeather($data, $city) {
        DB::table('weather')->insert([
            'data' => json_encode($data),
            'location' => $city,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    public static function getWeather($city) {
        $date = date('Y-m-d H:i:s', strtotime('-1 hours'));
        return DB::table('weather')->where('location', '=', $city)->where('created_at', '>=', $date)->orderByDesc('created_at')->first();

    }
}
