<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        try {
            Log::info('Attempting to fetch comics in admin');
            $comics = Comic::with('user')
                         ->orderBy('created_at', 'desc')
                         ->get();
            Log::info('Successfully fetched comics', ['count' => $comics->count()]);
            return response()->json($comics);
        } catch (\Exception $e) {
            Log::error('Error fetching comics in admin: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Error fetching comics', 'error' => $e->getMessage()], 500);
        }
    }

    public function stats()
    {
        try {
            Log::info('Attempting to fetch admin stats');
            $stats = DB::transaction(function () {
                $total_comics = Comic::count();
                $total_users = User::count();
                $published_comics = Comic::where('status', 'published')->count();

                Log::info('Stats counts:', [
                    'totalComics' => $total_comics,
                    'totalUsers' => $total_users,
                    'publishedComics' => $published_comics
                ]);

                return [
                    'totalComics' => $total_comics,
                    'totalUsers' => $total_users,
                    'publishedComics' => $published_comics
                ];
            });

            Log::info('Successfully fetched admin stats', $stats);
            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Error fetching admin stats: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Error fetching stats', 'error' => $e->getMessage()], 500);
        }
    }

    public function storeComic(Request $request)
    {
        try {
            Log::info('Attempting to create comic', $request->all());
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'author' => 'required|string|max:255',
                'genre' => 'required|string|max:255',
                'status' => 'required|in:draft,published',
                'image' => 'nullable|string',
                'featured' => 'boolean',
                'price' => 'numeric|min:0'
            ]);

            $comic = DB::transaction(function () use ($validatedData, $request) {
                return Comic::create([
                    ...$validatedData,
                    'user_id' => auth()->id(),
                    'featured' => $request->featured ?? false,
                    'price' => $request->price ?? 0.00
                ]);
            });

            Log::info('Comic created by admin', [
                'comic_id' => $comic->id,
                'admin_id' => auth()->id()
            ]);

            return response()->json($comic, 201);
        } catch (\Exception $e) {
            Log::error('Error creating comic in admin: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Error creating comic',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateComic(Request $request, Comic $comic)
    {
        try {
            Log::info('Attempting to update comic', ['comic_id' => $comic->id, 'data' => $request->all()]);
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'author' => 'required|string|max:255',
                'genre' => 'required|string|max:255',
                'status' => 'required|in:draft,published',
                'image' => 'nullable|string',
                'featured' => 'boolean',
                'price' => 'numeric|min:0'
            ]);

            DB::transaction(function () use ($comic, $request) {
                $comic->title = $request->title;
                $comic->description = $request->description;
                $comic->author = $request->author;
                $comic->genre = $request->genre;
                $comic->status = $request->status;
                $comic->featured = $request->featured ?? false;
                $comic->price = $request->price; // setPriceAttribute will be called now

                $comic->save();
            });

            Log::info('Comic updated by admin', [
                'comic_id' => $comic->id,
                'admin_id' => auth()->id()
            ]);

            return response()->json($comic->fresh());
        } catch (\Exception $e) {
            Log::error('Error updating comic in admin: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Error updating comic',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteComic(Comic $comic)
    {
        try {
            Log::info('Attempting to delete comic', ['comic_id' => $comic->id]);
            $comicId = $comic->id;

            DB::transaction(function () use ($comic) {
                $comic->delete();
            });

            Log::info('Comic deleted by admin', [
                'comic_id' => $comicId,
                'admin_id' => auth()->id()
            ]);

            return response()->json(['message' => 'Comic deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting comic in admin: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Error deleting comic',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function users()
    {
        try {
            Log::info('Attempting to fetch users');
            $users = User::withCount('comics')
                        ->orderBy('created_at', 'desc')
                        ->get();
            Log::info('Successfully fetched users', ['count' => $users->count()]);
            return response()->json($users);
        } catch (\Exception $e) {
            Log::error('Error fetching users in admin: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Error fetching users'], 500);
        }
    }
}