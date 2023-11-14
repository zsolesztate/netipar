<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactsAddresses extends Model
{
    use HasFactory;

    protected $fillable = [
        'residential_address',
        'mailing_address'
    ];

    public function contact()
    {
        return $this->belongsTo(Contacts::class);
    }

}
