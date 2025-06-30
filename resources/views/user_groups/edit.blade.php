@extends("layouts.app")

@section("contents")
<form method="POST" action="{{ route("user_groups.update", $userGroup->id) }}">
    @csrf
    @method("PUT")

    <div class="flex items-end justify-between">
        <div>
            <p class="text-xs font-bold text-gray-500">UserGroup</p>
            <input name="name" class="rounded text-2xl font-bold" value="{{ $userGroup->name }}" placeholder="グループ名">
        </div>
        @if(\Auth::user()->isOwnerOf($userGroup->id))
            <a href="{{ route("user_groups.edit", $userGroup->id) }}">編集</a>
        @endif
    </div>
    

    <button type="submit" class="mt-4 px-8 py-2 rounded bg-slate-700 text-white">変更を保存する</button>
</form>
@endsection