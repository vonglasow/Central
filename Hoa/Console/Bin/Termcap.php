<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2014, Ivan Enderlin. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace {

from('Hoa')

/**
 * \Hoa\Console
 */
-> import('Console.~');

}

namespace Hoa\Console\Bin {

/**
 * Class Hoa\Console\Bin\Termcap.
 *
 * Get terminal capabilities.
 *
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2014 Ivan Enderlin.
 * @license    New BSD License
 */

class Termcap extends \Hoa\Console\Dispatcher\Kit {

    /**
     * Options description.
     *
     * @var \Hoa\Console\Bin\Termcap array
     */
    protected $options = array(
        array('terminal', \Hoa\Console\GetOption::NO_ARGUMENT,       't'),
        array('file',     \Hoa\Console\GetOption::NO_ARGUMENT,       'f'),
        array('has',      \Hoa\Console\GetOption::REQUIRED_ARGUMENT, 'H'),
        array('count',    \Hoa\Console\GetOption::REQUIRED_ARGUMENT, 'c'),
        array('get',      \Hoa\Console\GetOption::REQUIRED_ARGUMENT, 'g'),
        array('booleans', \Hoa\Console\GetOption::NO_ARGUMENT,       'b'),
        array('numbers',  \Hoa\Console\GetOption::NO_ARGUMENT,       'n'),
        array('strings',  \Hoa\Console\GetOption::NO_ARGUMENT,       's'),
        array('help',     \Hoa\Console\GetOption::NO_ARGUMENT,       'h'),
        array('help',     \Hoa\Console\GetOption::NO_ARGUMENT,       '?')
    );



    /**
     * The entry method.
     *
     * @access  public
     * @return  int
     */
    public function main ( ) {

        $tput = \Hoa\Console::getTput();

        while(false !== $c = $this->getOption($v)) switch($c) {

            case 't':
                echo $tput->getTerm();
              return;

            case 'f':
                echo $tput->getTerminfo();
              return;

            case 'H':
                echo $tput->has($v) ? 1 : 0;
              return;

            case 'c':
                echo $tput->count($v);
              return;

            case 'g':
                echo $tput->get($v);
              return;

            case 'b':
                $informations = $tput->getInformations();
                static::format($informations['booleans']);
              return;

            case 'n':
                $informations = $tput->getInformations();
                static::format($informations['numbers']);
              return;

            case 's':
                $informations = $tput->getInformations();
                static::format($informations['strings']);
              return;

            case '__ambiguous':
                $this->resolveOptionAmbiguity($v);
              break;

            case 'h':
            case '?':
            default:
                return $this->usage();
              break;
        }

        return $this->usage();
    }

    /**
     * The command usage.
     *
     * @access  public
     * @return  int
     */
    public function usage ( ) {

        echo 'Usage   : console:termcap', "\n",
             'Options :', "\n",
             $this->makeUsageOptionsList(array(
                 't'    => 'Get terminal name.',
                 'f'    => 'Get path to the terminfo file.',
                 'H'    => 'Get value of a boolean capability.',
                 'c'    => 'Get value of a number capability.',
                 'g'    => 'Get value of a string capability.',
                 'b'    => 'Get all boolean capabilites.',
                 'n'    => 'Get all number capabilites.',
                 's'    => 'Get all string capabilites.',
                 'help' => 'This help.'
             )), "\n",
             'Examples:', "\n",
             '    $ hoa console:termcap --count max_colors', "\n",
             '    $ TERM=vt200 hoa console:termcap --has back_color_erase', "\n";

        return;
    }

    /**
     * Format a collection of informations.
     *
     * @access  public
     * @param   array  $data    Data.
     * @return  void
     */
    public static function format ( Array $data ) {

        $max = 0;

        foreach($data as $key => $_)
            if($max < ($handle = strlen($key)))
                $max = $handle;

        $format = '%-' . ($max + 1) . 's: %s' . "\n";

        foreach($data as $key => $value)
            printf(
                $format,
                $key,
                is_bool($value)
                    ? ($value ? 'true' : 'false')
                    : (is_string($value)
                         ? str_replace(
                               array(
                                   "\033",
                                   "\n",
                                   ord(0xa),
                                   "\r",
                                   "\b",
                                   "\f",
                                   "\0"
                               ),
                               array(
                                   '\e',
                                   '\n',
                                   '\l',
                                   '\r',
                                   '\b',
                                   '\f',
                                   '\0'
                               ),
                               $value
                           )
                         : $value)
            );

        return;
    }
}

}

__halt_compiler();
Terminal capabilities.