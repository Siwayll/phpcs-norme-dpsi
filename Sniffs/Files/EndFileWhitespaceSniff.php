<?php
/**
 * DPSI_Sniffs_Files_EndFileWhitespaceSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2011 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * DPSI_Sniffs_Files_EndFileWhitespaceSniff.
 *
 * Checks that there is a single blank line at the end of PHP files.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2011 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.3.5
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class DPSI_Sniffs_Files_EndFileWhitespaceSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_OPEN_TAG);

    }//end register()


    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        // We are only interested if this is the first open tag.
        if ($stackPtr !== 0) {
            if ($phpcsFile->findPrevious(T_OPEN_TAG, ($stackPtr - 1)) !== false) {
                return;
            }
        }

        // Skip to the end of the file.
        $tokens   = $phpcsFile->getTokens();
        $stackPtr = ($phpcsFile->numTokens - 1);

        // Go looking for the last non-empty line.
        $lastLine = $tokens[$stackPtr]['line'];
        while ($tokens[$stackPtr]['code'] === T_WHITESPACE) {
            $stackPtr--;
        }

        $lastCodeLine = $tokens[$stackPtr]['line'];
        $blankLines   = $lastLine - $lastCodeLine;
        if ($blankLines === 0) {
            $error = 'Expected 1 blank line at end of file; 0 found';
            $data  = array($blankLines);
            $phpcsFile->addError($error, $stackPtr, 'NotFound', $data);
        } else if ($blankLines > 1) {
            $error = 'Expected 1 blank line at end of file; "%s" found';
            $data  = array($blankLines);
            $phpcsFile->addError($error, $stackPtr, 'TooMany', $data);
        }
    }//end process()


}//end class

?>
