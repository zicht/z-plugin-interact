# z plugin for user interaction

This plugin provides a few user interaction utilities implemented using the 'dialog helper' from Symfony.

## Examples:

Ask a user for confirmation:

```yml
plugins: ['interact']

tasks:
    test:
        - @(if confirm("Are you sure?")) echo "OK, I will..."
```


