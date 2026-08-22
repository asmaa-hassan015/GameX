
// =========================================================
// 1. DASHBOARD ELEMENTS
// =========================================================

const totalPlayers = document.getElementById("totalPlayers");
const totalGames = document.getElementById("totalGames");
const totalOrders = document.getElementById("totalOrders");
const totalRevenue = document.getElementById("totalRevenue");

const playersGrowth = document.getElementById("playersGrowth");
const gamesGrowth = document.getElementById("gamesGrowth");
const ordersGrowth = document.getElementById("ordersGrowth");
const revenueGrowth = document.getElementById("revenueGrowth");

const topGamesContainer = document.getElementById("topGamesContainer");
const recentOrdersTable = document.getElementById("recentOrdersTable");
const ordersPeriod = document.getElementById("ordersPeriod");

// =========================================================
// 2. API CONFIGURATION
// =========================================================

const DASHBOARD_API = "./BACKEND/dashboard.php";

// =========================================================
// 3. FORMAT MONEY
// =========================================================

function formatMoney(value) {
  const number = Number(value) || 0;

  return `$${number.toFixed(2)}`;
}

// =========================================================
// 4. UPDATE GROWTH
// =========================================================

function updateGrowth(element, value) {
  if (!element) {
    return;
  }

  const number = Number(value) || 0;

  const icon = number >= 0 ? "fa-arrow-up" : "fa-arrow-down";

  const iconColor = number >= 0 ? "text-purple-400" : "text-red-400";

  element.innerHTML = `
        <i class="fa-solid ${icon} ${iconColor} text-[9px]"></i>

        ${number}%

        <span class="text-gray-500 font-normal">
            from last week
        </span>
    `;
}

// =========================================================
// 5. UPDATE DASHBOARD
// =========================================================

function updateDashboard(data) {
  if (!data) {
    return;
  }

  // -----------------------------------------------------
  // KPI CARDS
  // -----------------------------------------------------

  if (totalPlayers) {
    totalPlayers.textContent = Number(data.totalPlayers) || 0;
  }

  if (totalGames) {
    totalGames.textContent = Number(data.totalGames) || 0;
  }

  if (totalOrders) {
    totalOrders.textContent = Number(data.totalOrders) || 0;
  }

  if (totalRevenue) {
    totalRevenue.textContent = formatMoney(data.totalRevenue);
  }

  // -----------------------------------------------------
  // GROWTH
  // -----------------------------------------------------

  updateGrowth(playersGrowth, data.playersGrowth);

  updateGrowth(gamesGrowth, data.gamesGrowth);

  updateGrowth(ordersGrowth, data.ordersGrowth);

  updateGrowth(revenueGrowth, data.revenueGrowth);

  // -----------------------------------------------------
  // TOP GAMES
  // -----------------------------------------------------

  renderTopGames(data.topGames || []);

  // -----------------------------------------------------
  // RECENT ORDERS
  // -----------------------------------------------------

  renderRecentOrders(data.recentOrders || []);

  // -----------------------------------------------------
  // ORDERS CHART
  // -----------------------------------------------------

  updateOrdersChart(
    data.ordersChart?.labels || [],
    data.ordersChart?.values || [],
  );
}

// =========================================================
// 6. RENDER TOP GAMES
// =========================================================

function renderTopGames(games) {
  if (!topGamesContainer) {
    return;
  }

  // -----------------------------------------------------
  // EMPTY DATA
  // -----------------------------------------------------

  if (!Array.isArray(games) || games.length === 0) {
    topGamesContainer.innerHTML = `
            <div class="min-h-[220px] flex items-center justify-center">

                <p class="text-xs text-gray-500 font-medium">
                    No games available.
                </p>

            </div>
        `;

    return;
  }

  // -----------------------------------------------------
  // RENDER GAMES
  // -----------------------------------------------------

  topGamesContainer.innerHTML = games
    .slice(0, 5)
    .map((game, index) => {
      return `
                <div
                    class="flex items-center gap-3
                           p-3 rounded-xl
                           bg-[#0b0c19]
                           border border-[#1d1d3b]"
                >

                    <div
                        class="w-8 h-8 rounded-lg
                               bg-purple-600/20
                               text-purple-400
                               flex items-center
                               justify-center
                               text-xs font-bold"
                    >
                        ${index + 1}
                    </div>


                    <div class="flex-1 min-w-0">

                        <p
                            class="text-sm
                                   font-medium
                                   truncate"
                        >
                            ${escapeHTML(game.name || "Unknown Game")}
                        </p>

                        <p
                            class="text-[10px]
                                   text-gray-500"
                        >
                            ${Number(game.orders) || 0} orders
                        </p>

                    </div>


                    <span
                        class="text-xs
                               text-purple-400
                               font-semibold"
                    >
                        ${Number(game.percentage) || 0}%
                    </span>

                </div>
            `;
    })
    .join("");
}

