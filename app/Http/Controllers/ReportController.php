<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Note;
use App\Models\Participant;
use App\Models\Venue;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $venues = Venue::withCount('events')->get();

        $venue = Venue::findOrFail($request->query('venue', $venues->first()->id));
        $event = Event::findOrFail($request->query('event', Event::first()->id));
        $participant = Participant::findOrFail($request->query('participant', Participant::first()->id));
        $note = Note::findOrFail($request->query('note', Note::first()->id));

        return view('report', [
            'venues' => $venues,
            'allEvents' => Event::orderBy('title')->get(),
            'allParticipants' => Participant::orderBy('name')->get(),
            'allNotes' => Note::orderBy('id')->get(),

            'venue' => $venue,
            'venueEvents' => $venue->events,
            'event' => $event,
            'eventParticipants' => $event->participants,
            'participant' => $participant,
            'participantEvents' => $participant->events,
            'waitlistedEvents' => Event::whereHas('participants', fn ($q) => $q->where('event_participant.status', 'waitlisted'))->get(),
            'eventNotes' => $event->notes,
            'venueNotes' => $venue->notes,
            'note' => $note,
            'noteOwner' => $note->notable,
        ]);
    }
}