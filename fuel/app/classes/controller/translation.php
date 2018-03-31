<?php

/**
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.8
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2016 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Translation extends Controller_TemplateJournalMaterializeNoneHeader
{
	public function action_index()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# リクエスト配列
			$arr_params = array();

			# プレゼンタ生成
			$presenter = Presenter::forge('translation/index');

			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "日本語->ローマ字変換");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('translation/index');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "日本語->ローマ字変換");
		}
	}

	public function get_ajax()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			\Log::info($_GET['q']);

			// postでおくったらロリポップでは100文字で拒否されたためgetにした。

// 			# リクエストを取得
// 			$handle = fopen('php://input', 'r');
// 			$json_request = fgets($handle);
// 			fclose($handle);
// 			$obj_request = json_decode($json_request);
// 			$request_text = $obj_request->q;
			$request_text = preg_replace('/(@ret@)/i', PHP_EOL, $_GET['q']);
			$request_text = preg_replace('/[\n]/', '‘', $request_text);
			$request_text = mb_convert_kana($request_text, "a", "utf-8");
			$request_text = trim($request_text);

			if (empty($request_text))
			{
				return $this->response(array('text', ''));
			}

			# 漢字 -> ひらがな変換API
			$request_text = $this->ajax_hiraganize($request_text);

			# ひらがな->ローマ字変換API
			$subject = $this->ajax_romanize($request_text);

			$subject = preg_replace('/。/i', '.', $subject);
			$subject = preg_replace('/？/i', '?', $subject);
			$subject = preg_replace('/ー/i', '-', $subject);
			$subject = preg_replace('/<span>((‘)+)<\/span>/i', '<span title="@ret@">@ret@</span>', $subject);
			$subject = preg_replace('/<span>([a-zA-Z0-9!?_\-\^\(\)\.\:\;\[\]]*)<\/span>/i', '<span title="$1">$1</span>', $subject);
			$subject = preg_replace('/<span>(.*)<\/span>/i', '<span title=" ">$1</span>', $subject);
			$tx = "";
			if (preg_match_all('/title="(?P<name>[^"]+)"/i', $subject, $match))
			{
				if ( ! empty($match[1]))
				{
					foreach ($match[1] as $i => $val)
					{
						$tx .= preg_replace('/\/.*/i', '', $val);
					}
				}
			}

			$arr_explode_tx = explode('@ret@', $tx);
			$upper_camel_tx = '';
			foreach ($arr_explode_tx as $i => $val)
			{
				$upper_camel_tx = $upper_camel_tx. ucwords(trim($val)). '@ret@'; // ucfirstだと先頭文字のみアッパーキャメル
			}

			\Log::info($upper_camel_tx);

			return $this->response(array('text' => trim($upper_camel_tx)));
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			\Log::error($e->getCode());
			$arr_response = array(
				'success'  => false,
				'code'     => '9001',
				'response' => $e->getMessage(),
				'result'   => array(),
			);
			return $this->response($arr_response);
		}
	}

	private function ajax_hiraganize($request_text)
	{
		\Log::debug('[start]'. __METHOD__);

		# リクエスト配列
		$arr_params = array(
				'app_id'      => 'eeb32099c804e088d986bda38388dce9ef95c165e201eb6ade13de96437c17bf',
				'request_id'  => 'ppap',
				'sentence'    => $request_text,
				'output_type' => 'hiragana',
		);

		$url = "https://labs.goo.ne.jp/api/hiragana";
		$curl = \Request::forge($url, 'curl');
		$curl->set_method('post');
		$curl->set_header('Content-type', 'application/json; charset=UTF-8');
		$curl->set_params(json_encode($arr_params));
		$curl->set_options(array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_TIMEOUT => 60,
				CURLOPT_CONNECTTIMEOUT => 60,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		));
		$curl->execute();
		$subject = $curl->response();
		$subject = json_decode($subject);
		\Log::debug('hiraganize:'. $subject->converted);

		return $subject->converted;
	}

	private function ajax_romanize($request_text)
	{
		\Log::debug('[start]'. __METHOD__);

		# リクエスト配列
		$arr_params = array(
			'mode' => 'japanese',
			'ie'   => 'UTF-8',
			'q'    => preg_replace('/[\s\,]/i', '　', $request_text),
		);

		$url = "http://www.kawa.net/works/ajax/romanize/romanize.cgi?". http_build_query($arr_params);
		$curl = \Request::forge($url, 'curl');
		$curl->set_method('get');
		$curl->set_header('Content-type', 'application/json; charset=UTF-8');
		$curl->set_options(array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_TIMEOUT => 60,
				CURLOPT_CONNECTTIMEOUT => 60,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		));
		$curl->execute();
		$subject = $curl->response();
		\Log::debug('romanize'. $subject);

		return $subject;
	}
}
