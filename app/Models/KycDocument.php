<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class KycDocument extends Model
{
    use HasFactory;

    protected $table = 'user_kyc_docs';
    
    protected $primaryKey = 'kyc_id';

    protected $fillable = [
        'user_id',
        'reviewed_by',
        'doc_type',
        'doc_name',
        'doc_path',
        'proof_of_income',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship with the user who uploaded the KYC
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relationship with the admin who reviewed the KYC
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'user_id');
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'reject';
    }

    public function getDocUrlAttribute()
    {
        return $this->doc_path ? asset('storage/' . ltrim($this->doc_path, '/')) : null;
    }

    public function getProofOfIncomeUrlAttribute()
    {
        return $this->proof_of_income ? asset('storage/' . ltrim($this->proof_of_income, '/')) : null;
    }
}