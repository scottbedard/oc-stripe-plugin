<?php

namespace Bedard\Saas\Classes;

use Closure;
use Illuminate\Routing\Controller;

class ApiController extends Controller
{
    use \October\Rain\Extension\ExtendableTrait;

    /**
     * @var array Behaviors implemented by this controller.
     */
    public $implement;

    /**
     * Construct.
     *
     * @return void
     */
    public function __construct()
    {
        $this->extendableConstruct();
    }

    /**
     * Extend object properties upon construction.
     *
     * @param Closure $callback
     */
    public static function extend(Closure $callback)
    {
        self::extendableExtendCallback($callback);
    }
}
