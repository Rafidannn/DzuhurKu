USE dzuhurku;

-- 1. XI RPL 2
INSERT INTO kelas (nama_kelas) VALUES ('XI RPL 2');
SET @id_rpl2 = LAST_INSERT_ID();

INSERT INTO murid (nisn, nama_murid, password, id_kelas) VALUES 
('0081758558', 'Abdul Zaki', '123456', @id_rpl2),
('0085429037', 'Aisya Maulida', '123456', @id_rpl2),
('0084197429', 'Ajeng Chandra Dewi', '123456', @id_rpl2),
('0086097223', 'Aribasa Bintang Juliyanto Marbun Banjarnahor', '123456', @id_rpl2),
('0075845707', 'Arlica Defiona', '123456', @id_rpl2),
('0089036657', 'Aulia Putri Pratiwi', '123456', @id_rpl2),
('0087557328', 'Azriel Akbar Ghifari', '123456', @id_rpl2),
('0088723576', 'Benediktus Samuel Riyanto', '123456', @id_rpl2),
('0085903332', 'Bilqis Sahila', '123456', @id_rpl2),
('0085857286', 'Elvira Amelia Putri', '123456', @id_rpl2),
('0087947941', 'Fayyad Rifqi Pratama', '123456', @id_rpl2),
('0084506189', 'Gigih Erlangga', '123456', @id_rpl2),
('0061817126', 'Jehezkiel Lauw Wijaya', '123456', @id_rpl2),
('0082189341', 'Jonathan Richard Sianipar', '123456', @id_rpl2),
('0095970238', 'Kevin Christian Barus', '123456', @id_rpl2),
('0086540030', 'Kolose Dolok Saribu', '123456', @id_rpl2),
('0088130321', 'Leta Gultom', '123456', @id_rpl2),
('0091057627', 'Martha Judika Sitorus', '123456', @id_rpl2),
('0086228523', 'Marvel Sabatian Katulistyo', '123456', @id_rpl2),
('0086442212', 'Maysha Zahra Nuraini', '123456', @id_rpl2),
('3081660305', 'Mazen Mohammad Jawwad Faris', '123456', @id_rpl2),
('0094579392', 'Melvern Semuel Hamonangan Pardamean Sinaga', '123456', @id_rpl2),
('0098764678', 'Michelle Diesqu', '123456', @id_rpl2),
('0084261928', 'Miftakhul Latifah Sukmaning Widjaya', '123456', @id_rpl2),
('0071599915', 'Muhamad Zain', '123456', @id_rpl2),
('0089037572', 'Muhammad Gyan Farhan', '123456', @id_rpl2),
('0084288362', 'Muhammad Rio Pahlevi', '123456', @id_rpl2),
('0087400848', 'Muhammad Rizki Gunawan', '123456', @id_rpl2),
('0085468985', 'Natasya Eka Silvy', '123456', @id_rpl2),
('0095360466', 'Rizky Maulana', '123456', @id_rpl2),
('0096161801', 'Stevan Christan Barus', '123456', @id_rpl2),
('0092975967', 'Tyas Salsabila', '123456', @id_rpl2),
('0088218713', 'Valentinus Rinjani Prabowo', '123456', @id_rpl2),
('0095856969', 'Waldan Hafidz', '123456', @id_rpl2),
('0084190384', 'Yoga Fitra Yandika', '123456', @id_rpl2),
('0087214080', 'Yoga Gautama', '123456', @id_rpl2);


-- 2. XI TBS 3
INSERT INTO kelas (nama_kelas) VALUES ('XI TBS 3');
SET @id_tbs3 = LAST_INSERT_ID();

