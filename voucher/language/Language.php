<?php

/**
 * Class Language
 */
class Language
{

    private $supportedLanguages;
    private $languageName;
    private $defaultLanguage;


    /**
     * Detects a suitable language from different sources
     *
     * Detection strategy in priority order
     * 1) detect language from GET variable
     * 2) detect language from session variable
     * 3) detect language from browser language
     * 4) use default language
     *
     * @return string - language
     */
    public function detectLanguage()
    {
        $supported = self::flatArray($this->getSupportedLanguages());
        $default = $this->getDefaultLanguage();


        $language = $default;
        if (!empty($_GET['lang'])) {
            //detect language from GET variable
            $get_lang = Locale::canonicalize($_GET['lang']);
            if (in_array($get_lang, $supported)) {
                $language = $get_lang;
                //print "detected from GET";
            }
        } else {
            $inSession = false;
            if (isset($_SESSION['language'])) {
                //detect language from session variable
                $session_lang = Locale::canonicalize($_SESSION['language']);
                if (in_array($session_lang, $supported)) {
                    $inSession = true;
                    $language = $session_lang;
                    //print "detected from Session";
                }
            }
            if (!$inSession) {
                //detect language from browser language
                // Tries to find out best available locale based on HTTP "Accept-Language" header
                $browser_locale = Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
                if (!empty($browser_locale)) {
                    // Searches the language tag list for the best match to the language
                    $language = Locale::lookup($supported, $browser_locale, false, $default);
                }
            }
        }
        $language = $this->arraySearchRecursive($language, $this->getSupportedLanguages());
        if (empty($language)) {
            $language = $default;
        }

        return $language;
    }

    /**
     * Search through an array
     *
     * @param $needle
     * @param $haystack
     * @param bool|true $strict - true for identical matches with the same type
     * @return int|null|string
     */
    function arraySearchRecursive($needle, $haystack, $strict = true)
    {
        $searchKey = null;
        foreach ($haystack as $key => $value) {
            if (($strict ? $value === $needle : $value == $needle) || (is_array($value) && !is_null(self::arraySearchRecursive($needle,
                        $value, $strict)))
            ) {
                $searchKey = $key;
            }
        }

        return $searchKey;
    }

    /**
     * Converts a multidimensional array to a list
     *
     * @param array $haystack - input array
     * @param null $needle - optional filter
     * @return array - result list
     */
    function flatArray($haystack = array(), $needle = null)
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($haystack));
        $elements = array();

        foreach ($iterator as $element) {
            if (is_null($needle) || $iterator->key() == $needle) {
                $elements[] = $element;
            }
        }

        return $elements;
    }

    /**
     * @return mixed
     */
    public function getSupportedLanguages()
    {
        if (!isset($this->supportedLanguages)) {
            $config = include($_SERVER['DOCUMENT_ROOT'] . '/../config/config.php');
            $this->supportedLanguages = $config['supported_languages'];
        }
        return $this->supportedLanguages;
    }

    /**
     * @return mixed
     */
    public function getLanguageName()
    {
        if (!isset($this->languageName)) {
            $config = include($_SERVER['DOCUMENT_ROOT'] . '/../config/config.php');
            $this->languageName = $config['language_names'];
        }
        return $this->languageName;
    }

    /**
     * @return mixed
     */
    public function getDefaultLanguage()
    {
        if (!isset($this->defaultLanguage)) {
            $config = include($_SERVER['DOCUMENT_ROOT'] . '/../config/config.php');
            $this->defaultLanguage = $config['default_language'];
        }
        return $this->defaultLanguage;
    }
}