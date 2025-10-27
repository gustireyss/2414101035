// --- GLOBAL VARIABLES & DATA (SHARED) ---
const CUSTOMER_VIEW_ID = 'customer-view';
const ADMIN_VIEW_ID = 'admin-view';
const AUTH_AREA_ID = 'auth-area';
const ADMIN_PASSWORD = "admin"; // **PASSWORD ADMIN**
const customerView = document.getElementById(CUSTOMER_VIEW_ID);
const adminView = document.getElementById(ADMIN_VIEW_ID);
const authArea = document.getElementById(AUTH_AREA_ID);
const footerText = document.getElementById('footer-text');


// Data Produk (Menggunakan LocalStorage agar data produk persisten)
let products = JSON.parse(localStorage.getItem("products")) || [
    { id: 1, name: "Bayam", price: 5000, stock: 50, unit: "ikat", image: "https://pasarsegar.co.id/wp-content/uploads/2020/12/name-222-1.jpg" },
    { id: 2, name: "Kangkung", price: 4500, stock: 40, unit: "ikat", image: "https://www.static-src.com/wcsstore/Indraprastha/images/catalog/full//96/MTA-10783157/hypermart_daun-kangkung-ikat-150-gr_full01.jpg" },
    { id: 3, name: "Wortel", price: 8000, stock: 30, unit: "kg", image: "https://balifoodstore.com/10-large_default/wortel-500gr.jpg" },
    { id: 4, name: "Tempe", price: 6000, stock: 30, unit: "bungkus", image: "https://i0.wp.com/halalmui.org/wp-content/uploads/2022/07/Fermentasi_Media_Padat_dari_Tempe_sampai_Enzim_11zon.jpg?w=1000&ssl=1" },
    { id: 5, name: "Kol", price: 3000, stock: 33, unit: "kg", image: "https://tse1.mm.bing.net/th?q=manfaat%20sayur%20kol%20mentah" },
];

// Keranjang Belanja (Customer)
let cart = JSON.parse(sessionStorage.getItem("customer_cart")) || [];


// --- VIEW MANAGEMENT & AUTH ---

function setView(viewMode) {
    if (viewMode === 'admin') {
        customerView.style.display = 'none';
        adminView.style.display = 'block';
        footerText.textContent = '© "Admin Panel" | Dibuat untuk manajemen Rey\'s Vegetables.';
        renderAdminProducts();
    } else { // 'customer'
        customerView.style.display = 'block';
        adminView.style.display = 'none';
        footerText.textContent = '© "Hidup sehat dimulai dari makanan sehat, dan sayur adalah salah satunya".';
        renderCustomerProducts();
        renderCart();
    }
}

function checkAdminLogin() {
    const password = prompt("Masukkan password Admin:");
    if (password === ADMIN_PASSWORD) {
        sessionStorage.setItem("isAdminLoggedIn", "true");
        renderAuthButton();
        setView('admin');
    } else if (password !== null) {
        alert("Password salah.");
    }
}

window.adminLogout = function() {
    sessionStorage.removeItem("isAdminLoggedIn");
    alert("Anda telah berhasil Logout.");
    renderAuthButton();
    setView('customer');
}

function renderAuthButton() {
    if (sessionStorage.getItem("isAdminLoggedIn") === "true") {
        authArea.innerHTML = `
            <button class="admin-link admin-btn-style" onclick="setView('admin')">Dashboard Admin 📊</button>
        `;
    } else {
        authArea.innerHTML = `
            <button class="admin-link" onclick="checkAdminLogin()">Admin Login</button>
        `;
    }
}


// --- CUSTOMER FUNCTIONS ---

const customerProductList = document.getElementById("customer-products");
const cartItemsContainer = document.querySelector(".cart-items");
const subtotalElement = document.getElementById("subtotal");
const grandTotalElement = document.getElementById("grand-total");
const cartEmptyMessage = document.getElementById("cart-empty-message");
const checkoutButton = document.getElementById("checkout-button");
const checkoutForm = document.getElementById("checkout-form");

