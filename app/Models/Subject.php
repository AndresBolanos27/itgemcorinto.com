<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Group;
use App\Models\Teacher;
use App\Models\AcademicLoad;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'estado'
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'academic_loads', 'subject_id', 'group_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'academic_loads', 'subject_id', 'teacher_id');
    }
    
    /**
     * Get the academic loads for the subject.
     */
    public function academicLoads()
    {
        return $this->hasMany(AcademicLoad::class);
    }
    
    /**
     * Get the teachers through academic loads.
     */
    public function teachersViaLoads()
    {
        return $this->belongsToMany(Teacher::class, 'academic_loads', 'subject_id', 'teacher_id')
                    ->where('academic_loads.estado', 'activo');
    }
    
    /**
     * Get the groups through academic loads.
     */
    public function groupsViaLoads()
    {
        return $this->belongsToMany(Group::class, 'academic_loads', 'subject_id', 'group_id')
                    ->where('academic_loads.estado', 'activo');
    }
}
