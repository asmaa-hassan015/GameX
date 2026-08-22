const API_URL = "/GameX/BACKEND/reviews.php";
const GAMES_API_URL = "/GameX/BACKEND/games.php";


// =========================================================
// GLOBAL VARIABLES
// =========================================================

let reviews = [];
let games = [];

const perPage = 6;
let currentPage = 1;


// =========================================================
// DOM ELEMENTS
// =========================================================

const grid = document.getElementById("reviewsGrid");
const pagination = document.getElementById("pagination");

const reviewModal = document.getElementById("reviewModal");
const detailsModal = document.getElementById("detailsModal");

const reviewForm = document.getElementById("reviewForm");

const ratingValue = document.getElementById("ratingValue");

const stars = document.querySelectorAll("#starPicker i");

const gameSelect = document.getElementById("reviewGame");


// =========================================================
// STAR HTML
// =========================================================

function starsHTML(rating) {

    let html = "";

    for (let i = 1; i <= 5; i++) {

        html += i <= rating ? "★" : "☆";

    }

    return html;
}


// =========================================================
// ESCAPE HTML
// =========================================================

function escapeHTML(value) {

    if (value === null || value === undefined) {

        return "";

    }

    const div = document.createElement("div");

    div.textContent = String(value);

    return div.innerHTML;
}


// =========================================================
// LOAD GAMES
// =========================================================

async function loadGames() {

    try {

        gameSelect.innerHTML = `
            <option value="">
                Loading games...
            </option>
        `;

        const response = await fetch(GAMES_API_URL, {

            method: "GET",

            credentials: "include"

        });

        const data = await response.json();

        if (!response.ok || !data.success) {

            throw new Error(
                data.message || "Failed to load games."
            );

        }

        games = Array.isArray(data.games)
            ? data.games
            : [];

        gameSelect.innerHTML = `
            <option value="">
                Select a game
            </option>
        `;

        games.forEach((game) => {

            const option = document.createElement("option");

            option.value = game.id;

            option.textContent =
                game.title || game.name;

            gameSelect.appendChild(option);

        });

        if (games.length === 0) {

            gameSelect.innerHTML = `
                <option value="">
                    No games available
                </option>
            `;

        }

    } catch (error) {

        console.error("Games Error:", error);

        gameSelect.innerHTML = `
            <option value="">
                Failed to load games
            </option>
        `;

    }

}


// =========================================================
// LOAD REVIEWS
// =========================================================

async function loadReviews() {

    try {

        grid.innerHTML = `
            <div class="md:col-span-2 flex justify-center py-16">

                <div class="text-[#a855f7] text-lg">
                    Loading reviews...
                </div>

            </div>
        `;

        const response = await fetch(API_URL, {

            method: "GET",

            credentials: "include"

        });

        const data = await response.json();

        if (!response.ok || !data.success) {

            throw new Error(
                data.message || "Failed to load reviews."
            );

        }

        reviews = Array.isArray(data.reviews)
            ? data.reviews
            : [];

        currentPage = 1;

        renderReviews();

    } catch (error) {

        console.error("Reviews Error:", error);

        grid.innerHTML = `
            <div class="md:col-span-2 text-center py-16">

                <p class="text-red-400 mb-3">
                    Failed to load reviews.
                </p>

                <button
                    onclick="loadReviews()"
                    class="neon rounded-lg px-5 py-2 text-[#a855f7]"
                >
                    Try Again
                </button>

            </div>
        `;

    }

}


// =========================================================
// RENDER REVIEWS
// =========================================================

