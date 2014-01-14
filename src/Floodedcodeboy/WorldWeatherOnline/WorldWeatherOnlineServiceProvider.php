<?php namespace Floodedcodeboy\WorldWeatherOnline;

use Illuminate\Support\ServiceProvider;

class WorldWeatherOnlineServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('floodedcodeboy/world-weather-online-laravel');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
		$this->app['weather'] = $this->app->share(function($app){
			return new Weather;
		});

		// Allow alias to work without amendinghttp://46.137.140.1/ app config file.
		$this->app->booting(function() {
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('Weather', 'Floodedcodeboy\WorldWeatherOnline\Facades\WorldWeatherOnline');
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}