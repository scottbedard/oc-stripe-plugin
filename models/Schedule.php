<?php

namespace Bedard\Saas\Models;

use Model;

/**
 * Schedule Model.
 */
class Schedule extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_saas_schedules';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'calendar_duration',
        'calendar_unit',
        'cost',
        'name',
    ];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [
        'calendar_duration' => 'required|integer|min:1',
        'calendar_unit'     => 'required|in:day,month,year',
        'cost'              => 'required|numeric|min:0',
        'name'              => 'required',
    ];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [
        'calendar_unit' => 'number',
        'cost'          => 'number',
    ];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'plan' => 'Bedard\Saas\Models\Plan',
    ];
}
