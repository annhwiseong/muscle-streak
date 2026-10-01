<x-layouts.app :title="__('応援コメント')">
  <div class="p-6">
    <a href="{{ route('workouts.show', $workout) }}" class="text-blue-500 hover:text-blue-700">記録に戻る</a>
    <p class="text-sm text-gray-500 mt-2">{{ $workout->trained_on->format('Y-m-d') }}　{{ $workout->body_part }}（{{ $workout->user->name }}）</p>
    <form method="POST" action="{{ route('workouts.comments.store', $workout) }}" class="mt-4">
      @csrf
      <div class="mb-4">
        <label for="comment" class="block text-sm font-bold mb-2">応援コメント</label>
        <input type="text" name="comment" id="comment" value="{{ old('comment') }}" placeholder="例：ナイスバルク！その調子！"
          class="border rounded w-full py-2 px-3 dark:bg-gray-700">
        @error('comment')
        <span class="text-red-500 text-xs italic">{{ $message }}</span>
        @enderror
      </div>
      <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">コメントする</button>
    </form>
  </div>
</x-layouts.app>