<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\ManageCategoryRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category\Category;
use App\Services\Category\Contracts\CategoryServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoryController
 */
class CategoryController extends Controller
{
    /**
     * @var CategoryServiceInterface
     */
    private CategoryServiceInterface $categoryService;

    /**
     * @param CategoryServiceInterface $categoryService
     */
    public function __construct(CategoryServiceInterface $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return CategoryResource::collection($this->categoryService->getAllPaginated(pageSize: 500));
    }

    /**
     * @param ManageCategoryRequest $request
     * @return CategoryResource
     */
    public function store(ManageCategoryRequest $request): CategoryResource
    {
        return CategoryResource::make($this->categoryService->create($request->validated()));
    }

    /**
     * @param Category $category
     * @param ManageCategoryRequest $request
     * @return CategoryResource
     */
    public function update(Category $category, ManageCategoryRequest $request): CategoryResource
    {
        return CategoryResource::make($this->categoryService->update($category, $request->validated()));
    }

    /**
     * @throws Exception
     */
    public function destroy(Category $category): JsonResponse
    {
        $this->categoryService->delete($category);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
