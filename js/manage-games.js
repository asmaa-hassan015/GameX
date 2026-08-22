// =========================================================
// API
// =========================================================

const API = {
  get: "/GameX/BACKEND/manage-games/games.php",
  add: "/GameX/BACKEND/manage-games/add-game.php",
  update: "/GameX/BACKEND/manage-games/update-game.php",
  delete: "/GameX/BACKEND/manage-games/delete-game.php",
};

// =========================================================
// GLOBAL VARIABLES
// =========================================================

let allGames = [];

let currentPage = 1;

const gamesPerPage = 6;

let editingGameId = null;

let detailsGameId = null;

// =========================================================
// DOM ELEMENTS
// =========================================================

const gamesList = document.getElementById("gamesList");

const gamesPagination = document.getElementById("gamesPagination");

const addPage = document.getElementById("addPage");

const gameForm = document.getElementById("gameForm");

const formTitle = document.getElementById("formTitle");

const submitBtn = document.getElementById("submitBtn");

const detailsPage = document.getElementById("detailsPage");

const detailsEditBtn = document.getElementById("detailsEditBtn");

// =========================================================
// LOAD GAMES
// =========================================================

async function loadGames() {
  try {
    showLoading();

    console.log("Fetching:", API.get);

    const response = await fetch(API.get, {
      method: "GET",
      headers: {
        Accept: "application/json",
      },
    });

    const text = await response.text();

    console.log("Games API Response:", text);

    if (!response.ok) {
      throw new Error(`HTTP Error: ${response.status}`);
    }

    let data;

    try {
      data = JSON.parse(text);
    } catch (error) {
      console.error("Invalid JSON:", text);

      throw new Error("Server returned invalid JSON.");
    }

    if (!data.success) {
      throw new Error(data.message || "Failed to load games.");
    }

    allGames = Array.isArray(data.games) ? data.games : [];

    currentPage = 1;

    renderGames();
  } catch (error) {
    console.error("Load Games Error:", error);

    if (gamesList) {
      gamesList.innerHTML = `
                <div class="
                    py-10
                    text-center
                    text-red-400
                ">
                    ${escapeHTML(error.message || "Failed to load games.")}
                </div>
            `;
    }

    if (gamesPagination) {
      gamesPagination.innerHTML = "";
    }
  }
}

// =========================================================
// LOADING STATE
// =========================================================

function showLoading() {
  if (!gamesList) {
    return;
  }

  gamesList.innerHTML = `
        <div class="
            py-10
            text-center
            text-[#A5A1B5]
        ">
            Loading games...
        </div>
    `;
}

// =========================================================
// RENDER GAMES
// =========================================================

function renderGames() {
  if (!gamesList) {
    return;
  }

  if (allGames.length === 0) {
    gamesList.innerHTML = `
            <div class="
                py-10
                text-center
                text-[#A5A1B5]
            ">
                No games found.
            </div>
        `;

    renderPagination();

    return;
  }

  const startIndex = (currentPage - 1) * gamesPerPage;

  const endIndex = startIndex + gamesPerPage;

  const currentGames = allGames.slice(startIndex, endIndex);

  gamesList.innerHTML = currentGames
    .map((game) => createGameRow(game))
    .join("");

  renderPagination();
}

// =========================================================
// CREATE GAME ROW
// =========================================================

