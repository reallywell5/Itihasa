@extends('layouts.frontend')

@section('title', 'Book Tickets')

@section('content')
<div class="container">
    <h1>Itihasa</h1>
    <h2 style="margin-bottom: 2rem;">Select Date</h2>

    <div class="booking-container">
        <div>
            <!-- Calendar -->
            <div class="calendar-placeholder" id="calendarPlaceholder">
                <h3>📅 Select Date</h3>
                <p>Click to select date</p>
                <input type="date" id="bookingDate" style="margin-top: 1rem; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>

            <h3 style="margin-top: 2rem;">Select Tickets</h3>
            <form id="bookingForm" action="{{ route('booking.store') }}" method="POST">
                @csrf
                <input type="hidden" name="museum_id" value="{{ $museum->id ?? '' }}">
                <input type="hidden" name="date" id="selectedDate" value="">
                
                <table class="ticket-table">
                    <thead>
                        <tr>
                            <th>Ticket Type</th>
                            <th>Price</th>
                            <th>Available</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets ?? [] as $ticket)
                        <tr>
                            <td>{{ $ticket->ticket_name }}</td>
                            <td>${{ number_format($ticket->price, 2) }}</td>
                            <td>{{ $ticket->slot }} slots</td>
                            <td>
                                <select class="ticket-select" name="tickets[{{ $ticket->id }}]" data-price="{{ $ticket->price }}" data-name="{{ $ticket->ticket_name }}">
                                    <option value="0">0</option>
                                    @for($i = 1; $i <= min(5, $ticket->slot); $i++)
                                        <option value="{{ $i }}" {{ $i == 2 && $loop->first ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem; color: #999;">No tickets available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Visitor Information -->
                <div style="margin-top: 2rem; background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <h3>Visitor Information</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                        <div>
                            <label style="display: block; margin-bottom: 0.3rem; font-weight: bold;">Full Name *</label>
                            <input type="text" name="name" required style="width: 100%; padding: 0.7rem; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 0.3rem; font-weight: bold;">Email *</label>
                            <input type="email" name="email" required style="width: 100%; padding: 0.7rem; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 0.3rem; font-weight: bold;">Phone *</label>
                            <input type="tel" name="phone" required style="width: 100%; padding: 0.7rem; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div>
            <div class="order-summary">
                <h3>Order Summary</h3>
                <p><strong>{{ $museum->name ?? 'National Museum' }}</strong></p>
                <p>Selected Date: <span class="selected-date" id="selectedDateDisplay">---</span></p>
                
                <div class="summary-items" id="summaryItems">
                    <div class="order-item">
                        <span>2x Adult</span>
                        <span>$50.00</span>
                    </div>
                </div>
                
                <div class="order-total">
                    <span>Total</span>
                    <span class="total-amount" id="totalAmount">$50.00</span>
                </div>
                
                <button class="btn-continue" id="continueBooking">CONTINUE</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('bookingDate');
    const dateDisplay = document.getElementById('selectedDateDisplay');
    const selectedDateHidden = document.getElementById('selectedDate');
    
    dateInput.addEventListener('change', function() {
        const date = new Date(this.value);
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const formattedDate = date.toLocaleDateString('en-US', options);
        dateDisplay.textContent = formattedDate;
        selectedDateHidden.value = this.value;
    });

    const selects = document.querySelectorAll('.ticket-select');
    const summaryItems = document.getElementById('summaryItems');
    const totalDisplay = document.getElementById('totalAmount');

    function updateSummary() {
        let total = 0;
        let items = [];
        
        selects.forEach(select => {
            const quantity = parseInt(select.value);
            if (quantity > 0) {
                const price = parseFloat(select.dataset.price);
                const name = select.dataset.name || select.closest('tr').querySelector('td:first-child').textContent.trim();
                const subtotal = quantity * price;
                total += subtotal;
                items.push({ name, quantity, subtotal });
            }
        });

        if (items.length === 0) {
            summaryItems.innerHTML = '<div class="order-item"><span>No tickets selected</span><span>$0.00</span></div>';
        } else {
            summaryItems.innerHTML = items.map(item => `
                <div class="order-item">
                    <span>${item.quantity}x ${item.name}</span>
                    <span>$${item.subtotal.toFixed(2)}</span>
                </div>
            `).join('');
        }

        totalDisplay.textContent = `$${total.toFixed(2)}`;
    }

    selects.forEach(select => {
        select.addEventListener('change', updateSummary);
    });

    updateSummary();

    document.getElementById('continueBooking').addEventListener('click', function() {
        const date = document.getElementById('bookingDate').value;
        if (!date) {
            alert('Please select a date first!');
            return;
        }
        
        let hasTicket = false;
        document.querySelectorAll('.ticket-select').forEach(select => {
            if (parseInt(select.value) > 0) hasTicket = true;
        });
        
        if (!hasTicket) {
            alert('Please select at least one ticket!');
            return;
        }
        
        document.getElementById('bookingForm').submit();
    });
});
</script>
@endpush