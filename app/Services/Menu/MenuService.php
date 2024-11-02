<?php

namespace App\Services\Menu;

use App\Repositories\Menu\MenuRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

class MenuService implements MenuServiceInterface
{
    public function __construct
    (
        private MenuRepositoryInterface $menuRepository,
        private S3ServiceInterface      $s3Service,
    )
    {
    }

    public function dataTable()
    {
        return $this->menuRepository->dataTable();
    }

    public function findById($id)
    {
        return $this->menuRepository->findOrFail($id);
    }

    public function store($title, $parentId, $url, $bannerTitle, $bannerUrl, $bannerLogo)
    {
        $logoPath = "";
        if ($bannerLogo) {
            $logoPath = $this->s3Service->upload($bannerLogo, "menu");
        }
        return $this->menuRepository->create([
            "title" => $title,
            "parent_id" => $parentId,
            "url" => $url,
            "banner_title" => $bannerTitle,
            "banner_link" => $bannerUrl,
            "banner_logo" => $logoPath
        ]);
    }

    public function update($id, $title, $parentId, $url, $bannerTitle, $bannerUrl, $bannerLogo)
    {
        $menu = $this->menuRepository->findOrFail($id);
        $logoPath = $menu->banner_logo;
        if ($bannerLogo) {
            $this->s3Service->remove("menu/" . $bannerLogo);
            $logoPath = $this->s3Service->upload($bannerLogo, "menu");
        }
        return $this->menuRepository->update($menu,
            [
                "title" => $title,
                "parent_id" => $parentId,
                "url" => $url,
                "banner_title" => $bannerTitle,
                "banner_link" => $bannerUrl,
                "banner_logo" => $logoPath
            ]);
    }

    public function buildMenu()
    {
        return $this->menuRepository->getWithChildren();
    }
}
