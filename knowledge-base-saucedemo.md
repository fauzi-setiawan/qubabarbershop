# Software Knowledge Base — SauceDemo
## Dokumen Knowledge Project untuk AI Test Case Generation (RAG)

**Versi Dokumen:** 1.0  
**Tanggal:** 12 Juni 2026  
**URL Aplikasi:** https://www.saucedemo.com/  
**Dipersiapkan untuk:** Sistem AI RAG Test Case Generator  

---

# 1. Project Overview

| Atribut | Detail |
|---|---|
| **Nama Aplikasi** | Swag Labs (SauceDemo) |
| **URL** | https://www.saucedemo.com/ |
| **Tujuan Aplikasi** | Aplikasi e-commerce demo yang digunakan untuk latihan dan pengujian otomatisasi pengujian perangkat lunak (test automation practice) |
| **Deskripsi Singkat** | Swag Labs adalah aplikasi web toko online sederhana yang menyediakan fitur login, katalog produk, keranjang belanja, dan proses checkout. Aplikasi ini dikembangkan oleh Sauce Labs sebagai platform latihan untuk quality assurance engineer dan test automation engineer |
| **Jenis Aplikasi** | Single Page Application (SPA) berbasis React.js |
| **Target Pengguna** | QA Engineer, SDET, Test Automation Engineer yang ingin berlatih pengujian aplikasi web |
| **Teknologi** | React.js, HTML5, CSS3, JavaScript |
| **Meta Description** | "Sauce Labs Swag Labs app" |
| **Bahasa** | English (en) |
| **Responsive** | Ya — mendukung layout responsif (2 kolom pada viewport lebar, 1 kolom pada viewport sempit) |

---

# 2. Authentication Module

## 2.1 Halaman Login

| Elemen | Detail |
|---|---|
| **URL** | https://www.saucedemo.com/ |
| **Logo/Header** | "Swag Labs" |
| **Deskripsi** | Halaman utama aplikasi yang menampilkan form login dengan daftar kredensial yang valid |

## 2.2 Form Login

### Field Username
| Atribut | Nilai |
|---|---|
| **HTML Tag** | `<input>` |
| **ID** | `user-name` |
| **Class** | `input_error form_input` |
| **Placeholder** | `Username` |
| **Type** | text |
| **Required** | Ya (validasi client-side) |

### Field Password
| Atribut | Nilai |
|---|---|
| **HTML Tag** | `<input>` |
| **ID** | `password` |
| **Class** | `input_error form_input` |
| **Placeholder** | `Password` |
| **Type** | password |
| **Required** | Ya (validasi client-side) |

### Tombol Login
| Atribut | Nilai |
|---|---|
| **HTML Tag** | `<input>` |
| **ID** | `login-button` |
| **Class** | `submit-button btn_action` |
| **Value/Text** | `Login` |
| **Type** | submit |

## 2.3 Kredensial yang Tersedia

Halaman login menampilkan daftar username dan password yang valid:

**Accepted Usernames:**
| Username | Tipe User | Perilaku |
|---|---|---|
| `standard_user` | User standar | Login berhasil, semua fitur berfungsi normal |
| `locked_out_user` | User terkunci | Login ditolak dengan pesan error khusus |
| `problem_user` | User bermasalah | Login berhasil, tetapi beberapa fitur mengalami bug (gambar salah, sorting tidak berfungsi) |
| `performance_glitch_user` | User dengan delay | Login berhasil, tetapi dengan performa lambat (delay loading) |
| `error_user` | User dengan error | Login berhasil, tetapi beberapa aksi menghasilkan error |
| `visual_user` | User dengan visual bug | Login berhasil, tetapi UI memiliki perbedaan visual |

**Password untuk semua user:** `secret_sauce`

## 2.4 Validasi Login

### Skenario Login Berhasil
| Kondisi | Hasil |
|---|---|
| Username: `standard_user`, Password: `secret_sauce` | Redirect ke `https://www.saucedemo.com/inventory.html` |

### Skenario Login Gagal — Field Kosong

| Kondisi | Pesan Error |
|---|---|
| Username kosong, Password kosong | `Epic sadface: Username is required` |
| Username kosong, Password terisi | `Epic sadface: Username is required` |
| Username terisi, Password kosong | `Epic sadface: Password is required` |

### Skenario Login Gagal — Kredensial Salah

| Kondisi | Pesan Error |
|---|---|
| Username: `invalid_user`, Password: `wrong_pass` | `Epic sadface: Username and password do not match any user in this service` |
| Username valid, Password salah | `Epic sadface: Username and password do not match any user in this service` |

### Skenario Akun Terkunci

| Kondisi | Pesan Error |
|---|---|
| Username: `locked_out_user`, Password: `secret_sauce` | `Epic sadface: Sorry, this user has been locked out.` |

### Perilaku Error UI
- Saat error muncul, kedua field (username dan password) mendapat class `error` sebagai highlight visual
- Error message ditampilkan dalam container error di bawah form
- Error message memiliki ikon "X" untuk menutup pesan error

## 2.5 Proteksi Akses (Access Control)

| URL yang Diakses Langsung | Hasil Tanpa Login |
|---|---|
| `/inventory.html` | Redirect ke halaman login dengan pesan: `Epic sadface: You can only access '/inventory.html' when you are logged in.` |
| `/cart.html` | Redirect ke halaman login dengan pesan: `Epic sadface: You can only access '/cart.html' when you are logged in.` |
| `/checkout-step-one.html` | Redirect ke halaman login dengan pesan: `Epic sadface: You can only access '/checkout-step-one.html' when you are logged in.` |

---

# 3. Dashboard / Inventory Module

## 3.1 Halaman Inventory

| Atribut | Detail |
|---|---|
| **URL** | https://www.saucedemo.com/inventory.html |
| **Judul Halaman** | "Products" (di dalam inventory container) |
| **Header** | "Swag Labs" |
| **Layout** | 2 kolom pada viewport lebar (≥1280px), 1 kolom pada viewport sempit |

## 3.2 Elemen Header

| Elemen | ID/Class | Fungsi |
|---|---|---|
| **Hamburger Menu Button** | ID: `react-burger-menu-btn` | Membuka sidebar navigasi |
| **Logo** | Class: `app_logo` | Menampilkan "Swag Labs" |
| **Shopping Cart Link** | Class: `shopping_cart_link` | Navigasi ke halaman cart |
| **Sort Dropdown** | Class: `product_sort_container` | Mengurutkan produk |

## 3.3 Daftar Produk

Aplikasi menampilkan **6 produk** pada halaman inventory:

### Produk 1: Sauce Labs Backpack
| Atribut | Nilai |
|---|---|
| **Nama** | Sauce Labs Backpack |
| **Title Link ID** | `item_4_title_link` |
| **Image Link ID** | `item_4_img_link` |
| **Harga** | $29.99 |
| **Deskripsi** | carry.allTheThings() with the sleek, streamlined Sly Pack that melds uncompromising style with unequaled laptop and tablet protection. |
| **Add to Cart Button ID** | `add-to-cart-sauce-labs-backpack` |
| **Remove Button ID** | `remove-sauce-labs-backpack` |

### Produk 2: Sauce Labs Bike Light
| Atribut | Nilai |
|---|---|
| **Nama** | Sauce Labs Bike Light |
| **Title Link ID** | `item_0_title_link` |
| **Image Link ID** | `item_0_img_link` |
| **Harga** | $9.99 |
| **Deskripsi** | A red light isn't the desired state in testing but it sure helps when riding your bike at night. Water-resistant with 3 lighting modes, 1 AAA battery included. |
| **Add to Cart Button ID** | `add-to-cart-sauce-labs-bike-light` |
| **Remove Button ID** | `remove-sauce-labs-bike-light` |

### Produk 3: Sauce Labs Bolt T-Shirt
| Atribut | Nilai |
|---|---|
| **Nama** | Sauce Labs Bolt T-Shirt |
| **Title Link ID** | `item_1_title_link` |
| **Image Link ID** | `item_1_img_link` |
| **Harga** | $15.99 |
| **Deskripsi** | Get your testing superhero on with the Sauce Labs bolt T-shirt. From American Apparel, 100% ringspun combed cotton, heather gray with red bolt. |
| **Add to Cart Button ID** | `add-to-cart-sauce-labs-bolt-t-shirt` |
| **Remove Button ID** | `remove-sauce-labs-bolt-t-shirt` |

