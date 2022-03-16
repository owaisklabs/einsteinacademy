<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable ;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $guarded =[];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at'  => 'date:d-M-Y',
    ];
    const STUDENT = 'STUDENT';
    const TEACHER = 'TEACHER';

    const BLOCK = 'BLOCK';
    const UNBLOCK = 'UNBLOCK';
    public function setPasswordAttribute($pass)
    {
        $this->attributes['password'] = Hash::make($pass);
    }
    public function grade()
    {
        return $this->belongsTo(Grade::class,'grade_id');
    }
    public function subjects()
    {
        return $this->belongsToMany(Subject::class,'teacher_subjects','user_id','subject_id');
    }
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followes', 'user_id', 'follower_id');
    }
    public function followings()
    {
        return $this->belongsToMany(User::class, 'followes', 'follower_id', 'user_id');
    }
    public function studyMaterials()
    {
        return $this->hasMany(StudyMaterial::class,'user_id');
    }
    public function studyNotes()
    {
        return $this->hasMany(StudyNote::class,'user_id');
    }

    public static function isFollowed($follower_id){
        // return auth()->user()->id;
        $followed=false;
        $followed=Followe::where('follower_id',$follower_id)->where('user_id',auth()->user()->id)->first();
        if($followed!=null){
            return true;
        }
        return false;
    }

}
