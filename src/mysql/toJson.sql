CREATE FUNCTION `toJson`(params VARCHAR(1000)) RETURNS varchar(1000) CHARSET utf8
BEGIN
    DECLARE json VARCHAR(1000);
    DECLARE param_key VARCHAR(255);
    DECLARE param_value VARCHAR(255);
    DECLARE i INT DEFAULT 1;

    SET json = '{';

    WHILE i <= LENGTH(params) - LENGTH(REPLACE(params, '[;]', '')) + 1 DO
        SET param_key = SUBSTRING_INDEX(SUBSTRING_INDEX(params, '[;]', i), '[;]', -1);
        SET param_value = SUBSTRING_INDEX(SUBSTRING_INDEX(params, '[;]', i + 1), '[;]', -1);
        SET json = CONCAT(json, '"', param_key, '": "', param_value, '"');
        SET i = i + 2;
        IF i <= LENGTH(params) - LENGTH(REPLACE(params, '[;]', '')) + 1 THEN
            SET json = CONCAT(json, ', ');
        END IF;
    END WHILE;

    SET json = CONCAT(json, '}');
    RETURN json;
END
