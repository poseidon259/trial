<?php

namespace App\Services;

use App\Repositories\BannerStore\BannerStoreRepositoryInterface;

class BannerStoreService
{
    const BANNER_STORE_FOLDER = 'banner_stores';
    /**
     * @var BannerStoreRepositoryInterface
     */
    private $bannerStoreRepositoryInterface;

    /**
     * @var ImageKitService
     */
    private $imageKitService;


    public function __construct(
        BannerStoreRepositoryInterface $bannerStoreRepositoryInterface,
        ImageKitService $imageKitService
    )
    {
        $this->bannerStoreRepositoryInterface = $bannerStoreRepositoryInterface;
        $this->imageKitService = $imageKitService;
    }

    public function update($request, $storeId)
    {
        $params = [];
        if (isset($request->images)) {

            if (count($request->images) > BANNER_LIMIT) {
                return _error(null, __('messages.banner_max'), HTTP_BAD_REQUEST);
            }

            $this->deleteOldImage($storeId);

            $images = $request->images;

            foreach ($images as $index => $image) {
                $file = $image['image'];
                $fileName = $file->getClientOriginalName();
                $options = [
                    'folder' => self::BANNER_STORE_FOLDER,
                ];

                $uploadFile = $this->imageKitService->upload($file, $fileName, $options);

                $params[] = [
                    'image' => $uploadFile['filePath'],
                    'file_id' => $uploadFile['fileId'],
                    'link_url' => $image['link_url'],
                    'sort' => $image['sort'] ?? $index,
                    'display' => $image['display'] ?? BANNER_ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'store_id' => $storeId,
                ];
            }

            $this->bannerStoreRepositoryInterface->insert($params);

            return _success(null, __('messages.create_success'), HTTP_SUCCESS);
        } else {

            $this->deleteOldImage($storeId);
            return _success(null, __('messages.create_success'), HTTP_SUCCESS);
        }
    }

    public function deleteOldImage($storeId)
    {
        $oldBanners = $this->bannerStoreRepositoryInterface->getListBanner($storeId);

        if (count($oldBanners) > 0) {
            $oldIds = [];
            foreach ($oldBanners as $oldBanner) {
                $this->imageKitService->delete($oldBanner->file_id);
                $oldIds[] = $oldBanner->id;
            }

            $this->bannerStoreRepositoryInterface->deleteIds($oldIds);
        }
    }

    public function list($storeId)
    {
        $banners = $this->bannerStoreRepositoryInterface->list($storeId);

        return _success($banners, __('messages.success'), HTTP_SUCCESS);
    }

    public function getListBannerStorePublic($storeId)
    {
        $banners = $this->bannerStoreRepositoryInterface->getListBannerStorePublic($storeId);

        return _success($banners, __('messages.success'), HTTP_SUCCESS);
    }
}
