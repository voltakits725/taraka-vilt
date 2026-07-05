# FSD Restructuring Walkthrough (Admin Portal)

Berikut adalah rangkuman dari refactoring struktur FSD yang telah selesai secara keseluruhan untuk Portal Admin. Seluruh komponen inti di sisi Admin kini telah dipisah sesuai arsitektur Feature-Sliced Design (FSD) menggunakan format `PascalCase`.

## Struktur yang Dihasilkan
```
resources/js/
├── entities/
│   └── Admin/
│       ├── Category/
│       │   └── ui/CategoryTable.vue
│       ├── Dashboard/
│       │   └── ui/MetricCards.vue
│       ├── Ingredient/
│       │   └── ui/IngredientTable.vue
│       ├── Menu/
│       │   └── ui/MenuTable.vue
│       ├── Order/
│       │   └── ui/
│       │       ├── OrderInfo.vue
│       │       ├── OrderItemsList.vue
│       │       └── OrderTable.vue
│       ├── Reservation/
│       │   └── ui/ReservationTable.vue
│       └── Table/
│           └── ui/TableList.vue
└── features/
    └── Admin/
        ├── Category/
        │   └── ui/CategoryModal.vue
        ├── Dashboard/
        │   └── ui/
        │       ├── DashboardFilter.vue
        │       ├── RevenueChart.vue
        │       ├── SalesAnalysis.vue
        │       └── TransactionAnalysis.vue
        ├── Ingredient/
        │   └── ui/IngredientModal.vue
        ├── Menu/
        │   └── ui/MenuForm.vue
        ├── Order/
        │   └── ui/OrderStatusUpdater.vue
        └── Table/
            └── ui/TableModal.vue
```

## Hasil Refactoring Halaman-Halaman Admin

> [!TIP]
> Dengan memisahkan UI menjadi komponen FSD yang lebih kecil, setiap file menjadi lebih ringan (seperti `Dashboard.vue` yang turun drastis dari 500+ baris menjadi <150 baris). Ini membuat pembacaan kode, pencarian letak *bug*, dan penambahan fitur baru menjadi jauh lebih cepat.

1. **Dashboard**
   - Dipecah menjadi 5 subkomponen fungsional: Filter, Metrik/Overview, Grafik Utama (Pendapatan), Analisis Penjualan, dan Analisis Transaksi.
   
2. **Kategori & Menu**
   - Ekstraksi tabel `CategoryTable.vue` dan `MenuTable.vue` (Entity).
   - Ekstraksi form modal `CategoryModal.vue` dan `MenuForm.vue` (Feature).

3. **Master Bahan & Meja**
   - Ekstraksi `IngredientTable.vue` dan `TableList.vue` (Entity).
   - Ekstraksi `IngredientModal.vue` dan `TableModal.vue` (Feature).

4. **Pesanan & Reservasi**
   - Ekstraksi `OrderTable.vue` dan `ReservationTable.vue` (Entity).
   - Ekstraksi widget interaktif seperti `OrderStatusUpdater.vue` (Feature).

## Kesimpulan
Keseluruhan bagian Admin kini sudah **FSD-Compliant**. Halaman Customer (Customer Features & Entities) masih dibiarkan berada di tempat aslinya untuk tahap selanjutnya jika diperlukan.
