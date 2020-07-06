# z plugin for user interaction

This plugin provides a few user interaction utilities implemented using the 'dialog helper' from Symfony.

## Examples:

### Ask a user for confirmation

```yml
plugins: ['interact']

tasks:
    test:
        - @(if confirm("Are you sure?")) echo "OK, I will..."
```

### Ask a user to choose between a few options

It's very easy to supply defaults with Z, which will ask the user
if it wasn't supplied on the command line. See this example which
uses `env` and `interact` together:


```yml
plugins: ['interact', 'env']


envs:
    production:
        ssh: user@production
    testing:
        ssh: user@production

tasks:
    ssh:
        args:
            target_env: ? choose("Where do you want to go?", keys envs)
        do: @env.ssh
```

This will result in a list of options being displayed:

```
$ z --explain ssh
[0] production
[1] testing
Where do you want to go?: 1
NOTICE: interactive shell:
( /bin/bash -c 'ssh -tq user@testing "cd ; bash --login"' )
```

### Ask a user for text input
```yml
plugins: ['interact']

tasks:
    test:
        set:
            name: ask("What is your name?", USER)
        do: echo "Hello $(name), my name is `uname`"
```
Running this:

```
$ z test
What is your name? [gerard]: 
Hello gerard, my name is Linux
```

# Maintainer(s)