function saveCart() {
    sessionStorage.setItem("customer_cart", JSON.stringify(cart));
}

window.renderCustomerProducts = function() {
    customerProductList.innerHTML = "";
    products.forEach((p) => {
        const item = document.createElement("article");
        item.classList.add("product");
        item.innerHTML = `
            <div class="thumb">
                <img src="${p.image}" alt="${p.name}">
            </div>
            <div class="pinfo">
                <h3>${p.name}</h3>
                <div class="meta">Stok: ${p.stock > 0 ? p.stock + ' ' + p.unit : 'Habis'}</div>
                <div class="price">Rp ${p.price.toLocaleString('id-ID')} / ${p.unit}</div>
                <div class="paction">
                    <button class="btn-cust" ${p.stock === 0 ? 'disabled' : ''} onclick="addToCart(${p.id})">Tambah ke Keranjang</button>
                </div>
            </div>
        `;
        customerProductList.appendChild(item);
    });
}

window.addToCart = function(productId) {
    const product = products.find(p => p.id === productId);
    if (!product || product.stock <= 0) {
        alert("Stok produk ini sedang kosong!");
        return;
    }
    const cartItem = cart.find(item => item.id === productId);
    if (cartItem) {
        if (cartItem.qty < product.stock) {
            cartItem.qty++;
        } else {
            alert(`Stok maksimal untuk ${product.name} adalah ${product.stock} ${product.unit}.`);
        }
    } else {
        cart.push({ id: product.id, name: product.name, price: product.price, unit: product.unit, qty: 1 });
    }
    saveCart();
    renderCart();
}

window.updateCartQty = function(productId, change) {
    const product = products.find(p => p.id === productId);
    const cartItem = cart.find(item => item.id === productId);
    if (!cartItem) return;
    cartItem.qty += change;
    if (cartItem.qty > product.stock) {
        cartItem.qty = product.stock;
        alert(`Stok maksimal untuk ${product.name} adalah ${product.stock} ${product.unit}.`);
    } else if (cartItem.qty <= 0) {
        cart = cart.filter(item => item.id !== productId);
    }
    saveCart();
    renderCart();
}

window.renderCart = function() {
    cartItemsContainer.innerHTML = "";
    let subtotal = 0;

    if (cart.length === 0) {
        cartEmptyMessage.style.display = 'block';
        checkoutButton.disabled = true;
        checkoutButton.textContent = `Buat Pemesanan (Rp 0)`;
    } else {
        cartEmptyMessage.style.display = 'none';
        checkoutButton.disabled = false;
    }

    cart.forEach((item) => {
        const totalItemPrice = item.price * item.qty;
        subtotal += totalItemPrice;
        const itemDiv = document.createElement("div");
        itemDiv.classList.add("cart-item");
        itemDiv.innerHTML = `
            <div class="item-info">
                <strong>${item.name}</strong>
                <small>Rp ${item.price.toLocaleString('id-ID')} / ${item.unit}</small>
                <small>Total: Rp ${totalItemPrice.toLocaleString('id-ID')}</small>
            </div>
            <div class="item-qty">
                <button class="qty-btn remove" onclick="updateCartQty(${item.id}, -1)">-</button>
                <span>${item.qty} ${item.unit}</span>
                <button class="qty-btn" onclick="updateCartQty(${item.id}, 1)">+</button>
            </div>
        `;
        cartItemsContainer.appendChild(itemDiv);
    });

    subtotalElement.textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
    grandTotalElement.textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
    checkoutButton.textContent = `Buat Pemesanan (Rp ${subtotal.toLocaleString('id-ID')})`;
}

