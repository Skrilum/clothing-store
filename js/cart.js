document.addEventListener('DOMContentLoaded', function() {
    // Обновление суммы при изменении количества
    document.querySelectorAll('input[name="quantity"]').forEach(input => {
        input.addEventListener('change', function() {
            const form = this.closest('form');
            const price = parseFloat(form.querySelector('.price').dataset.price);
            const quantity = parseInt(this.value);
            const subtotal = price * quantity;
            
            form.querySelector('.subtotal').textContent = subtotal.toFixed(2) + ' руб.';
            updateTotal();
        });
    });
    
    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(el => {
            total += parseFloat(el.textContent);
        });
        document.getElementById('cart-total').textContent = total.toFixed(2) + ' руб.';
    }
});