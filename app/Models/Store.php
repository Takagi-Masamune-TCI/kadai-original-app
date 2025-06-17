<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    /**
     * テーブル名を明示的に指定
     */
    protected $table = 'stores';

    /** 
     * 大量割り当て可能な属性を指定
     */
    protected $fillable = [
        'name',
        'is_public',
        'created_by'
    ];

    /**
     * 外部に対して隠したい属性を指定
     */
    protected $hidden = [];

    /**
     * キャストが必要な属性を指定
     */
    protected $casts = [];

    /**
     * リレーションシップ数を読み込む
     * @return void
     */
    public function loadRelationshipCount() 
    {
        $this->loadCount(['propDefinitions', 'records', 'favoritedBy']);
    }

    /**
     * この Store を作成した User とのリレーションシップを定義
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * この Store で定義している PropDefinition とのリレーションシップを定義
     */
    public function propDefinitions() 
    {
        return $this->hasMany(PropDefinition::class);
    }

    /**
     * この Store に入っている Record とのリレーションシップを定義
     */
    public function records()
    {
        return $this->hasMany(Record::class);
    }

    /**
     * この Store をお気に入りしている User とのリレーションシップを定義
     */
    public function favoritedBy() 
    {
        return $this->belongsToMany(User::class, 'store_favorites');
    }

    // loadRelationshipCount を見直す
}
