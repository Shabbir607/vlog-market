@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-5 mb-4">Marketplaces in {{ $selectedCountry }}</h1>

        <p class="lead">Selected Country: {{ $selectedCountry }}</p>

        <div class="row">
            <div class="col-md-6">
                <h2 class="mb-3">Marketplaces:</h2>
                <ul class="list-group">
                    
                    @forelse ($marketplaces as $marketplace)
                        <li class="list-group-item">{{ $marketplace->name }}</li>
                    @empty
                        <li class="list-group-item">No marketplaces found for {{ $selectedCountry }}</li>
                    @endforelse
                </ul>
            </div>

            <div class="col-md-6">
                <h2 class="mb-3">Category Information:</h2>
                <p>Title: {{ $categoryInfo->title ?? 'N/A' }}</p>
                <p>Total Markets: {{ $categoryInfo->markets_count ?? 0 }}</p>
            </div>
        </div>
    </div>
@endsection
