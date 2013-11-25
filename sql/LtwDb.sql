/* USER MANAGEMENT */

DROP TABLE IF EXISTS role;
DROP TABLE IF EXISTS user;

CREATE TABLE IF NOT EXISTS role (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name CHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username CHAR(50) NOT NULL UNIQUE,
    password CHAR(256) NOT NULL,
    role_id INTEGER NOT NULL,
    FOREIGN KEY(role_id) REFERENCES role(id)
);

/* INVOICING SYSTEM */

DROP TABLE IF EXISTS customer;
DROP TABLE IF EXISTS invoice;
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS city;
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

CREATE TABLE IF NOT EXISTS customer (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tax_id INTEGER UNIQUE,
    company_name CHAR(100) NOT NULL,
    email CHAR(60) NOT NULL,
    detail CHAR(50),
    city_id INTEGER,
    postal_code CHAR(8) NOT NULL,
    country_code CHAR(2) NOT NULL,
    FOREIGN KEY(city_id) REFERENCES city(id),
    FOREIGN KEY(country_code) REFERENCES country(code)
);

CREATE TABLE IF NOT EXISTS product (
    id INTEGER PRIMARY KEY,
    description CHAR(50) NOT NULL,
    unit_price INTEGER CHECK (unit_price > 0));

CREATE TABLE IF NOT EXISTS invoice (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    billing_date DATE NOT NULL,
    customer_id INTEGER,
    FOREIGN KEY(customer_id) REFERENCES customer(id)
);

CREATE TABLE IF NOT EXISTS line (
    product_id INTEGER,
    line_number INTEGER,
    invoice_id INTEGER,
    quantity INTEGER CHECK (quantity > 0),
    unit_price REAL CHECK (unit_price >= 0),
    tax_id INTEGER,
    PRIMARY KEY(product_id, line_number, invoice_id),
    FOREIGN KEY(product_id) REFERENCES product(id),
    FOREIGN KEY(invoice_id) REFERENCES invoice(id),
    FOREIGN KEY(tax_id) REFERENCES tax(id)
);

CREATE TABLE IF NOT EXISTS tax (
    id INTEGER PRIMARY KEY,
    type CHAR(50) NOT NULL,
    percentage INTEGER CHECK (percentage > 0)
);

INSERT INTO role (name) VALUES
('Reader'),
('Editor'),
('Administrator');

/* INSERTIONS */

INSERT INTO tax (id, type, percentage) VALUES
(1, 'IVA', 23),
(2, 'VAT', 18);

INSERT INTO product (id, description, unit_price) VALUES
(125, 'MSI nGTX560-ti OC edition GPU',     229),
(126, 'OCZ Vector4 128Gb SSD',             100),
(127, 'GSkill 1600Mhz 2x2Gb RAM',         50),
(128, 'Samsung Pinpoint F3 2TB HDD',     70),
(129, 'Generic Sata cable',             2),
(130, 'Samsung 840 EVO 512GB SSD',         380),
(131, 'Asus nGTX 780ti matrix',         550),
(132, 'Msi R290x Lightning edition',     500),
(133, 'Asus P67 Sabertooth motherboard', 130),
(134, 'ASRock Z77 pro3 motherboard',     100),
(135, 'XFX Pro 550W Bronze PSU',         80),
(136, 'Corsair 1050i Gold PSU',         120),
(137, 'Coolermaster HAF-X pc case',     100),
(138, 'Coolermaster HAF-xb pc case', 90),
(139, 'Corsair carbide R300 pc case', 70),
(140, 'LG liteon dvd drive', 20),
(141, 'Computer hardware for dummies book, 2nd edition', 8),
(142, 'Razer Deathadder 2013 edition mouse', 45),
(143, 'Microsoft Office 1 month trial retail', 13),
(144, 'Windows 7.9 premium retail', 79),
(145, 'Asus N550-CN214H laptop', 1100),
(146, 'Asus n56-S4800 laptop', 900),
(147, 'Intel core i5 3930K CPU', 150),
(148, 'Amd A8 cpu', 100),
(149, 'LG 23 inch widescreen LED monitor (1920x1080)', 140),
(150, '50 shades of grey book, 3rd edition', 22),
(151, 'Microsoft Visio 2012 Retail', 55),
(152, 'Microsoft Visual Studio 2013 Ultimate Retail', 330),
(153, 'Adobe Suite Retail', 1099),
(154, '007: Skyfall Blu-ray Retail', 20),
(155, 'Steelseries 6Gv2 mechanical keyboard', 65),
(156, 'Drive blu-ray retail', 20),
(157, 'Only god forgives Blu-ray retail', 20),
(158, 'Metallica: Best of CD-DVD retail', 25),
(159, '´Google chrome: a buggy history´ book retail', 21),
(160, '´Why web development was made by the devil´ book retail', 22),
(161, '´A heurística´ book retail', 17),
(162, '´Mais vale dois pássaros na mão que um a voar´ book retail', 16),
(163, '´Bottom line: java sucks´ book retail', 44),
(164, '´100 reasons to not get married´ book retail', 24),
(165, '´O convento do Memorial´ book retail', 12),
(166, '´O Luís e o íadas´ book retail', 33),
(168, 'Bitphoenix prodigy pc case', 80),
(169, 'Asus Xonar 5.1 PCI-E audio card', 99),
(170, 'TP-Link wireless card', 88),
(171, 'HDMI cable 2 meters', 1),
(172, 'Thunderbolt cable 1 meter', 30),
(173, 'Thunderbolt cable 3 meters', 45),
(174, '´The Witcher 2´ PC retail', 12),
(175, '´Mass Effect Trilogy´ PC retail', 19),
(176, '´The Witcher: Enhanced edition´ PC retail', 11),
(177, '´Metal Gear Solid: Legacy Edition´ PS3 retail', 50),
(178, '´The Walking dead: season 1´ PC digital', 20),
(179, '´Battlefield 4´ PC retail', 60),
(180, '´Forza 5´ Xbox One retail', 22),
(181, 'PS4 retail', 399),
(182, 'Xbox one retail', 500);

