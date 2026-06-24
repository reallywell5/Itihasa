@extends('layouts.frontend')

@section('title', 'Discover Ancient History')

@section('content')
<div class="container">
    <div class="hero">
        <h1>Discover Ancient History</h1>
        <p>Book tickets to the most prestigious museums across the country</p>
        
        <div class="search-box">
            <input type="text" placeholder="Search for museums, exhibitions, or cities..." id="searchInput">
        </div>
    </div>

    <h2>Featured Museums</h2>
    <div class="museum-grid">
        @forelse($museums as $museum)
        <div class="museum-card">
            @php
                $imagePath = $museum->image;
            @endphp

            @if($imagePath)
                <img src="{{ asset('storage/' . $imagePath) }}" 
                     alt="{{ $museum->name }}" 
                     style="width: 100%; height: 200px; object-fit: cover;">
            @else
                <img src="{{ asset('images/default-museum.jpg') }}" 
                     alt="Default Museum" 
                     style="width: 100%; height: 200px; object-fit: cover; background: #eee;">
            @endif

            <div class="content">
                <h3>{{ $museum->name }}</h3>
                <p class="location">📍 {{ $museum->address ?? 'Location not specified' }}</p>
                <p class="hours">🕐 {{ date('h:i A', strtotime($museum->opening_time)) }} - {{ date('h:i A', strtotime($museum->closing_time)) }}</p>
                <a href="{{ route('booking', ['museum' => $museum->id]) }}" class="btn">Book Now</a>
            </div>
        </div>
        @empty
        <!-- Dummy data jika belum ada museum -->
        <div class="museum-card">
            <div class="content">
                <h3>National Museum</h3>
                <p class="location">📍 New Delhi</p>
                <p class="hours">🕐 10:00 AM - 6:00 PM</p>
                <a href="{{ route('booking', ['museum' => 1]) }}" class="btn">Book Now</a>
            </div>
        </div>
        <div class="museum-card">
            <div class="content">
                <h3>Indian Museum</h3>
                <p class="location">📍 Kolkata</p>
                <p class="hours">🕐 10:00 AM - 5:00 PM</p>
                <a href="{{ route('booking', ['museum' => 2]) }}" class="btn">Book Now</a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection