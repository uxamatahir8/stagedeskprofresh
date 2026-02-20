<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'scope',
        'company_id',
        'display_name',
        'method_type',
        'account_name',
        'account_number',
        'iban',
        'swift_code',
        'wallet_email',
        'instructions',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeMasterOwned($query)
    {
        return $query->where('scope', 'master')->whereNull('company_id');
    }

    public function scopeCompanyOwned($query, int $companyId)
    {
        return $query->where('scope', 'company')->where('company_id', $companyId);
    }
}
