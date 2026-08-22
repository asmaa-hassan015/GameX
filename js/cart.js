
const CART_API = "/GameX/BACKEND/";

const TAX_RATE = 0.1;
const DISCOUNT_FLAT = 10.0;

// =========================================================
// 2. CART API - GET CART
// =========================================================

async function getCart() {
  try {
    const response = await fetch(CART_API + "cart.php", {
      method: "GET",
      credentials: "include",
      headers: {
        Accept: "application/json",
      },
    });

    const data = await response.json();

    // Check API response
    if (!response.ok) {
      console.error("Get cart API error:", data);
      return [];
    }

    // PHP response:
    // {
    //     success: true,
    //     data: [...]
    // }

    if (data && data.success === true && Array.isArray(data.data)) {
      return data.data;
    }

    // Compatibility if PHP returns array directly
    if (Array.isArray(data)) {
      return data;
    }

    console.error("Invalid cart response:", data);

    return [];
  } catch (error) {
    console.error("Get cart error:", error);

    return [];
  }
}

// =========================================================
// 3. CART API - ADD TO CART
// =========================================================

async function addToCart(button) {
  if (!button) {
    return {
      success: false,
      message: "Button not found",
    };
  }

  const gameId = parseInt(button.dataset.gameId, 10);

  if (!gameId) {
    console.error("Invalid game ID:", button);

    return {
      success: false,
      message: "Invalid game ID",
    };
  }

  try {
    const response = await fetch(CART_API + "cart.php", {
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

    // =====================================================
    // LOGIN REQUIRED
    // =====================================================

    if (response.status === 401) {
      alert("Please login first.");

      return {
        success: false,
        message: "Not logged in",
      };
    }

    // =====================================================
    // API ERROR
    // =====================================================

    if (!response.ok) {
      console.error("Add to cart API error:", result);

      return {
        success: false,
        message: result.message || "Failed to add item",
      };
    }

    return result;
  } catch (error) {
    console.error("Add to cart error:", error);

    return {
      success: false,
      message: "Server error",
    };
  }
}

// =========================================================
// 4. ADD TO CART BUTTONS
// =========================================================

function initAddToCartButtons() {
  const buttons = document.querySelectorAll(".add-cart-btn");

  buttons.forEach((btn) => {
    btn.addEventListener("click", async function (event) {
      event.preventDefault();
      event.stopPropagation();

      // =================================================
      // GET GAME ID
      // =================================================

      const gameId = parseInt(btn.dataset.gameId, 10);

      if (!gameId) {
        console.error("Missing data-game-id:", btn);

        return;
      }

      // =================================================
      // SAVE ORIGINAL ICON
      // =================================================

      const icon = btn.querySelector("i");

      const originalClass = icon ? icon.className : null;

      // =================================================
      // DISABLE BUTTON
      // =================================================

      btn.disabled = true;

      // =================================================
      // ADD GAME TO CART
      // =================================================

      const result = await addToCart(btn);

      // =================================================
      // SUCCESS
      // =================================================

      if (result.success === true) {
        if (icon) {
          icon.className = "fa-solid fa-check text-sm";

          setTimeout(() => {
            if (originalClass) {
              icon.className = originalClass;
            }
          }, 900);
        }

        await updateCartBadge();
      } else {
        console.error("Could not add item:", result.message);
      }

      // =================================================
      // ENABLE BUTTON
      // =================================================

      btn.disabled = false;
    });
  });
}

// =========================================================
// 5. CART BADGE
// =========================================================

async function updateCartBadge() {
  const badge = document.getElementById("cart-badge");

  if (!badge) {
    return;
  }

  // =====================================================
  // GET CART
  // =====================================================

  const cart = await getCart();

  // =====================================================
  // INVALID CART
  // =====================================================

  if (!Array.isArray(cart)) {
    badge.textContent = "0";

    badge.classList.add("hidden");

    return;
  }

  // =====================================================
  // CALCULATE TOTAL QUANTITY
  // =====================================================

  const totalQty = cart.reduce((sum, item) => {
    return sum + Number(item.quantity || 0);
  }, 0);

  // =====================================================
  // UPDATE BADGE
  // =====================================================

  badge.textContent = totalQty;

  badge.classList.toggle("hidden", totalQty === 0);
}

// =========================================================
// 6. UPDATE CART ITEM QUANTITY
// =========================================================

async function updateCartQuantity(row, itemId, action) {
  try {
    // =====================================================
    // DISABLE QUANTITY BUTTONS
    // =====================================================

    const buttons = row.querySelectorAll(".qty-btn");

    buttons.forEach((button) => {
      button.disabled = true;
    });

    // =====================================================
    // SEND REQUEST
    // =====================================================

    const response = await fetch(CART_API + "cart.php", {
      method: "POST",
      credentials: "include",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({
        action: action,
        id: parseInt(itemId, 10),
      }),
    });

    const result = await response.json();

    // =====================================================
    // API ERROR
    // =====================================================

    if (!response.ok) {
      console.error("Update quantity error:", result);

      buttons.forEach((button) => {
        button.disabled = false;
      });

      return;
    }

    // =====================================================
    // ITEM REMOVED
    // =====================================================

    if (result.success !== true || Number(result.quantity) <= 0) {
      row.remove();

      updateCartSummary();

      await updateCartBadge();

      return;
    }

    // =====================================================
    // GET NEW QUANTITY
    // =====================================================

    const newQuantity = Number(result.quantity);

    // =====================================================
    // GET NEW PRICE
    // =====================================================

    const newPrice = Number(
      result.price !== undefined ? result.price : row.dataset.price,
    );

    // =====================================================
    // UPDATE DATASET
    // =====================================================

    row.dataset.qty = newQuantity;
    row.dataset.price = newPrice;

    // =====================================================
    // UPDATE QUANTITY UI
    // =====================================================

    const quantityElement = row.querySelector(".qty-value");

    if (quantityElement) {
      quantityElement.textContent = newQuantity;
    }

    // =====================================================
    // UPDATE TOTAL UI
    // =====================================================

    const totalElement = row.querySelector(".row-total");

    if (totalElement) {
      totalElement.textContent = "$" + (newPrice * newQuantity).toFixed(2);
    }

    // =====================================================
    // UPDATE SUMMARY
    // =====================================================

    updateCartSummary();

    await updateCartBadge();

    // =====================================================
    // ENABLE BUTTONS
    // =====================================================

    buttons.forEach((button) => {
      button.disabled = false;
    });
  } catch (error) {
    console.error("Update quantity error:", error);

    // =====================================================
    // ENABLE BUTTONS AFTER ERROR
    // =====================================================

    const buttons = row.querySelectorAll(".qty-btn");

    buttons.forEach((button) => {
      button.disabled = false;
    });
  }
}

// =========================================================
// 7. RENDER CART ROW
// =========================================================

function renderCartRow(item) {
  const row = document.createElement("div");

  // =====================================================
  // ROW DATA
  // =====================================================

  row.className =
    "flex items-center justify-between " +
    "gap-3 bg-[#0f0d1a] " +
    "border border-[#26223D] " +
    "rounded-xl p-3";

  row.dataset.id = item.id;

  // =====================================================
  // PRODUCT DATA
  // =====================================================

  const price = Number(item.price || 0);

  const quantity = Number(item.quantity || 1);

  row.dataset.price = price;
  row.dataset.qty = quantity;

  // =====================================================
  // CART ROW HTML
  // =====================================================

  row.innerHTML = `

        <!-- PRODUCT INFO -->

        <div
            class="flex items-center
                   gap-3
                   min-w-0"
        >

            <img
                src="${item.img || item.image || ""}"
                class="w-16 h-16
                       object-cover
                       rounded-lg
                       shrink-0"
                alt="${item.title || "Game"}"
            >

            <div class="min-w-0">

                <h3
                    class="font-semibold
                           text-textmain
                           truncate"
                >
                    ${item.title || "Unknown Game"}
                </h3>

                <p
                    class="text-xs
                           text-gray-400
                           mt-1"
                >
                    ${item.publisher || ""}
                </p>

                <p
                    class="text-xs
                           text-gray-500
                           mt-1"
                >
                    $${price.toFixed(2)} each
                </p>

            </div>

        </div>


        <!-- PRODUCT ACTIONS -->

        <div
            class="flex items-center
                   gap-4
                   shrink-0"
        >

            <!-- TOTAL -->

            <span
                class="row-total
                       font-bold
                       w-20
                       text-right"
            >
                $${(price * quantity).toFixed(2)}
            </span>


            <!-- QUANTITY -->

            <div
                class="flex items-center
                       gap-3
                       bg-[#15121f]
                       border border-[#26223D]
                       rounded-lg
                       px-3 py-1"
            >

                <!-- DECREASE -->

                <button
                    class="qty-btn
                           text-gray-300
                           hover:text-[#A726DD]
                           w-4
                           transition
                           disabled:opacity-50"
                    data-action="decrease"
                    type="button"
                    aria-label="Decrease quantity"
                >
                    −
                </button>


                <!-- QUANTITY -->

                <span
                    class="qty-value
                           w-4
                           text-center"
                >
                    ${quantity}
                </span>


                <!-- INCREASE -->

                <button
                    class="qty-btn
                           text-gray-300
                           hover:text-[#A726DD]
                           w-4
                           transition
                           disabled:opacity-50"
                    data-action="increase"
                    type="button"
                    aria-label="Increase quantity"
                >
                    +
                </button>

            </div>


            <!-- REMOVE -->

            <button
                class="cart-remove-btn
                       text-red-500
                       hover:text-red-400
                       duration-300
                       w-9 h-9
                       flex items-center
                       justify-center"
                type="button"
                title="Remove item"
            >

                <i class="fa-solid fa-trash"></i>

            </button>

        </div>
    `;

  // =====================================================
  // QUANTITY BUTTONS
  // =====================================================

  row.querySelectorAll(".qty-btn").forEach((btn) => {
    btn.addEventListener("click", async function () {
      const action = btn.dataset.action;

      await updateCartQuantity(row, item.id, action);
    });
  });

  // =====================================================
  // REMOVE BUTTON
  // =====================================================

  const removeButton = row.querySelector(".cart-remove-btn");

  if (removeButton) {
    removeButton.addEventListener("click", async function () {
      try {
        removeButton.disabled = true;

        // =================================================
        // REMOVE REQUEST
        // =================================================

        const response = await fetch(CART_API + "cart.php", {
          method: "POST",
          credentials: "include",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
          body: JSON.stringify({
            action: "remove",
            id: parseInt(item.id, 10),
          }),
        });

        const result = await response.json();

        // =================================================
        // API ERROR
        // =================================================

        if (!response.ok) {
          console.error("Remove cart item error:", result);

          removeButton.disabled = false;

          return;
        }

        // =================================================
        // REMOVE SUCCESSFULLY
        // =================================================

        if (result.success === true) {
          row.remove();

          updateCartSummary();

          await updateCartBadge();
        }
      } catch (error) {
        console.error("Remove cart item error:", error);

        removeButton.disabled = false;
      }
    });
  }

  return row;
}

// =========================================================
// 8. UPDATE CART SUMMARY
// =========================================================

function updateCartSummary() {
  const container = document.getElementById("cart-items");

  if (!container) {
    return;
  }

  // =====================================================
  // GET CART ROWS
  // =====================================================

  const rows = container.querySelectorAll("[data-id]");

  // =====================================================
  // CALCULATE SUBTOTAL
  // =====================================================

  let subtotal = 0;

  rows.forEach((row) => {
    const price = Number(row.dataset.price || 0);

    const quantity = Number(row.dataset.qty || 0);

    subtotal += price * quantity;
  });

  // =====================================================
  // CALCULATE DISCOUNT
  // =====================================================

  const discount = rows.length > 0 ? DISCOUNT_FLAT : 0;

  // =====================================================
  // CALCULATE TAX
  // =====================================================

  const taxableAmount = Math.max(subtotal - discount, 0);

  const tax = taxableAmount * TAX_RATE;

  // =====================================================
  // CALCULATE TOTAL
  // =====================================================

  const total = taxableAmount + tax;

  // =====================================================
  // GET SUMMARY ELEMENTS
  // =====================================================

  const subtotalEl = document.getElementById("subtotal");

  const discountEl = document.getElementById("discount");

  const taxEl = document.getElementById("tax");

  const totalEl = document.getElementById("total");

  const checkoutBtn = document.getElementById("checkout-btn");

  // =====================================================
  // UPDATE SUMMARY UI
  // =====================================================

  if (subtotalEl) {
    subtotalEl.textContent = "$" + subtotal.toFixed(2);
  }

  if (discountEl) {
    discountEl.textContent = "-$" + discount.toFixed(2);
  }

  if (taxEl) {
    taxEl.textContent = "$" + tax.toFixed(2);
  }

  if (totalEl) {
    totalEl.textContent = "$" + total.toFixed(2);
  }

  // =====================================================
  // CHECKOUT BUTTON
  // =====================================================

  if (checkoutBtn) {
    checkoutBtn.disabled = rows.length === 0;
  }

  // =====================================================
  // EMPTY CART
  // =====================================================

  if (rows.length === 0) {
    container.innerHTML = `

            <div
                class="text-center py-10"
            >

                <i
                    class="fa-solid
                           fa-cart-shopping
                           text-4xl
                           text-muted
                           mb-4"
                >
                </i>

                <p
                    class="text-gray-400"
                >
                    Your cart is empty.
                </p>

            </div>
        `;
  }
}

// =========================================================
// 9. RENDER CART PAGE
// =========================================================

async function renderCartPage() {
  const container = document.getElementById("cart-items");

  if (!container) {
    return;
  }

  // =====================================================
  // LOADING
  // =====================================================

  container.innerHTML = `

        <div
            class="text-center py-10"
        >

            <p
                class="text-gray-400"
            >
                Loading cart...
            </p>

        </div>
    `;

  // =====================================================
  // GET CART DATA
  // =====================================================

  const cart = await getCart();

  container.innerHTML = "";

  // =====================================================
  // EMPTY CART
  // =====================================================

  if (!Array.isArray(cart) || cart.length === 0) {
    container.innerHTML = `

            <div
                class="text-center py-10"
            >

                <i
                    class="fa-solid
                           fa-cart-shopping
                           text-4xl
                           text-muted
                           mb-4"
                >
                </i>

                <p
                    class="text-gray-400"
                >
                    Your cart is empty.
                </p>

            </div>
        `;

    updateCartSummary();

    return;
  }

  // =====================================================
  // RENDER CART ITEMS
  // =====================================================

  cart.forEach((item) => {
    container.appendChild(renderCartRow(item));
  });

  // =====================================================
  // UPDATE SUMMARY
  // =====================================================

  updateCartSummary();

  // =====================================================
  // CHECKOUT BUTTON
  // =====================================================

  const checkoutBtn = document.getElementById("checkout-btn");

  if (checkoutBtn) {
    checkoutBtn.onclick = function () {
      const rows = container.querySelectorAll("[data-id]");

      if (rows.length === 0) {
        return;
      }

      // Do not clear cart.
      // Payment page handles checkout.

      window.location.href = "payment.php";
    };
  }
}

// =========================================================
// 10. INITIALIZE CART PAGE
// =========================================================

document.addEventListener("DOMContentLoaded", async function () {
  // Initialize add-to-cart buttons
  initAddToCartButtons();

  // Render cart
  await renderCartPage();

  // Update navbar badge
  await updateCartBadge();
});
