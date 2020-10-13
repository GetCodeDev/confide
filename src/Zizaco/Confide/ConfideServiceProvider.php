<?php namespace Zizaco\Confide;

use Illuminate\Support\ServiceProvider;
use Zizaco\Confide\Commands\ControllerCommand;
use Zizaco\Confide\Commands\MigrationCommand;
use Zizaco\Confide\Commands\RoutesCommand;

class ConfideServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../../migrations');

        $this->loadViewsFrom(__DIR__.'/../../views', 'confide');

        $this->publishes([
            __DIR__.'/../../config/confide.php' => config_path('confide.php'),
        ]);

        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'confide');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/confide.php', 'confide'
        );

        $this->registerRepository();

        $this->registerConfide();

        $this->registerCommands();
    }

    /**
     * Register the repository that will handle all the database interaction.
     *
     * @return void
     */
    protected function registerRepository()
    {
        $this->app->bind('confide.repository', function($app)
        {
            return new ConfideEloquentRepository;
        });
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    protected function registerConfide()
    {
        $this->app->bind('confide', function($app)
        {
            return new Confide($app->make('confide.repository'));
        });
    }

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    protected function registerCommands()
    {

        /*$this->commands([
            ControllerCommand::class,
            RoutesCommand::class,
            MigrationCommand::class
        ]);*/
    }

}