### Produk 4: Sauce Labs Fleece Jacket
| Atribut | Nilai |
|---|---|
| **Nama** | Sauce Labs Fleece Jacket |
| **Title Link ID** | `item_5_title_link` |
| **Image Link ID** | `item_5_img_link` |
| **Harga** | $49.99 |
| **Deskripsi** | It's not every day that you come across a midweight quarter-zip fleece jacket capable of handling everything from a relaxing day outdoors to a busy day at the office. |
| **Add to Cart Button ID** | `add-to-cart-sauce-labs-fleece-jacket` |
| **Remove Button ID** | `remove-sauce-labs-fleece-jacket` |

### Produk 5: Sauce Labs Onesie
| Atribut | Nilai |
|---|---|
| **Nama** | Sauce Labs Onesie |
| **Title Link ID** | `item_2_title_link` |
| **Image Link ID** | `item_2_img_link` |
| **Harga** | $7.99 |
| **Deskripsi** | Rib snap infant onesie for the junior automation engineer in development. Reinforced 3-snap bottom closure, two-needle hemmed sleeved and bottom won't unravel. |
| **Add to Cart Button ID** | `add-to-cart-sauce-labs-onesie` |
| **Remove Button ID** | `remove-sauce-labs-onesie` |

### Produk 6: Test.allTheThings() T-Shirt (Red)
| Atribut | Nilai |
|---|---|
| **Nama** | Test.allTheThings() T-Shirt (Red) |
| **Title Link ID** | `item_3_title_link` |
| **Image Link ID** | `item_3_img_link` |
| **Harga** | $15.99 |
| **Deskripsi** | This classic Sauce Labs t-shirt is perfect to wear when cozying up to your keyboard to automate a few tests. Super-soft and comfy ringspun combed cotton. |
| **Add to Cart Button ID** | `add-to-cart-test.allthethings()-t-shirt-(red)` |
| **Remove Button ID** | `remove-test.allthethings()-t-shirt-(red)` |

## 3.4 Informasi Produk yang Ditampilkan

Setiap kartu produk menampilkan:
1. **Gambar Produk** — thumbnail gambar produk (link ke halaman detail)
2. **Nama Produk** — teks yang dapat diklik untuk membuka halaman detail
3. **Deskripsi Produk** — deskripsi singkat produk
4. **Harga Produk** — format: `$XX.XX`
5. **Tombol Add to Cart / Remove** — toggle button untuk menambah/menghapus dari keranjang

## 3.5 Tombol Add to Cart

| State | Label Tombol | Class |
|---|---|---|
| **Belum di cart** | "Add to cart" | `btn btn_primary btn_small btn_inventory` |
| **Sudah di cart** | "Remove" | `btn btn_secondary btn_small btn_inventory` |

**Perilaku:**
- Klik "Add to cart" → tombol berubah menjadi "Remove"
- Klik "Remove" → tombol kembali menjadi "Add to cart"
- ID tombol mengikuti pola: `add-to-cart-{nama-produk-kebab-case}` / `remove-{nama-produk-kebab-case}`

## 3.6 Badge Jumlah Item Cart

| Kondisi | Perilaku |
|---|---|
| Cart kosong | Badge tidak ditampilkan |
| 1 item di cart | Badge menampilkan "1" |
| N item di cart | Badge menampilkan angka N |
| Item dihapus dari cart | Badge berkurang, jika 0 maka badge menghilang |

- Badge ditampilkan di dalam elemen `<a class="shopping_cart_link">` sebagai text node
- State cart persisten selama sesi (bertahan saat navigasi antar halaman)

---

# 4. Product Detail Module

## 4.1 Cara Membuka Detail Produk

Detail produk dapat diakses dengan dua cara:
1. **Klik pada nama produk** (title link) di halaman inventory
2. **Klik pada gambar produk** (image link) di halaman inventory

## 4.2 URL Pattern

```
https://www.saucedemo.com/inventory-item.html?id={product_id}
```

| Produk | Product ID | URL |
|---|---|---|
| Sauce Labs Backpack | 4 | `/inventory-item.html?id=4` |
| Sauce Labs Bike Light | 0 | `/inventory-item.html?id=0` |
| Sauce Labs Bolt T-Shirt | 1 | `/inventory-item.html?id=1` |
| Sauce Labs Fleece Jacket | 5 | `/inventory-item.html?id=5` |
| Sauce Labs Onesie | 2 | `/inventory-item.html?id=2` |
| Test.allTheThings() T-Shirt (Red) | 3 | `/inventory-item.html?id=3` |

## 4.3 Informasi yang Tersedia

Halaman detail produk menampilkan:
1. **Nama Produk** — judul produk
2. **Deskripsi Produk** — deskripsi lengkap produk
3. **Harga Produk** — format: `$XX.XX`
4. **Gambar Produk** — gambar ukuran besar

## 4.4 Elemen Interaktif

### Tombol Add to Cart / Remove
| Atribut | Nilai |
|---|---|
| **Add to Cart ID** | `add-to-cart` |
| **Remove ID** | `remove` |
| **Add to Cart Class** | `btn btn_primary btn_small btn_inventory` |
| **Remove Class** | `btn btn_secondary btn_small btn_inventory` |

> **Catatan:** Berbeda dengan halaman inventory, tombol di halaman detail menggunakan ID sederhana (`add-to-cart` / `remove`) tanpa menyertakan nama produk.

### Tombol Back to Products
| Atribut | Nilai |
|---|---|
| **ID** | `back-to-products` |
| **Class** | `btn btn_secondary back btn_large inventory_details_back_button` |
| **Text** | "Back to products" |
| **Navigasi** | Kembali ke halaman inventory (`/inventory.html`) |

## 4.5 Sinkronisasi State Cart

- State Add to Cart / Remove disinkronkan antara halaman inventory dan halaman detail produk
- Jika produk ditambahkan ke cart dari halaman detail, tombol di halaman inventory juga berubah menjadi "Remove" dan sebaliknya

---

# 5. Shopping Cart Module

## 5.1 Halaman Cart

| Atribut | Detail |
|---|---|
| **URL** | https://www.saucedemo.com/cart.html |
| **Judul** | "Your Cart" |
| **Akses** | Klik ikon shopping cart di header |

## 5.2 Struktur Tampilan Cart

### Header Kolom
| Kolom | Label |
|---|---|
| **Quantity** | QTY |
| **Description** | Description |

### Informasi Setiap Item
| Elemen | Detail |
|---|---|
| **Quantity** | Menampilkan angka "1" (teks statis, **tidak dapat diedit**) |
| **Nama Produk** | Teks yang dapat diklik (link ke halaman detail) |
| **Deskripsi** | Deskripsi produk |
| **Harga** | Format: `$XX.XX` |
| **Tombol Remove** | Menghapus item dari cart |

### Contoh Item di Cart
```
QTY  | Description
1    | Sauce Labs Backpack
       carry.allTheThings() with the sleek...
       $29.99
       [Remove]
```

## 5.3 Tombol Remove Item
| Atribut | Nilai |
|---|---|
| **ID Pattern** | `remove-{nama-produk-kebab-case}` |
| **Class** | `btn btn_secondary btn_small cart_button` |
| **Perilaku** | Menghapus item dari cart, item hilang dari daftar, badge cart berkurang |

## 5.4 Tombol Continue Shopping
| Atribut | Nilai |
|---|---|
| **ID** | `continue-shopping` |
| **Class** | `btn btn_secondary back btn_medium` |
| **Text** | "Continue Shopping" |
| **Navigasi** | Kembali ke halaman inventory (`/inventory.html`) |

## 5.5 Tombol Checkout
| Atribut | Nilai |
|---|---|
| **ID** | `checkout` |
| **Class** | `btn btn_action btn_medium checkout_button` |
| **Text** | "Checkout" |
| **Navigasi** | Menuju halaman checkout step one (`/checkout-step-one.html`) |

