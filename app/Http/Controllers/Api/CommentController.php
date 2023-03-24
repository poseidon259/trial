<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\GetListCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Services\CommentService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    /**
     * @var CommentService
     */
    private $commentService;

    public function __construct(
        CommentService $commentService
    ) {
        $this->commentService = $commentService;
    }

    public function create(CreateCommentRequest $request, $productId)
    {
        DB::beginTransaction();
        try {
            $comment = $this->commentService->create($request, $productId);
            DB::commit();
            return $comment;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function update(UpdateCommentRequest $request, $productId, $commentId)
    {
        DB::beginTransaction();
        try {
            $comment = $this->commentService->update($request, $productId, $commentId);
            DB::commit();
            return $comment;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function delete($productId, $commentId)
    {
        DB::beginTransaction();
        try {
            $comment = $this->commentService->delete($productId, $commentId);
            DB::commit();
            return $comment;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function show($productId, $commentId)
    {
        try {
            return $this->commentService->show($productId, $commentId);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function list(GetListCommentRequest $request, $productId)
    {
        try {
            return $this->commentService->list($request, $productId);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }
}
