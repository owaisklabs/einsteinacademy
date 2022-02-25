<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PastPaper extends Model
{
    use HasFactory;
    public function Medias()
    {
        return  $this->hasMany(PastPaperMedia::class,'past_paper_id');
    }
    public function grade()
    {
        return  $this->belongsTo(Grade::class,'grade_id');
    }
    public function subject()
    {
        return  $this->belongsTo(Subject::class,'subject_id');
    }
}
