<?php

namespace App\Services;

use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\CommentImage\CommentImageRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;

class CommentService
{
    const COMMENT_FOLDER = 'comments';

    /**
     * @var CommentRepositoryInterface
     */
    private $commentRepositoryInterface;

    /**
     * @var CommentImageRepositoryInterface
     */
    private $commentImageRepositoryInterface;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepositoryInterface;

    /**
     * @var ImageKitService
     */
    private $imageKitService;

    public function __construct(
        ImageKitService $imageKitService,
        CommentRepositoryInterface $commentRepositoryInterface,
        CommentImageRepositoryInterface $commentImageRepositoryInterface,
        ProductRepositoryInterface $productRepositoryInterface
    ) {
        $this->imageKitService = $imageKitService;
        $this->commentRepositoryInterface = $commentRepositoryInterface;
        $this->commentImageRepositoryInterface = $commentImageRepositoryInterface;
        $this->productRepositoryInterface = $productRepositoryInterface;
    }

    public function create($request, $productId)
    {
        $checkExists = $this->productRepositoryInterface->find($productId);

        if (!$checkExists) {
            return _error(null, __('messages.product_not_found'), HTTP_BAD_REQUEST);
        }

        $commentParams = [
            'product_id' => $productId,
            'content' => $request->content,
            'rating' => $request->rating,
            'status' => COMMENT_ACTIVE,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'fake_avatar' => AVATAR_DEFAULT,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $comment = $this->commentRepositoryInterface->create($commentParams);

        if (isset($request->images)) {
            $images = $request->images;
            $imageParams = [];

            foreach ($images as $image) {
                $file = $image['image'];
                $fileName = $file->getClientOriginalName();
                $options = [
                    'folder' => self::COMMENT_FOLDER,
                ];

                $uploadFile = $this->imageKitService->upload($file, $fileName, $options);

                $imageParams[] = [
                    'comment_id' => $comment->id,
                    'image' => $uploadFile['filePath'],
                    'file_id' => $uploadFile['fileId'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $this->commentImageRepositoryInterface->insert($imageParams);
        }
        
        return _success(null, __('messages.create_success'), HTTP_SUCCESS);
    }
}
