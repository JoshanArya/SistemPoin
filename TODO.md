# Fix NIS List in add_perjanjian_ortu.php

- [x] Create TODO.md with implementation steps
- [x] Update pages/cetak/add_perjanjian_ortu_fixed.php: Replace datalist/AJAX with PHP dropdown + JS cache (backup original as \_fixed.php)
- [x] Test form functionality (select siswa → ortu → submit): ✅ Dropdown loads all NIS|Nama, ortu options show only available names, fields populate correctly, submit enabled only when complete
- [x] Verify surat_perjanjian_ortu.php receives correct data: ✅ POST data includes nis, ortu_type, nama_ortu, pekerjaan, alamat, no_telp
- [x] Clean up: Removed AJAX/datalist code, no syntax errors (`php -l` clean), improved UX with disabled states
- [x] Mark complete: NIS list fixed in pages/cetak/add_perjanjian_ortu_fixed.php ✅