function createGameRow(game) {
  const image = game.image || "src/Images/games/default.png";

  const title = game.title || "Untitled Game";

  const category = game.category || game.categories || "Other";

  const price = Number(game.price || 0).toFixed(2);

  const status = game.status === "active" ? "active" : "inactive";

  const statusClass =
    status === "active"
      ? `
                bg-green-500/10
                text-green-400
                border-green-500/20
            `
      : `
                bg-red-500/10
                text-red-400
                border-red-500/20
            `;

  const statusText = status === "active" ? "Active" : "Inactive";

  return `
        <div class="
            grid
            grid-cols-[minmax(0,1fr)_70px_60px]
            sm:grid-cols-[minmax(0,1fr)_90px_75px]
            md:grid-cols-[minmax(0,1fr)_140px_90px_100px_90px]
            items-center
            px-3
            sm:px-5
            py-3.5
            border-b
            border-[#26223D]
            hover:bg-[#0D0B1A]
            transition
        ">

            <!-- GAME -->

            <div class="
                flex
                items-center
                gap-3
                min-w-0
            ">

                <img
                    src="${escapeHTML(image)}"
                    alt="${escapeHTML(title)}"
                    class="
                        w-11
                        h-11
                        sm:w-12
                        sm:h-12
                        rounded-lg
                        object-cover
                        shrink-0
                    "
                    onerror="
                        this.onerror=null;
                        this.src='src/Images/games/default.png'
                    "
                >

                <div class="min-w-0">

                    <p class="
                        text-white
                        text-sm
                        font-medium
                        truncate
                    ">
                        ${escapeHTML(title)}
                    </p>

                    <p class="
                        text-[#6B687A]
                        text-[11px]
                        truncate
                        mt-0.5
                    ">
                        ${escapeHTML(game.developer || "Unknown developer")}
                    </p>

                </div>

            </div>

            <!-- CATEGORY -->

            <div class="
                hidden
                md:block
                text-[#A5A1B5]
                text-sm
                truncate
            ">
                ${escapeHTML(category)}
            </div>

            <!-- PRICE -->

            <div class="
                text-[#F5F3FF]
                text-sm
                whitespace-nowrap
            ">
                $${price}
            </div>

            <!-- STATUS -->

            <div class="
                hidden
                md:flex
                justify-center
                relative
            ">

                <button
                    type="button"
                    onclick="
                        toggleStatusMenu(
                            ${Number(game.id)},
                            event
                        )
                    "
                    class="
                        inline-flex
                        items-center
                        gap-1.5
                        px-2.5
                        py-1
                        rounded-full
                        border
                        text-xs
                        font-medium
                        cursor-pointer
                        transition
                        ${statusClass}
                    "
                >

                    ${statusText}

                    <i class="
                        fa-solid
                        fa-chevron-down
                        text-[9px]
                    "></i>

                </button>

                <div
                    id="statusMenu-${Number(game.id)}"
                    class="
                        status-menu
                        hidden
                        absolute
                        top-full
                        mt-1.5
                        right-0
                        w-28
                        bg-[#0D0B1A]
                        border
                        border-[#26223D]
                        rounded-lg
                        overflow-hidden
                        shadow-lg
                        z-20
                    "
                >

                    <button
                        type="button"
                        onclick="
                            selectGameStatus(
                                ${Number(game.id)},
                                'active'
                            )
                        "
                        class="
                            w-full
                            text-left
                            px-3
                            py-2
                            text-xs
                            text-green-400
                            hover:bg-[#1a1730]
                            transition
                        "
                    >
                        Active
                    </button>

                    <button
                        type="button"
                        onclick="
                            selectGameStatus(
                                ${Number(game.id)},
                                'inactive'
                            )
                        "
                        class="
                            w-full
                            text-left
                            px-3
                            py-2
                            text-xs
                            text-red-400
                            hover:bg-[#1a1730]
                            transition
                        "
                    >
                        Inactive
                    </button>

                </div>

            </div>

            <!-- ACTIONS -->

            <div class="
                flex
                items-center
                justify-end
                gap-1.5
            ">

                <button
                    type="button"
                    onclick="
                        editGame(
                            ${Number(game.id)}
                        )
                    "
                    title="Edit game"
                    class="
                        w-8
                        h-8
                        sm:w-9
                        sm:h-9
                        rounded-lg
                        bg-[#090b16]
                        border
                        border-[#24213a]
                        text-gray-400
                        hover:text-[#A855F7]
                        hover:border-[#7C2CFF]
                        transition
                    "
                >
                    <i class="
                        fa-solid
                        fa-pen
                        text-xs
                    "></i>
                </button>

                <button
                    type="button"
                    onclick="
                        viewGameDetails(
                            ${Number(game.id)}
                        )
                    "
                    title="View details"
                    class="
                        w-8
                        h-8
                        sm:w-9
                        sm:h-9
                        rounded-lg
                        bg-[#090b16]
                        border
                        border-[#24213a]
                        text-gray-400
                        hover:text-[#A855F7]
                        hover:border-[#7C2CFF]
                        transition
                    "
                >
                    <i class="
                        fa-solid
                        fa-eye
                        text-xs
                    "></i>
                </button>

                <button
                    type="button"
                    onclick="
                        deleteGame(
                            ${Number(game.id)}
                        )
                    "
                    title="Delete game"
                    class="
                        w-8
                        h-8
                        sm:w-9
                        sm:h-9
                        rounded-lg
                        bg-[#090b16]
                        border
                        border-[#24213a]
                        text-gray-400
                        hover:text-red-400
                        hover:border-red-500/40
                        transition
                    "
                >
                    <i class="
                        fa-solid
                        fa-trash
                        text-xs
                    "></i>
                </button>

            </div>

        </div>
    `;
}

