<?php

class User{
    private $_db,
            $_data,
            $_sessionName,
            $_cookieName,
            $_isLoggedIn;
    
    public function __construct($user = null){
        $this->_db = DB::getInstance();
        
        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');
        
        if(!$user){
            if(Session::exists($this->_sessionName)){
                $user = Session::get($this->_sessionName);
                
                if($this->find($user)){
                    $this->_isLoggedIn = true;
                }
                else{
                    //process logout
                }
            }
        } else{
            $this->find($user);
        }
    }
    
    public function create($fields){
        if(!$this->_db->insert('users', $fields)){
            throw new exception('There was a problem creating an account.');
        }
    }
    
    public function find($user = null){
        if($user){
            $field = (is_numeric($user)) ? 'id' : 'username';
            $data =$this->_db;
            $data->get('users', array($field, '=', $user));
            
            if($data->count()){
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }
    
    public function login($username = null, $password = null, $remember){
        
        $user = $this->find($username);
        
        if($user){
            if(password_verify($password, $this->data()->password)){
               Session::put($this->_sessionName, $this->data()->id);
                
                if($remember){
                    $hash = Hash::unique();
                    $hashCheck = $this->_db;
                    $hashCheck->get('users_session', array('user_id', '=', $this->data()->id));
                    
                    if(!$hashCheck->count()){
                        $this->_db->insert('users_session', array(
                            'user_id' => $this->data()->id,
                            'hash' => $hash
                        ));
                    } else {
                        $hash = $hashCheck->first()->hash;
                    }
                    
                    Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                    
                }
                return true;
            }
        }
        
        return false;
    }
    
    public function logout(){
        Session::delete($this->_sessionName);
    }
    
    public function data(){
        return $this->_data;
    }
    
    public function isLoggedIn(){
        return $this->_isLoggedIn;
    }
}