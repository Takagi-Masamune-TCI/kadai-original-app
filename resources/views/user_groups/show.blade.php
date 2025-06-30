@extends("layouts.app")

@section("contents")
    <div class="flex items-end justify-between">
        <div>
            <p class="text-xs font-bold text-gray-500">UserGroup</p>
            <h1 class="mt-1 text-4xl font-bold">{{ $userGroup->name }}</h1>
        </div>
        @if(\Auth::user()->isOwnerOf($userGroup->id))
            <a href="{{ route("user_groups.edit", $userGroup->id) }}">編集</a>
        @endif
    </div>
    

    <div class="mt-12">
        <h2 class="text-xl mb-4">参加中のメンバー</h2>
        <div class="flex flex-col gap-2">
            @foreach($userGroup->users as $user)
                <div class="px-4 py-2 flex items-center justify-between bg-white rounded-md shadow-md ">
                    <div class="flex gap-2 items-center">
                        <p class="{{ $user->id == \Auth::id() ? "font-bold text-gray-500" : "" }}">{{ $user->name }}</p>
                        @if($userGroup->createdBy->id == $user->id)
                            <p class="ml-2 rounded-full px-2 py-0.5 text-sm border border-solid border-gray-500 text-gray-700 bg-gray-100">
                                作成者
                            </p>
                        @endif
                    </div>

                    <div class="flex gap-2 items-center">
                        @if (\Auth::user()->isOwnerOf($userGroup->id) && $user->id != $userGroup->createdBy->id)
                            <!-- オーナー権限を変更する -->
                            <form method="POST" action="{{ route("user_groups.members", $userGroup->id) }}">
                                @csrf
                                @method("PUT")
                                <input type="hidden" name="userId" value="{{ $user->id }}">
                                <select name="isOwner" onchange="this.closest('form').submit()" class="rounded-full pl-2 pr-8 py-1 text-sm text-center border border-solid border-gray-500 text-gray-700 bg-gray-100">
                                    <option value="on" {{ $user->isOwnerOf($userGroup->id) ? "selected" : "" }}>
                                        オーナー
                                    </option>
                                    <option value="off" {{ $user->isOwnerOf($userGroup->id) ? "" : "selected" }}>
                                        一般メンバー
                                    </option>
                                </select>
                            </form>

                            <!-- メンバーから削除する -->
                            <form method="POST" action="{{ route("user_groups.members", $userGroup->id) }}">
                                @csrf
                                @method("DELETE")
                                <input type="hidden" name="userId" value="{{ $user->id }}">
                                <button type="submit">削除</button>
                            </form>
                        @else
                            @if($user->isOwnerOf($userGroup->id))
                                <p class="ml-2 rounded-full px-4 py-1 text-sm border border-solid border-gray-500 text-gray-700 bg-gray-100">
                                    オーナー
                                </p>
                            @endif
                        @endif
                    </div>
                        
                </div>
            @endforeach
        </div>
        
        <!-- メンバー追加 -->
        @if (\Auth::user()->isOwnerOf($userGroup->id))
            <form method="POST" action="{{ route("user_groups.members", $userGroup->id) }}" class="mt-4">
                @csrf

                <h3 class="text-lg">メールアドレスを指定して追加</h3>
                <div class="flex gap-2 items-center">
                    <input type="email" name="email" placeholder="メールアドレス" class="rounded-md w-96 border-none">
                    <label>
                        <input type="checkbox" name="isOwner" class="rounded-md py-0.5 px-1">
                        オーナー
                    </label>
                    <button type="submit" class="rounded-full px-4 py-1 text-white bg-slate-600 shadow shadow-slate-500/50">
                        追加
                    </button>
                </div>
            </form>
        @endif
    </div>

    <!-- Store の表示 -->
    <div class="mt-10">
        <h2 class="text-lg font-bold text-gray-600">アクセス可能な Store</h2>
        <div class="mt-4 flex gap-4 flex-wrap">
            @include("stores.list", [ "stores" => $userGroup->accessPermittedStores, "hasDeleteButton" => false ])
        </div>
    </div>
@endsection