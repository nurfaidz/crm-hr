<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EventsController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:event_list', ['only' => ['index','show']]);
        $this->middleware('permission:event_create', ['only' => ['create','store']]);
        $this->middleware('permission:event_edit', ['only' => ['edit','update']]);
        $this->middleware('permission:event_delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function dor(Request $request){
        $role = json_encode(Auth::user()->hasPermissionTo('event_list'));
        if($role){
            echo "iso";
        } 
        else{
            echo "ora ";
        }
        
    }

    public function index(Request $request)
    {
        $role = json_encode(Auth::user()->hasPermissionTo('event_list'));
        if($role){
            
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $event = Event::latest()->paginate($perPage);
        } else {
            $event = Event::latest()->paginate($perPage);
        }
    }

        return view('events.event.index', compact('event'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('events.event.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $requestData = $request->all();
        
        Event::create($requestData);

        return redirect()->route('events.index')->with('flash_message', 'Event added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);

        return view('events.event.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);

        return view('events.event.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        
        $requestData = $request->all();
        
        $event = Event::findOrFail($id);
        $event->update($requestData);

        return redirect()->route('events.index')->with('flash_message', 'Event updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Event::destroy($id);

        return redirect()->route('events.index')->with('flash_message', 'Event deleted!');
    }
}
