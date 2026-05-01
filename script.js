const CATEGORY_PAGE_BASE = "category.php?c=";

const FALLBACK_HOME_CATALOG_ITEMS = [
  {
    slug: "nakladnye-prostupi",
    title: "Армированные накладные проступи",
    description: "Решение для облицовки и усиления лестничных ступеней",
    image: "assets/images/catalog-1.png",
    className: "catalog-card-large",
  },
  {
    slug: "trotuarnaya-plitka",
    title: "Тротуарная плитка",
    description: "Прочные решения для мощения",
    image: "assets/images/catalog-2.png",
    className: "catalog-card-top-center",
  },
  {
    slug: "fasadnye-paneli",
    title: "Фасадные панели",
    description: "Современная облицовка фасадов",
    image: "assets/images/catalog-3.png",
    className: "catalog-card-top-right",
  },
  {
    slug: "bordyury-i-vodostoki",
    title: "Бордюры и водостоки",
    description: "Функциональное оформление территории",
    image: "assets/images/catalog-4.png",
    className: "catalog-card-middle-center",
  },
  {
    slug: "ritualnye-plity",
    title: "Армированные ритуальные плиты",
    description: "Функциональное оформление территории",
    image: "assets/images/catalog-5.png",
    className: "catalog-card-middle-right",
  },
  {
    slug: "poshagovye-plity",
    title: "Армированные пошаговые плиты",
    description: "Долговечные мемориальные изделия",
    image: "assets/images/catalog-6.png",
    className: "catalog-card-bottom-left",
  },
  {
    slug: "parapetnye-plity",
    title: "Армированные парапетные плиты",
    description: "Для садовых и ландшафтных дорожек",
    image: "assets/images/catalog-7.png",
    className: "catalog-card-bottom-center",
  },
];

const PRODUCT_PAGE_BASE = "product.php?p=";
const MOBILE_MENU_CATEGORIES = [
  { href: "category.php?c=trotuarnaya-plitka", label: "Брусчатка" },
  { href: "category.php?c=trotuarnaya-plitka", label: "Тротуарная плитка" },
  { href: "category.php?c=fasadnye-paneli", label: "Фасадные панели" },
  { href: "category.php?c=bordyury-i-vodostoki", label: "Бордюры и водостоки" },
  { href: "category.php?c=nakladnye-prostupi", label: "Накладные проступи" },
  { href: "category.php?c=ritualnye-plity", label: "Ритуальные плиты" },
  { href: "category.php?c=poshagovye-plity", label: "Пошаговые плиты" },
  { href: "category.php?c=parapetnye-plity", label: "Накрывные элементы" },
];

const FALLBACK_PRODUCT_ITEMS = [
  {
    slug: "fasad-tsvetok-elit",
    title: "Каменный цветок элит",
    metaLines: ["Размеры(мм): 40", "Вес: 90"],
    pricePerSqm: true,
    priceAmount: "от 30.00 руб.",
    image: "assets/images/product-flower-elite.png",
  },
  {
    slug: "kaliforniya-kamen",
    title: "Калифорния камень",
    metaLines: ["Размеры(мм): 300х300х40"],
    pricePerSqm: true,
    priceAmount: "от 30.00 руб.",
    image: "assets/images/product-california-stone.png",
  },
  {
    slug: "bordyur-1000-220",
    title: "Бордюр тротуарный 1000х220",
    metaLines: ["Размеры(мм): 1000х220х75"],
    pricePerSqm: false,
    priceAmount: "от 10 руб./шт",
    image: "assets/images/product-curb.png",
  },
  {
    slug: "poshagovaya-gladkaya",
    title: "Пошаговая плита гладкая",
    metaLines: ["Размеры(мм): 800х400х50"],
    pricePerSqm: false,
    priceAmount: "от 35 руб./шт",
    image: "assets/images/product-step-slab.png",
  },
];

const PRODUCT_CARD_ARROW_SRC = "assets/images/arrow.svg";

function escapeHtml(str) {
  return String(str)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");
}

function buildDimensionStackHtml(parts) {
  return parts
    .map((p, i) => {
      const sep =
        i < parts.length - 1
          ? '<span class="product-dimension-sep" aria-hidden="true">, </span>'
          : "";
      return `<span class="product-dimension-part">${escapeHtml(p)}</span>${sep}`;
    })
    .join("");
}

