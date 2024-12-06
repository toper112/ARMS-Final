<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
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
            'fines' => 'required|integer'
        ]);
        Event::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'date' => $validated['date'],
            'fines' => $validated['fines']
        ]);
        return redirect()->route('events.index')->with('message', 'Event created successfully.');
    }

    // EventController.php
public function show($id)
{
    $event = Event::findOrFail($id);

    // Fetch attendance counts for morning and afternoon sessions
    $morningTimeInAttendanceCount = AttendanceRecord::where('event_id', $id)
        ->whereNotNull('morning_time_in')
        ->count();
    $morningTimeOutAttendanceCount = AttendanceRecord::where('event_id', $id)
        ->whereNotNull('morning_time_out')
        ->count();

    $afternoonTimeInAttendanceCount = AttendanceRecord::where('event_id', $id)
        ->whereNotNull('afternoon_time_in')
        ->count();
    $afternoonTimeOutAttendanceCount = AttendanceRecord::where('event_id', $id)
        ->whereNotNull('afternoon_time_out')
        ->count();

    return view('admin.events.display', compact('event', 'morningTimeInAttendanceCount','morningTimeOutAttendanceCount', 'afternoonTimeInAttendanceCount','afternoonTimeOutAttendanceCount'));
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
