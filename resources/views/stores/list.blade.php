<!-- use $stores -->
<!-- use $hasDeleteButton = true -->

@php
$hasDeleteButton ??= true;
$noItemMessage ??= "表示する Store がありません";
@endphp

@foreach ($stores as $store)
    <div class="group/store flex transition">
        <div class="flex items-center bg-white shadow rounded-lg grow transition hover:scale-y-105 focus:scale-y-105 hover:scale-x-[1.005] focus:scale-x-[1.005] ">
            <a href="{{ route("stores.show", $store->id) }}" class="grow px-4 py-2 flex gap-3 items-center">
                <!-- 公開/非公開のインディケーター -->
                @if($store->is_public)
                    <div class="w-2 h-2 rounded-full bg-green-500 ring ring-green-200"></div>
                @else
                    <div class="w-2 h-2 rounded-full bg-gray-400 ring ring-gray-200"></div>
                @endif

                <div>
                    <!-- 名前 -->
                    {{ $store->name }}
    
                    <!-- 制作者 -->
                    @if ($store->createdBy->id != \Auth::id())
                        <p class="text-xs text-gray-500">created by {{ $store->createdBy->name }}</p>
                    @endif
                </div>
            </a>
            <div class="px-4 py-2 flex gap-2">
                <!-- お気に入りボタン -->
                @if(\Auth::user()->isStoreFavorited($store->id))
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
        @if ($hasDeleteButton && $store->createdBy->id == \Auth::id() )
            <div class="pl-2 grow-0 flex items-center">
                <form method="POST" action="{{ route("stores.destroy", $store->id) }}">
                    @csrf
                    @method("DELETE")
                    
                    <button type="submit" class="button cursor-pointer rounded bg-gray-200 text-gray-800 px-2 py-1 transition opacity-0 invisible group-hover/store:visible group-hover/store:opacity-100 hover:bg-red-600 hover:text-white">
                        削除
                    </button>
                </form>
            </div>
        @endif
        
        
    </div>
@endforeach

@if(isset($stores) == false || count($stores) == 0)
    <p class="text-gray-500">{{ $noItemMessage }}</p>
@endif