<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

class EventController extends Controller
{

    public function index(Request $request)
    {
        return Event::all();
    }
 
    public function show(Request $request, $id)
    {
        return Event::findOrFail($id);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required',
            'startDate' => 'required|date',
            'endDate'   => 'required|date'
        ]);
        
        $startDate = Carbon::create($request->input('startDate'));
        $endDate   = Carbon::create($request->input('endDate'));

        if ($startDate->gte($endDate)) {
            return response()->json(['status' => 'Start time is greater than or equal end time.' ], 422);
        }

        $request->merge([
            'userId'    => $this->getUserId($request),
            'startDate' => $startDate,
            'endDate'   => $endDate
        ]);

        return Event::create($request->all());
    }

    public function update(Request $request, $id)
    {

        $userId = $this->getUserId($request);
        $event = Event::where('userId', $userId)->where('id', $id)->first();
        if(!$event) {
            abort(401, 'Unauthorized action.');
        }

        if ($request->has('startDate') || $request->has('startDate')) {
            
            $startDate = Carbon::create($request->has('startDate') ? $request->input('startDate') : $event->startDate);
            $endDate   = Carbon::create($request->has('endDate') ? $request->input('endDate') : $event->endDate);
            if ($startDate->gte($endDate)) {
                return response()->json(['status' => 'Start time is greater than or equal end time.' ], 422);
            }
            if ($endDate->lte($startDate)) {
                return response()->json(['status' => 'End time is less than or equal start time.' ], 422);
            }

        } 

        $event->update($request->all());

        return $event;
    }

    public function delete(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        if(!$event) {
            abort(401, 'Unauthorized action.');
        }

        $now = Carbon::now();
        if($now->gte($event->startDate) && $now->lte($event->endDate)) {
            abort(401, 'Unauthorized action.');
        }

        $event->delete();
        return 204;
    }

    private function getUserId($request)
    {
        if (!$request->has('api_token')) {
            abort(401, 'Unauthorized action.');
        }
        return User::where('api_token', $request->input('api_token'))->first()->id;
    }

}