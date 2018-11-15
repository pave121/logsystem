<?php

require_once 'core/init.php';

$user = DB::getInstance();
$user->update('users', 2, array(
    'password' => 'newpass'
));

