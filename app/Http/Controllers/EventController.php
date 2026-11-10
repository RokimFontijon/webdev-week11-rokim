<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index()
    {
        DB::enableQueryLog();

        $events = Event::with('venue')
            ->withCount('participants')
            ->orderBy('id')
            ->limit(50)
            ->get();

        $html = view('events.index', ['events' => $events])->render();
        $queryCount = count(DB::getQueryLog());

        return str_replace('<!--COUNT-->', "Queries sent: {$queryCount}", $html);
    }

    public function filter(Request $request)
    {
        $code = $request->query('dress_code', 'masquerade');

        // The same filter written two ways
        $arrow    = Event::where('details->dress_code', $code);
        $contains = Event::whereJsonContains('details', ['dress_code' => $code]);

        $arrowSql         = $arrow->toSql();
        $containsSql      = $contains->toSql();
        $arrowBindings    = $arrow->getBindings();
        $containsBindings = $contains->getBindings();

        $arrowCount    = $arrow->count();
        $containsCount = $contains->count();

        // The list on the page uses chained scopes
        $events = Event::with('venue')
            ->upcoming()
            ->withDressCode($code)
            ->when($request->query('venue'), fn ($q, $id) => $q->atVenue((int) $id))
            ->orderBy('starts_at')
            ->limit(50)
            ->get();

        return view('events.filter', compact(
            'events',
            'code',
            'arrowSql',
            'containsSql',
            'arrowBindings',
            'containsBindings',
            'arrowCount',
            'containsCount'
        ));
    }

    public function search(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $results = collect();
        $sql = null;
        $bindings = [];

        if ($q !== '') {
            $query = Event::with('venue')
                ->whereFullText('description', $q)
                ->limit(50);

            $sql = $query->toSql();
            $bindings = $query->getBindings();
            $results = $query->get();
        }

        return view('events.search', compact('q', 'sql', 'bindings', 'results'));
    }
}