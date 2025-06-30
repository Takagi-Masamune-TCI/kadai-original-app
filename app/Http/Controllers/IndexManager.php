<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait IndexManager
{
    /**
     * $index の位置に要素を挿入するために、他の要素の index をずらす
     * @param Builder|HasMany $list
     * @param int $index
     * @return void
     */
    protected function reindexForInsertInto(Builder|HasMany $list, int $index) {
        $list->where("index", ">=", $index)
            ->increment("index");
    }

    /**
     * $index の位置から要素を削除するために、他の要素の index をずらす
     * @param Builder|HasMany $list
     * @param int $index
     * @return void
     */
    protected function reindexForRemoveFrom(Builder|HasMany $list, int $index) {
        // 削除予定の自身の index は動かさない
        $list->where("index", ">", $index)
            ->decrement("index");
    }

    /**
     * $fromIndex の要素を $toIndex へ動かすために、他の要素の index をずらす
     * @param Builder|HasMany $list
     * @param int $fromIndex
     * @param int $toIndex
     * @return void
     */
    protected function reindexForReplace(Builder|HasMany $list, int $fromIndex, int $toIndex) {
        if ($fromIndex < $toIndex) {
            // 動かす要素は右方向へ移動する
            $list->where("index", ">", $fromIndex)
                ->where("index", "<=", $toIndex)
                ->decrement("index");   // 他の要素は左方向へ移動させる
        } else {
            // 動かす要素は左方向へ移動する
            $list->where("index", "<", $fromIndex)
                ->where("index", ">=", $toIndex)
                ->increment("index");   // 他の要素は右方向へ移動させる
        }
    }
}