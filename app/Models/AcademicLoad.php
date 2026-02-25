<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Group;

class AcademicLoad extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'group_id',
        'estado',
        'periodo',
        'observaciones'
    ];

    /**
     * Get the teacher associated with the academic load.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the subject associated with the academic load.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the group associated with the academic load.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
