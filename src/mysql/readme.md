# Penggunaan
berkut contoh penggunaan
```mysql
SELECT 
    b.faktur
    , b.tgl
    , b.dari
    , toJson(CONCAT_WS("[;]","kode","kode")) AS ke
FROM mtsgud b
JOIN mtsgud i ON i.faktur = b.faktur
WHERE b.faktur = 'M1120240408002' 
GROUP BY b.faktur
```
