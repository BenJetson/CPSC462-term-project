
.PHONY: run
run:
	docker-compose up

.PHONY: logs
logs:
	docker-compose logs -f

.PHONY: log
log: logs ;

.PHONY: stop
stop:
	docker-compose down

.PHONY: halt
halt: stop ;

.PHONY: clean
clean:
	docker-compose down -v
	yes | docker-compose rm

.PHONY: build
build: clean
	docker-compose build

.PHONY: rebuild-run
rebuild-run: clean build run ;

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
