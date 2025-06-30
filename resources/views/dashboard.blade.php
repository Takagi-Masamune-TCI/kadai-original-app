@php
$userStores = \Auth::user()->usingStores()->get()->all();
$favoriteStores = array_filter($userStores, fn ($store) => \Auth::user()->isStoreFavorited($store->id));
@endphp

@extends("layouts.app")

@section("contents")
    <!-- お気に入りの Store -->
    <div>
        <h2 class="text-lg font-bold text-gray-600">お気に入り</h2>
        <div class="flex gap-8 overflow-auto mx-[-1rem] px-4 pt-6 pb-10">
            @foreach ($favoriteStores as $store)
                @php
                    $storeIsCreatedByMe = $store->createdBy->id == \Auth::user()->id;
                    $storeCanBeAccessedFromGroup = \Auth::user()->accessPermittedStores()->where("stores.id", $store->id)->exists();
                @endphp
                    <a href="{{ route("stores.show", $store->id) }}" 
                    class="group/store relative flex flex-col h-32 min-w-48 px-6 py-4 items-start justify-end rounded-lg bg-gradient-to-br text-white shadow-lg transition hover:shadow-xl focus:shadow-xl hover:scale-105 focus:scale-105 active:scale-95 {{ 
                        $storeIsCreatedByMe
                            // 制作者が自身
                            ? $store->is_public
                                // 公開中
                                ? "from-green-600 to-green-400 shadow-green-400/50 hover:shadow-green-400/50 focus:shadow-green-400/50"
                                // 非公開
                                : ($storeCanBeAccessedFromGroup
                                    ? "from-cyan-600 to-cyan-400 shadow-cyan-400/50 hover:shadow-cyan-400/50 focus:shadow-cyan-400/50"
                                    : "from-yellow-600 to-yellow-400 shadow-yellow-400/50 hover:shadow-yellow-400/50 focus:shadow-yellow-400/50"
                                )
                            // 制作者が他人
                            : ($storeCanBeAccessedFromGroup
                                // 所属しているグループのストア
                                ? "from-cyan-600 to-cyan-400 shadow-cyan-400/50 hover:shadow-cyan-400/50 focus:shadow-cyan-400/50"
                                // 一般のストア
                                : "from-orange-600 to-orange-400 shadow-orange-400/50 hover:shadow-orange-400/50 focus:shadow-orange-400/50")
                    }}">
                    <p class="text-xl">
                        {{ $store->name }}
                        <span class="block text-xs text-white/60 {{ $store->createdBy->id == \Auth::user()->id ? "invisible" : "visible" }}">
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
                
                <input type="hidden" name="name" value="新しい Store">
                <button type="submit" class="px-4 py-2 rounded bg-gray-600 text-white transition hover:bg-gray-500 hover:shadow focus:bg-gray-500 focus:shadow hover:scale-105 focus:scale-105 active:scale-95">
                    新規作成
                </button>
            </form>
        </div>
        <div class="mt-4 flex flex-col gap-4">
            @include("stores.list", [ "stores" => \Auth::user()->stores ])
        </div>
    </div>

    <!-- 所属グループの Store -->
    
    @if (count(\Auth::user()->groups->toArray()) > 0)
        <div class="mt-12">
            <div class="flex justify-between">
                <h2 class="text-lg font-bold text-gray-600">所属グループの Store</h2>
            </div>
            <div class="mt-4 py-4 flex gap-8 flex-nowrap w-full overflow-x-auto">
                @foreach (\Auth::user()->groups as $group)
                    <div class="px-8 py-6 bg-white/80 shadow rounded-lg">
                        <h3 class="text-lg font-bold text-gray-800">
                            <a href="{{ route("user_groups.show", $group->id) }}" class="px-2 py-1">{{ $group->name }}</a>
                        </h3>
                        <div class="mt-4 flex flex-col gap-2">
                            @include("stores.list", [ "stores" => $group->accessPermittedStores, "hasDeleteButton" => false ])
                        </div>
                    </div>
                @endforeach
                
            </div>
        </div>
    @endif
            
    <!-- 他ユーザーの Store -->
    <div class="mt-12">
        <h2 class="text-lg font-bold text-gray-600">他のユーザーの Store</h2>
        <div class="mt-4 flex gap-4 flex-wrap">
            @include("stores.list", [ "stores" => $foreignStores ])
        </div>
    </div>

@endsection