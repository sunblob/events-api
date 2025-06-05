<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Models\EventYear;
use Illuminate\Http\Request;

class EventYearController extends Controller
{
    public function index()
    {
        $eventYears = EventYear::orderBy('year', 'desc')->get();

        return response()->json([
            'data' => $eventYears,
            'count' => $eventYears->count(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|unique:event_years,year',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'editor_id' => 'nullable|exists:users,id',
        ]);

        $eventYear = EventYear::create($validated);

        return response()->json([
            'data' => $eventYear,
        ]);
    }

    public function show(string $id)
    {
        $eventYear = EventYear::find($id);

        if (!$eventYear) {
            throw new NotFoundException('Event year not found');
        }

        $eventYear->load(['pages', 'users']);

        return response()->json([
            'data' => $eventYear,
        ]);
    }

    public function showByYear(string $year)
    {
        $eventYear = EventYear::where('year', $year)->first();

        if (!$eventYear) {
            throw new NotFoundException('Event year not found');
        }

        $eventYear->load(['pages', 'users']);

        return response()->json([
            'data' => $eventYear,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $eventYear = EventYear::find($id);

        if (!$eventYear) {
            throw new NotFoundException('Event year not found');
        }

        $validated = $request->validate([
            'year' => 'nullable|integer',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'editor_id' => 'nullable|exists:users,id',
        ]);

        $updated = $eventYear->update($validated);

        if (!$updated) {
            return response()->json([
                'message' => 'Failed to update event year',
            ], 500);
        }

        return response()->json([
            'data' => $eventYear,
        ]);
    }

    public function destroy(string $id)
    {
        $eventYear = EventYear::find($id);

        if (!$eventYear) {
            throw new NotFoundException('Event year not found');
        }

        $deleted = $eventYear->delete();

        if (!$deleted) {
            return response()->json([
                'message' => 'Failed to delete event year',
            ], 500);
        }

        return response()->json([
            'message' => 'Event year deleted successfully',
        ]);
    }

    public function addUserToEventYear(string $id, string $userId)
    {
        $eventYear = EventYear::find($id);

        if (!$eventYear) {
            throw new NotFoundException('Event year not found');
        }

        $eventYear->users()->attach($userId);

        $eventYear->load('users');

        return response()->json([
            'data' => $eventYear,
        ]);
    }

    public function removeUserFromEventYear(string $id, string $userId)
    {
        $eventYear = EventYear::find($id);

        if (!$eventYear) {
            throw new NotFoundException('Event year not found');
        }

        $eventYear->users()->detach($userId);

        $eventYear->load('users');

        return response()->json([
            'data' => $eventYear,
        ]);
    }

    public function removeEditor(string $id)
    {
        $eventYear = EventYear::find($id);

        if (!$eventYear) {
            throw new NotFoundException('Event year not found');
        }

        $eventYear->editor_id = null;
        $eventYear->save();

        return response()->json([
            'data' => $eventYear,
        ]);
    }

    public function getEditorEvents(string $id)
    {
        $events = EventYear::where('editor_id', $id)
            ->orderBy('year', 'desc')
            ->get();

        return response()->json([
            'data' => $events,
            'count' => $events->count(),
        ]);
    }
}
