$(document).ready(function () {
    // Handle modal display
    $("#addExpenseButton").click(function () {
        $("#addExpenseModal").css("display", "block");
    });

    // Close the modal when the close button is clicked
    $("#closeModalButton").click(function () {
        $("#addExpenseModal").css("display", "none");
    });

    // Handle modal form submission
    $("#expenseForm").submit(function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "add_expense.php",
            data: formData,
            success: function (response) {
                // Close the modal and refresh the table
                $("#addExpenseModal").css("display", "none");
                $("#expensesTable").html(response);
            }
        });
    });
});
