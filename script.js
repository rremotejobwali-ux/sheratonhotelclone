document.addEventListener('DOMContentLoaded', function() {
    // Date Validation
    const checkinInputs = document.querySelectorAll('input[name="checkin"]');
    const checkoutInputs = document.querySelectorAll('input[name="checkout"]');

    if (checkinInputs.length > 0 && checkoutInputs.length > 0) {
        checkinInputs.forEach((input, index) => {
            input.addEventListener('change', function() {
                // When checkin changes, set min attribute of checkout
                if (checkoutInputs[index]) {
                    checkoutInputs[index].min = this.value;
                    
                    // If checkout is before new checkin, clear it
                    if (checkoutInputs[index].value && checkoutInputs[index].value < this.value) {
                        checkoutInputs[index].value = this.value;
                    }
                }
            });
        });

        // Set min date to today for all date inputs
        const today = new Date().toISOString().split('T')[0];
        checkinInputs.forEach(input => input.setAttribute('min', today));
        checkoutInputs.forEach(input => input.setAttribute('min', today));
    }

    // Interactive Star Rating (if we had a review form, but just for show here)
    console.log("Sheraton Hotel Clone scripts loaded.");
});
document.addEventListener('DOMContentLoaded', function() {
    // Date Validation
    const checkinInputs = document.querySelectorAll('input[name="checkin"]');
    const checkoutInputs = document.querySelectorAll('input[name="checkout"]');

    if (checkinInputs.length > 0 && checkoutInputs.length > 0) {
        checkinInputs.forEach((input, index) => {
            input.addEventListener('change', function() {
                // When checkin changes, set min attribute of checkout
                if (checkoutInputs[index]) {
                    checkoutInputs[index].min = this.value;
                    
                    // If checkout is before new checkin, clear it
                    if (checkoutInputs[index].value && checkoutInputs[index].value < this.value) {
                        checkoutInputs[index].value = this.value;
                    }
                }
            });
        });

        // Set min date to today for all date inputs
        const today = new Date().toISOString().split('T')[0];
        checkinInputs.forEach(input => input.setAttribute('min', today));
        checkoutInputs.forEach(input => input.setAttribute('min', today));
    }

    // Interactive Star Rating (if we had a review form, but just for show here)
    console.log("Sheraton Hotel Clone scripts loaded.");
});
