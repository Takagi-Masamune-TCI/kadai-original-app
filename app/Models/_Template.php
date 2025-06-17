<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _Template extends Model
{
    use HasFactory;

    /**
     * テーブル名を明示的に指定
     */
    protected $table = '...';

    /** 
     * 大量割り当て可能なカラムを指定
     */
    protected $fillable = [
        '...',
    ];

    /**
     * 外部に対して隠したいカラムを指定
     */
    protected $hidden = [];

    /**
     * キャストが必要なカラムを指定
     */
    protected $casts = [];

    /**
     * リレーションシップ数を読み込む
     * @return void
     */
    public function loadRelationshipCount() 
    {
        $this->loadCount(['...']);
    }


    // リレーションシップを定義する



    // loadRelationshipCount を見直す
}
