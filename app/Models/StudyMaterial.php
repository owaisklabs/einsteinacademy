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
}
