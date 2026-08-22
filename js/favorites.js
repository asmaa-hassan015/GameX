
// =========================================================
// 1. API CONFIGURATION
// =========================================================

const WISHLIST_API = "/GameX/BACKEND/";

// =========================================================
// 2. GET USER WISHLIST
// =========================================================

async function getFavorites() {
  try {
    const response = await fetch(WISHLIST_API + "wishlist.php", {
      method: "GET",
      credentials: "include",
      headers: {
        Accept: "application/json",
      },
    });

    const data = await response.json();

    // User not logged in
    if (response.status === 401) {
      console.warn("User is not logged in.");
      return [];
    }

    // API error
    if (!response.ok) {
      console.error("Wishlist API Error:", data);
      return [];
    }

    // PHP returns array directly
    if (Array.isArray(data)) {
      return data;
    }

    // PHP returns:
    // {
    //     success: true,
    //     data: [...]
    // }

    if (data && data.success === true && Array.isArray(data.data)) {
      return data.data;
    }

    return [];
  } catch (error) {
    console.error("Get wishlist error:", error);
    return [];
  }
}

// =========================================================
// 3. UPDATE NAVBAR WISHLIST BADGE
// =========================================================

async function updateWishlistBadge() {
  const badge = document.getElementById("wishlist-badge");

  if (!badge) {
    return;
  }

  const favorites = await getFavorites();

  badge.textContent = favorites.length;

  badge.classList.toggle("hidden", favorites.length === 0);
}

// =========================================================
// 4. UPDATE HEART UI
// =========================================================

function updateHeartUI(button, active) {
  if (!button) {
    return;
  }

  const icon = button.querySelector("i");

  // Active favorite
  if (active) {
    button.classList.remove("bg-red-600", "hover:bg-red-400");

    button.classList.add("bg-[#A726DD]", "hover:bg-[#8e1fc0]");

    if (icon) {
      icon.classList.remove("fa-regular");
      icon.classList.add("fa-solid");
    }
  }

  // Not favorite
  else {
    button.classList.remove("bg-[#A726DD]", "hover:bg-[#8e1fc0]");

    button.classList.add("bg-red-600", "hover:bg-red-400");

    if (icon) {
      icon.classList.remove("fa-solid");
      icon.classList.add("fa-regular");
    }
  }
}

// =========================================================
// 5. GET GAME ID FROM FAVORITE BUTTON
// =========================================================

function getGameIdFromFavoriteButton(button) {
  if (!button) {
    return null;
  }

  // HTML:
  // data-game-id="1"

  const gameId = parseInt(button.dataset.gameId, 10);

  if (!gameId) {
    console.error("Favorite button does not have valid data-game-id:", button);

    return null;
  }

  return gameId;
}

// =========================================================
// 6. TOGGLE FAVORITE
// =========================================================

async function toggleFavorite(button) {
  if (!button) {
    return;
  }

  const gameId = getGameIdFromFavoriteButton(button);

  if (!gameId) {
    return;
  }

  try {
    const response = await fetch(WISHLIST_API + "wishlist.php", {
      method: "POST",
      credentials: "include",

      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },

      body: JSON.stringify({
        action: "toggle",
        id: gameId,
      }),
    });

    const result = await response.json();

    // =================================================
    // LOGIN REQUIRED
    // =================================================

    if (response.status === 401) {
      alert("Please login first.");
      return;
    }

    // =================================================
    // API ERROR
    // =================================================

    if (!response.ok) {
      console.error("Toggle wishlist error:", result);

      alert(result.message || "Could not update wishlist.");

      return;
    }

    // =================================================
    // UPDATE HEART
    // =================================================

    const status = result.data?.status || result.status;

    updateHeartUI(button, status === "added");

    // =================================================
    // UPDATE BADGE
    // =================================================

    await updateWishlistBadge();
  } catch (error) {
    console.error("Toggle favorite error:", error);
  }
}

// =========================================================
// 7. INITIALIZE FAVORITE BUTTONS
// =========================================================

