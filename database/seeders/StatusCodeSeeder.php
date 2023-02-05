<?php

namespace Database\Seeders;

use App\Models\StatusCode;
use Illuminate\Database\Seeder;

class StatusCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $codes = [
            'can',
            'cls',
            'lhn',
            'lhy',
            'lmn',
            'lmy',
            'lpd',
            'rmr',
            'rmy',
            'rhr',
            'rhy',
            'rfr',
            'rfy',
            'rpd',
            'acm',
            'acw',
            'amm',
            'amw',
            'aab',
            'ado',
            'asc',
            'apm',
            'arj',
            'apd',
            'opd',
            'oca',
            'oap',
            'orj',
            'odn',
            'shy',
            'shr',
            'shp',
            'shc',
        ];

        $shorts = [
            'Canceled',
            'Closed',
            'Rejected',
            'Approved',
            'Rejected',
            'Approved',
            'Pending',
            'Rejected',
            'Approved',
            'Rejected',
            'Approved',
            'Rejected',
            'Approved',
            'Pending',
            'Check In',
            'Check In',
            'Check In',
            'Check In',
            'Absence',
            'Day Off',
            'Sick',
            'Permission',
            'Reject',
            'Pending',
            'Pending',
            'Canceled',
            'Approved',
            'Rejected',
            'Done',
            'Approved',
            'Rejected',
            'Pending',
            'Canceled',
        ];

        $longs = [
            'Canceled',
            'Closed',
            'Leave HR Rejected',
            'Leave HR Approved',
            'Leave Manager Rejected',
            'Leave Manager Approved',
            'Leave Pending',
            'Reimbursement Manager Rejected',
            'Reimbursement Manager Approved',
            'Reimbursement HR Rejected',
            'Reimbursement HR Approved',
            'Reimbursement Finance Rejected',
            'Reimbursement Finance Approved',
            'Reimbursement Pending',
            'Attendance Check In Mobile',
            'Attendance Check In Web',
            'Attendance Manual Mobile',
            'Attendance Manual Web',
            'Attendance Absence',
            'Attendance Day Off',
            'Attendance Sick',
            'Attendance Permission',
            'Attendance Reject',
            'Attendance Pending',
            'Overtime Pending',
            'Overtime Canceled',
            'Overtime Approved',
            'Overtime Rejected',
            'Overtime Done',
            'Saving Loan Approved',
            'Saving Loan Rejected',
            'Saving Loan Pending',
            'Saving Loan Canceled'
        ];
        
        for ($i = 0; $i < count($codes); $i++) {
            StatusCode::create([
                'code' => $codes[$i],
                'short' => $shorts[$i],
                'long' => $longs[$i]
            ]);
        }
    }
}
