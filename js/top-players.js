
let topPlayersData = [];

let currentPeriod = "global";

let currentPage = 1;

const PLAYERS_PER_PAGE = 3;

const podiumEl = document.getElementById("podium");

const tableBody = document.getElementById("tableBody");

const paginationEl = document.getElementById("pagination");

const tabs = document.querySelectorAll(".tab-btn");

// =========================================================
// API URL
// =========================================================

const API_URL = "/GameX/BACKEND/top-player.php";

// =========================================================
// MODAL ELEMENTS
// =========================================================

const modal = document.getElementById("modal");

const closeModalBtn = document.getElementById("closeModal");

const modalGameImg = document.getElementById("modalGameImg");

const modalGameTitle = document.getElementById("modalGameTitle");

const modalGenre = document.getElementById("modalGenre");

const modalPrice = document.getElementById("modalPrice");

const modalAvatar = document.getElementById("modalAvatar");

const modalPlayer = document.getElementById("modalPlayer");

const modalCount = document.getElementById("modalCount");

const modalSpent = document.getElementById("modalSpent");

// =========================================================
// DEFAULT IMAGES
// =========================================================

const DEFAULT_AVATAR = "/GameX/src/Images/avatars/default.png";

const DEFAULT_GAME_IMAGE = "";

// =========================================================
// FORMAT PRICE
// =========================================================

function fmtPrice(value) {
  const number = Number(value);

  if (Number.isNaN(number)) {
    return "$0.00";
  }

  return (
    "$" +
    number.toLocaleString("en-US", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    })
  );
}

// =========================================================
// MEDAL
// =========================================================

function medalIcon(rank) {
  if (rank === 1) {
    return `<i class="fa-solid fa-crown text-[#F0AE2D]"></i>`;
  }

  if (rank === 2) {
    return `<i class="fa-solid fa-medal text-[#A5A1B5]"></i>`;
  }

  return `<i class="fa-solid fa-medal text-[#B87333]"></i>`;
}

// =========================================================
// ESCAPE HTML
// =========================================================

