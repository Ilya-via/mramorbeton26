function adminSlugify(value) {
  const map = {
    а: "a", б: "b", в: "v", г: "g", д: "d", е: "e", ё: "e",
    ж: "zh", з: "z", и: "i", й: "y", к: "k", л: "l", м: "m",
    н: "n", о: "o", п: "p", р: "r", с: "s", т: "t", у: "u",
    ф: "f", х: "h", ц: "cz", ч: "ch", ш: "sh", щ: "shh",
    ъ: "", ы: "y", ь: "", э: "e", ю: "yu", я: "ya",
  };

  return value
    .trim()
    .toLowerCase()
    .split("")
    .map((char) => map[char] ?? char)
    .join("")
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "") || "item";
}

function resolveAdminPreviewSrc(src) {
  const value = (src || "").trim();
  if (!value) return "";

  if (
    value.startsWith("http://") ||
    value.startsWith("https://") ||
    value.startsWith("data:") ||
    value.startsWith("blob:") ||
    value.startsWith("/") ||
    value.startsWith("../") ||
    value.startsWith("./")
  ) {
    return value;
  }

  return `../${value.replace(/^\/+/, "")}`;
}

function setupAutoSlug() {
  document.querySelectorAll("[data-autoslug-source]").forEach((source) => {
    const targetSelector = source.getAttribute("data-autoslug-target");
    const target = targetSelector ? document.querySelector(targetSelector) : null;
    if (!target) return;

    let touched = target.value.trim() !== "";
    target.addEventListener("input", () => {
      touched = target.value.trim() !== "";
    });

    source.addEventListener("input", () => {
      if (touched) return;
      target.value = adminSlugify(source.value);
    });
  });
}

function previewImage(wrapper, src) {
  const image = wrapper.querySelector("img");
  const placeholder = wrapper.querySelector("[data-preview-placeholder]");
  if (!image || !placeholder) return;

  const resolvedSrc = resolveAdminPreviewSrc(src);

  if (resolvedSrc) {
    image.src = resolvedSrc;
    image.hidden = false;
    placeholder.hidden = true;
  } else {
    image.removeAttribute("src");
    image.hidden = true;
    placeholder.hidden = false;
  }
}

function setupImagePreviews() {
  document.querySelectorAll("[data-image-preview]").forEach((wrapper) => {
    const inputSelector = wrapper.getAttribute("data-preview-input");
    const uploadSelector = wrapper.getAttribute("data-preview-upload");
    const pathInput = inputSelector ? document.querySelector(inputSelector) : null;
    const uploadInput = uploadSelector ? document.querySelector(uploadSelector) : null;

    if (pathInput) {
      previewImage(wrapper, pathInput.value.trim());
      pathInput.addEventListener("input", () => {
        if (uploadInput && uploadInput.files && uploadInput.files.length) return;
        previewImage(wrapper, pathInput.value.trim());
      });
    }

    if (uploadInput) {
      uploadInput.addEventListener("change", () => {
        const file = uploadInput.files && uploadInput.files[0];
        if (!file) {
          previewImage(wrapper, pathInput ? pathInput.value.trim() : "");
          return;
        }
        previewImage(wrapper, URL.createObjectURL(file));
      });
    }
  });
}

