$(document).ready(function () {
  // Initialize Materialize components
  M.AutoInit();

  let allProductsData = []; // To store all products initially fetched
  let cart = JSON.parse(localStorage.getItem("shoppingCart")) || [];

  // Function to render categories
  function renderCategories(categories) {
    const categoryList = $("#category-list");
    categoryList.find('a:not([data-category-code="all"])').remove(); // Clear existing dynamic categories

    categories.forEach((category) => {
      const categoryItem = $(
        `<a href="#!" class="collection-item" data-category-code="${category.kode}">${category.nama}</a>`
      );
      categoryList.append(categoryItem);
    });
  }

  // Function to render products
  function renderProducts(products) {
    const productList = $("#product-list");
    productList.find(".col.s12.m6.l4").remove(); // Clear existing products
    $("#products-loading").hide(); // Hide loading indicator

    if (products.length === 0) {
      productList.append(
        '<p class="center-align">No products found in this category.</p>'
      );
      return;
    }

    products.forEach((product) => {
      const productCard = `
        <div class="col s12 m6 l4">
            <div class="card">
                <div class="card-image">
                    <img src="${origin}/uploads/produk/${
        product.gambar
      }" alt="${product.nama}">
                    <a class="btn-floating halfway-fab waves-effect waves-light red add-to-cart-btn" data-product='${JSON.stringify(
                      product
                    )}'><i class="material-icons">add_shopping_cart</i></a>
                </div>
                <div class="card-content">
                    <span class="card-title truncate">${product.nama}</span>
                    <p class="truncate">${product.deskripsi}</p>
                    <p><strong>Price:</strong> Rp ${parseFloat(
                      product.harga
                    ).toLocaleString("id-ID")}</p>
                    <p><strong>Stock:</strong> ${product.stok}</p>
                </div>
                <div class="card-action">
                    <a href="#!" class="view-details-btn" data-product='${JSON.stringify(
                      product
                    )}'>View Details</a>
                </div>
            </div>
        </div>
      `;
      productList.append(productCard);
    });

    // Re-initialize Materialbox for new images
    $(".materialboxed").materialbox();
  }

  // Function to update the cart display
  function updateCartDisplay() {
    const cartItemsList = $("#cart-items");
    cartItemsList.find(".cart-item").remove(); // Clear existing cart items
    $("#empty-cart-message").show();
    let total = 0;

    if (cart.length > 0) {
      $("#empty-cart-message").hide();
      cart.forEach((item, index) => {
        const itemTotal = parseFloat(item.harga) * item.quantity;
        total += itemTotal;
        const cartItemHtml = `
          <li class="collection-item cart-item">
              <div>
                  ${item.nama} x ${item.quantity}
                  <a href="#!" class="secondary-content remove-from-cart" data-index="${index}"><i class="material-icons red-text">delete</i></a>
                  <span class="secondary-content">Rp ${itemTotal.toLocaleString(
                    "id-ID"
                  )}</span>
              </div>
          </li>
        `;
        cartItemsList.append(cartItemHtml);
      });
    }

    $("#cart-total").text(`Rp ${total.toLocaleString("id-ID")}`);
    localStorage.setItem("shoppingCart", JSON.stringify(cart));
  }

  // Add product to cart
  $(document).on("click", ".add-to-cart-btn", function () {
    const productData = $(this).data("product");
    const existingItem = cart.find((item) => item.id === productData.id);

    if (existingItem) {
      if (existingItem.quantity < productData.stok) {
        existingItem.quantity++;
        M.toast({ html: `${productData.nama} quantity updated in cart!` });
      } else {
        M.toast({ html: `Max stock reached for ${productData.nama}!` });
      }
    } else {
      cart.push({ ...productData, quantity: 1 });
      M.toast({ html: `${productData.nama} added to cart!` });
    }
    updateCartDisplay();
  });

  // Add product from modal to cart
  $(document).on(
    "click",
    "#product-detail-modal .add-to-cart-button",
    function () {
      const productId = $(this).data("product-id");
      const quantity = parseInt($("#modal-product-quantity").val());
      const productData = allProductsData.find((p) => p.id === productId);

      if (!productData || quantity <= 0) {
        M.toast({ html: "Invalid quantity or product data." });
        return;
      }

      const existingItem = cart.find((item) => item.id === productData.id);

      if (existingItem) {
        if (existingItem.quantity + quantity <= productData.stok) {
          existingItem.quantity += quantity;
          M.toast({ html: `${productData.nama} quantity updated in cart!` });
        } else {
          M.toast({
            html: `Cannot add more. Only ${
              productData.stok - existingItem.quantity
            } more available for ${productData.nama}!`,
          });
        }
      } else {
        if (quantity <= productData.stok) {
          cart.push({ ...productData, quantity: quantity });
          M.toast({ html: `${productData.nama} added to cart!` });
        } else {
          M.toast({
            html: `Only ${productData.stok} available for ${productData.nama}!`,
          });
        }
      }
      updateCartDisplay();
      $("#product-detail-modal").modal("close");
    }
  );

  // Remove item from cart
  $(document).on("click", ".remove-from-cart", function () {
    const indexToRemove = $(this).data("index");
    const removedItem = cart.splice(indexToRemove, 1)[0];
    M.toast({ html: `${removedItem.nama} removed from cart.` });
    updateCartDisplay();
  });

  // Filter products by category
  $(document).on("click", "#category-list .collection-item", function () {
    $("#category-list .collection-item")
      .removeClass("active teal lighten-2 white-text")
      .addClass("grey lighten-3 black-text");
    $(this)
      .addClass("active teal lighten-2 white-text")
      .removeClass("grey lighten-3 black-text");

    const categoryCode = $(this).data("category-code");
    if (categoryCode === "all") {
      renderProducts(allProductsData);
    } else {
      const filteredProducts = allProductsData.filter(
        (product) => product.kategori_kode === categoryCode
      );
      renderProducts(filteredProducts);
    }
  });

  // View product details in modal
  $(document).on("click", ".view-details-btn", function () {
    const product = $(this).data("product");
    $("#modal-product-name").text(product.nama);
    $("#modal-product-image").attr(
      "src",
      `${origin}/uploads/produk/${product.gambar}`
    );
    $("#modal-product-description").text(product.deskripsi);
    $("#modal-product-price").text(
      `Rp ${parseFloat(product.harga).toLocaleString("id-ID")}`
    );
    $("#modal-product-stock").text(product.stok);
    $("#modal-product-category").text(
      product.kategori ? product.kategori.nama : "N/A"
    );
    $("#modal-product-quantity").val(1).attr("max", product.stok); // Reset quantity and set max
    $("#product-detail-modal .add-to-cart-button").data(
      "product-id",
      product.id
    ); // Set product ID for adding to cart
    M.updateTextFields(); // Update Materialize text fields
    $("#product-detail-modal").modal("open");
  });

  // Checkout button click
  $("#checkout-button").on("click", function () {
    if (cart.length === 0) {
      M.toast({
        html: "Your cart is empty. Please add items before checking out.",
      });
      return;
    }
    // In a real application, you'd check server-side if the user is logged in
    // For this example, we'll assume a client-side check or direct modal open
    const isLoggedIn = false; // This should be determined by your backend/auth status

    if (isLoggedIn) {
      // Redirect to a secure checkout page or initiate payment gateway
      M.toast({ html: "Proceeding to payment gateway..." });
      // window.location.href = '/checkout/payment'; // Example redirect
    } else {
      $("#checkout-modal").modal("open");
    }
  });

  // Fetch data
  cloud
    .add(origin + "/api/v2/kategori", {
      name: "kategori",
      callback: (data) => {
        renderCategories(data);
      },
    })
    .add(origin + "/api/v2/produk", {
      name: "produk",
      callback: (data) => {
        allProductsData = data; // Store all products
        renderProducts(data); // Initially render all products
      },
    })
    .then(() => {
      console.log("Kategori and Produk loaded");
      $(".preloader").slideUp(); // Hide preloader after data is loaded
      updateCartDisplay(); // Load cart from local storage and display
    })
    .catch((error) => {
      console.error("Error loading data:", error);
      $(".preloader").slideUp(); // Hide preloader even on error
      M.toast({ html: "Failed to load products. Please try again later." });
    });

  // Initial cart display on page load
  updateCartDisplay();
});
