# Survey Analytics API

REST API for managing market research surveys, member panels, and response analytics. Built with Laravel 12 + MySQL.

## What this demonstrates

- REST API design with versioning (`/api/v1`)
- High-traffic query optimization (composite indexes, eager loading, raw joins for analytics)
- Caching strategy (Laravel Cache with TTL, invalidation on write)
- Queue-based async processing (ProcessResponseJob with retry + failure handling)
- JSON-typed columns for flexible survey schemas
- Pagination on all list endpoints
- Validation via FormRequest pattern

## Tech stack

- **Backend:** Laravel 12, PHP 8.3
- **Database:** MySQL 8 (composite indexes, JSON columns)
- **Cache:** Laravel Cache (file driver locally, Redis-ready in production)
- **Queue:** Laravel Queue (database driver locally)

## Setup

### Local Setup

```bash
git clone https://github.com/Cilkotron/survey-analytics-api.git
cd survey-analytics-api
composer install
cp .env.example .env
php artisan key:generate

# Configure DB in .env
php artisan migrate
php artisan db:seed

php artisan serve
php artisan queue:work
```

### Docker Setup (Laravel Sail)

Laravel Sail provides a Docker-based local development environment with PHP 8.5 and MySQL 8.4.

**Prerequisites:**
- Docker Desktop installed and running

**Installation:**

```bash
git clone https://github.com/Cilkotron/survey-analytics-api.git
cd survey-analytics-api
cp .env.example .env

# Build and start the containers
./sail up

# Run composer install inside the container
./sail composer install

# Generate application key
./sail artisan key:generate

# Run migrations
./sail artisan migrate

# Seed the database
./sail artisan db:seed

# Run the queue worker
./sail artisan queue:work
```

## Endpoints

### Surveys
- `GET /api/v1/surveys` — list surveys (paginated, filterable by status)
- `POST /api/v1/surveys` — create survey
- `GET /api/v1/surveys/{id}` — show survey + response count
- `PATCH /api/v1/surveys/{id}` — update
- `DELETE /api/v1/surveys/{id}` — delete

### Responses
- `GET /api/v1/responses` — list (filterable by survey_id, status)
- `POST /api/v1/responses` — submit response (dispatches ProcessResponseJob)

### Analytics
- `GET /api/v1/analytics/surveys/{id}` — per-survey stats (cached 5 min)
- `GET /api/v1/analytics/dashboard` — global stats (cached 10 min)

## Testing

The project uses PHPUnit with SQLite in-memory for fast, isolated tests.

**Local:**
```bash
php artisan test
```

**With Sail:**
```bash
./sail test
```

### Test coverage

- **Feature tests** (HTTP request lifecycle through routes, middleware, controllers):
  - `SurveyControllerTest` — list, filter by status, create, validation
  - `ResponseControllerTest` — create response, validate foreign keys, job dispatch

- **Unit tests** (isolated class logic):
  - `ProcessResponseJobTest` — verifies member stats increment after response

Tests run against SQLite in-memory database — fast (~2-5 seconds) and isolated from local MySQL.

## Schema highlights

```sql
-- Composite indexes for high-traffic queries
CREATE INDEX idx_responses_survey_completed 
  ON responses(survey_id, completed_at);

CREATE INDEX idx_responses_survey_status 
  ON responses(survey_id, completion_status);
```

## Production considerations

- **Database:** read replicas for analytics queries, write to primary
- **Cache:** swap to Redis driver
- **Queue:** use Redis or SQS, run multiple workers
- **Monitoring:** add Sentry/Bugsnag for error tracking
- **Rate limiting:** Laravel's built-in `throttle` middleware on auth endpoints

## Performance notes

Tested locally with ~20k members and ~45k responses. Analytics queries with composite indexes return in <100ms. For 1M+ responses in production, the same query plan holds with read replicas + Redis caching.

## Author

Sanja Budić — [linkedin.com/in/sanjabudic](https://linkedin.com/in/sanjabudic)
