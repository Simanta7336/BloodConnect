<!DOCTYPE html>
<html>
<head>
    <title>Search Donors</title>
</head>
<body>

    <h1>Search Blood Group</h1>

    <form method="GET" action="{{ route('donors.search') }}">

        <label for="blood_group">Blood Group:</label>

        <select name="blood_group" id="blood_group" required>
            <option value="">Select Blood Group</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
        </select>

        <button type="submit">Search</button>

    </form>

    @if(isset($donors))

        <h2>Available donors with {{ $bloodGroup }} Blood Group. </h2>

        @forelse($donors as $donor)

            <div style="margin-bottom: 60px;">
                <p> {{ $loop->iteration }}.{{ $donor->name }}</p>
                <p> Blood Group: {{ $donor->blood_group }}</p>
                <p> Location: {{ $donor->location }}</p>
                <p> Phone Number: {{ $donor->phone }}</p>
            </div>

        @empty

            <p>No donors found for {{ $bloodGroup }}.</p>

        @endforelse

    @endif

<script>
    if (window.location.search) {
        window.history.replaceState({}, document.title, window.location.pathname);
    }
</script>
</body>
</html>