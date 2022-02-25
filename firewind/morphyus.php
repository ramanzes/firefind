<?php
	require_once __DIR__.'/phpmorphy/src/common.php';

	class morphyus {
		private $phpmorphy     = null;
		private $regexp_word   = '/([a-zа-я0-9]+)/ui';
		private $regexp_entity = '/&([a-zA-Z0-9]+);/';

		public function __construct($language='ru_RU') {
			// $directory            = __DIR__.'/phpmorphy/dicts';
	//	  $language             = 'ru_RU';
			$options[ 'storage' ] = PHPMORPHY_STORAGE_FILE;

			$directory            = __DIR__.'/phpmorphy/dicts';
	//		$language            = 'en_EN';


			// Инициализация библиотеки //
			$this->phpmorphy      = new phpMorphy( $directory, $language, $options );
		}

		/**
		 * Разбивает текст на массив слов
		 *
		 * @param  {string}  content Исходный текст для выделения слов
		 * @param  {boolean} filter  Активирует фильтрацию HTML-тегов и сущностей
		 * @return {array}           Результирующий массив
		 */
		public function get_words( $content, $filter=true ) {
			// Фильтрация HTML-тегов и HTML-сущностей //
			if ( $filter ) {
				$content = strip_tags( $content );
				$content = preg_replace( $this->regexp_entity, ' ', $content );
			}

			// Перевод в верхний регистр //
			$content = mb_strtoupper( $content, 'UTF-8' );

			// Замена ё на е //
			$content = str_ireplace( 'Ё', 'Е', $content );

			// Выделение слов из контекста //
			preg_match_all( $this->regexp_word, $content, $words_src );
			return $words_src[ 1 ];
		}

		/**
		 * Находит леммы слова
		 *
		 * @param {string} word   Исходное слово
		 * @param {array|boolean} Массив возможных лемм слова, либо false
		 */
		public function lemmatize( $word ) {
			// Получение базовой формы слова //
			$lemmas = $this->phpmorphy->lemmatize( $word );
			return $lemmas;
		}

		public function getAllFormsWithGramInfo( $word ) {
			// mixed phpMorphy::getAllFormsWithGramInfo($word, $asText = true, $type = self::NORMAL)
			// Возвращает массив в формате
		 //
		 // array(
		 // 	// омоним №1
		 // 	array(
		 // 		'forms' => array(
		 // 		),
		 // 		'all' => array(
		 // 			массив содержит часть речи и граммемы для каждой формы из 'forms'. Граммемы разделены запятой. Часть речи отделена от граммем пробелом.
		 // 			например: ПРИЧАСТИЕ ДСТ,ЕД,ИМ,МР,НО,НП,ОД,ПРШ,СВ
		 // 		),
		 // 		'common' => строка содержащая общие для всех форм граммемы
		 // )
		 // )
		 //
		 // Данный метод рекомендуется использовать только для отладки. Для анализа используйте метод findWord(). Если $asText = true грамматическая информация возвращается в виде строки, как описано выше. Иначе в виде массива
			$Forms = $this->phpmorphy->getAllFormsWithGramInfo( $word );
			return $Forms;
		}

	public function findWord($word){
		return $this->phpmorphy->findWord($word);
	}



		// string getEncoding()
		//
		// Возвращает кодировку загруженного словаря.
		//
		// echo $this->phpmorphy->getEncoding();
		// // windows-1251 или utf-8 и т.п., в зависимость от словаря.
		//
		// string getLocale()
		//
		// Возвращает код языка. В формате ISO3166 код страны, символ '_', ISO639 код языка.
		//
		// echo $this->phpmorphy->getLocale();
		// // ru_RU или en_EN или uk_UA, в зависимости от словаря
		//



		public function getAncode( $word ) {
			// mixed phpMorphy::getAncode($word, $type = self::NORMAL)
			//
			// Возвращает анкоды для слова.
			$Forms = $this->phpmorphy->getAncode( $word );
			return $Forms;
		}
		public function getGramInfo( $word ) {
 // Возвращает грамматическую информацию для слова
			$Forms = $this->phpmorphy->getGramInfo( $word );
			return $Forms;
		}

		public function castFormByGramInfo( $word, $partOfSpeech, $grammems, $returnOnlyWord = false, $callback = null, $type = self::NORMAL ) {
		// mixed phpMorphy::castFormByGramInfo($word, $partOfSpeech, $grammems, $returnOnlyWord = false, $callback = null, $type = self::NORMAL)
		//
		// Приводит слово в заданную форму. $partOfSpeech – необходим только для прилагательных и глаголов т.к. только для этих частей речи внутри парадигмы встречаются различные части речи. Если $partOfSpeech == null, часть речи не используется.
			$result = $this->phpmorphy->castFormByGramInfo( $word, $partOfSpeech, $grammems, $returnOnlyWord, $callback, $type);
			return $result;
		}


		public function castFormByPattern($word, $patternWord, $grammemsProvider = null, $returnOnlyWord = false, $callback = null, $type = self::NORMAL) {
		// mixed phpMorphy::castFormByGramInfo($word, $partOfSpeech, $grammems, $returnOnlyWord = false, $callback = null, $type = self::NORMAL)
		//
		// Приводит слово в заданную форму. $partOfSpeech – необходим только для прилагательных и глаголов т.к. только для этих частей речи внутри парадигмы встречаются различные части речи. Если $partOfSpeech == null, часть речи не используется.
// 		var_dump($morphy->castFormByPattern('ДИВАН', 'СТОЛАМИ', null, true));
// /*
// Результат:
// array(1) {
//   [0]=>
//   string(8) "ДИВАНАМИ"
// }
// */
// Сложность возникает, если некоторые граммемы у слов не совпадают. Т.к. данная функция ищет в парадигме слова $word форму у которой граммемы совпадают с граммемами $patternWord, то в таких случаях на выходе получим пустой результат. Например, ДИВАН и КРОВАТЬ имеют разный род (мужской и женский соответственно).

// Нам требуется указать, что род сравнивать не нужно. Можно это сделать следующим способом
//
// $provider = $morphy->getGrammemsProvider();
// $provider->excludeGroups('С', 'род');
// /*
// указываем, что для существительных род сравнивать не будем.
//
// Первым параметром указывается часть речи, для которой требуется внести изменения
// Вторым - группу граммем, которую необходимо исключить, может принимать следующие значения:
// 1)	род
// 2)	одушевленность
// 3)	число
// 4)	падеж
// 5)	залог
// 6)	время
// 7)	повелительная форма
// 8)	лицо
// 9)	сравнительная форма
// 10)	превосходная степень
// 11)	вид
// 12)	переходность
// 13)	безличный глагол
//
// следует помнить, что все данные должны быть в кодировке словаря
// */
// var_dump($morphy->castFormByPattern('ДИВАН', 'КРОВАТЯМИ', $provider, true));
/*
Результат:
array(1) {
  [0]=>
  string(8) "ДИВАНАМИ"
}
*/
/*
Чтобы не передавать $provider каждый раз, можно сделать изменения глобально
*/
// $morphy->getDefaultGrammemsProvider()->excludeGroups('С', 'род');
// var_dump($morphy->castFormByPattern('ДИВАН', 'КРОВАТЯМИ', null, true));

			$result = $this->phpmorphy->castFormByPattern($word, $patternWord, $grammemsProvider, $returnOnlyWord, $callback, $type);
			return $result;
		}


		// mixed phpMorphy::castFormByPattern($word, $patternWord, phpMorphy_GrammemsProvider_Interface $grammemsProvider = null, $returnOnlyWord = false, $callback = null, $type = self::NORMAL)
		//
		// Приводит слово $word в форму в которой стоит слово $patternWord
		//
		// var_dump($morphy->castFormByPattern('ДИВАН', 'СТОЛАМИ', null, true));
		// /*
		// Результат:
		// array(1) {
		//   [0]=>
		//   string(8) "ДИВАНАМИ"
		// }
		// */







		public function getAllForms( $word ) {
		// Возвращает список всех форм (в виде массива) для слова. Если $word отождествляется с формами разных слов, словоформы для каждого слова сливаются в один массив. Это синоним для метода lemmatize
			$Forms = $this->phpmorphy->getAllForms( $word );
			return $Forms;
		}

		public function getPseudoRoot( $word ) {
		// Возвращает общую часть для всех словоформ заданного слова. Общая часть может быть пустой (к примеру, для слова ДЕТИ). Этот метод не возвращает корень слова в привычном его понимании (только longest common substring для всех словоформ). Всегда возвращает строку (не массив!).
			$Forms = $this->phpmorphy->getPseudoRoot( $word );
			return $Forms;
		}

    public function getPartOfSpeech( $word, $profile=false ) {
// Возвращает часть речи для заданного слова. Т.к. словоформа может образовываться от нескольких слов, метод возвращает массив. Возвращаемое значение зависит от опции инициализации graminfo_as_text. Если graminfo_as_text = true часть речи представляется в текстовом виде, иначе в виде значения константы. (подробнее см. выше)
// ТЕСТ образовывается от ТЕСТ и ТЕСТО, однако оба слова являются существительными
// ДУША образовывается от ДУШ, ДУША и ДУШИТЬ
			$partsOfSpeech = $this->phpmorphy->getPartOfSpeech( $word );

			// Профиль по умолчанию //
			if ( !$profile ) {
				$profile = [
					// Служебные части речи //
					'ПРЕДЛ' => 0,
					'СОЮЗ'  => 0,
					'МЕЖД'  => 0,
					'ВВОДН' => 0,
					'ЧАСТ'  => 0,
					'МС'    => 0,

					// Наиболее значимые части речи //
					'С'     => 5,
					'Г'     => 5,
					'П'     => 3,
					'Н'     => 3,

					// Остальные части речи //
					'DEFAULT' => 1
				];
			}

			// Если не удалось определить возможные части речи //
			if ( !$partsOfSpeech ) {
				return $profile[ 'DEFAULT' ];
			}

			// Определение ранга //
			for ( $i = 0; $i < count( $partsOfSpeech ); $i++ ) {
				if ( isset( $profile[ $partsOfSpeech[ $i ] ] ) ) {
					$range[] = $profile[ $partsOfSpeech[ $i ] ];
				} else {
					$range[] = $profile[ 'DEFAULT' ];
				}
			}

			return max( $range );
		}



    /**
    		 * Выполняет поиск слов одного индексного объекта в другом
    		 *
    		 * @param  {object}  target Искомые данные
    		 * @param  {object}  source Данные, в которых выполняется поиск
    		 * @return {integer}        Суммарный ранг на основе найденных данных
    		 */
    		public function search( $target, $index ) {
    			$total_range = 0;

    			// Перебор слов запроса //
    			foreach ( $target->words as $target_word ) {
    				// Перебор слов индекса //
    				foreach ( $index->words as $index_word ) {
    					if ( $index_word->source === $target_word->source ) {
    						$total_range += $index_word->range;
    					} else if ( $index_word->basic && $target_word->basic ) {
    						// Если у искомого и индексированного слов есть леммы //
    						$index_count  = count( $index_word  ->basic );
    						$target_count = count( $target_word ->basic );

    						for ( $i = 0; $i < $target_count; $i++ ) {
    							for ( $j = 0; $j < $index_count; $j++ ) {
    								if ( $index_word->basic[ $j ] === $target_word->basic[ $i ] ) {
    									$total_range += $index_word->range;
    									continue 2;
    								}
    							}
    						}
    					}
    				}
    			}

    			return $total_range;
    		}






	}
?>
