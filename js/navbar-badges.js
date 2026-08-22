
// =========================================================
// API
// =========================================================

const NAVBAR_API = "/GameX/BACKEND/";

// =========================================================
// UPDATE CART BADGE
// =========================================================

async function updateCartBadge() {
  const badge = document.getElementById("cart-badge");

  if (!badge) {
    console.warn("Cart badge not found.");
    return;
  }

  try {
    const response = await fetch(NAVBAR_API + "cart.php", {
      method: "GET",
      credentials: "include",
      headers: {
        Accept: "application/json",
      },
    });

    const data = await response.json();

    console.log("Cart badge response:", data);

    // ---------------------------------------------------------
    // HANDLE HTTP ERROR
    // ---------------------------------------------------------

    if (!response.ok) {
      badge.textContent = "0";
      badge.classList.add("hidden");
      return;
    }

    // ---------------------------------------------------------
    // GET CART DATA
    // ---------------------------------------------------------

    let cart = [];

    // { success: true, data: [...] }
    if (data && data.success === true && Array.isArray(data.data)) {
      cart = data.data;
    }

    // { items: [...] }
    else if (data && Array.isArray(data.items)) {
      cart = data.items;
    }

    // Array directly
    else if (Array.isArray(data)) {
      cart = data;
    }

    // ---------------------------------------------------------
    // CALCULATE TOTAL QUANTITY
    // ---------------------------------------------------------

    const totalQuantity = cart.reduce((total, item) => {
      return total + Number(item.quantity || 0);
    }, 0);

    // ---------------------------------------------------------
    // UPDATE BADGE
    // ---------------------------------------------------------

    badge.textContent = totalQuantity;

    if (totalQuantity > 0) {
      badge.classList.remove("hidden");
    } else {
      badge.classList.add("hidden");
    }
  } catch (error) {
    console.error("Update cart badge error:", error);
  }
}

// =========================================================
// UPDATE WISHLIST BADGE
// =========================================================

async function updateWishlistBadge() {
  const badge = document.getElementById("wishlist-badge");

  if (!badge) {
    console.warn("Wishlist badge not found.");
    return;
  }

  try {
    const response = await fetch(NAVBAR_API + "wishlist.php", {
      method: "GET",
      credentials: "include",
      headers: {
        Accept: "application/json",
      },
    });

    const data = await response.json();

    console.log("Wishlist badge response:", data);

    // ---------------------------------------------------------
    // HANDLE HTTP ERROR
    // ---------------------------------------------------------

    if (!response.ok) {
      badge.textContent = "0";
      badge.classList.add("hidden");
      return;
    }

    // ---------------------------------------------------------
    // GET WISHLIST DATA
    // ---------------------------------------------------------

    let wishlist = [];

    // { success: true, data: [...] }
    if (data && data.success === true && Array.isArray(data.data)) {
      wishlist = data.data;
    }

    // { items: [...] }
    else if (data && Array.isArray(data.items)) {
      wishlist = data.items;
    }

    // Array directly
    else if (Array.isArray(data)) {
      wishlist = data;
    }

    // ---------------------------------------------------------
    // CALCULATE WISHLIST COUNT
    // ---------------------------------------------------------

    const totalWishlist = wishlist.length;

    // ---------------------------------------------------------
    // UPDATE BADGE
    // ---------------------------------------------------------

    badge.textContent = totalWishlist;

    if (totalWishlist > 0) {
      badge.classList.remove("hidden");
    } else {
      badge.classList.add("hidden");
    }
  } catch (error) {
    console.error("Update wishlist badge error:", error);
  }
}

// =========================================================
// UPDATE BOTH BADGES
// =========================================================

async function updateNavbarBadges() {
  await Promise.all([updateCartBadge(), updateWishlistBadge()]);
}

// =========================================================
// MAKE FUNCTIONS AVAILABLE GLOBALLY
// =========================================================

window.updateCartBadge = updateCartBadge;
window.updateWishlistBadge = updateWishlistBadge;
window.updateNavbarBadges = updateNavbarBadges;

// =========================================================
// INITIALIZE
// =========================================================

document.addEventListener("DOMContentLoaded", () => {
  updateNavbarBadges();
});
