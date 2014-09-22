<?php
namespace Etrepat\LaravelCompleet;

use Illuminate\Foundation\Application;
use Predis\Client;
use Compleet\Loader;
use Compleet\Matcher;

class CompleetManager {

  /**
   * The application instance.
   *
   * @var \Illuminate\Foundation\Application
   */
  protected $app;

  /**
   * Redis connection instance.
   *
   * @var \Predis\Client
   */
  protected $redis;

  /**
   * Compleet loader instance.
   *
   * @var \Compleet\Loader
   */
  protected $loader;

  /**
   * Compleet matcher instance.
   *
   * @var \Compleet\Matcher
   */
  protected $matcher;

  /**
   * Create a new Compleet manager instance.
   *
   * @param  \Illuminate\Foundation\Application  $app
   * @return void
   */
  public function __construct(Application $app) {
    $this->app = $app;
  }

  /**
   * Wrapper for Compleet\Loader::load function.
   * Indexes for the specified type the supplied array of items.
   *
   * @param   string  $type
   * @param   array   $items
   * @return  array
   */
  public function load($type, array $items) {
    return $this->loader($type)->load($items);
  }

  /**
   * Wrapper for Compleet\Loader::clear function.
   * Deletes all items indexed for the specified type.
   *
   * @param   string  $type
   * @return  void
   */
  public function clear($type) {
    $this->loader($type)->clear($type);
  }

  /**
   * Wrapper for Compleet\Loader::add function.
   * Adds the supplied item data to the specified type index.
   *
   * @param   string  $type
   * @param   array   $item
   * @param   bool    $skipDuplicateChecks
   * @return  void
   */
  public function add($type, array $item, $skipDuplicateChecks = false) {
    $this->loader($type)->add($item, $skipDuplicateChecks);
  }

  /**
   * Wrapper for Compleet\Loader::add function.
   * Removes the supplied item data of the specified type index. Only 'id' item
   * is used, all other (if present) are ignored.
   *
   * @param   string  $type
   * @param   array   $item
   * @return  void
   */
  public function remove($type, $item) {
    return $this->loader($type)->remove($item);
  }

  /**
   * Wrapper for Compleet\Matcher::matches function.
   * Searches against the current type index for the supplied term.
   *
   * @param   string  $type
   * @param   string  $term
   * @param   array   $options
   * @return  array
   */
  public function matches($type, $term, $options = array()) {
    return $this->matcher($type)->matches($term, $options);
  }

  /**
   * Alias for matches.
   *
   * @param   string  $type
   * @param   string  $term
   * @param   array   $options
   * @return  array
   */
  public function find($type, $term, $options = array()) {
    return $this->matches($type, $term, $options);
  }

  /**
   * Get the redis connection name/parameters.
   *
   * @return string
   */
  public function redisConnection() {
    return $this->app['config']->get('laravel-compleet::redis');
  }

  /**
   * Get the redis connection to use.
   *
   * @return Predis\Client
   */
  public function redis() {
    if ( !is_null($this->redis) ) return $this->redis;

    $name = $this->redisConnection();

    $this->redis = is_array($name) ? new Client($name) : $this->app['redis']->connection($name);
    return $this->redis;
  }

  /**
   * Get the config-supplied min-complete setting.
   *
   * @return int
   */
  public function getMinComplete() {
    return $this->app['config']->get('laravel-compleet::min-complete') ?: 2;
  }

  /**
   * Get the config-supplied stop-words setting.
   *
   * @return int
   */
  public function getStopWords() {
    return $this->app['config']->get('laravel-compleet::stop-words') ?: [];
  }

  /**
   * Resolves a Compleet\Loader instance for the supplied type.
   *
   * @param   string  $type
   * @return  Compleet\Loader
   */
  protected function loader($type) {
    if ( !is_null($this->loader) && $this->loader->getType() == $type )
      return $this->loader;

    $this->loader = new Loader($type, $this->redis());
    $this->loader->setMinComplete($this->getMinComplete());
    $this->loader->setStopWords($this->getStopWords());

    return $this->loader;
  }

  /**
   * Resolves a Compleet\Matcher instance for the supplied type.
   *
   * @param   string  $type
   * @return  Compleet\Matcher
   */
  protected function matcher($type) {
    if ( !is_null($this->matcher) && $this->matcher->getType() == $type )
      return $this->matcher;

    $this->matcher = new Matcher($type, $this->redis());
    $this->matcher->setMinComplete($this->getMinComplete());
    $this->matcher->setStopWords($this->getStopWords());

    return $this->matcher;
  }

}
