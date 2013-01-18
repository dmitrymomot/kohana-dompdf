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
	 * Настройки класса
	 */
	protected $config;

	/**
	 * Конструктор (#кеп)
	 * Создает экземпляр класса DOMPDF
	 */
	public function __construct($html)
	{
		// Создание экземпляра класса DOMPDF
		$this->dompdf 	= new DOMPDF();

		// Формируется "правильный" html, из которого будет формироваться pdf
		$html = View::factory('html2pdf/base')->set('content', $html)->render();

		// Загрузка html в метод load_html() класса DOMPDF
		$this->dompdf->load_html($html);

		// Загрузка настроек
		$this->config = Kohana::$config->load('html2pdf');
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
		$this->dompdf->render();

		return $this;
	}

	/**
	 * Сохранение PDF на сервере
	 *
	 * @return boolean
	 */
	public function save($path = NULL, $name = NULL)
	{
		$pdf = $this->dompdf->output();

		if($name == NULL)
		{
			$name = time();
		}

		if($path == NULL)
		{
			$path = $this->config->upload_path;
		}

		$filename = $this->get_dir(UTF8::trim($path, '/')) . DIRECTORY_SEPARATOR . UTF8::trim($name, '/') . self::EXT;

		return (file_put_contents($filename, $pdf));
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

	/**
	 * @param string $dir - your/path
	 * @return string
	 */
	private function get_dir($dir)
	{
		if ( ! is_dir($dir) )
		{
			mkdir($dir, 0777, TRUE);
		}

		if ( ! is_writable($dir))
		{
			chmod($dir, 0777);
		}

		return $dir;
	}
}
