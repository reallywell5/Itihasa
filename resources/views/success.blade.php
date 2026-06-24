@extends('layouts.frontend')

@section('title', 'Booking Successful')

@section('content')
<div class="container">
    <div class="success-container">
        <div class="success-card">
            <div class="success-icon">✅</div>
            <h2>Booking Successful</h2>
            
            <h3 style="margin-top: 1.5rem;">{{ $transaction->museum->name ?? 'National History Museum' }}</h3>
            <p style="color: #666;">{{ \Carbon\Carbon::parse($transaction->date)->format('l, d F Y') ?? 'Monday, 12 June 2024' }}</p>
            
            <div class="qr-placeholder">
                @if(isset($transaction->qr_code))
                    <img src="data:image/png;base64,{{ $transaction->qr_code }}" alt="QR Code" style="width: 200px; height: 200px;">
                @else
                    <div style="text-align: center;">
                        <div style="font-size: 3rem; margin-bottom: 0.5rem;">■</div>
                        <div style="font-size: 0.8rem;">QR Code</div>
                    </div>
                @endif
            </div>
            
            <div class="booking-id">
                <strong>Booking ID</strong><br>
                #{{ $transaction->booking_id ?? 'IT-29482' }}
            </div>
            
            <div class="visitor-info">
                <strong>Visitor</strong><br>
                {{ $transaction->name ?? 'John Doe' }} 
                @if(isset($transaction->tickets))
                    @php
                        $tickets = json_decode($transaction->tickets, true);
                        $totalTickets = collect($tickets)->sum('quantity');
                        $firstTicket = collect($tickets)->first();
                    @endphp
                    ({{ $totalTickets }} {{ $firstTicket['name'] ?? 'Adult' }})
                @endif
            </div>
            
            <div class="btn-group">
                <button class="btn btn-primary" onclick="downloadPDF()">📄 Download PDF</button>
                <button class="btn" onclick="shareTicket()">📤 Share Ticket</button>
            </div>

            <div style="margin-top: 2rem;">
                <a href="{{ route('home') }}" class="btn" style="background: #2c1810;">Back to Home</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function downloadPDF() {
    // You can implement PDF generation here using libraries like jsPDF or DomPDF
    alert('PDF download would start here. (Simulated)');
}

function shareTicket() {
    if (navigator.share) {
        navigator.share({
            title: 'Itihasa - Museum Ticket',
            text: 'Check out my museum ticket!',
            url: window.location.href
        }).catch(() => {
            alert('Share this ticket with others!');
        });
    } else {
        // Fallback
        navigator.clipboard?.writeText(window.location.href).then(() => {
            alert('Link copied to clipboard!');
        }).catch(() => {
            alert('Please share this ticket manually.');
        });
    }
}
</script>
@endpush