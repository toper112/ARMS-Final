<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(){

        $events = Event::orderByDesc('date')->get();
        return view('admin.events.index', compact('events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'remarks' => 'required|string|max:255',
        ]);
        Event::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'remarks' => $validated['remarks']
        ]);
        return redirect()->route('events.index')->with('message', 'Event created successfully.');
    }

    public function show($id)
        {

            $event = Event::findOrFail($id);
            return view('admin.events.display', compact('event'));
        }


    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->update($request->all());
        return redirect()->route('events.index')->with( 'message', 'Event updated successfully.');
    }
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
            $event->delete();
        return redirect()->route('events.index')->with('message', 'Event deleted successfully.');
    }
}
