<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Announcement;
use App\Models\Employee;
use App\Repositories\AnnouncementsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AnnouncementsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master_announcement.list', ['only' => ['index', 'show', 'lists', 'viewmore', 'view']]);
        $this->middleware('permission:master_announcement.create', ['only' => ['create', 'store', 'publish']]);
        $this->middleware('permission:master_announcement.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:master_announcement.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function lists(Request $request)
    {
        $announcements = Announcement::all();
        if ($request->ajax()) {
            return DataTables::of($announcements)
                ->addIndexColumn()
                ->addColumn('statusBtn', function ($row) {
                    $publishBtn  = '<div class="spinner-border" data-id="' . $row->announcement_id . '" role="status" hidden><span class="sr-only">Loading...</span></div><input id="check" class="check" role="button" type="checkbox" value="' . $row->announcement_status . '" data-id="' . $row->announcement_id . '" onclick=publish_data($(this))>';
                    return $publishBtn;
                })
                ->addColumn('published_at', function ($row) {
                    $date =  date('d/m/Y', strtotime($row->published_at));
                    return $date;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn  = '<div title="Detail" ><a onclick=view_data($(this)) data-id="' . $row->announcement_id . '" id="view" class="btn p-0 mr-1"><img src="./img/icons/view.svg" alt="View"></a>';
                    $actionBtn .= '<a title="Edit" onclick=edit_data($(this)) data-id="' . $row->announcement_id . '"  class="edit btn p-0 mr-1" id="edit"><img src="./img/icons/edit.svg" alt="Edit"></a>';
                    $actionBtn .= '<a title="Delete" onclick=delete_data($(this)) data-id="' . $row->announcement_id . '" class="btn p-0" ><img src="./img/icons/trash.svg" alt="Delete"></a></div>';
                    return $actionBtn;
                })

                ->rawColumns(['action', 'statusBtn', 'published_at'])
                ->make(true);
        }
        return view('announcements.lists', ['announcements' => $announcements]);
    }

    public function viewmore(Request $request)
    {

        $id = null;
        $announcements = Announcement::where('announcement_status', 'Published')->orderBy('published_at', 'desc')->paginate(10);
        $file = $request->file('announcement_attachment');
        $fileName = null;
        $firstName = null;
        $lastName = null;
        $image = $request->file('announcement_image');

        $i = 0;
        foreach ($announcements as $announcement) {
            $firstName[$i] = Employee::where('user_id', $announcement->user_id)->first()->getOriginal('first_name');
            $lastName[$i] = Employee::where('user_id', $announcement->user_id)->first()->getOriginal('last_name');
            if ($announcement->getFirstMedia('announcement_attachment') != null) {
                $file[$i] = $announcement->getFirstMedia('announcement_attachment')->getUrl();
                $fileName[$i] = $announcement->getFirstMedia('announcement_attachment')->file_name;
            }
            if ($announcement->getFirstMedia('announcement_image') != null) {
                $image[$i] = $announcement->getFirstMedia('announcement_image')->getUrl();
            } else {
                $image[$i] = null;
            }
            $i++;
        }

        return view('announcements.viewmore', [
            'announcements' => $announcements,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'id' => $id,
            'image_url' => $image,
            'file_url' => $file,
            'file_name' => $fileName
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function view($id, Request $request)
    {
        $announcements = Announcement::all();
        $id = $id;
        $file = $request->file('announcement_attachment');
        $image = $request->file('announcement_image');
        foreach ($announcements as $announcement) {

            if ($announcement->announcement_attachment != null) {
                $file = $announcement->getFirstMedia('announcement_attachment')->getUrl();
            } else {
                $file = [];
            }
            if ($announcement->announcement_image != null) {
                $image = $announcement->getFirstMedia('announcement_image')->getUrl();
            } else {
                $image = [];
            }
        }
        return view('announcements.viewmore', ['announcements' => $announcements, 'id' => $id]);
    }

    public function publish($id)
    {
        $announcement = Announcement::findOrFail($id);
        if ($announcement->announcement_status == 'Published') {
            $announcement->update([
                'announcement_status' => 'Unpublished',
                'published_at' => null
            ]);
        } else {
            $announcement->update([
                'announcement_status' => 'Published',
                'published_at' => Carbon::now()
            ]);
        }


        if ($announcement) {
            return response()->json(["error" => false, "data" => $announcement]);
        } else {
            return response()->json(["error" => true, "message" => "Something Went Wrong"]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(Announcement::$rules);
        $file = $request->file('announcement_attachment');
        $image = $request->file('announcement_image');
        $request->announcement_status = 'Unpublished';
        try {
            if ($file || $image) {
                $input  = $request->except(['announcement_attachment', 'announcement_image']);
                $announcement = Announcement::create($input);
                if ($file) {
                    // $filename = $request->file('announcement_attachment')->getClientOriginalName() . '.' . $request->file('announcement_attachment')->getClientOriginalExtension();
                    $announcement->addMedia($file)->toMediaCollection('announcement_attachment');
                }
                if ($image) {
                    // $imagename = $request->file('announcement_image')->getClientOriginalName() . '.' . $request->file('announcement_image')->getClientOriginalExtension();
                    $announcement->addMedia($image)->toMediaCollection('announcement_image');
                }
                $announcement->save();
            } else {
                $input  = $request->all();
                $announcement = Announcement::create($input);
            }
            return response()->json(["error" => false, "message" => 'Successfuly Added New Announcement!']);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }

        //return dd($requestData);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $announcement  = Announcement::where('announcement_id', $id)->first();
        $employee = Employee::where('user_id', $announcement->user_id)->first();
        $publishedBy =  $employee->first_name . ' ' . $employee->last_name;
        $file = $announcement->getFirstMediaUrl('announcement_attachment');
        if ($file == '') {
            $fileName = null;
            $fileSize = null;
            $fileNameExt = null;
        } else {
            $fileName = $announcement->getFirstMedia('announcement_attachment')->name;
            $fileNameExt = $announcement->getFirstMedia('announcement_attachment')->file_name;
            $fileSize = $announcement->getFirstMedia('announcement_attachment')->size;
        }
        $image = $announcement->getFirstMediaUrl('announcement_image');
        $date = date('d/m/Y', strtotime($announcement->published_at));
        if ($date == '01/01/1970') {
            $date = 'Not Published';
        }


        if ($announcement) {
            return response()->json([
                "error" => false,
                "data" => $announcement,
                "image_url" => $image,
                'published_by' => $publishedBy,
                'date' => $date,
                'file_size' => $fileSize,
                "file_url" => $file,
                "file_name_ext" => $fileNameExt,
                "file_name" => $fileName
            ]);
        } else {
            return response()->json(["error" => true, "message" => "Data Not Found"]);
        }
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
        $requestData = $request->except('_token', 'announcement_image', 'announcement_attachment', 'published_at');
        $announcements = Announcement::findOrFail($id);
        if ($announcements != null) {
            if ($request->hasFile('announcement_image')) {
                $image = $request->file('announcement_image');
                $announcements->clearMediaCollection('announcement_image');
                $announcements->addMedia($image)->toMediaCollection('announcement_image');
            }
            if ($request->hasFile('announcement_attachment')) {
                $file = $request->file('announcement_attachment');
                $announcements->clearMediaCollection('announcement_attachment');
                $announcements->addMedia($file)->toMediaCollection('announcement_attachment');
            }
            $announcements->update($requestData);
            return response()->json(["error" => false, "message" => "Successfully Update Announcement"]);
        } else {
            return response()->json(["error" => true, "message" => "Sorry, Something Went Wrong"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $announcements = Announcement::where('announcement_id', $id)->first();
            $medias = Media::where('model_id', $id)->get();
            if ($announcements->getFirstMediaUrl('announcement_attachment')) {
                $announcements->clearMediaCollection('announcement_attachment');
            }
            if ($announcements->getFirstMediaUrl('announcement_image')) {
                $announcements->clearMediaCollection('announcement_image');
            }
            $announcements->delete();
            return response()->json(["error" => false, "message" => "Successfuly Deleted Announcement Data!"]);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }
}