function formatMetaLineHtml(line) {
  const s = String(line || "").trim();
  if (!s) {
    return "";
  }
  const m = s.match(/^([^:]+:\s*)(.+)$/);
  if (!m) {
    return escapeHtml(s);
  }
  const value = m[2].trim();
  if (!value.includes(",")) {
    return escapeHtml(s);
  }
  const parts = value
    .split(",")
    .map((p) => p.trim())
    .filter(Boolean);
  if (parts.length <= 1) {
    return escapeHtml(s);
  }
  return escapeHtml(m[1]) + `<span class="product-dimension-stack">${buildDimensionStackHtml(parts)}</span>`;
}

function formatProductPrice(item) {
  if (item.priceText) {
    return item.priceText;
  }
  if (item.pricePerSqm) {
    return `${item.priceAmount}/m<sup class="product-price__sq">2</sup>`;
  }
  return item.priceAmount || "";
}

function formatProductMeta(item) {
  const lines = item.metaLines && item.metaLines.length ? item.metaLines : [item.meta || ""];
  return lines.map((line) => `<p>${formatMetaLineHtml(line)}</p>`).join("");
}

function getHomeCatalogClassName(index) {
  const classes = [
    "catalog-card-large",
    "catalog-card-top-center",
    "catalog-card-top-right",
    "catalog-card-middle-center",
    "catalog-card-middle-right",
    "catalog-card-bottom-left",
    "catalog-card-bottom-center",
  ];

  return classes[index] || "catalog-card-bottom-center";
}

function renderCatalog(items = FALLBACK_HOME_CATALOG_ITEMS) {
  const homeRoot = document.querySelector("#catalog-grid");

  if (homeRoot) {
    const cardMarkup = (item, index) => `
          <a class="catalog-card ${item.className || getHomeCatalogClassName(index)} catalog-card-link" href="${CATEGORY_PAGE_BASE}${item.slug}">
            <img src="${item.image}" alt="${item.title}" loading="lazy">
            <div class="catalog-card-content">
              <h3>${item.title}</h3>
              <p class="catalog-card-desc">${item.description}</p>
              <span class="link-line">В каталог</span>
            </div>
          </a>
        `;
    const normalized = items.slice(0, 7);
    homeRoot.innerHTML = normalized.map((item, index) => cardMarkup(item, index)).join("");
    markMissingImages();
  }
}

function renderProducts(items = FALLBACK_PRODUCT_ITEMS) {
  const root = document.querySelector("#products-grid");
  if (!root) return;

  root.innerHTML = items
    .map(
      (item) => `
        <article class="product-card">
          <a class="product-card-link" href="${PRODUCT_PAGE_BASE}${item.slug}">
            <div class="product-media image-frame">
              <img src="${item.image}" alt="${item.title}" loading="lazy">
            </div>
            <div class="product-content">
              <h3>${item.title}</h3>
              <div class="product-meta">${formatProductMeta(item)}</div>
              <div class="product-bottom">
                <span class="product-price">${formatProductPrice(item)}</span>
                <span class="product-action" aria-hidden="true">
                  <img class="product-action__icon" src="${PRODUCT_CARD_ARROW_SRC}" width="18" height="18" alt="" decoding="async">
                </span>
              </div>
            </div>
          </a>
        </article>
      `
    )
    .join("");
  markMissingImages();
  setupScrollControls();
}

/** URL ведёт на index (главная в подпапке или в корне). */
function isPathLikelyHomeTarget(pathname) {
  const p = (pathname || "").replace(/\\/g, "/");
  const lower = p.toLowerCase();
  if (lower === "/" || lower === "") return true;
  if (
    lower.endsWith("/index.html") ||
    lower.endsWith("/index.htm") ||
    lower.endsWith("/index.php")
  ) {
    return true;
  }
  const parts = p.split("/").filter(Boolean);
  const last = (parts[parts.length - 1] || "").toLowerCase();
  if (last === "index.html" || last === "index.htm" || last === "index.php") return true;
  return false;
}

function isCurrentDocumentHome() {
  return Boolean(document.getElementById("hero") && document.getElementById("catalog"));
}

/** Якорь на главной: #id или index.html#id, пока открыта главная страница. */
function getInPageTargetIdFromHref(href) {
  if (!href) return null;
  const t = String(href).trim();
  if (t.startsWith("#") && t.length > 1) {
    try {
      return decodeURIComponent(t.slice(1));
    } catch {
      return t.slice(1);
    }
  }
  try {
    const u = new URL(t, window.location.href);
    if (u.origin !== window.location.origin) return null;
    if (!u.hash || u.hash.length < 2) return null;
    const here =
      isPathLikelyHomeTarget(window.location.pathname) || isCurrentDocumentHome();
    if (!here || !isPathLikelyHomeTarget(u.pathname)) return null;
    return decodeURIComponent(u.hash.slice(1));
  } catch {
    return null;
  }
}

