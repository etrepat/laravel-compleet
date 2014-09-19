<?php
namespace Etrepat\LaravelCompleet\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Etrepat\LaravelCompleet\CompleetManager;

class QueryCommand extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'compleet:query';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Queries for items from collection specified by TYPE.';

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
    $type = $this->argument('type');

    $query = $this->argument('query');

    $results = $this->manager->matches($type, $query, ['limit' => 0]);

    foreach($results as $item)
      $this->comment(json_encode($item, JSON_PRETTY_PRINT));

    $this->info('Found ' . count($results) . "for '{$query}'.");
  }

  /**
   * Get the console command arguments.
   *
   * @return array
   */
  protected function getArguments() {
    return array(
      array('type'  , InputArgument::REQUIRED, 'The TYPE of the data collection to which the supplied query will be performed.'),
      array('query' , InputArgument::REQUIRED, 'The query string to match against.')
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