function renderReviews() {

    const totalPages =
        Math.max(1, Math.ceil(reviews.length / perPage));

    if (currentPage > totalPages) {

        currentPage = totalPages;

    }

    const start =
        (currentPage - 1) * perPage;

    const pageItems =
        reviews.slice(start, start + perPage);


    if (pageItems.length === 0) {

        grid.innerHTML = `
            <div class="md:col-span-2 text-center py-16">

                <i
                    class="fa-regular fa-comments text-5xl text-[#7c2cff] mb-5"
                ></i>

                <h3 class="text-2xl font-semibold mb-2">
                    No Reviews Yet
                </h3>

                <p class="muted">
                    Be the first one to share your experience.
                </p>

            </div>
        `;

        renderPagination(totalPages);

        return;

    }


    grid.innerHTML = pageItems.map((review, idx) => {

        return `
            <article
                class="glass rounded-2xl p-8 flex flex-col justify-between"
            >

                <div>

                    <div class="flex justify-between items-start gap-4">

                        <div class="flex gap-4 items-center">

                            <div
                                class="h-14 w-14 rounded-full border border-[#7c2cff] bg-[#17121f] grid place-items-center flex-shrink-0 overflow-hidden"
                            >

                                ${
                                    review.avatar
                                    ?

                                    `
                                    <img
                                        src="/GameX/${escapeHTML(review.avatar)}"
                                        alt="${escapeHTML(review.name)}"
                                        class="w-full h-full object-cover"
                                    >
                                    `

                                    :

                                    `
                                    <i
                                        class="fa-solid fa-user text-xl text-[#a855f7]"
                                    ></i>
                                    `
                                }

                            </div>


                            <div>

                                <h3
                                    class="text-xl font-bold flex items-center gap-2"
                                >

                                    ${escapeHTML(review.name)}

                                    <i
                                        class="fa-solid fa-circle-check text-[#a855f7] text-sm"
                                    ></i>

                                </h3>

                                <div class="text-yellow-400 mt-1">

                                    ${starsHTML(review.rating)}

                                </div>

                            </div>

                        </div>


                        <p
                            class="muted text-xs whitespace-nowrap"
                        >
                            ${escapeHTML(review.date)}
                        </p>

                    </div>


                    <p
                        class="text-gray-300 text-base leading-7 mt-6 line-clamp-3"
                    >
                        ${escapeHTML(review.text)}
                    </p>


                    ${
                        review.game
                        ?

                        `
                        <p
                            class="text-[#a855f7] text-sm mt-4"
                        >

                            <i class="fa-solid fa-gamepad mr-1"></i>

                            ${escapeHTML(review.game)}

                        </p>
                        `

                        :

                        ""
                    }

                </div>


                <button
                    class="view-details neon rounded-lg px-5 py-2.5 mt-6 hover:bg-[#7c2cff]/10 transition self-start text-sm cursor-pointer"
                    data-index="${start + idx}"
                >
                    View Details →
                </button>

            </article>
        `;

    }).join("");


    document.querySelectorAll(".view-details").forEach((button) => {

        button.addEventListener("click", () => {

            const index =
                Number(button.dataset.index);

            openDetails(reviews[index]);

        });

    });


    renderPagination(totalPages);

}


// =========================================================
// PAGINATION
// =========================================================

function renderPagination(totalPages) {

    let html = `

        <button
            id="prevPage"
            class="h-11 w-11 border border-[#24213a] rounded-lg hover:border-[#7c2cff] cursor-pointer"
        >
            ‹
        </button>

    `;


    for (let i = 1; i <= totalPages; i++) {

        html += `

            <button
                data-page="${i}"
                class="page-btn h-11 w-11 rounded-lg border border-[#24213a] hover:border-[#7c2cff] cursor-pointer ${
                    i === currentPage ? "active" : ""
                }"
            >
                ${i}
            </button>

        `;

    }


    html += `

        <button
            id="nextPage"
            class="h-11 w-11 border border-[#24213a] rounded-lg hover:border-[#7c2cff] cursor-pointer"
        >
            ›

        </button>

    `;


    pagination.innerHTML = html;


    document.querySelectorAll(".page-btn").forEach((button) => {

        button.addEventListener("click", () => {

            currentPage =
                Number(button.dataset.page);

            renderReviews();

            window.scrollTo({

                top: 0,

                behavior: "smooth"

            });

        });

    });


    const prevButton =
        document.getElementById("prevPage");

    prevButton.addEventListener("click", () => {

        if (currentPage > 1) {

            currentPage--;

            renderReviews();

            window.scrollTo({

                top: 0,

                behavior: "smooth"

            });

        }

    });


    const nextButton =
        document.getElementById("nextPage");

    nextButton.addEventListener("click", () => {

        if (currentPage < totalPages) {

            currentPage++;

            renderReviews();

            window.scrollTo({

                top: 0,

                behavior: "smooth"

            });

        }

    });

}


// =========================================================
// OPEN WRITE REVIEW
// =========================================================

document
    .getElementById("openWriteReview")
    .addEventListener("click", async () => {

        reviewModal.classList.remove("hidden");

        reviewModal.classList.add("flex");

        await loadGames();

    });


// =========================================================
// CLOSE WRITE REVIEW
// =========================================================

document
    .getElementById("closeWriteReview")
    .addEventListener("click", closeWriteReview);


reviewModal.addEventListener("click", (event) => {

    if (event.target === reviewModal) {

        closeWriteReview();

    }

});


function closeWriteReview() {

    reviewModal.classList.add("hidden");

    reviewModal.classList.remove("flex");

}


// =========================================================
// STAR PICKER
// =========================================================

function paintStars(rating) {

    stars.forEach((star) => {

        const value =
            Number(star.dataset.value);

        star.classList.toggle(
            "text-yellow-400",
            value <= rating
        );

        star.classList.toggle(
            "text-gray-600",
            value > rating
        );

    });

}


paintStars(5);


stars.forEach((star) => {

    star.addEventListener("click", () => {

        const value =
            Number(star.dataset.value);

        ratingValue.value = value;

        paintStars(value);

    });

});


