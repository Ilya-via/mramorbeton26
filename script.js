const CATEGORY_PAGE_BASE = "category.php?c=";

const homeCatalogItems = [
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

const catalogPageItems = [
  {
    slug: "trotuarnaya-plitka",
    title: "Тротуарная плитка",
    description: "Надёжные бетонные изделия для благоустройства и строительства",
    image: "assets/images/catalog-1.png",
  },
  {
    slug: "fasadnye-paneli",
    title: "Фасадные панели",
    description: "Декоративная отделка фасадов и цоколей",
    image: "assets/images/catalog-2.png",
  },
  {
    slug: "nakladnye-prostupi",
    title: "Армированные накладные проступи",
    description: "Бетонные элементы для облицовки лестниц",
    image: "assets/images/catalog-3.png",
  },
  {
    slug: "bordyury-i-vodostoki",
    title: "Бордюры и водостоки",
    description: "Организация границ и отвода воды",
    image: "assets/images/catalog-4.png",
  },
  {
    slug: "ritualnye-plity",
    title: "Армированные ритуальные плиты",
    description: "Плиты и элементы для благоустройства мемориальных зон",
    image: "assets/images/catalog-5.png",
  },
  {
    slug: "parapetnye-plity",
    title: "Армированные парапетные плиты",
    description: "Защитные бетонные крышки для заборов и ограждений",
    image: "assets/images/catalog-6.png",
  },
  {
    slug: "poshagovye-plity",
    title: "Армированные пошаговые плиты",
    description: "Плиты для декоративных садовых дорожек",
    image: "assets/images/catalog-7.png",
  },
];

const featureItems = [
  {
    number: "01",
    title: "Высокопрочный бетон",
    text: "Использование бетона марки M500 со специальными добавками обеспечивает исключительную плотность и прочность изделий на протяжении десятилетий.",
    tone: "",
  },
  {
    number: "02",
    title: "Морозостойкость",
    text: "Особая структура бетона предотвращает образование трещин при резких перепадах температур, что критично для климата Минска.",
    tone: "dark",
  },
  {
    number: "03",
    title: "Эстетика и стиль",
    text: "Широкий ассортимент форм, размеров и цветов позволяет создавать уникальные и стильные покрытия для любых ландшафтных проектов.",
    tone: "accent",
  },
];

const instagramItems = [
  { image: "assets/images/instagram-1.png", alt: "Фотография объекта из Instagram 1" },
  { image: "assets/images/instagram-2.png", alt: "Фотография объекта из Instagram 2" },
  { image: "assets/images/instagram-3.png", alt: "Фотография объекта из Instagram 3" },
  { image: "assets/images/instagram-4.png", alt: "Фотография объекта из Instagram 4" },
];

const processItems = [
  {
    number: "01",
    title: "Заявка и консультация",
    text: "Оставляете заявку, наш специалист уточняет детали и помогает с выбором материалов под ваши задачи.",
  },
  {
    number: "02",
    title: "Расчет и договор",
    text: "Составляем подробную смету, фиксируем сроки и стоимость в договоре.",
    textExtra: "Никаких скрытых платежей.",
  },
  {
    number: "03",
    title: "Производство",
    text: "Запускаем ваш заказ в работу. Вы можете в любой момент приехать на производство и увидеть процесс.",
  },
  {
    number: "04",
    title: "Доставка и приемка",
    text: "Привозим готовую продукцию, разгружаем и подписываем акт приемки. Наслаждаетесь результатом.",
  },
];

const PRODUCT_PAGE_BASE = "product.php?p=";

const productItems = [
  {
    slug: "fasad-tsvetok-elit",
    title: "Каменный цветок элит",
    metaLines: ["Размеры(мм): 40", "Вес(кг/м2): 90"],
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

function formatProductPrice(item) {
  if (item.pricePerSqm) {
    return `${item.priceAmount}/m<sup class="product-price__sq">2</sup>`;
  }
  return item.priceAmount;
}

function formatProductMeta(item) {
  const lines = item.metaLines && item.metaLines.length ? item.metaLines : [item.meta || ""];
  return lines.map((line) => `<p>${line}</p>`).join("");
}

function renderCatalog() {
  const homeRoot = document.querySelector("#catalog-grid");
  const pageRoot = document.querySelector("#catalog-page-grid");

  if (homeRoot) {
    const items = homeCatalogItems;
    const cardMarkup = (item) => `
          <a class="catalog-card ${item.className} catalog-card-link" href="${CATEGORY_PAGE_BASE}${item.slug}">
            <img src="${item.image}" alt="${item.title}" loading="lazy">
            <div class="catalog-card-content">
              <h3>${item.title}</h3>
              <p class="catalog-card-desc">${item.description}</p>
              <span class="link-line">В каталог</span>
            </div>
          </a>
        `;
    const top = items.slice(0, 5).map((item) => cardMarkup(item)).join("");
    const row3 = items
      .slice(5, 7)
      .map((item) => cardMarkup({ ...item, className: "catalog-card--row3-tile" }))
      .join("");
    homeRoot.innerHTML = `${top}<div class="catalog-grid__row3">${row3}</div>`;
  }

  if (pageRoot) {
    pageRoot.innerHTML = catalogPageItems
      .map(
        (item) => `
          <a class="catalog-card catalog-page-card catalog-card-link" href="${CATEGORY_PAGE_BASE}${item.slug}">
            <div class="catalog-page-card-media">
              <img src="${item.image}" alt="${item.title}" loading="lazy">
            </div>
            <div class="catalog-page-card-content">
              <h3>${item.title}</h3>
              <p class="catalog-card-desc">${item.description}</p>
            </div>
          </a>
        `
      )
      .join("");
  }
}

function renderFeatures() {
  const root = document.querySelector("#features-grid");
  if (!root) return;

  root.innerHTML = featureItems
    .map(
      (item) => `
        <article class="feature-card ${item.tone}">
          <div class="feature-card-top">
            <h3>${item.title}</h3>
            <span class="feature-card-badge" aria-hidden="true">${item.number}</span>
          </div>
          <p>${item.text}</p>
        </article>
      `
    )
    .join("");
}

function renderInstagram() {
  const root = document.querySelector("#instagram-grid");
  if (!root) return;

  root.innerHTML = instagramItems
    .map(
      (item) => `
        <article class="instagram-card">
          <div class="image-frame">
            <img src="${item.image}" alt="${item.alt}" loading="lazy">
          </div>
        </article>
      `
    )
    .join("");
}

function renderProcess() {
  const root = document.querySelector("#process-steps");
  if (!root) return;

  root.innerHTML = processItems
    .map(
      (item, index) => {
        const extra = item.textExtra
          ? `<p class="process-step__text">${item.textExtra}</p>`
          : "";
        return `
        <article class="process-step${index === 0 ? " process-step--current" : ""}">
          <span class="process-step__number" aria-hidden="true">${item.number}</span>
          <div class="process-step__body">
            <h3 class="process-step__title">${item.title}</h3>
            <div class="process-step__text-block">
              <p class="process-step__text">${item.text}</p>
              ${extra}
            </div>
          </div>
        </article>
      `;
      }
    )
    .join("");
}

function renderProducts() {
  const root = document.querySelector("#products-grid");
  if (!root) return;

  root.innerHTML = productItems
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
}

function setupMenu() {
  const toggle = document.querySelector(".menu-toggle");
  const nav = document.querySelector(".site-nav");
  if (!toggle || !nav) return;

  toggle.addEventListener("click", () => {
    const isOpen = nav.classList.toggle("is-open");
    toggle.setAttribute("aria-expanded", String(isOpen));
  });

  nav.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      nav.classList.remove("is-open");
      toggle.setAttribute("aria-expanded", "false");
    });
  });
}

function setupPhoneMask() {
  const input = document.querySelector('input[name="phone"]');
  if (!input) return;

  input.addEventListener("input", () => {
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
  });
}

function setupForm() {
  const form = document.querySelector("#contact-form");
  const status = document.querySelector("#form-status");
  if (!form || !status) return;

  form.addEventListener("submit", (event) => {
    event.preventDefault();
    status.textContent = "Заявка отправлена. Здесь можно подключить почту, Telegram-бота или CRM.";
    form.reset();
  });
}

function markMissingImages() {
  document.querySelectorAll("img").forEach((image) => {
    image.addEventListener("error", () => {
      const frame = image.closest(".image-frame") || image.parentElement;
      if (frame) {
        frame.classList.add("is-missing");
      }
    });

    if (image.complete && image.naturalWidth === 0) {
      const frame = image.closest(".image-frame") || image.parentElement;
      if (frame) {
        frame.classList.add("is-missing");
      }
    }
  });
}

renderCatalog();
renderFeatures();
renderInstagram();
renderProcess();
renderProducts();
setupMenu();
setupPhoneMask();
setupForm();
markMissingImages();