const PENDING_HOME_SCROLL_KEY = "mb_pending_home_scroll";

function getHomeScrollOffsetPx() {
  const header = document.querySelector(".site-header");
  if (!header) return 96;
  return Math.round(header.getBoundingClientRect().height) + 10;
}

function scrollToHomeSectionById(id, smooth) {
  const el = document.getElementById(id);
  if (!el) return false;
  const y = el.getBoundingClientRect().top + window.scrollY - getHomeScrollOffsetPx();
  window.scrollTo({ top: Math.max(0, y), behavior: smooth ? "smooth" : "auto" });
  return true;
}

function stripHomeHashFromUrl() {
  if (!window.history || !window.history.replaceState) return;
  if (!window.location.hash) return;
  window.history.replaceState(null, "", window.location.pathname + window.location.search);
}

/** Кнопки «Заказать звонок» — попап, не скролл к форме */
function isLeadCallbackAnchor(a) {
  const href = a.getAttribute("href") || "";
  if (!href.includes("contact-form")) return false;
  if (a.classList.contains("mobile-menu__button")) return true;
  return a.classList.contains("button-accent") && a.classList.contains("button-small");
}

/** Прокрутка после входа с index.html#… (хеш снят inline-скриптом в head) и резервно по hash/hashchange */
function consumePendingHomeScroll() {
  if (!isCurrentDocumentHome()) return;
  let id = null;
  try {
    id = sessionStorage.getItem(PENDING_HOME_SCROLL_KEY);
    if (id) sessionStorage.removeItem(PENDING_HOME_SCROLL_KEY);
  } catch {
    id = null;
  }
  if (!id || !document.getElementById(id)) return;

  const run = () => {
    scrollToHomeSectionById(id, false);
    stripHomeHashFromUrl();
  };

  const schedule = () => {
    window.requestAnimationFrame(() => {
      window.requestAnimationFrame(run);
    });
  };

  if (document.readyState === "complete") {
    schedule();
  } else {
    window.addEventListener("load", schedule, { once: true });
  }
}

