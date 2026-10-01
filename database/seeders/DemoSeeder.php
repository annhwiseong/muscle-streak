<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workout;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    // メモの候補（null はメモなし）
    private array $memos = [
        '自己ベスト更新！', '今日はきつかった…', 'フォームを意識してやった',
        'パンプ最高', '次は重量を上げたい', null, null, null,
    ];

    // 応援コメントの候補
    private array $comments = [
        'ナイスバルク！', 'その調子！', 'キレてる！', '継続すごい！',
        '肩にちっちゃい重機のせてんのかい！', '見習います💪',
    ];

    public function run(): void
    {
        // [名前, メール, 予定（曜日番号 => 部位）, 続けるストリーク数, 今日やったか]
        // 曜日番号：0=日, 1=月, 2=火, 3=水, 4=木, 5=金, 6=土
        $profiles = [
            ['筋トレ太郎',     'demo@example.com',   [1 => '胸', 3 => '背中', 5 => '脚'],                              6,  false],
            ['ゴリ田マッスル', 'gorita@example.com', [1 => '胸', 2 => '背中', 4 => '脚', 5 => '肩', 6 => '腕'], 25, true],
            ['プロテイン花子', 'hanako@example.com', [2 => '脚', 4 => '背中', 6 => '全身'],                         10, true],
            ['ベンチ次郎',     'jiro@example.com',   [1 => '胸', 4 => '胸'],                                      3,  false],
            ['サボり三郎',     'saburo@example.com', [1 => '全身', 4 => '全身'],                                  0,  false],
        ];

        $users = collect();

        foreach ($profiles as [$name, $email, $schedule, $streakTarget, $trainedToday]) {
            // ユーザー（パスワードは password）
            $user = User::factory()->create([
                'name'  => $name,
                'email' => $email,
            ]);

            // 予定
            foreach ($schedule as $day => $part) {
                $user->schedules()->create([
                    'day_of_week' => $day,
                    'body_part'   => $part,
                ]);
            }

            // 過去の記録（昨日から60日前まで遡って作る）
            $this->createWorkouts($user, $schedule, $streakTarget);

            // 今日の記録
            if ($trainedToday) {
                $this->createWorkout($user, today(), $schedule[today()->dayOfWeek] ?? '全身');
            }

            $users->push($user);
        }

        $this->createFollows($users);
        $this->createLikesAndComments($users);
    }

    // 予定日に記録を作る（直近は指定のストリーク数だけ連続させ、その前に1回サボらせる）
    private function createWorkouts(User $user, array $schedule, int $streakTarget): void
    {
        $date = today()->subDay();
        $start = today()->subDays(60);
        $remaining = $streakTarget;
        $missed = false;

        while ($date->gte($start)) {
            if (array_key_exists($date->dayOfWeek, $schedule)) {
                if ($remaining > 0) {
                    // ストリーク部分：必ずやる
                    $this->createWorkout($user, $date, $schedule[$date->dayOfWeek]);
                    $remaining--;
                } elseif (! $missed) {
                    // ストリークの手前で1回サボる（ここでストリークが途切れる）
                    $missed = true;
                } elseif (random_int(1, 100) <= 70) {
                    // それより前は70%の確率でやる
                    $this->createWorkout($user, $date, $schedule[$date->dayOfWeek]);
                }
            }
            $date->subDay();
        }
    }

    private function createWorkout(User $user, $date, string $bodyPart): void
    {
        $user->workouts()->create([
            'trained_on' => $date->toDateString(),
            'body_part'  => $bodyPart,
            'memo'       => $this->memos[array_rand($this->memos)],
        ]);
    }

    // フォロー関係
    private function createFollows($users): void
    {
        $main = $users->first(); // 筋トレ太郎

        foreach ($users->skip(1) as $other) {
            // 筋トレ太郎は全員をフォロー
            $main->follows()->syncWithoutDetaching([$other->id]);

            // 半分くらいの確率でフォローし返す
            if (random_int(0, 1)) {
                $other->follows()->syncWithoutDetaching([$main->id]);
            }
        }

        // ゴリ田と花子は相互フォロー
        $users[1]->follows()->syncWithoutDetaching([$users[2]->id]);
        $users[2]->follows()->syncWithoutDetaching([$users[1]->id]);
    }

    // 直近2週間の記録に、ナイスバルク！と応援コメントを付ける
    private function createLikesAndComments($users): void
    {
        $recentWorkouts = Workout::where('trained_on', '>=', today()->subDays(14))->get();

        foreach ($recentWorkouts as $workout) {
            foreach ($users as $user) {
                if ($user->id === $workout->user_id) {
                    continue; // 自分の記録には付けない
                }

                if (random_int(1, 100) <= 40) {
                    $workout->liked()->attach($user->id);
                }

                if (random_int(1, 100) <= 15) {
                    $workout->comments()->create([
                        'user_id' => $user->id,
                        'comment' => $this->comments[array_rand($this->comments)],
                    ]);
                }
            }
        }
    }
}