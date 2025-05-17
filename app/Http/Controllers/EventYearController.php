<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundError;
use App\Models\EventYear;
use Illuminate\Http\Request;

class EventYearController extends Controller
{
    public function index()
    {
        $eventYears = EventYear::all();

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
            throw new NotFoundError('Event year not found');
        }

        $eventYear->load('pages');

        return response()->json([
            'data' => $eventYear,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $eventYear = EventYear::find($id);

        if (!$eventYear) {
            throw new NotFoundError('Event year not found');
        }

        $validated = $request->validate([
            'year' => 'nullable|integer',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
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
            throw new NotFoundError('Event year not found');
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

}
