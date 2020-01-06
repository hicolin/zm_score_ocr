<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    private $appId = '18182853';
    private $apiKey = '9hoY0PiA82n46cTnb58LZGh1';
    private $secretKey = 'gSodlBb4xstpFnSMAejzukaMEQBGFGXD';

    public function index()
    {
        return view('index');
    }

    public function ocr(Request $request)
    {
        $file = $request->file('file');
        $realPath = $file->getRealPath();

        if ($file->isValid()) {
            if ($fp = fopen($realPath, 'rb', 0)) {
                $image = fread($fp, filesize($realPath));
                fclose($fp);

                $aipOci = new \AipOcr($this->appId, $this->apiKey, $this->secretKey);
                $res = $aipOci->basicGeneral($image);
                $wordsResult = $res['words_result'];

                if (!isset($wordsResult) || empty($wordsResult)) {
                    return $this->json(100, '请上传正确的图片');
                }
                if (array_search('芝麻分', array_column($wordsResult, 'words')) === false) {
                    return $this->json(100, '请上传正确的图片');
                }

                $zmScore = 0;
                foreach ($wordsResult as $val) {
                    $words = (int)$val['words'];
                    if ($words >= 350 && $words <= 950) {
                        $zmScore = $words;
                    }
                }

                if (!$zmScore) {
                    return $this->json(100, '请上传正确的图片');
                }
                return $this->json(200, $zmScore);
            }
        }
    }

    private function json($code, $msg)
    {
        return json_encode(['code' => $code, 'msg' => $msg]);
    }
}
