<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasApiTokens, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function resolveRouteBinding($encryptedId, $field = null)
    {
        return $this->where('id', Crypt::decryptString($encryptedId))->firstOrFail();
    }

    public function name() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => ($value) ? Crypt::decryptString($value) : '',
            set: fn ($value) => $value,
        );
    }

    public function getAvatar() : string
    {
      return (!$this->avatar) ? asset('/img/default-avatar.png') : asset('/img/users/'.$this->avatar);
    }

    public function passLog()
    {
      return $this->hasMany(UserPassLog::class, 'user_id');
    }
    
    public function lastLog()
    {
      return $this->hasOne(UserPassLog::class, 'user_id')->latestOfMany();
    }

    public function loginLogs()
    {
      return $this->hasMany(UserLoginLog::class, 'user_id');  
    }

    public function branches()
    {
      return $this->belongsToMany(GlbBranch::class, 'branch_user', 'user_id', 'branch_id')->withPivot('active')->withTimeStamps();
    }

    public function sales()
    {
      return $this->hasMany(Sales::class);
    }
}