## 5.6 Perilaku Cart

- Quantity selalu bernilai **1** per item dan **tidak dapat diubah** (tidak ada fitur increment/decrement)
- Untuk menambah produk yang sama, user harus kembali ke inventory (tetapi produk yang sudah ada di cart hanya bisa di-remove)
- Setiap produk hanya bisa ditambahkan **satu kali** ke cart
- Cart state persisten selama sesi login

---

# 6. Checkout Module (Step One)

## 6.1 Halaman Checkout Step One

| Atribut | Detail |
|---|---|
| **URL** | https://www.saucedemo.com/checkout-step-one.html |
| **Judul** | "Checkout: Your Information" |

## 6.2 Form Informasi Pelanggan

### Field First Name
| Atribut | Nilai |
|---|---|
| **ID** | `first-name` |
| **Placeholder** | `First Name` |
| **Type** | text |
| **Required** | Ya |

### Field Last Name
| Atribut | Nilai |
|---|---|
| **ID** | `last-name` |
| **Placeholder** | `Last Name` |
| **Type** | text |
| **Required** | Ya |

### Field Zip/Postal Code
| Atribut | Nilai |
|---|---|
| **ID** | `postal-code` |
| **Placeholder** | `Zip/Postal Code` |
| **Type** | text |
| **Required** | Ya |

## 6.3 Validasi Field Wajib

Validasi dilakukan secara berurutan (sequential validation):

| Kondisi | Pesan Error |
|---|---|
| Semua field kosong | `Error: First Name is required` |
| Hanya First Name terisi | `Error: Last Name is required` |
| First Name + Last Name terisi, Postal Code kosong | `Error: Postal Code is required` |

**Perilaku Error:**
- Error message ditampilkan di bawah form fields
- Field yang bermasalah mendapat highlight visual (class `error`)
- Validasi bersifat sequential — hanya satu error ditampilkan pada satu waktu

## 6.4 Tombol Continue
| Atribut | Nilai |
|---|---|
| **ID** | `continue` |
| **Class** | `submit-button btn btn_primary cart_button btn_action` |
| **Text/Value** | "Continue" |
| **Navigasi** | Menuju checkout step two (`/checkout-step-two.html`) jika semua field valid |

## 6.5 Tombol Cancel
| Atribut | Nilai |
|---|---|
| **ID** | `cancel` |
| **Class** | `btn btn_secondary back btn_medium cart_cancel_link` |
| **Text** | "Cancel" |
| **Navigasi** | Kembali ke halaman cart (`/cart.html`) |

---

# 7. Checkout Overview Module (Step Two)

## 7.1 Halaman Checkout Overview

| Atribut | Detail |
|---|---|
| **URL** | https://www.saucedemo.com/checkout-step-two.html |
| **Judul** | "Checkout: Overview" |

## 7.2 Ringkasan Produk

Menampilkan daftar semua item yang ada di cart dengan informasi:
- Quantity (QTY)
- Nama produk
- Deskripsi produk
- Harga per item

## 7.3 Informasi Pembayaran

| Elemen | Label | Nilai Contoh |
|---|---|---|
| **Payment Information** | "Payment Information:" | SauceCard #31337 |
| **Shipping Information** | "Shipping Information:" | Free Pony Express Delivery! |

## 7.4 Rincian Harga

| Elemen | Label | Contoh Nilai | Keterangan |
|---|---|---|---|
| **Item Total** | "Item total:" | $39.98 | Jumlah harga semua item |
| **Tax** | "Tax:" | $3.20 | Pajak ~8% dari item total |
| **Total** | "Total:" | $43.18 | Item total + Tax |

### Perhitungan Pajak
- **Rate pajak:** ~8% (0.08)
- **Formula:** Tax = Item Total × 0.08 (dibulatkan ke 2 desimal)
- **Contoh:** $39.98 × 0.08 = $3.1984 → $3.20

## 7.5 Tombol Finish
| Atribut | Nilai |
|---|---|
| **ID** | `finish` |
| **Class** | `btn btn_action btn_medium cart_button` |
| **Text** | "Finish" |
| **Navigasi** | Menuju halaman checkout complete (`/checkout-complete.html`) |

## 7.6 Tombol Cancel
| Atribut | Nilai |
|---|---|
| **ID** | `cancel` |
| **Class** | `btn btn_secondary back btn_medium cart_cancel_link` |
| **Text** | "Cancel" |
| **Navigasi** | Kembali ke halaman inventory (`/inventory.html`) |

---

# 8. Checkout Complete Module

## 8.1 Halaman Checkout Complete

| Atribut | Detail |
|---|---|
| **URL** | https://www.saucedemo.com/checkout-complete.html |
| **Header Halaman** | "Checkout: Complete!" |

## 8.2 Pesan Sukses

| Elemen | Teks |
|---|---|
| **Title** | "Thank you for your order!" |
| **Deskripsi** | "Your order has been dispatched, and will arrive just as fast as the pony can get there!" |
| **Gambar** | Ikon centang/sukses (pony express illustration) |

## 8.3 Tombol Back Home
| Atribut | Nilai |
|---|---|
| **ID** | `back-to-products` |
| **Class** | `btn btn_primary btn_small` |
| **Text** | "Back Home" |
| **Navigasi** | Kembali ke halaman inventory (`/inventory.html`) |

## 8.4 Perilaku Setelah Checkout
- Cart dikosongkan setelah order berhasil
- Badge cart menghilang (count = 0)
- User diarahkan kembali ke inventory untuk melakukan belanja baru

---

# 9. Navigation Module

## 9.1 Menu Hamburger (Sidebar)

Sidebar dibuka dengan mengklik tombol hamburger di pojok kiri atas.

| Atribut | Nilai |
|---|---|
| **Trigger Button ID** | `react-burger-menu-btn` |
| **Close Button ID** | `react-burger-cross-btn` |
| **Menu Container** | Sidebar yang muncul dari kiri dengan animasi slide |

## 9.2 Menu Items

### All Items
| Atribut | Nilai |
|---|---|
| **ID** | `inventory_sidebar_link` |
| **Text** | "All Items" |
| **Class** | `bm-item menu-item` |
| **Navigasi** | Halaman inventory (`/inventory.html`) |

### About
| Atribut | Nilai |
|---|---|
| **ID** | `about_sidebar_link` |
| **Text** | "About" |
| **Class** | `bm-item menu-item` |
| **Navigasi** | https://saucelabs.com/ (situs eksternal Sauce Labs) |

### Logout
| Atribut | Nilai |
|---|---|
| **ID** | `logout_sidebar_link` |
| **Text** | "Logout" |
| **Class** | `bm-item menu-item` |
| **Navigasi** | Kembali ke halaman login (`/`) dan session dihapus |

### Reset App State
| Atribut | Nilai |
|---|---|
| **ID** | `reset_sidebar_link` |
| **Text** | "Reset App State" |
| **Class** | `bm-item menu-item` |
| **Fungsi** | Menghapus semua item dari cart dan mereset state aplikasi ke kondisi awal |

## 9.3 Perilaku Sidebar
- Sidebar muncul dari sisi kiri dengan animasi slide-in
- Sidebar dapat ditutup dengan klik tombol "X" (close button) atau klik area di luar sidebar
- Menu tersedia di semua halaman yang memerlukan autentikasi

---

# 10. Sorting Module

## 10.1 Dropdown Sorting

| Atribut | Nilai |
|---|---|
| **Elemen** | `<select>` |
| **Class** | `product_sort_container` |
| **Lokasi** | Halaman inventory, di bawah header, sejajar dengan judul "Products" |
| **Default** | Name (A to Z) |

## 10.2 Opsi Sorting

| Value | Label Tampilan | Deskripsi | Urutan |
|---|---|---|---|
| `az` | Name (A to Z) | Urutkan berdasarkan nama produk secara ascending (A → Z) | **Default** |
| `za` | Name (Z to A) | Urutkan berdasarkan nama produk secara descending (Z → A) | — |
| `lohi` | Price (low to high) | Urutkan berdasarkan harga dari termurah ke termahal | — |
| `hilo` | Price (high to low) | Urutkan berdasarkan harga dari termahal ke termurah | — |

