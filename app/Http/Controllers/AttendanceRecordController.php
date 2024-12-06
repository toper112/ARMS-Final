<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceRecordController extends Controller
{
     public function index()
    {
        $user = auth()->user();
        // Fetch all events and the related attendance records for the logged-in user
        $events = Event::with(['attendanceRecords' => function($query) {
            $query->where('user_id', auth()->id()); // Only fetch attendance records for the logged-in user
        }])->orderBy('date')->get();

        return view('admin.attendance.index', compact('events','user'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        // Fetch all events and the related attendance records for the specified user
        $events = Event::with(['attendanceRecords' => function ($query) use ($id) {
            $query->where('user_id', $id); // Filter by the specified user ID
        }])->orderBy('date')->get();

        return view('admin.attendance.index', compact('events', 'user'));
    }

    public function paid($id)
{
    // Get all events
    $events = Event::all();
    $currentDate = now()->toDateString();

    // Iterate through each event to ensure attendance exists for the user
    foreach ($events as $event) {
        // Check if attendance exists for the user in this event
        $attendance = AttendanceRecord::where('user_id', $id)
                                      ->where('event_id', $event->id)
                                      ->first();

        // If attendance record does not exist, create a new one
        if (!$attendance) {
            AttendanceRecord::create([
                'user_id' => $id,
                'event_id' => $event->id,
                'date' => $currentDate,
                'morning_time_in' => null,
                'morning_time_out' => null,
                'afternoon_time_in' => null,
                'afternoon_time_out' => null,
                'remarks' => 'paid', // Mark as paid
            ]);
        } else {
            // If the attendance exists, update the remarks to 'paid' and set the time fields to null
            $attendance->remarks = 'paid';
            $attendance->save();
        }
    }

    // Redirect with a success message
    return redirect()->back()->with('message', 'Attendance fines have been paid!');
}


    public function destroy($id)
    {
        // Delete all attendance records for the specified user
        $deletedRows = AttendanceRecord::where('user_id', $id)->delete();

        // Optionally add a message for confirmation
        return redirect()->route('admin.users.index')
            ->with('message', 'All attendance records have been cleared.');
    }


}
