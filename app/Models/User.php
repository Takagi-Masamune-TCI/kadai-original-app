<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
}
