// =========================================================
// SHOW ERROR
// =========================================================

function showError(input, message) {
  removeError(input);

  const error = document.createElement("p");

  error.className = "js-error text-red-500 text-xs mt-1";

  error.textContent = message;

  const parent = input.closest("div");

  if (parent) {
    parent.appendChild(error);
  }
}

// =========================================================
// REMOVE ERROR
// =========================================================

function removeError(input) {
  if (!input) {
    return;
  }

  const parent = input.closest("div");

  if (!parent) {
    return;
  }

  const oldError = parent.querySelector(".js-error");

  if (oldError) {
    oldError.remove();
  }
}

// =========================================================
// TOGGLE PASSWORD
// =========================================================

function togglePw(id) {
  const input = document.getElementById(id);

  if (!input) {
    return;
  }

  const button = input.parentElement.querySelector("button");

  const icon = button ? button.querySelector("i") : null;

  if (input.type === "password") {
    input.type = "text";

    if (icon) {
      icon.classList.remove("fa-eye-slash");
      icon.classList.add("fa-eye");
    }
  } else {
    input.type = "password";

    if (icon) {
      icon.classList.remove("fa-eye");
      icon.classList.add("fa-eye-slash");
    }
  }
}

// =========================================================
// REGISTER VALIDATION
// =========================================================

const registerForm = document.getElementById("registerForm");

