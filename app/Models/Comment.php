<?php

namespace App\Models;

use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** @use HasFactory<CommentFactory> */
    use HasFactory;

    protected $fillable = ['content', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
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
        $this->hearts()->create([
            'user_id' => 20,
        ]);
    }

    public function unheart()
    {
        $this->hearts()->where('user_id', 20)->delete();
    }
}
