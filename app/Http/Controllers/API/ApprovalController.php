<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Interfaces\ApprovalInterface;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Models\Employee;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    private ApprovalInterface $approvalInterface;

    public function __construct(ApprovalInterface $approvalInterface)
    {
        $this->approvalInterface = $approvalInterface;
    }

        /**
     * @OA\GET(
     *      path="/api/users/leave_approvals/{user_id}",
     *      operationId="leaveApprovalStatistics",
     *      tags={"Users"},
     *      summary="Get Leave Approvals",
     *      description="Get Leave Approvals",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="user_id",
     *          description="user id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Get Leave Approvals Success",
     *      ),
     *      @OA\Response(
     *         response="500",
     *         description="Something went wrong"
     *      )
     * )
     */

    public function leaveApprovalStatistics($user_id)
    {
        try {
            $statistics = $this->approvalInterface->leaveApprovalStatistics($user_id);

            if(!empty($statistics)) $statistics = $statistics['leaveApplications'];
            return ResponseFormatter::success([
                'approvals' => $statistics
            ], 'Get Leave Approvals Success');
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'error' => $e->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    /**
     * @OA\GET(
     *      path="/api/users/attendance_approvals/{user_id}",
     *      operationId="attendanceApprovalStatistics",
     *      tags={"Users"},
     *      summary="Get Manual Attendance Approvals",
     *      description="Get Manual Attendance Approvals",
     *      security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *          name="user_id",
     *          description="user id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Get Attendance Approvals Success",
     *      ),
     *      @OA\Response(
     *         response="500",
     *         description="Something went wrong"
     *      )
     * )
     */

    public function attendanceApprovalStatistics($user_id) {
        try {
            $statistics = $this->approvalInterface->attendanceApprovalStatistics($user_id);

            if(!empty($statistics['manualAttendance'])) $statistics=$statistics['manualAttendances'];
            else $statistics=[];
            
            return ResponseFormatter::success([
                'approvals' => $statistics
            ], 'Get Attendance Approvals Success');
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'error' => $e->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
