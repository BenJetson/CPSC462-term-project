
.PHONY: local
local:
	docker-compose up

.PHONY: stop
stop:
	docker-compose down

.PHONY: clean
clean:
	yes | docker-compose rm

.PHONY: build
build: clean
	docker-compose build

.PHONY: deploy
deploy:
	@echo
	@echo "⚠️ WARNING: This starts a deploy to the PRODUCTION environment."
	@echo "Press enter to continue or ^C to abort."
	@echo
	@read
	TIER=prod ./scripts/deploy.sh

.PHONY: dev
dev:
	TIER=dev ./scripts/deploy.sh

.PHONY: composer
composer:
	composer install
