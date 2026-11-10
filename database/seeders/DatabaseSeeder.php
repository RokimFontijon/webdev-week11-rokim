<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Note;
use App\Models\Participant;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 3 venues
        $venues = collect([
            ['name' => 'Main Auditorium', 'address' => 'Building A, Ground Floor', 'capacity' => 300],
            ['name' => 'Gymnasium', 'address' => 'Sports Complex', 'capacity' => 500],
            ['name' => 'Library Hall', 'address' => 'Library, 2nd Floor', 'capacity' => 80],
        ])->map(fn ($v) => Venue::create($v));

        // 6 events, created through the venue relationship (2 per venue)
        $eventData = [
            [0, 'Freshman Orientation', '2026-12-01 09:00:00'],
            [0, 'Tech Talk', '2026-12-03 14:00:00'],
            [1, 'Intramurals Opening', '2026-12-05 08:00:00'],
            [1, 'Basketball Finals', '2026-12-07 17:00:00'],
            [2, 'Book Fair', '2026-12-09 10:00:00'],
            [2, 'Poetry Night', '2026-12-11 19:00:00'],
        ];

        $events = collect($eventData)->map(fn ($e) => $venues[$e[0]]->events()->create([
            'title' => $e[1],
            'starts_at' => $e[2],
        ]));

        // 8 participants
        $participants = collect([
            ['Ana Reyes', 'ana@example.com'],
            ['Ben Santos', 'ben@example.com'],
            ['Carla Cruz', 'carla@example.com'],
            ['Dino Lopez', 'dino@example.com'],
            ['Ella Garcia', 'ella@example.com'],
            ['Franz Mendoza', 'franz@example.com'],
            ['Gina Torres', 'gina@example.com'],
            ['Hugo Ramos', 'hugo@example.com'],
        ])->map(fn ($p) => Participant::create(['name' => $p[0], 'email' => $p[1]]));

        // 12 registrations, both statuses used: [event index, participant index, status]
        $registrations = [
            [0, 0, 'registered'], [0, 1, 'registered'], [0, 2, 'waitlisted'],
            [1, 0, 'registered'], [1, 3, 'registered'],
            [2, 4, 'registered'], [2, 5, 'waitlisted'],
            [3, 6, 'registered'], [3, 1, 'registered'],
            [4, 7, 'registered'], [4, 2, 'waitlisted'],
            [5, 3, 'registered'],
        ];

        foreach ($registrations as [$e, $p, $status]) {
            $events[$e]->participants()->attach($participants[$p]->id, ['status' => $status]);
        }

        // 6 notes: 3 on events, 3 on venues
        $events[0]->notes()->create(['body' => 'Bring your school ID.']);
        $events[2]->notes()->create(['body' => 'Wear your team jersey.']);
        $events[4]->notes()->create(['body' => 'Free entry for students.']);

        $venues[0]->notes()->create(['body' => 'Projector needs a technician.']);
        $venues[1]->notes()->create(['body' => 'Parking is limited.']);
        $venues[2]->notes()->create(['body' => 'Quiet zone, no food allowed.']);
    }
}