// =========================================================
// PAGINATION
// =========================================================

function renderPagination() {
  if (!gamesPagination) {
    return;
  }

  const totalPages = Math.ceil(allGames.length / gamesPerPage);

  if (totalPages <= 1) {
    gamesPagination.innerHTML = "";

    return;
  }

  let buttons = "";

  // =====================================================
  // PREVIOUS
  // =====================================================

  buttons += `
        <button
            type="button"
            onclick="changePage(${currentPage - 1})"
            ${currentPage === 1 ? "disabled" : ""}
            class="
                w-9
                h-9
                rounded-lg
                border
                border-[#24213a]
                text-gray-400
                hover:text-white
                hover:border-[#7C2CFF]
                disabled:opacity-30
                disabled:cursor-not-allowed
                transition
            "
        >
            <i class="
                fa-solid
                fa-chevron-left
                text-xs
            "></i>
        </button>
    `;

  // =====================================================
  // PAGE NUMBERS
  // =====================================================

  for (let page = 1; page <= totalPages; page++) {
    const activeClass =
      page === currentPage
        ? `
                    bg-[#7C2CFF]
                    text-white
                    border-[#7C2CFF]
                `
        : `
                    bg-[#090b16]
                    text-gray-400
                    border-[#24213a]
                    hover:text-white
                    hover:border-[#7C2CFF]
                `;

    buttons += `
            <button
                type="button"
                onclick="changePage(${page})"
                class="
                    w-9
                    h-9
                    rounded-lg
                    border
                    ${activeClass}
                    transition
                "
            >
                ${page}
            </button>
        `;
  }

  // =====================================================
  // NEXT
  // =====================================================

  buttons += `
        <button
            type="button"
            onclick="changePage(${currentPage + 1})"
            ${currentPage === totalPages ? "disabled" : ""}
            class="
                w-9
                h-9
                rounded-lg
                border
                border-[#24213a]
                text-gray-400
                hover:text-white
                hover:border-[#7C2CFF]
                disabled:opacity-30
                disabled:cursor-not-allowed
                transition
            "
        >
            <i class="
                fa-solid
                fa-chevron-right
                text-xs
            "></i>
        </button>
    `;

  gamesPagination.innerHTML = buttons;
}

// =========================================================
// CHANGE PAGE
// =========================================================

function changePage(page) {
  const totalPages = Math.ceil(allGames.length / gamesPerPage);

  if (page < 1 || page > totalPages) {
    return;
  }

  currentPage = page;

  renderGames();
}

// =========================================================
// OPEN ADD PAGE
// =========================================================

function openAddPage() {
  editingGameId = null;

  if (gameForm) {
    gameForm.reset();
  }

  if (formTitle) {
    formTitle.textContent = "Add New Game";
  }

  if (submitBtn) {
    submitBtn.textContent = "Add Game";
  }

  const status = document.getElementById("gameStatus");

  if (status) {
    status.checked = true;
  }

  clearErrors();

  const previewWrap = document.getElementById("coverPreviewWrap");

  if (previewWrap) {
    previewWrap.classList.add("hidden");
  }

  if (addPage) {
    addPage.classList.remove("hidden");

    addPage.classList.add("flex");
  }
}

// =========================================================
// CLOSE ADD PAGE
// =========================================================

function closeAddPage() {
  if (addPage) {
    addPage.classList.add("hidden");

    addPage.classList.remove("flex");
  }

  editingGameId = null;

  clearErrors();
}

// =========================================================
// EDIT GAME
// =========================================================