/** Прокрутка по внутренним ссылкам главной: только JS, без хеша в адресной строке */
function setupHomeHashNavigation() {
  if (!isCurrentDocumentHome()) return;

  function applyHashFromUrl() {
    const hash = window.location.hash;
    if (!hash || hash.length < 2) return;
    let id;
    try {
      id = decodeURIComponent(hash.slice(1));
    } catch {
      id = hash.slice(1);
    }
    if (!id) return;
    const run = () => {
      scrollToHomeSectionById(id, false);
      stripHomeHashFromUrl();
    };
    window.requestAnimationFrame(() => {
      window.requestAnimationFrame(run);
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", applyHashFromUrl, { once: true });
  } else {
    applyHashFromUrl();
  }

  window.addEventListener("hashchange", applyHashFromUrl);

  document.addEventListener(
    "click",
    (e) => {
      const a = e.target.closest("a");
      if (!a) return;
      if (a.closest(".mobile-menu")) return;
      if (isLeadCallbackAnchor(a)) return;
      if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
      const id = getInPageTargetIdFromHref(a.getAttribute("href") || "");
      if (!id || !document.getElementById(id)) return;
      e.preventDefault();
      scrollToHomeSectionById(id, true);
      stripHomeHashFromUrl();
    },
    true
  );
}

async function loadHomeCatalogData() {
  const catalogRoot = document.querySelector("#catalog-grid");
  const productsRoot = document.querySelector("#products-grid");
  if (!catalogRoot && !productsRoot) return;

  try {
    const response = await fetch("api/home-data.php", {
      headers: {
        Accept: "application/json",
      },
    });
    if (!response.ok) {
      throw new Error("Home data request failed");
    }

    const data = await response.json();
    if (catalogRoot && Array.isArray(data.categories) && data.categories.length) {
      renderCatalog(data.categories);
    } else if (catalogRoot) {
      renderCatalog();
    }

    if (productsRoot && Array.isArray(data.featuredProducts) && data.featuredProducts.length) {
      renderProducts(
        data.featuredProducts.map((item) => ({
          slug: item.slug,
          title: item.title,
          metaLines: item.metaLines || [],
          priceText: item.priceText || "",
          image: item.image,
        }))
      );
    } else if (productsRoot) {
      renderProducts();
    }
  } catch (error) {
    if (catalogRoot) renderCatalog();
    if (productsRoot) renderProducts();
  }
}

function setupMenu() {
  const toggle = document.querySelector(".menu-toggle");
  const nav = document.querySelector(".site-nav");
  const header = document.querySelector(".site-header");
  const brand = header?.querySelector(".brand");
  const phoneLink = header?.querySelector(".header-phone");
  const phoneMeta = header?.querySelector(".header-phone-wrap span");
  const callbackLink = header?.querySelector('.header-contacts .button, .header-contacts [href*="contact-form"]');

  if (!toggle || !nav || !brand) return;

  const navigationItems = Array.from(nav.querySelectorAll("a"))
    .map((link) => ({
      href: link.getAttribute("href") || "#",
      label: (link.textContent || "").trim(),
      current: link.getAttribute("aria-current") === "page",
    }))
    .filter((item) => item.label !== "");

  const catalogItem = navigationItems.find((item) => item.label === "Каталог");
  const topItems = navigationItems.filter((item) => item.label !== "Каталог");
  const callbackHref = callbackLink?.getAttribute("href") || "#contact-form";
  const phoneHref = phoneLink?.getAttribute("href") || "tel:+375293258259";
  const phoneText = (phoneLink?.textContent || "").trim() || "+375 (29) 325-82-59";
  const phoneMetaText = (phoneMeta?.textContent || "").trim() || "Пн-Вс: 09:00 — 20:00";

  const popup = document.createElement("div");
  popup.className = "mobile-menu";
  popup.hidden = true;
  popup.setAttribute("aria-hidden", "true");
  popup.innerHTML = `
    <div class="mobile-menu__backdrop" data-menu-close></div>
    <div class="mobile-menu__panel" role="dialog" aria-modal="true" aria-labelledby="mobile-menu-title">
      <div class="mobile-menu__header">
        <a class="mobile-menu__brand brand" href="${brand.getAttribute("href") || "index.php"}" aria-label="MRAMORBETON" id="mobile-menu-title">
          ${brand.innerHTML}
        </a>
        <button class="mobile-menu__close" type="button" data-menu-close aria-label="Закрыть меню">
          <img src="assets/x.svg" width="24" height="24" alt="">
        </button>
      </div>
      <nav class="mobile-menu__nav" aria-label="Мобильная навигация">
        ${
          catalogItem
            ? `<a href="${catalogItem.href}"${catalogItem.current ? ' aria-current="page"' : ""}>${catalogItem.label}</a>`
            : ""
        }
        ${topItems
          .map(
            (item) =>
              `<a href="${item.href}"${item.current ? ' aria-current="page"' : ""}>${item.label}</a>`
          )
          .join("")}
      </nav>
      <div class="mobile-menu__footer">
        <div class="mobile-menu__contacts">
          <a class="mobile-menu__phone" href="${phoneHref}">${phoneText}</a>
          <p>${phoneMetaText}</p>
        </div>
        <a class="mobile-menu__button" href="${callbackHref}">Заказать звонок</a>
      </div>
    </div>
  `;

  document.body.appendChild(popup);

  const closeElements = popup.querySelectorAll("[data-menu-close]");
  const focusTarget = popup.querySelector(".mobile-menu__brand");

  function openMenu() {
    popup.hidden = false;
    popup.setAttribute("aria-hidden", "false");
    popup.classList.add("is-open");
    document.body.classList.add("mobile-menu-open");
    toggle.setAttribute("aria-expanded", "true");
    window.setTimeout(() => focusTarget?.focus(), 20);
  }

  function closeMenu() {
    popup.classList.remove("is-open");
    popup.setAttribute("aria-hidden", "true");
    popup.hidden = true;
    document.body.classList.remove("mobile-menu-open");
    toggle.setAttribute("aria-expanded", "false");
  }

  toggle.addEventListener("click", () => {
    if (popup.classList.contains("is-open")) {
      closeMenu();
      return;
    }
    openMenu();
  });

  closeElements.forEach((element) => {
    element.addEventListener("click", closeMenu);
  });

  popup.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", (event) => {
      if (event.defaultPrevented) {
        closeMenu();
        return;
      }
      const href = (link.getAttribute("href") || "").trim();
      const id = getInPageTargetIdFromHref(href);
      const target = id ? document.getElementById(id) : null;
      if (target) {
        event.preventDefault();
        closeMenu();
        window.requestAnimationFrame(() => {
          window.requestAnimationFrame(() => {
            scrollToHomeSectionById(id, true);
            stripHomeHashFromUrl();
          });
        });
        return;
      }
      closeMenu();
    });
  });

  document.addEventListener("keydown", (event) => {
    if (!popup.classList.contains("is-open")) return;
    if (event.key === "Escape") {
      event.preventDefault();
      closeMenu();
    }
  });

  window.addEventListener("resize", () => {
    if (window.innerWidth > 1260 && popup.classList.contains("is-open")) {
      closeMenu();
    }
  });
}

