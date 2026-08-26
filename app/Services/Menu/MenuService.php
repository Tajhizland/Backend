<?php

namespace App\Services\Menu;

use App\DTOs\Menu\MenuStoreDto;
use App\DTOs\Menu\MenuUpdateDto;
use App\Repositories\Menu\MenuRepositoryInterface;
use App\Services\S3\S3ServiceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class MenuService implements MenuServiceInterface
{
    public function __construct
    (
        private MenuRepositoryInterface $menuRepository,
        private S3ServiceInterface      $s3Service,
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->menuRepository->dataTable();
    }

    public function find(int $id): mixed
    {
        $menu = $this->menuRepository->find($id);
        if (!$menu) {
            throw new NotFoundHttpException();
        }
        return $menu;
    }

    public function store(MenuStoreDto $dto): mixed
    {
        $logoPath = "";
        if ($dto->banner_logo) {
            $logoPath = $this->s3Service->upload($dto->banner_logo, "menu");
        }
        return $this->menuRepository->create([
            "title" => $dto->title,
            "parent_id" => $dto->parent_id,
            "url" => $dto->url,
            "status" => $dto->status,
            "category_id" => $dto->category_id,
            "banner_link" => $dto->banner_link,
            "banner_logo" => $logoPath,
        ]);
    }

    public function update(MenuUpdateDto $dto): bool
    {
        $menu = $this->find($dto->menuId);
        $logoPath = $menu->banner_logo;
        if ($dto->banner_logo) {
            $this->s3Service->remove("menu/" . $logoPath);
            $logoPath = $this->s3Service->upload($dto->banner_logo, "menu");
        }
        return $this->menuRepository->update($menu, [
            "title" => $dto->title,
            "parent_id" => $dto->parent_id,
            "url" => $dto->url,
            "status" => $dto->status,
            "category_id" => $dto->category_id,
            "banner_link" => $dto->banner_link,
            "banner_logo" => $logoPath,
        ]);
    }

    public function buildMenu(): mixed
    {
        return $this->menuRepository->getWithChildren();
    }

    public function list(): mixed
    {
        return $this->menuRepository->allActiveList();
    }

    public function delete(int $id): bool|null
    {
        return $this->menuRepository->delete($this->find($id));
    }

    public function deleteBanner(int $id): bool
    {
        $menu = $this->find($id);
        $this->s3Service->remove("menu/" . $menu->banner_logo);
        return $this->menuRepository->update($menu, ["banner_logo" => null]);
    }
}
