<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'description', 'status', 'client_id'];

    public function client()
	{
    	return $this->belongsTo('App\Client');
	}
}