function setupSingleImageUploaders() {
  document.querySelectorAll("[data-single-image-uploader]").forEach((wrapper) => {
    const hiddenSelector = wrapper.getAttribute("data-hidden-input");
    const fileSelector = wrapper.getAttribute("data-file-input");
    const hiddenInput = hiddenSelector ? document.querySelector(hiddenSelector) : null;
    const fileInput = fileSelector ? document.querySelector(fileSelector) : null;
    const trigger = wrapper.querySelector("[data-file-trigger]");
    const previewWrapper = wrapper.querySelector("[data-image-preview]");
    if (!hiddenInput || !fileInput || !previewWrapper) return;

    const openDialog = () => fileInput.click();
    if (trigger) {
      trigger.addEventListener("click", openDialog);
    }

    wrapper.addEventListener("dragover", (event) => {
      event.preventDefault();
      wrapper.classList.add("is-dragover");
    });
    wrapper.addEventListener("dragleave", () => wrapper.classList.remove("is-dragover"));
    wrapper.addEventListener("drop", (event) => {
      event.preventDefault();
      wrapper.classList.remove("is-dragover");
      const file = event.dataTransfer?.files?.[0];
      if (!file) return;
      const dt = new DataTransfer();
      dt.items.add(file);
      fileInput.files = dt.files;
      previewImage(previewWrapper, URL.createObjectURL(file));
    });

    fileInput.addEventListener("change", () => {
      const file = fileInput.files?.[0];
      if (!file) {
        previewImage(previewWrapper, hiddenInput.value.trim());
        return;
      }
      previewImage(previewWrapper, URL.createObjectURL(file));
    });
  });
}

function createCollectionRow(config, values = []) {
  const row = document.createElement("div");
  row.className = "admin-collection__row";
  const previewMarkup = config.previewField
    ? `
      <div class="admin-collection__preview">
        <div class="admin-collection__preview-placeholder" data-collection-preview-placeholder>Здесь будет показано изображение</div>
        <img alt="Предпросмотр изображения" data-collection-preview hidden>
      </div>
    `
    : "";
  row.innerHTML = `
    ${previewMarkup}
    <div class="admin-collection__fields"></div>
    <button class="admin-button--ghost admin-collection__remove" type="button">Удалить</button>
  `;

  const fieldsRoot = row.querySelector(".admin-collection__fields");
  let previewInput = null;
  config.fields.forEach((field, index) => {
    const fieldWrap = document.createElement("div");
    fieldWrap.className = "admin-form__field";
    fieldWrap.innerHTML = `
      <label>${field.label}</label>
      <input type="text" data-collection-field="${field.key}" placeholder="${field.placeholder || ""}">
    `;
    const input = fieldWrap.querySelector("input");
    input.value = values[index] || "";
    if (config.previewField && field.key === config.previewField) {
      previewInput = input;
    }
    fieldsRoot.appendChild(fieldWrap);
  });

  if (previewInput) {
    const preview = row.querySelector("[data-collection-preview]");
    const placeholder = row.querySelector("[data-collection-preview-placeholder]");
    const updatePreview = () => {
      const src = previewInput.value.trim();
      if (!preview || !placeholder) return;
      const resolvedSrc = resolveAdminPreviewSrc(src);
      if (resolvedSrc) {
        preview.src = resolvedSrc;
        preview.hidden = false;
        placeholder.hidden = true;
      } else {
        preview.removeAttribute("src");
        preview.hidden = true;
        placeholder.hidden = false;
      }
    };
    previewInput.addEventListener("input", updatePreview);
    updatePreview();
  }

  row.querySelector(".admin-collection__remove").addEventListener("click", () => {
    row.remove();
  });

  return row;
}

function setupCollectionEditors() {
  document.querySelectorAll("[data-collection-editor]").forEach((editor) => {
    const textareaSelector = editor.getAttribute("data-target");
    const textarea = textareaSelector ? document.querySelector(textareaSelector) : null;
    const rowsRoot = editor.querySelector("[data-collection-rows]");
    const addButton = editor.querySelector("[data-collection-add]");
    if (!textarea || !rowsRoot || !addButton) return;

    const fields = JSON.parse(editor.getAttribute("data-fields") || "[]");
    const separator = editor.getAttribute("data-separator") || "|";
    const config = {
      fields,
      previewField: editor.getAttribute("data-preview-field") || "",
    };

    const existingLines = textarea.value
      .split(/\r?\n/)
      .map((line) => line.trim())
      .filter(Boolean);

    const addRow = (values = []) => {
      rowsRoot.appendChild(createCollectionRow(config, values));
    };

    if (existingLines.length) {
      existingLines.forEach((line) => addRow(line.split(separator)));
    } else {
      addRow();
    }

    addButton.addEventListener("click", () => addRow());

    const form = editor.closest("form");
    if (!form) return;

    form.addEventListener("submit", () => {
      const lines = Array.from(rowsRoot.querySelectorAll(".admin-collection__row"))
        .map((row) =>
          fields
            .map((field) => {
              const input = row.querySelector(`[data-collection-field="${field.key}"]`);
              return input ? input.value.trim() : "";
            })
            .join(separator)
        )
        .filter((line) => line.replaceAll(separator, "").trim() !== "");
      textarea.value = lines.join("\n");
    });
  });
}

