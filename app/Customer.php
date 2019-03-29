<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    public function scopeSearch($query, $s) {
        return $query->where('customers.company','like', '%'.$s.'%')->orWhere('customers.email','like', '%'.$s.'%');
    }
}
