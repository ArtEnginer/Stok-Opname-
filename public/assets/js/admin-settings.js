/**
 * Admin Settings Management JavaScript
 */

const API_BASE = window.location.origin;
let currentSettings = {};

// Initialize on page load
document.addEventListener("DOMContentLoaded", function () {
  setupTabs();
  loadAllSettings();
  setupFileInputs();
});

/**
 * Setup tab navigation
 */
function setupTabs() {
  const tabs = document.querySelectorAll(".tab");
  const contents = document.querySelectorAll(".tab-content");

  tabs.forEach((tab) => {
    tab.addEventListener("click", function () {
      const targetTab = this.dataset.tab;

      // Remove active class from all tabs and contents
      tabs.forEach((t) => t.classList.remove("active"));
      contents.forEach((c) => c.classList.remove("active"));

      // Add active class to clicked tab and corresponding content
      this.classList.add("active");
      document.getElementById(targetTab).classList.add("active");
    });
  });
}

/**
 * Setup file input handlers
 */
function setupFileInputs() {
  // Logo upload
  document.getElementById("app_logo").addEventListener("change", function () {
    handleFileSelect(this, "logo_preview");
  });

  // Favicon upload
  document
    .getElementById("app_favicon")
    .addEventListener("change", function () {
      handleFileSelect(this, "favicon_preview");
    });
}

/**
 * Handle file selection and preview
 */
function handleFileSelect(input, previewId) {
  const file = input.files[0];
  const preview = document.getElementById(previewId);

  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
    };
    reader.readAsDataURL(file);
  }
}

/**
 * Load all settings from API
 */
async function loadAllSettings() {
  showLoading(true);

  try {
    const response = await fetch(`${API_BASE}/admin/settings/grouped`);
    const data = await response.json();

    if (data.status === "success") {
      currentSettings = data.data;
      populateSettings();
      updateMidtransEnvBadge();
    } else {
      showAlert("error", "Failed to load settings");
    }
  } catch (error) {
    console.error("Error loading settings:", error);
    showAlert("error", "Error loading settings: " + error.message);
  } finally {
    showLoading(false);
  }
}

/**
 * Populate form fields with settings data
 */
function populateSettings() {
  // General settings
  setInputValue("app_name", currentSettings.general?.app_name?.value);
  setInputValue(
    "app_description",
    currentSettings.general?.app_description?.value
  );
  setInputValue("app_email", currentSettings.general?.app_email?.value);
  setInputValue("app_phone", currentSettings.general?.app_phone?.value);
  setInputValue("app_address", currentSettings.general?.app_address?.value);

  // Show logo/favicon preview if exists
  if (currentSettings.general?.app_logo?.value) {
    document.getElementById(
      "logo_preview"
    ).innerHTML = `<img src="${API_BASE}/writable/uploads/settings/${currentSettings.general.app_logo.value}" alt="Logo">`;
  }
  if (currentSettings.general?.app_favicon?.value) {
    document.getElementById(
      "favicon_preview"
    ).innerHTML = `<img src="${API_BASE}/writable/uploads/settings/${currentSettings.general.app_favicon.value}" alt="Favicon">`;
  }

  // Payment settings
  setInputValue(
    "midtrans_server_key",
    currentSettings.payment?.midtrans_server_key?.value
  );
  setInputValue(
    "midtrans_client_key",
    currentSettings.payment?.midtrans_client_key?.value
  );
  setInputValue(
    "midtrans_merchant_id",
    currentSettings.payment?.midtrans_merchant_id?.value
  );
  setCheckboxValue(
    "midtrans_is_production",
    currentSettings.payment?.midtrans_is_production?.value
  );
  setCheckboxValue(
    "midtrans_is_sanitized",
    currentSettings.payment?.midtrans_is_sanitized?.value
  );
  setCheckboxValue(
    "midtrans_is_3ds",
    currentSettings.payment?.midtrans_is_3ds?.value
  );

  // Social media settings
  setInputValue(
    "social_facebook",
    currentSettings.social?.social_facebook?.value
  );
  setInputValue(
    "social_twitter",
    currentSettings.social?.social_twitter?.value
  );
  setInputValue(
    "social_instagram",
    currentSettings.social?.social_instagram?.value
  );
  setInputValue(
    "social_linkedin",
    currentSettings.social?.social_linkedin?.value
  );
  setInputValue(
    "social_youtube",
    currentSettings.social?.social_youtube?.value
  );

  // SEO settings
  setInputValue("seo_meta_title", currentSettings.seo?.seo_meta_title?.value);
  setInputValue(
    "seo_meta_description",
    currentSettings.seo?.seo_meta_description?.value
  );
  setInputValue(
    "seo_meta_keywords",
    currentSettings.seo?.seo_meta_keywords?.value
  );

  // Email settings
  setInputValue(
    "email_from_name",
    currentSettings.email?.email_from_name?.value
  );
  setInputValue(
    "email_from_address",
    currentSettings.email?.email_from_address?.value
  );

  // System settings
  setCheckboxValue(
    "maintenance_mode",
    currentSettings.system?.maintenance_mode?.value
  );
  setCheckboxValue(
    "enable_registration",
    currentSettings.system?.enable_registration?.value
  );
}

