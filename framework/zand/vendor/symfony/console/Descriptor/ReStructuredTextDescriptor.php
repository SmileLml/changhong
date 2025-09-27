<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Descriptor;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\String\UnicodeString;

class ReStructuredTextDescriptor extends Descriptor
{
    // <h1>
    /**
     * @var string
     */
    private $partChar = '=';
    // <h2>
    /**
     * @var string
     */
    private $chapterChar = '-';
    // <h3>
    /**
     * @var string
     */
    private $sectionChar = '~';
    // <h4>
    /**
     * @var string
     */
    private $subsectionChar = '.';
    // <h5>
    /**
     * @var string
     */
    private $subsubsectionChar = '^';
    // <h6>
    /**
     * @var string
     */
    private $paragraphsChar = '"';

    /**
     * @var mixed[]
     */
    private $visibleNamespaces = [];

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param object $object
     * @param mixed[] $options
     */
    public function describe($output, $object, $options = [])
    {
        $decorated = $output->isDecorated();
        $output->setDecorated(false);

        parent::describe($output, $object, $options);

        $output->setDecorated($decorated);
    }

    /**
     * Override parent method to set $decorated = true.
     * @param string $content
     * @param bool $decorated
     */
    protected function write($content, $decorated = true)
    {
        parent::write($content, $decorated);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputArgument $argument
     * @param mixed[] $options
     */
    protected function describeInputArgument($argument, $options = [])
    {
        $this->write(
            $argument->getName() ?: '<none>'."\n".str_repeat($this->paragraphsChar, Helper::width($argument->getName()))."\n\n"
                .($argument->getDescription() ? preg_replace('/\s*[\r\n]\s*/', "\n", $argument->getDescription())."\n\n" : '')
                .'- **Is required**: '.($argument->isRequired() ? 'yes' : 'no')."\n"
                .'- **Is array**: '.($argument->isArray() ? 'yes' : 'no')."\n"
                .'- **Default**: ``'.str_replace("\n", '', var_export($argument->getDefault(), true)).'``'
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputOption $option
     * @param mixed[] $options
     */
    protected function describeInputOption($option, $options = [])
    {
        $name = '\-\-'.$option->getName();
        if ($option->isNegatable()) {
            $name .= '|\-\-no-'.$option->getName();
        }
        if ($option->getShortcut()) {
            $name .= '|-'.str_replace('|', '|-', $option->getShortcut());
        }

        $optionDescription = $option->getDescription() ? preg_replace('/\s*[\r\n]\s*/', "\n\n", $option->getDescription())."\n\n" : '';
        $optionDescription = (new UnicodeString($optionDescription))->ascii();
        $this->write(
            $name."\n".str_repeat($this->paragraphsChar, Helper::width($name))."\n\n"
            .$optionDescription
            .'- **Accept value**: '.($option->acceptValue() ? 'yes' : 'no')."\n"
            .'- **Is value required**: '.($option->isValueRequired() ? 'yes' : 'no')."\n"
            .'- **Is multiple**: '.($option->isArray() ? 'yes' : 'no')."\n"
            .'- **Is negatable**: '.($option->isNegatable() ? 'yes' : 'no')."\n"
            .'- **Default**: ``'.str_replace("\n", '', var_export($option->getDefault(), true)).'``'."\n"
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputDefinition $definition
     * @param mixed[] $options
     */
    protected function describeInputDefinition($definition, $options = [])
    {
        if ($showArguments = ((bool) $definition->getArguments())) {
            $this->write("Arguments\n".str_repeat($this->subsubsectionChar, 9))."\n\n";
            foreach ($definition->getArguments() as $argument) {
                $this->write("\n\n");
                $this->describeInputArgument($argument);
            }
        }

        if ($nonDefaultOptions = $this->getNonDefaultOptions($definition)) {
            if ($showArguments) {
                $this->write("\n\n");
            }

            $this->write("Options\n".str_repeat($this->subsubsectionChar, 7)."\n\n");
            foreach ($nonDefaultOptions as $option) {
                $this->describeInputOption($option);
                $this->write("\n");
            }
        }
    }

    /**
     * @param \Symfony\Component\Console\Command\Command $command
     * @param mixed[] $options
     */
    protected function describeCommand($command, $options = [])
    {
        if ($options['short'] ?? false) {
            $this->write(
                '``'.$command->getName()."``\n"
                .str_repeat($this->subsectionChar, Helper::width($command->getName()))."\n\n"
                .($command->getDescription() ? $command->getDescription()."\n\n" : '')
                ."Usage\n".str_repeat($this->paragraphsChar, 5)."\n\n"
                .array_reduce($command->getAliases(), static function ($carry, $usage) {
                    return $carry.'- ``'.$usage.'``'."\n";
                })
            );

            return;
        }

        $command->mergeApplicationDefinition(false);

        foreach ($command->getAliases() as $alias) {
            $this->write('.. _'.$alias.":\n\n");
        }
        $this->write(
            $command->getName()."\n"
            .str_repeat($this->subsectionChar, Helper::width($command->getName()))."\n\n"
            .($command->getDescription() ? $command->getDescription()."\n\n" : '')
            ."Usage\n".str_repeat($this->subsubsectionChar, 5)."\n\n"
            .array_reduce(array_merge([$command->getSynopsis()], $command->getAliases(), $command->getUsages()), static function ($carry, $usage) {
                return $carry.'- ``'.$usage.'``'."\n";
            })
        );

        if ($help = $command->getProcessedHelp()) {
            $this->write("\n");
            $this->write($help);
        }

        $definition = $command->getDefinition();
        if ($definition->getOptions() || $definition->getArguments()) {
            $this->write("\n\n");
            $this->describeInputDefinition($definition);
        }
    }

    /**
     * @param \Symfony\Component\Console\Application $application
     * @param mixed[] $options
     */
    protected function describeApplication($application, $options = [])
    {
        $description = new ApplicationDescription($application, $options['namespace'] ?? null);
        $title = $this->getApplicationTitle($application);

        $this->write($title."\n".str_repeat($this->partChar, Helper::width($title)));
        $this->createTableOfContents($description, $application);
        $this->describeCommands($application, $options);
    }

    /**
     * @param \Symfony\Component\Console\Application $application
     */
    private function getApplicationTitle($application)
    {
        if ('UNKNOWN' === $application->getName()) {
            return 'Console Tool';
        }
        if ('UNKNOWN' !== $application->getVersion()) {
            return sprintf('%s %s', $application->getName(), $application->getVersion());
        }

        return $application->getName();
    }

    /**
     * @param mixed[] $options
     */
    private function describeCommands($application, $options)
    {
        $title = 'Commands';
        $this->write("\n\n$title\n".str_repeat($this->chapterChar, Helper::width($title))."\n\n");
        foreach ($this->visibleNamespaces as $namespace) {
            if ('_global' === $namespace) {
                $commands = $application->all('');
                $this->write('Global'."\n".str_repeat($this->sectionChar, Helper::width('Global'))."\n\n");
            } else {
                $commands = $application->all($namespace);
                $this->write($namespace."\n".str_repeat($this->sectionChar, Helper::width($namespace))."\n\n");
            }

            foreach ($this->removeAliasesAndHiddenCommands($commands) as $command) {
                $this->describeCommand($command, $options);
                $this->write("\n\n");
            }
        }
    }

    /**
     * @param \Symfony\Component\Console\Descriptor\ApplicationDescription $description
     * @param \Symfony\Component\Console\Application $application
     */
    private function createTableOfContents($description, $application)
    {
        $this->setVisibleNamespaces($description);
        $chapterTitle = 'Table of Contents';
        $this->write("\n\n$chapterTitle\n".str_repeat($this->chapterChar, Helper::width($chapterTitle))."\n\n");
        foreach ($this->visibleNamespaces as $namespace) {
            if ('_global' === $namespace) {
                $commands = $application->all('');
            } else {
                $commands = $application->all($namespace);
                $this->write("\n\n");
                $this->write($namespace."\n".str_repeat($this->sectionChar, Helper::width($namespace))."\n\n");
            }
            $commands = $this->removeAliasesAndHiddenCommands($commands);

            $this->write("\n\n");
            $this->write(implode("\n", array_map(static function ($commandName) {
                return sprintf('- `%s`_', $commandName);
            }, array_keys($commands))));
        }
    }

    /**
     * @param \Symfony\Component\Console\Input\InputDefinition $definition
     */
    private function getNonDefaultOptions($definition)
    {
        $globalOptions = [
            'help',
            'quiet',
            'verbose',
            'version',
            'ansi',
            'no-interaction',
        ];
        $nonDefaultOptions = [];
        foreach ($definition->getOptions() as $option) {
            // Skip global options.
            if (!\in_array($option->getName(), $globalOptions)) {
                $nonDefaultOptions[] = $option;
            }
        }

        return $nonDefaultOptions;
    }

    /**
     * @param \Symfony\Component\Console\Descriptor\ApplicationDescription $description
     */
    private function setVisibleNamespaces($description)
    {
        $commands = $description->getCommands();
        foreach ($description->getNamespaces() as $namespace) {
            try {
                $namespaceCommands = $namespace['commands'];
                foreach ($namespaceCommands as $key => $commandName) {
                    if (!\array_key_exists($commandName, $commands)) {
                        // If the array key does not exist, then this is an alias.
                        unset($namespaceCommands[$key]);
                    } elseif ($commands[$commandName]->isHidden()) {
                        unset($namespaceCommands[$key]);
                    }
                }
                if (!$namespaceCommands) {
                    // If the namespace contained only aliases or hidden commands, skip the namespace.
                    continue;
                }
            } catch (\Exception $exception) {
            }
            $this->visibleNamespaces[] = $namespace['id'];
        }
    }

    /**
     * @param mixed[] $commands
     */
    private function removeAliasesAndHiddenCommands($commands)
    {
        foreach ($commands as $key => $command) {
            if ($command->isHidden() || \in_array($key, $command->getAliases(), true)) {
                unset($commands[$key]);
            }
        }
        unset($commands['completion']);

        return $commands;
    }
}
