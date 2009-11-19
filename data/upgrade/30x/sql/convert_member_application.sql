ALTER TABLE member_application MODIFY member_id int(11) NOT NULL;
ALTER TABLE member_application ADD public_flag varchar(255) NOT NULL DEFAULT 'public';
ALTER TABLE member_application MODIFY sort_order bigint(20) DEFAULT NULL;
ALTER TABLE member_application DROP is_disp_other;
ALTER TABLE member_application DROP is_disp_home;
ALTER TABLE member_application DROP is_gadget;
