// DOM Event & Confirmation before deleting a record
function confirmDelete() {
    return confirm("Are you sure you want to delete this item?");
}

// Client-side form validation using DOM
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("itemForm");
    if (form) {
        form.addEventListener("submit", function (e) {
            const qty = parseFloat(document.getElementById("quantity").value);
            const price = parseFloat(document.getElementById("price").value);

            if (qty < 0 || price < 0) {
                alert("Quantity and Price cannot be negative!");
                e.preventDefault();
            }
        });
    }
});