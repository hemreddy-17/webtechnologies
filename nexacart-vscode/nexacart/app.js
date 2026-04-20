// ============================================
//  NexaCart — Shared App Logic
//  23CSE404 | Capstone Web Project
// ============================================

// ---- PRODUCT DATA ----
const PRODUCTS = [
  { id:1,  name:"Linen Blazer",           category:"Women",       price:3499, originalPrice:4999, img:"images/Linen_Blazer.jpg",           badge:"New",         tags:["women","blazer"] },
  { id:2,  name:"Classic White Shirt",    category:"Men",         price:1299, originalPrice:1799, img:"images/Classic_White_Shirt.jpg",    badge:"Best Seller", tags:["men","shirt"] },
  { id:3,  name:"Floral Midi Dress",      category:"Women",       price:2799, originalPrice:3999, img:"images/Floral_Midi_Dress.jpg",      badge:"Sale",        tags:["women","dress"] },
  { id:4,  name:"Slim Chinos",            category:"Men",         price:1799, originalPrice:2499, img:"images/Slim_Chinos.jpg",            badge:"",            tags:["men","pants"] },
  { id:5,  name:"Knit Turtleneck",        category:"Women",       price:2199, originalPrice:null, img:"images/Knit_Turtleneck.jpg",        badge:"New",         tags:["women","knitwear"] },
  { id:6,  name:"Leather Tote Bag",       category:"Accessories", price:4999, originalPrice:6499, img:"images/Leather_Tote_Bag.jpg",      badge:"Sale",        tags:["accessories","bags"] },
  { id:7,  name:"Oversized Denim Jacket", category:"Men",         price:3299, originalPrice:null, img:"images/Oversized_Denim_Jacket.jpg", badge:"",            tags:["men","jacket"] },
  { id:8,  name:"Silk Scarf",             category:"Accessories", price:1499, originalPrice:1999, img:"images/Silk_Scarf.jpg",             badge:"New",         tags:["accessories","scarfs"] },
  { id:9,  name:"High-Waist Trousers",    category:"Women",       price:2499, originalPrice:3299, img:"images/High-Waist_Trousers.jpg",    badge:"",            tags:["women","pants"] },
  { id:10, name:"Polo Shirt",             category:"Men",         price:1199, originalPrice:null, img:"images/Polo_Shirt.jpg",             badge:"Best Seller", tags:["men","shirt"] },
  { id:11, name:"Woven Crossbody Bag",    category:"Accessories", price:2299, originalPrice:3199, img:"images/Woven_Crossbody_Bag.jpg",   badge:"Sale",        tags:["accessories","bags"] },
  { id:12, name:"Maxi Linen Skirt",       category:"Women",       price:1999, originalPrice:null, img:"images/Maxi_Linen_Skirt.jpg",       badge:"New",         tags:["women","skirt"] },
];

// ---- CART LOGIC ----
function getCart() {
  try { return JSON.parse(localStorage.getItem('nexaCart')) || []; }
  catch { return []; }
}

function saveCart(cart) {
  localStorage.setItem('nexaCart', JSON.stringify(cart));
  updateCartCount();
}

function addToCart(productId, qty = 1, size = 'M') {
  const cart = getCart();
  const existing = cart.find(i => i.id === productId && i.size === size);
  if (existing) {
    existing.qty += qty;
  } else {
    const product = PRODUCTS.find(p => p.id === productId);
    if (product) cart.push({ ...product, qty, size });
  }
  saveCart(cart);
  showToast(`Added to cart!`);
}

function removeFromCart(productId, size) {
  const cart = getCart().filter(i => !(i.id === productId && i.size === size));
  saveCart(cart);
}

function updateQty(productId, size, delta) {
  const cart = getCart();
  const item = cart.find(i => i.id === productId && i.size === size);
  if (item) {
    item.qty = Math.max(1, item.qty + delta);
    saveCart(cart);
  }
  renderCart();
}

function updateCartCount() {
  const count = getCart().reduce((s, i) => s + i.qty, 0);
  document.querySelectorAll('#cartCount').forEach(el => el.textContent = count);
}

function getCartTotal() {
  return getCart().reduce((s, i) => s + i.price * i.qty, 0);
}

