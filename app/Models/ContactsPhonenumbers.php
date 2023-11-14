<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactsPhonenumbers extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_phone_number'
    ];

    public function contact()
    {
        return $this->belongsTo(Contacts::class);
    }

    
}
