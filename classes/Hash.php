<?php

class Hash{
    
    public static function make($string){
        return password_hash("rasmuslerdorf", PASSWORD_DEFAULT);
    }
    
    public static function salt($length){
        return crypt($length);
    }
    
    public static function unique(){
        return self::make(uniqid());
    }
}