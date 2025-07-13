<?php

namespace Src\Authorization\Users\Domain;

use Ramsey\Uuid\Uuid;


use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'users';
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'user_login',
        'email',
        'password',
        'password_wordpress',
        'buyer_id',
        'vendor_id',
        'admin_id',
    ];

    protected $casts = [
        'id' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'password_wordpress',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */


    protected static function boot()
    {
        parent::boot();
  
        static::creating(function ($model) {
            // Set the UUIDs if not already set
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Uuid::uuid4()->toString();
            }
  
        });
    }

    protected static function newFactory()
    {
        return \Database\Factories\UserModelFactory::new();
    }
    
    /*
    public function buyer()
    {
        return $this->hasOne(BuyerModel::class, 'user_id', 'id');
    }

    public function admin()
    {
        return $this->hasOne(AdminModel::class, 'id', 'admin_id');
    }

    public function meta()
    {
        return $this->hasOne(UserMetaModel::class, 'user_id', 'id');
    }
    */
}