function editGame(id) {
  const game = allGames.find((item) => Number(item.id) === Number(id));

  if (!game) {
    alert("Game not found.");

    return;
  }

  editingGameId = Number(id);

  const titleInput = document.getElementById("gameName");

  const categoryInput = document.getElementById("gameCategory");

  const priceInput = document.getElementById("gamePrice");

  const statusInput = document.getElementById("gameStatus");

  if (titleInput) {
    titleInput.value = game.title || "";
  }

  if (categoryInput) {
    categoryInput.value = game.category || game.categories || "Other";
  }

  if (priceInput) {
    priceInput.value = game.price ?? "";
  }

  if (statusInput) {
    statusInput.checked = game.status === "active";
  }

  // =====================================================
  // EXISTING IMAGE
  // =====================================================

  if (game.image) {
    const preview = document.getElementById("coverPreview");

    const previewWrap = document.getElementById("coverPreviewWrap");

    if (preview) {
      preview.src = game.image;
    }

    if (previewWrap) {
      previewWrap.classList.remove("hidden");
    }
  }

  if (formTitle) {
    formTitle.textContent = "Edit Game";
  }

  if (submitBtn) {
    submitBtn.textContent = "Update Game";
  }

  clearErrors();

  if (addPage) {
    addPage.classList.remove("hidden");

    addPage.classList.add("flex");
  }
}

// =========================================================
// SAVE GAME
// =========================================================

async function saveGame(event) {
  event.preventDefault();

  clearErrors();

  const titleInput = document.getElementById("gameName");

  const categoryInput = document.getElementById("gameCategory");

  const priceInput = document.getElementById("gamePrice");

  const statusInput = document.getElementById("gameStatus");

  const imageInput = document.getElementById("gameCover");

  const title = titleInput?.value.trim() || "";

  const category = categoryInput?.value.trim() || "Other";

  const price = priceInput?.value.trim() || "";

  // =====================================================
  // VALIDATION
  // =====================================================

  let valid = true;

  if (title.length < 2) {
    showError("gameNameError");

    valid = false;
  }

  if (price === "" || Number(price) < 0 || Number.isNaN(Number(price))) {
    showError("gamePriceError");

    valid = false;
  }

  if (!valid) {
    return false;
  }

  // =====================================================
  // CURRENT GAME
  // =====================================================

  const currentGame = editingGameId
    ? allGames.find((item) => Number(item.id) === Number(editingGameId))
    : null;

  // =====================================================
  // CREATE FORM DATA
  // =====================================================

  const formData = new FormData();

  formData.append("title", title);

  formData.append("category", category);

  formData.append("price", price);

  formData.append("status", statusInput?.checked ? "active" : "inactive");

  // =====================================================
  // IMAGE
  // =====================================================

  if (imageInput && imageInput.files && imageInput.files.length > 0) {
    const file = imageInput.files[0];

    formData.append("image", "src/Images/games/" + file.name);
  } else {
    formData.append("image", currentGame?.image || "");
  }

  // =====================================================
  // OTHER GAME DATA
  // =====================================================

  formData.append("developer", currentGame?.developer || "");

  formData.append("publisher", currentGame?.publisher || "");

  formData.append("description", currentGame?.description || "");

  formData.append("old_price", currentGame?.old_price ?? "");

  formData.append("discount", currentGame?.discount ?? 0);

  formData.append("release_date", currentGame?.release_date || "");

  formData.append("rating", currentGame?.rating ?? 0);

  formData.append("total_reviews", currentGame?.total_reviews ?? 0);

  // =====================================================
  // ADD OR UPDATE
  // =====================================================

  if (editingGameId) {
    formData.append("id", editingGameId);

    await updateGame(formData);
  } else {
    await addGame(formData);
  }

  return false;
}

// =========================================================
// ADD GAME
// =========================================================

async function addGame(formData) {
  try {
    setSubmitLoading(true);

    console.log("Sending Add Game Request...");

    // =================================================
    // DEBUG FORM DATA
    // =================================================

    for (const [key, value] of formData.entries()) {
      console.log(`${key}:`, value);
    }

    const response = await fetch(API.add, {
      method: "POST",
      body: formData,
      credentials: "same-origin",
    });

    const text = await response.text();

    console.log("Add Game Response:", text);

    let data;

    try {
      data = JSON.parse(text);
    } catch {
      console.error("Invalid JSON:", text);

      throw new Error("Server returned invalid JSON.");
    }

    if (!response.ok || !data.success) {
      throw new Error(data.message || "Failed to add game.");
    }

    alert("Game added successfully.");

    closeAddPage();

    await loadGames();
  } catch (error) {
    console.error("Add Game Error:", error);

    alert(error.message || "Failed to add game.");
  } finally {
    setSubmitLoading(false);
  }
}

// =========================================================
// UPDATE GAME
// =========================================================

