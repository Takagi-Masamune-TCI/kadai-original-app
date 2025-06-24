@php
$userStores = \Auth::user()->usingStores()->get()->all();
$favoriteStores = array_filter($userStores, fn ($store) => \Auth::user()->isStoreFavorited($store->id));
$nonfavoriteStores = array_filter($userStores, fn ($store) => \Auth::user()->isStoreFavorited($store->id) == false);
@endphp

<nav class="bg-white px-12 py-10 shadow-md shrink-0 flex flex-col justify-start">
    <h1 class="text-4xl font-bold">
        <a class="py-2" href="/">LifeStore</a>
    </h1>
    <div class="mt-20">
        <h2 class="text-lg border-b border-solid border-gray-200">
            <a href="/dashboard">
                Stores
            </a>
        </h2>

        <h3 class="mt-6 mb-4 text-xs font-bold text-gray-500">お気に入り</h3>
        
        @if(isset($favoriteStores) && count($favoriteStores) > 0)
            @foreach($favoriteStores as $store)
                <a href="{{ route("stores.show", $store->id) }}" 
                    class="my-3 py-2 px-0 rounded-lg hover:bg-gray-100 block border-l-0 border-solid border-gray-500 transition-all hover:px-2 {{ 
                        request()->routeIs("stores.show", "stores.edit") && Route::current()->parameters['store'] == $store->id 
                            ? "border-l-4 px-2 bg-white rounded-lg shadow-md font-bold text-gray-600" : "" 
                    }}">
                    {{ $store->name }}
                </a>
            @endforeach
        @else
            <p class="my-4 text-sm text-gray-500">よく使う Store をお気に入りしてみましょう</p>
        @endif
        
        <div class="group mt-10 mb-6 flex justify-between items-center">
            <h3 class="text-xs font-bold text-gray-500">作成済み</h3>
            <form method="POST" action="{{ route("stores.store") }}">
                @csrf
                
                <input type="hidden" name="name" value="new">
                <button type="submit" class="relative h-6 w-6 rounded text-gray-500 opacity-0 transition group-hover:opacity-100 group-focus-within:opacity-100 hover:bg-gray-100 focus:bg-gray-100 hover:scale-105 focus:scale-105 active:scale-95">
                    <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">+</span>
                </button>
            </form>
        </div>
        @if(isset($nonfavoriteStores) && count($nonfavoriteStores) > 0)
            @foreach($nonfavoriteStores as $store)
                <a href="{{ route("stores.show", $store->id) }}" 
                    class="my-3 py-2 px-0 rounded-lg hover:bg-gray-100 block border-l-0 border-solid border-gray-500 transition-all hover:px-2 {{ 
                        request()->routeIs("stores.show", "stores.edit") && Route::current()->parameters['store'] == $store->id 
                            ? "border-l-4 px-2 bg-white rounded-lg shadow-md font-bold text-gray-600" : "" 
                        }}">
                    {{ $store->name }}
                </a>
            @endforeach
        @else
            <p class="my-4 text-sm text-gray-500">Store を作成しましょう</p>
        @endif
        
    </div>

    <div class="justify-self-end mt-auto">
        <p class="mb-2 text-lg">{{ \Auth::user()->name }}</p>
        <form method="POST" action="{{ route("logout") }}">
            @csrf
            <button type="submit" class="text-sm rounded-full px-2 py-0.5 hover:bg-red-200 hover:text-red-600 focus:bg-red-200 focus:tesxt-red-600">
                ログアウト
            </button>
        </form>
    </div>
</nav>
