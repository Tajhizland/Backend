<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\HomepageVlog\HomepageVlogServiceInterface;

class HomepageVlogController extends Controller
{
    public function __construct
    (
        private HomepageVlogServiceInterface $homepageVlogService
    )
    {
    }

    public function get()
    {

    }

    public function update()
    {

    }
}