async function updateGame(formData) {
  try {
    setSubmitLoading(true);

    const response = await fetch(API.update, {
      method: "POST",
      body: formData,
      credentials: "same-origin",
    });

    const text = await response.text();

    console.log("Update Game Response:", text);

    let data;

    try {
      data = JSON.parse(text);
    } catch {
      throw new Error("Invalid JSON returned from update-game.php.");
    }

    if (!response.ok || !data.success) {
      throw new Error(data.message || "Failed to update game.");
    }

    alert("Game updated successfully.");

    editingGameId = null;

    closeAddPage();

    await loadGames();
  } catch (error) {
    console.error("Update Game Error:", error);

    alert(error.message || "Failed to update game.");
  } finally {
    setSubmitLoading(false);
  }
}

// =========================================================
// STATUS DROPDOWN
// =========================================================

function toggleStatusMenu(id, event) {
  if (event) {
    event.stopPropagation();
  }

  document.querySelectorAll(".status-menu").forEach((menu) => {
    if (menu.id !== `statusMenu-${id}`) {
      menu.classList.add("hidden");
    }
  });

  const menu = document.getElementById(`statusMenu-${id}`);

  if (menu) {
    menu.classList.toggle("hidden");
  }
}

// =========================================================
// CLOSE STATUS DROPDOWN
// =========================================================

document.addEventListener("click", () => {
  document.querySelectorAll(".status-menu").forEach((menu) => {
    menu.classList.add("hidden");
  });
});

// =========================================================
// SELECT GAME STATUS
// =========================================================

async function selectGameStatus(id, newStatus) {
  const menu = document.getElementById(`statusMenu-${id}`);

  if (menu) {
    menu.classList.add("hidden");
  }

  const game = allGames.find((item) => Number(item.id) === Number(id));

  if (!game) {
    alert("Game not found.");

    return;
  }

  const currentStatus = game.status === "active" ? "active" : "inactive";

  if (currentStatus === newStatus) {
    return;
  }

  const formData = new FormData();

  formData.append("id", game.id);

  formData.append("title", game.title || "");

  formData.append("category", game.category || game.categories || "Other");

  formData.append("developer", game.developer || "");

  formData.append("publisher", game.publisher || "");

  formData.append("description", game.description || "");

  formData.append("price", game.price ?? 0);

  formData.append("old_price", game.old_price ?? "");

  formData.append("discount", game.discount ?? 0);

  formData.append("image", game.image || "");

  formData.append("release_date", game.release_date || "");

  formData.append("rating", game.rating ?? 0);

  formData.append("total_reviews", game.total_reviews ?? 0);

  formData.append("status", newStatus);

  await updateGame(formData);
}

// =========================================================
// VIEW GAME DETAILS
// =========================================================

function viewGameDetails(id) {
  const game = allGames.find((item) => Number(item.id) === Number(id));

  if (!game) {
    alert("Game not found.");

    return;
  }

  detailsGameId = Number(id);

  // =====================================================
  // SET TEXT
  // =====================================================

  const setText = (elementId, value) => {
    const element = document.getElementById(elementId);

    if (element) {
      element.textContent = value;
    }
  };

  // =====================================================
  // IMAGE
  // =====================================================

  const image = document.getElementById("detailsImage");

  if (image) {
    image.src = game.image || "src/Images/games/default.png";
  }

  // =====================================================
  // STATUS
  // =====================================================

  const status = game.status === "active" ? "active" : "inactive";

  // =====================================================
  // GAME INFORMATION
  // =====================================================

  setText("detailsTitle", game.title || "Untitled Game");

  setText("detailsDeveloper", game.developer || "—");

  setText("detailsPublisher", game.publisher || "—");

  setText("detailsCategory", game.category || game.categories || "—");

  setText("detailsReleaseDate", game.release_date || "—");

  setText("detailsPrice", `$${Number(game.price || 0).toFixed(2)}`);

  setText(
    "detailsOldPrice",
    game.old_price !== null &&
      game.old_price !== undefined &&
      game.old_price !== ""
      ? `$${Number(game.old_price).toFixed(2)}`
      : "—",
  );

  setText("detailsDiscount", `${Number(game.discount || 0)}%`);

  setText("detailsRating", `${Number(game.rating || 0).toFixed(1)} / 5`);

  setText(
    "detailsDescription",
    game.description || "No description available.",
  );

  // =====================================================
  // STATUS BADGE
  // =====================================================

  const statusBadge = document.getElementById("detailsStatus");

  if (statusBadge) {
    statusBadge.textContent = status === "active" ? "Active" : "Inactive";

    statusBadge.className = `
            inline-flex
            items-center
            px-2.5
            py-1
            mt-1.5
            rounded-full
            border
            text-xs
            font-medium
            ${
              status === "active"
                ? `
                        bg-green-500/10
                        text-green-400
                        border-green-500/20
                    `
                : `
                        bg-red-500/10
                        text-red-400
                        border-red-500/20
                    `
            }
        `;
  }

  // =====================================================
  // OPEN DETAILS
  // =====================================================

  if (detailsPage) {
    detailsPage.classList.remove("hidden");

    detailsPage.classList.add("flex");
  }
}

