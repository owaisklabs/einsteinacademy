<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyMaterialMedia extends Model
{
    use HasFactory;

    public function studyMaterial()
    {
        return  $this->belongsTo(StudyMaterial::class,'study_material_id');
    }
}
