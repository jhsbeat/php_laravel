<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

//    /**
//     * @var \Illuminate\Cache\CacheManager
//     */
//    protected $cache;
//    /**
//     * Controller constructor.
//     */
//    public function __construct() {
//        $this->cache = app('cache');
//        if ((new \ReflectionClass($this))->implementsInterface(Cacheable::class) and taggable()) {
//            $this->cache = app('cache')->tags($this->cacheTags());
//        }
//    }
}
