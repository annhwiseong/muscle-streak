<x-layouts.app :title="__('予定の設定')">
  <div class="p-6">
    <h2 class="font-semibold text-xl mb-2">{{ __('トレーニング予定の設定') }}</h2>
    <p class="text-sm text-gray-500 mb-4">チェックした曜日にトレーニングすると 🔥 が続きます。</p>

    @if (session('status'))
    <p class="mb-4 p-2 bg-green-100 text-green-800 rounded">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('schedule.update') }}">
      @csrf
      @method('PUT')

      @foreach (\App\Models\Schedule::DAYS as $num => $label)
      <div class="flex items-center gap-3 mb-3">
        <label class="flex items-center gap-2 w-16">
          <input type="checkbox" name="days[]" value="{{ $num }}"
            @checked(in_array($num, old('days', $schedules->keys()->all())))>
          <span class="font-bold">{{ $label }}</span>
        </label>
        <select name="body_parts[{{ $num }}]" class="border rounded py-1 px-2 flex-1 dark:bg-gray-700">
          <option value="">部位は未定</option>
          @foreach (\App\Models\Workout::BODY_PARTS as $part)
          <option value="{{ $part }}" @selected(old("body_parts.$num", $schedules->get($num)?->body_part) === $part)>{{ $part }}</option>
          @endforeach
        </select>
      </div>
      @endforeach

      @error('days.*')
      <p class="text-red-500 text-xs italic">{{ $message }}</p>
      @enderror
      @error('body_parts.*')
      <p class="text-red-500 text-xs italic">{{ $message }}</p>
      @enderror

      <button type="submit" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">保存する</button>
    </form>
  </div>
</x-layouts.app>