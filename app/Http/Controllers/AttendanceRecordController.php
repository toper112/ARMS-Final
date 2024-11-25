<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceRecordController extends Controller
{
    public function index()
    {
        return view('admin.attendance.index');
        }
}
