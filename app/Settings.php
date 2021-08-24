<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = "tbl_settings";
    const WHITE_LOGO_FIELD = "whiteLogo";
    const BLACK_LOGO_FIELD = "blackLogo";
    const LOGO_SETTING = "LOGO";
    const GROUP_SETTING = "GROUP";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'field', 'value', 'type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
}