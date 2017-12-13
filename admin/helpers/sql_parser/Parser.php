<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 *
 * PHP versions 5
 *
 * LICENSE: This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; If not, see <http://www.gnu.org/licenses/>.
 *
 * @todo      Refactor sentinel conditions to show flow
 * @todo      Document EBNF of what each major block is actually doing
 * @todo      Document getToken/pushBack assumptions of each major block
 * @todo      Refactor into Expression classes, keeping the Tokenizer the same,
 *            outputting the same parse tree
 * @todo      we need to remember spaces, this is esential to determine whether
 *            it is a function call: "function("
 *            or just some expression: "ident ("
 * @category  Database
 * @package   SQL_Parser
 * @author    Erich Enke <erich.Enke@gmail.com>
 * @author    Brent Cook <busterbcook@yahoo.com>
 * @author    Jason Pell <jasonpell@hotmail.com>
 * @author    Lauren Matheson <inan@canada.com>
 * @author    John Griffin <jgriffin316@netscape.net>
 * @copyright 2002-2004 Brent Cook
 *            2005 Erich Enke
 * @license   http://www.gnu.org/licenses/lgpl.html GNU Lesser GPL 3
 * @version   CVS: $Id: Parser.php 295082 2010-02-15 06:23:04Z clockwerx $
 * @link      http://pear.php.net/package/SQL_Parser
 * @since     File available since Release 0.1.0
 */

/**
 *
 */
require_once dirname(__FILE__) . '/Parser/Lexer.php';
require_once dirname(__FILE__) . '/sql_parser/src/PHPSQLParser.php';

/**
 * A sql parser
 *
 * @category  Database
 * @package   SQL_Parser
 * @author    Brent Cook <busterbcook@yahoo.com>
 * @copyright 2002-2004 Brent Cook
 *            2005 Erich Enke
 * @license   http://www.gnu.org/licenses/lgpl.html GNU Lesser GPL 3
 * @version   Devel: 0.5
 * @link      http://pear.php.net/package/SQL_Parser
 * @since     File available since Release 0.1.0
 */
class SQL_Parser
{
    /**
     * @var    SQL_Parser_Lexer
     * @access public
     */
    public $lexer;

    /**
     * @var    string
     * @access public
     */
    public $token;

    /**
     * @var    array
     * @access public
     */
    public $functions = array();

    /**
     * @var    array
     * @access public
     */
    public $types = array();

    /**
     * @var    array
     * @access public
     */
    public $symbols = array();

    /**
     * @var    array
     * @access public
     */
    public $operators = array();

    /**
     * @var    array
     * @access public
     */
    public $synonyms = array();

    /**
     * @var    array
     * @access public
     */
    public $lexeropts = array();

    /**
     * @var    array
     * @access public
     */
    public $parseropts = array();

    /**
     * @var    array
     * @access public
     */
    public $comments = array();

    /**
     * @var    array
     * @access public
     */
    public $quotes = array();

    /**
     * @var    array
     * @access public
     */
    static public $dialects = array(
        'ANSI',
        'MySQL',
    );

    public $notes = array();

    /**
     *
     */
    const DIALECT_ANSI = 'ANSI';

    /**
     *
     */
    const DIALECT_MYSQL = 'MySQL';

    // {{{ function SQL_Parser($string = null, $dialect = 'ANSI')
    /**
     * Constructor
     *
     * @param string $string the sql_parser query to parse
     * @param string $dialect the sql_parser dialect
     * @uses  SQL_Parser::setDialect()
     * @uses  SQL_Parser::$lexer      W to create it
     * @uses  SQL_Parser::$symbols    R
     * @uses  SQL_Parser::$lexeropts  R
     * @uses  SQL_Parser_Lexer        to create an Object
     * @uses  SQL_Parser_Lexer::$symbols W to set it
     * @uses  is_string()
     */
    public function __construct($string = null, $dialect = 'ANSI')
    {
        $this->setDialect($dialect);

        if (is_string($string)) {
            $this->initLexer($string);
        }
    }
    // }}}

    function initLexer($string)
    {
        // Initialize the Lexer with a 3-level look-back buffer
        $this->lexer = new SQL_Parser_Lexer($string, 3, $this->lexeropts);
        $this->lexer->symbols  =& $this->symbols;
        $this->lexer->comments =& $this->comments;
        $this->lexer->quotes   =& $this->quotes;
    }

