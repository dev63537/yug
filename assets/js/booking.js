/* Booking Flow JavaScript */
document.addEventListener('DOMContentLoaded', function() {
    const seatInput = document.getElementById('seats_count');
    const unitPrice = document.getElementById('unit_price_val');
    const totalPrice = document.getElementById('total_price_val');
    
    if (seatInput && unitPrice && totalPrice) {
        seatInput.addEventListener('input', function() {
            const seats = parseInt(this.value) || 1;
            const price = parseFloat(unitPrice.value) || 0;
            totalPrice.textContent = (seats * price).toFixed(2);
        });
    }
});
