# Define Sail command path
SAIL = ./vendor/bin/sail

# Display help
help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'

# Commands
up: ## Start the Laravel Sail environment
	$(SAIL) up -d

down: ## Stop the Laravel Sail environment
	$(SAIL) down

ssh: ## SSH into the Sail container
	$(SAIL) shell

install: ## Install Composer dependencies
	$(SAIL) composer install

migrate: ## Run the database migrations
	$(SAIL) artisan migrate

seed: ## Seed the database
	$(SAIL) artisan db:seed

make-seeder: ## Create a new seeder (use: make make-seeder SEEDER_NAME=YourSeeder)
	$(SAIL) artisan make:seeder $(SEEDER_NAME)

test: ## Run the tests
	$(SAIL) artisan test

# Use .PHONY to declare commands that are not actual files
.PHONY: up down ssh install migrate seed make-seeder test help
