https://tailwindui.com/components/application-ui/application-shells/sidebar

## Commands:

### PHP-CS-Fixer

```bash
vendor/bin/php-cs-fixer fix --config=.dev/php-cs-fixer.php --show-progress=dots
```

### PHPStan

```bash
vendor/bin/phpstan analyze --configuration=.dev/phpstan.neon
```

### PHPMD

```bash
vendor/bin/phpmd controllers text .dev/phpmd.xml
```
