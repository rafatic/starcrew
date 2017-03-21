<?php

namespace starcrew;

use Illuminate\Database\Eloquent\Model;

class CrewSlot extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['mission_id', 'role_id', 'user_id'];
}
