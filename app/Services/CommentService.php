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

        if (!$comment) {
            return _error(null, __('messages.create_error'), HTTP_BAD_REQUEST);
        }

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

    public function update($request, $productId, $commentId)
    {
        $checkExistsProduct = $this->productRepositoryInterface->find($productId);

        if (!$checkExistsProduct) {
            return _error(null, __('messages.product_not_found'), HTTP_BAD_REQUEST);
        }

        $checkExistsComment = $this->commentRepositoryInterface->find($commentId);

        if (!$checkExistsComment) {
            return _error(null, __('messages.comment_not_found'), HTTP_BAD_REQUEST);
        }

        $commentParams = [
            'content' => $request->content,
            'rating' => $request->rating,
            'status' => $request->status,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'updated_at' => now(),
        ];

        $comment = $this->commentRepositoryInterface->update($commentId, $commentParams);

        if (!$comment) {
            return _error(null, __('messages.update_error'), HTTP_BAD_REQUEST);
        }

        if (isset($request->images)) {
            $images = $request->images;
            $imageParams = [];

            $this->deleteOldImage($commentId);

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
        } else {
            $this->deleteOldImage($commentId);
        }

        return _success(null, __('messages.update_success'), HTTP_SUCCESS);
    }

    public function delete($productId, $commentId)
    {
        $checkExistsProduct = $this->productRepositoryInterface->find($productId);

        if (!$checkExistsProduct) {
            return _error(null, __('messages.product_not_found'), HTTP_BAD_REQUEST);
        }

        $checkExistsComment = $this->commentRepositoryInterface->find($commentId);

        if (!$checkExistsComment) {
            return _error(null, __('messages.comment_not_found'), HTTP_BAD_REQUEST);
        }

        $this->deleteOldImage($commentId);
        $this->commentRepositoryInterface->delete($commentId);

        return _success(null, __('messages.delete_success'), HTTP_SUCCESS);
    }

    public function show($productId, $commentId)
    {
        $checkExistsProduct = $this->productRepositoryInterface->find($productId);

        if (!$checkExistsProduct) {
            return _error(null, __('messages.product_not_found'), HTTP_BAD_REQUEST);
        }

        $checkExistsComment = $this->commentRepositoryInterface->find($commentId);

        if (!$checkExistsComment) {
            return _error(null, __('messages.comment_not_found'), HTTP_BAD_REQUEST);
        }

        $comment = $this->commentRepositoryInterface->detail($commentId);

        return _success($comment, __('messages.success'), HTTP_SUCCESS);
    }

    public function list($request, $productId)
    {
        $checkExistsProduct = $this->productRepositoryInterface->find($productId);

        if (!$checkExistsProduct) {
            return _error(null, __('messages.product_not_found'), HTTP_BAD_REQUEST);
        }

        $limit = $request->limit ?? LIMIT;
        $page = $request->page ?? PAGE;

        $comments = $this->commentRepositoryInterface->getListCommentByProductId($request, $productId)->paginate($limit, $page);

        return [
            'comments' => $comments->items(),
            'total' => $comments->total(),
            'current_page' => $comments->currentPage(),
            'last_page' => $comments->lastPage(),
            'per_page' => $comments->perPage(),
        ];
    }

    public function deleteOldImage($commentId)
    {
        $oldImages = $this->commentImageRepositoryInterface->getListByCommentId($commentId);

        if (count($oldImages) > 0) {
            $oldIds = [];
            foreach ($oldImages as $image) {
                $this->imageKitService->delete($image->file_id);
                $oldIds[] = $image->id;
            }

            $this->commentImageRepositoryInterface->deleteIds($oldIds);
        }
    }

    public function createCommentPublic($request, $productId, $user)
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
            'user_id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'fake_avatar' => $user->avatar,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $comment = $this->commentRepositoryInterface->create($commentParams);

        if (!$comment) {
            return _error(null, __('messages.create_error'), HTTP_BAD_REQUEST);
        }

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
