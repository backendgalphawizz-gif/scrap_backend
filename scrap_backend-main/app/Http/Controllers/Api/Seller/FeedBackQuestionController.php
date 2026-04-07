<?php

namespace App\Http\Controllers\Api\Seller;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\CPU\ProductManager;
use App\Http\Controllers\Controller;

use App\Models\BrandFeedbackQuestion;
use App\Models\Seller;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function App\CPU\translate;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CommonResource;



class FeedbackQuestionController extends Controller
{
    public function index(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
            $feedbackQuestions = BrandFeedbackQuestion::where('brand_id', $seller['id'])->get();
            $response = [
                'status' => true,
                'message' => 'Seller profile',
                'data' => CommonResource::collection($feedbackQuestions)
            ];
        }

        return response()->json($response, 200);
    }
    
    public function store(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] != 1) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $question = BrandFeedbackQuestion::create([
            'brand_id' => $data['data']['id'],
            'question' => $request->question,
        ]);

        return response()->json(['status' => true, 'message' => 'Question created', 'data' => $question], 201);
    }

    public function show($id)
    {
        $question = BrandFeedbackQuestion::find($id);
        
        if (!$question) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $question], 200);
    }

    public function update(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] != 1) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        $question = BrandFeedbackQuestion::find($id);
        if (!$question) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }

        $question->update($request->only(['question']));

        return response()->json(['status' => true, 'message' => 'Updated successfully', 'data' => $question], 200);
    }

    public function destroy(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] != 1) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        $question = BrandFeedbackQuestion::find($id);
        if (!$question) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }

        $question->delete();

        return response()->json(['status' => true, 'message' => 'Deleted successfully'], 200);
    }

}
