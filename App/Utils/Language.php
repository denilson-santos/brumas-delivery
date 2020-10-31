<?php
namespace App\Utils;

class Language {
    private $language;
    private $iniDicionary;

    public function __construct() {

        $this->language = DEFAULT_LANG;

        if (!empty($_SESSION['language']) && file_exists('languages/'.$_SESSION['language'].'.ini')) {
            $this->language = $_SESSION['language'];
        }

        // converte o conteudo do arquivo ini para um array
        $this->iniDicionary = parse_ini_file('languages/'.$this->language.'.ini');
    }

    public function getLanguage() {
        return $this->language;
    }

    public function getIniDicionary() {
        return $this->iniDicionary;
    }

    // recebe a palavra como 1 param e deixa por padrao atraves do param return com value false a mensagem em formato "echo", caso fique true o formato será retornado
    public function getWord($word, $returnType = false) {
        $text = $word;

        if (!empty($this->iniDicionary[$word])) {
            $text = $this->iniDicionary[$word];
        }

        if ($returnType) {
            return $text;
        } else {
            echo $text;
        }
    }
}