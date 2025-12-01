<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\UserRole;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $primaryKey = 'user_id'; // Add this line

     protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'phone_num',
        'email',
        'role',
        'position',
        'password',
        'status',
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
            'role'=> UserRole::class,
            'status' => "string",
        ];
    }

    // Relationship with KYC documents
    public function kycDocuments()
    {
        return $this->hasMany(KycDocument::class, 'user_id', 'user_id');
    }

    // Get latest KYC document
    public function latestKycDocument()
    {
        return $this->hasOne(KycDocument::class, 'user_id', 'user_id')->latestOfMany();
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    public function isVerified()
    {
        return $this->status === 'verified' && $this->hasVerifiedEmail();
    }

    public function hasApprovedKyc()
    {
        return $this->kycDocuments()->where('status', 'approved')->exists();
    }

    public function pendingKycDocuments()
    {
        return $this->kycDocuments()->where('status', 'pending');
    }
    public function getKycStatusAttribute()
    {
        // If kyc_status is stored in users table
        return $this->attributes['kyc_status'] ?? 'pending';
        
    }
    // In your User model, update these relationships:
    public function leases(): HasMany
    {
        return $this->hasMany(Leases::class, 'user_id', 'user_id');
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Leases::class, 'user_id','lease_id', 'user_id','lease_id'  );
    }

    public function billings()
    {
        return $this->hasManyThrough(Billing::class, Leases::class, 'user_id', 'lease_id', 'user_id',  'lease_id'  );
    }
    public function properties()
    {
        return $this->hasMany(Property::class, 'user_id', 'user_id');
    }
    public function isActiveTenant()
    {
        return $this->leases()
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->exists();
    }
}
