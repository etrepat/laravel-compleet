<?php
namespace Etrepat\LaravelCompleet\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Etrepat\LaravelCompleet\CompleetManager;

class AddCommand extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'compleet:add';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Adds items to collection specified by TYPE read from stdin in the JSON lines format.';

  /**
   * Compleet manager instance.
   *
   * @var Etrepat\LaravelCompleet\CompleetManager
   */
  protected $manager = null;

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct(CompleetManager $manager) {
    parent::__construct();

    $this->manager = $manager;
  }

  /**
   * Execute the console command.
   *
   * @return void
   */
  public function fire() {
    $data = explode(PHP_EOL, file_get_contents("php://stdin"));

    $items = array_filter(array_map(function($json) {
      return json_decode($json, true); }, $data), function($item) {
        return !is_null($item); });

    $type = $this->argument('type');

    $inserts = 0;

    if ( count($items) > 0 ) {
      foreach($items as $item) {
        $this->manager->add($type, $item);
        $inserts = $inserts + 1;
      }
    }

    $this->info("{$inserts} new items loaded into the {$type} index.");
  }

  /**
   * Get the console command arguments.
   *
   * @return array
   */
  protected function getArguments() {
    return array(
      array('type', InputArgument::REQUIRED, 'The TYPE of the data collection to which the supplied item will be added.')
    );
  }

  /**
   * Get the console command options.
   *
   * @return array
   */
  protected function getOptions() {
    return array();
  }

}
