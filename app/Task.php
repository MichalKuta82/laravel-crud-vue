<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    protected $fillable = [
    	'name', 'body', 'user_id',
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
