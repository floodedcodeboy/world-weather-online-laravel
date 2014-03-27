<?php namespace Floodedcodeboy\WorldWeatherOnline;

use Config;

class WorldWeatherOnline
{
    //URLs correct on 27/03/2014
    public static $apiURL = array(
        'free' => 'http://api.worldweatheronline.com/free/v1/weather.ashx?q=',
        'paid' => 'http://api.worldweatheronline.com/premium/weather.ashx?q='
        );

    //Expects the JSON output from World Weather Online
    public static function validateData($weatherJSON) {
        if(!$weatherJSON) {
            throw new \Exception('Weather lookup failed. Response empty.', 101);
        }

        //Try and decode it
        $weather = json_decode($weatherJSON);

        //If the JSON decode failed
        if(!$weather) {
            throw new \Exception('Weather lookup failed. Response: '.$weatherJSON, 101);
        }

        //If there is an error message then something went wrong, throw the exception back.
        if($weather && !empty($weather->data->error)) {
            $msg = '';
            foreach($weather->data->error as $error) {
                $msg .= $error->msg.' ';
            }
            throw new \Exception('Weather lookup failed. '.$msg, 102);
        }

        return true;
    }

    //Helper method to curl to the url
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

    //Call to get the raw weather from the API
    public static function get_weather($location, $days = 1, $format = 'json') {
        //These are set in the config.php file in the package
        $account_type = Config::get('world-weather-online-laravel::account_type');
        $api_key = Config::get('world-weather-online-laravel::key');

        $apiURL = WorldWeatherOnline::$apiURL[$account_type];
        $url = $apiURL. urlencode($location) . '&format=' . $format . '&num_of_days=' . $days . '&key=' . $api_key;

        $weather = WorldWeatherOnline::curl($url);

        //Try and validate the data. If it doesn't an exception will be thrown
        WorldWeatherOnline::validateData($weather);

        $weather = json_decode($weather);
        $current_conditions = $weather->data->current_condition[0];

        return $current_conditions;
    }

    //Returns the raw condition object
    public static function current_conditions($location) {
        return WorldWeatherOnline::get_weather($location);
    }

    //Returns just the temperature
    public static function current_temp($location) {
        $units = Config::get('world-weather-online-laravel::units');

        $temperature = false;

        $current_conditions = WorldWeatherOnline::get_weather($location);

        //Otherwise look for the most relevant condition and give that back
        if(!empty($current_conditions) && !empty($current_conditions->temp_C)) {
            if($units = 'metric') {
                $temperature = $current_conditions->temp_C;
            } else {
                $temperature = $current_conditions->temp_F;
            }
        }

        return $temperature;
    }

}