(function () {
  const pageBody = document.body;
  const mainImg = document.querySelector("#product-main-img");
  const mainTrigger = document.querySelector(".product-gallery__main");
  const pageThumbs = document.querySelectorAll(".product-thumb[data-full-src]");
  const lightbox = document.getElementById("product-lightbox");
  const lightboxImg = document.getElementById("product-lightbox-img");
  const lightboxThumbsRoot = document.getElementById("product-lightbox-thumbs");

  const thicknessBtns = document.querySelectorAll(".product-thickness-option[data-thickness-option]");
  let productPageRevealed = false;

  function revealLoadedProductPage() {
    if (
      productPageRevealed ||
      !pageBody ||
      !pageBody.classList.contains("page-product--loading")
    ) {
      return;
    }

    productPageRevealed = true;
    pageBody.classList.remove("page-product--loading");
    pageBody.classList.add("page-product--ready");
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", revealLoadedProductPage, { once: true });
  } else {
    revealLoadedProductPage();
  }

  window.addEventListener("load", revealLoadedProductPage, { once: true });
  window.setTimeout(revealLoadedProductPage, 800);

  if (!lightbox || !lightboxImg || !lightboxThumbsRoot || !mainImg) {
    if (thicknessBtns.length) initThickness();
    return;
  }

  const slides = [
    {
      full: mainImg.getAttribute("src") || "",
      alt: mainImg.getAttribute("alt") || "",
      thumbSrc: mainImg.getAttribute("src") || "",
      isMain: true,
    },
    ...Array.from(pageThumbs).map((btn) => ({
      full: btn.getAttribute("data-full-src") || "",
      alt: btn.getAttribute("data-alt") || "",
      thumbSrc: btn.querySelector("img")?.getAttribute("src") || btn.getAttribute("data-full-src") || "",
      isMain: false,
    })),
  ];

  let currentIndex = 0;
  let lightboxIndex = 0;

  function normalizeIndex(i) {
    const n = slides.length;
    if (n === 0) return 0;
    return ((i % n) + n) % n;
  }

  function setPageGalleryIndex(i) {
    currentIndex = normalizeIndex(i);
    const slide = slides[currentIndex];
    if (!slide || !mainImg) return;

    if (slide.full) mainImg.src = slide.full;
    mainImg.alt = slide.alt || mainImg.alt;

    pageThumbs.forEach((b) => {
      const thumbIndex = Number(b.getAttribute("data-slide-index"));
      const on = thumbIndex === currentIndex;
      b.classList.toggle("is-active", on);
      b.setAttribute("aria-pressed", on ? "true" : "false");
    });
  }

  function updateLightboxThumbsActive() {
    lightboxThumbsRoot.querySelectorAll(".product-lightbox__thumb").forEach((tb, idx) => {
      const on = idx === lightboxIndex;
      tb.classList.toggle("is-active", on);
      tb.setAttribute("aria-selected", on ? "true" : "false");
    });
  }

  function updateLightboxImage() {
    const s = slides[lightboxIndex];
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
        lightboxIndex = normalizeIndex(i);
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
    lightboxIndex = normalizeIndex(index);
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
    lightboxIndex = normalizeIndex(lightboxIndex + delta);
    updateLightboxImage();
  }

  buildLightboxThumbs();

  if (mainTrigger) {
    mainTrigger.addEventListener("click", () => {
      openLightbox(currentIndex);
    });
  }

  pageThumbs.forEach((btn, i) => {
    btn.setAttribute("data-slide-index", String(i + 1));
    btn.addEventListener("click", () => {
      openLightbox(i + 1);
    });
  });

  lightbox.addEventListener("click", (e) => {
    const target = e.target;
    if (!(target instanceof Element)) return;

    if (target.closest("[data-lightbox-close]")) {
      e.preventDefault();
      closeLightbox();
      return;
    }

    const isInteractive = target.closest(
      ".product-lightbox__frame, .product-lightbox__nav, .product-lightbox__thumbs"
    );

    if (!isInteractive) {
      closeLightbox();
    }
  });

  if (prevBtn) prevBtn.addEventListener("click", () => stepLightbox(-1));
  if (nextBtn) nextBtn.addEventListener("click", () => stepLightbox(1));
  updateNavVisibility();
  setPageGalleryIndex(0);

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

  function escapeHtml(str) {
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;");
  }

  function setSpecDimensionValue(el, raw) {
    if (!el) {
      return;
    }
    const s = String(raw || "").trim();
    if (!s || s === "—") {
      el.textContent = s || "—";
      return;
    }
    const parts = s
      .split(",")
      .map((p) => p.trim())
      .filter(Boolean);
    if (parts.length <= 1) {
      el.textContent = s;
      return;
    }
    el.innerHTML = `<span class="product-dimension-stack">${parts
      .map((p, i) => {
        const sep =
          i < parts.length - 1
            ? '<span class="product-dimension-sep" aria-hidden="true">, </span>'
            : "";
        return `<span class="product-dimension-part">${escapeHtml(p)}</span>${sep}`;
      })
      .join("")}</span>`;
  }

  function initThickness() {
    const specSizeEl = document.querySelector("#product-spec-size-value");
    const specWeightEl = document.querySelector("#product-spec-weight-value");
    const pricesRoot = document.querySelector("#product-price-rows");

    function renderPrices(rows) {
      if (!pricesRoot) return;
      pricesRoot.innerHTML = "";

      rows.forEach((row) => {
        const priceRow = document.createElement("div");
        priceRow.className = "product-price-row";

        const label = document.createElement("p");
        label.className = "product-price-label";
        label.textContent = row.label || "";

        const leader = document.createElement("span");
        leader.className = "product-price-leader";
        leader.setAttribute("aria-hidden", "true");

        const value = document.createElement("p");
        value.className = "product-price-value";
        value.append(document.createTextNode(row.amount || ""));

        const unit = document.createElement("span");
        unit.textContent = row.unit || "";
        value.append(document.createTextNode(" "));
        value.append(unit);

        priceRow.append(label, leader, value);
        pricesRoot.append(priceRow);
      });
    }

    thicknessBtns.forEach((btn) => {
      btn.addEventListener("click", () => {
        thicknessBtns.forEach((b) => {
          b.classList.remove("is-selected");
          b.setAttribute("aria-pressed", "false");
        });
        btn.classList.add("is-selected");
        btn.setAttribute("aria-pressed", "true");

        if (specSizeEl) {
          const nextSize = btn.getAttribute("data-spec-size");
          if (nextSize != null && nextSize !== "") {
            setSpecDimensionValue(specSizeEl, nextSize);
          }
        }
        if (specWeightEl) {
          specWeightEl.textContent = btn.getAttribute("data-spec-weight") || specWeightEl.textContent;
        }

        try {
          const prices = JSON.parse(btn.getAttribute("data-prices") || "[]");
          if (Array.isArray(prices)) {
            renderPrices(prices);
          }
        } catch (error) {
          // Ignore malformed admin data and keep current prices.
        }
      });
    });
  }

  initThickness();
})();
