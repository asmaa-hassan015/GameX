
// =========================================================
// DOM READY
// =========================================================

document.addEventListener("DOMContentLoaded", () => {
  const paymentForm = document.getElementById("paymentForm");

  if (!paymentForm) {
    return;
  }

  // =========================================================
  // API
  // =========================================================

  const API_BASE = "/GameX/BACKEND/";

  // =========================================================
  // DOM ELEMENTS
  // =========================================================

  const cardNumber = document.getElementById("cardNumber");
  const cardName = document.getElementById("cardName");
  const expiry = document.getElementById("expiry");
  const cvv = document.getElementById("cvv");

  const cardIcon = document.getElementById("cardIcon");

  const saveCard = document.getElementById("saveCard");
  const saveCardBox = document.getElementById("saveCardBox");

  const payBtn = document.getElementById("payBtn");
  const paySpinner = document.getElementById("paySpinner");
  const payBtnLabel = document.getElementById("payBtnLabel");

  const paymentMethods = document.querySelectorAll(".method-option");

  const cardFormSection = document.getElementById("cardFormSection");

  const brandsWrap = document.getElementById("brandsWrap");

  const walletInfo = document.getElementById("walletInfo");

  // =========================================================
  // PAYMENT METHOD
  // =========================================================

  let selectedMethod = "card";

  // =========================================================
  // SAVE CARD
  // =========================================================

  if (saveCardBox && saveCard) {
    saveCardBox.addEventListener("click", (event) => {
      if (event.target === saveCard) {
        return;
      }

      saveCard.checked = !saveCard.checked;

      updateSaveCardUI();
    });

    saveCard.addEventListener("change", () => {
      updateSaveCardUI();
    });
  }

  // =========================================================
  // UPDATE SAVE CARD UI
  // =========================================================

  function updateSaveCardUI() {
    if (!saveCardBox || !saveCard) {
      return;
    }

    saveCardBox.textContent = saveCard.checked ? "✓" : "";

    saveCardBox.classList.toggle("bg-primary", saveCard.checked);

    saveCardBox.classList.toggle("bg-transparent", !saveCard.checked);

    saveCardBox.classList.toggle("border", !saveCard.checked);

    saveCardBox.classList.toggle("border-borderc", !saveCard.checked);
  }

  // =========================================================
  // CARD BRAND DETECTION
  // =========================================================

  function detectBrand(digits) {
    if (/^4/.test(digits)) {
      return "visa";
    }

    if (/^5[1-5]/.test(digits) || /^2(2[2-9]|[3-6]\d|7[01]|720)/.test(digits)) {
      return "mastercard";
    }

    if (/^3[47]/.test(digits)) {
      return "amex";
    }

    if (/^6(011|5)/.test(digits)) {
      return "discover";
    }

    if (/^35/.test(digits)) {
      return "jcb";
    }

    return null;
  }

  // =========================================================
  // CARD NUMBER INPUT
  // =========================================================

  if (cardNumber) {
    cardNumber.addEventListener("input", () => {
      let digits = cardNumber.value.replace(/\D/g, "").slice(0, 16);

      const formatted = digits.match(/.{1,4}/g)?.join(" ") || "";

      cardNumber.value = formatted;

      clearError(cardNumber, "cardNumberError");

      const brand = detectBrand(digits);

      if (cardIcon) {
        cardIcon.textContent = brand ? "✓" : "💳";

        cardIcon.classList.toggle("text-success", Boolean(brand));

        cardIcon.classList.toggle("text-accent", !brand);
      }
    });
  }

  // =========================================================
  // EXPIRY INPUT
  // =========================================================

  if (expiry) {
    expiry.addEventListener("input", () => {
      let value = expiry.value.replace(/\D/g, "").slice(0, 4);

      if (value.length >= 3) {
        value = value.slice(0, 2) + " / " + value.slice(2);
      }

      expiry.value = value;

      clearError(expiry, "expiryError");
    });
  }

  // =========================================================
  // CVV INPUT
  // =========================================================

  if (cvv) {
    cvv.addEventListener("input", () => {
      cvv.value = cvv.value.replace(/\D/g, "").slice(0, 4);

      clearError(cvv, "cvvError");
    });
  }

  // =========================================================
  // CARD NAME INPUT
  // =========================================================

  if (cardName) {
    cardName.addEventListener("input", () => {
      clearError(cardName, "cardNameError");
    });
  }

  // =========================================================
  // ERROR HANDLING
  // =========================================================

  function setError(input, errorId, show) {
    const errorElement = document.getElementById(errorId);

    if (!input || !errorElement) {
      return;
    }

    if (show) {
      input.classList.add("border-danger", "ring-[3px]", "ring-danger/20");

      input.classList.remove("border-success");

      errorElement.classList.remove("hidden");
    } else {
      input.classList.remove("border-danger", "ring-[3px]", "ring-danger/20");

      errorElement.classList.add("hidden");
    }
  }

  // =========================================================
  // CLEAR ERROR
  // =========================================================

  function clearError(input, errorId) {
    setError(input, errorId, false);
  }

  // =========================================================
  // LUHN CHECK
  // =========================================================

  function luhnCheck(number) {
    let sum = 0;
    let alternate = false;

    for (let i = number.length - 1; i >= 0; i--) {
      let digit = parseInt(number[i], 10);

      if (alternate) {
        digit *= 2;

        if (digit > 9) {
          digit -= 9;
        }
      }

      sum += digit;
      alternate = !alternate;
    }

    return sum % 10 === 0;
  }

  // =========================================================
  // VALIDATE CARD FORM
  // =========================================================

  function validateCardForm() {
    let valid = true;

    // ---------------------------------------------------------
    // CARD NUMBER
    // ---------------------------------------------------------

    const digits = cardNumber ? cardNumber.value.replace(/\D/g, "") : "";

    if (
      !cardNumber ||
      digits.length < 13 ||
      digits.length > 16 ||
      !luhnCheck(digits)
    ) {
      setError(cardNumber, "cardNumberError", true);

      valid = false;
    } else {
      cardNumber.classList.remove("border-danger");

      cardNumber.classList.add("border-success");

      clearError(cardNumber, "cardNumberError");
    }

    // ---------------------------------------------------------
    // CARD NAME
    // ---------------------------------------------------------

    if (!cardName || cardName.value.trim().length < 2) {
      setError(cardName, "cardNameError", true);

      valid = false;
    } else {
      cardName.classList.remove("border-danger");

      cardName.classList.add("border-success");

      clearError(cardName, "cardNameError");
    }

    // ---------------------------------------------------------
    // EXPIRY
    // ---------------------------------------------------------

    const expiryMatch = expiry
      ? expiry.value.match(/^(\d{2}) \/ (\d{2})$/)
      : null;

    let expiryValid = false;

    if (expiryMatch) {
      const month = parseInt(expiryMatch[1], 10);

      const year = parseInt(expiryMatch[2], 10);

      if (month >= 1 && month <= 12) {
        const now = new Date();

        const currentYear = now.getFullYear() % 100;

        const currentMonth = now.getMonth() + 1;

        if (
          year > currentYear ||
          (year === currentYear && month >= currentMonth)
        ) {
          expiryValid = true;
        }
      }
    }

    if (!expiryValid) {
      setError(expiry, "expiryError", true);

      valid = false;
    } else {
      expiry.classList.remove("border-danger");

      expiry.classList.add("border-success");

      clearError(expiry, "expiryError");
    }

    // ---------------------------------------------------------
    // CVV
    // ---------------------------------------------------------

    if (!cvv || cvv.value.length < 3 || cvv.value.length > 4) {
      setError(cvv, "cvvError", true);

      valid = false;
    } else {
      cvv.classList.remove("border-danger");

      cvv.classList.add("border-success");

      clearError(cvv, "cvvError");
    }

    return valid;
  }

  // =========================================================
  // PAYMENT METHODS
  // =========================================================

  paymentMethods.forEach((method) => {
    method.addEventListener("click", () => {
      // ---------------------------------------------------------
      // RESET ALL METHODS
      // ---------------------------------------------------------

      paymentMethods.forEach((item) => {
        item.classList.remove(
          "selected",
          "border-primary",
          "bg-primary/[0.06]",
          "shadow-[0_0_0_1px_rgba(124,44,255,0.15),0_0_24px_rgba(124,44,255,0.12)]",
        );

        item.classList.add("border-borderc");

        const radio = item.querySelector(".radio-dot");

        if (radio) {
          radio.classList.remove("border-accent");

          radio.classList.add("border-borderc");

          const dot = radio.querySelector("span");

          if (dot) {
            dot.remove();
          }
        }
      });

      // ---------------------------------------------------------
      // SELECT CURRENT METHOD
      // ---------------------------------------------------------

      method.classList.add(
        "selected",
        "border-primary",
        "bg-primary/[0.06]",
        "shadow-[0_0_0_1px_rgba(124,44,255,0.15),0_0_24px_rgba(124,44,255,0.12)]",
      );

      method.classList.remove("border-borderc");

      const radio = method.querySelector(".radio-dot");

      if (radio) {
        radio.classList.remove("border-borderc");

        radio.classList.add("border-accent");

        if (!radio.querySelector("span")) {
          const dot = document.createElement("span");

          dot.className =
            "w-2 h-2 rounded-full bg-accent shadow-[0_0_8px_#B026FF]";

          radio.appendChild(dot);
        }
      }

      // ---------------------------------------------------------
      // UPDATE SELECTED METHOD
      // ---------------------------------------------------------

      selectedMethod = method.dataset.method;

      // ---------------------------------------------------------
      // CARD SECTION
      // ---------------------------------------------------------

      if (cardFormSection) {
        cardFormSection.classList.toggle("hidden", selectedMethod !== "card");
      }

      if (brandsWrap) {
        brandsWrap.classList.toggle("hidden", selectedMethod !== "card");
      }

      // ---------------------------------------------------------
      // WALLET SECTION
      // ---------------------------------------------------------

      if (walletInfo) {
        walletInfo.classList.toggle("hidden", selectedMethod !== "wallet");

        walletInfo.classList.toggle("flex", selectedMethod === "wallet");
      }
    });
  });

  // =========================================================
  // LOAD CART SUMMARY
  // =========================================================

  async function loadPaymentSummary() {
    try {
      console.log("Fetching:", API_BASE + "cart.php");

      const response = await fetch(API_BASE + "cart.php", {
        method: "GET",
        credentials: "include",
        headers: {
          Accept: "application/json",
        },
      });

      console.log("Cart HTTP Status:", response.status);

      if (!response.ok) {
        throw new Error(`Failed to load cart: HTTP ${response.status}`);
      }

      const result = await response.json();

      console.log("Cart API Response:", result);

      // ---------------------------------------------------------
      // VALIDATE API RESPONSE
      // ---------------------------------------------------------

      if (!result.success) {
        throw new Error(result.message || "Cart API returned an error");
      }

      // ---------------------------------------------------------
      // GET CART ITEMS
      // ---------------------------------------------------------

      const cart = Array.isArray(result.data) ? result.data : [];

      renderPaymentSummary(cart);
    } catch (error) {
      console.error("Payment cart error:", error);

      renderPaymentSummary([]);
    }
  }

  // =========================================================
  // RENDER PAYMENT SUMMARY
  // =========================================================

  function renderPaymentSummary(cart) {
    const orderItems = document.getElementById("orderItems");

    const emptyOrderMessage = document.getElementById("emptyOrderMessage");

    const subtotalEl = document.getElementById("subtotal");

    const discountEl = document.getElementById("discount");

    const taxEl = document.getElementById("tax");

    const totalEl = document.getElementById("total");

    if (!orderItems) {
      return;
    }

    orderItems.innerHTML = "";

    // ---------------------------------------------------------
    // EMPTY CART
    // ---------------------------------------------------------

    if (!Array.isArray(cart) || cart.length === 0) {
      if (emptyOrderMessage) {
        emptyOrderMessage.classList.remove("hidden");
      }

      if (subtotalEl) {
        subtotalEl.textContent = "$0.00";
      }

      if (discountEl) {
        discountEl.textContent = "-$0.00";
      }

      if (taxEl) {
        taxEl.textContent = "$0.00";
      }

      if (totalEl) {
        totalEl.textContent = "$0.00";
      }

      return;
    }

    // ---------------------------------------------------------
    // HIDE EMPTY MESSAGE
    // ---------------------------------------------------------

    if (emptyOrderMessage) {
      emptyOrderMessage.classList.add("hidden");
    }

    // ---------------------------------------------------------
    // RENDER ITEMS
    // ---------------------------------------------------------

    cart.forEach((item) => {
      const price = Number(item.price) || 0;

      const quantity = Number(item.quantity) || 0;

      const itemTotal = price * quantity;

      const itemElement = document.createElement("div");

      itemElement.className = "flex items-center justify-between gap-3";

      const image = item.img || item.image || "";

      const title = item.title || "Unknown Game";

      itemElement.innerHTML = `
                <div class="flex items-center gap-3 min-w-0">

                    <img
                        src="${image}"
                        alt="${title}"
                        class="w-12 h-12 rounded-lg object-cover border border-borderc shrink-0"
                    />

                    <div class="min-w-0">

                        <p class="text-sm text-textmain font-medium truncate">
                            ${title}
                        </p>

                        <p class="text-xs text-muted mt-1">
                            Qty: ${quantity}
                        </p>

                    </div>

                </div>

                <span class="text-sm text-textmain font-semibold shrink-0">
                    $${itemTotal.toFixed(2)}
                </span>
            `;

      orderItems.appendChild(itemElement);
    });

    // ---------------------------------------------------------
    // CALCULATE TOTALS
    // ---------------------------------------------------------

    let subtotal = 0;

    cart.forEach((item) => {
      const price = Number(item.price) || 0;

      const quantity = Number(item.quantity) || 0;

      subtotal += price * quantity;
    });

    const DISCOUNT_FLAT = 10;
    const TAX_RATE = 0.1;

    const discount = subtotal > 0 ? Math.min(DISCOUNT_FLAT, subtotal) : 0;

    const taxableAmount = Math.max(subtotal - discount, 0);

    const tax = taxableAmount * TAX_RATE;

    const total = taxableAmount + tax;

    // ---------------------------------------------------------
    // UPDATE UI
    // ---------------------------------------------------------

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
  }

  // =========================================================
  // PAYMENT BUTTON
  // =========================================================

  if (payBtn) {
    payBtn.addEventListener("click", async (event) => {
      event.preventDefault();

      // ---------------------------------------------------------
      // WALLET PAYMENT
      // ---------------------------------------------------------

      if (selectedMethod === "wallet") {
        showPaymentToast("Wallet payment selected successfully.", true);

        return;
      }

      // ---------------------------------------------------------
      // CARD VALIDATION
      // ---------------------------------------------------------

      const valid = validateCardForm();

      if (!valid) {
        showPaymentToast("Please fix the highlighted fields.", false);

        return;
      }

      // ---------------------------------------------------------
      // BUTTON LOADING
      // ---------------------------------------------------------

      payBtn.disabled = true;

      if (paySpinner) {
        paySpinner.classList.remove("hidden");
      }

      if (payBtnLabel) {
        payBtnLabel.textContent = "Processing...";
      }

      // ---------------------------------------------------------
      // FRONTEND PAYMENT SIMULATION
      // ---------------------------------------------------------

      setTimeout(() => {
        showPaymentToast("Payment information is valid.", true);

        if (paySpinner) {
          paySpinner.classList.add("hidden");
        }

        if (payBtnLabel) {
          payBtnLabel.textContent = "✓ Payment Confirmed";
        }

        payBtn.disabled = false;
      }, 1200);
    });
  }

  // =========================================================
  // PAYMENT TOAST
  // =========================================================

  function showPaymentToast(message, success) {
    const toast = document.getElementById("toast");

    const toastMsg = document.getElementById("toastMsg");

    if (!toast || !toastMsg) {
      return;
    }

    toastMsg.textContent = message;

    toast.classList.remove(
      "border-success",
      "border-danger",
      "opacity-100",
      "translate-y-0",
    );

    toast.classList.add(
      success ? "border-success" : "border-danger",

      "opacity-100",
      "translate-y-0",
    );

    const dot = toast.querySelector("span");

    if (dot) {
      dot.classList.remove("bg-success", "bg-danger");

      dot.classList.add(success ? "bg-success" : "bg-danger");

      dot.textContent = success ? "✓" : "!";
    }

    // ---------------------------------------------------------
    // HIDE TOAST
    // ---------------------------------------------------------

    setTimeout(() => {
      toast.classList.remove("opacity-100", "translate-y-0");

      toast.classList.add("opacity-0", "-translate-y-5");
    }, 3200);
  }

  // =========================================================
  // INITIALIZE
  // =========================================================

  updateSaveCardUI();
  loadPaymentSummary();
});
