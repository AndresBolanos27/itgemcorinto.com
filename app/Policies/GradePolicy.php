<?php

namespace App\Policies;

use App\Models\Grade;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GradePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher']);
    }

    public function view(User $user, Grade $grade): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'teacher') {
            // El docente solo puede ver las notas de las materias que imparte
            return $user->teacher->subjects->contains($grade->subject_id);
        }

        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher']);
    }

    public function update(User $user, Grade $grade): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'teacher') {
            // El docente solo puede actualizar las notas de las materias que imparte
            return $user->teacher->subjects->contains($grade->subject_id);
        }

        return false;
    }

    public function delete(User $user, Grade $grade): bool
    {
        // Solo los administradores pueden eliminar notas
        return $user->role === 'admin';
    }
}
