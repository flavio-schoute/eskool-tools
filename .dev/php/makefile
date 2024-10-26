php-fix: php-cs-fixer

php-lint: php-lint-cs php-lint-cs-fix php-lint-md php-lint-stan

vendor: composer.json composer.lock
	@composer install

php-cs-fixer:
	@vendor/bin/php-cs-fixer fix --config=.dev/php/.php-cs-fixer.php --show-progress=dots

php-lint-cs:
	@vendor/bin/phpcs --standard=.dev/php/phpcs.xml -ps

php-lint-cs-fix:
	@vendor/bin/phpcbf --standard=.dev/php/phpcs.xml -p

php-lint-md:
	@vendor/bin/phpmd app config database routes tests ansi .dev/php/phpmd.xml --cache

php-lint-stan:
	@vendor/bin/phpstan analyze -c .dev/php/phpstan.neon --memory-limit=2G

test: vendor
	@vendor/bin/phpunit tests

test-coverage: vendor
	@php -dpcov.enabled=1 -dpcov.directory=. vendor/bin/phpunit --coverage-html ./.report