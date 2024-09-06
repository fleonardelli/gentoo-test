<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\ApiService\PostService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends BaseController
{
    public function index(Request $request, PostService $postService): JsonResponse
    {
        try {
            $queryParams = $request->attributes->get('queryParams');

            $result = $postService->getItems(
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
        // depending on the coding standards, this can be also placed in the service to have controllers with the less
        // business logic possible.
        try {
            $post = Post::findOrFail($id);
            // This performs a hard delete. Depending on each case, a soft delete might be adequate.
            $post->delete();

            return response()->json(null, 204);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Post not found.'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            // log error here, and then return a client friendly error.
            return response()->json(['error' => 'An error occurred while deleting the post.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
