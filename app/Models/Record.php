<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    /**
     * テーブル名を明示的に指定
     */
    protected $table = 'records';

    /** 
     * 大量割り当て可能なカラムを指定
     */
    protected $fillable = [
        'store_id',
        'index',
        'created_by'
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
        $this->loadCount(['props', 'favoritedBy']);
    }

    /**
     * この Record を作成した User とのリレーションシップを定義
     */
    public function createdBy() 
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * この Record が属している Store とのリレーションシップを定義
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * この Record が持っている Prop (PropDefinition とのリレーションシップ) を定義
     * record_items テーブルを Record と PropDefinition の中間テーブルとみなす
     */
    public function props() 
    {
        return $this->belongsToMany(PropDefinition::class, 'record_items', 'record_id', 'prop_id')
            ->withPivot('value')
            ->withTimestamps();
    }


    /**
     * この Record をお気に入りしている User とのリレーションシップを定義
     */
    public function favoritedBy() 
    {
        return $this->belongsToMany(User::class, 'record_favorites');
    }

    // loadRelationshipCount を見直す
}
