# Campus Events: Relational Data Model (Week 10)

Laravel + PostgreSQL data layer for a small Campus Events system.

## Setup

1. `composer install`
2. Copy `.env.example` to `.env`, set the `DB_` values for PostgreSQL, then run `php artisan key:generate`
3. Create a database named `campus_events`
4. `php artisan migrate:fresh --seed`
5. `php artisan serve`, then open `http://localhost:8000/report`

## Relationships

| Link | Type | Model holding the foreign key | Methods | ON DELETE | Reason |
|---|---|---|---|---|---|
| Venue and Event | One-to-many | `Event` (`events.venue_id`) | `Venue::events()` (hasMany), `Event::venue()` (belongsTo) | RESTRICT | A venue that still has events must not be deletable. |
| Event and Participant | Many-to-many | Pivot `event_participant` (`event_id`, `participant_id`) | `Event::participants()`, `Participant::events()` (both belongsToMany, with `status` on the pivot) | CASCADE (both keys) | Deleting an event or participant should remove its registrations. |
| Note and Event / Venue | Polymorphic one-to-many | `Note` (`notable_type`, `notable_id`) | `Event::notes()`, `Venue::notes()` (morphMany), `Note::notable()` (morphTo) | None (no foreign key possible) | One column pair can point to two tables, so PostgreSQL cannot enforce it; only Laravel code can. |

## Notes

- The pivot table has a composite primary key (`event_id`, `participant_id`), so the same participant cannot register for the same event twice.
- `status` on the pivot is `registered` or `waitlisted`.