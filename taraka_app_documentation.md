# Dokumentasi Aplikasi Web Taraka Cafe

Aplikasi Taraka Cafe adalah platform web modern berbasis **VILT Stack** (Vue.js, Inertia.js, Laravel, TailwindCSS) yang dirancang dengan arsitektur *Clean Code* (menggunakan *Service & Repository Pattern*). Aplikasi ini melayani dua sisi utama: **Admin** (untuk operasional cafe) dan **Customer/Pelanggan** (untuk pengalaman pemesanan interaktif).

Berikut adalah rincian lengkap seluruh fitur yang ada di dalamnya:

---

## 👨‍💼 Panel Admin (Operasional & Manajemen)
Bagian ini didesain khusus untuk staf atau manajer cafe untuk mengelola semua aspek bisnis dengan antarmuka yang bersih dan responsif.

### 1. 📊 Dashboard Analitik
- **Ringkasan Penjualan:** Menampilkan statistik metrik penting seperti Total Pendapatan, Jumlah Order, Pesanan Selesai, dan Pelanggan Baru.
- **Export Report:** Fitur untuk mencetak / mengekspor laporan ringkasan operasional ke format PDF untuk keperluan pembukuan harian/bulanan.

### 2. 🍔 Manajemen Katalog Menu
- **Kategori & Menu:** Admin dapat menambah, mengubah, dan menghapus kategori serta daftar menu.
- **Upload Gambar Cloudinary:** Gambar menu diunggah dan disimpan secara aman menggunakan sistem *cloud storage* (Cloudinary), bukan penyimpanan server lokal.
- **Status Ketersediaan:** Mengatur harga, nama, deskripsi, dan status *available/sold out* dari suatu menu.

### 3. 📦 Manajemen Bahan Baku (Inventory)
- Mengelola stok *ingredient* atau bahan baku cafe untuk memastikan kelancaran pembuatan pesanan.

### 4. 🛒 Manajemen Pesanan (Order)
- **Monitoring Pesanan:** Admin dapat melihat daftar pesanan yang masuk secara berurutan.
- **Update Status:** Admin dapat memproses pesanan melalui tahapan: `Pending` ➔ `Processing` (Diproses) ➔ `Completed` (Selesai/Siap) ➔ `Cancelled` (Dibatalkan).
- **Trigger Notifikasi Instan:** Setiap kali status pesanan diubah, sistem otomatis menembak *Real-Time Push Notification* ke perangkat pelanggan yang memesan.

### 5. 🗓️ Manajemen Reservasi Meja
- **Review Booking:** Melihat daftar permohonan reservasi meja dari pelanggan beserta informasi jam dan jumlah kursi.
- **Konfirmasi Booking:** Admin berhak menerima (`Confirmed`) atau menolak (`Cancelled`) reservasi. Keputusan ini juga otomatis memicu *Push Notification* ke pelanggan.

### 6. 📱 QR Code Meja (Dine-in System)
- Admin dapat men- *generate* QR Code khusus untuk setiap nomor meja. Pelanggan cukup men- *scan* QR Code tersebut untuk langsung masuk ke mode *Dine-in* pemesanan meja.

### 7. 🎨 Pengaturan Tema (Dynamic Customizer)
- Fitur *White-Labeling* di mana Admin dapat merubah warna tema aplikasi (Surface, Teks, Akses/Aksen Warna, Border, dll) secara langsung lewat UI. Warna ini akan diterapkan secara serentak (Global) ke halaman Pelanggan dan Admin, serta disesuaikan di *SweetAlert* dan komponen lainnya.

---

## 🙋‍♂️ Panel Customer (Pengalaman Pelanggan)
Bagian ini dirancang dengan gaya estetik, interaktif, dan modern, berfokus pada kenyamanan pelanggan dalam bereksplorasi dan bertransaksi.

### 1. 🤖 Tanya AI (Groq-Powered AI Assistant)
- **Chatbot Cerdas:** Asisten virtual interaktif berbasis kecerdasan buatan (Groq AI) yang dapat diajak *chatting*.
- **Rekomendasi Pintar:** Pelanggan dapat menanyakan "Menu kopi apa yang cocok buat cuaca dingin?" atau meminta rekomendasi, dan AI akan merespons layaknya pelayan cafe (Pramusaji) profesional.

### 2. 🔍 Eksplorasi Menu & Filter
- Pelanggan dapat menjelajahi katalog menu dengan sistem *Search* langsung.
- Terdapat Filter Kategori dan pengurutan harga (Termurah/Termahal). 
- *Logic* fitur ini sangat cepat karena dijalankan dengan arsitektur *Service Pattern* di sisi *Backend*.

### 3. 🛒 Sistem Keranjang (Cart) yang Dinamis
- Fitur keranjang belanja interaktif yang bisa menambah dan mengurangi porsi (kuantitas) menu.
- Pengecekan meja secara *real-time* untuk memastikan tidak ada pemesanan ganda.

### 4. 💳 Checkout & Integrasi Midtrans
- Pemrosesan pembayaran digital otomatis yang diintegrasikan langsung dengan **Midtrans Payment Gateway** (Sistem Snap Popup).
- Mendukung berbagai metode pembayaran (GoPay, QRIS, Virtual Account, dll) dengan *auto-generate Order ID* unik (`TRK-xxxx`).

### 5. 📅 Reservasi Meja (Booking System)
- Memungkinkan pelanggan mengecek ketersediaan meja di jam dan tanggal tertentu.
- Sistem ini dilengkapi *anti-bentrok* (Slot ketersediaan 2 jam) sehingga meja tidak bisa di-*booking* dua kali di rentang waktu yang sama.

### 6. 🔔 Real-Time Native Notifications (Web Push)
- **WebSockets via Reverb:** Terhubung menggunakan Laravel Reverb.
- **Push Notification:** Pesan *popup* bawaan OS (layaknya notifikasi WA di HP atau notifikasi sudut layar di Laptop) yang muncul tanpa menutup konten web.
- **Smart Messages:** Jika pesanan *Takeaway*, notif menyuruh pelanggan ambil di kasir. Jika pesanan *Dine In*, notif memberitahu makanan sedang diantar ke Meja.
- **UX Notifikasi Premium (Ala Instagram):** Ikon lonceng merah akan mereset hitungan (0) seketika saat di-klik menggunakan *localStorage*, namun *badge* **NEW** warna hijau pada tiap daftar notifikasi tetap awet hingga pesan tersebut benar-benar diklik dan dibaca.

### 7. 📜 Riwayat Transaksi
- Halaman komprehensif bagi pelanggan untuk melacak *History* Pesanan (Lunas/Menunggu Pembayaran) dan Riwayat Reservasi (Diterima/Ditolak/Selesai).

---

> [!NOTE]
> **Arsitektur Teknis:** Aplikasi ini menggunakan **Clean Architecture** memisahkan *Controllers* dari logika bisnis kompleks dengan memindahkannya ke lapisan *Services* (`CartService`, `CheckoutService`, `CustomerService`, dll), menjadikan kode sangat solid, mudah dikembangkan, dan aman.
