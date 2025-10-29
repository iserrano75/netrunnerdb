-- Migration for User Collection Feature
-- This SQL creates the user_collection table to store which packs each user owns

CREATE TABLE user_collection (
    id INT AUTO_INCREMENT NOT NULL,
    user_id INT NOT NULL,
    pack_id INT NOT NULL,
    date_added DATETIME NOT NULL,
    PRIMARY KEY(id),
    UNIQUE INDEX user_pack_unique (user_id, pack_id),
    INDEX user_pack_index (user_id, pack_id),
    INDEX IDX_user_id (user_id),
    INDEX IDX_pack_id (pack_id),
    CONSTRAINT FK_user_collection_user FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE,
    CONSTRAINT FK_user_collection_pack FOREIGN KEY (pack_id) REFERENCES pack (id) ON DELETE CASCADE
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