async function initFavoriteButtons() {
  const favoriteButtons = document.querySelectorAll(".favorite-btn");

  // =================================================
  // UPDATE NAVBAR BADGE
  // =================================================

  await updateWishlistBadge();

  if (favoriteButtons.length === 0) {
    return;
  }

  // =================================================
  // GET CURRENT FAVORITES
  // =================================================

  const favorites = await getFavorites();

  /*
        Wishlist response should contain:

        {
            id: 1,
            title: "...",
            ...
        }
    */

  const favoriteIds = favorites.map((item) => String(item.id));

  // =================================================
  // INITIALIZE EACH HEART BUTTON
  // =================================================

  favoriteButtons.forEach((button) => {
    const gameId = String(button.dataset.gameId || "");

    if (!gameId) {
      console.error("Favorite button does not have data-game-id:", button);

      return;
    }

    // =================================================
    // INITIAL HEART STATE
    // =================================================

    updateHeartUI(button, favoriteIds.includes(gameId));

    // =================================================
    // CLICK EVENT
    // =================================================

    button.addEventListener("click", async function (event) {
      event.preventDefault();
      event.stopPropagation();

      await toggleFavorite(button);
    });
  });
}

// =========================================================
// 8. ADD ITEM TO CART
// =========================================================

async function addItemToCart(item) {
  try {
    const gameId = parseInt(item.id, 10);

    if (!gameId) {
      console.error("Invalid game ID:", item.id);

      return false;
    }

    const response = await fetch(WISHLIST_API + "cart.php", {
      method: "POST",
      credentials: "include",

      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },

      body: JSON.stringify({
        action: "add",
        id: gameId,
      }),
    });

    const result = await response.json();

    // =================================================
    // LOGIN REQUIRED
    // =================================================

    if (response.status === 401) {
      alert("Please login first.");
      return false;
    }

    // =================================================
    // API ERROR
    // =================================================

    if (!response.ok) {
      console.error("Add cart error:", result);

      return false;
    }

    return result.success === true;
  } catch (error) {
    console.error("Add to cart error:", error);

    return false;
  }
}

// =========================================================
// 9. REMOVE FROM WISHLIST
// =========================================================

async function removeFromWishlist(gameId) {
  try {
    const id = parseInt(gameId, 10);

    if (!id) {
      console.error("Invalid wishlist game ID:", gameId);

      return false;
    }

    const response = await fetch(WISHLIST_API + "wishlist.php", {
      method: "POST",
      credentials: "include",

      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },

      body: JSON.stringify({
        action: "remove",
        id: id,
      }),
    });

    const result = await response.json();

    // =================================================
    // LOGIN REQUIRED
    // =================================================

    if (response.status === 401) {
      alert("Please login first.");
      return false;
    }

    // =================================================
    // API ERROR
    // =================================================

    if (!response.ok) {
      console.error("Remove wishlist error:", result);

      return false;
    }

    return result.success === true;
  } catch (error) {
    console.error("Remove wishlist error:", error);

    return false;
  }
}

// =========================================================
// 10. RENDER WISHLIST ROW
// =========================================================

function renderWishlistRow(item) {
  const row = document.createElement("div");

  // =====================================================
  // ROW STRUCTURE
  // =====================================================

  row.className =
    "flex flex-col sm:flex-row " +
    "sm:items-center justify-between " +
    "gap-3 bg-[#0f0d1a] " +
    "border border-[#26223D] " +
    "rounded-xl p-3";

  row.dataset.id = item.id;

  // =====================================================
  // PRICE
  // =====================================================

  let priceHTML = `
        <span class="font-bold text-lg">
            $${Number(item.price || 0).toFixed(2)}
        </span>
    `;

  // =====================================================
  // OLD PRICE / DISCOUNT
  // =====================================================

  if (item.oldPrice !== null && item.oldPrice !== "" && item.oldPrice !== "0") {
    priceHTML += `
            ${
              item.discount
                ? `
                        <span
                            class="bg-red-600/90
                                   text-xs
                                   font-bold
                                   px-2
                                   py-0.5
                                   rounded-md
                                   mx-1">

                            ${item.discount}

                        </span>
                    `
                : ""
            }

            <span
                class="text-gray-500
                       line-through
                       text-sm">

                $${item.oldPrice}

            </span>
        `;
  }

  // =====================================================
  // ROW HTML
  // =====================================================

  row.innerHTML = `

        <!-- PRODUCT INFO -->

        <div class="flex items-center gap-3">

            <img
                src="${item.img || ""}"
                class="w-16 h-16
                       object-cover
                       rounded-lg"
                alt="${item.title || "Game"}"
            >

            <div>

                <h3 class="font-semibold">
                    ${item.title || "Unknown Game"}
                </h3>

                <div class="mt-1">
                    ${priceHTML}
                </div>

                ${
                  item.publisher
                    ? `
                            <p
                                class="text-gray-500
                                       text-xs
                                       mt-1">

                                ${item.publisher}

                            </p>
                        `
                    : ""
                }

            </div>

        </div>


        <!-- PRODUCT ACTIONS -->

        <div
            class="flex items-center
                   gap-3
                   self-end
                   sm:self-auto">

            <!-- ADD TO CART -->

            <button
                class="wishlist-add-cart-btn
                       bg-[#5207A1]
                       hover:bg-[#6d12c7]
                       duration-300
                       px-5 py-2
                       rounded-lg
                       text-sm
                       font-semibold">

                Add to Cart

            </button>


            <!-- REMOVE -->

            <button
                class="wishlist-remove-btn
                       text-gray-400
                       hover:text-red-500
                       duration-300
                       w-9 h-9
                       flex items-center
                       justify-center">

                <i class="fa-solid fa-trash"></i>

            </button>

        </div>

    `;

  // =====================================================
  // 11. ADD TO CART BUTTON
  // =====================================================

  const cartButton = row.querySelector(".wishlist-add-cart-btn");

  cartButton.addEventListener("click", async function () {
    const success = await addItemToCart(item);

    if (success) {
      cartButton.innerHTML = `<i class="fa-solid fa-check"></i>`;

      // Update cart badge
      if (typeof updateCartBadge === "function") {
        await updateCartBadge();
      }

      setTimeout(() => {
        cartButton.innerHTML = "Add to Cart";
      }, 900);
    } else {
      alert("Could not add game to cart.");
    }
  });

  // =====================================================
  // 12. REMOVE BUTTON
  // =====================================================

  const removeButton = row.querySelector(".wishlist-remove-btn");

  removeButton.addEventListener("click", async function () {
    const success = await removeFromWishlist(item.id);

    if (success) {
      row.remove();

      updateWishlistCount();

      await updateWishlistBadge();
    }
  });

  return row;
}

