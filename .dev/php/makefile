php-lint: php-lint-cs-fixer php-lint-cs php-lint-md php-lint-stan

php-lint-cs-fixer:
	@vendor/bin/phpcs --standard=.dev/php/phpcs.xml -ps

php-lint-cs:
	@vendor/bin/phpcbf --standard=.dev/php/phpcs.xml -p

php-lint-md:
	@vendor/bin/phpmd app config database routes tests ansi .dev/php/phpmd.xml --cache

php-lint-stan:
	@vendor/bin/phpstan analyze -c .dev/php/phpstan.neon --memory-limit=2G

vendor: composer.json composer.lock
	@composer install

php-fix: vendor php-lint-cs
	@vendor/bin/php-cs-fixer fix --config=.dev/php/.php-cs-fixer.php --show-progress=dots

test: vendor
	@vendor/bin/phpunit tests