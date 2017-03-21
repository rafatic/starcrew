<?php

namespace starcrew;

use Illuminate\Database\Eloquent\Model;

class UserLanguage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'language_id'];

}
