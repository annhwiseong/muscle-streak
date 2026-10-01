<x-layouts.app :title="__('Dashboard')">
  <div class="p-6">

    {{-- ストリーク --}}
    <div class="p-6 mb-6 rounded-xl bg-orange-50 dark:bg-gray-700 text-center">
      @if ($streak > 0)
      <p class="text-5xl font-bold">🔥 {{ $streak }}</p>
      <p class="mt-2 text-lg">回連続で予定を達成中！</p>
      @else
      <p class="text-5xl">🔥 0</p>
      <p class="mt-2 text-lg">次の予定日からストリークを始めよう！</p>
      @endif
    </div>

    {{-- 今日の状況 --}}
    <div class="p-6 rounded-xl bg-gray-100 dark:bg-gray-700">
      @if (! $hasSchedule)
        <p>まだトレーニング予定が設定されていません。</p>
        <a href="{{ route('schedule.edit') }}" class="text-blue-500 hover:text-blue-700">予定を設定する</a>

      @elseif ($trainedToday)
        <p class="text-lg">今日（{{ $todayLabel }}）のトレーニングは完了！✅</p>

      @elseif ($todaySchedule)
        <p class="text-lg">
          今日（{{ $todayLabel }}）は
          {{ $todaySchedule->body_part ? '「' . $todaySchedule->body_part . '」の日' : 'トレーニングの日' }}
          です 💪
        </p>
        <a href="{{ route('workouts.create') }}" class="inline-block mt-3 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">記録する</a>

      @else
        <p class="text-lg">今日（{{ $todayLabel }}）は休養日です 😴</p>
      @endif
    </div>

    {{-- 仲間のランキング --}}
    <div class="mt-6 p-6 rounded-xl bg-gray-100 dark:bg-gray-700">
      <h3 class="font-semibold text-lg mb-3">👥 仲間の🔥ランキング</h3>

      @forelse ($friends as $index => $friend)
      <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-600 last:border-0">
        <div class="flex items-center gap-3">
          <span class="w-6 text-gray-500">{{ $index + 1 }}.</span>
          <a href="{{ route('users.show', $friend) }}" class="font-bold hover:underline">{{ $friend->name }}</a>
          <span>🔥 {{ $friend->streak }}</span>
        </div>
        <div class="text-sm">
          @if ($friend->trainedToday())
            <span class="text-green-600">✅ 今日は完了</span>
          @elseif ($friend->todaySchedule())
            <span class="text-orange-600">💪 今日はまだ</span>
          @else
            <span class="text-gray-500">😴 休養日</span>
          @endif
        </div>
      </div>
      @empty
      <p class="text-gray-500">まだ誰もフォローしていません。</p>
      <a href="{{ route('workouts.index') }}" class="text-blue-500 hover:text-blue-700">記録一覧から仲間を探す</a>
      @endforelse
    </div>

    {{-- 今日まだの仲間 --}}
    @if ($notYetFriends->isNotEmpty())
    <div class="mt-6 p-6 rounded-xl bg-orange-50 dark:bg-gray-700">
      <h3 class="font-semibold text-lg mb-3">📣 今日まだトレーニングしていない仲間</h3>
      @foreach ($notYetFriends as $friend)
      <p class="py-1">
        <a href="{{ route('users.show', $friend) }}" class="font-bold hover:underline">{{ $friend->name }}</a>さん
        @if ($friend->todaySchedule()->body_part)
        <span class="text-sm text-gray-500">（今日は「{{ $friend->todaySchedule()->body_part }}」の日）</span>
        @endif
      </p>
      @endforeach
    </div>
    @endif
  </div>
</x-layouts.app>