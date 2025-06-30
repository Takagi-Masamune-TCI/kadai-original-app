@extends("layouts.app")

@section("contents")
    <div class="relative w-full h-full bg-white rounded-2xl shadow-2xl px-12 pt-20 pb-10 flex flex-col">
        <!-- オプション -->
        <div class="group/options absolute top-0 left-0 right-0 pr-4 py-4 flex gap-1 justify-end">
            <!-- お気に入りボタン -->
            @if(\Auth::user()->isStoreFavorited($store->id))
                <form method="POST" action="{{ route("stores.unfavorite", $store->id) }}">
                    @csrf
                    @method("DELETE")
                    
                    <button type="submit" class="button text-lg rounded-full cursor-pointer text-yellow-500 px-2 py-1 transition hover:bg-yellow-100 focus:bg-yellow-100 hover:scale-105 focus:scale-105 hover:text-yellow-600">
                        ★
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route("stores.favorite", $store->id) }}">
                    @csrf
                    
                    <button type="submit" class="button text-lg rounded-full cursor-pointer text-gray-300 px-2 py-1 transition hover:bg-yellow-100 focus:bg-yellow-100 hover:scale-105 focus:scale-105 hover:text-yellow-500/50">
                        ★
                    </button>
                </form>
            @endif
            <div class="pl-2 grow-0 flex items-center">
                <form method="POST" action="{{ route("stores.destroy", $store->id) }}">
                    @csrf
                    @method("DELETE")
                    
                    <button type="submit" class="button cursor-pointer rounded bg-gray-100 text-gray-800 px-2 py-1 transition opacity-0 invisible group-hover/options:visible group-hover/options:opacity-100 hover:bg-red-600 hover:text-white">
                        削除
                    </button>
                </form>
            </div>
        </div>

        <!-- Store 名 -->
        <div class="flex justify-between items-end">
            <div>
                <p class="text-xs text-gray-500 font-bold">Store</p>
                <h2 class="font-bold text-4xl">{{ $store->name }}</h2>
                @if ($store->is_public)
                    <div class="mt-2 pl-4 pr-6 py-0.5 w-fit flex items-center gap-2 rounded-full bg-green-100">
                        <div class="w-2 h-2 rounded-full bg-green-500 ring ring-green-200"></div>
                        <p class="text-green-600">公開中</p>
                    </div>
                @else 
                    <div class="mt-2 pl-4 pr-6 py-0.5 w-fit flex items-center gap-2 rounded-full bg-gray-200">
                        <div class="w-2 h-2 rounded-full bg-gray-400 ring ring-gray-200"></div>
                        <p class="text-gray-800">非公開</p>
                    </div>
                @endif
                <div class="mt-4">
                    <h3 class="text-sm text-gray-500">
                        アクセス可能な UserGroup：
                        @if (count($store->userGroups) == 0)
                            <span class="text-gray-500">なし</span>
                        @endif
                    </h3>
                    <div class="mt-1 flex gap-1">
                        @foreach ($store->userGroups as $userGroup)
                            <a href="{{ route("user_groups.show", $userGroup->id) }}" class="pl-2 pr-4 py-0.5 w-fit flex items-center gap-2 rounded-full text-gray-800 transition hover:bg-cyan-100 hover:text-cyan-800">
                                <div class="w-2 h-2 rounded-full bg-cyan-500 ring ring-cyan-200"></div>
                                <p class="text-inherit">{{ $userGroup->name }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <a href="{{ route("stores.edit", $store->id) }}">編集</a>
        </div>

        <!-- テーブル -->
        <div class="mt-4 overflow-x-auto grow overflow-y-auto">
            <table class="border-separate border-spacing-0">
                <thead class="sticky top-0 left-0 right-0 pt-2 bg-white/50 backdrop-blur">
                    <tr class="">
                        <td class="min-w-20 px-4 py-2 text-gray-500 border-solid border-b-2 border-gray-300" colspan="2">id</td>
                        @foreach($store->propDefinitions as $propDefinition)
                            <td class="min-w-32 pl-4 pr-8 py-2 text-nowrap text-gray-500 last:w-full border-solid border-b-2 border-gray-300">{{ $propDefinition->name }}</td>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $store_records = $store->records()->orderBy("index")->get()
                    @endphp
                    @foreach($store_records as $i => $record)
                        <tr class="group transition hover:bg-gray-100 focus-within:bg-gray-100" data-index="{{ $record->index }}">
                            <!-- ID部分 -->
                            <th class="py-2 font-normal border-solid border-b border-gray-300">
                                <a href="{{ route("records.edit", $record->id) }}" class="px-4 py-2 rounded-lg bg-white transition group-hover:shadow-md group-focus-within:shadow-md">
                                    <span class="text-sm text-gray-600 mr-1">#</span>{{ $record->id }}
                                </a>
                            </th>
                            <td class="py-2 pr-2 border-solid border-b border-gray-300">
                                <div class="h-full flex gap-2 items-center">
                                    <!-- お気に入り -->
                                    @if(\Auth::user()->isRecordFavorited($record->id))
                                        <form method="POST" action="{{ route("records.unfavorite", $record->id) }}">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" class="w-8 h-8 text-center align-middle rounded-full text-yellow-400 transition hover:bg-yellow-200/50 focus-within:bg-yellow-200/50 text-nowrap">
                                                ★
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route("records.favorite", $record->id) }}">
                                            @csrf
                                            <button type="submit" class="w-8 h-8 text-center align-middle rounded-full text-gray-300 transition hover:bg-yellow-200/50 focus-within:bg-yellow-200/50 hover:text-yellow-400/50 focus-within:text-yellow-400/50 text-nowrap">
                                                ★
                                            </button>
                                        </form>
                                    @endif
                                    <!-- 隠しボタン -->
                                    <div class="relative flex gap-1 items-center opacity-0 invisible transition group-hover:opacity-100 group-hover:visible group-focus-within:opacity-100">
                                        <div class="absolute flex flex-col gap-8">
                                            <!-- 上へ移動 -->
                                            @if($i > 0)
                                                <form method="POST" action="{{ route("records.insert", $record->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="into_id" value="{{ $store_records[$i - 1]->id }}">
                                                    <button type="submit" class="px-4 py-1 rounded text-gray-400 bg-white shadow-lg transition hover:bg-gray-100 focus-within:bg-gray-200 hover:scale-105 focus:scale-105 active:scale-95">
                                                        ▲
                                                    </button>
                                                </form>
                                            @else
                                                <div class="px-4 py-1 rounded bg-gray-200/50 text-gray-300 pointer-events-none">▲</div>
                                            @endif
                                            <!-- 下へ移動 -->
                                            @if($i < count($store_records) - 1)
                                                <form method="POST" action="{{ route("records.insert", $record->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="into_id" value="{{ $store_records[$i + 1]->id }}">
                                                    <button type="submit" class="px-4 py-1 rounded text-gray-400 bg-white shadow-lg transition hover:bg-gray-100 focus-within:bg-gray-200 hover:scale-105 focus:scale-105 active:scale-95">
                                                        ▼
                                                    </button>
                                                </form>
                                            @else
                                                <div class="px-4 py-1 rounded bg-gray-200/50 text-gray-300 pointer-events-none">▼</div>
                                            @endif
                                        </div>
                                        <div class="ml-12">
                                            <!-- 削除 -->
                                            <form method="POST" action="{{ route("records.destroy", $record->id) }}">
                                                @csrf
                                                @method("DELETE")
                                                <button type="submit" class="px-1 py-1 rounded text-gray-500 transition hover:bg-gray-200 focus-within:bg-gray-200 text-nowrap">
                                                    削除
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <!-- 値部分 -->
                            @foreach($store->propDefinitions as $propDefinition)
                                <td class="px-4 pl-4 pr-8 text-nowrap  border-solid border-b border-gray-300">
                                    {!! nl2br(e($record->props->where("id", $propDefinition->id)->first()?->pivot->value)) !!}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Record を追加 ボタン -->
        <form method="POST" action="{{ route("records.store") }}" class="grow-0">
            @csrf
            
            <input type="hidden" name="store_id" value="{{ $store->id }}">
            <button class="block w-full py-2 px-4 text-left border-t-2 border-b border-gray-200 text-gray-400 transition hover:bg-gray-100 focus:bg-gray-100 hover:text-gray-500 focus:text-gray-500 hover:border-gray-300 focus:border-gray-300">
                + Record を追加
            </button>
        </form>
    </div>
@endsection