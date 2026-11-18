<?php

namespace App\Contracts;

interface ProfileRepository
{
    public function updateProfile($profile, array $data);

    public function getOwnDepartment($id);

    public function getDepartmentEmployees($employee);
}
