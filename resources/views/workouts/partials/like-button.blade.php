{{-- ナイスバルク！ボタン（$workout を受け取って表示する部品） --}}
<div class="flex items-center mt-2">
  @if (auth()->user()->is($workout->user))
    {{-- 自分の記録：押せない。数だけ表示 --}}
    <span class="text-sm text-gray-500">💪 ナイスバルク！ {{ $workout->liked->count() }}</span>

  @elseif ($workout->liked->contains(auth()->id()))
    {{-- 押し済み：押すと取り消し --}}
    <form action="{{ route('workouts.dislike', $workout) }}" method="POST">
      @csrf
      @method('DELETE')
      <button type="submit" class="text-sm py-1 px-3 rounded-full bg-orange-500 text-white hover:bg-orange-600">
        💪 ナイスバルク！ {{ $workout->liked->count() }}
      </button>
    </form>

  @else
    {{-- まだ押していない --}}
    <form action="{{ route('workouts.like', $workout) }}" method="POST">
      @csrf
      <button type="submit" class="text-sm py-1 px-3 rounded-full border border-orange-500 text-orange-500 hover:bg-orange-50">
        💪 ナイスバルク！ {{ $workout->liked->count() }}
      </button>
    </form>
  @endif
</div>