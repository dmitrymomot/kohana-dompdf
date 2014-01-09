<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class PDF
 * @autor Dmitry Momot <dmitry@dimkof.com>
 * @license MIT
 */
class Kohana_PDF {

	/**
	 * PDF files extension
	 */
	const EXT = '.pdf';

	/**
	 * @param string $html
	 * @return this class instance
	 */
	public static function factory($html)
	{
		return new static($html);
	}

	/**
	 * @var class instance of DOMPDF
	 */
	protected $dompdf;

	/**
	 * @var array
	 */
	protected $config;

	/**
	 * @var string
	 */
	protected $_filename;

	/**
	 * @param string $html
	 * @return void
	 */
	public function __construct($html)
	{
		// Load library DOMPDF
		Kohana::load( Kohana::find_file('vendor', 'dompdf/dompdf_config.inc') );

		$this->config = Kohana::$config->load('dompdf')->as_array();
		$this->dompdf = new DOMPDF();
		$this->dompdf->load_html(View::factory('dompdf/base')->set('content', $html)->render());
	}

	/**
	 * @return string filename
	 */
	public function get_filename()
	{
		if ( ! isset($this->_filename) )
		{
			$this->set_filename();
		}

		return $this->_filename;
	}

	/**
	 * @param string $filename
	 * @return $this
	 */
	public function set_filename( $filename = NULL )
	{
		if ($filename != NULL)
		{
			$filename = UTF8::trim(str_replace(self::EXT, '', strtolower($filename)), '/');
			$this->_filename = $filename . self::EXT;
		}
		else
		{
			$this->_filename = time() . self::EXT;
		}

		return $this;
	}

	/**
	 * Renders PDF file
	 *
	 * @return $this
	 */
	public function render()
	{
		$this->dompdf->render();

		return $this;
	}

	/**
	 * Saves on server
	 *
	 * @param string $path
	 * @param string $name
	 * @return boolean|string filepath
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

		$filename = $this->get_dir(UTF8::trim($path, '/')) . DIRECTORY_SEPARATOR . $this->get_filename();

		if(file_put_contents($filename, $pdf))
		{
			return UTF8::trim($path, '/') .'/'. $this->get_filename();
		}

		return FALSE;
	}

	/**
	 * Sends stream to browser
	 *
	 * @param string $name
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
	 * Sets directory for file saving
	 *
	 * @param string $dir
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
