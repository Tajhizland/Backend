<?php

namespace App\Services\HomepageVlog;

interface HomepageVlogServiceInterface
{
    public function get();

    public function update($id, $vlogId);
}
