# Contributing Guide

Dokumen ini merupakan panduan kontribusi dan standar penggunaan Git untuk seluruh anggota tim pengembang proyek NEXPOOL.

## 📌 Tentang Proyek

NEXPOOL merupakan aplikasi informasi dan pemesanan tiket kolam renang berbasis mobile yang dilengkapi dengan dashboard admin untuk mengelola data kolam renang.

Teknologi yang digunakan:
- Backend: Laravel dan PHP
- Database: MySQL
- Mobile: Flutter dan Dart
- Version Control: Git dan GitHub

## 📌 Branch Convention

Format penamaan branch:

`<type>/<short-description>`

| Type | Kegunaan | Contoh |
|---|---|---|
| `feature/` | Menambahkan fitur baru | `feature/123-user-login` |
| `fix/` | Memperbaiki bug | `fix/cart-total` |
| `hotfix/` | Perbaikan darurat | `hotfix/payment-down` |
| `refactor/` | Memperbaiki struktur kode | `refactor/order-service` |
| `docs/` | Mengubah dokumentasi | `docs/api-reference` |
| `test/` | Menambahkan pengujian | `test/checkout-flow` |
| `chore/` | Pemeliharaan proyek | `chore/update-deps` |

**Aturan:**
- Menggunakan huruf kecil semua.
- Menggunakan tanda hubung (-), bukan spasi.
- Menyertakan nomor tiket jika tersedia.
- Maksimal 50 karakter.
- Menggunakan nama branch yang singkat dan jelas.

## 📝 Commit Convention

Format commit:

`<type>(<scope>): <subject>`

Jenis commit:
- `feat`: Menambahkan fitur.
- `fix`: Memperbaiki bug.
- `docs`: Mengubah dokumentasi.
- `style`: Mengubah format kode.
- `refactor`: Merapikan struktur kode.
- `perf`: Meningkatkan performa.
- `test`: Menambahkan atau memperbaiki pengujian.
- `build`: Mengubah sistem build.
- `ci`: Mengubah konfigurasi CI/CD.
- `chore`: Pemeliharaan proyek.
- `revert`: Membatalkan commit sebelumnya.

**Contoh commit yang benar:**

`feat(auth): add user login`

`fix(ticket): correct ticket price calculation`

`docs(readme): update installation steps`

**Contoh commit yang salah:**

`update`

`wip`

`fix bug`

`asdf`

**Aturan:**
- Subject menggunakan huruf kecil.
- Menggunakan kalimat perintah (imperative mood).
- Tidak menggunakan tanda titik di akhir subject.
- Maksimal 50 karakter untuk subject.
- Body commit dipisahkan dengan satu baris kosong.
- Mencantumkan referensi issue jika tersedia, misalnya `Closes #123`.

## 🔀 Pull Request (PR)

Setiap perubahan kode harus diajukan melalui Pull Request.

**Aturan:**
- Judul PR mengikuti format Conventional Commits.
- Ukuran PR diusahakan kurang dari 400 baris perubahan.
- Wajib mendapatkan minimal satu persetujuan reviewer.
- Semua pemeriksaan CI harus berhasil.
- Menggunakan Squash and Merge.
- Branch dihapus setelah berhasil di-merge.

## 👀 Code Review

Setiap anggota tim wajib melakukan pemeriksaan kode sebelum perubahan digabungkan.

Label komentar review:
- `blocking:` Perubahan wajib diperbaiki.
- `nit:` Saran kecil yang bersifat opsional.
- `question:` Memerlukan penjelasan atau klarifikasi.
- `praise:` Apresiasi terhadap kode yang baik.

## ⚙️ Workflow Pengembangan

Urutan kerja pengembangan proyek menggunakan Git:

1. Pindah ke branch main dan ambil perubahan terbaru.
2. Membuat branch baru sesuai pekerjaan.
3. Mengerjakan fitur atau perbaikan.
4. Menyimpan perubahan menggunakan commit atomik.
5. Melakukan push branch ke GitHub.
6. Membuat Pull Request.
7. Menunggu proses review dan pemeriksaan CI.
8. Menggabungkan perubahan menggunakan Squash Merge.
9. Menghapus branch yang sudah selesai digunakan.

**Contoh perintah Git:**

```bash
git checkout main
git pull origin main

git checkout -b feature/user-login

git add .
git commit -m "feat(auth): add user login"

git push -u origin feature/user-login
```

## 🚫 Hal yang Dilarang

- Melakukan push langsung ke branch `main`.
- Menggunakan commit message tidak jelas seperti `wip`, `test`, atau `asdf`.
- Mengunggah file `.env` dan informasi rahasia (credentials).
- Mengunggah folder `node_modules/`.
- Mengubah branch milik anggota lain tanpa koordinasi.
- Melakukan rebase pada branch yang sudah di-push dan digunakan anggota lain tanpa koordinasi.

## 📖 Penutup

Seluruh anggota tim NEXPOOL diharapkan mengikuti standar Git ini agar proses pengembangan berjalan lebih terstruktur, aman, dan mudah dipahami.

Dengan adanya panduan ini, kolaborasi antaranggota dapat berjalan lebih efektif, mengurangi konflik kode, dan menjaga kualitas proyek NEXPOOL.