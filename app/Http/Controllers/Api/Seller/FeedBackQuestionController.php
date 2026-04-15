<?php

namespace App\Http\Controllers\Api\Seller;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;

use App\Models\BrandFeedbackQuestion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CommonResource;



class FeedbackQuestionController extends Controller
{
    private function normalizeOptions($rawOptions): array
    {
        if (is_string($rawOptions)) {
            $trimmed = trim($rawOptions);
            if ($trimmed === '') {
                return [];
            }

            // Accept JSON string in form-data: "[\"Yes\",\"No\"]"
            $decoded = json_decode($trimmed, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $rawOptions = $decoded;
            } else {
                // Fallback: comma-separated list
                $rawOptions = explode(',', $trimmed);
            }
        }

        if (!is_array($rawOptions)) {
            return [];
        }

        return collect($rawOptions)
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->values()
            ->all();
    }

    public function index(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] != 1) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        $seller = $data['data'];
        $limit = (int) $request->input('limit', 20);
        $search = trim((string) $request->input('search', ''));

        $questions = BrandFeedbackQuestion::with('category:id,name')
            ->where('brand_id', 0)
            ->when($request->filled('brand_category_id'), function ($query) use ($request) {
                $query->where('brand_category_id', $request->brand_category_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', (int) $request->status);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where('question', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($limit > 0 ? $limit : 20);

        return response()->json([
            'status' => true,
            'message' => 'Feedback questions retrieved successfully',
            'data' => CommonResource::collection($questions),
            'meta' => [
                'current_page' => $questions->currentPage(),
                'last_page' => $questions->lastPage(),
                'per_page' => $questions->perPage(),
                'total' => $questions->total(),
            ]
        ], 200);
    }
    
    public function store(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] != 1) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        $seller = $data['data'];

        // Backward compatible defaults for old clients using only question text.
        if (!$request->has('question_type')) {
            $request->merge(['question_type' => 'multiple_choice']);
        }
        if (!$request->has('status')) {
            $request->merge(['status' => 1]);
        }
        if (!$request->filled('brand_category_id') && !empty($seller->category_id)) {
            $request->merge(['brand_category_id' => $seller->category_id]);
        }

        $validator = Validator::make($request->all(), [
            'brand_category_id' => 'nullable|exists:brand_categories,id',
            'question' => 'required|string|max:1000',
            'question_type' => 'nullable|in:multiple_choice,input',
            'options' => 'nullable',
            'status' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $questionType = $request->input('question_type', 'multiple_choice');
        $options = $this->normalizeOptions($request->input('options', []));

        if ($questionType === 'multiple_choice' && count($options) < 2) {
            return response()->json([
                'status' => false,
                'message' => 'At least 2 options are required for multiple choice questions.'
            ], 422);
        }

        if (!$request->filled('brand_category_id')) {
            return response()->json([
                'status' => false,
                'message' => 'brand_category_id is required. Please select a category from brand/brand-category-list.'
            ], 422);
        }

        $question = BrandFeedbackQuestion::create([
            'brand_id' => 0,
            'brand_category_id' => (int) $request->brand_category_id,
            'question' => trim($request->question),
            'question_type' => $questionType,
            'options' => $questionType === 'multiple_choice' ? $options : [],
            'status' => (bool) $request->input('status', 1),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Question created',
            'data' => new CommonResource($question->load('category:id,name'))
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] != 1) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        $question = BrandFeedbackQuestion::with('category:id,name')
            ->where('brand_id', 0)
            ->find($id);

        if (!$question) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }

        return response()->json(['status' => true, 'data' => new CommonResource($question)], 200);
    }

    public function update(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] != 1) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        // Backward compatible defaults.
        if (!$request->has('question_type')) {
            $request->merge(['question_type' => 'multiple_choice']);
        }
        if (!$request->has('status')) {
            $request->merge(['status' => 1]);
        }

        $validator = Validator::make($request->all(), [
            'brand_category_id' => 'nullable|exists:brand_categories,id',
            'question' => 'required|string|max:1000',
            'question_type' => 'nullable|in:multiple_choice,input',
            'options' => 'nullable',
            'status' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $question = BrandFeedbackQuestion::where('brand_id', 0)->find($id);
        if (!$question) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }

        $questionType = $request->input('question_type', 'multiple_choice');
        $options = $this->normalizeOptions($request->input('options', []));

        if ($questionType === 'multiple_choice' && count($options) < 2) {
            return response()->json([
                'status' => false,
                'message' => 'At least 2 options are required for multiple choice questions.'
            ], 422);
        }

        $brandCategoryId = $request->filled('brand_category_id')
            ? (int) $request->brand_category_id
            : (int) $question->brand_category_id;

        if (!$brandCategoryId) {
            return response()->json([
                'status' => false,
                'message' => 'brand_category_id is required. Please select a category from brand/brand-category-list.'
            ], 422);
        }

        $question->update([
            'brand_category_id' => $brandCategoryId,
            'question' => trim($request->question),
            'question_type' => $questionType,
            'options' => $questionType === 'multiple_choice' ? $options : [],
            'status' => (bool) $request->input('status', $question->status),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Updated successfully',
            'data' => new CommonResource($question->load('category:id,name'))
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] != 1) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        $question = BrandFeedbackQuestion::where('brand_id', 0)->find($id);
        if (!$question) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }

        $question->delete();

        return response()->json(['status' => true, 'message' => 'Deleted successfully'], 200);
    }

}
