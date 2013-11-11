DROP TABLE IF EXISTS customer;
DROP TABLE IF EXISTS invoice;
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS city;
DROP TABLE IF EXISTS address;
DROP TABLE IF EXISTS country;
DROP TABLE IF EXISTS line;
DROP TABLE IF EXISTS tax;

CREATE TABLE IF NOT EXISTS country (
    code CHAR(2) PRIMARY KEY, 
    name CHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS city (
    id INTEGER PRIMARY KEY, 
    name CHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS address (
    id INTEGER PRIMARY KEY,
    detail CHAR(50),
    city_id INTEGER REFERENCES city(id),
    postal_code CHAR(8) NOT NULL,
    country_code CHAR(2) REFERENCES country(code)
);

CREATE TABLE IF NOT EXISTS customer (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tax_id INTEGER UNIQUE,
    company_name CHAR(100) NOT NULL,
    email CHAR(60) NOT NULL,
    address_id INTEGER REFERENCES address(id)
);

CREATE TABLE IF NOT EXISTS product (
    id INTEGER PRIMARY KEY,             /* id -> product code */
    description CHAR(50) NOT NULL, 
    unit_price INTEGER CHECK (unit_price > 0),
    unit_of_measure CHAR(20) NOT NULL
);

CREATE TABLE IF NOT EXISTS invoice (
    id INTEGER PRIMARY KEY,
    billing_date DATE NOT NULL,
    customer_id INTEGER REFERENCES customer(id),
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

/* INSERTIONS */

INSERT INTO tax (id, type, percentage) VALUES
(1, 'IVA', 23);

INSERT INTO line (product_id, line_number, invoice_id, quantity, unit_price, tax_id) VALUES
(125, 1, 1, 3, 90, 1),
(126, 2, 1, 1, 450, 1);

INSERT INTO invoice (id, billing_date, customer_id, tax_cost, net_total) VALUES
(1, '2013-09-27', 555560, 20, 30);

INSERT INTO address (id, detail, city_id, postal_code, country_code) VALUES
(1, 'qqcoisa', 1, '1234-567', 'PT'); 

INSERT INTO customer (id, tax_id, company_name, email, address_id) VALUES
(555560, 123, 'FEUP', 'feup@feup.com', 1);

INSERT INTO country (code, name) VALUES 
('AF', 'Afghanistan'),
('AX', 'Åland Islands'),
('AL', 'Albania'),
('DZ', 'Algeria'),
('AS', 'American Samoa'),
('AD', 'Andorra'),
('AO', 'Angola'),
('AI', 'Anguilla'),
('AQ', 'Antarctica'),
('AG', 'Antigua And Barbuda'),
('AR', 'Argentina'),
('AM', 'Armenia'),
('AW', 'Aruba'),
('AU', 'Australia'),
('AT', 'Austria'),
('AZ', 'Azerbaijan'),
('BS', 'Bahamas'),
('BH', 'Bahrain'),
('BD', 'Bangladesh'),
('BB', 'Barbados'),
('BY', 'Belarus'),
('BE', 'Belgium'),
('BZ', 'Belize'),
('BJ', 'Benin'),
('BM', 'Bermuda'),
('BT', 'Bhutan'),
('BO', 'Bolivia, Plurinational State Of'),
('BQ', 'Bonaire, Sint Eustatius And Saba'),
('BA', 'Bosnia And Herzegovina'),
('BW', 'Botswana'),
('BV', 'Bouvet Island'),
('BR', 'Brazil'),
('IO', 'British Indian Ocean Territory'),
('BN', 'Brunei Darussalam'),
('BG', 'Bulgaria'),
('BF', 'Burkina Faso'),
('BI', 'Burundi'),
('KH', 'Cambodia'),
('CM', 'Cameroon'),
('CA', 'Canada'),
('CV', 'Cape Verde'),
('KY', 'Cayman Islands'),
('CF', 'Central African Republic'),
('TD', 'Chad'),
('CL', 'Chile'),
('CN', 'China'),
('CX', 'Christmas Island'),
('CC', 'Cocos (Keeling) Islands'),
('CO', 'Colombia'),
('KM', 'Comoros'),
('CG', 'Congo'),
('CD', 'Congo, The Democratic Republic Of The'),
('CK', 'Cook Islands'),
('CR', 'Costa Rica'),
('CI', 'Côte D`Ivoire'),
('HR', 'Croatia'),
('CU', 'Cuba'),
('CW', 'Curaçao'),
('CY', 'Cyprus'),
('CZ', 'Czech Republic'),
('DK', 'Denmark'),
('DJ', 'Djibouti'),
('DM', 'Dominica'),
('DO', 'Dominican Republic'),
('EC', 'Ecuador'),
('EG', 'Egypt'),
('SV', 'El Salvador'),
('GQ', 'Equatorial Guinea'),
('ER', 'Eritrea'),
('EE', 'Estonia'),
('ET', 'Ethiopia'),
('FK', 'Falkland Islands (Malvinas)'),
('FO', 'Faroe Islands'),
('FJ', 'Fiji'),
('FI', 'Finland'),
('FR', 'France'),
('GF', 'French Guiana'),
('PF', 'French Polynesia'),
('TF', 'French Southern Territories'),
('GA', 'Gabon'),
('GM', 'Gambia'),
('GE', 'Georgia'),
('DE', 'Germany'),
('GH', 'Ghana'),
('GI', 'Gibraltar'),
('GR', 'Greece'),
('GL', 'Greenland'),
('GD', 'Grenada'),
('GP', 'Guadeloupe'),
('GU', 'Guam'),
('GT', 'Guatemala'),
('GG', 'Guernsey'),
('GN', 'Guinea'),
('GW', 'Guinea-Bissau'),
('GY', 'Guyana'),
('HT', 'Haiti'),
('HM', 'Heard Island And Mcdonald Islands'),
('VA', 'Holy See (Vatican City State)'),
('HN', 'Honduras'),
('HK', 'Hong Kong'),
('HU', 'Hungary'),
('IS', 'Iceland'),
('IN', 'India'),
('ID', 'Indonesia'),
('IR', 'Iran, Islamic Republic Of'),
('IQ', 'Iraq'),
('IE', 'Ireland'),
('IM', 'Isle Of Man'),
('IL', 'Israel'),
('IT', 'Italy'),
('JM', 'Jamaica'),
('JP', 'Japan'),
('JE', 'Jersey'),
('JO', 'Jordan'),
('KZ', 'Kazakhstan'),
('KE', 'Kenya'),
('KI', 'Kiribati'),
('KP', 'Korea, Democratic People`s Republic Of'),
('KR', 'Korea, Republic Of'),
('KW', 'Kuwait'),
('KG', 'Kyrgyzstan'),
('LA', 'Lao People`S Democratic Republic'),
('LV', 'Latvia'),
('LB', 'Lebanon'),
('LS', 'Lesotho'),
('LR', 'Liberia'),
('LY', 'Libya'),
('LI', 'Liechtenstein'),
('LT', 'Lithuania'),
('LU', 'Luxembourg'),
('MO', 'Macao'),
('MK', 'Macedonia, The Former Yugoslav Republic Of'),
('MG', 'Madagascar'),
('MW', 'Malawi'),
('MY', 'Malaysia'),
('MV', 'Maldives'),
('ML', 'Mali'),
('MT', 'Malta'),
('MH', 'Marshall Islands'),
('MQ', 'Martinique'),
('MR', 'Mauritania'),
('MU', 'Mauritius'),
('YT', 'Mayotte'),
('MX', 'Mexico'),
('FM', 'Micronesia, Federated States Of'),
('MD', 'Moldova, Republic Of'),
('MC', 'Monaco'),
('MN', 'Mongolia'),
('ME', 'Montenegro'),
('MS', 'Montserrat'),
('MA', 'Morocco'),
('MZ', 'Mozambique'),
('MM', 'Myanmar'),
('NA', 'Namibia'),
('NR', 'Nauru'),
('NP', 'Nepal'),
('NL', 'Netherlands'),
('NC', 'New Caledonia'),
('NZ', 'New Zealand'),
('NI', 'Nicaragua'),
('NE', 'Niger'),
('NG', 'Nigeria'),
('NU', 'Niue'),
('NF', 'Norfolk Island'),
('MP', 'Northern Mariana Islands'),
('NO', 'Norway'),
('OM', 'Oman'),
('PK', 'Pakistan'),
('PW', 'Palau'),
('PS', 'Palestine, State Of'),
('PA', 'Panama'),
('PG', 'Papua New Guinea'),
('PY', 'Paraguay'),
('PE', 'Peru'),
('PH', 'Philippines'),
('PN', 'Pitcairn'),
('PL', 'Poland'),
('PT', 'Portugal'),
('PR', 'Puerto Rico'),
('QA', 'Qatar'),
('RE', 'Réunion'),
('RO', 'Romania'),
('RU', 'Russian Federation'),
('RW', 'Rwanda'),
('BL', 'Saint Barthélemy'),
('SH', 'Saint Helena, Ascension And Tristan Da Cunha'),
('KN', 'Saint Kitts And Nevis'),
('LC', 'Saint Lucia'),
('MF', 'Saint Martin (French Part)'),
('PM', 'Saint Pierre And Miquelon'),
('VC', 'Saint Vincent And The Grenadines'),
('WS', 'Samoa'),
('SM', 'San Marino'),
('ST', 'Sao Tome And Principe'),
('SA', 'Saudi Arabia'),
('SN', 'Senegal'),
('RS', 'Serbia'),
('SC', 'Seychelles'),
('SL', 'Sierra Leone'),
('SG', 'Singapore'),
('SX', 'Sint Maarten (Dutch Part)'),
('SK', 'Slovakia'),
('SI', 'Slovenia'),
('SB', 'Solomon Islands'),
('SO', 'Somalia'),
('ZA', 'South Africa'),
('GS', 'South Georgia And The South Sandwich Islands'),
('SS', 'South Sudan'),
('ES', 'Spain'),
('LK', 'Sri Lanka'),
('SD', 'Sudan'),
('SR', 'Suriname'),
('SJ', 'Svalbard And Jan Mayen'),
('SZ', 'Swaziland'),
('SE', 'Sweden'),
('CH', 'Switzerland'),
('SY', 'Syrian Arab Republic'),
('TW', 'Taiwan, Province Of China'),
('TJ', 'Tajikistan'),
('TZ', 'Tanzania, United Republic Of'),
('TH', 'Thailand'),
('TL', 'Timor-Leste'),
('TG', 'Togo'),
('TK', 'Tokelau'),
('TO', 'Tonga'),
('TT', 'Trinidad And Tobago'),
('TN', 'Tunisia'),
('TR', 'Turkey'),
('TM', 'Turkmenistan'),
('TC', 'Turks And Caicos Islands'),
('TV', 'Tuvalu'),
('UG', 'Uganda'),
('UA', 'Ukraine'),
('AE', 'United Arab Emirates'),
('GB', 'United Kingdom'),
('US', 'United States'),
('UM', 'United States Minor Outlying Islands'),
('UY', 'Uruguay'),
('UZ', 'Uzbekistan'),
('VU', 'Vanuatu'),
('VE', 'Venezuela, Bolivarian Republic Of'),
('VN', 'Viet Nam'),
('VG', 'Virgin Islands, British'),
('VI', 'Virgin Islands, U.S.'),
('WF', 'Wallis And Futuna'),
('EH', 'Western Sahara'),
('YE', 'Yemen'),
('ZM', 'Zambia'),
('ZW', 'Zimbabwe');

/* QUERIES */

-- lines_per_invoice
SELECT
    product.id AS "Product code",
    line.line_number AS "Line",
    invoice.billing_date AS "Date",
    customer.company_name AS "Company"
FROM invoice
    JOIN line ON invoice.id = line.invoice_id
    JOIN customer ON customer.id = invoice.id
    JOIN product ON line.product_id = product.id
GROUP BY invoice.id
ORDER BY line.line_number ASC;
