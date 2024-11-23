<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Suggestion;
use App\Models\User;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function index() {
        $events = Event::orderByDesc('date')->get();
        $suggestions = Suggestion::paginate(10);
        return view('admin.suggestions.index', compact('suggestions', 'events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'context' => 'required|string|max:255',
        ]);

        Suggestion::create([
            'user_id' => auth()->id(),
            'event_id' => $validated['event_id'],
            'context' => $validated['context'],
            'dateSubmitted' => now(),
        ]);
        return redirect()->back()->with('message', 'Suggestion submitted successfully');
    }
}
