<?php

namespace starcrew;

use Illuminate\Database\Eloquent\Model;

class MissionLanguage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['mission_id', 'language_id'];
}
