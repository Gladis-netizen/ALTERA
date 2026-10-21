document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(
        'form[action="register.php"]'
    );

    if (!form) return;

    form.addEventListener("submit", function (event) {
        const password = document.getElementById("password");
        const konfirmasi = document.getElementById("konfirmasi");

        if (password.value !== konfirmasi.value) {
            event.preventDefault();
            alert("Konfirmasi password belum cocok.");
            konfirmasi.focus();
        }
    });
});