// =========================================================
// 13. UPDATE WISHLIST COUNT
// =========================================================

function updateWishlistCount() {
  const countElement = document.getElementById("wishlist-count");

  const container = document.getElementById("wishlist-container");

  if (!countElement || !container) {
    return;
  }

  // =====================================================
  // COUNT ITEMS
  // =====================================================

  const rows = container.querySelectorAll("[data-id]");

  const count = rows.length;

  countElement.textContent = count;

  // =====================================================
  // EMPTY WISHLIST
  // =====================================================

  if (count === 0) {
    container.innerHTML = `

            <p
                class="text-gray-400
                       text-center
                       py-10">

                Your wishlist is empty.

            </p>

        `;
  }
}

// =========================================================
// 14. RENDER WISHLIST PAGE
// =========================================================

async function renderWishlistPage() {
  const container = document.getElementById("wishlist-container");

  if (!container) {
    return;
  }

  // =====================================================
  // LOADING
  // =====================================================

  container.innerHTML = `

        <p
            class="text-gray-400
                   text-center
                   py-10">

            Loading...

        </p>

    `;

  // =====================================================
  // GET WISHLIST
  // =====================================================

  const favorites = await getFavorites();

  container.innerHTML = "";

  // =====================================================
  // UPDATE COUNT
  // =====================================================

  const countElement = document.getElementById("wishlist-count");

  if (countElement) {
    countElement.textContent = favorites.length;
  }

  // =====================================================
  // EMPTY WISHLIST
  // =====================================================

  if (favorites.length === 0) {
    container.innerHTML = `

            <p
                class="text-gray-400
                       text-center
                       py-10">

                Your wishlist is empty.

            </p>

        `;

    return;
  }

  // =====================================================
  // RENDER ITEMS
  // =====================================================

  favorites.forEach((item) => {
    container.appendChild(renderWishlistRow(item));
  });

  // =====================================================
  // MOVE ALL TO CART
  // =====================================================

  const moveAllButton = document.getElementById("move-all-cart");

  if (!moveAllButton) {
    return;
  }

  moveAllButton.onclick = async function (event) {
    event.preventDefault();

    // Get current wishlist
    const currentFavorites = await getFavorites();

    // Add all items to cart
    for (const item of currentFavorites) {
      const added = await addItemToCart(item);

      // Remove from wishlist
      if (added) {
        await removeFromWishlist(item.id);
      }
    }

    // Refresh wishlist
    await renderWishlistPage();

    // Update wishlist badge
    await updateWishlistBadge();

    // Update cart badge
    if (typeof updateCartBadge === "function") {
      await updateCartBadge();
    }
  };
}

// =========================================================
// 15. PAGE INITIALIZATION
// =========================================================

document.addEventListener("DOMContentLoaded", async function () {
  // Initialize favorite buttons
  await initFavoriteButtons();

  // Render wishlist page
  await renderWishlistPage();
});

// =========================================================
// END GAME X - FAVORITES / WISHLIST
// =========================================================
