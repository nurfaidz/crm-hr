<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnnouncementGetRequest;
use App\Models\Announcement;
use App\Models\JobClass;
use App\Repositories\AnnouncementsRepository;
use App\Repositories\EmployeeRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPSTORM_META\map;

class AnnouncementController extends Controller
{

    private AnnouncementsRepository $announcementsRepository;
    private EmployeeRepository $employeeRepository;

    function __construct(AnnouncementsRepository $announcementsRepository, EmployeeRepository $employeeRepository)
    {
        $this->announcementsRepository = $announcementsRepository;
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/announcements",
     *     tags={"Announcements"},
     *     summary="Announcements",
     *     description="Get announcements",
     *     operationId="getAnnouncements",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="which numbers of page that should have to displayed",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="which numbers of much annnouncement that should have to displayed on one page, default number is 15.",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Authenticated Success"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request / Validation Errors"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Something went wrong"
     *     ),
     * )
     */
    public function index(AnnouncementGetRequest $request)
    {
        try {
            $q = ($request->has(["per_page"])) ? $request->per_page : null;
            $announcements = $this->announcementsRepository->getAnnouncements($q)->toArray();


            foreach ($announcements["data"] as $index => $announcement) {
                $employee = ($this->employeeRepository->getEmployee($announcement['user']['id']));
                $announcements["data"][$index]['publisher'] = "{$employee['first_name']} {$employee['last_name']}"; // menambahkan nama publisher
                unset($announcements["data"][$index]['user']); // menghapus data user yang tidak terpakai
                
                $media_files = array(
                    "image"=>null,
                    "doc"=>null,
                );

                if (!is_null($announcement["media"])) {

                    foreach ($announcement["media"] as $i => $media) {

                     
                        $filePath = public_path("uploads/{$media['id']}/{$media['file_name']}"); // mengambil file path
                        $announcements["data"][$index]["media"][$i]["disk"] = "file not found or renamed";
                        if (file_exists($filePath)) {
                            $announcements["data"][$index]["media"][$i]["disk"] = env('APP_URL') . "/uploads/{$media['id']}/{$media['file_name']}";
                        }

                        if($media['collection_name']=="announcement_image"){

                            $media_files["image"] = $announcements["data"][$index]["media"][$i];

                        }else{

                            $media_files["doc"] = $announcements["data"][$index]["media"][$i];

                        }

                        unset($matches);
                    }
                }

                $announcements["data"][$index]["media"]=$media_files;
                unset($media_files);
                
            }
            return ResponseFormatter::success($announcements, 'Get Announcements');

        } catch (Exception $e) {

            $response = [
                'errors' => $e->getMessage(),
            ];
            return ResponseFormatter::error($response, 'Something went wrong', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }
}