function setupRecommendationFilter() {
  document.querySelectorAll("[data-filter-input]").forEach((input) => {
    const targetSelector = input.getAttribute("data-filter-target");
    const items = targetSelector ? document.querySelectorAll(`${targetSelector} [data-filter-item]`) : [];
    input.addEventListener("input", () => {
      const query = input.value.trim().toLowerCase();
      items.forEach((item) => {
        const text = (item.getAttribute("data-filter-item") || item.textContent || "").toLowerCase();
        item.hidden = query !== "" && !text.includes(query);
      });
    });
  });
}

function setupRecommendationLimit() {
  const checkboxes = Array.from(document.querySelectorAll('input[name="related_ids[]"]'));
  if (!checkboxes.length) return;

  const updateState = () => {
    const checked = checkboxes.filter((input) => input.checked);
    const limitReached = checked.length >= 3;

    checkboxes.forEach((input) => {
      input.disabled = limitReached && !input.checked;
    });
  };

  checkboxes.forEach((input) => {
    input.addEventListener("change", updateState);
  });

  updateState();
}

function createThicknessPriceRow(values = {}) {
  const row = document.createElement("div");
  row.className = "admin-thickness-price-row";
  row.innerHTML = `
    <div class="admin-form__field">
      <label>Название</label>
      <input type="text" data-price-field="label" placeholder="Серый">
    </div>
    <div class="admin-form__field">
      <label>Цена</label>
      <input type="text" data-price-field="amount" placeholder="30">
    </div>
    <div class="admin-form__field">
      <label>Единица</label>
      <input type="text" data-price-field="unit" placeholder="руб/м²">
    </div>
    <button class="admin-button--ghost" type="button" data-price-remove>Удалить цену</button>
  `;

  row.querySelector('[data-price-field="label"]').value = values.label || "";
  row.querySelector('[data-price-field="amount"]').value = values.amount || "";
  row.querySelector('[data-price-field="unit"]').value = values.unit || "";
  row.querySelector("[data-price-remove]").addEventListener("click", () => row.remove());

  return row;
}

