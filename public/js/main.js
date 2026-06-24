// Main JavaScript for all pages

document.addEventListener('DOMContentLoaded', function() {
    // Initialize components
    initializeTicketSelector();
    initializeSearch();
    initializeDatePicker();
    initializeBookingActions();
});

// Ticket selector functionality
function initializeTicketSelector() {
    const selects = document.querySelectorAll('.ticket-select');
    const summaryItems = document.querySelector('.summary-items');
    const totalDisplay = document.querySelector('.total-amount');
    
    if (!selects.length) return;
    
    // Prices
    const prices = {
        adult: 25.00,
        student: 15.00,
        child: 10.00
    };
    
    // Update summary when ticket count changes
    selects.forEach(select => {
        select.addEventListener('change', function() {
            updateOrderSummary(prices);
        });
    });
    
    // Initial update
    updateOrderSummary(prices);
}

function updateOrderSummary(prices) {
    const adultSelect = document.querySelector('#adult-tickets');
    const studentSelect = document.querySelector('#student-tickets');
    const childSelect = document.querySelector('#child-tickets');
    const summaryItems = document.querySelector('.summary-items');
    const totalDisplay = document.querySelector('.total-amount');
    
    if (!adultSelect || !summaryItems) return;
    
    const adultCount = parseInt(adultSelect.value) || 0;
    const studentCount = parseInt(studentSelect?.value) || 0;
    const childCount = parseInt(childSelect?.value) || 0;
    
    // Clear existing items
    summaryItems.innerHTML = '';
    
    let total = 0;
    
    // Add adult tickets
    if (adultCount > 0) {
        const subtotal = adultCount * prices.adult;
        total += subtotal;
        summaryItems.innerHTML += `
            <div class="order-item">
                <span>${adultCount}x Adult</span>
                <span>$${subtotal.toFixed(2)}</span>
            </div>
        `;
    }
    
    // Add student tickets
    if (studentCount > 0) {
        const subtotal = studentCount * prices.student;
        total += subtotal;
        summaryItems.innerHTML += `
            <div class="order-item">
                <span>${studentCount}x Student</span>
                <span>$${subtotal.toFixed(2)}</span>
            </div>
        `;
    }
    
    // Add child tickets
    if (childCount > 0) {
        const subtotal = childCount * prices.child;
        total += subtotal;
        summaryItems.innerHTML += `
            <div class="order-item">
                <span>${childCount}x Child</span>
                <span>$${subtotal.toFixed(2)}</span>
            </div>
        `;
    }
    
    // Update total
    if (totalDisplay) {
        totalDisplay.textContent = `$${total.toFixed(2)}`;
    }
    
    // Update continue button
    const continueBtn = document.querySelector('.btn-continue');
    if (continueBtn) {
        if (total > 0 && adultCount > 0) {
            continueBtn.disabled = false;
            continueBtn.style.opacity = '1';
            continueBtn.style.cursor = 'pointer';
        } else {
            continueBtn.disabled = true;
            continueBtn.style.opacity = '0.5';
            continueBtn.style.cursor = 'not-allowed';
        }
    }
}

