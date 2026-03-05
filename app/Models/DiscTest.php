<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscTest extends Model
{
    protected $fillable = [
        'name','email',
        'most_d','most_i','most_s','most_c','most_star',
        'least_d','least_i','least_s','least_c','least_star',
        'self_d','self_i','self_s','self_c','self_star'
    ];
}