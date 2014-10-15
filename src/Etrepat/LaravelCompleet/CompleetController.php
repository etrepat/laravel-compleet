<?php
namespace Etrepat\LaravelCompleet;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Compleet\Base as CompleetBase;
use Compleet\Support\Str as StrUtil;

class CompleetController extends Controller {

  /**
   * The compleet manager instance
   *
   * @var Etrepat\LaravelCompleet\CompleetManager
   */
  protected $manager;

  public function __construct(CompleetManager $manager) {
    $this->manager = $manager;
  }

  public function index() {
    return Response::json(['compleet' => CompleetBase::VERSION, 'status' => 'ok']);
  }

  // ?types[]=type1&types[]=type2&term=somesearch&limit=10
  public function search() {
    if ( !Input::has('types') || !Input::has('term') ) return App::abort('404');

    $limit    = Input::has('limit') ? intval(Input::get('limit')) : 5;
    $types    = Input::get('types');
    $term     = Input::get('term');

    $results  = $this->getResults($types, $term, ['limit' => $limit]);

    return Response::json(['term' => $term, 'results' => $results])->setCallback(Input::get('callback'));
  }

  protected function getResults($types, $term, $options = array()) {
    if ( !is_array($types) ) $types = array($types);
    $types  = array_map(function($t) { return StrUtil::normalize($t); }, $types);

    $results = array();

    foreach($types as $type)
      $results[$type] = $this->manager->matches($type, $term, $options);

    return $results;
  }

}
