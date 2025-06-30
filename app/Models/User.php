<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /** テーブル名を明示的に指定 */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * リレーションシップ数を読み込む
     * @return void
     */
    public function loadRelationshipCount() 
    {
        $this->loadCount(['stores', 'records', 'favoriteStores', 'favoriteRecords']);
    }

    /**
     * ユーザーが作成したストアを表すリレーションシップ。
     */
    public function stores()
    {
        // Store のテーブルから stores.created_by == $this->id のレコードを取得する。
        // 該当のカラムが stores.user_id ではないため、第2引数で明示的に指定。
        return $this->hasMany(Store::class, 'created_by');
    }

    /**
     * ユーザーが作成したレコードを表すリレーションシップ。
     */
    public function records()
    {
        // Record のテーブルから records.created_by == $this->id のレコードを取得する。
        // 該当のカラムが records.user_id ではないため、第2引数で明示的に指定。
        return $this->hasMany(Record::class, 'created_by');
    }

    /** 
     * ユーザーがお気に入りしているストアを表すリレーションシップ。
     */
    public function favoriteStores()
    {
        // store_favorites.user_id == $this->id なレコードの store_favorites.store_id == stores.id の Store を取得する
        // 中間テーブル名が store_user ではないため明示的に指定。
        // store_favorites テーブルでは User は user_id で表されるため省略。
        // store_favorites テーブルでは Store は store_id で表されるため省略。 
        return $this->belongsToMany(Store::class, 'store_favorites')
            ->withTimestamps();
    }

    /**
     * ユーザーがお気に入りしているレコードを表すリレーションシップ。
     */
    public function favoriteRecords()
    {
        // record_favorites.user_id == $this->id なレコードの record_favorites.record_id == records.id の Record を取得する
        // 中間テーブル名が record_user ではないため明示的に指定。
        // record_favorites テーブルでは User は user_id で表されるため省略。
        // record_favorites テーブルでは Record は record_id で表されるため省略。 
        return $this->belongsToMany(Record::class, 'record_favorites')
            ->withTimestamps();
    }

    public function usingStores() {
        $favoriteStoreIds = $this->favoriteStores()->pluck("stores.id")->toArray();

        return Store::where("created_by", $this->id)
            ->orWhereIn("id", $favoriteStoreIds);
    }

    public function isStoreFavorited(int $storeId)
    {
        return $this->favoriteStores()->where("store_id", $storeId)->exists();
    }

    public function favoriteStore(int $storeId)
    {
        if ($this->isStoreFavorited($storeId)) {
            return false;
        }

        $this->favoriteStores()->attach($storeId);
        return true;
    }

    public function unfavoriteStore(int $storeId)
    {
        if ($this->isStoreFavorited($storeId) == false) {
            return false;
        }

        $this->favoriteStores()->detach($storeId);
        return true;
    }

    public function isRecordFavorited(int $recordId)
    {
        return $this->favoriteRecords()->where("record_id", $recordId)->exists();
    }

    public function favoriteRecord(int $recordId)
    {
        if ($this->isRecordFavorited($recordId)) {
            return false;
        }

        $this->favoriteRecords()->attach($recordId);
        return true;
    }

    public function unfavoriteRecord(int $recordId)
    {
        if ($this->isRecordFavorited($recordId) == false) {
            return false;
        }

        $this->favoriteRecords()->detach($recordId);
        return true;
    }

    public function foreignStoresFilter() 
    {
        $favoriteStoreIds = $this->favoriteStores()->pluck("store_id")->toArray();

        return Store::where("created_by", "!=", $this->id)
            ->where("is_public", true)
            ->whereNotIn("id", $favoriteStoreIds);
    }

    public function groups()
    {
        // user_belongings テーブルでは User は user_id, UserGroup は user_group_id で表されるため省略。
        return $this->belongsToMany(UserGroup::class, "user_belongings");
    }

    public function createdGroups()
    {
        return $this->hasMany(UserGroup::class, "created_by");
    }

    public function ownedGroups()
    {
        return $this->groups()->where("is_owner", true);
    }

    public function isOwnerOf(string $groupId)
    {
        return $this->ownedGroups()->where("user_group_id", $groupId)->exists();
    }

    /**
     * 所属するグループのアクセスが許可された Store
     */
    public function accessPermittedStores()
    {
        return Store::whereIn("stores.id", fn ($query) => 
            $query->select("store_id")
                ->from("user_group_store_permissions")
                ->whereIn("user_group_id", $this->groups()->pluck("user_groups.id")->toArray())
        );
    }

    /**
     * アクセス可能なすべての Store
     */
    public function accessibleStores()
    {
        return Store::
            // 自身が作成した Store
            where("created_by", $this->id)
            // 公開設定の Store
            ->orWhere("is_public", true)
            // 所属する UserGroup からのアクセスが許可されている Store
            ->orWhereIn("stores.id", fn ($query) => 
                $query->select("store_id")   
                ->from("user_group_store_permissions")
                ->whereIn("user_group_id", $this->groups()->pluck("user_groups.id")->toArray())
        );
    }
}
