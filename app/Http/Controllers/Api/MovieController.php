<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\UserPremium;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;

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
        $user = $request->get('user');

        $userPremium = UserPremium::where('user_id', $user->id)->first();

        $movie = Redis::get('movie-'.$id);

        if (!$movie) {
            $movie = Movie::find($id);
            Redis::set('movie-'.$id, $movie);
        } else {
            $movie = json_decode($movie);
        }

        if (!$movie) {
            return response()->json([
                'message' => 'movie not found'
            ], 404);
        }

        if ($userPremium) {
            $endOfSubscription = $userPremium->end_of_subscription;
            $date = Carbon::createFromFormat('Y-m-d', $endOfSubscription);
            $isValidSubscription = $date->greaterThan(now());
            if ($isValidSubscription) {
                return response()->json($movie);
            }
        }

        return response()->json(['message' => 'you dint have subscription plan']);
    }
}
