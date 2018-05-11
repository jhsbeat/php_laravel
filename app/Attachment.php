<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = ['filename', 'bytes', 'mime'];

    public function article(){
        return $this->belongsTo(Article::class);
    }

    public function getBytesAttribute($value){
        return format_filesize($value);
    }

    public function getUrlAttribute(){
        return url('files/'.$this->filename);
    }
}
