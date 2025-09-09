document.addEventListener("DOMContentLoaded", function () {
  let lastScrollTop = 0;
  const navbar = document.getElementById("navbar");

  window.addEventListener("scroll", function () {
    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (scrollTop > lastScrollTop && scrollTop > 50) {
      navbar.style.transform = "translate(-50%, -150%)";
    } else {
      navbar.style.transform = "translate(-50%, 0)";
    }

    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
  });
});
