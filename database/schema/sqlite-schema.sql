CREATE TABLE "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE INDEX "cache_expiration_index" on "cache"("expiration");
CREATE TABLE "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE INDEX "cache_locks_expiration_index" on "cache_locks"("expiration");
CREATE TABLE "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE "personal_access_tokens"(
  "id" integer primary key autoincrement not null,
  "tokenable_type" varchar not null,
  "tokenable_id" integer not null,
  "name" text not null,
  "token" varchar not null,
  "abilities" text,
  "last_used_at" datetime,
  "expires_at" datetime,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" on "personal_access_tokens"(
  "tokenable_type",
  "tokenable_id"
);
CREATE UNIQUE INDEX "personal_access_tokens_token_unique" on "personal_access_tokens"(
  "token"
);
CREATE INDEX "personal_access_tokens_expires_at_index" on "personal_access_tokens"(
  "expires_at"
);
CREATE TABLE "categories"(
  "id" integer primary key autoincrement not null,
  "name_ar" varchar not null,
  "name_en" varchar not null,
  "slug" varchar not null,
  "parent_id" integer,
  "image" varchar,
  "is_active" tinyint(1) not null default '1',
  "sort_order" integer not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  "name_fr" varchar,
  foreign key("parent_id") references "categories"("id") on delete set null
);
CREATE UNIQUE INDEX "categories_slug_unique" on "categories"("slug");
CREATE TABLE "products"(
  "id" integer primary key autoincrement not null,
  "name_ar" varchar not null,
  "name_en" varchar not null,
  "slug" varchar not null,
  "description_ar" text,
  "description_en" text,
  "price" numeric not null,
  "original_price" numeric,
  "discount_percent" integer,
  "stock" integer not null default '0',
  "category_id" integer not null,
  "images" text,
  "specifications" text,
  "is_active" tinyint(1) not null default '1',
  "is_featured" tinyint(1) not null default '0',
  "deal_ends_at" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "name_fr" varchar,
  "description_fr" text,
  foreign key("category_id") references "categories"("id") on delete cascade
);
CREATE UNIQUE INDEX "products_slug_unique" on "products"("slug");
CREATE TABLE "product_variants"(
  "id" integer primary key autoincrement not null,
  "product_id" integer not null,
  "attribute" varchar not null,
  "value" varchar not null,
  "extra_price" numeric not null default '0',
  "stock" integer not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("product_id") references "products"("id") on delete cascade
);
CREATE TABLE "addresses"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "label" varchar not null default 'Home',
  "name" varchar not null,
  "phone" varchar not null,
  "street" varchar not null,
  "city" varchar not null,
  "country" varchar not null default 'EG',
  "is_default" tinyint(1) not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE TABLE "coupons"(
  "id" integer primary key autoincrement not null,
  "code" varchar not null,
  "discount_type" varchar check("discount_type" in('percent', 'fixed')) not null default 'percent',
  "value" numeric not null,
  "max_uses" integer,
  "used_count" integer not null default '0',
  "min_order_amount" numeric not null default '0',
  "expires_at" datetime,
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "coupons_code_unique" on "coupons"("code");
CREATE TABLE "orders"(
  "id" integer primary key autoincrement not null,
  "user_id" integer,
  "address_id" integer,
  "status" varchar check("status" in('placed', 'preparing', 'awaiting_shipment', 'shipped', 'in_transit', 'delivered', 'no_answer', 'postponed', 'wrong_address', 'cancelled', 'returned')) not null default 'placed',
  "subtotal" numeric not null,
  "delivery_fee" numeric not null default '0',
  "discount" numeric not null default '0',
  "total" numeric not null,
  "coupon_code" varchar,
  "notes" text,
  "guest_name" varchar,
  "guest_phone" varchar,
  "guest_address" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "guest_email" varchar,
  "payment_method" varchar not null default 'cod',
  "payment_id" varchar,
  foreign key("user_id") references "users"("id") on delete set null,
  foreign key("address_id") references "addresses"("id") on delete set null
);
CREATE TABLE "order_items"(
  "id" integer primary key autoincrement not null,
  "order_id" integer not null,
  "product_id" integer not null,
  "variant_id" integer,
  "product_name" varchar not null,
  "unit_price" numeric not null,
  "qty" integer not null,
  "total" numeric not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("order_id") references "orders"("id") on delete cascade,
  foreign key("product_id") references "products"("id") on delete cascade,
  foreign key("variant_id") references "product_variants"("id") on delete set null
);
CREATE TABLE "wishlists"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "product_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade,
  foreign key("product_id") references "products"("id") on delete cascade
);
CREATE UNIQUE INDEX "wishlists_user_id_product_id_unique" on "wishlists"(
  "user_id",
  "product_id"
);
CREATE TABLE "reviews"(
  "id" integer primary key autoincrement not null,
  "product_id" integer not null,
  "user_id" integer not null,
  "rating" integer not null,
  "comment" text,
  "approved" tinyint(1) not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("product_id") references "products"("id") on delete cascade,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE UNIQUE INDEX "reviews_product_id_user_id_unique" on "reviews"(
  "product_id",
  "user_id"
);
CREATE TABLE "cart_items"(
  "id" integer primary key autoincrement not null,
  "session_id" varchar,
  "user_id" integer,
  "product_id" integer not null,
  "variant_id" integer,
  "qty" integer not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade,
  foreign key("product_id") references "products"("id") on delete cascade,
  foreign key("variant_id") references "product_variants"("id") on delete set null
);
CREATE INDEX "cart_items_session_id_index" on "cart_items"("session_id");
CREATE TABLE "product_views"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "product_id" integer not null,
  "viewed_at" datetime not null default CURRENT_TIMESTAMP,
  foreign key("user_id") references "users"("id") on delete cascade,
  foreign key("product_id") references "products"("id") on delete cascade
);
CREATE UNIQUE INDEX "product_views_user_id_product_id_unique" on "product_views"(
  "user_id",
  "product_id"
);
CREATE TABLE "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar,
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "role" varchar not null default('customer'),
  "phone" varchar,
  "avatar" varchar
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE TABLE "social_accounts"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "provider" varchar not null,
  "provider_id" varchar not null,
  "avatar" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE UNIQUE INDEX "social_accounts_provider_provider_id_unique" on "social_accounts"(
  "provider",
  "provider_id"
);
CREATE INDEX "social_accounts_user_id_index" on "social_accounts"("user_id");
CREATE TABLE "notifications"(
  "id" varchar not null,
  "type" varchar not null,
  "notifiable_type" varchar not null,
  "notifiable_id" integer not null,
  "data" text not null,
  "read_at" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  primary key("id")
);
CREATE INDEX "notifications_notifiable_type_notifiable_id_index" on "notifications"(
  "notifiable_type",
  "notifiable_id"
);
CREATE TABLE "banners"(
  "id" integer primary key autoincrement not null,
  "image_path" varchar not null,
  "is_active" tinyint(1) not null default '1',
  "order" integer not null default '0',
  "created_at" datetime,
  "updated_at" datetime
);

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2026_04_09_232909_create_personal_access_tokens_table',1);
INSERT INTO migrations VALUES(5,'2026_04_10_000001_add_fields_to_users_table',1);
INSERT INTO migrations VALUES(6,'2026_04_10_000002_create_categories_table',1);
INSERT INTO migrations VALUES(7,'2026_04_10_000003_create_products_table',1);
INSERT INTO migrations VALUES(8,'2026_04_10_000004_create_orders_tables',1);
INSERT INTO migrations VALUES(9,'2026_04_10_000005_create_social_tables',1);
INSERT INTO migrations VALUES(10,'2026_04_10_031732_add_french_fields_to_tables',1);
INSERT INTO migrations VALUES(11,'2026_04_10_153000_create_product_views_table',1);
INSERT INTO migrations VALUES(12,'2026_04_10_162000_add_social_accounts_and_nullable_password',1);
INSERT INTO migrations VALUES(13,'2026_06_06_000001_add_guest_email_to_orders_table',2);
INSERT INTO migrations VALUES(14,'2026_06_06_180853_add_payment_method_to_orders_table',3);
INSERT INTO migrations VALUES(15,'2026_06_06_191945_create_notifications_table',4);
INSERT INTO migrations VALUES(16,'2026_06_06_194229_create_banners_table',5);
