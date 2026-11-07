<?php

namespace App\Models;

use App\Traits\HasHeart;
use Database\Factories\QuestionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /** @use HasFactory<QuestionFactory> */
    use HasFactory, HasHeart;

protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected static function booted()
    {
        static::deleting(function ($question) {
        $question->hearts()->delete();
        $question -> comments()->get()->each(function ($comment) {
        $comment->hearts()->delete();    
        $comment->delete();
        });
        $question -> answers()->get()->each(function ($answer) {
            $answer->hearts()->delete();
            $answer -> comments()->get()->each(function ($comment) {
        $comment->hearts()->delete();    
        $comment->delete();
        });
            //$answer->delete();
        });
        });
    }
}
