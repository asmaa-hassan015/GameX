
document.addEventListener("DOMContentLoaded", () => {
  // =========================================================
  // API
  // =========================================================

  const API_URL = "/GameX/BACKEND/player-profile.php";

  // =========================================================
  // ELEMENTS
  // =========================================================

  const playerName = document.getElementById("playerName");
  const playerAvatar = document.getElementById("playerAvatar");
  const defaultAvatar = document.getElementById("defaultAvatar");
  const playerLevel = document.getElementById("playerLevel");

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

  const statGames = document.getElementById("statGames");
  const statAchievements = document.getElementById("statAchievements");
  const statHours = document.getElementById("statHours");
  const statWishlist = document.getElementById("statWishlist");

  // =========================================================
  // RECENTLY PLAYED
  // =========================================================

  const gamesGrid = document.getElementById("gamesGrid");
  const viewAllGames = document.getElementById("viewAllGames");

  // =========================================================
  // XP
  // =========================================================

  const xpNote = document.getElementById("xpNote");
  const xpText = document.getElementById("xpText");
  const xpBar = document.getElementById("xpBar");

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

    // Full URL
    path = path.replace(/^https?:\/\/[^/]+\/GameX\//i, "");

    // /GameX/
    path = path.replace(/^\/?GameX\//i, "");

    // Leading slash
    path = path.replace(/^\/+/, "");

    // Images normalization
    path = path.replace(/^src\/images\/avatars\//i, "src/Images/avatars/");

    path = path.replace(/^images\/avatars\//i, "src/Images/avatars/");

    path = path.replace(/^Images\/avatars\//i, "src/Images/avatars/");

    // Filename only
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
  // CUSTOM FILE PREVIEW
  // =========================================================

  if (editAvatar) {
    editAvatar.addEventListener("change", () => {
      clearAvatarError();

      const file = editAvatar.files?.[0];

      if (!file) {
        return;
      }

      // File size
      const maxSize = 5 * 1024 * 1024;

      if (file.size > maxSize) {
        showAvatarError("Avatar must be smaller than 5MB.");

        editAvatar.value = "";

        return;
      }

      // File type
      const allowedTypes = ["image/jpeg", "image/png", "image/webp"];

      if (!allowedTypes.includes(file.type)) {
        showAvatarError("Only JPG, PNG or WEBP images are allowed.");

        editAvatar.value = "";

        return;
      }

      // Custom image selected
      // Do not send predefined avatar
      currentAvatarPath = "";

      // Preview
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
    if (statGames) {
      statGames.textContent = "0";
    }

    if (statAchievements) {
      statAchievements.textContent = "0";
    }

    if (statHours) {
      statHours.textContent = "0h";
    }

    if (statWishlist) {
      statWishlist.textContent = "0";
    }

    if (xpText) {
      xpText.textContent = "0 / 100 XP";
    }

    if (xpNote) {
      xpNote.textContent = "100 XP remaining";
    }

    if (xpBar) {
      xpBar.style.width = "0%";
    }
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
      // AVATAR
      // =====================================================

      const avatar = normalizeAvatarPath(user.avatar || "");

      currentAvatarPath = avatar;

      setAvatar(avatar);
      setPreview(avatar);
      updateNavbarAvatar(avatar);

      // =====================================================
      // LEVEL
      // =====================================================

      const level = Number(user.level || 1);

      if (playerLevel) {
        playerLevel.textContent = level;
      }

      // =====================================================
      // STATS
      // =====================================================

      if (data.stats) {
        if (statGames) {
          statGames.textContent = Number(data.stats.games || 0);
        }

        if (statAchievements) {
          statAchievements.textContent = Number(data.stats.achievements || 0);
        }

        if (statHours) {
          statHours.textContent = Number(data.stats.hours || 0) + "h";
        }

        if (statWishlist) {
          statWishlist.textContent = Number(data.stats.wishlist || 0);
        }

        const currentXP = Number(data.stats.currentXP || 0);

        const requiredXP = Number(data.stats.requiredXP || 100);

        if (xpText) {
          xpText.textContent = `${currentXP} / ${requiredXP} XP`;
        }

        if (xpNote) {
          const remaining = Math.max(requiredXP - currentXP, 0);

          xpNote.textContent = `${remaining} XP remaining`;
        }

        if (xpBar) {
          let percentage = 0;

          if (requiredXP > 0) {
            percentage = (currentXP / requiredXP) * 100;
          }

          percentage = Math.min(Math.max(percentage, 0), 100);

          xpBar.style.width = percentage + "%";
        }
      } else {
        resetStats();
      }

      // =====================================================
      // RECENTLY PLAYED
      // =====================================================

      renderRecentlyPlayed(data.recentlyPlayed || []);
    } catch (error) {
      console.error("Profile Load Error:", error);

      if (playerName) {
        playerName.textContent = "Failed to load profile";
      }

      showToast(error.message);
    }
  }

  // =========================================================
  // RENDER RECENTLY PLAYED
  // =========================================================

  function renderRecentlyPlayed(games) {
    if (!gamesGrid) {
      return;
    }

    gamesGrid.innerHTML = "";

    if (!Array.isArray(games) || games.length === 0) {
      gamesGrid.innerHTML = `
        <div class="col-span-full text-center py-8 text-gray-500">
          No recently played games.
        </div>
      `;

      return;
    }

    games.slice(0, 4).forEach((game) => {
      const title = game.title || game.name || "Unknown Game";

      const image = game.image || game.cover || "";

      const hours = game.hours || 0;

      const card = document.createElement("div");

      card.className =
        "bg-[#0d0f1c] border border-[#24213a] rounded-xl overflow-hidden hover:border-[#7c2cff] transition";

      card.innerHTML = `
        <div class="h-40 bg-[#17121f] overflow-hidden">

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
                  <i class="fa-solid fa-gamepad text-4xl text-[#7c2cff]"></i>
                </div>
              `
          }

        </div>

        <div class="p-4">

          <h3 class="font-semibold truncate">
            ${escapeHTML(title)}
          </h3>

          <p class="text-sm text-gray-500 mt-2">
            ${Number(hours)}
            hours played
          </p>

        </div>
      `;

      gamesGrid.appendChild(card);
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

      // Current avatar preview
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

      // ===================================================
      // NAME
      // ===================================================

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

      // ===================================================
      // FORM DATA
      // ===================================================

      const formData = new FormData();

      formData.append("name", name);

      // ===================================================
      // AVATAR
      // ===================================================

      const file = editAvatar?.files?.[0];

      if (file) {
        // Custom image
        formData.append("avatar_file", file);

        console.log("Uploading custom avatar:", file.name);
      } else if (currentAvatarPath) {
        // Predefined / current avatar
        formData.append("avatar", normalizeAvatarPath(currentAvatarPath));

        console.log("Using avatar path:", currentAvatarPath);
      }

      // ===================================================
      // DEBUG
      // ===================================================

      console.log("Saving profile...");
      console.log("Name:", name);
      console.log("File:", file ? file.name : "No new file");

      // ===================================================
      // BUTTON
      // ===================================================

      const oldButton = saveProfileBtn ? saveProfileBtn.innerHTML : "";

      if (saveProfileBtn) {
        saveProfileBtn.disabled = true;

        saveProfileBtn.innerHTML = `
            <i class="fa-solid fa-spinner fa-spin mr-2"></i>
            Saving...
          `;
      }

      // ===================================================
      // SEND REQUEST
      // ===================================================

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

        // =================================================
        // UPDATED USER
        // =================================================

        const updatedUser = data.user;

        // =================================================
        // NAME
        // =================================================

        if (playerName) {
          playerName.textContent = updatedUser.username;
        }

        if (editName) {
          editName.value = updatedUser.username;
        }

        // =================================================
        // AVATAR
        // =================================================

        const updatedAvatar = normalizeAvatarPath(updatedUser.avatar || "");

        currentAvatarPath = updatedAvatar;

        setAvatar(updatedAvatar);
        setPreview(updatedAvatar);
        updateNavbarAvatar(updatedAvatar);

        // =================================================
        // LEVEL
        // =================================================

        if (playerLevel) {
          playerLevel.textContent = Number(updatedUser.level || 1);
        }

        // =================================================
        // CLEAR FILE
        // =================================================

        if (editAvatar) {
          editAvatar.value = "";
        }

        // =================================================
        // SUCCESS
        // =================================================

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
  // VIEW ALL GAMES
  // =========================================================

  if (viewAllGames) {
    viewAllGames.addEventListener("click", () => {
      window.location.href = "Games.php";
    });
  }

  // =========================================================
  // START
  // =========================================================

  loadProfile();
});