## 10.3 Verifikasi Sorting

### Name (A to Z) — Default
1. Sauce Labs Backpack ($29.99)
2. Sauce Labs Bike Light ($9.99)
3. Sauce Labs Bolt T-Shirt ($15.99)
4. Sauce Labs Fleece Jacket ($49.99)
5. Sauce Labs Onesie ($7.99)
6. Test.allTheThings() T-Shirt (Red) ($15.99)

### Name (Z to A)
1. Test.allTheThings() T-Shirt (Red) ($15.99)
2. Sauce Labs Onesie ($7.99)
3. Sauce Labs Fleece Jacket ($49.99)
4. Sauce Labs Bolt T-Shirt ($15.99)
5. Sauce Labs Bike Light ($9.99)
6. Sauce Labs Backpack ($29.99)

### Price (low to high)
1. Sauce Labs Onesie ($7.99)
2. Sauce Labs Bike Light ($9.99)
3. Sauce Labs Bolt T-Shirt ($15.99)
4. Test.allTheThings() T-Shirt (Red) ($15.99)
5. Sauce Labs Backpack ($29.99)
6. Sauce Labs Fleece Jacket ($49.99)

### Price (high to low)
1. Sauce Labs Fleece Jacket ($49.99)
2. Sauce Labs Backpack ($29.99)
3. Sauce Labs Bolt T-Shirt ($15.99)
4. Test.allTheThings() T-Shirt (Red) ($15.99)
5. Sauce Labs Bike Light ($9.99)
6. Sauce Labs Onesie ($7.99)

---

# 11. Business Rules

## 11.1 Aturan Autentikasi
| ID | Aturan |
|---|---|
| BR-AUTH-001 | User **harus login** sebelum dapat mengakses halaman inventory, cart, atau checkout |
| BR-AUTH-002 | Akses langsung ke URL terproteksi tanpa login akan di-redirect ke halaman login dengan pesan error |
| BR-AUTH-003 | User dengan status `locked_out` tidak dapat login meskipun password benar |
| BR-AUTH-004 | Username dan password bersifat **case-sensitive** |
| BR-AUTH-005 | Logout menghapus session dan mengarahkan user kembali ke halaman login |

## 11.2 Aturan Keranjang Belanja (Cart)
| ID | Aturan |
|---|---|
| BR-CART-001 | Setiap produk hanya dapat ditambahkan **satu kali** ke cart (quantity fixed = 1) |
| BR-CART-002 | Quantity item di cart **tidak dapat diubah** (tidak ada fitur increment/decrement) |
| BR-CART-003 | Cart state **persisten** selama sesi login (bertahan saat navigasi antar halaman) |
| BR-CART-004 | Badge cart menampilkan jumlah total item dan menghilang saat cart kosong |
| BR-CART-005 | State Add to Cart / Remove disinkronkan antara halaman inventory dan halaman detail |
| BR-CART-006 | Reset App State akan mengosongkan cart |

## 11.3 Aturan Checkout
| ID | Aturan |
|---|---|
| BR-CHK-001 | Checkout **tidak dapat dilakukan** jika cart kosong |
| BR-CHK-002 | **Semua field** pada form checkout (First Name, Last Name, Postal Code) wajib diisi |
| BR-CHK-003 | Validasi field bersifat **sequential** — satu error per submit |
| BR-CHK-004 | Pajak dihitung sebesar **~8%** dari item total |
| BR-CHK-005 | Pembayaran menggunakan "SauceCard #31337" (payment method tetap) |
| BR-CHK-006 | Pengiriman menggunakan "Free Pony Express Delivery!" (shipping method tetap) |
| BR-CHK-007 | Setelah checkout berhasil, cart dikosongkan secara otomatis |
| BR-CHK-008 | Total = Item Total + Tax |

## 11.4 Aturan Produk
| ID | Aturan |
|---|---|
| BR-PRD-001 | Katalog produk bersifat **statis** — terdapat 6 produk tetap |
| BR-PRD-002 | Harga produk **tidak berubah** dan tidak ada diskon |
| BR-PRD-003 | Setiap produk memiliki **ID unik** yang digunakan dalam URL detail |
| BR-PRD-004 | Sorting default adalah **Name (A to Z)** |

## 11.5 Aturan Navigasi
| ID | Aturan |
|---|---|
| BR-NAV-001 | Menu hamburger tersedia di **semua halaman** yang memerlukan autentikasi |
| BR-NAV-002 | Link "About" membuka **situs eksternal** Sauce Labs |
| BR-NAV-003 | "All Items" selalu mengarah ke halaman inventory |

---

# 12. Functional Requirements

## FR-001: Login dengan Kredensial Valid
| Atribut | Detail |
|---|---|
| **Deskripsi** | Sistem harus memungkinkan user login dengan username dan password yang valid |
| **Input** | Username: `standard_user`, Password: `secret_sauce` |
| **Proses** | Validasi kredensial terhadap database user yang tersedia |
| **Output** | User diarahkan ke halaman inventory (`/inventory.html`) |

## FR-002: Validasi Login — Username Kosong
| Atribut | Detail |
|---|---|
| **Deskripsi** | Sistem harus menampilkan error jika username tidak diisi |
| **Input** | Username: (kosong), Password: (opsional) |
| **Proses** | Validasi keberadaan username |
| **Output** | Pesan error: `Epic sadface: Username is required` |

## FR-003: Validasi Login — Password Kosong
| Atribut | Detail |
|---|---|
| **Deskripsi** | Sistem harus menampilkan error jika password tidak diisi |
| **Input** | Username: (terisi), Password: (kosong) |
| **Proses** | Validasi keberadaan password |
| **Output** | Pesan error: `Epic sadface: Password is required` |

## FR-004: Validasi Login — Kredensial Salah
| Atribut | Detail |
|---|---|
| **Deskripsi** | Sistem harus menampilkan error untuk kredensial yang tidak valid |
| **Input** | Username: `invalid_user`, Password: `wrong_pass` |
| **Proses** | Validasi kredensial gagal |
| **Output** | Pesan error: `Epic sadface: Username and password do not match any user in this service` |

## FR-005: Blokir Login Akun Terkunci
| Atribut | Detail |
|---|---|
| **Deskripsi** | Sistem harus menolak login dari akun yang terkunci |
| **Input** | Username: `locked_out_user`, Password: `secret_sauce` |
| **Proses** | Cek status akun → terkunci |
| **Output** | Pesan error: `Epic sadface: Sorry, this user has been locked out.` |

## FR-006: Menampilkan Daftar Produk
| Atribut | Detail |
|---|---|
| **Deskripsi** | Sistem harus menampilkan seluruh produk pada halaman inventory |
| **Input** | User berhasil login |
| **Proses** | Load dan render 6 produk dari katalog |
| **Output** | Daftar 6 produk dengan nama, deskripsi, harga, gambar, dan tombol Add to Cart |

## FR-007: Menambahkan Produk ke Cart
| Atribut | Detail |
|---|---|
| **Deskripsi** | Sistem harus memungkinkan user menambahkan produk ke keranjang belanja |
| **Input** | Klik tombol "Add to cart" pada produk |
| **Proses** | Tambahkan produk ke cart, ubah tombol menjadi "Remove", update badge cart |
| **Output** | Produk masuk ke cart, tombol berubah menjadi "Remove", badge bertambah 1 |

## FR-008: Menghapus Produk dari Cart
| Atribut | Detail |
|---|---|
| **Deskripsi** | Sistem harus memungkinkan user menghapus produk dari keranjang belanja |
| **Input** | Klik tombol "Remove" pada produk |
| **Proses** | Hapus produk dari cart, ubah tombol menjadi "Add to cart", update badge cart |
| **Output** | Produk dihapus dari cart, tombol kembali ke "Add to cart", badge berkurang 1 |

## FR-009: Melihat Detail Produk
| Atribut | Detail |
|---|---|
| **Deskripsi** | Sistem harus menampilkan halaman detail saat produk diklik |
| **Input** | Klik nama atau gambar produk |
| **Proses** | Navigasi ke halaman detail produk dengan ID yang sesuai |
| **Output** | Halaman detail menampilkan nama, deskripsi, harga, gambar, dan tombol Add to Cart |

