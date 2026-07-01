document.addEventListener('DOMContentLoaded', function () {
    initializeTicketSelector();
    initializeSearch();
    initializeSmoothScroll();
    initializeObserverAnimation();
});


// ==========================================
// TICKET SELECTOR
// ==========================================
function initializeTicketSelector() {
    const ticketContainers = document.querySelectorAll('.ticket-counter');

    ticketContainers.forEach(container => {
        const minusBtn = container.querySelector('.minus');
        const plusBtn = container.querySelector('.plus');
        const quantityText = container.querySelector('.quantity');

        let quantity = parseInt(quantityText.innerText);

        minusBtn?.addEventListener('click', () => {
            if (quantity > 0) {
                quantity--;
                quantityText.innerText = quantity;
                updateOrderSummary();
            }
        });

        plusBtn?.addEventListener('click', () => {
            quantity++;
            quantityText.innerText = quantity;
            updateOrderSummary();
        });
    });
}


// ==========================================
// UPDATE ORDER SUMMARY
// ==========================================
function updateOrderSummary() {
    const prices = {
        adult: 25000,
        student: 15000,
        child: 10000
    };

    const adultQty = parseInt(document.querySelector('#adult-qty')?.innerText || 0);
    const studentQty = parseInt(document.querySelector('#student-qty')?.innerText || 0);
    const childQty = parseInt(document.querySelector('#child-qty')?.innerText || 0);

    const total =
        (adultQty * prices.adult) +
        (studentQty * prices.student) +
        (childQty * prices.child);

    const totalElement = document.querySelector('#total-price');

    if (totalElement) {
        totalElement.innerText = 'Rp ' + total.toLocaleString('id-ID');
    }
}


// ==========================================
// SEARCH FUNCTION
// ==========================================
function initializeSearch() {
    const searchInput = document.querySelector('.search-box input');
    if (!searchInput) return;

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        const cards = document.querySelectorAll('.museum-card');

        cards.forEach(card => {
            const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const location = card.querySelector('.location')?.textContent.toLowerCase() || '';

            card.style.display =
                title.includes(query) || location.includes(query)
                    ? 'block'
                    : 'none';
        });
    });
}


// ==========================================
// SMOOTH SCROLL
// ==========================================
function initializeSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            const target = document.querySelector(this.getAttribute('href'));

            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
}


// ==========================================
// FADE-IN ANIMATION
// ==========================================
function initializeObserverAnimation() {
    const elements = document.querySelectorAll('.fade-up');

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
            }
        });
    }, {
        threshold: 0.2
    });

    elements.forEach(el => observer.observe(el));
}


// ==========================================
// VALIDATE BOOKING
// ==========================================
window.validateBooking = function () {
    const dateInput = document.querySelector('input[type="date"]');
    const totalElement = document.querySelector('#total-price');

    if (!dateInput || !dateInput.value) {
        alert('Silakan pilih tanggal terlebih dahulu.');
        return false;
    }

    if (!totalElement || totalElement.innerText === 'Rp 0') {
        alert('Silakan pilih minimal 1 tiket.');
        return false;
    }

    return true;
};


// ==========================================
// QR SCAN SUCCESS
// ==========================================
window.onScanSuccess = function (decodedText) {
    if (window.isProcessing) return;

    window.isProcessing = true;

    try {
        window.beep?.play();
    } catch (e) {}

    const inputField = document.getElementById('qr_code');
    const resultContainer = document.getElementById('result');
    const submitBtn = document.getElementById('submit-btn');

    if (!inputField || !resultContainer || !submitBtn) return;

    inputField.value = decodedText;

    resultContainer.className =
        "min-h-[80px] flex items-center p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-sm text-zinc-800";

    resultContainer.innerHTML = `
        <div class="w-full">
            <p class="font-bold text-emerald-800 mb-1">QR Berhasil Dibaca</p>
            <p class="font-mono text-xs text-zinc-600 bg-white p-2 rounded border break-all">
                ${decodedText}
            </p>
        </div>
    `;

    submitBtn.disabled = false;
    submitBtn.innerHTML = 'Validasi Tiket';

    window.html5QrcodeScanner?.clear();
};
