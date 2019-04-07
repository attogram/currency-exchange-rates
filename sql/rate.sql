CREATE TABLE IF NOT EXISTS 'rate' (
    'day' DATETIME NOT NULL,
    'rate' NUMERIC,
    'currency' TEXT NOT NULL,
    'xcurrency' TEXT NOT NULL,
    'source' TEXT NOT NULL,
    'last_updated' DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY ('day', 'source', 'currency', 'xcurrency')
)