## FR-010: Sorting Produk
| Atribut | Detail |
|---|---|
| **Deskripsi** | Sistem harus memungkinkan sorting produk berdasarkan nama atau harga |
| **Input** | Pilih opsi sorting dari dropdown |
| **Proses** | Re-render daftar produk sesuai kriteria sorting yang dipilih |
| **Output** | Produk ditampilkan dalam urutan yang sesuai (A-Z, Z-A, Low-High, High-Low) |

## FR-011: Melihat Halaman Cart
| Atribut | Detail |
|---|---|
| **Deskripsi** | Sistem harus menampilkan semua item dalam keranjang belanja |
| **Input** | Klik ikon shopping cart |
| **Proses** | Navigasi ke halaman cart, render daftar item |
| **Output** | Halaman cart menampilkan QTY, nama, deskripsi, harga, dan tombol Remove untuk setiap item |

## FR-012: Checkout — Validasi Form
| Atribut | Detail |
|---|---|
| **Deskripsi** | Sistem harus memvalidasi semua field pada form checkout |
| **Input** | Klik "Continue" dengan field yang kosong |
| **Proses** | Validasi sequential: First Name → Last Name → Postal Code |
| **Output** | Pesan error sesuai field yang kosong |

## FR-013: Checkout — Isi Form dan Lanjutkan
| Atribut | Detail |
|---|---|
| **Deskripsi** | Sistem harus memproses form checkout yang valid |
| **Input** | First Name, Last Name, Postal Code terisi lengkap |
| **Proses** | Validasi semua field → navigasi ke halaman overview |
| **Output** | Halaman Checkout Overview menampilkan ringkasan pesanan |

## FR-014: Checkout Overview — Tampilkan Ringkasan
| Atribut | Detail |
|---|---|
| **Deskripsi** | Sistem harus menampilkan ringkasan pesanan sebelum finalisasi |
| **Input** | Form checkout valid + items di cart |
| **Proses** | Render ringkasan: item, payment info, shipping info, item total, tax, total |
| **Output** | Ringkasan pesanan lengkap dengan kalkulasi pajak ~8% |

## FR-015: Checkout Complete — Finalisasi Pesanan
| Atribut | Detail |
|---|---|
| **Deskripsi** | Sistem harus menyelesaikan pesanan dan menampilkan konfirmasi |
| **Input** | Klik tombol "Finish" pada halaman overview |
| **Proses** | Finalisasi order, kosongkan cart |
| **Output** | Halaman sukses: "Thank you for your order!" + tombol "Back Home" |

## FR-016: Logout
| Atribut | Detail |
|---|---|
| **Deskripsi** | Sistem harus memungkinkan user logout dari aplikasi |
| **Input** | Klik menu "Logout" dari sidebar |
| **Proses** | Hapus session, redirect ke halaman login |
| **Output** | User diarahkan ke halaman login (`/`) |

## FR-017: Reset App State
| Atribut | Detail |
|---|---|
| **Deskripsi** | Sistem harus memungkinkan reset state aplikasi ke kondisi awal |
| **Input** | Klik menu "Reset App State" dari sidebar |
| **Proses** | Kosongkan cart, reset semua state |
| **Output** | Cart kosong, badge menghilang, semua tombol kembali ke "Add to cart" |

## FR-018: Proteksi Akses Halaman
| Atribut | Detail |
|---|---|
| **Deskripsi** | Sistem harus melindungi halaman dari akses tanpa autentikasi |
| **Input** | Akses langsung ke URL terproteksi tanpa login |
| **Proses** | Cek session → tidak valid → redirect ke login |
| **Output** | Redirect ke login dengan pesan: `Epic sadface: You can only access '{path}' when you are logged in.` |

---

# 13. Non-Functional Requirements

## 13.1 Security

| ID | Requirement | Detail |
|---|---|---|
| NFR-SEC-001 | **Autentikasi Wajib** | Semua halaman selain login harus memerlukan autentikasi |
| NFR-SEC-002 | **Proteksi URL Langsung** | Akses langsung ke URL terproteksi harus di-redirect ke login |
| NFR-SEC-003 | **Session Management** | Session harus dihapus saat logout |
| NFR-SEC-004 | **Password Masking** | Field password harus di-mask (type="password") |
| NFR-SEC-005 | **Akun Lockout** | Sistem harus mendukung mekanisme penguncian akun |

## 13.2 Performance

| ID | Requirement | Detail |
|---|---|---|
| NFR-PRF-001 | **Loading Time** | Halaman harus dimuat dalam waktu ≤ 3 detik pada koneksi normal |
| NFR-PRF-002 | **Sorting Response** | Perubahan sorting harus direspons secara instan tanpa loading ulang halaman |
| NFR-PRF-003 | **Cart Update** | Penambahan/penghapusan item cart harus terupdate secara real-time |
| NFR-PRF-004 | **Performance Glitch User** | User `performance_glitch_user` mengalami delay pada loading (simulasi performa buruk) |

## 13.3 Reliability

| ID | Requirement | Detail |
|---|---|---|
| NFR-REL-001 | **State Persistence** | State cart harus konsisten selama sesi login |
| NFR-REL-002 | **Cross-page Sync** | State Add to Cart/Remove harus sinkron antara halaman inventory dan detail |
| NFR-REL-003 | **Kalkulasi Akurat** | Perhitungan item total, tax, dan total harus akurat |
| NFR-REL-004 | **Error Handling** | Pesan error harus informatif dan sesuai konteks |

## 13.4 Usability

| ID | Requirement | Detail |
|---|---|---|
| NFR-USB-001 | **Navigasi Intuitif** | Menu hamburger, breadcrumb, dan tombol back harus berfungsi dengan benar |
| NFR-USB-002 | **Feedback Visual** | Tombol harus berubah state saat diklik (Add to Cart → Remove) |
| NFR-USB-003 | **Error Visibility** | Pesan error harus terlihat jelas dan mudah dibaca |
| NFR-USB-004 | **Responsive Design** | Layout harus menyesuaikan ukuran viewport (2 kolom / 1 kolom) |

## 13.5 Compatibility

| ID | Requirement | Detail |
|---|---|---|
| NFR-CMP-001 | **Cross-browser** | Aplikasi harus berfungsi di Chrome, Firefox, Safari, Edge |
| NFR-CMP-002 | **Responsive Viewport** | Aplikasi harus mendukung berbagai ukuran layar (mobile, tablet, desktop) |
| NFR-CMP-003 | **SPA Architecture** | Aplikasi menggunakan React.js SPA — navigasi tanpa full page reload |

---

# 14. Testable Scenarios

## 14.1 Authentication Module

| No | Module | Scenario | Expected Result |
|---|---|---|---|
| TS-001 | Authentication | Login dengan username dan password valid | Redirect ke halaman inventory |
| TS-002 | Authentication | Login dengan username kosong | Error: "Epic sadface: Username is required" |
| TS-003 | Authentication | Login dengan password kosong | Error: "Epic sadface: Password is required" |
| TS-004 | Authentication | Login dengan kedua field kosong | Error: "Epic sadface: Username is required" |
| TS-005 | Authentication | Login dengan kredensial salah | Error: "Epic sadface: Username and password do not match any user in this service" |
| TS-006 | Authentication | Login dengan akun locked_out_user | Error: "Epic sadface: Sorry, this user has been locked out." |
| TS-007 | Authentication | Login dengan problem_user | Login berhasil, beberapa fitur bermasalah |
| TS-008 | Authentication | Login dengan performance_glitch_user | Login berhasil dengan delay |
| TS-009 | Authentication | Login dengan error_user | Login berhasil, beberapa aksi menghasilkan error |
| TS-010 | Authentication | Login dengan visual_user | Login berhasil, UI memiliki perbedaan visual |
| TS-011 | Authentication | Akses langsung /inventory.html tanpa login | Redirect ke login dengan error message |
| TS-012 | Authentication | Akses langsung /cart.html tanpa login | Redirect ke login dengan error message |
| TS-013 | Authentication | Akses langsung /checkout-step-one.html tanpa login | Redirect ke login dengan error message |
| TS-014 | Authentication | Logout dan coba akses halaman terproteksi | Redirect ke login |

