<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Подключение библиотеки DOMPDF
 */
require_once( Kohana::find_file('vendor', 'dompdf/dompdf_config.inc') );

/**
 * Class PDF
 */
class Kohana_PDF {

	/**
	 * Расширение файлов pdf
	 */
	const EXT = '.pdf';

	/**
	 * Экземпляр класса DOMPDF
	 *
	 * @var class instance
	 */
	protected $dompdf;

	/**
	 * HTML, из которого будет генерироваться PDF
	 *
	 * @var string
	 */
	protected $html;

	/**
	 * Конструктор (#кеп)
	 * Создает экземпляр класса DOMPDF
	 */
	public function __construct($html)
	{
		// Создание экземпляра класса DOMPDF
		$this->dompdf 	= new DOMPDF();

		// Формируется "правильный" html, из которого будет формироваться pdf
		$this->html = View::factory('html2pdf/base')->set('content', $html)->render();
	}

	/**
	 * Фабрика.
	 * Создание экземпляра текущего класса
	 *
	 * @return class instance
	 */
	public static function factory($html)
	{
		return new PDF($html);
	}

	/**
	 * Генерирование pdf-файла
	 *
	 * @return class instance
	 */
	public function render()
	{
		$this->dompdf->load_html($this->html);
		$this->dompdf->render();

		return $this;
	}

	/**
	 * Сохранение PDF на сервере
	 *
	 * @return boolean
	 */
	public function save($path, $name)
	{

	}

	/**
	 * Отдает файл в браузер
	 *
	 * @return pdf
	 */
	public function load($name = NULL)
	{
		if ($name == NULL)
		{
			$name = time();
		}

		return $this->dompdf->stream($name . self::EXT);
	}

}
