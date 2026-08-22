// =========================================================
// GAME X - MANAGE PLAYERS
// =========================================================

// =========================================================
// API
// =========================================================

const API = {
  get: "/GameX/BACKEND/manage-players/players.php",
  update: "/GameX/BACKEND/manage-players/update-player.php",
  delete: "/GameX/BACKEND/manage-players/delete-player.php",
};

// =========================================================
// GLOBAL
// =========================================================

let allPlayers = [];
let currentPage = 1;

const playersPerPage = 6;

// =========================================================
// DOM
// =========================================================

const playersTableBody = document.getElementById("playersTableBody");
const playersMessage = document.getElementById("playersMessage");
const playersCount = document.getElementById("playersCount");
const playersPagination = document.getElementById("pagination");

// =========================================================
// LOAD PLAYERS
// =========================================================

async function loadPlayers() {
  try {
    console.log("Fetching:", API.get);

    showLoading();

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

    console.log("Players API Response:", text);

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
      throw new Error(data.message || "Failed to load players.");
    }

    // =====================================================
    // SAVE DATA
    // =====================================================

    allPlayers = Array.isArray(data.players) ? data.players : [];

    currentPage = 1;

    // =====================================================
    // RENDER
    // =====================================================

    renderPlayers();
  } catch (error) {
    console.error("Load Players Error:", error);

    if (playersTableBody) {
      playersTableBody.innerHTML = "";
    }

    if (playersCount) {
      playersCount.textContent = "0";
    }

    if (playersMessage) {
      playersMessage.classList.remove("hidden");
      playersMessage.classList.add("text-red-400");
      playersMessage.textContent = error.message || "Failed to load players.";
    }

    if (playersPagination) {
      playersPagination.innerHTML = "";
    }
  }
}

// =========================================================
// LOADING
// =========================================================

function showLoading() {
  if (playersTableBody) {
    playersTableBody.innerHTML = "";
  }

  if (playersMessage) {
    playersMessage.classList.remove("hidden");
    playersMessage.classList.remove("text-red-400");

    playersMessage.textContent = "Loading players...";
  }
}

// =========================================================
// RENDER PLAYERS
// =========================================================

function renderPlayers() {
  if (!playersTableBody) {
    return;
  }

  playersTableBody.innerHTML = "";

  // =====================================================
  // UPDATE PLAYERS COUNT
  // =====================================================

  if (playersCount) {
    playersCount.textContent = allPlayers.length;
  }

  // =====================================================
  // EMPTY STATE
  // =====================================================

  if (allPlayers.length === 0) {
    if (playersMessage) {
      playersMessage.classList.remove("hidden");
      playersMessage.classList.remove("text-red-400");

      playersMessage.textContent = "No players found.";
    }

    renderPagination();

    return;
  }

  // =====================================================
  // HIDE MESSAGE
  // =====================================================

  if (playersMessage) {
    playersMessage.classList.add("hidden");
    playersMessage.classList.remove("text-red-400");
  }

  // =====================================================
  // PAGINATION
  // =====================================================

  const startIndex = (currentPage - 1) * playersPerPage;

  const endIndex = startIndex + playersPerPage;

  const currentPlayers = allPlayers.slice(startIndex, endIndex);

  // =====================================================
  // CREATE ROWS
  // =====================================================

  currentPlayers.forEach((player) => {
    playersTableBody.appendChild(createPlayerRow(player));
  });

  // =====================================================
  // INITIALIZE DELETE BUTTONS
  // =====================================================

  initializeDeleteButtons();

  // =====================================================
  // RENDER PAGINATION
  // =====================================================

  renderPagination();
}

// =========================================================
// CREATE PLAYER ROW
// =========================================================

function createPlayerRow(player) {
  const row = document.createElement("tr");

  row.className = `
        hover:bg-[#0d0f1c]
        transition
    `;

  // =====================================================
  // PLAYER DATA
  // =====================================================

  const playerId = Number(player.id);

  const playerName = player.name || "Unknown Player";

  const email = player.email || "-";

  const avatar = player.avatar || "";

  const joined = formatDate(player.joined);

  const status = player.status === "active" ? "active" : "blocked";

  // =====================================================
  // STATUS STYLE
  // =====================================================

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

  const statusText = status === "active" ? "Active" : "Blocked";

  // =====================================================
  // ROW HTML
  // =====================================================

  row.innerHTML = `
        <!-- PLAYER -->

        <td class="p-4">

            <div class="flex items-center gap-3">

                <!-- AVATAR -->

                <div
                    class="
                        w-10
                        h-10
                        rounded-full
                        bg-[#7c2cff]/20
                        text-[#a855f7]
                        flex
                        items-center
                        justify-center
                        overflow-hidden
                        shrink-0
                    "
                >

                    ${
                      avatar
                        ? `
                                <img
                                    src="${escapeHTML(avatar)}"
                                    alt="${escapeHTML(playerName)}"
                                    class="
                                        w-full
                                        h-full
                                        object-cover
                                    "
                                    onerror="
                                        this.onerror=null;
                                        this.style.display='none';
                                    "
                                >
                            `
                        : `
                                <i
                                    class="
                                        fa-solid
                                        fa-user
                                    "
                                ></i>
                            `
                    }

                </div>

                <!-- PLAYER INFO -->

                <div class="min-w-0">

                    <p
                        class="
                            font-semibold
                            text-white
                            truncate
                        "
                    >
                        ${escapeHTML(playerName)}
                    </p>

                    <p
                        class="
                            text-xs
                            text-gray-500
                            mt-0.5
                        "
                    >
                        ID: ${escapeHTML(String(playerId))}
                    </p>

                </div>

            </div>

        </td>

        <!-- EMAIL -->

        <td
            class="
                p-4
                text-gray-300
                text-sm
            "
        >
            ${escapeHTML(email)}
        </td>

        <!-- JOINED -->

        <td
            class="
                p-4
                text-gray-400
                text-sm
            "
        >
            ${escapeHTML(joined)}
        </td>

        <!-- STATUS -->

        <td class="p-4">

            <div
                class="
                    relative
                    inline-block
                "
            >

                <button
                    type="button"
                    onclick="
                        togglePlayerStatusMenu(
                            ${playerId},
                            event
                        )
                    "
                    title="Change status"
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

                    <i
                        class="
                            fa-solid
                            fa-chevron-down
                            text-[9px]
                        "
                    ></i>

                </button>

                <!-- STATUS MENU -->

                <div
                    id="playerStatusMenu-${playerId}"
                    class="
                        status-menu
                        hidden
                        absolute
                        top-full
                        mt-1.5
                        left-0
                        w-28
                        bg-[#0D0B1A]
                        border
                        border-[#24213a]
                        rounded-lg
                        overflow-hidden
                        shadow-lg
                        z-20
                    "
                >

                    <!-- ACTIVE -->

                    <button
                        type="button"
                        onclick="
                            selectPlayerStatus(
                                ${playerId},
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

                    <!-- BLOCKED -->

                    <button
                        type="button"
                        onclick="
                            selectPlayerStatus(
                                ${playerId},
                                'blocked'
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
                        Blocked
                    </button>

                </div>

            </div>

        </td>

        <!-- ACTIONS -->

        <td class="p-4">

            <div
                class="
                    flex
                    justify-center
                    gap-2
                "
            >

                <!-- DELETE -->

                <button
                    type="button"
                    class="
                        delete-player-btn
                        h-9
                        w-9
                        rounded-lg
                        border
                        border-red-500/20
                        text-red-400
                        hover:bg-red-500/10
                        transition
                    "
                    data-player-id="${playerId}"
                    title="Delete Player"
                >

                    <i
                        class="
                            fa-solid
                            fa-trash
                        "
                    ></i>

                </button>

            </div>

        </td>
    `;

  return row;
}

// =========================================================
// PAGINATION
// =========================================================

function renderPagination() {
  if (!playersPagination) {
    return;
  }

  const totalPages = Math.ceil(allPlayers.length / playersPerPage);

  // =====================================================
  // NO PAGINATION
  // =====================================================

  if (totalPages <= 1) {
    playersPagination.innerHTML = "";
    return;
  }

  let buttons = "";

  // =====================================================
  // PREVIOUS
  // =====================================================

  buttons += `
        <button
            type="button"
            onclick="
                changePage(
                    ${currentPage - 1}
                )
            "
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
            <i
                class="
                    fa-solid
                    fa-chevron-left
                    text-xs
                "
            ></i>
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
                onclick="
                    changePage(${page})
                "
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
            onclick="
                changePage(
                    ${currentPage + 1}
                )
            "
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
            <i
                class="
                    fa-solid
                    fa-chevron-right
                    text-xs
                "
            ></i>
        </button>
    `;

  playersPagination.innerHTML = buttons;
}

// =========================================================
// CHANGE PAGE
// =========================================================

function changePage(page) {
  const totalPages = Math.ceil(allPlayers.length / playersPerPage);

  if (page < 1 || page > totalPages) {
    return;
  }

  currentPage = page;

  renderPlayers();
}

// =========================================================
// STATUS DROPDOWN
// =========================================================

function togglePlayerStatusMenu(id, event) {
  if (event) {
    event.stopPropagation();
  }

  document.querySelectorAll(".status-menu").forEach((menu) => {
    if (menu.id !== `playerStatusMenu-${id}`) {
      menu.classList.add("hidden");
    }
  });

  const menu = document.getElementById(`playerStatusMenu-${id}`);

  if (menu) {
    menu.classList.toggle("hidden");
  }
}

// =========================================================
// CLOSE STATUS MENUS
// =========================================================

document.addEventListener("click", () => {
  document.querySelectorAll(".status-menu").forEach((menu) => {
    menu.classList.add("hidden");
  });
});

// =========================================================
// SELECT PLAYER STATUS
// =========================================================

async function selectPlayerStatus(id, newStatus) {
  const menu = document.getElementById(`playerStatusMenu-${id}`);

  if (menu) {
    menu.classList.add("hidden");
  }

  // =====================================================
  // FIND PLAYER
  // =====================================================

  const player = allPlayers.find((item) => Number(item.id) === Number(id));

  if (!player) {
    alert("Player not found.");
    return;
  }

  const currentStatus = player.status === "active" ? "active" : "blocked";

  if (currentStatus === newStatus) {
    return;
  }

  // =====================================================
  // UPDATE STATUS
  // =====================================================

  try {
    const response = await fetch(API.update, {
      method: "POST",

      headers: {
        "Content-Type": "application/json",

        Accept: "application/json",
      },

      body: JSON.stringify({
        id: Number(id),
        status: newStatus,
      }),
    });

    // ===================================================
    // READ RESPONSE
    // ===================================================

    const text = await response.text();

    console.log("Update Player Response:", text);

    // ===================================================
    // PARSE JSON
    // ===================================================

    let data;

    try {
      data = JSON.parse(text);
    } catch {
      throw new Error("Server returned invalid JSON.");
    }

    // ===================================================
    // API ERROR
    // ===================================================

    if (!response.ok || !data.success) {
      throw new Error(data.message || "Failed to update player status.");
    }

    // ===================================================
    // UPDATE LOCAL DATA
    // ===================================================

    player.status = newStatus;

    // ===================================================
    // RENDER
    // =====================================================

    renderPlayers();

    console.log("Player status updated:", id, newStatus);
  } catch (error) {
    console.error("Update Player Error:", error);

    alert(error.message || "Failed to update player status.");
  }
}

// =========================================================
// DELETE PLAYER
// =========================================================

async function deletePlayer(id) {
  // =====================================================
  // FIND PLAYER
  // =====================================================

  const player = allPlayers.find((item) => Number(item.id) === Number(id));

  if (!player) {
    alert("Player not found.");
    return;
  }

  // =====================================================
  // CONFIRMATION
  // =====================================================

  const confirmed = confirm(
    `Are you sure you want to delete "${player.name}"?`,
  );

  if (!confirmed) {
    return;
  }

  try {
    // ===================================================
    // SEND REQUEST
    // ===================================================

    const response = await fetch(API.delete, {
      method: "POST",

      headers: {
        "Content-Type": "application/json",

        Accept: "application/json",
      },

      body: JSON.stringify({
        id: Number(player.id),
      }),
    });

    // ===================================================
    // READ RESPONSE
    // ===================================================

    const text = await response.text();

    console.log("Delete Player Response:", text);

    // ===================================================
    // PARSE JSON
    // ===================================================

    let data;

    try {
      data = JSON.parse(text);
    } catch {
      throw new Error("Server returned invalid JSON.");
    }

    // ===================================================
    // API ERROR
    // ===================================================

    if (!response.ok || !data.success) {
      throw new Error(data.message || "Failed to delete player.");
    }

    // ===================================================
    // REMOVE FROM LOCAL DATA
    // =====================================================

    allPlayers = allPlayers.filter((item) => Number(item.id) !== Number(id));

    // ===================================================
    // FIX CURRENT PAGE
    // ===================================================

    const totalPages = Math.max(
      1,
      Math.ceil(allPlayers.length / playersPerPage),
    );

    if (currentPage > totalPages) {
      currentPage = totalPages;
    }

    // ===================================================
    // RENDER
    // ===================================================

    renderPlayers();

    console.log("Player deleted:", id);
  } catch (error) {
    console.error("Delete Player Error:", error);

    alert(error.message || "Failed to delete player.");
  }
}

// =========================================================
// DELETE BUTTONS
// =========================================================

function initializeDeleteButtons() {
  document.querySelectorAll(".delete-player-btn").forEach((button) => {
    button.addEventListener("click", () => {
      const playerId = button.dataset.playerId;

      if (!playerId) {
        return;
      }

      deletePlayer(playerId);
    });
  });
}

// =========================================================
// FORMAT DATE
// =========================================================

function formatDate(dateValue) {
  if (!dateValue) {
    return "-";
  }

  const date = new Date(dateValue);

  if (Number.isNaN(date.getTime())) {
    return dateValue;
  }

  return date.toLocaleDateString("en-GB", {
    day: "2-digit",
    month: "short",
    year: "numeric",
  });
}

// =========================================================
// INITIALS
// =========================================================

function getInitials(name = "") {
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

function escapeHTML(value) {
  return String(value ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

// =========================================================
// DOM READY
// =========================================================

document.addEventListener("DOMContentLoaded", () => {
  loadPlayers();
});
