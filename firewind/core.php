<?php
	require_once 'morphyus.php';

	class firewind {
		public  $VERSION = "1.0.0";
		private $morphyus;

		public function __construct() {
			$this->morphyus = new morphyus;
		}

		/**
		 * Выполняет индексирование текста
		 *
		 * @param  {string}  content Текст для индексирования
		 * @param  {integer} [range] Коэффициент значимости индексируемых данных
		 * @return {object}          Результат индексирования
		 */
		public function make_index( $content, $range=1 ) {
			$index = new stdClass;
			$index->range = $range;
			$index->words = [];

			// Выделение слов из текста //
			$words = $this->morphyus->get_words( $content );

			foreach ( $words as $word ) {
				// Оценка значимости слова //
				$weight = $this->morphyus->weigh( $word );

				if ( $weight > 0 ) {
					// Количество слов в индексе //
					$length = count( $index->words );

					// Проверка существования исходного слова в индексе //
					for ( $i = 0; $i < $length; $i++ ) {
						if ( $index->words[ $i ]->source === $word ) {
							// Исходное слово уже есть в индексе //
							$index->words[ $i ]->count++;
							$index->words[ $i ]->range =
								$range * $index->words[ $i ]->count * $index->words[ $i ]->weight;

							// Обработка следующего слова //
							continue 2;
						}
					}

					// Если исходного слова еще нет в индексе //
					$lemma = $this->morphyus->lemmatize( $word );

					if ( $lemma ) {
						// Проверка наличия лемм в индексе //
						for ( $i = 0; $i < $length; $i++ ) {
							// Если у сравниваемого слова есть леммы //
							if ( $index->words[ $i ]->basic ) {
								$difference = count(
									array_diff( $lemma, $index->words[ $i ]->basic )
								);

								// Если сравниваемое слово имеет менее двух отличных лемм //
								if ( $difference === 0 ) {
									$index->words[ $i ]->count++;
									$index->words[ $i ]->range =
										$range * $index->words[ $i ]->count * $index->words[ $i ]->weight;

									// Обработка следующего слова //
									continue 2;
								}
							}
						}
					}

					// Если в индексе нет ни лемм, ни исходного слова, //
					// значит пора добавить его //
					$node = new stdClass;
					$node->source = $word;
					$node->count  = 1;
					$node->range  = $range * $weight;
					$node->weight = $weight;
					$node->basic  = $lemma;

					$index->words[] = $node;
				}
			}

			return $index;
		}
	}
?>
