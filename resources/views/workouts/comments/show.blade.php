<x-layouts.app :title="__('コメント詳細')">
  <div class="p-6">
    <a href="{{ route('workouts.show', $workout) }}" class="text-blue-500 hover:text-blue-700">記録に戻る</a>
    <p class="text-sm text-gray-500 mt-2">{{ $workout->trained_on->format('Y-m-d') }}　{{ $workout->body_part }}（{{ $workout->user->name }}）</p>
    <p class="text-lg mt-2">{{ $comment->comment }}</p>
    <p class="text-sm text-gray-500">{{ $comment->user->name }}</p>
    <p class="text-sm text-gray-500">コメント作成日時: {{ $comment->created_at->format('Y-m-d H:i') }}</p>
    <p class="text-sm text-gray-500">コメント更新日時: {{ $comment->updated_at->format('Y-m-d H:i') }}</p>

    @if (auth()->user()->is($comment->user))
    <div class="flex mt-4">
      <a href="{{ route('workouts.comments.edit', [$workout, $comment]) }}" class="text-blue-500 hover:text-blue-700 mr-2">編集</a>
      <form action="{{ route('workouts.comments.destroy', [$workout, $comment]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-500 hover:text-red-700">削除</button>
      </form>
    </div>
    @endif
  </div>
</x-layouts.app>