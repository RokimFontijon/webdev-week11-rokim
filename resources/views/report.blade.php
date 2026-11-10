<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Campus Events Report</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 30px auto; padding: 0 16px; color: #222; }
        h1 { border-bottom: 3px solid #333; padding-bottom: 8px; }
        section { margin: 28px 0; padding: 16px; border: 1px solid #ccc; border-radius: 6px; }
        h2 { margin-top: 0; font-size: 18px; color: #1a4d8f; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 6px 10px; text-align: left; }
        th { background: #f0f0f0; }
        form { margin-bottom: 10px; }
        .empty { color: #888; font-style: italic; }
    </style>
</head>
<body>
    <h1>Campus Events Report</h1>

    {{-- Query 1 --}}
    <section>
        <h2>1. Every venue with the number of events it hosts</h2>
        <table>
            <tr><th>Venue</th><th>Events hosted</th></tr>
            @foreach ($venues as $v)
                <tr><td>{{ $v->name }}</td><td>{{ $v->events_count }}</td></tr>
            @endforeach
        </table>
    </section>

    {{-- Query 2 --}}
    <section>
        <h2>2. All events at one chosen venue: {{ $venue->name }}</h2>
        <form method="GET" action="/report">
            <input type="hidden" name="event" value="{{ $event->id }}">
            <input type="hidden" name="participant" value="{{ $participant->id }}">
            <input type="hidden" name="note" value="{{ $note->id }}">
            <select name="venue" onchange="this.form.submit()">
                @foreach ($venues as $v)
                    <option value="{{ $v->id }}" @selected($v->id === $venue->id)>{{ $v->name }}</option>
                @endforeach
            </select>
        </form>
        <ul>
            @forelse ($venueEvents as $e)
                <li>{{ $e->title }} ({{ $e->starts_at->format('M d, Y g:i A') }})</li>
            @empty
                <li class="empty">No events.</li>
            @endforelse
        </ul>
    </section>

    {{-- Query 3 --}}
    <section>
        <h2>3. All participants of one chosen event: {{ $event->title }}</h2>
        <form method="GET" action="/report">
            <input type="hidden" name="venue" value="{{ $venue->id }}">
            <input type="hidden" name="participant" value="{{ $participant->id }}">
            <input type="hidden" name="note" value="{{ $note->id }}">
            <select name="event" onchange="this.form.submit()">
                @foreach ($allEvents as $e)
                    <option value="{{ $e->id }}" @selected($e->id === $event->id)>{{ $e->title }}</option>
                @endforeach
            </select>
        </form>
        <table>
            <tr><th>Participant</th><th>Email</th><th>Status</th></tr>
            @forelse ($eventParticipants as $p)
                <tr><td>{{ $p->name }}</td><td>{{ $p->email }}</td><td>{{ $p->pivot->status }}</td></tr>
            @empty
                <tr><td colspan="3" class="empty">No participants.</td></tr>
            @endforelse
        </table>
    </section>

    {{-- Query 4 --}}
    <section>
        <h2>4. All events one chosen participant has joined: {{ $participant->name }}</h2>
        <form method="GET" action="/report">
            <input type="hidden" name="venue" value="{{ $venue->id }}">
            <input type="hidden" name="event" value="{{ $event->id }}">
            <input type="hidden" name="note" value="{{ $note->id }}">
            <select name="participant" onchange="this.form.submit()">
                @foreach ($allParticipants as $p)
                    <option value="{{ $p->id }}" @selected($p->id === $participant->id)>{{ $p->name }}</option>
                @endforeach
            </select>
        </form>
        <table>
            <tr><th>Event</th><th>Status</th></tr>
            @forelse ($participantEvents as $e)
                <tr><td>{{ $e->title }}</td><td>{{ $e->pivot->status }}</td></tr>
            @empty
                <tr><td colspan="2" class="empty">No events joined.</td></tr>
            @endforelse
        </table>
    </section>

    {{-- Query 5 --}}
    <section>
        <h2>5. All events that have at least one waitlisted participant</h2>
        <ul>
            @forelse ($waitlistedEvents as $e)
                <li>{{ $e->title }}</li>
            @empty
                <li class="empty">None.</li>
            @endforelse
        </ul>
    </section>

    {{-- Query 6 --}}
    <section>
        <h2>6a. All notes of the chosen event: {{ $event->title }}</h2>
        <ul>
            @forelse ($eventNotes as $n)
                <li>{{ $n->body }}</li>
            @empty
                <li class="empty">No notes.</li>
            @endforelse
        </ul>
        <h2>6b. All notes of the chosen venue: {{ $venue->name }}</h2>
        <ul>
            @forelse ($venueNotes as $n)
                <li>{{ $n->body }}</li>
            @empty
                <li class="empty">No notes.</li>
            @endforelse
        </ul>
    </section>

    {{-- Query 7 --}}
    <section>
        <h2>7. For one chosen note: what it belongs to and what kind of thing it is</h2>
        <form method="GET" action="/report">
            <input type="hidden" name="venue" value="{{ $venue->id }}">
            <input type="hidden" name="event" value="{{ $event->id }}">
            <input type="hidden" name="participant" value="{{ $participant->id }}">
            <select name="note" onchange="this.form.submit()">
                @foreach ($allNotes as $n)
                    <option value="{{ $n->id }}" @selected($n->id === $note->id)>Note {{ $n->id }}: {{ $n->body }}</option>
                @endforeach
            </select>
        </form>
        <table>
            <tr><th>Note</th><th>Belongs to</th><th>Kind of thing</th></tr>
            <tr>
                <td>{{ $note->body }}</td>
                <td>{{ $noteOwner->name ?? $noteOwner->title }}</td>
                <td>{{ class_basename($note->notable_type) }}</td>
            </tr>
        </table>
    </section>
</body>
</html>