// =========================================================
// TAILWIND CONFIG
// =========================================================

tailwind.config = {
  theme: {
    extend: {
      colors: {
        bgmain: "#070612",
        bgsecond: "#0D0B1A",
        card: "#111022",

        primary: "#7C2CFF",
        accent: "#A855F7",
        glow: "#B026FF",

        purpledark: "#4C1D95",
        purpleprimary: "#3D1398",
        purplebright: "#892ACD",
        purpleaccent: "#AD30E0",

        textmain: "#F5F3FF",
        textsecond: "#A5A1B5",
        muted: "#6B687A",

        borderc: "#26223D",
        neonborder: "#6D28D9",

        success: "#22C55E",
        danger: "#EF4444",
        rating: "#FACC15",

        gold: "#F0AE2D",
        goldbright: "#F0AE2D",
        golddark: "#7F4914",

        silver: "#C9C3D6",
        bronze: "#C97A3D",
      },

      fontFamily: {
        display: ["Poppins", "sans-serif"],
        tech: ["Rajdhani", "sans-serif"],
      },
    },
  },
};

// =========================================================
// GLOBAL HELPERS
// =========================================================

function escapeHtml(str) {
  const div = document.createElement("div");

  div.textContent = str ?? "";

  return div.innerHTML;
}
