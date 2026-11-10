<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index()
    {
        DB::enableQueryLog();

        // Naive version: no eager loading
        $events = Event::orderBy('id')->limit(50)->get();

        $html = view('events.index', ['events' => $events])->render();
        $queryCount = count(DB::getQueryLog());

        return str_replace('<!--COUNT-->', "Queries sent: {$queryCount}", $html);
    }
}