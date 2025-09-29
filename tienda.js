function filterCat(categoria) {
    const productos = document.querySelectorAll('.product-card');
    const botones = document.querySelectorAll('.cat-btn');

    // Maneja cuál botón se ve activo
    botones.forEach(btn => {
        const btnCategory = btn.textContent.trim().toLowerCase();
        if ((categoria === 'todos' && btnCategory === 'todos') || (btnCategory === categoria)) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    // Muestra u oculta los productos
    productos.forEach(prod => {
        if (categoria === 'todos' || prod.dataset.cat === categoria) {
            prod.style.display = 'block';
        } else {
            prod.style.display = 'none';
        }
    });
}