-- =====================================================================
-- UPDATE: isi lagu untuk keempat album + kolom durasi
-- Jalankan HANYA jika database realityclub sudah ter-import sebelumnya.
-- phpMyAdmin -> pilih database realityclub -> tab SQL -> paste -> Go
-- (Jika import ulang realityclub_database.sql yang baru, file ini tidak perlu.)
-- =====================================================================
ALTER TABLE tracks ADD COLUMN duration VARCHAR(8) NULL COMMENT 'Durasi lagu (m:dd)' AFTER title;
DELETE FROM tracks;
ALTER TABLE tracks AUTO_INCREMENT = 1;

INSERT INTO tracks (album_id, track_number, title, duration) VALUES
((SELECT id FROM albums WHERE title='Never Get Better'),1,'Elastic Hearts','4:35'),
((SELECT id FROM albums WHERE title='Never Get Better'),2,'Things I Don''t Know','3:21'),
((SELECT id FROM albums WHERE title='Never Get Better'),3,'Shouldn''t End This Way','4:36'),
((SELECT id FROM albums WHERE title='Never Get Better'),4,'Cursive Curses','2:51'),
((SELECT id FROM albums WHERE title='Never Get Better'),5,'Fatal Attraction','3:30'),
((SELECT id FROM albums WHERE title='Never Get Better'),6,'Hesitation','5:12'),
((SELECT id FROM albums WHERE title='Never Get Better'),7,'Okay','5:24'),
((SELECT id FROM albums WHERE title='Never Get Better'),8,'Mentors','4:45'),
((SELECT id FROM albums WHERE title='Never Get Better'),9,'A Graceful Retreat','4:28'),
((SELECT id FROM albums WHERE title='Never Get Better'),10,'For Lack of a Better Word','4:50'),
((SELECT id FROM albums WHERE title='Never Get Better'),11,'Is It The Answer?','3:50'),
((SELECT id FROM albums WHERE title='Never Get Better'),12,'Never Get Better','5:20'),
((SELECT id FROM albums WHERE title='Never Get Better'),13,'Epilogue','3:10'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),1,'Prologue','1:11'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),2,'SSR','4:02'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),3,'All Along All Things Were Wrong','2:07'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),4,'Caught in a Trap','5:48'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),5,'The Rush','3:31'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),6,'Vita o morte','3:53'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),7,'Telenovia','5:21'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),8,'On My Own, Again','3:43'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),9,'Alexandra','3:59'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),10,'A Sorrowful Reunion','4:20'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),11,'2112','5:57'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),1,'I Wish I Was Your Joke','3:38'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),2,'You Let Her Go Again','3:30'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),3,'Dancing In The Breeze Alone','4:27'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),4,'Tell Me I''m Wrong','4:26'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),5,'Desire','4:06'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),6,'Arrowhead Man','3:01'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),7,'Am I Bothering You?','3:45'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),8,'Anything You Want','3:56'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),9,'Four Summers','2:32'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),10,'Love Epiphany','5:39'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),1,'I''ll Do It Myself','3:01'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),2,'Lost Myself in Reveries','2:49'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),3,'Enough for You','3:59'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),4,'Does It Happen?','3:54'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),5,'Shut Up, Behave','3:41'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),6,'Finding a Catholic Man to Love the Love of My Life','3:08'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),7,'Muted Sirens','4:10'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),8,'Close to You / Jauh','3:19'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),9,'Quick! Love!','3:24'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),10,'Now I''m a Diplomat','3:15'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),11,'Thank You for Hijacking My Existential Crisis','3:51'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),12,'You''ll Find Lovers Like You and Me','3:29'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),13,'Mama''s Coming Home','3:53');
