// Confirm before deleting the account
const deleteButton = document.querySelector(".delete-account");

deleteButton.addEventListener("click", () => {
    const confirmation = confirm("Are you sure you want to delete your account? This action cannot be undone.");
    if (confirmation) {
        alert("Your account has been deleted.");
        // Logic for deleting the account can be added here
    }
});

// Highlight button on hover
const buttons = document.querySelectorAll("button");

buttons.forEach(button => {
    button.addEventListener("mouseover", () => {
        button.style.transform = "scale(1.1)";
        button.style.transition = "transform 0.3s ease";
    });
    button.addEventListener("mouseout", () => {
        button.style.transform = "scale(1)";
    });
});

// Show a toast message when a button is clicked
buttons.forEach(button => {
    button.addEventListener("click", () => {
        const toast = document.createElement("div");
        
        toast.style.position = "fixed";
        toast.style.bottom = "20px";
        toast.style.right = "20px";
        toast.style.backgroundColor = "#444";
        toast.style.color = "#fff";
        toast.style.padding = "10px 20px";
        toast.style.borderRadius = "5px";
        toast.style.boxShadow = "0 2px 5px rgba(0, 0, 0, 0.2)";
        toast.style.zIndex = "1000";
        toast.style.opacity = "1";
        toast.style.transition = "opacity 0.5s ease";

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = "0";
            setTimeout(() => {
                toast.remove();
            }, 500);
        }, 2000);
    });
});