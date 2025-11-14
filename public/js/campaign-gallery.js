/**
 * Campaign Gallery JavaScript
 * Handles image gallery functionality for campaign pages
 */

// ===== Small Gallery (Donation Form) =====
let currentIndexSmall = 0;

/**
 * Change image in small gallery
 * @param {number} direction - Direction to navigate (-1 for prev, 1 for next)
 */
function changeImageSmall(direction) {
  const images = document.querySelectorAll(".gallery-image-small");
  const thumbnails = document.querySelectorAll(".thumbnail-small");

  if (images.length === 0) return;

  // Hide current image
  images[currentIndexSmall].classList.add("hidden");
  if (thumbnails[currentIndexSmall]) {
    thumbnails[currentIndexSmall].classList.remove("border-primary-600");
    thumbnails[currentIndexSmall].classList.add("border-gray-300");
  }

  // Calculate new index
  currentIndexSmall =
    (currentIndexSmall + direction + images.length) % images.length;

  // Show new image
  images[currentIndexSmall].classList.remove("hidden");
  if (thumbnails[currentIndexSmall]) {
    thumbnails[currentIndexSmall].classList.remove("border-gray-300");
    thumbnails[currentIndexSmall].classList.add("border-primary-600");
  }

  // Update counter
  const counter = document.querySelector(".current-image-small");
  if (counter) {
    counter.textContent = currentIndexSmall + 1;
  }
}

/**
 * Go to specific image in small gallery
 * @param {number} index - Index of image to display
 */
function goToImageSmall(index) {
  const images = document.querySelectorAll(".gallery-image-small");
  const thumbnails = document.querySelectorAll(".thumbnail-small");

  if (images.length === 0 || index < 0 || index >= images.length) return;

  // Hide current
  images[currentIndexSmall].classList.add("hidden");
  if (thumbnails[currentIndexSmall]) {
    thumbnails[currentIndexSmall].classList.remove("border-primary-600");
    thumbnails[currentIndexSmall].classList.add("border-gray-300");
  }

  // Show selected
  currentIndexSmall = index;
  images[currentIndexSmall].classList.remove("hidden");
  if (thumbnails[currentIndexSmall]) {
    thumbnails[currentIndexSmall].classList.remove("border-gray-300");
    thumbnails[currentIndexSmall].classList.add("border-primary-600");
  }

  // Update counter
  const counter = document.querySelector(".current-image-small");
  if (counter) {
    counter.textContent = currentIndexSmall + 1;
  }
}

// ===== Main Gallery (Campaign Detail) =====
let currentMainIndex = 0;

/**
 * Change image in main gallery
 * @param {number} direction - Direction to navigate (-1 for prev, 1 for next)
 */
function changeMainImage(direction) {
  const images = document.querySelectorAll(".gallery-main-image");
  const thumbnails = document.querySelectorAll(".thumbnail-main");

  if (images.length === 0) return;

  // Hide current image
  images[currentMainIndex].classList.add("hidden");
  if (thumbnails[currentMainIndex]) {
    thumbnails[currentMainIndex].classList.remove(
      "border-primary-600",
      "ring-2",
      "ring-primary-300"
    );
    thumbnails[currentMainIndex].classList.add("border-gray-300");
  }

  // Calculate new index
  currentMainIndex =
    (currentMainIndex + direction + images.length) % images.length;

  // Show new image
  images[currentMainIndex].classList.remove("hidden");
  if (thumbnails[currentMainIndex]) {
    thumbnails[currentMainIndex].classList.remove("border-gray-300");
    thumbnails[currentMainIndex].classList.add(
      "border-primary-600",
      "ring-2",
      "ring-primary-300"
    );
  }

  // Update counter
  const counter = document.querySelector(".current-main-image");
  if (counter) {
    counter.textContent = currentMainIndex + 1;
  }
}

/**
 * Go to specific image in main gallery
 * @param {number} index - Index of image to display
 */
function goToMainImage(index) {
  const images = document.querySelectorAll(".gallery-main-image");
  const thumbnails = document.querySelectorAll(".thumbnail-main");

  if (images.length === 0 || index < 0 || index >= images.length) return;

  // Hide current
  images[currentMainIndex].classList.add("hidden");
  if (thumbnails[currentMainIndex]) {
    thumbnails[currentMainIndex].classList.remove(
      "border-primary-600",
      "ring-2",
      "ring-primary-300"
    );
    thumbnails[currentMainIndex].classList.add("border-gray-300");
  }

  // Show selected
  currentMainIndex = index;
  images[currentMainIndex].classList.remove("hidden");
  if (thumbnails[currentMainIndex]) {
    thumbnails[currentMainIndex].classList.remove("border-gray-300");
    thumbnails[currentMainIndex].classList.add(
      "border-primary-600",
      "ring-2",
      "ring-primary-300"
    );
  }

  // Update counter
  const counter = document.querySelector(".current-main-image");
  if (counter) {
    counter.textContent = currentMainIndex + 1;
  }
}

// ===== Fullscreen Gallery =====

/**
 * Open fullscreen gallery modal
 */
function openFullscreen() {
  const modal = document.getElementById("fullscreen-modal");
  const container = document.getElementById("fullscreen-image-container");
  const images = document.querySelectorAll(".gallery-main-image");

  if (!modal || !container || images.length === 0) return;

  // Clear container
  container.innerHTML = "";

  // Add all images to fullscreen
  images.forEach((img, index) => {
    const fullImg = document.createElement("img");
    fullImg.src = img.src;
    fullImg.alt = img.alt;
    fullImg.className =
      "fullscreen-img " + (index === currentMainIndex ? "" : "hidden");
    fullImg.dataset.fullscreenIndex = index;
    container.appendChild(fullImg);
  });

  // Update counter
  updateFullscreenCounter();

  // Show modal
  modal.classList.remove("hidden");
  document.body.style.overflow = "hidden";
}

