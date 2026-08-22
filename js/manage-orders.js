// =========================================================
// GAME X - MANAGE ORDERS
// =========================================================

// =========================================================
// API
// =========================================================

const API = {
  get: "/GameX/BACKEND/manage-orders/orders.php",
  delete: "/GameX/BACKEND/manage-orders/delete-order.php",
};

// =========================================================
// GLOBAL VARIABLES
// =========================================================

const ORDERS_PER_PAGE = 5;

let currentPage = 1;
let allOrders = [];

// =========================================================
// DOM
// =========================================================

const ordersList = document.getElementById("ordersList");

const ordersPagination = document.getElementById("ordersPagination");

const ordersCount = document.getElementById("ordersCount");

// =========================================================
// STATUS CLASSES
// =========================================================

const statusClass = {
  Completed: "bg-green-500/10 text-green-400",

  Pending: "bg-yellow-500/10 text-yellow-400",

  Processing: "bg-purple-500/10 text-purple-400",

  Cancelled: "bg-red-500/10 text-red-400",
};

// =========================================================
// UPDATE ORDERS COUNT
// =========================================================

function updateOrdersCount() {
  if (!ordersCount) {
    return;
  }

  ordersCount.textContent = allOrders.length;
}

// =========================================================
// INITIALS
// =========================================================

function initials(name = "") {
  return name
    .split(" ")
    .filter(Boolean)
    .map((part) => part[0])
    .join("")
    .slice(0, 2)
    .toUpperCase();
}

// =========================================================
// ESCAPE HTML
// =========================================================

