##---
## Symfony Installer
##---

db-migrations: ## Execute migrations
	docker exec -i -t bh_postgres psql admin admin -f sql/migrations.sql

db-seeds: ## Import seeds
	docker exec -it bh_postgres psql admin admin -f sql/seeds.sql

db-delete: ## Reset DB
  php bin/console doctrine:database:drop --if-exists --force

db-create: ## Create DB
  php bin/console doctrine:database:create --if-not-exists

db-schema: ## Create/Update Schema
  php bin/console doctrine:schema:update --force --dump-sql

db-load-fixtures: ## Load Fixtures
  yes "" | php bin/console doctrine:fixtures:load

install: ## Drop seeds
  php bin/console doctrine:database:create --if-not-exists
  php bin/console doctrine:schema:update --force --dump-sql
  yes "" | php bin/console doctrine:fixtures:load


# DEFAULT
.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help

## ---
