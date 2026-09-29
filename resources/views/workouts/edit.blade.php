<x-layouts.app :title="__('トレーニング編集')">
  <div class="p-6">
    <a href="{{ route('workouts.show', $workout) }}" class="text-blue-500 hover:text-blue-700">詳細に戻る</a>
    <form method="POST" action="{{ route('workouts.update', $workout) }}" class="mt-4">
      @csrf
      @method('PUT')
      <div class="mb-4">
        <label for="trained_on" class="block text-sm font-bold mb-2">日付</label>
        <input type="date" name="trained_on" id="trained_on"
          value="{{ old('trained_on', $workout->trained_on->format('Y-m-d')) }}"
          max="{{ today()->format('Y-m-d') }}"
          class="border rounded w-full py-2 px-3 dark:bg-gray-700">
        @error('trained_on')
        <span class="text-red-500 text-xs italic">{{ $message }}</span>
        @enderror
      </div>

      <div class="mb-4">
        <label for="body_part" class="block text-sm font-bold mb-2">部位</label>
        <select name="body_part" id="body_part" class="border rounded w-full py-2 px-3 dark:bg-gray-700">
          @foreach (\App\Models\Workout::BODY_PARTS as $part)
          <option value="{{ $part }}" @selected(old('body_part', $workout->body_part) === $part)>{{ $part }}</option>
          @endforeach
        </select>
        @error('body_part')
        <span class="text-red-500 text-xs italic">{{ $message }}</span>
        @enderror
      </div>

      <div class="mb-4">
        <label for="memo" class="block text-sm font-bold mb-2">メモ（任意）</label>
        <textarea name="memo" id="memo" rows="4" class="border rounded w-full py-2 px-3 dark:bg-gray-700">{{ old('memo', $workout->memo) }}</textarea>
        @error('memo')
        <span class="text-red-500 text-xs italic">{{ $message }}</span>
        @enderror
      </div>

      <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">更新する</button>
    </form>
  </div>
</x-layouts.app>