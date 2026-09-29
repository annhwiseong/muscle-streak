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

  </div>
</x-layouts.app>