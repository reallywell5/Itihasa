@extends('layouts.frontend')

@section('title', 'About the Museum')

@section('content')
<div class="container">
    <div class="about-container">
        <h1>About the Museum</h1>
        
        <div class="about-section">
            <h2>Our Story</h2>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            </p>
        </div>

        <div class="about-section">
            <h2>Opening Hours</h2>
            <div class="hours-grid">
                <div class="hours-item">
                    <span>Monday - Friday</span>
                    <span>09:00 AM - 17:00 PM</span>
                </div>
                <div class="hours-item">
                    <span>Saturday</span>
                    <span>10:00 AM - 18:00 PM</span>
                </div>
                <div class="hours-item">
                    <span>Sunday</span>
                    <span style="color: #d32f2f;">Closed</span>
                </div>
            </div>
        </div>

        <div class="about-section">
            <h2>Location Map</h2>
            <div class="map-placeholder">
                <div style="text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">🗺️</div>
                    <div>Map Widget Placeholder</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection