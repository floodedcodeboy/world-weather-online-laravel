World Weather Online Package
=============

This package is to interact with the World Weather Online API. It was developed for Laravel 4.
It will return a temperature or the full weather object given a location.

### Installation ###
1. Install Composer: https://getcomposer.org/doc/00-intro.md
2. Edit the composer.json file in your laravel project and add: "floodedcodeboy/world-weather-online-laravel": "dev-master" in the "require" section.
3. Run "composer update"
4. Get an API key with World Weather Online: http://developer.worldweatheronline.com/
5. Edit the config.php file in vendor/floodedcodeboy/world-weather-online-laravel/src/config and put the API key in
6. Add the Service provider in your app/config/app.php under 'providers': Floodedcodeboy\WorldWeatherOnline\WorldWeatherOnlineServiceProvider

### Example Usage ###
In your app create the object, and call the appropriate functions.

```
$WorldWeatherOnline = App::make('worldweatheronline');

$location = 'London, UK';
$temperature = $WorldWeatherOnline::current_temp($location);
$condition = $WorldWeatherOnline::current_conditions($location);

$location2 = '-33.8678500, 151.2073200';
$temperature = $WorldWeatherOnline::current_temp($location2);
$condition = $WorldWeatherOnline::current_conditions($location2);
```