function escapeHtml(value) {
  return String(value ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

// =========================================================
// SAFE IMAGE
// =========================================================

function safeImage(src, fallback = DEFAULT_AVATAR) {
  if (!src || String(src).trim() === "") {
    return fallback;
  }

  return src;
}

// =========================================================
// LOAD TOP PLAYERS
// =========================================================

async function loadTopPlayers(period = "global") {
  try {
    currentPeriod = period;

    currentPage = 1;

    const response = await fetch(
      `${API_URL}?period=${encodeURIComponent(period)}`,
      {
        method: "GET",
        headers: {
          Accept: "application/json",
        },
      },
    );

    if (!response.ok) {
      throw new Error(`HTTP ${response.status}`);
    }

    const data = await response.json();

    console.log("Top Players API:", data);

    if (!data.success) {
      throw new Error(data.message || "Failed to load top players.");
    }

    topPlayersData = Array.isArray(data.players) ? data.players : [];

    console.log("Total Players:", topPlayersData.length);

    renderTopPlayers(period);
  } catch (error) {
    console.error("Top Players API Error:", error);

    topPlayersData = [];

    renderEmptyState();
  }
}

// =========================================================
// RANK PLAYERS
// =========================================================

function rankPlayers(period) {
  return [...topPlayersData]
    .map((player) => ({
      ...player,

      stats: player.stats?.[period] || {
        purchases: 0,
        spent: 0,
      },
    }))
    .sort((a, b) => {
      const spentA = Number(a.stats?.spent || 0);

      const spentB = Number(b.stats?.spent || 0);

      if (spentB !== spentA) {
        return spentB - spentA;
      }

      const purchasesA = Number(a.stats?.purchases || 0);

      const purchasesB = Number(b.stats?.purchases || 0);

      if (purchasesB !== purchasesA) {
        return purchasesB - purchasesA;
      }

      return Number(a.id || 0) - Number(b.id || 0);
    });
}

// =========================================================
// EMPTY STATE
// =========================================================

function renderEmptyState() {
  if (podiumEl) {
    podiumEl.innerHTML = `
            <div
                class="col-span-3 flex flex-col items-center justify-center py-10"
            >
                <i
                    class="fa-solid fa-users text-3xl text-[#6B687A] mb-3"
                ></i>

                <p
                    class="font-[Rajdhani] text-sm text-[#A5A1B5]"
                >
                    No players available yet.
                </p>
            </div>
        `;
  }

  if (tableBody) {
    tableBody.innerHTML = `
            <tr>
                <td
                    colspan="6"
                    class="py-12 text-center text-[#A5A1B5] font-[Rajdhani]"
                >
                    No players available yet.
                </td>
            </tr>
        `;
  }

  if (paginationEl) {
    paginationEl.innerHTML = "";
  }
}

// =========================================================
// RENDER PODIUM
// =========================================================

function renderPodium(ranked) {
  if (!podiumEl) {
    return;
  }

  const top3 = ranked.slice(0, 3);

  if (top3.length === 0) {
    renderEmptyState();
    return;
  }

  // =====================================================
  // LESS THAN 3 PLAYERS
  // =====================================================

  if (top3.length < 3) {
    podiumEl.innerHTML = top3
      .map((player, i) => {
        const rank = i + 1;

        const avatar = safeImage(player.avatar, DEFAULT_AVATAR);

        return `
                    <div
                        class="
                            flex
                            flex-col
                            items-center
                            player-row-fade
                        "
                    >

                        <div class="relative mb-2">

                            <div
                                class="
                                    absolute
                                    -top-2
                                    -right-1
                                    w-6
                                    h-6
                                    rounded-full
                                    flex
                                    items-center
                                    justify-center
                                    text-[11px]
                                    font-bold
                                    ${
                                      rank === 1
                                        ? "bg-[#F0AE2D] text-[#070612]"
                                        : rank === 2
                                          ? "bg-[#A5A1B5] text-[#070612]"
                                          : "bg-[#B87333] text-white"
                                    }
                                "
                            >
                                ${rank}
                            </div>

                            <img
                                src="${escapeHtml(avatar)}"
                                alt="${escapeHtml(player.name || "")}"
                                class="
                                    w-16
                                    h-16
                                    sm:w-20
                                    sm:h-20
                                    rounded-full
                                    object-cover
                                    ${
                                      rank === 1
                                        ? "player-avatar-gold"
                                        : "player-avatar"
                                    }
                                "
                                onerror="this.src='${DEFAULT_AVATAR}'"
                            />

                        </div>

                        <p
                            class="
                                font-[Poppins]
                                font-bold
                                text-xs
                                sm:text-base
                                text-center
                            "
                        >
                            ${escapeHtml(player.name || "")}
                        </p>

                        <div
                            class="
                                font-[Rajdhani]
                                text-[10px]
                                sm:text-xs
                                mt-1.5
                                px-2.5
                                py-0.5
                                rounded-full
                                bg-[#3D1398]/40
                                border
                                border-[#AD30E0]/50
                                text-[#F5F3FF]
                            "
                        >
                            ${Number(player.stats.purchases || 0)}
                            Games Purchased
                        </div>

                        <div
                            class="
                                font-[Rajdhani]
                                text-[11px]
                                sm:text-xs
                                mt-1.5
                                flex
                                items-center
                                gap-1
                                text-[#F0AE2D]
                                font-semibold
                            "
                        >
                            <i
                                class="fa-solid fa-cart-shopping text-[9px]"
                            ></i>

                            ${fmtPrice(player.stats.spent)}
                        </div>

                        <p
                            class="
                                font-[Rajdhani]
                                text-[9px]
                                text-[#A5A1B5]
                                mt-0.5
                            "
                        >
                            Total Spent
                        </p>

                    </div>
                `;
      })
      .join("");

    return;
  }

  // =====================================================
  // NORMAL TOP 3
  // =====================================================

  const order = [top3[1], top3[0], top3[2]];

  const baseHeights = ["h-8 sm:h-12", "h-14 sm:h-20", "h-6 sm:h-9"];

  const baseClasses = ["podium-silver", "podium-gold", "podium-bronze"];

  const rankNums = [2, 1, 3];

  podiumEl.innerHTML = order
    .map((player, i) => {
      const rank = rankNums[i];

      const isFirst = rank === 1;

      const avatar = safeImage(player.avatar, DEFAULT_AVATAR);

      return `
                <div
                    class="
                        flex
                        flex-col
                        items-center
                        ${isFirst ? "-mt-4 sm:-mt-8" : ""}
                        player-row-fade
                    "
                    style="animation-delay:${i * 60}ms"
                >

                    <!-- AVATAR -->

                    <div class="relative mb-2">

                        <!-- RANK -->

                        <div
                            class="
                                absolute
                                -top-2
                                -right-1
                                w-6
                                h-6
                                rounded-full
                                flex
                                items-center
                                justify-center
                                text-[11px]
                                font-bold
                                ${
                                  isFirst
                                    ? "bg-[#F0AE2D] text-[#070612]"
                                    : rank === 2
                                      ? "bg-[#A5A1B5] text-[#070612]"
                                      : "bg-[#B87333] text-white"
                                }
                            "
                        >
                            ${rank}
                        </div>


                        <!-- CROWN -->

                        ${
                          isFirst
                            ? `
                                    <div
                                        class="
                                            absolute
                                            -top-6
                                            left-1/2
                                            -translate-x-1/2
                                            text-[#F0AE2D]
                                            text-base
                                        "
                                    >
                                        ${medalIcon(1)}
                                    </div>
                                `
                            : ""
                        }


                        <!-- PLAYER AVATAR -->

                        <img
                            src="${escapeHtml(avatar)}"
                            alt="${escapeHtml(player.name || "")}"
                            class="
                                ${
                                  isFirst
                                    ? "w-16 h-16 sm:w-20 sm:h-20"
                                    : "w-12 h-12 sm:w-16 sm:h-16"
                                }
                                rounded-full
                                object-cover
                                ${
                                  isFirst
                                    ? "player-avatar-gold"
                                    : "player-avatar"
                                }
                            "
                            onerror="this.src='${DEFAULT_AVATAR}'"
                        />

                    </div>


                    <!-- PLAYER NAME -->

                    <p
                        class="
                            font-[Poppins]
                            font-bold
                            text-xs
                            sm:text-base
                            text-center
                        "
                    >
                        ${escapeHtml(player.name || "")}
                    </p>


                    <!-- PURCHASES -->

                    <div
                        class="
                            font-[Rajdhani]
                            text-[10px]
                            sm:text-xs
                            mt-1.5
                            px-2.5
                            py-0.5
                            rounded-full
                            bg-[#3D1398]/40
                            border
                            border-[#AD30E0]/50
                            text-[#F5F3FF]
                        "
                    >
                        ${Number(player.stats.purchases || 0)}
                        Games Purchased
                    </div>


                    <!-- TOTAL SPENT -->

                    <div
                        class="
                            font-[Rajdhani]
                            text-[11px]
                            sm:text-xs
                            mt-1.5
                            flex
                            items-center
                            gap-1
                            text-[#F0AE2D]
                            font-semibold
                        "
                    >
                        <i
                            class="fa-solid fa-cart-shopping text-[9px]"
                        ></i>

                        ${fmtPrice(player.stats.spent)}
                    </div>

                    <p
                        class="
                            font-[Rajdhani]
                            text-[9px]
                            text-[#A5A1B5]
                            mt-0.5
                        "
                    >
                        Total Spent
                    </p>


                    <!-- PODIUM BASE -->

                    <div
                        class="
                            w-full
                            ${baseHeights[i]}
                            mt-3
                            rounded-t-xl
                            ${baseClasses[i]}
                        "
                    ></div>

                </div>
            `;
    })
    .join("");
}

// =========================================================
// RENDER TABLE
// =========================================================

function renderTable(ranked) {
  if (!tableBody) {
    return;
  }

  const rest = ranked.slice(3);

  if (rest.length === 0) {
    tableBody.innerHTML = "";

    if (ranked.length < 3) {
      tableBody.innerHTML = `
                <tr>
                    <td
                        colspan="6"
                        class="
                            py-12
                            text-center
                            text-[#A5A1B5]
                            font-[Rajdhani]
                        "
                    >
                        No additional players available.
                    </td>
                </tr>
            `;
    }

    if (paginationEl) {
      paginationEl.innerHTML = "";
    }

    return;
  }

  // =====================================================
  // PAGINATION CALCULATION
  // =====================================================

  const totalPages = Math.ceil(rest.length / PLAYERS_PER_PAGE);

  if (currentPage > totalPages) {
    currentPage = totalPages;
  }

  const startIndex = (currentPage - 1) * PLAYERS_PER_PAGE;

  const endIndex = startIndex + PLAYERS_PER_PAGE;

  const pagePlayers = rest.slice(startIndex, endIndex);

  // =====================================================
  // RENDER CURRENT PAGE
  // =====================================================

  tableBody.innerHTML = pagePlayers
    .map((player, i) => {
      const rank = startIndex + i + 4;

      const game = player.game || {};

      const avatar = safeImage(player.avatar, DEFAULT_AVATAR);

      const gameImage = safeImage(game.img, DEFAULT_GAME_IMAGE);

      return `
                <tr
                    class="
                        border-b
                        border-[#26223D]/70
                        last:border-0
                        hover:bg-[#0D0B1A]/60
                        transition
                        player-row-fade
                    "
                    style="animation-delay:${i * 40}ms"
                >

                    <!-- RANK -->

                    <td
                        class="
                            py-3
                            px-3
                            sm:px-5
                            font-[Rajdhani]
                            font-semibold
                            text-[#A5A1B5]
                            text-sm
                        "
                    >
                        ${rank}
                    </td>


                    <!-- PLAYER -->

                    <td class="py-3 px-3 sm:px-5">

                        <div
                            class="flex items-center gap-2.5"
                        >

                            <img
                                src="${escapeHtml(avatar)}"
                                alt="${escapeHtml(player.name || "")}"
                                class="
                                    w-7
                                    h-7
                                    rounded-full
                                    object-cover
                                    border
                                    border-[#26223D]
                                "
                                onerror="this.src='${DEFAULT_AVATAR}'"
                            />

                            <span
                                class="
                                    font-semibold
                                    text-xs
                                    sm:text-sm
                                "
                            >
                                ${escapeHtml(player.name || "")}
                            </span>

                        </div>

                    </td>


                    <!-- GAME -->

                    <td class="py-3 px-3 sm:px-5">

                        <div
                            class="flex items-center gap-2"
                        >

                            ${
                              gameImage
                                ? `
                                        <img
                                            src="${escapeHtml(gameImage)}"
                                            alt="${escapeHtml(game.title || "")}"
                                            class="
                                                w-6
                                                h-8
                                                rounded
                                                object-cover
                                                border
                                                border-[#26223D]
                                            "
                                            onerror="this.style.display='none'"
                                        />
                                    `
                                : ""
                            }

                            <span
                                class="
                                    text-xs
                                    sm:text-sm
                                    text-[#F5F3FF]
                                "
                            >
                                ${escapeHtml(game.title || "No Game")}
                            </span>

                        </div>

                    </td>


                    <!-- GENRE -->

                    <td
                        class="
                            py-3
                            px-3
                            sm:px-5
                            text-xs
                            sm:text-sm
                            text-[#A5A1B5]
                        "
                    >
                        ${escapeHtml(game.genre || "-")}
                    </td>


                    <!-- PRICE -->

                    <td
                        class="
                            py-3
                            px-3
                            sm:px-5
                            font-[Rajdhani]
                            font-semibold
                            text-sm
                        "
                    >
                        ${fmtPrice(game.price)}
                    </td>


                    <!-- DETAILS -->

                    <td
                        class="
                            py-3
                            px-3
                            sm:px-5
                            text-right
                        "
                    >

                        <button
                            type="button"
                            class="
                                viewDetailsBtn
                                font-[Rajdhani]
                                text-[11px]
                                sm:text-xs
                                font-semibold
                                px-3
                                py-1
                                rounded-full
                                border
                                border-[#AD30E0]
                                text-[#AD30E0]
                                hover:bg-[#AD30E0]
                                hover:text-[#070612]
                                transition
                            "
                            data-player-id="${escapeHtml(player.id || "")}"
                        >
                            View Details
                        </button>

                    </td>

                </tr>
            `;
    })
    .join("");

  // =====================================================
  // RENDER PAGINATION
  // =====================================================

  renderPagination(totalPages);

  // =====================================================
  // DETAILS BUTTONS
  // =====================================================

  attachDetailListeners(pagePlayers);
}

// =========================================================
// PAGINATION
// =========================================================

function renderPagination(totalPages) {
  if (!paginationEl) {
    return;
  }

  if (totalPages <= 1) {
    paginationEl.innerHTML = "";
    return;
  }

  let html = "";

  // =====================================================
  // PREVIOUS BUTTON
  // =====================================================

  html += `
        <button
            type="button"
            class="
                pagination-btn
                px-3
                py-1.5
                rounded-lg
                border
                border-[#3D1398]
                text-[#A5A1B5]
                hover:text-white
                hover:bg-[#3D1398]
                transition
                ${currentPage === 1 ? "opacity-40 cursor-not-allowed" : ""}
            "
            data-page="${currentPage - 1}"
            ${currentPage === 1 ? "disabled" : ""}
        >
            <i class="fa-solid fa-chevron-left"></i>
        </button>
    `;

  // =====================================================
  // PAGE NUMBERS
  // =====================================================

  for (let page = 1; page <= totalPages; page++) {
    html += `
            <button
                type="button"
                class="
                    pagination-btn
                    px-3
                    py-1.5
                    rounded-lg
                    border
                    transition
                    ${
                      page === currentPage
                        ? "bg-gradient-to-r from-[#3D1398] to-[#AD30E0] text-white border-[#AD30E0]"
                        : "border-[#26223D] text-[#A5A1B5] hover:text-white hover:border-[#AD30E0]"
                    }
                "
                data-page="${page}"
            >
                ${page}
            </button>
        `;
  }

  // =====================================================
  // NEXT BUTTON
  // =====================================================

  html += `
        <button
            type="button"
            class="
                pagination-btn
                px-3
                py-1.5
                rounded-lg
                border
                border-[#3D1398]
                text-[#A5A1B5]
                hover:text-white
                hover:bg-[#3D1398]
                transition
                ${
                  currentPage === totalPages
                    ? "opacity-40 cursor-not-allowed"
                    : ""
                }
            "
            data-page="${currentPage + 1}"
            ${currentPage === totalPages ? "disabled" : ""}
        >
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    `;

  paginationEl.innerHTML = html;

  // =====================================================
  // PAGINATION EVENTS
  // =====================================================

  paginationEl.querySelectorAll(".pagination-btn").forEach((button) => {
    button.addEventListener("click", () => {
      const page = Number(button.dataset.page);

      if (page < 1 || page > totalPages || page === currentPage) {
        return;
      }

      currentPage = page;

      const ranked = rankPlayers(currentPeriod);

      renderTable(ranked);

      tableBody?.scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    });
  });
}

// =========================================================
// RENDER TOP PLAYERS
// =========================================================

function renderTopPlayers(period = "global") {
  const ranked = rankPlayers(period);

  console.log("Ranked Players:", ranked);

  if (ranked.length === 0) {
    renderEmptyState();
    return;
  }

  // TOP 3
  renderPodium(ranked);

  // REST OF PLAYERS
  renderTable(ranked);
}

// =========================================================
// OPEN PLAYER DETAILS
// =========================================================

function openPlayerDetails(player) {
  if (!modal || !player) {
    return;
  }

  const game = player.game || {};

  const stats = player.stats || {
    purchases: 0,
    spent: 0,
  };

  // =====================================================
  // GAME DETAILS
  // =====================================================

  if (modalGameTitle) {
    modalGameTitle.textContent = game.title || "No Game";
  }

  if (modalGenre) {
    modalGenre.textContent = game.genre || "Unknown Genre";
  }

  if (modalPrice) {
    modalPrice.textContent = fmtPrice(game.price);
  }

  // =====================================================
  // GAME IMAGE
  // =====================================================

  if (modalGameImg) {
    if (game.img) {
      modalGameImg.src = game.img;

      modalGameImg.style.display = "block";
    } else {
      modalGameImg.removeAttribute("src");

      modalGameImg.style.display = "none";
    }

    modalGameImg.alt = game.title || "Game";
  }

  // =====================================================
  // PLAYER
  // =====================================================

  if (modalPlayer) {
    modalPlayer.textContent = player.name || "Unknown Player";
  }

  // =====================================================
  // AVATAR
  // =====================================================

  if (modalAvatar) {
    modalAvatar.src = safeImage(player.avatar);

    modalAvatar.alt = player.name || "Player";

    modalAvatar.onerror = function () {
      this.src = DEFAULT_AVATAR;
    };
  }

  // =====================================================
  // STATISTICS
  // =====================================================

  if (modalCount) {
    modalCount.textContent = Number(stats.purchases || 0);
  }

  if (modalSpent) {
    modalSpent.textContent = fmtPrice(stats.spent);
  }

  // =====================================================
  // SHOW MODAL
  // =====================================================

  modal.classList.remove("hidden");

  modal.classList.add("flex");

  document.body.classList.add("overflow-hidden");
}

// =========================================================
// CLOSE PLAYER DETAILS
// =========================================================

function closePlayerDetails() {
  if (!modal) {
    return;
  }

  modal.classList.add("hidden");

  modal.classList.remove("flex");

  document.body.classList.remove("overflow-hidden");
}

// =========================================================
// DETAIL BUTTONS
// =========================================================

function attachDetailListeners(players) {
  document.querySelectorAll(".viewDetailsBtn").forEach((button) => {
    button.addEventListener("click", () => {
      const playerId = button.dataset.playerId;

      let player;

      // =====================================================
      // FIND PLAYER BY ID
      // =====================================================

      if (playerId) {
        player = players.find((item) => String(item.id) === String(playerId));
      }

      if (!player) {
        console.error("Player not found:", playerId);

        return;
      }

      console.log("Player Details:", player);

      openPlayerDetails(player);
    });
  });
}

// =========================================================
// CLOSE MODAL BUTTON
// =========================================================

if (closeModalBtn) {
  closeModalBtn.addEventListener("click", closePlayerDetails);
}

// =========================================================
// CLOSE WHEN CLICK OUTSIDE MODAL
// =========================================================

if (modal) {
  modal.addEventListener("click", (event) => {
    if (event.target === modal) {
      closePlayerDetails();
    }
  });
}

// =========================================================
// CLOSE WITH ESC
// =========================================================

document.addEventListener("keydown", (event) => {
  if (event.key === "Escape" && modal && !modal.classList.contains("hidden")) {
    closePlayerDetails();
  }
});

// =========================================================
// PERIOD TABS
// =========================================================

tabs.forEach((tab) => {
  tab.addEventListener("click", () => {
    tabs.forEach((item) => {
      item.classList.remove(
        "bg-gradient-to-r",
        "from-[#3D1398]",
        "to-[#AD30E0]",
        "text-white",
        "shadow-[0_0_20px_rgba(173,48,224,0.5)]",
      );

      item.classList.add("text-[#A5A1B5]");
    });

    tab.classList.add(
      "bg-gradient-to-r",
      "from-[#3D1398]",
      "to-[#AD30E0]",
      "text-white",
      "shadow-[0_0_20px_rgba(173,48,224,0.5)]",
    );

    tab.classList.remove("text-[#A5A1B5]");

    const period = tab.dataset.period || "global";

    loadTopPlayers(period);
  });
});

// =========================================================
// INITIAL LOAD
// =========================================================

if (podiumEl || tableBody) {
  loadTopPlayers("global");
}
