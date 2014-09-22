<?php

return array(

  /*
  |--------------------------------------------------------------------------
  | Redis connection name
  |--------------------------------------------------------------------------
  |
  | The redis connection name, which Compleet will use to connect to an existing
  | Redis server instance, from those defined in config/database.php.
  |
  */

  'redis' => 'default',

  /*
  |--------------------------------------------------------------------------
  | Url
  |--------------------------------------------------------------------------
  |
  | Url path that will be used to serve the autocomplete requests.
  |
  */

  'url' => 'autocomplete',

  /*
  |--------------------------------------------------------------------------
  | Min. complete
  |--------------------------------------------------------------------------
  |
  | Minimun char length which a substring needs to have in order to be indexed
  | and/or matched.
  |
  */

  'min-complete' => 2,

  /*
  |--------------------------------------------------------------------------
  | Stop words
  |--------------------------------------------------------------------------
  |
  | Default set of stop words. Words included here will be ignored both when
  | indexing and matching.
  |
  */

  'stop-words' => ['vs', 'at', 'the']

);
