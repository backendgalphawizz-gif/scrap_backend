<?php

namespace App\Http\Controllers;

use App\Models\BrandCategory;
use App\Models\BrandFeedbackQuestion;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackQuestionController extends Controller
{
    public function index(Request $request)
    {
        $categories = BrandCategory::where('status', 1)->orderBy('name')->get();

        $questions = BrandFeedbackQuestion::with('category:id,name')
            ->where('brand_id', 0)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);
                $query->where('question', 'like', "%{$search}%");
            })
            ->when($request->filled('brand_category_id'), function ($query) use ($request) {
                $query->where('brand_category_id', $request->brand_category_id);
            })
            ->orderBy('id', 'DESC')
            ->paginate(10)
            ->withQueryString();

        return view('admin-views.feedback-questions.index', compact('questions', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_category_id' => 'required|exists:brand_categories,id',
            'question' => 'required|string|max:1000',
            'question_type' => 'required|in:multiple_choice,input',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:255',
            'status' => 'nullable|in:0,1',
        ]);

        $options = collect($request->input('options', []))
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->values()
            ->all();

        if ($validated['question_type'] === 'multiple_choice' && count($options) < 2) {
            return redirect()->back()->withErrors(['options' => 'At least 2 valid options are required.'])->withInput();
        }

        BrandFeedbackQuestion::create([
            'brand_id' => 0,
            'brand_category_id' => (int) $validated['brand_category_id'],
            'question' => trim($validated['question']),
            'question_type' => $validated['question_type'],
            'options' => $validated['question_type'] === 'multiple_choice' ? $options : [],
            'status' => (bool) $request->input('status', 1),
        ]);

        return redirect()->back()->with('success', 'Feedback question created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'brand_category_id' => 'required|exists:brand_categories,id',
            'question' => 'required|string|max:1000',
            'question_type' => 'required|in:multiple_choice,input',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:255',
            'status' => 'nullable|in:0,1',
        ]);

        $options = collect($request->input('options', []))
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->values()
            ->all();

        if ($validated['question_type'] === 'multiple_choice' && count($options) < 2) {
            return redirect()->back()->withErrors(['options' => 'At least 2 valid options are required.'])->withInput();
        }

        $question = BrandFeedbackQuestion::where('brand_id', 0)->findOrFail($id);

        $question->update([
            'brand_category_id' => (int) $validated['brand_category_id'],
            'question' => trim($validated['question']),
            'question_type' => $validated['question_type'],
            'options' => $validated['question_type'] === 'multiple_choice' ? $options : [],
            'status' => (bool) $request->input('status', 1),
        ]);

        return redirect()->back()->with('success', 'Feedback question updated successfully.');
    }

    public function destroy($id)
    {
        $question = BrandFeedbackQuestion::where('brand_id', 0)->findOrFail($id);
        $question->delete();

        return redirect()->back()->with('success', 'Feedback question deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $question = BrandFeedbackQuestion::where('brand_id', 0)->findOrFail($id);
        $question->status = !$question->status;
        $question->save();

        return redirect()->back()->with('success', 'Feedback question status updated successfully.');
    }

    public function feedbackList(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $rating = (string) $request->input('rating', '');
        $limit = (int) $request->input('limit', 20);
        $limit = $limit > 0 ? $limit : 20;

        $feedbacks = Feedback::with(['user', 'brand', 'campaign'])
            ->when($rating !== '', function ($query) use ($rating) {
                $query->where('ratings', $rating);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('user_feedback', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('campaign', function ($cq) use ($search) {
                            $cq->where('title', 'like', "%{$search}%");
                        })
                        ->orWhereHas('brand', function ($bq) use ($search) {
                            $bq->where('username', 'like', "%{$search}%")
                                ->orWhereRaw('CONCAT(f_name, " ", l_name) like ?', ["%{$search}%"]);
                        });
                });
            })
            ->latest()
            ->paginate($limit)
            ->withQueryString();

        return view('admin-views.feedback-questions.feedback-list', compact('feedbacks', 'search', 'rating', 'limit'));
    }
}
