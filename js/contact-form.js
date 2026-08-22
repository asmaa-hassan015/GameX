
document.addEventListener("DOMContentLoaded", function () {
  // =====================================================
  // FORM ELEMENTS
  // =====================================================

  const form = document.getElementById("contactForm");

  if (!form) {
    return;
  }

  const nameInput = document.getElementById("name");
  const emailInput = document.getElementById("email");
  const subjectInput = document.getElementById("subject");
  const messageInput = document.getElementById("message");

  const submitBtn = document.getElementById("submitBtn");
  const alertBox = document.getElementById("formAlert");

  // =====================================================
  // ERROR HANDLING
  // =====================================================

  function showError(input, message) {
    clearError(input);

    input.classList.add("border-red-500");

    const errorEl = document.createElement("p");

    errorEl.className = "error-message text-red-500 text-xs mt-1";

    errorEl.textContent = message;

    input.closest(".field-wrapper").appendChild(errorEl);
  }

  function clearError(input) {
    input.classList.remove("border-red-500");

    const wrapper = input.closest(".field-wrapper");

    const existingError = wrapper.querySelector(".error-message");

    if (existingError) {
      existingError.remove();
    }
  }

  // =====================================================
  // ALERT HANDLING
  // =====================================================

  function showAlert(message, type = "error") {
    alertBox.textContent = message;

    alertBox.className =
      "text-center text-sm mb-4 p-3 rounded-lg " +
      (type === "success"
        ? "bg-green-500/10 text-green-400 border border-green-500/30"
        : "bg-red-500/10 text-red-400 border border-red-500/30");

    alertBox.classList.remove("hidden");
  }

  function hideAlert() {
    alertBox.classList.add("hidden");
  }

  // =====================================================
  // VALIDATION - NAME
  // =====================================================

  function validateName() {
    const value = nameInput.value.trim();

    if (value.length < 3) {
      showError(nameInput, "Minimum 3 characters");

      return false;
    }

    clearError(nameInput);

    return true;
  }

  // =====================================================
  // VALIDATION - EMAIL
  // =====================================================

  function validateEmail() {
    const value = emailInput.value.trim();

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(value)) {
      showError(emailInput, "Please enter a valid email");

      return false;
    }

    clearError(emailInput);

    return true;
  }

  // =====================================================
  // VALIDATION - SUBJECT
  // =====================================================

  function validateSubject() {
    const value = subjectInput.value;

    if (!value || value === "Subject") {
      showError(subjectInput, "Please choose a subject");

      return false;
    }

    clearError(subjectInput);

    return true;
  }

  // =====================================================
  // VALIDATION - MESSAGE
  // =====================================================

  function validateMessage() {
    const value = messageInput.value.trim();

    if (value.length < 10) {
      showError(messageInput, "Minimum 10 characters");

      return false;
    }

    clearError(messageInput);

    return true;
  }

  // =====================================================
  // INPUT EVENTS
  // =====================================================

  nameInput.addEventListener("blur", validateName);

  emailInput.addEventListener("blur", validateEmail);

  subjectInput.addEventListener("change", validateSubject);

  messageInput.addEventListener("blur", validateMessage);

  // =====================================================
  // FORM SUBMIT
  // =====================================================

  form.addEventListener("submit", async function (e) {
    e.preventDefault();

    hideAlert();

    // =================================================
    // VALIDATE FORM
    // =================================================

    const isNameValid = validateName();

    const isEmailValid = validateEmail();

    const isSubjectValid = validateSubject();

    const isMessageValid = validateMessage();

    // =================================================
    // STOP IF INVALID
    // =================================================

    if (!isNameValid || !isEmailValid || !isSubjectValid || !isMessageValid) {
      showAlert("Please correct the wrong values");

      return;
    }

    // =================================================
    // LOADING STATE
    // =================================================

    const originalBtnText = submitBtn.innerHTML;

    submitBtn.disabled = true;

    submitBtn.innerHTML =
      '<i class="fa-solid fa-spinner fa-spin"></i> Sending, please wait...';

    // =================================================
    // SEND DATA TO PHP BACKEND
    // =================================================

    try {
      const formData = new FormData();

      formData.append("name", nameInput.value.trim());

      formData.append("email", emailInput.value.trim());

      formData.append("subject", subjectInput.value);

      formData.append("message", messageInput.value.trim());

      // =================================================
      // API REQUEST
      // =================================================

      const response = await fetch("contactus.php", {
        method: "POST",
        body: formData,
      });

      // =================================================
      // API RESPONSE
      // =================================================

      const result = await response.json();

      // =================================================
      // SUCCESS
      // =================================================

      if (result.success) {
        showAlert(result.message, "success");

        form.reset();
      }

      // =================================================
      // BACKEND VALIDATION ERRORS
      // =================================================
      else {
        if (result.errors) {
          if (result.errors.name) {
            showError(nameInput, result.errors.name);
          }

          if (result.errors.email) {
            showError(emailInput, result.errors.email);
          }

          if (result.errors.subject) {
            showError(subjectInput, result.errors.subject);
          }

          if (result.errors.message) {
            showError(messageInput, result.errors.message);
          }
        }

        showAlert(result.message || "There is a problem. Please try again.");
      }
    } catch (err) {
      // =================================================
      // CONNECTION ERROR
      // =================================================

      console.error("Contact form error:", err);

      showAlert("Connection error. Please try again.");
    } finally {
      // =================================================
      // RESTORE BUTTON
      // =================================================

      submitBtn.disabled = false;

      submitBtn.innerHTML = originalBtnText;
    }
  });
});

// =========================================================
// FAQ SECTION
// =========================================================

document.addEventListener("DOMContentLoaded", function () {
  // =====================================================
  // FAQ SECTION
  // =====================================================

  const faqSection = document.getElementById("faq-section");

  if (!faqSection) {
    return;
  }

  // =====================================================
  // FAQ BUTTONS
  // =====================================================

  const faqButtons = faqSection.querySelectorAll("button");

  // =====================================================
  // FAQ EVENTS
  // =====================================================

  faqButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      // =============================================
      // GET FAQ ELEMENTS
      // =============================================

      const answer = this.nextElementSibling;

      const arrow = this.querySelector(".fa-chevron-down");

      const isOpen = this.classList.contains("faq-open");

      // =============================================
      // CLOSE OTHER FAQ ITEMS
      // =============================================

      faqButtons.forEach(function (btn) {
        if (btn !== button) {
          btn.classList.remove("faq-open");

          btn.nextElementSibling.style.maxHeight = "0px";

          const otherArrow = btn.querySelector(".fa-chevron-down");

          if (otherArrow) {
            otherArrow.style.transform = "rotate(0deg)";
          }
        }
      });

      // =============================================
      // CLOSE CURRENT FAQ
      // =============================================

      if (isOpen) {
        this.classList.remove("faq-open");

        answer.style.maxHeight = "0px";

        if (arrow) {
          arrow.style.transform = "rotate(0deg)";
        }
      }

      // =============================================
      // OPEN CURRENT FAQ
      // =============================================
      else {
        this.classList.add("faq-open");

        answer.style.maxHeight = answer.scrollHeight + "px";

        if (arrow) {
          arrow.style.transform = "rotate(180deg)";
        }
      }
    });
  });
});
