# Claude Development Rules

## Docker Environment
- This project uses Docker for development
- Always prefix Laravel artisan commands with `docker compose exec app`
- Example: `docker compose exec app php artisan migrate`
- Example: `docker compose exec app php artisan test`

## Testing
- Run tests with: `make test` (which uses Docker internally)
- For specific tests, use: `docker compose exec app php artisan test --filter=TestName`

## Migrations
- Always use Docker when running migrations: `docker compose exec app php artisan migrate`
- For fresh migration: `docker compose exec app php artisan migrate:fresh --seed`