INSERT INTO config (key, value) VALUES ("version", "1");

INSERT INTO user_accounts (username, password, name, email, primaryGroup) VALUES
    ('root', 'a5bbbe41b5f03df38576fd4b884a755e3dac72c9a26657d7cc3c25d6ba035037d91714a5eebbbce12a2c4da9f08ae807', 'Root', 'root@domain.com', 1);

INSERT INTO mimetypes (mimetype, description, editable) VALUES
    ('application/msword', 'Word Document', 0);

INSERT INTO mimetypes (mimetype, description, editable) VALUES
    ('application/excel', 'Excel Spreadsheet', 0);

INSERT INTO mimetypes (mimetype, description, editable) VALUES
    ('text/plain', 'Text Document', 1);

INSERT INTO mimetypes (mimetype, description, editable) VALUES
    ('text/html', 'HTML Document', 1);

INSERT INTO mimetypes (mimetype, description, editable) VALUES
    ('application/octet-stream', 'Binary File', 0);

INSERT INTO mimetypes (mimetype, description, editable) VALUES
    ('application/pdf', 'PDF Document', 0);

INSERT INTO mimetypes (mimetype, description, editable) VALUES
    ('application/rtf', 'Rich Text File', 0);

INSERT INTO mimetypes (mimetype, description, editable) VALUES
    ('application/zip', 'Zip File', 0);

INSERT INTO mimetypes (mimetype, description, editable) VALUES
    ('application/tar', 'Tar File', 0);

INSERT INTO mimetypes (mimetype, description, editable) VALUES
    ('application/x-gzip', 'GZipped File', 0);

INSERT INTO mimetypes (mimetype, description, editable) VALUES
    ('image/gif', 'GIF Image', 0);

INSERT INTO mimetypes (mimetype, description, editable) VALUES
    ('image/jpeg', 'JPEG Image', 0);

INSERT INTO mimetypes (mimetype, description, editable) VALUES
    ('application/vnd-ms-excel', 'Excel Spreadsheet', 0);
