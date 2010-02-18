INSERT INTO tags (name) VALUES ("PHP");
INSERT INTO tags (name) VALUES ("Programming");

INSERT INTO files (originalFilename, directory, reference, mimetype, filesize, dateUploaded, author, title) VALUES
    ('my_file.doc', 'files', 'asdiasd23', 'text/plain', 1234, '2010-02-16 12:14:12', 1, 'My File');

INSERT INTO comments (fileId, version, comment, dateAdded, author) VALUES
    (1, 1, 'This is the comment', '2010-02-16 12:15:12', 1);

INSERT INTO files_tags_xref (fileId, tagId) VALUES
    (1,1);

INSERT INTO users (username, password, name, email, primaryGroup) VALUES
    ('root', 'bef3452591febf751a9333de927f2d9c', 'Root', 'root@domain.com', 1);
