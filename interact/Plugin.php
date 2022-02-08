<?php
/**
 * @copyright Zicht Online <https://zicht.nl>
 */

namespace Zicht\Tool\Plugin\Interact;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Zicht\Tool\Container\Container;
use Zicht\Tool\Plugin as BasePlugin;

/**
 * Provides utilities to interact with the user.
 */
class Plugin extends BasePlugin
{
    public function setContainer(Container $container)
    {
        $helper = new QuestionHelper();
        $input = new ArgvInput();
        $input->setInteractive((bool)$container->get('INTERACTIVE'));

        $container->method(
            'ask',
            function ($container, $question, $default = null) use ($helper, $input) {
                $questionObj = new Question($question . ($default ? sprintf(' [<info>%s</info>]', $default) : '') . ': ', $default);
                return $helper->ask($input, $container->output, $questionObj);
            }
        );
        $container->method(
            'choose',
            function ($container, $question, $options) use ($helper, $input) {
                foreach ($options as $key => $option) {
                    $container->output->writeln(sprintf('[<info>%s</info>] %s', $key, $option));
                }

                $choiceQuestion = new ChoiceQuestion($question . ': ', $options);
                return $helper->ask($input, $container->output, $choiceQuestion);
            }
        );

        $container->method(
            'confirm',
            function ($container, $question, $default = false) use ($helper, $input) {
                $confirmationQuestion = new ConfirmationQuestion($question . ($default === false ? ' [y/N] ' : ' [Y/n]'), $default);
                return $helper->ask($input, $container->output, $confirmationQuestion);
            }
        );
    }
}