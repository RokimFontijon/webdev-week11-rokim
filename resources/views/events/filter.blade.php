<!DOCTYPE html>
<html>
<head>
    <title>Filter events</title>
</head>
<body>
    <h1>Events with dress code: {{ $code }}</h1>

    <h3>Arrow form: where('details->dress_code', ...)</h3>
    <pre>{{ $arrowSql }}</pre>
    <pre>Bindings: {{ json_encode($arrowBindings) }}</pre>
    <p>Rows matched: {{ $arrowCount }}</p>

    <h3>Containment form: whereJsonContains('details', [...])</h3>
    <pre>{{ $containsSql }}</pre>
    <pre>Bindings: {{ json_encode($containsBindings) }}</pre>
    <p>Rows matched: {{ $containsCount }}</p>

    <h3>Upcoming events with this dress code (first 50, scopes chained)</h3>
    <table border="1" cellpadding="6">
        <tr>
            <th>Title</th>
            <th>Venue</th>
            <th>Starts</th>
        </tr>
        @foreach ($events as $event)
            <tr>
                <td>{{ $event->title }}</td>
                <td>{{ $event->venue->name }}</td>
                <td>{{ $event->starts_at->format('M d, Y g:i A') }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>