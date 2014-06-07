<?php
/**
 * @author Gerard van Helden <gerard@zicht.nl>
 * @copyright Zicht online <http://zicht.nl>
 */
namespace Zicht\Tool\Plugin\Interact;

use Zicht\Tool\Plugin as BasePlugin;
use \Zicht\Tool\Container\Container;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * Provides utilities to interact with the user.
 */
class Plugin extends BasePlugin
{
    public function appendConfiguration(ArrayNodeDefinition $rootNode)
    {
    }

    public function setContainer(Container $container)
    {
        trigger_error('console_dialog_helper is no longer a property of container. This needs to be refactored', E_USER_ERROR);
        die();

        $container->method(
            'ask',
            function($container, $q, $default = null) {
                return $container->console_dialog_helper->ask(
                    $container->output,
                    $q . ($default ? sprintf(' [<info>%s</info>]', $default) : '') . ': ',
                    $default
                );
            }
        );
        $container->method(
            'choose',
            function($container, $q, $options) {
                foreach ($options as $key => $option) {
                    $container->output->writeln(sprintf('[<info>%s</info>] %s', $key, $option));
                }

                return $container->console_dialog_helper->askAndValidate(
                    $container->output,
                    "$q: ",
                    function($value) use($options) {
                        if (!array_key_exists($value, $options)) {
                            throw new \InvalidArgumentException("Invalid option [$value]");
                        }
                        return $options[$value];
                    }
                );
            }
        );

        $container->method(
            'confirm',
            function($container, $q, $default = false) {
                return $container->console_dialog_helper->askConfirmation(
                    $container->output,
                    $q .
                        ($default === false ? ' [y/N] ' : ' [Y/n]'),
                    $default
                );
            }
        );
    }
}