// =========================================================
// 7. RENDER RECENT ORDERS
// =========================================================

function renderRecentOrders(orders) {
  if (!recentOrdersTable) {
    return;
  }

  // -----------------------------------------------------
  // EMPTY DATA
  // -----------------------------------------------------

  if (!Array.isArray(orders) || orders.length === 0) {
    recentOrdersTable.innerHTML = `
            <tr>

                <td
                    colspan="6"
                    class="py-12
                           text-center
                           text-gray-500
                           font-medium"
                >
                    No recent orders found.
                </td>

            </tr>
        `;

    return;
  }

  // -----------------------------------------------------
  // RENDER ORDERS
  // -----------------------------------------------------

  recentOrdersTable.innerHTML = orders
    .slice(0, 10)
    .map((order) => {
      const status = order.status || "Pending";

      let statusClass = "bg-yellow-500/10 text-yellow-400";

      // -------------------------------------------------
      // COMPLETED
      // -------------------------------------------------

      if (status.toLowerCase() === "completed") {
        statusClass = "bg-green-500/10 text-green-400";
      }

      // -------------------------------------------------
      // CANCELLED
      // -------------------------------------------------

      if (
        status.toLowerCase() === "cancelled" ||
        status.toLowerCase() === "canceled"
      ) {
        statusClass = "bg-red-500/10 text-red-400";
      }

      // -------------------------------------------------
      // PROCESSING
      // -------------------------------------------------

      if (status.toLowerCase() === "processing") {
        statusClass = "bg-blue-500/10 text-blue-400";
      }

      // -------------------------------------------------
      // ORDER ROW
      // -------------------------------------------------

      return `
                <tr
                    class="hover:bg-[#111225]
                           transition"
                >

                    <td class="py-4">
                        #${escapeHTML(order.id || "-")}
                    </td>

                    <td class="py-4">
                        ${escapeHTML(order.player || "-")}
                    </td>

                    <td class="py-4">
                        ${escapeHTML(order.game || "-")}
                    </td>

                    <td class="py-4">
                        ${formatMoney(order.amount)}
                    </td>

                    <td class="py-4">

                        <span
                            class="px-2.5 py-1
                                   rounded-full
                                   text-[10px]
                                   font-medium
                                   ${statusClass}"
                        >
                            ${escapeHTML(status)}
                        </span>

                    </td>

                    <td class="py-4 text-gray-400">
                        ${escapeHTML(order.date || "-")}
                    </td>

                </tr>
            `;
    })
    .join("");
}

// =========================================================
// 8. CHART SETUP
// =========================================================

let ordersChart = null;

// =========================================================
// 9. CREATE ORDERS CHART
// =========================================================

function createOrdersChart() {
  const canvas = document.getElementById("ordersChart");

  if (!canvas) {
    return;
  }

  const ctx = canvas.getContext("2d");

  ordersChart = new Chart(ctx, {
    type: "line",

    data: {
      labels: [],

      datasets: [
        {
          label: "Orders",

          data: [],

          borderColor: "#8b5cf6",

          backgroundColor: "rgba(139, 92, 246, 0.10)",

          borderWidth: 3,

          pointBackgroundColor: "#8b5cf6",

          pointBorderColor: "#ffffff",

          pointRadius: 4,

          fill: true,

          tension: 0.35,
        },
      ],
    },

    options: {
      responsive: true,

      maintainAspectRatio: false,

      // -------------------------------------------------
      // PLUGINS
      // -------------------------------------------------

      plugins: {
        legend: {
          display: false,
        },
      },

      // -------------------------------------------------
      // AXES
      // -------------------------------------------------

      scales: {
        x: {
          grid: {
            display: false,
          },

          ticks: {
            color: "#6b7280",

            font: {
              size: 11,
            },
          },
        },

        y: {
          beginAtZero: true,

          ticks: {
            color: "#6b7280",

            font: {
              size: 11,
            },

            precision: 0,
          },

          grid: {
            color: "#1a1c38",
          },
        },
      },
    },
  });
}