checkoutForm.addEventListener('submit', function(e) {
    e.preventDefault();
    if (cart.length === 0) {
        alert("Keranjang belanja Anda kosong!");
        return;
    }
    const name = document.getElementById('input-name').value.trim();
    const phone = document.getElementById('input-phone').value.trim();
    const address = document.getElementById('input-address').value.trim();
    const paymentMethod = document.getElementById('input-payment-method').value;

    if (!name || !phone || !address) {
        alert("Mohon lengkapi semua data pemesanan: Nama, Nomor HP, dan Alamat.");
        return;
    }

    let orderDetails = "Halo Rey's Vegetables, saya ingin memesan:\n\n";
    let totalOrder = 0;

    cart.forEach(item => {
        const itemTotal = item.price * item.qty;
        totalOrder += itemTotal;
        orderDetails += `- *${item.name}*: ${item.qty} ${item.unit} (Rp ${itemTotal.toLocaleString('id-ID')})\n`;
    });

    orderDetails += `\n*TOTAL PESANAN:* Rp ${totalOrder.toLocaleString('id-ID')}`;
    orderDetails += `\n\n*DATA PENGIRIMAN:*\n`;
    orderDetails += `Nama: ${name}\n`;
    orderDetails += `HP: ${phone}\n`;
    orderDetails += `Alamat: ${address}\n`;
    orderDetails += `Metode Pembayaran: ${paymentMethod}`;
    orderDetails += `\n\n_Catatan: Biaya kirim akan dikonfirmasi via WA._`;

    const waNumber = "6281312545499"; 
    const message = encodeURIComponent(orderDetails);
    const waLink = `https://wa.me/${waNumber}?text=${message}`;

    window.open(waLink, '_blank');
    
    setTimeout(() => {
        if(confirm("Apakah Anda ingin mengosongkan keranjang setelah mengirim pesanan ke WhatsApp?")){
            cart = [];
            saveCart();
            renderCart();
            // Di sini seharusnya data pesanan ditambahkan ke LocalStorage/DB untuk dilihat Admin.
            alert("Pesanan berhasil dikirim ke WhatsApp. Kami akan segera menghubungi Anda!");
        }
    }, 500);
});


// --- ADMIN FUNCTIONS ---

const adminProductList = document.getElementById("admin-products");

function saveAdminProducts() {
    localStorage.setItem("products", JSON.stringify(products)); // Simpan ke key produk yang sama
}

window.renderAdminProducts = function() {
    adminProductList.innerHTML = "";
    products.forEach((p) => {
        const item = document.createElement("article");
        item.classList.add("product");
        item.innerHTML = `
            <div class="thumb">
                <img src="${p.image}" alt="${p.name}">
            </div>
            <div class="pinfo">
                <h3>${p.name}</h3>
                <div class="meta">Rp ${p.price.toLocaleString('id-ID')} / ${p.unit} • Stok ${p.stock}</div>
                <div class="paction">
                    <button class="btn-admin" onclick="editProduct(${p.id})">Edit</button>
                    <button class="btn-admin delete" onclick="deleteProduct(${p.id})">Hapus</button>
                </div>
            </div>
        `;
        adminProductList.appendChild(item);
    });
}

window.addProduct = function() {
    const name = prompt("Nama produk:");
    const price = parseInt(prompt("Harga produk (angka):"));
    const stock = parseInt(prompt("Stok produk (angka):"));
    const unit = prompt("Satuan (kg/ikat/bungkus):");
    const image = prompt("Link gambar produk (URL):");

    if (name && !isNaN(price) && !isNaN(stock) && unit && image) {
        const newProduct = {
            id: Date.now(),
            name, price, stock, unit, image
        };
        products.push(newProduct);
        saveAdminProducts();
        renderAdminProducts();
    } else {
        alert("Input tidak valid. Pastikan Harga dan Stok adalah angka.");
    }
}

window.editProduct = function(id) {
    const p = products.find((prod) => prod.id === id);
    if (!p) return;

    p.name = prompt("Nama produk:", p.name) || p.name;
    p.price = parseInt(prompt("Harga:", p.price)) || p.price;
    p.stock = parseInt(prompt("Stok:", p.stock)) || p.stock;
    p.unit = prompt("Satuan:", p.unit) || p.unit;
    p.image = prompt("Link gambar:", p.image) || p.image;

    saveAdminProducts();
    renderAdminProducts();
}

