<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Advert;

class Filestorage extends Model
{
    use HasFactory;

    public function advert()
    {
        return $this->belongsTo(Advert::class);
    }

}
