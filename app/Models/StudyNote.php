<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyNote extends Model
{
    use HasFactory;
    public function Medias()
    {
        return  $this->hasMany(StudyNotesMedia::class,'study_notes_id');
    }
    public function grade()
    {
        return  $this->belongsTo(Grade::class,'grade_id');
    }
    public function subject()
    {
        return  $this->belongsTo(Subject::class,'subject_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function rating(){
        return $this->hasMany(StudyNotesRating::class,'study_notes_id');
    }
}