function createThicknessCard(option = {}) {
  const card = document.createElement("div");
  card.className = "admin-thickness-card";
  card.innerHTML = `
    <div class="admin-thickness-card__head">
      <h4 class="admin-thickness-card__title">Вариант толщины</h4>
      <button class="admin-button--danger" type="button" data-thickness-remove>Удалить вариант</button>
    </div>
    <div class="admin-form__grid">
      <div class="admin-form__field">
        <label>Название кнопки</label>
        <input type="text" data-thickness-field="label" placeholder="Стандарт">
      </div>
      <div class="admin-form__field">
        <label>Текст толщины</label>
        <input type="text" data-thickness-field="value" placeholder="30, 35, 40 мм">
      </div>
      <div class="admin-form__field">
        <label>Размер для этого варианта</label>
        <input type="text" data-thickness-field="spec_size" placeholder="1210×320×40">
      </div>
      <div class="admin-form__field">
        <label>Вес для этого варианта</label>
        <input type="text" data-thickness-field="spec_weight" placeholder="90 кг/м²">
      </div>
    </div>
    <div class="admin-thickness-card__prices">
      <div class="admin-toolbar">
        <strong>Цены для этого варианта</strong>
        <button class="admin-button--ghost" type="button" data-price-add>Добавить цену</button>
      </div>
      <div data-price-rows></div>
    </div>
  `;

  card.querySelector('[data-thickness-field="label"]').value = option.label || "";
  card.querySelector('[data-thickness-field="value"]').value = option.value || "";
  card.querySelector('[data-thickness-field="spec_size"]').value = option.spec_size || "";
  card.querySelector('[data-thickness-field="spec_weight"]').value = option.spec_weight || "";

  const pricesRoot = card.querySelector("[data-price-rows]");
  const prices = Array.isArray(option.prices) && option.prices.length ? option.prices : [{}];
  prices.forEach((price) => pricesRoot.appendChild(createThicknessPriceRow(price)));

  card.querySelector("[data-price-add]").addEventListener("click", () => {
    pricesRoot.appendChild(createThicknessPriceRow({}));
  });
  card.querySelector("[data-thickness-remove]").addEventListener("click", () => card.remove());

  return card;
}

function setupThicknessEditor() {
  document.querySelectorAll("[data-thickness-editor]").forEach((editor) => {
    const targetSelector = editor.getAttribute("data-target");
    const textarea = targetSelector ? document.querySelector(targetSelector) : null;
    const rowsRoot = editor.querySelector("[data-thickness-rows]");
    const addButton = editor.querySelector("[data-thickness-add]");
    if (!textarea || !rowsRoot || !addButton) return;

    let existing = [];
    try {
      existing = JSON.parse(textarea.value || "[]");
      if (!Array.isArray(existing)) existing = [];
    } catch (error) {
      existing = [];
    }

    const addCard = (option = {}) => {
      rowsRoot.appendChild(createThicknessCard(option));
    };

    if (existing.length) {
      existing.forEach((option) => addCard(option));
    } else {
      addCard({});
    }

    addButton.addEventListener("click", () => addCard({}));

    const form = editor.closest("form");
    if (!form) return;

    form.addEventListener("submit", () => {
      const payload = Array.from(rowsRoot.querySelectorAll(".admin-thickness-card")).map((card, index) => {
        const label = card.querySelector('[data-thickness-field="label"]')?.value.trim() || "";
        const value = card.querySelector('[data-thickness-field="value"]')?.value.trim() || "";
        const specSize = card.querySelector('[data-thickness-field="spec_size"]')?.value.trim() || "";
        const specWeight = card.querySelector('[data-thickness-field="spec_weight"]')?.value.trim() || "";
        const prices = Array.from(card.querySelectorAll(".admin-thickness-price-row")).map((row) => ({
          label: row.querySelector('[data-price-field="label"]')?.value.trim() || "",
          amount: row.querySelector('[data-price-field="amount"]')?.value.trim() || "",
          unit: row.querySelector('[data-price-field="unit"]')?.value.trim() || "",
        })).filter((row) => row.label || row.amount || row.unit);

        return {
          key: adminSlugify(label || `variant-${index + 1}`),
          label,
          value,
          spec_size: specSize,
          spec_weight: specWeight,
          prices,
        };
      }).filter((option) => option.label || option.value || option.spec_size || option.spec_weight || option.prices.length);

      textarea.value = JSON.stringify(payload);
    });
  });
}

function createGalleryCard({ src = "", isUploaded = false, fileIndex = -1 } = {}) {
  const card = document.createElement("div");
  card.className = "admin-gallery-card";
  card.dataset.galleryType = isUploaded ? "uploaded" : "existing";
  if (fileIndex >= 0) {
    card.dataset.fileIndex = String(fileIndex);
  }

  card.innerHTML = `
    <div class="admin-gallery-card__preview">
      <img alt="Изображение галереи">
    </div>
    <div class="admin-gallery-card__footer">
      <button class="admin-button--ghost" type="button" data-gallery-remove>Удалить</button>
    </div>
  `;

  const image = card.querySelector("img");
  image.src = isUploaded ? src : resolveAdminPreviewSrc(src);

  return card;
}

