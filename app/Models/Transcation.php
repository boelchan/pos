<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Transcation extends Model
{
    protected $guarded = [];

    protected $primaryKey = 'invoices_number';

    public $incrementing = false;

    protected $keyType = 'string';

    public function productTranscation()
    {
        return $this->hasMany(ProductTranscation::class, 'invoices_number', 'invoices_number');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getTanggalAttribute()
    {
        return (new Carbon($this->created_at))->format('d-m-Y');
    }
}
