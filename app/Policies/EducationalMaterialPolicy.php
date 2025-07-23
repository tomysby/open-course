<?php

namespace App\Policies;

use App\Models\EducationalMaterial;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EducationalMaterialPolicy
{
    public function review(User $user)
    {
        return $user->isAdmin();
    }

    public function delete(User $user, EducationalMaterial $material)
    {
        return $user->isAdmin() || $user->id === $material->user_id;
    }
}