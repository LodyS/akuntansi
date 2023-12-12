set_neraca
==========

| id |                   nama                   | induk | kode  | level | urutan | jenis | jenis_neraca | created_at | updated_at |   |
|----|------------------------------------------|-------|-------|-------|--------|-------|--------------|------------|------------|---|
| 1  | ASSET                                    |       | 1     | 0     | 1      | 1     | Aktiva       |            |            |
| 2  | ASET LANCAR                              | 1     | 1.1   | 1     | 1      | 1     | Aktiva       |            |            |
| 3  | KAS & SETARA KAS                         | 2     | 1.1.1 | 2     | 1      | 1     | Aktiva       |            |            |
| 4  | PIUTANG PELAYANAN                        | 2     | 1.1.2 | 2     | 2      | 1     | Aktiva       |            |            |
| 5  | PIUTANG LAIN-LAIN                        | 2     | 1.1.3 | 2     | 3      | 1     | Aktiva       |            |            |
| 6  | PERSEDIAAN                               | 2     | 1.1.4 | 2     | 4      | 1     | Aktiva       |            |            |
| 7  | BEBAN DIBAYAR DIMUKA                     | 2     | 1.1.5 | 2     | 5      | 1     | Aktiva       |            |            |
| 8  | JUMLAH ASET LANCAR                       | 2     | 1.1.6 | 2     | 6      | 1     | Aktiva       |            |            |
| 9  | ASET TETAP                               | 1     | 1.2   | 1     | 2      | 1     | Aktiva       |            |            |
| 10 | TANAH                                    | 9     | 1.2.1 | 2     | 1      | 1     | Aktiva       |            |            |
| 11 | GEDUNG & BANGUNAN                        | 9     | 1.2.2 | 2     | 2      | 1     | Aktiva       |            |            |
| 12 | PERALATAN MEDIS                          | 9     | 1.2.3 | 2     | 3      | 1     | Aktiva       |            |            |
| 13 | PERALATAN KANTOR & RT                    | 9     | 1.2.4 | 2     | 4      | 1     | Aktiva       |            |            |
| 14 | MESIN-MESIN                              | 9     | 1.2.5 | 2     | 5      | 1     | Aktiva       |            |            |
| 15 | KENDARAAN                                | 9     | 1.2.6 | 2     | 6      | 1     | Aktiva       |            |            |
| 16 | ASET TETAP (PEROLEHAN)                   | 9     | 1.2.7 | 2     | 7      | 1     | Aktiva       |            |            |
| 17 | AK. PENYUSUTAN ASET TETAP                | 9     | 1.2.8 | 2     | 8      | 1     | Aktiva       |            |            |
| 18 | JUMLAH ASET TETAP (NILAI BUKU)           | 9     | 1.2.9 | 2     | 9      | 1     | Aktiva       |            |            |
| 19 | ASET TIDAK BERWUJUD                      | 1     | 1.3   | 1     | 3      | 1     | Aktiva       |            |            |
| 20 | SOFTWARE                                 | 19    | 1.3.1 | 2     | 1      | 1     | Aktiva       |            |            |
| 21 | SERTIFIKASI MUTU RS                      | 19    | 1.3.2 | 2     | 2      | 1     | Aktiva       |            |            |
| 22 | ASET TIDAK BERWUJUD (PEROLEHAN)          | 19    | 1.3.3 | 2     | 3      | 1     | Aktiva       |            |            |
| 23 | AK.AMORTISASI ASET TIDAK BERWUJUD        | 19    | 1.3.4 | 2     | 4      | 1     | Aktiva       |            |            |
| 24 | JUMLAH ASET TIDAK BERWUJUD (NILAI TETAP) | 19    | 1.3.5 | 2     | 5      | 1     | Aktiva       |            |            |
| 25 | ASET LAIN-LAIN                           | 1     | 1.4   | 1     | 4      | 1     | Aktiva       |            |            |
| 26 | ASET DALAM PROSES                        | 25    | 1.4.1 | 2     | 1      | 1     | Aktiva       |            |            |
| 27 | PENYERTAAN                               | 25    | 1.4.2 | 2     | 2      | 1     | Aktiva       |            |            |
| 28 | JUMLAH ASET LAIN-LAIN                    | 25    | 1.4.3 | 2     | 3      | 1     | Aktiva       |            |            |
| 29 | TOTAL ASET                               | 1     | 1.5   | 1     | 5      | 1     | Aktiva       |            |            |
| 30 | LIABILITAS                               |       | 2     | 0     | 2      | 1     | Passiva      |            |            |
| 31 | LIABILITAS JK. PENDEK                    | 30    | 2.1   | 1     | 1      | -1    | Passiva      |            |            |
| 32 | UANG MUKA PERAWATAN                      | 31    | 2.1.1 | 2     | 1      | -1    | Passiva      |            |            |
| 33 | UTANG REKANAN                            | 31    | 2.1.2 | 2     | 2      | -1    | Passiva      |            |            |
| 34 | UTANG JASA PROFESI KESEHATAN             | 31    | 2.1.3 | 2     | 3      | -1    | Passiva      |            |            |
| 35 | PEND.DITERIMA DIMUKA                     | 31    | 2.1.4 | 2     | 4      | -1    | Passiva      |            |            |
| 36 | BEBAN YMH DIBAYAR                        | 31    | 2.1.5 | 2     | 5      | -1    | Passiva      |            |            |
| 37 | UTANG PIHAK KETIGA                       | 31    | 2.1.6 | 2     | 6      | -1    | Passiva      |            |            |
| 38 | UTANG PAJAK                              | 31    | 2.1.7 | 2     | 7      | -1    | Passiva      |            |            |
| 39 | UTANG JK. PENDEK LAINNYA                 | 31    | 2.1.8 | 2     | 8      | -1    | Passiva      |            |            |
| 40 | JUMLAH LIABILITAS JANGKA PENDEK          | 31    | 2.1.9 | 2     | 9      | -1    | Passiva      |            |            |
| 41 | UTANG JANGKA PANJANG                     | 30    | 2.2   | 1     | 2      | -1    | Passiva      |            |            |
| 42 | DAPERSI ( Manfaat Pasti Ke Iuran Pasti ) | 41    | 2.2.1 | 2     | 1      | -1    | Passiva      |            |            |
| 43 | Kewajiban Imbal Pasca Kerja              | 41    | 2.2.2 | 2     | 2      | -1    | Passiva      |            |            |
| 44 | JUMLAH LIABILITAS JK. PANJANG            | 41    | 2.2.3 | 2     | 3      | -1    | Passiva      |            |            |
| 45 | ASET NETO                                | 30    | 2.3   | 1     | 3      | -1    | Passiva      |            |            |
| 46 | ASET NETO TIDAK TERIKAT                  | 45    | 2.3.1 | 2     | 1      | -1    | Passiva      |            |            |
| 47 | ASET NETO TERIKAT TEMPORER               | 45    | 2.3.2 | 2     | 2      | -1    | Passiva      |            |            |
| 48 | ASET NETO TERIKAT PERMANEN               | 45    | 2.3.3 | 2     | 3      | -1    | Passiva      |            |            |
| 49 | JUMLAH ASET NETO                         | 45    | 2.3.4 | 2     | 4      | -1    | Passiva      |            |            |
| 50 | TOTAL  LIABILITAS                        | 30    | 2.3   | 1     | 3      | -1    | Passiva      |            |            |
(50 rows)