/**
 * Close fullscreen gallery modal
 */
function closeFullscreen() {
  const modal = document.getElementById("fullscreen-modal");
  if (!modal) return;

  modal.classList.add("hidden");
  document.body.style.overflow = "auto";
}

/**
 * Change image in fullscreen mode
 * @param {number} direction - Direction to navigate (-1 for prev, 1 for next)
 */
function changeFullscreenImage(direction) {
  const images = document.querySelectorAll(".fullscreen-img");

  if (images.length === 0) return;

  // Hide current
  images[currentMainIndex].classList.add("hidden");

  // Calculate new index
  currentMainIndex =
    (currentMainIndex + direction + images.length) % images.length;

  // Show new
  images[currentMainIndex].classList.remove("hidden");

  // Update main gallery sync
  syncMainGallery();

  // Update counters
  updateFullscreenCounter();
}

/**
 * Sync main gallery with fullscreen selection
 */
function syncMainGallery() {
  const mainImages = document.querySelectorAll(".gallery-main-image");
  const thumbnails = document.querySelectorAll(".thumbnail-main");

  // Hide all and remove highlights
  mainImages.forEach((img) => img.classList.add("hidden"));
  thumbnails.forEach((thumb) => {
    thumb.classList.remove("border-primary-600", "ring-2", "ring-primary-300");
    thumb.classList.add("border-gray-300");
  });

  // Show current
  if (mainImages[currentMainIndex]) {
    mainImages[currentMainIndex].classList.remove("hidden");
  }
  if (thumbnails[currentMainIndex]) {
    thumbnails[currentMainIndex].classList.remove("border-gray-300");
    thumbnails[currentMainIndex].classList.add(
      "border-primary-600",
      "ring-2",
      "ring-primary-300"
    );
  }

  // Update main counter
  const mainCounter = document.querySelector(".current-main-image");
  if (mainCounter) {
    mainCounter.textContent = currentMainIndex + 1;
  }
}

/**
 * Update fullscreen counter display
 */
function updateFullscreenCounter() {
  const counter = document.getElementById("fullscreen-counter");
  const total = document.querySelectorAll(".fullscreen-img").length;

  if (counter) {
    counter.textContent = `${currentMainIndex + 1} / ${total}`;
  }
}

// ===== Event Listeners =====

/**
 * Initialize gallery on page load
 */
document.addEventListener("DOMContentLoaded", function () {
  // Initialize counters
  const smallCounter = document.querySelector(".current-image-small");
  const mainCounter = document.querySelector(".current-main-image");

  if (smallCounter) {
    smallCounter.textContent = "1";
  }

  if (mainCounter) {
    mainCounter.textContent = "1";
  }

  // Setup keyboard navigation for fullscreen
  document.addEventListener("keydown", function (e) {
    const modal = document.getElementById("fullscreen-modal");

    if (modal && !modal.classList.contains("hidden")) {
      if (e.key === "ArrowLeft") {
        e.preventDefault();
        changeFullscreenImage(-1);
      } else if (e.key === "ArrowRight") {
        e.preventDefault();
        changeFullscreenImage(1);
      } else if (e.key === "Escape") {
        e.preventDefault();
        closeFullscreen();
      }
    }
  });

  // Close fullscreen on click outside image
  const modal = document.getElementById("fullscreen-modal");
  if (modal) {
    modal.addEventListener("click", function (e) {
      if (e.target === modal) {
        closeFullscreen();
      }
    });
  }
});

// ===== Touch Support =====

/**
 * Add touch swipe support for mobile devices
 */
(function () {
  let touchStartX = 0;
  let touchEndX = 0;

  function handleSwipe(element, onSwipeLeft, onSwipeRight) {
    if (!element) return;

    element.addEventListener(
      "touchstart",
      function (e) {
        touchStartX = e.changedTouches[0].screenX;
      },
      false
    );

    element.addEventListener(
      "touchend",
      function (e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipeGesture(onSwipeLeft, onSwipeRight);
      },
      false
    );
  }

  function handleSwipeGesture(onSwipeLeft, onSwipeRight) {
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;

    if (Math.abs(diff) > swipeThreshold) {
      if (diff > 0 && onSwipeLeft) {
        onSwipeLeft();
      } else if (diff < 0 && onSwipeRight) {
        onSwipeRight();
      }
    }
  }

  // Apply to galleries when DOM is ready
  document.addEventListener("DOMContentLoaded", function () {
    const smallGallery = document.querySelector(".campaign-gallery-small");
    const mainGallery = document.querySelector(".campaign-gallery-main");
    const fullscreenModal = document.getElementById("fullscreen-modal");

    if (smallGallery) {
      handleSwipe(
        smallGallery,
        () => changeImageSmall(1),
        () => changeImageSmall(-1)
      );
    }

    if (mainGallery) {
      handleSwipe(
        mainGallery,
        () => changeMainImage(1),
        () => changeMainImage(-1)
      );
    }

    if (fullscreenModal) {
      handleSwipe(
        fullscreenModal,
        () => changeFullscreenImage(1),
        () => changeFullscreenImage(-1)
      );
    }
  });
})();
