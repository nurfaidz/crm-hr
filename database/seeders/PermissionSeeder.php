<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'dashboard.superadmin',
            'master_company.list',
            'master_company.create',
            'master_company.edit',
            'master_company.delete',
            'role_permission.list',
            'role_permission.create',
            'role_permission.edit',
            'role_permission.delete',
            'branch_company.list',
            'branch_company.create',
            'branch_company.edit',
            'branch_company.delete',
            'department_company.list',
            'department_company.create',
            'department_company.edit',
            'department_company.delete',
            'job_position.list',
            'job_position.create',
            'job_position.edit',
            'job_position.delete',
            'job_class.list',
            'job_class.create',
            'job_class.edit',
            'job_class.delete',
            'manage_holiday.list',
            'manage_holiday.create',
            'manage_holiday.edit',
            'manage_holiday.delete',
            'national_holiday.list',
            'national_holiday.create',
            'national_holiday.edit',
            'national_holiday.delete',
            'leave_type.list',
            'leave_type.create',
            'leave_type.edit',
            'leave_type.delete',
            'joint_holiday.list',
            'joint_holiday.create',
            'joint_holiday.edit',
            'joint_holiday.delete',
            'leave_period.list',
            'leave_period.create',
            'leave_period.edit',
            'leave_period.delete',
            'master_workday.list',
            'master_workday.create',
            'master_workday.edit',
            'master_workday.delete',
            'master_workshift.list',
            'master_workshift.create',
            'master_workshift.edit',
            'master_workshift.delete',
            'master_late.list',
            'master_late.create',
            'master_late.edit',
            'master_late.delete',
            'approval_queue.list',
            'approval_queue.leave_application',
            'approval_queue.manual_attendance',
            'approval_queue.update_data',
            'approval_queue.overtime',
            'approval_queue.medical_reimbursement',
            'leave_approval.approve',
            'leave_approval.reject',
            'medical_reimbursement_approval.manage',
            'medical_reimbursement_approval.approve_manager',
            'medical_reimbursement_approval.reject_manager',
            'medical_reimbursement_approval.approve_hr',
            'medical_reimbursement_approval.reject_hr',
            'medical_reimbursement_approval.approve_finance',
            'medical_reimbursement_approval.reject_finance',
            'master_employee.list',
            'master_employee.create',
            'master_employee.edit',
            'master_employee.delete',
            'leave_report.list',
            'leave_report.view_all',
            'daily_attendance.list',
            'monthly_attendance.list',
            'overall_attendance.list',
            'manual_attendance.approve',
            'manual_attendance.reject',
            'manual_attendance.cancel',
            'manual_attendance.finish',
            'master_announcement.list',
            'master_announcement.create',
            'master_announcement.edit',
            'master_announcement.delete',
            'self_profile.list',
            'self_profile.create',
            'self_profile.edit',
            'self_profile.delete',
            'self_attendance.list',
            'self_attendance.create',
            'self_attendance.edit',
            'self_attendance.delete',
            'self_leavetracker.list',
            'self_leavetracker.create',
            'self_leave_application.list',
            'self_leave_application.create',
            'self_leave_application.cancel',
            'self_medical_reimbursement.list',
            'self_medical_reimbursement.create',
            'self_medical_reimbursement.cancel',
            'self_overtime.list',
            'self_overtime.create',
            'self_overtime.cancel',
            'overtime.history',
            'overtime.approve',
            'overtime.reject',
            'overtime.cancel',
            'overtime.done',
            'update_data.approve',
            'update_data.reject',
            'employee_balance.list',
            'leave_tracker.approve',
            'leave_tracker.reject',
            'leave_tracker.cancel',
            'leave_tracker.finish',
            'medical_report.list',
            'leave_balance.list',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Super Admin
        $superAdminPermissions = [
            'dashboard.superadmin',
            'master_company.list',
            'master_company.create',
            'master_company.edit',
            'master_company.delete',
            'role_permission.list',
            'role_permission.create',
            'role_permission.edit',
            'role_permission.delete',
            'branch_company.list',
            'branch_company.create',
            'branch_company.edit',
            'branch_company.delete',
            'department_company.list',
            'department_company.create',
            'department_company.edit',
            'department_company.delete',
            'job_position.list',
            'job_position.create',
            'job_position.edit',
            'job_position.delete',
            'job_class.list',
            'job_class.create',
            'job_class.edit',
            'job_class.delete',
            'manage_holiday.list',
            'manage_holiday.create',
            'manage_holiday.edit',
            'manage_holiday.delete',
            'national_holiday.list',
            'national_holiday.create',
            'national_holiday.edit',
            'national_holiday.delete',
            'leave_type.list',
            'leave_type.create',
            'leave_type.edit',
            'leave_type.delete',
            'joint_holiday.list',
            'joint_holiday.create',
            'joint_holiday.edit',
            'joint_holiday.delete',
            'leave_period.list',
            'leave_period.create',
            'leave_period.edit',
            'leave_period.delete',
            'master_workday.list',
            'master_workday.create',
            'master_workday.edit',
            'master_workday.delete',
            'master_workshift.list',
            'master_workshift.create',
            'master_workshift.edit',
            'master_workshift.delete',
            'master_late.list',
            'master_late.create',
            'master_late.edit',
            'master_late.delete',
            'approval_queue.list',
            'approval_queue.leave_application',
            'approval_queue.manual_attendance',
            'approval_queue.update_data',
            'approval_queue.overtime',
            'approval_queue.medical_reimbursement',
            'master_employee.list',
            'master_employee.create',
            'master_employee.edit',
            'master_employee.delete',
            'leave_report.list',
            'leave_report.view_all',
            'daily_attendance.list',
            'monthly_attendance.list',
            'overall_attendance.list',
            'manual_attendance.approve',
            'manual_attendance.reject',
            'manual_attendance.cancel',
            'manual_attendance.finish',
            'master_announcement.list',
            'master_announcement.create',
            'master_announcement.edit',
            'master_announcement.delete',
            'leave_tracker.approve',
            'leave_tracker.reject',
            'leave_tracker.cancel',
            'leave_tracker.finish',
            'medical_reimbursement_approval.manage',
            'self_profile.list',
            'self_profile.create',
            'self_profile.edit',
            'self_profile.delete',
            'self_attendance.list',
            'self_attendance.create',
            'self_attendance.edit',
            'self_attendance.delete',
            'self_leavetracker.list',
            'self_leavetracker.create',
            'self_leave_application.list',
            'self_leave_application.create',
            'self_leave_application.cancel',
            'self_medical_reimbursement.list',
            'self_medical_reimbursement.create',
            'self_medical_reimbursement.cancel',
            'self_overtime.list',
            'self_overtime.create',
            'self_overtime.cancel',
            'overtime.history',
            'overtime.approve',
            'overtime.reject',
            'overtime.cancel',
            'overtime.done',
            'update_data.approve',
            'update_data.reject',
            'employee_balance.list',
            'medical_report.list',
            'leave_balance.list',
        ];

        $superAdmin = Role::create(['name' => 'Super Admin']);

        foreach ($superAdminPermissions as $superAdminPermission) {
            $superAdmin->givePermissionTo($superAdminPermission);
        }

        // Worker
        $workerPermissions = [
            'self_profile.list',
            'self_profile.create',
            'self_profile.edit',
            'self_profile.delete',
            'self_attendance.list',
            'self_attendance.create',
            'self_attendance.edit',
            'self_attendance.delete',
            'self_leavetracker.list',
            'self_leavetracker.create',
            'self_leave_application.list',
            'self_leave_application.create',
            'self_leave_application.cancel',
            'self_medical_reimbursement.list',
            'self_medical_reimbursement.create',
            'self_medical_reimbursement.cancel',
            'self_overtime.list',
            'self_overtime.create',
            'self_overtime.cancel',
        ];

        $worker = Role::create(['name' => 'Worker']);

        foreach ($workerPermissions as $workerPermission) {
            $worker->givePermissionTo($workerPermission);
        }

        // Manager
        $managerPermissions = [
            'approval_queue.list',
            'approval_queue.leave_application',
            'approval_queue.manual_attendance',
            'approval_queue.overtime',
            'approval_queue.medical_reimbursement',
            'leave_approval.approve',
            'leave_approval.reject',
            'medical_reimbursement_approval.approve_manager',
            'medical_reimbursement_approval.reject_manager',
            'master_employee.list',
            'leave_report.list',
            'leave_report.view_all',
            'daily_attendance.list',
            'monthly_attendance.list',
            'overall_attendance.list',
            'master_announcement.list',
            'self_profile.list',
            'self_profile.create',
            'self_profile.edit',
            'self_profile.delete',
            'self_attendance.list',
            'self_attendance.create',
            'self_attendance.edit',
            'self_attendance.delete',
            'self_leavetracker.list',
            'self_leavetracker.create',
            'self_leave_application.list',
            'self_leave_application.create',
            'self_leave_application.cancel',
            'self_medical_reimbursement.list',
            'self_medical_reimbursement.create',
            'self_medical_reimbursement.cancel',
            'self_overtime.list',
            'self_overtime.create',
            'self_overtime.cancel',
        ];

        $manager = Role::create(['name' => 'Manager']);

        foreach ($managerPermissions as $managerPermission) {
            $manager->givePermissionTo($managerPermission);
        }

        // Human Resources
        $humanResourcesPermissions = [
            'branch_company.list',
            'branch_company.create',
            'branch_company.edit',
            'department_company.list',
            'department_company.create',
            'department_company.edit',
            'job_position.list',
            'job_position.create',
            'job_position.edit',
            'job_class.list',
            'job_class.create',
            'job_class.edit',
            'manage_holiday.list',
            'manage_holiday.create',
            'manage_holiday.edit',
            'national_holiday.list',
            'national_holiday.create',
            'national_holiday.edit',
            'leave_type.list',
            'leave_type.create',
            'leave_type.edit',
            'joint_holiday.list',
            'joint_holiday.create',
            'joint_holiday.edit',
            'leave_period.list',
            'leave_period.create',
            'leave_period.edit',
            'master_workday.list',
            'master_workday.create',
            'master_workday.edit',
            'master_workshift.list',
            'master_workshift.create',
            'master_workshift.edit',
            'master_late.list',
            'master_late.create',
            'master_late.edit',
            'approval_queue.list',
            'approval_queue.leave_application',
            'approval_queue.manual_attendance',
            'approval_queue.overtime',
            'approval_queue.medical_reimbursement',
            'leave_approval.approve',
            'leave_approval.reject',
            'medical_reimbursement_approval.approve_hr',
            'medical_reimbursement_approval.reject_hr',
            'master_employee.list',
            'master_employee.create',
            'master_employee.edit',
            'master_employee.delete',
            'leave_report.list',
            'leave_report.view_all',
            'daily_attendance.list',
            'monthly_attendance.list',
            'overall_attendance.list',
            'manual_attendance.approve',
            'manual_attendance.reject',
            'manual_attendance.cancel',
            'manual_attendance.finish',
            'master_announcement.list',
            'master_announcement.create',
            'master_announcement.edit',
            'master_announcement.delete',
            'self_profile.list',
            'self_profile.create',
            'self_profile.edit',
            'self_profile.delete',
            'self_attendance.list',
            'self_attendance.create',
            'self_attendance.edit',
            'self_attendance.delete',
            'self_leavetracker.list',
            'self_leavetracker.create',
            'self_leave_application.list',
            'self_leave_application.create',
            'self_leave_application.cancel',
            'self_medical_reimbursement.list',
            'self_medical_reimbursement.create',
            'self_medical_reimbursement.cancel',
            'self_overtime.list',
            'self_overtime.create',
            'self_overtime.cancel',
            'overtime.history',
            'overtime.approve',
            'overtime.reject',
            'overtime.cancel',
            'overtime.done',
            'leave_balance.list',
        ];

        $humanResources = Role::create(['name' => 'Human Resources']);

        foreach ($humanResourcesPermissions as $humanResourcesPermission) {
            $humanResources->givePermissionTo($humanResourcesPermission);
        }

        // Finance
        $financePermissions = [
            'approval_queue.list',
            'approval_queue.medical_reimbursement',
            'medical_reimbursement_approval.approve_finance',
            'medical_reimbursement_approval.reject_finance',
            'self_profile.list',
            'self_profile.create',
            'self_profile.edit',
            'self_profile.delete',
            'self_attendance.list',
            'self_attendance.create',
            'self_attendance.edit',
            'self_attendance.delete',
            'self_leavetracker.list',
            'self_leavetracker.create',
            'self_leave_application.list',
            'self_leave_application.create',
            'self_leave_application.cancel',
            'self_medical_reimbursement.list',
            'self_medical_reimbursement.create',
            'self_medical_reimbursement.cancel',
            'self_overtime.list',
            'self_overtime.create',
            'self_overtime.cancel',
        ];

        $finance = Role::create(['name' => 'Finance']);

        foreach ($financePermissions as $financePermission) {
            $finance->givePermissionTo($financePermission);
        }
    }
}
