<?php

namespace App\Models;

use Database\Factories\AnswerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    /** @use HasFactory<AnswerFactory> */
    use HasFactory;

    protected $fillable = ['content', 'user_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function hearts()
    {
        return $this->morphMany(Heart::class, 'heartable');
    }
    public function isHeartedBy()
    {
        return $this->hearts()->where('user_id', 20)->exists();
    }

     public function heart()
    {
        $this->hearts()->create ([
            'user_id' => 20, 
        ]);
    }
    public function unheart()
    {
        $this->hearts()->where('user_id', 20)->delete();
    }
}
