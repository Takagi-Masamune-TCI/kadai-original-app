@extends("layouts.app")

@section("contents")
    <div>
        <p class="text-xs font-bold text-gray-500">User #{{ $user->id }}</p>
        <h1 class="text-4xl font-bold">{{ $user->name }}</h1>
    </div>

    <div>
        <h2 class="text-xl mt-12 mb-4">参加中のグループ</h2>
        <div class="flex flex-col gap-2">
            @include("user_groups.list", [ 
                "userGroups" => $user->groups, 
                "noItemMessage" => "参加中のグループはありません" 
            ])
        </div>
    </div>

    <div>
        <h2 class="text-xl mt-8 mb-2">作成したグループ</h2>
        <div>
            @php
                $createdGroups = $user->groups()->where("created_by", $user->id)->get();
            @endphp
            
            <div class="flex flex-col gap-2">
                @include("user_groups.list", [ 
                    "userGroups" => $user->createdGroups, 
                    "noItemMessage" => "作成したグループはありません" 
                ])
            </div>

            <form method="POST" action="{{ route("user_groups.store") }}" class="mt-4">
                @csrf
                <input type="hidden" name="name" value="new">
                <button type="submit" class="rounded-full px-4 py-1 text-white bg-slate-600 shadow shadow-slate-500/50">
                    ＋作成する
                </button>
            </form>
        </div>
    </div>
@endsection