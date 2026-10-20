document.addEventListener("DOMContentLoaded", () => {
    const toast = document.getElementById("toast");
    let toastTimer;

    function showToast(message) {
        toast.textContent = message;
        toast.classList.add("show");
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.remove("show"), 2600);
    }

    // Tombol yang fiturnya belum dibuat memberi informasi, bukan error.
    document.querySelectorAll("[data-message]").forEach((button) => {
        button.addEventListener("click", () => {
            showToast(button.dataset.message);
        });
    });

    // Pencarian produk berdasarkan nama, atelier, atau lokasi.
    const searchInput = document.getElementById("searchInput");
    const productCards = [...document.querySelectorAll(".product-card")];
    const emptyProducts = document.getElementById("emptyProducts");

    function filterProducts() {
        const keyword = searchInput.value.trim().toLowerCase();
        let visibleCount = 0;

        productCards.forEach((card) => {
            const matches = card.dataset.name.includes(keyword);
            card.hidden = !matches;
            if (matches) visibleCount++;
        });

        emptyProducts.hidden = visibleCount !== 0;
    }

    document.getElementById("searchForm").addEventListener("submit", (event) => {
        event.preventDefault();
        filterProducts();
        document.getElementById("productGrid").scrollIntoView({ behavior: "smooth", block: "start" });
    });

    searchInput.addEventListener("input", filterProducts);

    // Filter kategori koleksi.
    const filterButtons = document.querySelectorAll(".filter-chip");

    filterButtons.forEach((button) => {
        button.addEventListener("click", () => {
            filterButtons.forEach((item) => item.classList.remove("active"));
            button.classList.add("active");

            const filter = button.dataset.filter;
            let visibleCount = 0;

            productCards.forEach((card) => {
                const category = card.dataset.category;
                const matches = filter === "Semua"
                    || category === filter
                    || (filter === "Jas" && card.dataset.name.includes("jas"))
                    || (filter === "Seragam" && card.dataset.name.includes("seragam"));

                card.hidden = !matches;
                if (matches) visibleCount++;
            });

            emptyProducts.hidden = visibleCount !== 0;
            searchInput.value = "";
        });
    });

    // Klik kategori di bagian atas mengarahkan filter produk.
    document.querySelectorAll(".category-chip").forEach((button) => {
        button.addEventListener("click", () => {
            const category = button.dataset.category;
            document.querySelectorAll(".category-chip").forEach((item) => item.classList.remove("selected"));
            button.classList.add("selected");

            const matchingFilter = [...filterButtons].find((item) => item.dataset.filter === category);
            filterButtons.forEach((item) => item.classList.remove("active"));

            if (matchingFilter) {
                matchingFilter.classList.add("active");
            } else {
                document.querySelector('[data-filter="Semua"]').classList.add("active");
            }

            let visibleCount = 0;
            productCards.forEach((card) => {
                const matches = card.dataset.category === category;
                card.hidden = !matches;
                if (matches) visibleCount++;
            });

            emptyProducts.hidden = visibleCount !== 0;
            document.getElementById("productGrid").scrollIntoView({ behavior: "smooth", block: "start" });
        });
    });

    // Tombol hati untuk menandai produk favorit secara visual.
    document.querySelectorAll(".heart-button").forEach((button) => {
        button.addEventListener("click", () => {
            const liked = button.classList.toggle("liked");
            button.textContent = liked ? "♥" : "♡";
            button.setAttribute("aria-pressed", String(liked));
            showToast(liked ? "Produk ditambahkan ke favorit." : "Produk dihapus dari favorit.");
        });
    });
});
