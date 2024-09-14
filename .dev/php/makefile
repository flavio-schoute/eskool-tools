vendor: composer.json composer.lock
	@composer install

php-fix: vendor
	@vendor/bin/php-cs-fixer fix --config=.dev/.php-cs-fixer.php --show-progress=dots

test: vendor
	@vendor/bin/phpunit tests