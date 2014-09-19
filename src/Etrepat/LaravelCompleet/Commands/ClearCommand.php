<?php
namespace Etrepat\LaravelCompleet\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Etrepat\LaravelCompleet\CompleetManager;

class ClearCommand extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'compleet:clear';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Deletes all items for a specific TYPE.';

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

    $this->manager->clear($type);

    $this->info("${type} index cleared.");
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
