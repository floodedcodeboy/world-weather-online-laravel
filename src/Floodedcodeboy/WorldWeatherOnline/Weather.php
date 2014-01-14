<?php

namespace Floodedcodeboy\WorldWeatherOnline

class Weather
{
    public static function free($latitude, $longitude, $days = 1, $format = 'json') 
    {
        // load api key
        $api_key = Config::get('weather.api_key');

        $url = "http://free.worldweatheronline.com/feed/weather.ashx?q=".$latitude.",".$longitude."&format=".$format."&num_of_days=".$days."&key=".$api_key;

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

    public static function paid($latitude, $longitude, $days = 1, $format = 'json') {
        // load api key
        $api_key = Config::get('weather.api_key');

        $url = 'http://api.worldweatheronline.com/premium/v1/weather.ashx?q=' . $latitude . '%2C' . $longitude . '&format='.$format.'&num_of_days='.$days.'&key='. $api_key;

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
        
        
        //return getForecast($temperature);

    }

    public static function get_weather($latitude, $longitude) {
        $account_type = Config::get('weather.account_type');
        if ($account_type == 'paid')
            $weather = Weather::paid($latitude, $longitude);
        else
            $weather = Weather::free($latitude, $longitude);
        return $weather;
    }

    public static function current_conditions($latitude, $longitude) 
    {
        $weather = get_weather($latitude, $longitude);
        if($weather == FALSE) {
            return false;
        } else {
            $weather = json_decode($weather);
            $current_conditions = $weather->data->current_condition[0];
            return $current_conditions;
        }
    }

    public static function current_temp($latitude, $longtitude)
    {
        $units = Config::get('weather.units');
        $weather = get_weather($latitude, $longitude);
        if($weather == FALSE) {
            return false;
        } else {
            $weather = json_decode($weather);
            $current_conditions = $weather->data->current_condition[0];
            $temperature = $weather->data->current_condition[0]['temp_C'];
            return $temperature
        }
    }
        
}