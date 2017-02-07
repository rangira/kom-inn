<?php

namespace app;

class Cli extends \Pimple\Container
{

    public function run(callable $task) {
        return $task($this);
    }

    public function debug()
    {
        if (!$this->debug) return;
        $args = func_get_args();
        call_user_func_array([$this, 'out'], $args);
    }

    public function verbose()
    {
        if ($this->offsetGet('verbose')) {
            $args = func_get_args();
            call_user_func_array([$this, 'out'], $args);
        }
    }

    public function out()
    {
        $args = func_get_args();
        foreach ($args as $d) {
            switch (true) {
                case (is_string($d)) :
                    echo $d;
                    break;
                case (is_array($d)) :
                    print_r($d);
                    break;
                default:
                    var_dump($d);
                    break;
            }
            echo PHP_EOL;
        }
    }

    public function error()
    {
        $args = func_get_args();
        foreach ($args as $d) {
            switch (true) {
                case (is_string($d)) :
                    echo $d;
                    break;
                case (is_array($d)) :
                    print_r($d);
                    break;
                default:
                    var_dump($d);
                    break;
            }
            echo PHP_EOL;
        }
    }

    /**
     * Grab console command arguments and turn them into a cleaned options array.
     *
     * By default will check for these reserved options:
     *
     * -h --help
     * -d --dry
     * -D --DEBUG
     * -m: --max:
     * -l: --limit:
     * -o: --offset:
     *
     * You can add more options in addition
     *
     * Example: get_console_commands('fa:j', array('force', 'always:'), array('f'=>'force'), array('a'=>'always'))
     *
     * Note: If you use a modifier character without a word version, the 'option' will only be available
     * if the argument is given, but the value will be false.
     *
     * @param  string $customCharacters  string of characters to accept as -a -b, suffix with : to take argument
     * @param  array  $customWords   array of words to accept as --always --before, suffix with : to take argument
     * @param  array  $existToWords  array of single character to word for combining to word
     * @param  array  $valueToWords  array of single character to word for combining to word
     * @return array
     */
    public static function get_console_commands($customCharacters = '', $customWords = [], $existToWords = [], $valueToWords = []) : array
    {
        $keyWords = [
            // options
            'limit:','offset:', 'max:',
            // modifiers
            'dry', 'help', 'verbose', 'DEBUG'
        ];
        foreach ($customWords as $word) array_push($keyWords, $word);

        $commands = getopt('hvdDtm:l:o:' . $customCharacters, $keyWords);

        foreach (['d'=>'dry', 't'=>'tasks', 'v'=>'verbose', 'h'=>'help', 'D' => 'DEBUG'] + $existToWords as $short => $long) {
            $commands[$long] = array_key_exists($short, $commands) ? true : isset($commands[$long]); unset($commands[$short]);
        }
        foreach (['l'=>'limit', 'o'=>'offset', 'm'=>'max'] + $valueToWords as $short => $long) {
            if (array_key_exists($short, $commands)) {
                $commands[$long] = $commands[$short];
                unset($commands[$short]);
            }
        }
        return $commands;
    }
}
