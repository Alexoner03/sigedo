<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function contract_type()
    {
        return $this->belongsTo(ContractType::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function userAssigned()
    {
        return $this->belongsTo(User::class,'id_user_assigned','id');
    }

    public function userCreator()
    {
        return $this->belongsTo(User::class,'id_user_creator','id');
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class,'user_contract')->withPivot('check','observations');
    }

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'request_date' => 'datetime:d-m-Y',
        'term_date' => 'datetime:d-m-Y',
        'id_user_assigned' => 'integer',
        'id_user_creator' => 'integer',
        'business_id' => 'integer',
        'contract_type_id' => 'integer',
    ];

}