function setupScrollControls() {
  document.querySelectorAll(".mobile-slider-controls").forEach((controls) => {
    if (typeof controls._syncState === "function") {
      controls._syncState();
      return;
    }

    const prev = controls.querySelector(".mobile-slider-control--prev");
    const next = controls.querySelector(".mobile-slider-control--next");
    const targetId = prev?.getAttribute("data-scroll-target") || next?.getAttribute("data-scroll-target");
    const target = targetId ? document.getElementById(targetId) : null;
    if (!target) return;

    function getStep() {
      const firstChild = target.firstElementChild;
      return firstChild ? firstChild.getBoundingClientRect().width + 24 : target.clientWidth;
    }

    function syncState() {
      const maxScroll = target.scrollWidth - target.clientWidth;
      const isScrollable = maxScroll > 8;
      controls.hidden = !isScrollable;
      if (!isScrollable) return;

      if (prev) prev.disabled = target.scrollLeft <= 4;
      if (next) next.disabled = target.scrollLeft >= maxScroll - 4;
    }

    controls._syncState = syncState;
    controls.dataset.sliderReady = "true";

    prev?.addEventListener("click", () => {
      target.scrollBy({ left: -getStep(), behavior: "smooth" });
    });

    next?.addEventListener("click", () => {
      target.scrollBy({ left: getStep(), behavior: "smooth" });
    });

    target.addEventListener("scroll", syncState, { passive: true });
    window.addEventListener("resize", syncState);
    window.setTimeout(syncState, 0);
  });
}

function setupArticleBenefitsPager() {
  document.querySelectorAll(".article-mobile-slider-controls[data-benefits-grid]").forEach((controls) => {
    const gridId = controls.getAttribute("data-benefits-grid");
    const pageSizeAttr = Number(controls.getAttribute("data-benefits-page-size"));
    const pageSizeDesktop = Number.isFinite(pageSizeAttr) && pageSizeAttr > 0 ? pageSizeAttr : 3;
    const grid = gridId ? document.getElementById(gridId) : null;
    const prev = controls.querySelector("[data-benefits-prev]");
    const next = controls.querySelector("[data-benefits-next]");
    const items = grid ? Array.from(grid.children) : [];
    if (!grid || !prev || !next || items.length === 0) return;

    let currentPage = 0;

    function sync() {
      const isMobile = window.innerWidth <= 600;
      const pageSize = isMobile ? pageSizeDesktop : items.length;
      const totalPages = Math.max(1, Math.ceil(items.length / pageSize));
      currentPage = Math.min(currentPage, totalPages - 1);

      items.forEach((item, index) => {
        if (!isMobile) {
          item.hidden = false;
          return;
        }

        const start = currentPage * pageSize;
        const end = start + pageSize;
        item.hidden = index < start || index >= end;
      });

      const showSingle = controls.dataset.benefitsShowSingle === "true";
      controls.hidden = !isMobile || (!showSingle && totalPages <= 1);
      prev.disabled = currentPage === 0;
      next.disabled = currentPage >= totalPages - 1;
    }

    if (controls.dataset.benefitsPagerReady !== "true") {
      controls.dataset.benefitsPagerReady = "true";
      prev.addEventListener("click", () => {
        currentPage = Math.max(0, currentPage - 1);
        sync();
      });
      next.addEventListener("click", () => {
        const totalPages = Math.max(1, Math.ceil(items.length / pageSizeDesktop));
        currentPage = Math.min(totalPages - 1, currentPage + 1);
        sync();
      });
      window.addEventListener("resize", sync);
    }

    sync();
  });
}

function setupPhoneMask() {
  function applyPhoneMask(input) {
    const digits = input.value.replace(/\D/g, "").slice(0, 12);
    const normalized = digits.startsWith("375") ? digits : `375${digits}`;
    const value = normalized.slice(0, 12);

    let result = "+375";
    if (value.length > 3) result += ` (${value.slice(3, 5)}`;
    if (value.length >= 5) result += ")";
    if (value.length > 5) result += ` ${value.slice(5, 8)}`;
    if (value.length > 8) result += `-${value.slice(8, 10)}`;
    if (value.length > 10) result += `-${value.slice(10, 12)}`;

    input.value = result;
  }

  document.querySelectorAll('input[name="phone"]').forEach((input) => {
    if (input.dataset.phoneMaskReady === "true") return;
    input.dataset.phoneMaskReady = "true";
    input.addEventListener("input", () => applyPhoneMask(input));
  });
}

