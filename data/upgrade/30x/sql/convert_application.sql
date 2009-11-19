ALTER TABLE application MODIFY height bigint(20) NOT NULL;
ALTER TABLE application MODIFY scrolling tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE application MODIFY singleton tinyint(1) NOT NULL DEFAULT 1;
ALTER TABLE application DROP updated_at;
