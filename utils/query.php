1. Buat validasi required
2. Nomor urut di schedule dyeing
3. Delete hard code (ip programmer aja dulu)


Select * from MoveBatch
-- Move by position
Insert into MoveBatch
(Dyelot, Redye, Job ,DstMachineNo, InsertAtPos, AcknowledgeRequired)
Values 
('00173933', 302, 2 ,'1455', 1, 0)
- untuk ubah nomor urut ganti nomor 1 menjadi nomor urut yg di inginkan.
- untuk ubah nama mesin, ganti '1455' menjadi nomor mesin yg di inginkan (tetapi jika beda treatment belum bisa)