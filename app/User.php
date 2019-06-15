<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use App\Notifications\PasswordResetNotification;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use HasRolesAndAbilities;

    protected $fillable = [
        'email', 'fname', 'lname', 'password', 'provider',
        'provider_id', 'account', 'avatar', 'status',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $status = [
        'pending' => 0, 'active' => 1, 'suspended' => 2,
    ];

    protected $userManageableModels = [
        'vendor' => [
            Vendor::class,
            JobQuote::class,
        ],
        'couple' => [
            Couple::class,
            JobPost::class,
        ],
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }

    public function getUserManageableModels()
    {
        return $this->account ? $this->userManageableModels[$this->account] : [];
    }

    public function setStatusAttribute($value)
    {
        $status = isset($this->status[$value]) ? $this->status[$value] : $this->status['inactive'];

        return $this->attributes['status'] = $status;
    }

    public function getStatusAttribute($value)
    {
        return array_search($value, $this->status);
    }

    public function setAccountAttribute($value)
    {
        return $this->attributes['account'] = strtolower($value);
    }

    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = bcrypt($value);
    }

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class);
    }

    public function vendorProfile()
    {
        return $this->hasOne(Vendor::class);
    }

    public function coupleA()
    {
        return $this->hasOne(Couple::class, 'userA_id', 'id');
    }

    public function coupleB()
    {
        return $this->hasOne(Couple::class, 'id', 'userB_id');
    }

    public function coupleProfile()
    {
        return Couple::where('userA_id', $this->id)->orWhere('userB_id', $this->id)->first();
    }

    public function hasProfile()
    {
        if ($this->account === 'couple') {
            return Couple::where('userA_id', $this->id)
                ->orWhere('userB_id', $this->id)->exists();
        }

        if ($this->account === 'vendor') {
            return Vendor::whereId($this->id)->exists();
        }

        return false;
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function tcFiles()
    {
        return $this->hasMany(File::class)->where('meta_key', 'tc');
    }

    public function favoriteVendors()
    {
        return $this->hasMany(FavoriteVendor::class);
    }

    public function savedJobs()
    {
        return $this->hasMany(SavedJob::class);
    }

    public function settings()
    {
        return $this->hasOne(UserSetting::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function paymentGatewayUser()
    {
        return $this->hasOne(PaymentGatewayUser::class);
    }
}