// =========================================================
// CLOSE GAME DETAILS
// =========================================================

function closeDetailsPage() {
  if (detailsPage) {
    detailsPage.classList.add("hidden");

    detailsPage.classList.remove("flex");
  }

  detailsGameId = null;
}

// =========================================================
// EDIT FROM DETAILS
// =========================================================

if (detailsEditBtn) {
  detailsEditBtn.addEventListener("click", () => {
    if (detailsGameId === null) {
      return;
    }

    const id = detailsGameId;

    closeDetailsPage();

    editGame(id);
  });
}

// =========================================================
// DELETE GAME
// =========================================================

async function deleteGame(id) {
  const game = allGames.find((item) => Number(item.id) === Number(id));

  if (!game) {
    alert("Game not found.");

    return;
  }

  const confirmed = confirm(`Are you sure you want to delete "${game.title}"?`);

  if (!confirmed) {
    return;
  }

  const formData = new FormData();

  formData.append("id", game.id);

  try {
    const response = await fetch(API.delete, {
      method: "POST",
      body: formData,
      credentials: "same-origin",
    });

    const text = await response.text();

    console.log("Delete Game Response:", text);

    let data;

    try {
      data = JSON.parse(text);
    } catch {
      throw new Error("Invalid JSON returned from delete-game.php.");
    }

    if (!response.ok || !data.success) {
      throw new Error(data.message || "Failed to delete game.");
    }

    alert("Game deleted successfully.");

    await loadGames();
  } catch (error) {
    console.error("Delete Game Error:", error);

    alert(error.message || "Failed to delete game.");
  }
}

// =========================================================
// COVER IMAGE PREVIEW
// =========================================================

function handleCoverUpload(event) {
  const file = event.target.files?.[0];

  if (!file) {
    return;
  }

  // =====================================================
  // VALIDATE IMAGE
  // =====================================================

  if (!file.type.startsWith("image/")) {
    alert("Please select an image file.");

    event.target.value = "";

    return;
  }

  // =====================================================
  // PREVIEW ELEMENTS
  // =====================================================

  const preview = document.getElementById("coverPreview");

  const previewWrap = document.getElementById("coverPreviewWrap");

  if (!preview || !previewWrap) {
    return;
  }

  // =====================================================
  // READ IMAGE
  // =====================================================

  const reader = new FileReader();

  reader.onload = function () {
    preview.src = reader.result;

    previewWrap.classList.remove("hidden");
  };

  reader.readAsDataURL(file);
}

// =========================================================
// SUBMIT LOADING
// =========================================================

function setSubmitLoading(loading) {
  if (!submitBtn) {
    return;
  }

  submitBtn.disabled = loading;

  submitBtn.classList.toggle("opacity-60", loading);

  submitBtn.classList.toggle("cursor-not-allowed", loading);

  if (loading) {
    submitBtn.textContent = "Saving...";
  } else {
    submitBtn.textContent = editingGameId ? "Update Game" : "Add Game";
  }
}

// =========================================================
// SHOW ERROR
// =========================================================

function showError(id) {
  const element = document.getElementById(id);

  if (!element) {
    return;
  }

  element.classList.remove("hidden");
}

// =========================================================
// CLEAR ERRORS
// =========================================================

function clearErrors() {
  const errors = ["gameNameError", "gamePriceError"];

  errors.forEach((id) => {
    const element = document.getElementById(id);

    if (element) {
      element.classList.add("hidden");
    }
  });
}

// =========================================================
// ESCAPE HTML
// =========================================================

function escapeHTML(value) {
  return String(value ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

// =========================================================
// FORM SUBMIT
// =========================================================

if (gameForm) {
  gameForm.addEventListener("submit", saveGame);
}

// =========================================================
// DOM READY
// =========================================================

document.addEventListener("DOMContentLoaded", () => {
  loadGames();
});
