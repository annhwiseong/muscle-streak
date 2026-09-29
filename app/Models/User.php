<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class User extends Authenticatable // implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function workouts()
    {
        return $this->hasMany(Workout::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // 予定した曜日に連続でトレーニングできた回数
    public function scheduleStreak(): int
    {
        // 予定がある曜日の番号（例：[1, 3, 5]）
        $scheduledDays = $this->schedules->pluck('day_of_week')->all();
        if (empty($scheduledDays)) {
            return 0;
        }

        // トレーニングした日付をまとめて取得し、キーにする（例：['2026-09-28' => 0, ...]）
        $trainedDates = $this->workouts()
            ->pluck('trained_on')
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->unique()
            ->flip();

        if ($trainedDates->isEmpty()) {
            return 0;
        }

        // 一番古い記録の日（ここより前は数えない）
        $firstDate = Carbon::parse($trainedDates->keys()->min());

        $streak = 0;
        $date = today();

        // 今日が予定日でまだ記録していなければ、途切れ扱いにせず昨日から数える
        if (in_array($date->dayOfWeek, $scheduledDays) && ! $trainedDates->has($date->toDateString())) {
            $date->subDay();
        }

        // 1日ずつ遡って、予定日だけチェックする
        while ($date->gte($firstDate)) {
            if (in_array($date->dayOfWeek, $scheduledDays)) {
                if ($trainedDates->has($date->toDateString())) {
                    $streak++;
                } else {
                    break; // 予定日に記録なし → ここで終了
                }
            }
            $date->subDay();
        }

        return $streak;
    }
}