INSERT INTO murid (nisn, nama_murid, password, id_kelas) VALUES 
('0085134886', 'Adisti Pratiwi', '123456', @id_tbs3),
('0084975608', 'Almira Wursita Putri', '123456', @id_tbs3),
('0095359989', 'Amelia Ibtisamah Hasna', '123456', @id_tbs3),
('0083853701', 'Astri Nurul Aulia', '123456', @id_tbs3),
('0081174854', 'Aurelie Stevanny Bilbina', '123456', @id_tbs3),
('0072685145', 'Azarine Keira Divi', '123456', @id_tbs3),
('0083278365', 'Chania Talitha Shafiqah', '123456', @id_tbs3),
('0082387885', 'Dhita Nurfiatin Nadiroh', '123456', @id_tbs3),
('0086178252', 'Dzea Aira Mozain', '123456', @id_tbs3),
('0097008615', 'Fidela Rahma Maulida', '123456', @id_tbs3),
('0087058388', 'Indri Oktaviani', '123456', @id_tbs3),
('0083915882', 'Khirani Akmalia', '123456', @id_tbs3),
('0085340135', 'Laila Tunnisa', '123456', @id_tbs3),
('0089241495', 'Lana Althafunnisa', '123456', @id_tbs3),
('0078276454', 'Nabila Desputri Prana', '123456', @id_tbs3),
('0085966098', 'Nabila Yusriyah Ramadhani', '123456', @id_tbs3),
('0086546505', 'Nadin Lailla Fajri', '123456', @id_tbs3),
('0082045468', 'Najwa Dhiaul Aulia', '123456', @id_tbs3),
('0082929144', 'Nayzwa Olivia', '123456', @id_tbs3),
('0086541645', 'Nazhira Nesya Shabilla', '123456', @id_tbs3),
('3069508464', 'Putri Nur''aini Fauziyah', '123456', @id_tbs3),
('0082794689', 'Rahmania Aisyah Putri', '123456', @id_tbs3),
('0089769653', 'Sheila Nurul Cahya Anggitha', '123456', @id_tbs3),
('0089734050', 'Siti Halimah Pertiwi', '123456', @id_tbs3),
('0088626653', 'Siti Nurhusniah', '123456', @id_tbs3),
('0086251801', 'Stevania Tri Ramadhani', '123456', @id_tbs3),
('3081787345', 'Syafira Azzahra', '123456', @id_tbs3),
('0096426956', 'Syifa Ramadhani', '123456', @id_tbs3),
('0091987036', 'Tasya Putri Priyana', '123456', @id_tbs3),
('0082344258', 'Tifany Zahra', '123456', @id_tbs3);


-- 3. XI TBS 2
INSERT INTO kelas (nama_kelas) VALUES ('XI TBS 2');
SET @id_tbs2 = LAST_INSERT_ID();

INSERT INTO murid (nisn, nama_murid, password, id_kelas) VALUES 
('0089309673', 'Adhania', '123456', @id_tbs2),
('3089868679', 'Almira Sakhi Aulia', '123456', @id_tbs2),
('0088212290', 'Anggun Nur Ivana', '123456', @id_tbs2),
('0089160328', 'Anjani Putri Aqilla', '123456', @id_tbs2),
('0082262383', 'Annisa Rahmawati', '123456', @id_tbs2),
('0086326496', 'Azka Aqeyla', '123456', @id_tbs2),
('0083134726', 'Bunga Cintanya Endi', '123456', @id_tbs2),
('0093337272', 'Carissa Putri Salsabila', '123456', @id_tbs2),
('0092743308', 'Chelsea Marito Febriyanti Sihombing', '123456', @id_tbs2),
('0095525893', 'Jessica Berta Lemmuela', '123456', @id_tbs2),
('0082390944', 'Kirana Durotun Nafisah', '123456', @id_tbs2),
('0086200245', 'Launaisa Sinulingga', '123456', @id_tbs2),
('0093484845', 'Luvena Nadia Ristanto', '123456', @id_tbs2),
('3083801268', 'Manika Faidah Hasna Andryani', '123456', @id_tbs2),
('0096650787', 'Marsha Dwi Febriani', '123456', @id_tbs2),
('0089066776', 'Raisya Nurul Fadillah', '123456', @id_tbs2),
('0084247745', 'Raisya Rahma', '123456', @id_tbs2),
('0089782095', 'Raisyah Alifa Zahra', '123456', @id_tbs2),
('0098403061', 'Riyanita Pratiwi', '123456', @id_tbs2),
('3093751009', 'Safa Al Zaena Kinaya', '123456', @id_tbs2),
('0087247899', 'Safira Roberkah', '123456', @id_tbs2),
('0089250661', 'Salma Salzabil', '123456', @id_tbs2),
('0087636080', 'Siti Afira', '123456', @id_tbs2),
('0084299238', 'Sri Mulyati', '123456', @id_tbs2),
('0085760877', 'Suci Dini Utami', '123456', @id_tbs2),
('0081996557', 'Suryani', '123456', @id_tbs2),
('0089016636', 'Vionata Suci Lestari', '123456', @id_tbs2),
('0085397173', 'Zhazqhya Septhiana Ramadhanie', '123456', @id_tbs2);


