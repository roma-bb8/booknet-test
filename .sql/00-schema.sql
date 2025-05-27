CREATE TABLE "ref_i18n"
(
    "id"   SMALLINT PRIMARY KEY,
    "code" VARCHAR(2)
);

CREATE TABLE "ref_countries"
(
    "id"   SMALLINT PRIMARY KEY,
    "code" VARCHAR(2)
);

CREATE TABLE "ref_os"
(
    "id"   SMALLINT PRIMARY KEY,
    "name" VARCHAR(7)
);

CREATE TABLE "ref_product_type"
(
    "id"   SMALLINT PRIMARY KEY,
    "name" VARCHAR(12)
);

CREATE TABLE "payment_systems"
(
    "id"          SERIAL PRIMARY KEY,
    "name"        VARCHAR(55) UNIQUE NOT NULL,
    "description" VARCHAR(255),
    "image_url"   VARCHAR(255)       NOT NULL,
    "status"      BOOLEAN,
    "updated_at"  TIMESTAMP                   DEFAULT NULL,
    "created_at"  TIMESTAMP          NOT NULL DEFAULT NOW()
);

CREATE INDEX "idx_payment_systems_status"
    ON "payment_systems" ("status");

CREATE TABLE "payment_systems__i18n"
(
    "id"                SERIAL PRIMARY KEY,
    "payment_system_id" INTEGER  NOT NULL REFERENCES "payment_systems" ("id") ON DELETE CASCADE,
    "i18n_id"           SMALLINT NOT NULL REFERENCES "ref_i18n" ("id") ON DELETE CASCADE,
    "name"              VARCHAR(55),
    "status"            BOOLEAN,
    UNIQUE ("payment_system_id", "i18n_id")
);

CREATE INDEX "idx_payment_systems__i18n_status"
    ON "payment_systems__i18n" ("status");

CREATE TABLE "payment_systems__countries"
(
    "id"                SERIAL PRIMARY KEY,
    "payment_system_id" INTEGER  NOT NULL REFERENCES "payment_systems" ("id") ON DELETE CASCADE,
    "country_id"        SMALLINT NOT NULL REFERENCES "ref_countries" ("id") ON DELETE CASCADE,
    "image_url"         VARCHAR(255),
    UNIQUE ("payment_system_id", "country_id")
);

CREATE TABLE "payment_methods"
(
    "id"                SERIAL PRIMARY KEY,
    "payment_system_id" INTEGER      NOT NULL REFERENCES "payment_systems" ("id") ON DELETE CASCADE,
    "name"              VARCHAR(255) NOT NULL,
    "commission"        DECIMAL(5, 2)         DEFAULT 0.00,
    "pay_url"           VARCHAR(255) NOT NULL,
    "status"            BOOLEAN,
    "priority"          INTEGER,
    "updated_at"        TIMESTAMP             DEFAULT NULL,
    "created_at"        TIMESTAMP    NOT NULL DEFAULT NOW()
);

CREATE INDEX "idx_payment_methods_status_priority"
    ON "payment_methods" ("status", "priority");

CREATE TABLE "payment_methods__i18n"
(
    "id"                SERIAL PRIMARY KEY,
    "payment_method_id" INTEGER  NOT NULL REFERENCES "payment_methods" ("id") ON DELETE CASCADE,
    "i18n_id"           SMALLINT NOT NULL REFERENCES "ref_i18n" ("id") ON DELETE CASCADE,
    "name"              VARCHAR(255),
    "status"            BOOLEAN,
    "priority"          INTEGER,
    UNIQUE ("payment_method_id", "i18n_id")
);

CREATE INDEX "idx_payment_methods__i18n_status_priority"
    ON "payment_methods__i18n" ("status", "priority");

CREATE TABLE "payment_methods__countries"
(
    "id"                SERIAL PRIMARY KEY,
    "payment_method_id" INTEGER  NOT NULL REFERENCES "payment_methods" ("id") ON DELETE CASCADE,
    "country_id"        SMALLINT NOT NULL REFERENCES "ref_countries" ("id") ON DELETE CASCADE,
    "status"            BOOLEAN,
    "priority"          INTEGER,
    UNIQUE ("payment_method_id", "country_id")
);

CREATE INDEX "idx_payment_methods__countries_status_priority"
    ON "payment_methods__countries" ("status", "priority");

CREATE TABLE "payment_methods_list"
(
    "id"                SERIAL PRIMARY KEY,
    "payment_method_id" INTEGER       NOT NULL REFERENCES "payment_methods" ("id") ON DELETE CASCADE,
    "product_type_id"   SMALLINT      NOT NULL REFERENCES "ref_product_type" ("id") ON DELETE CASCADE,
    "country_id"        SMALLINT               DEFAULT NULL REFERENCES "ref_countries" ("id") ON DELETE CASCADE,
    "amount_min"        DECIMAL(7, 2) NOT NULL DEFAULT 0.00,
    "amount_max"        DECIMAL(7, 2) NOT NULL DEFAULT 10000.00,
    "os_id"             SMALLINT               DEFAULT NULL REFERENCES "ref_os" ("id") ON DELETE CASCADE,
    "updated_at"        TIMESTAMP              DEFAULT NULL,
    "created_at"        TIMESTAMP     NOT NULL DEFAULT NOW()
);

CREATE INDEX "idx_payment_methods_list_filter"
    ON "payment_methods_list" ("product_type_id", "os_id", "amount_min", "amount_max");
