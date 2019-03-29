<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    //
    public function scopeSearch($query, $s) {
        return $query->where('details.po','like', '%'.$s.'%');
    }
}