    // {{{ function setDialect($dialect)
    /**
     * loads sql_parser dialect specific data
     *
     * @param string $dialect the sql_parser dialect to use
     * @return mixed true on success, otherwise Error
     * @uses  in_array()
     * @uses  SQL_Parser::$dialects   R
     * @uses  SQL_Parser::$types      W to set it
     * @uses  SQL_Parser::$functions  W to set it
     * @uses  SQL_Parser::$operators  W to set it
     * @uses  SQL_Parser::$commands   W to set it
     * @uses  SQL_Parser::$synonyms   W to set it
     * @uses  SQL_Parser::$symbols    W to set it
     * @uses  SQL_Parser::$lexeropts  W to set it
     * @uses  SQL_Parser::$parseropts W to set it
     * @uses  SQL_Parser::raiseError()
     */
    public function setDialect($dialect)
    {
        if (! in_array($dialect, SQL_Parser::$dialects)) {
            throw new Exception('Unknown sql_parser dialect:' . $dialect);
        }

        include dirname(__FILE__) .'/Parser/Dialect/' . $dialect . '.php';
        $this->types      = array_flip($dialect['types']);
        $this->functions  = array_flip($dialect['functions']);
        $this->operators  = array_flip($dialect['operators']);
        $this->commands   = array_flip($dialect['commands']);
        $this->synonyms   = $dialect['synonyms'];
        $this->symbols    = array_merge(
            $this->types,
            $this->functions,
            $this->operators,
            $this->commands,
            array_flip($dialect['reserved']),
            array_flip($dialect['conjunctions']));
        $this->lexeropts  = $dialect['lexeropts'];
        $this->parseropts = $dialect['parseropts'];
        $this->comments   = $dialect['comments'];
        $this->quotes     = $dialect['quotes'];

        return true;
    }
    // }}}

    // {{{ getParams(&$values, &$types)
    /**
     * extracts parameters from a function call
     *
     * this function should be called if an opening brace is found,
     * so the first call to $this->getTok() will return first param
     * or the closing )
     *
     * @param array   &$values to set it
     * @param array   &$types  to set it
     * @param integer $i       position
     * @return mixed true on success, otherwise Error
     * @uses  SQL_Parser::$token  R
     * @uses  SQL_Parser::$lexer  R
     * @uses  SQL_Parser::getTok()
     * @uses  SQL_Parser::isVal()
     * @uses  SQL_Parser::raiseError()
     * @uses  SQL_Parser_Lexer::$tokText R
     */
    public function getParams(&$values, &$types, $i = 0)
    {
        $values = array();
        $types  = array();

        // the first opening brace is already fetched
        // function(
        $open_braces = 1;

        while ($open_braces > 0) {
            $this->getTok();

            if ($this->token === ')') {
                $open_braces--;
            } elseif ($this->token === '(') {
                $open_braces++;
            } elseif ($this->token === ',') {
                $i++;
            } elseif (isset($values[$i])) {
                $values[$i] .= '' . $this->lexer->tokText;
                $types[$i]  .= $this->token;
            } else {
                $values[$i] = $this->lexer->tokText;
                $types[$i]  = $this->token;
            }
        }

        return true;
    }
    // }}}

    // {{{ raiseError($message)
    /**
     *
     * @param string $message error message
     * @return Error
     * @uses  is_null()
     * @uses  substr()
     * @uses  strlen()
     * @uses  str_repeat()
     * @uses  abs()
     * @uses  SQL_Parser::$lexer      R
     * @uses  SQL_Parser::$token      R
     * @uses  SQL_Parser_Lexer::$string   R
     * @uses  SQL_Parser_Lexer::$lineBegin R
     * @uses  SQL_Parser_Lexer::$stringLen R
     * @uses  SQL_Parser_Lexer::$lineNo   R
     * @uses  SQL_Parser_Lexer::$tokText  R
     * @uses  SQL_Parser_Lexer::$tokPtr   R
     */
    public function raiseError($message)
    {
        $end = 0;
        if ($this->lexer->string != '') {
            while ($this->lexer->lineBegin + $end < $this->lexer->stringLen
             && $this->lexer->string{$this->lexer->lineBegin + $end} != "\n") {
                $end++;
            }
        }

        $message = 'Parse error: ' . $message . ' on line ' .
            ($this->lexer->lineNo + 1) . "\n";
        $message .= substr($this->lexer->string, $this->lexer->lineBegin, $end);
        $message .= "\n";
        $length   = is_null($this->token) ? 0 : strlen($this->lexer->tokText);
        $message .= str_repeat(' ', abs($this->lexer->tokPtr -
            $this->lexer->lineBegin - $length)) . "^";
        $message .= ' found: "' . $this->lexer->tokText . '"';

        throw new Exception($message);
    }
    // }}}

