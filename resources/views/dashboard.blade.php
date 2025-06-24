@php
$user = \Auth::user();
$userStores = $user->usingStores()->get()->all();
$favoriteStores = array_filter($userStores, fn ($store) => $user->isStoreFavorited($store->id));
$nonfavoriteStores = array_filter($userStores, fn ($store) => $user->isStoreFavorited($store->id) == false);
@endphp

@extends("layouts.app")

@section("contents")
    <!-- お気に入りの Store -->
    <div>
        <h2 class="text-lg font-bold text-gray-600">お気に入り</h2>
        <div class="flex gap-8 overflow-auto mx-[-1rem] px-4 pt-6 pb-10">
            @foreach ($favoriteStores as $store)
                <a href="{{ route("stores.show", $store->id) }}" 
                    class="group/store relative flex flex-col h-32 min-w-48 px-6 py-4 items-start justify-end rounded-lg bg-gradient-to-br text-white shadow-lg transition hover:shadow-xl focus:shadow-xl hover:scale-105 focus:scale-105 active:scale-95 {{ 
                        $store->createdBy->id == $user->id
                            // 制作者が自身
                            ? $store->is_public
                                // 公開中
                                ? "from-green-600 to-green-400 shadow-green-400/50 hover:shadow-green-400/50 focus:shadow-green-400/50"
                                // 非公開
                                : "from-yellow-600 to-yellow-400 shadow-yellow-400/50 hover:shadow-yellow-400/50 focus:shadow-yellow-400/50"
                            // 制作者が他人
                            // : "from-cyan-600 to-cyan-400 shadow-cyan-400/50 hover:shadow-cyan-400/50 focus:shadow-cyan-400/50"
                            : "from-orange-600 to-orange-400 shadow-orange-400/50 hover:shadow-orange-400/50 focus:shadow-orange-400/50"
                    }}">
                    <p class="text-xl">
                        {{ $store->name }}
                        <span class="block text-xs text-white/60 {{ $store->createdBy->id == $user->id ? "invisible" : "visible" }}">
                            created by {{ $store->createdBy->name }}
                        </span>
                    </p>
                    <div class="absolute top-2 right-2 opacity-0 transition group-hover/store:opacity-100 group-focus-within/store:opacity-100">
                        <form method="POST" action="{{ route("stores.unfavorite", $store->id) }}">
                            @csrf
                            @method("DELETE")
                            
                            <button type="submit" class="button cursor-pointer text-yellow-500 h-6 w-6 align-middle text-center transition hover:scale-125 hover:text-yellow-600 bg-white rounded">
                                ★
                            </button>
                        </form>
                    </div>
                </a>
            @endforeach
            @if(isset($favoriteStores) == false || count($favoriteStores) == 0)
                <p class="text-gray-500">お気に入りがまだありません</p>
            @endif
        </div>
    </div>

    <!-- 作成した Store -->
    <div class="mt-8">
        <div class="flex justify-between">
            <h2 class="text-lg font-bold text-gray-600">作成した store</h2>
            <form method="POST" action="{{ route("stores.store") }}">
                @csrf
                
                <input type="hidden" name="name" value="new">
                <button type="submit" class="px-4 py-2 rounded bg-gray-600 text-white transition hover:bg-gray-500 hover:shadow focus:bg-gray-500 focus:shadow hover:scale-105 focus:scale-105 active:scale-95">
                    新規作成
                </button>
            </form>
        </div>
        <div class="mt-4">
            @foreach ($nonfavoriteStores as $store)
                <div class="group/store flex mb-4 transition">
                    <div class="flex items-center bg-white shadow rounded-lg grow transition hover:scale-y-105 focus:scale-y-105 hover:scale-x-[1.005] focus:scale-x-[1.005] ">
                        <a href="{{ route("stores.show", $store->id) }}" class="grow px-4 py-2 flex gap-3 items-center">
                            <!-- 公開/非公開のインディケーター -->
                            @if($store->is_public)
                                <div class="w-2 h-2 rounded-full bg-green-500 ring ring-green-200"></div>
                            @else
                                <div class="w-2 h-2 rounded-full bg-gray-400 ring ring-gray-200"></div>
                            @endif
                            <!-- 名前 -->
                            {{ $store->name }}
                        </a>
                        <div class="px-4 py-2 flex gap-2">
                            <!-- お気に入りボタン -->
                            @if($user->isStoreFavorited($store->id))
                                <form method="POST" action="{{ route("stores.unfavorite", $store->id) }}">
                                    @csrf
                                    @method("DELETE")
                                    
                                    <button type="submit" class="button cursor-pointer text-yellow-500 px-2 py-1 transition hover:scale-125 hover:text-yellow-600">
                                        ★
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route("stores.favorite", $store->id) }}">
                                    @csrf
                                    
                                    <button type="submit" class="button cursor-pointer text-gray-300 px-2 py-1 transition hover:scale-125 hover:text-yellow-500/50">
                                        ★
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="pl-2 grow-0 flex items-center">
                        <form method="POST" action="{{ route("stores.destroy", $store->id) }}">
                            @csrf
                            @method("DELETE")
                            
                            <button type="submit" class="button cursor-pointer rounded bg-gray-200 text-gray-800 px-2 py-1 transition opacity-0 invisible group-hover/store:visible group-hover/store:opacity-100 hover:bg-red-600 hover:text-white">
                                削除
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
            
            @if(isset($nonfavoriteStores) == false || count($nonfavoriteStores) == 0)
                <p class="text-gray-500">表示する Store がありません</p>
            @endif
        </div>
    </div>

    <!-- 他ユーザーの Store -->
    <div class="mt-10">
        <h2 class="text-lg font-bold text-gray-600">他のユーザーの Store</h2>
        <div class="mt-4 flex gap-4 flex-wrap">
            @foreach ($foreignStores as $store)
                <div class="group/store flex items-center bg-white shadow rounded-lg mb-2 transition hover:scale-105 focus:scale-105 active:scale-95">
                    <a href="{{ route("stores.show", $store->id) }}" 
                        class="grow px-4 py-2">
                        <p>{{ $store->name }}</p>
                        <p class="text-xs text-gray-500">created by {{ $store->createdBy->name }}</p>
                        
                    </a>
                    <div class="pl-4 pr-1 py-2">
                        @if($user->isStoreFavorited($store->id))
                            <form method="POST" action="{{ route("stores.unfavorite", $store->id) }}">
                                @csrf
                                @method("DELETE")
                                
                                <button type="submit" class="button cursor-pointer text-yellow-600 px-2 py-1 transition hover:scale-125 hover:text-yellow-800">
                                    ★
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route("stores.favorite", $store->id) }}">
                                @csrf
                                
                                <button type="submit" class="button cursor-pointer text-gray-300 px-2 py-1 transition hover:scale-125 hover:text-yellow-500/50">
                                    ★
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection