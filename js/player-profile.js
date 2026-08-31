document.addEventListener("DOMContentLoaded", () => {
  // =========================================================
  // API
  // =========================================================

  const API_URL = "/GameX/BACKEND/player-profile.php";

  // =========================================================
  // ELEMENTS
  // =========================================================

  const playerName = document.getElementById("playerName");
  const memberSinceEl = document.getElementById("memberSince");
  const playerAvatar = document.getElementById("playerAvatar");
  const defaultAvatar = document.getElementById("defaultAvatar");

  const openEditProfile = document.getElementById("openEditProfile");
  const editModal = document.getElementById("editModal");
  const closeEditModal = document.getElementById("closeEditModal");
  const editForm = document.getElementById("editForm");

  const editName = document.getElementById("editName");
  const editAvatar = document.getElementById("editAvatar");

  const avatarPreview = document.getElementById("avatarPreview");
  const avatarPreviewDefault = document.getElementById("avatarPreviewDefault");

  const avatarError = document.getElementById("avatarError");
  const saveProfileBtn = document.getElementById("saveProfileBtn");
  const toast = document.getElementById("toast");
  const navbarAvatar = document.getElementById("navbarAvatar");

  // =========================================================
  // STATS
  // =========================================================

  const statOrders = document.getElementById("statOrders");
  const statGames = document.getElementById("statGames");
  const statWishlist = document.getElementById("statWishlist");
  const statReviews = document.getElementById("statReviews");

  // =========================================================
  // RECENT PURCHASES
  // =========================================================

  const purchasesGrid = document.getElementById("purchasesGrid");

  // =========================================================
  // CURRENT USER
  // =========================================================

  const userId = window.CURRENT_USER_ID;

  if (!userId) {
    console.error("CURRENT_USER_ID is missing.");
    return;
  }

  // =========================================================
  // DEFAULT AVATAR
  // =========================================================

  const DEFAULT_AVATAR = "src/Images/avatars/blaze.png";

  // Mirrors the server's $allowedAvatars whitelist in
  // BACKEND/player-profile.php. Used to tell a predefined
  // avatar apart from a previously uploaded custom avatar.
  const allowedAvatarPaths = [
    "src/Images/avatars/blaze.png",
    "src/Images/avatars/sentinel.png",
    "src/Images/avatars/raven.png",
    "src/Images/avatars/phantom.png",
  ];

  // =========================================================
  // CURRENT AVATAR
  // =========================================================

  let currentAvatarPath = "";

  // =========================================================
  // TOAST
  // =========================================================

  function showToast(message) {
    if (!toast) {
      alert(message);
      return;
    }

    toast.textContent = message;

    toast.classList.remove("opacity-0", "pointer-events-none");
    toast.classList.add("opacity-100");

    setTimeout(() => {
      toast.classList.remove("opacity-100");
      toast.classList.add("opacity-0", "pointer-events-none");
    }, 3000);
  }

  // =========================================================
  // AVATAR ERROR
  // =========================================================

  function showAvatarError(message) {
    if (!avatarError) {
      return;
    }

    avatarError.textContent = message;
    avatarError.classList.remove("hidden");
  }

  function clearAvatarError() {
    if (!avatarError) {
      return;
    }

    avatarError.textContent = "";
    avatarError.classList.add("hidden");
  }

  // =========================================================
  // NORMALIZE AVATAR PATH
  // =========================================================

  function normalizeAvatarPath(path) {
    if (!path) {
      return "";
    }

    path = String(path).trim();

    path = path.replace(/^https?:\/\/[^/]+\/GameX\//i, "");
    path = path.replace(/^\/?GameX\//i, "");
    path = path.replace(/^\/+/, "");

    path = path.replace(/^src\/images\/avatars\//i, "src/Images/avatars/");
    path = path.replace(/^images\/avatars\//i, "src/Images/avatars/");
    path = path.replace(/^Images\/avatars\//i, "src/Images/avatars/");

    const allowedNames = [
      "blaze.png",
      "sentinel.png",
      "raven.png",
      "phantom.png",
    ];

    if (allowedNames.includes(path)) {
      path = "src/Images/avatars/" + path;
    }

    return path;
  }

  // =========================================================
  // IMAGE URL
  // =========================================================

  function getImageUrl(path) {
    if (!path) {
      return "";
    }

    path = normalizeAvatarPath(path);

    if (!path) {
      return "";
    }

    if (path.startsWith("http://") || path.startsWith("https://")) {
      return path;
    }

    return "/GameX/" + path;
  }

  // =========================================================
  // SET MAIN AVATAR
  // =========================================================

  function setAvatar(path) {
    if (!playerAvatar || !defaultAvatar) {
      return;
    }

    path = normalizeAvatarPath(path);

    if (!path) {
      playerAvatar.src = "";
      playerAvatar.classList.add("hidden");
      defaultAvatar.classList.remove("hidden");
      return;
    }

    const imageUrl = getImageUrl(path);

    playerAvatar.src = imageUrl + "?t=" + Date.now();

    playerAvatar.classList.remove("hidden");
    defaultAvatar.classList.add("hidden");

    playerAvatar.onerror = () => {
      playerAvatar.src = "";
      playerAvatar.classList.add("hidden");
      defaultAvatar.classList.remove("hidden");
    };
  }

  // =========================================================
  // SET PREVIEW
  // =========================================================

  function setPreview(path) {
    if (!avatarPreview) {
      return;
    }

    path = normalizeAvatarPath(path);

    if (!path) {
      avatarPreview.src = "";
      avatarPreview.classList.add("hidden");

      if (avatarPreviewDefault) {
        avatarPreviewDefault.classList.remove("hidden");
      }

      return;
    }

    avatarPreview.src = getImageUrl(path) + "?t=" + Date.now();
    avatarPreview.classList.remove("hidden");

    if (avatarPreviewDefault) {
      avatarPreviewDefault.classList.add("hidden");
    }
  }

  // =========================================================
  // NAME VALIDATION (letters only, no digits/symbols/HTML/JS)
  // =========================================================

  const NAME_ALLOWED_REGEX = /^\p{L}+(?:\s\p{L}+)*$/u;
  const NAME_STRIP_REGEX = /[^\p{L}\s]/gu;

  if (editName) {
    editName.addEventListener("input", () => {
      const cleaned = editName.value.replace(NAME_STRIP_REGEX, "");

      if (cleaned !== editName.value) {
        editName.value = cleaned;
      }
    });
  }

  // =========================================================
  // CUSTOM FILE PREVIEW
  // =========================================================

  if (editAvatar) {
    editAvatar.addEventListener("change", () => {
      clearAvatarError();

      const file = editAvatar.files?.[0];

      if (!file) {
        return;
      }

      const maxSize = 5 * 1024 * 1024;

      if (file.size > maxSize) {
        showAvatarError("Avatar must be smaller than 5MB.");
        editAvatar.value = "";
        return;
      }

      const allowedTypes = ["image/jpeg", "image/png", "image/webp"];

      if (!allowedTypes.includes(file.type)) {
        showAvatarError("Only JPG, PNG or WEBP images are allowed.");
        editAvatar.value = "";
        return;
      }

      currentAvatarPath = "";

      const reader = new FileReader();

      reader.onload = (event) => {
        if (avatarPreview) {
          avatarPreview.src = event.target.result;
          avatarPreview.classList.remove("hidden");
        }

        if (avatarPreviewDefault) {
          avatarPreviewDefault.classList.add("hidden");
        }
      };

      reader.readAsDataURL(file);
    });
  }

  // =========================================================
  // UPDATE NAVBAR AVATAR
  // =========================================================

  function updateNavbarAvatar(path) {
    if (!navbarAvatar) {
      return;
    }

    path = normalizeAvatarPath(path);

    if (!path) {
      navbarAvatar.src = getImageUrl(DEFAULT_AVATAR);
      return;
    }

    navbarAvatar.src = getImageUrl(path) + "?t=" + Date.now();

    navbarAvatar.onerror = () => {
      navbarAvatar.src = getImageUrl(DEFAULT_AVATAR);
    };
  }

  // =========================================================
  // RESET STATS
  // =========================================================

  function resetStats() {
    if (statOrders) statOrders.textContent = "0";
    if (statGames) statGames.textContent = "0";
    if (statWishlist) statWishlist.textContent = "0";
    if (statReviews) statReviews.textContent = "0";
  }

  // =========================================================
  // FORMAT PRICE
  // =========================================================

  function formatPrice(value) {
    const num = Number(value || 0);
    return "$" + num.toFixed(2);
  }

  // =========================================================
  // STATUS BADGE
  // =========================================================

  function statusBadge(status) {
    const normalized = (status || "").toLowerCase();

    const styles = {
      completed: "bg-emerald-500/15 text-emerald-400",
      processing: "bg-blue-500/15 text-blue-400",
      pending: "bg-yellow-500/15 text-yellow-400",
      cancelled: "bg-red-500/15 text-red-400",
    };

    const cls = styles[normalized] || "bg-gray-500/15 text-gray-400";
    const label = normalized
      ? normalized.charAt(0).toUpperCase() + normalized.slice(1)
      : "Unknown";

    return `<span class="inline-block text-xs font-semibold px-3 py-1 rounded-full ${cls}">${label}</span>`;
  }

  // =========================================================
  // LOAD PROFILE
  // =========================================================

  async function loadProfile() {
    try {
      const response = await fetch(API_URL, {
        method: "GET",
        credentials: "same-origin",
        cache: "no-store",
      });

      const data = await response.json();

      if (!response.ok || !data.success) {
        throw new Error(data.message || "Failed to load profile.");
      }

      const user = data.user;

      console.log("Profile:", user);

      // =====================================================
      // NAME
      // =====================================================

      if (playerName) {
        playerName.textContent = user.username || "Player";
      }

      if (editName) {
        editName.value = user.username || "";
      }

      // =====================================================
      // MEMBER SINCE
      // =====================================================

      if (memberSinceEl) {
        memberSinceEl.textContent = user.memberSince
          ? "Member since " + user.memberSince
          : "";
      }

      // =====================================================
      // AVATAR
      // =====================================================

      const avatar = normalizeAvatarPath(user.avatar || "");

      currentAvatarPath = avatar;

      setAvatar(avatar);
      setPreview(avatar);
      updateNavbarAvatar(avatar);

      // =====================================================
      // STATS
      // =====================================================

      if (data.stats) {
        if (statOrders) statOrders.textContent = Number(data.stats.orders || 0);
        if (statGames) statGames.textContent = Number(data.stats.games || 0);
        if (statWishlist)
          statWishlist.textContent = Number(data.stats.wishlist || 0);
        if (statReviews)
          statReviews.textContent = Number(data.stats.reviews || 0);
      } else {
        resetStats();
      }

      // =====================================================
      // RECENT PURCHASES
      // =====================================================

      renderRecentPurchases(data.recentPurchases || []);
    } catch (error) {
      console.error("Profile Load Error:", error);

      if (playerName) {
        playerName.textContent = "Failed to load profile";
      }

      showToast(error.message);
    }
  }

  // =========================================================
  // RENDER RECENT PURCHASES
  // =========================================================

  function renderRecentPurchases(purchases) {
    if (!purchasesGrid) {
      return;
    }

    purchasesGrid.innerHTML = "";

    if (!Array.isArray(purchases) || purchases.length === 0) {
      purchasesGrid.innerHTML = `
        <div class="col-span-full text-center py-8 text-gray-500">
          No purchases yet.
        </div>
      `;

      return;
    }

    purchases.slice(0, 4).forEach((item) => {
      const title = item.title || "Unknown Game";
      const image = item.image || "";
      const price = formatPrice(item.price);
      const status = item.status || "";

      let dateLabel = "";

      if (item.purchasedAt) {
        const purchasedDate = new Date(item.purchasedAt.replace(" ", "T"));

        if (!isNaN(purchasedDate)) {
          dateLabel = purchasedDate.toLocaleDateString(undefined, {
            year: "numeric",
            month: "short",
            day: "numeric",
          });
        }
      }

      const card = document.createElement("div");

      card.className =
        "bg-[#0d0f1c] border border-[#24213a] rounded-xl overflow-hidden hover:border-[#7c2cff] transition p-4 flex gap-4";

      card.innerHTML = `
        <div class="w-20 h-24 rounded-lg overflow-hidden bg-[#17121f] shrink-0">
          ${
            image
              ? `
                <img
                  src="${getImageUrl(image)}"
                  alt="${escapeHTML(title)}"
                  class="w-full h-full object-cover"
                >
              `
              : `
                <div class="w-full h-full grid place-items-center">
                  <i class="fa-solid fa-gamepad text-2xl text-[#7c2cff]"></i>
                </div>
              `
          }
        </div>

        <div class="flex-1 min-w-0">
          <h3 class="font-semibold truncate">
            ${escapeHTML(title)}
          </h3>

          <p class="text-[#a855f7] font-semibold mt-1">
            ${escapeHTML(price)}
          </p>

          ${
            dateLabel
              ? `
                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                  <i class="fa-regular fa-calendar"></i> ${escapeHTML(dateLabel)}
                </p>
              `
              : ""
          }

          <div class="mt-3">
            ${statusBadge(status)}
          </div>
        </div>
      `;

      purchasesGrid.appendChild(card);
    });
  }

  // =========================================================
  // ESCAPE HTML
  // =========================================================

  function escapeHTML(value) {
    const div = document.createElement("div");
    div.textContent = value ?? "";
    return div.innerHTML;
  }

  // =========================================================
  // OPEN MODAL
  // =========================================================

  if (openEditProfile) {
    openEditProfile.addEventListener("click", () => {
      if (!editModal) {
        return;
      }

      if (editName && playerName) {
        editName.value = playerName.textContent.trim();
      }

      if (currentAvatarPath) {
        setPreview(currentAvatarPath);
      }

      clearAvatarError();

      editModal.classList.remove("hidden");
      editModal.classList.add("flex");
    });
  }

  // =========================================================
  // CLOSE MODAL
  // =========================================================

  function closeModal() {
    if (!editModal) {
      return;
    }

    editModal.classList.add("hidden");
    editModal.classList.remove("flex");

    clearAvatarError();
  }

  if (closeEditModal) {
    closeEditModal.addEventListener("click", closeModal);
  }

  if (editModal) {
    editModal.addEventListener("click", (event) => {
      if (event.target === editModal) {
        closeModal();
      }
    });
  }

  // =========================================================
  // SAVE PROFILE
  // =========================================================

  if (editForm) {
    editForm.addEventListener("submit", async (event) => {
      event.preventDefault();

      clearAvatarError();

      const name = editName ? editName.value.trim() : "";

      if (!name) {
        showToast("Display name is required.");
        return;
      }

      if (name.length < 2) {
        showToast("Display name must contain at least 2 characters.");
        return;
      }

      if (name.length > 50) {
        showToast("Display name is too long.");
        return;
      }

      if (!NAME_ALLOWED_REGEX.test(name)) {
        showToast(
          "Display name may only contain letters (no numbers or symbols).",
        );
        return;
      }

      const formData = new FormData();
      formData.append("name", name);

      const file = editAvatar?.files?.[0];

      if (file) {
        formData.append("avatar_file", file);
        console.log("Uploading custom avatar:", file.name);
      } else if (
        currentAvatarPath &&
        allowedAvatarPaths.includes(normalizeAvatarPath(currentAvatarPath))
      ) {
        formData.append("avatar", normalizeAvatarPath(currentAvatarPath));
        console.log("Using avatar path:", currentAvatarPath);
      } else {
        console.log(
          "Keeping existing avatar unchanged (no avatar field sent).",
        );
      }

      console.log("Saving profile...");
      console.log("Name:", name);
      console.log("File:", file ? file.name : "No new file");

      const oldButton = saveProfileBtn ? saveProfileBtn.innerHTML : "";

      if (saveProfileBtn) {
        saveProfileBtn.disabled = true;

        saveProfileBtn.innerHTML = `
            <i class="fa-solid fa-spinner fa-spin mr-2"></i>
            Saving...
          `;
      }

      try {
        const response = await fetch(API_URL, {
          method: "POST",
          body: formData,
          credentials: "same-origin",
        });

        const data = await response.json();

        console.log("Save response:", data);

        if (!response.ok || !data.success) {
          throw new Error(data.message || "Failed to save profile.");
        }

        if (editAvatar) {
          editAvatar.value = "";
        }

        await loadProfile();

        showToast("Profile saved successfully!");

        setTimeout(() => {
          closeModal();
        }, 700);
      } catch (error) {
        console.error("Profile Save Error:", error);
        showToast(error.message);
      } finally {
        if (saveProfileBtn) {
          saveProfileBtn.disabled = false;
          saveProfileBtn.innerHTML = oldButton;
        }
      }
    });
  }

  // =========================================================
  // START
  // =========================================================

  loadProfile();
});
