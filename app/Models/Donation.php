<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public const TYPES = [
        'Disaster',
        'Education',
        'Humanity',
        'Infrastructure',
        'Medical',
        'Religion',
    ];

    public const STATUSES = [
        'pending',
        'success',
        'failed',
        'expired',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('donor_name', 'LIKE', "%$search%")
                    ->orWhere('amount', 'LIKE', "%$search%");
            });
        });

        $query->when($filters['donation_type'] ?? false, function ($query, $donation_type) {
            $query->where('donation_type', $donation_type);
        });

        $query->when($filters['status'] ?? false, function ($query, $status) {
            $query->where('status', $status);
        });
    }

    public function setStatusPending()
    {
        $this->attributes['status'] = 'pending';
        self::save();
    }

    public function setStatusSuccess()
    {
        $this->attributes['status'] = 'success';
        self::save();
    }

    public function setStatusFailed()
    {
        $this->attributes['status'] = 'failed';
        self::save();
    }

    public function setStatusExpired()
    {
        $this->attributes['status'] = 'expired';
        self::save();
    }
}
