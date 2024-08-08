<?php

/*
 * FileSender www.filesender.org
 *
 * Copyright (c) 2009-2012, AARNet, Belnet, HEAnet, SURFnet, UNINETT
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * *	Redistributions of source code must retain the above copyright
 * 	notice, this list of conditions and the following disclaimer.
 * *	Redistributions in binary form must reproduce the above copyright
 * 	notice, this list of conditions and the following disclaimer in the
 * 	documentation and/or other materials provided with the distribution.
 * *	Neither the name of AARNet, Belnet, HEAnet, SURFnet and UNINETT nor the
 * 	names of its contributors may be used to endorse or promote products
 * 	derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

// Require environment (fatal)
if (!defined('FILESENDER_BASE')) {
    die('Missing environment');
}

/**
 * Application version
 */
class Version
{
    /**
     * Code version
     */
    const CODE_VERSION = '2.49';
    
    /**
     * Version file path
     */
    const VERSION_FILE = '/scripts/upgrade/version.txt';
    
    /**
     * Get running version (data one)
     *
     * @return string
     */
    public static function running()
    {
        $file = FILESENDER_BASE.self::VERSION_FILE;
        
        if (!file_exists($file)) {
            return '0.0';
        }
        
        foreach (explode("\n", file_get_contents($file)) as $line) {
            $line = strtolower(trim($line));
            if (substr($line, 0, 1) == '#') {
                continue;
            }
            if (!preg_match('`^([0-9]+(?:\.[0-9]+(?:[abr]c?)?)?)`', $line, $m)) {
                continue;
            }
            return $m[1];
        }
        
        // Still here ?
        throw Exception('Version not in '.$file);
    }
    
    /**
     * Save updated version
     *
     * @param string $version to set
     */
    public static function updated()
    {
        $file = FILESENDER_BASE.self::VERSION_FILE;
        if ($fh = fopen($file, 'w')) {
            fwrite($fh, '# Do not edit this file !'."\n");
            fwrite($fh, self::CODE_VERSION);
            fclose($fh);
        } else {
            throw new CoreCannotWriteFileException($file);
        }
    }
    
    /**
     * Get code version
     *
     * @return string
     */
    public static function code()
    {
        return self::CODE_VERSION;
    }
    
    /**
     * Get version (always code one)
     *
     * @return string
     */
    public static function get()
    {
        return self::code();
    }
    
    /**
     * Compare versions
     *
     * @return int
     */
    public static function compare()
    {
        $code = strtolower(self::code());
        $running = strtolower(self::running());
        
        return version_compare($running, $code);
    }
}
