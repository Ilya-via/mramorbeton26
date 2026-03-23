const catalogItems = [
  {
    title: "Армированные накладные проступи",
    description: "Решение для облицовки и усиления лестничных ступеней",
    image: "assets/images/catalog-main-steps.png",
    className: "catalog-card-large",
  },
  {
    title: "Тротуарная плитка",
    description: "Прочные решения для мощения",
    image: "assets/images/catalog-paving.png",
    className: "catalog-card-top-center",
  },
  {
    title: "Фасадные панели",
    description: "Современная облицовка фасадов",
    image: "assets/images/catalog-facade.png",
    className: "catalog-card-top-right",
  },
  {
    title: "Бордюры и водостоки",
    description: "Функциональное оформление территории",
    image: "assets/images/catalog-curbs.png",
    className: "catalog-card-middle-center",
  },
  {
    title: "Армированные ритуальные плиты",
    description: "Функциональное оформление территории",
    image: "assets/images/catalog-ritual.png",
    className: "catalog-card-middle-right",
  },
  {
    title: "Армированные пошаговые плиты",
    description: "Долговечные мемориальные изделия",
    image: "assets/images/catalog-step-plates.png",
    className: "catalog-card-bottom-left",
  },
  {
    title: "Армированные парапетные плиты",
    description: "Для садовых и ландшафтных дорожек",
    image: "assets/images/catalog-parapet.png",
    className: "catalog-card-bottom-center",
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
    text: "Составляем подробную смету, фиксируем сроки и стоимость в договоре. Никаких скрытых платежей.",
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

const productItems = [
  {
    title: "Каменный цветок элит",
    meta: "Размеры(мм): 40 Вес(кг/м2): 90",
    price: "от 30.00 руб./m2",
    image: "assets/images/product-flower-elite.png",
  },
  {
    title: "Калифорния камень",
    meta: "Размеры(мм): 300x300x40",
    price: "от 30.00 руб./m2",
    image: "assets/images/product-california-stone.png",
  },
  {
    title: "Бордюр тротуарный 1000x220",
    meta: "Размеры(мм): 1000x220x75",
    price: "от 10 руб./шт",
    image: "assets/images/product-curb.png",
  },
  {
    title: "Пошаговая плита гладкая",
    meta: "Размеры(мм): 800x400x50",
    price: "от 35 руб./шт",
    image: "assets/images/product-step-slab.png",
  },
];

function renderCatalog() {
  const root = document.querySelector("#catalog-grid");
  if (!root) return;

  root.innerHTML = catalogItems
    .map(
      (item) => `
        <article class="catalog-card ${item.className}" tabindex="0">
          <img src="${item.image}" alt="${item.title}" loading="lazy">
          <div class="catalog-card-content">
            <h3>${item.title}</h3>
            <p class="catalog-card-desc">${item.description}</p>
            <span class="link-line">В каталог</span>
          </div>
        </article>
      `
    )
    .join("");
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
      (item) => `
        <article class="process-step">
          <strong>${item.number}</strong>
          <div>
            <h3>${item.title}</h3>
            <p>${item.text}</p>
          </div>
        </article>
      `
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
          <div class="product-media image-frame">
            <img src="${item.image}" alt="${item.title}" loading="lazy">
          </div>
          <div class="product-content">
            <h3>${item.title}</h3>
            <p class="product-meta">${item.meta}</p>
            <div class="product-bottom">
              <span class="product-price">${item.price}</span>
              <a class="product-action" href="#contact-form" aria-label="Заказать ${item.title}">+</a>
            </div>
          </div>
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
