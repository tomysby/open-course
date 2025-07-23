<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Course $course)
    {
        return $course->is_published || $user->isAdmin();
    }

    public function create(User $user)
    {
        return $user->isMember() || $user->isAdmin();
    }

    public function update(User $user, Course $course)
    {
        return $user->isAdmin() || $course->user_id === $user->id;
    }

    public function delete(User $user, Course $course)
    {
        return $user->isAdmin();
    }
}
