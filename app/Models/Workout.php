<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    /** @use HasFactory<\Database\Factories\WorkoutFactory> */
    use HasFactory;

    public const BODY_PARTS = ['胸', '背中', '脚', '肩', '腕', '腹', '全身', '有酸素'];

    protected $fillable = ['trained_on', 'body_part', 'memo'];

    protected function casts(): array
    {
        return [
            'trained_on' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
