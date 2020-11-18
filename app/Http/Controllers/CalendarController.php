<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Http\Resources\CalendarResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CalendarResource::collection(Calendar::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $calendar = Calendar::create(['title'=> $request->title, 'start'=>$request->start, 'end'=>$request->end]);
        return response()->json(['data' => new CalendarResource($calendar),
        'message'=>'Successully added new rental',
        'status'=> Response::HTTP_CREATED]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function show(Calendar $calendar)
    {
        return new CalendarResource($calendar);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Calendar $calendar)
    {
        $calendar->update(['title'=> $request->title, 'start'=>$request->start, 'end'=>$request->end]);
        return response()->json([
            'data'=> new CalendarResource($calendar),
            'message'=> 'Successfully Updated',
            'status' => Response::HTTP_ACCEPTED]);        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calendar $calendar)
    {
        $calendar->delete();
        return response()->json([
            'message'=> 'Successfully Deleted',
            'status' => Response::HTTP_NO_CONTENT]);               
    }
}
