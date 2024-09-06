// Control de movimiento y botones del carrusel de imágenes principal
document.addEventListener("DOMContentLoaded", function () {
  let slideIndex = 1;
  showSlides(slideIndex);

  function plusSlides(n) {
    showSlides((slideIndex += n));
  }

  function currentSlide(n) {
    showSlides((slideIndex = n));
  }

  function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("mySlides");
    if (slides.length === 0) return; // No hace nada si no hay slides disponibles.
    if (n > slides.length) {
      slideIndex = 1;
    }
    if (n < 1) {
      slideIndex = slides.length;
    }
    for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
    }
    slides[slideIndex - 1].style.display = "block";
  }

  // Vincula los botones de navegación solo si están presentes en la página
  const prevButton = document.querySelector(".prev");
  const nextButton = document.querySelector(".next");

  if (prevButton) {
    prevButton.addEventListener("click", function () {
      plusSlides(-1);
    });
  }

  if (nextButton) {
    nextButton.addEventListener("click", function () {
      plusSlides(1);
    });
  }
});

// Botón flotante para subir hacia el principio de la página

window.onscroll = function () {
  scrollFunction();
};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    document.getElementById("scrollToTopBtn").style.display = "block";
  } else {
    document.getElementById("scrollToTopBtn").style.display = "none";
  }
}

document
  .getElementById("scrollToTopBtn")
  .addEventListener("click", function () {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
  });

const currentLocation = window.location.pathname;

const navLinks = document.querySelectorAll("a");

navLinks.forEach((link) => {
  if (link.getAttribute("href") === currentLocation) {
    link.parentElement.classList.add("active");
  }
});

// Mostrar alert si hay un parámetro de estado en la URL
const urlParams = new URLSearchParams(window.location.search);
const status = urlParams.get('status');

if (status === 'created') {
    alert('Usuario creado exitosamente.');
} else if (status === 'updated') {
    alert('Usuario actualizado exitosamente.');
} else if (status === 'deleted') {
    alert('Usuario eliminado exitosamente.');
}