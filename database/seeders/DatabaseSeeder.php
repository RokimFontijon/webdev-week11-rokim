<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Participant;
use App\Models\Venue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now()->toDateTimeString();

        // Venues: your original 3 plus a few more
        $venueRows = [
            ['name' => 'Main Auditorium', 'address' => 'Building A, Ground Floor', 'capacity' => 300],
            ['name' => 'Gymnasium', 'address' => 'Sports Complex', 'capacity' => 500],
            ['name' => 'Library Hall', 'address' => 'Library, 2nd Floor', 'capacity' => 80],
            ['name' => 'Science Lab Atrium', 'address' => 'Building B, 1st Floor', 'capacity' => 120],
            ['name' => 'Open Quad', 'address' => 'Central Campus', 'capacity' => 800],
            ['name' => 'Student Center', 'address' => 'Building C', 'capacity' => 200],
        ];
        foreach ($venueRows as &$v) {
            $v['created_at'] = $now;
            $v['updated_at'] = $now;
        }
        unset($v);
        DB::table('venues')->insert($venueRows);
        $venueIds = Venue::pluck('id')->all();

        // 20,000 events, inserted in chunks of 1,000
        $phrases = [
            'a hands-on workshop for beginners',
            'networking with industry guests',
            'free snacks and drinks provided',
            'bring your own laptop',
            'open to all year levels',
            'guest speaker from a local company',
            'live demo and Q&A session',
            'team challenge with prizes',
            'held outdoors on the campus lawn',
            'registration closes one day before',
        ];
        $dressCodes = ['casual', 'formal', 'smart casual'];
        $tags = ['tech', 'music', 'sports', 'career', 'art', 'food'];

        $rows = [];
        for ($i = 1; $i <= 20000; $i++) {
            $description = implode('. ', Arr::random($phrases, 3));
            if ($i % 150 === 0) {
                $description .= '. astrophotography night';   // 133 rows, about 0.67%
            }

            $dress = ($i % 175 === 0) ? 'masquerade' : Arr::random($dressCodes);   // 114 rows, about 0.57%

            $rows[] = [
                'venue_id'    => Arr::random($venueIds),
                'title'       => 'Event ' . $i,
                'starts_at'   => now()->addDays(rand(-60, 120))->addMinutes(rand(0, 1439))->toDateTimeString(),
                'description' => $description,
                'details'     => json_encode([
                    'dress_code' => $dress,
                    'capacity'   => rand(20, 500),
                    'tags'       => Arr::random($tags, 2),
                ]),
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }
        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('events')->insert($chunk);
        }

        // 200 participants
        $participantRows = [];
        for ($i = 1; $i <= 200; $i++) {
            $participantRows[] = [
                'name'       => 'Participant ' . $i,
                'email'      => "participant{$i}@example.com",
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        foreach (array_chunk($participantRows, 100) as $chunk) {
            DB::table('participants')->insert($chunk);
        }
        $participantIds = Participant::pluck('id')->all();

        // Registrations for the first 100 events (5 to 15 each, no duplicate pairs)
        $firstEventIds = Event::orderBy('id')->limit(100)->pluck('id');
        $pivotRows = [];
        foreach ($firstEventIds as $eventId) {
            foreach (Arr::random($participantIds, rand(5, 15)) as $participantId) {
                $pivotRows[] = [
                    'event_id'       => $eventId,
                    'participant_id' => $participantId,
                    'status'         => rand(1, 5) === 1 ? 'waitlisted' : 'registered',
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ];
            }
        }
        foreach (array_chunk($pivotRows, 500) as $chunk) {
            DB::table('event_participant')->insert($chunk);
        }

        // A few notes, as in Week 10
        Event::orderBy('id')->first()->notes()->create(['body' => 'Bring your school ID.']);
        Venue::first()->notes()->create(['body' => 'Projector needs a technician.']);
    }
}