function escapeHTML(value = "") {
  return String(value ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

// =========================================================
// LOAD ORDERS
// =========================================================

async function loadOrders() {
  try {
    showLoading();

    console.log("Fetching:", API.get);

    const response = await fetch(API.get, {
      method: "GET",

      headers: {
        Accept: "application/json",
      },
    });

    // =====================================================
    // READ RESPONSE
    // =====================================================

    const text = await response.text();

    console.log("Orders API Response:", text);

    // =====================================================
    // HTTP ERROR
    // =====================================================

    if (!response.ok) {
      throw new Error(`HTTP Error: ${response.status}`);
    }

    // =====================================================
    // PARSE JSON
    // =====================================================

    let data;

    try {
      data = JSON.parse(text);
    } catch {
      console.error("Invalid JSON:", text);

      throw new Error("Server returned invalid JSON.");
    }

    // =====================================================
    // API ERROR
    // =====================================================

    if (!data.success) {
      throw new Error(data.message || "Failed to load orders.");
    }

    // =====================================================
    // SAVE DATA
    // =====================================================

    allOrders = Array.isArray(data.orders) ? data.orders : [];

    currentPage = 1;

    // =====================================================
    // RENDER
    // =====================================================

    renderOrders();
  } catch (error) {
    console.error("Load Orders Error:", error);

    showError(error.message || "Failed to load orders.");
  }
}

// =========================================================
// LOADING
// =========================================================

function showLoading() {
  if (!ordersList) {
    return;
  }

  ordersList.innerHTML = `
        <div
            class="
                flex
                flex-col
                items-center
                justify-center
                py-12
                sm:py-16
                px-5
                text-[#A5A1B5]
                text-sm
                text-center
            "
        >

            <i
                class="
                    fa-solid
                    fa-spinner
                    fa-spin
                    text-2xl
                    sm:text-3xl
                    mb-3
                "
            ></i>

            <span>
                Loading orders...
            </span>

        </div>
    `;

  if (ordersPagination) {
    ordersPagination.innerHTML = "";
  }
}

// =========================================================
// ERROR
// =========================================================

function showError(message) {
  if (ordersList) {
    ordersList.innerHTML = `
            <div
                class="
                    flex
                    flex-col
                    items-center
                    justify-center
                    py-12
                    sm:py-16
                    px-5
                    text-red-400
                    text-sm
                    text-center
                "
            >

                <i
                    class="
                        fa-solid
                        fa-triangle-exclamation
                        text-2xl
                        sm:text-3xl
                        mb-3
                    "
                ></i>

                <span>
                    ${escapeHTML(message)}
                </span>

            </div>
        `;
  }

  if (ordersPagination) {
    ordersPagination.innerHTML = "";
  }
}

// =========================================================
// RENDER ORDERS
// =========================================================

function renderOrders() {
  if (!ordersList) {
    return;
  }

  ordersList.innerHTML = "";

  // =======================================================
  // UPDATE COUNT
  // =======================================================

  updateOrdersCount();

  // =======================================================
  // EMPTY
  // =======================================================

  if (allOrders.length === 0) {
    ordersList.innerHTML = `
            <div
                class="
                    flex
                    flex-col
                    items-center
                    justify-center
                    py-12
                    sm:py-16
                    px-5
                    text-[#A5A1B5]
                    text-sm
                    text-center
                "
            >

                <i
                    class="
                        fa-solid
                        fa-box-open
                        text-2xl
                        sm:text-3xl
                        mb-3
                    "
                ></i>

                <span>
                    No orders found.
                </span>

            </div>
        `;

    renderPagination();

    return;
  }

  // =======================================================
  // PAGINATION CALCULATION
  // =======================================================

  const totalPages = Math.max(1, Math.ceil(allOrders.length / ORDERS_PER_PAGE));

  if (currentPage > totalPages) {
    currentPage = totalPages;
  }

  const startIndex = (currentPage - 1) * ORDERS_PER_PAGE;

  const endIndex = startIndex + ORDERS_PER_PAGE;

  const currentOrders = allOrders.slice(startIndex, endIndex);

  // =======================================================
  // CREATE ORDER ROWS
  // =======================================================

  currentOrders.forEach((order) => {
    ordersList.innerHTML += createOrderRow(order);
  });

  // =======================================================
  // ATTACH EVENTS
  // =======================================================

  attachOrderListeners();

  // =======================================================
  // PAGINATION
  // =======================================================

  renderPagination();
}

// =========================================================
// CREATE ORDER ROW
// =========================================================

function createOrderRow(order) {
  const orderId = order.id ?? "";

  const playerName = order.player || order.username || "Unknown Player";

  const total = Number(order.total || 0).toFixed(2);

  const status = order.status || "Pending";

  const date = order.date || order.created_at || "";

  const orderColor = order.color || "#7C2CFF";

  const currentStatusClass =
    statusClass[status] || "bg-gray-500/10 text-gray-400";

  return `
        <div
            class="
                order-row
                grid
                grid-cols-[80px_minmax(120px,1.3fr)_70px_90px_90px_40px]
                sm:grid-cols-[110px_minmax(180px,1.3fr)_90px_110px_110px_70px]
                items-center
                gap-2
                px-3
                py-4
                sm:px-5
                border-b
                border-[#26223D]
                hover:bg-[#0D0B1A]
                transition
            "
        >

            <!-- ORDER ID -->

            <span
                class="
                    font-tech
                    font-semibold
                    text-xs
                    sm:text-sm
                    text-[#A855F7]
                "
            >
                #${escapeHTML(orderId)}
            </span>

            <!-- PLAYER -->

            <div
                class="
                    flex
                    items-center
                    gap-2
                    min-w-0
                "
            >

                <div
                    class="
                        w-8
                        h-8
                        sm:w-9
                        sm:h-9
                        shrink-0
                        rounded-full
                        flex
                        items-center
                        justify-center
                        font-tech
                        text-xs
                        font-semibold
                    "
                    style="
                        background-color: ${orderColor}1A;
                        color: ${orderColor};
                    "
                >
                    ${escapeHTML(initials(playerName))}
                </div>

                <span
                    class="
                        min-w-0
                        text-xs
                        sm:text-sm
                        text-[#F5F3FF]
                        truncate
                    "
                >
                    ${escapeHTML(playerName)}
                </span>

            </div>

            <!-- TOTAL -->

            <span
                class="
                    font-tech
                    font-semibold
                    text-xs
                    sm:text-sm
                    text-[#F5F3FF]
                "
            >
                $${total}
            </span>

            <!-- STATUS -->

            <span
                class="
                    inline-flex
                    items-center
                    w-fit
                    px-2
                    sm:px-3
                    py-1
                    rounded-full
                    text-[9px]
                    sm:text-xs
                    font-medium
                    whitespace-nowrap
                    ${currentStatusClass}
                "
            >
                ${escapeHTML(status)}
            </span>

            <!-- DATE -->

            <span
                class="
                    text-[10px]
                    sm:text-xs
                    text-[#A5A1B5]
                    whitespace-nowrap
                "
            >
                ${escapeHTML(date)}
            </span>

            <!-- DELETE -->

            <div
                class="
                    flex
                    justify-end
                "
            >

                <button
                    type="button"
                    class="
                        order-action
                        w-7
                        h-7
                        sm:w-8
                        sm:h-8
                        flex
                        items-center
                        justify-center
                        rounded-lg
                        text-[#6B687A]
                        hover:text-red-400
                        hover:bg-red-500/10
                        transition
                    "
                    data-order-id="${escapeHTML(orderId)}"
                    title="Delete Order"
                >

                    <i
                        class="
                            fa-solid
                            fa-trash-can
                            text-xs
                            sm:text-sm
                        "
                    ></i>

                </button>

            </div>

        </div>
    `;
}

// =========================================================
// PAGINATION
// =========================================================

function renderPagination() {
  if (!ordersPagination) {
    return;
  }

  const totalPages = Math.ceil(allOrders.length / ORDERS_PER_PAGE);

  // =======================================================
  // NO PAGINATION
  // =======================================================

  if (totalPages <= 1) {
    ordersPagination.innerHTML = "";

    return;
  }

  let buttons = "";

  // =======================================================
  // PREVIOUS
  // =======================================================

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
                border-[#24213A]
                text-gray-400
                hover:text-white
                hover:border-[#7C2CFF]
                disabled:opacity-30
                disabled:cursor-not-allowed
                transition
            "
        >

            <i
                class="
                    fa-solid
                    fa-chevron-left
                    text-xs
                "
            ></i>

        </button>
    `;

  // =======================================================
  // PAGE NUMBERS
  // =======================================================

  for (let page = 1; page <= totalPages; page++) {
    const activeClass =
      page === currentPage
        ? `
                    bg-[#7C2CFF]
                    text-white
                    border-[#7C2CFF]
                `
        : `
                    bg-[#090B16]
                    text-gray-400
                    border-[#24213A]
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

  // =======================================================
  // NEXT
  // =======================================================

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
                border-[#24213A]
                text-gray-400
                hover:text-white
                hover:border-[#7C2CFF]
                disabled:opacity-30
                disabled:cursor-not-allowed
                transition
            "
        >

            <i
                class="
                    fa-solid
                    fa-chevron-right
                    text-xs
                "
            ></i>

        </button>
    `;

  ordersPagination.innerHTML = buttons;
}

// =========================================================
// CHANGE PAGE
// =========================================================

function changePage(page) {
  const totalPages = Math.ceil(allOrders.length / ORDERS_PER_PAGE);

  if (page < 1 || page > totalPages) {
    return;
  }

  currentPage = page;

  renderOrders();
}

// =========================================================
// ATTACH ORDER LISTENERS
// =========================================================

function attachOrderListeners() {
  document.querySelectorAll(".order-action").forEach((button) => {
    button.addEventListener("click", () => {
      const orderId = button.getAttribute("data-order-id");

      const order = allOrders.find(
        (item) => String(item.id) === String(orderId),
      );

      // ===================================================
      // ORDER NOT FOUND
      // ===================================================

      if (!order) {
        alert("Order not found.");

        return;
      }

      // ===================================================
      // CONFIRM DELETE
      // ===================================================

      const confirmed = confirm(
        `Are you sure you want to delete order #${order.id}?`,
      );

      if (!confirmed) {
        return;
      }

      // ===================================================
      // DISABLE ROW
      // ===================================================

      const row = button.closest(".order-row");

      if (row) {
        row.classList.add("opacity-50");

        row.style.pointerEvents = "none";
      }

      // ===================================================
      // DELETE ORDER
      // ===================================================

      deleteOrder(orderId, row);
    });
  });
}

// =========================================================
// DELETE ORDER
// =========================================================

async function deleteOrder(orderId, row) {
  try {
    // ===================================================
    // API REQUEST
    // ===================================================

    const response = await fetch(API.delete, {
      method: "POST",

      headers: {
        "Content-Type": "application/json",

        Accept: "application/json",
      },

      body: JSON.stringify({
        id: Number(orderId),
      }),
    });

    // ===================================================
    // READ RESPONSE
    // ===================================================

    const text = await response.text();

    console.log("Delete Order Response:", text);

    // ===================================================
    // PARSE JSON
    // ===================================================

    let data;

    try {
      data = JSON.parse(text);
    } catch {
      throw new Error("Invalid JSON returned from delete-order.php.");
    }

    // ===================================================
    // CHECK RESPONSE
    // ===================================================

    if (!response.ok || !data.success) {
      throw new Error(data.message || "Failed to delete order.");
    }

    // ===================================================
    // REMOVE FROM LOCAL ARRAY
    // ===================================================

    allOrders = allOrders.filter((item) => String(item.id) !== String(orderId));

    // ===================================================
    // FIX CURRENT PAGE
    // ===================================================

    const totalPages = Math.max(
      1,
      Math.ceil(allOrders.length / ORDERS_PER_PAGE),
    );

    if (currentPage > totalPages) {
      currentPage = totalPages;
    }

    // ===================================================
    // RENDER AGAIN
    // ===================================================

    renderOrders();

    console.log("Order deleted successfully:", orderId);
  } catch (error) {
    console.error("Delete Order Error:", error);

    alert(error.message || "Something went wrong while deleting the order.");

    // ===================================================
    // RESTORE ROW
    // ===================================================

    if (row) {
      row.classList.remove("opacity-50");

      row.style.pointerEvents = "auto";
    }
  }
}

// =========================================================
// DOM READY
// =========================================================

document.addEventListener("DOMContentLoaded", () => {
  loadOrders();
});
