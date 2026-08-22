
// =========================================================
// 1. API CONFIGURATION
// =========================================================

const GAME_DETAILS_API = "/GameX/BACKEND/";

// =========================================================
// 2. GET CURRENT GAME ID
// =========================================================

const currentGameId = Number(window.GAME_ID || 0);

// =========================================================
// 3. VALIDATE GAME ID
// =========================================================

function isValidGameId() {
  return Number.isInteger(currentGameId) && currentGameId > 0;
}

// =========================================================
// 4. IMAGE GALLERY
// =========================================================

function selectImage(element, image) {
  const mainImage = document.getElementById("mainDisplayImage");

  if (!mainImage) {
    return;
  }

  // Update main image
  mainImage.src = image;

  // Remove active state
  document.querySelectorAll(".thumb-active").forEach((item) => {
    item.classList.remove("thumb-active");
  });

  // Add active state
  if (element) {
    element.classList.add("thumb-active");
  }
}

// =========================================================
// 5. PLAY TRAILER
// =========================================================

function playTrailer() {
  console.log("Trailer button clicked.");
}

// =========================================================
// 6. HANDLE ADD TO CART
// =========================================================

async function handleCart() {
  // =====================================================
  // VALIDATE GAME ID
  // =====================================================

  if (!isValidGameId()) {
    console.error("Invalid game ID:", currentGameId);

    alert("Invalid game ID.");

    return false;
  }

  // =====================================================
  // GET BUTTON ELEMENTS
  // =====================================================

  const button = document.getElementById("cartBtn");

  const buttonText = document.getElementById("cartBtnText");

  if (!button) {
    console.error("Cart button not found.");

    return false;
  }

  // =====================================================
  // PREVENT DUPLICATE REQUEST
  // =====================================================

  if (button.disabled) {
    return false;
  }

  // =====================================================
  // SAVE ORIGINAL BUTTON TEXT
  // =====================================================

  const originalText = buttonText
    ? buttonText.textContent.trim()
    : "Add to Cart";

  // =====================================================
  // LOADING STATE
  // =====================================================

  button.disabled = true;

  if (buttonText) {
    buttonText.textContent = "Adding...";
  }

  try {
    // =================================================
    // SEND CART REQUEST
    // =================================================

    console.log("Adding game to cart:", currentGameId);

    const response = await fetch(GAME_DETAILS_API + "cart.php", {
      method: "POST",

      credentials: "include",

      headers: {
        "Content-Type": "application/json",

        Accept: "application/json",
      },

      body: JSON.stringify({
        action: "add",
        id: currentGameId,
      }),
    });

    // =================================================
    // READ API RESPONSE
    // =================================================

    const text = await response.text();

    let result;

    try {
      result = JSON.parse(text);
    } catch (error) {
      console.error("Cart API did not return JSON:", text);

      throw new Error("Invalid response from cart API.");
    }

    console.log("Cart API response:", result);

    // =================================================
    // LOGIN REQUIRED
    // =================================================

    if (response.status === 401) {
      alert("Please login first.");

      if (buttonText) {
        buttonText.textContent = originalText;
      }

      return false;
    }

    // =================================================
    // API ERROR
    // =================================================

    if (!response.ok || result.success !== true) {
      console.error("Cart API error:", result);

      alert(result.message || "Failed to add game to cart.");

      if (buttonText) {
        buttonText.textContent = originalText;
      }

      return false;
    }

    // =================================================
    // SUCCESS STATE
    // =================================================

    if (buttonText) {
      buttonText.textContent = "Added to Cart";
    }

    const icon = button.querySelector("i");

    if (icon) {
      icon.className = "fa-solid fa-check mr-3";
    }

    // =================================================
    // UPDATE CART BADGE
    // =================================================

    if (typeof window.updateCartBadge === "function") {
      await window.updateCartBadge();
    }

    return true;
  } catch (error) {
    // =================================================
    // REQUEST ERROR
    // =================================================

    console.error("Cart request error:", error);

    alert("Could not connect to cart.");

    if (buttonText) {
      buttonText.textContent = originalText;
    }

    return false;
  } finally {
    // =================================================
    // ENABLE BUTTON
    // =================================================

    button.disabled = false;
  }
}

