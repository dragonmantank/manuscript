CREATE VIEW [dbo].[new_mimetypes] AS
    SELECT
        DISTINCT fd.mimetype AS 'newMimetype'
    FROM
        files_detail AS fd
    WHERE
        NOT EXISTS (SELECT m.mimetype FROM mimetypes AS m WHERE fd.mimetype = m.mimetype);
