<?php
/**
 * Este codigo contiene
 * 
 *
 * @category   PHP_Analyzer_Standard
 */

 /** StandardAnalyzer_ */
 /* Depending on your circumstances, you may want to change the paths to meet your conventional / functional needs */

require_once 'Devnet/StandardAnalyzer/Analyzer/Standard.php';
require_once 'Devnet/StandardAnalyzer/TokenFilter/SpanishStemmer.php';

 /** Zend_Search_Lucene_Analysis_Analyzer_Standard */
require_once 'Zend/Search/Lucene/Analysis/Analyzer.php';
/** Zend_Search_Lucene_Analysis_TokenFilter_LowerCase */
require_once 'Zend/Search/Lucene/Analysis/TokenFilter/LowerCase.php';
/** Zend_Search_Lucene_Analysis_TokenFilter_StopWords */
require_once 'Zend/Search/Lucene/Analysis/TokenFilter/StopWords.php';

class StandardAnalyzer_Analyzer_Standard_Spanish
    extends StandardAnalyzer_Analyzer_Standard
{
    private $_stopWords = array ("de", "la", "que", "el", "en", "y", "a", "los", "del", "se", "las",
                                 "por", "un", "para", "con", "no", "una", "su", "al", "es", "lo",
                                 "como", "mas", "más", "pero", "sus", "le", "ya", "o", "fue", "este",
                                 "ha", "sí", "si", "porque", "esta", "son", "entre", "está", "cuando", "muy",
                                 "sin", "sobre", "ser", "tiene", "también", "tambien", "me", "hasta",
                                 "donde", "han", "quien", "están", "estado", "desde", "todo", "nos", "durante",
                                 "estados", "todos", "uno", "les", "ni", "contra", "otros", "fueron", "ese", "eso",
                                 "había", "ante", "ellos", "e", "esto", "mi", "antes", "algunos", "que", "unos",
                                 "yo", "otro", "otras", "otra", "él", "el", "tanto", "esa", "estos", "mucho",
                                 "quienes", "nada", "muchos", "cual", "sea", "poco", "ella", "estar", "haber", "estas",
                                 "estaba", "estamos", "algunas", "algo", "nosotros", "mi", "mis", "tu", "te", "ti",
                                 "nosotras", "vosotros", "vosotras", "os", "mío", "mía", "mios", "mías", "tuyo", "tuya",
                                 "tuyos", "tuyas", "suyo", "suya", "suyos", "suyas", "nuestro", "nuestra",
                                 "nuestros", "nuestras", "vuestro", "vuestra", "vuestros", "vuestras", "esos", "esas",
                                 "estoy", "estas", "esta", "estamos", "estais", "estan", "este", "estes", "estemos",
                                 "esteis", "esten", "estaré", "estarás", "estará", "estaremos", "estaréis", "estarán",
                                 "estaría", "estarías", "estaríamos", "estarías", "estarían", "estaba", "estabas",
                                 "estábamos", "estabais", "estaban", "estuve", "estuviste", "estuvo", "estuvimos",
                                 "estuvisteis", "estuvieron", "estuviera", "estuvieras", "estuviéramos", "estuvierais",
                                 "estuvieran", "estuviese", "estuvieses", "estuviésemos", "estuvieseis", "estuviesen",
                                 "estando", "estado", "estada", "estados", "estadas", "estad",
                                 "ha", "si", "will", "with");

/*he
has
ha
hemos
habéis
han
haya
hayas
hayamos
hayáis
hayan
habré
habrás
habrá
habremos
habréis
habrán
habría
habrías
habríamos
habríais
habrían
había
habías
habíamos
habíais
habían
hube
hubiste
hubo
hubimos
hubisteis
hubieron
hubiera
hubieras
hubiéramos
hubierais
hubieran
hubiese
hubieses
hubiésemos
hubieseis
hubiesen
habiendo
habido
habida
habidos
habidas

               | forms of ser, to be (not including the infinitive):
soy
eres
es
somos
sois
son
sea
seas
seamos
seáis
sean
seré
serás
será
seremos
seréis
serán
sería
serías
seríamos
seríais
serían
era
eras
éramos
erais
eran
fui
fuiste
fue
fuimos
fuisteis
fueron
fuera
fueras
fuéramos
fuerais
fueran
fuese
fueses
fuésemos
fueseis
fuesen
siendo
sido
  |  sed also means 'thirst'

               | forms of tener, to have (not including the infinitive):
tengo
tienes
tiene
tenemos
tenéis
tienen
tenga
tengas
tengamos
tengáis
tengan
tendré
tendrás
tendrá
tendremos
tendréis
tendrán
tendría
tendrías
tendríamos
tendríais
tendrían
tenía
tenías
teníamos
teníais
tenían
tuve
tuviste
tuvo
tuvimos
tuvisteis
tuvieron
tuviera
tuvieras
tuviéramos
tuvierais
tuvieran
tuviese
tuvieses
tuviésemos
tuvieseis
tuviesen
teniendo
tenido
tenida
tenidos
tenidas
tened
*/

    public function __construct()
    {
        $this->addFilter(new Zend_Search_Lucene_Analysis_TokenFilter_LowerCaseUtf8());
        $this->addFilter(new Zend_Search_Lucene_Analysis_TokenFilter_StopWords($this->_stopWords));
        $this->addFilter(new StandardAnalyzer_Analysis_TokenFilter_SpanishStemmer());        
    }

    /**
     * Retorna solo las palabras relevantes para los tags
     *
     * @param varchar $srcToken
     * @return array
     */
    public function getRelevantes($srcToken) {
        if (in_array($srcToken, $this->_stopWords)) {
            return false;
        } else {
            return true;
        }
    }

}

