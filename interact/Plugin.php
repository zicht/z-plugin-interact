<?php
/**
 * @author Gerard van Helden <gerard@zicht.nl>
 * @copyright Zicht online <http://zicht.nl>
 */
namespace Zicht\Tool\Plugin\Interact;

use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
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
        $helper = new DialogHelper();

        $container->method(
            'ask',
            function($container, $q, $default = null) use($helper) {
                return $helper->ask(
                    $container->output,
                    $q . ($default ? sprintf(' [<info>%s</info>]', $default) : '') . ': ',
                    $default
                );
            }
        );
        $container->method(
            'choose',
            function($container, $q, $options) use($helper) {
                foreach ($options as $key => $option) {
                    $container->output->writeln(sprintf('[<info>%s</info>] %s', $key, $option));
                }

                return $helper->askAndValidate(
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
            function($container, $q, $default = false) use($helper) {
                return $helper->askConfirmation(
                    $container->output,
                    $q .
                        ($default === false ? ' [y/N] ' : ' [Y/n]'),
                    $default
                );
            }
        );
    }
}