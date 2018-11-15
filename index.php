<?php

require_once 'core/init.php';

$user = DB::getInstance();
$user->get('users', array('username', '=', 'alex'));

if(!$user->count()){
    echo 'No User';
}
else{
    echo "OK!";
    echo '<pre>';
    print_r($user);
    echo  '</pre>';
}