/**
 * Helper to set input value
 */
function setInputValue(id, value) {
  const element = document.getElementById(id);
  if (element && value !== undefined && value !== null) {
    element.value = value;
  }
}

/**
 * Helper to set checkbox value
 */
function setCheckboxValue(id, value) {
  const element = document.getElementById(id);
  if (element) {
    element.checked = Boolean(value);
  }
}

/**
 * Save General Settings
 */
async function saveGeneralSettings() {
  showLoading(true);

  try {
    const settings = [
      { setting_key: "app_name", setting_value: getValue("app_name") },
      {
        setting_key: "app_description",
        setting_value: getValue("app_description"),
      },
      { setting_key: "app_email", setting_value: getValue("app_email") },
      { setting_key: "app_phone", setting_value: getValue("app_phone") },
      { setting_key: "app_address", setting_value: getValue("app_address") },
    ];

    await updateBatchSettings(settings);

    // Upload logo if selected
    const logoFile = document.getElementById("app_logo").files[0];
    if (logoFile) {
      await uploadFile("app_logo", logoFile);
    }

    // Upload favicon if selected
    const faviconFile = document.getElementById("app_favicon").files[0];
    if (faviconFile) {
      await uploadFile("app_favicon", faviconFile);
    }

    showAlert("success", "General settings saved successfully!");
    await loadAllSettings();
  } catch (error) {
    showAlert("error", "Failed to save settings: " + error.message);
  } finally {
    showLoading(false);
  }
}

/**
 * Save Payment Settings
 */
async function savePaymentSettings() {
  showLoading(true);

  try {
    const data = {
      midtrans_server_key: getValue("midtrans_server_key"),
      midtrans_client_key: getValue("midtrans_client_key"),
      midtrans_merchant_id: getValue("midtrans_merchant_id"),
      midtrans_is_production: getCheckboxValue("midtrans_is_production")
        ? "1"
        : "0",
      midtrans_is_sanitized: getCheckboxValue("midtrans_is_sanitized")
        ? "1"
        : "0",
      midtrans_is_3ds: getCheckboxValue("midtrans_is_3ds") ? "1" : "0",
    };

    const response = await fetch(
      `${API_BASE}/admin/settings/payment/midtrans`,
      {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      }
    );

    const result = await response.json();

    if (result.status === "success") {
      showAlert("success", "Payment settings saved successfully!");
      updateMidtransEnvBadge();
      await loadAllSettings();
    } else {
      showAlert("error", result.message || "Failed to save payment settings");
    }
  } catch (error) {
    showAlert("error", "Failed to save payment settings: " + error.message);
  } finally {
    showLoading(false);
  }
}

/**
 * Save Social Settings
 */
async function saveSocialSettings() {
  showLoading(true);

  try {
    const settings = [
      {
        setting_key: "social_facebook",
        setting_value: getValue("social_facebook"),
      },
      {
        setting_key: "social_twitter",
        setting_value: getValue("social_twitter"),
      },
      {
        setting_key: "social_instagram",
        setting_value: getValue("social_instagram"),
      },
      {
        setting_key: "social_linkedin",
        setting_value: getValue("social_linkedin"),
      },
      {
        setting_key: "social_youtube",
        setting_value: getValue("social_youtube"),
      },
    ];

    await updateBatchSettings(settings);
    showAlert("success", "Social media links saved successfully!");
    await loadAllSettings();
  } catch (error) {
    showAlert("error", "Failed to save social settings: " + error.message);
  } finally {
    showLoading(false);
  }
}

/**
 * Save SEO Settings
 */
