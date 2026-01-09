<?php
namespace App\Http\Controllers;

use App\Models\EventType;
use Illuminate\Http\Request;

class EventTypeController extends Controller
{
    //

    public function index()
    {
        $title      = 'Event Types';
        $event_types     = EventType::all();
        $mode       = 'create';

        return view('dashboard.pages.event-types.index', compact('title', 'event_types', 'mode'));
    }

    public function edit($event)
    {
        $title      = 'Edit Event Type';
        $mode       = 'edit';
        $event   = EventType::find($event);
        $event_types     = EventType::all();
        return view('dashboard.pages.event-types.index', compact('title', 'mode', 'event', 'event_types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_type' => 'required',
        ]);

        EventType::create([
            'event_type' => $request->event_type,
        ]);

        return redirect()->route('event-types')->with('success', 'Event type created successfully');
    }

    public function update(Request $request, $category)
    {
        $request->validate([
            'event_type' => 'required',
        ]);

        EventType::find($category)->update([
            'event_type' => $request->event_type,
        ]);

        return redirect()->route('event-types')->with('success', 'Event type updated successfully');
    }

    public function destroy($category)
    {
        EventType::find($category)->delete();
        return redirect()->route('event-types')->with('success', 'Event type deleted successfully');
    }
}
