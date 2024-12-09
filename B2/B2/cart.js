// JavaScript to update total price and item totals
document.addEventListener('DOMContentLoaded', function () {
    const quantityInputs = document.querySelectorAll('.quantity');
    const priceElements = document.querySelectorAll('.price');
    const totalElements = document.querySelectorAll('.total');
    const totalPriceElement = document.getElementById('total-price');

    // Function to update the total for each item and the overall total
    function updateCart() {
        let totalPrice = 0;

        quantityInputs.forEach((input, index) => {
            const quantity = input.value;
            const price = priceElements[index].getAttribute('data-price');
            const itemTotal = quantity * price;

            totalElements[index].textContent = '$' + itemTotal;
            totalPrice += itemTotal;
        });

        totalPriceElement.textContent = '$' + totalPrice;
    }

    // Add event listener to each quantity input
    quantityInputs.forEach(input => {
        input.addEventListener('input', updateCart);
    });

    // Initial update of the cart totals
    updateCart();
});