// =========================================================
// 7. HANDLE ADD TO WISHLIST
// =========================================================

async function handleWishlist() {
  // =====================================================
  // VALIDATE GAME ID
  // =====================================================

  if (!isValidGameId()) {
    console.error("Invalid game ID:", currentGameId);

    alert("Invalid game ID.");

    return false;
  }

  // =====================================================
  // GET BUTTON ELEMENTS
  // =====================================================

  const button = document.getElementById("wishlistBtn");

  const buttonText = document.getElementById("wishlistBtnText");

  const icon = document.getElementById("wishlistIcon");

  if (!button) {
    console.error("Wishlist button not found.");

    return false;
  }

  // =====================================================
  // PREVENT DUPLICATE REQUEST
  // =====================================================

  if (button.disabled) {
    return false;
  }

  // =====================================================
  // SAVE ORIGINAL BUTTON TEXT
  // =====================================================

  const originalText = buttonText
    ? buttonText.textContent.trim()
    : "Add to Wishlist";

  // =====================================================
  // LOADING STATE
  // =====================================================

  button.disabled = true;

  if (buttonText) {
    buttonText.textContent = "Adding...";
  }

  try {
    // =================================================
    // SEND WISHLIST REQUEST
    // =================================================

    console.log("Adding game to wishlist:", currentGameId);

    const response = await fetch(GAME_DETAILS_API + "wishlist.php", {
      method: "POST",

      credentials: "include",

      headers: {
        "Content-Type": "application/json",

        Accept: "application/json",
      },

      body: JSON.stringify({
        action: "add",
        game_id: currentGameId,
      }),
    });

    // =================================================
    // READ API RESPONSE
    // =================================================

    const text = await response.text();

    let result;

    try {
      result = JSON.parse(text);
    } catch (error) {
      console.error("Wishlist API did not return JSON:", text);

      throw new Error("Invalid response from wishlist API.");
    }

    console.log("Wishlist API response:", result);

    // =================================================
    // LOGIN REQUIRED
    // =================================================

    if (response.status === 401) {
      alert("Please login first.");

      if (buttonText) {
        buttonText.textContent = originalText;
      }

      return false;
    }

    // =================================================
    // API ERROR
    // =================================================

    if (!response.ok || result.success !== true) {
      console.error("Wishlist API error:", result);

      alert(result.message || "Failed to add game to wishlist.");

      if (buttonText) {
        buttonText.textContent = originalText;
      }

      return false;
    }

    // =================================================
    // SUCCESS STATE
    // =================================================

    if (icon) {
      icon.className = "fa-solid fa-heart mr-3";
    }

    if (buttonText) {
      buttonText.textContent = "Added to Wishlist";
    }

    // =================================================
    // UPDATE WISHLIST BADGE
    // =================================================

    if (typeof window.updateWishlistBadge === "function") {
      await window.updateWishlistBadge();
    }

    return true;
  } catch (error) {
    // =================================================
    // REQUEST ERROR
    // =================================================

    console.error("Wishlist request error:", error);

    alert("Could not connect to wishlist.");

    if (buttonText) {
      buttonText.textContent = originalText;
    }

    return false;
  } finally {
    // =================================================
    // ENABLE BUTTON
    // =================================================

    button.disabled = false;
  }
}

// =========================================================
// 8. BUY NOW
// =========================================================

async function buyNow() {
  // =====================================================
  // VALIDATE GAME ID
  // =====================================================

  if (!isValidGameId()) {
    console.error("Invalid game ID.");

    alert("Invalid game ID.");

    return;
  }

  // =====================================================
  // ADD GAME TO CART FIRST
  // =====================================================

  const added = await handleCart();

  // =====================================================
  // REDIRECT TO CART
  // =====================================================

  if (added === true) {
    window.location.href = "/GameX/cart.php";
  }
}
