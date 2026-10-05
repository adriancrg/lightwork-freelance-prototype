// Animación de rayo viajando
document.addEventListener('scroll', function() {
    // Aquí iría el código para la animación del rayo al hacer scroll
    // Por ejemplo, podría crear un elemento div que se mueve con el scroll
});

// Efecto de carga eléctrica en botones
document.querySelectorAll('.cta-button').forEach(button => {
    button.addEventListener('mouseover', function() {
        this.style.boxShadow = '0 0 20px rgba(138, 43, 226, 0.8)';
    });
    button.addEventListener('mouseout', function() {
        this.style.boxShadow = 'none';
    });
});

// Simulación de carga de testimonios
const testimonials = [
    "LightWork ha revolucionado la forma en que recibo pagos. ¡Instantáneo y sin complicaciones!",
    "Como freelancer internacional, LightWork me ha ahorrado muchísimo en comisiones bancarias.",
    "La velocidad de los pagos con Lightning Network es increíble. Nunca volvería al sistema tradicional."
];

const testimonialContent = document.getElementById('testimonial-content');
let currentTestimonial = 0;

function rotateTestimonials() {
    testimonialContent.innerHTML = `<p>"${testimonials[currentTestimonial]}"</p>`;
    currentTestimonial = (currentTestimonial + 1) % testimonials.length;
}

rotateTestimonials(); // Mostrar el primer testimonio
setInterval(rotateTestimonials, 5000); // Rotar cada 5 segundos

// Añadir efecto de aparición a las secciones
const sections = document.querySelectorAll('.section');
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate__fadeIn');
        }
    });
}, { threshold: 0.1 });

sections.forEach(section => {
    observer.observe(section);
});