    // {{{ isType()
    /**
     * Returns true if current token is a variable type name, otherwise false
     *
     * @uses  SQL_Parser::$types  R
     * @uses  SQL_Parser::$token  R
     * @return  boolean  true if current token is a variable type name
     */
    public function isType()
    {
        return isset($this->types[$this->token]);
    }
    // }}}

    // {{{ isVal()
    /**
     * Returns true if current token is a value, otherwise false
     *
     * @uses  SQL_Parser::$token  R
     * @return  boolean  true if current token is a value
     */
    public function isVal()
    {
        return ($this->token == 'real_val' ||
        $this->token == 'int_val' ||
        $this->token == 'text_val' || strtolower($this->lexer->tokText) == 'true'
                || strtolower($this->lexer->tokText)== 'false'||
        $this->token == 'null');
    }
    // }}}

    // {{{ isFunc()
    /**
     * Returns true if current token is a function, otherwise false
     *
     * @uses  SQL_Parser::$token  R
     * @uses  SQL_Parser::$functions R
     * @return  boolean  true if current token is a function
     */
    public function isFunc()
    {
        return isset($this->functions[$this->token]);
    }
    // }}}

    // {{{ isCommand()
    /**
     * Returns true if current token is a command, otherwise false
     *
     * @uses  SQL_Parser::$token  R
     * @uses  SQL_Parser::$commands R
     * @return  boolean  true if current token is a command
     */
    public function isCommand()
    {
        return isset($this->commands[$this->token]);
    }
    // }}}

    // {{{ isReserved()
    /**
     * Returns true if current token is a reserved word, otherwise false
     *
     * @uses  SQL_Parser::$token  R
     * @uses  SQL_Parser::$symbols R
     * @return  boolean  true if current token is a reserved word
     */
    public function isReserved()
    {
        return isset($this->symbols[$this->token]);
    }
    // }}}

    // {{{ isOperator()
    /**
     * Returns true if current token is an operator, otherwise false
     *
     * @uses  SQL_Parser::$token  R
     * @uses  SQL_Parser::$operators R
     * @return  boolean  true if current token is an operator
     */
    public function isOperator()
    {
        return isset($this->operators[$this->token]);
    }
    // }}}

    // {{{ getTok()
    /**
     * retrieves next token
     *
     * @uses  SQL_Parser::$token  W to set it
     * @uses  SQL_Parser::$lexer  R
     * @uses  SQL_Parser_Lexer::lex()
     * @return void
     */
    public function getTok()
    {
        $this->token = strtolower($this->lexer->lex());
        //echo $this->token . "\t" . $this->lexer->tokText . "\n";
    }
    // }}}

    public function goToComma()
    {
        while($this->token != ','){
            $this->getTok();
        }
    }
    public function goToENDOfStatement()
    {
        while($this->token != ';'){
            $this->getTok();
        }
    }

