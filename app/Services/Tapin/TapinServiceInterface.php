<?php

namespace App\Services\Tapin;

interface TapinServiceInterface
{
    public function send($order, $postStatus, $weight, $part, $boxId);
}