function leadApiUrl() {
  try {
    return new URL("api/submit-lead.php", window.location.href).href;
  } catch {
    return "api/submit-lead.php";
  }
}

function leadThankYouPageUrl() {
  try {
    return new URL("thank-you.html", window.location.href).href;
  } catch {
    return "thank-you.html";
  }
}

function ensureLeadHoneypot(form) {
  if (!form || form.querySelector('[data-lead-honeypot="1"]')) return;
  const input = document.createElement("input");
  input.type = "text";
  input.name = "lead_hp";
  input.setAttribute("data-lead-honeypot", "1");
  input.setAttribute("tabindex", "-1");
  input.setAttribute("autocomplete", "off");
  input.setAttribute("aria-hidden", "true");
  Object.assign(input.style, {
    position: "absolute",
    left: "-9999px",
    width: "1px",
    height: "1px",
    opacity: "0",
  });
  form.appendChild(input);
}

function getLeadStatusEl(form) {
  return (
    form.querySelector("[data-form-status]") ||
    form.querySelector(".lead-form__status") ||
    form.querySelector(".form-status")
  );
}

function isLeadPhoneValid(value) {
  return value.replace(/\D/g, "").length >= 12;
}

async function postLeadForm(form, extra = {}) {
  const fd = new FormData(form);
  fd.set("page_url", window.location.href);
  if (extra.context) {
    fd.set("context", String(extra.context));
  }
  const res = await fetch(leadApiUrl(), {
    method: "POST",
    body: fd,
    credentials: "same-origin",
  });
  let data = {};
  try {
    data = await res.json();
  } catch {
    data = {};
  }
  if (!res.ok || !data.ok) {
    throw new Error((data && data.error) || "Не удалось отправить заявку");
  }
}

function setupForm() {
  document.querySelectorAll('form[data-lead-form="true"], #contact-form').forEach((form) => {
    if (form.dataset.formReady === "true") return;
    form.dataset.formReady = "true";
    ensureLeadHoneypot(form);

    const status = getLeadStatusEl(form);
    const submitBtn = form.querySelector('button[type="submit"]');

    form.addEventListener("submit", async (event) => {
      event.preventDefault();
      const phoneInput = form.querySelector('input[name="phone"]');
      if (phoneInput && !isLeadPhoneValid(phoneInput.value)) {
        if (status) {
          status.textContent = "Введите полный номер телефона в формате +375 XX XXX-XX-XX.";
        }
        phoneInput.focus();
        return;
      }
      if (status) status.textContent = "";
      if (submitBtn) submitBtn.disabled = true;
      try {
        await postLeadForm(form, {});
        window.location.assign(leadThankYouPageUrl());
        return;
      } catch (err) {
        if (status) {
          status.textContent = err instanceof Error ? err.message : "Не удалось отправить заявку.";
        }
      } finally {
        if (submitBtn) submitBtn.disabled = false;
      }
    });
  });
}

