<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
    ];
}
