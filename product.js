(function () {
  const mainImg = document.querySelector("#product-main-img");
  const mainTrigger = document.querySelector(".product-gallery__main");
  const pageThumbs = document.querySelectorAll(".product-thumb[data-full-src]");
  const lightbox = document.getElementById("product-lightbox");
  const lightboxImg = document.getElementById("product-lightbox-img");
  const lightboxThumbsRoot = document.getElementById("product-lightbox-thumbs");

  const thicknessBtns = document.querySelectorAll(".product-thickness-option[data-thickness-option]");

  if (!lightbox || !lightboxImg || !lightboxThumbsRoot || pageThumbs.length === 0) {
    if (thicknessBtns.length) initThickness();
    return;
  }

  const slides = Array.from(pageThumbs).map((btn) => ({
    full: btn.getAttribute("data-full-src") || "",
    alt: btn.getAttribute("data-alt") || "",
    thumbSrc: btn.querySelector("img")?.getAttribute("src") || btn.getAttribute("data-full-src") || "",
  }));

  let currentIndex = Math.max(
    0,
    Array.from(pageThumbs).findIndex((b) => b.classList.contains("is-active"))
  );

  function normalizeIndex(i) {
    const n = slides.length;
    if (n === 0) return 0;
    return ((i % n) + n) % n;
  }

  function setPageGalleryIndex(i) {
    currentIndex = normalizeIndex(i);
    const btn = pageThumbs[currentIndex];
    if (!btn || !mainImg) return;
    const src = btn.getAttribute("data-full-src");
    if (src) mainImg.src = src;
    mainImg.alt = btn.getAttribute("data-alt") || mainImg.alt;

    pageThumbs.forEach((b) => {
      const on = b === btn;
      b.classList.toggle("is-active", on);
      b.setAttribute("aria-pressed", on ? "true" : "false");
    });
  }

  function updateLightboxThumbsActive() {
    lightboxThumbsRoot.querySelectorAll(".product-lightbox__thumb").forEach((tb, idx) => {
      const on = idx === currentIndex;
      tb.classList.toggle("is-active", on);
      tb.setAttribute("aria-selected", on ? "true" : "false");
    });
  }

  function updateLightboxImage() {
    const s = slides[currentIndex];
    if (!s) return;
    lightboxImg.src = s.full;
    lightboxImg.alt = s.alt;
    updateLightboxThumbsActive();
  }

  function buildLightboxThumbs() {
    lightboxThumbsRoot.innerHTML = slides
      .map(
        (s, i) => `
        <button
          type="button"
          class="product-lightbox__thumb${i === currentIndex ? " is-active" : ""}"
          data-lightbox-thumb="${i}"
          role="tab"
          aria-selected="${i === currentIndex ? "true" : "false"}"
          aria-label="Фото ${i + 1}"
        >
          <img src="${s.thumbSrc}" alt="" loading="lazy">
        </button>
      `
      )
      .join("");

    lightboxThumbsRoot.querySelectorAll("[data-lightbox-thumb]").forEach((tb) => {
      tb.addEventListener("click", () => {
        const i = Number(tb.getAttribute("data-lightbox-thumb"));
        setPageGalleryIndex(i);
        updateLightboxImage();
      });
    });
  }

  const prevBtn = lightbox.querySelector("[data-lightbox-prev]");
  const nextBtn = lightbox.querySelector("[data-lightbox-next]");

  function updateNavVisibility() {
    const multi = slides.length > 1;
    if (prevBtn) prevBtn.hidden = !multi;
    if (nextBtn) nextBtn.hidden = !multi;
  }

  function openLightbox(index) {
    currentIndex = normalizeIndex(index);
    setPageGalleryIndex(currentIndex);
    updateLightboxImage();
    updateNavVisibility();
    lightbox.classList.add("is-open");
    lightbox.removeAttribute("hidden");
    lightbox.setAttribute("aria-hidden", "false");
    document.body.classList.add("product-lightbox-open");

    const closeBtn = lightbox.querySelector(".product-lightbox__close");
    if (closeBtn) closeBtn.focus();
  }

  function closeLightbox() {
    lightbox.classList.remove("is-open");
    lightbox.setAttribute("hidden", "");
    lightbox.setAttribute("aria-hidden", "true");
    document.body.classList.remove("product-lightbox-open");
    if (mainTrigger) mainTrigger.focus();
  }

  function stepLightbox(delta) {
    setPageGalleryIndex(currentIndex + delta);
    updateLightboxImage();
  }

  buildLightboxThumbs();

  if (mainTrigger) {
    mainTrigger.addEventListener("click", () => {
      const idx = Array.from(pageThumbs).findIndex((b) => b.classList.contains("is-active"));
      openLightbox(idx >= 0 ? idx : 0);
    });
  }

  pageThumbs.forEach((btn, i) => {
    btn.addEventListener("click", () => {
      openLightbox(i);
    });
  });

  lightbox.querySelectorAll("[data-lightbox-close]").forEach((el) => {
    el.addEventListener("click", (e) => {
      e.preventDefault();
      closeLightbox();
    });
  });

  if (prevBtn) prevBtn.addEventListener("click", () => stepLightbox(-1));
  if (nextBtn) nextBtn.addEventListener("click", () => stepLightbox(1));
  updateNavVisibility();

  document.addEventListener("keydown", (e) => {
    if (!lightbox.classList.contains("is-open")) return;
    if (e.key === "Escape") {
      e.preventDefault();
      closeLightbox();
    } else if (e.key === "ArrowLeft") {
      e.preventDefault();
      stepLightbox(-1);
    } else if (e.key === "ArrowRight") {
      e.preventDefault();
      stepLightbox(1);
    }
  });

  function initThickness() {
    thicknessBtns.forEach((btn) => {
      btn.addEventListener("click", () => {
        thicknessBtns.forEach((b) => {
          b.classList.remove("is-selected");
          b.setAttribute("aria-pressed", "false");
        });
        btn.classList.add("is-selected");
        btn.setAttribute("aria-pressed", "true");
      });
    });
  }

  initThickness();
})();
