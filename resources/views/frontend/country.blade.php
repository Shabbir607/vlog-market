<!-- resources/views/select_country.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Country</title>
</head>
<body>
    <h1>Vlog Market is available on following countries</h1>

    <form action="{{ route('postCountry') }}" method="post">
        @csrf
        <label for="country">Choose your country:</label>
        <select name="country" id="country">
            @foreach($countries as $code => $name)
                <option value="{{ $code }}">{{ $name }}</option>
            @endforeach
        </select>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
