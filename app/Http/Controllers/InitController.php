<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\Syllabus;
use Illuminate\Http\Request;

class InitController extends Controller
{
    public function getData()
    {
        $grade = Grade::all();
        $subject = Subject::all();
        $sallybus =Syllabus::all();

        return [
            'grade'=>$grade,
            'subject'=>$subject,
            'syllabus'=>$sallybus
        ];
    }
}