## 14.2 Inventory Module

| No | Module | Scenario | Expected Result |
|---|---|---|---|
| TS-015 | Inventory | Verifikasi 6 produk ditampilkan | 6 kartu produk terlihat dengan informasi lengkap |
| TS-016 | Inventory | Verifikasi informasi produk (nama, deskripsi, harga, gambar) | Semua informasi sesuai data produk |
| TS-017 | Inventory | Klik "Add to cart" pada produk | Tombol berubah menjadi "Remove", badge cart bertambah |
| TS-018 | Inventory | Klik "Remove" pada produk | Tombol kembali ke "Add to cart", badge cart berkurang |
| TS-019 | Inventory | Tambahkan semua 6 produk ke cart | Badge menampilkan "6" |
| TS-020 | Inventory | Remove semua produk dari cart | Badge menghilang |
| TS-021 | Inventory | Verifikasi badge cart update real-time | Badge bertambah/berkurang sesuai aksi |

## 14.3 Sorting Module

| No | Module | Scenario | Expected Result |
|---|---|---|---|
| TS-022 | Sorting | Default sorting saat halaman dimuat | Produk diurutkan Name A-Z |
| TS-023 | Sorting | Pilih sorting Name (A to Z) | Produk diurutkan: Backpack → Bike Light → Bolt T-Shirt → Fleece → Onesie → Test.allTheThings() |
| TS-024 | Sorting | Pilih sorting Name (Z to A) | Produk diurutkan: Test.allTheThings() → Onesie → Fleece → Bolt T-Shirt → Bike Light → Backpack |
| TS-025 | Sorting | Pilih sorting Price (low to high) | Produk diurutkan: $7.99 → $9.99 → $15.99 → $15.99 → $29.99 → $49.99 |
| TS-026 | Sorting | Pilih sorting Price (high to low) | Produk diurutkan: $49.99 → $29.99 → $15.99 → $15.99 → $9.99 → $7.99 |
| TS-027 | Sorting | Ganti sorting berulang kali | Produk selalu diurutkan dengan benar sesuai pilihan |

## 14.4 Product Detail Module

| No | Module | Scenario | Expected Result |
|---|---|---|---|
| TS-028 | Product Detail | Klik nama produk dari inventory | Navigasi ke halaman detail dengan URL pattern `/inventory-item.html?id={id}` |
| TS-029 | Product Detail | Klik gambar produk dari inventory | Navigasi ke halaman detail produk yang sama |
| TS-030 | Product Detail | Verifikasi informasi detail produk | Nama, deskripsi, harga, gambar sesuai |
| TS-031 | Product Detail | Klik "Add to cart" di halaman detail | Tombol berubah menjadi "Remove", badge cart bertambah |
| TS-032 | Product Detail | Klik "Remove" di halaman detail | Tombol kembali ke "Add to cart", badge cart berkurang |
| TS-033 | Product Detail | Klik "Back to products" | Kembali ke halaman inventory |
| TS-034 | Product Detail | Verifikasi sinkronisasi state cart antara inventory dan detail | State konsisten di kedua halaman |

## 14.5 Shopping Cart Module

| No | Module | Scenario | Expected Result |
|---|---|---|---|
| TS-035 | Cart | Klik ikon cart dengan item di dalamnya | Halaman cart menampilkan semua item |
| TS-036 | Cart | Verifikasi informasi item (QTY, nama, deskripsi, harga) | Informasi sesuai dengan produk yang ditambahkan |
| TS-037 | Cart | Verifikasi quantity selalu 1 dan tidak editable | QTY = 1, tidak ada kontrol untuk mengubah |
| TS-038 | Cart | Klik "Remove" pada item di cart | Item dihapus dari cart, badge berkurang |
| TS-039 | Cart | Remove semua item dari cart | Cart kosong, badge menghilang |
| TS-040 | Cart | Klik "Continue Shopping" | Kembali ke halaman inventory |
| TS-041 | Cart | Klik "Checkout" dengan item di cart | Navigasi ke checkout step one |
| TS-042 | Cart | Klik ikon cart saat cart kosong | Halaman cart kosong ditampilkan |

## 14.6 Checkout Module (Step One)

| No | Module | Scenario | Expected Result |
|---|---|---|---|
| TS-043 | Checkout | Submit form dengan semua field kosong | Error: "Error: First Name is required" |
| TS-044 | Checkout | Submit form hanya dengan First Name | Error: "Error: Last Name is required" |
| TS-045 | Checkout | Submit form dengan First Name + Last Name | Error: "Error: Postal Code is required" |
| TS-046 | Checkout | Submit form dengan semua field terisi | Navigasi ke checkout overview |
| TS-047 | Checkout | Klik "Cancel" pada form checkout | Kembali ke halaman cart |
| TS-048 | Checkout | Verifikasi field placeholder text | First Name, Last Name, Zip/Postal Code |

## 14.7 Checkout Overview Module (Step Two)

| No | Module | Scenario | Expected Result |
|---|---|---|---|
| TS-049 | Checkout Overview | Verifikasi ringkasan produk | Semua item dari cart ditampilkan dengan QTY, nama, deskripsi, harga |
| TS-050 | Checkout Overview | Verifikasi Payment Information | "SauceCard #31337" |
| TS-051 | Checkout Overview | Verifikasi Shipping Information | "Free Pony Express Delivery!" |
| TS-052 | Checkout Overview | Verifikasi perhitungan Item Total | Jumlah harga semua item |
| TS-053 | Checkout Overview | Verifikasi perhitungan Tax (~8%) | Tax = Item Total × 0.08 |
| TS-054 | Checkout Overview | Verifikasi perhitungan Total | Total = Item Total + Tax |
| TS-055 | Checkout Overview | Klik "Finish" | Navigasi ke halaman checkout complete |
| TS-056 | Checkout Overview | Klik "Cancel" | Kembali ke halaman inventory |

## 14.8 Checkout Complete Module

| No | Module | Scenario | Expected Result |
|---|---|---|---|
| TS-057 | Checkout Complete | Verifikasi pesan sukses header | "Thank you for your order!" |
| TS-058 | Checkout Complete | Verifikasi pesan sukses deskripsi | "Your order has been dispatched, and will arrive just as fast as the pony can get there!" |
| TS-059 | Checkout Complete | Klik "Back Home" | Kembali ke halaman inventory |
| TS-060 | Checkout Complete | Verifikasi cart kosong setelah checkout | Badge cart menghilang, cart kosong |

## 14.9 Navigation Module

| No | Module | Scenario | Expected Result |
|---|---|---|---|
| TS-061 | Navigation | Buka hamburger menu | Sidebar muncul dengan 4 menu item |
| TS-062 | Navigation | Klik "All Items" | Navigasi ke halaman inventory |
| TS-063 | Navigation | Klik "About" | Navigasi ke https://saucelabs.com/ |
| TS-064 | Navigation | Klik "Logout" | Session dihapus, redirect ke halaman login |
| TS-065 | Navigation | Klik "Reset App State" | Cart dikosongkan, state direset |
| TS-066 | Navigation | Tutup sidebar dengan tombol "X" | Sidebar tertutup |

## 14.10 End-to-End Scenarios

| No | Module | Scenario | Expected Result |
|---|---|---|---|
| TS-067 | E2E | Login → Add item → Cart → Checkout → Complete → Back Home | Alur lengkap berhasil dari awal hingga akhir |
| TS-068 | E2E | Login → Add multiple items → Checkout → Verifikasi total → Finish | Total dihitung dengan benar untuk multiple items |
| TS-069 | E2E | Login → Add item → Remove item → Verifikasi cart kosong | Cart kosong setelah semua item dihapus |
| TS-070 | E2E | Login → Add item → Logout → Login kembali → Verifikasi cart | Perilaku cart setelah re-login |
| TS-071 | E2E | Login → Sort → Add item → Cart → Continue Shopping → Sort lagi | Sorting berfungsi normal setelah navigasi |