// =========================================================
// SUBMIT REVIEW
// =========================================================

reviewForm.addEventListener("submit", async (event) => {

    event.preventDefault();


    const gameId =
        Number(gameSelect.value || 0);

    const text =
        document
            .getElementById("reviewText")
            .value
            .trim();

    const rating =
        Number(ratingValue.value);


    // =====================================================
    // GAME VALIDATION
    // =====================================================

    if (!gameId) {

        showReviewMessage(
            "Please select a game.",
            "error"
        );

        return;

    }


    // =====================================================
    // REVIEW TEXT VALIDATION
    // =====================================================

    if (!text) {

        showReviewMessage(
            "Please write your review.",
            "error"
        );

        return;

    }


    // =====================================================
    // RATING VALIDATION
    // =====================================================

    if (rating < 1 || rating > 5) {

        showReviewMessage(
            "Please select a rating.",
            "error"
        );

        return;

    }


    // =====================================================
    // SUBMIT BUTTON
    // =====================================================

    const submitButton =
        reviewForm.querySelector(
            'button[type="submit"]'
        );

    submitButton.disabled = true;

    submitButton.textContent =
        "Submitting...";


    try {

        const response = await fetch(API_URL, {

            method: "POST",

            credentials: "include",

            headers: {

                "Content-Type":
                    "application/json"

            },

            body: JSON.stringify({

                game_id: gameId,

                rating: rating,

                text: text

            })

        });


        const data =
            await response.json();


        if (!response.ok || !data.success) {

            throw new Error(
                data.message ||
                "Failed to submit review."
            );

        }


        // =================================================
        // RESET FORM
        // =================================================

        reviewForm.reset();

        ratingValue.value = 5;

        paintStars(5);

        gameSelect.value = "";


        closeWriteReview();


        alert(
            data.message ||
            "Review submitted successfully."
        );


        await loadReviews();


    } catch (error) {

        console.error(
            "Submit Review Error:",
            error
        );

        showReviewMessage(
            error.message ||
            "Something went wrong.",
            "error"
        );

    } finally {

        submitButton.disabled = false;

        submitButton.textContent =
            "Submit Review";

    }

});


// =========================================================
// REVIEW MESSAGE
// =========================================================

function showReviewMessage(
    message,
    type = "error"
) {

    const existing =
        document.getElementById(
            "reviewMessage"
        );

    if (existing) {

        existing.remove();

    }


    const messageElement =
        document.createElement("div");


    messageElement.id =
        "reviewMessage";


    messageElement.className =
        type === "error"

        ? "text-red-400 text-sm mt-2"

        : "text-green-400 text-sm mt-2";


    messageElement.textContent =
        message;


    reviewForm.prepend(
        messageElement
    );


    setTimeout(() => {

        messageElement.remove();

    }, 5000);

}


// =========================================================
// VIEW DETAILS
// =========================================================

function openDetails(review) {

    if (!review) {

        return;

    }


    document.getElementById(
        "detailsName"
    ).textContent = review.name;


    document.getElementById(
        "detailsStars"
    ).textContent =
        starsHTML(review.rating);


    document.getElementById(
        "detailsGame"
    ).textContent =
        review.game
        ? `Game: ${review.game}`
        : "";


    document.getElementById(
        "detailsDate"
    ).textContent =
        review.date;


    document.getElementById(
        "detailsText"
    ).textContent =
        review.text;


    const avatar =
        document.getElementById(
            "detailsAvatar"
        );

    const avatarIcon =
        document.getElementById(
            "detailsAvatarIcon"
        );


    if (review.avatar) {

        avatar.src =
            "/GameX/" + review.avatar;

        avatar.classList.remove("hidden");

        avatarIcon.classList.add("hidden");

    } else {

        avatar.classList.add("hidden");

        avatarIcon.classList.remove("hidden");

    }


    detailsModal.classList.remove("hidden");

    detailsModal.classList.add("flex");

}


// =========================================================
// CLOSE DETAILS
// =========================================================

document
    .getElementById("closeDetails")
    .addEventListener(
        "click",
        closeDetails
    );


detailsModal.addEventListener(
    "click",
    (event) => {

        if (event.target === detailsModal) {

            closeDetails();

        }

    }
);


function closeDetails() {

    detailsModal.classList.add("hidden");

    detailsModal.classList.remove("flex");

}


// =========================================================
// VIEW ALL REVIEWS
// =========================================================

document
    .getElementById("viewAllBtn")
    .addEventListener("click", (event) => {

        event.preventDefault();

        currentPage = 1;

        renderReviews();

        window.scrollTo({

            top: 0,

            behavior: "smooth"

        });

    });


// =========================================================
// INITIAL LOAD
// =========================================================

loadReviews();