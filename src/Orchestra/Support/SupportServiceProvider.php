<?php namespace Orchestra\Support;

use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['orchestra.messages'] = $this->app->share(function($app)
		{
			return new Messages;
		});
	}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerMessagesEvents();
	}

	/**
	 * Register the events needed for messages.
	 *
	 * @return void
	 */
	protected function registerMessagesEvents()
	{
		$app = $this->app;

		$app->after(function($request, $response) use ($app)
		{
			$app['orchestra.messages']->shutdown();
		});
	}
}