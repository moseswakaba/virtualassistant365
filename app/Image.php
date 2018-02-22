<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //
    protected $fillable = ['public_id','url','secure_url','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
