<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function index() {
        // Retrieve paginated suggestions for the admin view
        $suggestions = Suggestion::paginate(10);
        return view('admin.suggestions.index', compact('suggestions'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'event_id' => 'required|exists:events,id',
        'context' => 'required|string|max:255',
        'dateSubmitted' => 'required|date',
    ]);

    Suggestion::create([
        'user_id' => $validated['user_id'],
        'event_id' => $validated['event_id'],
        'context' => $validated['context'],
        'dateSubmitted' => now(),
    ]);
    return redirect()->back()->with('message', 'Suggestion submitted successfully');
}
}
