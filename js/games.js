
document.addEventListener("DOMContentLoaded", function () {
  // =========================================================
  // API
  // =========================================================

  const API_URL = "./BACKEND/games.php";

  // =========================================================
  // ELEMENTS
  // =========================================================

  const gamesGrid = document.getElementById("gamesGrid");
  const categoryButtonsContainer = document.getElementById("categoryButtons");
  const genreFiltersContainer = document.getElementById("genreFilters");
  const platformFiltersContainer = document.getElementById("platformFilters");
  const ratingFilters = document.querySelectorAll(".rating-filter");
  const priceRange = document.getElementById("priceRange");
  const priceValue = document.getElementById("priceValue");
  const clearFilters = document.getElementById("clearFilters");
  const sortSelect = document.getElementById("sortSelect");
  const pageNumbers = document.getElementById("pageNumbers");
  const prevPage = document.getElementById("prevPage");
  const nextPage = document.getElementById("nextPage");
  const loadingState = document.getElementById("loadingState");
  const emptyState = document.getElementById("emptyState");
  const pagination = document.getElementById("pagination");

  // =========================================================
  // VARIABLES
  // =========================================================

  let games = [];
  let categories = [];
  let platforms = [];
  let selectedCategory = "all";
  let currentPage = 1;

  const itemsPerPage = 8;

  let maxDatabasePrice = 100;

  // =========================================================
  // ESCAPE HTML
  // =========================================================

  function escapeHtml(value) {
    const div = document.createElement("div");

    div.textContent = value ?? "";

    return div.innerHTML;
  }

  // =========================================================
  // LOAD GAMES
  // =========================================================

  async function loadGames() {
    try {
      loadingState.classList.remove("hidden");
      emptyState.classList.add("hidden");
      gamesGrid.innerHTML = "";

      const response = await fetch(API_URL);

      if (!response.ok) {
        throw new Error("Failed to load games.");
      }

      const data = await response.json();

      if (!data.success) {
        throw new Error(data.message || "Failed to load games.");
      }

      games = Array.isArray(data.games) ? data.games : [];

      categories = Array.isArray(data.categories) ? data.categories : [];

      platforms = Array.isArray(data.platforms) ? data.platforms : [];

      // =====================================================
      // INITIAL PRICE
      // =====================================================

      const prices = games.map((game) => Number(game.price || 0));

      maxDatabasePrice = prices.length ? Math.ceil(Math.max(...prices)) : 100;

      maxDatabasePrice = Math.max(maxDatabasePrice, 10);

      priceRange.max = maxDatabasePrice;
      priceRange.value = maxDatabasePrice;
      priceValue.textContent = "$" + maxDatabasePrice;

      // =====================================================
      // RENDER FILTERS
      // =====================================================

      renderCategoryButtons();
      renderGenreFilters();
      renderPlatformFilters();

      // =====================================================
      // RENDER GAMES
      // =====================================================

      applyFilters();
    } catch (error) {
      console.error("Games API Error:", error);

      loadingState.classList.add("hidden");

      gamesGrid.innerHTML = `
                <div class="col-span-full">
                    <div class="border border-red-500/30 bg-[#0f0d1a] rounded-xl p-10 text-center">

                        <i class="fa-solid fa-triangle-exclamation text-red-500 text-5xl mb-4"></i>

                        <h3 class="text-2xl font-bold mb-2">
                            Failed to Load Games
                        </h3>

                        <p class="text-gray-400">
                            Please try again later.
                        </p>

                    </div>
                </div>
            `;

      pagination.classList.add("hidden");
    }
  }

  // =========================================================
  // CATEGORY BUTTONS
  // =========================================================

  function renderCategoryButtons() {
    categoryButtonsContainer.innerHTML = `
            <button
                type="button"
                class="category-btn active-category bg-[#A726DD] text-white px-5 py-2 rounded-full font-semibold shadow-lg text-sm cursor-pointer transition"
                data-category="all"
            >
                All Games
            </button>
        `;

    categories.forEach((category) => {
      const button = document.createElement("button");

      button.type = "button";

      button.className =
        "category-btn bg-[#15121f] hover:bg-[#A726DD] duration-300 text-white px-5 py-2 rounded-full text-sm cursor-pointer";

      button.dataset.category = category.name;

      button.textContent = category.name;

      categoryButtonsContainer.appendChild(button);
    });

    attachCategoryEvents();
  }

  // =========================================================
  // CATEGORY EVENTS
  // =========================================================

  function attachCategoryEvents() {
    const categoryButtons = document.querySelectorAll(".category-btn");

    categoryButtons.forEach((button) => {
      button.addEventListener("click", function () {
        categoryButtons.forEach((btn) => {
          btn.classList.remove("bg-[#A726DD]", "font-semibold", "shadow-lg");

          btn.classList.add("bg-[#15121f]");
        });

        this.classList.remove("bg-[#15121f]");

        this.classList.add("bg-[#A726DD]", "font-semibold", "shadow-lg");

        selectedCategory = this.dataset.category || "all";

        currentPage = 1;

        applyFilters();
      });
    });
  }

  // =========================================================
  // GENRE FILTERS
  // =========================================================

  function renderGenreFilters() {
    genreFiltersContainer.innerHTML = "";

    categories.forEach((category) => {
      const label = document.createElement("label");

      label.className = "flex items-center gap-2 cursor-pointer";

      label.innerHTML = `
                <input
                    type="checkbox"
                    class="genre-filter accent-[#A726DD] rounded"
                    value="${escapeHtml(category.name)}"
                >

                ${escapeHtml(category.name)}
            `;

      genreFiltersContainer.appendChild(label);
    });

    attachGenreEvents();
  }

  // =========================================================
  // GENRE EVENTS
  // =========================================================

  function attachGenreEvents() {
    const genreFilters = document.querySelectorAll(".genre-filter");

    genreFilters.forEach((input) => {
      input.addEventListener("change", function () {
        currentPage = 1;

        applyFilters();
      });
    });
  }

  // =========================================================
  // PLATFORM FILTERS
  // =========================================================

  function renderPlatformFilters() {
    platformFiltersContainer.innerHTML = "";

    platforms.forEach((platform) => {
      const label = document.createElement("label");

      label.className = "flex items-center gap-2 cursor-pointer";

      label.innerHTML = `
                <input
                    type="checkbox"
                    class="platform-filter accent-[#A726DD] rounded"
                    value="${escapeHtml(platform.name)}"
                >

                ${escapeHtml(platform.name)}
            `;

      platformFiltersContainer.appendChild(label);
    });

    attachPlatformEvents();
  }

  // =========================================================
  // PLATFORM EVENTS
  // =========================================================

  function attachPlatformEvents() {
    const platformFilters = document.querySelectorAll(".platform-filter");

    platformFilters.forEach((input) => {
      input.addEventListener("change", function () {
        currentPage = 1;

        applyFilters();
      });
    });
  }

  // =========================================================
  // APPLY FILTERS
  // =========================================================

  function applyFilters() {
    let filteredGames = games.filter((game) => {
      const price = Number(game.price || 0);

      const rating = Number(game.rating || 0);

      const gameCategories = (game.categories || "")
        .toLowerCase()
        .split(",")
        .map((item) => item.trim())
        .filter(Boolean);

      const gamePlatforms = (game.platforms || "")
        .toLowerCase()
        .split(",")
        .map((item) => item.trim())
        .filter(Boolean);

      // =====================================================
      // CATEGORY
      // =====================================================

      if (
        selectedCategory !== "all" &&
        !gameCategories.includes(selectedCategory.toLowerCase())
      ) {
        return false;
      }

      // =====================================================
      // PRICE
      // =====================================================

      if (price > Number(priceRange.value)) {
        return false;
      }

      // =====================================================
      // GENRES
      // =====================================================

      const selectedGenres = Array.from(
        document.querySelectorAll(".genre-filter:checked"),
      ).map((input) => input.value.toLowerCase());

      if (selectedGenres.length > 0) {
        const genreMatch = selectedGenres.some((genre) =>
          gameCategories.includes(genre),
        );

        if (!genreMatch) {
          return false;
        }
      }

      // =====================================================
      // PLATFORMS
      // =====================================================

      const selectedPlatforms = Array.from(
        document.querySelectorAll(".platform-filter:checked"),
      ).map((input) => input.value.toLowerCase());

      if (selectedPlatforms.length > 0) {
        const platformMatch = selectedPlatforms.some((platform) =>
          gamePlatforms.includes(platform),
        );

        if (!platformMatch) {
          return false;
        }
      }

      // =====================================================
      // RATINGS
      // =====================================================

      const selectedRatings = Array.from(
        document.querySelectorAll(".rating-filter:checked"),
      ).map((input) => Number(input.value));

      if (selectedRatings.length > 0) {
        const ratingMatch = selectedRatings.some(
          (minRating) => rating >= minRating,
        );

        if (!ratingMatch) {
          return false;
        }
      }

      return true;
    });

    // =========================================================
    // SORT
    // =========================================================

    const sortValue = sortSelect.value;

    filteredGames.sort((a, b) => {
      const priceA = Number(a.price || 0);

      const priceB = Number(b.price || 0);

      const ratingA = Number(a.rating || 0);

      const ratingB = Number(b.rating || 0);

      const reviewsA = Number(a.total_reviews || 0);

      const reviewsB = Number(b.total_reviews || 0);

      if (sortValue === "price-low") {
        return priceA - priceB;
      }

      if (sortValue === "price-high") {
        return priceB - priceA;
      }

      if (sortValue === "rating") {
        return ratingB - ratingA;
      }

      if (sortValue === "newest") {
        return new Date(b.created_at) - new Date(a.created_at);
      }

      return ratingB * 100 + reviewsB - (ratingA * 100 + reviewsA);
    });

    // =========================================================
    // PAGINATION
    // =========================================================

    const totalPages = Math.max(
      1,
      Math.ceil(filteredGames.length / itemsPerPage),
    );

    if (currentPage > totalPages) {
      currentPage = totalPages;
    }

    const start = (currentPage - 1) * itemsPerPage;

    const end = start + itemsPerPage;

    const pageGames = filteredGames.slice(start, end);

    // =========================================================
    // RENDER
    // =========================================================

    renderGames(pageGames);

    renderPagination(totalPages, filteredGames.length);
  }

  // =========================================================
  // RENDER GAMES
  // =========================================================

  function renderGames(pageGames) {
    loadingState.classList.add("hidden");

    gamesGrid.innerHTML = "";

    if (pageGames.length === 0) {
      emptyState.classList.remove("hidden");

      pagination.classList.add("hidden");

      return;
    }

    emptyState.classList.add("hidden");

    pagination.classList.remove("hidden");

    pageGames.forEach((game) => {
      const card = createGameCard(game);

      gamesGrid.appendChild(card);
    });

    if (typeof initAddToCartButtons === "function") {
      initAddToCartButtons();
    }

    if (typeof initFavoriteButtons === "function") {
      initFavoriteButtons();
    }
  }

  // =========================================================
  // CREATE GAME CARD
  // =========================================================

  function createGameCard(game) {
    const card = document.createElement("div");

    const gameId = Number(game.id);

    const price = Number(game.price || 0);

    const oldPrice =
      game.old_price !== null && game.old_price !== undefined
        ? Number(game.old_price)
        : null;

    const discount = Number(game.discount || 0);

    const rating = Number(game.rating || 0);

    const image = game.image || "src/Images/gamepic.png";

    const title = game.title || "";

    const categoriesString = game.categories || "";

    const platformsString = game.platforms || "";

    card.className =
      "game-card relative border border-[#26223D] rounded-xl overflow-hidden hover:scale-[1.02] duration-300 group bg-[#0f0d1a]";

    card.dataset.gameId = gameId;
    card.dataset.gameName = title;
    card.dataset.gamePrice = price;
    card.dataset.gameOldPrice = oldPrice ?? "";
    card.dataset.gameDiscount = discount;
    card.dataset.gameRating = rating;
    card.dataset.gameImage = image;
    card.dataset.gameCategories = categoriesString;
    card.dataset.gamePlatforms = platformsString;

    card.innerHTML = `
            <!-- ================================================= -->
            <!-- GAME DETAILS -->
            <!-- ================================================= -->

            <a
                href="game-details.php?id=${gameId}"
                class="block"
            >

                <!-- ================================================= -->
                <!-- DISCOUNT -->
                <!-- ================================================= -->

                ${
                  discount > 0
                    ? `
                            <span
                                class="absolute top-2 left-2 bg-[#A726DD] text-white text-xs font-bold px-2 py-1 rounded-md z-10 shadow-md"
                            >
                                -${formatNumber(discount)}%
                            </span>
                        `
                    : ""
                }

                <!-- ================================================= -->
                <!-- IMAGE -->
                <!-- ================================================= -->

                <img
                    src="${escapeHtml(image)}"
                    class="w-full h-44 object-cover group-hover:opacity-90 transition"
                    alt="${escapeHtml(title)}"
                    onerror="this.src='src/Images/gamepic.png'"
                >

                <!-- ================================================= -->
                <!-- GAME INFO -->
                <!-- ================================================= -->

                <div class="p-3">

                    <h3
                        class="font-bold text-lg group-hover:text-[#A726DD] duration-300 truncate"
                        title="${escapeHtml(title)}"
                    >
                        ${escapeHtml(title)}
                    </h3>

                    <!-- ================================================= -->
                    <!-- RATING -->
                    <!-- ================================================= -->

                    <div
                        class="flex items-center gap-1 text-sm text-yellow-400 mt-1"
                    >

                        <i class="fa-solid fa-star"></i>

                        <span class="text-gray-300 font-semibold">
                            ${rating.toFixed(1)}
                        </span>

                    </div>

                    <!-- ================================================= -->
                    <!-- PRICE -->
                    <!-- ================================================= -->

                    <div
                        class="flex justify-between items-center mt-3"
                    >

                        <div>

                            <span class="font-bold text-lg">
                                $${price.toFixed(2)}
                            </span>

                            ${
                              oldPrice !== null && oldPrice > price
                                ? `
                                        <span class="text-gray-500 line-through text-sm ml-1">
                                            $${oldPrice.toFixed(2)}
                                        </span>
                                    `
                                : ""
                            }

                        </div>

                    </div>

                </div>

            </a>

            <!-- ================================================= -->
            <!-- ACTION BUTTONS -->
            <!-- ================================================= -->

            <div
                class="p-3 pt-0 flex justify-end gap-2"
            >

                <!-- ================================================= -->
                <!-- CART -->
                <!-- ================================================= -->

                <button
                    type="button"
                    class="add-cart-btn bg-[#5207A1] hover:bg-[#A726DD] duration-300 w-9 h-9 rounded-lg flex items-center justify-center cursor-pointer shadow-md"
                    data-game-id="${gameId}"
                    data-game-name="${escapeHtml(title)}"
                    data-game-price="${price}"
                    data-game-image="${escapeHtml(image)}"
                >

                    <i class="fa-solid fa-cart-shopping text-sm"></i>

                </button>

                <!-- ================================================= -->
                <!-- WISHLIST -->
                <!-- ================================================= -->

                <button
                    type="button"
                    class="favorite-btn bg-[#15121f] hover:bg-red-600 border border-[#26223D] duration-300 w-9 h-9 rounded-lg flex items-center justify-center cursor-pointer"
                    data-game-id="${gameId}"
                    data-game-name="${escapeHtml(title)}"
                    data-game-price="${price}"
                    data-game-image="${escapeHtml(image)}"
                >

                    <i class="fa-regular fa-heart text-sm"></i>

                </button>

            </div>
        `;

    return card;
  }

  // =========================================================
  // FORMAT NUMBER
  // =========================================================

  function formatNumber(value) {
    return Number(value)
      .toFixed(2)
      .replace(/\.00$/, "")
      .replace(/(\.\d)0$/, "$1");
  }

  // =========================================================
  // PAGINATION
  // =========================================================

  function renderPagination(totalPages, totalItems) {
    pageNumbers.innerHTML = "";

    if (totalItems === 0 || totalPages <= 1) {
      prevPage.disabled = true;
      nextPage.disabled = true;

      prevPage.classList.add("opacity-40");

      nextPage.classList.add("opacity-40");

      return;
    }

    prevPage.disabled = currentPage === 1;

    nextPage.disabled = currentPage === totalPages;

    prevPage.classList.toggle("opacity-40", currentPage === 1);

    nextPage.classList.toggle("opacity-40", currentPage === totalPages);

    for (let page = 1; page <= totalPages; page++) {
      const button = document.createElement("button");

      button.type = "button";

      button.textContent = page;

      button.className =
        "w-10 h-10 rounded-lg border duration-300 cursor-pointer";

      if (page === currentPage) {
        button.classList.add(
          "bg-[#A726DD]",
          "text-white",
          "font-semibold",
          "border-[#A726DD]",
        );
      } else {
        button.classList.add(
          "bg-[#15121f]",
          "hover:bg-[#221c33]",
          "border-[#26223D]",
        );
      }

      button.addEventListener("click", function () {
        currentPage = page;

        applyFilters();

        window.scrollTo({
          top: 0,
          behavior: "smooth",
        });
      });

      pageNumbers.appendChild(button);
    }
  }

  // =========================================================
  // PREVIOUS PAGE
  // =========================================================

  prevPage.addEventListener("click", function () {
    if (currentPage > 1) {
      currentPage--;

      applyFilters();
    }
  });

  // =========================================================
  // NEXT PAGE
  // =========================================================

  nextPage.addEventListener("click", function () {
    const totalFilteredPages = Math.ceil(
      getFilteredGamesCount() / itemsPerPage,
    );

    if (currentPage < totalFilteredPages) {
      currentPage++;

      applyFilters();
    }
  });

  // =========================================================
  // GET FILTERED GAMES COUNT
  // =========================================================

  function getFilteredGamesCount() {
    const filteredGames = games.filter((game) => {
      const price = Number(game.price || 0);

      const rating = Number(game.rating || 0);

      const gameCategories = (game.categories || "")
        .toLowerCase()
        .split(",")
        .map((item) => item.trim())
        .filter(Boolean);

      const gamePlatforms = (game.platforms || "")
        .toLowerCase()
        .split(",")
        .map((item) => item.trim())
        .filter(Boolean);

      if (
        selectedCategory !== "all" &&
        !gameCategories.includes(selectedCategory.toLowerCase())
      ) {
        return false;
      }

      if (price > Number(priceRange.value)) {
        return false;
      }

      const selectedGenres = Array.from(
        document.querySelectorAll(".genre-filter:checked"),
      ).map((input) => input.value.toLowerCase());

      if (
        selectedGenres.length > 0 &&
        !selectedGenres.some((genre) => gameCategories.includes(genre))
      ) {
        return false;
      }

      const selectedPlatforms = Array.from(
        document.querySelectorAll(".platform-filter:checked"),
      ).map((input) => input.value.toLowerCase());

      if (
        selectedPlatforms.length > 0 &&
        !selectedPlatforms.some((platform) => gamePlatforms.includes(platform))
      ) {
        return false;
      }

      const selectedRatings = Array.from(
        document.querySelectorAll(".rating-filter:checked"),
      ).map((input) => Number(input.value));

      if (
        selectedRatings.length > 0 &&
        !selectedRatings.some((minRating) => rating >= minRating)
      ) {
        return false;
      }

      return true;
    });

    return filteredGames.length;
  }

  // =========================================================
  // PRICE EVENT
  // =========================================================

  priceRange.addEventListener("input", function () {
    priceValue.textContent = "$" + this.value;

    currentPage = 1;

    applyFilters();
  });

  // =========================================================
  // RATING EVENTS
  // =========================================================

  ratingFilters.forEach((input) => {
    input.addEventListener("change", function () {
      currentPage = 1;

      applyFilters();
    });
  });

  // =========================================================
  // SORT EVENT
  // =========================================================

  sortSelect.addEventListener("change", function () {
    currentPage = 1;

    applyFilters();
  });

  // =========================================================
  // CLEAR FILTERS
  // =========================================================

  clearFilters.addEventListener("click", function () {
    // =====================================================
    // RESET CATEGORY
    // =====================================================

    selectedCategory = "all";

    const categoryButtons = document.querySelectorAll(".category-btn");

    categoryButtons.forEach((button, index) => {
      button.classList.remove("bg-[#A726DD]", "font-semibold", "shadow-lg");

      button.classList.add("bg-[#15121f]");

      if (index === 0) {
        button.classList.remove("bg-[#15121f]");

        button.classList.add("bg-[#A726DD]", "font-semibold", "shadow-lg");
      }
    });

    // =====================================================
    // RESET GENRES
    // =====================================================

    document
      .querySelectorAll(".genre-filter")
      .forEach((input) => (input.checked = false));

    // =====================================================
    // RESET PLATFORMS
    // =====================================================

    document
      .querySelectorAll(".platform-filter")
      .forEach((input) => (input.checked = false));

    // =====================================================
    // RESET RATINGS
    // =====================================================

    ratingFilters.forEach((input) => (input.checked = false));

    // =====================================================
    // RESET PRICE
    // =====================================================

    priceRange.value = maxDatabasePrice;

    priceValue.textContent = "$" + maxDatabasePrice;

    // =====================================================
    // RESET SORT
    // =====================================================

    sortSelect.value = "popularity";

    // =====================================================
    // RESET PAGE
    // =====================================================

    currentPage = 1;

    applyFilters();
  });

  // =========================================================
  // INITIAL LOAD
  // =========================================================

  loadGames();
});
