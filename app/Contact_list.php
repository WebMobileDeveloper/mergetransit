<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact_list extends Model
{
    public function scopeSearch($query, $s) {
        return $query->where('contact_lists.d_company_name','like', '%'.$s.'%');
    }
}
