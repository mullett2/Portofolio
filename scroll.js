  let lastScrollTop = 0;
  const navbar = document.getElementById("navbar");

  window.addEventListener("scroll", function () {
    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (scrollTop > lastScrollTop) {
      // Scroll ke bawah → hide
      navbar.classList.add("-translate-y-full");
    } else {
      // Scroll ke atas → show
      navbar.classList.remove("-translate-y-full");
    }

    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // biar ga negatif
  });
