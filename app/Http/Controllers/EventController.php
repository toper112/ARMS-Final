<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Event;
use App\Models\User;
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
            'fines' => $validated['fines'],
            'remarks' => 'disable' //Default value

        ]);
        return redirect()->route('events.index')->with('message', 'Event created successfully.');
    }

    // EventController.php
public function show($id, Request $request)
{
    // Start the user query, excluding 'admin'
    $query = User::where('first_name', '!=', 'admin');
    $years = User::distinct()->whereNot('year', '1')->orderBy('year')->pluck('year');

    // Check if 'year' filter is present in the request and apply it
    if ($request->has('year') && $request->year != '') {
        $query->where('year', $request->year);
    }

    // Get the total number of users after applying filters
    $totalUsers = $query->count();

    // Retrieve the event by its ID
    $event = Event::findOrFail($id);

    // Filter attendance records based on the selected year
    $attendanceQuery = AttendanceRecord::where('event_id', $id);

    if ($request->has('year') && $request->year != '') {
        $attendanceQuery->whereHas('user', function ($query) use ($request) {
            $query->where('year', $request->year);
        });
    }

    // Fetch attendance counts for morning and afternoon sessions
    $morningTimeInAttendanceCount = (clone $attendanceQuery)
        ->whereNotNull('morning_time_in')
        ->count();
    $morningTimeOutAttendanceCount = (clone $attendanceQuery)
        ->whereNotNull('morning_time_out')
        ->count();
    $afternoonTimeInAttendanceCount = (clone $attendanceQuery)
        ->whereNotNull('afternoon_time_in')
        ->count();
    $afternoonTimeOutAttendanceCount = (clone $attendanceQuery)
        ->whereNotNull('afternoon_time_out')
        ->count();

    // Return the view with the data
    return view('admin.events.display', compact(
        'event', 'totalUsers',
        'morningTimeInAttendanceCount', 'morningTimeOutAttendanceCount',
        'afternoonTimeInAttendanceCount', 'afternoonTimeOutAttendanceCount',
        'years'
    ));
}





    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->update($request->all());
        return redirect()->route('events.index')->with( 'message', 'Event updated successfully.');
    }

    public function updateRemarks(Request $request, $id)
    {

        // Validate the request data (if necessary)
        $request->validate([
            'remarks' => 'required|string|in:enable,disable', // Example: remarks can only be 'enable' or 'disable'
        ]);

        // Find the event or fail if not found
        $event = Event::findOrFail($id);

        // Update the remarks
        $event->update($request->only('remarks'));

        // Set a dynamic success message
        $message = $event->remarks == 'enable'
            ? 'Event enabled scanning successfully.'
            : 'Event disabled scanning successfully.';

        // Redirect with the success message
        return back()->with('message', $message);

    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
            $event->delete();
        return redirect()->route('events.index')->with('message', 'Event deleted successfully.');
    }
}
