<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactsEmails extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_email_address'
    ];

    public function contact()
    {
        return $this->belongsTo(Contacts::class);
    }
}