INSERT INTO line (product_id, line_number, invoice_id, quantity, unit_price, tax_id) VALUES
(125, 1, 1, 3, 90, 1),
(126, 2, 1, 1, 450, 1),
(125, 1, 2, 4, 90, 1),
(126, 2, 2, 10, 450, 1),
(127, 1, 3, 7, 40, 2),
(129, 2 , 3, 7, 2, 2),
(128, 3 , 3, 7, 380, 2),
(129, 4 , 3, 7, 550, 2),
(130, 5 , 3, 7, 500, 2),
(131, 6 , 3, 7, 130, 2),
(132, 7 , 3, 7, 100, 2),
(133, 8 , 3, 7, 80, 2),
(134, 9 , 3, 7, 120, 2),
(135, 10, 3, 7, 100, 2),
(136, 11, 3, 7, 90, 2),
(137, 12, 3, 7, 70, 2),
(138, 13, 3, 7, 20, 2),
(139, 14, 3, 7, 8, 2),
(140, 15, 3, 7, 45, 2),
(141, 16, 3, 7, 13, 2),
(142, 17, 3, 7, 79, 2),
(143, 18, 3, 7, 1100, 1),
(144, 19, 3, 7, 900, 2),
(145, 20, 3, 7, 150, 2),
(146, 21, 3, 7, 100, 2),
(147, 22, 3, 7, 150, 2),
(148, 23, 3, 7, 380, 2),
(168, 24, 3, 7, 233, 2),
(169, 25, 3, 7, 212, 2),
(170, 26, 3, 7, 151, 2),
(171, 27, 3, 7, 555, 2),
(172, 28, 3, 7, 100, 2),
(173, 29, 3, 7, 115, 2),
(148, 1, 4, 7, 140, 2),
(150, 1 , 6, 100, 55, 1),
(151, 2 , 6, 30, 43, 1),
(152, 3 , 6, 22, 42, 1),
(153, 4 , 6, 31, 11, 1),
(154, 5 , 6, 442, 33, 1),
(155, 6 , 6, 223, 39, 1),
(156, 7 , 6, 122, 21, 1),
(157, 8 , 6, 23, 22, 1),
(158, 9 , 6, 44, 50, 1),
(159, 10, 6, 100, 22, 1),
(160, 11, 6, 100, 22, 1),
(161, 12, 6, 21, 22, 1),
(162, 13, 6, 5, 32, 1),
(163, 14, 6, 1, 22, 1),
(164, 15, 6, 3, 22, 1),
(165, 16, 6, 100, 22, 1),
(166, 17, 6, 143, 40, 1),
(174, 18, 6, 143, 25, 1),
(175, 19, 6, 143, 19, 1),
(176, 20, 6, 143, 11, 1),
(177, 21, 6, 143, 30, 1),
(178, 22, 6, 143, 14, 1),
(179, 23, 6, 143, 22, 1),
(180, 24, 6, 143, 26, 1),
(181, 25, 6, 143, 26, 1),
(182, 26, 6, 143, 11, 1),
(128, 1, 5, 500, 150, 1),
(127, 1, 8, 134, 69, 1);

INSERT INTO invoice (id, billing_date, customer_id) VALUES
(1, '2013-09-27', 555560),
(2, '2013-09-27', 555560),
(3, '2013-09-30', 555568),
(4, '2013-10-24', 555565),
(5, '2013-10-21', 555566),
(6, '2013-11-10', 555567),
(8, '2013-07-22', 555568);

INSERT INTO city (id, name) VALUES
(1, 'Porto'),
(2, 'Lisbon'),
(3, 'London');

INSERT INTO customer (id, tax_id, company_name, email, detail, city_id, postal_code, country_code) VALUES
(555560, 123, 'FEUP', 'feup@feup.com', 'Rua Dr. Roberto Frias, s/n', 1, '4200-465', 'PT'),
(555561, 124, 'UP', 'up@up.com', 'Praça Gomes Teixeira', 1, '4099-002', 'PT'),
(555565, 125, 'WSI', 'comercial@wsi-bg.pt', 'Rua Faria Guimarães, 765', 1, '4200-291', 'PT'),
(555566, 126, 'Alientech', 'comercial@alientech.pt', 'Rua da Torrinha, 194', 1, '4050-610', 'PT'),
(555567, 127, 'FNAC', 'comercial@fnac.pt', 'Rua Professor Carlos Alberto Mota Pinto, nr 9 - 6 B', 2, '1070-374', 'PT'),
(555568, 128, 'Memory', 'comercial@memory.co', 'London', 3, '1000-155', 'GB');


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
