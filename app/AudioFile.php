<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AudioFile extends Model
{
    protected $table = 'audio_files';

    protected $fillble = [
        'user_id',
        'file'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
