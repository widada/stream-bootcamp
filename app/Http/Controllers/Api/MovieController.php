<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;

use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $movies = Movie::where('title', 'like', '%'.$search.'%')
            ->orderBy('featured', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return response()->json($movies);
    }

    public function show(Request $request, $id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json([
                'message' => 'movie not found'
            ], 404);
        }

        return response()->json($movie);
    }
}
