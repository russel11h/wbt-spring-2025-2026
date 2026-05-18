-- Run this only if you already imported the old database before this update.
-- It adds category image support without deleting existing data.

ALTER TABLE categories
ADD COLUMN image_path VARCHAR(255) NULL AFTER category_type;

UPDATE categories
SET image_path = 'asset/medicine-default.png'
WHERE image_path IS NULL OR image_path = '';
