<x-layouts.app :title="__('トレーニング詳細')">
  <div class="p-6">
    <a href="{{ route('workouts.index') }}" class="text-blue-500 hover:text-blue-700">一覧に戻る</a>
    <p class="text-lg font-bold mt-2">{{ $workout->trained_on->format('Y-m-d') }}　{{ $workout->body_part }}</p>
    @if ($workout->memo)
    <p class="mt-2 whitespace-pre-line">{{ $workout->memo }}</p>
    @endif
    <p class="text-sm text-gray-500">
      記録者: <a href="{{ route('users.show', $workout->user) }}" class="hover:underline">{{ $workout->user->name }}</a>
    </p>
    <p class="text-sm text-gray-500">作成日時: {{ $workout->created_at->format('Y-m-d H:i') }}</p>
    <p class="text-sm text-gray-500">更新日時: {{ $workout->updated_at->format('Y-m-d H:i') }}</p>

    @if (auth()->id() === $workout->user_id)
    <div class="flex mt-4">
      <a href="{{ route('workouts.edit', $workout) }}" class="text-blue-500 hover:text-blue-700 mr-2">編集</a>
      <form action="{{ route('workouts.destroy', $workout) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-500 hover:text-red-700">削除</button>
      </form>
    </div>
    @endif
  </div>
</x-layouts.app>