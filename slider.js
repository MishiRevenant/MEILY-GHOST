let current = 0;
const slides = document.querySelectorAll('.slide');
document.getElementById('next').onclick = () => move(1);
document.getElementById('prev').onclick = () => move(-1);

function move(dir) {
    slides[current].classList.remove('active');
    current = (current + dir + slides.length) % slides.length;
    slides[current].classList.add('active');
}
// Auto-slider
setInterval(() => move(1), 5000);
