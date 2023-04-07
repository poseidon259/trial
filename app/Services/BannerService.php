<?php

namespace App\Services;

use App\Repositories\Banner\BannerRepositoryInterface;

class BannerService
{
    const BANNER_FOLDER = 'banners';
    /**
     * @var BannerRepositoryInterface
     */
    private $bannerRepositoryInterface;

    /**
     * @var ImageKitService
     */
    private $imageKitService;


    public function __construct(
        BannerRepositoryInterface $bannerRepositoryInterface,
        ImageKitService           $imageKitService
    )
    {
        $this->bannerRepositoryInterface = $bannerRepositoryInterface;
        $this->imageKitService = $imageKitService;
    }

    public function update($request)
    {
        $params = [];
        if (isset($request->images)) {

            if (count($request->images) > BANNER_LIMIT) {
                return _error(null, __('messages.banner_max'), HTTP_BAD_REQUEST);
            }

            $this->deleteOldImage();

            $images = $request->images;

            foreach ($images as $index => $image) {
                if (isset($image['image'])) {
                    $file = $image['image'];
                    $fileName = $file->getClientOriginalName();
                    $options = [
                        'folder' => self::BANNER_FOLDER,
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
                    ];
                }
            }

            $this->bannerRepositoryInterface->insert($params);

            return _success(null, __('messages.create_success'), HTTP_SUCCESS);
        } else {

            $this->deleteOldImage();
            return _success(null, __('messages.create_success'), HTTP_SUCCESS);
        }
    }

    public function deleteOldImage()
    {
        $oldBanners = $this->bannerRepositoryInterface->getListBanner();

        if (count($oldBanners) > 0) {
            $oldIds = [];
            foreach ($oldBanners as $oldBanner) {
                $this->imageKitService->delete($oldBanner->file_id);
                $oldIds[] = $oldBanner->id;
            }

            $this->bannerRepositoryInterface->deleteIds($oldIds);
        }
    }

    public function list()
    {
        $banners = $this->bannerRepositoryInterface->list();

        return _success($banners, __('messages.success'), HTTP_SUCCESS);
    }
}
