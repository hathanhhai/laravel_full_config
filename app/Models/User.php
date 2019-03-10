<?php

namespace App\Models;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
class User extends Eloquent
{
    protected $collection = "users";
    public $timestamps = false;
    protected $fillable = [
        'username', 'password', 'setting_id', 'type', 'role','area','token'
    ];





}