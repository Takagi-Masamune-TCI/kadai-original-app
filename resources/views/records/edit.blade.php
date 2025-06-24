@extends("layouts.app")

@section("contents")

    <div class="relative w-full h-full bg-white rounded-2xl shadow-2xl px-12 py-20 overflow-y-auto">
        <!-- オプション -->
        <div class="group/options absolute top-0 left-0 right-0 pr-4 py-4 flex gap-1 justify-end">
            <!-- お気に入りボタン -->
            @if(\Auth::user()->isRecordFavorited($record->id))
                <form method="POST" action="{{ route("records.unfavorite", $record->id) }}">
                    @csrf
                    @method("DELETE")
                    
                    <button type="submit" class="button text-lg rounded-full cursor-pointer text-yellow-500 px-2 py-1 transition hover:bg-yellow-100 focus:bg-yellow-100 hover:scale-105 focus:scale-105 hover:text-yellow-600">
                        ★
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route("records.favorite", $record->id) }}">
                    @csrf
                    
                    <button type="submit" class="button text-lg rounded-full cursor-pointer text-gray-300 px-2 py-1 transition hover:bg-yellow-100 focus:bg-yellow-100 hover:scale-105 focus:scale-105 hover:text-yellow-500/50">
                        ★
                    </button>
                </form>
            @endif
            <div class="pl-2 grow-0 flex items-center">
                <form method="POST" action="{{ route("records.destroy", $record->id) }}">
                    @csrf
                    @method("DELETE")
                    
                    <button type="submit" class="button cursor-pointer rounded bg-gray-100 text-gray-800 px-2 py-1 transition opacity-0 invisible group-hover/options:visible group-hover/options:opacity-100 hover:bg-red-600 hover:text-white">
                        削除
                    </button>
                </form>
            </div>
        </div>

        <!-- 内容編集 -->
        <form method="POST" action="{{ route("records.update", $record->id) }}" class="flex flex-col h-full">
            @csrf
            @method("PUT")
            <input type="hidden" name="with_props" value="1">
            
            <h3 clsass="text-xl">Record<span class="ml-4 text-2xl">#<span class="text-4xl ml-1">{{ $record->id }}</span></span></h3>
            <a href="{{ route("stores.show", $record->store->id) }}" class="mt-2 text-xs px-4 py-1 rounded shadow-md w-fit">
                <h2>Store<span class="ml-2 text-base">{{ $record->store->name }}</span></h2>
            </a>
            <div>
                <div class="mt-10 grid grid-cols-[max-content_1fr] gap-y-2 gap-x-8">
                    @foreach($record->store->propDefinitions as $prop)
                    <div class="contents">
                        <p class="px-2 py-4">{{ $prop->name }}</p>
                        <input type="hidden" name="prop_ids[]" value="{{ $prop->id }}">
                        <textarea type="text" name="prop_values[]"
                            class="rounded outline-none px-2 py-4 min-h-[1em] box-content border-none bg-gray-50 hover:bg-gray-100 field-sizing-content [field-sizing:content] resize-none">{{ 
                            // 空白が入らないようにこのようなインデントにしている
                            $record->props->where("id", $prop->id)->first()?->pivot->value
                        }}</textarea>
                    </div>
                    @endforeach
                </div>    
            </div>

            <!-- 送信ボタン -->
            <button type="submit" class="button mt-auto px-10 py-2 rounded bg-gray-800 text-white hover:bg-gray-600 focus:bg-gray-600 outline-none self-center">
                変更を保存する
            </button>
        </form>
    </div>


@endsection