function setupGalleryManagers() {
  document.querySelectorAll("[data-gallery-manager]").forEach((manager) => {
    const textareaSelector = manager.getAttribute("data-target");
    const fileSelector = manager.getAttribute("data-file-input");
    const textarea = textareaSelector ? document.querySelector(textareaSelector) : null;
    const fileInput = fileSelector ? document.querySelector(fileSelector) : null;
    const grid = manager.querySelector("[data-gallery-grid]");
    const trigger = manager.querySelector("[data-gallery-trigger]");
    const dropzone = manager.querySelector("[data-gallery-dropzone]");
    if (!textarea || !fileInput || !grid || !dropzone) return;

    const existingItems = textarea.value
      .split(/\r?\n/)
      .map((line) => line.trim())
      .filter(Boolean)
      .map((line) => ({ src: line.split("|")[0].trim() }))
      .filter((item) => item.src);

    let uploadedFiles = [];

    function syncInputFiles() {
      const dt = new DataTransfer();
      uploadedFiles.forEach((file) => dt.items.add(file));
      fileInput.files = dt.files;
    }

    function render() {
      grid.innerHTML = "";

      existingItems.forEach((item, index) => {
        const card = createGalleryCard({ src: item.src });
        card.querySelector("[data-gallery-remove]").addEventListener("click", () => {
          existingItems.splice(index, 1);
          render();
        });
        grid.appendChild(card);
      });

      uploadedFiles.forEach((file, index) => {
        const card = createGalleryCard({
          src: URL.createObjectURL(file),
          isUploaded: true,
          fileIndex: index,
        });
        card.querySelector("[data-gallery-remove]").addEventListener("click", () => {
          uploadedFiles.splice(index, 1);
          syncInputFiles();
          render();
        });
        grid.appendChild(card);
      });
    }

    function addFiles(fileList) {
      const files = Array.from(fileList || []).filter((file) => file.type.startsWith("image/"));
      if (!files.length) return;
      uploadedFiles = uploadedFiles.concat(files);
      syncInputFiles();
      render();
    }

    if (trigger) {
      trigger.addEventListener("click", () => fileInput.click());
    }

    fileInput.addEventListener("change", () => {
      addFiles(fileInput.files);
    });

    dropzone.addEventListener("dragover", (event) => {
      event.preventDefault();
      dropzone.classList.add("is-dragover");
    });
    dropzone.addEventListener("dragleave", () => dropzone.classList.remove("is-dragover"));
    dropzone.addEventListener("drop", (event) => {
      event.preventDefault();
      dropzone.classList.remove("is-dragover");
      addFiles(event.dataTransfer?.files);
    });

    const form = manager.closest("form");
    if (form) {
      form.addEventListener("submit", () => {
        textarea.value = existingItems.map((item) => item.src).join("\n");
      });
    }

    render();
  });
}

function setupThicknessVisibility() {
  const toggle = document.querySelector("[data-thickness-toggle]");
  if (!toggle) return;

  const thicknessBlocks = document.querySelectorAll("[data-show-when-thickness]");
  const regularPriceBlocks = document.querySelectorAll("[data-show-when-no-thickness]");

  const update = () => {
    const enabled = toggle.checked;
    thicknessBlocks.forEach((block) => {
      block.hidden = !enabled;
    });
    regularPriceBlocks.forEach((block) => {
      block.hidden = enabled;
    });
  };

  toggle.addEventListener("change", update);
  update();
}

setupAutoSlug();
setupImagePreviews();
setupSingleImageUploaders();
setupCollectionEditors();
setupRecommendationFilter();
setupRecommendationLimit();
setupThicknessEditor();
setupThicknessVisibility();
setupGalleryManagers();
