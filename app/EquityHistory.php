<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquityHistory extends Model
{
    protected $table = "tbl_equity_history";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id', 'equity', 'max_equity', 'min_equity', 'openposition_profit', 'dayprofit', 'balence', 'drowdown', 'date', 'month', 'year', 'lastMonth',
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

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }

    public function account()
    {
        return $this->belongsTo(Accounts::class, 'account_id');
    }
}