<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['first_name', 'last_name', 'email', 'password'];

    public function projects()
    {
        return $this->hasMany('App\Project');
    }
}
