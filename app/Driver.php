<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    //
    public function scopeSearch($query, $s) {
        return $query->where('customers.company','like', '%'.$s.'%')->orWhere('users.firstname','like', '%'.$s.'%');
    }
}