// ---- PRODUCT CARD ----
function productCardHTML(p) {
  const badgeClass = p.badge === 'Sale' ? 'product-badge sale' : 'product-badge';
  return `
  <div class="product-card" onclick="openProductModal(${p.id})">
    <div class="product-img-wrap">
      <img src="${p.img}" alt="${p.name}" loading="lazy" />
      ${p.badge ? `<span class="${badgeClass}">${p.badge}</span>` : ''}
      <div class="quick-add" onclick="event.stopPropagation(); addToCart(${p.id})">Quick Add</div>
    </div>
    <div class="product-info">
      <p class="product-category">${p.category}</p>
      <h3 class="product-name">${p.name}</h3>
      <div class="product-price">
        <span class="price-current">₹${p.price.toLocaleString('en-IN')}</span>
        ${p.originalPrice ? `<span class="price-original">₹${p.originalPrice.toLocaleString('en-IN')}</span>` : ''}
      </div>
    </div>
  </div>`;
}

// ---- FEATURED PRODUCTS (home page) ----
function renderFeaturedProducts() {
  const grid = document.getElementById('featuredGrid');
  if (!grid) return;
  const featured = PRODUCTS.filter(p => p.badge).slice(0, 4);
  grid.innerHTML = featured.map(productCardHTML).join('');
}

// ---- PRODUCTS PAGE ----
function renderAllProducts(list = PRODUCTS) {
  const grid = document.getElementById('allProductsGrid');
  if (!grid) return;
  if (list.length === 0) {
    grid.innerHTML = `<div class="empty-state" style="grid-column:1/-1"><div class="big-icon">🔍</div><h3>No products found</h3><p>Try adjusting your filters.</p></div>`;
    return;
  }
  grid.innerHTML = list.map(productCardHTML).join('');
  document.getElementById('resultCount').textContent = `${list.length} products`;
}

function filterProducts() {
  const search = document.getElementById('searchInput')?.value?.toLowerCase() || '';
  const category = document.getElementById('categoryFilter')?.value || '';
  const sortBy = document.getElementById('sortSelect')?.value || '';
  const maxPrice = parseInt(document.getElementById('priceRange')?.value || 9999);

  let filtered = PRODUCTS.filter(p => {
    const matchSearch = p.name.toLowerCase().includes(search) || p.category.toLowerCase().includes(search);
    const matchCat = !category || p.category === category;
    const matchPrice = p.price <= maxPrice;
    return matchSearch && matchCat && matchPrice;
  });

  if (sortBy === 'price-asc') filtered.sort((a,b) => a.price - b.price);
  if (sortBy === 'price-desc') filtered.sort((a,b) => b.price - a.price);
  if (sortBy === 'name') filtered.sort((a,b) => a.name.localeCompare(b.name));

  renderAllProducts(filtered);
}

// ---- CART PAGE ----
// Cart rendering is handled directly in cart.html via drawCart()
// This stub exists so any legacy call to renderCart() doesn't throw an error.
function renderCart() {
  if (typeof drawCart === 'function') drawCart();
}

// ---- PRODUCT MODAL ----
function openProductModal(id) {
  const p = PRODUCTS.find(pr => pr.id === id);
  if (!p) return;
  const overlay = document.getElementById('productModal');
  if (!overlay) return;
  document.getElementById('modalImg').src = p.img;
  document.getElementById('modalName').textContent = p.name;
  document.getElementById('modalCategory').textContent = p.category;
  document.getElementById('modalPrice').textContent = `₹${p.price.toLocaleString('en-IN')}`;
  document.getElementById('modalOriginalPrice').textContent = p.originalPrice ? `₹${p.originalPrice.toLocaleString('en-IN')}` : '';
  document.getElementById('modalAddBtn').onclick = () => {
    const size = document.getElementById('modalSize').value;
    addToCart(p.id, 1, size);
    closeModal();
  };
  overlay.classList.add('open');
}

function closeModal() {
  document.getElementById('productModal')?.classList.remove('open');
}

// ---- TOAST ----
function showToast(msg) {
  let toast = document.querySelector('.toast');
  if (!toast) {
    toast = document.createElement('div');
    toast.className = 'toast';
    document.body.appendChild(toast);
  }
  toast.textContent = msg;
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 2800);
}

// ---- HAMBURGER MENU ----
function initHamburger() {
  const btn = document.getElementById('hamburger');
  const links = document.getElementById('navLinks');
  if (!btn || !links) return;
  btn.addEventListener('click', () => {
    links.classList.toggle('open');
    btn.innerHTML = links.classList.contains('open') ? '✕' : '&#9776;';
  });
}

// ---- INIT ----
document.addEventListener('DOMContentLoaded', () => {
  updateCartCount();
  initHamburger();
});
