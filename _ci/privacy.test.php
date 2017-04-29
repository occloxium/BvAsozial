<?php
  require('constants.php');
  require_once(ABS_PATH.INC_PATH.'functions.php');

  function testFilterUsers($mysqli){
    $user = getUser("a_bartolomey", $mysqli);
    print_r($user['freunde']);
    echo "\n";
    $user['freunde'] = filterUsers($user['uid'], $user['freunde'], $mysqli);
    print_r($user['freunde']);
  }

  function testIsVisible($mysqli){
    var_dump(is_visible("e_mustermann", "a_bartolomey", $mysqli));
    var_dump(is_visible("m_mustermann", "a_bartolomey", $mysqli));
  }

  function testFetch($mysqli){
    var_dump(fetch("name", "person", "uid", "a_bartolomey", $mysqli, "LIMIT 1"));
    var_dump(fetch("name", "person", "uid", "a_bartolomey", $mysqli));
  }

  testFilterUsers($mysqli);

  testIsVisible($mysqli);

  testFetch($mysqli);

 ?>
