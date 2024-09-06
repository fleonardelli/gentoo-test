<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Services\ApiService\CommentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends BaseController
{
    public function index(Request $request, CommentService $commentService): JsonResponse
    {
        try {
            $queryParams = $request->attributes->get('queryParams');

            $result = $commentService->getItems(
                pagination: $queryParams->getPaginationParams(),
                filtering: $queryParams->getFiltering(),
                sorting: $queryParams->getSorting(),
                relations: $queryParams->getRelations()
            );

            return $this->ok($result);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $comment = Comment::findOrFail($id);
            $comment->delete();

            return response()->json(null, 204);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Comment not found.'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the comment.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
