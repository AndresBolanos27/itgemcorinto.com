<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Student;
use App\Models\Group;
use App\Models\Subject;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $teachersCount = Teacher::count();
        $studentsCount = Student::count();
        $groupsCount = Group::count();
        $subjectsCount = Subject::count();

        return view('admin.dashboard', compact(
            'teachersCount',
            'studentsCount',
            'groupsCount',
            'subjectsCount'
        ));
    }
}
