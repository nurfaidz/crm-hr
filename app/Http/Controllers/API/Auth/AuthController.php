<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ResponseFormatter;
use App\Models\Employee;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/users/login",
     *     tags={"User Auth"},
     *     summary="User login",
     *     description="Operation for user login into the mobile app",
     *     operationId="loginUser",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="The email for login",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="The password for login",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Authenticated Success"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="We dont have an account for email or wrong password"
     *     ),
     * )
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            $deletedAccount = Employee::join('users', 'employees.user_id', '=', 'users.id')->where('email', '=', $credentials['email'])->first();

            $validate = Validator::make($credentials, [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validate->fails()) {
                $response = [
                    'errors' => $validate->errors()
                ];
                
                return ResponseFormatter::error($response, 'Bad Request', 400);
            }

            if (!Auth::attempt($credentials)) {
                $messages = 'This account doesn\'t exist or wrong password.';

                throw new Exception($messages, 401);
            } else if ($deletedAccount['date_of_leaving'] != null) {
                $messages = 'This account doesn\'t exist or wrong password.';

                throw new Exception($messages, 401);
            }

            $user = User::where('email', $request['email'])->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;
            $employee = Employee::where('user_id', $user->id)->firstOrFail();

            $response = [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
                'employee' => $employee
            ];

            return ResponseFormatter::success($response, 'Authenticated Success');
        } 
        
        catch (Exception $e) {
            $statuscode = 500;
            if ($e->getCode()) $statuscode = $e->getCode();

            $response = [
                'errors' => $e->getMessage(),
            ];

            return ResponseFormatter::error($response, 'Something went wrong', $statuscode);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/users/logout",
     *     tags={"User Auth"},
     *     summary="Logs out current logged in user session",
     *     operationId="logoutUser",
     *     security={{"bearerAuth":{}}}, 
     *      
     *     @OA\Response(
     *         response="200",
     *         description="Token was revoked"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $response = [
            'messages' => 'Success logout'
        ];

        return ResponseFormatter::success($response, 'Token was revoked');
    }

    /**
     * @OA\Post(
     *     path="/api/forgotpass",
     *     tags={"User Auth"},
     *     summary="Forgot Password",
     *     description="Send link reset password to target email",
     *     operationId="forgotPassUser",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="The email for reset password",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Email Was Sent"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request"
     *     ),
     * )
     */
    public function forgotpass(Request $request){
        try {
            $credentials = $request->only('email');

            $validate = Validator::make($credentials, [
                'email' => 'required|email|exists:users',
            ]);

            if ($validate->fails()) {
                $response = [
                    'errors' => $validate->errors()
                ];
                
                return ResponseFormatter::error($response, 'Bad Request', 400);
            }
            $token = Str::random(60);

            PasswordReset::create([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            Mail::send('auth.click', ['token' => $token], function ($message) use ($request) {
                $message->from(env('MAIL_FROM_ADDRESS'));
                $message->to($request->email);
                $message->subject('Reset Password Notification');
            });
            $response = [
                'messages' => 'Success'
            ];

            return ResponseFormatter::success($response, 'Email Was Sent');
        } catch (Exception $e) {
            $statuscode = 500;
            if ($e->getCode()) $statuscode = $e->getCode();

            $response = [
                'errors' => $e->getMessage(),
            ];

            return ResponseFormatter::error($response, 'Something went wrong', $statuscode);
        }
    }
}