window.deleteProduct = function(id) {
    if (confirm("Yakin ingin menghapus produk ini?")) {
        products = products.filter((p) => p.id !== id);
        saveAdminProducts();
        renderAdminProducts();
    }
}

// Catatan: Fungsi updateOrderStatus dan deleteOrder di admin-view bersifat ilustratif
// karena data pesanan di HTML hanya statis. Untuk fungsionalitas penuh,
// pesanan juga harus disimpan di LocalStorage/DB.
window.updateOrderStatus = function(id, newStatus) {
    alert(`Mengubah status pesanan #${id} menjadi: ${newStatus}. (Fungsionalitas penuh memerlukan penyimpanan data pesanan)`);
    // Implementasi logika perubahan status dan re-render di sini
}

window.deleteOrder = function(id) {
    if (confirm(`Yakin ingin menghapus pesanan #${id}?`)) {
        alert(`Pesanan #${id} dihapus. (Fungsionalitas penuh memerlukan penyimpanan data pesanan)`);
        // Implementasi logika penghapusan dan re-render di sini
    }
}

// --- INITIALIZATION ---
// Panggil fungsi inisialisasi saat dokumen selesai dimuat
document.addEventListener('DOMContentLoaded', () => {
    // Tentukan tampilan awal berdasarkan status login admin
    if (sessionStorage.getItem("isAdminLoggedIn") === "true") {
        setView('admin');
    } else {
        setView('customer');
    }
    renderAuthButton(); 
});

/**
 * Fungsi untuk memformat angka menjadi format Rupiah (Rp).
 */
function formatRupiah(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID');
}

/**
 * Fungsi utama untuk menghitung ulang total keranjang, 
 * memperbarui tampilan, dan mengatur status tombol checkout.
 */
function updateCartAndButton() {
    
    // 1. Ambil semua item yang ada di keranjang
    //    (Kita asumsikan setiap item memiliki class 'cart-item'
    //     dan menyimpan harga/kuantitas di data-attributes)
    const cartItems = document.querySelectorAll('.cart-items .cart-item');
    
    let subtotal = 0;

    // 2. Hitung total harga
    cartItems.forEach(item => {
        // Ambil harga dan kuantitas dari data-attribute
        // Ini harus Anda atur saat menambahkan item ke keranjang
        const price = parseFloat(item.dataset.price) || 0;
        const quantity = parseInt(item.dataset.quantity) || 0;
        
        subtotal += (price * quantity);
    });

    // 3. Perbarui teks Subtotal dan Grand Total di HTML
    document.getElementById('subtotal').textContent = formatRupiah(subtotal);
    document.getElementById('grand-total').textContent = formatRupiah(subtotal);
    
    // --- INI LOGIKA UTAMA UNTUK TOMBOL ---
    
    // Ambil elemen tombolnya
    const checkoutButton = document.getElementById('checkout-button');

    // 4. Cek apakah totalnya lebih dari 0
    if (subtotal > 0) {
        // Jika ada isinya:
        // a. Hapus atribut 'disabled' agar tombol bisa diklik
        checkoutButton.disabled = false;
        // b. Perbarui teks tombolnya
        checkoutButton.textContent = 'Buat Pemesanan (' + formatRupiah(subtotal) + ')';
        
        // Tampilkan keranjang, sembunyikan pesan kosong
        document.getElementById('cart-empty-message').style.display = 'none';

    } else {
        // Jika keranjang kosong:
        // a. Tambahkan atribut 'disabled' agar tidak bisa diklik
        checkoutButton.disabled = true;
        // b. Kembalikan teks tombol ke default
        checkoutButton.textContent = 'Buat Pemesanan (Rp 0)';
        
        // Sembunyikan keranjang, tampilkan pesan kosong
        document.getElementById('cart-empty-message').style.display = 'block';
    }
}