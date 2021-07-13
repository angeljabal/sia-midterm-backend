<?php

namespace App\Http\Controllers;
use App\Models\Movie;
use Exception;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function show(Movie $movie) {
        return response()->json($movie,200);
    }

    public function search(Request $request) {
        $request->validate(['key'=>'string|required']);

        $movies = Movie::where('title','like',"%$request->key%")
            ->orWhere('description','like',"%$request->key%")->get();

        return response()->json($movies, 200);
    }

    public function store(Request $request){
        $request->validate([
            'title'         => 'required',
            'description'   => 'required',
            'genre'         => 'required',
            'year'          => 'required|numeric',
            'status'        => 'required',
        ]);

        try {
            $movie = auth('api')->user()->movies()->create($request->all());
            return response()->json($movie, 202);
        }catch(Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ],500);
        }
    }

    public function update(Request $request, Movie $movie) {
        try {
            $movie->update($request->all());
            return response()->json($movie, 202);
        }catch(Exception $ex) {
            return response()->json(['message'=>$ex->getMessage()], 500);
        }
    }

    public function destroy(Movie $movie) {
        $movie->delete();
        return response()->json(['message'=>'Movie deleted.'],202);
    }
    
    public function index() {
        $movies = auth()->user()->movies;
        return response()->json($movies, 200);
    }
}
