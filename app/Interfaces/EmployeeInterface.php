<?php

namespace App\Interfaces;

interface EmployeeInterface
{
    public function getAllEmployees();
    public function createUser($data);
    public function createEmployee($data);
    public function createVaccine($data);
    public function updateEmployee($id, $data);
    public function updateVaccine($id, $data);
    public function updateUser($id, $data);
    public function deleteEmployee($id, $data);
    public function getEmployeeId($userId);
    public function getEmployee($userId);
    public function getVaccine($id);
    public function getEmployeeById($id);
    public function getEmployeeNameById($id);
    public function getEmployeePicture($id);
    public function getEmployeeAge($date_of_birth);
    public function getProfilEmployee();
}