---

# 15. Test Data

## 15.1 Data Autentikasi

### Username Valid
| Username | Password | Tipe | Perilaku |
|---|---|---|---|
| `standard_user` | `secret_sauce` | Standard | Login berhasil, semua fitur normal |
| `locked_out_user` | `secret_sauce` | Locked | Login ditolak |
| `problem_user` | `secret_sauce` | Problematic | Login berhasil, beberapa fitur bermasalah |
| `performance_glitch_user` | `secret_sauce` | Slow | Login berhasil dengan delay |
| `error_user` | `secret_sauce` | Error-prone | Login berhasil, beberapa aksi error |
| `visual_user` | `secret_sauce` | Visual Bug | Login berhasil, UI bermasalah |

### Username Invalid (untuk pengujian negatif)
| Username | Password | Expected Result |
|---|---|---|
| `invalid_user` | `wrong_pass` | Error: credentials tidak cocok |
| `admin` | `admin` | Error: credentials tidak cocok |
| ` ` (spasi) | ` ` (spasi) | Error: credentials tidak cocok |
| `standard_user` | `wrong_password` | Error: credentials tidak cocok |
| `STANDARD_USER` | `secret_sauce` | Error: credentials tidak cocok (case-sensitive) |

## 15.2 Data Produk

| ID | Nama Produk | Harga | Deskripsi |
|---|---|---|---|
| 4 | Sauce Labs Backpack | $29.99 | carry.allTheThings() with the sleek, streamlined Sly Pack that melds uncompromising style with unequaled laptop and tablet protection. |
| 0 | Sauce Labs Bike Light | $9.99 | A red light isn't the desired state in testing but it sure helps when riding your bike at night. Water-resistant with 3 lighting modes, 1 AAA battery included. |
| 1 | Sauce Labs Bolt T-Shirt | $15.99 | Get your testing superhero on with the Sauce Labs bolt T-shirt. From American Apparel, 100% ringspun combed cotton, heather gray with red bolt. |
| 5 | Sauce Labs Fleece Jacket | $49.99 | It's not every day that you come across a midweight quarter-zip fleece jacket capable of handling everything from a relaxing day outdoors to a busy day at the office. |
| 2 | Sauce Labs Onesie | $7.99 | Rib snap infant onesie for the junior automation engineer in development. Reinforced 3-snap bottom closure, two-needle hemmed sleeved and bottom won't unravel. |
| 3 | Test.allTheThings() T-Shirt (Red) | $15.99 | This classic Sauce Labs t-shirt is perfect to wear when cozying up to your keyboard to automate a few tests. Super-soft and comfy ringspun combed cotton. |

### Ringkasan Harga Produk
| Statistik | Nilai |
|---|---|
| **Harga Terendah** | $7.99 (Sauce Labs Onesie) |
| **Harga Tertinggi** | $49.99 (Sauce Labs Fleece Jacket) |
| **Total Semua Produk** | $129.94 |
| **Rata-rata Harga** | $21.66 |
| **Jumlah Produk** | 6 |

## 15.3 Data Checkout

### Data Form Valid
| Field | Contoh Nilai |
|---|---|
| First Name | John |
| Last Name | Doe |
| Zip/Postal Code | 12345 |

### Data Kalkulasi
| Skenario | Item Total | Tax (8%) | Total |
|---|---|---|---|
| 1 item ($29.99) | $29.99 | $2.40 | $32.39 |
| 2 items ($29.99 + $9.99) | $39.98 | $3.20 | $43.18 |
| Semua 6 items | $129.94 | $10.40 | $140.34 |

---

# 16. Risk Areas

## 16.1 Login & Authentication

| Risk ID | Area | Risiko | Severity | Detail |
|---|---|---|---|---|
| RISK-001 | Login | Bypass autentikasi melalui URL langsung | High | User bisa mengakses halaman internal tanpa login jika proteksi tidak berjalan |
| RISK-002 | Login | Session tidak dihapus setelah logout | High | User bisa mengakses halaman setelah logout via back button |
| RISK-003 | Login | Brute force tanpa rate limiting | Medium | Tidak ada mekanisme rate limiting untuk percobaan login berulang |
| RISK-004 | Login | Kredensial ditampilkan di halaman login | High | Username dan password terekspos di halaman (concern security untuk production) |

## 16.2 Cart Management

| Risk ID | Area | Risiko | Severity | Detail |
|---|---|---|---|---|
| RISK-005 | Cart | State cart tidak konsisten antar halaman | Medium | State Add to Cart/Remove bisa out of sync antara inventory dan detail |
| RISK-006 | Cart | Cart state setelah Reset App State | Medium | Reset mungkin tidak sepenuhnya membersihkan semua state |
| RISK-007 | Cart | Manipulasi quantity via DevTools | Low | Quantity tidak dapat diubah via UI, tetapi mungkin bisa via client-side manipulation |
| RISK-008 | Cart | Race condition pada cart update | Low | Klik cepat Add/Remove bisa menyebabkan state tidak konsisten |

## 16.3 Checkout Process

| Risk ID | Area | Risiko | Severity | Detail |
|---|---|---|---|---|
| RISK-009 | Checkout | Checkout dengan cart kosong | High | Sistem harus mencegah checkout jika cart kosong |
| RISK-010 | Checkout | Kalkulasi pajak tidak akurat | Medium | Pembulatan desimal pada tax bisa menghasilkan total yang salah |
| RISK-011 | Checkout | Form validation bypass | Medium | Validasi hanya client-side, bisa di-bypass via DevTools |
| RISK-012 | Checkout | Navigasi back setelah checkout complete | Medium | User mungkin bisa kembali ke checkout overview dan re-submit |
| RISK-013 | Checkout | XSS pada field checkout | High | Input user pada First Name, Last Name, Postal Code tidak divalidasi format |

## 16.4 Sorting

| Risk ID | Area | Risiko | Severity | Detail |
|---|---|---|---|---|
| RISK-014 | Sorting | Sorting tidak berfungsi untuk problem_user | Medium | Khusus user bertipe `problem_user`, sorting mungkin tidak bekerja dengan benar |
| RISK-015 | Sorting | Produk dengan harga sama pada sorting harga | Low | Dua produk seharga $15.99 — urutan relatif bisa tidak konsisten |

## 16.5 Session Management

| Risk ID | Area | Risiko | Severity | Detail |
|---|---|---|---|---|
| RISK-016 | Session | Session persistence setelah tab ditutup | Medium | Session mungkin masih valid setelah tab/browser ditutup |
| RISK-017 | Session | Multiple tab/window session | Medium | Membuka aplikasi di multiple tab bisa menyebabkan state conflict |
| RISK-018 | Session | Session timeout | Medium | Tidak ada mekanisme session timeout yang terlihat |
| RISK-019 | Session | Cross-user state leakage | High | State dari satu user type bisa terbawa ke user type lain jika login bergantian |

## 16.6 User-Specific Bugs

| Risk ID | Area | Risiko | Severity | Detail |
|---|---|---|---|---|
| RISK-020 | problem_user | Gambar produk salah | High | User `problem_user` mungkin melihat gambar yang tidak sesuai produk |
| RISK-021 | error_user | Aksi tertentu menghasilkan error | High | User `error_user` mengalami error pada beberapa operasi |
| RISK-022 | visual_user | Perbedaan visual/UI | Medium | User `visual_user` melihat UI yang berbeda dari seharusnya |
| RISK-023 | performance_glitch_user | Performa lambat | Medium | Delay pada loading dan navigasi untuk user ini |

---

# 17. AI Testing Context

## 17.1 Ringkasan Sistem untuk AI RAG

### Konteks Aplikasi
```
Swag Labs (SauceDemo) adalah aplikasi e-commerce demo berbasis React.js SPA yang berlokasi
di https://www.saucedemo.com/. Aplikasi ini memiliki alur utama: Login → Browse Products →
Add to Cart → Checkout → Order Complete. Terdapat 6 produk statis dengan harga tetap,
6 tipe user dengan perilaku berbeda, dan 4 opsi sorting produk.
```

