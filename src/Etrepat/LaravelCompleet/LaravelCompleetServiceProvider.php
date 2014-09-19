<?php
namespace Etrepat\LaravelCompleet;

use Illuminate\Support\ServiceProvider;

use Etrepat\LaravelCompleet\Commands\LoadCommand;
use Etrepat\LaravelCompleet\Commands\ClearCommand;
use Etrepat\LaravelCompleet\Commands\AddCommand;
use Etrepat\LaravelCompleet\Commands\RemoveCommand;
use Etrepat\LaravelCompleet\Commands\QueryCommand;

class LaravelCompleetServiceProvider extends ServiceProvider {

  /**
   * Laravel-Compleet version
   *
   * @var string
   */
  const VERSION = '1.0.0';

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
  public function boot() {
    $this->package('etrepat/laravel-compleet');
  }

  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register() {
    $this->registerManager();

    $this->registerCommands();
  }

  /**
   * Register the Compleet manager.
   *
   * @return void
   */
  protected function registerManager() {
    $this->app->bindShared('compleet', function($app) {
      $manager = new CompleetManager($app);

      return $manager;
    });
  }

  /**
   * Register the artisan commands.
   *
   * @return void
   */
  protected function registerCommands() {
    $this->registerLoadCommand();

    $this->registerClearCommand();

    $this->registerAddCommand();

    $this->registerRemoveCommand();

    $this->registerQueryCommand();
  }

  /**
   * Register the compleet:load console command.
   *
   * @return void
   */
  protected function registerLoadCommand() {
    $this->app->bindShared('command.compleet.load', function($app) {
      return new LoadCommand($app['compleet']);
    });

    $this->commands('command.compleet.load');
  }

  /**
   * Register the compleet:clear console command.
   *
   * @return void
   */
  protected function registerClearCommand() {
    $this->app->bindShared('command.compleet.clear', function($app) {
      return new ClearCommand($app['compleet']);
    });

    $this->commands('command.compleet.clear');
  }

  /**
   * Register the compleet:add console command.
   *
   * @return void
   */
  protected function registerAddCommand() {
    $this->app->bindShared('command.compleet.add', function($app) {
      return new AddCommand($app['compleet']);
    });

    $this->commands('command.compleet.add');
  }

  /**
   * Register the compleet:remove console command.
   *
   * @return void
   */
  protected function registerRemoveCommand() {
    $this->app->bindShared('command.compleet.remove', function($app) {
      return new RemoveCommand($app['compleet']);
    });

    $this->commands('command.compleet.remove');
  }

  /**
   * Register the compleet:query console command.
   *
   * @return void
   */
  protected function registerQueryCommand() {
    $this->app->bindShared('command.compleet.query', function($app) {
      return new QueryCommand($app['compleet']);
    });

    $this->commands('command.compleet.query');
  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides() {
    return array(
      'compleet'                ,
      'command.compleet.load'   ,
      'command.compleet.clear'  ,
      'command.compleet.add'    ,
      'command.compleet.remove' ,
      'command.compleet.query'
    );
  }

}
