<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile_number',
        'address',
        'role_id',
        'country_code',
        'active_trip_id',
        'status',
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

    public function createUser($data)
    {
        if (in_array(config('roles.' . $data->role_id), ['Developer', 'Admin'])) {
            $data->validate([
                'email' => ' required',
                'password' => 'required',
            ]);
        }
        $data->merge(['mobile_number' => ltrim($data->mobile_number, '0')]);
        $user = $this->create($data->all());
        return $user;
    }

    public function updateUser($data, $user)
    {
        $user->update($data->all());
        return $user;
    }

    public function checkUserExistOrNot($mobile_number)
    {
        $user = User::where('mobile_number', $mobile_number)->first();
        if ($user != null) {
            return $user;
        } else {
            return null;
        }
    }
}
