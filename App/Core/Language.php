<?php
namespace App\Core;

use App\Config\Config;

class Language {
    private $language;
    private $iniDicionary;

    public function __construct() {

        $config = new Config();

        $this->language = $config->getDefaultLang();

        if (!empty($_SESSION['language']) && file_exists('languages/'.$_SESSION['language'].'.ini')) {
            $this->language = $_SESSION['language'];
        }

        // converte o conteudo do arquivo ini para um array
        $this->iniDicionary = parse_ini_file('languages/'.$this->language.'.ini');
    }

    // recebe a palavra como 1 param e deixa por padrao atraves do param return com value false a mensagem em formato "echo", caso fique true o formato será retornado
    public function get($word, $returnType = false) {
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