### Peta Halaman (Sitemap)
```
/ (Login Page)
├── /inventory.html (Product Listing — 6 products)
│   ├── /inventory-item.html?id={0-5} (Product Detail — 6 pages)
│   └── Sorting: az | za | lohi | hilo
├── /cart.html (Shopping Cart)
├── /checkout-step-one.html (Checkout Form)
├── /checkout-step-two.html (Checkout Overview)
└── /checkout-complete.html (Order Confirmation)
```

### Alur Utama (Primary Flow)
```
1. User membuka saucedemo.com → Halaman Login
2. User memasukkan username & password → Klik Login
3. Jika valid → Redirect ke /inventory.html (Daftar Produk)
4. User melihat 6 produk, bisa sort (4 opsi), bisa klik detail
5. User klik "Add to cart" → Badge cart bertambah
6. User klik ikon cart → /cart.html (Keranjang)
7. User klik "Checkout" → /checkout-step-one.html (Form Info)
8. User isi First Name, Last Name, Postal Code → Klik Continue
9. Validasi: semua field wajib (sequential validation)
10. → /checkout-step-two.html (Overview: items + tax 8% + total)
11. User klik "Finish" → /checkout-complete.html
12. Pesan: "Thank you for your order!" → Cart dikosongkan
13. User klik "Back Home" → Kembali ke /inventory.html
```

### Elemen Kunci untuk Test Generation

**Selectors (Element IDs):**
```json
{
  "login": {
    "username_field": "#user-name",
    "password_field": "#password",
    "login_button": "#login-button"
  },
  "inventory": {
    "burger_menu": "#react-burger-menu-btn",
    "cart_link": ".shopping_cart_link",
    "sort_dropdown": ".product_sort_container",
    "add_to_cart_pattern": "#add-to-cart-{product-kebab-name}",
    "remove_pattern": "#remove-{product-kebab-name}"
  },
  "product_detail": {
    "back_button": "#back-to-products",
    "add_to_cart": "#add-to-cart",
    "remove": "#remove"
  },
  "cart": {
    "continue_shopping": "#continue-shopping",
    "checkout": "#checkout",
    "remove_pattern": "#remove-{product-kebab-name}"
  },
  "checkout_step_one": {
    "first_name": "#first-name",
    "last_name": "#last-name",
    "postal_code": "#postal-code",
    "continue": "#continue",
    "cancel": "#cancel"
  },
  "checkout_step_two": {
    "finish": "#finish",
    "cancel": "#cancel"
  },
  "checkout_complete": {
    "back_home": "#back-to-products"
  },
  "sidebar": {
    "all_items": "#inventory_sidebar_link",
    "about": "#about_sidebar_link",
    "logout": "#logout_sidebar_link",
    "reset": "#reset_sidebar_link"
  }
}
```

**Error Messages:**
```json
{
  "login_errors": {
    "username_required": "Epic sadface: Username is required",
    "password_required": "Epic sadface: Password is required",
    "credentials_mismatch": "Epic sadface: Username and password do not match any user in this service",
    "locked_out": "Epic sadface: Sorry, this user has been locked out.",
    "access_denied_pattern": "Epic sadface: You can only access '{path}' when you are logged in."
  },
  "checkout_errors": {
    "first_name_required": "Error: First Name is required",
    "last_name_required": "Error: Last Name is required",
    "postal_code_required": "Error: Postal Code is required"
  }
}
```

**Kredensial:**
```json
{
  "valid_credentials": {
    "standard": {"username": "standard_user", "password": "secret_sauce"},
    "locked": {"username": "locked_out_user", "password": "secret_sauce"},
    "problem": {"username": "problem_user", "password": "secret_sauce"},
    "performance": {"username": "performance_glitch_user", "password": "secret_sauce"},
    "error": {"username": "error_user", "password": "secret_sauce"},
    "visual": {"username": "visual_user", "password": "secret_sauce"}
  }
}
```

**Products:**
```json
{
  "products": [
    {"id": 4, "name": "Sauce Labs Backpack", "price": 29.99, "kebab": "sauce-labs-backpack"},
    {"id": 0, "name": "Sauce Labs Bike Light", "price": 9.99, "kebab": "sauce-labs-bike-light"},
    {"id": 1, "name": "Sauce Labs Bolt T-Shirt", "price": 15.99, "kebab": "sauce-labs-bolt-t-shirt"},
    {"id": 5, "name": "Sauce Labs Fleece Jacket", "price": 49.99, "kebab": "sauce-labs-fleece-jacket"},
    {"id": 2, "name": "Sauce Labs Onesie", "price": 7.99, "kebab": "sauce-labs-onesie"},
    {"id": 3, "name": "Test.allTheThings() T-Shirt (Red)", "price": 15.99, "kebab": "test.allthethings()-t-shirt-(red)"}
  ],
  "total_products": 6,
  "price_range": {"min": 7.99, "max": 49.99},
  "total_all_products": 129.94,
  "tax_rate": 0.08
}
```

### Matriks Cakupan Testing

```
┌─────────────────────┬────────────────────────────────────────────────────────┐
│ Module              │ Test Categories                                        │
├─────────────────────┼────────────────────────────────────────────────────────┤
│ Authentication      │ Valid login, Invalid login, Empty fields, Locked user, │
│                     │ Access control, Logout, Session management              │
├─────────────────────┼────────────────────────────────────────────────────────┤
│ Inventory           │ Product display, Add to cart, Remove from cart,         │
│                     │ Cart badge, Layout responsive                          │
├─────────────────────┼────────────────────────────────────────────────────────┤
│ Product Detail      │ Navigation, Product info, Add/Remove, Back button,     │
│                     │ State synchronization                                  │
├─────────────────────┼────────────────────────────────────────────────────────┤
│ Cart                │ Item display, Quantity (static), Remove item,          │
│                     │ Continue shopping, Checkout navigation                 │
├─────────────────────┼────────────────────────────────────────────────────────┤
│ Checkout Form       │ Field validation (sequential), Cancel, Continue,       │
│                     │ Placeholder text                                       │
├─────────────────────┼────────────────────────────────────────────────────────┤
│ Checkout Overview   │ Item summary, Payment info, Shipping info,             │
│                     │ Tax calculation, Total calculation, Finish, Cancel      │
├─────────────────────┼────────────────────────────────────────────────────────┤
│ Checkout Complete   │ Success message, Back home navigation, Cart cleared    │
├─────────────────────┼────────────────────────────────────────────────────────┤
│ Navigation          │ Hamburger menu, All Items, About, Logout, Reset State  │
├─────────────────────┼────────────────────────────────────────────────────────┤
│ Sorting             │ A-Z, Z-A, Price Low-High, Price High-Low, Default      │
├─────────────────────┼────────────────────────────────────────────────────────┤
│ E2E                 │ Full purchase flow, Multi-item checkout,               │
│                     │ Cart management across pages                           │
└─────────────────────┴────────────────────────────────────────────────────────┘
```

### Instruksi untuk AI Test Generator

```
Ketika menggunakan dokumen ini sebagai knowledge base untuk menghasilkan test case:

1. SELALU gunakan element IDs/selectors yang terdokumentasi untuk lokasi elemen
2. SELALU verifikasi expected results menggunakan exact error messages yang terdokumentasi
3. GUNAKAN test data yang tersedia (kredensial, produk, harga) untuk data-driven testing
4. PERTIMBANGKAN semua user types (standard, locked, problem, performance, error, visual)
5. PERHATIKAN business rules saat merancang test case boundary
6. PRIORITASKAN risk areas untuk test coverage
7. GUNAKAN URL patterns untuk navigasi dan verifikasi halaman
8. PASTIKAN test case mencakup positive, negative, dan boundary scenarios
9. PERHATIKAN bahwa validasi checkout bersifat sequential (satu error per submit)
10. PERHATIKAN bahwa quantity di cart fixed = 1 dan tidak editable
```

---

> **Dokumen ini dipersiapkan sebagai sumber pengetahuan lengkap untuk sistem AI RAG Test Case Generator. Seluruh informasi di atas diperoleh melalui eksplorasi langsung terhadap aplikasi SauceDemo pada 12 Juni 2026.**