-- 4. XI TBS 1
INSERT INTO kelas (nama_kelas) VALUES ('XI TBS 1');
SET @id_tbs1 = LAST_INSERT_ID();

INSERT INTO murid (nisn, nama_murid, password, id_kelas) VALUES 
('0086633723', 'Aisyah Luna Bonita Manik', '123456', @id_tbs1),
('0089324298', 'Aliyyah Putri Herdiana', '123456', @id_tbs1),
('0071851166', 'Astry Kemuning Fitriyani', '123456', @id_tbs1),
('0082538786', 'Divfa Risqita Sari', '123456', @id_tbs1),
('0088675713', 'Desvita Haningtyas Putri M', '123456', @id_tbs1),
('0096099614', 'Firli Alyadian', '123456', @id_tbs1),
('0084312397', 'Fitri Handayani', '123456', @id_tbs1),
('3087222027', 'Gievandra Yoga Kirmanedi', '123456', @id_tbs1),
('0084057564', 'Isma Sabil Lutfilah', '123456', @id_tbs1),
('0082901765', 'Jesica Natasya Azahra', '123456', @id_tbs1),
('0083454012', 'Kayla Murdiasari Azzahra', '123456', @id_tbs1),
('0081751196', 'Khumaira Isnaini Rustandi', '123456', @id_tbs1),
('3087544673', 'Malika Kaiza Adiba', '123456', @id_tbs1),
('0082073172', 'Mela Asyhifa Subagya', '123456', @id_tbs1),
('0084983071', 'Muhamad Hafidz Faiz Kemal', '123456', @id_tbs1),
('0085347387', 'Nayla Azizah', '123456', @id_tbs1),
('0085496055', 'Nazwa Bilqis Nabila', '123456', @id_tbs1),
('3081259194', 'Rafa Dwi Julianti', '123456', @id_tbs1),
('0086948162', 'Rifqah Aprilia', '123456', @id_tbs1),
('0088284864', 'Safira Nadia Sakhiy', '123456', @id_tbs1),
('0085672753', 'Sakinah Zahra', '123456', @id_tbs1),
('0091919521', 'Sabina Aulia Jasmine', '123456', @id_tbs1),
('0084343356', 'Saneta Novianta', '123456', @id_tbs1),
('0093123858', 'Sarah Ahmad Saleh Baa''syin', '123456', @id_tbs1),
('0097732441', 'Syaffanisa Almira Tianandra', '123456', @id_tbs1),
('0082721445', 'Syafitri Harahap', '123456', @id_tbs1),
('0073637411', 'Syaima Zulaikha Helmi', '123456', @id_tbs1),
('0083724007', 'Thufailah Shoba Aqilah', '123456', @id_tbs1),
('0081870330', 'Tsalast Ubay Dilla Salman', '123456', @id_tbs1),
('0084050209', 'Widya Dwi Ariyanti', '123456', @id_tbs1),
('0087295036', 'Zalika Noor Raisya', '123456', @id_tbs1),
('0091361581', 'Zalina Sandra Sihotang', '123456', @id_tbs1),
('0082302487', 'Zarkasya Agusti', '123456', @id_tbs1);
