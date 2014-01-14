<?php namespace Floodedcodeboy\WorldWeatherOnline\Facades;

use Illuminate\Support\Facades\Facade;

class WorldWeatherOnline extends Facade {
	protected static function getFacadeAccessor() {
		return 'worldweatheronline';
	}
}