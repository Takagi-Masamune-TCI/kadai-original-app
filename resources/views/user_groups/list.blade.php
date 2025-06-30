<!-- use $userGroups -->

@php
    $noItemMessage ??= "該当する UserGroup がありません"
@endphp

@foreach($userGroups as $group)
    <a href="{{ route("user_groups.show", $group->id) }}" class="group/userGroup mb-2 px-4 py-2 flex gap-2 items-center justify-between bg-white shadow rounded">
        <div class="flex flex-col gap1">
            <p>
                {{ $group->name }}
                @if($user->isOwnerOf($group->id))
                    <span class="ml-2 rounded-full px-2 py-0.5 text-sm border border-solid border-gray-500 text-gray-500 bg-gray-100">
                        オーナー
                    </span>
                @endif
            </p>
            <p class="text-sm text-gray-500">created by {{ $group->createdBy->name }}</p>
        </div>
        
        <div>
            <!-- 削除 -->
            <form method="POST" action="{{ route("user_groups.destroy", $group->id) }}">
                @csrf
                @method("DELETE")

                <button type="submit" class="px-2 py-1 rounded-full invisible opacity-0 transition group-hover/userGroup:visible group-hover/userGroup:opacity-100 hover:bg-red-200 focus:bg-red-200 hover:text-red-700 focus:text-red-700">
                    削除
                </button>
            </form>
        </div>
    </a>
@endforeach

@if (count($userGroups) == 0)
    <p class="text-sm text-gray-500">{{ $noItemMessage }}</p>
@endif