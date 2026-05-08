<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product', 'order']);

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$s}%"))
                  ->orWhereHas('product', fn($p) => $p->where('name', 'like', "%{$s}%"))
                  ->orWhere('comment', 'like', "%{$s}%");
            });
        }

        $reviews = $query->latest()->paginate(15);

        // Stats
        $totalReviews = Review::count();
        $avgRating = Review::avg('rating') ?? 0;
        $fiveStars = Review::where('rating', 5)->count();

        return view('admin.reviews', compact('reviews', 'totalReviews', 'avgRating', 'fiveStars'));
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        return redirect()->back()->with('success', '✅ Review berhasil dihapus.');
    }
}
