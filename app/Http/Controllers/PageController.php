<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Str;
use App\Models\Page;
use App\Exceptions\NotFoundException;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::all();

        return response()->json([
            'data' => $pages,
            'count' => $pages->count(),
        ]);
    }

    public function show(string $id)
    {
        $page = Page::find($id);

        if (!$page) {
            throw new NotFoundException('Page not found');
        }

        $page->load('files');

        return response()->json([
            'data' => $page,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string|json',
            'event_year_id' => 'required|exists:event_years,id',
        ]);

        $slug = Str::slug($validated['title']) . '-' . Str::random(5);

        $page = Page::create([
            ...$validated,
            'slug' => $slug,
        ]);

        return response()->json([
            'data' => $page,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $page = Page::find($id);

        if (!$page) {
            throw new NotFoundException('Page not found');
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string|json',
            'event_year_id' => 'required|exists:event_years,id',
        ]);

        $slug = $page->slug;

        if (isset($validated['title']) && $validated['title'] !== $page->title) {
            $slug = Str::slug($validated['title']) . '-' . Str::random(5);
        }

        $updated = $page->update([
            ...$validated,
            'slug' => $slug,
        ]);

        if (!$updated) {
            return response()->json([
                'message' => 'Failed to update page',
            ], 500);
        }

        return response()->json([
            'data' => $page,
        ]);
    }

    public function destroy(string $id)
    {
        $page = Page::find($id);

        if (!$page) {
            throw new NotFoundException('Page not found');
        }

        $deleted = $page->delete();

        if (!$deleted) {
            return response()->json([
                'message' => 'Failed to delete page',
            ], 500);
        }

        return response()->json([
            'message' => 'Page deleted successfully',
        ], 200);
    }
}
