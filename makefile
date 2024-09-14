-include .dev/php/makefile

.DEFAULT_GOAL: lint

lint: php-lint

fix: php-fix