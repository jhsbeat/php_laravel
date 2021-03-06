<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $with = ['user'];

    protected $fillable = ['title', 'content'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tags(){
        return $this->belongsToMany(Tag::class);
    }

    public function attachments(){
        return $this->hasMany(Attachment::class);
    }
}
