<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'logo',
        'contact_email',
        'facebook_link',
        'footer_text',
    ];
}
