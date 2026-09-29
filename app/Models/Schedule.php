<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /** @use HasFactory<\Database\Factories\ScheduleFactory> */
    use HasFactory;

    public const DAYS = [
        1 => '月',
        2 => '火',
        3 => '水',
        4 => '木',
        5 => '金',
        6 => '土',
        0 => '日',
    ];

    protected $fillable = ['day_of_week', 'body_part'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
