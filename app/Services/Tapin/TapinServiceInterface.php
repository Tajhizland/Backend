<?php

namespace App\Service\Tapin;

interface TapinServiceInterface
{
    public function send($order, $boxId, $postStatus,$weight, $part);

}
