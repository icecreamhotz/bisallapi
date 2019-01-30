<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Worktime extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'work_startdate', 'work_enddate', 'work_timein', 'work_timeout', 'time_in', 'time_out', 'work_img',
        'add_by', 'check_by'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public $timestamps = true;

    protected $primaryKey = 'work_id';

    protected $table = 'worktimes';
}