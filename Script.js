document.querySelector("form").addEventListener("submit", function(e) {
    let email = document.querySelector("input[name='email']").value;
    let password = document.querySelector("input[name='password']").value;

    if (!email || !password) {
        alert("Please fill in both fields.");
        e.preventDefault();
    }
});