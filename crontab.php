CREATE TABLE public.data_mentah (
	tanggal date NOT NULL,
	id_pegawai varchar(36) NOT NULL,
	hari varchar(10) NULL,
	jam_kerja varchar(100) NULL,
	jadwal_masuk timestamp NULL,
	jadwal_pulang timestamp NULL,
	finger_masuk timestamp NULL,
	finger_pulang timestamp NULL,
	pulang_cepat integer NULL,
	datang_telat integer NULL,
	lembur integer NULL,
	lembur_diakui integer NULL,
	kode_masuk varchar(100) NULL,
	keterangan_masuk varchar(100) NULL,
	kode_tidak_masuk varchar(100) NULL,
	keterangan_tidak_masuk varchar(100) NULL
)
WITH (
	OIDS=FALSE
) ;
ALTER TABLE public.data_mentah ALTER COLUMN finger_masuk TYPE timestamp USING finger_masuk::timestamp;
ALTER TABLE public.data_mentah ALTER COLUMN finger_pulang TYPE timestamp USING finger_pulang::timestamp;
ALTER TABLE public.data_mentah ALTER COLUMN jadwal_masuk TYPE timestamp USING jadwal_masuk::timestamp;
ALTER TABLE public.data_mentah ALTER COLUMN jadwal_pulang TYPE timestamp USING jadwal_pulang::timestamp;


ALTER TABLE data_mentah ADD UNIQUE (tanggal, id_pegawai);


//////////////////// ganti

CREATE TABLE public.m_kategori_user (
	id_kategori_user int4 NOT NULL,
	nama_kategori_user varchar(50) NULL DEFAULT NULL::character varying,
	keterangan varchar(150) NULL DEFAULT NULL::character varying
)
WITH (
	OIDS=FALSE
) ;


INSERT INTO public.m_kategori_user (id_kategori_user,nama_kategori_user,keterangan) VALUES
(2,'Administrator','Untuk Admin Aplikasi')
,(3,'Admin BKD','Untuk Admin BKD')
,(5,'Eksekutif','Untuk Kepala Dinas')
,(4,'Admin SKPD','Untuk Admin SKPD')
,(1,'Developer','Untuk Developer')
;


//////////////////// ganti


CREATE TABLE public.c_security_user_new (
	id varchar(255) NOT NULL,
	active bool NULL,
	fullname varchar(255) NULL,
	"password" varchar(255) NULL,
	photo varchar(255) NULL,
	username varchar(255) NULL,
	id_role varchar(255) NULL,
	kode_unor varchar NULL,
	kode_instansi varchar(13) NULL,
	kode_jenis_jabatan varchar(255) NULL,
	id_pegawai varchar(36) NULL,
	password_new varchar NULL,
	id_kategori_user int4 NULL,
	CONSTRAINT c_security_user_new_pkey PRIMARY KEY (id)
)
WITH (
	OIDS=FALSE
) ;


