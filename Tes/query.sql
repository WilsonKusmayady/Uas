CREATE TABLE IF NOT EXISTS resi (
    id SERIAL PRIMARY KEY,
    nomor_resi VARCHAR(255) UNIQUE NOT NULL,
    tanggal_resi DATE NOT NULL
);


CREATE TABLE IF NOT EXISTS log_pengiriman (
    id SERIAL PRIMARY KEY,
    id_resi INTEGER REFERENCES resi(id),
    status VARCHAR(255),
    tgl_log TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS user_admin (
    id SERIAL PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
	nama_admin VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    status_aktif BOOLEAN DEFAULT TRUE
);


-- INSERT INTO user_admin (nama, password, nama_admin, status_aktif)
-- VALUES 
-- ('wilson', 'bapak', 'Wilson Kusmayady', TRUE);



DROP TABLE user_admin;
DROP TABLE resi;
DROP TABLE log_pengiriman;
SELECT * FROM user_admin;
SELECT * FROM resi;
SELECT * FROM log_pengiriman;