async function saveSEOSettings() {
  showLoading(true);

  try {
    const settings = [
      {
        setting_key: "seo_meta_title",
        setting_value: getValue("seo_meta_title"),
      },
      {
        setting_key: "seo_meta_description",
        setting_value: getValue("seo_meta_description"),
      },
      {
        setting_key: "seo_meta_keywords",
        setting_value: getValue("seo_meta_keywords"),
      },
    ];

    await updateBatchSettings(settings);
    showAlert("success", "SEO settings saved successfully!");
    await loadAllSettings();
  } catch (error) {
    showAlert("error", "Failed to save SEO settings: " + error.message);
  } finally {
    showLoading(false);
  }
}

/**
 * Save Email Settings
 */
async function saveEmailSettings() {
  showLoading(true);

  try {
    const settings = [
      {
        setting_key: "email_from_name",
        setting_value: getValue("email_from_name"),
      },
      {
        setting_key: "email_from_address",
        setting_value: getValue("email_from_address"),
      },
    ];

    await updateBatchSettings(settings);
    showAlert("success", "Email settings saved successfully!");
    await loadAllSettings();
  } catch (error) {
    showAlert("error", "Failed to save email settings: " + error.message);
  } finally {
    showLoading(false);
  }
}

/**
 * Save System Settings
 */
async function saveSystemSettings() {
  showLoading(true);

  try {
    const settings = [
      {
        setting_key: "maintenance_mode",
        setting_value: getCheckboxValue("maintenance_mode") ? "1" : "0",
      },
      {
        setting_key: "enable_registration",
        setting_value: getCheckboxValue("enable_registration") ? "1" : "0",
      },
    ];

    await updateBatchSettings(settings);
    showAlert("success", "System settings saved successfully!");
    await loadAllSettings();
  } catch (error) {
    showAlert("error", "Failed to save system settings: " + error.message);
  } finally {
    showLoading(false);
  }
}

/**
 * Update multiple settings at once
 */
async function updateBatchSettings(settings) {
  const response = await fetch(`${API_BASE}/admin/settings/batch`, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ settings }),
  });

  const result = await response.json();

  if (result.status !== "success") {
    throw new Error(result.message || "Failed to update settings");
  }

  return result;
}

/**
 * Upload file for setting
 */
async function uploadFile(settingKey, file) {
  const formData = new FormData();
  formData.append("file", file);

  const response = await fetch(
    `${API_BASE}/admin/settings/${settingKey}/upload`,
    {
      method: "POST",
      body: formData,
    }
  );

  const result = await response.json();

  if (result.status !== "success") {
    throw new Error(result.message || "Failed to upload file");
  }

  return result;
}

/**
 * Test Midtrans connection
 */
async function testMidtransConnection() {
  showLoading(true);

  try {
    const data = {
      server_key: getValue("midtrans_server_key"),
      client_key: getValue("midtrans_client_key"),
      is_production: getCheckboxValue("midtrans_is_production"),
    };

    const response = await fetch(`${API_BASE}/admin/midtrans/test-connection`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    });

    const result = await response.json();

    if (result.status === "success" && result.data.connected) {
      showAlert(
        "success",
        `✅ Midtrans connection successful! (${result.data.environment})`
      );
    } else {
      showAlert(
        "error",
        "❌ Midtrans connection failed: " + (result.error || "Unknown error")
      );
    }
  } catch (error) {
    showAlert("error", "Failed to test connection: " + error.message);
  } finally {
    showLoading(false);
  }
}

/**
 * Update Midtrans environment badge
 */
function updateMidtransEnvBadge() {
  const isProduction = currentSettings.payment?.midtrans_is_production?.value;
  const badge = document.getElementById("midtrans-env-badge");

  if (badge) {
    if (isProduction) {
      badge.textContent = "Production";
      badge.className = "badge badge-success";
    } else {
      badge.textContent = "Sandbox";
      badge.className = "badge badge-warning";
    }
  }
}

/**
 * Helper functions
 */
function getValue(id) {
  const element = document.getElementById(id);
  return element ? element.value : "";
}

function getCheckboxValue(id) {
  const element = document.getElementById(id);
  return element ? element.checked : false;
}

function showLoading(show) {
  const loading = document.getElementById("loading");
  if (loading) {
    loading.classList.toggle("active", show);
  }
}

function showAlert(type, message) {
  const container = document.getElementById("alert-container");
  const alertClass = type === "success" ? "alert-success" : "alert-error";

  const alert = document.createElement("div");
  alert.className = `alert ${alertClass}`;
  alert.textContent = message;

  container.innerHTML = "";
  container.appendChild(alert);

  // Auto remove after 5 seconds
  setTimeout(() => {
    alert.remove();
  }, 5000);

  // Scroll to top to show alert
  window.scrollTo({ top: 0, behavior: "smooth" });
}