function setupLeadPopup() {
  const callbackButtons = document.querySelectorAll(
    '.button.button-accent.button-small[href="#contact-form"], .button.button-accent.button-small[href="index.html#contact-form"], .button.button-accent.button-small[href="index.php#contact-form"], .mobile-menu__button[href="#contact-form"], .mobile-menu__button[href="index.html#contact-form"], .mobile-menu__button[href="index.php#contact-form"]'
  );
  const productButtons = document.querySelectorAll(".product-order-btn");

  if (!callbackButtons.length && !productButtons.length) return;

  const popup = document.createElement("div");
  popup.className = "site-popup";
  popup.id = "lead-popup";
  popup.setAttribute("aria-hidden", "true");
  popup.hidden = true;
  popup.innerHTML = `
    <div class="site-popup__backdrop" data-popup-close tabindex="-1"></div>
    <div class="site-popup__dialog" role="dialog" aria-modal="true" aria-labelledby="lead-popup-title">
      <button type="button" class="site-popup__close" data-popup-close aria-label="Закрыть">
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </button>
      <div class="site-popup__copy">
        <h2 id="lead-popup-title" class="site-popup__title">Заказать звонок</h2>
        <p class="site-popup__subtitle">Оставьте заявку, и менеджер свяжется с вами в ближайшее время</p>
      </div>
      <div class="site-popup__success" aria-live="polite">
        <div class="site-popup__success-icon" aria-hidden="true"></div>
        <h2 class="site-popup__success-title">Ваша заявка успешно отправлена!</h2>
        <p class="site-popup__success-text">Мы перезвоним вам в течение 15 минут (в рабочее время Пн-Вс: 09:00 — 20:00)</p>
      </div>
      <form class="site-popup-form" data-lead-form="true" novalidate>
        <div class="site-popup-form__field">
          <div class="site-popup-form__label-row">
            <label class="site-popup-form__label" for="popup-contact-name">Ваше имя</label>
          </div>
          <input class="site-popup-form__control" type="text" name="name" id="popup-contact-name" placeholder="Введите имя" autocomplete="name">
        </div>
        <div class="site-popup-form__field">
          <div class="site-popup-form__label-row">
            <label class="site-popup-form__label" for="popup-contact-phone">Номер телефона</label>
          </div>
          <input class="site-popup-form__control" type="tel" name="phone" id="popup-contact-phone" placeholder="+375 (___) ___-__-__" autocomplete="tel" inputmode="tel">
          <p class="site-popup-form__error" data-phone-error>Введите весь номер телефона в правильном формате: +375 XX XXX-XX-XX</p>
        </div>
        <div class="site-popup-form__field">
          <div class="site-popup-form__label-row">
            <label class="site-popup-form__label" for="popup-contact-message">Сообщение</label>
          </div>
          <textarea class="site-popup-form__control site-popup-form__control--message" name="message" id="popup-contact-message" rows="2" placeholder="Введите сообщение"></textarea>
        </div>
        <button class="site-popup-form__submit" type="submit">
          <span>Отправить заявку</span>
          <svg class="site-popup-form__submit-icon" viewBox="0 0 24 24" aria-hidden="true">
            <path fill="currentColor" d="M2.01 21 23 12 2.01 3 2 10l15 2-15 2z"></path>
          </svg>
        </button>
        <p class="site-popup-form__policy">
          Нажимая кнопку, вы соглашаетесь с политикой конфиденциальности и условиями обработки персональных данных.
        </p>
        <p class="site-popup-form__status" data-form-status aria-live="polite"></p>
      </form>
    </div>
  `;

  document.body.appendChild(popup);

  const title = popup.querySelector("#lead-popup-title");
  const messageField = popup.querySelector("#popup-contact-message");
  const firstInput = popup.querySelector("#popup-contact-name");
  const phoneInput = popup.querySelector("#popup-contact-phone");
  const popupForm = popup.querySelector("form");
  const popupStatus = popup.querySelector("[data-form-status]");
  const phoneError = popup.querySelector("[data-phone-error]");
  const successScreen = popup.querySelector(".site-popup__success");
  let closeTimer = null;

  if (popupForm) {
    popupForm.dataset.formReady = "true";
    ensureLeadHoneypot(popupForm);
  }

  const defaultState = {
    title: "Заказать звонок",
    message: "",
  };

  function setPopupSuccessState(isSuccess) {
    popup.classList.toggle("is-success", isSuccess);
    if (successScreen) {
      successScreen.setAttribute("aria-hidden", isSuccess ? "false" : "true");
    }
  }

  function openPopup(config = {}) {
    const nextTitle = config.title || defaultState.title;
    const nextMessage = config.message || defaultState.message;
    if (closeTimer !== null) {
      window.clearTimeout(closeTimer);
      closeTimer = null;
    }
    if (title) title.textContent = nextTitle;
    if (messageField) messageField.value = nextMessage;
    if (popupStatus) popupStatus.textContent = "";
    setPopupSuccessState(false);
    if (phoneInput) phoneInput.classList.remove("site-popup-form__control--error");
    if (phoneError) phoneError.classList.remove("is-visible");
    popup.hidden = false;
    popup.classList.add("is-open");
    popup.setAttribute("aria-hidden", "false");
    document.body.classList.add("site-popup-open");
    if (firstInput) firstInput.focus();
  }

  function closePopup() {
    popup.classList.remove("is-open");
    popup.setAttribute("aria-hidden", "true");
    document.body.classList.remove("site-popup-open");
    closeTimer = window.setTimeout(() => {
      popup.hidden = true;
      if (popupForm) popupForm.reset();
      if (popupStatus) popupStatus.textContent = "";
      setPopupSuccessState(false);
      if (title) title.textContent = defaultState.title;
      if (phoneInput) phoneInput.classList.remove("site-popup-form__control--error");
      if (phoneError) phoneError.classList.remove("is-visible");
      closeTimer = null;
    }, 240);
  }

  function syncFilledState(input) {
    input.classList.toggle("site-popup-form__control--filled", input.value.trim() !== "");
  }

  function validatePhoneField() {
    if (!phoneInput) return true;
    const digits = phoneInput.value.replace(/\D/g, "");
    const valid = digits.length >= 12;
    phoneInput.classList.toggle("site-popup-form__control--error", !valid);
    if (phoneError) phoneError.classList.toggle("is-visible", !valid);
    return valid;
  }

  popup.querySelectorAll(".site-popup-form__control").forEach((input) => {
    syncFilledState(input);
    input.addEventListener("input", () => {
      syncFilledState(input);
      if (input === phoneInput && phoneError?.classList.contains("is-visible")) {
        validatePhoneField();
      }
    });
    input.addEventListener("blur", () => {
      if (input === phoneInput && phoneInput.value.trim() !== "") {
        validatePhoneField();
      }
    });
  });

  callbackButtons.forEach((button) => {
    button.addEventListener(
      "click",
      (event) => {
        event.preventDefault();
        openPopup(defaultState);
      },
      true
    );
  });

  productButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      event.preventDefault();
      const fallbackTitle = document.querySelector(".product-title")?.textContent?.trim() || "Товар";
      openPopup({
        title: button.getAttribute("data-lead-title") || fallbackTitle,
        message: button.getAttribute("data-lead-message") || `Интересует товар: ${fallbackTitle}`,
      });
    });
  });

  popup.querySelectorAll("[data-popup-close]").forEach((element) => {
    element.addEventListener("click", (event) => {
      event.preventDefault();
      closePopup();
    });
  });

  document.addEventListener("keydown", (event) => {
    if (!popup.classList.contains("is-open")) return;
    if (event.key === "Escape") {
      event.preventDefault();
      closePopup();
    }
  });

  if (popupForm) {
    popupForm.addEventListener("submit", async (event) => {
      event.preventDefault();
      if (!validatePhoneField()) {
        phoneInput?.focus();
        return;
      }
      if (popupStatus) popupStatus.textContent = "";
      const submitBtn = popupForm.querySelector('button[type="submit"]');
      if (submitBtn) submitBtn.disabled = true;
      const context = title?.textContent?.trim() || "";
      try {
        await postLeadForm(popupForm, { context });
        window.location.assign(leadThankYouPageUrl());
        return;
      } catch (err) {
        if (popupStatus) {
          popupStatus.textContent = err instanceof Error ? err.message : "Не удалось отправить заявку.";
        }
      } finally {
        if (submitBtn) submitBtn.disabled = false;
      }
    });
  }
}

