<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'code',
        'expire_at',
        'status'
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

    public function generateCode()
    {
        $this->timestamps = false;
        $this->code = rand(1000, 9999);
        $this->expire_at = now()->addMinute(5);
        $this->save();
    }

    public function restCode()
    {
        $this->timestamps = false;
        $this->code = null;
        $this->expire_at = null;
        $this->status = 'verified';
        $this->save();
    }

    public function routeNotificationForMail(Notification $notification): array|string
    {
        // Return email address only...
        return $this->email;
    }
}