// Search functionality
function initializeSearch() {
    const searchInput = document.querySelector('.search-box input');
    if (!searchInput) return;
    
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const cards = document.querySelectorAll('.museum-card');
        
        cards.forEach(card => {
            const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const location = card.querySelector('.location')?.textContent.toLowerCase() || '';
            
            if (title.includes(query) || location.includes(query)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
}

// Date picker placeholder
function initializeDatePicker() {
    const datePlaceholder = document.querySelector('.calendar-placeholder');
    const selectedDateDisplay = document.querySelector('.selected-date');
    
    if (!datePlaceholder) return;
    
    datePlaceholder.addEventListener('click', function() {
        // Create a simple date picker
        const dateInput = document.createElement('input');
        dateInput.type = 'date';
        dateInput.style.display = 'none';
        document.body.appendChild(dateInput);
        
        dateInput.addEventListener('change', function() {
            const date = new Date(this.value);
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const formattedDate = date.toLocaleDateString('en-US', options);
            
            if (selectedDateDisplay) {
                selectedDateDisplay.textContent = formattedDate;
            }
            
            // Update calendar placeholder text
            const placeholderText = datePlaceholder.querySelector('p');
            if (placeholderText) {
                placeholderText.textContent = `Selected: ${formattedDate}`;
                placeholderText.style.color = '#2c1810';
            }
            
            this.remove();
        });
        
        dateInput.click();
    });
}

// Booking actions
function initializeBookingActions() {
    // Handle continue button
    const continueBtn = document.querySelector('.btn-continue');
    if (continueBtn) {
        continueBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Show loading state
            const originalText = this.textContent;
            this.textContent = 'Processing...';
            this.disabled = true;
            
            // Simulate processing
            setTimeout(() => {
                // Get booking data
                const adultCount = document.querySelector('#adult-tickets')?.value || 0;
                const selectedDate = document.querySelector('.selected-date')?.textContent || 'Today';
                
                if (adultCount > 0) {
                    // Redirect to success page with data
                    const params = new URLSearchParams({
                        date: selectedDate,
                        adults: adultCount,
                        total: document.querySelector('.total-amount')?.textContent || '$0.00'
                    });
                    
                    window.location.href = `/success?${params.toString()}`;
                } else {
                    alert('Please select at least one adult ticket.');
                    this.textContent = originalText;
                    this.disabled = false;
                }
            }, 1000);
        });
    }
    
    // Handle booking buttons on home page
    const bookButtons = document.querySelectorAll('.museum-card .btn');
    bookButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const museumName = this.closest('.museum-card').querySelector('h3')?.textContent || 'Museum';
            window.location.href = `/booking?museum=${encodeURIComponent(museumName)}`;
        });
    });
}

// Success page - download PDF simulation
function initializeDownloadPDF() {
    const downloadBtn = document.querySelector('.btn-group .btn:first-child');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Show loading
            const originalText = this.textContent;
            this.textContent = 'Generating PDF...';
            this.disabled = true;
            
            setTimeout(() => {
                alert('PDF download would start here. (Simulated)');
                this.textContent = originalText;
                this.disabled = false;
            }, 1500);
        });
    }
    
    // Share ticket
    const shareBtn = document.querySelector('.btn-group .btn:last-child');
    if (shareBtn) {
        shareBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (navigator.share) {
                navigator.share({
                    title: 'Itihasa - Museum Ticket',
                    text: 'Check out my museum ticket for National History Museum!',
                    url: window.location.href
                }).catch(() => {
                    // Fallback
                    alert('Share this ticket with others!');
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                alert('Copy this link to share your ticket!');
                
                // Simulate copying to clipboard
                navigator.clipboard?.writeText(window.location.href).then(() => {
                    alert('Link copied to clipboard!');
                }).catch(() => {
                    alert('Please share this ticket manually.');
                });
            }
        });
    }
}

// Call success page functions if on success page
if (window.location.pathname.includes('/success')) {
    initializeDownloadPDF();
}

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add animation to cards on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

document.querySelectorAll('.museum-card, .about-section, .success-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
});

// Form validation helper
function validateBooking() {
    const adultSelect = document.querySelector('#adult-tickets');
    const dateDisplay = document.querySelector('.selected-date');
    
    if (!adultSelect || !dateDisplay) return true;
    
    const adultCount = parseInt(adultSelect.value) || 0;
    const hasDate = dateDisplay.textContent !== '---' && dateDisplay.textContent !== '';
    
    if (adultCount === 0) {
        alert('Please select at least one adult ticket.');
        return false;
    }
    
    if (!hasDate) {
        alert('Please select a date.');
        return false;
    }
    
    return true;
}