<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_name',
        'contact_image'
    ];

    /*protected $casts = [
        'addresses' => 'object'
    ];*/

    public function contacts_emails()
    {
        return $this->hasMany(ContactsEmails::class)->orderBy('contact_email_address');
    }

    public function contacts_phonenumbers()
    {
        return $this->hasMany(ContactsPhonenumbers::class)->orderBy('contact_phone_number');
    }

    public function contacts_addresses()
    {
        return $this->hasOne(ContactsAddresses::class);
    }
}
