<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'emp_scode', 'emp_code', 'emp_name', 'emp_lastname', 'emp_tel', 'emp_passport', 'emp_address', 'avatar'
        , 'work_id', 'pos_id', 'status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'emp_password',
    ];

    protected $table = 'employees';
}