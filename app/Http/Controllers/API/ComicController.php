<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ComicController extends Controller
{
    public function index()
    {
        try {
            $comics = Comic::with('user')->latest()->get();
            return response()->json($comics);
        } catch (\Exception $e) {
            Log::error('Error fetching comics: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching comics'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'author' => 'required|string|max:255',
                'genre' => 'required|string|max:255',
                'image' => 'nullable|string'
            ]);

            $comic = Comic::create([
                ...$validatedData,
                'user_id' => auth()->id()
            ]);

            return response()->json($comic, 201);
        } catch (\Exception $e) {
            Log::error('Error creating comic: ' . $e->getMessage());
            return response()->json(['message' => 'Error creating comic'], 500);
        }
    }

    public function show(Comic $comic)
    {
        try {
            return response()->json($comic->load('user'));
        } catch (\Exception $e) {
            Log::error('Error fetching comic: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching comic'], 500);
        }
    }

    public function update(Request $request, Comic $comic)
    {
        try {
            // Check if user owns the comic
            if ($comic->user_id !== auth()->id()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'author' => 'required|string|max:255',
                'genre' => 'required|string|max:255',
                'image' => 'nullable|string'
            ]);

            $comic->update($validatedData);

            return response()->json($comic);
        } catch (\Exception $e) {
            Log::error('Error updating comic: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating comic'], 500);
        }
    }

    public function destroy(Comic $comic)
    {
        try {
            // Check if user owns the comic
            if ($comic->user_id !== auth()->id()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $comic->delete();
            return response()->json(['message' => 'Comic deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting comic: ' . $e->getMessage());
            return response()->json(['message' => 'Error deleting comic'], 500);
        }
    }

    public function userComics()
    {
        try {
            $comics = Comic::where('user_id', auth()->id())->latest()->get();
            return response()->json($comics);
        } catch (\Exception $e) {
            Log::error('Error fetching user comics: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching user comics'], 500);
        }
    }
}