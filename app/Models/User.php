<?php
namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\User as UserNotify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $hidden = ['password', 'remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendNotification(array $data, int $type)
    {
        $this->notify(new UserNotify($data, $type, $this));
    }

    // public function notifications()
    // {
    //     return $this->morphMany(Notification::class, 'notifiable')->orderBy('created_at', 'desc');
    // }

    public function province()
    {
        return $this->belongsTo(Province::class,'province_id','id');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class,'ward_id','id');
    }

    public function district()
    {
        return $this->belongsTo(District::class,'district_id','id');
    }
}