    public function parseTableFactor()
    {
        if ($this->token == '(') {
            $this->getTok();
            $tree = $this->parseTableReference();
            // closing )
            $this->getTok();
            return $tree;
        } elseif ($this->token == 'select') {
            return $this->parseSelect();
        } else {
            return $this->parseIdentifier('table');
        }
    }
    public function readStatement()
    {
        $statment = "";
        while ($this->token != ';'){
            $statment = $statment . " " . $this->lexer->tokText;
            $this->getTok();
        }
        return $statment;
    }
    // {{{ parse($string)
    /**
     *
     * @return  array   parsed data
     * @uses  SQL_Parser::$lexeropts
     * @uses  SQL_Parser::$lexer
     * @uses  SQL_Parser::$symbols
     * @uses  SQL_Parser::$token
     * @uses  SQL_Parser::raiseError()
     * @uses  SQL_Parser::getTok()
     * @uses  SQL_Parser::parseSelect()
     * @uses  SQL_Parser::parseUpdate()
     * @uses  SQL_Parser::parseInsert()
     * @uses  SQL_Parser::parseDelete()
     * @uses  SQL_Parser::parseCreate()
     * @uses  SQL_Parser::parseDrop()
     * @uses  SQL_Parser_Lexer
     * @uses  SQL_Parser_Lexer::$symbols
     * @access  public
     */
    public function parseQuery()
    {
        $tree = array();

        // get query action
        $this->getTok();

        while (1) {
            $branch = array();
            $branchkeys="default";
			$tte = $this->token;
            switch (strtolower($tte)) {
				
                case null:
                    // null == end of string
                    break;
                case 'select':
                    $branch = (new PHPSQLParser($this->readStatement()))->parsed;
                    $branchkeys = "select";
                    break;
                case 'update':
                    $branch = (new PHPSQLParser($this->readStatement()))->parsed;
                    $branchkeys = "update";
                    break;
                case 'insert':
                    $branch = (new PHPSQLParser($this->readStatement()))->parsed;
                    $branchkeys = "insert";
                    break;
                case 'delete':
                    $branch = (new PHPSQLParser($this->readStatement()))->parsed;
                    $branchkeys = "delete";
                    break;
                case 'create':
                    $branch = (new PHPSQLParser($this->readStatement()))->parsed;
                    $branchkeys = "create";
                    break;
                case 'set':
                    $branch = (new PHPSQLParser($this->readStatement()))->parsed;
                    $branchkeys = "set";
                    break;
                case "alter":
                    $branch = (new PHPSQLParser($this->readStatement()))->parsed;
                    $branchkeys = "alter";
                    break;
                case "truncate":
                    $branch = (new PHPSQLParser($this->readStatement()))->parsed;
                    $branchkeys = "truncate";
                    break;
                case "replace":
                    $branch = (new PHPSQLParser($this->readStatement()))->parsed;
                    $branchkeys = "replace";
                    break;
                case 'rename':
                    $branch = (new PHPSQLParser($this->readStatement()))->parsed;
                    $branchkeys = "rename";
                    break;
                case 'drop':
                    $branch = (new PHPSQLParser($this->readStatement()))->parsed;
                    $branchkeys = "drop";
                    break;
                case 'explain':
                    $branch = (new PHPSQLParser($this->readStatement()))->parsed;
                    $branchkeys = "explain";
                    break;
                case 'describe':
                    $branch = (new PHPSQLParser($this->readStatement()))->parsed;
                    $branchkeys = "describe";
                    break;
                case 'unlock':
                    $this->getTok();
                    if ($this->token != 'tables') {
                        $this->raiseError('Expected tables');
                    }

                    $this->getTok();
                    $branch  = array('command' => 'unlock tables');
                    $branchkeys = "unlock";
                    break;
                case 'lock':
                    $branch= $this->parseLock();
                    $branchkeys = "lock";
                    break;
                case '(':
                    $branch[] = $this->parseQuery();
                    if ($this->token != ')') {
                        $this->raiseError('Expected )');
                    }
                    $this->getTok();
                    break;
                default:
                    $this->raiseError('Unknown action: ' . $this->token);
            }
            $tree[$branchkeys][] = $branch;

            // another command separated with ; or a UNION
            if ($this->token == ';') {
               // $tree[] = ';';
                $this->getTok();
                if (! is_null($this->token)) {
                    continue;
                }
            }

            // another command separated with ; or a UNION
            if ($this->token == 'UNION') {
                $tree[] = 'UNION';
                $this->getTok();
                continue;
            }

            // end? unknown?
            break;
        }

        return $tree;
    }

    public function parse($string = null)
    {
        try {
            if (is_string($string)) {
                $this->initLexer($string);
            } elseif (! $this->lexer instanceof SQL_Parser_Lexer) {
                throw new Exception('No initial string specified');
                return array('empty' => true);
            }
        } catch (Exception $e) {
            return 'Caught exception on init: ' . $e->getMessage() . "\n";
        }

        try {
            $tree = $this->parseQuery();
            if (! is_null($this->token)) {
                $this->raiseError('Expected EOQ');
            }
        } catch (Exception $e) {
            $tree = "\n";
            $tree .= 'Caught exception: ' . $e->getMessage() . "\n";
            $tree .= 'in: ' . $e->getFile() . '#' . $e->getLine() . "\n";
            $tree .= 'from: ' . "\n" . $e->getTraceAsString();
            $tree .= "\n";
        }

        return $tree;
    }
    // }}}
}
