<x-layouts.app :title="__('コメント編集')">
  <div class="p-6">
    <a href="{{ route('workouts.comments.show', [$workout, $comment]) }}" class="text-blue-500 hover:text-blue-700">詳細に戻る</a>
    <form method="POST" action="{{ route('workouts.comments.update', [$workout, $comment]) }}" class="mt-4">
      @csrf
      @method('PUT')
      <div class="mb-4">
        <label for="comment" class="block text-sm font-bold mb-2">コメントを編集</label>
        <input type="text" name="comment" id="comment" value="{{ old('comment', $comment->comment) }}"
          class="border rounded w-full py-2 px-3 dark:bg-gray-700">
        @error('comment')
        <span class="text-red-500 text-xs italic">{{ $message }}</span>
        @enderror
      </div>
      <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">更新する</button>
    </form>
  </div>
</x-layouts.app>