if (registerForm) {
  registerForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    let isValid = true;

    // -------------------------------------------------
    // GET FORM ELEMENTS
    // -------------------------------------------------

    const username = document.getElementById("username");

    const email = document.getElementById("email");

    const password = document.getElementById("pw1");

    const confirmPassword = document.getElementById("pw2");

    const terms = registerForm.querySelector('input[name="terms"]');

    // -------------------------------------------------
    // CLEAR OLD ERRORS
    // -------------------------------------------------

    removeError(username);
    removeError(email);
    removeError(password);
    removeError(confirmPassword);

    const oldTermsError = registerForm.querySelector(".terms-js-error");

    if (oldTermsError) {
      oldTermsError.remove();
    }

    // =================================================
    // USERNAME VALIDATION
    // =================================================

    if (username.value.trim() === "") {
      showError(username, "Username is required.");

      username.focus();

      isValid = false;
    } else if (username.value.trim().length < 2) {
      showError(username, "Username must be at least 2 characters.");

      if (isValid) {
        username.focus();
      }

      isValid = false;
    }

    // =================================================
    // EMAIL VALIDATION
    // =================================================

    if (email.value.trim() === "") {
      showError(email, "Email is required.");

      if (isValid) {
        email.focus();
      }

      isValid = false;
    } else if (!email.value.includes("@")) {
      showError(email, "Please enter a valid email.");

      if (isValid) {
        email.focus();
      }

      isValid = false;
    }

    // =================================================
    // PASSWORD VALIDATION
    // =================================================

    if (password.value.trim() === "") {
      showError(password, "Password is required.");

      if (isValid) {
        password.focus();
      }

      isValid = false;
    } else if (!/^[A-Z]/.test(password.value)) {
      showError(password, "Password must start with a capital letter.");

      if (isValid) {
        password.focus();
      }

      isValid = false;
    } else if (!/[0-9]/.test(password.value)) {
      showError(password, "Password must contain at least one number.");

      if (isValid) {
        password.focus();
      }

      isValid = false;
    }

    // =================================================
    // CONFIRM PASSWORD VALIDATION
    // =================================================

    if (confirmPassword.value.trim() === "") {
      showError(confirmPassword, "Confirm Password is required.");

      if (isValid) {
        confirmPassword.focus();
      }

      isValid = false;
    } else if (password.value !== confirmPassword.value) {
      showError(confirmPassword, "Passwords do not match.");

      if (isValid) {
        confirmPassword.focus();
      }

      isValid = false;
    }

    // =================================================
    // TERMS VALIDATION
    // =================================================

    if (terms && !terms.checked) {
      const termsContainer = terms.closest("div");

      const error = document.createElement("p");

      error.className = "terms-js-error text-red-500 text-xs mt-1";

      error.textContent =
        "You must agree to the Terms of Service and Privacy Policy.";

      if (termsContainer) {
        termsContainer.appendChild(error);
      }

      if (isValid) {
        terms.focus();
      }

      isValid = false;
    }

    // =================================================
    // STOP IF FRONTEND VALIDATION FAILS
    // =================================================

    if (!isValid) {
      return;
    }

    // =================================================
    // SEND DATA TO BACKEND
    // =================================================

    try {
      const formData = new FormData(registerForm);

      const response = await fetch("BACKEND/register.php", {
        method: "POST",
        body: formData,
      });

      // =================================================
      // READ BACKEND RESPONSE
      // =================================================

      const data = await response.json();

      // =================================================
      // CLEAR BACKEND ERRORS
      // =================================================

      removeError(username);
      removeError(email);
      removeError(password);
      removeError(confirmPassword);

      const oldBackendTermsError =
        registerForm.querySelector(".terms-js-error");

      if (oldBackendTermsError) {
        oldBackendTermsError.remove();
      }

      // =================================================
      // BACKEND VALIDATION ERRORS
      // =================================================

      if (!data.success) {
        if (data.errors) {
          // -------------------------------------------------
          // USERNAME ERROR
          // -------------------------------------------------

          if (data.errors.username) {
            showError(username, data.errors.username);
          }

          // -------------------------------------------------
          // EMAIL ERROR
          // -------------------------------------------------

          if (data.errors.email) {
            showError(email, data.errors.email);
          }

          // -------------------------------------------------
          // PASSWORD ERROR
          // -------------------------------------------------

          if (data.errors.password) {
            showError(password, data.errors.password);
          }

          // -------------------------------------------------
          // CONFIRM PASSWORD ERROR
          // -------------------------------------------------

          if (data.errors.confirm_password) {
            showError(confirmPassword, data.errors.confirm_password);
          }

          // -------------------------------------------------
          // TERMS ERROR
          // -------------------------------------------------

          if (data.errors.terms) {
            const termsContainer = terms.closest("div");

            const error = document.createElement("p");

            error.className = "terms-js-error text-red-500 text-xs mt-1";

            error.textContent = data.errors.terms;

            if (termsContainer) {
              termsContainer.appendChild(error);
            }
          }
        }

        return;
      }

      // =================================================
      // REGISTER SUCCESS
      // =================================================

      if (data.success) {
        window.location.href = "index.php";

        return;
      }
    } catch (error) {
      console.error("Register Error:", error);
    }
  });
}

// =========================================================
// LOGIN VALIDATION
// =========================================================

const loginForm = document.getElementById("loginForm");

if (loginForm) {
  loginForm.addEventListener("submit", function (e) {
    let isValid = true;

    // -------------------------------------------------
    // GET FORM ELEMENTS
    // -------------------------------------------------

    const email = document.getElementById("loginEmail");

    const password = document.getElementById("pw");

    // -------------------------------------------------
    // CLEAR OLD ERRORS
    // -------------------------------------------------

    removeError(email);
    removeError(password);

    // =================================================
    // EMAIL VALIDATION
    // =================================================

    if (email.value.trim() === "") {
      showError(email, "Email is required.");

      email.focus();

      isValid = false;
    } else if (!email.value.includes("@")) {
      showError(email, "Please enter a valid email.");

      email.focus();

      isValid = false;
    }

    // =================================================
    // PASSWORD VALIDATION
    // =================================================

    if (password.value.trim() === "") {
      showError(password, "Password is required.");

      if (isValid) {
        password.focus();
      }

      isValid = false;
    }

    // =================================================
    // FINAL LOGIN VALIDATION
    // =================================================

    if (!isValid) {
      e.preventDefault();
    }
  });
}
