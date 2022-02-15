<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Http\Request;

class InitController extends Controller
{
    public function getData()
    {
        $grade = Grade::all();
        $subject = Subject::all();

        return [
            'grade'=>$grade,
            'subject'=>$subject
        ];
    }
}
