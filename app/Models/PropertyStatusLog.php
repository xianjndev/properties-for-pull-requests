<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyStatusLog extends Model
{
    protected $fillable=[
        'user_id',
          'property_code',
          'status_code',
          'status_description',
          'remarks'
    ];
}
