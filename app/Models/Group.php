<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'estado'
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'academic_loads', 'group_id', 'subject_id');
    }

    /**
     * Get the academic loads for the group.
     */
    public function academicLoads()
    {
        return $this->hasMany(AcademicLoad::class);
    }
    
    /**
     * Get the teachers through academic loads.
     */
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'academic_loads', 'group_id', 'teacher_id');
    }
    
    /**
     * Get the subjects through academic loads.
     */
    public function subjectsViaLoads()
    {
        return $this->belongsToMany(Subject::class, 'academic_loads', 'group_id', 'subject_id')
                    ->where('academic_loads.estado', 'activo');
    }
}
