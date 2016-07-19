.PHONY: test

test:
	php vendor/bin/phpunit

test_ci:
	php vendor/bin/phpunit --coverage-clover build/logs/clover.xml
