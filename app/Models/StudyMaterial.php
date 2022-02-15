<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class StudyMaterial extends Model
{
    use HasFactory;

    public function Medias()
    {
        return  $this->hasMany(StudyMaterialMedia::class,'study_material_id');
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
}
