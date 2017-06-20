<?php namespace utilities\UserSessionHandler;

use Illuminate\Support\ServiceProvider;

class UserSessionHandlerServiceProvider extends ServiceProvider {

    public function register()
    {
		$this->app['sessionHandler'] = $this->app->share(function ($app) {
            return new UserSessionHandler;
        });
	}

}