function markMissingImages() {
  document.querySelectorAll("img").forEach((image) => {
    const frame =
      image.closest(".image-frame, .catalog-card, .product-gallery__main, .product-thumb, .product-card__media") ||
      image.parentElement;

    if (!frame) return;

    const showLoaded = () => {
      frame.classList.remove("is-loading", "is-missing");
    };

    const showMissing = () => {
      frame.classList.remove("is-loading");
      frame.classList.add("is-missing");
    };

    if (image.dataset.imageStateReady !== "true") {
      image.addEventListener("load", showLoaded);
      image.addEventListener("error", showMissing);
      image.dataset.imageStateReady = "true";
    }

    if (image.complete) {
      if (image.naturalWidth > 0) {
        showLoaded();
      } else {
        showMissing();
      }
      return;
    }

    frame.classList.remove("is-missing");
    frame.classList.add("is-loading");
  });
}

function setupMapLoading() {
  document.querySelectorAll(".contacts-map iframe").forEach((frame) => {
    const container = frame.parentElement;
    if (!container) return;

    const showReady = () => {
      container.classList.remove("is-loading");
      container.classList.add("is-ready");
    };

    if (frame.dataset.mapStateReady !== "true") {
      frame.addEventListener("load", showReady, { once: true });
      frame.dataset.mapStateReady = "true";
    }

    container.classList.remove("is-ready");
    container.classList.add("is-loading");
  });
}

loadHomeCatalogData();
setupHomeHashNavigation();
consumePendingHomeScroll();
setupMenu();
setupScrollControls();
setupArticleBenefitsPager();
setupLeadPopup();
setupPhoneMask();
setupForm();
markMissingImages();
setupMapLoading();
