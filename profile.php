<?php

$password = 'password';
$hash = password_hash($password, PASSWORD_DEFAULT);

if(password_verify($password, $hash))
    echo "OK!";