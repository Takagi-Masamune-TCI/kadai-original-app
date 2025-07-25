@extends("layouts.app")

@section("contents")
    <form id="store-form" method="POST" action="{{ route("stores.update", $store->id) }}">
        @csrf
        @method('PUT')

        <div class="w-full h-full bg-white rounded-2xl shadow-2xl px-12 py-20 flex flex-col gap-4 items-start">
            <!-- 名前入力欄 -->
            <div>
                <p class="text-xs text-gray-500 font-bold">Store</p>
                <input name="name" class="font-bold text-4xl rounded" value="{{ $store->name }}">
            </div>
            <div>
                <label class="group/isPublicButton cursor-pointer">
                    <input type="checkbox" name="is_public" class="peer hidden" {{ $store->is_public ? "checked" : "" }} value="on">
                    <div class="mt-2 pl-4 pr-6 py-2 w-fit flex items-center gap-2 rounded-full bg-gray-100 text-gray-600 transition peer-checked:bg-green-100 peer-checked:text-green-600 hover:bg-green-50/75 focus:bg-green-50/75 [.peer:checked_~_&:hover]:bg-green-100/75">
                        <div class="w-2 h-2 rounded-full bg-gray-400 ring-2 ring-gray-200 transition group-has-[:checked]/isPublicButton:ring-4 group-has-[:checked]/isPublicButton:bg-green-500 group-has-[:checked]/isPublicButton:ring-green-300"></div>
                        <p class="text-nowrap">公開する</p>
                    </div>
                </label>
            </div>

            <div class="mt-2">
                <h3>アクセス可能な UserGroup</h3>
                <div class="flex gap-2">
                    @foreach (\Auth::user()->groups as $userGroup)
                        <label class="group/isPublicButton cursor-pointer">
                            <input type="checkbox" name="userGroupIds[]" value="{{ $userGroup->id }}" class="peer hidden" {{ $userGroup->accessPermittedStores()->where("stores.id", $store->id)->exists() ? "checked" : "" }}>
                            <div class="mt-2 pl-4 pr-6 py-2 w-fit flex items-center gap-2 rounded-full bg-gray-100 text-gray-600 transition peer-checked:bg-cyan-100 peer-checked:text-cyan-600 hover:bg-cyan-50/75 focus:bg-cyan-50/75 [.peer:checked_~_&:hover]:bg-cyan-100/75">
                                <div class="w-2 h-2 rounded-full bg-gray-400 ring-2 ring-gray-200 transition group-has-[:checked]/isPublicButton:ring-4 group-has-[:checked]/isPublicButton:bg-cyan-500 group-has-[:checked]/isPublicButton:ring-cyan-300"></div>
                                <p class="text-nowrap">{{ $userGroup->name }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
            
            <!-- PropDef 変更欄 -->
            <div class="mt-4 w-full">
                <h3>プロパティ一覧</h3>
                <div class="px-2 flex gap-2 py-2 w-full overflow-x-auto items-stretch">
                    <!-- テンプレート -->
                    <div id="propDef-template" class="propDef group/propDef mt-2 py-4 px-2 rounded-lg bg-white shadow-md flex flex-col" style="display: none;">
                        <input type="hidden" name="propDefinition_id[]" value="" class="id" disabled>
                        <!-- プロパティ名入力欄 -->
                        <input type="text" name="propDefinition_name[]" value="" placeholder="プロパティ名" class="name border-solid border-transparent border-b-gray-300 w-32" disabled>
                        <div class="mt-4 grow flex flex-col gap-4 opacity-25 transition group-hover/propDef:opacity-100 group-focus-within/propDef:opacity-100">
                            <!-- 入れ替えボタン -->
                            <div class="flex justify-around items-center">
                                <button type="button" onclick="moveToPrev(this)" class="button p-4 text-2xl text-gray-300 rounded hover:bg-gray-100 [.propDef:first-child_&]:invisible">
                                    <div>◀</div>
                                </button>
                                <button type="button" onclick="moveToNext(this)" class="button p-4 text-2xl text-gray-300 rounded hover:bg-gray-100 [.propDef:last-child_&]:invisible">
                                    <div>▶</div>
                                </button>
                            </div>
                            <!-- 削除ボタン -->
                            <button type="button" onclick="removePropDef(this)" class="px-4 py-2 rounded bg-red-100 border border-solid border-red-500 text-red-500 transition hover:bg-red-500 focus:bg-red-500 hover:text-white focus:text-white">
                                削除
                            </button>
                        </div>
                    </div>

                    <!-- PropDef リスト -->
                    <div id="propDefs" class="flex gap-2">
                    </div>

                    <!-- 作成ボタン -->
                    <button type="button" onclick="newPropDef()" class="block px-4 rounded bg-gray-100 text-gray-500 text-2xl transition hover:bg-gray-300 focus:bg-gray-300 hover:scale-105 focus:scale-105">
                        <p>+</p>
                    </button>
                    <!-- 
                        1. すべての propDef を一度保存
                        2. 変更前の propDef の index を取得 $prevPropDefIndexes: number[]
                        3. 変更後の propDef の配列を生成 $propDefs: PropDefs[]
                        4. $propDefs[i]->index = $prevPropDefIndexes[i]
                    -->
                    <script id="data-propDefinitions" type="application/json">@json($store->propDefinitions()->get())</script>
                    <script>
                        const $propDefs = document.querySelector("#propDefs");
                        const $propDefTemplate = document.querySelector("#propDef-template");

                        function moveToPrev($btn) {
                            const $propDef = $btn.closest(".propDef");
                            const $prev = $propDef.previousElementSibling;
                            if ($prev != null)
                                // $propDef.before($prev);
                                $prev.before($propDef);
                        }
                        
                        function moveToNext($btn) {
                            const $propDef = $btn.closest(".propDef");
                            const $next = $propDef.nextElementSibling;
                            if ($next != null)
                                // $propDef.after($next);
                                $next.after($propDef);
                        }
                        
                        function newPropDef(propDef = { id: "new", name: "", index: "" }) {
                            const $propDef = $propDefTemplate.cloneNode(true);
                            $propDef.style.display = "";

                            const $id = $propDef.querySelector(".id");
                            $id.value = propDef.id;
                            $id.removeAttribute("disabled");

                            const $name = $propDef.querySelector(".name");
                            $name.value = propDef.name;
                            $name.removeAttribute("disabled");

                            $propDefs.appendChild($propDef);
                        }

                        function removePropDef($btn) {
                            const $propDef = $btn.closest(".propDef");
                            $propDef.remove();
                        }

                        const propDefs = JSON.parse(document.querySelector("#data-propDefinitions").textContent);
                        console.log(propDefs);
                        for (const propDef of propDefs) {
                            newPropDef(propDef);
                        }
                    </script>
                </div>
            </div>
            
            <!-- 変更保存ボタン -->
            <button type="submit" class="button mt-20 px-20 py-2 rounded self-center bg-gray-800 text-white shadow transition hover:bg-gray-600 hover:shadow-lg">
                変更を保存する
            </button>
        </div>
    </form>
@endsection