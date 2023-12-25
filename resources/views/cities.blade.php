<!-- resources/views/cities.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucfirst($countryCode) }} Cities</title>
</head>
<body>
    <h1>{{ ucfirst($countryCode) }} Cities</h1>

    <form action="{{ route('selectCity') }}" method="post">
        @csrf
        <label for="city">Choose a city:</label>
        <select name="city" id="city">
            @foreach($cities as $city)
                <option value="{{ $city }}">{{ $city }}</option>
            @endforeach
        </select>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
