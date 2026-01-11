<?php

namespace App\Http\Controllers\Api;

use App\Models\Exercise;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="API Endpoints for Categories"
 * )
 */
class CategoryController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Get all categories",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="List of categories with exercise counts"
     *     )
     * )
     */
    public function index()
    {
        // Get unique categories from exercises with their counts
        $categories = Exercise::selectRaw('category, COUNT(*) as exercise_count')
            ->groupBy('category')
            ->orderBy('category')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->category,
                    'exercise_count' => $item->exercise_count,
                    'icon' => $this->getCategoryIcon($item->category),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Get icon name for category (can be customized)
     */
    private function getCategoryIcon(string $category): string
    {
        $icons = [
            'arrays' => 'data_array',
            'strings' => 'text_fields',
            'loops' => 'loop',
            'functions' => 'functions',
            'algorithms' => 'psychology',
            'data structures' => 'account_tree',
            'math' => 'calculate',
            'recursion' => 'autorenew',
            'sorting' => 'sort',
            'searching' => 'search',
            'bit manipulation' => 'memory',
            'dynamic programming' => 'trending_up',
            'trees' => 'park',
            'graphs' => 'hub',
            'greedy' => 'speed',
            'backtracking' => 'undo',
            'hash tables' => 'tag',
            'heaps' => 'stacked_bar_chart',
            'queues' => 'queue',
            'design' => 'architecture',
        ];

        return $icons[strtolower($category)] ?? 'code';
    }
}
