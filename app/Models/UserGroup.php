<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    use HasFactory;

    /**
     * テーブル名を明示的に指定
     */
    protected $table = 'user_groups';

    /** 
     * 大量割り当て可能なカラムを指定
     */
    protected $fillable = [
        'name', 'created_by'
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
        $this->loadCount(["users", "owners", "accessibleStores", "createdBy"]);
    }


    // リレーションシップを定義する
    public function users() 
    {
        // user_belongings テーブルでは UserGroup は user_group_id, User は user_id で表されるため省略。
        return $this->belongsToMany(User::class, "user_belongings")
            ->withPivot("is_owner");
    }

    public function owners() 
    {
        return $this->users()->where("is_owner", true);
    }

    public function accessPermittedStores() 
    {
        // user_group_store_permissions テーブルでは UserGroup は user_group_id, Store は store_id で表されるため省略。
        return $this->belongsToMany(Store::class, "user_group_store_permissions");
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, "created_by");
    }

    // loadRelationshipCount を見直す
}
