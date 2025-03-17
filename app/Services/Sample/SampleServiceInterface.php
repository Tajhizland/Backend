<?php

namespace App\Services\Sample;

interface SampleServiceInterface
{
    public function find();
    public function getImages();
    public function getVideos();
    public function update($content);
    public function uploadImage($image);
    public function removeImage($id);
    public function addVideo($id);
    public function deleteVideo($id);
}
