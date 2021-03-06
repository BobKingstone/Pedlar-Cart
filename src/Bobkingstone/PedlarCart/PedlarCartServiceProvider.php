<?php namespace Bobkingstone\PedlarCart;

use Bobkingstone\PedlarCart\Storage\SessionStorage;
use Illuminate\Support\ServiceProvider;

class PedlarCartServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->singleton('cart', function()
        {
           return new Cart(new SessionStorage());
        });

        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Cart', 'Bobkingstone\PedlarCart\Facades\Cart');
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('cart');
	}


    /**
     *
     */
    public function boot()
    {
        $this->package('bobkingstone/pedlarcart');
    }

}
