<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropDefinition extends Model
{
    use HasFactory;

    /**
     * テーブル名を明示的に指定
     */
    protected $table = 'prop_definitions';

    /** 
     * 大量割り当て可能なカラムを指定
     */
    protected $fillable = [
        'store_id',
        'name',
        'index'
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
        $this->loadCount(['recordItems']);
    }

    /**
     * PropDefinition が定義されている Store とのリレーションシップを定義
     */
    public function store() 
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * PropDefinition の値となる RecordItems とのリレーションシップを定義
     * (record_items テーブルを PropDefinition と Record の中間テーブルとみなす)
     */
    public function recordItems()
    {
        return $this->belongsToMany(Record::class, 'record_items', 'prop_id', 'record_id')
            ->withPivot('value')
            ->withTimestamps();
    }


    // loadRelationshipCount を見直す
}
