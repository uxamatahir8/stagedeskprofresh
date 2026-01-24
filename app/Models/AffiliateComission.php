<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateComission extends Model
{
    protected $fillable = [
        'affiliate_id',
        'company_id',
        'company_subscription_id',
        'amount',
        'status',
    ];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function subscription()
    {
        return $this->belongsTo(CompanySubscription::class, 'company_subscription_id');
    }
}
