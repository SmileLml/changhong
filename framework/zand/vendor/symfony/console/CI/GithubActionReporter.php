<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\CI;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Utility class for Github actions.
 *
 * @author Maxime Steinhausser <maxime.steinhausser@gmail.com>
 */
class GithubActionReporter
{
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    /**
     * @see https://github.com/actions/toolkit/blob/5e5e1b7aacba68a53836a34db4a288c3c1c1585b/packages/core/src/command.ts#L80-L85
     */
    private const ESCAPED_DATA = [
        '%' => '%25',
        "\r" => '%0D',
        "\n" => '%0A',
    ];

    /**
     * @see https://github.com/actions/toolkit/blob/5e5e1b7aacba68a53836a34db4a288c3c1c1585b/packages/core/src/command.ts#L87-L94
     */
    private const ESCAPED_PROPERTIES = [
        '%' => '%25',
        "\r" => '%0D',
        "\n" => '%0A',
        ':' => '%3A',
        ',' => '%2C',
    ];

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function __construct($output)
    {
        $this->output = $output;
    }

    public static function isGithubActionEnvironment()
    {
        return false !== getenv('GITHUB_ACTIONS');
    }

    /**
     * Output an error using the Github annotations format.
     *
     * @see https://docs.github.com/en/free-pro-team@latest/actions/reference/workflow-commands-for-github-actions#setting-an-error-message
     * @param string $message
     * @param string|null $file
     * @param int|null $line
     * @param int|null $col
     */
    public function error($message, $file = null, $line = null, $col = null)
    {
        $this->log('error', $message, $file, $line, $col);
    }

    /**
     * Output a warning using the Github annotations format.
     *
     * @see https://docs.github.com/en/free-pro-team@latest/actions/reference/workflow-commands-for-github-actions#setting-a-warning-message
     * @param string $message
     * @param string|null $file
     * @param int|null $line
     * @param int|null $col
     */
    public function warning($message, $file = null, $line = null, $col = null)
    {
        $this->log('warning', $message, $file, $line, $col);
    }

    /**
     * Output a debug log using the Github annotations format.
     *
     * @see https://docs.github.com/en/free-pro-team@latest/actions/reference/workflow-commands-for-github-actions#setting-a-debug-message
     * @param string $message
     * @param string|null $file
     * @param int|null $line
     * @param int|null $col
     */
    public function debug($message, $file = null, $line = null, $col = null)
    {
        $this->log('debug', $message, $file, $line, $col);
    }

    /**
     * @param string $type
     * @param string $message
     * @param string|null $file
     * @param int|null $line
     * @param int|null $col
     */
    private function log($type, $message, $file = null, $line = null, $col = null)
    {
        // Some values must be encoded.
        $message = strtr($message, self::ESCAPED_DATA);

        if (!$file) {
            // No file provided, output the message solely:
            $this->output->writeln(sprintf('::%s::%s', $type, $message));

            return;
        }

        $this->output->writeln(sprintf('::%s file=%s,line=%s,col=%s::%s', $type, strtr($file, self::ESCAPED_PROPERTIES), strtr($line ?? 1, self::ESCAPED_PROPERTIES), strtr($col ?? 0, self::ESCAPED_PROPERTIES), $message));
    }
}
