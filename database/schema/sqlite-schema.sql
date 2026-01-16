CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar not null,
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "role" varchar not null default 'user'
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
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
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "schedules"(
  "id" integer primary key autoincrement not null,
  "date" date not null,
  "room" varchar not null,
  "capacity" integer not null,
  "used_capacity" integer not null default '0',
  "status" varchar check("status" in('available', 'full')) not null default 'available',
  "category" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  "time" time not null default '08:00:00'
);
CREATE TABLE IF NOT EXISTS "faculties"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "study_programs"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "level" varchar not null,
  "faculty_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("faculty_id") references "faculties"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "participants"(
  "id" integer primary key autoincrement not null,
  "schedule_id" integer not null,
  "seat_number" varchar not null,
  "status" varchar not null default('pending'),
  "nim" varchar not null,
  "name" varchar not null,
  "gender" varchar not null,
  "birth_place" varchar not null,
  "birth_date" date not null,
  "email" varchar not null,
  "major" varchar not null,
  "faculty" varchar not null,
  "phone" varchar not null,
  "payment_date" date not null,
  "test_category" varchar not null,
  "previous_test_date" date,
  "payment_proof_path" varchar not null,
  "photo_path" varchar not null,
  "ktp_path" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  "study_program_id" integer,
  "faculty_id" integer,
  "username" varchar,
  "password" varchar,
  "test_score" numeric,
  "passed" tinyint(1) not null default '0',
  "test_date" date,
  "reading_score" numeric,
  "listening_score" numeric,
  "speaking_score" numeric,
  "writing_score" numeric,
  "test_format" varchar not null default 'iBT',
  "listening_score_pbt" numeric,
  "structure_score_pbt" numeric,
  "reading_score_pbt" numeric,
  "total_score_pbt" numeric,
  "academic_level" varchar,
  "seat_status" varchar not null default 'reserved',
  "temp_seat_number" varchar,
  foreign key("schedule_id") references schedules("id") on delete cascade on update no action,
  foreign key("study_program_id") references "study_programs"("id") on delete set null,
  foreign key("faculty_id") references "faculties"("id") on delete set null
);
CREATE UNIQUE INDEX "participants_username_unique" on "participants"(
  "username"
);
CREATE TABLE IF NOT EXISTS "sessions"(
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
CREATE UNIQUE INDEX "participants_nim_unique" on "participants"("nim");

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2025_01_01_000001_create_schedules_and_participants_table',1);
INSERT INTO migrations VALUES(5,'2025_11_25_110333_create_prodi_and_faculty_table',1);
INSERT INTO migrations VALUES(6,'2025_11_25_114123_add_role_to_users_table',1);
INSERT INTO migrations VALUES(7,'2025_11_25_114200_add_username_password_to_participants_table',1);
INSERT INTO migrations VALUES(14,'2025_11_27_074825_add_test_score_to_participants_table',2);
INSERT INTO migrations VALUES(15,'2025_11_27_090606_add_section_scores_to_participants_table',2);
INSERT INTO migrations VALUES(16,'2025_11_27_091244_add_pbt_scores_to_participants_table',2);
INSERT INTO migrations VALUES(17,'2025_11_27_100042_add_academic_level_to_participants_table',2);
INSERT INTO migrations VALUES(18,'2025_11_29_033051_create_sessions_table',2);
INSERT INTO migrations VALUES(19,'2025_11_29_082216_add_unique_constraint_to_nim_in_participants_table',2);
INSERT INTO migrations VALUES(20,'2025_11_30_021515_add_time_column_to_schedules_table',3);
INSERT INTO migrations VALUES(21,'2025_11_30_040847_add_seat_reservation_fields_to_participants_table',4);
