<?php

namespace App\Services\OnHoldOrder;

interface OnHoldOrderServiceInterface
{
    public function userHoldOnPaginate($userId);
    public function removeItem($id);
    public function setReject($id);
    public function setAccept($id);
    public function dataTable();

}
