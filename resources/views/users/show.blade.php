<x-layouts.app :title="$user->name">
  <div class="p-6">

    {{-- 名前とフォローボタン --}}
    <div class="flex items-center justify-between mb-4">
      <h2 class="font-semibold text-2xl">{{ $user->name }}</h2>

      @if ($user->id !== auth()->id())
        @if ($isFollowing)
        <form action="{{ route('follow.destroy', $user) }}" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="border border-gray-400 py-1 px-4 rounded-full hover:bg-gray-100">フォロー中</button>
        </form>
        @else
        <form action="{{ route('follow.store', $user) }}" method="POST">
          @csrf
          <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-4 rounded-full">フォローする</button>
        </form>
        @endif
      @endif
    </div>

    {{-- ストリークとフォロー数 --}}
    <div class="flex gap-6 mb-4">
      <p class="text-xl font-bold">🔥 {{ $streak }}</p>
      <p>フォロー <span class="font-bold">{{ $user->follows->count() }}</span></p>
      <p>フォロワー <span class="font-bold">{{ $user->followers->count() }}</span></p>
    </div>

    {{-- 予定の曜日 --}}
    <div class="mb-6">
      <p class="text-sm text-gray-500 mb-1">トレーニング予定</p>
      <div class="flex flex-wrap gap-2">
        @foreach (\App\Models\Schedule::DAYS as $num => $label)
          @php $schedule = $user->schedules->firstWhere('day_of_week', $num); @endphp
          @if ($schedule)
          <span class="px-3 py-1 rounded-full bg-orange-100 dark:bg-gray-600 text-sm">
            {{ $label }}{{ $schedule->body_part ? '：' . $schedule->body_part : '' }}
          </span>
          @endif
        @endforeach
        @if ($user->schedules->isEmpty())
        <span class="text-sm text-gray-500">未設定</span>
        @endif
      </div>
    </div>

    {{-- 記録一覧 --}}
    <h3 class="font-semibold text-lg mb-2">トレーニング記録</h3>
    @forelse ($workouts as $workout)
    <div class="mb-3 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
      <p class="font-bold">{{ $workout->trained_on->format('Y-m-d') }}　{{ $workout->body_part }}</p>
      @if ($workout->memo)
      <p class="mt-1">{{ Str::limit($workout->memo, 50) }}</p>
      @endif
      <a href="{{ route('workouts.show', $workout) }}" class="text-blue-500 hover:text-blue-700">詳細を見る</a>
      <a href="{{ route('workouts.show', $workout) }}" class="text-blue-500 hover:text-blue-700">詳細を見る</a>
      @include('workouts.partials.like-button', ['workout' => $workout])
    </div>
    @empty
    <p class="text-gray-500">まだ記録がありません。</p>
    @endforelse

    <div class="mt-4">
      {{ $workouts->links() }}
    </div>

  </div>
</x-layouts.app>