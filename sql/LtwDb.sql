DROP TABLE IF EXISTS client;
DROP TABLE IF EXISTS invoice;
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS city;
DROP TABLE IF EXISTS address;
DROP TABLE IF EXISTS country;
DROP TABLE IF EXISTS line;
DROP TABLE IF EXISTS tax;

CREATE TABLE IF NOT EXISTS client (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tax_id INTEGER UNIQUE,
    company_name CHAR(50) NOT NULL,
    email CHAR(50) NOT NULL,
    adress_id INTEGER REFERENCES address(id)
);

CREATE TABLE IF NOT EXISTS product (
    id INTEGER PRIMARY KEY,             /* id -> product code */
    description CHAR(50) NOT NULL, 
    unit_price INTEGER CHECK (unit_price > 0),
    unit_of_measure CHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS address (
    id INTEGER PRIMARY KEY,
    detail CHAR(50),
    city_id INTEGER REFERENCES city(id),
    postal_code CHAR(50) UNIQUE,
    country_id INTEGER REFERENCES country(id)
);

CREATE TABLE IF NOT EXISTS country (
    id INTEGER PRIMARY KEY, 
    name CHAR(50) NOT NULL, 
    code INTEGER UNIQUE
);

CREATE TABLE IF NOT EXISTS city (
    id INTEGER PRIMARY KEY, 
    name CHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS invoice (
    id INTEGER PRIMARY KEY,
    billing_date DATE NOT NULL,
    client_id INTEGER REFERENCES client(id),
    tax_cost REAL,
    net_total REAL
);

CREATE TABLE IF NOT EXISTS line (
    product_id INTEGER REFERENCES product(id),
    line_number INTEGER,
    invoice_id INTEGER REFERENCES invoice(id),
    quantity INTEGER CHECK (quantity > 0),
    unit_price REAL CHECK (unit_price >= 0),
    tax_id INTEGER REFERENCES tax(id),
    PRIMARY KEY (product_id, line_number, invoice_id)
);

CREATE TABLE IF NOT EXISTS tax (
    id INTEGER PRIMARY KEY,
    type CHAR(50) NOT NULL,
    percentage INTEGER CHECK (percentage > 0)
);

/* QUERIES */

-- lines_per_invoice
SELECT
    product.id AS "Product code",
    line.line_number AS "Line",
    invoice.billing_date AS "Date",
    client.company_name AS "Company"
FROM invoice
    JOIN line ON invoice.id = line.invoice_id
    JOIN client ON client.id = invoice.id
    JOIN product ON line.product_id = product.id
GROUP BY invoice.id
ORDER BY line.line_number ASC;
    