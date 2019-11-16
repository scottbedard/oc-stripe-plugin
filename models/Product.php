<?php

namespace Bedard\Saas\Models;

use Model;

/**
 * Product Model.
 */
class Product extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_saas_products';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [
        'is_active' => 'boolean',
        'name'      => 'required',
        'slug'      => 'required|unique:bedard_saas_products',
    ];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [
        'is_active' => 'boolean',
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
    public $hasMany = [
        'schedules' => 'Bedard\Saas\Models\Schedule',
    ];

    /**
     * Query scopes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}