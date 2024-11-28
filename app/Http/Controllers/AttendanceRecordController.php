<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendanceRecordController extends Controller
{
     public function index()
    {
        // Fetch all events and the related attendance records for the logged-in user
        $events = Event::with(['attendanceRecords' => function($query) {
            $query->where('user_id', auth()->id()); // Only fetch attendance records for the logged-in user
        }])->orderBy('date')->get();

        // dd($events->first()->attendanceRecords);


        // Return the view with the events data
        return view('admin.attendance.index', compact('events'));
    }
}
