<?php namespace Floodedcodeboy\WorldWeatherOnline;

use Config;

class WorldWeatherOnline
{

    public static function hello() {
        return 'howdy';
    }

    public static function curl($url) {
        //Setup curl request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $weather = curl_exec($ch);

        //Catch errors
        if(curl_errno($ch)) {
            //$errors = curl_error($ch);
            curl_close($ch);
            return false;
        }
        else {
            curl_close($ch);
            return $weather;
        }
    }

    public static function free($latitude, $longitude, $days = 1, $format = 'json') 
    {
        // load api key
        $api_key = Config::get('world-weather-online-laravel::key');
        $url = "http://free.worldweatheronline.com/feed/weather.ashx?q=".$latitude."%2C".$longitude."&format=".$format."&num_of_days=".$days."&key=".$api_key;

        return WorldWeatherOnline::curl($url);
    }

    public static function paid($latitude, $longitude, $days = 1, $format = 'json') {
        // load api key
        $api_key = Config::get('world-weather-online-laravel::key');
        $url = 'http://api.worldweatheronline.com/premium/v1/weather.ashx?q=' . $latitude . '%2C' . $longitude . '&format='.$format.'&num_of_days='.$days.'&key='. $api_key;

        return WorldWeatherOnline::curl($url);//return getForecast($temperature);
    }

    public static function get_weather($latitude, $longitude) {
        $account_type = Config::get('world-weather-online-laravel::account_type');
        if ($account_type == 'paid')
            $weather = WorldWeatherOnline::paid($latitude, $longitude);
        else
            $weather = WorldWeatherOnline::free($latitude, $longitude);
        return $weather;
    }

    public static function current_conditions($latitude, $longitude) 
    {
        $weather = WorldWeatherOnline::get_weather($latitude, $longitude);
        if($weather == FALSE) {
            return false;
        } else {
            $weather = json_decode($weather);
            $current_conditions = $weather->data->current_condition[0];
            return $current_conditions;
        }
    }

    public static function current_temp($latitude, $longitude)
    {
        $units = Config::get('world-weather-online-laravel::units');
        $weather = WorldWeatherOnline::get_weather($latitude, $longitude);
        if($weather == FALSE) {
            return false;
        } else {
            $weather = json_decode($weather);
            $current_conditions = $weather->data->current_condition[0];
            $temperature = $weather->data->current_condition[0]['temp_C'];
            return $temperature;
        }
    }
        
}