INSERT INTO public.c_security_user_new (id,active,fullname,"password",photo,username,id_role,kode_unor,kode_instansi,kode_jenis_jabatan,id_pegawai,password_new,id_kategori_user) VALUES
('8afb117e-342d-45e2-bf2b-5c5dbb216228',true,'SATUAN POLISI PAMONG PRAJA','$2a$10$arkGfOLnTD.l4edKw7Oy4Oxp8sVeD3zik5dDhwGzJ03BPfBT7G10O','img/user/no_photo.jpg','satpol_pp','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.19.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('d0e49bc2-6890-4643-bae5-80f9985cc2bc',true,'Auditor','$2a$10$iydbZmUbzWjJAJjsU0Y7AuXf70vopnHqa98dISquGkRMyEQ7HYQQW','img/user/no_photo.jpg','auditor','a137224c-2c13-4977-a9df-967535527cda',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',3)
,('aa56bd21-92b9-479c-b3e7-360b7a7e8608',true,'KELURAHAN BENDUL MERISI','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_bendulmerisi','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.14.02.00.00','e138a042-7e83-11e6-bd1e-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('b2a27249-9b5a-4401-accc-d5390dc73af3',true,'dkpp2','$2a$10$G8C0U2bPASY78C/5iP0tleYGEIpJ783OvxbckZBM.fxd/76FlmeF.','img/user/no_photo.jpg','dkpp2','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.12.00.00.00',NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('67f53bcb-047e-483e-8bb5-77f68f1d204e',true,'KELURAHAN KETABANG','$2a$10$3AbHvQoqEMFhc7G3oXdCPuXv2fcAEHhPLkiFr/s20AVWQyQkB3Pdm','img/user/no_photo.jpg','kel_ketabang','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.01.04.00.00','e1379cba-7e83-11e6-bc17-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('04afb6c3-2379-4337-9a6e-dbcafddf7675',true,'uptd parkir khusus mayjend','$2a$10$pPiOuyMn50mh.HcoFlbySO3YHmapgY8gJDs1y8AySP.SzbN4ZB64y','img/user/no_photo.jpg','uptdparkir_mayjend','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.11.00.00.00','e38b1f90-561f-11e7-8882-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('dc43f4b3-8e00-4692-811b-81cf38243861',true,'THP KENJERAN','$2a$10$2XrsfFOZ1PV0afzE.N4Itub2FuAXHg16UcO.mD3tUID7FTSgpGhN.','img/user/no_photo.jpg','thp_kenjeran','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.04.00.00.00','e38aebb0-561f-11e7-8858-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('c7c8386c-c746-4040-ab44-72e532b80645',true,'KELURAHAN GAYUNGAN','$2a$10$tEZ81YANb2.36ZD6mzYgm.tdEmRtuWJgtW3QXmZ2Wqd0kc3nuUmR6','img/user/no_photo.jpg','kel_gayungan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.24.01.00.00','e135a400-7e83-11e6-ba15-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('178ad59b-3c03-4c57-8ec0-2e1bc5600a81',true,'KELURAHAN GUNUNGANYAR TAMBAK','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_gununganyartambak','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.22.04.00.00','e137b218-7e83-11e6-bc2a-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('0342747a-ea6a-42ab-8ce2-dfa4e5c4b55a',true,'KELURAHAN GUNUNGSARI','$2a$10$sOhNsl5TuCDrxCEtb7X5zOmI7I4urnaUgqDhnSNzb75Dm.tV1qKEK','img/user/no_photo.jpg','kel_gunungsari','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.26.03.00.00','e1379350-7e83-11e6-bc0c-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('c27c6fa5-f89d-4eab-b0c3-805bf8ccb87a',true,'KELURAHAN JAMBANGAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_jambangan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.23.01.00.00','e137449a-7e83-11e6-bbc1-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('7413658c-b977-46d7-98f5-2c8f70ca9c9a',true,'KELURAHAN KENJERAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_kenjeran','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.29.05.00.00','e13588bc-7e83-11e6-b9fc-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('d26728c8-c59f-4bc8-891c-2310b4011bb2',true,'abc','$2a$10$vmrbhjV5A9QtZXaNq/3KquMQKsjUSXmCymK4qVGK1/j9c72/DmZTi','img/user/no_photo.jpg','abc','a137224c-2c13-4977-a9df-967535527cda',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',3)
,('20c91959-c778-4f8b-876e-256acb6224e8',true,'def','$2a$10$iPG1Q7VCns339qC71zU.lek5QU2D.E9yZQ5UqDU45AJ4GO2XaTa1.','img/user/no_photo.jpg','def','a137224c-2c13-4977-a9df-967535527cda',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',3)
,('4a25e629-4e3c-4f3d-992a-17994abcf2b1',true,'qwe','$2a$10$RUjslE8meCmUtimKZlzvMu90xUMyNwnLyk3wFELWEASuzcPXaMg9.','img/user/no_photo.jpg','qwe','a137224c-2c13-4977-a9df-967535527cda',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',3)
,('575b994c-650e-43e4-802d-7fa3c37e39fb',true,'arsips','$2a$10$S1saFqRoGsL1jjbQwg3JS.2j33KBhLmlxQGH.7/VQYm3TbeHhWzsS','img/user/no_photo.jpg','arsips','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'3.01.00.00.00',NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('2afb7662-0da1-4e7f-b207-e5ba73e01faf',true,'KELURAHAN MANYAR SABRANGAN','$2a$10$uHV5EaOb2.qqDWKvBIXPLe31Ch83byFV0h.n22LEnUTzGOtnEcJYq','img/user/no_photo.jpg','kel_manyarsabrangan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.20.02.00.00','e13651ac-7e83-11e6-bacb-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('c59c99d6-57bd-498d-aa85-91774a7226a1',true,'UPTSA PUSAT SIOLA','$2a$10$jNT45p/ee7WG46BV8/e7F..osJ6HuFobDcFJxnFIHGS1MpKEWf2pu','img/user/no_photo.jpg','uptsasiola','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'3.13.00.00.00','e38bc09e-561f-11e7-891d-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('8b5148c2-30df-4c57-90b5-c85cbb05325b',false,'agung_desk','$2a$10$Ei380EbuPp8bDZGIE9bAjenlM7nEY6vP339sSkATjTsnOnxJq/FLW','img/user/no_photo.jpg','agung_desk','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('79bfe271-fdbd-41a2-bbde-9f44c3b502fd',true,'KELURAHAN SONOKUIJENAN','$2a$10$zTekKIPzLcZflxAITS4uZu5faC0Jmkgb8lXiiIR2ZiKJubgyUrQTe','img/user/no_photo.jpg','kel_sonokuijenan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.28.03.00.00','e1349330-7e83-11e6-b8e9-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('3a76456f-8921-48b5-bcac-75df866455b6',true,'KELURAHAN TAMBAK WEDI','$2a$10$KbFD9u/OVS3QgJN5owkfJu2HZ4YVIumn5lc29fJbM3AVPufve9MyO','img/user/no_photo.jpg','kel_tambakwedi','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.17.04.00.00','e136addc-7e83-11e6-bb29-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('86401fa0-7eb5-4698-a5f9-4b82e89f062d',true,'KELURAHAN TANDES','$2a$10$o4O/RhZC7hsC9LyPPVd8pOQfA4q73pH7o1Byl3hHWvMoLGpYIluFO','img/user/no_photo.jpg','kel_tandes','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.12.13.00.00','e138a5ba-7e83-11e6-bd24-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('f0e152a2-1d58-41b1-97f8-08dd8eb4b2f6',true,'SEKRETARIAT DAERAH','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','sekda','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'1.00.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('b5991fde-4554-4206-8ef5-e52a694ce1a1',true,'KELURAHAN BIBIS','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_bibis','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('f15ee1fa-914d-11e8-a243-000c29e9e639',true,'Fatich',NULL,'img/user/no_photo.jpg','fatich',NULL,NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',1)
,('196959b8-a671-11e8-bb05-000c29e9e639',true,'rey',NULL,'img/user/no_photo.jpg','rey',NULL,NULL,NULL,NULL,NULL,'UzBZT29vRUpWcUVZUFlsSjVaV09zdz09',1)
,('314a5716-b45e-4c52-964d-859da46ff1f4',true,'KELURAHAN ROMOKALISARI','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_romokalisari','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.18.05.00.00','e1362cea-7e83-11e6-baa2-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('567afff0-b3d2-4267-a81a-7ea98b4ee4de',true,'KELURAHAN BALASKLUMPRIK','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_balasklumprik','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.25.04.00.00','e137edaa-7e83-11e6-bc69-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('b102ccc4-ed06-45db-a88d-da81d3152efd',true,'KELURAHAN DUKUH SETRO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_dukuhsetro','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.05.08.00.00','e138624e-7e83-11e6-bcdd-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('3ade5cc5-c6f0-4f57-82c9-8e658790b3c4',true,'KELURAHAN GENTING','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_genting','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.27.07.00.00','e135cfde-7e83-11e6-ba43-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('057234ba-af13-4b6d-adf9-12af2db88fc5',true,'Adi','a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09','img/user/no_photo.jpg','adi','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',1)
,('ee19be13-b61e-48bf-bc2b-314dc6e6fb56',false,'haidar','$2a$10$1nv6ZKhy.V2aoB4z9nC9WuImMJtZi8ToXD5y7Ec3aDipJQdo8Vb42','img/user/no_photo.jpg','haidar','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('68033822-08cc-4855-856e-a70476a1902l',false,'Irwin','$2a$10$vQiogNf7GNm910MGkkXo6uqZ4NJKDzJIs87JFHlbU3DwH9nfZkahC','img/user/no_photo.jpg','irwin','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('68033822-08cc-4855-856c-a70476a1902l',false,'ADMIN','$2a$10$BKjrMkxgS8CGaRb5OrmkKOyyJ6b5uwJLHnNdQgLv5tyzlc.OzH1sS','img/user/no_photo.jpg','admin','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('10204456-1814-4469-a90f-6681470b5536',false,'ahmad','$2a$10$/kpwpQj5ZpVK5pV4SJNq2ebmwSv8LO0kAl8y1xcllODrjv1z4DTDK','img/user/no_photo.jpg','ahmad','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('f8724635-98f3-4b33-9ab6-b2f34aa39b31',false,'hari','$2a$10$7sToKdky3WNNGdIu.uEaBOrL9AV.dM.PDclaID87g.TUZDz1xlvwa','img/user/no_photo.jpg','hari','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('ff874516-ef8d-4eab-a775-c10c5bdb9a69',true,'erinda','$2a$10$KQ9.27ceBdCUObdFL7q5VO1KVc9taVTr4BEacqWdhZQEE5oCIjviy','img/user/no_photo.jpg','erinda','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('fa39c309-67c0-4fd9-880b-dad688c926e3',false,'gusti','$2a$10$2E66YQ502klQOa1tjdiuDuBwiP7GEuuRg/B5q0llCNHkMmDCJkJl2','img/user/no_photo.jpg','gusti','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('4d005bc4-8850-4231-9f89-b511c9ad8470',true,'risal','$2a$10$aPG.aRV/cXgbvKXguphoK.gjyLJGPz2NvF3MqB2jF7zpCvJtLiKUe','img/user/no_photo.jpg','risal','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('49177d87-ac74-4870-b231-f650ad10946e',false,'Kominfo Desk','$2a$10$LIRl5VL4dM7AC7.73AJFsOUpjAcsmzYsNRJU/Xawwloml7IIf64vS','img/user/no_photo.jpg','kominfo_desk','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('ede4311d-6119-479d-bdc5-3e8df01f8abc',false,'SILVY OKTAVIYANI','$2a$10$71cak/YAmVy1jqiisZtAeOuXfJoRllBAntblcMRAuKjFuPM1CW6N6','img/user/no_photo.jpg','silvy','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('3344b239-44b7-4b8c-9bc9-f572f19cb93c',false,'wahyu','$2a$10$xfYJWAuKW27snYKZIjstH.JVlBPUUEHywb1j0KxpDaOOYe7MZ23Fu','img/user/no_photo.jpg','wahyu','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('a9b06d36-1005-4489-8c0b-89a9c9d30a10',true,'WALIKOTA','$2a$10$U66qJI/ms5mn/qoErcEYHumQeQHrKeOo6uZaqnMvwzr2hHhWriTw.','img/user/no_photo.jpg','walikota','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('9b5df0c0-ae11-45b4-acbe-31b76c839857',true,'Ali Wafa','$2a$10$rgV8wPLFzSXekElrtQXHROtseRc6ncEk5bB0OUnuoLg/LVXpshDMm','img/user/no_photo.jpg','aliwafa','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('68033822-08cc-4855-856g-a70476a1902l',false,'Agus','$2a$10$VVlQ.t3cb2ZaxqxCaIjwxuk0/VDfrk5f6PYfrfzlmjPhqfriEXKUC','img/user/no_photo.jpg','agus','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('d554c410-0520-4f75-b602-4beb13b41243',true,'YUDHO FEBRIADI','$2a$10$h7Nu31tKYUFGzOODqCG6nup9GN6ex7JC5upaxZ0X6Ca.nePcDEMZu','img/user/no_photo.jpg','yudho','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('0434d482-d555-4b4a-9c4b-a9f4e903721d',true,'egov_desk','$2a$10$o9U2xvJhHluH5LtF/KCvg.I475TM0v.uLJ0J7eAoisjjsI.SUGoLe','img/user/no_photo.jpg','egov_desk','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('9de72279-fce9-4f34-b60a-e04c696f06c4',false,'Aina Nur Af''ida','$2a$10$kzGXD1Ri4zO9aG1yYrXMiOFN/ikCd8ctXtqEM80sX84STE7gkB2iW','img/user/no_photo.jpg','Ainaafi','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('4c9f5636-7af4-4909-85a6-aa0f11e024bf',true,'hadi','$2a$10$SLqYIZPVs1u37Xh7ffhtBu.RKsYXV8.K9twYfrHo4rcYRjt81siSe','img/user/no_photo.jpg','hadi','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('902d4341-4b26-4c10-b523-00c3940f8625',false,'gbrs','$2a$10$.Z98Ok9GH8WHar4/uptjremiI9LUYctZesjKpDFVoYGE9F3b5C1Ye','img/user/no_photo.jpg','gbrs','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('94d11813-141c-41eb-8eb5-c6df9d9fdaa7',true,'adminbkd','$2a$10$qIDkJCe9/JivtWK7R8mEPeGK1C9pgXt250PCP2S8pV7V2rZ6BppIu','img/user/no_photo.jpg','adminbkd','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('5087cb92-4ad9-4b33-81a5-63da8d6bf233',true,'a_desk','$2a$10$bUnROzfW7iLTFi.XD9A73.MtLbI9AP6cr5id6L55EJDCbBLPyy7iu','img/user/no_photo.jpg','a_desk','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('47274bc3-a6c0-4eb8-8ef3-4e54d04fc473',false,'Farris Fardiansyah','$2a$10$G2lCGwzLIehimABjw6nsI.oLQN49cy488UyhzTA6yxv/mAlK7TwC2','img/user/no_photo.jpg','farris_gs','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('6324618f-e26c-4288-bc31-0e4c50add934',true,'Yonda','$2a$10$4B6Zjt9jVWCF4KnXWoPFdO2kTV8w4mRKG4/qqLSrJQxV91bFyexlu','img/user/no_photo.jpg','yonda','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('98822cd2-3408-41e9-8d99-d2c85ece1c8d',true,'Achmad Hadi','$2a$10$9EWi48yq/CMaILgC.C.COOkDA92OAAyKXapsprTcTSZ5aCIM8ZxvO','img/user/no_photo.jpg','ach_hadi','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('84a50a93-a6a7-407b-8d75-38074fa84914',true,'YUSTI MUSTIKO','$2a$10$6ON8PJbNr3xaMYMMaZVWxe9/75bntZQwI250O5ojJ6Id6kzkXrWoG','img/user/no_photo.jpg','yusti','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('021ede5b-331d-4b32-8d1f-9184429c9995',false,'nur','$2a$10$QaD/FTk7RL7xifjb3PqpPewdoslCX8sc7jChMDtY21IJq4P1lUX4q','img/user/no_photo.jpg','nur','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('387cebb8-6027-4a30-a411-b3a4ece62d02',true,'riski','$2a$10$q2Z4hvvBwFFxG1b.ujkUqOTF7qUPRMODmqW7fOSnATs5wn5DdmLW6','img/user/no_photo.jpg','riski','88fd9d65-d094-4157-b882-f3b5f48f73d7',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',2)
,('c9fb7271-01cb-4ebd-83ba-ca81f2b0d4c7',false,'BPK','$2a$10$28ckf7G72W6JJlCclvuQNuBKKmeyiq7650oI2Rdbog/rAgk8dj.Ve','img/user/no_photo.jpg','bpk','a137224c-2c13-4977-a9df-967535527cda',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',3)
,('da1fc618-7bc6-4193-8f26-b1db1e0b0240',true,'KELURAHAN KETINTANG','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_ketintang','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.24.02.00.00','e1379508-7e83-11e6-bc0e-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('e9073a87-f693-427a-a7b6-bc4349bfb928',true,'KELURAHAN MENUR PUMPUNGAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_menurpumpungan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.16.04.00.00','e13888fa-7e83-11e6-bd06-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('f8c57384-88b0-400e-ae0b-6c549c0e5b13',true,'Kominfo EGOV','$2a$10$zEJyMVfuH2XXGtdZNusGEOeTBuewdeHjnZOOrLtGBbFBjg8cpOuGe','img/user/no_photo.jpg','egov','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.16.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('a685e014-99ec-4a3d-8cae-ea27c729fa36',true,'UPTSA MANYAR','$2a$10$nQ/W9548eAYe6cS2ziQfHOj5KmAhD5OPrcPcdNw..aItGBdygwpJ2','img/user/no_photo.jpg','uptsa_manyar','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'3.13.00.00.00',NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('e002afb4-2b57-4239-b0e9-2d60214cd136',true,'KECAMATAN SUKOLILO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kec_sukolilo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.16.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('75e3c389-fc9a-443e-bb2b-e56e4803a775',true,'KELURAHAN KANDANGAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_kandangan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.18.01.00.00','e1358222-7e83-11e6-b9f6-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('902f5422-a443-4146-bbcd-f9922b16c8fa',true,'KELURAHAN KEDUNG BARUK','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_kedungbaruk','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.15.03.00.00','e136cd26-7e83-11e6-bb49-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('c0f660ee-8866-4473-946f-68fdcd907bdd',true,'KELURAHAN PLOSO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_ploso','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.05.02.00.00','e1387360-7e83-11e6-bcef-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('5c72e15c-1da3-4f60-8067-2b2d68fd507d',true,'KELURAHAN PUTAT JAYA','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_putatjaya','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.11.04.00.00','e136162e-7e83-11e6-ba8a-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('79d42353-9e85-4e80-a9e4-d02923820950',true,'KELURAHAN RANGKAH','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_rangkah','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.05.05.00.00','e137285c-7e83-11e6-bba1-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('ddd3730f-d0a3-410d-80ff-a1040383a63a',true,'KELURAHAN SIDOTOPO WETAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_sidotopowetan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.17.02.00.00','e135932a-7e83-11e6-ba05-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('d9f7278d-5d6c-49f5-945b-b5c8f9fd0b5d',true,'KELURAHAN TEGALSARI','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_tegalsari','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.03.04.00.00','e136f30a-7e83-11e6-bb71-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('ffd1dac3-8f58-4973-9481-7851b2538534',true,'KELURAHAN TEMBOK DUKUH','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_tembokdukuh','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.02.05.00.00','e13790b2-7e83-11e6-bc09-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('c072f3a5-bc0f-4060-96f3-e869289048ed',true,'KELURAHAN NGAGEL REJO','$2a$10$RfSlMzP4HOEtkUv4/19hkOCzNF1hUYhfvR1.FNztEt.n3yNiHDtDG','img/user/no_photo.jpg','kel_ngagelrejo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.10.04.00.00','e137f35e-7e83-11e6-bc6f-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('25ca2e67-7e98-4162-97b6-8dbdcd8ac6ce',true,'KELURAHAN DUKUH SUTOREJO','$2a$10$GwGDgFekML55Rm8rKXr6oe8QVrK875/0mq1fisHJyF8GdTybtC7Gi','img/user/no_photo.jpg','kel_dukuhsutorejo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.20.05.00.00','e136608e-7e83-11e6-badb-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('3362927d-d123-4b2d-9807-04e664af9bbc',true,'KELURAHAN KALISARI','$2a$10$JYWtS32h7UDTr2oBJ2mce.sIh.QSf3Hs53tynyn3zRoilrJucuOOC','img/user/no_photo.jpg','kel_kalisari','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.20.04.00.00','e13544ec-7e83-11e6-b9b3-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('7d196c62-7f19-4f8e-8223-79b3d5e87de6',true,'KELURAHAN KEJAWEN PUTIH TAMBAK','$2a$10$SIdqF01Ac6iQf.ZPiN1xuuLMsQAc8OXeUJAF.MWfJ9NH2NKIjCEby','img/user/no_photo.jpg','kel_kejawenputih','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.20.03.00.00','e137bfec-7e83-11e6-bc38-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('a42dbe1a-bcef-419b-b1d7-d4990593a721',true,'KELURAHAN MARGOREJO','$2a$10$ENN39iuauL2AC/imlHcBRu.t9Dkm0sY3LdMcfI/cuPlFAHJNcoJjS','img/user/no_photo.jpg','kel_margorejo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.14.03.00.00','e135478a-7e83-11e6-b9b6-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('111262b2-5b72-444e-960d-389c248dc799',true,'KELURAHAN MENANGGAL','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_menanggal','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.24.03.00.00','e137840a-7e83-11e6-bbfd-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('0270d557-a39b-4879-bb4e-a9efddccec3c',true,'KELURAHAN PRADAH KALI KENDAL','$2a$10$CKuqNRHE7xWxtKg7XddqnuId2PXQpaoNGOiQnzLDQnouxP48GZiKm','img/user/no_photo.jpg','kel_pradahkalikendal','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.26.04.00.00','e13863de-7e83-11e6-bcdf-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('4fbac897-29eb-487a-bae7-99c7ab6bb574',true,'KELURAHAN SIMO MULYO','$2a$10$ICMUm38DTO3XMOhyi.DpL.OxTWmnRR93wW8UbDRBEnORJemwGke5O','img/user/no_photo.jpg','kel_simomulyo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.28.05.00.00','e135e7ee-7e83-11e6-ba5c-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('78158159-30b0-485b-b956-ae6ff1052410',true,'RSUD Dr. MUHAMMAD SOEWANDHIE','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','soewandhi','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'3.12.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('85ebeaee-7525-4cb3-bbe2-ba0316020c01',true,'RSUD Bakti Dharma Husada','$2a$10$dCim6W3lDr37mx3vrNG1We2Vl4kYI7YxDwDoXqxWJZ1CtXaogWv0.','img/user/no_photo.jpg','bdh','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'4.03.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('3c0aa5ef-ac1c-43d4-aeb1-427b0cebaf84',true,'KECAMATAN MULYOREJO','$2a$10$cPZs9SWsWMmsTga6WGQFt.QhmEMdIOuroq/2qkzVDYi5DKaJsJo62','img/user/no_photo.jpg','kec_mulyorejo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.20.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('49b75c8a-749b-4ba5-a15b-a12d467223d4',true,'ASISTEN PEREKONOMIAN DAN PEMBANGUNAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','asisten2','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'1.00.02.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('27848e6f-d38b-43d7-8180-29d6ce028850',true,'ASISTEN ADMINISTRASI UMUM','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','asisten3','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'1.00.03.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('73eb2a26-c00a-45fd-8dac-0a3ac4b7e4b3',true,'KECAMATAN BUBUTAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kec_bubutan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.02.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('6d5df195-eb54-420c-9571-6872b83ce56e',true,'KECAMATAN KARANG PILANG','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kec_karangpilang','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.13.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('e8dd4b42-58e9-44ba-80f5-11b04f2f2dbf',true,'KELURAHAN LIDAH WETAN','$2a$10$d5D7JqwgQJyfw2i4mTEcKOozSxBjbrz0V8PMa.5Kxgeu4cN0QAWQy','img/user/no_photo.jpg','kel_lidahwetan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.19.05.00.00','e1364eb4-7e83-11e6-bac7-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('c3ce14cc-eefa-4119-a262-5ecdaffebbbc',true,'RSUD dr. Mohamad Soewandhie','$2a$10$c5TDYW2PYK7mDULmWgBNxuutcpiGi0yNqvSR2n84JLj787ifS22gG','img/user/no_photo.jpg','soewandhi2','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'4.02.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22','f0d81e80-64d2-11e6-a037-7351c01af9da','a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('95112161-042e-40b8-92e3-23b4609ef4d3',true,'BAGIAN ORGANISASI','$2a$10$bGuQhiy1Za9jDJeTOqNXKeJzRv7emcY8g/NaVx1HJXHNpCd2CtHR.','img/user/no_photo.jpg','organisasi','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'1.01.03.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('55b6431b-7d9a-4e21-a4e6-9f33efeac154',true,'KECAMATAN WONOCOLO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kec_wonocolo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.14.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('341449f1-6f8e-480b-919a-fad97b5aab75',true,'BADAN KESATUAN BANGSA, POLITIK DAN PERLINDUNGAN MASYARAKAT','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','linmas','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'3.14.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('665709d7-4c42-46da-9e66-767ce8e8402c',true,'KECAMATAN SIMOKERTO','$2a$10$Ix.kTm2su2usRNUci3gMwuwdwkjzxldUQfNneLTEKCWrm2OIlLl9a','img/user/no_photo.jpg','kec_simokerto','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.04.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('e49301e5-c5b1-4ec1-b001-b4bf41d08c32',true,'Simomulyo Baru','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_simomulyobaru','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.28.06.00.00','fecd619e-5617-11e7-87ff-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('4a219903-5c20-4b25-a7e6-c4317dcfe52b',true,'KECAMATAN GUNUNG ANYAR','$2a$10$ckWquBGZIpFO1CVoO.0CPeA67n15GTIpne/XPG39.DTxzcImyrnNS','img/user/no_photo.jpg','kec_gununganyar','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.22.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('ef15c64d-1a84-402d-9a8f-a9320c20d5f6',true,'DINAS LINGKUNGAN HIDUP','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','dlh','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'3.10.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('9ab29a49-698d-4218-9b81-2e158e9416dd',true,'KECAMATAN BENOWO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kec_benowo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.18.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('a7ba4a48-b595-4d4d-a3eb-970a1189d3d1',true,'KECAMATAN DUKUH PAKIS','$2a$10$fDOxZkbhqZg1RdRJeBjbAOVooRp.xmEPa7/qVvaJwKxJrb6bL.bvW','img/user/no_photo.jpg','kec_dukuhpakis','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.26.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('655df46c-cd33-4876-af60-2ad62c2b0296',true,'KECAMATAN GAYUNGAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kec_gayungan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.24.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('3475a3bf-faf3-4081-a4d2-370ecea05103',true,'KECAMATAN GENTENG','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kec_genteng','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.01.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('838ee0f5-5165-41bd-8e02-5115541d490c',true,'KECAMATAN GUBENG','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kec_gubeng','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.06.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('95870e27-4ea3-4029-884c-597dbe28f01f',true,'KECAMATAN JAMBANGAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kec_jambangan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.23.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('166941f3-16f2-46a6-87a7-1c01d20d4ecb',true,'KECAMATAN KENJERAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kec_kenjeran','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.17.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('a820b6bc-6736-486f-a00b-c6e7c6407c78',true,'DINAS KEBUDAYAAN DAN PARIWISATA','$2a$10$AYH.7vGsHxrURy8sHc/8vuDN7evzCmuzznhtJTnm5yucqvAhUB1Sq','img/user/no_photo.jpg','disbudpar','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.04.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('71c2f907-9f53-44f3-83da-785e7ac5700d',true,'DINAS KEPEMUDAAN DAN OLAH RAGA','$2a$10$b1rPrFPRSG0UnXwzgrLPYOVdnpxC/a1ZdvMAaL0tXInlqYcgp7Zi6','img/user/no_photo.jpg','dispora','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.17.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('356fb255-dfcf-4362-9a39-0118d1779398',true,'KECAMATAN BULAK','$2a$10$wHwZdA0buFGRZ1Ie3BbD2esXD2J.sKP0VRujEQUFEQ0CaGGxVdmL6','img/user/no_photo.jpg','kec_bulak','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.29.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('3c10d91b-6287-486c-af35-6a0e36f09ba2',true,'KECAMATAN KREMBANGAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kec_krembangan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.07.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('a3d33686-784d-4f4a-b258-42cff65a345f',true,'KELURAHAN DUKUH PAKIS','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_dukuhpakis','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.26.01.00.00','e134c9fe-7e83-11e6-b92c-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('96599b9d-27a1-47c0-bd60-8a68e26f1e3e',true,'KELURAHAN DUKUH KUPANG','$2a$10$SnzQ/UqxJL.ZIkGyYkp7qO3pmA0l8GFboz4E7RsNz3MeXRhdSydWO','img/user/no_photo.jpg','kel_dukukupang','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.26.02.00.00','e1351ed6-7e83-11e6-b988-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('bc9c95ba-3930-4330-a1d1-61b3f75c6a44',true,'KELURAHAN DUPAK','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_dupak','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.07.05.00.00','e1373950-7e83-11e6-bbb4-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('d9d2713d-9db0-42fb-9dae-4a07a3b96e17',true,'KELURAHAN EMBONG KALIASIN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_embongkaliasin','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.01.01.00.00','e1374634-7e83-11e6-bbc3-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('89a95613-1d5a-4f19-a648-dfed2b9d10c1',true,'KELURAHAN GADING','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_gading','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.05.03.00.00','e137c74e-7e83-11e6-bc40-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('4bb7df1c-440f-4e53-aeb7-d2ddbaa5ff08',true,'DINAS PENGELOLAAN BANGUNAN DAN TANAH','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','dpbt','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.18.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('320f091e-ad5b-4f67-8739-b3d5effce097',false,'Kominfo','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kominfo_gs','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.16.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('b15d4413-8540-44a7-8dea-7c1d0fae1c57',true,'KECAMATAN SUKOMANUNGGAL','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kec_sukomanunggal','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.28.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('1fd85a21-b82b-4b62-95f0-bdfd3c8b301a',true,'DINAS KESEHATAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','dinkes','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.06.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('b15b7851-20d9-4a67-93d5-8895fa28898b',true,'KECAMATAN PABEAN CANTIAN','$2a$10$dYGfoEdnZwFMpjPxw784fOwRhksjkyT3n1FfQiPJp2ZAuvrlgJx3i','img/user/no_photo.jpg','kec_pabeancantikan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.09.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('fef48b20-a7c7-457e-b12f-04fd97b26b48',true,'DINAS KOPERASI DAN USAHA MIKRO','$2a$10$qsLa1dc/GhGHfLgFnKP0R./L4hd9LWWzgYy9DkXE.Zq9r5.xnCzD2','img/user/no_photo.jpg','dinkopum','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.07.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('18594e21-3da7-44ce-bd31-b7e6692e50ab',true,'KECAMATAN LAKARSANTRI','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kec_lakarsantri','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.19.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('7432fd5e-3a9b-4276-b980-834edaff9b98',true,'KECAMATAN RUNGKUT','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kec_rungkut','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.15.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('d6c13e2c-22a3-43b8-9386-d064d9eee194',true,'KECAMATAN SAMBIKEREP','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kec_sambikerep','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.31.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('05e39e43-9ad3-4729-bf3b-20004e88541a',true,'KECAMATAN SEMAMPIR','$2a$10$i4NUfMQo8tATGBQS//0O1eyG3vWh3/zMJT9Op5in/v/3b7BW6G3E.','img/user/no_photo.jpg','kec_semampir','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.08.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('58d06e66-b581-4ce0-9289-d86126ec0e64',true,'KECAMATAN TAMBAKSARI','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kec_tambaksari','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.05.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('b09780c6-e6f2-4387-8680-49a504c915b1',true,'KECAMATAN TEGALSARI','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kec_tegalsari','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.03.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('4b11f459-3ebe-45d5-b6c2-1ce960ba5de7',true,'DINAS TENAGA KERJA','$2a$10$uEBlkGM8Z.FaJPBYbop6AeGl5c5suG/9L7I53qVFTFaFgn2XCRZPS','img/user/no_photo.jpg','disnaker','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.15.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('8e3154da-9503-44fd-a3ba-d9240e9a09b3',true,'KECAMATAN WIYUNG','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kec_wiyung','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.25.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('2c8f52be-e5f6-48ab-956e-2f70c45c3134',true,'KECAMATAN WONOKROMO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kec_wonokromo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.10.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('204d93ee-a346-4856-becf-418ef11b567f',true,'KELURAHAN KUPANG KRAJAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_kupangkrajan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.11.05.00.00','e136597c-7e83-11e6-bad3-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('2c4db4c6-2339-4fcf-aebf-b62a13a581ca',true,'DINAS PEMADAM  KEBAKARAN','$2a$10$UhUobRRVIglCy9Mn9jg1dODfG4NV.pkyUzeZC1Aut.e/78msFQxNa','img/user/no_photo.jpg','damkar','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.02.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('0b37fafa-c8b2-49c3-8c0d-dd326f27aef7',true,'KELURAHAN KARANG PILANG','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_karangpilang','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.13.01.00.00','e1369644-7e83-11e6-bb0f-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('0c5ec378-44cf-4174-bfd9-f759dc603800',true,'KECAMATAN SAWAHAN','$2a$10$X0.iKKQw81vlg5/Hw/R13ecbLGVLGFs3PXKQIyYjUaFQCLPqghQ4C','img/user/no_photo.jpg','kec_sawahan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.11.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('071c1127-4f3e-426c-9a54-49c50f763648',true,'KECAMATAN PAKAL','$2a$10$BbTMxpdKT4FL4I8Ao0pYyOU48JD92PhR9QGKIKfRygQ66pM73eyAm','img/user/no_photo.jpg','kec_pakal','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.30.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('c6f4976a-7f6a-4fda-8b7c-8ec18530fd8f',true,'RSUD BDH 2','$2a$10$m3SsV11iZulBq0lUxdnhX.UVGdIQ5/VHo3.YiqQ6.UcncOS3kPvEy','img/user/no_photo.jpg','rsudbdh','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'4.03.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('f5d91fc9-d993-4aae-8709-0da087680fd3',true,'KECAMATAN TANDES','$2a$10$AczK6A2a4KORQs.nZ5p/qO3n7chyIH16qizmGYK/O5Gfccxi8qQlu','img/user/no_photo.jpg','kec_tandes','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.12.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('510c1df1-fb42-4e24-bdac-759940848643',true,'COMMAND CENTER ROOM','$2a$10$Cga2mYlt9zvg9IQwSmzt7OVKd7rvpJOLeOUy6OeDthz6/cz62NKB2','img/user/no_photo.jpg','ccroom','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'8.01.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('ed823809-0f2b-4602-8e07-deb70752daba',true,'KELURAHAN KEPUTRAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_keputran','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.03.03.00.00','e1381bb8-7e83-11e6-bc98-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('ade2ab67-7855-4e5c-9c54-ed7a10891fd6',true,'KELURAHAN PETEMON','$2a$10$VK9Qgi399W5aReL.UbUmhOv.evk31OZK7NiQd1rMXr8dslNRVhilG','img/user/no_photo.jpg','kel_petemon','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.11.01.00.00','fecd6540-5617-11e7-8801-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('43c05295-a5d0-4d73-8b09-3d78c1e794d2',true,'DINAS PERDAGANGAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','disdag','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.10.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('c5761633-ed42-4811-95c3-b588c2a0ea01',true,'DINAS PERHUBUNGAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','dishub','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.11.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('7eaac945-5f03-479b-91d5-e4195e0290db',true,'DINAS PENDIDIKAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','dispendik','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.09.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('c6298aa6-8c67-4312-905e-d8ed5d06d277',true,'DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','dispendukcapil','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.05.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('e5f60ab5-f09c-463a-b1d7-b45039a3e393',true,'DINAS PEKERJAAN UMUM BINA MARGA DAN PEMATUSAN (UPTD, RAYON DAN RUMAH POMPA)','$2a$10$MnnX0kWZ9iIdOp1A3PGnMO.NdZmgZLy/4.T7Pbhfx6j0aWG0J4L4y','img/user/no_photo.jpg','pubinamarga2','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.01.11.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22','f1ae5a5e-64d2-11e6-811d-ef407e85ae09','a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('75547c29-606f-4b63-84bb-cfd81bde76c1',true,'KELURAHAN KLAMPIS NGASEM','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_klampisngasem','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.16.03.00.00','e135eb22-7e83-11e6-ba60-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('2f1248cf-bcfd-4512-a090-03f7ab8695b3',true,'KELURAHAN PENJARINGANSARI','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_penjaringansari','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.15.04.00.00','e4d697e2-54c3-11e7-9326-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('f77fdd27-cc3c-4a5c-acce-81ab66f3f084',true,'KELURAHAN PERAKBARAT','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_perakbarat','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.07.03.00.00','e135f270-7e83-11e6-ba68-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('42acb8d6-c849-40b5-ad00-f76938834fda',true,'KELURAHAN PERAK TIMUR','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_peraktimur','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.09.05.00.00','e1376ab0-7e83-11e6-bbe6-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('ed6a211f-41d9-41b3-80a5-647454d9463a',true,'KELURAHAN PERAK UTARA','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_perakutara','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.09.04.00.00','e1355374-7e83-11e6-b9c4-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('8fb17cc4-8673-4a1f-ae80-e6424b171ef2',true,'KELURAHAN PUCANG SEWU','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_pucangsewu','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.06.06.00.00','e13720c8-7e83-11e6-bb98-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('bfd3c4ea-7cce-4847-af52-350e53a6de71',true,'KELURAHAN PUTAT GEDE','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_putatgede','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.28.04.00.00','e1374e86-7e83-11e6-bbcc-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('b3c12ab2-1185-41a9-8ffb-9f1e9ea804a0',true,'KELURAHAN RUNGKUT MENANGGAL','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_rungkutmenanggal','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.22.03.00.00','e13802ae-7e83-11e6-bc80-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('1dca9b35-9ccd-44d5-b103-43b5338784c5',true,'KELURAHAN SAMBIKEREP','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_sambikerep','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.31.01.00.00','e135d8c6-7e83-11e6-ba4b-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('5a0d3dce-4c51-40c3-aef0-e30d784a4009',true,'KELURAHAN TAMBAK REJO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_tambakrejo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.04.05.00.00','e138ba0a-7e83-11e6-bd34-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('939af42b-9e13-4bca-9156-4826ff245f39',true,'perekonomian','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','perekonomian','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'1.04.02.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('71f44081-bd5a-4e5c-8de2-e4e00b898f52',true,'DINAS SOSIAL','$2a$10$itrrkZ7HcAagzxvVywBlf.s.QD9Z9MAoJKRm6DncPNp5xHJ.pIoN.','img/user/no_photo.jpg','dinsos','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.13.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('987eb48d-a452-42b4-8733-eab345496199',true,'KELURAHAN BABAT JERAWAT','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_babatjerawat','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.30.01.00.00','e1345d34-7e83-11e6-b8b2-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('f3543056-2d9e-4ad2-a432-ace2bd79f4d2',true,'KELURAHAN BALONGSARI','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_balongsari','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.12.07.00.00','e13814b0-7e83-11e6-bc92-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('72cfcad6-b5d0-4497-8fe2-f6fbeaea6644',true,'KELURAHAN BANGKINGAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_bangkingan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.19.01.00.00','fecd6694-5617-11e7-8803-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('998e3da3-2b60-4cd7-95e5-cc73f91e0e73',true,'KELURAHAN KARANGPOH','$2a$10$U2vgnuw41FXLPTJ6XhAqleYG0KMa79l4.a34WPUV0ltxRgsxCsfrC','img/user/no_photo.jpg','kel_karangpoh','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.12.06.00.00','e137ece2-7e83-11e6-bc68-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('aea95a56-097e-4998-9a67-699111b40d21',true,'KELURAHAN TENGGILIS MEJOYO','$2a$10$77elHQd4POjdAhLEvlQ.GuYdJBPsREEtVzbs0tUmqZUCMzc5x70lC','img/user/no_photo.jpg','kel_tenggilismejoyo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.21.01.00.00','e137b6e6-7e83-11e6-bc30-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('820caef2-e538-4e3c-9a79-a27b209c655c',true,'DINAS PEKERJAAN UMUM BINA MARGA DAN PEMATUSAN','$2a$10$n25hvmA.xgVScVQRKD4z3u4A2SnDB0201dkU09nKvQDo0JuSIrUhu','img/user/no_photo.jpg','pubinamarga','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.01.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('369c0e6f-95f9-40f9-8a74-92df7e03a05f',true,'KELURAHAN BENOWO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_benowo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.30.03.00.00','fecd6018-5617-11e7-87fd-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('49cc619e-ef9a-4410-b728-4bddcbc0434c',true,'SEKRETARIAT DPRD','$2a$10$UQLbzsnem.AP65umrz99DucPPPBSFWBn0avm7ZLrZN.Q/.uF4lxdq','img/user/no_photo.jpg','sekwan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'2.00.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('5079ff57-49e0-460a-86d0-2cdc9661f25a',true,'BAGIAN HUBUNGAN MASYARAKAT','$2a$10$ys3t3DVANts3Nc0RaXnOSOHvLnvUQXG5bGRh8fn0xVM7Ebgkc0kXW','img/user/no_photo.jpg','humas','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'1.04.03.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('4709bb94-d2cf-4768-ba09-f13be45db934',true,'ASISTEN PEMERINTAHAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','asisten1','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'1.00.01.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('fab0823e-dbcd-4d7c-83a1-561bdddcf930',true,'BADAN PENANGGULANGAN BENCANA DAN PERLINDUNGAN MASYARAKAT','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','bpblinmas','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'3.03.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('22589ba7-7405-4ed6-a27e-c48d9352a5ed',true,'KELURAHAN BERINGIN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_beringin','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.31.03.00.00','e1368474-7e83-11e6-bb00-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('e64bd94a-fbfb-4e27-83ea-c26521377858',true,'KELURAHAN DR. SOETOMO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_drsoetomo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.03.01.00.00','e136df50-7e83-11e6-bb5c-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('b8222321-694a-4c13-817b-6994df3cfe94',true,'KELURAHAN KEDUNGDORO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_kedungdoro','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.03.02.00.00','e136777c-7e83-11e6-baf2-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('b4a9087e-0f31-4907-addb-77d059bccc57',true,'KELURAHAN LONTAR','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_lontar','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.31.04.00.00','e1379fc6-7e83-11e6-bc1a-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('0530b2d4-ca66-477a-8dfb-972ff1b0691b',true,'KELURAHAN MADE','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_made','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.31.02.00.00','e1349ab0-7e83-11e6-b8f3-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('729b2af6-d73e-4ea7-84c7-42bde4cc526c',true,'KELURAHAN SIMOKERTO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_simokerto','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.04.01.00.00','2bf8c188-54b8-11e7-b1df-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('d1ea5e7d-f2f8-4dd0-840f-978a1020eaf7',true,'KELURAHAN SUKOLILO BARU','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_sukolilobaru','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.29.06.00.00','e138ac0e-7e83-11e6-bd2a-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('ae53ebb1-9aaa-416d-9f12-161ae1983773',true,'KELURAHAN SUMBEREJO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_sumberejo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.30.04.00.00','e137ca96-7e83-11e6-bc44-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('7839404c-6434-484c-93ff-ac0239c03b47',true,'KELURAHAN WARU GUNUNG','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_warugunung','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.13.04.00.00','e137512e-7e83-11e6-bbcf-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('c5dda57d-4f20-4e1f-af38-c3a0a9d90262',true,'KELURAHAN WONOREJO TEGALSARI','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_wonorejotegalsari','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.03.05.00.00','e134993e-7e83-11e6-b8f1-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('5ef636bb-f42d-4353-b03b-d4d79720d46c',true,'DINAS KETAHANAN PANGAN DAN PERTANIAN','$2a$10$2Cp5cts18u/iQRg2ik1F1OGdekmbdCXq3H04WNapJQbLpllYmmVdi','img/user/no_photo.jpg','dkpp','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.12.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('3f571e60-f97d-4a28-80d9-91b1c6f95dd0',true,'KELURAHAN BUNTARAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_buntaran','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('e9dfca9c-84a7-4c7a-9d94-eb3714d17086',true,'KELURAHAN GADEL','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_gadel','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('19f6ecf8-4cc7-40db-bbb1-2907403b91c8',true,'KELURAHAN GEDANGASIN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_gedangasin','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('846bfa58-c46b-4f84-92f4-f12c91bdc56f',true,'KELURAHAN GREGES','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_greges','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('d7831933-ad87-48eb-814d-15013bd33d09',true,'KELURAHAN KLAKAHREJO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_klakahrejo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('b5880687-2671-46b9-ab6e-9e15922a8260',true,'DINAS KEBERSIHAN DAN RUANG TERBUKA HIJAU','$2a$10$m.drMfxUbFT/HCsJPbMecO1txzskU0DGBfy0E7PkAlkckmWs4VV66','img/user/no_photo.jpg','dkrth','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.03.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('b6618362-9277-4010-ab94-fd46294606b9',true,'BAGIAN HUKUM','$2a$10$2DmpY1LiQDhH/nZO1xAIleM1iAOghAI2QY.fVtxLjI2EJi3XjdCU.','img/user/no_photo.jpg','hukum','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'1.01.02.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('8bc1bc19-1e0a-4e6f-8015-87f4ac88c938',true,'DINAS PENGENDALIAN PENDUDUK, PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK','$2a$10$fB4/fFIuScUhm802RJ7aa.18R/6sjKyEBMYt3raYjSTy22nmMYjva','img/user/no_photo.jpg','dp5a','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'3.04.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('1ef60d98-b34a-482d-a868-c798fb6d2eb4',true,'BADAN KEPEGAWAIAN DAN DIKLAT','$2a$10$cASsP6eifmwWcI5Ap/SYrO82LUkRyHFhDKb.bBCspS8LRuCl6Nnbu','img/user/no_photo.jpg','bkd','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'3.02.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('f846e19b-9e70-41f1-b40b-3664eca3a261',true,'DINAS  PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU','$2a$10$2WSFH58QeEEpE7WUSnOfIuLG.ymmrwn.qUwbChn1w7ZAncuhFimgm','img/user/no_photo.jpg','dpmpm','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'3.13.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('eeb739dd-72c5-4c8d-b5a3-82775ddf336d',true,'KELURAHAN KOMPLEK KENJERAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_komplekkenjeran','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('47e32ad2-8c08-411b-90a3-6b14a7430da5',true,'KELURAHAN BANJAR SUGIHAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_banjarsugihan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.12.12.00.00','e138165e-7e83-11e6-bc94-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('1d852c67-dfe7-40e2-9337-cafdf362710e',true,'KELURAHAN BANYU URIP','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_banyuurip','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.11.03.00.00','e13801fa-7e83-11e6-bc7f-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('f4aad36f-6557-4295-b9e8-83dfbb33c389',true,'KELURAHAN GEBANG PUTIH','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_gebangputih','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.16.02.00.00','e1376e8e-7e83-11e6-bbea-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('2a01e7e6-07f0-49d9-a56f-7427d46a4e09',true,'KELURAHAN JEMUR WONOSARI','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_jemurwonosari','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.14.04.00.00','e1369fc2-7e83-11e6-bb19-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('09bab9c5-b489-4a19-8563-a7efac2301b5',true,'KELURAHAN BARATAJAYA','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_baratajaya','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.06.05.00.00','e135efbe-7e83-11e6-ba65-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('7672f852-53b5-4c16-8f29-d6d3cfb59ce3',true,'KELURAHAN BONGKARAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_bongkaran','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.09.01.00.00','e1346892-7e83-11e6-b8be-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('3db91f2f-6bfe-468c-9a8e-04867a42f00b',true,'KELURAHAN BUBUTAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_bubutan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.02.02.00.00','e1383c6a-7e83-11e6-bcb5-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('8b9e0e62-ce37-415e-8943-99bf21bbfb55',true,'KELURAHAN BULAK','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_bulak','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.29.01.00.00','e134eec0-7e83-11e6-b954-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('b94ab293-fd72-4616-9e39-48bacc755bd4',true,'KELURAHAN DARMO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_darmo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.10.05.00.00','e138c090-7e83-11e6-bd3b-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('8704346c-fe21-411f-8b97-473219c30b74',true,'KELURAHAN GENTENG','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_genteng','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.01.02.00.00','e1364f68-7e83-11e6-bac8-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('24ea0c93-e896-4a57-bdcb-7dcbeee8e733',true,'KELURAHAN GUBENG','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_gubeng','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.06.04.00.00','e1388ea4-7e83-11e6-bd0c-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('9662c73a-9a99-4cc6-a84e-273dd8e253ec',true,'KELURAHAN GUNDIH','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_gundih','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.02.03.00.00','e135ed3e-7e83-11e6-ba62-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('32b7c02c-8569-42db-998d-000c1877d401',true,'KELURAHAN GUNUNGANYAR','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_gununganyar','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.22.01.00.00','e137d694-7e83-11e6-bc50-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('8c3312f2-33d8-4b1e-90e3-ea16523021c2',true,'KELURAHAN JAJAR TUNGGAL','$2a$10$2dobVH5gbjzFIdRElsoA4OtSVJXTZYv0nXwQbLDP4B83puBzEkbrm','img/user/no_photo.jpg','kel_jajartunggal','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.25.02.00.00','e13844d0-7e83-11e6-bcbe-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('777e306a-abde-4bb7-890a-42500624b6b7',true,'KELURAHAN JEPARA','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_jepara','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.02.04.00.00','e137c3ca-7e83-11e6-bc3c-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('ade02e6b-6733-4959-85be-7b3278e94ccf',true,'KELURAHAN JERUK','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_jeruk','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.19.02.00.00','e13664bc-7e83-11e6-bae0-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('65a13141-0b8a-42dc-907d-b14b024cc851',true,'KELURAHAN KALIANAK','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_kalianak','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.27.07.00.00','e135cfde-7e83-11e6-ba43-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('e745554a-dcad-4176-a546-b8faaa745043',true,'KELURAHAN KALIJUDAN','$2a$10$4GcxMe.Vs9B2dzLN5yBa2eyKJD5q8r0Y1VMbY/S40cNFMwIEm1qP6','img/user/no_photo.jpg','kel_kalijudan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.20.06.00.00','e1377e38-7e83-11e6-bbf9-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('d7f0593d-ea29-49e9-94e6-8e4e49c0fc35',true,'KELURAHAN KALI RUNGKUT','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_kalirungkut','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.15.01.00.00','e134cab2-7e83-11e6-b92d-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('06ffd245-7b98-4a64-be1a-fd10dd1363e4',true,'KELURAHAN PAKAL','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_pakal','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.30.02.00.00','e1382b58-7e83-11e6-bca6-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('8b14db91-e8f9-44d7-9947-1f8e8d4ebb7c',true,'KELURAHAN BULAK BANTENG','$2a$10$Wp4ShD2yQJqKpXhWU6SgduubFWXqDAsC4hLeTbXJfIo3cm.lyKekO','img/user/no_photo.jpg','kel_bulakbanteng','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.17.03.00.00','e137e88c-7e83-11e6-bc63-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('4e349e2c-b428-4735-a6c3-e9d894909323',true,'KELURAHAN PRAPEN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_prapen','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('198822e8-c30a-423b-9318-a07e598e39a8',true,'KELURAHAN JAGIR','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_jagir','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.10.02.00.00','e1360670-7e83-11e6-ba78-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('7fc08368-f674-4855-b846-fb8192317834',true,'KELURAHAN KAPASAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_kapasan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.04.02.00.00','e136cde4-7e83-11e6-bb4a-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('dfd3d01f-ad58-4836-a30a-88fc9e08f195',true,'KELURAHAN KAPASARI','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_kapasari','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.01.03.00.00','e1379bf2-7e83-11e6-bc16-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('8826ee57-fd3b-49dd-aba5-53966f431389',true,'KELURAHAN KARAH','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_karah','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.23.02.00.00','e1352714-7e83-11e6-b992-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('3153c3c1-24e9-4b6d-bc2c-cd0e98c267f1',true,'KELURAHAN KEBONSARI','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_kebonsari','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.23.03.00.00','e1350c5c-7e83-11e6-b971-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('7c769944-2a3b-4788-9ba4-0c9494a5a9c5',true,'KELURAHAN KEBRAON','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_kebraon','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.13.02.00.00','e1386320-7e83-11e6-bcde-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('999021d3-577e-4195-bff7-6058a4401a23',true,'KELURAHAN KEDUNG COWEK','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_kedungcowek','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.29.02.00.00','e137e062-7e83-11e6-bc5b-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('8f753b3d-864f-4040-a62b-ac438eafe239',true,'KELURAHAN KEDURUS','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_kedurus','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.13.03.00.00','e13478be-7e83-11e6-b8d2-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('3961089d-a69d-4499-b6c8-d9c72fecb6bc',true,'KELURAHAN KEMAYORAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_kemayoran','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.07.02.00.00','e1343eda-7e83-11e6-b889-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('327fb150-29d7-4f75-8574-ed88f6ddba50',true,'KELURAHAN KENDANGSARI','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_kendangsari','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.21.04.00.00','e1363f46-7e83-11e6-bab6-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('e7bfddd6-2acf-4b20-834f-859dfc01f315',true,'KELURAHAN KEPUTIH','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_keputih','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.16.01.00.00','e1368bea-7e83-11e6-bb04-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('638e0a24-a62e-4129-8ffb-8a4f795f562f',true,'KELURAHAN KERTAJAYA','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_kertajaya','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.06.03.00.00','e1389908-7e83-11e6-bd17-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('1c2663d6-b02c-49b0-8217-029f59267ba8',true,'KELURAHAN KREMBANGAN SELATAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_krembanganselatan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.07.01.00.00','e136b1ce-7e83-11e6-bb2e-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('a3cd75c2-6b2d-4bde-9e84-f329940f3e8f',true,'KELURAHAN KREMBANGAN UTARA','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_krembanganutara','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.09.03.00.00','fecd5ca8-5617-11e7-87fb-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('ab607a93-8df0-4757-a782-7c230a1dc66a',true,'KELURAHAN KUTISARI','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_kutisari','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.21.05.00.00','e1370386-7e83-11e6-bb83-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('803e9df8-a582-415f-82a7-016bf020b797',true,'KELURAHAN LIDAH KULON','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_lidahkulon','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.19.04.00.00','e1369284-7e83-11e6-bb0b-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('9ba7cc1a-dd39-4a74-91f4-7015d15e0994',true,'KELURAHAN MANUKAN WETAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_manukanwetan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.12.11.00.00','e1354c26-7e83-11e6-b9bb-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('05b91705-73b2-41f1-b412-533f35915808',true,'KELURAHAN MEDOKAN AYU','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_medokanayu','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.15.06.00.00','e13876e4-7e83-11e6-bcf3-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('6785b685-436f-4ca2-ada0-e44219203c2d',true,'KELURAHAN MEDOKAN SEMAMPIR','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_medokansemampir','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.16.07.00.00','e1351b02-7e83-11e6-b983-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('ef2535b8-835b-467c-a8d6-fce099e385ab',true,'KELURAHAN MOJO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_mojo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.06.01.00.00','e13495e2-7e83-11e6-b8ed-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('f77c9411-c3b5-4634-bdcb-f066fd30eb08',true,'KELURAHAN MOROKREMBANGAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_morokrembangan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.07.04.00.00','e1383f08-7e83-11e6-bcb8-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('682c73be-4c7e-44c3-a0fc-1a673b73b2de',true,'KELURAHAN MULYOREJO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_mulyorejo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.20.01.00.00','e1377abe-7e83-11e6-bbf5-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('54d426ef-e773-49ed-a24a-33ceaf1ad4c0',true,'KELURAHAN NGAGEL','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_ngagel','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.10.03.00.00','e1353272-7e83-11e6-b99f-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('48a5f431-56f5-45bf-8eef-dade51c94eba',true,'KELURAHAN NGINDEN JANGKUNGAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_ngindenjangkungan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.16.05.00.00','e1360d96-7e83-11e6-ba80-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('a4965031-f2b9-4caf-8fa9-124c4c75313d',true,'KELURAHAN NYAMPLUNGAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_nyamplungan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.09.02.00.00','e136196c-7e83-11e6-ba8e-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('d7052a9e-e3d5-471f-8f10-cadfe789d67e',true,'KELURAHAN TAMBAKDONO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_tambakdono','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('3b94388d-21a8-4bef-a0bc-072c90684908',true,'KELURAHAN TAMBAK LANGON','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_tambaklangon','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('9f4c4f43-b336-4d60-8118-d87608714fe9',true,'KELURAHAN TANDES KIDUL','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_tandeskidul','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('03e3df2a-2f10-4d93-ba67-d43fbe549907',true,'KELURAHAN TANDES LOR','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_tandeslor','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('8736c516-203f-46be-815a-235836fa6f9a',true,'KELURAHAN TUBANAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_tubanan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,NULL,NULL,NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('5a91195e-0d85-481e-be1b-39b1cc7ee723',true,'KELURAHAN LAKARSANTRI','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_lakarsantri','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.19.03.00.00','e13807fe-7e83-11e6-bc86-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('c5c59449-7f13-41a7-96e5-efda495ad2f3',true,'KELURAHAN MANUKAN KULON','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_manukankulon','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.12.09.00.00','e13742ec-7e83-11e6-bbbf-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('0aa81ee1-3da5-46fa-bf65-a375d4a00423',true,'KELURAHAN PACARKELING','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_pacarkeling','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.05.06.00.00','e13540f0-7e83-11e6-b9ae-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('61667e66-ade3-428f-8a25-f6eb73305f6f',true,'KELURAHAN PACARKEMBANG','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_pacarkembang','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.05.04.00.00','e1345140-7e83-11e6-b8a2-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('6643a0bc-9cf9-47b6-baad-25199568dbc4',true,'KELURAHAN PAGESANGAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_pagesangan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.23.04.00.00','e135b3dc-7e83-11e6-ba27-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('7cb5da8b-7adb-4efb-ab5f-3cd7f9e8e5c3',true,'KELURAHAN PANJANG JIWO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_panjangjiwo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.21.03.00.00','e1359a8c-7e83-11e6-ba0a-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('533f5113-5577-44f3-9de5-742c5dc7d43b',true,'KELURAHAN PENELEH','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_peneleh','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.01.05.00.00','fecd6450-5617-11e7-8800-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('0de661fc-21a8-4178-b9d2-16d127180ab6',true,'KELURAHAN SAWUNGGALING','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_sawunggaling','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.10.06.00.00','e135b328-7e83-11e6-ba26-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('efb099cc-d51e-4f2d-9905-e399e4a0ed00',true,'KELURAHAN SEMEMI','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_sememi','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.18.03.00.00','e137aa02-7e83-11e6-bc21-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('51db789c-163e-4a88-bc55-85dd76204091',true,'KELURAHAN SEMOLOWARU','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_semolowaru','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.16.06.00.00','e137c578-7e83-11e6-bc3e-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('3666afa5-b43e-491e-aac2-20159ea9b8b6',true,'KELURAHAN SIDODADI','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_sidodadi','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.04.03.00.00','e1379954-7e83-11e6-bc13-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('b11e840e-bc6d-4a84-af11-edd0987bc775',true,'KELURAHAN SIDOTOPO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_sidotopo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.08.05.00.00','e1372f5a-7e83-11e6-bba9-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('88e5e298-3284-4d11-b756-8e0e0e6aee60',true,'KELURAHAN SIMOLAWANG','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_simolawang','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.04.04.00.00','e13446aa-7e83-11e6-b894-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('0c235295-c0ac-4724-8a8b-da3e807761ac',true,'KELURAHAN SIWALANKERTO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_siwalankerto','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.14.05.00.00','e1382a90-7e83-11e6-bca5-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('7cceb78d-acfa-4c75-8da3-aeec5e40d490',true,'KELURAHAN SUKOMANUNGGAL','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_sukomanunggal','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.28.01.00.00','e1383760-7e83-11e6-bcaf-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('35dcdd44-4c10-4979-93f3-ea4998ad2f1d',true,'KELURAHAN SUMURWELUT','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_sumurwelut','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.19.06.00.00','e135108a-7e83-11e6-b976-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('826e127d-bd1c-4a1b-a837-fd3fc1d54db1',true,'KELURAHAN TANAH KALI KEDINDING','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_tanahkedinding','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.17.01.00.00','e135a356-7e83-11e6-ba14-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('3d4142c2-d079-4eb5-9134-87a03badad62',true,'KELURAHAN TAMBAKSARI','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_tambaksari','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.05.01.00.00','e1362a74-7e83-11e6-ba9f-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('7343b37e-ff88-409d-aad6-ac57fe88251f',true,'KELURAHAN UJUNG','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_ujung','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.08.04.00.00','e136a936-7e83-11e6-bb24-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('140fe711-ff08-460d-b83d-817908f7e500',true,'KELURAHAN WIYUNG','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_wiyung','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.25.01.00.00','e13493da-7e83-11e6-b8ea-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('76717ec9-12b6-464a-ad88-5a201052512e',true,'KELURAHAN WONOKROMO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_wonokromo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.10.01.00.00','e134e90c-7e83-11e6-b94d-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('dc207b69-d456-421a-af76-5ba96c524b19',true,'KELURAHAN WONOKUSUMO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_wonokusumo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.08.03.00.00','e1372ea6-7e83-11e6-bba8-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('51a35304-ca71-4ad7-978a-3e993b7ecef9',true,'KELURAHAN PEGIRIAN','$2a$10$oCSmpKJ63J3rpYC2X5I8DO3IZ9ylQ8YODz.DV5F.hN1K3nsDZPG3a','img/user/no_photo.jpg','kel_pegirian','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.08.02.00.00','e1343c32-7e83-11e6-b885-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('63d2d560-3bc3-432a-a452-5d46a68ae312',true,'KELURAHAN AMPEL','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_ampel','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.08.01.00.00','e1342724-7e83-11e6-b873-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('c447e15e-74b0-4d1b-9ebf-c83fde282894',true,'KELURAHAN RUNGKUT TENGAH','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_rungkuttengah','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.22.02.00.00','e1383544-7e83-11e6-bcad-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('f8948c02-adaf-4aed-915d-54bb83f11c53',true,'BADAN PERENCANAAN PEMBANGUNAN','$2a$10$/w6TlYceW2iVYi05FEv6duZDa8OYKLiTmR2l8uslBGIyPCx6pmJ1m','img/user/no_photo.jpg','bappeko','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'3.11.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('1d94bc96-327d-41a2-a663-0bb51020b257',true,'DINAS KEBERSIHAN DAN RUANG TERBUKA HIJAU UPTD','$2a$10$jHjiOni6z2VPBVwPYulCpekuQHOCcTOrN8OujioJ6vIn.ylWawhTi','img/user/no_photo.jpg','kebersihan2','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.03.99.00.00','e135f07c-7e83-11e6-ba66-000c29766abb','f140db0a-64d2-11e6-8d40-e7c1839896b5','a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('9ff426d7-3601-41c8-a349-47b0adfa0eea',true,'Cipta Karya 2','$2a$10$zmjAt5mnNq/1LjVCLJPJGuvnftBhgAUqoiajB5NRlRKWis6wZL.zq','img/user/no_photo.jpg','dprcktr2','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.14.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('81b6d0f6-5312-4804-b5dd-812bc764beda',true,'KELURAHAN AIRLANGGA','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_airlangga','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.06.02.00.00','e1348390-7e83-11e6-b8d9-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('67b544fa-d1f0-40b5-bfa7-62538b7cf625',true,'KELURAHAN ALUN-ALUN CONTONG','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_alunaluncontong','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.02.01.00.00','e138619a-7e83-11e6-bcdc-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('721049cb-a887-4b80-89e1-021084ce1900',true,'KELURAHAN ASEMROWO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_asemrowo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.27.01.00.00','e13447fe-7e83-11e6-b896-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('7c583c0e-b339-4414-b3ce-38d59f9fe1ce',true,'KELURAHAN BABATAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_babatan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.25.03.00.00','fecd65ea-5617-11e7-8802-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('ea8812a5-bbda-4c46-8283-67e98e22b434',true,'KAPAS MADYA BARU','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_kmb','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.05.07.00.00','e1358736-7e83-11e6-b9fa-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('91efda3a-018c-4dda-8fea-7006b3bb62fb',true,'KELURAHAN TAMBAK OSOWILANGUN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_osowilangun','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.18.04.00.00','e13609fe-7e83-11e6-ba7c-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('93830ece-a8f0-4280-93dd-82300850f3bd',true,'KELURAHAN PAKIS','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_pakis','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.11.06.00.00','e138a114-7e83-11e6-bd1f-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('6596d2a3-624b-42ec-a208-08c388b64570',true,'KELURAHAN SAWAHAN','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_sawahan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.11.02.00.00','e134c490-7e83-11e6-b925-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('af8b29e2-f3a4-4654-a8ab-a9771eee6f34',true,'KELURAHAN SIDOSERMO','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_sidosermo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.14.01.00.00','fecd4c9a-5617-11e7-87f8-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('a1592c61-a4df-4677-bac9-bb01e56d3b43',true,'KELURAHAN TANJUNGSARI','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_tanjungsari','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.28.02.00.00','e1388184-7e83-11e6-bcfe-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('4b434ab7-3c43-4c86-b170-c5f3913cad65',true,'KELURAHAN WONOREJO RUNGKUT','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kel_wonorejorungkut','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.15.05.00.00','e134993e-7e83-11e6-b8f1-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('5148383b-d408-466c-be21-340a442d606f',true,'BAGIAN ADMINISTRASI KERJASAMA','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','kerjasama','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'1.01.04.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('1826fc82-dfed-4f83-bb1b-fa546b2e74e4',true,'BAGIAN UMUM DAN PROTOKOL','$2a$10$hjRzkwnrTFQM0MElQqKXr.c.VYORP.hZ.jadtppO0if7a8DQyDVvy','img/user/no_photo.jpg','umum','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'1.03.01.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('a840da02-084c-4c13-9f45-51d42891e01a',true,'KELURAHAN TAMBAK SARIOSO','$2a$10$G2ifu0aoyEgbMx8lZNatvO4FqeXxWRf4L1N9LONt0gRansyxskrQS','img/user/no_photo.jpg','kel_sarioso','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.27.06.00.00','e13562c4-7e83-11e6-b9d6-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('be10463a-48a9-42f9-b83b-03dc2ba18f84',true,'INSPEKTORAT','$2a$10$b8wvV5tIDUh.2plHP/gJqO/M3Mwz1gVvmMD.HskWaE2uA8WCEDOiG','img/user/no_photo.jpg','inspektorat','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'3.06.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('b242c19a-d94e-4e1d-9126-c144a11983cd',true,'KECAMATAN ASEMROWO','$2a$10$AjpZJwQbdSFv.D4Qf1N9VOiSffVa8D7UqWYi0e..wDOdPfytZhV4u','img/user/no_photo.jpg','kec_asemrowo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.27.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('4e4159a7-53fb-4262-a3df-f1e51a0dd027',true,'UPTD PKB WIYUNG','$2a$10$He9mN6LyI84rkeEF8DBbY.uPW4kN8C1xFFY2x/3ynJxE70UVf7Pdm','img/user/no_photo.jpg','pkb_wiyung','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.11.00.00.00','e137d932-7e83-11e6-bc53-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('92ce39c9-37ad-4e97-8a9c-f96673ad0123',true,'BAGIAN ADMINISTRASI PEMERINTAHAN DAN OTONOMI DAERAH','$2a$10$8parFRZgymvBJEerpf9nO.bzo3yJwGauaRKi.nyuLvQARpQchIHde','img/user/no_photo.jpg','pemerintahan','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'1.01.01.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('e409767d-d8af-4dee-bae5-87c8975d33b9',true,'DINAS KOMUNIKASI DAN INFORMATIKA','$2a$10$qdEqy29Gew8LAEoHS15YD.btcafxvEsoGB6tm3C3ShTtW/hsw3C.y','img/user/no_photo.jpg','diskominfo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.16.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('4271991e-b14e-46e7-b75b-64774153d3c5',true,'Cipta Karya 3','$2a$10$VmJOrV2h/3zIKWb8R//RcuGjVhl7JW019qKgmv.v0bjurJqdAp4BK','img/user/no_photo.jpg','dprcktr3','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.14.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('a88d53ab-e011-4713-a0bd-de487e6e3289',true,'BAGIAN LAYANAN PENGADAAN DAN PENGELOLAAN ASET','$2a$10$LxW/pLsyQZ8F41OQmYjtDuBqUn5V9OJkQXFSs/3/c43kqI40KTmaS','img/user/no_photo.jpg','blppa','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'1.03.02.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('8d8d64a8-c4b8-4f75-8d14-b5a989fc4bd4',true,'Cipta Karya 4','$2a$10$l9.TH6xpxMuWSyFlqyI3J.NfhFHRfokZUsKo4YAQgSqGr24GPumYi','img/user/no_photo.jpg','dprcktr4','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.14.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('1198581a-2038-4b0a-a8b5-a2716db5373f',true,'DINAS PERPUSTAKAAN DAN KEARSIPAN','$2a$10$SBXMDBrm8Hfj3AIGD2xGie60ZLpE.hkjCL1Xlyqvpbdz9wQkEb3mO','img/user/no_photo.jpg','barpus','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'3.01.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('d2c210a4-b9f4-4683-aa30-5bba1b36ca38',true,'Cipta Karya','$2a$10$5C19f6rrOr92vjWSMU0FW.WJFvaRvnuEoLFtYFdBXCPM3pV1OuV.O','img/user/no_photo.jpg','dprcktr','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.14.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('ab46c5c3-9eba-44b8-a5f4-22a5c83396cd',true,'KELURAHAN DUKUH MENANGGAL','$2a$10$KCvGfxjpZQkyFdCWi.7q5.nIfP.qGUsC.yUkClwDbs8UWiIquan8K','img/user/no_photo.jpg','kel_dukuhmenanggal','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.24.04.00.00','fecd5ba4-5617-11e7-87fa-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('d040a25d-7571-4c30-8f2c-06f48724467f',true,'BADAN PENGELOLAAN KEUANGAN DAN PAJAK DAERAH','$2a$10$0WOHUaIn/6KoVgPMu59O0O.xwaDO4rNC9oE68AR6JgWI7YDcebrZa','img/user/no_photo.jpg','bpkpd','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.08.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('f34f45ae-6d21-4fd3-9129-32babde7727a',true,'Dinas Soisal 3','$2a$10$/4/h7wlddAkaYNOb7xNmOOCr2J2GcY3q1cjUZSpKIn/KXQ.7o8R2q','img/user/no_photo.jpg','dinsos3','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.13.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('88b87847-76c1-4f03-a7b4-1b01fe4f896c',true,'Dinas Soisal 2','$2a$10$He9kIWZ85j.6BKuH.eVjuuBWluFUUli9edEGDxVzFzuUq86soDUi6','img/user/no_photo.jpg','dinsos2','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.13.00.00.00','39f35d30-7e68-11e4-947d-4313ae46fa48',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('542bfc6b-7890-40ec-a476-88b8335e5ea7',true,'BAGIAN ADMINISTRASI PEMBANGUNAN','$2a$10$FqjcUoD3IOYmoRaH1AbKee9Wp1LEmHeEcLm.9lfnfeqHLm7/svxTC','img/user/no_photo.jpg','adpemb','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'1.02.01.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('70df81e4-a78e-4d55-a1f9-ff14a1eaeb7d',true,'KELURAHAN RUNGKUT KIDUL','$2a$10$qq6pzel0Gv4EDp7jqF4.wePpU6/aZax6L/kL39kLvWUNAGi84RAOO','img/user/no_photo.jpg','kel_rungkutkidul','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.15.02.00.00','e1387004-7e83-11e6-bceb-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('8bbccf67-68b8-490a-9baa-9d2c676d1c43',true,'BAGIAN ADMINISTRASI KESEJAHTERAAN RAKYAT','$2a$10$qg5SO4DccYnafIv.ttlO9ezsRlvp/lAi/xps620rZqFP/hI6mhHrS','img/user/no_photo.jpg','kesra','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'1.04.01.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('847a35f5-357e-4833-b9c9-cde5176004a0',true,'uptd terminal','$2a$10$EAFxTCaQUmkLcaqtqyUPFe1tY9cF6grxsBTu24u73aoa1Q/dNfSiS','img/user/no_photo.jpg','uptd_terminal','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.11.00.00.00','e38abed8-561f-11e7-8835-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('8f723d51-cdfe-4437-95c8-2046c1b7b331',true,'BPS SURABAYA 2','$2a$10$0ADQ.l1ooWzisgGFn68DaelHhhxaigj6GKVYZmnlhMeWXfg8RF3RW','img/user/no_photo.jpg','bps_2','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.09.00.00.00','e1372cd0-7e83-11e6-bba6-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('911e1a79-9131-4735-9bbf-a442a2d300fa',true,'UPTD PKB TANDES','$2a$10$FOP6OncTd8393TqM/xu6RuNEHPgrLufBzou9/WDR25sf5ji3qDcCe','img/user/no_photo.jpg','pkb_tandes','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.11.00.00.00','e1389278-7e83-11e6-bd10-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('93f9e7ab-9683-4010-8a18-b7727e55f6a4',true,'UPTD TERMINAL 2','$2a$10$YpGUz9rYRmB1UrmU8IXcPe68b4.Z9n76x6pQ2jHK/dFcvZK60Usda','img/user/no_photo.jpg','uptd_terminal2','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'5.11.00.00.00','e38abed8-561f-11e7-8835-000c29766abb',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
,('114978ea-727a-43fb-af97-b2b52dbccf79',true,'KECAMATAN TENGGILIS MEJOYO','$2a$10$ZgjFThIBtsyv7M6Ry5c.dOnXLyTyMS7IhghMaU8Nh4Fz6RogI9LiG','img/user/no_photo.jpg','kec_tenggilismejoyo','948b846c-d24f-4af0-ae33-7358bdad949d',NULL,'6.21.00.00.00','39f30ede-7e68-11e4-b4c2-ef70e56fde22',NULL,'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09',4)
;


//////////////////////  ganti



CREATE TABLE public.m_menu (
	id_menu int4 NOT NULL,
	id_parent int4 NULL,
	nama_menu varchar(100) NULL DEFAULT NULL::character varying,
	judul_menu varchar(250) NULL DEFAULT NULL::character varying,
	link_menu varchar(35) NULL DEFAULT NULL::character varying,
	icon_menu varchar(25) NULL DEFAULT NULL::character varying,
	aktif_menu varchar(1) NULL DEFAULT NULL::character varying,
	tingkat_menu int4 NULL,
	urutan_menu int4 NULL,
	add_button varchar(1) NULL DEFAULT NULL::character varying,
	edit_button varchar(1) NULL DEFAULT NULL::character varying,
	delete_button varchar(1) NULL DEFAULT NULL::character varying
)
WITH (
	OIDS=FALSE
) ;



INSERT INTO public.m_menu (id_menu,id_parent,nama_menu,judul_menu,link_menu,icon_menu,aktif_menu,tingkat_menu,urutan_menu,add_button,edit_button,delete_button) VALUES
(2,1,'Basis Data','Basis Data',NULL,NULL,'Y',2,1,'N','N','N')
,(3,1,'System','System',NULL,NULL,'Y',2,2,'N','N','N')
,(4,3,'Setting User','Setting User','setting_user',NULL,'Y',3,1,'Y','Y','Y')
,(5,3,'Setting Role','Setting Role','setting_role',NULL,'Y',3,2,'Y','Y','Y')
,(7,2,'Backup','Backup','backup',NULL,'Y',3,1,'N','N','N')
,(10,9,'Kepegawaian','Kepegawaian','','','Y',2,1,'N','N','N')
,(12,10,'Master Unit Organisasi Kerja','Master Unit Organisasi Kerja','master_unor_kerja','','Y',3,2,'Y','Y','Y')
,(13,10,'Master Instansi','Master Instansi','master_instansi','','Y',3,3,'Y','Y','Y')
,(14,10,'Master Status Pegawai','Master Status Pegawai','master_status_pegawai','','Y',3,4,'Y','Y','Y')
,(15,10,'Master Golongan','Master Golongan','master_golongan','','Y',3,5,'Y','Y','Y')
,(16,10,'Master Eselon','Master Eselon','master_eselon','','Y',3,6,'Y','Y','Y')
,(17,10,'Master Jenis Kelamin','Master Jenis Kelamin','master_jenis_kelamin','','Y',3,7,'Y','Y','Y')
,(18,10,'Master Rumpun Jabatan','Master Rumpun Jabatan','master_rumpun_jabatan','','Y',3,8,'Y','Y','Y')
,(19,9,'Master Absensi','','','','Y',2,2,'N','N','N')
,(20,19,'Master Agama','Master Agama','master_agama','','Y',3,1,'Y','Y','Y')
,(11,10,'Master Pegawai','Master Pegawai','master_pegawai','','Y',3,1,'Y','Y','Y')
,(21,19,'Master Hari Libur',' Master Hari Libur','master_hari_libur','','Y',3,2,'Y','Y','Y')
,(24,19,'Jenis Roster','Jenis Roster','jenis_roster','','Y',3,5,'Y','Y','Y')
,(25,19,'Role Jam Kerja','Role Jam Kerja','role_jam_kerja','','Y',3,6,'Y','Y','Y')
,(26,19,'Master  Jam Kerja','Master  Jam Kerja','master_jam_kerja','','Y',3,7,'Y','Y','Y')
,(27,19,'Copy Jam Kerja Pegawai',' Copy Jam Kerja Pegawai','copy_jam_kerja','','Y',3,8,'Y','Y','Y')
,(22,19,'Master  Jenis Ijin Cuti','Master  Jenis Ijin Cuti','jenis_ijin_cuti','','Y',3,3,'Y','Y','Y')
,(28,9,'Mesin','','','','Y',2,3,'N','N','N')
,(29,28,'Monitoring Mesin','Monitoring Mesin Fingerprint ','monitoring_mesin','','Y',2,1,'N','N','N')
,(30,28,'Master Area Mesin','Master Area Mesin','master_area_mesin','','Y',3,2,'Y','Y','Y')
,(31,28,'Userinfo Mesin','Daftar User Mesin','userinfo_mesin','','Y',3,3,'N','N','N')
,(32,28,'Master Mesin','Master Mesin','master_mesin','','Y',3,4,'Y','Y','Y')
,(23,19,'Roster Pegawai','Roster Pegawai','roster_pegawai','','Y',3,4,'Y','Y','Y')
,(33,10,'Master Jenis Jabatan','Master Jenis Jabatan','master_jenis_jabatan','','Y',3,9,'Y','Y','Y')
,(55,49,'Lap. Rekap Anggaran','Rekap Pelanggaran Per Unit Kerja','lap_rekap_anggaran','','Y',2,6,'N','N','N')
,(54,49,'Lap. Detail Waktu','Laporan Detail Waktu','lap_detail_waktu','','Y',3,5,'N','N','N')
,(41,34,'Daftar Kendala Teknis dan Penugasan','Daftar Kendala Teknis dan Penugasan','daftar_kendala_teknis','','Y',2,8,'N','Y','N')
,(39,34,'Lembur Pegawai','Lembur Pegawai','lembur_pegawai','','Y',2,5,'N','N','N')
,(35,34,'Ijin / Cuti Pegawai','Ijin / Cuti Pegawai','ijin_cuti_pegawai','','Y',2,1,'N','N','N')
,(6,0,'Dashboard','dashboard','dashboard','icon-home','Y',1,1,'N','N','N')
,(9,0,'Data Master','Data Master','','icon-wrench','Y',1,2,'N','N','N')
,(34,0,'Data Absensi','','','icon-calendar','Y',0,3,'N','N','N')
,(1,0,'Setting','','','icon-settings','Y',1,4,'N','N','N')
,(44,0,'Laporan','','','icon-briefcase','Y',0,5,'N','N','N')
,(45,44,'Per Pegawai','Laporan Absensi Per Pegawai','lap_per_pegawai','','Y',2,1,'N','N','N')
,(53,49,'Lap. Rekap Kehadiran','Rekap Kehadiran/ Unit Kerja','lap_rekap_kehadiran','','Y',3,4,'N','N','N')
,(52,49,'Lap. Absensi Per Pegawai','Laporan Absensi Per Pegawai','lap_absensi_per_pegawai','','Y',3,3,'N','N','N')
,(38,34,'Log Absen per Mesin','Log Absen per Mesin','log_absen_per_mesin','','Y',2,11,'N','N','N')
,(46,44,'Rekap Instansi','Rekap Kehadiran/ Unit Kerja','lap_rekap_instansi','','Y',2,2,'N','N','N')
,(47,44,'SKOR','Skor Kehadiran/ Unit Kerja','lap_skor','','Y',2,3,'N','N','N')
,(49,44,'Lainnya','','','','Y',2,5,'N','N','N')
,(51,49,'Rekap Absensi Lembur','Laporan Rekap Lembur','lap_absensi_lembur','','Y',3,2,'N','N','N')
,(50,49,'Laporan Absensi Makan','Laporan Absensi Uang Makan','lap_absensi_uang_makan','','Y',3,1,'N','N','N')
,(8,0,'Utilitas Menu (Developer)','Utilitas Menu (Developer)','menu','icon-settings','Y',1,99,'Y','Y','Y')
,(36,34,'Daftar Ijin / Cuti Pegawai','Daftar Ijin / Cuti Pegawai','daftar_ijin_cuti_pegawai','','Y',2,2,'N','Y','Y')
,(42,34,'Kendala Teknis dan Penugasan','Kendala Teknis dan Penugasan','kendala_teknis','','Y',2,7,'N','N','N')
,(43,34,'Import Pegawai','Import Pegawai dari File Xls','import_pegawai','','Y',2,13,'N','N','N')
,(37,34,'Log Absen per Unor','Log Absen per Unor','log_absen_per_unor','log_absen_per_unor','Y',2,12,'N','N','N')
,(40,34,'Daftar Lembur Pegawai','Daftar Lembur Pegawai','daftar_lembur_pegawai','','Y',2,6,'N','Y','N')
,(48,44,'Log Kehadiran Pegawai','Ekspor Data Kehadiran','eksport_kehadiran','','Y',2,4,'N','N','N')
;



///////////////////////  ganti


CREATE TABLE public.t_hak_akses (
	id_menu int4 NOT NULL,
	id_kategori_user int4 NOT NULL,
	add_button varchar(1) NULL DEFAULT NULL::character varying,
	edit_button varchar(1) NULL DEFAULT NULL::character varying,
	delete_button varchar(1) NULL DEFAULT NULL::character varying
)
WITH (
	OIDS=FALSE
) ;


INSERT INTO public.t_hak_akses (id_menu,id_kategori_user,add_button,edit_button,delete_button) VALUES
(6,1,'','','')
,(9,1,'','','')
,(10,1,'','','')
,(12,1,'Y','Y','Y')
,(13,1,'Y','Y','Y')
,(14,1,'Y','Y','Y')
,(15,1,'Y','Y','Y')
,(16,1,'Y','Y','Y')
,(17,1,'Y','Y','Y')
,(18,1,'Y','Y','Y')
,(11,1,'Y','Y','Y')
,(33,1,'Y','Y','Y')
,(19,1,'','','')
,(20,1,'Y','Y','Y')
,(21,1,'Y','Y','Y')
,(24,1,'Y','Y','Y')
,(25,1,'Y','Y','Y')
,(26,1,'Y','Y','Y')
,(27,1,'Y','Y','Y')
,(22,1,'Y','Y','Y')
,(23,1,'Y','Y','Y')
,(28,1,'','','')
,(29,1,'','','')
,(30,1,'Y','Y','Y')
,(31,1,'','','')
,(32,1,'Y','Y','Y')
,(34,1,'','','')
,(35,1,'','','')
,(36,1,'','Y','')
,(39,1,'','','')
,(40,1,'','Y','')
,(42,1,'','','')
,(41,1,'','Y','')
,(38,1,'','','')
,(37,1,'','','')
,(43,1,'','','')
,(1,1,'','','')
,(2,1,'','','')
,(7,1,'','','')
,(3,1,'','','')
,(4,1,'Y','Y','Y')
,(5,1,'Y','Y','Y')
,(44,1,'','','')
,(6,4,'','','')
,(9,4,'','','')
,(10,4,'','','')
,(11,4,'','','')
,(19,4,'','','')
,(23,4,'','','')
,(34,4,'','','')
,(35,4,'','','')
,(36,4,'','Y','')
,(37,4,'','','')
,(38,4,'','','')
,(39,4,'','','')
,(40,4,'','','')
,(42,4,'','','')
,(41,4,'','Y','')
,(44,4,'','','')
,(45,4,'','','')
,(46,4,'','','')
,(48,4,'','','')
,(49,4,'','','')
,(55,4,'','','')
,(54,4,'','','')
,(53,4,'','','')
,(52,4,'','','')
,(45,1,'','','')
,(46,1,'','','')
,(47,1,'','','')
,(48,1,'','','')
,(49,1,'','','')
,(55,1,'','','')
,(54,1,'','','')
,(53,1,'','','')
,(52,1,'','','')
,(51,1,'','','')
,(51,4,'','','')
,(50,4,'','','')
,(50,1,'','','')
,(8,1,'Y','Y','Y')
;


//////////// ganti

ALTER TABLE public.t_lembur_pegawai ADD file_lampiran varchar(255) NULL;


ALTER TABLE public.m_skor_lembur ADD menit_mulai integer NULL;
ALTER TABLE public.m_skor_lembur ADD menit_akhir integer NULL;


INSERT INTO public.m_skor_lembur (id,jam_mulai,jam_sampai,keterangan,operasi,skor,timeins,timeupd,userins,userupd,menit_mulai,menit_akhir) VALUES
('d899d7fb-648a-11e8-8a76-14dae95dfc76','30:00:00','','di atas 30 jam ','LEBIH',100,'2018-06-06 11:07:39.963',NULL,'system',NULL,NULL,NULL)
,('d899b0fb-648a-11e8-8a71-14dae95dfc76','03:21:00','08:40:00','3 jam 20 menit s.d 8 jam 40 menit','ANTARA',80,'2018-06-06 11:07:39.963',NULL,'system',NULL,NULL,NULL)
,('d899b0fc-648a-11e8-8a72-14dae95dfc76','08:41:00','14:00:00','8 jam 40 menit s.d 14 jam','ANTARA',84,'2018-06-06 11:07:39.963',NULL,'system',NULL,NULL,NULL)
,('d899b0fd-648a-11e8-8a73-14dae95dfc76','14:01:00','19:20:00','14 jam s.d 19 Jam 20 Menit','ANTARA',88,'2018-06-06 11:07:39.963',NULL,'system',NULL,NULL,NULL)
,('d899b0fe-648a-11e8-8a74-14dae95dfc76','19:21:00','24:40:00','19 Jam 20 Menit s.d 24 jam 40 menit','ANTARA',92,'2018-06-06 11:07:39.963',NULL,'system',NULL,NULL,NULL)
,('d899d7fa-648a-11e8-8a75-14dae95dfc76','24:41:00','30:00:00','24 jam 40 menit s.d 30 jam','ANTARA',96,'2018-06-06 11:07:39.963',NULL,'system',NULL,NULL,NULL)
,('d899b0fa-648a-11e8-8a70-14dae95dfc76','00:00:00','03:20:00','0 s.d 3 jam 20 menit','ANTARA',76,'2018-06-06 11:07:39.963',NULL,'system',NULL,NULL,NULL)
,('d898edb8-648a-11e8-8a69-14dae95dfc76','00:00:00','06:40:00','0 s.d 6 jam 40 menit','ANTARA',76,'2018-06-06 11:07:39.963',NULL,'system',NULL,0,399)
,('d899b0f4-648a-11e8-8a6a-14dae95dfc76','06:41:00','17:20:00','6 jam 40 menit s.d 17 jam 20 menit','ANTARA',80,'2018-06-06 11:07:39.963',NULL,'system',NULL,400,1039)
,('d899b0f5-648a-11e8-8a6b-14dae95dfc76','17:21:00','28:00:00','17 jam 40 menit s.d 28 jam','ANTARA',84,'2018-06-06 11:07:39.963',NULL,'system',NULL,1040,1679)
,('d899b0f6-648a-11e8-8a6c-14dae95dfc76','28:01:00','38:40:00','28 jam s.d 38 Jam 40 Menit','ANTARA',88,'2018-06-06 11:07:39.963',NULL,'system',NULL,1680,2319)
,('d899b0f7-648a-11e8-8a6d-14dae95dfc76','38:41:00','49:20:00','38 Jam 40 Menit s.d 49 jam 20 menit','ANTARA',92,'2018-06-06 11:07:39.963',NULL,'system',NULL,2320,2959)
,('d899b0f8-648a-11e8-8a6e-14dae95dfc76','49:21:00','60:00:00','49 jam 20 menit s.d 60 jam','ANTARA',96,'2018-06-06 11:07:39.963',NULL,'system',NULL,2960,3599)
,('d899b0f9-648a-11e8-8a6f-14dae95dfc76','60:00:00','','di atas 60 jam ','LEBIH',100,'2018-06-06 11:07:39.963',NULL,'system',NULL,3600,10000)
;


///////////////////////////////////////////////////////////////////////

CREATE TABLE public.m_pegawai_jabatan_histori (
	id varchar(36) NOT NULL,
	tgl_mulai date NULL,
	tgl_upd timestamp NULL,
	user_upd varchar(50) NULL,
	id_pegawai varchar(36) NULL,
	kode_jabatan varchar(50) NULL,
	CONSTRAINT m_pegawaijabatan_histori_pkey PRIMARY KEY (id),
	CONSTRAINT m_pegawai_jabatan_histori_jabatan_fkey FOREIGN KEY (kode_jabatan) REFERENCES m_jenis_jabatan(kode),
	CONSTRAINT m_pegawai_jabatan_histori_pegawai_fkey FOREIGN KEY (id_pegawai) REFERENCES m_pegawai(id) ON UPDATE CASCADE ON DELETE CASCADE
)

CREATE TABLE public.m_pegawai_golongan_histori (
	id varchar(36) NOT NULL,
	tgl_mulai date NULL,
	tgl_upd timestamp NULL,
	user_upd varchar(50) NULL,
	id_pegawai varchar(36) NULL,
	kode_golongan varchar(50) NULL,
	CONSTRAINT m_pegawai_golongan_histori_pkey PRIMARY KEY (id),
	CONSTRAINT m_pegawai_histori_golongan_fkey FOREIGN KEY (kode_golongan) REFERENCES m_golongan(kode),
	CONSTRAINT m_pegawai_histori_golongan_pegawai_fkey FOREIGN KEY (id_pegawai) REFERENCES m_pegawai(id) ON UPDATE CASCADE ON DELETE CASCADE
)

CREATE TABLE public.m_pegawai_eselon_histori (
	id varchar(36) NOT NULL,
	tgl_mulai date NULL,
	tgl_upd timestamp NULL,
	user_upd varchar(50) NULL,
	id_pegawai varchar(36) NULL,
	kode_eselon varchar(50) NULL,
	CONSTRAINT m_pegawai_eselon_histori_pkey PRIMARY KEY (id),
	CONSTRAINT m_pegawai_histori_eselon_fkey FOREIGN KEY (kode_eselon) REFERENCES m_eselon(kode),
	CONSTRAINT m_pegawai_histori_eselon_pegawai_fkey FOREIGN KEY (id_pegawai) REFERENCES m_pegawai(id) ON UPDATE CASCADE ON DELETE CASCADE
)

CREATE INDEX m_pegawai_eselon_histori_kode_eselon_idx ON public.m_pegawai_eselon_histori (kode_eselon,id_pegawai,tgl_mulai) ;
