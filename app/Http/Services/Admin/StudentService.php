<?php

namespace App\Http\Services\Admin;

use App\Http\Resources\UserResource;
use App\Models\User;

class StudentService
{

    public function getStudents()
    {
        $students = User::where('role','student')->get();
        return UserResource::collection($students);
    }


}
