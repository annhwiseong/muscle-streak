<x-layouts.app :title="__('トレーニング記録')">
  <div class="p-6">
    <h2 class="font-semibold text-xl mb-4">{{ __('トレーニング記録') }}</h2>
    @forelse ($workouts as $workout)
    <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
      <p class="font-bold">{{ $workout->trained_on->format('Y-m-d') }}　{{ $workout->body_part }}</p>
      @if ($workout->memo)
      <p class="mt-1">{{ Str::limit($workout->memo, 50) }}</p>
      @endif
      <p class="text-sm text-gray-500">
        記録者: <a href="{{ route('users.show', $workout->user) }}" class="hover:underline">{{ $workout->user->name }}</a>
      </p>
      <a href="{{ route('workouts.show', $workout) }}" class="text-blue-500 hover:text-blue-700">詳細を見る</a>
      @include('workouts.partials.like-button', ['workout' => $workout])
    </div>
    @empty
    <p>まだ記録がありません。</p>
    @endforelse
  </div>
</x-layouts.app>