// =========================================================
// 10. UPDATE ORDERS CHART
// =========================================================

function updateOrdersChart(labels, values) {
  if (!ordersChart) {
    return;
  }

  ordersChart.data.labels = Array.isArray(labels) ? labels : [];

  ordersChart.data.datasets[0].data = Array.isArray(values) ? values : [];

  ordersChart.update();
}

// =========================================================
// 11. LOAD DASHBOARD DATA
// =========================================================

async function loadDashboardData(period = "week") {
  try {
    // -----------------------------------------------------
    // LOADING STATE
    // -----------------------------------------------------

    if (totalPlayers) {
      totalPlayers.textContent = "...";
    }

    if (totalGames) {
      totalGames.textContent = "...";
    }

    if (totalOrders) {
      totalOrders.textContent = "...";
    }

    if (totalRevenue) {
      totalRevenue.textContent = "...";
    }

    // -----------------------------------------------------
    // API REQUEST
    // -----------------------------------------------------

    const response = await fetch(
      `${DASHBOARD_API}?period=${encodeURIComponent(period)}`,
      {
        method: "GET",

        headers: {
          Accept: "application/json",
        },

        cache: "no-store",
      },
    );

    // -----------------------------------------------------
    // HTTP ERROR
    // -----------------------------------------------------

    if (!response.ok) {
      throw new Error(`HTTP Error: ${response.status}`);
    }

    // -----------------------------------------------------
    // READ RESPONSE
    // -----------------------------------------------------

    const data = await response.json();

    // -----------------------------------------------------
    // API ERROR
    // -----------------------------------------------------

    if (!data.success) {
      throw new Error(data.message || "Dashboard data could not be loaded.");
    }

    // -----------------------------------------------------
    // UPDATE DASHBOARD
    // -----------------------------------------------------

    updateDashboard(data);

    console.log("Dashboard data loaded:", data);
  } catch (error) {
    console.error("Dashboard Error:", error);

    // -----------------------------------------------------
    // RESET KPI CARDS
    // -----------------------------------------------------

    if (totalPlayers) {
      totalPlayers.textContent = "0";
    }

    if (totalGames) {
      totalGames.textContent = "0";
    }

    if (totalOrders) {
      totalOrders.textContent = "0";
    }

    if (totalRevenue) {
      totalRevenue.textContent = "$0.00";
    }

    // -----------------------------------------------------
    // TOP GAMES ERROR
    // -----------------------------------------------------

    if (topGamesContainer) {
      topGamesContainer.innerHTML = `
                <div
                    class="min-h-[220px]
                           flex items-center
                           justify-center"
                >

                    <p class="text-xs text-red-400">
                        Failed to load dashboard data.
                    </p>

                </div>
            `;
    }

    // -----------------------------------------------------
    // ORDERS ERROR
    // -----------------------------------------------------

    if (recentOrdersTable) {
      recentOrdersTable.innerHTML = `
                <tr>

                    <td
                        colspan="6"
                        class="py-12
                               text-center
                               text-red-400"
                    >
                        Failed to load orders.
                    </td>

                </tr>
            `;
    }
  }
}

// =========================================================
// 12. PERIOD CHANGE
// =========================================================

if (ordersPeriod) {
  ordersPeriod.addEventListener("change", function () {
    loadDashboardData(this.value);
  });
}

// =========================================================
// 13. HTML ESCAPE
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
// 14. INITIALIZE DASHBOARD
// =========================================================

document.addEventListener("DOMContentLoaded", function () {
  createOrdersChart();

  loadDashboardData("week");
});

// =========================================================
// END ADMIN DASHBOARD
// =========================================================
